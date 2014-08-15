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

  define ('U_VERSION', '0.4 by xam');
  include_once INC_PATH . '/functions_ratio.php';
  $combine = false;
  if ((isset ($_GET['uploader']) AND is_valid_id ($_GET['uploader'])))
  {
    $uploader = intval ($_GET['uploader']);
    $combine = true;
  }

  $user = array ();
  $query = sql_query ('SELECT id, name, added, owner, seeders, leechers FROM torrents');
  while ($uploads = mysql_fetch_assoc ($query))
  {
    ++$user['totaltorrents'][$uploads['owner']];
    $user['lastupload'][$uploads['owner']] = ($combine ? $user['lastupload'][$uploads['owner']] : '') . '<a href="' . $BASEURL . '/details.php?id=' . $uploads['id'] . '"><strong>' . $uploads['name'] . '</strong></a> on ' . my_datee ($dateformat, $uploads['added']) . ' ' . my_datee ($timeformat, $uploads['added']) . ' Seeders: ' . ts_nf ($uploads['seeders']) . ' Leechers: ' . ts_nf ($uploads['leechers']) . '<br />';
  }

  $what = ((isset ($_GET['type']) AND $_GET['type'] == 2) ? 'g.canupload = \'yes\'' : 'u.usergroup=' . UC_UPLOADER);
  if ($combine)
  {
    $what = 'u.id=' . sqlesc ($_GET['uploader']);
  }

  include_once $rootpath . '/admin/include/global_config.php';
  $query = sql_query ('' . 'SELECT u.id FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' AND ' . $what);
  $total_count = mysql_num_rows ($query);
  list ($pagertop, $pagerbottom, $limit) = pager ($config['uploaders']['query_limit'], $total_count, $_this_script_ . '&amp;' . ($_GET['type'] ? 'type=' . intval ($_GET['type']) . '&amp;' : '') . ($uploader ? 'uploader=' . $uploader . '&amp;' : ''));
  stdhead ($SITENAME . ' Uploader List');
  echo $pagertop;
  _form_header_open_ ($SITENAME . ' Uploader List (<a href="' . $_this_script_ . '&amp;type=1">Show usergroup = UC_UPLOADER</a> **** <a href="' . $_this_script_ . '&amp;type=2">Show canupload = yes only</a>)', 4);
  echo '
<tr>
	<td class="subheader" width="20%" align="left">Uploader / Ratio</td>
	<td class="subheader" width="14%" align="center">Last Access</td>
	<td class="subheader" width="6%" align="center">Uploads</td>
	<td class="subheader" width="60%" align="left">Last Upload' . ($combine ? 's' : '') . '</td>
</tr>
';
  $uploaders = array ();
  $query = sql_query ('' . 'SELECT u.username, u.id, u.last_access, u.uploaded, u.downloaded, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' AND ' . $what . ' ' . $limit);
  while ($res = mysql_fetch_assoc ($query))
  {
    $info = ($user['lastupload'][$res['id']] ? $user['lastupload'][$res['id']] : 'There is no uploaded torrent detected for this user!');
    echo '
	<tr>
	<td align="left" valign="top"><a href="' . $BASEURL . '/userdetails.php?id=' . $res['id'] . '">' . get_user_color ($res['username'], $res['namestyle']) . '</a> (' . get_user_ratio ($res['uploaded'], $res['downloaded']) . ') (<a href="' . $_this_script_ . '&amp;uploader=' . $res['id'] . '">show all</a>)</td>
	<td align="center" valign="top">' . my_datee ($dateformat, $res['last_access']) . ' ' . my_datee ($timeformat, $res['last_access']) . '</td>
	<td align="center" valign="top">' . ts_nf ($user['totaltorrents'][$res['id']]) . '</td>
	<td align="left" valign="top">' . $info . '</td>
	</tr>
	';
  }

  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
