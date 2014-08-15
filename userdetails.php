<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function b_day_calcage ($birthday)
  {
    list ($day, $month, $year) = explode ('-', $birthday);
    $age = date ('Y') - $year;
    if (date ('m') < $month)
    {
      --$age;
    }
    else
    {
      if (date ('d') < $day)
      {
        --$age;
      }
    }

    return $age;
  }

  function show_userdetails_errors ()
  {
    global $error;
    global $lang;
    if (0 < count ($error))
    {
      $errors = implode ('<br />', $error);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  define ('UD_VERSION', '3.3.9 ');
  define ('NcodeImageResizer', true);
  require 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('userdetails');
  $userid = (isset ($_GET['id']) ? intval ($_GET['id']) : (isset ($_POST['id']) ? intval ($_POST['id']) : intval ($CURUSER['id'])));
  $IsStaff = is_mod ($usergroups);
  $SameUser = ($userid == $CURUSER['id'] ? true : false);
  if (!is_valid_id ($userid))
  {
    stderr ($lang->global['error'], $lang->userdetails['invaliduser'], false);
  }

  if (PROFILE_MAX_VISITOR < 2)
  {
    define ('PROFILE_MAX_VISITOR', 2);
  }

  $query = sql_query ('SELECT userid FROM ts_profilevisitor WHERE userid = \'' . $userid . '\' AND visible = \'1\' GROUP BY userid HAVING COUNT(*) > ' . PROFILE_MAX_VISITOR);
  if (0 < mysql_num_rows ($query))
  {
    while ($user = mysql_fetch_assoc ($query))
    {
      $QQuery = sql_query ('SELECT userid, visitorid, dateline FROM ts_profilevisitor WHERE userid = \'' . $user['userid'] . '\' ORDER BY dateline DESC LIMIT ' . PROFILE_MAX_VISITOR . ', 1');
      if (0 < mysql_num_rows ($QQuery))
      {
        while ($delete = mysql_fetch_assoc ($QQuery))
        {
          sql_query ('DELETE FROM ts_profilevisitor WHERE dateline = \'' . $delete['dateline'] . '\' AND userid = \'' . $delete['userid'] . '\' AND visitorid = \'' . $delete['visitorid'] . '\'');
        }

        continue;
      }
    }

    unset ($user);
    unset ($delete);
  }

  if ((!$SameUser AND $usergroups['canviewotherprofile'] != 'yes'))
  {
    print_no_permission ();
  }

  ($Query = sql_query ('SELECT u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, c.name as countryname, c.flagpic, g.namestyle, g.title FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN countries c ON (u.country=c.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 63));
  if (0 < mysql_num_rows ($Query))
  {
    $user = mysql_fetch_assoc ($Query);
  }
  else
  {
    stderr ($lang->global['error'], $lang->userdetails['invaliduser'], false);
  }

  if ((((preg_match ('#I3#is', $user['options']) OR preg_match ('#I4#is', $user['options'])) AND !$IsStaff) AND !$SameUser))
  {
    print_no_permission (false, true, $lang->userdetails['noperm']);
  }

  if ($user['status'] == 'pending')
  {
    stderr ($lang->global['error'], $lang->userdetails['pendinguser']);
  }
  else
  {
    if ((!$user['username'] OR !$user))
    {
      stderr ($lang->global['error'], $lang->userdetails['invaliduser'], false);
    }
  }

  $user['ip'] = htmlspecialchars_uni ($user['ip']);
  $user['email'] = htmlspecialchars_uni ($user['email']);
  $user['page'] = '<a href="' . str_replace ('&amp;', '&', htmlspecialchars_uni ($user['page'])) . '" alt="' . htmlspecialchars_uni ($user['page']) . '" title="' . htmlspecialchars_uni ($user['page']) . '">' . cutename ($user['page'], 30) . '</a>';
  if (((preg_match ('#B1#is', $user['options']) AND !$IsStaff) AND !$SameUser))
  {
    $user['last_access'] = $user['last_login'];
  }

  if ($user['invited_by'])
  {
    ($query = sql_query ('SELECT u.username, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=\'' . $user['invited_by'] . '\'') OR sqlerr (__FILE__, 97));
    if (0 < mysql_num_rows ($query))
    {
      $IUser = mysql_fetch_assoc ($query);
      $user['invited_by'] = '<a href="' . ts_seo ($user['invited_by'], $IUser['username']) . '">' . get_user_color ($IUser['username'], $IUser['namestyle']) . '</a>';
    }
  }

  if ($userid != $CURUSER['id'])
  {
    sql_query ('
		REPLACE INTO ts_profilevisitor
			(userid, visitorid, dateline, visible)
		VALUES
			(
				\'' . $userid . '\',
				\'' . $CURUSER['id'] . '\',
				\'' . TIMENOW . '\',
				\'1\'
			)
	');
    sql_query ('UPDATE users SET visitorcount = visitorcount + 1 WHERE id = ' . sqlesc ($userid));
    ++$user['visitorcount'];
  }

  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND $_POST['do'] == 'save_vmsg'))
  {
    if (preg_match ('#M3#is', $user['options']))
    {
      $error[] = $lang->userdetails['cerror4'];
    }
    else
    {
      if ((preg_match ('#M2#is', $user['options']) AND !$IsStaff))
      {
        ($query = sql_query ('SELECT id FROM friends WHERE status=\'c\' AND userid=' . $userid . ' AND friendid=' . (int)$CURUSER['id']) OR sqlerr (__FILE__, 130));
        if (mysql_num_rows ($query) < 1)
        {
          $error[] = $lang->userdetails['cerror4'];
        }
      }
    }

    if (!$error)
    {
      $message = $_POST['message'];
      $msglong = strlen ($message);
      if ($usergroups['cancomment'] != 'yes')
      {
        $error[] = $lang->global['nopermission'];
      }
      else
      {
        if ((empty ($message) OR $msglong < 3))
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
              sql_query ('UPDATE ts_visitor_messages SET visitormsg = ' . sqlesc ($message) . ' WHERE id = ' . sqlesc (intval ($_POST['isupdate'])));
            }
            else
            {
              (sql_query ('INSERT INTO ts_visitor_messages (userid,visitorid,visitormsg,added) VALUES (' . sqlesc ($userid) . ', ' . sqlesc ($CURUSER['id']) . ',' . sqlesc ($message) . ', \'' . time () . '\')') OR sqlerr (__FILE__, 161));
            }
          }
        }
      }
    }
  }

  if ((((strtoupper ($_SERVER['REQUEST_METHOD']) == 'GET' AND isset ($_GET['do'])) AND $_GET['do'] == 'delete_msg') AND $IsStaff))
  {
    $Dmsg_id = intval ($_GET['msg_id']);
    if (is_valid_id ($Dmsg_id))
    {
      sql_query ('DELETE FROM ts_visitor_messages WHERE id = ' . sqlesc ($Dmsg_id));
    }
  }

  if ((((strtoupper ($_SERVER['REQUEST_METHOD']) == 'GET' AND isset ($_GET['do'])) AND $_GET['do'] == 'edit_msg') AND $IsStaff))
  {
    $Emsg_id = intval ($_GET['msg_id']);
    if (is_valid_id ($Emsg_id))
    {
      $eQuery = sql_query ('SELECT visitormsg FROM ts_visitor_messages WHERE id = ' . sqlesc ($Emsg_id));
      if (0 < mysql_num_rows ($eQuery))
      {
        $Vmsg = htmlspecialchars_uni (mysql_result ($eQuery, 0, 'visitormsg'));
      }
    }
  }

  stdhead (sprintf ($lang->userdetails['title'], $user['username']), true, 'supernote', 'INDETAILS');
  require INC_PATH . '/functions_icons.php';
  $Buttons = '
