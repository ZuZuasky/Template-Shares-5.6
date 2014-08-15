<?php
require_once('global.php');
dbconn();
loggedinorreturn();
maxsysop ();
parked();
$lang->load('twitter');
stdhead(sprintf($lang->twitter['head'], $SITENAME));
?>
  <meta charset="UTF-8" />
  <link href="scripts/twitter/tweet.css" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="scripts/twitter/tweet.js" charset="utf-8"></script>
  <script>
    jQuery(document).ready(function($) {

      $("#userandquery").tweet({

        username: "TattooBen",
        avatar_size: 50,
        count: 30,
        query: "TattooBen",
        loading_text: "searching twitter..."
        
      });

    })
  </script>
<?

 print "<table width=100% border=0 cellpadding=5 cellspacing=0>";
 print "<div id=userandquery class=query></div>";
 print "</table>";
  


stdfoot();
?>
