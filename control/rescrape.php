<?php

$data_path = '../results/';

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
    $results_output = array();
    $ct=0;
    foreach ($results_input as $result) {
      $results_output[$ct]['race_name'] = $result['C'];
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