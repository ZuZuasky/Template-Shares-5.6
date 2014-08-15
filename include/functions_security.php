<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_recaptcha_code ($submitbutton = false, $buttonname = 'go', $extra = '')
  {
    global $reCAPTCHAPublickey;
    global $reCAPTCHAPrivatekey;
    global $reCAPTCHATheme;
    global $reCAPTCHALanguage;
    global $lang;
    include_once INC_PATH . '/recaptchalib.php';
    echo '
	<script type="text/javascript">
		//<![CDATA[
		var RecaptchaOptions = {
			theme : "' . $reCAPTCHATheme . '",
			lang : "' . $reCAPTCHALanguage . '"
			};
		//]]>
	</script>
	';
    echo '
	<tr>
		<td class="rowhead">
			' . $lang->global['secimage'] . '
		</td>
		<td>
			' . recaptcha_get_html ($reCAPTCHAPublickey, NULL) . '
		</td>
	</tr>' . ($submitbutton ? '<tr><td class="rowhead">' . $lang->global['seccode'] . '</td><td><input type="submit" value="' . $buttonname . '" class="button" ' . $extra . ' /></td></tr>' : '');
  }

  function show_image_code ($submitbutton = false, $buttonname = 'go', $extra = '')
  {
    global $iv;
    global $BASEURL;
    global $lang;
    global $pic_base_url;
    $imagehash = '';
    if ($iv == 'reCAPTCHA')
    {
      unset ($_SESSION[security_code]);
      show_recaptcha_code ($submitbutton, $buttonname, $extra);
      return null;
    }

    if ($iv == 'yes')
    {
      unset ($_SESSION[security_code]);
      echo (!defined ('SKIP_RELOAD_CODE') ? '
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
		' : '') . '
		<tr>
			<td class="rowhead">' . $lang->global['secimage'] . '</td>
			<td>
				<table>
					<tr>
						<td rowspan="2" class="none"><img src="' . $BASEURL . '/include/class_tscaptcha.php" id="regimage" border="0" alt="" /></td>
						<td class="none"><img src="' . $BASEURL . '/' . $pic_base_url . 'listen.gif" border="0" style="cursor:pointer" onclick="return ts_open_popup(\'' . $BASEURL . '/listen.php\', 400, 120);" alt="' . $lang->global['seclisten'] . '" title="' . $lang->global['seclisten'] . '" /></td>
					</tr>
					<tr>
						<td class="none"><img src="' . $BASEURL . '/' . $pic_base_url . 'reload.gif" border="0" style="cursor: pointer;" onclick="javascript:reload();" alt="' . $lang->global['secimagehint'] . '" title="' . $lang->global['secimagehint'] . '" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td class="rowhead">' . $lang->global['seccode'] . '</td>
		<td><input type="text" size="26" name="security_code" class="inputPassword" value="" />
		' . ($submitbutton ? '<input type="submit" value="' . $buttonname . '" class="button" ' . $extra . '/>' : '') . '
		</td></tr>';
    }

  }

  function check_code ($imagestring, $where = 'signup.php', $maxattemptlog = true, $extra = '', $returnback = false)
  {
    global $BASEURL;
    global $iv;
    global $reCAPTCHAPrivatekey;
    $__is_valided = false;
    if ($iv == 'reCAPTCHA')
    {
      global $reCAPTCHAPrivatekey;
      include_once INC_PATH . '/recaptchalib.php';
      $resp = recaptcha_check_answer ($reCAPTCHAPrivatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
      if ($resp->is_valid)
      {
        $__is_valided = true;
      }
    }

    if (($__is_valided OR ((!empty ($_SESSION['security_code']) AND !empty ($_POST['security_code'])) AND $_SESSION['security_code'] === $_POST['security_code'])))
    {
      unset ($_SESSION[security_code]);
      if ($returnback)
      {
        return $returnback;
      }
    }
    else
    {
      unset ($_SESSION[security_code]);
      if ($returnback)
      {
        if ($maxattemptlog)
        {
          failedlogins ('silent');
        }

        return false;
      }

      if (($where == 'login.php' AND $maxattemptlog))
      {
        failedlogins ('silent');
        header ('' . 'Location: ' . $BASEURL . '/login.php?error=2' . $extra);
        exit ();
        return null;
      }

      if (($where == 'recover.php' AND $maxattemptlog))
      {
        failedlogins ('silent');
        header ('' . 'Location: ' . $BASEURL . '/recover.php?error=2');
        exit ();
        return null;
      }

      if (($where == 'recoverhint.php' AND $maxattemptlog))
      {
        failedlogins ('silent');
        header ('' . 'Location: ' . $BASEURL . '/recoverhint.php?error=2');
        exit ();
        return null;
      }

      if (strstr ($where, 'signup.php'))
      {
        $ayrac = (strstr ($where, '?') ? '&' : '?');
        header ('Location: ' . $BASEURL . '/' . $where . $ayrac . 'error=2');
        exit ();
        return null;
      }

      if ($maxattemptlog)
      {
        failedlogins ('silent');
      }

      $where = $BASEURL . (substr ($where, 0, 1) == '/' ? '' : '/') . $where;
      header ('Location: ' . $where);
      exit ();
    }

  }

  function remaining ($type = 'login')
  {
    global $maxloginattempts;
    global $ip;
    if (!$ip)
    {
      $ip = getip ();
    }

    $Query = sql_query ('SELECT attempts FROM loginattempts WHERE ip=' . sqlesc ($ip) . ' LIMIT 1');
    $total = (0 < mysql_num_rows ($Query) ? intval (mysql_result ($Query, 0, 'attempts')) : 0);
    $left = $maxloginattempts - $total;
    return ($left <= 2 ? '<font color="#f90510">[' . $left . ']</font>' : '<font color="#037621">[' . $left . ']</font>');
  }

  function failedloginscheck ($type = 'Login')
  {
    global $maxloginattempts;
    global $BASEURL;
    global $ip;
    global $lang;
    if (!$ip)
    {
      $ip = getip ();
    }

    $Query = sql_query ('SELECT attempts FROM loginattempts WHERE ip=' . sqlesc ($ip) . ' LIMIT 1');
    $total = (0 < mysql_num_rows ($Query) ? intval (@mysql_result ($Query, 0, 'attempts')) : 0);
    if ($maxloginattempts <= $total)
    {
      sql_query ('UPDATE loginattempts SET banned = \'yes\' WHERE ip=' . sqlesc ($ip));
      stderr (sprintf ($lang->global['xlocked'], $type), sprintf ($lang->global['xlocked2'], '<a href="' . $BASEURL . '/unbaniprequest.php">', '<a href="' . $BASEURL . '/contactus.php">'), false);
    }

  }

  function failedlogins ($type = 'login', $recover = false, $head = true, $msg = false, $uid = 0)
  {
    global $BASEURL;
    global $ip;
    global $lang;
    global $username;
    global $password;
    global $md5pw;
    global $iphost;
    global $ipaddress;
    if (!$ip)
    {
      $ip = getip ();
    }

    $added = sqlesc (get_date_time ());
    $a = mysql_fetch_row (@sql_query ('SELECT COUNT(*) FROM loginattempts WHERE ip=' . @sqlesc ($ip) . ' LIMIT 0,1'));
    if ($a[0] == 0)
    {
      sql_query ('INSERT INTO loginattempts (ip, added, attempts) VALUES (' . sqlesc ($ip) . ('' . ', ' . $added . ', 1)'));
    }
    else
    {
      sql_query ('UPDATE loginattempts SET attempts = attempts + 1 WHERE ip=' . sqlesc ($ip));
    }

    if ($recover)
    {
      sql_query ('UPDATE loginattempts SET type = \'recover\' WHERE ip = ' . sqlesc ($ip));
    }

    if (($msg AND $uid))
    {
      require_once INC_PATH . '/functions_pm.php';
      send_pm ($uid, sprintf ($lang->global['accountwarn'], $username, $password, $md5pw, $ipaddress, $iphost), $lang->global['warning']);
    }

    if (($type == 'silent' OR $type == 'login'))
    {
      return null;
    }

    stderr ($lang->global['error'], $type, false, $head);
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
