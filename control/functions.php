<?php

/**
 * 
 * VIRTUALLY NOTHING IN HERE IS ACTUALLY USED AT THIS POINT
 *
 * THE IDEA WAS to have a place to handle all the operations that would be
 * required using an SQLite DB to store various ELECTION objects with dates
 * and the associated county results URLs for each. These functions do most
 * of the same types of operations that would be required for nelex, just
 * need to be adjust from their former life as part of the holiday-lights app.
 *
 * PLANS FOR THE FUTURE: Finish off the DB interactions and set up a script
 * that can be running in cron all the time, checking for results in all
 * associated counties when there's an election object set as "live," and
 * doing nothing if not. That would make back-end control as simple as setting
 * up the election as zeroed results pages appear on Clarity. The basic form page
 * template is in election_edit.php. index.php only redirects to convert.php for now.
 * 
 */


date_default_timezone_set('America/Denver');

if (!function_exists('get_config')) {
    function get_config() {
        return json_decode(file_get_contents('config.json'),true);
    }
}

/**
 * Remove a sub-string, only if it as the end of the parent string
 */
if (!function_exists('remove_if_trailing')) {
    function remove_if_trailing($haystack, $needle) {
        $needle_position = strlen($needle) * -1;
        if (substr($haystack, $needle_position) == $needle) {
             $haystack = substr($haystack, 0, $needle_position);
        }
        return $haystack;
    }
}

if (!function_exists('titleCase')) {
    function titleCase($string) {
        $smallwordsarray = array('of','a','the','and','an','or','nor','but','is','if','then','else','when','at','from','by','on','off','for','in','out','over','to','into','with');
        $words = explode(' ', $string);
        foreach ($words as $key => $word) {
            if (!$key or !in_array($word, $smallwordsarray))
            $words[$key] = ucwords($word);
        }
        $newtitle = implode(' ', $words);
        return $newtitle;
    }
}

if (!function_exists('get_url')) {
    // get contents of a URL
    function get_url($url) {
        $url=str_replace('&amp;','&',$url);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}

// Good for pushing stuff to Extras -- might need tweaking
if (!function_exists('do_ftp')) {
    function do_ftp($files,$dir,$config) {
        $success = false;
        $connection = ftp_connect($config['ftp_server']);
        $login = ftp_login($connection,$config['ftp_user'],$config['ftp_pass']);
        if ($login) {
            ftp_pasv($connection, TRUE);
            $chg_dir = ftp_chdir($connection,$config['ftp_path'].$dir);
            if (!$chg_dir) {
                ftp_mkdir($connection,$config['ftp_path'].$dir);
                ftp_chdir($connection,$config['ftp_path'].$dir);
            }
            $remotefiles = ftp_nlist($connection,'.');
            foreach($files as $file) {
                $local_file = './nelex/'.$file;
                $local_exists = file_exists($local_file);
                $remote_exists = in_array($file, $remotefiles);
                if (!$remote_exists && $local_exists) {
                    $put = ftp_put($connection, $file, $local_file, FTP_BINARY);
                    if (!$put) {
                        $error = 'FTP of '.$file.' to '.$config['ftp_path'].$dir.' failed.';
                    }
                }
            }
            ftp_close($connection);
            if (isset($error)) {
                return array(false,$error);
            } else {
                return array(true,'Success!');
            }
        } else {
            return array(false,'Could not log in to FTP server.');
        }
    }
}

if (!function_exists('sort_lights')) {
    function sort_lights($item1,$item2) {
        if ($item1['status'] == $item2['status']) return 0;
        return ($item1['status'] < $item2['status']) ? -1 : 1;
    }
}

if (!function_exists('sort_json')) {
    function sort_json($item1,$item2) {
        if ($item1['winner'] == $item2['winner']) return 0;
        return ($item1['winner'] > $item2['winner']) ? -1 : 1;
    }
}

if (!function_exists('count_queued')) {
    function count_queued() {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT COUNT(*) FROM lights WHERE status = 1") or die('Could not read from database.');
        $result = $return->fetchArray();
        $count = $result[0];
        $db->close();
        return $count;
    }
}


if (!function_exists('change_status')) {
    function change_status($id,$newstatus) {
        if (!check_coords($id)) {
            $newstatus = '2';
        }
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $update = "UPDATE lights SET status = $newstatus WHERE id = $id";
        $db->exec($update) or die('Could not write to database');
        $db->close();
        return true;
    }
}

// Make into GET_ELECTIONS ??
if (!function_exists('get_years')) {
    // Returns an array of years that have results in the database
    function get_years() {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT DISTINCT year FROM lights") or die('Could not read from database.');
        $row = array();
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[] = $result['year'];
        }
        $db->close();
        arsort($row);
        return $row;
    }
}

