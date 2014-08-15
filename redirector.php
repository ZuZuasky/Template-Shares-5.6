<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  define ('R_VERSION', '0.7 ');
  $url = fix_url ($_GET['url']);
  if (((empty ($url) OR strlen ($url) < 12) OR (substr ($url, 0, 7) != 'http://' AND substr ($url, 0, 8) != 'https://')))
  {
    define ('errorid', 403);
    include_once TSDIR . '/ts_error.php';
    exit ();
  }

  $url = '' . 'http://anonym.to/?' . $url;
  echo '<HTML>
<HEAD>
<TITLE>';
  echo $SITENAME;
  echo ' - ';
  echo $lang->global['redirectto'];
  echo ' ';
  echo fix_url ($url);
  echo '</TITLE>
</HEAD>
<frameset border="1" framespacing="0" rows="*,20" frameborder="0">
	<frame name="content" marginwidth="0" marginheight="0" bottomnargin="0" src="';
  echo fix_url ($url);
  echo '">
	<frame name="footer" marginwidth="0" marginheight="0" src="redirector_footer.php?url=';
  echo fix_url ($url);
  echo '" scrolling="no">
</frameset>
<noframes></noframes>
<body bgcolor="#ffffff">
</body>
</HTML>';
?>
