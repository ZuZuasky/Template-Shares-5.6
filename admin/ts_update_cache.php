<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_categories_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM categories WHERE type = \'c\' ORDER by name,id');
    while ($_c = mysql_fetch_assoc ($query))
    {
      $_ccache[] = $_c;
    }

    $query = sql_query ('SELECT * FROM categories WHERE type = \'s\' ORDER by name,id');
    while ($_c = mysql_fetch_assoc ($query))
    {
      $_ccache2[] = $_c;
    }

    $content = var_export ($_ccache, true);
    $content2 = var_export ($_ccache2, true);
    $_filename = TSDIR . '/' . $cache . '/categories.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#7 - Do Not Alter
 * Cache Name: Categories
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$_categoriesC = ' . $content . ';

';
    $_cachecontents .= '' . '$_categoriesS = ' . $content2 . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function update_ipban_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM ipbans');
    $_ucache = mysql_fetch_assoc ($query);
    $content = var_export ($_ucache, true);
    $_filename = TSDIR . '/' . $cache . '/ipbans.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#6 - Do Not Alter
 * Cache Name: IPBans
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$ipbanscache = ' . $content . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function update_plugin_cache ()
  {
    global $cache;
    $left = $middle = $right = array ();
    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 1 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $left[] = $query;
    }

    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 2 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $middle[] = $query;
    }

    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 3 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $right[] = $query;
    }

    $left = var_export ($left, true);
    $middle = var_export ($middle, true);
    $right = var_export ($right, true);
    $_filename = TSDIR . '/' . $cache . '/plugins.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
if (!defined(\'IN_PLUGIN_SYSTEM\')) die("<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>");

';
    $_cachecontents .= '/** TS Generated Cache#3 - Do Not Alter
 * Cache Name: Plugins
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$Plugins_LEFT = ' . $left . ';

';
    $_cachecontents .= '' . '$Plugins_MIDDLE = ' . $middle . ';

';
    $_cachecontents .= '' . '$Plugins_RIGHT = ' . $right . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function update_usergroup_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM usergroups ORDER by gid');
    while ($_uc = mysql_fetch_assoc ($query))
    {
      $_ucache[$_uc['gid']] = $_uc;
    }

    $content = var_export ($_ucache, true);
    $_filename = TSDIR . '/' . $cache . '/usergroups.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#5 - Do Not Alter
 * Cache Name: Usergroups
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$usergroupscache = ' . $content . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function update_funds_cache ()
  {
    global $cache;
    $get_total_funds = sql_query ('SELECT SUM(cash) AS total_funds FROM funds WHERE cash > 0');
    $total_funds = mysql_fetch_assoc ($get_total_funds);
    $contents = array ('funds_so_far' => $total_funds['total_funds']);
    $name = 'funds';
    $filename = TSDIR . '/' . $cache . '/funds.php';
    $cachefile = @fopen ('' . $filename, 'w');
    $cachecontents = '' . '<?php
/** TS Generated Cache#12 - Do Not Alter
 * Cache Name: ' . $name . '
 * Generated: ' . gmdate ('r') . '
*/

';
    $cachecontents .= ('' . '$') . $name . ' = ' . @var_export ($contents, true) . ';
?>';
    @fwrite ($cachefile, $cachecontents);
    @fclose ($cachefile);
  }

  function update_indexstats_cache ()
  {
    global $cache;
    global $includeexpeers;
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
    $latestuser = '<a href="' . $BASEURL . '/userdetails.php?id=' . $latestuser['id'] . '">' . $latestuser['username'] . '</a>';
    $getfstats = sql_query ('SELECT SUM(posts) AS totalposts, SUM(threads) AS totalthreads FROM ' . TSF_PREFIX . 'forums');
    $fstats = mysql_fetch_assoc ($getfstats);
    $totalposts = $fstats['totalposts'];
    $totalthreads = $fstats['totalthreads'];
    $contents = array ('torrents' => $torrents, 'seeders' => $seeders, 'leechers' => $leechers, 'peers' => '' . $peers, 'totaldownloaded' => mksize ($totaldownloaded), 'totaluploaded' => mksize ($totaluploaded), 'registered' => $registered, 'latestuser' => $latestuser, 'totalposts' => $totalposts, 'totalthreads' => $totalthreads);
    $filename = TSDIR . '/' . $cache . '/indexstats.php';
    $name = 'indexstats';
    $cachefile = @fopen ('' . $filename, 'w');
    $cachecontents = '' . '<?php
/** TS Generated Cache#1 - Do Not Alter
 * Cache Name: ' . $name . '
 * Generated: ' . gmdate ('r') . '
*/

';
    $cachecontents .= ('' . '$') . $name . ' = ' . @var_export ($contents, true) . ';
?>';
    @fwrite ($cachefile, $cachecontents);
    @fclose ($cachefile);
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('TUC_VERSION', '0.5 by xam');
  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    $cachename = trim ($_POST['cachename']);
    if (function_exists ('update_' . $cachename . '_cache'))
    {
      switch ($cachename)
      {
        case 'categories':
        {
          update_categories_cache ();
          break;
        }

        case 'ipban':
        {
          update_ipban_cache ();
          break;
        }

        case 'plugin':
        {
          update_plugin_cache ();
          break;
        }

        case 'usergroup':
        {
          update_usergroup_cache ();
          break;
        }

        case 'funds':
        {
          update_funds_cache ();
          break;
        }

        case 'indexstats':
        {
          update_indexstats_cache ();
          break;
        }

        case 'smilies':
        {
          update_smilies_cache ();
        }
      }

      $is_done[$cachename] = 1;
    }
  }

  $cache_arrays = array ('categories', 'ipban', 'plugin', 'usergroup', 'funds', 'indexstats', 'smilies');
  $i = 0;
  while ($i < count ($cache_arrays))
  {
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="do" value="ts_update_cache">
		<input type="hidden" name="cachename" value="' . $cache_arrays[$i] . '">
		<input type="submit" value="Rebuild ' . $cache_arrays[$i] . ' Cache">' . ($is_done[$cache_arrays[$i]] ? ' <b><font color="red">Updated!</font></b>' : '') . '
	</form><br />';
    ++$i;
  }

  if (!isset ($_GET['delete']))
  {
    echo '<div align="center"><a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_update_cache&amp;delete=true"><b><font color="red">Delete Cache Files</font></b></a></div>';
    return 1;
  }

  $CACHEFOLDER = $rootpath . 'cache/';
  if ($handle = opendir ($CACHEFOLDER))
  {
    echo '<div align="center">';
    $deletedcachefile = false;
    while (false !== $file = readdir ($handle))
    {
      if ((($file != '.' AND $file != '..') AND get_extension ($file) == 'html'))
      {
        unlink ($CACHEFOLDER . $file);
        echo $CACHEFOLDER . $file . ' has been deleted...<br />';
        $deletedcachefile = true;
        continue;
      }
    }

    closedir ($handle);
    if (!$deletedcachefile)
    {
      echo 'There is no cache file to delete!';
    }

    echo '</div>';
    return 1;
  }

  echo '<div align="center">I can\'t open cache folder.</div>';
?>
