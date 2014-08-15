<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_app_errors ($text = '')
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
					' . ($text ? $text : $lang->global['error']) . '
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

  require 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('TSA_VERSION', '0.1 ');
  $lang->load ('ts_applications');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  $uid = 0 + $CURUSER['id'];
  ($query = sql_query ('' . 'SELECT rid FROM ts_application_requests WHERE uid = \'' . $uid . '\' AND status = \'0\'') OR sqlerr (__FILE__, 56));
  if (0 < mysql_num_rows ($query))
  {
    stderr ($lang->global['error'], $lang->ts_applications['error5']);
  }

  if ((($do == 'save_apply' AND $aid = intval ($_GET['aid'])) AND is_valid_id ($aid)))
  {
    $url = trim ($_POST['url']);
    $info = trim ($_POST['info']);
    if (((strlen ($url) < 5 OR !preg_match ('' . '/^[a-zA-Z]+[:\\/\\/]+[A-Za-z0-9\\-_]+\\.+[A-Za-z0-9\\.\\/%&=\\?\\-_]+$/i', $url)) OR $url == 'http://'))
    {
      $errors[] = $lang->ts_applications['error4'];
    }

    if (strlen ($info) < 5)
    {
      $errors[] = str_replace (':', '', $lang->ts_applications['info']);
    }

    if (0 < count ($errors))
    {
      $do = 'apply';
      $_POST['apply'][$aid] = 'yes';
    }
    else
    {
      (sql_query ('' . 'INSERT INTO ts_application_requests (aid, uid, url, info, created) VALUES (\'' . $aid . '\', \'' . $uid . '\', ' . sqlesc ($url) . ', ' . sqlesc ($info) . ', \'' . time () . '\')') OR sqlerr (__FILE__, 84));
      if (mysql_affected_rows ())
      {
        ($query = sql_query ('SELECT u.id, g.gid FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE g.cansettingspanel = \'yes\' AND u.enabled=\'yes\'') OR sqlerr (__FILE__, 87));
        if (0 < mysql_num_rows ($query))
        {
          $subject = $lang->ts_applications['subject'];
          $msg = sprintf ($lang->ts_applications['msg'], '[URL=' . $BASEURL . '/userdetails.php?id=' . $uid . ']' . $CURUSER['username'] . '[/URL]', '[URL=' . $BASEURL . '/admin/index.php?act=ts_application_requests&do=view&rid=' . mysql_insert_id () . ']', '[/URL]');
          require_once INC_PATH . '/functions_pm.php';
          while ($SM = mysql_fetch_assoc ($query))
          {
            send_pm ($SM['id'], $msg, $subject);
          }
        }

        stdhead ();
        $errors[] = $lang->ts_applications['done'];
        show_app_errors (sprintf ($lang->ts_applications['header'], $SITENAME));
        stdfoot ();
        exit ();
      }
      else
      {
        stderr ($lang->global['error'], $lang->global['dberror']);
      }
    }
  }

  if ((($do == 'apply' AND $aid = intval ($_GET['aid'])) AND is_valid_id ($aid)))
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      if ((!$_POST['apply'][$aid] OR $_POST['apply'][$aid] != 'yes'))
      {
        $errors[] = $lang->ts_applications['error'];
      }
      else
      {
        ($query = sql_query ('' . 'SELECT title FROM ts_applications WHERE enabled = \'1\' AND aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 121));
        if (0 < mysql_num_rows ($query))
        {
          $app = mysql_fetch_assoc ($query);
          $title = sprintf ($lang->ts_applications['header'], $SITENAME);
          stdhead ($title);
          show_app_errors ();
          echo '
				<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=save_apply&aid=' . $aid . '">
				<input type="hidden" name="do" value="save_apply" />
				<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
					<tr>
						<td align="left" class="thead">
							' . $title . '
						</td>
					</tr>
					<tr>
						<td>
							<fieldset>
								<legend>' . $app['title'] . '</legend>
								<table width="100%" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td class="none" align="right">' . $lang->ts_applications['username'] . '</td>
										<td class="none"><input type="text" size="30" name="username" value="' . $CURUSER['username'] . '" disabled="disabled" /></td>
									</tr>
									<tr>
										<td class="none" align="right">' . $lang->ts_applications['email'] . '</td>
										<td class="none"><input type="text" size="40" name="email" value="' . $CURUSER['email'] . '" disabled="disabled" /></td>
									</tr>
									<tr>
										<td class="none" align="right">' . $lang->ts_applications['url'] . '</td>
										<td class="none"><input type="text" size="50" name="url" value="' . ($url ? htmlspecialchars_uni ($url) : 'http://') . '" /></td>
									</tr>
									<tr>
										<td class="none" align="right" valign="top">' . $lang->ts_applications['info'] . '</td>
										<td class="none"><textarea name="info" style="width: 390px; height: 90px;">' . ($info ? htmlspecialchars_uni ($info) : '') . '</textarea></td>
									</tr>
									<tr>
										<td class="none" align="center" colspan="2"><input type="submit" value="' . $lang->ts_applications['button3'] . '" /> <input type="reset" value="' . $lang->ts_applications['button4'] . '" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
				</table>
				</form>
				';
          stdfoot ();
          exit ();
        }
        else
        {
          stderr ($lang->global['error'], $lang->ts_applications['error3']);
        }
      }
    }

    ($query = sql_query ('' . 'SELECT title, description, requirements, enabled FROM ts_applications WHERE aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 177));
    if (0 < mysql_num_rows ($query))
    {
      $app = mysql_fetch_assoc ($query);
      if ($app['enabled'] != '1')
      {
        $errors[] = $lang->ts_applications['error3'];
      }
      else
      {
        $title = sprintf ($lang->ts_applications['header'], $SITENAME) . ' - ' . $app['title'];
        stdhead ($title);
        show_app_errors ();
        echo '
			<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=apply&aid=' . $aid . '">
			<input type="hidden" name="do" value="apply" />
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
				<tr>
					<td align="left" class="thead">
						' . $title . '
					</td>
				</tr>
				<tr>
					<td>
						<fieldset>
							<legend>' . $lang->ts_applications['desc'] . '</legend>
							' . nl2br ($app['description']) . '
						</fieldset>
						<fieldset>
							<legend>' . $lang->ts_applications['req'] . '</legend>
							' . nl2br ($app['requirements']) . '
						</fieldset>
						<fieldset>
							<legend>' . $lang->ts_applications['button'] . '</legend>
							<input type="checkbox" name="apply[' . $aid . ']" value="yes" class="inlineimg" /> ' . $lang->ts_applications['apply'] . '
							<input type="submit" value="' . $lang->ts_applications['button'] . '" />
						</fieldset>
					</td>
				</tr>
			</table>
			</form>
			';
        stdfoot ();
        exit ();
      }
    }
    else
    {
      $errors[] = $lang->ts_applications['error3'];
    }
  }

  $title = sprintf ($lang->ts_applications['header'], $SITENAME);
  stdhead ($title);
  $str = '';
  ($query = sql_query ('SELECT aid, title, description, created, enabled FROM ts_applications ORDER BY created, enabled DESC') OR sqlerr (__FILE__, 232));
  if (0 < mysql_num_rows ($query))
  {
    while ($app = mysql_fetch_assoc ($query))
    {
      if ($app['enabled'] == '1')
      {
        $button = '<input type="button" value="' . $lang->ts_applications['button'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=apply&aid=' . $app['aid'] . '\'); return false;" />';
      }
      else
      {
        $button = '<input type="button" value="' . $lang->ts_applications['button2'] . '" onclick="alert(\'' . $lang->ts_applications['error3'] . '\'); return false;" />';
      }

      $str .= '
		<tr>
			<td align="left" valign="top">' . $app['title'] . '</td>
			<td align="left" valign="top">' . $app['description'] . '</td>
			<td align="center" valign="center">' . my_datee ($dateformat, $app['created']) . ' ' . my_datee ($timeformat, $app['created']) . '</td>
			<td align="center" valign="center">' . $button . '</td>
		</tr>';
    }
  }
  else
  {
    $str = '<tr><td colspan="4">' . $lang->ts_applications['norecord'] . '</td></tr>';
  }

  show_app_errors ();
  echo '
<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type="hidden" name="do" value="apply" />
<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td align="left" class="thead" colspan="4">
			' . $title . '
		</td>
	</tr>
	<tr>
		<td align="left" class="subheader" width="30%">
			' . $lang->ts_applications['title'] . '
		</td>
		<td align="left" class="subheader" width="40%">
			' . $lang->ts_applications['desc'] . '
		</td>
		<td align="center" class="subheader" width="20%">
			' . $lang->ts_applications['created'] . '
		</td>
		<td align="center" class="subheader" width="10%">
			' . $lang->ts_applications['button'] . '
		</td>
	</tr>
	' . $str . '
</table>
</form>
';
  stdfoot ();
?>
