<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function getaltbg ()
  {
    global $bgcolor;
    if ($bgcolor == 'tdclass1')
    {
      $bgcolor = 'tdclass2';
    }
    else
    {
      $bgcolor = 'tdclass1';
    }

    return $bgcolor;
  }

  function starttable ($width = '100%', $border = 1, $padding = 6)
  {
    echo '' . '<table cellpadding="' . $border . '" cellspacing="0" border="0" align="center" width="' . $width . '" class="bordercolor">
';
    echo '<tr><td>
';
    echo '' . '<table cellpadding="' . $padding . '" cellspacing="0" border="0" width="100%" class="tback">';
  }

  function endtable ()
  {
    echo '</table>
';
    echo '</td>
</tr>
</table>
';
    echo '<br />
';
  }

  function tableheader ($title, $anchor = '', $colspan = 2)
  {
    global $bgcolor;
    if ($anchor)
    {
      $anchor = '' . '<a name="' . $anchor . '">' . $title . '</a>';
    }
    else
    {
      $anchor = $title;
    }

    echo '' . '<tr>
<td class="theat" align="center" colspan="' . $colspan . '">' . $anchor . '</td>
</tr>
';
    $bgcolor = 'altbg2';
  }

  function quickpermissions ($fid = '', $pid = '')
  {
    global $forum;
    if ($fid)
    {
      ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forumpermissions WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 75));
      while ($fperm = mysql_fetch_assoc ($query))
      {
        $fperms[$fperm['gid']] = $fperm;
      }
    }

    echo '<script type="text/javascript">
';
    echo '
function checkPermRow(id, master) {
	chk = getElemRefs("canview:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("canviewthreads:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("canpostthreads:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("canpostreplys:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("caneditposts:"+id);
	chk.checked = master.checke';
    echo 'd;
	chk = getElemRefs("candeleteposts:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("candeletethreads:"+id);
	chk.checked = master.checked;	
	chk = getElemRefs("canpostattachments:"+id);
	chk.checked = master.checked;
	chk = getElemRefs("cansearch:"+id);
	chk.checked = master.checked;

	uncheckInheritPerm(id);
}


function getElemRefs(id) {
	if(document.getElementById) {
';
    echo '
		return document.getElementById(id);
	}
	else if(document.all) {
		return document.all[id];
	}
	else if(document.layers) {
		return document.layers[id];
	}
}
</script>
';
    starttable ();
    if ($fid)
    {
      tableheader ('' . '<strong>Quick Forum Permissions for ' . $forum['name'] . '</strong>', '', '11');
    }
    else
    {
      tableheader ('<strong>Quick Forum Permissions</strong>', '', '11');
    }

    echo '<tr>
';
    echo '<td class="subheader">User Group</td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can View Forum</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can View Threads</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Post Threads</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Post Replies</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Edit Own Post</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Delete Own Post</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Delete Own Thread</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Upload Attachment</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">Can Search</span></td>
';
    echo '<td class="subheader" align="center" width="10%"><span class="smalltext">ALL</span></td>
';
    echo '</tr>
';
    ($query = sql_query ('SELECT * FROM usergroups') OR sqlerr (__FILE__, 144));
    while ($usergroup = mysql_fetch_assoc ($query))
    {
      $bgcolor = getaltbg ();
      if ($fperms[$usergroup['gid']])
      {
        $perms = $fperms[$usergroup['gid']];
      }

      if (!is_array ($perms))
      {
        $perms = $usergroup;
      }

      if ($fperms[$usergroup['gid']])
      {
        $inheritcheck = '';
        $inheritclass = '';
      }
      else
      {
        $inheritcheck = ' checked="checked"';
        $inheritclass = 'highlight1';
      }

      if ($perms['canview'] == 'yes')
      {
        $canview = ' checked="checked"';
      }
      else
      {
        $canview = '';
      }

      if ($perms['canviewthreads'] == 'yes')
      {
        $canviewthreads = ' checked="checked"';
      }
      else
      {
        $canviewthreads = '';
      }

      if ($perms['canpostthreads'] == 'yes')
      {
        $canpostthreads = ' checked="checked"';
      }
      else
      {
        $canpostthreads = '';
      }

      if ($perms['canpostreplys'] == 'yes')
      {
        $canpostreplys = ' checked="checked"';
      }
      else
      {
        $canpostreplys = '';
      }

      if ($perms['caneditposts'] == 'yes')
      {
        $caneditposts = ' checked="checked"';
      }
      else
      {
        $caneditposts = '';
      }

      if ($perms['candeleteposts'] == 'yes')
      {
        $candeleteposts = ' checked="checked"';
      }
      else
      {
        $candeleteposts = '';
      }

      if ($perms['candeletethreads'] == 'yes')
      {
        $candeletethreads = ' checked="checked"';
      }
      else
      {
        $candeletethreads = '';
      }

      if ($perms['canpostattachments'] == 'yes')
      {
        $canpostattachments = ' checked="checked"';
      }
      else
      {
        $canpostattachments = '';
      }

      if ($perms['cansearch'] == 'yes')
      {
        $cansearch = ' checked="checked"';
      }
      else
      {
        $cansearch = '';
      }

      if ((((((((($canview AND $canviewthreads) AND $canpostthreads) AND $canpostreplys) AND $caneditposts) AND $candeleteposts) AND $candeletethreads) AND $canpostattachments) AND $cansearch))
      {
        $allcheck = ' checked="checked"';
      }
      else
      {
        $allcheck = '';
      }

      echo '<tr>
';
      echo '' . '<td class="' . $bgcolor . '"><font class="smalltext">' . get_user_color ($usergroup['title'], $usergroup['namestyle']) . '</font></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="canview[' . $usergroup['gid'] . ']') . '" id="canview:' . $usergroup['gid'] . '" value="yes" ' . $canview . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="canviewthreads[' . $usergroup['gid'] . ']') . '" id="canviewthreads:' . $usergroup['gid'] . '" value="yes" ' . $canviewthreads . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="canpostthreads[' . $usergroup['gid'] . ']') . '" id="canpostthreads:' . $usergroup['gid'] . '" value="yes"' . $canpostthreads . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="canpostreplys[' . $usergroup['gid'] . ']') . '" id="canpostreplys:' . $usergroup['gid'] . '" value="yes" ' . $canpostreplys . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="caneditposts[' . $usergroup['gid'] . ']') . '" id="caneditposts:' . $usergroup['gid'] . '" value="yes" ' . $caneditposts . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="candeleteposts[' . $usergroup['gid'] . ']') . '" id="candeleteposts:' . $usergroup['gid'] . '" value="yes" ' . $candeleteposts . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="candeletethreads[' . $usergroup['gid'] . ']') . '" id="candeletethreads:' . $usergroup['gid'] . '" value="yes" ' . $candeletethreads . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="canpostattachments[' . $usergroup['gid'] . ']') . '" id="canpostattachments:' . $usergroup['gid'] . '" value="yes" ' . $canpostattachments . ' /></td>
