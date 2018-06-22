<?php

$base_url = 'https://elections.denverpost.com/';
$base_title = 'Election Results - The Denver Post';
$base_description = 'Election results for national, state, county and city elections in Colorado from The Denve Post.';

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

    <meta name="description" content="Election results for national, state, county and city elections in Colorado from The Denve Post.">
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
    <meta property="og:url" content="<?php echo $base_url; ?>" />a
    <meta property="og:image" content="<?php echo $base_url; ?>img/election-results-share.jpg" />
    <meta property="og:site_name" content="The Denver Post" />
    <meta property="og:description" content="<?php echo $base_description; ?>" />
    <meta property="article:publisher" content="https://www.facebook.com/denverpost" />

    <meta name="google-site-verification" content="2bKNvyyGh6DUlOvH1PYsmKN4KRlb-0ZI7TvFtuKLeAc" />

    <script>
        // Only load foundation.css on handheld devices
        if ( window.screen.availWidth < 769 || window.innerWidth < 769 )
        {
            var link = document.createElement( "link" );
            link.href = "http://cdn.foundation5.zurb.com/foundation.css";
            link.type = "text/css";
            link.rel = "stylesheet";
            link.media = "screen,print";
            document.getElementsByTagName("head")[0].appendChild(link);
        }
    </script>

    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/site.css">
    <style type="text/css">
    dt { float: left; }
    dt:after { content: ':\00a0'; }
{% block style %}{% endblock style %}
    </style>

    
    <script src="//extras.denverpost.com/media/js/jquery-min.js"></script>
    <script src="//extras.denverpost.com/media/js/dputils-min.js?v=3"></script>

    <script>
        var iframe = '', kiosk = '';
        if ( document.location.hash === '#iframe' ) { iframe = 1 }
        if ( window.top !== window.self ) { iframe = 1; }
        if ( iframe === '' ) {
            //Chartbeat startup
            var _sf_startpt=(new Date()).getTime();
            var _sf_async_config={};
        }
    </script>
    <script>
        if ( iframe === '' ) {
        (function() {
          var useSSL = "https:" == document.location.protocol;
          var src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
          document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
         })();
        }
    </script>
    <script>
      if ( typeof googletag !== "undefined" )
      {
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
    {% block head %}{% endblock head %}
</head>
<body>
<!-- Google Tag Manager Data Layer -->
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
            <a href="http://www.denverpost.com/">Home</a>
            &rsaquo; <a href="http://www.denverpost.com/politics/">Politics</a>
            &rsaquo; <a href="http://www.denverpost.com/politics/colorado-legislature/">Legislature</a>
        </div>

        <div class="row body-copy">

            <h1><?php echo $base_title; ?></h1>

            <div class="maincol small-12 large-9 columns">

                <!-- CONTENT HERE -->

            <script src="//extras.denverpost.com/app/mailer-rules/mailer.js"></script>
            <link href='//extras.denverpost.com/app/mailer-rules/style.css' rel='stylesheet' type='text/css'>

            <div id="emailThick">
                <h2>Send this page to a friend.</h2>
                <div class="left">
                    <p></p>
                </div>
                <div class="right">
                    <form action="http://denverpostplus.com/app/mailer-rules/www/" method="post" name="friend" id="friend">
                        <p id="results"></p>
                        <input type="hidden" name="keebler" value="goof111" />
                        <input type="hidden" name="goof111" value="TRUE" />
                        <input type="hidden" name="redirect" id="inputRedirURL" value="http://extras.denverpost.com/splash/sportsthanks.html" />
                        <input type="hidden" name="id" value="friendsend" />
                        <input type="hidden" name="which" value="whatever" />
                        <input type="hidden" name="url_to_send" value="<?php echo $base_url; ?>" />
                        <input type="hidden" name="subject" value="A friend wants you to check out <?php echo $base_title; ?>" />
                        <input type="text" name="name_first" value="Humans: Do Not Use" style="display:none;" />
                        <input type="text" name="name_first" value="Humans: Do Not Use" style="display:none;" />
                        <input placeholder="Your Email Address" type="text" name="email_address" maxlength="50" value="" style="width:30%;">
                        <input placeholder="Your Friend's Email" type="text" name="friend_email_address" maxlength="50" value="" style="width:30%;">
                        <input type="submit" value="SEND" onClick="form_handler.submit('#friend');">
                    </form>
                </div>
            </div>

            </div>

            <div class="rightRail sidebarcol small-12 large-3 columns" id="rightRail">

                {% if response.app.page.url %}
                <div style="margin-bottom:10px;" class="fb-like" data-href="<?php echo $base_url; ?>" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>

                <a href="https://twitter.com/share" class="twitter-share-button" data-via="denverpolitics" data-size="large" data-related="thespot" data-hashtags="coleg" data-dnt="true">Tweet this page</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                {%- endif %}


                <h3>Recently in Colorado legislature</h3>
                <p>
                    Check out Colorado Bill Tracker's
                    <a href="<?php echo $base_url; ?>the-week/{{response.app.recent.week}}/">latest week in review (for the week ending {{response.app.recent.week|date_raw|datetime_filter('datefull')}})</a>,
                    and <a href="<?php echo $base_url; ?>the-day/{{response.app.recent.day}}/">most-recent day in review, for {{response.app.recent.day|date_raw|datetime_filter('datefullweekday')}}</a>.
                </p>

                <div id='dfp-20' class='ad'>
                    <script>
                        if ( typeof googletag !== "undefined" ) googletag.display('dfp-20');
                    </script>
                </div>

                <h3>Colorado legislature news</h3>
                <script src="//extras.denverpost.com/cache/politics_legislature.js"></script>
                <noscript><p><a href="http://www.denverpost.com/politics/colorado-legislature/">Recent Colorado legislature headlines</a></p></noscript>

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
    <script src="//extras.denverpost.com/vendor_templates/mason/vendor-include.js"></script>

    <footer>
    <!-- START Parse.ly Include: Standard -->
    <div id="parsely-root" style="display: none">
      <span id="parsely-cfg" data-parsely-site="denverpost.com"></span>
    </div>
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
    </script>
</body>
</html>
