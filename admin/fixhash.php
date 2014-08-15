<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  @set_time_limit (0);
  @ini_set ('upload_max_filesize', (1000 < $max_torrent_size ? $max_torrent_size : 10485760));
  @ini_set ('memory_limit', '20000M');
  @ignore_user_abort (1);
  define ('FH_VERSION', '0.5 by xam');
  require_once INC_PATH . '/benc.php';
  require_once './include/global_config.php';
  $query = sql_query ('SELECT id FROM torrents');
  $results = mysql_num_rows ($query);
  $perpage = ($config['fixhash_perpage'] ? $config['fixhash_perpage'] : 10);
  $totalpages = @ceil ($results / $perpage);
  $pagenumber = (isset ($_GET['page']) & 0 < $_GET['page'] ? intval ($_GET['page']) : 1);
  if ($totalpages == 0)
  {
    $totalpages = 1;
  }

  if ($pagenumber < 1)
  {
    $pagenumber = 1;
  }
  else
  {
    if ($totalpages < $pagenumber)
    {
      $pagenumber = $totalpages;
    }
  }

  $limitlower = ($pagenumber - 1) * $perpage;
  $limitupper = $pagenumber * $perpage;
  if ($results < $limitupper)
  {
    $limitupper = $results;
    if ($results < $limitlower)
    {
      $limitlower = $results - $perpage - 1;
    }
  }

  if ($limitlower < 0)
  {
    $limitlower = 0;
  }

  $nextpage = $pagenumber + 1;
  stdhead ('Fix Torrent Hashes');
  ob_flush ();
  flush ();
  echo '
<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead" colspan="2">Fix Torrent Hashes</td>
	</tr>
';
  ob_flush ();
  flush ();
  $count = 0;
  $res = sql_query ('' . 'SELECT name, id FROM torrents ORDER BY added DESC LIMIT ' . $limitlower . ', ' . $limitupper);
  while ($row = mysql_fetch_assoc ($res))
  {
    echo '
		<tr>
			<td>Torrent <a href="' . $BASEURL . '/details.php?id=' . $row['id'] . '" target="_blank"><b>' . htmlspecialchars_uni ($row['name']) . '</b></a> is fixing:</td>';
    ob_flush ();
    flush ();
    $torrent = TSDIR . '/' . $torrent_dir . '/' . $row['id'] . '.torrent';
    $dict = bdec_file ($torrent, 1024 * 1024);
    $fixed = false;
    if ((file_exists ($torrent) AND isset ($dict)))
    {
      $dict = $dict['value'];
      if ((isset ($dict['info']['string']) AND !empty ($dict['info']['string'])))
      {
        $info_hash = pack ('H*', sha1 ($dict['info']['string']));
        if (sql_query ('UPDATE torrents SET info_hash = ' . sqlesc ($info_hash) . ' WHERE id = ' . sqlesc ($row['id'])))
        {
          $fixed = true;
          ++$count;
        }
      }
    }

    echo '<td>' . ($fixed ? '<font color="green"><b>Fixed</b>' : '<font color="red"><b>Error</b>') . '</td></tr>';
    ob_flush ();
    flush ();
    unset ($dict);
    unset ($info_hash);
    unset ($torrent);
  }

  echo '
</table>
<Br />
' . ($totalpages < $nextpage ? '' : '
<script type="text/JavaScript">
	<!--
	setTimeout("location.href = \'' . $_this_script_ . '&page=' . $nextpage . '\';",10000);
	-->
</script>') . '

<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead">Results</td>
	</tr>
	<tr>
		<td>There are total <b>' . ts_nf ($count) . '</b> torrents has been fixed. ' . ($totalpages < $nextpage ? 'All Torrents <b>' . ts_nf ($results) . '</b> has been fixed!' : 'Click <a href="' . $_this_script_ . '&page=' . $nextpage . '">here</a> to fix another torrents or wait 10 seconds.') . '</td>
	</tr>
</table>
';
  ob_flush ();
  flush ();
  stdfoot ();
  ob_flush ();
  flush ();
?>
