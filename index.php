<?php

require_once './counties.php';

function get_county_from_slug($cslug,$counties) {
    $return_county = false;
    foreach($counties as $county) {
        $county_slug = str_replace(' ', '-', strtolower($county));
        if ($cslug == $county_slug) {
            $return_county = $county;
        }
    }
    return $return_county;
}

$iframe = (isset($_GET['iframe']) && $_GET['iframe'] == 'true') ? true : false;
$election_date = (isset($_GET['date']) && ctype_digit($_GET['date'])) ? $_GET['date'] : false;
$election_county = (isset($_GET['county']) && ctype_alpha($_GET['county'])) ? $_GET['county'] : false;
$election_county_display = (get_county_from_slug($election_county,$counties) && $election_county) ? get_county_from_slug($election_county,$counties) : false;

$base_url = 'https://elections.denverpost.com/';
$base_title = 'Election Results - The Denver Post';
$base_description = 'Election results for national, state, county and city elections in Colorado from The Denve Post.';

$directories = array();
if ($results = scandir('./results')) {
    foreach ($results as $result) {
        if ($result === '.' || $result === '..' || $result === 'index.php') continue;

        if (is_dir('./results/' . $result)) {
            $directories[] = $result;
        }
    }
}
sort($directories, SORT_NATURAL);
$directories = array_reverse($directories);
$elections_available = array();
foreach($directories as $dir) {
    if ($handle = scandir('./results/'.$dir.'/')) {
        foreach ($handle as $file) {
            $filepath = pathinfo($file);
            if ($file === '.' || $file === '..' || $file === 'index.php' || $filepath['extension'] !== 'json') {
                continue;
            } else {
                $elections_available[$dir][] = $filepath['filename'];
            }
        }
    }
}
foreach($directories as $dir) {
    sort($elections_available[$dir], SORT_NATURAL);
}

$datafile_address = false;
if ($election_date) {
    $datafile_address = './results/'.$election_date.'/';
    if ($election_county) {
        $datafile_address .= $election_county.'.json';
    } else {
        $datafile_address .= 'colorado.json';
    }
}

$elex_available = json_encode($elections_available);

