<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function sgpermission ($Option)
  {
    global $usergroup;
    $Options = array ('canview' => '0', 'cancreate' => '1', 'canpost' => '2', 'candelete' => '3', 'canjoin' => '4', 'canedit' => '5', 'canmanagemsg' => '6', 'canmanagegroup' => '7');
    $What = (isset ($Options[$Option]) ? $Options[$Option] : 0);
    return ($usergroup['sgperms'][$What] == '1' ? true : false);
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
<td valign="top" width="60%" align="right">' . $title . '</td>
<td valign="top" width="40%" align="left"><label><input type="radio" name="' . $name . '" class="bginput" value="yes"' . (isset ($yescheck) ? $yescheck : '') . ('' . ' />&nbsp;Yes</label> &nbsp;&nbsp;<label><input type="radio" name="' . $name . '" value="no"') . (isset ($nocheck) ? $nocheck : '') . ' />&nbsp;No</label></td>
</tr>
';
  }

  function inputbox ($title, $name, $value = '', $class = 'specialboxes', $size = '25', $extra = '', $maxlength = '', $autocomplete = 1, $extra2 = '')
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
<td valign="top" width="60%" align="right">' . $title . '</td>
<td valign="top" width="40%" align="left">
' . $extra2 . '<input type="text" class="bginput" name="' . $name . '"') . $size . $maxlength . $ac . $value . ' />
' . $extra . '
</td>
</tr>
';
  }

  function update_usergroup_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM usergroups ORDER by gid');
    while ($_uc = mysql_fetch_assoc ($query))
    {
      $_ucache[$_uc['gid']] = $_uc;
    }

    $content = var_export ($_ucache, true);
    $_filename = TSDIR . '/' . $cache . '/usergroups.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#5 - Do Not Alter
 * Cache Name: Usergroups
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$usergroupscache = ' . $content . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('UG_VERSION', 'v1.4.2 by xam');
  $gid = (isset ($_POST['gid']) ? intval ($_POST['gid']) : (isset ($_GET['gid']) ? intval ($_GET['gid']) : ''));
  if ($do == 'usergroups')
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $disporder = $_POST['disporder'];
      foreach ($disporder as $gid => $disporder)
      {
        (sql_query ('UPDATE usergroups SET disporder = ' . intval ($disporder) . ' WHERE gid = ' . intval ($gid)) OR sqlerr (__FILE__, 78));
      }

      update_usergroup_cache ();
    }

    ($query = sql_query ('SELECT g.*, COUNT(u.id) as totalusers FROM usergroups g LEFT JOIN users u ON (g.gid=u.usergroup) WHERE g.type = 1 GROUP BY g.gid') OR sqlerr (__FILE__, 83));
    if (mysql_num_rows ($query) == 0)
    {
      admin_cp_critical_error ('No UserGroup Found!');
      exit ();
    }

    $where = array ('Create New Usergroup' => $_SERVER['SCRIPT_NAME'] . '?do=newgroup');
    echo jumpbutton ($where);
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=usergroups">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="5" align="center">Default Usergroups</td></tr>';
    echo '<tr class="subheader"><td width="10%" align="center">Group ID</td><td width="30%" align="left">Title</td><td align="left" width="40%">Description</td><td width="10%" align="center">Total Users</td><td width="5%" align="center">Order</td></tr>';
    while ($usergroup = mysql_fetch_array ($query))
    {
      $group = (int)$usergroup['gid'];
      echo '<tr><td align="center">' . (int)$usergroup['gid'] . '</td><td align="left"><a href=?do=editusergroup&gid=' . (int)$usergroup['gid'] . '>' . get_user_color ($usergroup['title'], $usergroup['namestyle']) . '</a></td><td align="left">' . htmlspecialchars_uni ($usergroup['description']) . '</td><td align="center">' . (int)$usergroup['totalusers'] . '</td>' . (($usergroup['showstaffteam'] == 'yes' OR $usergroup['showstaffteam'] == 'staff') ? '<td width="5%" align="center"><input type="text" size="2" name="disporder[' . $usergroup['gid'] . ']" value="' . $usergroup['disporder'] . '" class="bginput"></td>' : '<td width="5%" align="center"><em>NO</em></td>') . '</tr>';
    }

    echo '</table></table><br />';
    ($query = sql_query ('SELECT g.*, COUNT(u.id) as totalusers FROM usergroups g LEFT JOIN users u ON (g.gid=u.usergroup) WHERE g.type = 2 GROUP BY g.gid') OR sqlerr (__FILE__, 102));
    if (1 <= mysql_num_rows ($query))
    {
      echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
      echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="5" align="center">Custom Usergroups</td></tr>';
      echo '<tr class="subheader"><td width="10%" align="center">Group ID</td><td width="30%" align="left">Title</td><td align="left" width="40%">Description</td><td width="10%" align="center">Total Users</td><td width="5%" align="center">Order</td></tr>';
      while ($usergroup = mysql_fetch_array ($query))
      {
        $group = (int)$usergroup['gid'];
        echo '<tr><td align="center">' . (int)$usergroup['gid'] . '</td><td align="left"><a href=?do=editusergroup&gid=' . (int)$usergroup['gid'] . '>' . get_user_color ($usergroup['title'], $usergroup['namestyle']) . '</a> <a href=?do=deletegroup&gid=' . (int)$usergroup['gid'] . '><font color="red">[delete]</font></a></td><td align="left">' . htmlspecialchars_uni ($usergroup['description']) . '</td><td align="center">' . (int)$usergroup['totalusers'] . '</td>' . (($usergroup['showstaffteam'] == 'yes' OR $usergroup['showstaffteam'] == 'staff') ? '<td width="5%" align="center"><input type="text" class="bginput" size="2" name="disporder[' . $usergroup['gid'] . ']" value="' . $usergroup['disporder'] . '"></td>' : '<td width="5%" align="center"><em>NO</em></td>') . '</tr>';
      }

      echo '</table></table>';
    }

    echo '
	<br />
	<div align="right"><input type="submit" value="Save Display Orders" class="button"></div>
	</form>';
    return 1;
  }

  if ($do == 'newgroup')
  {
    echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="savenewgroup">';
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Create New UserGroup</td></tr>';
    echo '<tr><td class="heading" align="right" valign="top">Group Title:</td><td><input type="text" name="title" id="specialboxnn" class="bginput"></td></tr>';
    echo '<tr><td class="heading" align="right" valign="top">Group Description:</td><td><input type="text" name="description" class="bginput" id="specialboxnn"> <input type="submit" value="save" class="button"></td></tr>';
    echo '</form></table></table>';
    return 1;
  }

  if ($do == 'deletegroup')
  {
    $firstquery = sql_query ('SELECT gid,title,type FROM usergroups WHERE gid = ' . sqlesc ($gid));
    if (mysql_num_rows ($firstquery) == 0)
    {
      admin_cp_critical_error ('Invalid Group');
    }
    else
    {
      $checkquery = mysql_fetch_array ($firstquery);
      if ($checkquery['type'] == 1)
      {
        admin_cp_critical_error ('Permission denied. This is a default usergroup and can not be deleted!');
      }
    }

    $query = sql_query ('SELECT COUNT(id) FROM users WHERE usergroup = ' . sqlesc ($gid));
    $tu = mysql_fetch_array ($query);
    if (1 <= $tu[0])
    {
      echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="do" value="deletegroup_changeusers">
		<input type="hidden" name="gid" value="' . $gid . '">';
      echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Delete Usergroups and Move Users</td></tr>';
      echo '<tr><td class=subheader align=center colspan=2><b><font color=red>' . $tu[0] . '</b> users found in this usergroup. Please select new usergroup to move these users.</font></td></tr>';
      echo '<tr><td class=rowhead>Move these users to:</td>';
      echo '<td><select name=newgroup id=specialboxn class="bginput">';
      $secondquery = sql_query ('SELECT gid,title FROM usergroups');
      while ($g = mysql_fetch_array ($secondquery))
      {
        if ($g['gid'] != $gid)
        {
          echo '<option value=' . $g['gid'] . '>' . $g['title'] . '</option>';
          continue;
        }
      }

      echo '</select> <input type=submit value="Move users to this group" class="button"></td></tr>';
      echo '</form></table></table>';
      exit ();
    }
    else
    {
      sql_query ('DELETE FROM usergroups WHERE gid = ' . sqlesc ($gid) . ' LIMIT 1');
      update_usergroup_cache ();
    }

    admin_cp_redirect ('usergroups', 'Usergroup has been deleted..');
    return 1;
  }

  if ($do == 'deletegroup_changeusers')
  {
    $firstquery = sql_query ('SELECT gid,title,type FROM usergroups WHERE gid = ' . sqlesc ($gid));
    if (mysql_num_rows ($firstquery) == 0)
    {
      admin_cp_critical_error ('Invalid Group');
    }
    else
    {
      $checkquery = mysql_fetch_array ($firstquery);
      if ($checkquery['type'] == 1)
      {
        admin_cp_critical_error ('Permission denied. This is a default usergroup and can not be deleted!');
      }
    }

    $oldgroup = $gid;
    $newgroup = (int)$_POST['newgroup'];
    sql_query ('UPDATE users SET usergroup = ' . sqlesc ($newgroup) . ' WHERE usergroup IN (' . $oldgroup . ')');
    sql_query ('DELETE FROM usergroups WHERE gid = ' . sqlesc ($gid) . ' LIMIT 1');
    update_usergroup_cache ();
    admin_cp_redirect ('usergroups', 'Usergroup has been deleted..');
    return 1;
  }

  if ($do == 'savenewgroup')
  {
    require INC_PATH . '/functions_getvar.php';
    getvar (array ('title', 'description'));
    if ((empty ($title) OR empty ($description)))
    {
      admin_cp_critical_error ('Dont leave required fields blank!');
    }

    (sql_query ('INSERT INTO usergroups (type,title,description) VALUES (2, ' . sqlesc ($title) . ', ' . sqlesc ($description) . ')') OR sqlerr (__FILE__, 190));
    $gid = mysql_insert_id ();
    update_usergroup_cache ();
    admin_cp_redirect ('editusergroup', 'New UserGroup has been created..', 'gid=' . $gid);
    exit ();
    return 1;
  }

  if ($do == 'editusergroup')
  {
    $query = sql_query ('SELECT * FROM usergroups WHERE gid = ' . sqlesc ($gid));
    if (mysql_num_rows ($query) == 0)
    {
      admin_cp_critical_error ('Invalid Group');
      exit ();
    }
    else
    {
      $usergroup = mysql_fetch_array ($query);
    }

    echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="updategroup">
	<input type="hidden" name="gid" value="' . $gid . '">';
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="2" align="center">Edit Usergroup: ' . $usergroup['title'] . ' (' . $usergroup['description'] . ')</td></tr>';
    echo '<tr class="subheader"><td align="center" colspan="2">Usergroup Details</td></tr>';
    inputbox ('Usergroup Title', 'title', $usergroup['title'], 'specialboxnn', 70);
    inputbox ('Usergroup Description', 'description', $usergroup['description'], 'specialboxnn', 70);
    echo '<tr class="subheader"><td align="center" colspan="2">Usergroup Style</td></tr>';
    inputbox ('Username Style<br /><small>This allows you to set a custom name style for this usergroup. Please use {username} to represent the name.</small>', 'namestyle', $usergroup['namestyle'], 'specialboxnn', 70, '<div align="right">' . get_user_color ($usergroup['title'], $usergroup['namestyle']) . '</div>');
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions: General</td></tr>';
    yesno ('Is \'Banned\' Group?<br /><small>If this group is a \'banned\' usergroup, users will be able to be \'banned\' into this usergroup.</small>', 'isbanned', ($usergroup['isbanned'] == 'yes' ? 'yes' : 'no'));
    yesno ('Is \'VIP\' Group?<br /><small>If this group is a \'VIP\' usergroup, users will have same special permissions such as maxslots, waitsystem etc..</small>', 'isvipgroup', ($usergroup['isvipgroup'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can FreeLeech?<br /><small>Do not count download stats while leeching a torrent.</small>', 'canfreeleech', ($usergroup['canfreeleech'] == 'yes' ? 'yes' : 'no'));
    echo '<tr>
<td valign="top" width="60%" align="right">Show on \'STAFF\' Page?<br /><small>Should this usergroup be shown on the staff team page?</small></td>
<td valign="top" width="40%" align="left"><label><input type="radio" class="bginput" name="showstaffteam" value="yes"' . ($usergroup['showstaffteam'] == 'yes' ? 'checked=\\"checked\\"' : '') . ' />&nbsp;Yes</label> &nbsp;&nbsp;<label><input type="radio" name="showstaffteam" class="bginput" value="no"' . ($usergroup['showstaffteam'] == 'no' ? 'checked=\\"checked\\"' : '') . ' />&nbsp;No</label> &nbsp;&nbsp;<label><input class="bginput" type="radio" name="showstaffteam" value="staff"' . ($usergroup['showstaffteam'] == 'staff' ? 'checked=\\"checked\\"' : '') . ' />&nbsp;Staff Only</label></td>
</tr>
';
    yesno ('Can Use Private Messaging?<br /><small>User can send PM.</small>', 'canpm', ($usergroup['canpm'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Download Torrent?<br /><small>User can Download a torrent.</small>', 'candownload', ($usergroup['candownload'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Upload Torrent?<br /><small>User can Upload a torrent.</small>', 'canupload', ($usergroup['canupload'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Upload External Torrent?<br /><small>User can Upload an external torrent.</small>', 'canexternal', ($usergroup['canexternal'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Transfer?<br /><small>User can transfer his upload amount to his friend.</small>', 'cantransfer', ($usergroup['cantransfer'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Request Torrent?<br /><small>User can Request a torrent.</small>', 'canrequest', ($usergroup['canrequest'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Post Comment?<br /><small>User can post a Comment/Visitor Message.</small>', 'cancomment', ($usergroup['cancomment'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Report?<br /><small>User can Report (Torrent/Comment/User).</small>', 'canreport', ($usergroup['canreport'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Bookmark Torrent?<br /><small>User can Bookmark a torrent.</small>', 'canbookmark', ($usergroup['canbookmark'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Create Poll on Forums?<br /><small>User can Create a new poll on Forums.</small>', 'cancreatepoll', ($usergroup['cancreatepoll'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Vote on Polls?<br /><small>User can Vote on Polls.</small>', 'canvote', ($usergroup['canvote'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Rate?<br /><small>User can Rate on Torrents, Posts or Users.</small>', 'canrate', ($usergroup['canrate'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Thanks on Torrents/Posts?<br /><small>User can Say Thanks on Torrents/Posts.</small>', 'canthanks', ($usergroup['canthanks'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Use Shoutbox/IRC?<br /><small>User can use Shoutbox/IRC.</small>', 'canshout', ($usergroup['canshout'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Use Invite System?<br /><small>User can Invite his friends.</small>', 'caninvite', ($usergroup['caninvite'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Use Bonus Points<br /><small>User can Exchange his Karma Bonus Points.</small>', 'canbonus', ($usergroup['canbonus'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Reset Passkey?<br /><small>User can reset his Passkey.</small>', 'canresetpasskey', ($usergroup['canresetpasskey'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Insert BAD-USER?<br /><small>User can insert a bad user into bad users page.</small>', 'canbaduser', ($usergroup['canbaduser'] == 'yes' ? 'yes' : 'no'));
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions: Viewing</td></tr>';
    yesno ('Can View Profiles?<br /><small>User can view other user Profiles.</small>', 'canviewotherprofile', ($usergroup['canviewotherprofile'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View Memberlist?<br /><small>User can view Memberlist.</small>', 'canmemberlist', ($usergroup['canmemberlist'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View Friendlist?<br /><small>User can view Friendlist.</small>', 'canfriendlist', ($usergroup['canfriendlist'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View Snatch List?<br /><small>User can view Snatch Details.</small>', 'cansnatch', ($usergroup['cansnatch'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View Peer List?<br /><small>User can view Peers on Details Page.</small>', 'canpeers', ($usergroup['canpeers'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View Top10 Page?<br /><small>User can view Top10 Page.</small>', 'cantopten', ($usergroup['cantopten'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can View VIP Categories?<br /><small>User can view VIP categories.</small>', 'canviewviptorrents', ($usergroup['canviewviptorrents'] == 'yes' ? 'yes' : 'no'));
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions: UserCP</td></tr>';
    yesno ('Can Email Notification?<br /><small>User can get email notification on new torrents, pms.</small>', 'canemailnotify', ($usergroup['canemailnotify'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Use Signature?<br /><small>User can use Signature.</small>', 'cansignature', ($usergroup['cansignature'] == 'yes' ? 'yes' : 'no'));
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions: Administrative</td></tr>';
    yesno ('Can Access Settings Panel?<br /><small>User can access Settings Panel of tracker.</small>', 'cansettingspanel', ($usergroup['cansettingspanel'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Access Staff Panel?<br /><small>User can access Staff Panel of tracker.</small>', 'canstaffpanel', ($usergroup['canstaffpanel'] == 'yes' ? 'yes' : 'no'));
    yesno ('Is Super Moderator?<br /><small>Can delete/ban/promote users.</small>', 'issupermod', ($usergroup['issupermod'] == 'yes' ? 'yes' : 'no'));
    yesno ('Is Forum Moderator?<br /><small>Can delete/edit/move threads.</small>', 'isforummod', ($usergroup['isforummod'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Mass Delete?<br /><small>Can MASS delete threads and posts.</small>', 'canmassdelete', ($usergroup['canmassdelete'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Edit Userdetails?<br /><small>User can update userdetails.</small>', 'canuserdetails', ($usergroup['canuserdetails'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Access Tracker?<br /><small>If tracker turned off, this group will still be able to see the tracker.</small>', 'canaccessoffline', ($usergroup['canaccessoffline'] == 'yes' ? 'yes' : 'no'));
    yesno ('Can Delete Torrent?<br /><small>User can delete his own torrent.</small>', 'candeletetorrent', ($usergroup['candeletetorrent'] == 'yes' ? 'yes' : 'no'));
    echo '<tr class="subheader"><td align="center" colspan="2">Permissions: Limitations</td></tr>';
    inputbox ('PM Quota<br /><small>Here you can set the maximum number of stored pms for this group.<br />Set to 0 to disable this.</small>', 'pmquote', $usergroup['pmquote']);
    inputbox ('Flood Limit<br /><small>Set the time (in seconds) users have to wait between posting, to be in effect.<br />Set to 0 to disable this.</small>', 'floodlimit', $usergroup['floodlimit']);
    inputbox ('Slot Limit<br /><small>Set the slot users have to wait between downloading, to be in effect.<br />Set to 0 to disable this.</small>', 'slotlimit', $usergroup['slotlimit']);
    inputbox ('Wait Limit<br /><small>Set the time (in hours) users have to wait between downloading, to be in effect.<br />Set to 0 to disable this.</small>', 'waitlimit', $usergroup['waitlimit']);
    echo '<tr class="subheader"><td align="center" colspan="2">Cleanup Actions</td></tr>';
    inputbox ('Automatic Invite<br /><small>Set the limit of automatic invites for each month<br />Set to 0 to disable this.</small>', 'autoinvite', $usergroup['autoinvite']);
    if (($usergroup['showstaffteam'] == 'yes' OR $usergroup['showstaffteam'] == 'staff'))
    {
      echo '<tr class="subheader"><td align="center" colspan="2">Display Order on Staff Team Page</td></tr>';
      inputbox ('Display Order', 'disporder', $usergroup['disporder']);
    }

    echo '<tr class="subheader"><td align="center" colspan="2">Social Group Permissions</td></tr>';
    yesno ('Can View Social Groups<br /><small>This permission, when set to "yes" allows the user to view Social Groups.</small>', 'sgperms[canview]', (sgpermission ('canview') ? 'yes' : 'no'));
    yesno ('Can Create Social Groups<br /><small>This permission, when set to "yes" allows the user to Create Social Groups (Also requires the "Can View Social Groups" and "Can Join Social Groups" permissions).</small>', 'sgperms[cancreate]', (sgpermission ('cancreate') ? 'yes' : 'no'));
    yesno ('Can Post Messages<br /><small>This permission, when set to "yes" allows the user to post Messages in Groups.</small>', 'sgperms[canpost]', (sgpermission ('canpost') ? 'yes' : 'no'));
    yesno ('Can Delete Own Social Groups<br /><small>This permission, when set to "yes" allows the user to delete their own Social Groups.</small>', 'sgperms[candelete]', (sgpermission ('candelete') ? 'yes' : 'no'));
    yesno ('Can Join Social Groups<br /><small>This permission, when set to "yes" allows the user to Join Social Groups (Also requires the "Can View Social Groups" permission).</small>', 'sgperms[canjoin]', (sgpermission ('canjoin') ? 'yes' : 'no'));
    yesno ('Can Edit Own Social Groups<br /><small>This permission, when set to "yes" allows the user to edit the details of their own Social Groups.</small>', 'sgperms[canedit]', (sgpermission ('canedit') ? 'yes' : 'no'));
    yesno ('Can Manage Own Social Group Messages<br /><small>This permission, when set to "yes" allows the user to manage their own Messages within a Social Group</small>', 'sgperms[canmanagemsg]', (sgpermission ('canmanagemsg') ? 'yes' : 'no'));
    yesno ('Can Manage Own Social Groups<br /><small>Allowing a user to manage his/her own social groups grants the following options on social groups created by that user: Delete messages, Edit messages, Kick members from the group.</small>', 'sgperms[canmanagegroup]', (sgpermission ('canmanagegroup') ? 'yes' : 'no'));
    echo '<tr><td colspan="2" align="right"><input type="submit" value="Update Usergroup" class="button"> <input type="reset" value="Reset" class="button"></td></tr>';
    echo '</form></table></table>';
    return 1;
  }

  if ($do == 'updategroup')
  {
    require INC_PATH . '/functions_getvar.php';
    getvar (array ('title', 'description', 'namestyle', 'isbanned', 'isvipgroup', 'canfreeleech', 'showstaffteam', 'canpm', 'candownload', 'canupload', 'canexternal', 'cantransfer', 'canrequest', 'cancomment', 'canreport', 'canbookmark', 'canresetpasskey', 'canbaduser', 'canviewotherprofile', 'cancreatepoll', 'canvote', 'canrate', 'canthanks', 'canshout', 'caninvite', 'canbonus', 'canmemberlist', 'canfriendlist', 'cansnatch', 'canpeers', 'cantopten', 'canviewviptorrents', 'canemailnotify', 'cansignature', 'cansettingspanel', 'canstaffpanel', 'issupermod', 'isforummod', 'canmassdelete', 'canuserdetails', 'canaccessoffline', 'candeletetorrent', 'pmquote', 'floodlimit', 'slotlimit', 'waitlimit', 'autoinvite', 'disporder'));
    $updateset[] = 'title							=	' . sqlesc ($title);
    $updateset[] = 'description				=	' . sqlesc ($description);
    $updateset[] = 'namestyle					=	' . sqlesc ($namestyle);
    $updateset[] = 'isbanned					=	' . sqlesc ($isbanned);
    $updateset[] = 'isvipgroup					=	' . sqlesc ($isvipgroup);
    $updateset[] = 'canfreeleech				=	' . sqlesc ($canfreeleech);
    $updateset[] = 'showstaffteam			=	' . sqlesc ($showstaffteam);
    $updateset[] = 'canpm						=	' . sqlesc ($canpm);
    $updateset[] = 'candownload				=	' . sqlesc ($candownload);
    $updateset[] = 'canupload					=	' . sqlesc ($canupload);
    $updateset[] = 'canexternal				=	' . sqlesc ($canexternal);
    $updateset[] = 'cantransfer				=	' . sqlesc ($cantransfer);
    $updateset[] = 'canrequest				=	' . sqlesc ($canrequest);
    $updateset[] = 'cancomment				=	' . sqlesc ($cancomment);
    $updateset[] = 'canreport					=	' . sqlesc ($canreport);
    $updateset[] = 'canbookmark			=	' . sqlesc ($canbookmark);
    $updateset[] = 'canresetpasskey		=	' . sqlesc ($canresetpasskey);
    $updateset[] = 'canbaduser				=	' . sqlesc ($canbaduser);
    $updateset[] = 'canviewotherprofile	=	' . sqlesc ($canviewotherprofile);
    $updateset[] = 'cancreatepoll			=	' . sqlesc ($cancreatepoll);
    $updateset[] = 'canvote						=	' . sqlesc ($canvote);
    $updateset[] = 'canrate						=	' . sqlesc ($canrate);
    $updateset[] = 'canthanks					=	' . sqlesc ($canthanks);
    $updateset[] = 'canshout					=	' . sqlesc ($canshout);
    $updateset[] = 'caninvite					=	' . sqlesc ($caninvite);
    $updateset[] = 'canbonus					=	' . sqlesc ($canbonus);
    $updateset[] = 'canmemberlist			=	' . sqlesc ($canmemberlist);
    $updateset[] = 'canfriendlist				=	' . sqlesc ($canfriendlist);
    $updateset[] = 'cansnatch					=	' . sqlesc ($cansnatch);
    $updateset[] = 'canpeers					=	' . sqlesc ($canpeers);
    $updateset[] = 'cantopten					=	' . sqlesc ($cantopten);
    $updateset[] = 'canviewviptorrents	=	' . sqlesc ($canviewviptorrents);
    $updateset[] = 'canemailnotify			=	' . sqlesc ($canemailnotify);
    $updateset[] = 'cansignature				=	' . sqlesc ($cansignature);
    $updateset[] = 'cansettingspanel		=	' . sqlesc ($cansettingspanel);
    $updateset[] = 'canstaffpanel			=	' . sqlesc ($canstaffpanel);
    $updateset[] = 'issupermod				=	' . sqlesc ($issupermod);
    $updateset[] = 'isforummod				=	' . sqlesc ($isforummod);
    $updateset[] = 'canmassdelete			=	' . sqlesc ($canmassdelete);
    $updateset[] = 'canuserdetails			=	' . sqlesc ($canuserdetails);
    $updateset[] = 'canaccessoffline		=	' . sqlesc ($canaccessoffline);
    $updateset[] = 'candeletetorrent		=	' . sqlesc ($candeletetorrent);
    $updateset[] = 'pmquote					=	' . sqlesc (intval ($pmquote));
    $updateset[] = 'floodlimit					=	' . sqlesc (intval ($floodlimit));
    $updateset[] = 'slotlimit						=	' . sqlesc (intval ($slotlimit));
    $updateset[] = 'waitlimit					=	' . sqlesc (intval ($waitlimit));
    $updateset[] = 'autoinvite					=	' . sqlesc (intval ($autoinvite));
    $updateset[] = 'disporder					=	' . sqlesc (intval ($disporder));
    $SGPerms = '';
    foreach ($_POST['sgperms'] as $Field => $Value)
    {
      $SGPerms .= ($Value == 'yes' ? '1' : '0');
    }

    $updateset[] = 'sgperms = \'' . $SGPerms . '\'';
    (sql_query ('UPDATE usergroups SET  ' . implode (', ', $updateset) . ' WHERE gid=' . sqlesc ($gid)) OR sqlerr (__FILE__, 356));
    update_usergroup_cache ();
    admin_cp_redirect ('editusergroup', 'The usergroup has successfully been updated.', 'gid=' . $gid);
    return 1;
  }

  admin_cp_critical_error ('Invalid Action!');
?>