// Way to get info from DB and use it to decide on files to push to extras
if (!function_exists('push_images')) {
    function push_images($id,$config) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');

        $return = $db->query("SELECT filename,fileyear FROM lights WHERE id = $id ") or die('Could not read from database.');
        $row = array();
        $i=0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[$i]['file'] = $result['filename'];
            $row[$i]['fileyear'] = $result['fileyear'];
            $i++;
        }
        foreach ($row as $item) {
            $ftp_result = do_ftp(array($item['file'].'-full.jpg',$item['file'].'-med.jpg',$item['file'].'-thumb.jpg'),$item['fileyear'],$config);
        }
        $db->close();
    }
}

// Duplicate checker 
if (!function_exists('check_exists')) {
    // Checks if a submission with the same lights_year and email address already exists
    function check_exists($new) {
        $email = $new['email'];
        $year = $new['year'];
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT EXISTS(SELECT 1 FROM lights WHERE email = '$email' AND year = '$year' LIMIT 1)") or die('Could not read from database.');
        $result = $return->fetchArray();
        $db->close();
        if ($result[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('check_id')) {
    // Checks if a submission with the same lights_year and email address already exists
    function check_id($id) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT EXISTS(SELECT 1 FROM lights WHERE id = $id LIMIT 1)") or die('Could not read from database.');
        $result = $return->fetchArray();
        $db->close();
        if ($result[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('update_search')) {
    function update_search() {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $insert = "INSERT INTO lightsearch SELECT id, title, street, city, zip, email, name, desc FROM lights";
        $db->exec($insert) or die('Could not write to database');
        $db->close();
        return true;
    }
}

// GET_ELECTIONS ??
if (!function_exists('get_json_lights')) {
    // Gets all the existing lights in the database and returns them in an array
    function get_json_lights($year) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT * FROM lights WHERE year = '$year' AND status = 4") or die('Could not read from database.');
        $row = array();
        $i = 0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            if (check_coords($result['id'])) {
                $image_urls = build_image_urls($result['filename'],$result['fileyear']);
                $row[$i]['id'] = (string)$result['id'];
                $row[$i]['title'] = $result['title'];
                $row[$i]['desc'] = $result['desc'];
                $row[$i]['street'] = $result['street'];
                $row[$i]['city'] = $result['city'];
                $row[$i]['zip'] = $result['zip'];
                $row[$i]['lat'] = $result['lat'];
                $row[$i]['lon'] = $result['lon'];
                $row[$i]['year'] = $result['year'];
                $row[$i]['winner'] = $result['winner'];
                $row[$i]['imgfull'] = $image_urls[0];
                $row[$i]['imgmed'] = $image_urls[1];
                $row[$i]['imgthumb'] = $image_urls[2];
            }
            $i++;
        }
        usort($row,'sort_json');
        $db->close();
        return $row;
    }
}

// GET_SINGLE_ELECTION ??
if (!function_exists('get_single')) {
    // Gets a single lights entry from the database and returns it in an array
    function get_single($id) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $return = $db->query("SELECT * FROM lights WHERE id = '$id'") or die('Could not read from database.');
        $row = array();
        $i = 0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[$i]['id'] = $result['id'];
            $row[$i]['title'] = $result['title'];
            $row[$i]['desc'] = $result['desc'];
            $row[$i]['street'] = $result['street'];
            $row[$i]['city'] = $result['city'];
            $row[$i]['zip'] = $result['zip'];
            $row[$i]['lat'] = $result['lat'];
            $row[$i]['lon'] = $result['lon'];
            $row[$i]['name'] = $result['name'];
            $row[$i]['email'] = $result['email'];
            $row[$i]['phone'] = $result['phone'];
            $row[$i]['status'] = $result['status'];
            $row[$i]['winner'] = $result['winner'];
            $row[$i]['year'] = $result['year'];
            $row[$i]['fileyear'] = $result['fileyear'];
            $row[$i]['submitted'] = $result['submitted'];
            $row[$i]['filename'] = $result['filename'];
            $i++;
        }
        $db->close();
        if (isset($row[0])) {
            return $row[0];
        } else {
            return false;
        }
    }
}

if (!function_exists('search_lights')) {
    // Gets all the existing lights in the database and returns them in an array
    function search_lights($searchstring) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        // $searchquery = "SELECT id FROM lightsearch WHERE lightsearch MATCH $searchstring";
        $searchquery = "SELECT id,title,street,city,zip,status,winner,email,filename,year,fileyear FROM lights WHERE id IN ( SELECT id FROM lightsearch WHERE lightsearch MATCH '$searchstring' )";
        $return = $db->query($searchquery) or die('Could not read from database.');
        $row = array();
        $i = 0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[$i]['id'] = $result['id'];
            $row[$i]['title'] = $result['title'];
            $row[$i]['street'] = $result['street'];
            $row[$i]['city'] = $result['city'];
            $row[$i]['zip'] = $result['zip'];
            $row[$i]['status'] = $result['status'];
            $row[$i]['winner'] = $result['winner'];
            $row[$i]['email'] = $result['email'];
            $row[$i]['filename'] = $result['filename'];
            $row[$i]['year'] = $result['year'];
            $row[$i]['fileyear'] = $result['fileyear'];
            $i++;
        }
        $db->close();
        return $row;
    }
}