<input type="button" class=button value="' . $lang->userdetails['button1'] . '" onclick="jumpto(\'' . $BASEURL . '/sendmessage.php?receiver=' . $userid . '\'); return false;" />
<input type="button" class=button value="' . $lang->userdetails['button2'] . '" onclick="jumpto(\'' . $BASEURL . '/report.php?action=reportuser&reportid=' . $userid . '\'); return false;" />
<input type="button" class=button value="' . $lang->userdetails['button3'] . '" onclick="window.open(\'' . $BASEURL . '/transfer.php?receiver=' . $userid . '\',\'transfer\',\'toolbar=no, scrollbars=no, resizable=no, width=700, height=350, top=250, left=250\'); return false;" />
<input type="button" class=button value="' . $lang->userdetails['button4'] . '" onclick="jumpto(\'' . $BASEURL . '/friends.php?action=add_friend&friendid=' . $userid . '\'); return false;" />
<input type="button" class=button value="' . $lang->userdetails['button5'] . '" onclick="jumpto(\'' . $BASEURL . '/friends.php?action=add_block&friendid=' . $userid . '&tab=blocks\'); return false;" />';
  $imagepath = '' . $BASEURL . '/' . $pic_base_url . 'friends/';
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

  $UserInfo = array ('username' => get_user_color ($user['username'], $user['namestyle']), 'title' => get_user_color ($user['title'], $user['namestyle']), 'joindate' => my_datee ($regdateformat, $user['added']), 'lastaccess' => my_datee ($dateformat, $user['last_access']) . ' ' . my_datee ($timeformat, $user['last_access']), 'page' => (($IsStaff OR $SameUser) ? $user['page'] : $lang->userdetails['hidden']));
  switch ($user['usergroup'])
  {
    case 0:
    {
    }

    case 1:
    {
    }

    case 2:
    {
      $png = 'rank_full_blank';
      break;
    }

    default:
    {
      $png = 'rank_star_blank';
      break;
    }
  }

  require_once INC_PATH . '/functions_mkprettytime.php';
  $country = '<img src="' . $BASEURL . '/' . $pic_base_url . 'flag/' . ($user['flagpic'] ? $user['flagpic'] : 'jollyroger.gif') . '" border="0" alt="' . $user['countryname'] . '" title="' . $user['countryname'] . '" />';
  $userbday = ($user['birthday'] ? b_day_calcage ($user['birthday']) : false);
  $image_hash = $_SESSION['image_hash'] = md5 ($user['id'] . $user['username'] . $securehash);
  $image = '<img src="' . $BASEURL . '/include/class_user_title.php?str=' . base64_encode ($user['title']) . '&png=' . base64_encode ($png) . '" border="0" alt= "" title ="" />';
  $email = (((preg_match ('#I1#is', $user['options']) OR $SameUser) OR $IsStaff) ? $user['email'] : $lang->userdetails['hidden']);
  $uploaded = mksize ($user['uploaded']);
  $downloaded = mksize ($user['downloaded']);
  $signature = ($user['signature'] ? '<hr />' . format_comment ($user['signature']) : '');
  $donoruntil = ((($SameUser OR $IsStaff) AND $user['donoruntil'] != '0000-00-00 00:00:00') ? sprintf ($lang->userdetails['donoruntil'], mkprettytime (strtotime ($user['donoruntil']) - gmtime ())) . '<br />' : '');
  if ((!$donoruntil AND ($SameUser OR $IsStaff)))
  {
    $query = sql_query ('SELECT vip_until FROM ts_auto_vip WHERE userid = \'' . $userid . '\'');
    if (0 < mysql_num_rows ($query))
    {
      $donoruntil = sprintf ($lang->userdetails['donoruntil'], mkprettytime (strtotime (mysql_result ($query, 0, 'vip_until')) - gmtime ())) . '<br />';
    }
  }

  $donated = (($SameUser OR $IsStaff) ? sprintf ($lang->userdetails['donated'], ts_nf ($user['donated']), ts_nf ($user['total_donated'])) . '<br />' : '');
  $kps = (($SameUser OR $IsStaff) ? '<br />' . sprintf ($lang->userdetails['kps'], number_format ($user['seedbonus'], 2)) : '');
  $xoffline = sprintf ($lang->userdetails['xoffline'], $user['username']);
  $xonline = sprintf ($lang->userdetails['xonline'], $user['username']);
  $dt = get_date_time (gmtime () - TS_TIMEOUT);
  if (((preg_match ('#B1#is', $user['options']) AND !$SameUser) AND !$IsStaff))
  {
    $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0" class="inlineimg" />';
  }
  else
  {
    if (($dt < $user['last_access'] OR $SameUser))
    {
      $onoffpic = '<img src="' . $imagepath . 'online.png" alt="' . $xonline . '" title="' . $xonline . '" border="0" class="inlineimg" />';
    }
    else
    {
      $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0" class="inlineimg" />';
    }
  }

  if (0 < $user['downloaded'])
  {
    $sr = $user['uploaded'] / $user['downloaded'];
    if (4 <= $sr)
    {
      $s = 'w00t';
    }
    else
    {
      if (2 <= $sr)
      {
        $s = 'grin';
      }
      else
      {
        if (1 <= $sr)
        {
          $s = 'smile1';
        }
        else
        {
          if (0.5 <= $sr)
          {
            $s = 'noexpression';
          }
          else
          {
            if (0.25 <= $sr)
            {
              $s = 'sad';
            }
            else
            {
              $s = 'cry';
            }
          }
        }
      }
    }

    $sr = floor ($sr * 1000) / 1000;
    $ratioimage = ' <img src="' . $BASEURL . '/' . $pic_base_url . 'smilies/' . $s . '.gif" border="0" alt="" title="" class="inlineimg" />';
  }

  $ratio = (0 < $user['downloaded'] ? number_format ($user['uploaded'] / $user['downloaded'], 2) : (0 < $user['uploaded'] ? 'INF.' : '---'));
  $ratio = '<font color="' . get_ratio_color ($sr) . '">' . $ratio . $ratioimage . '</font>';
  require TSDIR . '/' . $cache . '/downloadspeed.php';
  require TSDIR . '/' . $cache . '/uploadspeed.php';
  $downloadspeed = $uploadspeed = '--';
  $UserSpeed = explode ('~', $user['speed']);
  if ($_downloadspeed[$UserSpeed[0]])
  {
    $downloadspeed = $_downloadspeed[$UserSpeed[0]]['name'];
  }

  if ($_uploadspeed[$UserSpeed[1]])
  {
    $uploadspeed = $_uploadspeed[$UserSpeed[1]]['name'];
  }

  unset ($_uploadspeed);
  unset ($_downloadspeed);
  $lang->load ('quick_editor');
  require INC_PATH . '/functions_quick_editor.php';
  require_once INC_PATH . '/class_tsquickbbcodeeditor.php';
  $QuickEditor = new TSQuickBBCodeEditor ();
  $QuickEditor->ImagePath = $BASEURL . '/' . $pic_base_url;
  $QuickEditor->SmiliePath = $BASEURL . '/' . $pic_base_url . 'smilies/';
  $QuickEditor->FormName = 'quickreply';
  $QuickEditor->TextAreaName = 'message';
  $VisitorMessagesForm = '
