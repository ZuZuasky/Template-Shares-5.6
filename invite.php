<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function check_amount ($uid)
  {
    $res = sql_query ('SELECT invites FROM users WHERE id = ' . sqlesc ($uid));
    if (mysql_num_rows ($res) == 0)
    {
      return false;
    }

    $amount = mysql_fetch_array ($res);
    if ($amount['invites'] < 1)
    {
      return false;
    }

    return true;
  }

  function invite_amount ($uid)
  {
    $res = sql_query ('SELECT invites FROM users WHERE id = ' . sqlesc ($uid));
    $amount = mysql_fetch_array ($res);
    if (($amount['invites'] == 1 OR $amount['invites'] == 2))
    {
      $msg = '<font color=red>' . $amount['invites'] . '</font>';
    }
    else
    {
      $msg = '<font color=green>' . $amount['invites'] . '</font>';
    }

    return $msg;
  }

  function is_email_exists ($email)
  {
    $check1 = sql_query ('SELECT email FROM users WHERE email = ' . sqlesc ($email));
    if (1 <= mysql_num_rows ($check1))
    {
      return false;
    }

    $check2 = sql_query ('SELECT invitee FROM invites WHERE invitee = ' . sqlesc ($email));
    if (1 <= mysql_num_rows ($check2))
    {
      return false;
    }

    return true;
  }

  function failed ($msg, $error = true, $clean = false)
  {
    global $lang;
    if ($error)
    {
      $msg = $msg . ' Click <a href="javascript: history.back(1)">here</a> to go back';
    }

    stdmsg (($error ? $lang->invite['failed'] : $lang->invite['success']), $msg, $clean);
    stdfoot ();
    exit ();
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('I_VERSION', '1.2');
  require INC_PATH . '/readconfig_signup.php';
  require INC_PATH . '/readconfig_cleanup.php';
  if ($ai == 'yes')
  {
    $query = sql_query ('SELECT u.id, u.modcomment, u.lastinvite, u.usergroup, g.autoinvite FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' AND u.usergroup != ' . UC_BANNED . ' AND u.status=\'confirmed\' AND UNIX_TIMESTAMP(u.lastinvite) < ' . (TIMENOW - $autoinvitetime * 24 * 60 * 60) . ' AND g.autoinvite > 0');
    if (0 < mysql_num_rows ($query))
    {
      $lang->load ('cronjobs');
      require_once INC_PATH . '/functions_pm.php';
      while ($arr = mysql_fetch_assoc ($query))
      {
        sql_query ('UPDATE users SET lastinvite = NOW(), invites = invites + ' . $arr['autoinvite'] . ', modcomment = ' . sqlesc (gmdate ('Y-m-d') . ' - Earned ' . $arr['autoinvite'] . ' invites by system.
' . $arr['modcomment']) . ' WHERE id = ' . sqlesc ($arr['id']));
        send_pm ($arr['id'], sprintf ($lang->cronjobs['invite_message'], $arr['autoinvite'], $arr['id']), $lang->cronjobs['invite_subject']);
      }
    }
  }

  if ($usergroups['caninvite'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  $lang->load ('invite');
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'main'));
  $type = (isset ($_POST['type']) ? htmlspecialchars ($_POST['type']) : (isset ($_GET['type']) ? htmlspecialchars ($_GET['type']) : ''));
  $is_mod = is_mod ($usergroups);
  stdhead ($lang->invite['head'], true, 'collapse');
  if (((isset ($_GET['id']) AND is_valid_id ($_GET['id'])) AND ($is_mod OR $usergroups['canuserdetails'] == 'yes')))
  {
    $inviterid = (int)$_GET['id'];
    $ra = sql_query ('SELECT username FROM users where id = ' . sqlesc ($inviterid));
    $raa = mysql_fetch_array ($ra);
    $invitername = htmlspecialchars (trim ($raa['username']));
  }
  else
  {
    $inviterid = (int)$CURUSER['id'];
    $invitername = htmlspecialchars (trim ($CURUSER['username']));
  }

  if ($action == 'delete')
  {
    $error = false;
    $deleteids = $_POST['id'];
    if ((empty ($deleteids) OR !is_array ($deleteids)))
    {
      $error = true;
    }
    else
    {
      foreach ($deleteids as $id)
      {
        if (!is_valid_id ($id))
        {
          $error = true;
          break;
        }
      }
    }

    if (!$error)
    {
      $ids = implode (',', $deleteids);
      sql_query ('' . 'DELETE FROM invites WHERE id IN (' . $ids . ') AND inviter = ' . sqlesc ($inviterid));
    }

    $action = 'main';
  }

  if ($action == 'main')
  {
    $res = sql_query ('SELECT invites FROM users WHERE id = ' . sqlesc ($inviterid));
    $inv = mysql_fetch_array ($res);
    if ($inv['invites'] != 1)
    {
      $_s = 's';
    }
    else
    {
      $_s = '';
    }

    $number = tsrowcount ('id', 'users', 'invited_by=' . $inviterid);
    $ret = sql_query ('SELECT u.id, u.username, u.email, u.uploaded, u.last_access, u.last_login, u.options, u.added, u.downloaded, u.status, u.warned, u.enabled, u.donor, u.email, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.invited_by = ' . sqlesc ($inviterid));
    $num = mysql_num_rows ($ret);
    echo '
	<p align="right">
		<input value="' . $lang->invite['button'] . '" onclick="jumpto(\'invite.php?action=send\');" type="button">
	</p>';
    echo '<table border=1 width=100% cellspacing=0 cellpadding=5><tr class=tabletitle><td colspan="8" class="colhead">' . ts_collapse ('invitetable1') . '<b>' . $lang->invite['status'] . '</b> (' . $number . ') </td></tr>' . ts_collapse ('invitetable1', 2);
    if (!$num)
    {
      $str = '<tr class=tableb><td colspan=8>' . $lang->invite['noinvitesyet'] . '</td></tr></tbody>';
    }
    else
    {
      print '<tr class=tableb><td class="subheader"><b>' . $lang->invite['username'] . '</b></td><td class="subheader"><b>' . $lang->invite['email'] . '</b></td><td class="subheader"><b>' . $lang->invite['added'] . '</b></td><td class="subheader"><b>' . $lang->invite['lastseen'] . '</b></td><td class="subheader"><b>' . $lang->invite['uploaded'] . '</b></td><td class="subheader"><b>' . $lang->invite['downloaded'] . '</b></td><td class="subheader"><b>' . $lang->invite['ratio'] . '</b></td><td class="subheader"><b>' . $lang->invite['status2'] . '</b></td></tr>';
      $dt = get_date_time (gmtime () - TS_TIMEOUT);
      while ($arr = mysql_fetch_array ($ret))
      {
        $orj_username = $arr['username'];
        $arr['username'] = get_user_color ($arr['username'], $arr['namestyle']);
        $registered = my_datee ($dateformat, $arr['added']) . ' ' . my_datee ($timeformat, $arr['added']);
        $lastseen = $arr['last_access'];
        if ((preg_match ('#B1#is', $arr['options']) AND !$is_mod))
        {
          $lastseen = $arr['last_login'];
        }

        if (($lastseen == '0000-00-00 00:00:00' OR $lastseen == '-'))
        {
          $lastseen = $lang->invite['never'];
        }
        else
        {
          $lastseen = my_datee ($dateformat, $lastseen) . ' ' . my_datee ($timeformat, $lastseen);
        }

        if ($arr['status'] == 'pending')
        {
          $user = '' . '<a href=checkuser.php?id=' . $arr['id'] . '>' . $arr['username'] . '</a>';
        }
        else
        {
          $user = '<a href="' . ts_seo ($arr['id'], $orj_username) . ('' . '">' . $arr['username'] . '</a>') . ($arr['warned'] == 'yes' ? '&nbsp;<img src=' . $pic_base_url . 'warned.gif border=0 alt=\'' . $lang->global['imgwarned'] . '\' title=\'' . $lang->global['imgwarned'] . '\'>' : '') . '&nbsp;' . ($arr['enabled'] == 'no' ? '&nbsp;<img src=' . $pic_base_url . 'disabled.gif border=0 alt=\'' . $lang->global['imgdisabled'] . '\' title=\'' . $lang->global['imgdisabled'] . '\'>' : '') . '&nbsp;' . ($arr['donor'] == 'yes' ? '<img src=' . $pic_base_url . 'star.gif border=0 alt=\'' . $lang->global['imgdonated'] . '\' title=\'' . $lang->global['imgdonated'] . '\'>' : '');
        }

        if (0 < $arr['downloaded'])
        {
          include_once INC_PATH . '/functions_ratio.php';
          $ratio = number_format ($arr['uploaded'] / $arr['downloaded'], 2);
          $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
        }
        else
        {
          if (0 < $arr['uploaded'])
          {
            $ratio = 'Inf.';
          }
          else
          {
            $ratio = '---';
          }
        }

        if ($arr['status'] == 'confirmed')
        {
          $status = '<a href="' . ts_seo ($arr['id'], $orj_username) . '"><font color=#1f7309>' . $lang->invite['confirmed'] . '</font></a>';
        }
        else
        {
          $status = '' . '<a href=checkuser.php?id=' . $arr['id'] . '><font color=#ca0226>' . $lang->invite['pending'] . '</font></a>';
        }

        $str .= '' . '<tr class=tableb><td>' . $user . '</td><td><a href=mailto:' . $arr['email'] . '>' . $arr['email'] . '</a></td><td>' . $registered . '</td><td>' . $lastseen . '</td><td>' . mksize ($arr['uploaded']) . '</td><td>' . mksize ($arr['downloaded']) . ('' . '</td><td>' . $ratio . '</td><td>' . $status . '</td></tr>');
      }
    }

    echo $str . '</tbody></table><br />';
    unset ($str);
    $number1 = tsrowcount ('id', 'invites', 'inviter=' . $inviterid);
    $rer = sql_query ('SELECT id, invitee, hash, time_invited FROM invites WHERE inviter = ' . sqlesc ($inviterid));
    $num1 = mysql_num_rows ($rer);
    print '<table border=1 width=100% cellspacing=0 cellpadding=5>' . '<tr class=tabletitle><td colspan=5 class=colhead>' . ts_collapse ('invitetable2') . '<b>' . $lang->invite['status3'] . ('' . '</b> (' . $number1 . ')</td></tr>') . ts_collapse ('invitetable2', 2);
    if (!$num1)
    {
      $str = '<tr class=rowhead><td colspan=5>' . $lang->invite['nooutyet'] . '</tr>';
    }
    else
    {
      print '<tr class=rowhead><td class="subheader"><b>' . $lang->invite['email'] . '</b></td><td class="subheader"><b>' . $lang->invite['hash'] . '</b></td><td class="subheader"><b>' . $lang->invite['senddate'] . '</b></td><td class="subheader"><b>' . $lang->invite['invitedeadtime'] . '</b></td><td class="subheader" align="center"><b>' . $lang->invite['action'] . '</b></td></tr>';
      print '<form method=\'post\' action=\'' . $_SERVER['SCRIPT_NAME'] . '\'><input type=\'hidden\' name=\'action\' value=\'delete\'>';
      include_once INC_PATH . '/readconfig_cleanup.php';
      $i = 0;
      while ($i < $num1)
      {
        $arr1 = mysql_fetch_array ($rer);
        $timeout = strtotime ($arr1['time_invited']) + 172800;
        $timeoutdate = my_datee ($dateformat, $timeout);
        $timeouttime = my_datee ($timeformat, $timeout);
        $senddate = my_datee ($dateformat, $arr1['time_invited']) . ' ' . my_datee ($timeformat, $arr1['time_invited']);
        $_m_link = strip_tags (sprintf ($lang->invite['manuellink'], $BASEURL, $arr1[hash]));
        $str .= '' . '<tr class=rowhead><td>' . $arr1['invitee'] . '<td><span style="float: right;"><a href="" onclick="javascript:prompt(\'' . $_m_link . '\',\'' . $BASEURL . '/signup.php?invitehash=' . $arr1[hash] . '&type=invite\'); return false;"><img src="' . $BASEURL . '/' . $pic_base_url . 'plus.gif" alt="' . $lang->invite[hash] . '" title="' . $lang->invite[hash] . '" border=""></a></span>' . $arr1['hash'] . '</td><td>' . $senddate . '</td><td>' . $timeoutdate . ' ' . $timeouttime . '</td><td align=\'center\'><input type=\'checkbox\' name=\'id[]\' value=\'' . $arr1['id'] . '\'></td></tr>';
        ++$i;
      }

      $str .= '<tr><td colspan=\'5\' align=\'right\'><input type=\'submit\' value=\'' . $lang->invite['actionbutton'] . '\'></form></td></tr>';
    }

    echo $str . '</table>';
  }
  else
  {
    if ($action == 'send')
    {
      $alert = false;
      if (!check_amount ($inviterid))
      {
        failed ($lang->invite['noinvitesleft']);
      }
      else
      {
        if (($invitesystem == 'off' AND $is_mod))
        {
          $alert = true;
        }
        else
        {
          if ($invitesystem == 'off')
          {
            failed ($lang->invite['invitesystemoff']);
          }
        }
      }

      if ($alert)
      {
        echo '<div class="error">' . $lang->invite['alert'] . '</div>';
      }

      if ($type == 'email')
      {
        echo '<table border="1" cellspacing="0" cellpadding="10" width="100%">';
        echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="sendinvite" ' . submit_disable ('sendinvite', 'send') . '>';
        echo '<input type="hidden" name="action" value="sendinvite">';
        tr ($lang->invite['field1'], '<input type="text" name="email" id="specialboxn"> <b><font color=red>' . $lang->invite['field2'] . '</b></font>', 1);
        tr ($lang->invite['field3'], '<textarea name="note" rows="10" cols="40" tabindex="2" wrap="virtual" id="specialboxg">' . $lang->invite['default_invite_msg'] . '</textarea>', 1);
        tr (sprintf ($lang->invite['field4'], invite_amount ($inviterid)), '<input type="submit" value="' . $lang->invite['button2'] . '" name="send"> <input type="reset" value="' . $lang->invite['button3'] . '">', 1);
        echo '</table>';
      }
      else
      {
        if ($type == 'manual')
        {
          $hash = substr (md5 (md5 (rand ())), 0, 32);
          $time_invited = get_date_time ();
          sql_query ('INSERT INTO invites (inviter, invitee, hash, time_invited) VALUES (' . sqlesc ($inviterid) . ', ' . sqlesc ('manual') . ', ' . sqlesc ($hash) . ', ' . sqlesc ($time_invited) . ')');
          if (mysql_affected_rows () != 1)
          {
            failed ($lang->invite['error']);
          }
          else
          {
            sql_query ('UPDATE users SET invites = invites - 1 WHERE id = ' . sqlesc ($inviterid));
          }

          if (mysql_affected_rows () != 1)
          {
            failed ($lang->invite['error']);
          }
          else
          {
            stdmsg ($lang->invite['success'], sprintf ($lang->invite['manuellink'], $BASEURL, $hash), false, 'success');
            stdfoot ();
            exit ();
          }
        }
        else
        {
          echo '
		<table border="1" cellspacing="0" cellpadding="10" width="100%">
		<tr><td class="thead">' . $lang->invite['selecttype'] . '</td></tr>
		<tr><td class="trow1">
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="sendinvite" ' . submit_disable ('sendinvite', 'submit') . '>
		<input type="hidden" name="action" value="send">
		<select name="type">
		<option value="email">' . $lang->invite['type1'] . ' </option>
		<option value="manual">' . $lang->invite['type2'] . ' </option>
		</select>
		 <input type="submit" name="submit" value="' . $lang->invite['typebutton'] . ' " class="hoptobuttons">
		 </form>
		 </td></tr>
		</table>';
        }
      }
    }
    else
    {
      if ($action == 'sendinvite')
      {
        function safe_email ($email)
        {
          return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
        }

        if (($invitesystem == 'off' AND !$is_mod))
        {
          failed ($lang->invite['invitesystemoff']);
        }

        if (!check_amount ($inviterid))
        {
          failed ($lang->invite['noinvitesleft']);
        }

        $email = htmlspecialchars_uni (safe_email ($_POST['email']));
        if (!check_email ($email))
        {
          failed ($lang->invite['invalidemail']);
        }

        if (!is_email_exists ($email))
        {
          failed ($lang->invite['invalidemail2']);
        }

        $note = htmlspecialchars_uni ($_POST['note']);
        if (empty ($note))
        {
          $note = $lang->invite['nonote'];
        }

        $subject = sprintf ($lang->invite['subject'], $SITENAME);
        $time_invited = get_date_time ();
        $invitehash = substr (md5 (md5 (rand ())), 0, 32);
        include_once INC_PATH . '/readconfig_cleanup.php';
        $message = sprintf ($lang->invite['message'], $invitername, $SITENAME, $BASEURL, $invitehash, 2, $note);
        sql_query ('INSERT INTO invites (inviter, invitee, hash, time_invited) VALUES (' . sqlesc ($inviterid) . ', ' . sqlesc ($email) . ', ' . sqlesc ($invitehash) . ', ' . sqlesc ($time_invited) . ')');
        if (mysql_affected_rows () != 1)
        {
          failed ($lang->invite['error']);
        }
        else
        {
          sql_query ('UPDATE users SET invites = invites - 1 WHERE id = ' . sqlesc ($inviterid));
        }

        if (mysql_affected_rows () != 1)
        {
          failed ($lang->invite['error']);
        }
        else
        {
          sent_mail ($email, $subject, $message, 'invitesignup', false);
        }

        failed (sprintf ($lang->invite['sent'], $email), false);
      }
    }
  }

  stdfoot ();
?>
