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

  define ('M_VIP_VERSION', 'v0.4 by xam');
  $do = (isset ($_GET['do']) ? trim ($_GET['do']) : (isset ($_POST['do']) ? trim ($_POST['do']) : ''));
  if ($do == 'update')
  {
    $add = trim ($_POST['add']);
    $limit = intval ($_POST['limit']);
    $userids = $_POST['userids'];
    $page = $_GET['page'] = intval ($_POST['page']);
    if (((is_array ($userids) AND 0 < count ($userids)) AND 0 < $limit))
    {
      if ($add == 'donoruntil')
      {
        $donorlengthadd = $limit * 7;
        (sql_query ('' . 'UPDATE users SET donoruntil = IF(donoruntil=\'0000-00-00 00:00:00\', ADDDATE(NOW(), INTERVAL ' . $donorlengthadd . ' DAY ), ADDDATE( donoruntil, INTERVAL ' . $donorlengthadd . ' DAY)) WHERE id IN (0,' . implode (',', $userids) . ')') OR sqlerr (__FILE__, 35));
      }
      else
      {
        if ($add == 'seedbonus')
        {
          (sql_query ('' . 'UPDATE users SET seedbonus = seedbonus + ' . $limit . ' WHERE id IN (0,' . implode (',', $userids) . ')') OR sqlerr (__FILE__, 39));
        }
        else
        {
          if ($add == 'invites')
          {
            (sql_query ('' . 'UPDATE users SET invites = invites + ' . $limit . ' WHERE id IN (0,' . implode (',', $userids) . ')') OR sqlerr (__FILE__, 43));
          }
        }
      }
    }
  }

  $where = $link = $username = '';
  if ($do == 'search_user')
  {
    $username = (isset ($_GET['username']) ? trim ($_GET['username']) : (isset ($_POST['username']) ? trim ($_POST['username']) : ''));
    if (!empty ($username))
    {
      $where = ' AND (u.username = ' . sqlesc ($username) . ' OR u.username LIKE ' . sqlesc ('%' . $username . '%') . ') ';
      $link = 'username=' . htmlspecialchars_uni ($username) . '&amp;do=search_user&amp;';
    }
  }

  ($query = sql_query ('SELECT u.*, g.namestyle, g.title FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE (u.usergroup = ' . UC_VIP . ('' . ' OR g.isvipgroup=\'yes\') AND g.cansettingspanel=\'no\' AND g.canstaffpanel=\'no\' AND issupermod=\'no\'' . $where)) OR sqlerr (__FILE__, 59));
  $count = mysql_num_rows ($query);
  list ($pagertop, $pagerbottom, $limit) = pager (20, $count, $_this_script_ . '&amp;' . $link);
  $sortby = 'u.donoruntil';
  $type = ($_GET['type'] == 'DESC' ? 'ASC' : 'DESC');
  $allowed = array ('username', 'donoruntil', 'donated', 'total_donated', 'seedbonus', 'invites');
  if (((isset ($_GET['sortby']) AND !empty ($_GET['sortby'])) AND in_array ($_GET['sortby'], $allowed)))
  {
    $sortby = 'u.' . $_GET['sortby'];
  }

  stdhead ('Manage VIP Accounts (Total ' . ts_nf ($count) . ' VIP Accounts found)');
  _form_header_open_ ('Search User');
  echo '
<form method="post" action="' . $_this_script_ . '" name="search_user">
<input type="hidden" name="do" value="search_user">
<tr>
	<td>Username: <input type="text" size="20" name="username" value="' . htmlspecialchars_uni ($username) . '"> <input type="submit" value="search user"></td>
</tr>
</form>
';
  _form_header_close_ ();
  echo '<br />' . $pagertop;
  _form_header_open_ ('Manage VIP Accounts (Total ' . ts_nf ($count) . ' VIP Accounts found)', 7);
  echo '