' . $QuickEditor->GenerateCSS () . '
' . $QuickEditor->GenerateJavascript () . '
' . ($useajax == 'yes' ? '<script type="text/javascript" src="' . $BASEURL . '/scripts/quick_vm.js"></script>' : '') . '
<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?id=' . $userid . '&do=save_vmsg" name="quickreply" id="quickreply">
<input type="hidden" name="userid" value="' . $userid . '" />
<input type="hidden" name="do" value="save_vmsg" />
' . (isset ($Vmsg) ? '<input type="hidden" name="isupdate" value="' . $Emsg_id . '" />' : '') . '
<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td class="none">
			' . $QuickEditor->GenerateBBCode () . '
			<br />
			<textarea name="message" style="width:670px;height:100px;" id="message">' . (isset ($Vmsg) ? $Vmsg : (isset ($message) ? $message : '')) . '</textarea><br />
			<span id="loading-layer" style="display:none;"><img src="' . $BASEURL . '/' . $pic_base_url . 'ajax-loader.gif" border="0" alt="" title="" class="inlineimg" /></span>
			' . ($useajax == 'yes' ? '<input type="button" class="button" value="' . (isset ($Vmsg) ? $lang->userdetails['visitormsg6'] : $lang->userdetails['visitormsg2']) . '" name="submitvm" id="submitvm" onclick="javascript:TSajaxquickvm(\'' . $userid . '\', \'' . (isset ($Vmsg) ? $Emsg_id : 0) . '\');" />' : '<input type="submit" name="submit" value="' . (isset ($Vmsg) ? $lang->userdetails['visitormsg6'] : $lang->userdetails['visitormsg2']) . '" class="button" />') . '
			<input type="reset" value="' . $lang->userdetails['visitormsg3'] . '" class=button />
		</td>
	</tr>
