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

  define ('TSHRD_TOOL', 'v1.2 by xam');
  include_once $rootpath . '/admin/include/global_config.php';
  include_once $rootpath . '/admin/include/staff_languages.php';
  $torrentid = ((isset ($_GET['torrentid']) AND is_valid_id ($_GET['torrentid'])) ? intval ($_GET['torrentid']) : ((isset ($_POST['torrentid']) AND is_valid_id ($_POST['torrentid'])) ? intval ($_POST['torrentid']) : 0));
  $type = ((isset ($_GET['type']) AND $_GET['type'] == 'seedtime') ? 'seedtime' : 'ratio');
  if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
  {
    $eol = '
';
  }
  else
  {
    if (strtoupper (substr (PHP_OS, 0, 3) == 'MAC'))
    {
      $eol = '
';
    }
    else
    {
      $eol = '
';
    }
  }

  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    if ((!empty ($_POST['ban']) AND 0 < count ($_POST['user_torrent_ids'])))
    {
      foreach ($_POST['user_torrent_ids'] as $work)
      {
        $worknow = explode ('|', $work);
        $userids[] = $worknow[0];
      }

      if (0 < count ($userids))
      {
        $userids = implode (',', $userids);
        $modcomment = gmdate ('Y-m-d') . ' - Banned by ' . $CURUSER['username'] . '. (TS Hit & Run Staff Tool)' . $eol;
        (sql_query ('UPDATE users SET enabled=\'no\', usergroup=\'' . UC_BANNED . '\', modcomment=CONCAT(' . sqlesc ($modcomment . '') . ('' . ', modcomment) WHERE id IN(0,' . $userids . ')')) OR sqlerr (__FILE__, 44));
      }
    }
    else
    {
      if (!empty ($_POST['warn']))
      {
        if ($_POST['do'] == 'warn')
        {
          $user_torrent_ids = $_POST['user_torrent_ids'];
          $user_torrent_ids = explode (',', $user_torrent_ids);
          require_once INC_PATH . '/functions_pm.php';
          foreach ($user_torrent_ids as $work)
          {
            $arrays = explode ('|', $work);
            (sql_query ('REPLACE INTO ts_hit_and_run (userid,torrentid,added) VALUES (' . intval ($arrays[0]) . ', ' . intval ($arrays[1]) . ', ' . TIMENOW . ')') OR sqlerr (__FILE__, 57));
            $msg = str_replace (array ('{torrentinfo}', '{torrentdownloadinfo}', '{showratio}'), array ('[URL]' . $BASEURL . '/details.php?id=' . intval ($arrays[1]) . '[/URL]', '[URL]' . $BASEURL . '/download.php?id=' . intval ($arrays[1]) . '[/URL]', $arrays[2]), $_POST['warnmessage']);
            send_pm ($arrays[0], $msg, 'Warning!');
            $modcomment = gmdate ('Y-m-d') . ' - Warned by ' . $CURUSER['username'] . '. Torrent ID: ' . intval ($arrays[1]) . ' (TS Hit & Run Staff Tool)' . $eol;
            (sql_query ('UPDATE users SET timeswarned = timeswarned + 1, modcomment=CONCAT(' . sqlesc ($modcomment . '') . ', modcomment) WHERE id = ' . intval ($arrays[0])) OR sqlerr (__FILE__, 61));
          }
        }
        else
        {
          if (0 < count ($_POST['user_torrent_ids']))
          {
            stdhead ('TS Hit & Run Detection Tool');
            _form_header_open_ ('TS Hit & Run Detection Tool');
            echo '
			<form method="post" action="' . $_this_script_ . '" name="update">
			<input type="hidden" name="do" value="warn">
			<input type="hidden" name="page" value="' . intval ($_POST['page']) . '">
			' . ($torrentid ? '<input type="hidden" name="torrentid" value="' . $torrentid . '">' : '') . '
			<input type="hidden" name="user_torrent_ids" value="' . implode (',', $_POST['user_torrent_ids']) . '">
			<tr>
			<td><b>Please Enter Warning Message:</b> (Do not change <b>{torrentinfo}</b>, <b>{showratio}</b> and <b>{torrentdownloadinfo}</b> values which will be automaticly changed by system.<br />
			<textarea name="warnmessage" rows="15" cols="110">' . $adminlang['ts_hit_and_run'] . '</textarea><br />
			<input type="reset" value="reset message"> 
			<input type="submit" value="warn users" name="warn">
			</td>
			</tr>
			</form>
			';
            _form_header_close_ ();
            stdfoot ();
            exit ();
          }
        }
      }
    }
  }

  ($query = sql_query ('SELECT userid,torrentid,added FROM ts_hit_and_run WHERE added > ' . (TIMENOW - 60 * 60 * (7 * 24))) OR sqlerr (__FILE__, 90));
  if (0 < mysql_num_rows ($query))
  {
    while ($alreadywarned = mysql_fetch_assoc ($query))
    {
      $alreadywarnedarrays[$alreadywarned['userid']][$alreadywarned['torrentid']] = $alreadywarned['added'];
    }
  }

  $extraquery = $extraquery2 = $hiddenvalues = '';
  $link = $orjlink = '';
  if (is_valid_id ($torrentid))
  {
    $extraquery = '' . ' AND s.torrentid=' . $torrentid;
    $hiddenvalues = '<input type="hidden" name="torrentid" value="' . $torrentid . '">';
    $link = $orjlink = '' . 'torrentid=' . $torrentid . '&amp;';
  }

  if (isset ($_GET['page']))
  {
    $hiddenvalues .= '<input type="hidden" name="page" value="' . intval ($_GET['page']) . '">';
  }

  $skip_usergroups = implode (',', $config['ts_hit_and_run']['skip_usergroups']);
  if (isset ($_GET['show_by_userid']))
  {
    $userid = intval ($_GET['show_by_userid']);
    if (is_valid_id ($userid))
    {
      $extraquery2 = ' AND u.id=' . sqlesc ($userid);
    }
  }

  require_once INC_PATH . '/functions_icons.php';
  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND isset ($_POST['do_search'])))
  {
    if (!empty ($_POST['keywords']))
    {
      $keywords = trim ($_POST['keywords']);
      $searchtype = intval ($_POST['searchtype']);
      switch ($searchtype)
      {
        case '1':
        {
          $extraquery2 = ' AND u.username=' . sqlesc ($keywords);
          break;
        }

        case '2':
        {
          $extraquery2 = ' AND u.id=' . sqlesc ($keywords);
          break;
        }

        case '3':
        {
          $extraquery2 = ' AND s.torrentid=' . sqlesc ($keywords);
        }
      }
    }
  }

  switch ($type)
  {
    case 'ratio':
    {
      $typequery = '(t.seeders > 0 OR t.leechers > 0) AND s.uploaded/s.downloaded < ' . $config['ts_hit_and_run']['min_share_ratio'];
      $link = ($link ? $link . '&amp;' : '') . 'type=ratio&amp;';
      break;
    }

    case 'seedtime':
    {
      $typequery = '(s.seedtime = 0 OR s.seedtime < s.leechtime)';
      $link = ($link ? $link . '&amp;' : '') . 'type=seedtime&amp;';
    }
  }

  ($query = sql_query ('SELECT s.torrentid, t.leechers, u.timeswarned, u.username FROM snatched s INNER JOIN users u ON (s.userid=u.id) LEFT JOIN torrents t ON (s.torrentid=t.id) WHERE s.finished=\'yes\' AND s.seeder=\'no\' AND (u.enabled=\'yes\' AND u.usergroup NOT IN (' . $skip_usergroups . ('' . ') AND u.status=\'confirmed\') AND t.visible=\'yes\' AND ' . $typequery . $extraquery . $extraquery2)) OR sqlerr (__FILE__, 152));
  $total_count = mysql_num_rows ($query);
  list ($pagertop, $pagerbottom, $limit) = pager ($config['ts_hit_and_run']['query_limit'], $total_count, $_this_script_ . '&amp;' . $link);
  ($query = sql_query ('SELECT s.torrentid, s.seedtime, s.leechtime, s.userid, s.downloaded, s.uploaded, t.name, t.seeders, t.leechers, u.timeswarned, u.username, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM snatched s INNER JOIN users u ON (s.userid=u.id) LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN torrents t ON (s.torrentid=t.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.finished=\'yes\' AND s.seeder=\'no\' AND (u.enabled=\'yes\' AND u.usergroup NOT IN (' . $skip_usergroups . ') AND u.status=\'confirmed\') AND t.visible=\'yes\' AND (t.seeders > 0 OR t.leechers > 0) AND s.uploaded/s.downloaded < ' . $config['ts_hit_and_run']['min_share_ratio'] . ('' . $extraquery . $extraquery2 . ' ORDER by u.timeswarned DESC ') . $limit) OR sqlerr (__FILE__, 157));
  if (0 < $total = mysql_num_rows ($query))
  {
    include_once INC_PATH . '/readconfig_cleanup.php';
    $criticallimit = $ban_user_limit - 1;
    stdhead ('TS Hit & Run Detection Tool');
    echo '
	<form method="post" action="' . $_this_script_ . '&do_search">
	<input type="hidden" name="do_search" value="1">';
    _form_header_open_ ('Search Hit and Run');
    echo '
	<tr>
		<td>
			<span style="float: right;"><a href="' . $_this_script_ . '&amp;' . $orjlink . 'page=' . intval ($_GET['page']) . '&type=seedtime">Show by Seed/Leech Time</a> - <a href="' . $_this_script_ . '&amp;' . $orjlink . 'page=' . intval ($_GET['page']) . '&type=ratio">Show by Upload/Download Ratio</a></span>
			Keyword(s): <input type="text" name="keywords" value="' . htmlspecialchars_uni ($keywords) . '"> 
			<select name="searchtype">
				<option value="3"' . ($searchtype == 3 ? ' selected="selected"' : '') . '>Search by Torrent id</option>
				<option value="2"' . ($searchtype == 2 ? ' selected="selected"' : '') . '>Search by Userid</option>
				<option value="1"' . ($searchtype == 1 ? ' selected="selected"' : '') . '>Search by Username</option>				
			</select>
			 <input type="submit" name="do_search" value="search">
		</td>
	</tr>';
    _form_header_close_ ();
    echo '
	</form>
	<br />
	';
    echo $pagertop;
    _form_header_open_ ('TS Hit & Run Detection Tool (Found: ' . $total_count . ' users. Query Limit: ' . $config['ts_hit_and_run']['query_limit'] . ')', 7);
    echo '
	<form method="post" action="' . $_this_script_ . '" name="update">
	' . $hiddenvalues . '
	<tr>
		<td class="subheader">Username</td>
		<td class="subheader">Torrent Name</td>
		<td class="subheader">Uploaded / SeedTime</td>
		<td class="subheader">Downloaded / LeechTime</td>
		<td class="subheader" align="center">Ratio</td>
		<td class="subheader" align="center">Times Warned<br />(' . $ban_user_limit . ' warn(s) = ban)</td>
		<td class="subheader" align="center"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'update\', this, \'group\');"></td>
	</tr>
	';
    require_once INC_PATH . '/functions_mkprettytime.php';
    while ($user = mysql_fetch_assoc ($query))
    {
      $totalwarns = '';
      if ($alreadywarnedarrays[$user['userid']][$user['torrentid']])
      {
        $disabled = ' disabled';
        $alreadw = ' <b>*</b>';
      }
      else
      {
        $disabled = ' checkme="group"';
        $alreadw = '';
      }

      if ($user['timeswarned'] == 0)
      {
        $totalwarns = '<font color="green"><b>';
      }
      else
      {
        if ($user['timeswarned'] == $criticallimit)
        {
          $totalwarns = '<font color="red"><b>';
        }
        else
        {
          if ($ban_user_limit <= $user['timeswarned'])
          {
            $totalwarns = '<font color="darkred"><b>';
          }
        }
      }

      $user_icons = get_user_icons ($user);
      $ratio = number_format ($user['uploaded'] / $user['downloaded'], 2);
      echo '		
		<tr>
			<td><a href="' . $_this_script_ . '&show_by_userid=' . $user['userid'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a> ' . $user_icons . '</td>
			<td><a href="' . $_this_script_ . '&torrentid=' . $user['torrentid'] . '">' . cutename ($user['name'], 80) . '</a></td>
			<td>' . mksize ($user['uploaded']) . ' (' . mkprettytime ($user['seedtime']) . ')</td>
			<td>' . mksize ($user['downloaded']) . ' (' . mkprettytime ($user['leechtime']) . ')</td>
			<td align="center"><font color="red">' . $ratio . '</font></td>
			<td align="center">' . $totalwarns . $user['timeswarned'] . '</b></font></td>
			<td align="center">			
			<input type="checkbox" name="user_torrent_ids[]" value="' . $user['userid'] . '|' . $user['torrentid'] . '|' . $ratio . '"' . $disabled . '>' . $alreadw . '</td>
		</tr>
		';
    }

    echo '
	<tr>
		<td colspan="2" align="left"><b>*</b> Already warned</td><td colspan="5" align="right"><input type="submit" value="warn selected users" name="warn"> <input type="submit" value="ban selected users" name="ban"></td>
	</tr>
	</form>';
    _form_header_close_ ();
    echo $pagerbottom;
    stdfoot ();
    return 1;
  }

  stderr ($lang->global['error'], 'Nothing found!');
?>
