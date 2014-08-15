<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function check_msg_perm ($receiver = array ())
  {
    global $lang;
    global $CURUSER;
    if (preg_match ('#A1#is', $receiver['options']))
    {
      return $lang->messages['error9'];
    }

    if (preg_match ('#K1#is', $receiver['options']))
    {
      $res2 = sql_query ('SELECT id FROM friends WHERE status=\'b\' AND userid=' . (int)$receiver['id'] . ' AND friendid=' . (int)$CURUSER['id']);
      if (0 < mysql_num_rows ($res2))
      {
        return $lang->messages['error5'];
      }
    }
    else
    {
      if (preg_match ('#K2#is', $receiver['options']))
      {
        $res2 = sql_query ('SELECT id FROM friends WHERE status=\'c\' AND userid=' . (int)$receiver['id'] . ' AND friendid=' . (int)$CURUSER['id']);
        if (mysql_num_rows ($res2) < 1)
        {
          return $lang->messages['error6'];
        }
      }
      else
      {
        return $lang->messages['error7'];
      }
    }

  }

  function show_sm_errors ()
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
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $error . '
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
  define ('SM_VERSION', 'v2.0.1 ');
  define ('NcodeImageResizer', true);
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('messages');
  $is_mod = is_mod ($usergroups);
  $errors = array ();
  require_once INC_PATH . '/functions_message.php';
  require_once INC_PATH . '/functions_pm.php';
  if ($usergroups['canpm'] != 'yes')
  {
    print_no_permission ();
  }

  ($query = sql_query ('SELECT canmessage FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id'])) OR sqlerr (__FILE__, 36));
  if (0 < mysql_num_rows ($query))
  {
    $messageperm = mysql_fetch_assoc ($query);
    if ($messageperm['canmessage'] == '0')
    {
      print_no_permission ();
    }
  }

  $query = sql_query ('SELECT added FROM messages WHERE sender = ' . sqlesc ($CURUSER['id']) . ' ORDER by added DESC LIMIT 1');
  if (0 < mysql_num_rows ($query))
  {
    $last_pm = mysql_result ($query, 0, 'added');
    flood_check ($lang->messages['floodcomment'], $last_pm);
  }

  if (($_POST['previewpost'] AND !empty ($_POST['message'])))
  {
    $avatar = get_user_avatar ($CURUSER['avatar']);
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
	</tr></table><br />';
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    require_once INC_PATH . '/class_page_check.php';
    $newpage = new page_verify ();
    $newpage->check ('sendmessage');
    if (isset ($_POST['submit']))
    {
      $save = ($_POST['save'] == 'yes' ? 'yes' : 'no');
      $msg = trim ($_POST['message']);
      $subject = trim ($_POST['subject']);
      $receiver = htmlspecialchars_uni ($_POST['receivername']);
      $origmsg = 0 + $_POST['origmsg'];
      if ((!empty ($origmsg) AND !is_valid_id ($origmsg)))
      {
        $errors[] = $lang->messages['newtitle11'];
      }

      if (((empty ($msg) OR empty ($subject)) OR empty ($receiver)))
      {
        $errors[] = $lang->global['dontleavefieldsblank'];
      }

      if (count ($errors) == 0)
      {
        if (strpos ($receiver, ';') === false)
        {
          if (($CURUSER['username'] == $receiver AND !$is_mod))
          {
            $errors[] = $lang->messages['msgsenderror'];
          }
          else
          {
            $where = 'WHERE u.enabled= \'yes\' AND u.username=' . sqlesc ($receiver);
            $res = sql_query ('SELECT u.username,u.usergroup,u.options,u.email,u.id,u.notifs, UNIX_TIMESTAMP(u.last_access) as la, g.canstaffpanel, g.issupermod, g.cansettingspanel FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) ' . $where);
            $receiver = mysql_fetch_assoc ($res);
            if ((!$receiver OR !$receiver['username']))
            {
              $errors[] = $lang->messages['nouser'];
            }
            else
            {
              if (!$is_mod)
              {
                $check_msg_perm = check_msg_perm ($receiver);
                if (!empty ($check_msg_perm))
                {
                  $errors[] = $check_msg_perm;
                }
              }

              if (((!pm_limit (false, true, $receiver['id'], $receiver['usergroup']) AND !is_mod ($receiver)) AND !$is_mod))
              {
                $errors[] = $lang->messages['error10'];
              }

              if (count ($errors) == 0)
              {
                send_pm ($receiver['id'], $msg, $subject, $CURUSER['id'], $save);
                $msgid = mysql_insert_id ();
                $date = get_date_time ();
                if (strpos ($receiver['notifs'], '[pm]') === false)
                {
                }
                else
                {
                  $username = trim ($CURUSER['username']);
                  $msg_receiver = trim ($receiver['username']);
                  $body = sprintf ($lang->messages['body'], $msg_receiver, $SITENAME, $username, $BASEURL);
                  @sent_mail ($receiver['email'], @sprintf ($lang->messages['msgsubject'], $SITENAME), $body, 'sendmessage', false);
                }
              }
            }
          }
        }
        else
        {
          $receiver = preg_replace ('#\\s+#', '', $receiver);
          $Array = explode (';', $receiver);
          if (5 < count ($Array))
          {
            $errors[] = sprintf ($lang->messages['smmultiplerrror2'], count ($Array));
          }

          if ((in_array ($CURUSER['username'], $Array) AND !$is_mod))
          {
            $errors[] = $lang->messages['msgsenderror'];
          }

          if (count ($errors) == 0)
          {
            $AUsername = '';
            $PostedUsers = array ();
            foreach ($Array as $receiver)
            {
              $AUsername = htmlspecialchars_uni ($receiver);
              if (!in_array ($AUsername, $PostedUsers))
              {
                $where = 'WHERE u.enabled=\'yes\' AND u.username=' . sqlesc (trim ($receiver));
                $res = sql_query ('SELECT u.username,u.usergroup,u.options,u.email,u.id,u.notifs, UNIX_TIMESTAMP(u.last_access) as la, g.canstaffpanel, g.issupermod, g.cansettingspanel FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) ' . $where);
                $receiver = mysql_fetch_assoc ($res);
                if ((!$receiver OR !$receiver['username']))
                {
                  $errors[] = sprintf ($lang->messages['smmultiplerrror'], $AUsername);
                }

                if (!$is_mod)
                {
                  $check_msg_perm = check_msg_perm ($receiver);
                  if (!empty ($check_msg_perm))
                  {
                    $errors[] = $check_msg_perm;
                  }
                }

                if (((!pm_limit (false, true, $receiver['id'], $receiver['usergroup']) AND !is_mod ($receiver)) AND !$is_mod))
                {
                  $errors[] = $lang->messages['error10'];
                }

                if (count ($errors) == 0)
                {
                  send_pm ($receiver['id'], $msg, $subject, $CURUSER['id'], $save);
                  $msgid = mysql_insert_id ();
                  $date = get_date_time ();
                  if (strpos ($receiver['notifs'], '[pm]') === false)
                  {
                  }
                  else
                  {
                    $username = trim ($CURUSER['username']);
                    $msg_receiver = trim ($receiver['username']);
                    $body = sprintf ($lang->messages['body'], $msg_receiver, $SITENAME, $username, $BASEURL);
                    @sent_mail ($receiver['email'], @sprintf ($lang->messages['msgsubject'], $SITENAME), $body, 'sendmessage', false);
                  }

                  $PostedUsers[] = $AUsername;
                  continue;
                }

                continue;
              }
            }
          }
        }
      }

      if ((!empty ($origmsg) AND count ($errors) == 0))
      {
        if ($_POST['delete'] == 'yes')
        {
          $res = sql_query ('SELECT receiver,saved FROM messages WHERE id=' . sqlesc ($origmsg));
          if (0 < mysql_num_rows ($res))
          {
            $arr = mysql_fetch_assoc ($res);
            if ($arr['receiver'] != $CURUSER['id'])
            {
              redirect ('messages.php');
              exit ();
            }
            else
            {
              if ($arr['saved'] == 'no')
              {
                sql_query ('DELETE FROM messages WHERE id=' . sqlesc ($origmsg) . ' LIMIT 1');
                if (mysql_affected_rows ())
                {
                  unset ($_POST[returnto]);
                }
              }
              else
              {
                if ($arr['saved'] == 'yes')
                {
                  sql_query ('UPDATE messages SET location = \'0\' WHERE id=' . sqlesc ($origmsg));
                  if (mysql_affected_rows ())
                  {
                    unset ($_POST[returnto]);
                  }
                }
              }
            }
          }
        }
      }

      if (count ($errors) == 0)
      {
        $returnto = (!empty ($_POST['returnto']) ? fix_url ($_POST['returnto']) : 'messages.php');
        $returnto = str_replace (array ($BASEURL, '//'), array ('', '/'), $returnto);
        redirect ($returnto, $lang->global['msgsend'], '', 3, false, false);
        exit ();
      }
    }
  }

  $compose = true;
  if ((!empty ($_GET['receiver']) AND is_valid_id ($_GET['receiver'])))
  {
    $compose = false;
    $receiver = 0 + $_GET['receiver'];
    if (($CURUSER['id'] == $receiver AND !$is_mod))
    {
      $errors[] = $lang->messages['msgsenderror'];
    }
    else
    {
      $res = sql_query ('SELECT u.options, u.id, u.usergroup, u.username, g.canstaffpanel, g.issupermod, g.cansettingspanel, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' AND u.id=' . sqlesc ($receiver));
      $receiver = mysql_fetch_assoc ($res);
      if ((!$receiver OR !$receiver['username']))
      {
        unset ($receiver);
        $errors[] = $lang->messages['nouser'];
      }
      else
      {
        if ((!$is_mod AND $_GET['type'] != 'forward'))
        {
          $check_msg_perm = check_msg_perm ($receiver);
          if (!empty ($check_msg_perm))
          {
            $errors[] = $check_msg_perm;
          }
        }

        if (((!pm_limit (false, true, $receiver['id'], $receiver['usergroup']) AND !is_mod ($receiver)) AND !$is_mod))
        {
          $errors[] = $lang->messages['error10'];
        }

        if (((!empty ($_GET['replyto']) AND is_valid_id ($_GET['replyto'])) AND count ($errors) == 0))
        {
          $replyto = 0 + $_GET['replyto'];
          $res = sql_query ('SELECT receiver,sender,msg,subject FROM messages WHERE id=' . sqlesc ($replyto));
          $msga = mysql_fetch_assoc ($res);
          if ((!$msga OR ($msga['receiver'] != $CURUSER['id'] AND !$is_mod)))
          {
            $errors[] = $lang->messages['newtitle11'];
          }
          else
          {
            $res = sql_query ('SELECT username FROM users WHERE id=' . sqlesc ($msga['sender']));
            $usra = mysql_fetch_assoc ($res);
            if ((!$usra OR !$usra['username']))
            {
              $errors[] = $lang->messages['newtitle11'];
            }
            else
            {
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

              $body = '[quote=' . htmlspecialchars_uni ($usra['username']) . ']' . $msga['msg'] . '[/quote]' . $eol . $eol;
              $subject = preg_replace ('#(FW|RE):( *)#is', '', $msga['subject']);
              $subject = ($_GET['type'] == 'forward' ? 'FW: ' : 'Re: ') . $subject;
            }
          }
        }
      }
    }
  }

  if (((($_GET['receiver'] == 0 AND $_GET['type'] == 'forward') AND !empty ($_GET['replyto'])) AND is_valid_id ($_GET['replyto'])))
  {
    $replyto = 0 + $_GET['replyto'];
    $res = sql_query ('SELECT receiver,msg,subject FROM messages WHERE id=' . sqlesc ($replyto));
    $msga = mysql_fetch_assoc ($res);
    if ((!$msga OR ($msga['receiver'] != $CURUSER['id'] AND !$is_mod)))
    {
      $errors[] = $lang->messages['newtitle11'];
    }

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

    $body = '[quote=System]' . $msga['msg'] . '[/quote]' . $eol . $eol;
    $subject = preg_replace ('#(FW|RE):( *)#is', '', $msga['subject']);
    $subject = 'FW: ' . $subject;
  }

  require_once INC_PATH . '/class_page_check.php';
  $newpage = new page_verify ();
  $newpage->create ('sendmessage');
  stdhead ($lang->messages['head']);
  show_sm_errors ();
  $returnto = (isset ($_GET['returnto']) ? fix_url ($_GET['returnto']) : (isset ($_POST['returnto']) ? fix_url ($_POST['returnto']) : fix_url ($_SERVER['HTTP_REFERER'])));
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . (!$compose ? '?receiver=' . $receiver['id'] . '&replyto=' . $replyto . (isset ($_GET['type']) ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '') : '') . '">
<input type="hidden" name="returnto" value="' . $returnto . '" />
' . (!$compose ? '<input type="hidden" name="origmsg" value="' . (int)$replyto . '">' : '<input type="hidden" name="compose" value="1" />');
  $postoptionstitle = array ('1' => $lang->messages['savemsg'], '2' => (isset ($replyto) ? $lang->messages['delmsg'] : ''));
  $postoptions = array ('1' => '<input type="checkbox" name="save" value="yes" />', '2' => (isset ($replyto) ? '<input type="checkbox" name="delete" value="yes" />' : ''));
  $extrasubject = array ($lang->global['sendtousername'] => '<input tabindex="2" name="receivername" size="52" type="text" value="' . (isset ($_POST['receivername']) ? htmlspecialchars_uni ($_POST['receivername']) : ($_GET['type'] != 'forward' ? $receiver['username'] : '')) . '" />
	<input type="button" onclick="window.open(\'' . $BASEURL . '/finduser.php?formname=compose&value=receivername\',\'finduser\',\'toolbar=no, scrollbars=no, resizable=no, width=360, height=140, top=50, left=50\'); return false;" value="' . $lang->global['finduser'] . '" class="button" /> <br /> ' . $lang->messages['smmultiple'] . '
	');
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  $str .= insert_editor (true, (isset ($_POST['subject']) ? $_POST['subject'] : (isset ($subject) ? $subject : '')), (isset ($_POST['message']) ? $_POST['message'] : (isset ($body) ? $body : '')), $lang->messages['head'], ($compose ? $lang->messages['title'] : $lang->messages['title2'] . ' <a href="' . ts_seo ($receiver['id'], $receiver['username']) . '">' . get_user_color ($receiver['username'], $receiver['namestyle']) . '</a>'), $postoptionstitle, $postoptions, true, $extrasubject, $lang->global['buttonsend']);
  $str .= '</form>';
  echo $str;
  stdfoot ();
?>
