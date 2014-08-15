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

  define ('SB_VERSION', '0.7');
  define ('NcodeImageResizer', true);
  $action = htmlspecialchars ($_GET['action']);
  $url = $_this_script_ . '&amp;';
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

  if (($_SERVER['REQUEST_METHOD'] == 'POST' AND (!empty ($_POST['setanswered']) OR !empty ($_POST['delete']))))
  {
    if (0 < count ($_POST['setanswered']))
    {
      foreach ($_POST['setanswered'] as $set)
      {
        sql_query ('UPDATE staffmessages SET answered = 1, answeredby = ' . $CURUSER['id'] . ' WHERE id = ' . sqlesc ($set));
      }

      unset ($action);
    }

    if (0 < count ($_POST['delete']))
    {
      foreach ($_POST['delete'] as $del)
      {
        sql_query ('DELETE FROM staffmessages WHERE id = ' . sqlesc ($del));
      }

      unset ($action);
    }
  }

  if (!$action)
  {
    stdhead ('Staff PM\'s');
    ($res = sql_query ('SELECT count(id) FROM staffmessages') OR sqlerr (__FILE__, 54));
    $row = mysql_fetch_array ($res);
    $count = $row[0];
    $perpage = $ts_perpage;
    list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $url);
    _form_header_open_ ('Staff PM\'s');
    if ($count == 0)
    {
      print ' No messages yet!';
    }
    else
    {
      begin_main_frame ();
      print '<table width=100% border=1 cellspacing=0 cellpadding=5 align=center>
';
      print '
		<tr>
		<td class=subheader align=left>Subject</td>
		<td class=subheader align=left>Sender</td>
		<td class=subheader align=left>Added</td>
		<td class=subheader align=left>Answered</td>
		<td class=subheader align=center>Set Answered</td>
		<td class=subheader align=center>Del</td>
		</tr>
		';
      print '<form method=\'post\' action=\'' . $url . '\'>';
      $res = sql_query ('' . 'SELECT 
		s.*, u.username, g.namestyle,
		uu.username as username2, gg.namestyle as namestyle2
		FROM staffmessages s 
		LEFT JOIN users u ON (s.sender=u.id) 
		LEFT JOIN usergroups g ON (u.usergroup=g.gid)
		LEFT JOIN users uu ON (uu.id=s.answeredby)
		LEFT JOIN usergroups gg ON (gg.gid=uu.usergroup)
		ORDER BY s.id desc ' . $limit);
      while ($arr = mysql_fetch_array ($res))
      {
        if ($arr['answered'])
        {
          $answered = '' . '<font color=green><b>Yes - <a href=' . $BASEURL . '/userdetails.php?id=' . $arr['answeredby'] . '><b>' . get_user_color ($arr['username2'], $arr['namestyle2']) . '</b></a> (<a href=' . $url . ('' . 'action=viewanswer&pmid=' . $arr['id'] . '>View Answer</a>)</b></font>');
        }
        else
        {
          $answered = '<font color=red><b>No</b></font>';
        }

        $pmid = $arr['id'];
        print '<tr>
			<td><a href=' . $url . ('' . 'action=viewpm&pmid=' . $pmid . '><b>') . htmlspecialchars_uni ($arr['subject']) . ('' . '</b></td>
			<td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['sender'] . '><b>') . get_user_color ($arr['username'], $arr['namestyle']) . ('' . '</b></a></td>
			<td>' . $arr['added'] . '</td><td align=left>' . $answered . '</td>
			<td align=center><input type="checkbox" name="setanswered[]" value="') . $arr['id'] . '" /></td>
			<td><input type=\'checkbox\' name=\'delete[]\' value=\'' . $arr['id'] . '\'></td>
			</tr>

			';
      }

      print '</table>
';
      print '<p align=right><input type=submit value=Confirm class=button></p>';
      print '</form>';
      echo $pagerbottom;
      end_main_frame ();
    }

    _form_header_close_ ();
    stdfoot ();
  }

  if ($action == 'viewpm')
  {
    $pmid = 0 + $_GET['pmid'];
    int_check ($pmid, true);
    ($ress4 = sql_query ('SELECT s.*, u.username, g.namestyle FROM staffmessages s LEFT JOIN users u ON (s.answeredby=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.id=' . sqlesc ($pmid)) OR sqlerr (__FILE__, 129));
    $arr4 = mysql_fetch_array ($ress4);
    $answeredby = $arr4['answeredby'];
    $senderr = '' . $arr4['sender'] . '';
    if (is_valid_id ($arr4['sender']))
    {
      ($res2 = sql_query ('SELECT u.username,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . $arr4['sender']) OR sqlerr (__FILE__, 139));
      $arr2 = mysql_fetch_array ($res2);
      $sender = '' . '<a href=\'' . $BASEURL . '/userdetails.php?id=' . $senderr . '\'>' . ($arr2['username'] ? get_user_color ($arr2['username'], $arr2['namestyle']) : '[Deleted]') . '</a>';
    }
    else
    {
      $sender = 'System';
    }

    $subject = htmlspecialchars_uni ($arr4['subject']);
    if ($arr4['answered'] == '0')
    {
      $answered = '<font color=red><b>No</b></font>';
    }
    else
    {
      $answered = '' . '<font color=blue><b>Yes</b></font> by <a href=' . $BASEURL . '/userdetails.php?id=' . $answeredby . '>' . get_user_color ($arr4['username'], $arr4['namestyle']) . '</a> (<a href=' . $url . ('' . 'action=viewanswer&pmid=' . $pmid . '>Show Answer</a>)');
    }

    if ($arr4['answered'] == '0')
    {
      $setanswered = '[<a href=' . $url . ('' . 'action=setanswered&id=' . $arr4['id'] . '>Mark Answered</a>]');
    }
    else
    {
      $setanswered = '';
    }

    $iidee = $arr4['id'];
    stdhead ('Staff PM\'s');
    print '<table class=bottom width=100% border=0 cellspacing=0 cellpadding=10><tr><td class=embedded width=700>
';
    print '<h1 align=center>Messages to staff</h1>
';
    require_once INC_PATH . '/functions_mkprettytime.php';
    $elapsed = mkprettytime (time () - strtotime ($arr4['added']));
    print '<table width=100% border=1 cellspacing=0 cellpadding=10 style=\'margin-bottom: 10px\'><tr><td class=text>
';
    print '' . 'From <b>' . $sender . '</b> at
' . $arr4['added'] . ('' . ' (' . $elapsed . ') GMT
');
    print '' . '<br /><br style=\'margin-bottom: -10px\'><div align=left><b>Subject: <font color=darkred>' . $subject . '</b></font>
	&nbsp;&nbsp;<br /><b>Answered:</b> ' . $answered . '&nbsp;&nbsp;' . $setanswered . '</div>
	<br /><table class=main width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=staffpms>
';
    print format_comment ($arr4['msg']);
    print '</td></tr></table>
';
    print '<table width=100% border=0><tr><td class=embedded>
';
    print ($arr4['sender'] ? '<a href=' . $url . 'action=answermessage&receiver=' . $arr4['sender'] . ('' . '&answeringto=' . $iidee . '><b>Reply</b></a>') : '<font class=gray><b>Reply</b></font>') . ' | <a href=' . $url . 'action=deletestaffmessage&id=' . $arr4['id'] . '><b>Delete</b></a></td>';
    print '</table></table>
';
    print '</table>
';
    stdfoot ();
  }

  if ($action == 'viewanswer')
  {
    $pmid = 0 + $_GET['pmid'];
    int_check ($pmid, true);
    ($ress4 = sql_query ('SELECT s.*, u.username, g.namestyle FROM staffmessages s LEFT JOIN users u ON (s.answeredby=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.id=' . sqlesc ($pmid)) OR sqlerr (__FILE__, 191));
    $arr4 = mysql_fetch_array ($ress4);
    $answeredby = $arr4['answeredby'];
    if (is_valid_id ($arr4['sender']))
    {
      ($res2 = sql_query ('SELECT u.username,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . $arr4['sender']) OR sqlerr (__FILE__, 199));
      $arr2 = mysql_fetch_array ($res2);
      $sender = '' . '<a href=' . $BASEURL . '/userdetails.php?id=' . $arr4['sender'] . '>' . ($arr2['username'] ? get_user_color ($arr2['username'], $arr2['namestyle']) : '[Deleted]') . '</a>';
    }
    else
    {
      $sender = 'System';
    }

    if ($arr4['subject'] == '')
    {
      $subject = 'No subject';
    }
    else
    {
      $subject = '<a style=\'color: darkred\' href=' . $url . ('' . 'action=viewpm&pmid=' . $pmid . '>') . htmlspecialchars_uni ($arr4['subject']) . '</a>';
    }

    $iidee = $arr4['id'];
    if ($arr4[answer] == '')
    {
      $answer = '[b][i]This message has not been answered yet or message is empty or marked as answered![/b][/i]';
    }
    else
    {
      $answer = $arr4['answer'];
    }

    stdhead ('Staff PM\'s');
    print '<table class=bottom width=100% border=0 cellspacing=0 cellpadding=10><tr><td class=embedded width=700>
';
    print '<h1 align=center>Viewing Answer</h1>
';
    require_once INC_PATH . '/functions_mkprettytime.php';
    $elapsed = mkprettytime (time () - strtotime ($arr4['added']));
    print '<table width=100% border=1 cellspacing=0 cellpadding=10 style=\'margin-bottom: 10px\'><tr><td class=text>
';
    print '' . '<b><a href=' . $BASEURL . '/userdetails.php?id=' . $answeredby . '>' . get_user_color ($arr4['username'], $arr4['namestyle']) . ('' . '</a></b> answered this message sent by ' . $sender);
    print '' . '<br /><br style=\'margin-bottom: -10px\'><div align=left><b>Subject: ' . $subject . '</b>
	&nbsp;&nbsp;<br /><b>Answer:</b></div>
	<br /><table class=main width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=staffpms>
';
    print format_comment ($answer);
    print '</td></tr></table>
';
    print '</table>
';
    print '</table>
';
    stdfoot ();
  }

  if ($action == 'answermessage')
  {
    $returnto = (isset ($_GET['returnto']) ? fix_url ($_GET['returnto']) : (isset ($_POST['returnto']) ? fix_url ($_POST['returnto']) : fix_url ($_SERVER['HTTP_REFERER'])));
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
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

      if (isset ($_POST['submit']))
      {
        $receiver = 0 + $_POST['receiver'];
        $answeringto = (int)$_POST['answeringto'];
        int_check ($receiver, true);
        $userid = (int)$CURUSER['id'];
        $msg = trim ($_POST['message']);
        $message = sqlesc ($msg);
        if (!$msg)
        {
          stderr ('Error', 'Please enter something!');
        }

        require_once INC_PATH . '/functions_pm.php';
        send_pm ($receiver, $msg, trim ($_POST['subject']), $userid);
        (sql_query ('' . 'UPDATE staffmessages SET answer=' . $message . ' WHERE id=' . sqlesc ($answeringto)) OR sqlerr (__FILE__, 275));
        (sql_query ('UPDATE staffmessages SET answered=\'1\', answeredby=' . sqlesc ($userid) . ' WHERE id=' . sqlesc ($answeringto)) OR sqlerr (__FILE__, 277));
        header ('Location: ' . $url . ('' . 'action=viewpm&pmid=' . $answeringto));
        exit ();
      }
    }

    $answeringto = 0 + $_GET['answeringto'];
    $receiver = 0 + $_GET['receiver'];
    int_check (array ($receiver, $answeringto), true);
    ($res = sql_query ('SELECT u.username,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($receiver)) OR sqlerr (__FILE__, 288));
    $user = mysql_fetch_array ($res);
    if (!$user)
    {
      stderr ('Error', 'No user with that ID.');
    }

    ($res2 = sql_query ('SELECT * FROM staffmessages WHERE id=' . sqlesc ($answeringto)) OR sqlerr (__FILE__, 294));
    $array = mysql_fetch_array ($res2);
    stdhead ('Answer to Staff PM', false);
    define ('IN_EDITOR', true);
    include_once INC_PATH . '/editor.php';
    $str = '<form method="post" name="compose" action="' . $url . '&action=answermessage&answeringto=' . $answeringto . '&receiver=' . $receiver . '">
	<input type="hidden" name="returnto" value="' . $returnto . '">
	<input type="hidden" name="receiver" value="' . $receiver . '">
     <input type="hidden" name="answeringto" value="' . $answeringto . '">';
    if (!empty ($prvp))
    {
      $str .= $prvp;
    }

    $body = '[quote=' . $user['username'] . ']' . htmlspecialchars_uni ($array['msg']) . '[/quote]' . $eol;
    $str .= insert_editor (true, ($_POST['subject'] ? $_POST['subject'] : $array['subject']), (!empty ($_POST['message']) ? $_POST['message'] : $body), 'Answer Staff Message', 'Answering to <a href=' . $url . 'action=viewpm&pmid=' . $array['id'] . '><i>' . htmlspecialchars_uni ($array['subject']) . '</i></a> sent by <i>' . get_user_color ($user['username'], $user['namestyle']) . '</i>');
    $str .= '</form>';
    echo $str;
    stdfoot ();
    exit ();
  }

  if ($action == 'deletestaffmessage')
  {
    $id = 0 + $_GET['id'];
    int_check ($id, true);
    sql_query ('DELETE FROM staffmessages WHERE id=' . sqlesc ($id));
    header ('Location: ' . $url);
  }

  if ($action == 'setanswered')
  {
    $id = 0 + $_GET['id'];
    int_check ($id, true);
    (sql_query ('' . 'UPDATE staffmessages SET answered=1, answeredby = ' . $CURUSER['id'] . ' WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 336));
    header ('Refresh: 0; url=' . $url . ('' . 'action=viewpm&pmid=' . $id));
  }

  if ($action == 'takecontactanswered')
  {
    foreach ($_POST['setanswered'] as $id)
    {
      if (is_valid_id ($id))
      {
        sql_query ('UPDATE staffmessages SET answered = 1, answeredby = ' . $CURUSER['id'] . ' WHERE id = ' . sqlesc ($id));
        continue;
      }
    }

    header ('Refresh: 0; url=' . $url);
  }

?>
