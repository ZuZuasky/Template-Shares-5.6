<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_cache ($name = 'indexstats', $ForceUpdate = false)
  {
    global $cache;
    global $cachetime;
    global $cachesystem;
    global $BASEURL;
    global $includeexpeers;
    $filename = TSDIR . '/' . $cache . '/' . $name . '.php';
    if (file_exists ($filename))
    {
      clearstatcache ();
      $Update = (filemtime ($filename) + $cachetime * 60 < time () ? true : false);
    }
    else
    {
      $Update = false;
    }

    if (($Update OR $ForceUpdate))
    {
      if ($name == 'funds')
      {
        $get_total_funds = sql_query ('SELECT SUM(cash) AS total_funds FROM funds WHERE cash > 0');
        $total_funds = mysql_fetch_assoc ($get_total_funds);
        $contents = array ('funds_so_far' => $total_funds['total_funds']);
      }
      else
      {
        if ($name == 'indexstats')
        {
          $torrents = tsrowcount ('id', 'torrents');
          $seeders = tsrowcount ('id', 'peers', 'seeder=\'yes\'');
          $leechers = tsrowcount ('id', 'peers', 'seeder=\'no\'');
          if ($includeexpeers == 'yes')
          {
            $ts_e_query = sql_query ('SELECT SUM(leechers) as leechers, SUM(seeders) as seeders FROM torrents WHERE ts_external = \'yes\'');
            $ts_e_query_r = mysql_fetch_row ($ts_e_query);
            $leechers += $ts_e_query_r[0];
            $seeders += $ts_e_query_r[1];
          }

          $peers = $seeders + $leechers;
          $ratio = ($leechers == 0 ? 0 : round ($seeders / $leechers * 100));
          $result = sql_query ('SELECT SUM(downloaded) AS totaldl, SUM(uploaded) AS totalul, COUNT(id) AS totaluser FROM users');
          $row = mysql_fetch_assoc ($result);
          $totaldownloaded = $row['totaldl'];
          $totaluploaded = $row['totalul'];
          $registered = $row['totaluser'];
          $latestuser = mysql_fetch_assoc (sql_query ('SELECT id,username FROM users WHERE status=\'confirmed\' ORDER BY id DESC LIMIT 0,1'));
          $latestuser = '<a href="' . ts_seo ($latestuser['id'], $latestuser['username']) . '">' . $latestuser['username'] . '</a>';
          $getfstats = sql_query ('SELECT SUM(posts) AS totalposts, SUM(threads) AS totalthreads FROM ' . TSF_PREFIX . 'forums');
          $fstats = mysql_fetch_assoc ($getfstats);
          $totalposts = $fstats['totalposts'];
          $totalthreads = $fstats['totalthreads'];
          $contents = array ('torrents' => $torrents, 'seeders' => $seeders, 'leechers' => $leechers, 'peers' => '' . $peers, 'totaldownloaded' => mksize ($totaldownloaded), 'totaluploaded' => mksize ($totaluploaded), 'registered' => $registered, 'latestuser' => $latestuser, 'totalposts' => $totalposts, 'totalthreads' => $totalthreads);
        }
      }

      $cachefile = fopen ($filename, 'w');
      $cachecontents = '' . '<?php
/** TS Generated Cache#1 - Do Not Alter
 * Cache Name: ' . $name . '
 * Generated: ' . gmdate ('r') . '
*/

';
      $cachecontents .= ('' . '$') . $name . ' = ' . var_export ($contents, true) . ';
?>';
      fwrite ($cachefile, $cachecontents);
      fclose ($cachefile);
    }

  }

  if ((!defined ('IN_SCRIPT_TSSEv56') AND !defined ('IN_CRON')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TSC_VERSION', '1.0 by xam');
  if (!function_exists ('ts_seo'))
  {
    require_once INC_PATH . '/functions_tsseo.php';
  }

  if (!function_exists ('sql_query'))
  {
    function sql_query ($Q)
    {
      return mysql_query ($Q);
    }
  }

?>