?><!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title><?php echo $base_title; ?></title>

    <link rel="canonical" href="<?php echo $base_url; ?>" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />

    <meta name="distribution" content="global" />
    <meta name="robots" content="index" />
    <meta name="title" content="Election Results - The Denver Post" />
    <meta name="language" content="en, sv" />
    <meta name="Copyright" content="Copyright &copy; The Denver Post." />

    <meta name="description" content="Election results for national, state, county and city elections in Colorado from The Denver Post.">
    <meta name="news_keywords" content="election,results,votes,candidates,politics,colorado,legislature,governor,secretary">

    <meta name="twitter:card" value="summary" />
    <meta name="twitter:url" value="https://elections.denverpost.com" />
    <meta name="twitter:title" value="Election Results - The Denver Post" />
    <meta name="twitter:description" value="<?php echo $base_description; ?>" />
    <meta name="twitter:image" value="<?php echo $base_url; ?>img/election-results-share.jpg" />
    <meta name="twitter:site" value="@denverpost" />
    <meta name="twitter:domain" value="denverpost.com" />
    <meta name="twitter:creator" value="@joemurph">
    <meta name="twitter:app:name:iphone" value="Denver Post">
    <meta name="twitter:app:name:ipad" value="Denver Post">
    <meta name="twitter:app:name:googleplay" value="The Denver Post">
    <meta name="twitter:app:id:iphone" value="id375264133">
    <meta name="twitter:app:id:ipad" value="id409389010">
    <meta name="twitter:app:id:googleplay" value="com.ap.denverpost" />

    <meta property="fb:app_id" content="105517551922"/>
    <meta property="og:title" content="<?php echo $base_title; ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?php echo $base_url; ?>" />
    <meta property="og:image" content="<?php echo $base_url; ?>img/election-results-share.jpg" />
    <meta property="og:site_name" content="The Denver Post" />
    <meta property="og:description" content="<?php echo $base_description; ?>" />
    <meta property="article:publisher" content="https://www.facebook.com/denverpost" />

    <meta name="google-site-verification" content="2bKNvyyGh6DUlOvH1PYsmKN4KRlb-0ZI7TvFtuKLeAc" />

    <!-- STYLE SHEETS -->
    <link rel="stylesheet" href="./css/foundation.min.css" />
    <link rel="stylesheet" href="./css/normalize.min.css" />
    <link rel="stylesheet" href="./css/site.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">

    <script src="./js/jquery.min.js"></script>
    <script src="./js/modernizr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js"></script>
    <script>
        var datafile = <?php echo ($datafile_address) ? "'".$datafile_address."'" : 'false'; ?>;
        var elex_available_php = <?php echo ($elex_available) ? "'".$elex_available."'" : 'false'; ?>;
        var elex_available = [];
        if ( elex_available_php !== 'false') {
            elex_available = $.parseJSON(elex_available_php);
        }
    </script>

    <script>
        var iframe = ( window.top !== window.self ) ? 1 : 0;
    </script>
    
    <?php if ($iframe === false) { ?>
    <script>
        if ( iframe === '' ) {
            var s = document.createElement("script");
            s.src = "//www.googletagservices.com/tag/js/gpt.js";
            $("head").append(s);
        }
    </script>
    <?php } ?>

    <script>
      if ( typeof googletag !== "undefined" ) {
        googletag.defineSlot('/8013/denverpost.com/News',[[300,250],[300,600],[160,600],[300,1050]], 'dfp-20').addService(googletag.pubads()).setTargeting('pos',['Cube1_RRail_ATF']).setTargeting('kv','politics');                                          
        googletag.defineSlot('/8013/denverpost.com/News',[[300,250]], 'dfp-21').addService(googletag.pubads()).setTargeting('pos',['Cube2_RRail_mid']).setTargeting('kv','politics');
        googletag.defineSlot('/8013/denverpost.com/News',[[300,250]], 'dfp-22').addService(googletag.pubads()).setTargeting('pos',['Cube3_RRail_lower']).setTargeting('kv','politics');
        var dfpBuiltMappings = {};
        var ranNum = Math.floor(Math.random()*101);
        var ranRPN = ranNum.toString();
        dfpBuiltMappings["top_leaderboard"] = googletag.sizeMapping().addSize([1000,200],[[728,90],[970,90],[970,250],[970,30]]).addSize([750,200],[[728,90]]).addSize([300,400],[[300,50],[320,50],[320,100]]).build();
        dfpBuiltMappings["Cube1_RRail_ATF"] = googletag.sizeMapping().addSize([1000,200],[[300,250],[300,600],[300,1050]]).addSize([750,200],[[300,250]]).addSize([300,400],[[300,250]]).build();
        dfpBuiltMappings["Cube2_RRail_mid"] = googletag.sizeMapping().addSize([1000,200],[[300,250]]).addSize([750,200],[[300,250]]).addSize([300,400],[[300,250]]).build();
        dfpBuiltMappings["Cube3_RRail_lower"] = googletag.sizeMapping().addSize([1000,200],[[300,250]]).addSize([750,200],[[300,250]]).addSize([300,400],[[300,250]]).build();
        dfpBuiltMappings["bottom_leaderboard"] = googletag.sizeMapping().addSize([1000,200],[[728,90],[970,250],[970,90]]).addSize([750,200],[[728,90]]).addSize([300,400],[[320,100],[320,50]]).build();
        googletag.defineSlot("\/8013\/denverpost.com\/politics\/colorado-legislature",[728,90],"div-gpt-ad-top_leaderboard").defineSizeMapping(dfpBuiltMappings["top_leaderboard"]).setTargeting("POS",["top_leaderboard"]).setTargeting("kv","colorado-legislature").setTargeting("page",["section"]).setTargeting("RPN", ranRPN).addService(googletag.pubads());
        googletag.defineSlot("\/8013\/denverpost.com\/politics\/colorado-legislature",[300,250],"div-gpt-ad-Cube1_RRail_ATF").defineSizeMapping(dfpBuiltMappings["Cube1_RRail_ATF"]).setTargeting("POS",["Cube1_RRail_ATF"]).setTargeting("kv","colorado-legislature").setTargeting("page",["section"]).setTargeting("RPN", ranRPN).addService(googletag.pubads());
        googletag.defineSlot("\/8013\/denverpost.com\/politics\/colorado-legislature",[300,250],"div-gpt-ad-Cube2_RRail_mid").defineSizeMapping(dfpBuiltMappings["Cube2_RRail_mid"]).setTargeting("POS",["Cube2_RRail_mid"]).setTargeting("kv","colorado-legislature").setTargeting("page",["section"]).setTargeting("RPN", ranRPN).addService(googletag.pubads());
        googletag.defineSlot("\/8013\/denverpost.com\/politics\/colorado-legislature",[728,90],"div-gpt-ad-bottom_leaderboard").defineSizeMapping(dfpBuiltMappings["bottom_leaderboard"]).setTargeting("POS",["bottom_leaderboard"]).setTargeting("kv","colorado-legislature").setTargeting("page",["section"]).setTargeting("RPN", ranRPN).addService(googletag.pubads());
        googletag.pubads().enableSyncRendering();
        googletag.enableServices();
      }
    </script>
