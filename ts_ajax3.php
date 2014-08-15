<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '', $error = false)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    if ($error)
    {
      exit ('<error>' . $message . '</error>');
    }

    exit ($message);
  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  require 'global.php';
  gzip ();
  dbconn ();
  sleep (1);
  define ('TS_AJAX_VERSION', '1.1.9 ');
  define ('NcodeImageResizer', true);
  if (((!defined ('IN_SCRIPT_TSSEv56') OR strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST') OR !$CURUSER))
  {
    show_msg ($lang->global['nopermission']);
  }

  $lang->load ('browse');
  $type = (isset ($_POST['type']) ? trim ($_POST['type']) : 'new');
  $extra_query = ($type == 'free' ? ' AND free=\'yes\'' : ($type == 'silver' ? ' AND silver=\'yes\'' : ($type == 'sticky' ? ' AND sticky=\'yes\'' : '')));
  $Title = ($type == 'free' ? $lang->browse['show_free_torrents'] : ($type == 'silver' ? $lang->browse['show_silver_torrents'] : ($type == 'sticky' ? $lang->browse['show_recommend_torrents'] : $lang->browse['show_latest'])));
  $query = sql_query ('' . 'SELECT id, name, seeders, leechers, t_image FROM torrents WHERE t_image != \'\' AND visible = \'yes\' AND banned = \'no\'' . $extra_query . ' ORDER BY added DESC LIMIT ' . $i_torrent_limit);
  $str = '
<table width="100%" border="1" cellspacing="0" cellpadding="5" align="center">
	<tr>
		<td class="thead">' . ts_collapse ('showtorrents') . '
			<div align="center">
				<strong>
					' . $Title . '
				</strong>
			</div>
		</td>	' . ts_collapse ('showtorrents', 2) . '
	</tr>
	<tr>
		<td align="center">';
  if (0 < mysql_num_rows ($query))
  {
    while ($row = mysql_fetch_assoc ($query))
    {
      $seolink = ts_seo ($row['id'], $row['name'], 's');
      $fullname = htmlspecialchars_uni ($row['name']) . ' (' . $lang->browse['sortby4'] . ': ' . ts_nf ($row['seeders']) . ' ' . $lang->browse['sortby5'] . ': ' . ts_nf ($row['leechers']) . ')';
      $str .= '
				<span style="padding-right: 6px;"><a href="' . $seolink . '"><img src="' . htmlspecialchars_uni ($row['t_image']) . '" width="125" height="125" alt="' . $fullname . '" title="' . $fullname . '" /></a></span>';
    }
  }
  else
  {
    $str .= $lang->browse['tryagain'];
  }

  $str .= '</td>
	</tr>
</table>
<br />';
  show_msg ($str);
?>
