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

  if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
  {
    $username = trim ($_POST['username']);
    if (!$username)
    {
      stderr ('Error', 'Dont leave any fields blank!');
    }

    ($res = sql_query ('SELECT id,username,email FROM users WHERE username=' . sqlesc ($username)) OR sqlerr (__FILE__, 25));
    if (mysql_num_rows ($res) == '0')
    {
      stderr ('Error', 'No user found!');
    }
    else
    {
      $arr = mysql_fetch_array ($res);
    }

    $id = (int)$arr['id'];
    $wantpassword = 'ts_auto_password_reset';
    $secret = mksecret ();
    $wantpasshash = md5 ($secret . $wantpassword . $secret);
    sql_query ('UPDATE users SET passhash=' . sqlesc ($wantpasshash) . ', secret= ' . sqlesc ($secret) . ' where id=' . sqlesc ($id));
    if (mysql_affected_rows () != 1)
    {
      stderr ('Error', 'Unable to RESET PASSWORD on this account.');
      return 1;
    }

    write_log ('' . 'Password Reset for ' . $username . ' by ' . $CURUSER['username']);
    if ($_POST['inform'] == 'yes')
    {
      $subject = 'Password Reset on ' . $SITENAME;
      $body = 'Hello ' . $arr['username'] . ',

			Your password has been reset by ' . $CURUSER['username'] . '.
			
			Please login and change your password by using following link:
			' . $BASEURL . '/usercp.php?act=edit_password

			Your new password is: ' . $wantpassword . '

			Thank you.
			' . $SITENAME . ' Team.';
      sent_mail ($arr['email'], $subject, $body, 'reset', false);
    }

    stdhead ('Reset User\'s Lost Password');
    stdmsg ('Success', 'The account \'<b>' . htmlspecialchars ($username) . '</b>\' password reset to \'<b>' . $wantpassword . '</b>\'.  ' . ($_POST['inform'] == 'yes' ? 'Information mail has been sent.' : 'Please inform user of this change.'), false, 'success');
    stdfoot ();
    return 1;
  }

  stdhead ('Reset User\'s Lost Password');
  _form_header_open_ ('Reset User\'s Lost Password');
  echo '
	<table border=1 cellspacing=0 cellpadding=5 width=100%>
	<form method=post action="' . $_this_script_ . '">
	<tr><td class=rowhead>User name</td><td><input size=40 name=username id=specialboxn> <input type=checkbox name=inform value=yes> Inform user via email <input type=submit class=button value="Reset Password"></td></tr>
	</form>
	</table>
	';
  _form_header_close_ ();
  stdfoot ();
?>