<form method="post" action="' . $_this_script_ . '" name="update">
<input type="hidden" name="do" value="update">
<input type="hidden" name="page" value="' . intval ($_GET['page']) . '">
<tr>
	<td class="subheader" align="left"><a href="' . $_this_script_ . '&amp;sortby=username&amp;type=' . $type . '">Username</a>' . ($_GET['sortby'] == 'username' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;sortby=donoruntil&amp;type=' . $type . '">Vip Until</a>' . ($_GET['sortby'] == 'donoruntil' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;sortby=donated&amp;type=' . $type . '">Donated</a>' . ($_GET['sortby'] == 'donated' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;sortby=total_donated&amp;type=' . $type . '">Total Donated</a>' . ($_GET['sortby'] == 'total_donated' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;sortby=seedbonus&amp;type=' . $type . '">Points</a>' . ($_GET['sortby'] == 'seedbonus' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;sortby=invites&amp;type=' . $type . '">Invites</a>' . ($_GET['sortby'] == 'invites' ? '<b><font color="red">*</font></b>' : '') . '</td>
	<td align="center" class="subheader"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'update\', this, \'group\');"></td>
</tr>
';
  ($query = sql_query ('SELECT u.*, g.namestyle, g.title FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE (u.usergroup = ' . UC_VIP . ('' . ' OR g.isvipgroup=\'yes\') AND g.cansettingspanel=\'no\' AND g.canstaffpanel=\'no\' AND issupermod=\'no\'' . $where . ' ORDER by ' . $sortby . ' ' . $type . ' ' . $limit)) OR sqlerr (__FILE__, 98));
  $lang->load ('tsf_forums');
  require_once INC_PATH . '/functions_mkprettytime.php';
  while ($vip = mysql_fetch_assoc ($query))
  {
    $lastseen = my_datee ($dateformat, $vip['last_access']) . ' ' . my_datee ($timeformat, $vip['last_access']);
    $downloaded = mksize ($vip['downloaded']);
    $uploaded = mksize ($vip['uploaded']);
    $ratio = get_user_ratio ($vip['uploaded'], $vip['downloaded']);
    $ratio = str_replace ('\'', '\\\'', $ratio);
    $tooltip = '<b>' . $lang->tsf_forums['jdate'] . '</b>' . my_datee ($dateformat, $vip['added']) . '<br />' . sprintf ($lang->tsf_forums['tooltip'], $lastseen, $downloaded, $uploaded, $ratio);
    $username = get_user_color ($vip['username'], $vip['namestyle']);
    $vipuntil = ($vip['donoruntil'] != '0000-00-00 00:00:00' ? my_datee ($dateformat, $vip['donoruntil']) . ' ' . my_datee ($timeformat, $vip['donoruntil']) . ' <br />' . mkprettytime (strtotime ($vip['donoruntil']) - gmtime ()) . ' left' : '<b><font color="red">Unlimited</font></b>');
    $donated = $vip['donated'];
    $total_donated = $vip['total_donated'];
    echo '
	<tr>
		<td><a href="' . $BASEURL . '/userdetails.php?id=' . $vip['id'] . '" target="_blank" onmouseover="ddrivetip(\'' . $tooltip . '\', 200)"; onmouseout="hideddrivetip()">' . $username . '</a></td>
		<td align="left">' . $vipuntil . '</td>
		<td align="center">' . $donated . '</td>
		<td align="center">' . $total_donated . '</td>
		<td align="center">' . $vip['seedbonus'] . '</td>
		<td align="center">' . $vip['invites'] . '</td>
		<td align="center"><input type="checkbox" name="userids[]" value="' . $vip['id'] . '" checkme="group"></td>
	</tr>
	';
  }

  echo '
<tr>
	<td colspan="7" align="right">	
	Amount: <input type="text" value="" size="5" name="limit"> 
	<select name="add">
		<option value="donoruntil">Add Extra Donor Time (weeks)</option>
		<option value="seedbonus">Give Extra Karma Points</option>
		<option value="invites">Give Extra Invites</option> 
	</select>
	<input type="submit" value="update selected accounts" class=button>
	</td>
</tr>
';
  _form_header_close_ ();
  echo '
</form>
' . $pagerbottom;
  stdfoot ();
?>
