<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  set_time_limit (0);
  clearstatcache ();
  extract (unserialize (file_get_contents ('./../../config/SHOUTCAST')), EXTR_PREFIX_SAME, 'wddx');
  if ($sock = @fsockopen ($s_serverip, $s_serverport, $errno, $errstr, ($_GET['ping'] == 'true' ? 1 : 5)))
  {
    @fputs ($sock, 'GET / HTTP/1.0
');
    @fputs ($sock, (('' . 'Host: ' . $s_servername . '
') . '
'));
    @fputs ($sock, 'User-Agent: WinampMPEG/2.8
');
    @fputs ($sock, 'Connection: close

');
    if ($_GET['ping'] == 'true')
    {
      echo @fread ($sock, 8);
    }
    else
    {
      while ($contents = @fread ($sock, 524))
      {
        echo $contents;
      }
    }

    fclose ($sock);
  }

?>
