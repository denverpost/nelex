<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

function pretty_dump($var) {
  echo '<pre>' . var_export($var, true) . '</pre>';
}

require_once './functions.php';
$json = false;
if (isset($_POST['election_date']) && isset($_POST['data_url'])) {
  $date = (isset($_POST['election_date'])) ? date('Ymd', strtotime($_POST['election_date'])) : false;
  $url = (isset($_POST['data_url']) && filter_var($_POST['data_url'], FILTER_VALIDATE_URL) ) ? $_POST['data_url'] : false;
  $url = remove_if_trailing($_POST['data_url'],'#/');
  $base_url = remove_if_trailing($url,'Web02/');
  $version_url = $base_url.'current_ver.txt';
  $current_version = file_get_contents($version_url);
  $json_url = $base_url.$current_version.'/json/en/summary.json';
  $json = file_get_contents($json_url);
  $results_input = json_decode($json, true);
  $results_output = array();
  $ct=0;
  foreach ($results_input as $result) {
    $results_output[$ct]['race_name'] = $result['C'];
    foreach ($result['CH'] as $choice) {
      $results_output[$ct]['race_choices'][] = $choice;
    }
    foreach ($result['V'] as $vote) {
      $results_output[$ct]['race_votes'][] = $vote;
    }
    foreach ($result['PCT'] as $percent) {
      $results_output[$ct]['race_vote_percent'][] = round($percent,2);
    }
    $ct++;
  }
  pretty_dump($date);
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

    <link rel="shortcut icon" href="//plus.denverpost.com/favicon.ico" type="image/x-icon" />

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
          <p>Paste the URL of a county or state results page and the results will be scraped into a data file. You must choose an election data to associate the data with.</p>
          <p style="font-style:italic;font-weight:bold;color:darkred;">NOTE: Only URLs with <code>/Web02/</code> or <code>/Web02/#/</code> will work!</p>
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
                  <label for="desc">Election Date</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <select name="election_date" id="election_date" required>
                    <option value="20180626">2018-06-26</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="large-12 columns text-center">
                  <input type="submit" tabindex="4" class="button large-12 columns" style="margin-top:2em;padding:1em 2em" value="CONVERT DATA" />
                </div>
              </div>
            </fieldset><?php if ($json) { ?>
            <fieldset>
              <legend>&nbsp;Found the following json:&nbsp;</legend>
              <div class="row">
                <div class="large-12 columns text-center">
                  <textarea style="width:100%;height:24em;" readonly><?php echo $json; ?></textarea>
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
