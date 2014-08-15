<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function cache_check ($file = 'cachefile')
  {
    global $cache;
    global $cachesystem;
    global $cachetime;
    global $lang;
    global $dateformat;
    global $timeformat;
    if ($cachesystem == 'yes')
    {
      $cachefile = TSDIR . '/' . $cache . '/' . $file . '.html';
      $cachetimee = 60 * $cachetime;
      clearstatcache ();
      if ((file_exists ($cachefile) AND TIMENOW - $cachetimee < filemtime ($cachefile)))
      {
        include_once $cachefile;
        $filetime = filemtime ($cachefile);
        echo '<br />' . show_notice (sprintf ($lang->global['cachedmessage'], my_datee ($dateformat, $filetime) . ' ' . my_datee ($timeformat, $filetime), $cachetime));
        end_main_frame ();
        stdfoot ();
        exit ();
      }

      ob_start ();
    }

  }

  function cache_save ($file = 'cachefile')
  {
    global $cache;
    global $cachesystem;
    if ($cachesystem == 'yes')
    {
      $fp = fopen (TSDIR . '/' . $cache . '/' . $file . '.html', 'w');
      fwrite ($fp, ob_get_contents ());
      fclose ($fp);
      ob_end_flush ();
    }

  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
