<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  include_once INC_PATH . '/functions_ratio.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('VS_VERSION', '1.3.8 ');
  if ($usergroups['cansnatch'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  $is_mod = is_mod ($usergroups);
  if (($snatchmod == 'no' AND !$is_mod))
  {
    stderr ($lang->global['error'], $lang->global['notavailable']);
  }

  $lang->load ('viewsnatches');
  $id = intval ($_GET['id']);
  int_check ($id, true);
  if ((isset ($_GET['delete']) AND $usergroups['cansettingspanel'] == 'yes'))
  {
    $userid = intval ($_GET['userid']);
    if (is_valid_id ($userid))
    {
      sql_query ('' . 'DELETE FROM snatched WHERE userid = ' . $userid . ' AND torrentid = ' . $id);
    }
  }

  ($res3 = sql_query ('select count(snatched.id) from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.finished=\'yes\' AND snatched.torrentid = ' . sqlesc ($id)) OR sqlerr (__FILE__, 51));
  $row = mysql_fetch_array ($res3);
  $count = $row[0];
  $torrentsperpage = ($CURUSER['torrentsperpage'] != 0 ? intval ($CURUSER['torrentsperpage']) : $ts_perpage);
  $res3 = sql_query ('SELECT torrents.name,torrents.ts_external,categories.vip AS vip FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = ' . sqlesc ($id));
  $arr3 = mysql_fetch_array ($res3);
  if (($usergroups['canviewviptorrents'] != 'yes' AND $arr3['vip'] == 'yes'))
  {
    stderr ($lang->global['error'], $lang->global['viptorrent'], false);
  }

  if ($arr3['ts_external'] == 'yes')
  {
    stderr ($lang->global['error'], $lang->viewsnatches['external']);
  }

  stdhead ($lang->viewsnatches['headmessage']);
  if ($is_mod)
  {
    $modsearch = '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?id=' . $id . '">
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">
				Search User in Snatchlist (Moderator Tool)
			</td>
		</tr>
		<tr>
			<td class="subheader">
				Use this tool to search a specific user in Snatch List. Note: Min. 3 chars allowed to search an user.
			</td>
		</tr>
		<tr>
			<td>
				<span style="float: right;"><a href="' . $BASEURL . '/takereseed.php?reseedid=' . $id . '">Click here to Request a Reseed</a> - <a href="' . $BASEURL . '/admin/index.php?act=ts_hit_and_run&amp;torrentid=' . $id . '">Click here to Detect Hit & Run on this torrent</a></span>
				Username: <input type="text" name="username" size="15"> <input type="checkbox" value="yes" name="showunfinished"> Search Unfinished? <input type="submit" value="search">
			</td>
		</tr>
	</table>
	</form>
	';
    echo $modsearch . '<br />';
  }

  $type = 'DESC';
  $orderby = 'snatched.completedat';
  $typelink = '&amp;type=ASC';
  if ((isset ($_GET['type']) AND $_GET['type'] == 'DESC'))
  {
    $type = 'ASC';
    $typelink = '&amp;type=DESC';
  }

  if (isset ($_GET['order']))
  {
    $order = $_GET['order'];
    $allowed = array ('username', 'uploaded', 'downloaded', 'completedat', 'last_action', 'seeder', 'seedtime', 'leechtime', 'connectable');
    if (in_array ($order, $allowed))
    {
      if ($order == 'username')
      {
        $orderby = 'users.username';
      }
      else
      {
        $orderby = 'snatched.' . $order;
      }

      $orderlink = '' . '&amp;order=' . $order;
    }
  }

  $quicklink = $_SERVER['SCRIPT_NAME'] . '?id=' . $id . '&amp;type=' . $type . '&amp;order=';
  list ($pagertop, $pagerbottom, $limit) = pager ($torrentsperpage, $count, $_SERVER['SCRIPT_NAME'] . '?id=' . $id . $typelink . $orderlink . '&amp;');
  $finishquery = 'snatched.finished=\'yes\' AND ';
  $showpager = true;
  if (($_SERVER['REQUEST_METHOD'] == 'POST' AND $is_mod))
  {
    function validusername ($username)
    {
      if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
      {
        return true;
      }

      return false;
    }

    if ((!empty ($_POST['username']) AND (2 < strlen ($_POST['username']) AND validusername ($_POST['username']))))
    {
      $username = trim ($_POST['username']);
      $orderby = 'users.username';
      $type = 'ASC';
      $extraquery = ' AND users.username LIKE \'%' . mysql_real_escape_string ($username) . '%\'';
      if ($_POST['showunfinished'] == 'yes')
      {
        $finishquery = '';
      }

      $showpager = false;
    }
  }

  if ($showpager)
  {
    echo $pagertop;
  }

  print '<table border=1 cellspacing=0 cellpadding=5 align=center width=100%>
';
  print '<tr><td class="thead" colspan="13">' . sprintf ($lang->viewsnatches['snatchdetails'], '<a href=details.php?id=' . $id . '>' . $arr3['name']) . '</td></tr>';
  print '<tr><td class=subheader align=center><a href=\'' . $quicklink . 'username\'>' . $lang->viewsnatches['username'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'uploaded\'>' . $lang->viewsnatches['uploaded'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'downloaded\'>' . $lang->viewsnatches['downloaded'] . '</a></td><td class=subheader align=center>' . $lang->viewsnatches['ratio'] . '</td><td class=subheader align=center><a href=\'' . $quicklink . 'completedat\'>' . $lang->viewsnatches['finished'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'last_action\'>' . $lang->viewsnatches['lastaction'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'seeder\'>' . $lang->viewsnatches['seeding'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'seedtime\'>' . $lang->viewsnatches['seedtime'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'leechtime\'>' . $lang->viewsnatches['leechtime'] . '</a></td><td class=subheader align=center><a href=\'' . $quicklink . 'connectable\'>' . $lang->viewsnatches['connectable'] . '</a></td>
<td class=subheader align=center colspan=3></td></tr>';
  $res = sql_query ('' . 'select users.donor, users.enabled, users.warned, users.leechwarn, users.options, users.last_login, users.last_access, users.username, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, snatched.seedtime, snatched.leechtime, snatched.upspeed, snatched.downspeed, snatched.connectable, snatched.port, snatched.completedat, snatched.last_action, snatched.agent, snatched.seeder, snatched.userid, snatched.uploaded, snatched.downloaded, usergroups.namestyle from snatched inner join users on snatched.userid = users.id LEFT JOIN ts_u_perm p ON (users.id=p.userid) inner join torrents on snatched.torrentid = torrents.id inner join usergroups on users.usergroup = usergroups.gid where ' . $finishquery . 'snatched.torrentid = ' . sqlesc ($id) . $extraquery . ('' . ' ORDER BY ' . $orderby . ' ' . $type . ' ' . $limit));
  include_once INC_PATH . '/functions_icons.php';
  $dt = get_date_time (gmtime () - TS_TIMEOUT);
  require_once INC_PATH . '/functions_mkprettytime.php';
  while ($arr = mysql_fetch_array ($res))
  {
    if (($arr['connectable'] == 'yes' AND $arr['seeder'] == 'yes'))
    {
      $connectable = $lang->global['greenyes'];
    }
    else
    {
      $connectable = '<font color=red>' . $lang->viewsnatches['waiting'] . '</font>';
    }

    $port = 0 + $arr['port'];
    if (0 < $arr['downloaded'])
    {
      $ratio2 = number_format ($arr['uploaded'] / $arr['downloaded'], 2);
      $ratio2 = '<font color=' . get_ratio_color ($ratio2) . ('' . '>' . $ratio2 . '</font>');
    }
    else
    {
      if (0 < $arr['uploaded'])
      {
        $ratio2 = 'Inf.';
      }
      else
      {
        $ratio2 = '---';
      }
    }

    $uploaded2 = mksize ($arr['uploaded']);
    $downloaded2 = mksize ($arr['downloaded']);
    $highlight = ($CURUSER['id'] == $arr['userid'] ? ' class=highlight' : '');
    $last_access = $arr['last_access'];
    $userid = 0 + $arr['userid'];
    if (((preg_match ('#B1#is', $arr['options']) AND !$is_mod) AND $userid != $CURUSER['id']))
    {
      $last_access = $arr['last_login'];
      $onoffpic = '<img src=' . $pic_base_url . 'user_offline.gif border=0>';
    }
    else
    {
      if (($dt < $last_access OR $userid == $CURUSER['id']))
      {
        $onoffpic = '<img src=' . $pic_base_url . 'user_online.gif border=0>';
      }
      else
      {
        $onoffpic = '<img src=' . $pic_base_url . 'user_offline.gif border=0>';
      }
    }

    $username = get_user_color ($arr['username'], $arr['namestyle']);
    $seedtime = mkprettytime ($arr['seedtime']);
    $leechtime = mkprettytime ($arr['leechtime']);
    $last_action = my_datee ($dateformat, $arr['last_action']) . '<br />' . my_datee ($timeformat, $arr['last_action']);
    $completedat = ($arr['completedat'] != '0000-00-00 00:00:00' ? my_datee ($dateformat, $arr['completedat']) . '<br />' . my_datee ($timeformat, $arr['completedat']) : 'Unfinished');
    $upspeed = (0 < $arr['upspeed'] ? mksize ($arr['upspeed']) : (0 < $arr['seedtime'] ? mksize ($arr['uploaded'] / ($arr['seedtime'] + $arr['leechtime'])) : mksize (0)));
    $downspeed = (0 < $arr['downspeed'] ? mksize ($arr['downspeed']) : (0 < $arr['leechtime'] ? mksize ($arr['downloaded'] / $arr['leechtime']) : mksize (0)));
    print '' . '<tr' . $highlight . '><td align=center class=smalltext><a href=userdetails.php?id=' . $userid . '><b>' . $username . '</b></a>' . get_user_icons ($arr) . ('' . '</td><td align=center class=smalltext>' . $uploaded2 . '<br />' . $upspeed . '/s</td><td align=center class=smalltext>' . $downloaded2 . '<br />' . $downspeed . '/s</td><td align=center class=smalltext>' . $ratio2 . '</td><td align=center class=smalltext>' . $completedat . '</td><td align=center class=smalltext>' . $last_action . '</td><td align=center class=smalltext>') . ($arr['seeder'] == 'yes' ? $lang->global['greenyes'] . ' ' . $lang->viewsnatches['port'] . ' ' . $port : $lang->global['redno']) . '<br />' . htmlspecialchars_uni (substr ($arr['agent'], 0, 20)) . ('' . '</td><td align=center class=smalltext>' . $seedtime . '</td><td align=center class=smalltext>' . $leechtime . '</td><td align=center class=smalltext>' . $connectable . '</td>
	<td align=center colspan=3 class=smalltext>' . $onoffpic . ' <a href=sendmessage.php?receiver=' . $userid . '><img src=') . $pic_base_url . ('' . 'pm.gif border=0></a><br /><a href=report.php?action=reportuser&reportid=' . $userid . '><img border=0 src=') . $pic_base_url . 'report.gif></a>' . ($usergroups['cansettingspanel'] == 'yes' ? '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?id=' . $id . '&userid=' . $arr['userid'] . '&delete=true">delete</a>' : '') . '</td></tr>
';
  }

  print '</table>
';
  if ($showpager)
  {
    echo $pagerbottom;
  }

  stdfoot ();
?>
