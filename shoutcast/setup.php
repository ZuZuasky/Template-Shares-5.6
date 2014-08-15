<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('TS_SHOUTCAST'))
  {
    exit ();
  }

  require INC_PATH . '/readconfig_shoutcast.php';
  if (!defined ('SKIP_AUT'))
  {
    if ($s_allowedusergroups = explode (',', $s_allowedusergroups))
    {
      if (!in_array ($CURUSER['usergroup'], $s_allowedusergroups))
      {
        print_no_permission ();
      }
    }
  }

  if (!function_exists ('file_put_contents'))
  {
    function file_put_contents ($filename, $contents)
    {
      if (is_writable ($filename))
      {
        if ($handle = fopen ($filename, 'w'))
        {
          if (fwrite ($handle, $contents) === FALSE)
          {
            return false;
          }

          fclose ($filename);
          return true;
        }
      }

      return false;
    }
  }

  $scdef = $s_servername;
  $scip = $s_serverip;
  $scport = $s_serverport;
  $scpass = $s_serverpassword;
  $ircsite = $s_serverirc;
  $file = (defined ('CACHE_PATH') ? CACHE_PATH : '') . $s_servercachefile;
  $cache_tolerance = $s_servercachetime;
  if (file_exists ($file))
  {
    clearstatcache ();
    $time_difference = time () - filemtime ($file);
  }
  else
  {
    $time_difference = $cache_tolerance;
  }

  $scfp = @fsockopen ($scip, $scport, $errno, $errstr, 3);
  if ($scfp)
  {
    if ($cache_tolerance <= $time_difference)
    {
      if ($scsuccs != 1)
      {
        fputs ($scfp, '' . 'GET /admin.cgi?pass=' . $scpass . '&mode=viewxml HTTP/1.0
User-Agent: SHOUTcast Song Status (Mozilla Compatible)

');
        while (!feof ($scfp))
        {
          $xmlfeed .= fgets ($scfp, 8192);
        }

        fclose ($scfp);
      }

      file_put_contents ($file, $xmlfeed);
      flush ();
      $xmlcache = fopen ($file, 'r');
      $page = '';
      if ($xmlcache)
      {
        while (!feof ($xmlcache))
        {
          $page .= fread ($xmlcache, 8192);
        }

        fclose ($xmlcache);
      }
    }
    else
    {
      $xmlcache = fopen ($file, 'r');
      $page = '';
      if ($xmlcache)
      {
        while (!feof ($xmlcache))
        {
          $page .= fread ($xmlcache, 8192);
        }

        fclose ($xmlcache);
      }
    }

    $loop = array ('AVERAGETIME', 'CURRENTLISTENERS', 'PEAKLISTENERS', 'MAXLISTENERS', 'SERVERGENRE', 'SERVERURL', 'SERVERTITLE', 'SONGTITLE', 'SONGURL', 'IRC', 'ICQ', 'AIM', 'WEBHITS', 'STREAMHITS', 'LISTEN', 'STREAMSTATUS', 'BITRATE', 'CONTENT');
    $y = 0;
    while ($loop[$y] != '')
    {
      $pageed = ereg_replace ('' . '.*<' . $loop[$y] . '>', '', $page);
      $scphp = strtolower ($loop[$y]);
      $$scphp = ereg_replace ('' . '</' . $loop[$y] . '>.*', '', $pageed);
      if (((($loop[$y] == SERVERGENRE OR $loop[$y] == SERVERTITLE) OR $loop[$y] == SONGTITLE) OR $loop[$y] == SERVERTITLE))
      {
        $$scphp = urldecode ($$scphp);
      }

      ++$y;
    }

    $pageed = ereg_replace ('.*<SONGHISTORY>', '', $page);
    $pageed = ereg_replace ('<SONGHISTORY>.*', '', $pageed);
    $songatime = explode ('<SONG>', $pageed);
    $r = 1;
    while ($songatime[$r] != '')
    {
      $t = $r - 1;
      $playedat[$t] = ereg_replace ('.*<PLAYEDAT>', '', $songatime[$r]);
      $playedat[$t] = ereg_replace ('</PLAYEDAT>.*', '', $playedat[$t]);
      $song[$t] = ereg_replace ('.*<TITLE>', '', $songatime[$r]);
      $song[$t] = ereg_replace ('</TITLE>.*', '', $song[$t]);
      $song[$t] = urldecode ($song[$t]);
      $dj[$t] = ereg_replace ('.*<SERVERTITLE>', '', $page);
      $dj[$t] = ereg_replace ('</SERVERTITLE>.*', '', $pageed);
      ++$r;
    }

    $averagemin = round ($averagetime / 60, 2);
    $irclink = 'irc://' . $ircsite . '/' . htmlspecialchars_uni ($irc);
    $listenamp = 'http://' . $scip . ':' . $scport . '/listen.pls';
    $listenlnk = 'http://' . $scip . ':' . $scport . '';
  }

  echo ' ';
?>
