<?php

/**
 * RIDICULOUSTLY SIMPLE
 *
 * Just goes through all the election results URLs/counties listed in
 * urls.csv and pulls fresh results. Along the way, it looks for races BY NAME
 * that should be added to default.json, which is what the homepage of
 * the front-end site displays (otherwise it'll be blank) so make sure
 * to find a couple important races and add their EXACT NAMES below.
 *
 * This is also what allows the homepage itself to be easily iframed into
 * denverpost.com as a "top races" style results widget, i.e. on the homepage.
 */

require_once './functions.php';

$data_path = '../results/';

// Used each time through the urls.csv loop to check for a default.json match
function makeDefault($data,$data_date) {
  $default_output = array();
    $ct=0;
    // the EXACT names of the races to add into default.json
    $big_races = array(
      'governor - democratic party',
      'governor - republican party'
    );
    // Get only the data we actually care about
    foreach ($data as $result) {
      if (in_array(strtolower($result['C']), $big_races)) {
        echo "\n".'Found races to write DEFAULT results!'."\n\n";
        $default_output[$ct]['race_name'] = titleCase($result['C']);
        foreach ($result['CH'] as $key => $value) {
          $default_output[$ct]['results'][$key]['choice_name'] = $value;
        }
        foreach ($result['V'] as $key => $value) {
          $default_output[$ct]['results'][$key]['choice_votes'] = $value;
        }
        foreach ($result['PCT'] as $key => $value) {
          $default_output[$ct]['results'][$key]['choice_vote_percent'] = round($value,2);
        }
      }
      $ct++;
    }
    // add it to the array that will be saved as default.json
    $default_string = json_encode($default_output);

    //get to the right place in the results dir and save
    $elex_dir = '../results/'.$data_date.'/';
    if (!file_exists($elex_dir)) {
      mkdir($elex_dir, 0755, true);
    }
    $default_filename = $elex_dir.'default.json';
    if (file_exists($default_filename)) {
      // make a backup, just in case
      echo 'Backing up prior DEFAULT results!'."\n";
      rename($default_filename,$default_filename.'.old');
    }
    echo 'Writing new DEFAULT results!'."\n\n";
    file_put_contents($default_filename,$default_string);
    chmod($default_filename, 0755);
}

// Open up urls.csv
$handle = fopen('urls.csv', 'r');
$urls = array();
if ($handle) {
  echo "\n".'Found stored results to scrape!'."\n\n";
    $ct=0;
    while (($line = fgets($handle)) !== false) {
        // convert the lines into a multidimensional array
        $line_vars = explode(',', $line);
        $urls[$ct]['date'] = $line_vars[0];
        $urls[$ct]['county'] = $line_vars[1];
        $urls[$ct]['base_url'] = trim($line_vars[2]);
        $ct++;
    }
    fclose($handle);
}

// iterate through the array of URLs
foreach($urls as $url) {

  // Get the current version info
  $version_url = $url['base_url'].'current_ver.txt';
  echo 'Found results for '.ucfirst($url['county']).' County on '.$url['date'].'!'."\n";
  // Use it to fetch the latest version of the JSON results for that county-electiondate pair
  if ($current_version = file_get_contents($version_url)) {
    $json_url = $url['base_url'].$current_version.'/json/en/summary.json';
    $json = file_get_contents($json_url);
    // Make the JSON an array
    $results_input = json_decode($json, true);
    // check if this race is one for default.json
    makeDefault($results_input,$url['date']);
    $results_output = array();
    $ct=0;
    // Get only the data we actually care about
    foreach ($results_input as $result) {
      $results_output[$ct]['race_name'] = titleCase($result['C']);
      foreach ($result['CH'] as $key => $value) {
        $results_output[$ct]['results'][$key]['choice_name'] = $value;
      }
      foreach ($result['V'] as $key => $value) {
        $results_output[$ct]['results'][$key]['choice_votes'] = $value;
      }
      foreach ($result['PCT'] as $key => $value) {
        $results_output[$ct]['results'][$key]['choice_vote_percent'] = round($value,2);
      }
      $ct++;
    }
    // add it to the array that will be saved as {COUNTY}.json
    $results_string = json_encode($results_output);

    //get to the right place in the results dir and save
    $elex_dir = $data_path.$url['date'].'/';
    if (!file_exists($elex_dir)) {
      mkdir($elex_dir, 0755, true);
    }
    $filename = $elex_dir.$url['county'].'.json';
    if (file_exists($filename)) {
      // make a backup, just in case
      echo 'Backing up prior results!'."\n";
      rename($filename,$filename.'.old');
    }
    echo 'Writing new results!'."\n\n";
    file_put_contents($filename,$results_string);
    chmod($filename, 0755);
  }
}