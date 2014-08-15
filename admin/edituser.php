<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function months ()
  {
    $months = array ('0' => '---select---', '1' => '1 Week', '2' => '2 Weeks', '3' => '3 Weeks', '4' => '1 Month', '5' => '5 Weeks', '6' => '6 Weeks', '7' => '7 Weeks', '8' => '2 Months', '12' => '3 Months', '16' => '4 Months', '20' => '5 Months', '24' => '6 Months', '28' => '7 Months', '32' => '8 Months', '36' => '9 Months', '40' => '10 Months', '44' => '11 Months', '48' => '12 Months', '255' => 'Unlimited');
    $str = '';
    foreach ($months as $v => $d)
    {
      $str .= '<option value="' . $v . '">' . $d . '</option>';
    }

    return $str;
  }

  function weeks ()
  {
    $weeks = array ('0' => '---select---', '1' => '1 Week', '2' => '2 Weeks', '3' => '3 Weeks', '4' => '4 Weeks', '5' => '5 Weeks', '6' => '6 Weeks', '7' => '7 Weeks', '8' => '8 Weeks', '9' => '9 Weeks', '10' => '10 Weeks', '11' => '11 Weeks', '12' => '12 Weeks', '255' => 'Unlimited');
    $str = '';
    foreach ($weeks as $v => $d)
    {
      $str .= '<option value="' . $v . '">' . $d . '</option>';
    }

    return $str;
  }

  function permission_check ()
  {
    global $userdata;
    global $usergroups;
    global $CURUSER;
    if ((((($userdata['cansettingspanel'] == 'yes' AND $usergroups['cansettingspanel'] != 'yes') OR ($userdata['issupermod'] == 'yes' AND $usergroups['issupermod'] != 'yes')) OR ($userdata['canstaffpanel'] == 'yes' AND $usergroups['canstaffpanel'] != 'yes')) OR $CURUSER['id'] == $userdata['id']))
    {
      print_no_permission (false, true, 'Permission Denied: Protected usergroup!');
      return null;
    }

  }

  function insert_message ($userid, $message, $subject)
  {
    require_once INC_PATH . '/functions_pm.php';
    send_pm ($userid, $message, $subject);
  }

  function yesno ($title, $name, $value = 'yes')
  {
    if ($value == 'no')
    {
      $nocheck = ' checked="checked"';
    }
    else
    {
      $yescheck = ' checked="checked"';
    }

    echo '' . '<tr>
<td valign="top" width="40%" align="right">' . $title . '</td>
<td valign="top" width="60%" align="left"><label><input type="radio" name="' . $name . '" value="yes"' . (isset ($yescheck) ? $yescheck : '') . ('' . ' />&nbsp;Yes</label> &nbsp;&nbsp;<label><input type="radio" name="' . $name . '" value="no"') . (isset ($nocheck) ? $nocheck : '') . ' />&nbsp;No</label></td>
</tr>
';
  }

  function inputbox ($title, $name, $value = '', $class = 'specialboxnn', $size = '25', $extra = '', $maxlength = '', $autocomplete = 1, $extra2 = '')
  {
    $value = htmlspecialchars_uni ($value);
    if ($autocomplete != 1)
    {
      $ac = ' autocomplete="off"';
    }
    else
    {
      $ac = '';
    }

    if ($value != '')
    {
      $value = ('' . ' value="' . $value . '"');
    }

    if ($maxlength != '')
    {
      $maxlength = ('' . ' maxlength="' . $maxlength . '"');
    }

    if ($size != '')
    {
      $size = ('' . ' size="' . $size . '"');
    }

    echo ('' . '<tr>
<td valign="top" width="40%" align="right">' . $title . '</td>
<td valign="top" width="60%" align="left">
' . $extra2 . '<input type="text" id="' . $class . '" name="' . $name . '"') . $size . $maxlength . $ac . $value . ' />
' . $extra . '
</td>
</tr>
';
  }

  function selectbox ($title, $name, $type, $class = 'specialboxnn')
  {
    global $userdata;
    global $usergroups;
    echo '' . '<tr>
<td valign="top" width="40%" align="right">' . $title . '</td><td valign="top" width="60%" align="left">
<select name="' . $name . '" id="' . $class . '">
';
    if ($type == 'trackergroups')
    {
      $query = sql_query ('SELECT gid,title,cansettingspanel,issupermod,canstaffpanel FROM usergroups');
      while ($tclass = mysql_fetch_array ($query))
      {
        if (((((($tclass['cansettingspanel'] == 'yes' AND $usergroups['cansettingspanel'] != 'yes') OR ($tclass['issupermod'] == 'yes' AND $usergroups['issupermod'] != 'yes')) OR ($tclass['canstaffpanel'] == 'yes' AND $usergroups['canstaffpanel'] != 'yes')) OR (($tclass['cansettingspanel'] == 'yes' OR $tclass['issupermod'] == 'yes') AND $usergroups['cansettingspanel'] != 'yes')) OR (($tclass['gid'] == UC_ADMINISTRATOR OR $tclass['gid'] == UC_SYSOP) AND $usergroups['cansettingspanel'] != 'yes')))
        {
          continue;
        }

        echo '<option value="' . $tclass['gid'] . '" ' . ($userdata['usergroup'] == $tclass['gid'] ? 'selected' : '') . '>' . $tclass['title'] . '</option>';
      }
    }

    echo '</select>
</td>
</tr>
';
  }

  function get_user_data ()
  {
    global $userid;
    $res = sql_query ('SELECT u.*, g.cansettingspanel, g.canstaffpanel, g.issupermod FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id = ' . sqlesc ($userid));
    $arr = mysql_fetch_array ($res);
    if (!$arr)
    {
      stderr ('Error', 'No user with this ID!');
    }

    $query = sql_query ('SELECT supportfor, supportlang FROM ts_support WHERE userid = ' . sqlesc ($userid));
    if (0 < mysql_num_rows ($query))
    {
      $supportresult = mysql_fetch_assoc ($query);
      $temparray = array_merge ($arr, $supportresult);
      $arr = $temparray;
      unset ($temparray);
    }

    $GLOBALS['userdata'] = $arr;
  }

  function username_exists ($username)
  {
    $tracker_query = sql_query ('SELECT username FROM users WHERE username=' . sqlesc ($username) . ' LIMIT 1');
    if (1 <= mysql_num_rows ($tracker_query))
    {
      return false;
    }

    return true;
  }

  function validusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  function email_exists ($email)
  {
    $tracker_query = sql_query ('SELECT email FROM users WHERE email=' . sqlesc ($email) . ' LIMIT 1');
    if (1 <= mysql_num_rows ($tracker_query))
    {
      return false;
    }

    return true;
  }

  function modcomment ($what = 'Unknown Action taken')
  {
    global $modcomment;
    global $CURUSER;
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

    return gmdate ('Y-m-d') . ' - ' . $what . ' by ' . $CURUSER['username'] . $eol . $modcomment;
  }

  function update_ipban_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM ipbans');
    $_ucache = mysql_fetch_assoc ($query);
    $content = var_export ($_ucache, true);
    $_filename = TSDIR . '/' . $cache . '/ipbans.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#6 - Do Not Alter
 * Cache Name: IPBans
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$ipbanscache = ' . $content . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  $rootpath = './../';
  include $rootpath . '/global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('VERSION', 'Edit User Mod v.1.8.3 by xam');
  define ('NcodeImageResizer', true);
  $action = (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : ''));
  $do = (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : ''));
  $userid = (isset ($_POST['userid']) ? (int)$_POST['userid'] : (isset ($_GET['userid']) ? (int)$_GET['userid'] : ''));
  if ($usergroups['canuserdetails'] != 'yes')
  {
    print_no_permission (true);
  }

  if (((empty ($action) OR empty ($userid)) OR !is_valid_id ($userid)))
  {
    print_no_permission (true);
  }

  int_check ($userid, true);
  $lang->load ('modtask');
  require_once INC_PATH . '/functions_mkprettytime.php';
  if ($action == 'edituser')
  {
    define ('WYSIWYG_EDITOR', true);
    define ('USE_BB_CODE', true);
    define ('USE_SMILIES', true);
    define ('USE_HTML', false);
    require $thispath . 'wysiwyg/wysiwyg.php';
    get_user_data ();
    permission_check ();
    stdhead ('Edit User: ' . $userdata['username'] . ' (UID: ' . $userdata['id'] . ')');
    $where = array ('Cancel' => $BASEURL . '/userdetails.php?id=' . $userdata['id'], 'User Threads' => $BASEURL . '/tsf_forums/tsf_search.php?action=finduserthreads&id=' . $userdata['id'], 'User Posts' => $BASEURL . '/tsf_forums/tsf_search.php?action=finduserposts&id=' . $userdata['id'], 'Reset Passkey' => $_SERVER['SCRIPT_NAME'] . '?action=resetpasskey&userid=' . $userdata['id'], 'IP Info' => $BASEURL . '/admin/index.php?act=ip_info&userid=' . $userdata['id'], 'Insert Bad Users' => $BASEURL . '/badusers.php?act=insert&do=save&username=' . urlencode (htmlspecialchars_uni ($userdata['username'])) . '&email=' . urlencode (htmlspecialchars_uni ($userdata['email'])) . '&ipaddress=' . urlencode (htmlspecialchars_uni ($userdata['ip'])) . '&userid=' . intval ($userdata['id']) . '&comment=' . urlencode ('Bad User'), 'Delete' => $_SERVER['SCRIPT_NAME'] . '?action=deleteaccount&userid=' . $userdata['id']);
    echo jumpbutton ($where);
    echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="updateuser" ' . submit_disable ('updateuser', 'updateuser') . '>
	<input type="hidden" name="userid" value="' . $userdata['id'] . '">
	<input type="hidden" name="action" value="updateuser">';
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Modify User Account for ' . $userdata['username'] . ' (UID: ' . $userdata['id'] . ')</td></tr>';
    echo '<tr class="subheader"><td align="center" colspan="2">Required Information</td></tr>';
    inputbox ('<b>Username:</b>', 'username', $userdata['username']);
    inputbox ('<b>New Password:</b>', 'password');
    inputbox ('<b>Email Address:</b>', 'email', $userdata['email']);
    selectbox ('<b>Tracker Usergroup:</b>', 'usergroup', 'trackergroups');
    echo '<tr class="subheader"><td align="center" colspan="2">Personal Informations</td></tr>';
    inputbox ('<b>User Title:</b>', 'title', htmlspecialchars_uni ($userdata['title']));
    inputbox ('<b>User Avatar:</b>', 'avatar', htmlspecialchars_uni ($userdata['avatar']), 'specialboxg', 25, get_user_avatar ($userdata['avatar'], true));
    print '<tr><td align=\'right\' valign=\'top\'><b>User Signature:</b></td><td colspan=\'2\' align=\'left\'><textarea cols=\'60\' rows=\'6\' name=\'signature\' id=\'specialboxg\' />' . htmlspecialchars_uni ($userdata['signature']) . '</textarea><br />' . format_comment ($userdata['signature']) . '</td></tr>
';
    echo '<tr class="subheader"><td align="center" colspan="2">Account Preferences</td></tr>';
    yesno ('<b>Is Donor:</b>', 'donor', ($userdata['donor'] == 'yes' ? 'yes' : 'no'));
    echo '<tr><td colspan="1" align="right" valign="top"><b>Donor Status:</b></td>';
    echo '<td colspan="3" align="right">
	<table width="100%">';
    echo '<tr><td align="right">Current Donation:&nbsp;</td>
	<td align="left"><input type="text" id="specialboxes" size="6" name="donated" value="' . htmlspecialchars_uni ($userdata['donated']) . '">
	<input type="checkbox" name="update_funds_d" value="yes">&nbsp;Update Funds.
	</td></tr>';
    echo '<tr><td align=right>Total Donations:&nbsp;</td>
	<td align=left><input type="text" id="specialboxes" size="6" name="total_donated" value="' . htmlspecialchars_uni ($userdata['total_donated']) . '">	
	</td></tr>';
    if ($userdata['donor'] == 'yes')
    {
      echo '<tr><td align=right>Add to donor time:&nbsp;</td>
		<td align=left>
		<select name="donorlengthadd" id="specialboxs">
		' . months () . '
		</select></td></tr>';
      echo '<tr><td align=right>Remove Donor Status:&nbsp;</td>
		<td align=left>
		<input name="removedonorstatus" value="yes" type="checkbox"> Remove donor status if they were bad.
		</td></tr>';
      $donoruntil = $userdata['donoruntil'];
      if ($donoruntil == '0000-00-00 00:00:00')
      {
        echo '<tr><td align=right>Donated Status Until:&nbsp;</td>
		<td align=left><font color=red>Arbitrary duration.</font></td></tr>';
      }
      else
      {
        echo '<tr><td align=right>Donated Status Until:&nbsp;</td>
			<td align=left>' . $donoruntil . ' [ ' . mkprettytime (strtotime ($donoruntil) - gmtime ()) . ' ] to go</td></tr>';
      }
    }
    else
    {
      echo '<tr>
		<td align=right>Donor For:&nbsp;</td>
		<td align=left>
		<select name="donorlength" id="specialboxs">
		' . months () . '
		</select></td></tr>';
    }

    echo '</td></table></tr>';
    $warned = $userdata['warned'] == 'yes';
    echo '<tr><td colspan="1" align="right" valign="top"><b>Warning System:</b></td>';
    echo '<td colspan="3" align="right">
	<table width="100%">';
    echo '<tr><td align="right">Is user warned?&nbsp;</td>
	<td align="left">' . ($warned ? '<input name="warned" value="yes" type="radio" checked="checked">Yes<input name="warned" value="no" type="radio">No' : '&nbsp;Not Warned Yet!') . '</td></tr>';
    if ($warned)
    {
      echo '<tr><td align="right">Warned Until:&nbsp;</td>';
      $warneduntil = $userdata['warneduntil'];
      if ($warneduntil == '0000-00-00 00:00:00')
      {
        print '<td align=\'left\'><font color=\'red\'>(Arbitrary duration)</font></td></tr>
';
      }
      else
      {
        echo '<td align="left">' . $warneduntil . ' (' . mkprettytime (strtotime ($warneduntil) - gmtime ()) . ' to go)</td></tr>';
      }
    }
    else
    {
      echo '<tr><td align=right>Warn User:&nbsp;</td>';
      print '<td align=left><select name=\'warnlength\' id=specialboxs>
';
      print weeks ();
      print '</select></td></tr>
';
      echo '<tr><td align=right>Reason of Warning:&nbsp;</td>';
      print '<td align=left><input type=text size=60 name=\'warnpm\' id=specialboxn></td></tr>';
    }

    $elapsedlw = mkprettytime (time () - strtotime ($userdata['lastwarned']));
    include_once INC_PATH . '/readconfig_cleanup.php';
    echo '<tr><td align=right>Times Warned:&nbsp;</td><td align=left>&nbsp;' . $userdata['timeswarned'] . ' (Max: ' . $ban_user_limit . ' then we ban) 
	' . (1 < $userdata['timeswarned'] ? '<input type="checkbox" name="reset_timeswarned" value="yes"> Check to reset!' : '') . '</td></tr>';
    if (($userdata['timeswarned'] == 0 OR empty ($userdata['warnedby'])))
    {
      echo '<tr><td align=right>Last Warning:&nbsp;</td><td align=left>&nbsp;This user hasn\'t been warned yet.</td></tr>';
    }
    else
    {
      if (($userdata['warnedby'] != 'System' AND !empty ($userdata['warnedby'])))
      {
        ($res = sql_query ('SELECT id, username, warnedby FROM users WHERE id = ' . $userdata['warnedby']) OR sqlerr (__FILE__, 343));
        $arr = mysql_fetch_array ($res);
        $warnedby = ' by <a href=' . $BASEURL . '/userdetails.php?id=' . $arr['id'] . '>' . $arr['username'] . '</a>.';
      }
      else
      {
        $warnedby = ' Automatic Warn by System.';
      }

      echo '<tr><td align=right>Last Warning:&nbsp;</td><td align=left>&nbsp;' . $elapsedlw . ' ago ' . $warnedby . '</td></tr>';
    }

    $leechwarn = $userdata['leechwarn'] == 'yes';
    echo '<tr><td align=right>Auto Leech Warning:&nbsp;</td>';
    if ($leechwarn)
    {
      print '<td align=left>&nbsp;<font color=red>Yes, Warned (Low Ratio).</font></td>
';
      $leechwarnuntil = $userdata['leechwarnuntil'];
      if ($leechwarnuntil != '0000-00-00 00:00:00')
      {
        echo '<tr><td align=right>Warned Until:&nbsp;</td><td align=left>&nbsp;' . $leechwarnuntil . ' (' . mkprettytime (strtotime ($leechwarnuntil) - gmtime ()) . ' to go)</td>';
      }
      else
      {
        print '<tr><td align=right>Warned Until:&nbsp;</td><td align=left>&nbsp;UNLIMITED!</i></td></tr>
';
      }
    }
    else
    {
      print '<td align=left>&nbsp;No, Not Warned yet.</td>
';
    }

    echo '</td></table></tr>';
    $supportfor = htmlspecialchars_uni ($userdata['supportfor']);
    $supportlang = htmlspecialchars_uni ($userdata['supportlang']);
    print '' . '<tr><td class=rowhead>Support Language:</td><td colspan=2 align=left><input type=text name=supportlang value=\'' . $supportlang . '\' id=\'specialboxn\' /></td></tr>
';
    print '' . '<tr><td class=rowhead>Support for:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor id=specialboxg />' . $supportfor . '</textarea></td></tr>
';
    $modcomment = htmlspecialchars_uni ($userdata['modcomment']);
    if ($usergroups['cansettingspanel'] != 'yes')
    {
      print '' . '<tr><td class=rowhead>Comment:</td><td colspan=2 align=left><textarea cols=60 rows=18 name=modcomment id=specialboxg READONLY>' . $modcomment . '</textarea></td></tr>
';
    }
    else
    {
      print '' . '<tr><td class=rowhead>Comment</td><td colspan=2 align=left><textarea cols=60 rows=18 name=modcomment id=specialboxg>' . $modcomment . '</textarea></td></tr>
';
    }

    print '<tr><td class=rowhead>Add&nbsp;Comment:</td><td colspan=2 align=left><textarea cols=60 rows=2 name=addcomment id=specialboxg></textarea></td></tr>
';
    $bonuscomment = htmlspecialchars_uni ($userdata['bonuscomment']);
    print '' . '<tr><td class=rowhead>Seeding Karma:</td><td colspan=2 align=left><textarea cols=60 rows=6 id=specialboxg name=bonuscomment READONLY>' . $bonuscomment . '</textarea></td></tr>
';
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions</td></tr>';
    print '<tr><td class=rowhead>Account Enabled?</td><td colspan=\'2\' align=\'left\'><span style=\'float: right\'>Ban/Unban Ip? <select name=\'banip\'><option value=\'yes\'>Yes</option><option value=\'no\'>No</option></select></span><input name=\'enabled\' value=\'yes\' type=\'radio\'' . ($userdata['enabled'] == 'yes' ? ' checked' : '') . '>Yes <input name=\'enabled\' value=\'no\' type=\'radio\'' . ($userdata['enabled'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    if ($userdata['enabled'] == 'no')
    {
      echo '' . '<tr><td class=rowhead>Ban Reason:</td><td colspan=2 align=left>' . $userdata['notifs'] . '</td></tr>';
    }

    ($query = sql_query ('SELECT canupload, candownload, cancomment, canmessage, canshout FROM ts_u_perm WHERE userid = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 393));
    if (0 < mysql_num_rows ($query))
    {
      $permresults = mysql_fetch_assoc ($query);
      $userdata['cancomment'] = ($permresults['cancomment'] == 1 ? 'yes' : 'no');
      $userdata['canmessage'] = ($permresults['canmessage'] == 1 ? 'yes' : 'no');
      $userdata['canshout'] = ($permresults['canshout'] == 1 ? 'yes' : 'no');
      $userdata['canupload'] = ($permresults['canupload'] == 1 ? 'yes' : 'no');
      $userdata['candownload'] = ($permresults['candownload'] == 1 ? 'yes' : 'no');
    }
    else
    {
      $userdata['cancomment'] = $userdata['canmessage'] = $userdata['canshout'] = $userdata['canupload'] = $userdata['candownload'] = 'yes';
    }

    print '<tr><td class=rowhead>Torrent Comment possible?</td><td colspan=2 align=left><input type=radio name=cancomment value=yes' . ($userdata['cancomment'] == 'yes' ? ' checked' : '') . '>Yes <input type=radio name=cancomment value=no' . ($userdata['cancomment'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    print '<tr><td class=rowhead>Send message possible?</td><td colspan=2 align=left><input type=radio name=canmessage value=yes' . ($userdata['canmessage'] == 'yes' ? ' checked' : '') . '>Yes <input type=radio name=canmessage value=no' . ($userdata['canmessage'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    print '<tr><td class=rowhead>Shoutbox post possible?</td><td colspan=2 align=left><input type=radio name=canshout value=yes' . ($userdata['canshout'] == 'yes' ? ' checked' : '') . '>Yes <input type=radio name=canshout value=no' . ($userdata['canshout'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    print '<tr><td class=rowhead>Upload possible?</td><td colspan=2 align=left><input type=radio name=canupload value=yes' . ($userdata['canupload'] == 'yes' ? ' checked' : '') . '>Yes <input type=radio name=canupload value=no' . ($userdata['canupload'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    print '<tr><td class=rowhead>Download possible?</td><td colspan=2 align=left><input type=radio name=candownload value=yes' . ($userdata['candownload'] == 'yes' ? ' checked' : '') . '>Yes <input type=radio name=candownload value=no' . ($userdata['candownload'] == 'no' ? ' checked' : '') . '>No</td></tr>
';
    print '</td></tr>';
    $othervalue = ' disabled=\\"disabled\\" ';
    if (($usergroups['cansettingspanel'] == 'yes' OR $usergroups['issupermod'] == 'yes'))
    {
      $othervalue = '';
    }

    echo '<tr class="subheader"><td align="center" colspan="2">Other</td></tr>';
    print '<tr><td class=rowhead>Bonus Points:</td><td colspan=2 align=left><input type="text" name="seedbonus" size="50" id="specialboxn" value="' . htmlspecialchars_uni ($userdata['seedbonus']) . ('' . '" ' . $othervalue . '/></td></tr>
');
    print '<tr><td class=rowhead>Invites:</td><td colspan=2 align=left><input type="text" name="invites" size="50" id="specialboxn" value="' . (int)$userdata['invites'] . ('' . '" ' . $othervalue . '/></td></tr>
');
    print '<tr><td class=rowhead>Amount Uploaded:</td><td colspan=2 align=left><input type=text size=60 name=uploaded value="' . htmlspecialchars_uni ($userdata['uploaded']) . ('' . '" id="specialboxn" ' . $othervalue . '/> (') . mksize ($userdata['uploaded']) . ')</tr>
';
    print '<tr><td class=rowhead>Amount Downloaded:</td><td colspan=2 align=left><input type=text size=60 name=downloaded value="' . htmlspecialchars_uni ($userdata['downloaded']) . ('' . '" id="specialboxn" ' . $othervalue . '/> (') . mksize ($userdata['downloaded']) . ')</tr>
';
    print '<tr><td colspan=3 align=right><input type=\'submit\' class=button value=\'Update User\' name=\'updateuser\'> <input type=reset class=button value=\'Reset\'></td></tr></form>
';
    echo '</form></table></table>';
    stdfoot ();
    return 1;
  }

  if ($action == 'updateuser')
  {
    get_user_data ();
    permission_check ();
    require INC_PATH . '/functions_getvar.php';
    getvar (array ('username', 'password', 'email', 'usergroup', 'title', 'avatar', 'signature', 'donor', 'warned', 'warnlength', 'warnpm', 'supportlang', 'supportfor', 'addcomment', 'enabled', 'seedbonus', 'invites', 'uploaded', 'downloaded', 'reset_timeswarned'));
    $modcomment = $userdata['modcomment'];
    if (($username != $userdata['username'] AND (validusername ($username) AND username_exists ($username))))
    {
      $tu[] = 'username = ' . sqlesc ($username);
      $modcomment = modcomment ('Username (' . $userdata['username'] . ') changed to (' . htmlspecialchars_uni ($username) . ')');
    }

    if (!empty ($password))
    {
      $sec = mksecret ();
      $passhash = md5 ($sec . $password . $sec);
      $tu[] = 'secret = ' . sqlesc ($sec);
      $tu[] = 'passhash = ' . sqlesc ($passhash);
      $modcomment = modcomment ('Password updated');
    }

    require_once INC_PATH . '/functions_EmailBanned.php';
    if (($email != $userdata['email'] AND (!emailbanned ($email) AND check_email ($email))))
    {
      $tu[] = 'email = ' . sqlesc ($email);
      $modcomment = modcomment ('Email (' . $userdata['email'] . ') changed to (' . htmlspecialchars_uni ($email) . ')');
    }

    if (($usergroup != $userdata['usergroup'] AND is_valid_id ($usergroup)))
    {
      $tu[] = 'usergroup = ' . sqlesc ($usergroup);
      $modcomment = modcomment ('Usergroup (' . $userdata['usergroup'] . ') changed to (' . intval ($usergroup) . ')');
    }

    if ($title != $userdata['title'])
    {
      $tu[] = 'title = ' . sqlesc ($title);
      $modcomment = modcomment ('Title (' . htmlspecialchars_uni ($userdata['title']) . ') changed to (' . htmlspecialchars_uni ($title) . ')');
    }

    if ($avatar != $userdata['avatar'])
    {
      $tu[] = 'avatar = ' . sqlesc ($avatar);
      $modcomment = modcomment ('Avatar (' . htmlspecialchars_uni ($userdata['avatar']) . ') updated to (' . htmlspecialchars_uni ($avatar) . ')');
    }

    if ($signature != $userdata['signature'])
    {
      $tu[] = 'signature = ' . sqlesc ($signature);
      $modcomment = modcomment ('Signature (' . htmlspecialchars_uni ($userdata['signature']) . ') updated to (' . htmlspecialchars_uni ($signature) . ')');
    }

    if ($donor != $userdata['donor'])
    {
      $tu[] = 'donor = ' . sqlesc ($donor);
      $modcomment = modcomment ('Donor Status (' . htmlspecialchars_uni ($userdata['donor']) . ') updated to (' . htmlspecialchars_uni ($donor) . ')');
    }

    if ((isset ($_POST['donated']) AND $donated = $_POST['donated'] != $userdata['donated']))
    {
      if ((isset ($_POST['update_funds_d']) AND $_POST['update_funds_d'] == 'yes'))
      {
        $added = sqlesc (get_date_time ());
        (sql_query ('' . 'INSERT INTO funds (cash, user, added) VALUES (' . $donated . ', ' . $userid . ', ' . $added . ')') OR sqlerr (__FILE__, 505));
      }

      $tu[] = 'donated = ' . sqlesc ($donated);
      $modcomment = modcomment ('Donor Amount (' . htmlspecialchars_uni ($userdata['donated']) . ') changed to (' . htmlspecialchars_uni ($donated) . ')');
    }

    if ((isset ($_POST['total_donated']) AND $total_donated = $_POST['total_donated'] != $userdata['total_donated']))
    {
      $tu[] = '' . 'total_donated = ' . $total_donated;
    }

    if ((isset ($_POST['donorlength']) AND 0 < $donorlength = intval ($_POST['donorlength'])))
    {
      if ($donorlength == 255)
      {
        $modcomment = sprintf ($lang->modtask['modcommentdonorstatus'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
        $msg = sprintf ($lang->modtask['donorstatusmessage'], $CURUSER['username']);
        $subject = $lang->modtask['donorstatussubject'];
        $tu[] = 'donoruntil = \'0000-00-00 00:00:00\'';
      }
      else
      {
        $donoruntil = get_date_time (gmtime () + $donorlength * 604800);
        $dur = sprintf ($lang->modtask['weeks'], $donorlength);
        $msg = sprintf ($lang->modtask['donorstatusmessage2'], $userdata['username'], $SITENAME, $dur, $CURUSER['username']);
        $subject = $lang->modtask['donorstatussubject'];
        $modcomment = sprintf ($lang->modtask['modcommentdonorstatus2'], gmdate ('Y-m-d'), $dur, $CURUSER['username'], $modcomment);
        $tu[] = 'donoruntil = ' . sqlesc ($donoruntil);
      }

      $tu[] = 'donor = \'yes\'';
      $tu[] = 'usergroup = \'' . UC_VIP . '\'';
      insert_message ($userid, $msg, $subject);
    }

    if ((isset ($_POST['donorlengthadd']) AND 0 < $donorlengthadd = intval ($_POST['donorlengthadd'])))
    {
      $donoruntil = $userdata['donoruntil'];
      $dur = sprintf ($lang->modtask['weeks'], $donorlengthadd);
      $msg = sprintf ($lang->modtask['donorstatusmessage3'], $userdata['username'], $SITENAME, $dur, $CURUSER['username']);
      $subject = $lang->modtask['donorstatussubject2'];
      $modcomment = sprintf ($lang->modtask['modcommentdonorstatus3'], gmdate ('Y-m-d'), $dur, $CURUSER['username'], $modcomment);
      $donorlengthadd = $donorlengthadd * 7;
      (sql_query ('' . 'UPDATE users SET donoruntil = IF(donoruntil=\'0000-00-00 00:00:00\', ADDDATE(NOW(), INTERVAL ' . $donorlengthadd . ' DAY ), ADDDATE( donoruntil, INTERVAL ' . $donorlengthadd . ' DAY)) WHERE id = ' . sqlesc ($userdata['id'])) OR sqlerr (__FILE__, 550));
      insert_message ($userid, $msg, $subject);
      write_log ('Donor status changed by ' . $CURUSER['username'] . '. User: ' . $userdata['username']);
    }

    if ((isset ($_POST['removedonorstatus']) AND $_POST['removedonorstatus'] == 'yes'))
    {
      $tu[] = 'donor = \'no\'';
      $tu[] = 'donoruntil = \'0000-00-00 00:00:00\'';
      $tu[] = 'donated = \'0\'';
      $tu[] = 'usergroup = \'' . UC_POWERUSER . '\'';
      $modcomment = sprintf ($lang->modtask['modcommentdonorstatusremoved'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
      $msg = $lang->modtask['donorstatusremovedmessage'];
      $subject = $lang->modtask['donorstatusremovedsubject'];
      insert_message ($userid, $msg, $subject);
      write_log ('Donor status changed by ' . $CURUSER['username'] . '. User: ' . $userdata['username']);
    }

    if ($_POST['reset_timeswarned'] == 'yes')
    {
      $tu[] = 'timeswarned = 1';
      $modcomment = modcomment ('Warning Count Reset');
    }

    if (($warned == 'no' AND $userdata['warned'] == 'yes'))
    {
      $tu[] = 'warned = \'no\'';
      $tu[] = 'warneduntil = \'0000-00-00 00:00:00\'';
      $modcomment = sprintf ($lang->modtask['modcommentwarningremovedby'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
      $subject = $lang->modtask['warningremovedbysubject'];
      $msg = sprintf ($lang->modtask['warningremovedbymessage'], $CURUSER['username']);
      insert_message ($userid, $msg, $subject);
    }
    else
    {
      if ((is_valid_id ($warnlength) AND $userdata['warned'] == 'no'))
      {
        if (empty ($warnpm))
        {
          $warnpm = 'No Reason Given.';
        }

        if ($warnlength == '255')
        {
          $modcomment = sprintf ($lang->modtask['modcommentwarning'], gmdate ('Y-m-d'), $CURUSER['username'], $warnpm, $modcomment);
          $msg = sprintf ($lang->modtask['warningmessage'], $CURUSER['username'], $warnpm);
          $tu[] = 'warneduntil = \'0000-00-00 00:00:00\'';
        }
        else
        {
          $warneduntil = get_date_time (gmtime () + $warnlength * 604800);
          $dur = sprintf ($lang->modtask['weeks'], $warnlength);
          $msg = sprintf ($lang->modtask['warningmessage2'], $dur, $CURUSER['username'], $warnpm);
          $modcomment = sprintf ($lang->modtask['modcommentwarning2'], gmdate ('Y-m-d'), $dur, $CURUSER['username'], $warnpm, $modcomment);
          $tu[] = 'warneduntil = ' . sqlesc ($warneduntil);
        }

        $subject = $lang->modtask['warningsubject'];
        $lastwarned = sqlesc (get_date_time ());
        $tu[] = '' . 'warned = \'yes\', timeswarned = timeswarned + 1, lastwarned = ' . $lastwarned . ', warnedby = ' . $CURUSER['id'];
        insert_message ($userid, $msg, $subject);
      }
    }

    if (((isset ($supportfor) AND $supportfor != $userdata['supportfor']) OR (isset ($supportlang) AND $supportlang != $userdata['supportlang'])))
    {
      if (($supportfor == '' OR $supportlang == ''))
      {
        (sql_query ('DELETE FROM ts_support WHERE userid = ' . sqlesc ($userdata['id'])) OR sqlerr (__FILE__, 613));
      }
      else
      {
        (sql_query ('UPDATE ts_support SET supportfor = ' . sqlesc ($supportfor) . ', supportlang = ' . sqlesc ($supportlang) . ' WHERE userid = ' . sqlesc ($userdata['id'])) OR sqlerr (__FILE__, 617));
        if (!mysql_affected_rows ())
        {
          (sql_query ('INSERT INTO ts_support VALUES (\'\', ' . sqlesc ($userdata['id']) . ', ' . sqlesc ($supportfor) . ', ' . sqlesc ($supportlang) . ')') OR sqlerr (__FILE__, 620));
        }
      }

      $modcomment = modcomment ('Support Status Changed');
    }

    if ((isset ($addcomment) AND !empty ($addcomment)))
    {
      $modcomment = gmdate ('Y-m-d') . ' - ' . $addcomment . ' - ' . $CURUSER['username'] . '.
' . $modcomment;
    }

    if ((isset ($enabled) AND $enabled != $userdata['enabled']))
    {
      $modifier = (int)$CURUSER['id'];
      if ($enabled == 'yes')
      {
        $nowdate = sqlesc (get_date_time ());
        $modcomment = sprintf ($lang->modtask['modcommentunbanned'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
        if (1 < $userdata['timeswarned'])
        {
          $timeswarned = 'timeswarned = timeswarned -1';
        }
        else
        {
          $timeswarned = 'timeswarned = 1';
        }

        if ($userdata['leechwarn'] == 'yes')
        {
          $tu[] = 'usergroup = \'' . UC_USER . ('' . '\', downloaded = \'100\', uploaded = \'100\', ' . $timeswarned . ', last_access=' . $nowdate . ', leechwarn = \'no\', leechwarnuntil = \'0000-00-00 00:00:00\', notifs=\'\'');
          $modcomment = gmdate ('Y-m-d') . (('' . ' - Old DL&UL stats was: UL: ' . $userdata['uploaded'] . ' - DL: ' . $userdata['downloaded'] . '
') . $modcomment);
        }
        else
        {
          $tu[] = 'usergroup = \'' . UC_USER . ('' . '\', last_access=' . $nowdate . ', ' . $timeswarned . ', notifs=\'\'');
        }

        $bans = sql_query ('SELECT value FROM ipbans LIMIT 1');
        if (1 <= mysql_num_rows ($bans))
        {
          $banned = mysql_fetch_array ($bans);
          $value = str_replace ($userdata['ip'], '', $banned['value']);
          sql_query ('UPDATE ipbans SET value=' . sqlesc ($value) . ('' . ', date=' . $nowdate . ', modifier=') . sqlesc ($modifier));
          update_ipban_cache ();
        }

        $subject = 'You have been unbanned!';
        $message = 'Hi ' . $userdata['username'] . ',

			Your account has been unbanned by ' . $CURUSER['username'] . '.
			Feel free to come back again at any time.

			Kind regards,
			' . $SITENAME . ' Team,
			' . $BASEURL;
        sent_mail ($userdata['email'], $subject, $message, 'unban', false);
        write_log ('User ' . $userdata['username'] . ' has been un-banned by ' . $CURUSER['username']);
      }
      else
      {
        $modcomment = '{TSBANNED} ' . sprintf ($lang->modtask['modcommentbanned'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
        if ((isset ($_POST['banip']) AND $_POST['banip'] == 'yes'))
        {
          $bans = sql_query ('SELECT value FROM ipbans LIMIT 1');
          $date = sqlesc (get_date_time ());
          $dateline = sqlesc (time ());
          if (1 <= mysql_num_rows ($bans))
          {
            $banned = mysql_fetch_array ($bans);
            $value = sqlesc ('' . $banned['value'] . ' ' . $userdata['ip']);
            sql_query ('' . 'UPDATE ipbans SET value=' . $value . ', date=' . $date . ', modifier=' . sqlesc ($modifier));
            update_ipban_cache ();
          }
          else
          {
            $value = sqlesc ('' . $userdata['ip']);
            sql_query ('' . 'INSERT INTO ipbans (value,date,modifier) VALUES (' . $value . ', ' . $date . ', ' . sqlesc ($modifier) . ')');
            update_ipban_cache ();
          }
        }

        $tu[] = 'usergroup = \'' . UC_BANNED . '\'';
        write_log ('User ' . $userdata['username'] . ' has been banned by ' . $CURUSER['username']);
      }

      $tu[] = 'enabled = ' . sqlesc ($enabled);
    }

    $updateperm = array ();
    $updateperm['cancomment'] = ($_POST['cancomment'] == 'yes' ? 1 : 0);
    $updateperm['canmessage'] = ($_POST['canmessage'] == 'yes' ? 1 : 0);
    $updateperm['canshout'] = ($_POST['canshout'] == 'yes' ? 1 : 0);
    $updateperm['canupload'] = ($_POST['canupload'] == 'yes' ? 1 : 0);
    $updateperm['candownload'] = ($_POST['candownload'] == 'yes' ? 1 : 0);
    (sql_query ('' . 'REPLACE INTO ts_u_perm (userid, cancomment, canmessage, canshout, canupload, candownload) VALUES (' . $userid . ', ' . $updateperm['cancomment'] . ', ' . $updateperm['canmessage'] . ', ' . $updateperm['canshout'] . ', ' . $updateperm['canupload'] . ', ' . $updateperm['candownload'] . ')') OR sqlerr (__FILE__, 710));
    if ((isset ($seedbonus) AND $userdata['seedbonus'] != $seedbonus))
    {
      $tu[] = 'seedbonus = ' . sqlesc ($seedbonus);
      $modcomment = modcomment ('Seed Bonus (' . htmlspecialchars_uni ($userdata['seedbonus']) . ') changed to (' . htmlspecialchars_uni ($seedbonus) . ')');
    }

    if ((isset ($invites) AND $userdata['invites'] != $invites))
    {
      $tu[] = 'invites = ' . sqlesc ($invites);
      $modcomment = modcomment ('Invites (' . intval ($userdata['invites']) . ') changed to (' . intval ($invites) . ')');
    }

    if ((isset ($uploaded) AND $userdata['uploaded'] != $uploaded))
    {
      $tu[] = 'uploaded = ' . sqlesc ($uploaded);
      $modcomment = modcomment ('Upload Amount (' . mksize ($userdata['uploaded']) . ') changed to (' . mksize ($uploaded) . ')');
    }

    if ((isset ($downloaded) AND $userdata['downloaded'] != $downloaded))
    {
      $tu[] = 'downloaded = ' . sqlesc ($downloaded);
      $modcomment = modcomment ('Download Amount (' . mksize ($userdata['downloaded']) . ') changed to (' . mksize ($downloaded) . ')');
    }

    if ((((isset ($tu) AND !empty ($userdata['id'])) AND is_valid_id ($userdata['id'])) OR ((!empty ($addcomment) AND !empty ($userdata['id'])) AND is_valid_id ($userdata['id']))))
    {
      if ((isset ($modcomment) AND $modcomment != $userdata['modcomment']))
      {
        $tu[] = 'modcomment = ' . sqlesc ($modcomment);
      }

      (sql_query ('UPDATE users SET  ' . implode (', ', $tu) . ' WHERE id=' . sqlesc ($userdata['id'])) OR sqlerr (__FILE__, 743));
      write_log ('User ' . $userdata['username'] . ' has been edited by ' . $CURUSER['username']);
    }

    if (($enabled == 'no' AND $enabled != $userdata['enabled']))
    {
      stdhead ('Ban User');
      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?action=banaccount&userid=' . $userid . '">
		<input type="hidden" name="action" value="banaccount">
		<input type="hidden" name="userid" value="' . $userid . '">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="thead">Ban User</td>
		</tr>
		<tr>
			<td>Reason to show the user: <input type="text" name="reason" size="30"> <input type="submit" value="Ban User"></td>
		</tr>
		</table>
		</form>
		';
      stdfoot ();
      exit ();
      return 1;
    }

    redirect ('admin/edituser.php?action=edituser&userid=' . $userid, 'The account (' . $userid . ') has been updated...');
    return 1;
  }

  if ($action == 'banaccount')
  {
    get_user_data ();
    permission_check ();
    $reason = trim ($_POST['reason']);
    sql_query ('UPDATE users SET notifs = ' . sqlesc ('Reason: ' . $reason) . ' WHERE id = ' . sqlesc ($userdata['id']));
    write_log ('Following user banned by ' . $CURUSER['username'] . '. User: ' . $userdata['username'] . ' Reason: ' . htmlspecialchars_uni ($reason));
    redirect ('admin/edituser.php?action=edituser&userid=' . $userid, 'The account (' . $userid . ') has been updated...');
    return 1;
  }

  if ($action == 'deleteaccount')
  {
    if (($usergroups['issupermod'] != 'yes' AND $usergroups['cansettingspanel'] != 'yes'))
    {
      print_no_permission ();
    }

    get_user_data ();
    permission_check ();
    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = 'Are you sure to delete following account: ' . $userdata['id'] . ' - <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=deleteaccount&userid=' . $userdata['id'] . '&sure=1&hash={1}">YES</a> / <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=edituser&userid=' . $userdata['id'] . '">NO</a>';
    $ts_token->redirect = $_SERVER['SCRIPT_NAME'] . ('' . '?action=deleteaccount&userid=' . $userdata['id']);
    $ts_token->create ();
    sql_query ('DELETE FROM users WHERE id = ' . sqlesc ($userdata['id']));
    write_log ('Account: ' . $userdata['username.'] . ' (' . $userdata['id'] . ') has been deleted by ' . $CURUSER['username']);
    stderr ('Done', 'The Account <strong>' . $userdata['id'] . '</strong> has been deleted...', false);
    return 1;
  }

  if ($action == 'resetpasskey')
  {
    get_user_data ();
    permission_check ();
    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = 'Are you sure to reset passkey for following account: ' . $userdata['id'] . ' - <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=resetpasskey&userid=' . $userdata['id'] . '&sure=1&hash={1}">YES</a> / <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=edituser&userid=' . $userdata['id'] . '">NO</a>';
    $ts_token->redirect = $_SERVER['SCRIPT_NAME'] . ('' . '?action=resetpasskey&userid=' . $userdata['id']);
    $ts_token->create ();
    $modcomment = $userdata['modcomment'];
    $passkey = md5 ($userdata['username'] . get_date_time () . $userdata['passhash'] . md5 ($securehash . $SITENAME));
    $modcomment = sprintf ($lang->modtask['modcommentpasskey'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
    $msg = sprintf ($lang->modtask['passkeymsg'], $CURUSER['username']);
    $subject = $lang->modtask['passkeysubject'];
    sql_query ('UPDATE users SET passkey = ' . sqlesc ($passkey) . ', modcomment = ' . sqlesc ($modcomment) . ' WHERE id = ' . sqlesc ($userdata['id']));
    insert_message ($userid, $msg, $subject);
    write_log ('Passkey: ' . $userdata['username.'] . ' (' . $userdata['id'] . ') has been reset by ' . $CURUSER['username']);
    redirect ('admin/edituser.php?action=edituser&userid=' . $userdata['id'], 'The passkey has been updated...');
    return 1;
  }

  if ($action == 'warnuser')
  {
    get_user_data ();
    permission_check ();
    $modcomment = $userdata['modcomment'];
    if ($userdata['id'] == $CURUSER['id'])
    {
      print_no_permission ();
    }

    if ($do == 'warn')
    {
      require INC_PATH . '/functions_getvar.php';
      getvar (array ('warnpm', 'warnlength', 'hash'));
      if (((empty ($warnpm) OR empty ($hash)) OR !is_valid_id ($warnlength)))
      {
        stderr ('Error', 'Don\'t leave any fields blank!');
      }

      if ((($hash !== $_SESSION['token_code'] OR empty ($hash)) OR empty ($_SESSION['token_code'])))
      {
        unset ($_SESSION[token_code]);
        header ('' . 'Location: ' . $_SERVER['SCRIPT_NAME'] . '?action=warnuser&userid=' . $userdata['id']);
        exit ();
      }

      unset ($_SESSION[token_code]);
      if ($warnlength == '255')
      {
        $modcomment = sprintf ($lang->modtask['modcommentwarning'], gmdate ('Y-m-d'), $CURUSER['username'], $warnpm, $modcomment);
        $msg = sprintf ($lang->modtask['warningmessage'], $CURUSER['username'], $warnpm);
        $tu[] = 'warneduntil = \'0000-00-00 00:00:00\'';
      }
      else
      {
        $warneduntil = get_date_time (gmtime () + $warnlength * 604800);
        $dur = sprintf ($lang->modtask['weeks'], $warnlength);
        $msg = sprintf ($lang->modtask['warningmessage2'], $dur, $CURUSER['username'], $warnpm);
        $modcomment = sprintf ($lang->modtask['modcommentwarning2'], gmdate ('Y-m-d'), $dur, $CURUSER['username'], $warnpm, $modcomment);
        $tu[] = 'warneduntil = ' . sqlesc ($warneduntil);
      }

      $subject = $lang->modtask['warningsubject'];
      $lastwarned = sqlesc (get_date_time ());
      $tu[] = '' . 'warned = \'yes\', timeswarned = timeswarned + 1, lastwarned = ' . $lastwarned . ', warnedby = ' . $CURUSER['id'];
      insert_message ($userid, $msg, $subject);
      if (((isset ($tu) AND !empty ($userdata['id'])) AND is_valid_id ($userdata['id'])))
      {
        if ((isset ($modcomment) AND $modcomment != $userdata['modcomment']))
        {
          $tu[] = 'modcomment = ' . sqlesc ($modcomment);
        }

        (sql_query ('UPDATE users SET  ' . implode (', ', $tu) . ' WHERE id=' . sqlesc ($userdata['id'])) OR sqlerr (__FILE__, 862));
      }

      write_log ('Account: ' . $userdata['username.'] . ' (' . $userdata['id'] . ') has been warned by ' . $CURUSER['username']);
      redirect ('userdetails.php?id=' . $userdata['id'], 'User (' . $userdata['id'] . ') has been warned!');
      return 1;
    }

    stdhead ('Warn User: ' . $userdata['username'] . ' (UID: ' . $userdata['id'] . ')');
    $where = array ('Cancel' => $BASEURL . '/userdetails.php?id=' . $userdata['id']);
    echo jumpbutton ($where);
    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $hash = $ts_token->create_return ();
    echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="userid" value="' . $userdata['id'] . '">
		<input type="hidden" name="action" value="warnuser">
		<input type="hidden" name="do" value="warn">
		<input type="hidden" name="hash" value="' . $hash . '">';
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Warn User: ' . $userdata['username'] . ' (UID: ' . $userdata['id'] . ')</td></tr>';
    inputbox ('Warn Reason:', 'warnpm');
    print '<tr><td align=right>Warn Length:</td><td><select name=\'warnlength\' id=specialboxs>
';
    print weeks ();
    print '</select> <input type=submit value=\'Warn User\' class=button></td></tr>
';
    echo '</form></table></table>';
    stdfoot ();
  }

?>