</head>
<body<?php echo ($iframe) ? ' class="iframe"' : ''; ?>>
<!-- Google Tag Manager Data Layer -->
<?php if ($iframe === false) { ?>
  <script>
    var is_mobile = function() {
      var check = false;
      (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
      if ( check == true ) return 'YES';
      return 'NO';
    };
    analyticsEvent = function() {};
    analyticsSocial = function() {};
    analyticsVPV = function() {};
    analyticsClearVPV = function() {};
    analyticsForm = function() {};
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
        'ga_ua':'UA-61435456-7',
        'quantcast':'p-4ctCQwtnNBNs2',
        'quantcast label': 'Denver',
        'comscore':'6035443',
        'errorType':'',
        'Publisher Domain':'denverpost.com',
        'Publisher Product':'extras.denverpost.com',
        'Dateline':'',
        'Publish Hour of Day':'',
        'Create Hour of Day':'',
        'Update Hour of Day':'',
        'Behind Paywall':'NO',
        'Mobile Presentation':is_mobile(),
        'kv':'Colorado Legislature',
        'Release Version':'',
        'Digital Publisher':'Denver Post',
        'Platform':'custom',
        'Section':'Colorado Legislature',
        'Taxonomy1':'News',
        'Taxonomy2':'Politics',
        'Taxonomy3':'Legislature',
        'Taxonomy4':'',
        'Taxonomy5':'',
        'Content Source':'',
        'Canonical URL': '',
        'Slug':'',
        'Content ID':'',
        'Page Type':'section',
        'Publisher State':'COLORADO',
        'Byline':'',
        'Content Title':'',
        'URL':'',
        'Page Title':'',
        'User ID':''
    });
  </script>
  <!-- End Google Tag Manager Data Layer -->
<!-- Google Tag Manager --><noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TLFP4R" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>
        if ( iframe === '' ) {
(function(w,d,s,l,i) {
   w[l]=w[l]||[];
   w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
   var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
   j.async=true;
   j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})
(window,document,'script','dataLayer','GTM-TLFP4R');
}
</script><!-- End Google Tag Manager -->
<?php } ?>

