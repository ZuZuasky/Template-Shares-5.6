<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  include_once INC_PATH . '/functions_security.php';
  include_once INC_PATH . '/functions_login.php';
  gzip ();
  dbconn ();
  failedloginscheck ();
  cur_user_check ();
  define ('TL_VERSION', '0.6 ');
  require_once INC_PATH . '/class_page_check.php';
  $newpage = new page_verify ();
  $newpage->check ('login');
  require INC_PATH . '/functions_getvar.php';
  getvar (array ('username', 'password'));
  $lang->load ('login');
  if ((empty ($username) OR empty ($password)))
  {
    header ('Location: ' . $BASEURL . '/login.php?error=3&username=' . htmlspecialchars_uni ($username));
    exit ();
  }

  if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
  {
    check_code ((isset ($_POST['imagestring']) ? $_POST['imagestring'] : ''), 'login.php', true, '&username=' . htmlspecialchars_uni ($username));
  }

  $res = sql_query ('SELECT id, passhash, secret, enabled, usergroup, status, notifs FROM users WHERE username = ' . sqlesc ($username) . ' LIMIT 1');
  $row = mysql_fetch_assoc ($res);
  if ((empty ($row) OR !$row))
  {
    failedlogins ('silent');
    header ('Location: ' . $BASEURL . '/login.php?error=1&username=' . htmlspecialchars_uni ($username));
    exit ();
  }

  $ipaddress = getip ();
  if ($row['passhash'] != md5 ($row['secret'] . $password . $row['secret']))
  {
    $md5pw = md5 ($password);
    $iphost = @gethostbyaddr ($ipaddress);
    failedlogins ('login', false, true, true, (int)$row['id']);
    header ('Location: ' . $BASEURL . '/login.php?error=4&username=' . htmlspecialchars_uni ($username));
    exit ();
  }

  if ($row['enabled'] == 'no')
  {
    stderr ($lang->login['banned'], $row['notifs']);
  }
  else
  {
    if ($row['status'] == 'pending')
    {
      stderr ($lang->global['error'], $lang->login['pending']);
    }
  }

  $passh = $row['passhash'];
  logoutcookie ();
  if ((isset ($_POST['logout']) AND $_POST['logout'] == 'yes'))
  {
    logincookie ($row['id'], $passh, 15);
    if ((isset ($_POST['logintype']) AND $_POST['logintype'] == 'yes'))
    {
      sessioncookie ($row['id'], $passh, true, true);
    }
    else
    {
      sessioncookie ($row['id'], $passh, true);
    }
  }
  else
  {
    logincookie ($row['id'], $passh);
    if ((isset ($_POST['logintype']) AND $_POST['logintype'] == 'yes'))
    {
      sessioncookie ($row['id'], $passh, true, true);
    }
    else
    {
      sessioncookie ($row['id'], $passh);
    }
  }

  sql_query ('DELETE FROM loginattempts WHERE banned = \'no\' AND ip = ' . sqlesc ($ipaddress));
  $cut = TIMENOW - TS_TIMEOUT;
  sql_query ('DELETE FROM ts_sessions WHERE sessionhash = ' . sqlesc (md5 ($ipaddress . htmlspecialchars_uni (strtolower ($_SERVER['HTTP_USER_AGENT'])))) . ' OR lastactivity < ' . sqlesc ($cut));
  if (!empty ($_POST['returnto']))
  {
    $returnto = $_POST['returnto'];
  }
  else
  {
    $returnto = 'index.php';
  }

  redirect ($returnto, $lang->login['logged']);
?>
