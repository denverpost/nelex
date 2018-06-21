<?php

require_once './functions.php';

if (isset($_POST['election_date'])) {
  $election['date'] = (isset($_POST['election_date'])) ? strtotime($_POST['election_date']) : false;
  $election['type'] = (in_array($_POST['election_type'], array('G','P','S','R'))) ? $_POST['election_type'] : false;
  $election['live'] = (isset($_POST['election_live']) && $_POST['election_live'] == 'on') ? true : false;
  var_dump($election);
  die;
  //header("Refresh:0");
}


?>
<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Election - Denver Post nelex</title>
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
        <h2>Add/Edit Election</h2>
          <p>Every election must have a date and a type (general, primary, etc.). You can also choose at this time to make the election "live," which means results will be actively updated ass you add places to track.</p>
      </div>
    </div>

    <section id="submit-form">
      <p id="form-messages" class="alert-box success radius text-center" style="display:none;"></p>
      <form id="ajax-submit" method="post" enctype="multipart/form-data">
        <div id="theforms" class="row">
          <div class="Large-8 large-centered medium-10 medium-centered columns">
            <fieldset>
              <legend>Settings</legend>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="title">Election Type</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <select tabindex="1" name="election_type" id="election_type" required>
                    <option value="G" selected>General</option>
                    <option value="P">Primary</option>
                    <option value="S">Special</option>
                    <option value="R">Runoff</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="desc">Election Date</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <input type="date" tabindex="2" name="election_date" id="election_date" required />
                </div>
              </div>
              <div class="row">
                <div class="large-2 large-push-1 columns">
                  <label for="election_live">Live updates?</label>
                </div>
                <div class="large-6 large-pull-3 columns">
                  <div class="row">
                    <div class="large-1 columns">
                      <input type="checkbox" tabindex="3" name="election_live" id="election_live" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="large-12 columns text-center">
                  <input type="submit" tabindex="4" class="button large-12 columns" style="margin-top:2em;padding:1em 2em" value="CREATE ELECTION" />
                </div>
              </div>
            </fieldset>
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
