<?php

require_once './functions.php';

$data_path = '../results/';

function makeDefault($data,$data_date) {
  $default_output = array();
    $ct=0;
    $big_races = array(
      'governor - democratic party',
      'governor - republican party'
    );
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
    $default_string = json_encode($default_output);
    $elex_dir = '../results/'.$data_date.'/';
    if (!file_exists($elex_dir)) {
      mkdir($elex_dir, 0755, true);
    }
    $default_filename = $elex_dir.'default.json';
    if (file_exists($default_filename)) {
      echo 'Backing up prior DEFAULT results!'."\n";
      rename($default_filename,$default_filename.'.old');
    }
    echo 'Writing new DEFAULT results!'."\n\n";
    file_put_contents($default_filename,$default_string);
    chmod($default_filename, 0755);
}

$handle = fopen('urls.csv', 'r');
$urls = array();
if ($handle) {
  echo "\n".'Found stored results to scrape!'."\n\n";
    $ct=0;
    while (($line = fgets($handle)) !== false) {
        $line_vars = explode(',', $line);
        $urls[$ct]['date'] = $line_vars[0];
        $urls[$ct]['county'] = $line_vars[1];
        $urls[$ct]['base_url'] = trim($line_vars[2]);
        $ct++;
    }
    fclose($handle);
}

foreach($urls as $url) {

  $version_url = $url['base_url'].'current_ver.txt';
  echo 'Found results for '.ucfirst($url['county']).' County on '.$url['date'].'!'."\n";
  if ($current_version = file_get_contents($version_url)) {
    $json_url = $url['base_url'].$current_version.'/json/en/summary.json';

    $json = file_get_contents($json_url);
    $results_input = json_decode($json, true);
    if ($url['county'] == 'colorado') {
      makeDefault($results_input,$url['date']);
    }
    $results_output = array();
    $ct=0;
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
    $results_string = json_encode($results_output);
    $elex_dir = $data_path.$url['date'].'/';
    if (!file_exists($elex_dir)) {
      mkdir($elex_dir, 0755, true);
    }
    $filename = $elex_dir.$url['county'].'.json';
    if (file_exists($filename)) {
      echo 'Backing up prior results!'."\n";
      rename($filename,$filename.'.old');
    }
    echo 'Writing new results!'."\n\n";
    file_put_contents($filename,$results_string);
    chmod($filename, 0755);
  }
}