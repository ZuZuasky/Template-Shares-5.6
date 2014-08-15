<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '', $error = true, $color = 'red', $strong = true, $extra = '', $extra2 = '')
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

    exit ($extra . (!empty ($color) ? '<font color="' . $color . '">' : '') . ($strong ? '<strong>' : '') . $message . ($strong ? '</strong>' : '') . (!empty ($color) ? '</font>' : '') . $extra2);
  }

  function maketable ($res)
  {
    global $CURUSER;
    global $BASEURL;
    global $pic_base_url;
    global $table_cat;
    global $lang;
    global $dateformat;
    global $timeformat;
    $ret = '<table class=\'main\' border=\'1\' cellspacing=\'0\' cellpadding=\'0\' width=\'100%\'>' . '<tr><td class=\'colhead\' align=\'center\' width=\'36\'>' . $lang->global['type'] . '</td><td class=\'colhead\' style=\'padding: 0px 0px 0px 2px;\'>' . $lang->global['name'] . '</td><td class=\'colhead\' align=\'center\'>' . $lang->global['size'] . '</td><td class=\'colhead\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'seeders.gif\'></td><td class=\'colhead\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'leechers.gif\'></td><td class=\'colhead\' align=\'center\'>' . $lang->global['uploaded'] . '</td>
' . '<td class=\'colhead\' align=\'center\'>' . $lang->global['downloaded'] . '</td><td class=\'colhead\' align=\'center\'>' . $lang->global['ratio'] . '</td></tr>
';
    while ($arr = mysql_fetch_array ($res))
    {
      if (0 < $arr['downloaded'])
      {
        $ratio = number_format ($arr['uploaded'] / $arr['downloaded'], 2);
        $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
      }
      else
      {
        if (0 < $arr['uploaded'])
        {
          $ratio = 'Inf.';
        }
        else
        {
          $ratio = '---';
        }
      }

      $catimage = htmlspecialchars_uni ($arr['image']);
      $catname = htmlspecialchars_uni ($arr['catname']);
      $size = mksize ($arr['size']);
      $uploaded = mksize ($arr['uploaded']);
      $downloaded = mksize ($arr['downloaded']);
      $seeders = ts_nf ($arr['seeders']);
      $leechers = ts_nf ($arr['leechers']);
      $last_action_date = my_datee ($dateformat, $arr['last_action']) . ' ' . my_datee ($timeformat, $arr['last_action']);
      $ret .= '<tr><td width=\'36\' heigth=\'48\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . $catimage . ('' . '\' alt=\'' . $catname . '\'></td>
') . ('' . '<td style=\'padding: 0px 0px 0px 2px;\' width=\'300\'><a href=\'' . $BASEURL . '/details.php?id=' . $arr['torrent'] . '\' alt=\'') . $arr['torrentname'] . '\' title=\'' . $arr['torrentname'] . '\'><b>' . cutename ($arr['torrentname'], 60) . ('' . '</b></a><br />' . $last_action_date . '</td><td align=\'center\'>' . $size . '</td><td align=\'center\'>' . $seeders . '</td><td align=\'center\'>' . $leechers . '</td><td align=\'center\'>' . $uploaded . '</td>
') . ('' . '<td align=\'center\'>' . $downloaded . '</td><td align=\'center\'>' . $ratio . '</td></tr>
');
    }

    $ret .= '</table>
';
    return $ret;
  }

  function usersnatches ($res)
  {
    global $lang;
    global $BASEURL;
    global $pic_base_url;
    global $table_cat;
    $table = '<table class="main" border="1" cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<td class="colhead" align="center" width="36">' . $lang->global['type'] . '</td>
	<td class="colhead" style="padding: 0px 0px 0px 2px;">' . $lang->global['name'] . '</td>
	<td class="colhead">' . $lang->global['uploaded'] . '</td>
	<td class="colhead">' . $lang->global['downloaded'] . '</td>
	<td class="colhead" align="center">' . $lang->global['ratio'] . '</td>
	<td class="colhead">' . $lang->userdetails['seedtime'] . '</td>
	<td class="colhead">' . $lang->userdetails['leechtime'] . '</td>
	<td class="colhead">' . $lang->userdetails['completed'] . '</td>
	</tr>';
    require_once INC_PATH . '/functions_mkprettytime.php';
    while ($arr = mysql_fetch_assoc ($res))
    {
      $upspeed = (0 < $arr['upspeed'] ? mksize ($arr['upspeed']) : (0 < $arr['seedtime'] ? mksize ($arr['uploaded'] / ($arr['seedtime'] + $arr['leechtime'])) : mksize (0)));
      $downspeed = (0 < $arr['downspeed'] ? mksize ($arr['downspeed']) : (0 < $arr['leechtime'] ? mksize ($arr['downloaded'] / $arr['leechtime']) : mksize (0)));
      $ratio = (0 < $arr['downloaded'] ? number_format ($arr['uploaded'] / $arr['downloaded'], 2) : (0 < $arr['uploaded'] ? 'Inf.' : '---'));
      $ratio = '<font color=\'' . get_ratio_color ($sr) . ('' . '\'>' . $ratio . '</font>');
      $completed = sprintf ('%.2f%%', 100 * (1 - $arr['to_go'] / $arr['size']));
      $table .= '<tr>
		<td width=\'36\' heigth=\'48\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . htmlspecialchars_uni ($arr['catimg']) . '\' alt=\'' . htmlspecialchars_uni ($arr['catname']) . ('' . '\'></td>
		<td><a href=\'' . $BASEURL . '/details.php?id=' . $arr['torrentid'] . '\' alt=\'') . $arr['torrentname'] . '\' title=\'' . $arr['torrentname'] . '\'><b>' . cutename ($arr['torrentname'], 10) . '</b></a></td>
		<td>' . mksize ($arr['uploaded']) . ('' . '<br />' . $upspeed . '/s</td>
		<td>') . mksize ($arr['downloaded']) . ('' . '<br />' . $downspeed . '/s</td>
		<td align=\'center\'>' . $ratio . '</td>
		<td>') . mkprettytime ($arr['seedtime']) . '</td>
		<td>' . mkprettytime ($arr['leechtime']) . ('' . '</td>
		<td>' . $completed . '</td>
		</tr>
');
    }

    $table .= '</table>
';
    return $table;
  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  require 'global.php';
  gzip ();
  dbconn ();
  define ('TS_AJAX_VERSION', '1.1.9 ');
  define ('NcodeImageResizer', true);
  if (((!defined ('IN_SCRIPT_TSSEv56') OR strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST') OR !$CURUSER))
  {
    exit ();
  }

  include INC_PATH . '/functions_ratio.php';
  $lang->load ('userdetails');
  $userid = (isset ($_POST['userid']) ? intval ($_POST['userid']) : intval ($CURUSER['userid']));
  $IsStaff = is_mod ($usergroups);
  $SameUser = ($userid == $CURUSER['id'] ? true : false);
  if (!is_valid_id ($userid))
  {
    exit ();
  }
  else
  {
    if ((!$SameUser AND $usergroups['canviewotherprofile'] != 'yes'))
    {
      exit ();
    }
  }

  if ((isset ($_POST['what']) AND $_POST['what'] == 'showuploaded'))
  {
    $ultorrentscount = tsrowcount ('id', 'torrents', 'owner=' . $userid);
    if (($ultorrentscount AND 0 < $ultorrentscount))
    {
      $r = mysql_query ('SELECT t.id, t.name, t.seeders, t.leechers, t.times_completed, t.category, t.added, t.anonymous, t.owner, c.name as categoryname, c.image FROM torrents t INNER JOIN categories c ON (t.category=c.id) WHERE t.owner=' . sqlesc ($userid) . ' ORDER BY t.added DESC');
      $torrents = '
		<table class=\'main\' border=\'1\' cellspacing=\'0\' cellpadding=\'0\' width=\'100%\'>
' . '<tr><td class=\'colhead\' align=\'center\' width=\'36\'>' . $lang->global['type'] . '</td><td class=\'colhead\' align=\'left\' style=\'padding: 0px 0px 0px 2px;\'>' . $lang->global['name'] . '</td><td class=\'colhead\' align=\'center\'>' . $lang->global['snatched'] . '</td><td class=\'colhead\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'seeders.gif\'></td><td class=\'colhead\'  align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'leechers.gif\'></td></tr>
';
      while ($a = mysql_fetch_array ($r))
      {
        $orj_name_ = $a['name'];
        $t_added = my_datee ($dateformat, $a['added']) . ' ' . my_datee ($timeformat, $a['added']);
        $a['name'] = htmlspecialchars_uni ($a['name']);
        $cat = '<img src="' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . $a['image'] . ('' . '" alt="' . $a['categoryname'] . '" title="' . $a['categoryname'] . '">');
        $torrents .= '' . '<tr><td align=\'center\' width=\'36\' heigth=\'48\'>' . $cat . '</td><td align=\'left\' style=\'padding: 0px 0px 0px 2px;\'><a href=\'' . $BASEURL . '/details.php?id=' . $a['id'] . '\' alt=\'' . $a['name'] . '\' title=\'' . $a['name'] . '\'><b>' . cutename ($orj_name_, 80) . ('' . '</b></a><br />' . $t_added . '</td>') . ('' . '<td align=\'center\'><a href=\'' . $BASEURL . '/viewsnatches.php?id=') . $a['id'] . '\'><b>' . ts_nf ($a['times_completed']) . ' x </b>' . $lang->global['times'] . '</a></td><td align=\'center\'>' . ts_nf ($a['seeders']) . '</td><td align=\'center\'>' . ts_nf ($a['leechers']) . '</td></tr>
';
      }

      $torrents .= '</table>';
    }
    else
    {
      $torrents = $lang->global['nothingfound'];
    }

    show_msg ($torrents);
    exit ();
    return 1;
  }

  if ((isset ($_POST['what']) AND $_POST['what'] == 'showcompleted'))
  {
    $sntorrentscount = tsrowcount ('id', 'snatched', 'finished=\'yes\' AND userid=' . $userid);
    if (($sntorrentscount AND 0 < $sntorrentscount))
    {
      $r = sql_query ('' . 'SELECT	s.torrentid as id,
								s.uploaded, s.downloaded, s.completedat, s.last_action,
								t.seeders, t.leechers, t.name, t.category,
								c.name as categoryname, c.image
								FROM snatched s
								LEFT JOIN torrents t ON (s.torrentid=t.id)
								INNER JOIN categories c ON (t.category=c.id)
								WHERE s.finished=\'yes\' AND s.userid=' . $userid . ' ORDER BY s.completedat DESC, s.last_action DESC');
      $completed = '<table class=\'main\' border=\'1\' cellspacing=\'0\' cellpadding=\'0\' width=\'100%\'>
' . '<tr><td class=\'colhead\' align=\'center\' width=\'36\'>' . $lang->global['type'] . '</td><td class=\'colhead\' style=\'padding: 0px 0px 0px 2px;\' align=\'left\'>' . $lang->global['name'] . '</td><td class=\'colhead\' align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'seeders.gif\'></td><td class=\'colhead\'  align=\'center\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'leechers.gif\'></td><td class=\'colhead\'  align=\'center\'>' . $lang->global['uploaded'] . '</td><td class=\'colhead\'  align=\'center\'>' . $lang->global['downloaded'] . '</td><td class=\'colhead\'  align=\'center\'>' . $lang->global['ratio'] . '</td><td class=\'colhead\'  align=\'center\'>' . $lang->global['whencompleted'] . '</td><td class=\'colhead\'  align=\'center\'>' . $lang->global['lastaction'] . '</td></tr>
';
      while ($a = mysql_fetch_array ($r))
      {
        $orj_name_ = $a['name'];
        $a['name'] = htmlspecialchars_uni ($a['name']);
        if (0 < $a['downloaded'])
        {
          $ratio = number_format ($a['uploaded'] / $a['downloaded'], 2);
          $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
        }
        else
        {
          if (0 < $a['uploaded'])
          {
            $ratio = 'Inf.';
          }
          else
          {
            $ratio = '---';
          }
        }

        $uploaded = mksize ($a['uploaded']);
        $downloaded = mksize ($a['downloaded']);
        $last_action = my_datee ($dateformat, $a['last_action']) . '<br />' . my_datee ($timeformat, $a['last_action']);
        $completedat = my_datee ($dateformat, $a['completedat']) . '<br />' . my_datee ($timeformat, $a['completedat']);
        $cat = '<img src="' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . $a['image'] . ('' . '" alt="' . $a['categoryname'] . '">');
        $completed .= '' . '<tr><td width=\'36\' heigth=\'48\' align=\'center\'>' . $cat . '</td><td align=\'left\' style=\'padding: 0px 0px 0px 2px;\'><a href=\'' . $BASEURL . '/details.php?id=' . $a['id'] . '\' alt=\'' . $a['name'] . '\' title=\'' . $a['name'] . '\'><b>' . cutename ($orj_name_, 15) . '</b></a><br />' . str_replace ('<br />', ' ', $completedat) . '</td>' . ('' . '<td align=\'center\'>' . $a['seeders'] . '</td><td align=\'center\'>' . $a['leechers'] . '</td><td align=\'center\'>' . $uploaded . '</td><td align=\'center\'>' . $downloaded . '</td><td align=\'center\'>' . $ratio . '</td><td align=\'center\'>' . $completedat . '</td><td align=\'center\'>' . $last_action . '</td>
');
      }

      $completed .= '</table>';
    }
    else
    {
      $completed = $lang->global['nothingfound'];
    }

    show_msg ($completed);
    exit ();
    return 1;
  }

  if ((isset ($_POST['what']) AND $_POST['what'] == 'showleechs'))
  {
    $petorrentscount = tsrowcount ('id', 'peers', 'seeder = \'no\' AND userid=' . $userid);
    if (($petorrentscount AND 0 < $petorrentscount))
    {
      $res = sql_query ('' . 'SELECT torrent,added,uploaded,downloaded,torrents.anonymous,torrents.owner,torrents.name as torrentname,categories.name as catname,size,image,category,seeders,leechers,peers.last_action FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id INNER JOIN categories ON torrents.category = categories.id WHERE userid=' . $userid . ' AND seeder=\'no\' ORDER by added DESC');
      $leeching = maketable ($res);
    }
    else
    {
      $leeching = $lang->global['nothingfound'];
    }

    show_msg ($leeching);
    exit ();
    return 1;
  }

  if ((isset ($_POST['what']) AND $_POST['what'] == 'showseeds'))
  {
    $seedtorrentscount = tsrowcount ('id', 'peers', 'seeder = \'yes\' AND userid=' . $userid);
    if (($seedtorrentscount AND 0 < $seedtorrentscount))
    {
      $res = sql_query ('' . 'SELECT torrent,added,uploaded,downloaded,torrents.anonymous,torrents.owner,torrents.name as torrentname,categories.name as catname,size,image,category,seeders,leechers,peers.last_action FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id INNER JOIN categories ON torrents.category = categories.id WHERE userid=' . $userid . ' AND seeder=\'yes\' ORDER by peers.last_action DESC');
      $seeding = maketable ($res);
    }
    else
    {
      $seeding = $lang->global['nothingfound'];
    }

    show_msg ($seeding);
    exit ();
    return 1;
  }

  if ((isset ($_POST['what']) AND $_POST['what'] == 'showsnatches'))
  {
    $sstorrentscount = tsrowcount ('id', 'snatched', 'userid=' . $userid);
    if (($sstorrentscount AND 0 < $sstorrentscount))
    {
      $res = sql_query ('SELECT s.*, t.name as torrentname, t.size, c.name AS catname, c.image AS catimg FROM snatched s LEFT JOIN torrents t ON (s.torrentid=t.id) INNER JOIN categories c ON (t.category = c.id) WHERE s.userid = ' . sqlesc ($userid) . ' ORDER BY s.completedat DESC');
      $snatches = usersnatches ($res);
    }
    else
    {
      $snatches = $lang->global['nothingfound'];
    }

    show_msg ($snatches);
    exit ();
    return 1;
  }

  if (((isset ($_POST['what']) AND $_POST['what'] == 'detecthost') AND $IsStaff))
  {
    $ip = trim ($_POST['ip']);
    $ip = htmlspecialchars ($ip);
    if (!empty ($ip))
    {
      $dom = @gethostbyaddr ($_POST['ip']);
      show_msg ($dom);
      return 1;
    }
  }
  else
  {
    if ((isset ($_POST['what']) AND $_POST['what'] == 'save_vmsg'))
    {
      $lang->load ('userdetails');
      $userid = (isset ($_POST['userid']) ? intval ($_POST['userid']) : 0);
      $IsStaff = is_mod ($usergroups);
      $SameUser = ($userid == $CURUSER['id'] ? true : false);
      if (!is_valid_id ($userid))
      {
        show_msg ($lang->userdetails['invaliduser']);
      }
      else
      {
        if ((!$SameUser AND $usergroups['canviewotherprofile'] != 'yes'))
        {
          show_msg ($lang->userdetails['invaliduser']);
        }
      }

      $Query = sql_query ('SELECT username, status, options FROM users WHERE id = ' . sqlesc ($userid));
      if (0 < mysql_num_rows ($Query))
      {
        $user = mysql_fetch_assoc ($Query);
      }
      else
      {
        show_msg ($lang->userdetails['invaliduser']);
      }

      if ((((preg_match ('#I3#is', $user['options']) OR preg_match ('#I4#is', $user['options'])) AND !$IsStaff) AND !$SameUser))
      {
        show_msg ($lang->userdetails['noperm']);
      }

      if ($user['status'] == 'pending')
      {
        show_msg ($lang->userdetails['pendinguser']);
      }
      else
      {
        if ((!$user['username'] OR !$user))
        {
          show_msg ($lang->userdetails['invaliduser']);
        }
      }

      if (preg_match ('#M3#is', $user['options']))
      {
        $error[] = $lang->userdetails['cerror4'];
      }
      else
      {
        if ((preg_match ('#M2#is', $user['options']) AND !$IsStaff))
        {
          $query = sql_query ('SELECT id FROM friends WHERE status=\'c\' AND userid=' . $userid . ' AND friendid=' . (int)$CURUSER['id']);
          if (mysql_num_rows ($query) < 1)
          {
            $error[] = $lang->userdetails['cerror4'];
          }
        }
      }

      if (!$error)
      {
        $text = urldecode ($_POST['message']);
        $text = strval ($text);
        if (strtolower ($shoutboxcharset) != 'utf-8')
        {
          if (function_exists ('iconv'))
          {
            $text = iconv ('UTF-8', $shoutboxcharset, $text);
          }
          else
          {
            if (function_exists ('mb_convert_encoding'))
            {
              $text = mb_convert_encoding ($text, $shoutboxcharset, 'UTF-8');
            }
            else
            {
              if (strtolower ($shoutboxcharset) == 'iso-8859-1')
              {
                $text = utf8_decode ($text);
              }
            }
          }
        }

        $msglong = strlen ($text);
        $added = time ();
        if ($usergroups['cancomment'] != 'yes')
        {
          $error[] = $lang->global['nopermission'];
        }
        else
        {
          if ((empty ($text) OR $msglong < 3))
          {
            $error[] = $lang->userdetails['cerror2'];
          }
          else
          {
            if (5000 < $msglong)
            {
              $error[] = sprintf ($lang->userdetails['cerror3'], $msglong);
            }
            else
            {
              if ((($_POST['isupdate'] AND is_valid_id ($_POST['isupdate'])) AND $IsStaff))
              {
                sql_query ('UPDATE ts_visitor_messages SET visitormsg = ' . sqlesc ($text) . ' WHERE id = ' . sqlesc (intval ($_POST['isupdate'])));
                $vmid = intval ($_POST['isupdate']);
              }
              else
              {
                sql_query ('INSERT INTO ts_visitor_messages (userid,visitorid,visitormsg,added) VALUES (' . sqlesc ($userid) . ', ' . sqlesc ($CURUSER['id']) . ',' . sqlesc ($text) . ', \'' . $added . '\')');
                $vmid = intval (mysql_insert_id ());
              }
            }
          }
        }
      }

      if (0 < count ($error))
      {
        show_msg (implode ('
', $error));
        return 1;
      }

      if ((($_POST['isupdate'] AND is_valid_id ($_POST['isupdate'])) AND $IsStaff))
      {
        $query = sql_query ('SELECT v.visitorid as id, u.username, u.avatar, g.namestyle FROM ts_visitor_messages v LEFT JOIN users u ON (v.visitorid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE v.id = ' . sqlesc (intval ($_POST['isupdate'])));
        $vm = mysql_fetch_assoc ($query);
      }
      else
      {
        $vm = $CURUSER;
        $vm['namestyle'] = $usergroups['namestyle'];
      }

      $VisitorUsername = get_user_color ($vm['username'], $vm['namestyle']);
      $vAvatar = get_user_avatar ($vm['avatar'], false, 60, 60);
      $vAdded = my_datee ($dateformat, $added) . ' ' . my_datee ($timeformat, $added);
      $vPoster = '<a href="' . $BASEURL . '/userdetails.php?id=' . $vm['id'] . '">' . $VisitorUsername . '</a>';
      $vMessage = format_comment ($text);
      $VisitorMessages = '
		<div style="float: left;">' . $vAvatar . '</div>
		<div style="overflow:auto; padding: 2px;"><p class="subheader">' . sprintf ($lang->userdetails['visitormsg5'], $vAdded, $vPoster) . '</p><div name="msg' . $vmid . '" id="msg' . $vmid . '">' . $vMessage . '</div></div>
		';
      show_msg ($VisitorMessages, false, '', false);
    }
  }

?>
