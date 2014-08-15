<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function staffnamecheck ($username)
  {
    global $rootpath;
    global $lang;
    $username = strtolower ($username);
    $query = sql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($username));
    if (0 < mysql_num_rows ($query))
    {
      $res = mysql_fetch_assoc ($query);
      $userid = intval ($res['id']);
    }
    else
    {
      stderr ($lang->global['error'], $lang->global['nousername']);
    }

    $filename = CONFIG_DIR . '/STAFFTEAM';
    $results = @file_get_contents ($filename);
    $results = @explode (',', $results);
    if (in_array ($username . ':' . $userid, $results))
    {
      stderr ($lang->global['error'], $lang->recover['denyaccessforstaff'], false);
      exit ();
    }

  }

  function validusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  require_once 'global.php';
  include_once INC_PATH . '/functions_security.php';
  gzip ();
  dbconn ();
  failedloginscheck ('Recover');
  $lang->load ('recover');
  define ('RH_VERSION', '1.2.1 ');
  $act = (int)$_GET['act'];
  if ($act == '0')
  {
    define ('SKIP_RELOAD_CODE', true);
    stdhead ($lang->recover['head'], false, 'collapse');
    if (!empty ($_GET['error']))
    {
      if ($_GET['error'] == 1)
      {
        $error = '<tr><td colspan="2"><div class="error">' . sprintf ($lang->recover['errortype3'], remaining ()) . '</div></td></tr>';
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
		<form method="post" action="recoverhint.php?act=1" name="recover" onsubmit="document.forms[\'recover\'].elements[\'send\'].disabled=true; document.forms[\'recover\'].elements[\'send\'].value=\'' . $lang->global['pleasewait'] . '\';">
		<table width="100%" border="1" cellspacing="0" cellpadding="5">
			<tr>
				<td align="center" class="thead">' . $lang->recover['head'] . '</td>
			</tr>
			<tr>
				<td>' . sprintf ($lang->recover['info2'], $maxloginattempts) . '</td>
			</tr>
		</table>
		<br />
		<table border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td colspan="2" align="center" class="thead">' . $lang->recover['head'] . '</td>
			</tr>';
    if (isset ($error))
    {
      echo $error;
    }

    echo '
			<tr>
				<td class="rowhead" style="vertical-align: middle;">' . $lang->recover['fieldusername'] . '</td>
				<td><input class="inputUsername" type="text" size="30" name="username" /> ' . ($iv == 'no' ? ' <input type="submit" value="' . $lang->global['buttonrecover'] . '" name="send" class="button" />' : '') . '</td>
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
		</script>';
    stdfoot ();
    exit ();
  }

  if ($act == '1')
  {
    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'recoverhint.php', true);
    }

    $username = htmlspecialchars_uni ($_POST['username']);
    if ((empty ($username) OR !validusername ($username)))
    {
      failedlogins ('silent', false, false);
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      exit ();
    }

    staffnamecheck ($username);
    ($res = sql_query ('SELECT id, username FROM users WHERE username=' . sqlesc ($username) . ' AND status = \'confirmed\' AND enabled = \'yes\' LIMIT 1') OR sqlerr (__FILE__, 127));
    if (1 <= mysql_num_rows ($res))
    {
      $arr = mysql_fetch_assoc ($res);
      $securehash = securehash ($arr['id'] . $arr['username']);
      setcookie ('securehash_recoverhint', $securehash, TIMENOW + 3600);
      redirect ('recoverhint.php?act=3&id=' . $arr['id'] . '&username=' . $username, $lang->global['redirect']);
    }
    else
    {
      stdhead ($lang->recover['head']);
      stdmsg ($lang->global['error'], $lang->global['nousername']);
      failedlogins ('silent', false, false);
      stdfoot ();
    }

    exit ();
  }

  if ($act == '3')
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if ($_SESSION['password_generated'] != 0)
      {
        print_no_permission ();
      }

      $id = (int)$_GET['id'];
      int_check ($id, true);
      $answer = htmlspecialchars_uni ($_POST['answer']);
      if (!$answer)
      {
        failedlogins ('silent', false, false);
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }

      $res = sql_query ('SELECT id, username, status, enabled FROM users WHERE id = ' . sqlesc ($id));
      ($user = mysql_fetch_assoc ($res) OR stderr ($lang->global['error'], $lang->global['nouserid']));
      if ((empty ($user['username']) OR !validusername ($user['username'])))
      {
        failedlogins ('silent', false, false);
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        exit ();
      }

      staffnamecheck ($user['username']);
      $securehash = securehash ($user['id'] . $user['username']);
      if (($_COOKIE['securehash_recoverhint'] != $securehash OR (empty ($_COOKIE['securehash_recoverhint']) OR empty ($securehash))))
      {
        failedlogins ('silent', false, false);
        print_no_permission ();
        exit ();
      }

      $query = sql_query ('SELECT passhint, hintanswer FROM ts_secret_questions WHERE userid = ' . sqlesc ($user['id']));
      $Array = mysql_fetch_assoc ($query);
      if (($Array AND is_array ($Array)))
      {
        $user = array_merge ($user, $Array);
      }
      else
      {
        $user = false;
      }

      if ((md5 ($answer) != $user['hintanswer'] OR empty ($user['hintanswer'])))
      {
        failedlogins ('silent', false, false);
        stderr ($lang->global['error'], $lang->recover['invalidanswer']);
        return 1;
      }

      if (((((!$user OR $user['status'] == 'pending') OR $user['enabled'] == 'no') OR empty ($user['passhint'])) OR empty ($user['hintanswer'])))
      {
        failedlogins ('silent', false, false);
        stderr ($lang->global['error'], $lang->global['nouserid']);
        exit ();
        return 1;
      }

      $newpassword = mksecret (10);
      $newpasshash = md5 ($sec . $newpassword . $sec);
      sql_query ('UPDATE users SET secret=' . sqlesc ($sec) . ', passhash=' . sqlesc ($newpasshash) . ' WHERE id=' . sqlesc ($id));
      if (!mysql_affected_rows ())
      {
        stderr ($lang->global['error'], $lang->global['dberror']);
      }

      sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($id));
      ++$_SESSION['password_generated'];
      stderr ($lang->recover['generated1'], sprintf ($lang->recover['generated2'], $newpassword, $BASEURL), false);
      return 1;
    }

    $id = (int)$_GET['id'];
    $username = htmlspecialchars_uni ($_GET['username']);
    staffnamecheck ($username);
    if ((((empty ($id) OR !is_valid_id ($id)) OR empty ($username)) OR !validusername ($username)))
    {
      failedlogins ('silent', false, false);
      print_no_permission ();
      exit ();
    }

    $res = sql_query ('SELECT id, username, status, enabled FROM users WHERE id = ' . sqlesc ($id) . ' AND username = ' . sqlesc ($username));
    ($user = mysql_fetch_assoc ($res) OR stderr ($lang->global['error'], $lang->global['nouserid']));
    $securehash = securehash ($user['id'] . $user['username']);
    if (($_COOKIE['securehash_recoverhint'] != $securehash OR (empty ($_COOKIE['securehash_recoverhint']) OR empty ($securehash))))
    {
      failedlogins ('silent', false, false);
      print_no_permission ();
      exit ();
    }

    $query = sql_query ('SELECT passhint, hintanswer FROM ts_secret_questions WHERE userid = ' . sqlesc ($user['id']));
    $Array = mysql_fetch_assoc ($query);
    if (($Array AND is_array ($Array)))
    {
      $user = array_merge ($user, $Array);
    }
    else
    {
      $user = false;
    }

    if (((((!$user OR $user['status'] == 'pending') OR $user['enabled'] == 'no') OR empty ($user['passhint'])) OR empty ($user['hintanswer'])))
    {
      failedlogins ('silent', false, false);
      stderr ($lang->global['error'], $lang->global['nouserid']);
      exit ();
    }

    stdhead ($lang->recover['head'], false, 'collapse');
    echo '
		<table width="100%" border="1" cellspacing="0" cellpadding="6" wpar="nowrap">
		<form method="POST" action="recoverhint.php?act=3&id=' . $id . '">
		<tr><td align="center" class="thead" colspan="2">' . $lang->recover['head'] . '</td></tr>
		<tr><td colspan="2">' . $lang->recover['info3'] . '</td></tr>
		<tr><td class="rowhead">' . $lang->recover['sq'] . '</td>';
    $HF[0] = '/1/';
    $HF[1] = '/2/';
    $HF[2] = '/3/';
    $HR[0] = '<font color=blue>' . $lang->recover['hr0'] . '</font>';
    $HR[1] = '<font color=blue>' . $lang->recover['hr1'] . '</font>';
    $HR[2] = '<font color=blue>' . $lang->recover['hr2'] . '</font>';
    $passhint = preg_replace ($HF, $HR, $user['passhint']);
    echo '<td>' . $passhint . '</td>';
    echo '<tr><td class="rowhead">' . $lang->recover['ha'] . '</td>';
    echo '<td><input type="text" size="40" name="answer" id="specialboxn" /> <input type="submit" value="' . $lang->global['buttonrecover'] . '" class="button" /></td></tr>';
    echo '</form></table>';
    stdfoot ();
  }

?>