<div id="dfmHeader"><!--Header Goes Here--></div>
    <div id="div-gpt-ad-top_leaderboard" class="dfp-ad dfp-top_leaderboard" data-ad-unit="top_leaderboard">
        <script>
            if ( "undefined" !== typeof googletag ) {
                googletag.cmd.push( function() { googletag.display("div-gpt-ad-top_leaderboard"); } );
            }
        </script>
    </div>

    <div id="wrapper" class="body-copy">

        <div id="breadcrumbs">
            <a href="https://www.denverpost.com/politics/">Politics</a>
            <span style="font-size:120%;color:#aaa;padding:0 .25em;">&rsaquo;</span> <a href="https://elections.denverpost.com/">Elections</a>
            <?php if ($election_date) { ?>
                <span style="font-size:120%;color:#aaa;padding:0 .25em;">&rsaquo;</span> <a href="https://elections.denverpost.com/?date=<?php echo $election_date; ?>"><?php echo date('F j, Y',strtotime($election_date)); ?></a>
                <?php if ($election_county) { ?>
                <span style="font-size:120%;color:#aaa;padding:0 .25em;">&rsaquo;</span> <a href="https://elections.denverpost.com/?date=<?php echo $election_date; ?>&county=<?php echo $election_county; ?>"><?php echo $election_county_display; ?></a>
                <?php }
                } ?>
        </div>

        <div class="row body-copy">

            <div class="maincol small-12 large-9 columns">

                <h1><?php echo $base_title; ?></h1>
                <!-- RESULTS TABLE STARTS HERE -->
                <div class="row">                    
                    <div id="results-container" class="large-10 large-centered columns"></div>
                </div>

                <script id="results-template" type="text/x-handlebars-template">
                    {{#each this}}
                    <h3 class="race-title">{{race_name}}</h3> {{#eachByVotePct results "choice_vote_percent"}}
                    <table class="fixed-width">
                        <tbody>
                            <tr>
                                <td class="table-data">{{choice_name}}</td><td class="table-data">{{choice_vote_percent}}%</td>
                                <td class="table-data">
                                    <div class="bar-background">
                                        <div class="bar-percentage" style="width:{{choice_vote_percent}}%"></div>
                                    </div>
                                </td>
                                <td class="table-data">{{formatNumber choice_votes}}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{/eachByVotePct}} {{/each}}
                    </div>
                </script>
                <script>
                    //if (datafile !== 'false') { 
                        $.getJSON("./html_framework/results.json", function(data) {
                            $(document).ready(function() {
                                // Helper for formatting vote totals
                                Handlebars.registerHelper('formatNumber', function(value) {
                                    return value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "1,");
                                });
                                // Helper for sorting by vote percentage
                                Handlebars.registerHelper('eachByVotePct', function(context,array_key,options){
                                    var output = '';
                                    var contextSorted = context.concat()
                                        .sort( function(a,b) { return b[array_key] - a[array_key] } );
                                    for(var i=0, j=contextSorted.length; i<j; i++) {
                                        output += options.fn(contextSorted[i]);
                                    }
                                    return output;
                                });
                                var source = $("#results-template").html();
                                var template = Handlebars.compile(source);
                                var html = template(data);
                                $('#results-container').html(html);
                            })
                        })
                    //};
                </script>

            </div>
            <!-- RESULTS TABLE ENDS HERE -->

            <div class="rightRail sidebarcol small-12 large-3 columns" id="rightRail">



                <div id='dfp-20' class='ad'>
                    <script>
                        if ( typeof googletag !== "undefined" ) googletag.display('dfp-20');
                    </script>
                </div>

                <div class="sidebar_headlines panel" style="margin-bottom:2em;">
                    <h4>Jump to results</h4>
                    <form mathod="get">
                        <select name="date" id="date_select">
                            <option value="" disabled<?php echo (!$election_date) ? ' selected' : ''; ?>>Election date...</option>
                            <?php foreach($directories as $dir) {
                                $datename = date('F j, Y',strtotime($dir));
                                $date_selected = ($dir === $election_date) ? ' selected' : '';?>
                                <option value="<?php echo $dir; ?>"<?php echo $date_selected; ?>><?php echo $datename; ?></option>
                            <?php } ?>
                        </select>
                        <select name="county" id="county_select" disabled class="disabled-grey">
                            <option value="">Select county...</option>
                        </select>
                        <input type="button" onclick="form.submit();" value="Get results!" />
                    </form>
                </div>

                <div class="sidebar_headlines">
                    <h4>More politics headlines</h4>
                    <script src="//extras.denverpost.com/cache/politics_legislature.js"></script>
                </div>

                <div id='dfp-21' class='ad'>
                    <script>
                        if ( typeof googletag !== "undefined" ) googletag.display('dfp-21');
                    </script>
                </div>
                <div id='dfp-22' class='ad'>
                    <script>
                        if ( typeof googletag !== "undefined" ) googletag.display('dfp-22');
                    </script>
                </div>

            </div><!-- END rightRail -->
            <div class="clear" style="height:0;width:100%;clear:both;"></div>
        </div><!-- END div.row -->

    </div><!-- END wrapper -->

    <section id="content-footer" class="body-copy">
        
    </section>

    <div id="div-gpt-ad-bottom_leaderboard" class="dfp-ad dfp-bottom_leaderboard" data-ad-unit="bottom_leaderboard">
        <script>
            if ( "undefined" !== typeof googletag ) {
                googletag.cmd.push( function() { googletag.display("div-gpt-ad-bottom_leaderboard"); } );
            }
        </script>
    </div>
    <div id="dfmFooter" style="border-top:1px solid #ddd;"><!--CORPORATE FOOTER--></div>
    <script src="./js/nav.js"></script>

    <footer>
    <!-- START Parse.ly Include: Standard -->
    <div id="parsely-root" style="display: none">
      <span id="parsely-cfg" data-parsely-site="denverpost.com"></span>
    </div>

    <?php if ($iframe === false) { ?>
    <script>
        if ( iframe === 1 ) {
            (function(s, p, d) {
              var h=d.location.protocol, i=p+"-"+s,
                  e=d.getElementById(i), r=d.getElementById(p+"-root"),
                  u=h==="https:"?"d1z2jf7jlzjs58.cloudfront.net"
                  :"static."+p+".com";
              if (e) return;
              e = d.createElement(s); e.id = i; e.async = true;
              e.src = h+"//"+u+"/p.js"; r.appendChild(e);
            })("script", "parsely", document);
        }
    </script>
    <!-- END Parse.ly Include: Standard -->
    <?php } ?>

    </footer>
    
    <script>
    if ( iframe === 1 ) {
        // Loop through all the links and add target="_parent"
        $("a").each(function(index) {
            $(this).attr('target', '_parent');
        });
        // Remove the logo and footer
        $('footer, #dfmHeader, #dfmFooter, #breadcrumbs').remove();
    }
    var checkExist = setInterval(function() {
       if ($('#web-push-prompt').length) {
          $('#web-push-prompt').remove();
          clearInterval(checkExist);
       }
    }, 100);
    function titleCaseCounty(str) {
      str = str.toLowerCase().split('-');
      for (var i = 0; i < str.length; i++) {
        str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1); 
      }
      return str.join(' ');
    }
    $('#date_select').on('change', function(){
        var date_sel = $('#date_select').val().toString();
        if (elex_available[date_sel].length > 0) {
            $('#county_select').removeAttr('disabled');
            $('#county_select').removeClass('disabled-grey');
        }
        if (elex_available[date_sel].indexOf('colorado') > -1 ) {
            var html_statewide = '<option value="colorado">Statewide results</option>';
            $('#county_select').append(html_statewide);
        }
        elex_available[date_sel].forEach(function(county_slug){
            if (county_slug !== 'colorado') {
                var county_name = titleCaseCounty(county_slug);
                var html_output = '<option value="' + county_slug + '">' + county_name + '</option>';
                $('#county_select').append(html_output);
            }
        });
    });
    </script>
</body>
</html>