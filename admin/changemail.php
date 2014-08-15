<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function email_exists ($email)
  {
    $tracker_query = sql_query ('SELECT email FROM users WHERE email=' . sqlesc ($email) . ' LIMIT 1');
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

  function safe_email ($email)
  {
    return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('CE_VERSION', '0.4 by xam');
  if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
  {
    if (($_POST['username'] == '' OR $_POST['email'] == ''))
    {
      stderr ('Error', 'Missing form data.');
    }

    $username = $_POST['username'];
    if (!validusername ($username))
    {
      stderr ('Error', 'Invalid Username.');
    }

    $username = sqlesc ($username);
    $email = htmlspecialchars (trim ($_POST['email']));
    $email = safe_email ($email);
    if ((!check_email ($email) OR !email_exists ($email)))
    {
      stderr ('Error', 'Invalid email address or Email already taken.');
    }

    require_once INC_PATH . '/functions_EmailBanned.php';
    if (emailbanned ($email))
    {
      stderr ('Error', 'This email address has been banned!');
    }

    $email = sqlesc ($email);
    (sql_query ('' . 'UPDATE users SET email=' . $email . ' WHERE username=' . $username) OR sqlerr (__FILE__, 63));
    $res = sql_query ('' . 'SELECT id FROM users WHERE username=' . $username);
    $arr = mysql_fetch_array ($res);
    if (empty ($arr))
    {
      stderr ('Error', 'Unable to update account.');
    }
    else
    {
      write_log ($username . ('' . 's email has been changed to ' . $email . ' by ' . $CURUSER['username'] . ' (Change Email Tool)'));
    }

    header ('' . 'Location: ' . $BASEURL . '/userdetails.php?id=' . $arr['0']);
    exit ();
  }

  stdhead ('Change Users E-mail Address');
  _form_header_open_ ('Change Users E-mail Address');
  echo '
<form method=post action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type=hidden name=act value=changemail>
<table border=1 cellspacing=0 cellpadding=5 width=100%>
<tr><td class=rowhead>User name</td><td><input type=text name=username size=40 id=specialboxn></td></tr>
<tr><td class=rowhead>New E-mail</td><td><input type=email name=email size=40 id=specialboxn> <input type=submit value="Change Email" class=button></td></tr>
</table>
</form>';
  _form_header_close_ ();
  stdfoot ();
?>