';
      echo ('' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" name="cansearch[' . $usergroup['gid'] . ']') . '" id="cansearch:' . $usergroup['gid'] . '" value="yes" ' . $cansearch . ' /></td>
';
      echo '' . '<td class="' . $bgcolor . '" align="center"><input type="checkbox" onclick="checkPermRow(' . $usergroup['gid'] . ', this);"' . $allcheck . ' /></td>
';
      echo '</tr>
';
      unset ($perms);
    }

    endtable ();
  }

  function savequickperms ($fid)
  {
    $canview = $_POST['canview'];
    $canviewthreads = $_POST['canviewthreads'];
    $canpostthreads = $_POST['canpostthreads'];
    $canpostreplys = $_POST['canpostreplys'];
    $caneditposts = $_POST['caneditposts'];
    $candeleteposts = $_POST['candeleteposts'];
    $candeletethreads = $_POST['candeletethreads'];
    $canpostattachments = $_POST['canpostattachments'];
    $cansearch = $_POST['cansearch'];
    ($query = sql_query ('SELECT * FROM usergroups') OR sqlerr (__FILE__, 282));
    while ($usergroup = mysql_fetch_assoc ($query))
    {
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'forumpermissions WHERE fid = ' . sqlesc ($fid) . ' AND gid = ' . sqlesc ($usergroup['gid'])) OR sqlerr (__FILE__, 287));
      if ($canview[$usergroup['gid']] == 'yes')
      {
        $u_canview = 'yes';
      }
      else
      {
        $u_canview = 'no';
      }

      if ($canviewthreads[$usergroup['gid']] == 'yes')
      {
        $u_canviewthreads = 'yes';
      }
      else
      {
        $u_canviewthreads = 'no';
      }

      if ($canpostthreads[$usergroup['gid']] == 'yes')
      {
        $u_canpostthreads = 'yes';
      }
      else
      {
        $u_canpostthreads = 'no';
      }

      if ($canpostreplys[$usergroup['gid']] == 'yes')
      {
        $u_canpostreplys = 'yes';
      }
      else
      {
        $u_canpostreplys = 'no';
      }

      if ($caneditposts[$usergroup['gid']] == 'yes')
      {
        $u_caneditposts = 'yes';
      }
      else
      {
        $u_caneditposts = 'no';
      }

      if ($candeleteposts[$usergroup['gid']] == 'yes')
      {
        $u_candeleteposts = 'yes';
      }
      else
      {
        $u_candeleteposts = 'no';
      }

      if ($candeletethreads[$usergroup['gid']] == 'yes')
      {
        $u_candeletethreads = 'yes';
      }
      else
      {
        $u_candeletethreads = 'no';
      }

      if ($canpostattachments[$usergroup['gid']] == 'yes')
      {
        $u_canpostattachments = 'yes';
      }
      else
      {
        $u_canpostattachments = 'no';
      }

      if ($cansearch[$usergroup['gid']] == 'yes')
      {
        $u_cansearch = 'yes';
      }
      else
      {
        $u_cansearch = 'no';
      }

      (sql_query ('INSERT INTO ' . TSF_PREFIX . 'forumpermissions (fid, gid, canview, canviewthreads, canpostthreads, canpostreplys, caneditposts, candeleteposts, candeletethreads, canpostattachments, cansearch) VALUES (' . implode (',', array_map ('sqlesc', array ($fid, $usergroup['gid'], $u_canview, $u_canviewthreads, $u_canpostthreads, $u_canpostreplys, $u_caneditposts, $u_candeleteposts, $u_candeletethreads, $u_canpostattachments, $u_cansearch))) . ')') OR sqlerr (__FILE__, 368));
    }

  }

  function makeparentlist ($fid, $navsep = ',')
  {
    ($query = sql_query ('SELECT name,fid,pid FROM ' . TSF_PREFIX . 'forums ORDER BY disporder, pid') OR sqlerr (__FILE__, 375));
    while ($forum = mysql_fetch_assoc ($query))
    {
      $pforumcache[$forum['fid']][$forum['pid']] = $forum;
    }

    reset ($pforumcache);
    reset ($pforumcache[$fid]);
    foreach ($pforumcache[$fid] as $key => $forum)
    {
      if ($fid == $forum['fid'])
      {
        if ($pforumcache[$forum['pid']])
        {
          $navigation = makeparentlist ($forum['pid'], $navsep) . $navigation;
        }

        if ($navigation)
        {
          $navigation .= $navsep;
        }

        $navigation .= $forum['fid'];
        continue;
      }
    }

    return $navigation;
  }

  function show_tsf_forums ($selected = '')
  {
    global $SITENAME;
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'c\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 1301));
    $str = '			
			<select name="forumid" class="bginput" id="forumid">
			<optgroup label="' . $SITENAME . ' Forums">	';
    while ($forum = mysql_fetch_assoc ($query))
    {
      $str .= '<option value="' . $forum['fid'] . '"' . ($selected == $forum['fid'] ? ' SELECTED' : '') . '>-- ' . $forum['name'];
    }

    $str .= '
			</optgroup>
			</select>';
    return $str;
  }

  function show_error ()
  {
    global $error;
    if (!empty ($error))
    {
      echo '<div class="error">' . $error . '</div>';
    }

  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('FC_VERSION', 'v1.5.5 by xam');
  $action = (isset ($_GET['action']) ? $_GET['action'] : (isset ($_POST['action']) ? $_POST['action'] : ''));
  unset ($title);
  unset ($error);
  unset ($forumid);
  unset ($pagetext);
  unset ($userid);
  include_once INC_PATH . '/readconfig_forumcp.php';
  $fid = (isset ($_POST['fid']) ? intval ($_POST['fid']) : (isset ($_GET['fid']) ? intval ($_GET['fid']) : 0));
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  if ($action == 'newforum')
  {
    if ($do == 'save')
    {
      $name = htmlspecialchars_uni ($_POST['name']);
      if (empty ($name))
      {
        admin_cp_critical_error ('Forum Name can not be empty!');
      }

      $image = trim ($_POST['image']);
      if (!file_exists ($rootpath . 'tsf_forums/images/forumicons/' . $image))
      {
        admin_cp_critical_error ('Image does not exists!');
      }
      else
      {
        if (!in_array (get_extension ($image), array ('png', 'gif', 'jpg')))
        {
          admin_cp_critical_error ('Allowed Image Types: png, gif and jpg.');
        }
      }

      $description = htmlspecialchars_uni ($_POST['description']);
      $disporder = intval ($_POST['disporder']);
      $type = 'c';
      $pid = 0;
      (sql_query ('INSERT INTO ' . TSF_PREFIX . 'forums (name,description,disporder,type,pid,image) VALUES (' . sqlesc ($name) . ',' . sqlesc ($description) . ',' . sqlesc ($disporder) . ',' . sqlesc ($type) . ',' . sqlesc ($pid) . ',' . sqlesc ($image) . ')') OR sqlerr (__FILE__, 428));
      $fid = mysql_insert_id ();
      $parentlist = makeparentlist ($fid);
      (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET parentlist = ' . sqlesc ($parentlist) . ' WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 431));
      admin_cp_redirect ('forumpermissions', 'Forum (' . $fid . ') has been added.. You will now redirect to forum permissions!', 'action=modifyforum&fid=' . $fid);
      exit ();
    }

    $__path = $rootpath . 'tsf_forums/images/forumicons/';
    if ($__handle = opendir ($__path))
    {
      $_Images = '
			<select name="image" class="bginput">
				<option value="">---Auto Select Image From The Folder---</option>';
      while (false !== $__file = readdir ($__handle))
      {
        if ((($__file != '.' AND $__file != '..') AND in_array (get_extension ($__file), array ('png', 'gif', 'jpg'))))
        {
          $_Images .= '
					<option value="' . $__file . '"' . ($forum['image'] == $__file ? ' selected="selected"' : '') . '>' . $__file . '</option>
					';
          continue;
        }
      }

      closedir ($handle);
      $_Images .= '
			</select>';
    }

    echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="newforum">
		<input type="hidden" name="do" value="save">
		<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td class="thead" align="center" colspan="2">Add New Forum</td>
				</tr>
				<tr><td align="right">Name:</td><td><input type="text" name="name" id="specialboxnn" value="" class="bginput"></td></tr>
				<tr><td align="right" valign="top">Description:</td><td><textarea name="description" rows="4" id="specialboxnn" cols="40"></textarea></td></tr>
				<tr><td align="right">Forum Image:</td><td>' . $_Images . ' ' . show_helptip ('Please select the image that you have uploaded into tsf_forums/images/forumicons/ folder.', 'Quick Help', 600, '') . '</td></tr>
				<tr><td align="right">Display Order:</td><td><input type="text" name="disporder" id="specialboxes" value="" class="bginput"></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="add forum" class="hoptobutton"> <input type="reset" value="reset fields" class="hoptobutton"></td></tr>
			</tbody>
		</table>
		</form>';
    exit ();
    exit ();
  }

  if ($action == 'modifyforum')
  {
    if ((empty ($fid) OR !is_valid_id ($fid)))
    {
      print_no_permission ();
      exit ();
    }

    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forums WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 485));
    if (mysql_num_rows ($query) == 0)
    {
      admin_cp_critical_error ('Invalid Forum ID!');
      exit ();
    }

    $forum = mysql_fetch_assoc ($query);
    if ($do == 'forumpermissions')
    {
      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="modifyforum" />
		<input type="hidden" name="do" value="saveforumpermissions" />
		<input type="hidden" name="fid" value="' . $fid . '" />';
      quickpermissions ($fid);
      echo '
		<div align="right">
		<div class="formbuttons">
		<input type="submit" class="hoptobutton" name="Update Permissions" value="  Update Permissions  " />&nbsp;&nbsp;
		<input type="reset" class="hoptobutton" name="Reset" value="  Reset  " />&nbsp;&nbsp;
		</div>
		</div>
		</form>';
      exit ();
    }

    if ($do == 'saveforumpermissions')
    {
      savequickperms ($fid);
      admin_cp_redirect ('forumcp', 'Forum (' . $fid . ') Permissions has been saved..', 'forumcp');
      exit ();
    }

    if ($do == 'deleteforum')
    {
      $sure = (isset ($_GET['sure']) ? $_GET['sure'] : '');
      if ($sure != 'yes')
      {
        admin_cp_critical_error ('Are you sure you want to delete the forum called <strong>' . $forum['name'] . '</strong>" forum?<br /> <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=deleteforum&fid=' . $forum['fid'] . '&sure=yes">YES</a> - <a href="' . $_SERVER['SCRIPT_NAME'] . '?action=forumcp">NO</a>', false);
      }

      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'forums WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 531));
      ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . ('' . 'forums WHERE CONCAT(\',\', parentlist, \',\') LIKE \'%,' . $fid . ',%\'')) OR sqlerr (__FILE__, 533));
      while ($f = mysql_fetch_assoc ($query))
      {
        $fids[$f['fid']] = $fid;
        $delquery .= '' . ' OR fid=\'' . $f['fid'] . '\'';
      }

      (sql_query ('DELETE FROM ' . TSF_PREFIX . ('' . 'forums WHERE CONCAT(\',\',parentlist,\',\') LIKE \'%,' . $fid . ',%\'')) OR sqlerr (__FILE__, 541));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE fid = ' . sqlesc ($fid) . ('' . ' ' . $delquery)) OR sqlerr (__FILE__, 542));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ('' . ' ' . $delquery)) OR sqlerr (__FILE__, 543));
      admin_cp_redirect ('forumcp', 'Forum (' . $fid . ') has been deleted..', 'forumcp');
      exit ();
    }

    if ($do == 'savemoderator')
    {
      $doapplytochild = false;
      $applychild = $_POST['applychild'];
      if ($applychild == 'yes')
      {
        $doapplytochild = true;
        $plistforums[] = $forum['fid'];
        $query = sql_query ('SELECT fid FROM ' . TSF_PREFIX . ('' . 'forums WHERE pid = ' . $forum['fid']));
        if (0 < mysql_num_rows ($query))
        {
          while ($plist = mysql_fetch_assoc ($query))
          {
            $plistforums[] = $plist['fid'];
          }
        }
      }

      $removemoderator = $_POST['removemoderator'];
      if (0 < count ($removemoderator))
      {
        foreach ($removemoderator as $ruserid)
        {
          (sql_query ('DELETE FROM ' . TSF_PREFIX . ('' . 'moderators WHERE userid = ' . $ruserid . ' AND forumid =' . $forum['fid'])) OR sqlerr (__FILE__, 575));
          if (($doapplytochild AND 0 < count ($plistforums)))
          {
            (sql_query ('DELETE FROM ' . TSF_PREFIX . ('' . 'moderators WHERE userid = ' . $ruserid . ' AND forumid IN (') . implode (',', $plistforums) . ')') OR sqlerr (__FILE__, 578));
            continue;
          }
        }
      }

      $moderator = $_POST['moderator'];
      $alreadymod = array ();
      ($query = sql_query ('SELECT m.userid, u.username FROM ' . TSF_PREFIX . ('' . 'moderators m LEFT JOIN users u ON (m.userid=u.id) WHERE m.forumid = ' . $forum['fid'])) OR sqlerr (__FILE__, 586));
      while ($mods = mysql_fetch_assoc ($query))
      {
        $alreadymod[] = $mods['username'];
      }

      if (($doapplytochild AND 0 < count ($plistforums)))
      {
        ($query = sql_query ('SELECT m.userid, u.username FROM ' . TSF_PREFIX . 'moderators m LEFT JOIN users u ON (m.userid=u.id) WHERE m.forumid IN (' . implode (',', $plistforums) . ')') OR sqlerr (__FILE__, 593));
        while ($mods = mysql_fetch_assoc ($query))
        {
          $alreadymod[] = $mods['username'];
        }
      }

      if (0 < count ($moderator))
      {
        foreach ($moderator as $modname)
        {
          if (($doapplytochild AND 0 < count ($plistforums)))
          {
            foreach ($plistforums as $pfid)
            {
              if ((in_array ($modname, $alreadymod) !== TRUE AND $modname != ''))
              {
                ($query = sql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($modname)) OR sqlerr (__FILE__, 609));
                if (0 < mysql_num_rows ($query))
                {
                  $userid = mysql_result ($query, 0, 'id');
                  if ((@in_array ($userid, $removemoderator) !== TRUE AND $userid != ''))
                  {
                    (sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'moderators (userid,forumid) VALUES (' . $userid . ', ' . $pfid . ')')) OR sqlerr (__FILE__, 614));
                    continue;
                  }

                  continue;
                }

                continue;
              }
            }

            continue;
          }
          else
          {
            if ((in_array ($modname, $alreadymod) !== TRUE AND $modname != ''))
            {
              ($query = sql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($modname)) OR sqlerr (__FILE__, 623));
              if (0 < mysql_num_rows ($query))
              {
                $userid = mysql_result ($query, 0, 'id');
                if ((@in_array ($userid, $removemoderator) !== TRUE AND $userid != ''))
                {
                  (sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'moderators (userid,forumid) VALUES (' . $userid . ', ' . $forum['fid'] . ')')) OR sqlerr (__FILE__, 628));
                  continue;
                }

                continue;
              }

              continue;
            }

            continue;
          }
        }
      }

      $do = 'addmoderator';
    }

    if ($do == 'addmoderator')
    {
      $hiddenvalues;
      if ($forum['type'] == 'c')
      {
        $hiddenvalues = '
				<tr>
					<td align="right">
						Apply This Moderator(s) to Child Forums
					</td>
					<td>
						<input type="checkbox" name="applychild" value="yes" checked="checked">
					</td>
				</tr>
			';
      }

      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="modifyforum">
		<input type="hidden" name="do" value="savemoderator">
		<input type="hidden" name="fid" value="' . $fid . '">
		<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>
				<tr>
					<td class="thead" align="center" colspan="2">Add Moderator to: ' . $forum['name'] . '  (forumid: ' . $forum['fid'] . ')</td>
				</tr>
		';
      ($query = sql_query ('SELECT m.userid, u.username FROM ' . TSF_PREFIX . ('' . 'moderators m LEFT JOIN users u ON (m.userid=u.id) WHERE m.forumid = ' . $forum['fid'])) OR sqlerr (__FILE__, 665));
      $rowcount = mysql_num_rows ($query);
      if (0 < $rowcount)
      {
        $whilecount = 1;
        while ($mods = mysql_fetch_assoc ($query))
        {
          echo '
				<tr>
					<td align="right">
						<strong>
							Moderator Username[' . $whilecount . ']:
						</strong>
					</td>
					<td align="left">
						<input type="text" name="moderator[' . $whilecount . ']" value="' . $mods['username'] . '" size="30" class="bginput"> <input type="checkbox" name="removemoderator[]" value="' . $mods['userid'] . '"> remove moderator
					</td>
				</tr>
				';
          ++$whilecount;
        }

        $i = 1;
        while ($i <= 3)
        {
          echo '
				<tr>
					<td align="right">
						<strong>
							Moderator Username[' . $whilecount . ']:
						</strong>
					</td>
					<td align="left">
						<input type="text" name="moderator[' . $whilecount . ']" value="" size="30" class="bginput">
					</td>
				</tr>
				';
          ++$whilecount;
          ++$i;
        }
      }
      else
      {
        $i = 1;
        while ($i <= 3)
        {
          echo '
				<tr>
					<td align="right">
						<strong>
							Moderator Username[' . $i . ']:
						</strong>
					</td>
					<td align="left">
						<input type="text" name="moderator[' . $i . ']" value="" size="30" class="bginput">
					</td>
				</tr>
				';
          ++$i;
        }
      }

      echo $hiddenvalues . '
			<tr>
				<td align="center" colspan="2">
					<input type="submit" value="save" class="hoptobutton"> <input type="reset" value="reset" class="hoptobutton">
				</td>
			</tr>
			</tbody>
			</table>
			</form>';
      exit ();
    }

    if ($do == 'addchildforum')
    {
      $typee = (isset ($_GET['type']) ? 's' : 'f');
      if ($typee == 's')
      {
        $alert = '
			<tr><td colspan="2" align="left" class="subheader">Warning! Once you create the child forum, you should update forum permissions for ' . $forum['name'] . '</td></tr>';
      }

      $__path = $rootpath . 'tsf_forums/images/forumicons/';
      if ($__handle = opendir ($__path))
      {
        $_Images = '
			<select name="image" class="bginput">
				<option value="">---Auto Select Image From The Folder---</option>';
        while (false !== $__file = readdir ($__handle))
        {
          if ((($__file != '.' AND $__file != '..') AND in_array (get_extension ($__file), array ('png', 'gif', 'jpg'))))
          {
            $_Images .= '
					<option value="' . $__file . '"' . ($forum['image'] == $__file ? ' selected="selected"' : '') . '>' . $__file . '</option>
					';
            continue;
          }
        }

        closedir ($handle);
        $_Images .= '
			</select>';
      }

      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="modifyforum">
		<input type="hidden" name="do" value="savechildforum">
		<input type="hidden" name="fid" value="' . $fid . '">
		<input type="hidden" name="typee" value="' . $typee . '">
		<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td class="thead" align="center" colspan="2">Add Child Forum to: ' . $forum['name'] . '  (forumid: ' . $forum['fid'] . ')</td>
				</tr>' . $alert . '
				<tr><td align="right">Name:</td><td><input type="text" name="name" id="specialboxnn" value="" class="bginput"></td></tr>
				<tr><td align="right" valign="top">Description:</td><td><textarea name="description" rows="4" id="specialboxnn" cols="40"></textarea></td></tr>
				<tr><td align="right">Forum Image:</td><td>' . $_Images . ' ' . show_helptip ('Please select the image that you have uploaded into tsf_forums/images/forumicons/ folder.', 'Quick Help', 600, '') . '</td></tr>
				<tr><td align="right">Display Order:</td><td><input type="text" name="disporder" id="specialboxes" value="" class="bginput"></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="add child forum" class="hoptobutton"> <input type="reset" class="hoptobutton" value="reset fields"></td></tr>
			</tbody>
		</table>
		</form>';
      exit ();
    }

    if ($do == 'savechildforum')
    {
      $name = htmlspecialchars_uni ($_POST['name']);
      if (empty ($name))
      {
        admin_cp_critical_error ('Forum Name can not be empty!');
      }

      $image = trim ($_POST['image']);
      if (!file_exists ($rootpath . 'tsf_forums/images/forumicons/' . $image))
      {
        admin_cp_critical_error ('Image does not exists!');
      }
      else
      {
        if (!in_array (get_extension ($image), array ('png', 'gif', 'jpg')))
        {
          admin_cp_critical_error ('Allowed Image Types: png, gif and jpg.');
        }
      }

      $description = htmlspecialchars_uni ($_POST['description']);
      $disporder = intval ($_POST['disporder']);
      $type = ($_POST['typee'] == 's' ? 's' : 'f');
      $pid = $fid;
      (sql_query ('INSERT INTO ' . TSF_PREFIX . 'forums (name,description,disporder,type,pid,image) VALUES (' . sqlesc ($name) . ',' . sqlesc ($description) . ',' . sqlesc ($disporder) . ',' . sqlesc ($type) . ',' . sqlesc ($pid) . ',' . sqlesc ($image) . ')') OR sqlerr (__FILE__, 805));
      $fid = mysql_insert_id ();
      $parentlist = makeparentlist ($fid);
      (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET parentlist = ' . sqlesc ($parentlist) . ' WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 808));
      if ($type == 's')
      {
        admin_cp_redirect ('forumpermissions', 'Child-Forum (' . $fid . ') has been added.. Please update permissions, Redirecting to Permissions page!', 'action=modifyforum&fid=' . $pid);
      }
      else
      {
        admin_cp_redirect ('forumcp', 'Child-Forum (' . $fid . ') has been added..', 'forumcp');
      }

      exit ();
    }

    if ($do == 'save')
    {
      $name = htmlspecialchars_uni ($_POST['name']);
      if (empty ($name))
      {
        admin_cp_critical_error ('Forum Name can not be empty!');
      }

      $password = ($_POST['password'] ? $_POST['password'] : '');
      $description = htmlspecialchars_uni ($_POST['description']);
      $image = trim ($_POST['image']);
      if (!file_exists ($rootpath . 'tsf_forums/images/forumicons/' . $image))
      {
        admin_cp_critical_error ('Image does not exists!');
      }
      else
      {
        if (!in_array (get_extension ($image), array ('png', 'gif', 'jpg')))
        {
          admin_cp_critical_error ('Allowed Image Types: png, gif and jpg.');
        }
      }

      $disporder = intval ($_POST['disporder']);
      $pid = intval ($_POST['pid']);
      if ($pid)
      {
        $query = sql_query ('SELECT type FROM ' . TSF_PREFIX . 'forums WHERE fid = ' . sqlesc ($pid));
        $type = mysql_result ($query, 0, 'type');
        switch ($type)
        {
          case 'c':
          {
            $ftype = 'f';
            break;
          }

          case 'f':
          {
            $ftype = 's';
            break;
          }

          default:
          {
            $ftype = 's';
            break;
          }
        }

        (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET password = ' . sqlesc ($password) . ', type = ' . sqlesc ($ftype) . ', pid = ' . sqlesc ($pid) . ', name = ' . sqlesc ($name) . ', description = ' . sqlesc ($description) . ', image = ' . sqlesc ($image) . ', disporder = ' . sqlesc ($disporder) . ' WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 859));
        $parentlist = makeparentlist ($fid);
        (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET parentlist = ' . sqlesc ($parentlist) . ' WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 861));
      }
      else
      {
        (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET name = ' . sqlesc ($name) . ', description = ' . sqlesc ($description) . ', image = ' . sqlesc ($image) . ', disporder = ' . sqlesc ($disporder) . ' WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 865));
      }

      admin_cp_redirect ('forumcp', 'Forum has been updated..', 'forumcp');
      exit ();
    }

    $__path = $rootpath . 'tsf_forums/images/forumicons/';
    if ($__handle = opendir ($__path))
    {
      $_Images = '
		<select name="image" class="bginput">
			<option value="">---Auto Select Image From The Folder---</option>';
      while (false !== $__file = readdir ($__handle))
      {
        if ((($__file != '.' AND $__file != '..') AND in_array (get_extension ($__file), array ('png', 'gif', 'jpg'))))
        {
          $_Images .= '
				<option value="' . $__file . '"' . ($forum['image'] == $__file ? ' selected="selected"' : '') . '>' . $__file . '</option>
				';
          continue;
        }
      }

      closedir ($handle);
      $_Images .= '
		</select>';
    }

    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="action" value="modifyforum" />
	<input type="hidden" name="do" value="save" />
	<input type="hidden" name="fid" value="' . $fid . '" />
	<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td class="thead" align="center" colspan="2">Modify Forum: ' . $forum['name'] . '  (forumid: ' . $forum['fid'] . ')</td>
			</tr>
			<tr><td align="right">Name:</td><td><input type="text" class="bginput" name="name" id="specialboxnn" value="' . $forum['name'] . '" /></td></tr>
			<tr><td align="right" valign="top">Description:</td><td><textarea name="description" rows="4" id="specialboxnn" cols="40">' . $forum['description'] . '</textarea></td></tr>
			<tr><td align="right">Forum Image:</td><td>' . $_Images . ' ' . show_helptip ('Please select the image that you have uploaded into tsf_forums/images/forumicons/ folder.', 'Quick Help', 600, '') . '</td></tr>
			' . ($forum['type'] != 'c' ? '<tr><td align="right">Forum Password:</td><td><input class="bginput" type="text" name="password" id="specialboxnn" value="' . $forum['password'] . '" /></td></tr>' : '') . '<tr><td align="right">Display Order:</td><td><input class="bginput" type="text" name="disporder" id="specialboxes" value="' . intval ($forum['disporder']) . '" /></td></tr>';
    if ($forum['type'] == 'c')
    {
      echo '<tr><td align="right">Parent Forum</td><td><select name="pid" class="bginput"><option value="0">None</option><option value="0">-----------</option></select></td></tr>';
    }
    else
    {
      ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forums WHERE (type = \'c\' OR type = \'f\') ORDER by pid, disporder') OR sqlerr (__FILE__, 912));
      while ($forums = mysql_fetch_assoc ($query))
      {
        $f .= '
			<option value="' . $forums['fid'] . '" ' . ($forum['pid'] == $forums['fid'] ? 'selected="selected"' : '') . '>' . $forums['name'] . '</option>';
      }

      echo '
		<tr><td align="right">Parent Forum</td>
		<td>
		<select name="pid" class="bginput">
		' . $f . '
		</select>
		</td></tr>';
    }

    echo '	
	<tr><td colspan="2" align="center"><input type="submit" value="modify forum" class="hoptobutton"> <input type="reset" value="reset fields" class="hoptobutton"></td></tr>
		</tbody>
	</table>
	</form>';
    exit ();
  }

  if (($action == 'forumcp' OR $do == 'forumcp'))
  {
    $deepsubforums = $subforums = array ();
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f							
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 943));
    while ($subforum = mysql_fetch_assoc ($query))
    {
      $deepsubforums[$subforum['pid']] = $deepsubforums[$subforum['pid']] . '&nbsp;' . $subforum['name'] . ' [<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&fid=' . $subforum['fid'] . '">modify</a>] [<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=deleteforum&fid=' . $subforum['fid'] . '">delete</a>]&nbsp;';
    }

    ($query = sql_query ('
							SELECT fid,pid,name,description
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'f\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 954));
    while ($forum = mysql_fetch_assoc ($query))
    {
      $where = array ('Modify Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&fid=' . $forum['fid'], 'Add Child Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=addchildforum&type=s&fid=' . $forum['fid'], 'Add Moderator' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=addmoderator&fid=' . $forum['fid'], 'Delete Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=deleteforum&fid=' . $forum['fid'], ($deepsubforums[$forum['fid']] ? 'Permissions' : '') => ($deepsubforums[$forum['fid']] ? $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=forumpermissions&fid=' . $forum['fid'] : ''));
      $subforums[$forum['pid']] = $subforums[$forum['pid']] . '			
			<tr>
				<td class="tdclass2">
					<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&fid=' . $forum['fid'] . '">' . $forum['name'] . '</a><br />' . $forum['description'] . ($deepsubforums[$forum['fid']] ? '<br />&nbsp;&nbsp;<b>Subforums</b>: ' . $deepsubforums[$forum['fid']] : '') . jumpbutton ($where) . '
				</td>
			</tr>';
    }

    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f							
							WHERE f.type = \'c\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 977));
    while ($category = mysql_fetch_assoc ($query))
    {
      $where = array ('Modify Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&fid=' . $category['fid'], 'Add Child Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=addchildforum&fid=' . $category['fid'], 'Add Moderator' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=addmoderator&fid=' . $category['fid'], 'Delete Forum' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=deleteforum&fid=' . $category['fid'], 'Permissions' => $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&do=forumpermissions&fid=' . $category['fid']);
      $str .= '
		<tr>
			<td class="tdclass1">
				<table align="center" border="1" cellpadding="5" cellspacing="0" width="100%">
					<tr>
						<td class="tdclass1">
							<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=modifyforum&fid=' . $category['fid'] . '">
							<font color="red" size="2">' . $category['name'] . '</font></a>' . jumpbutton ($where) . $subforums[$category['fid']] . '
						</td>
					</tr>
				</table>
			</td>
		</tr>
			';
    }

    $where = array ('Forum Announcements' => $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements', 'Global Forum Settings' => $_SERVER['SCRIPT_NAME'] . '?action=forumsettings', 'Create New Forum' => $_SERVER['SCRIPT_NAME'] . '?action=newforum');
    echo jumpbutton ($where);
    echo '
	<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>			
			' . $str . '
		</tbody>
	</table>';
    exit ();
  }

  if ($action == 'forumannouncements')
  {
    $error = '';
    $id = (isset ($_GET['id']) ? intval ($_GET['id']) : (isset ($_POST['id']) ? intval ($_POST['id']) : ''));
    if ($do == 'save')
    {
      $title = trim ($_POST['title']);
      $pagetext = trim ($_POST['pagetext']);
      $forumid = intval ($_POST['forumid']);
      $userid = intval ($CURUSER['id']);
      $posted = time ();
      if (((empty ($forumid) OR empty ($title)) OR empty ($pagetext)))
      {
        $error = 'Please fill all fields!';
        $do = 'new';
      }
      else
      {
        (sql_query ('INSERT INTO ' . TSF_PREFIX . 'announcement (title,userid,posted,pagetext,forumid) VALUES (' . implode (',', array_map ('sqlesc', array ($title, $userid, $posted, $pagetext, $forumid))) . ')') OR sqlerr (__FILE__, 1039));
        unset ($do);
        unset ($error);
      }
    }

    if ($do == 'new')
    {
      show_error ();
      $where = array ('Cancel' => $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements');
      echo jumpbutton ($where) . '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="forumannouncements">
		<input type="hidden" name="do" value="save">';
      _form_header_open_ ('Announcement Manager', 2);
      echo '				
		<tr><td class="heading">Forum<br /><font class="smalltext">(Also applies to child forums)</font></td><td>' . show_tsf_forums ($forumid) . '</td></tr>
		<tr><td class="heading">Title</td><td><input type="text" class="bginput" name="title" id="title" style="width: 744px;" value="' . htmlspecialchars_uni ($title) . '"></td></tr>
		<tr><td class="heading">Text</td><td>	<textarea id="pagetext" name="pagetext">' . htmlspecialchars_uni ($pagetext) . '</textarea></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="save" class="hoptobutton" id="submitbutton"> <input type="reset" value="reset" class="hoptobutton"></td></tr>
		</form>
		<script>
		(function() {
			var Dom = YAHOO.util.Dom,
				Event = YAHOO.util.Event;
			
			var myConfig = {
				height: "300px",
				width: "600px",
				dompath: true,
				focusAtStart: true,
				handleSubmit: true
			};
			
			var myEditor = new YAHOO.widget.Editor("pagetext", myConfig);
			myEditor._defaultToolbar.buttonType = "basic";
			myEditor.render();
			
		})();
	</script>
		';
      _form_header_close_ ();
      exit ();
    }

    if ((($do == 'edit' OR $do == 'delete') OR $do == 'update'))
    {
      $query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'announcement WHERE announcementid = ' . sqlesc ($id));
      if (mysql_num_rows ($query) == 0)
      {
        $error = 'There is no announcement with this ID!';
      }
      else
      {
        $edit = mysql_fetch_assoc ($query);
      }
    }

    if ($do == 'update')
    {
      $title = trim ($_POST['title']);
      $pagetext = trim ($_POST['pagetext']);
      $forumid = intval ($_POST['forumid']);
      if (((empty ($forumid) OR empty ($title)) OR empty ($pagetext)))
      {
        $error = 'Please fill all fields!';
        $do = 'edit';
      }
      else
      {
        (sql_query ('UPDATE ' . TSF_PREFIX . 'announcement SET title = ' . sqlesc ($title) . ', pagetext = ' . sqlesc ($pagetext) . ', forumid = ' . sqlesc ($forumid) . ' WHERE announcementid = ' . sqlesc ($id)) OR sqlerr (__FILE__, 1111));
        unset ($do);
        unset ($error);
        unset ($edit);
      }
    }

    if (($do == 'edit' AND !empty ($edit)))
    {
      show_error ();
      $where = array ('Cancel' => $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements');
      echo jumpbutton ($where) . '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="action" value="forumannouncements">
		<input type="hidden" name="do" value="update">
		<input type="hidden" name="id" value="' . $id . '">';
      _form_header_open_ ('Announcement Manager', 2);
      echo '		
		<tr><td class="heading">Forum<br /><font class="smalltext">(Also applies to child forums)</font></td><td>' . show_tsf_forums ($edit['forumid']) . '</td></tr>
		<tr><td class="heading">Title</td><td><input type="text" class="bginput" name="title" style="width: 744px;" value="' . htmlspecialchars_uni ($edit['title']) . '"></td></tr>
		<tr><td class="heading">Text</td><td><textarea id="pagetext" name="pagetext">' . htmlspecialchars_uni ($edit['pagetext']) . '</textarea>
		</td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="save" class="hoptobutton"> <input type="reset" value="reset" class="hoptobutton"></td></tr>
		</form>
		<script>
		(function() {
			var Dom = YAHOO.util.Dom,
				Event = YAHOO.util.Event;
			
			var myConfig = {
				height: "300px",
				width: "600px",
				dompath: true,
				focusAtStart: true,
				handleSubmit: true
			};
			
			var myEditor = new YAHOO.widget.Editor("pagetext", myConfig);
			myEditor._defaultToolbar.buttonType = "basic";
			myEditor.render();
		})();
	</script>
		';
      _form_header_close_ ();
      exit ();
    }

    if (($do == 'delete' AND empty ($error)))
    {
      sql_query ('DELETE FROM ' . TSF_PREFIX . 'announcement WHERE announcementid = ' . sqlesc ($id));
      unset ($do);
      unset ($error);
    }

    $where = array ('New Announcement' => $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements&do=new');
    show_error ();
    echo jumpbutton ($where);
    _form_header_open_ ('Announcement Manager', 4);
    echo '
	<tr>
	<td class="subheader" align="left">Forum</td>
	<td class="subheader" align="left">Title</td>
	<td class="subheader" align="left">Posted</td>
	<td class="subheader" align="center">Action</td>
	</tr>';
    ($query = sql_query ('SELECT a.*, f.name, f.fid, u.id, u.username, g.namestyle FROM ' . TSF_PREFIX . 'announcement a LEFT JOIN ' . TSF_PREFIX . 'forums f ON (a.forumid=f.fid) LEFT JOIN users u ON (a.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER by a.posted DESC') OR sqlerr (__FILE__, 1178));
    if (mysql_num_rows ($query) == 0)
    {
      echo '<tr><td colspan="4">There is no announcement to show!</td></tr>';
    }
    else
    {
      while ($a = mysql_fetch_assoc ($query))
      {
        echo '
			<tr>
			<td><a href="' . $BASEURL . '/tsf_forums/index.php?fid=' . intval ($a['fid']) . '">' . htmlspecialchars_uni ($a['name']) . '</a></td>
			<td><a href="' . $BASEURL . '/tsf_forums/announcement.php?aid=' . intval ($a['announcementid']) . '">' . htmlspecialchars_uni ($a['title']) . '</a></td>
			<td><a href="' . $BASEURL . '/userdetails.php?id=' . intval ($a['id']) . '">' . my_datee ($dateformat, $a['posted']) . ' ' . my_datee ($timeformat, $a['posted']) . ' by ' . get_user_color ($a['username'], $a['namestyle']) . '</a></td>
			<td align="center"><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements&do=edit&id=' . intval ($a['announcementid']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" border="0"></a>&nbsp;&nbsp;<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=forumannouncements&do=delete&id=' . intval ($a['announcementid']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" border="0"></a></td>
			</tr>';
      }
    }

    _form_header_close_ ();
    exit ();
  }

  if ($action == 'forumsettings')
  {
    if ($do == 'save')
    {
      require INC_PATH . '/functions_getvar.php';
      getvar (array ('f_forum_online', 'f_offlinemsg', 'f_forumname', 'f_threadsperpage', 'f_postsperpage', 'f_minmsglength', 'f_avatar_maxwidth', 'f_avatar_maxheight', 'f_avatar_maxsize', 'f_showstats', 'f_upload_path', 'f_upload_maxsize', 'f_allowed_types', 'f_ads', 'f_sfpertr'));
      $FORUMCP['f_forum_online'] = ($f_forum_online == 'yes' ? 'yes' : 'no');
      $FORUMCP['f_offlinemsg'] = htmlspecialchars_uni ($f_offlinemsg);
      $FORUMCP['f_forumname'] = htmlspecialchars_uni ($f_forumname);
      $FORUMCP['f_threadsperpage'] = min (50, 0 + $f_threadsperpage);
      $FORUMCP['f_postsperpage'] = min (50, 0 + $f_postsperpage);
      $FORUMCP['f_minmsglength'] = min (25, 0 + $f_minmsglength);
      $FORUMCP['f_avatar_maxwidth'] = min (400, 0 + $f_avatar_maxwidth);
      $FORUMCP['f_avatar_maxheight'] = min (400, 0 + $f_avatar_maxheight);
      $FORUMCP['f_avatar_maxsize'] = 0 + $f_avatar_maxsize;
      $FORUMCP['f_showstats'] = ($f_showstats == 'yes' ? 'yes' : ($f_showstats == 'no' ? 'no' : 'staffonly'));
      $FORUMCP['f_upload_path'] = htmlspecialchars_uni ($f_upload_path);
      $FORUMCP['f_upload_maxsize'] = 0 + $f_upload_maxsize;
      $FORUMCP['f_allowed_types'] = $f_allowed_types;
      $FORUMCP['f_ads'] = $f_ads;
      $FORUMCP['f_sfpertr'] = $f_sfpertr;
      require_once INC_PATH . '/functions_writeconfig.php';
      writeconfig ('FORUMCP', $FORUMCP);
      $actiontime = date ('F j, Y, g:i a');
      write_log ('' . 'Forum settings updated by ' . $CURUSER['username'] . '. ' . $actiontime);
      header ('Location: ' . $_SERVER['SCRIPT_NAME'] . '?action=forumsettings&saved=true');
      exit ();
    }

    if ((isset ($_GET['saved']) AND $_GET['saved'] == 'true'))
    {
      echo '<div class="bluediv"><font color="red"><strong>Global Forum settings has been saved..</strong></font></div><br />';
    }

    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="action" value="forumsettings">
	<input type="hidden" name="do" value="save">
	<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td class="thead" align="center" colspan="2">Forum Settings</td>
			</tr>
			<tr>
			<td align="right">Forum Online?</td><td><select class="bginput" name="f_forum_online"><option value="yes" ' . ($f_forum_online == 'yes' ? 'selected="selected"' : '') . '>yes</option><option value="no" ' . ($f_forum_online == 'no' ? 'selected="selected"' : '') . '>no</option></td>
			</tr>
			<tr>
			<td align="right" valign="top">Offline Message</td><td><textarea name="f_offlinemsg" rows="6" cols="50">' . $f_offlinemsg . '</textarea></td>
			</tr>
			<tr>
			<td align="right">Forum Name</td><td><input class="bginput" type="text" size="40" name="f_forumname" value="' . $f_forumname . '"></td>
			</tr>
			<td align="right">Upload Path</td><td><input class="bginput" type="text" size="40" name="f_upload_path" value="' . $f_upload_path . '"></td>
			</tr>
			<td align="right">Upload Max.Size (KB)</td><td><input class="bginput" type="text" size="40" name="f_upload_maxsize" value="' . $f_upload_maxsize . '"> ' . mksize ($f_upload_maxsize * 1024) . '</td>
			</tr>

			<td align="right">Allowed File Types</td><td><input class="bginput" type="text" size="40" name="f_allowed_types" value="' . $f_allowed_types . '"> separated by ,</td>
			</tr>

			<tr>
			<td align="right">Threads Per Page</td><td><input class="bginput" type="text" size="5" name="f_threadsperpage" value="' . $f_threadsperpage . '"></td>
			</tr>
			<tr>
			<td align="right">Posts Per Page</td><td><input class="bginput" type="text" size="5" name="f_postsperpage" value="' . $f_postsperpage . '"></td>
			</tr>
			<tr>
			<td align="right">Minimum Message Length</td><td><input class="bginput" type="text" size="5" name="f_minmsglength" value="' . $f_minmsglength . '"></td>
			</tr>
			<tr>
			<td align="right">Avatar Max. Width</td><td><input class="bginput" type="text" size="5" name="f_avatar_maxwidth" value="' . $f_avatar_maxwidth . '"></td>
			</tr>
			<tr>
			<td align="right">Avatar Max. Height</td><td><input class="bginput" type="text" size="5" name="f_avatar_maxheight" value="' . $f_avatar_maxheight . '"></td>
			</tr>
			<tr>
			<td align="right">Avatar Max. Size (KB)</td><td><input class="bginput" type="text" size="5" name="f_avatar_maxsize" value="' . $f_avatar_maxsize . '"> ' . mksize ($f_avatar_maxsize) . '</td>
			</tr>
			<tr>
			<td align="right">Show Board Statistics?</td><td><select class="bginput" name="f_showstats"><option value="yes" ' . ($f_showstats == 'yes' ? 'selected="selected"' : '') . '>yes</option><option value="no" ' . ($f_showstats == 'no' ? 'selected="selected"' : '') . '>no</option><option value="staffonly" ' . ($f_showstats == 'staffonly' ? 'selected="selected"' : '') . '>Staff Only</option></select></td>
			</tr>
			<tr>
			<td align="right" valign="top">Sub Forum Per Line</td><td><input class="bginput" type="text" size="5" name="f_sfpertr" value="' . $f_sfpertr . '" /></td>
			</tr>
			<tr>
			<td align="right" valign="top">Advertisements Code<br>(Show Ads On Showthread Page)</td><td><textarea name="f_ads" rows="10" cols="90">' . $f_ads . '</textarea></td>
			</tr>
			<tr><td align="center" colspan="2"><input type="submit" value="save settings" class="hoptobutton"> <input type="reset" value="reset fields" class="hoptobutton">
		</tbody>
	</table>
	</form>';
    exit ();
  }

?>
