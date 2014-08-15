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
  include_once INC_PATH . '/functions_login.php';
  gzip ();
  dbconn ();
  cur_user_check ();
  define ('C_VERSION', '0.9');
  $lang->load ('confirm');
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
      $form = '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?act=manual">
		<input type="hidden" name="act" value="manual">
		<table border="0" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td colspan="2" class="thead">' . $lang->confirm['manual1'] . '</td>
			</tr>
			<tr>
				<td colspan="2" class="subheader">' . $lang->confirm['manual4'] . '</td>
			</tr>
			<tr>
				<td align="right">' . $lang->confirm['manual2'] . '</td>
				<td align="left"><input type="text" name="id" value="" size="32"></td>
			</tr>
			<tr>
				<td align="right">' . $lang->confirm['manual3'] . '</td>
				<td align="left"><input type="text" name="secret" value="" size="32"></td>
			</tr>
			<tr>
			<td colspan="2" align="center"><input type="submit" value="' . $lang->confirm['manual5'] . '"></td>
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

  $id = (isset ($_GET['id']) ? intval ($_GET['id']) : 0);
  $md5 = (isset ($_GET['secret']) ? $_GET['secret'] : '');
  if (((!is_valid_id ($id) OR empty ($id)) OR empty ($md5)))
  {
    stderr ($lang->global['error'], $lang->confirm['error1']);
  }

  if (strlen ($md5) != 32)
  {
    $md5 = preg_replace ('#\\s+#', '', $_GET['secret']);
    $md5 = urldecode ($md5);
    if (strlen ($md5) != 32)
    {
      stderr ($lang->global['error'], $lang->confirm['error1']);
    }
  }

  $res = sql_query ('SELECT u.passhash, u.status, u.country, u.username, e.editsecret FROM users u LEFT JOIN ts_user_validation e ON (u.id=e.userid) WHERE u.enabled = \'yes\' AND u.id = ' . sqlesc ($id) . ' LIMIT 1');
  ($row = mysql_fetch_assoc ($res) OR stderr ($lang->global['error'], $lang->global['dberror']));
  if (!$row)
  {
    stderr ($lang->global['error'], $lang->confirm['error2']);
  }

  if ($row['status'] != 'pending')
  {
    header ('' . 'Refresh: 0; url=' . $BASEURL . '/ok.php?type=confirmed');
    exit ();
  }

  $sec = hash_pad ($row['editsecret']);
  if ((($md5 != md5 ($sec) OR empty ($sec)) OR empty ($row['editsecret'])))
  {
    stderr ($lang->global['error'], $lang->confirm['error2']);
  }

  sql_query ('UPDATE users SET status=\'confirmed\' WHERE id=' . sqlesc ($id) . ' AND status=\'pending\' AND enabled=\'yes\'');
  if (!mysql_affected_rows ())
  {
    stderr ($lang->global['error'], $lang->confirm['error3']);
  }

  sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($id));
  if (($tsshoutbot == 'yes' AND preg_match ('#newuser#', $tsshoutboxoptions)))
  {
    $query = sql_query ('SELECT name FROM countries WHERE id = ' . sqlesc ($row['country']));
    $countryname = mysql_result ($query, 0, 'name');
    $username = $row['username'];
    $shoutbOT = sprintf ($lang->confirm['shoutbOT'], $id, $username, $countryname);
    $shout_sql = 'INSERT INTO shoutbox (userid, date, content) VALUES (\'999999999\', \'' . TIMENOW . '\', ' . sqlesc ('{systemnotice}' . $shoutbOT) . ')';
    $shout_result = sql_query ($shout_sql);
  }

  $passh = $row['passhash'];
  logincookie ($id, $passh);
  sessioncookie ($id, $passh);
  header ('' . 'Refresh: 0; url=' . $BASEURL . '/ok.php?type=confirm');
?>
