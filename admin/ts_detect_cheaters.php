<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_torrent_flags ($torrents)
  {
    global $BASEURL;
    global $pic_base_url;
    global $lang;
    global $rootpath;
    $lang->load ('browse');
    $isfree = ($torrents['free'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'freedownload.gif" class="inlineimg" alt="' . $lang->browse['freedownload'] . '" title="' . $lang->browse['freedownload'] . '" />' : '');
    $issilver = ($torrents['silver'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'silverdownload.gif" class="inlineimg" alt="' . $lang->browse['silverdownload'] . '" title="' . $lang->browse['silverdownload'] . '" />' : '');
    $isrequest = ($torrents['isrequest'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'isrequest.gif" class="inlineimg" alt="' . $lang->browse['requested'] . '" title="' . $lang->browse['requested'] . '" />' : '');
    $isnuked = ($torrents['isnuked'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'isnuked.gif" class="inlineimg" alt="' . sprintf ($lang->browse['nuked'], $torrents['WhyNuked']) . '" title="' . sprintf ($lang->browse['nuked'], $torrents['WhyNuked']) . '" />' : '');
    $issticky = ($torrents['sticky'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'sticky.gif" alt="' . $lang->browse['sticky'] . '" title="' . $lang->browse['sticky'] . '" />' : '');
    $anonymous = ($torrents['anonymous'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'chatpost.gif" alt="Anonymous torrent" title="Anonymous torrent" />' : '');
    $isbanned = ($torrents['banned'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'disabled.gif" alt="Banned torrent" title="Banned torrent" />' : '');
    $isexternal = (($torrents['ts_external'] == 'yes' AND $_GET['tsuid'] != $torrents['id']) ? '<a onclick=\'ts_show("loading-layer")\' href=\'' . $BASEURL . '/include/ts_external_scrape/ts_update.php?id=' . intval ($torrents['id']) . '\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'external.gif\' class=\'inlineimg\'  border=\'0\' alt=\'' . $lang->browse['update'] . '\' title=\'' . $lang->browse['update'] . '\' /></a>' : ((isset ($_GET['tsuid']) AND $_GET['tsuid'] == $torrents['id']) ? '<img src=\'' . $BASEURL . '/' . $pic_base_url . 'input_true.gif\' class=\'inlineimg\' border=\'0\' alt=\'' . $lang->browse['updated'] . '\' title=\'' . $lang->browse['updated'] . '\' />' : ''));
    $isvisible = ($torrents['visible'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'input_true.gif" class="inlineimg" alt="Active Torrent" title="Active Torrent" />' : '<img src="' . $BASEURL . '/' . $pic_base_url . 'input_error.gif" class="inlineimg" alt="Dead Torrent" title="Dead Torrent" />');
    $isdoubleupload = ($torrents['doubleupload'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'x2.gif" alt="' . $lang->browse['dupload'] . '" title="' . $lang->browse['dupload'] . '" class="inlineimg" />' : '');
    $isclosed = ($torrents['allowcomments'] == 'no' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'commentpos.gif" alt="Closed for Comment Posting" title="Closed for Comment Posting" class="inlineimg" />' : '');
    return '' . $isvisible . ' ' . $isfree . ' ' . $issilver . ' ' . $isrequest . ' ' . $isnuked . ' ' . $issticky . ' ' . $isexternal . ' ' . $anonymous . ' ' . $isbanned . ' ' . $isdoubleupload . ' ' . $isclosed;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('AU_VERSION', '0.6 by xam');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  $torrentsperpage = ($CURUSER['torrentsperpage'] != 0 ? intval ($CURUSER['torrentsperpage']) : $ts_perpage);
  $query = sql_query ('SELECT s.port, s.ip, s.last_action, s.startdat, s.agent, s.userid, s.uploaded, s.downloaded, s.torrentid, t.seeders, t.leechers, t.name, u.downloaded as usercurrentdownload, u.uploaded as usercurrentupload, u.username, g.namestyle, g.title FROM snatched s LEFT JOIN torrents t ON (s.torrentid=t.id) INNER JOIN users u ON (u.id=s.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.downloaded = 0 AND s.uploaded > 0 AND s.leechtime=0 AND u.enabled=\'yes\' AND u.usergroup NOT IN (' . UC_BANNED . ')');
  list ($pagertop, $pagerbottom, $limit) = pager ($torrentsperpage, mysql_num_rows ($query), $_this_script_ . '&amp;');
  ($query = sql_query ('SELECT s.port, s.ip, s.last_action, s.startdat, s.agent, s.userid, s.uploaded, s.downloaded, s.torrentid, t.name, t.free, t.silver, t.isrequest, t.isnuked, t.sticky, t.anonymous, t.banned, t.ts_external, t.visible, t.doubleupload, t.allowcomments, t.seeders, t.leechers, u.downloaded as usercurrentdownload, u.uploaded as usercurrentupload, u.username, u.added, u.options, u.avatar, u.last_access, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle, g.title FROM snatched s LEFT JOIN torrents t ON (s.torrentid=t.id) INNER JOIN users u ON (u.id=s.userid) LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.downloaded = 0 AND s.uploaded > 0 AND s.leechtime=0 AND u.enabled=\'yes\' AND u.usergroup NOT IN (' . UC_BANNED . ('' . ') ORDER BY u.username ' . $limit)) OR sqlerr (__FILE__, 45));
  $lang->load ('tsf_forums');
  include_once INC_PATH . '/functions_icons.php';
  include_once INC_PATH . '/functions_ratio.php';
  stdhead ();
  echo $pagertop;
  _form_header_open_ ('TS Detect Cheaters (Columns with a <span class="highlight"><font color="black">different background color</font></span> means: 69% Cheat!)', 5);
  echo '
<tr>
	<td class="subheader">Username</td>
	<td class="subheader">Torrent Name</td>
	<td class="subheader">Uploaded/Port</td>
	<td class="subheader">Agent/IP</td>
	<td class="subheader">Time</td>
</tr>
';
  while ($s = mysql_fetch_assoc ($query))
  {
    $sticky = (($s['usercurrentdownload'] == 0 AND $s['free'] == 'no') ? true : false);
    $lastseen = my_datee ($dateformat, $s['last_access']) . ' ' . my_datee ($timeformat, $s['last_access']);
    $downloaded = mksize ($s['usercurrentdownload']);
    $uploaded = mksize ($s['usercurrentupload']);
    $ratio = get_user_ratio ($s['usercurrentupload'], $s['usercurrentdownload']);
    $ratio = str_replace ('\'', '\\\'', $ratio);
    $tooltip = '<b>' . $lang->tsf_forums['jdate'] . '</b>' . my_datee ($dateformat, $s['added']) . '<br />' . sprintf ($lang->tsf_forums['tooltip'], $lastseen, $downloaded, $uploaded, $ratio);
    echo '
	<tr' . ($sticky ? ' class="highlight"' : '') . '>
		<td><a href="' . $BASEURL . '/userdetails.php?id=' . $s['userid'] . '" target="_blank" onmouseover="ddrivetip(\'' . $tooltip . '\', 200)"; onmouseout="hideddrivetip()">' . get_user_color ($s['username'], $s['namestyle']) . '</a><br />' . $s['title'] . '</td>
		<td><a href="' . $BASEURL . '/details.php?id=' . $s['torrentid'] . '" target="_blank">' . cutename ($s['name'], 80) . '</a> ' . get_torrent_flags ($s) . '<br />
		<b>Seeders:</b> ' . ts_nf ($s['seeders']) . ' / <b>Leechers:</b> ' . ts_nf ($s['leechers']) . '</td>
		<td>' . mksize ($s['uploaded']) . '<br /><b>Port:</b> ' . intval ($s['port']) . '</td>
		<td>' . htmlspecialchars_uni ($s['agent']) . '<br /><b>IP</b>: ' . htmlspecialchars_uni ($s['ip']) . '</td>
		<td><b>Started at:</b> ' . my_datee ($dateformat, $s['startdat']) . ' ' . my_datee ($timeformat, $s['startdat']) . '<br />
		<b>Last Action:</b> ' . my_datee ($dateformat, $s['last_action']) . ' ' . my_datee ($timeformat, $s['last_action']) . '</td>
	</tr>
	';
  }

  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
