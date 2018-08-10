<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Just used for testing to output arrays more attractively
function pretty_dump($var) {
  echo '<pre>' . var_export($var, true) . '</pre>';
}

// full of helper functions
require_once './functions.php';
require_once '../constants.php';

// where data files go
$data_path = '../results/';

// instantiations
$json = $results_string = false;

// If we've submitted the form
if (isset($_POST['election_date']) && isset($_POST['data_url']) && isset($_POST['election_county'])) {
  // get the POSt data
  $county = (isset($_POST['election_date'])) ? $_POST['election_county'] : false;
  $date = (isset($_POST['election_date'])) ? date('Ymd', strtotime($_POST['election_date'])) : false;
  $url = (isset($_POST['data_url']) && filter_var($_POST['data_url'], FILTER_VALIDATE_URL) ) ? $_POST['data_url'] : false;

  // These try to drop pieces of various types of Clarity results page URLs' to arrive at the root for that county, that date
  $url = remove_if_trailing($_POST['data_url'],'#/');
  $url = preg_replace('/Web02\.[0-9]{6}/', 'Web02', $url);
  $url = preg_replace('/Web02-state\.[0-9]{6}/', 'Web02-state', $url);
  $base_url = remove_if_trailing($url,'Web02/');
  $base_url = remove_if_trailing($base_url,'Web02-state/');

  // The file where we store the list of things to check up on **HC** Empty it out before each election
  $handle = fopen('urls.csv', 'r');
  $url_exists = false;
  // Make sure the URLs file doesn't already have this in it
  if ($handle) {
      while (($line = fgets($handle)) !== false) {
          if ($date.','.$county.','.$base_url == trim($line)) {
            $url_exists = true;
            break;
          }
      }
      fclose($handle);
      if (!$url_exists) {
        // if it's not there, add it quick
        file_put_contents('urls.csv', $date.','.$county.','.$base_url."\n", FILE_APPEND);
      }
  }

  // Get the mysterious version number, which Clarity stores in a text file in each couty-electiondate directory
  $version_url = $base_url.'current_ver.txt';
  $current_version = file_get_contents($version_url);
  // use the version to get the most-current results file
  $json_url = $base_url.$current_version.'/json/en/summary.json';
  $json = file_get_contents($json_url);
  // convert the json to an array
  $results_input = json_decode($json, true);
  $results_output = array();
  $ct=0;
  // Go through the array and start lifting out just the bits we want to keep for display
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
  // Encode the array back to JSON
  $results_string = json_encode($results_output);
  // Get into the results dir and create children based on the election date (if they aren't there)
  $elex_dir = $data_path.$date.'/';
  if (!file_exists($elex_dir)) {
    mkdir($elex_dir, 0755, true);
  }
  // then write each county file into them (county 'colorado' is state-wide/multi-geography results; 'default' is the races selected for display on the hompage)
  $filename = $elex_dir.$county.'.json';
  if (file_exists($filename)) {
    rename($filename,$filename.'.old');
  }
  // save it
  file_put_contents($filename,$results_string);
  chmod($filename, 0755);
}

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Convert Election Data - Denver Post nelex</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="language" content="en, sv" />
    <meta name="Copyright" content="Copyright &copy; The Denver Post." />

    <!-- STYLE SHEETS -->
    <link rel="stylesheet" href="./css/foundation.min.css" />
    <link rel="stylesheet" href="./css/normalize.min.css" />
    <link rel="stylesheet" href="./css/style.css" />

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />

    <link href='https://fonts.googleapis.com/css?family=Noticia+Text:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>

    <!-- SCRIPTS -->
    <script src="./js/jquery.min.js"></script>
    <script src="./js/modernizr.min.js"></script>
  </head>
  <body>
  	

    <section id="header">
      <!-- NAVIGATION BAR -->
      <div id="top-bar-margin" class="sticky">
        <nav class="top-bar" data-topbar role="navigation">
          <ul class="title-area">
            <li class="name">
              <a href="./"><img src="./nelex-logo.png" alt="nelex logo" class="nav-logo"></a>
            </li>
          </ul></nav>
      </div> <!-- Closes top-bar-margin -->

    <!-- SOCIAL MEDIA BUTTONS -->
    <div id="footer-whole">
      <div class="row">
        <div class="large-9 medium-9 small-12 columns">
          <h1>Election Administrator</h1>
        </div>
    </div>
  </section>

    <div class="row">
      <div class="Large-8 large-centered medium-10 medium-centered columns">
        <h2>Convert Election Data</h2>
          <p>Paste the URL of a county or state results page and the results will be scraped into a data file. You must choose an election date and a county (choose "Colorado" for statewide results) to associate these data with.</p>
          <p style="font-style:italic;font-weight:bold;color:darkred;">NOTE: Only URLs with <code>/Web02/</code> (including versioned formats like <code>/Web02.012345/</code>), with or without <code>#/</code>at the end, will work!</p>
      </div>
    </div>

    <section id="submit-form">
      <p id="form-messages" class="alert-box success radius text-center" style="display:none;"></p>
      <form id="ajax-submit" method="post" enctype="multipart/form-data">
        <div id="theforms" class="row">
          <div class="Large-8 large-centered medium-10 medium-centered columns">
            <fieldset>
              <legend>&nbsp;Data Location&nbsp;</legend>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="data_url">Election Data URL</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <input type="test" style="width:100%;" name="data_url" id="data_url" placeholder="http://results.enr.clarityelections.com/..." required />
                </div>
              </div>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="election_county">Election County</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <select name="election_county" id="election_county" required>
                    <?php foreach ($counties as $county) { ?>
                      <option value="<?php echo str_replace(' ', '-', strtolower($county)); ?>"><?php echo $county; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="election_date">Election Date</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <select name="election_date" id="election_date" required>
                    <option value="20181106">2018-11-06</option><!-- ADD NEW ELEX DATA **HC** -->
                    <option value="20180626">2018-06-26</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="large-12 columns text-center">
                  <input type="submit" tabindex="4" class="button large-12 columns" style="margin-top:2em;padding:1em 2em" value="CONVERT DATA" />
                </div>
              </div>
            </fieldset><?php if ($results_string) { ?>
            <fieldset>
              <legend>&nbsp;Outputting the following json:&nbsp;</legend>
              <div class="row">
                <div class="large-12 columns text-center">
                  <textarea style="width:100%;height:18em;" readonly><?php echo $results_string; ?></textarea>
                </div>
              </div>
            </fieldset><?php } ?>
          </div>
        </div>
    </form>
    </section>

    <div class="row">
      <div class="large-10 large-centered medium-10 medium-centered columns">
      <p class="text-center" style="margin:2em 0;"><em>Questions or problems? Email <a href="mailto:dpo@denverpost.com?subject=NELEX%20PROBLEM%20add%20new%20election">dpo@denverpost.com</a>.</em></p>
      </div>
    </div>

    <hr>

    <!-- FOOTER SOCIAL MEDIA BUTTONS -->
    <div id="footer-whole">
      <div class="row">
        <div class="large-12 medium-12 columns">
          <p class="left">Copyright &copy; 2018 The Denver Post</p>
        </div>
      </div>
    </div>

    <script src="./js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
    </script>
  </body>
</html>
