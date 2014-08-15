<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  $rootpath = './../../';
  require $rootpath . 'global.php';
  dbconn ();
  define ('TS_SHOUTCAST', true);
  define ('SKIP_AUT', true);
  define ('CACHE_PATH', './../');
  require './../setup.php';
  $LPS = '';
  foreach ($song as $s)
  {
    $LPS .= '+ ' . htmlspecialchars_uni ($s) . '<br />';
  }

  file_put_contents (CACHE_PATH . 'lps.dat', $LPS);
  echo '&_result=' . htmlspecialchars ($song[0]) . '&';
?>