if (!function_exists('list_lights')) {
    // Gets all the existing lights in the database and returns them in an array
    function list_lights($year) {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $querytext = ($year=='0') ? "SELECT id,title,street,city,zip,status,winner,email,filename,year,fileyear FROM lights" : "SELECT id,title,street,city,zip,status,winner,email,filename,year,fileyear FROM lights WHERE year = $year";
        $return = $db->query($querytext) or die('Could not read from database.');
        $row = array();
        $i = 0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[$i]['id'] = $result['id'];
            $row[$i]['title'] = $result['title'];
            $row[$i]['street'] = $result['street'];
            $row[$i]['city'] = $result['city'];
            $row[$i]['zip'] = $result['zip'];
            $row[$i]['status'] = $result['status'];
            $row[$i]['winner'] = $result['winner'];
            $row[$i]['email'] = $result['email'];
            $row[$i]['filename'] = $result['filename'];
            $row[$i]['year'] = $result['year'];
            $row[$i]['fileyear'] = $result['fileyear'];
            $i++;
        }
        usort($row,'sort_lights');
        $db->close();
        return $row;
    }
}

if (!function_exists('new_from_json')) {
    function new_from_json($filename,$year,$config) {
        $incoming = json_decode(file_get_contents($filename),true);
        foreach($incoming as $new) {
            $title = SQLite3::escapeString($new['title']);
            $desc = SQLite3::escapeString(htmlentities($new['desc']));
            $street = $new['street'];
            $city = $new['city'];
            $zip = $new['zip'];
            $lat = $new['lat'];
            $lon = $new['lon'];
            $name = $new['name'];
            $email = $new['email'];
            $phone = $new['phone'];
            $status = '1';
            $winner = '0';
            $year = $year;
            $submitted = time();
            $filename = $new['filename'];
            echo 'Trying to insert item with title '.$title.'.'."\n"."\n";
            $db = new SQLite3('lights.db') or die ('Could not open database.');
            $insert = "INSERT INTO lights(title,desc,street,city,zip,lat,lon,name,email,phone,status,winner,year,fileyear,submitted,filename) VALUES('$title','$desc','$street','$city','$zip','$lat','$lon','$name','$email','$phone','$status','$winner','$year','$year','$submitted','$filename')";
            $db->exec($insert) or die('Could not write to database');
            $db->close();
            update_search();
        }
    }
}

