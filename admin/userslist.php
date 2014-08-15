<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_ipban_cache ()
  {
    global $cache;
    ($query = sql_query ('SELECT * FROM ipbans') OR sqlerr (__FILE__, 182));
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

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('UL_VERSION', '0.4 by xam');
  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : ''));
    if ($action == 'banuser')
    {
      $id = (int)$_POST['banusr'];
      ($arr = sql_query ('SELECT id,username,ip,modcomment FROM users WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 24));
      if (1 <= mysql_num_rows ($arr))
      {
        $user = mysql_fetch_array ($arr);
      }
      else
      {
        stderr ('Error', 'No user with this ID! Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
      }

      $ip = $user['ip'];
      $userid = $user['id'];
      $modcomment = $user['modcomment'];
      $username = $user['username'];
      $modifier = (int)$CURUSER['id'];
      $dateline = sqlesc (time ());
      $date = sqlesc (get_date_time ());
      $reason = sqlesc (gmdate ('Y-m-d') . ' - Banned by ' . $CURUSER['username'] . ' via Tracker.');
      $newmodcomment = gmdate ('Y-m-d') . ' - Banned by ' . $CURUSER['username'] . '.
' . $modcomment;
      ($bans = sql_query ('SELECT value FROM ipbans LIMIT 1') OR sqlerr (__FILE__, 39));
      if (1 <= mysql_num_rows ($bans))
      {
        $banned = mysql_fetch_array ($bans);
        $value = sqlesc ('' . $banned['value'] . ' ' . $ip);
        (sql_query ('' . 'UPDATE ipbans SET value=' . $value . ', date=' . $date . ', modifier=' . sqlesc ($modifier)) OR sqlerr (__FILE__, 43));
        update_ipban_cache ();
      }
      else
      {
        $value = sqlesc ('' . $ip);
        (sql_query ('' . 'INSERT INTO ipbans (value,date,modifier) VALUES (' . $value . ', ' . $date . ', ' . sqlesc ($modifier) . ')') OR sqlerr (__FILE__, 47));
        update_ipban_cache ();
      }

      (sql_query ('UPDATE users SET enabled = \'no\', usergroup = \'' . UC_BANNED . '\', modcomment = ' . sqlesc ($newmodcomment) . ' WHERE id = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 51));
      write_log ('' . 'User (' . $username . ') banned by ' . $CURUSER['username']);
      stderr ('Done!', 'User [' . htmlspecialchars ($username) . '] successfull banned. Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
    }
    else
    {
      if ($action == 'unbanuser')
      {
        $id = (int)$_POST['unbanusr'];
        ($arr = sql_query ('SELECT id,username,ip,modcomment FROM users WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 56));
        if (1 <= mysql_num_rows ($arr))
        {
          $user = mysql_fetch_array ($arr);
        }
        else
        {
          stderr ('Error', 'No user with this ID! Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
        }

        $ip = $user['ip'];
        $userid = $user['id'];
        $modcomment = $user['modcomment'];
        $username = $user['username'];
        $nowdate = sqlesc (get_date_time ());
        $modifier = (int)$CURUSER['id'];
        $newmodcomment = gmdate ('Y-m-d') . ' - Unbanned by ' . $CURUSER['username'] . '.
' . $modcomment;
        ($bans = sql_query ('SELECT value FROM ipbans LIMIT 1') OR sqlerr (__FILE__, 69));
        if (1 <= mysql_num_rows ($bans))
        {
          $banned = mysql_fetch_array ($bans);
          $value = str_replace ($ip, '', $banned[value]);
          (sql_query ('UPDATE ipbans SET value=' . sqlesc ($value) . ('' . ', date=' . $nowdate . ', modifier=') . sqlesc ($modifier)) OR sqlerr (__FILE__, 73));
          update_ipban_cache ();
        }

        write_log ('' . 'User (' . $username . ') unbanned by ' . $CURUSER['username']);
        stderr ('Done!', 'User [' . htmlspecialchars ($username) . '] successfull unbanned. Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
      }
      else
      {
        if ($action == 'deleteuser')
        {
          $id = (int)$_POST['delusr'];
          ($arr = sql_query ('SELECT id,username FROM users WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 81));
          if (1 <= mysql_num_rows ($arr))
          {
            $user = mysql_fetch_array ($arr);
          }
          else
          {
            stderr ('Error', 'No user with this ID! Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
          }

          $userid = $user['id'];
          $username = $user['username'];
          (sql_query ('DELETE FROM users WHERE id = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 88));
          write_log ('' . 'User (' . $username . ') deleted by ' . $CURUSER['username']);
          stderr ('Done!', 'User [' . htmlspecialchars ($username) . '] successfull deleted. Click <a href=' . $_this_script_ . '>here</a> to return UserList.', false);
        }
      }
    }
  }

  unset ($query);
  if (isset ($_GET['searchby']))
  {
    if ($_GET['searchby'] == 'banned')
    {
      $query = '(u.enabled = \'no\' OR u.usergroup=' . UC_BANNED . ')';
    }
    else
    {
      if ($_GET['searchby'] == 'warned')
      {
        $query = '(u.warned = \'yes\' OR u.leechwarn = \'yes\')';
      }
      else
      {
        if ($_GET['searchby'] == 'donor')
        {
          $query = 'u.donor = \'yes\'';
        }
        else
        {
          if ($_GET['searchby'] == 'vip')
          {
            $query = 'u.usergroup = \'' . UC_VIP . '\'';
          }
          else
          {
            if ($_GET['searchby'] == 'poweruser')
            {
              $query = 'u.usergroup = \'' . UC_POWER_USER . '\'';
            }
            else
            {
              $query = 'u.usergroup = \'' . UC_USER . '\'';
            }
          }
        }
      }
    }
  }
  else
  {
    $query = 'u.usergroup = \'' . UC_USER . '\'';
  }

  (($res = sql_query ('SELECT COUNT(*) FROM users WHERE ' . str_replace ('u.', '', $query)) OR sqlerr (__FILE__, 110)) OR sqlerr (__FILE__, 110));
  $row = mysql_fetch_array ($res);
  $count = $row[0];
  $perpage = $ts_perpage;
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $_this_script_ . '&' . ($_GET['searchby'] ? 'searchby=' . htmlspecialchars ($_GET['searchby'] . '&') : ''));
  stdhead ('UsersList');
  if (mysql_num_rows ($res) == 0)
  {
    begin_main_frame ();
  }

  $users = number_format (tsrowcount ('id', 'users', 'usergroup=\'' . UC_USER . '\''));
  begin_frame ('' . 'Users List (' . $users . ')', true);
  begin_table (true);
  echo '<tr><td colspan=7>Search: <a href=';
  echo $_this_script_;
  echo '&searchby=all>Show ALL</a> | <a href=';
  echo $_this_script_;
  echo '&searchby=banned>banned</a>| <a href=';
  echo $_this_script_;
  echo '&searchby=warned>warned</a> | <a href=';
  echo $_this_script_;
  echo '&searchby=donor>donor</a> | <a href=';
  echo $_this_script_;
  echo '&searchby=vip>vip</a> | <a href=';
  echo $_this_script_;
  echo '&searchby=poweruser>poweruser</td></tr>

<tr>
<td class="colhead">ID</td>
<td class="colhead" align="left">Username</td>
<td class="colhead" align="left">e-mail</td>
<td class="colhead" align="left">Joined</td>
<td class="colhead" align="center">DELETE</td>
<td class="colhead" align="center">BAN</td>
<td class="colhead" align="center">UNBAN</td>
</tr>
';
  ($res = sql_query ('' . 'SELECT u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE ' . $query . ' ORDER BY u.id DESC ' . $limit) OR sqlerr (__FILE__, 137));
  if (1 <= mysql_num_rows ($res))
  {
    while ($arr = @mysql_fetch_array ($res))
    {
      $pic = get_user_icons ($arr);
      $cn = get_user_class_name ($arr['usergroup']);
      echo '<tr>
<td>' . $arr[id] . '</td>
<td align="left"><b><a href=' . $BASEURL . '/userdetails.php?id=' . $arr[id] . '>' . get_user_color ($arr['username'], $arr['namestyle']) . ' ' . $pic . ' (' . $cn . ')</b> ' . (($arr['enabled'] == 'no' ? '<font color=red>(banned)</font>' : $arr['usergroup'] == 9) ? '<font color=red>(banned)</font>' : '') . (($arr['warned'] == 'yes' ? '<font color=red>(warned)</font>' : $arr['leechwarn'] == 'yes') ? '<font color=red>(leechwarned)</font>' : '') . '</a><br />' . htmlspecialchars_uni ($arr['ip']) . '</td>
<td align="left"><a href=mailto:' . $arr[email] . '>' . $arr[email] . '</a></td>
<td align="left">' . $arr[added] . '</td>
<td align="center">
<form method=post action=' . $_this_script_ . ('' . '>
<input type=hidden name=action value=deleteuser>
<input type="hidden" name="delusr" value="' . $arr['id'] . '" />
<input class=button type="submit" value="DELETE">
</form></td>
<td align="center">
<form method=post action=') . $_this_script_ . '>
<input type=hidden name=action value=banuser>
<input type="hidden" name="banusr" value="' . $arr[id] . '" />
<input class=button type="submit" value="BAN">
</form></td>
<td align="center">
<form method=post action=' . $_this_script_ . '>
<input type=hidden name=action value=unbanuser>
<input type="hidden" name="unbanusr" value="' . $arr[id] . '" />
<input class=button type="submit" value="UNBAN">
</form></td></tr>';
    }

    echo '</form>
';
    echo '<tr><td colspan=7 align=center>' . $pagerbottom . '</td></tr>';
  }
  else
  {
    echo '<td><td colspan=7><div class=error>No user found!</div></td></tr>';
  }

  end_table ();
  end_frame ();
  end_main_frame ();
  stdfoot ();
?>
