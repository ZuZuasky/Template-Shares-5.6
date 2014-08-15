<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function hash_pad ($hash)
  {
    return str_pad ($hash, 20);
  }

  require_once 'global.php';
  include_once INC_PATH . '/functions_security.php';
  gzip ();
  dbconn ();
  failedloginscheck ('Recover');
  $lang->load ('recover');
  define ('R_VERSION', '1.3.4');
  if ($CURUSER)
  {
    stderr ($lang->global['error'], $lang->recover['error']);
  }

  $act = (isset ($_GET['act']) ? $_GET['act'] : (isset ($_POST['act']) ? $_POST['act'] : ''));
  if ($act == 'manual')
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $_GET['id'] = $_POST['id'];
      $_GET['secret'] = $_POST['secret'];
    }
    else
    {
      $lang->load ('confirm');
      $form = '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?act=manual">
		<input type="hidden" name="act" value="manual" />
		<table border="0" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td colspan="2" class="thead">' . $lang->confirm['manual1'] . '</td>
			</tr>
			<tr>
				<td colspan="2" class="subheader">' . $lang->confirm['manual4'] . '</td>
			</tr>
			<tr>
				<td align="right">' . $lang->confirm['manual2'] . '</td>
				<td align="left"><input type="text" name="id" value="" size="32" /></td>
			</tr>
			<tr>
				<td align="right">' . $lang->confirm['manual3'] . '</td>
				<td align="left"><input type="text" name="secret" value="" size="32" /></td>
			</tr>
			<tr>
			<td colspan="2" align="center"><input type="submit" value="' . $lang->confirm['manual5'] . '" /></td>
			</tr>
		</table>
		</form>
		';
      stdhead ($lang->confirm['manual1'], false);
      echo $form;
      stdfoot ();
      exit ();
    }
  }

  if (($_SERVER['REQUEST_METHOD'] == 'POST' AND empty ($act)))
  {
    function safe_email ($email)
    {
      return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
    }

    function unesc ($x)
    {
      if (get_magic_quotes_gpc ())
      {
        return stripslashes ($x);
      }

      return $x;
    }

    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'recover.php', true);
    }

    $email = unesc (htmlspecialchars (trim ($_POST['email'])));
    $email = safe_email ($email);
    if (!$email)
    {
      failedlogins ($lang->global['dontleavefieldsblank'], true);
    }

    if (!check_email ($email))
    {
      failedlogins ($lang->recover['error2'], true);
    }

    ($res = sql_query ('SELECT id, passhash, email FROM users WHERE email=' . sqlesc ($email) . ' LIMIT 1') OR sqlerr (__FILE__, 100));
    ($arr = mysql_fetch_assoc ($res) OR failedlogins ($lang->recover['error3'], true));
    $sec = mksecret ();
    sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($arr['id']));
    (sql_query ('INSERT INTO ts_user_validation (editsecret, userid) VALUES (' . sqlesc ($sec) . ', ' . sqlesc ($arr['id']) . ')') OR sqlerr (__FILE__, 104));
    if (!mysql_affected_rows ())
    {
      stderr ($lang->global['error'], $lang->global['dberror']);
    }

    $hash = md5 ($sec . $email . $arr['passhash'] . $sec);
    $ip = getip ();
    $body = sprintf ($lang->recover['body'], $email, $ip, $BASEURL, $arr['id'], $hash, $SITENAME, 'recover');
    sent_mail ($arr['email'], sprintf ($lang->recover['subject'], $SITENAME), $body, 'recover');
    stdhead ($lang->recover['head']);
    stdmsg ($lang->recover['head'], $lang->recover['msent'], true, 'success');
    stdfoot ();
    exit ();
    return 1;
  }

  if (($_GET['id'] AND $_GET['secret']))
  {
    $id = (int)$_GET['id'];
    $md5 = $_GET['secret'];
    if (((empty ($id) OR !is_valid_id ($id)) OR strlen ($md5) != 32))
    {
      stderr ($lang->global['error'], $lang->recover['invalidcodeorid']);
    }

    $res = sql_query ('SELECT u.username, u.email, u.passhash, e.editsecret FROM users u LEFT JOIN ts_user_validation e ON (u.id=e.userid) WHERE u.id = ' . sqlesc ($id));
    ($arr = mysql_fetch_assoc ($res) OR stderr ($lang->global['error'], $lang->global['nouserid']));
    $email = $arr['email'];
    $sec = hash_pad ($arr['editsecret']);
    if (preg_match ('/^ *$/s', $sec))
    {
      stderr ($lang->global['error'], $lang->recover['invalidcodeorid']);
    }

    if ($md5 != md5 ($sec . $email . $arr['passhash'] . $sec))
    {
      stderr ($lang->global['error'], $lang->recover['invalidcode3']);
    }

    $newpassword = mksecret (10);
    $sec = mksecret ();
    $newpasshash = md5 ($sec . $newpassword . $sec);
    (sql_query ('UPDATE users SET secret=' . sqlesc ($sec) . ', passhash=' . sqlesc ($newpasshash) . ' WHERE id=' . sqlesc ($id)) OR stderr ($lang->global['error'], $lang->global['dberror']));
    if (!mysql_affected_rows ())
    {
      stderr ($lang->global['error'], $lang->global['dberror']);
    }

    sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($id));
    $body = sprintf ($lang->recover['body2'], $arr['username'], $newpassword, $BASEURL, $SITENAME);
    sent_mail ($email, sprintf ($lang->recover['subject2'], $SITENAME), $body, 'details');
    return 1;
  }

  define ('SKIP_RELOAD_CODE', true);
  stdhead ($lang->recover['head'], false);
  $error = '';
  if (!empty ($_GET['error']))
  {
    if ($_GET['error'] == 1)
    {
      $error = '<tr><td colspan="2"><div class="error">' . sprintf ($lang->recover['errortype1'], remaining ()) . '</div></td></tr>';
    }
    else
    {
      if ($_GET['error'] == 2)
      {
        $error = '<tr><td colspan="2"><div class="error">' . sprintf ($lang->global['invalidimagecode'], remaining ()) . '</div></td></tr>';
      }
    }
  }

  echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="recover" onsubmit="document.forms[\'recover\'].elements[\'send\'].disabled=true; document.forms[\'recover\'].elements[\'send\'].value=\'' . $lang->global['pleasewait'] . '\';">
	<table width="100%" border="1" cellspacing="0" cellpadding="5">
		<tr>
			<td align="center" class="thead">' . $lang->recover['head'] . '</td>
		</tr>
		<tr>
			<td>' . sprintf ($lang->recover['info'], $maxloginattempts) . '</td>
		</tr>
	</table>
	<br />
	<table width="100%" border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td colspan="2" align="center" class="thead">' . $lang->recover['head'] . '</td>
	</tr>';
  if (isset ($error))
  {
    echo $error;
  }

  echo '
	<tr>
		<td class="rowhead" style="vertical-align: middle;">' . $lang->recover['fieldemail'] . '</td>
		<td><input type="text" size="26" name="email" id="email" class="inputUsername" />
		' . ($iv == 'no' ? '
		<input type="submit" value="' . $lang->global['buttonrecover'] . '" class="button" name="send" />' : '') . '</td>
	</tr>';
  show_image_code (true, $lang->global['buttonrecover'], 'name="send" ');
  echo '
	</table>
	</form>
	<script type="text/javascript">
		//<![CDATA[
		reload();
		function reload ()
		{
			TSGetID(\'regimage\').src = "' . $BASEURL . '/include/class_tscaptcha.php?" + (new Date()).getTime();
			return;
		};
		//]]>
	</script>
	';
  stdfoot ();
?>