if (!function_exists('insert_new')) {
    // inserts a new submission into the database
    function insert_new($args) {
        if (!isset($args['title'],$args['desc'],$args['street'],$args['city'],$args['zip'],$args['lat'],$args['lon'],$args['name'],$args['email'],$args['phone'],$args['year'],$args['submitted'],$args['filename'])) {
            return array(false,'Incomplete submission.');
        } else {
            $filehash = cache_submitted_image($args['filename']);
            if (!check_exists($args) && $filehash) {
                extract($args);
                $title = SQLite3::escapeString($title);
                $desc = SQLite3::escapeString($desc);
                $db = new SQLite3('lights.db') or die ('Could not open database.');
                $insert = "INSERT INTO lights(title,desc,street,city,zip,lat,lon,name,email,phone,status,winner,year,fileyear,submitted,filename) VALUES('".$title."','".$desc."','".$street."','".$city."','".$zip."','".$lat."','".$lon."','".$name."','".$email."','".$phone."','".$status."','".$winner."','".$year."','".$year."','".$submitted."','".$filehash."')";
                $db->exec($insert) or die('Could not write to database');
                $db->close();
                update_search();
                return array(true,'Added item.');
            } else {
                return array(false,'Duplicate submission.');
            }
        }
    }
}

if (!function_exists('update_lights')) {
    // inserts a new submission into the database
    function update_lights($args,$id) {
        if (!isset($args['title'],$args['desc'],$args['street'],$args['city'],$args['zip'],$args['lat'],$args['lon'],$args['name'],$args['email'],$args['phone'],$args['year'],$args['status'],$args['winner'])) {
            return array(false,'Incomplete submission.');
        } else {
            $msg = 'Entry updated.';
            if ($args['lat']=='-1' || $args['lon']=='-1') {
                $args['status'] = '2';
                $msg = 'Entry updated, but failed to geocode.';
            }
            if (check_id($id)) {
                extract($args);
                $title = SQLite3::escapeString($title);
                $desc = SQLite3::escapeString($desc);
                $db = new SQLite3('lights.db') or die ('Could not open database.');
                $update = "UPDATE lights SET title='$title', desc='$desc', street='$street', city='$city', zip='$zip', lat='$lat', lon='$lon', name='$name', email='$email', phone='$phone', status='$status', winner='$winner', year='$year' WHERE id = $id";
                $db->exec($update) or die('Could not write to database');
                $db->close();
                build_json($year);
                update_search();
                return array(true,'Entry updated.');
            } else {
                return array(false,'Display not found.');
            }
        }
    }
}

if (!function_exists('get_email_report')) {
    // Gets all the existing lights in the database and returns them in an array
    function get_email_report() {
        $db = new SQLite3('lights.db') or die ('Could not open database.');
        $querytext = "SELECT DISTINCT email FROM lights";
        $return = $db->query($querytext) or die('Could not read from database.');
        $row = array();
        $i = 0;
        while($result = $return->fetchArray(SQLITE3_ASSOC)) {
            $row[$i]['email'] = $result['email'];
            $i++;
        }
        $db->close();
        return $row;
    }
}

if (!function_exists('delete_lights')) {
    function delete_lights($id) {
        if (!isset($id)) {
            return array(false,'Invalid ID.');
        } else {
            $db = new SQLite3('lights.db') or die ('Could not open database.');
            $delete = "DELETE FROM lights WHERE id = $id";
            $db->exec($delete) or die('Could not write to database');
            $db->close();
            update_search();
            return array(true,$id);
        }
    }
}

if (!function_exists('build_json')) {
    function build_json($year) {
        // Builts the local copy of a given lights year's json file
        $config = get_config();
        $lightsarray = get_json_lights($year);
        $jsonroot = './json/';
        $filename = 'holidaylights-'.$year.'.json';
        if (count($lightsarray) >= 1) {
            if (file_exists($jsonroot.$filename)) {
                if (file_exists($jsonroot.$filename.'.bak')) {
                    unlink($jsonroot.$filename.'.bak');
                }
                rename($jsonroot.$filename,$jsonroot.$filename.'.bak');
            }
            file_put_contents($jsonroot.$filename, json_encode($lightsarray)) or die("Could not append harvested IDs!");
            ftp_json($filename,$config);
        }
    }
}


if (!function_exists('check_dupes')) {
    function check_dupes($lightsarray) {
        //check for possible duplicate entries -- if (same year and same email) OR (same year and same IP), etc.
        $emails = array();
        $duplicates = array();
        foreach ($lightsarray as $lights) {
            if (!in_array($lights['email'].$lights['year'], $emails)) {
                $emails[] = $lights['email'].$lights['year'];
            } else {
                $duplicates[] = $lights['email'];
            }
        }
        return $duplicates;
    }
}

?>