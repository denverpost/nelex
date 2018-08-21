<?php

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

function get_county_from_slug($cslug,$counties) {
    $return_county = false;
    foreach($counties as $county) {
        $county_slug = str_replace(' ', '-', strtolower($county));
        if ($cslug == $county_slug) {
            $return_county = $county;
        }
    }
    return ($return_county === 'Colorado') ? 'All Counties' : $return_county;
}

$iframe = (isset($_GET['iframe']) && $_GET['iframe'] == 'true') ? true : false;
$election_date = (isset($_GET['date']) && ctype_digit($_GET['date'])) ? $_GET['date'] : '20180626';
$election_county = (isset($_GET['county']) && preg_match('/^[A-Za-z-]+$/', $_GET['county'])) ? $_GET['county'] : false;
$election_county_display = (get_county_from_slug($election_county,$counties) && $election_county) ? get_county_from_slug($election_county,$counties) : false;

$base_url = 'https://elections.denverpost.com/';
$base_title = 'Election Results - The Denver Post';
$base_description = 'Colorado election results for national, state, county and city elections from The Denver Post.';
$election_county_for_title = ($election_county && $election_county_display === 'All Counties') ? titleCase($election_county_display) : titleCase($election_county_display) . ' County';
$base_title = ($election_county && $election_county_for_title) ? $election_county_for_title . ' ' . $base_title : $base_title;
$display_title = ($election_county && $election_county_display) ? str_replace(' - The Denver Post', '', $base_title) : $base_title;


// BUILD A LIST OF ELECTIONS and ELECTION DATA SOURCES
$directories = array();
if ($results = scandir('./' . $result_dir)) {
    foreach ($results as $result) {
        if ($result === '.' || $result === '..' || $result === 'index.php') continue;

        if (is_dir('./' . $result_dir . '/' . $result)) {
            $directories[] = $result;
        }
    }
}

// VIEW
// This determines the available elections
sort($directories, SORT_NATURAL);
$directories = array_reverse($directories);
$elections_available = array();
foreach($directories as $dir) {
    if ($handle = scandir('./' . $result_dir . '/'.$dir.'/')) {
        foreach ($handle as $file) {
            $filepath = pathinfo($file);
            if ($file === '.' || $file === '..' || $file === 'index.php' || $filepath['extension'] !== 'json') {
                continue;
			}
			$elections_available[$dir][] = $filepath['filename'];
        }
    }
}
foreach($directories as $dir) {
    sort($elections_available[$dir], SORT_NATURAL);
}

// VIEW
// This determines the data in the view we're looking at
$datafile_address = false;
if ($election_date) {
    $datafile_address = './' . $result_dir . '/'.$election_date.'/';
    if ($election_county) {
        $datafile_address .= $election_county.'.json';
    } else {
        if (file_exists('./' . $result_dir . '/'.$election_date.'/default.json')) {
            $datafile_address .= 'default.json';
        } else {
            $datafile_address .= 'colorado.json';
        }
    }
}

$elex_available = json_encode($elections_available);
