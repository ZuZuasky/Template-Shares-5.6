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
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('U_VERSION', '2.4.4 ');
  if ($usergroups['canmemberlist'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  $lang->load ('users');
  $errors = array ();
  $is_mod = is_mod ($usergroups);
  $action = (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : ''));
  $orderby = 'ORDER by u.username';
  $array_where = array ();
  $array_links = array ();
  if ($action == 'do_search')
  {
    function validusername ($username)
    {
      if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
      {
        return true;
      }

      return false;
    }

    $byusername = (isset ($_POST['byusername']) ? trim ($_POST['byusername']) : (isset ($_GET['byusername']) ? trim ($_GET['byusername']) : ''));
    $username = (isset ($_POST['username']) ? trim ($_POST['username']) : (isset ($_GET['username']) ? trim ($_GET['username']) : ''));
    $gender = (isset ($_POST['gender']) ? trim ($_POST['gender']) : (isset ($_GET['gender']) ? trim ($_GET['gender']) : ''));
    $country = (isset ($_POST['country']) ? intval ($_POST['country']) : (isset ($_GET['country']) ? intval ($_GET['country']) : ''));
    $usergroup = (isset ($_POST['usergroup']) ? intval ($_POST['usergroup']) : (isset ($_GET['usergroup']) ? intval ($_GET['usergroup']) : ''));
    if (($username != '' AND validusername ($username)))
    {
      $array_links[] = 'username=' . htmlspecialchars_uni ($username);
      switch ($byusername)
      {
        case 'begins':
        {
          $array_where[] = '' . 'u.username REGEXP("^' . $username . '")';
          $array_links[] = 'byusername=begins';
          break;
        }

        case 'contains':
        {
          $array_where[] = 'u.username LIKE("' . mysql_real_escape_string ($username) . '%")';
          $array_links[] = 'byusername=contains';
        }
      }
    }

    if (($gender != 'any' AND ($gender == 'male' OR $gender == 'female')))
    {
      $whatgender = ($gender == 'male' ? 'L1' : 'L2');
      $array_links[] = '' . 'gender=' . $gender;
      $array_where[] = '' . 'u.options REGEXP \'' . $whatgender . '\'';
    }

    if (($country != 0 AND is_valid_id ($country)))
    {
      $array_links[] = '' . 'country=' . $country;
      $array_where[] = '' . 'u.country=\'' . $country . '\'';
    }

    if (($usergroup != 0 AND is_valid_id ($usergroup)))
    {
      $array_links[] = '' . 'usergroup=' . $usergroup;
      $array_where[] = '' . 'u.usergroup=\'' . $usergroup . '\'';
      if (!$is_mod)
      {
        $array_where[] = 'g.canstaffpanel = \'no\'';
        $array_where[] = 'g.cansettingspanel = \'no\'';
        $array_where[] = 'g.issupermod = \'no\'';
        $array_where[] = 'g.isvipgroup = \'no\'';
      }
    }
  }

  if (0 < count ($array_links))
  {
    $implode_links = implode ('&amp;', $array_links);
    $pagerlink = $_SERVER['SCRIPT_NAME'] . '?' . $implode_links . '&amp;action=do_search&amp;';
  }
  else
  {
    $pagerlink = $_SERVER['SCRIPT_NAME'] . '?';
  }

  if (0 < count ($array_where))
  {
    $where = 'WHERE ' . implode (' AND ', $array_where);
  }
  else
  {
    $where = '';
  }

  $countries = '<select name="country"><option value="any">' . $lang->users['op5'] . '</option>';
  ($query = sql_query ('SELECT id,name FROM countries ORDER by name') OR sqlerr (__FILE__, 118));
  while ($qcountry = mysql_fetch_assoc ($query))
  {
    $countries .= '<option value="' . $qcountry['id'] . '"' . ($country == $qcountry['id'] ? ' selected="selected"' : '') . '>' . $qcountry['name'] . '</option>';
  }

  $countries .= '</select">';
  $listusergroups = '<select name="usergroup"><option value="any">' . $lang->users['op5'] . '</option>';
  $groupin = ($is_mod ? '' : ' WHERE canstaffpanel = \'no\' AND cansettingspanel = \'no\' AND issupermod = \'no\' AND isvipgroup = \'no\'');
  ($query = sql_query ('' . 'SELECT gid, title FROM usergroups ' . $groupin . 'ORDER by title') OR sqlerr (__FILE__, 127));
  while ($susergroup = mysql_fetch_assoc ($query))
  {
    $listusergroups .= '<option value="' . $susergroup['gid'] . '"' . ($usergroup == $susergroup['gid'] ? ' selected="selected"' : '') . '>' . $susergroup['title'] . '</option>';
  }

  $listusergroups .= '</select">';
  stdhead ($lang->users['title'], true, 'supernote');
  echo '
<script type="text/javascript" src="' . $BASEURL . '/scripts/prototype.js"></script>
<script type="text/javascript" src="' . $BASEURL . '/ratings/js/scriptaculous.js"></script>
<script type="text/javascript" src="' . $BASEURL . '/scripts/autocomplete.js"></script>
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type="hidden" name="action" value="do_search">
<table border="0" cellspacing="0" cellpadding="4" class="tborder" align="center">
	<tr>
		<td class="colhead" colspan="4"><a name="searchuser" id="searchuser"></a>' . ts_collapse ('searchuser') . $lang->users['title3'] . '</td>
	</tr>
	' . ts_collapse ('searchuser', 2) . '
	<tr>

		<td class="trow1" style="border: 0;" width="30%">
			<fieldset class="fieldset" style="padding: 5px 10px 10px 5px; height: 45px;">
				<legend>' . $lang->users['op1'] . '</legend>
				<select name="byusername">
					<option value="begins"' . ($byusername == 'begins' ? ' selected="selected"' : '') . '>' . $lang->users['op2'] . '</option>
					<option value="contains"' . ($byusername == 'contains' ? ' selected="selected"' : '') . '>' . $lang->users['op3'] . '</option>
				</select>
				<input type="text" id="auto_keywords" autocomplete="off" name="username" value="' . htmlspecialchars_uni ($username) . '" size="10" />
				<script type="text/javascript">  new AutoComplete(\'auto_keywords\', \'ts_ajax.php?action=autocomplete&type=users&field=username&keyword=\', { delay: 0.25, resultFormat: AutoComplete.Options.RESULT_FORMAT_TEXT }); </script>
			</fieldset>
		</td>

		<td class="trow1" style="border: 0;" width="15%">
			<fieldset class="fieldset" style="padding: 5px 10px 10px 5px; height: 45px;">
				<legend>' . $lang->users['op4'] . '</legend>
				<select name="gender">
					<option value="any">' . $lang->users['op5'] . '</option>
					<option value="male"' . ($gender == 'male' ? ' selected="selected"' : '') . '>' . $lang->users['op6'] . '</option>
					<option value="female"' . ($gender == 'female' ? ' selected="selected"' : '') . '>' . $lang->users['op7'] . '</option>
				</select>
			</fieldset>
		</td>

		<td class="trow1" style="border: 0;" width="28%">
			<fieldset class="fieldset" style="padding: 5px 10px 10px 5px; height: 45px;">
				<legend>' . $lang->users['op9'] . '</legend>
				' . $countries . '
			</fieldset>
		</td>

		<td class="trow1" style="border: 0;" width="27%">
			<fieldset class="fieldset" style="padding: 5px 10px 10px 5px; height: 45px;">
				<legend>' . $lang->users['op8'] . '</legend>
				' . $listusergroups . '
			</fieldset>&nbsp;&nbsp;<input type="submit" value="' . $lang->global['buttongo'] . '" class=button style="height: 22px;">
		</td>

	</tr>
</table>
</form>
<br />
';
  ($query = sql_query ('' . 'SELECT u.id,g.gid FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) ' . $where) OR sqlerr (__FILE__, 191));
  $count = mysql_num_rows ($query);
  list ($pagertop, $pagerbottom, $limit) = pager (21, $count, $pagerlink);
  ($query = sql_query ('' . 'SELECT u.id, u.username, u.options, u.avatar, u.last_access, u.last_login, u.added, u.added, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, c.name as countryname, c.flagpic as countryflag, g.namestyle, g.title as grouptitle FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN countries c ON (u.country=c.id) LEFT JOIN usergroups g ON (g.gid=u.usergroup) ' . $where . ' ' . $orderby . ' ' . $limit) OR sqlerr (__FILE__, 195));
  echo $pagertop;
  echo '
<table width="100%" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td class="colhead" colspan="4">' . ts_collapse ('userlist') . $lang->users['title'] . '</td>
	</tr>
	' . ts_collapse ('userlist', 2);
  if (0 < mysql_num_rows ($query))
  {
    include_once INC_PATH . '/functions_icons.php';
    $dt = get_date_time (gmtime () - TS_TIMEOUT);
    $imagepath = '' . $BASEURL . '/' . $pic_base_url . 'friends/';
    $lang->load ('friends');
    $count = 0;
    echo '<tr>';
    $quickmenu = '';
    while ($user = mysql_fetch_assoc ($query))
    {
      if ($count % 3 == 0)
      {
        echo '</tr><tr>';
      }

      if (preg_match ('#L1#is', $user['options']))
      {
        $UserGender = '<img src="' . $imagepath . 'Male.png" alt="Male" title="Male" border="0" class="inlineimg" />';
      }
      else
      {
        if (preg_match ('#L2#is', $user['options']))
        {
          $UserGender = '<img src="' . $imagepath . 'Female.png" alt="Female" title="Female" border="0" class="inlineimg" />';
        }
        else
        {
          $UserGender = '<img src="' . $imagepath . 'NA.png" alt="--" title="--" border="0" class="inlineimg" />';
        }
      }

      $xoffline = sprintf ($lang->friends['xoffline'], $user['username']);
      $xonline = sprintf ($lang->friends['xonline'], $user['username']);
      $xavatar = sprintf ($lang->friends['xavatar'], $user['username']);
      if (((preg_match ('#B1#is', $user['options']) AND !$is_mod) AND $user['id'] != $CURUSER['id']))
      {
        $user['last_access'] = $user['last_login'];
        $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0" class="inlineimg">';
      }
      else
      {
        if (($dt < $user['last_access'] OR $user['id'] == $CURUSER['id']))
        {
          $onoffpic = '<img src="' . $imagepath . 'online.png" alt="' . $xonline . '" title="' . $xonline . '" border="0" class="inlineimg">';
        }
        else
        {
          $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0" class="inlineimg">';
        }
      }

      echo '
		<td>
			<div>
				<div style="float: right;">
					<img src="' . $BASEURL . '/' . $pic_base_url . 'flag/' . $user['countryflag'] . '" alt="' . $user['countryname'] . '" title="' . $user['countryname'] . '" height="20" width="32" border="0"><br />
					<img src="' . ($user['avatar'] ? fix_url ($user['avatar']) : $BASEURL . '/' . $pic_base_url . 'default_avatar.gif') . '" alt="' . $xavatar . '" title="' . $xavatar . '" height="32" width="32" border="0">
				</div>
				' . $UserGender . '
				<strong><a href="#" id="quickmenu' . $user['id'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a></strong> ' . get_user_icons ($user) . '
				<br />
				' . $onoffpic . '
				<strong>' . $lang->friends['act3'] . '</strong> ' . my_datee ($dateformat, $user['last_access']) . ' ' . my_datee ($timeformat, $user['last_access']) . '
				<br />
				<a href="' . $BASEURL . '/sendmessage.php?receiver=' . $user['id'] . '" title="' . $lang->friends['act2'] . '"><img src="' . $imagepath . 'pm.png" alt="" border="0"></a> ' . sprintf ($lang->users['joined'], my_datee ($dateformat, $user['added'])) . '
			</div>
		</td>';
      $quickmenu .= '
		<div id="quickmenu' . $user['id'] . '_menu" class="menu_popup" style="display:none;">
			<table border="1" cellspacing="0" cellpadding="2">
				<tr>
					<td align="center" class="thead"><b>' . $lang->global['quickmenu'] . ' ' . $user['username'] . '</b></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . ts_seo ($user['id'], $user['username']) . '">' . $lang->global['qinfo1'] . '</a></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/sendmessage.php?receiver=' . $user['id'] . '">' . sprintf ($lang->global['qinfo2'], $user['username']) . '</td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserposts&id=' . $user['id'] . '">' . sprintf ($lang->global['qinfo3'], $user['username']) . '</a></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserthreads&id=' . $user['id'] . '">' . sprintf ($lang->global['qinfo4'], $user['username']) . '</a></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/friends.php?action=add_friend&friendid=' . $user['id'] . '">' . sprintf ($lang->global['qinfo5'], $user['username']) . '</td>
				</tr>

				' . ($is_mod ? '<tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=edituser&userid=' . $user['id'] . '">' . $lang->global['qinfo6'] . '</a></td></tr><tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=warnuser&userid=' . $user['id'] . '">' . $lang->global['qinfo7'] . '</td></tr>' : '') . '
			</table>
			</div>
			<script type="text/javascript">
				menu_register("quickmenu' . $user['id'] . '");
			</script>';
      ++$count;
    }
  }
  else
  {
    echo '<tr><td colspan="4">' . $lang->users['nr'] . '</td>';
  }

  echo '
</tr>
</table>
' . $quickmenu . '
<script type="text/javascript">
	menu.activate(true);
</script>
' . $pagertop;
  stdfoot ();
?>
