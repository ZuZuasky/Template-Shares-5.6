<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_friend_errors ()
  {
    global $errors;
    global $lang;
    if (0 < count ($errors))
    {
      $error = implode ('<br />', $errors);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->friends['sysmsg'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . htmlspecialchars_uni ($error) . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('F_VERSION', '0.8');
  if ($usergroups['canfriendlist'] != 'yes')
  {
    print_no_permission ();
  }

  $action = (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : ''));
  $tab = (isset ($_GET['tab']) ? htmlspecialchars_uni ($_GET['tab']) : (isset ($_POST['tab']) ? htmlspecialchars_uni ($_POST['tab']) : ''));
  $from = (isset ($_GET['from']) ? htmlspecialchars_uni ($_GET['from']) : (isset ($_POST['from']) ? htmlspecialchars_uni ($_POST['from']) : ''));
  $userid = intval ($CURUSER['id']);
  $friendid = (isset ($_GET['friendid']) ? intval ($_GET['friendid']) : (isset ($_POST['friendid']) ? intval ($_POST['friendid']) : 0));
  $lang->load ('friends');
  $errors = array ();
  $is_mod = is_mod ($usergroups);
  if ((($action == 'remove_friend' AND is_valid_id ($friendid)) AND $userid != $friendid))
  {
    $action = $tab;
    if ($from == 'pending')
    {
      @sql_query ('' . 'DELETE FROM friends WHERE userid = ' . $friendid . ' AND friendid = ' . $userid . ' AND status = \'p\'');
    }
    else
    {
      if ($from == 'mutual')
      {
        @sql_query ('' . 'DELETE FROM friends WHERE userid = ' . $friendid . ' AND friendid = ' . $userid . ' AND status=\'c\'');
      }
      else
      {
        @sql_query ('' . 'DELETE FROM friends WHERE userid = ' . $userid . ' AND friendid = ' . $friendid);
      }
    }
  }

  if ((($action == 'confirm_friend' AND is_valid_id ($friendid)) AND $userid != $friendid))
  {
    ($query = sql_query ('SELECT username FROM users WHERE status = \'confirmed\' AND enabled = \'yes\' AND usergroup NOT IN (' . UC_BANNED . ('' . ') AND id = ' . $friendid)) OR sqlerr (__FILE__, 88));
    if ((0 < mysql_num_rows ($query) AND $query))
    {
      $friendname = mysql_result ($query, '0', 'username');
      sql_query ('' . 'UPDATE friends SET status = \'c\' WHERE userid = ' . $friendid . ' AND friendid = ' . $userid);
      if (mysql_affected_rows ())
      {
        require_once INC_PATH . '/functions_pm.php';
        send_pm ($friendid, sprintf ($lang->friends['msg2'], $friendname, '[URL=' . ts_seo ($userid, $CURUSER['username']) . (('' . ']') . $CURUSER['username'] . '[/URL]'), '' . '[URL]' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '[/URL]'), $lang->friends['subject2']);
        $errors[] = $lang->friends['sysmsg4'];
      }
    }
    else
    {
      $errors[] = $lang->friends['sysmsg3'];
    }

    $action = $tab;
  }

  if ((($action == 'add_block' AND is_valid_id ($friendid)) AND $userid != $friendid))
  {
    $query = sql_query ('' . 'SELECT id FROM friends WHERE userid = ' . $userid . ' AND friendid = ' . $friendid);
    if (0 < mysql_num_rows ($query))
    {
      sql_query ('' . 'UPDATE friends SET status = \'b\' WHERE userid = ' . $userid . ' AND friendid = ' . $friendid);
    }
    else
    {
      (sql_query ('' . 'INSERT INTO friends (userid, friendid, status) VALUES (' . $userid . ', ' . $friendid . ', \'b\')') OR sqlerr (__FILE__, 116));
    }

    $action = 'blocks';
  }

  if ((($action == 'add_friend' AND is_valid_id ($friendid)) AND $userid != $friendid))
  {
    $query = sql_query ('' . 'SELECT id FROM friends WHERE userid = ' . $userid . ' AND friendid = ' . $friendid);
    $query2 = sql_query ('' . 'SELECT id FROM friends WHERE userid = ' . $friendid . ' AND friendid = ' . $userid . ' AND status=\'b\'');
    if (0 < mysql_num_rows ($query))
    {
      $errors[] = $lang->friends['sysmsg5'];
    }
    else
    {
      if (0 < mysql_num_rows ($query2))
      {
        $errors[] = $lang->friends['sysmsg6'];
      }
      else
      {
        ($query = sql_query ('SELECT username,options FROM users WHERE status = \'confirmed\' AND enabled = \'yes\' AND usergroup NOT IN (' . UC_BANNED . ('' . ') AND id = ' . $friendid)) OR sqlerr (__FILE__, 136));
        if ((0 < mysql_num_rows ($query) AND $query))
        {
          $friendprivacy = mysql_result ($query, 0, 'options');
          $friendname = mysql_result ($query, '0', 'username');
          if (!preg_match ('#I4#is', $friendprivacy))
          {
            (sql_query ('' . 'INSERT INTO friends (userid, friendid, status) VALUES (' . $userid . ', ' . $friendid . ', \'c\')') OR sqlerr (__FILE__, 143));
            $errors[] = $lang->friends['sysmsg2'];
          }
          else
          {
            (sql_query ('' . 'INSERT INTO friends (userid, friendid, status) VALUES (' . $userid . ', ' . $friendid . ', \'p\')') OR sqlerr (__FILE__, 148));
            require_once INC_PATH . '/functions_pm.php';
            send_pm ($friendid, sprintf ($lang->friends['msg'], $friendname, '[URL=' . ts_seo ($userid, $CURUSER['username']) . (('' . ']') . $CURUSER['username'] . '[/URL]'), '' . '[URL]' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?action=pending&tab=pending[/URL]'), $lang->friends['subject']);
            $errors[] = $lang->friends['sysmsg1'];
          }
        }
        else
        {
          $errors[] = $lang->friends['sysmsg3'];
        }
      }
    }
  }

  $imagepath = '' . $BASEURL . '/' . $pic_base_url . 'friends/';
  stdhead ($lang->friends['tab1']);
  show_friend_errors ();
  switch ($action)
  {
    case 'pending':
    {
      $status = 'p';
      $where = '' . 'f.friendid=' . $userid;
      $on = 'f.userid=u.id';
      $fwhat = 'f.userid';
      break;
    }

    case 'mutual':
    {
      $status = 'c';
      $where = '' . 'f.friendid=' . $userid;
      $on = 'f.userid=u.id';
      $fwhat = 'f.userid';
      break;
    }

    case 'blocks':
    {
      $status = 'b';
      $where = '' . 'f.userid=' . $userid;
      $on = 'f.friendid=u.id';
      $fwhat = 'f.friendid';
      break;
    }

    default:
    {
      $status = 'c';
      $where = '' . 'f.userid=' . $userid;
      $on = 'f.friendid=u.id';
      $fwhat = 'f.friendid';
      break;
    }
  }

  ($query = sql_query ('' . 'SELECT ' . $fwhat . ' as friendid, f.status, u.id, u.username, u.options, u.title, u.avatar, u.last_access, u.last_login, u.added, u.added, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle, g.title as grouptitle FROM friends f INNER JOIN users u ON (' . $on . ') LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE f.status=\'' . $status . '\' AND ' . $where . ' ORDER by u.username') OR sqlerr (__FILE__, 191));
  echo '
<div class="shadetabs">
	<ul>
		<li' . (!$tab ? ' class="selected"' : '') . '><a href="' . $_SERVER['SCRIPT_NAME'] . '">' . $lang->friends['tab1'] . '</a></li>
		<li' . ($tab == 'pending' ? ' class="selected"' : '') . '><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=pending&amp;tab=pending">' . $lang->friends['tab2'] . '</a></li>
		<li' . ($tab == 'blocks' ? ' class="selected"' : '') . '><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=blocks&amp;tab=blocks">' . $lang->friends['tab3'] . '</a></li>
		<li' . ($tab == 'mutual' ? ' class="selected"' : '') . '><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=mutual&amp;tab=mutual">' . $lang->friends['tab4'] . '</a></li>
	</ul>
</div>
<table width="100%" cellpadding="5" cellspacing="0">
';
  if (mysql_num_rows ($query) < 1)
  {
    echo '<tr><td>' . $lang->friends['nofriend'] . '</td></tr>';
  }
  else
  {
    $dt = get_date_time (gmtime () - TS_TIMEOUT);
    include_once INC_PATH . '/functions_icons.php';
    while ($friend = mysql_fetch_assoc ($query))
    {
      if (preg_match ('#L1#is', $friend['options']))
      {
        $UserGender = '<img src="' . $imagepath . 'Male.png" alt="Male" title="Male" border="0" class="inlineimg" />';
      }
      else
      {
        if (preg_match ('#L2#is', $friend['options']))
        {
          $UserGender = '<img src="' . $imagepath . 'Female.png" alt="Female" title="Female" border="0" class="inlineimg" />';
        }
        else
        {
          $UserGender = '<img src="' . $imagepath . 'NA.png" alt="--" title="--" border="0" class="inlineimg" />';
        }
      }

      $xoffline = sprintf ($lang->friends['xoffline'], $friend['username']);
      $xonline = sprintf ($lang->friends['xonline'], $friend['username']);
      $xavatar = sprintf ($lang->friends['xavatar'], $friend['username']);
      if (((preg_match ('#B1#is', $friend['options']) AND !$is_mod) AND $friend['id'] != $userid))
      {
        $friend['last_access'] = $friend['last_login'];
        $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0">';
      }
      else
      {
        if (($dt < $friend['last_access'] OR $friend['id'] == $userid))
        {
          $onoffpic = '<img src="' . $imagepath . 'online.png" alt="' . $xonline . '" title="' . $xonline . '" border="0">';
        }
        else
        {
          $onoffpic = '<img src="' . $imagepath . 'offline.png" alt="' . $xoffline . '" title="' . $xoffline . '" border="0">';
        }
      }

      echo '
		<tr>
		<td>
		<div>
		<div style="border-right: 1px dotted black; float: left; margin-right: 3px;">
		<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=remove_friend&amp;friendid=' . $friend['friendid'] . ($tab == 'pending' ? '&amp;from=pending' : ($tab == 'mutual' ? '&amp;from=mutual' : '')) . '&amp;tab=' . $action . '" title="' . $lang->friends['act1'] . '"><img src="' . $imagepath . 'remove.gif" alt="" border="0"></a>
		<br />';
      if ($friend['status'] == 'p')
      {
        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=confirm_friend&amp;friendid=' . $friend['friendid'] . '&amp;tab=' . $action . '" title="' . $lang->friends['act4'] . '"><img src="' . $imagepath . 'confirm.png" alt="" border="0"></a>';
      }
      else
      {
        echo '<a href="' . $BASEURL . '/sendmessage.php?receiver=' . $friend['friendid'] . '" title="' . $lang->friends['act2'] . '"><img src="' . $imagepath . 'pm.png" alt="" border="0"></a>';
      }

      echo '
		</div>

		<div style="float: right;">
		<img src="' . ($friend['avatar'] ? fix_url ($friend['avatar']) : $BASEURL . '/' . $pic_base_url . 'default_avatar.gif') . '" alt="' . $xavatar . '" title="' . $xavatar . '" height="40" width="40">
		</div>
		' . $UserGender . '
		<strong><a href="' . ts_seo ($friend['friendid'], $friend['username']) . '">' . get_user_color ($friend['username'], $friend['namestyle']) . '</a></strong> (' . ($friend['title'] ? htmlspecialchars_uni ($friend['title']) : $friend['grouptitle']) . ') ' . get_user_icons ($friend) . '
		<br />
		' . $onoffpic . '
		<strong>' . $lang->friends['act3'] . ' ' . my_datee ($dateformat, $friend['last_access']) . ' ' . my_datee ($timeformat, $friend['last_access']) . '</strong>
		</div>
		</td>
		</tr>
		';
    }
  }

  echo '
</table>';
  stdfoot ();
?>