</table>
</form>
';
  $VisitorMessages = '
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr>
			<td class="thead">' . ts_collapse ('content1a1') . $lang->userdetails['visitormsg1'] . '</td>
		</tr>
		' . ts_collapse ('content1a1', 2) . '
		<tr>
			<td id="PostedQuickVisitorMessages" name="PostedQuickVisitorMessages" style="display: none;">
			</td>
		</tr>';
  include_once INC_PATH . '/readconfig_forumcp.php';
  if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
  {
    $perpage = intval ($CURUSER['postsperpage']);
  }
  else
  {
    $perpage = $f_postsperpage;
  }

  $Query = sql_query ('SELECT id FROM ts_visitor_messages WHERE userid = ' . sqlesc ($userid));
  $Count = mysql_num_rows ($Query);
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $Count, ts_seo ($userid, $user['username']) . '&', '', false);
  ($Query2 = sql_query ('SELECT v.id as visitormsgid, v.visitorid, v.visitormsg, v.added, u.username, u.avatar, g.namestyle FROM ts_visitor_messages v LEFT JOIN users u ON (v.visitorid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE v.userid=' . sqlesc ($userid) . ('' . ' ORDER by v.added DESC ' . $limit)) OR sqlerr (__FILE__, 344));
  if (0 < mysql_num_rows ($Query2))
  {
    while ($vm = mysql_fetch_assoc ($Query2))
    {
      $VisitorUsername = get_user_color ($vm['username'], $vm['namestyle']);
      $vAvatar = get_user_avatar ($vm['avatar'], false, 60, 60);
      $vAdded = my_datee ($dateformat, $vm['added']) . ' ' . my_datee ($timeformat, $vm['added']);
      $vPoster = '<a href="' . ts_seo ($vm['visitorid'], $vm['username']) . '">' . $VisitorUsername . '</a>';
      $vMessage = format_comment ($vm['visitormsg']);
      $VisitorMessages .= '
		<tr>
			<td id="ShowVisitorMessage' . $vm['visitormsgid'] . '" name="ShowVisitorMessage' . $vm['visitormsgid'] . '">
				<div style="float: left;">' . $vAvatar . '</div>
				<div style="overflow:auto; padding: 2px;">
					<p class="subheader">
						<span style="float: right;">[<a href="' . $BASEURL . '/report.php?action=reportvisitormessage&reportid=' . $userid . '&votedfor_xtra=' . $vm['visitormsgid'] . '">' . $lang->userdetails['reportmsg'] . '</a>] ' . ($IsStaff ? ' [<a href="' . $_SERVER['SCRIPT_NAME'] . '?id=' . $userid . '&do=delete_msg&msg_id=' . $vm['visitormsgid'] . '">' . $lang->userdetails['deletemsg'] . '</a>] [<a href="' . $_SERVER['SCRIPT_NAME'] . '?id=' . $userid . '&do=edit_msg&msg_id=' . $vm['visitormsgid'] . '">' . $lang->userdetails['editmsg'] . '</a>]' : '') . '</span> ' . sprintf ($lang->userdetails['visitormsg5'], $vAdded, $vPoster) . '
					</p>
					<div name="msg' . $vm['visitormsgid'] . '" id="msg' . $vm['visitormsgid'] . '">' . $vMessage . '</div>
				</div>
			</td>
		</tr>
		';
    }
  }
  else
  {
    $VisitorMessages .= '
	<tr>
		<td>
			' . sprintf ($lang->userdetails['visitormsg4'], $user['username']) . '
		</td>
	</tr>';
  }

  $VisitorMessages .= '</table>';
  $RecentVisitorsArray = array ();
  $VQuery = sql_query ('SELECT v.visitorid, u.username, g.namestyle FROM ts_profilevisitor v LEFT JOIN users u ON (v.visitorid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE v.userid = ' . sqlesc ($userid) . ' ORDER By v.dateline DESC LIMIT ' . PROFILE_MAX_VISITOR);
  if (0 < mysql_num_rows ($VQuery))
  {
    while ($RV = mysql_fetch_assoc ($VQuery))
    {
      $RecentVisitorsArray[] = '<a href="' . ts_seo ($RV['visitorid'], $RV['username']) . '">' . get_user_color ($RV['username'], $RV['namestyle']) . '</a>';
    }
  }

  $SocialGroups = array ();
  $SGQuery = sql_query ('SELECT m.groupid, sg.name FROM ts_social_group_members m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.userid = ' . sqlesc ($userid) . ' AND m.type = \'public\'');
  if (0 < mysql_num_rows ($SGQuery))
  {
    while ($SG = mysql_fetch_assoc ($SGQuery))
    {
      $SocialGroups[] = '<a href="' . $BASEURL . '/ts_social_groups.php?do=showgroup&amp;groupid=' . $SG['groupid'] . '">' . cutename ($SG['name'], 50) . '</a>';
    }
  }

  $StaffTools = $rating = '';
  if ($ratingsystem == 'yes')
  {
    require 'ratings/includes/rating_functions.php';
    $rating = '
	<a href="#" id="rateuser">' . $lang->userdetails['rateuser'] . '</a>	&nbsp;
	<script type="text/javascript">
		menu_register("rateuser", true);
	</script>
	<div id="rateuser_menu" class="menu_popup" style="display:none;">
		<table border="1" cellspacing="0" cellpadding="2">
			<tr>
				<td class="thead">' . $lang->userdetails['rateuser'] . '</td>
			</tr>
			<tr>
				<td class="subheader">' . show_rating ($userid, $CURUSER['id'], 2) . '</td>
			</tr>
		</table>
	</div>';
  }

  if ($IsStaff)
  {
    $StaffTools = '
	<span style="float: right">
		' . $rating . '
		<a href="#" id="manageaccount">Manage Account</a>
		<script type="text/javascript">
			menu_register("manageaccount", true);
		</script>
		<div id="manageaccount_menu" class="menu_popup" style="display:none;">
			<table border="1" cellspacing="0" cellpadding="2">
				<tr>
					<td class="thead">Manage Account</td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=edituser&userid=' . $userid . '">Edit User Profile</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=warnuser&userid=' . $userid . '">Warn User</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/badusers.php?act=insert&do=save&username=' . urlencode ($user['username']) . '&email=' . urlencode ($user['email']) . '&ipaddress=' . urlencode ($user['ip']) . '&userid=' . $userid . '&comment=Bad+User">Insert Into Badusers</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/admin/ts_watch_list.php?action=add&userid=' . $userid . '">Watch This User</a></td>
				</tr>
				<tr>
					<td class="thead">Staff Tools</td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/admin/index.php?act=invitetree&tree=' . $userid . '">Show Invite Tree</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/admin/index.php?act=ip_info&userid=' . $userid . '">Show IP Info</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserthreads&id=' . $userid . '">Show User Threads</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserposts&id=' . $userid . '">Show User Posts</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/userhistory.php?action=viewcomments&id=' . $userid . '">Show User Comments</a></td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
			menu.activate(true);
		</script>&nbsp;&nbsp;
	</span>
	';
  }
  else
  {
    $StaffTools = '
	<span style="float: right">
		' . $rating . '
		<script type="text/javascript">
			menu.activate(true);
		</script>&nbsp;&nbsp;
	</span>
	';
  }

  echo '<s';
  echo 'cript type="text/javascript">
	function getTabData(id, ip)
	{
		var url = baseurl+\'/ts_ajax2.php\';
		var rand = Math.random(9999);
		var pars = \'what=\' + id + (ip ? \'&ip=\'+ip : \'\')+\'&userid=';
  echo $userid;
  echo '&rand=\' + rand;
		var myAjax = new Ajax.Request
		( url,
			{
				method: \'POST\',
				contentType: \'application/x-www-form-urlencoded\',
				encoding: 	\'';
  echo $charset;
  echo '\',
				parameters: pars,
				onLoading: showLoad,
				onComplete: showResponse,
				onFailure: function ()
				{
					alert(l_ajaxerror);
				}
			}
		);
	}
	function showLoad ()
	{
		$(\'load\').style.display = \'block\';
	}
	function showResponse (originalRequest)
	{
		var newData = originalRequest.responseText;
		$(\'load\').style.display = \'none\';
		$(\'showcontents\').innerHTML = newData;
	}
	functio';
  echo 'n TSAjaxRequest(WhatToShow, UserIP)
	{
		var TSElement = document.getElementById(\'hiddencontents\');
		TSElement.style.display = \'block\';
		document.getElementById(\'showcontents\').innerHTML = \'\';
		getTabData(WhatToShow, UserIP);
	}
</script>
<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr valign="top">
			<td valign="top" class="none">
				<div style="padding-bottom:';
  echo ' 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content1s') . ' ' . $StaffTools . sprintf ($lang->userdetails['title'], $user['username']);
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content1s', 2);
  echo '							<tr>
								<td>
									<div style="float: right;">
										';
  echo $onoffpic . $UserGender . '<br />' . $country;
  echo '									</div>
									<div style="float: left;">
										<table>
											<tr>
												<td class="none">
													';
  echo get_user_avatar ($user['avatar']) . '<br /> ' . $image;
  echo '												</td>
											</tr>
										</table>
									</div>
									<div valign="top">
										<table border="0" cellspacing="0" cellpadding="2">
											<tr>
												<td style="padding: 3px;" class="none">
													';
  echo $donoruntil . $donated . sprintf ($lang->userdetails['stats1'], $uploaded, $downloaded, $ratio) . $kps . sprintf ($lang->userdetails['duspeed'], $downloadspeed, $uploadspeed);
  echo '												</td>
											</tr>
										</table>
									<div>
									<div valign="bottom" style="float: right;">
										';
  echo $Buttons;
  echo '									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="display: none; padding-bottom: 15px;" name="hiddencontents" id="hiddencontents">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content1b') . $lang->userdetails['serverresponse'];
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content1b', 2);
  echo '							<tr>
								<td>
									<div id="load" style="display: none;"><img src="';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'ajax-loader.gif" alt="';
  echo $lang->global['pleasewait'];
  echo ' title="';
  echo $lang->global['pleasewait'];
  echo '" " class="inlineimg" />';
  echo $lang->global['pleasewait'];
  echo '</div>
									<div id="showcontents" name="showcontents"></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="padding-bottom: 15px;">
					';
  echo show_userdetails_errors ();
  echo '					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content1a') . (isset ($Vmsg) ? $lang->userdetails['editmsg2'] : $lang->userdetails['visitormsg']);
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content1a', 2);
  echo '							<tr>
								<td>
									';
  echo $VisitorMessagesForm;
  echo '
								</td>
							</tr>
						</tbody>
					</table>
					<br />
					';
  echo $pagertop . $VisitorMessages . $pagerbottom;
  echo '				</div>
			</td>
			<td style="padding-left: 15px" valign="top" width="210" class="none">
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content2a') . $lang->userdetails['ministats'];
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content2a', 2);
  echo '							<tr>
								<td>
									';
  echo sprintf ($lang->userdetails['ministats2'], $UserInfo['username'] . ($userbday ? '' . ' (' . $userbday . ') ' : '') . get_user_icons ($user), $UserInfo['title'], $email, $UserInfo['joindate'], $UserInfo['lastaccess'], $UserInfo['page']);
  if ($user['invited_by'])
  {
    echo sprintf ($lang->userdetails['iby'], $user['invited_by']);
  }

  echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
				';
  if (($SameUser OR $IsStaff))
  {
    echo '				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
    echo ts_collapse ('content2b') . $lang->userdetails['torrentstats'];
    echo '								</td>
							</tr>
							';
    echo ts_collapse ('content2b', 2);
    echo '							<tr>
								<td>
									<a href="#showcontents" onclick="TSAjaxRequest(\'showuploaded\');">';
    echo $lang->userdetails['torrentstats1'];
    echo '</a>
									<br />
									<a href="#showcontents" onclick="TSAjaxRequest(\'showcompleted\');">';
    echo $lang->userdetails['torrentstats2'];
    echo '</a>
									<br />
									<a href="#showcontents" onclick="TSAjaxRequest(\'showleechs\');">';
    echo $lang->userdetails['torrentstats3'];
    echo '</a>
									<br />
									<a href="#showcontents" onclick="TSAjaxRequest(\'showseeds\');">';
    echo $lang->userdetails['torrentstats4'];
    echo '</a>
									<br />
									<a href="#showcontents" onclick="TSAjaxRequest(\'showsnatches\');">';
    echo $lang->userdetails['torrentstats5'];
    echo '</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				';
  }

  echo '				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content2c') . $lang->userdetails['ipinfo'];
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content2c', 2);
  echo '							<tr>
								<td>
									';
  echo $lang->userdetails['ipinfo1'] . (($SameUser OR $IsStaff) ? $user['ip'] : $lang->userdetails['hidden']);
  echo '<br />
									';
  echo $lang->userdetails['ipinfo2'] . ($IsStaff ? '<a href="' . $BASEURL . '/admin/index.php?act=iphistory&id=' . $userid . '">' . $lang->userdetails['clicktosee'] . '</a>' : $lang->userdetails['hidden']);
  echo '									';
  echo ($IsStaff ? '<br /><br /><a href="#showcontents" onclick="TSAjaxRequest(\'detecthost\', \'' . $user['ip'] . '\');"><b>' . $lang->userdetails['detecthost'] . '</b></a> - <a href="' . $BASEURL . '/admin/index.php?act=iptocountry&do=2&ip_address=' . $user['ip'] . '"><b>' . $lang->userdetails['detectcountry'] . '</b></a> - <a href="' . $BASEURL . '/redirector.php?url=http://whois.domaintools.com/' . $user['ip'] . '" target="_blank"><b>' . $lang->userdetails['whois'] . '</b></a>' : '');
  echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content2d') . $lang->userdetails['usertools'];
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content2d', 2);
  echo '							<tr>
								<td>
									';
  if (($SameUser OR $IsStaff))
  {
    echo '
										<a href="' . $BASEURL . '/takeflush.php?id=' . $userid . '">' . $lang->userdetails['usertools1'] . '</a>
										<br />
										<a href="' . $BASEURL . '/invite.php">' . $lang->userdetails['usertools2'] . '</a> (' . ts_nf ($user['invites']) . ')
										<br />';
  }

  echo '									<a href="';
  echo $BASEURL;
  echo '/port_check.php">';
  echo $lang->userdetails['usertools3'];
  echo '</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				';
  if (0 < count ($SocialGroups))
  {
    echo '				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
    echo ts_collapse ('content2f') . $lang->userdetails['gmember'];
    echo '								</td>
							</tr>
							';
    echo ts_collapse ('content2f', 2);
    echo '							<tr>
								<td>
										';
    echo implode ('<br />', $SocialGroups) . '<hr />';
    echo '<div style="float: right"><a href="' . $BASEURL . '/ts_social_groups.php">' . $lang->userdetails['showgrp'] . '</a></div>';
    echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
				';
  }

  echo '				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo ts_collapse ('content2e') . $lang->userdetails['recentvisitors'];
  echo '								</td>
							</tr>
							';
  echo ts_collapse ('content2e', 2);
  echo '							<tr>
								<td>
										';
  if (0 < count ($RecentVisitorsArray))
  {
    echo sprintf ($lang->userdetails['recentvisitors2'], PROFILE_MAX_VISITOR) . '<br />' . implode (', ', $RecentVisitorsArray) . '<hr />';
  }

  echo '<div style="float: right">' . sprintf ($lang->userdetails['recentvisitors1'], $user['visitorcount']) . '</div>';
  echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
';
  stdfoot ();
?>
