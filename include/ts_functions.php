<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_notice ($notice = '', $iserror = false, $title = '', $BR = '<br />')
  {
    global $BASEURL;
    global $lang;
    $defaulttemplate = ts_template ();
    $imagepath = $BASEURL . '/include/templates/' . $defaulttemplate . '/images/';
    $lastword = ($iserror ? 'e' : 'n');
    $uniqeid = md5 (time ());
    return '
	<script type="text/javascript">
		function ts_show_tag(id, status)
		{
			if (TSGetID(id)){if (status == true || status == false){TSGetID(id).style.display = (status == true)?"none":"";}
			else{TSGetID(id).style.display = (TSGetID(id).style.display == "")?"none":"";}}
		}
	</script>
	<link rel="stylesheet" href="' . $BASEURL . '/include/templates/' . $defaulttemplate . '/style/notification.css" type="text/css" media="screen" />
	<div class="notification-border-' . $lastword . '" id="notification_' . $uniqeid . '" align="center">
		<table class="notification-th-' . $lastword . '" border="0" cellpadding="2" cellspacing="0">
			<tbody>
				<tr>
					<td align="left" width="100%" class="none">
					&nbsp;<img src="' . $imagepath . 'notification_' . $lastword . '.gif" alt="" align="top" border="0" height="14" width="14" />&nbsp;<span class="notification-title-' . $lastword . '" />' . ($title ? $title : $lang->global['sys_message']) . '</span>
					</td>
					<td class="none"><img src="' . $imagepath . 'notification_close.gif" alt="" onclick="ts_show_tag(\'notification_' . $uniqeid . '\', true);" class="hand" border="0" height="13" width="13" /></td>
				</tr>
			</tbody>
		</table>
		<div class="notification-body">
			' . $notice . '
		</div>
	</div>
	' . $BR;
  }

  function maxsysop ()
  {
    global $CURUSER;
    global $rootpath;
    global $lang;
    global $usergroups;
    if (is_mod ($usergroups))
    {
      $results = explode (',', file_get_contents (CONFIG_DIR . '/STAFFTEAM'));
      if (!in_array ($CURUSER['username'] . ':' . $CURUSER['id'], $results, true))
      {
        require_once INC_PATH . '/functions_pm.php';
        send_pm (1, 'Fake Account Detected: Username: ' . $CURUSER['username'] . ' - UserID: ' . $CURUSER['id'] . ' - UserIP : ' . getip (), 'Warning: Fake Account Detected!');
        write_log ($msg);
        stderr ($lang->global['error'], $lang->global['fakeaccount']);
      }

      unset ($results);
    }

  }

  function fix_url ($url)
  {
    $url = htmlspecialchars ($url);
    return str_replace (array ('&amp;', ' '), array ('&', '&nbsp;'), $url);
  }

  function htmlspecialchars_uni ($text, $entities = true)
  {
    return str_replace (array ('<', '>', '"'), array ('&lt;', '&gt;', '&quot;'), preg_replace ('/&(?!' . ($entities ? '#[0-9]+|shy' : '(#[0-9]+|[a-z]+)') . ';)/si', '&amp;', $text));
  }

  function sql_query ($_run_query)
  {
    if (!defined ('DEBUGMODE'))
    {
      $query_start = array_sum (explode (' ', microtime ()));
    }

    $__return = mysql_query ($_run_query);
    if (!defined ('DEBUGMODE'))
    {
      $query_end = round (array_sum (explode (' ', microtime ())) - $query_start, 4);
      if (!isset ($_SESSION['queries']))
      {
        $_SESSION['queries'] = array ();
      }

      if (isset ($_SESSION['totalqueries']))
      {
        ++$_SESSION['totalqueries'];
      }
      else
      {
        $_SESSION['totalqueries'] = 1;
      }

      $_SESSION['queries'][] = array ('id' => 0 + $_SESSION['totalqueries'], 'query_time' => substr ($query_end, 0, 8), 'query' => trim ($_run_query));
    }

    unset ($query_start);
    unset ($query_end);
    return $__return;
  }

  function tsrowcount ($C, $T, $E = '')
  {
    ($Q = sql_query ('' . 'SELECT COUNT(' . $C . ') FROM ' . $T . ($E ? '' . ' WHERE ' . $E : '')) OR sqlerr (__FILE__, 126));
    $R = mysql_fetch_row ($Q);
    return $R[0];
  }

  function write_log ($Text)
  {
    sql_query ('INSERT INTO sitelog VALUES (NULL, NOW(), ' . sqlesc ($Text) . ')');
  }

  function kps ($Type = '+', $Points = '1.0', $ID = '')
  {
    global $bonus;
    if ((empty ($bonus) OR !$bonus))
    {
      clearstatcache ();
      $var_array = unserialize (file_get_contents (CONFIG_DIR . '/KPS'));
      extract ($var_array, EXTR_PREFIX_SAME, 'wddx');
      unset ($var_array);
    }

    if (($bonus == 'enable' OR $bonus == 'disablesave'))
    {
      sql_query ('' . 'UPDATE users SET seedbonus = seedbonus ' . $Type . ' \'' . $Points . '\' WHERE id = \'' . $ID . '\'');
    }

  }

  function sent_mail ($to = '', $subject = '', $body = '', $type = 'confirmation', $showmsg = true, $multiple = false, $multiplemail = '')
  {
    global $rootpath;
    global $SITENAME;
    global $SITEEMAIL;
    global $charset;
    global $lang;
    include INC_PATH . '/readconfig_smtp.php';
    $fromname = $SITENAME;
    $fromemail = $SITEEMAIL;
    $skip_formats = array ('massmail', 'inactiveusers', 'sendmail');
    $windows = false;
    if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
    {
      $eol = '
';
      $windows = true;
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

    if ((strstr ($body, '<br />') === false AND !in_array ($type, $skip_formats)))
    {
      $body = format_comment ($body);
    }

    $mid = md5 (uniqid (rand (), true) . time ());
    $name = $_SERVER['SERVER_NAME'];
    $headers .= '' . 'From: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Reply-To: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Return-Path: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Message-ID: <' . $mid . ' thesystem@' . $name . '>' . $eol;
    $headers .= 'X-Mailer: PHP v' . phpversion () . $eol;
    $headers .= 'MIME-Version: 1.0' . $eol;
    $headers .= 'Content-Transfer-Encoding: 8bit' . $eol;
    $headers .= '' . 'Content-type: text/html; charset=' . $charset . $eol;
    $headers .= 'X-Sender: PHP' . $eol;
    if ($multiple)
    {
      $headers .= '' . 'Bcc: ' . $multiplemail . '.' . $eol;
    }

    if ($GLOBALS['SMTP']['smtptype'] == 'default')
    {
      $mail = mail ($to, $subject, $body, $headers);
      if ((!$mail AND $showmsg))
      {
        stderr ($lang->global['error'], $lang->global['mailerror']);
      }
    }
    else
    {
      if ($GLOBALS['SMTP']['smtptype'] == 'advanced')
      {
        if ((isset ($GLOBALS['SMTP']['smtp']) AND $GLOBALS['SMTP']['smtp'] == 'yes'))
        {
          ini_set ('SMTP', $GLOBALS['SMTP']['smtp_host']);
          ini_set ('smtp_port', $GLOBALS['SMTP']['smtp_port']);
          if ($windows)
          {
            ini_set ('sendmail_from', $GLOBALS['SMTP']['smtp_from']);
          }
        }

        $mail = mail ($to, $subject, $body, $headers);
        if ((!$mail AND $showmsg))
        {
          stderr ($lang->global['error'], $lang->global['mailerror']);
        }

        ini_restore (SMTP);
        ini_restore (smtp_port);
        if ($windows)
        {
          ini_restore ('sendmail_from');
        }
      }
      else
      {
        if ($GLOBALS['SMTP']['smtptype'] == 'external')
        {
          require_once INC_PATH . '/smtp/smtp.lib.php';
          $mail = new smtp ();
          $mail->debug (false);
          $mail->open ($GLOBALS['SMTP']['smtpaddress'], $GLOBALS['SMTP']['smtpport']);
          $mail->auth ($GLOBALS['SMTP']['accountname'], $GLOBALS['SMTP']['accountpassword']);
          $mail->from ($SITEEMAIL);
          $mail->to ($to);
          $mail->subject ($subject);
          $mail->body ($body);
          $mail->mime_charset ('text/html', $charset);
          $mail->send ();
          $mail->close ();
        }
      }
    }

    if ($showmsg)
    {
      if ($type == 'confirmation')
      {
        stderr ($lang->global['success'], sprintf ($lang->global['mailsent'], htmlspecialchars_uni ($to)), false);
        return null;
      }

      if ($type == 'details')
      {
        stderr ($lang->global['success'], sprintf ($lang->global['mailsent2'], htmlspecialchars_uni ($to)), false);
        return null;
      }
    }
    else
    {
      return true;
    }

  }

  function maxslots ()
  {
    global $CURUSER;
    global $maxdlsystem;
    global $lang;
    global $usergroups;
    include_once INC_PATH . '/readconfig_waitslot.php';
    if ((($maxdlsystem == 'yes' AND $usergroups['isvipgroup'] != 'yes') AND !is_mod ($usergroups)))
    {
      if ($GLOBALS['WAITSLOT']['waitsystemtype'] == 1)
      {
        $gigs = $CURUSER['uploaded'] / (1024 * 1024 * 1024);
        $ratio = (0 < $CURUSER['downloaded'] ? $CURUSER['uploaded'] / $CURUSER['downloaded'] : 1);
        if (($ratio < $GLOBALS['WAITSLOT']['ratio5'] OR $gigs < $GLOBALS['WAITSLOT']['upload5']))
        {
          $max = $GLOBALS['WAITSLOT']['slot1'];
        }
        else
        {
          if (($ratio < $GLOBALS['WAITSLOT']['ratio6'] OR $gigs < $GLOBALS['WAITSLOT']['upload6']))
          {
            $max = $GLOBALS['WAITSLOT']['slot2'];
          }
          else
          {
            if (($ratio < $GLOBALS['WAITSLOT']['ratio7'] OR $gigs < $GLOBALS['WAITSLOT']['upload7']))
            {
              $max = $GLOBALS['WAITSLOT']['slot3'];
            }
            else
            {
              if (($ratio < $GLOBALS['WAITSLOT']['ratio8'] OR $gigs < $GLOBALS['WAITSLOT']['upload8']))
              {
                $max = $GLOBALS['WAITSLOT']['slot4'];
              }
              else
              {
                $max = 0;
              }
            }
          }
        }
      }
      else
      {
        $max = $usergroups['slotlimit'];
      }

      if (0 < $max)
      {
        echo sprintf ($lang->global['slots'], $max);
      }
    }

  }

  function getip ()
  {
    $alt_ip = $_SERVER['REMOTE_ADDR'];
    if (isset ($_SERVER['HTTP_CLIENT_IP']))
    {
      $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else
    {
      if ((isset ($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all ('#\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)))
      {
        foreach ($matches[0] as $ip)
        {
          if (!preg_match ('#^(10|172\\.16|192\\.168)\\.#', $ip))
          {
            $alt_ip = $ip;
            break;
          }
        }
      }
      else
      {
        if (isset ($_SERVER['HTTP_FROM']))
        {
          $alt_ip = $_SERVER['HTTP_FROM'];
        }
      }
    }

    return htmlspecialchars ($alt_ip);
  }

  function isvalidip ($IP)
  {
    return (preg_match ('' . '/^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$/', $IP) ? true : false);
  }

  function dbconn ($activeautomaticclean = false, $checkuseraccount = true, $updateuseracc = true)
  {
    global $BASEURL;
    global $rootpath;
    global $mysql_host;
    global $mysql_user;
    global $mysql_pass;
    global $mysql_db;
    global $lang;
    if (!$connect = @mysql_connect ($mysql_host, $mysql_user, $mysql_pass))
    {
      switch (mysql_errno ())
      {
        case 1040:
        {
        }

        case 2002:
        {
          define ('errorid', 6);
          include TSDIR . '/ts_error.php';
          exit ();
          break;
        }

        default:
        {
          define ('errorid', 5);
          include TSDIR . '/ts_error.php';
          exit ();
          break;
        }
      }
    }

    $dberror = false;
    (mysql_select_db ($mysql_db) OR $dberror = true);
    if ($dberror)
    {
      define ('errorid', 5);
      include TSDIR . '/ts_error.php';
      exit ();
    }

    if ($checkuseraccount)
    {
      isuserlogged ($updateuseracc);
    }

    if (!defined ('SKIP_LOCATION_SAVE'))
    {
      $host = getip ();
      $useragent = htmlspecialchars_uni (strtolower ($_SERVER['HTTP_USER_AGENT']));
      $page = htmlspecialchars_uni ($_SERVER['SCRIPT_NAME']);
      $querystring = (isset ($_SERVER['QUERY_STRING']) ? '?' . htmlspecialchars_uni ($_SERVER['QUERY_STRING']) : '');
      (sql_query ('REPLACE INTO ts_sessions VALUES (\'' . md5 ($host . $useragent) . '\', \'' . ($GLOBALS['CURUSER']['id'] ? 0 + $GLOBALS['CURUSER']['id'] : 0) . '\', ' . sqlesc ($host) . ', \'' . time () . '\', ' . sqlesc ($page . $querystring) . ', ' . sqlesc ($useragent) . ')') OR sqlerr (__FILE__, 304));
      unset ($host);
      unset ($useragent);
      unset ($page);
      unset ($querystring);
    }

    $GLOBALS['ts_cron_image'] = (($activeautomaticclean AND !defined ('SKIP_CRON_JOBS')) ? true : false);
  }

  function isuserlogged ($updateuseracc = true)
  {
    global $rootpath;
    global $SITENAME;
    global $iplog1;
    global $securelogin;
    global $securehash;
    global $lang;
    global $cachetime;
    global $cache;
    global $where;
    unset ($GLOBALS[CURUSER]);
    unset ($GLOBALS[usergroups]);
    $ip = getip ();
    require_once INC_PATH . '/functions_isipbanned.php';
    if (isipbanned ($ip))
    {
      define ('errorid', 9);
      include TSDIR . '/ts_error.php';
      exit ();
    }

    if (((empty ($_COOKIE['c_secure_pass']) OR empty ($_COOKIE['c_secure_uid'])) OR strlen ($_COOKIE['c_secure_pass']) != 32))
    {
      return null;
    }

    if ((($securelogin == 'yes' OR $_COOKIE['s_secure_access']) AND (((empty ($_SESSION['s_secure_uid']) OR empty ($_SESSION['s_secure_pass'])) OR strlen ($_SESSION['s_secure_pass']) != 32) OR $_SESSION['s_secure_uid'] != $_COOKIE['c_secure_uid'])))
    {
      return null;
    }

    $id = intval ($_COOKIE['c_secure_uid']);
    if (!is_valid_id ($id))
    {
      return null;
    }

    ($res = @sql_query ('' . 'SELECT * FROM users WHERE id=' . $id . ' LIMIT 1') OR sqlerr (__FILE__, 331));
    if (@mysql_num_rows ($res) == 0)
    {
      return null;
    }

    $row = mysql_fetch_assoc ($res);
    if ($_COOKIE['c_secure_pass'] != md5 (md5 ($row['passhash']) . $ip . md5 ($securehash . $SITENAME)))
    {
      return null;
    }

    if ((($securelogin == 'yes' OR $_COOKIE['s_secure_access']) AND $_SESSION['s_secure_pass'] != md5 (md5 ($row['passhash']) . $ip . md5 ($securehash . $SITENAME))))
    {
      return null;
    }

    if ((($iplog1 == 'yes' AND $ip != $row['ip']) AND !empty ($ip)))
    {
      ($query = sql_query ('SELECT ip FROM iplog WHERE ip = ' . sqlesc ($ip) . ' AND userid = \'' . $id . '\'') OR sqlerr (__FILE__, 340));
      if (($query AND mysql_num_rows ($query) == 0))
      {
        (sql_query ('INSERT INTO iplog VALUES (NULL, ' . sqlesc ($ip) . ', \'' . $id . '\')') OR sqlerr (__FILE__, 341));
      }
    }

    $page = htmlspecialchars_uni ($_SERVER['SCRIPT_NAME']);
    $querystring = (isset ($_SERVER['QUERY_STRING']) ? '?' . htmlspecialchars_uni ($_SERVER['QUERY_STRING']) : '');
    if ($ip != $row['ip'])
    {
      $updateuser[] = 'ip = ' . sqlesc ($ip);
    }

    if (strlen ($row['passkey']) != 32)
    {
      $passkey = md5 ($row['username'] . TIMENOW . $row['passhash'] . md5 ($securehash . $SITENAME));
      $updateuser[] = '' . 'passkey = \'' . $passkey . '\'';
    }

    if ((($where == 'yes' AND $page != $row['page']) AND !preg_match ('/vote|ajax|poll|outputinfo|shoutbox/i', $page)))
    {
      $updateuser[] = 'page = ' . sqlesc ($page . $querystring);
    }

    if ((900 < TIMENOW - @strtotime ($row['last_login']) AND $updateuseracc))
    {
      $updateuser[] = '' . 'last_login = \'' . $row['last_access'] . '\'';
      $updateuser[] = 'last_access = NOW()';
    }
    else
    {
      if ($updateuseracc)
      {
        $updateuser[] = 'last_access = NOW()';
      }
    }

    if (preg_match ('#tsf_forums#Ui', $page))
    {
      if (900 < TIMENOW - $row['last_forum_active'])
      {
        $updateuser[] = 'last_forum_visit=\'' . $row['last_forum_active'] . '\'';
        $updateuser[] = 'last_forum_active=\'' . TIMENOW . '\'';
      }
      else
      {
        $updateuser[] = 'last_forum_active=\'' . TIMENOW . '\'';
      }
    }

    if (0 < count ($updateuser))
    {
      (sql_query ('UPDATE users SET ' . implode (', ', $updateuser) . ('' . ' WHERE id=' . $id)) OR sqlerr (__FILE__, 378));
    }

    $GLOBALS['CURUSER'] = $row;
    require_once TSDIR . '/' . $cache . '/usergroups.php';
    $group_data_results = $usergroupscache[$row['usergroup']];
    $GLOBALS['usergroups'] = $group_data_results;
    if ((($group_data_results['isbanned'] == 'yes' OR $row['enabled'] != 'yes') OR $row['status'] != 'confirmed'))
    {
      unset ($GLOBALS[CURUSER]);
      unset ($GLOBALS[usergroups]);
      unset ($group_data_results);
      print_no_permission (false, true, $row['notifs']);
      exit ();
    }

    if ((empty ($_COOKIE['ts_username']) OR $_COOKIE['ts_username'] != $row['username']))
    {
      @setcookie ('ts_username', $row['username'], TIMENOW + 365 * 24 * 60 * 60, '/');
    }

    unset ($row);
    unset ($group_data_results);
    unset ($usergroupscache);
    unset ($ip);
    unset ($id);
    unset ($res);
    unset ($page);
    unset ($querystring);
    unset ($updateuseracc);
    unset ($updateuser);
  }

  function mksize ($bytes = 0)
  {
    if ($bytes < 1000 * 1024)
    {
      return number_format ($bytes / 1024, 2) . ' KB';
    }

    if ($bytes < 1000 * 1048576)
    {
      return number_format ($bytes / 1048576, 2) . ' MB';
    }

    if ($bytes < 1000 * 1073741824)
    {
      return number_format ($bytes / 1073741824, 2) . ' GB';
    }

    return number_format ($bytes / 1099511627776, 2) . ' TB';
  }

  function sqlesc ($value)
  {
    if (get_magic_quotes_gpc ())
    {
      $value = stripslashes ($value);
    }

    if (!is_numeric ($value))
    {
      $value = '\'' . mysql_real_escape_string ($value) . '\'';
    }

    return $value;
  }

  function ts_template ()
  {
    global $defaulttemplate;
    global $CURUSER;
    $ut = htmlspecialchars_uni ($CURUSER['stylesheet']);
    $path = INC_PATH . '/templates/' . $ut;
    if (((!$CURUSER OR !$ut) OR $ut == $defaulttemplate))
    {
      return $defaulttemplate;
    }

    if (((@file_exists ($path . '/header.php') AND @file_exists ($path . '/footer.php')) AND $ut != $defaulttemplate))
    {
      return $ut;
    }

    return $defaulttemplate;
  }

  function mksecret ($length = 20)
  {
    $set = array ('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $str;
    $i = 1;
    while ($i <= $length)
    {
      $ch = rand (0, count ($set) - 1);
      $str .= $set[$ch];
      ++$i;
    }

    return $str;
  }

  function securehash ($var = NULL)
  {
    global $SITENAME;
    global $securehash;
    return md5 (md5 ($var) . getip () . md5 ($securehash . $SITENAME));
  }

  function loggedinorreturn ($mainpage = false)
  {
    global $rootpath;
    global $CURUSER;
    global $BASEURL;
    global $loadlimit;
    global $usergroups;
    if (!$CURUSER)
    {
      if ($mainpage)
      {
        header ('Location: ' . $BASEURL . '/login.php');
        exit ();
      }
      else
      {
        $to = fix_url ($_SERVER['REQUEST_URI']);
        header ('Location: ' . $BASEURL . '/login.php?returnto=' . urlencode ($to));
        exit ();
      }
    }

    if ((((0 < $loadlimit AND PHP_OS == 'Linux') AND @file_exists ('/proc/loadavg')) AND $filestuff = @file_get_contents ('/proc/loadavg')))
    {
      $loadavg = explode (' ', $filestuff);
      if ($loadlimit < trim ($loadavg[0]))
      {
        if ((!is_mod ($usergroups) AND !preg_match ('#(login|takelogin|sendmessage|settings)#i', $_SERVER['SCRIPT_NAME'])))
        {
          define ('errorid', 6);
          include TSDIR . '/ts_error.php';
          exit ();
        }
      }
    }

    if (((0 < $CURUSER['id'] AND isset ($_SERVER['HTTP_X_MOZ'])) AND strpos ($_SERVER['HTTP_X_MOZ'], 'prefetch') !== false))
    {
      define ('SAPI_NAME', php_sapi_name ());
      if ((SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi'))
      {
        header ('Status: 403 Forbidden');
      }
      else
      {
        header ('HTTP/1.1 403 Forbidden');
      }

      define ('errorid', 7);
      include TSDIR . '/ts_error.php';
      exit ();
    }

    checkinstallation ();
  }

  function readconfig ($configname = '')
  {
    if (strstr ($configname, ','))
    {
      $configlist = explode (',', $configname);
      foreach ($configlist as $key => $configname)
      {
        readconfig (trim ($configname));
      }

      return null;
    }

    if (!$contents = unserialize (file_get_contents (CONFIG_DIR . '/' . strtoupper ($configname))))
    {
      trigger_error ('TS SE Critical Error: Failed to read config file: ' . CONFIG_DIR . '/' . $configname . '.  File: ' . $_SERVER['SCRIPT_NAME'] . ' URL: ' . $_SERVER['REQUEST_URI']);
    }
    else
    {
      $GLOBALS[$configname] = $contents;
    }

    unset ($contents);
  }

  function parked ()
  {
    global $CURUSER;
    global $lang;
    if (preg_match ('#A1#is', $CURUSER['options']))
    {
      stderr ($lang->global['error'], $lang->global['parked']);
    }

  }

  function gzip ($use = false)
  {
    global $gzipcompress;
    if ((((($gzipcompress == 'yes' OR $use) AND @extension_loaded ('zlib')) AND @ini_get ('zlib.output_compression') != '1') AND @ini_get ('output_handler') != 'ob_gzhandler'))
    {
      @ob_start ('ob_gzhandler');
    }

  }

  function checkinstallation ()
  {
    global $rootpath;
    global $usergroups;
    global $cache;
    if (is_mod ($usergroups))
    {
/*      $__TSKeY = @file_get_contents (TSDIR . '/' . $cache . '/systemcache.dat'); */
      $__CU = (!empty ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : (!empty ($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
      $__CU = str_replace (array ('http://www.', 'http://', 'www.'), '', $__CU);
      $__OrjKey = md5 ('LEGAL LICENSE: ' . $__CU . ' INSTALL: FINISHED VERSION: TS Special Edition v.5.6');
      /*if ((empty ($__TSKeY) OR $__TSKeY != $__OrjKey))
      {
        define ('errorid', 8);
        include TSDIR . '/ts_error.php';
        exit ();
      }*/
    }

  }

  function warn_donor ($s, $warnday = 3)
  {
    if ($s < 0)
    {
      $s = 0;
    }

    $t = array ();
    foreach (array ('60:sec', '60:min', '24:hour', '0:day') as $x)
    {
      $y = explode (':', $x);
      if (1 < $y[0])
      {
        $v = $s % $y[0];
        $s = floor ($s / $y[0]);
      }
      else
      {
        $v = $s;
      }

      $t[$y[1]] = $v;
    }

    if ($t['day'] < $warnday)
    {
      return true;
    }

    return false;
  }

  function cutename ($name, $max = 35)
  {
    return htmlspecialchars_uni (($max < strlen ($name) ? substr ($name, 0, $max) . '...' : $name));
  }

  function get_extension ($file)
  {
    return strtolower (substr (strrchr ($file, '.'), 1));
  }

  function dir_list ($dir)
  {
    $dl = array ();
    $ext = '';
    if (!file_exists ($dir))
    {
      error ();
    }

    if ($hd = opendir ($dir))
    {
      while ($sz = readdir ($hd))
      {
        $ext = get_extension ($sz);
        if ((preg_match ('/^\\./', $sz) == 0 AND $ext != 'php'))
        {
          $dl[] = $sz;
          continue;
        }
      }

      closedir ($hd);
      asort ($dl);
      return $dl;
    }

    error ('', 'Couldn\'t open storage folder! Please check the path.');
  }

  function ts_nf ($number)
  {
    return number_format ($number, 0, '.', ',');
  }

  function ts_collapse ($id, $type = 1)
  {
    global $BASEURL;
    global $pic_base_url;
    global $tscollapse;
    if ($type === 1)
    {
      return '<a style="float: right;" href="#top" onclick="return toggle_collapse(\'' . $id . '\')"><img id="collapseimg_' . $id . '" src="' . $BASEURL . '/' . $pic_base_url . 'collapse_tcat' . (isset ($tscollapse['collapseimg_' . $id . '']) ? $tscollapse['collapseimg_' . $id . ''] : '') . '.gif" alt="" border="0" /></a>';
    }

    if ($type === 2)
    {
      return '<tbody id="collapseobj_' . $id . '" style="' . (isset ($tscollapse['collapseobj_' . $id]) ? $tscollapse['collapseobj_' . $id] : 'none') . '">';
    }

  }

  function is_mod ($user = array ())
  {
    return ((($user['cansettingspanel'] === 'yes' OR $user['issupermod'] === 'yes') OR $user['canstaffpanel'] === 'yes') ? true : false);
  }

  function highlight ($search, $subject, $hlstart = '<b><font color=\'#f7071d\'>', $hlend = '</font></b>')
  {
    $srchlen = strlen ($search);
    if ($srchlen == 0)
    {
      return $subject;
    }

    $find = $subject;
    while ($find = stristr ($find, $search))
    {
      $srchtxt = substr ($find, 0, $srchlen);
      $find = substr ($find, $srchlen);
      $subject = str_replace ($srchtxt, '' . $hlstart . $srchtxt . $hlend, $subject);
    }

    return $subject;
  }

  function pager ($perpage, $results, $address = '', $opts = array (), $showgoto = true, $whereto = '')
  {
    global $lang;
    global $BASEURL;
    if ($results < $perpage)
    {
      return array ('', '', '');
    }

    if ($results)
    {
      $totalpages = @ceil ($results / $perpage);
    }
    else
    {
      $totalpages = 0;
    }

    if ((isset ($_GET['showlast']) AND $_GET['showlast'] == 'true'))
    {
      $pagenumber = $totalpages;
    }
    else
    {
      $pagenumber = (isset ($_GET['page']) ? intval ($_GET['page']) : (isset ($_POST['page']) ? intval ($_POST['page']) : ''));
    }

    sanitize_pageresults ($results, $pagenumber, $perpage, 200);
    $limitlower = ($pagenumber - 1) * $perpage;
    $limitupper = $pagenumber * $perpage;
    if ($results < $limitupper)
    {
      $limitupper = $results;
      if ($results < $limitlower)
      {
        $limitlower = $results - $perpage - 1;
      }
    }

    if ($limitlower < 0)
    {
      $limitlower = 0;
    }

    $pagenav = $firstlink = $prevlink = $lastlink = $nextlink = '';
    $curpage = 0;
    if ($results <= $perpage)
    {
      $show['pagenav'] = false;
      return array ('', '', '' . 'LIMIT ' . $limitlower . ', ' . $perpage);
    }

    $show['pagenav'] = true;
    $total = ts_nf ($results);
    $show['prev'] = $show['next'] = $show['first'] = $show['last'] = false;
    if (1 < $pagenumber)
    {
      $prevpage = $pagenumber - 1;
      $prevnumbers = fetch_start_end_total_array ($prevpage, $perpage, $results);
      $show['prev'] = true;
    }

    if ($pagenumber < $totalpages)
    {
      $nextpage = $pagenumber + 1;
      $nextnumbers = fetch_start_end_total_array ($nextpage, $perpage, $results);
      $show['next'] = true;
    }

    if (!empty ($whereto))
    {
      $address = $address . $whereto . '=true&amp;';
      $whereto = '' . '#' . $whereto;
    }

    $pagenavpages = '3';
    if ((!isset ($pagenavsarr) OR !is_array ($pagenavsarr)))
    {
      $pagenavs = '10 50 100 500 1000';
      $pagenavsarr[] = preg_split ('#\\s+#s', $pagenavs, 0 - 1, PREG_SPLIT_NO_EMPTY);
    }

    while ($curpage++ < $totalpages)
    {
      if (($pagenavpages <= abs ($curpage - $pagenumber) AND $pagenavpages != 0))
      {
        if ($curpage == 1)
        {
          $firstnumbers = fetch_start_end_total_array (1, $perpage, $results);
          $show['first'] = true;
        }

        if ($curpage == $totalpages)
        {
          $lastnumbers = fetch_start_end_total_array ($totalpages, $perpage, $results);
          $show['last'] = true;
        }

        if (((in_array (abs ($curpage - $pagenumber), $pagenavsarr) AND $curpage != 1) AND $curpage != $totalpages))
        {
          $pagenumbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $relpage = $curpage - $pagenumber;
          if (0 < $relpage)
          {
            $relpage = '+' . $relpage;
          }

          $pagenav .= '' . '<li><a class="smalltext" href="' . $address . ($curpage != 1 ? 'page=' . $curpage . $whereto : '' . 'tsscript=true' . $whereto) . '" title="' . sprintf ($lang->global['show_results'], $pagenumbers['first'], $pagenumbers['last'], $total) . ('' . '"><!--' . $relpage . '-->' . $curpage . '</a></li>');
          continue;
        }

        continue;
      }
      else
      {
        if ($curpage == $pagenumber)
        {
          $numbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '<li><a name="current" class="current" title="' . sprintf ($lang->global['showing_results'], $numbers['first'], $numbers['last'], $total) . ('' . '">' . $curpage . '</a></li>');
          continue;
        }
        else
        {
          $pagenumbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '' . '<li><a href="' . $address . ($curpage != 1 ? 'page=' . $curpage . $whereto : '' . 'tsscript=true' . $whereto) . '" title="' . sprintf ($lang->global['show_results'], $pagenumbers['first'], $pagenumbers['last'], $total) . ('' . '">' . $curpage . '</a></li>');
          continue;
        }

        continue;
      }
    }

    $prp = ((isset ($prevpage) AND $prevpage != 1) ? 'page=' . $prevpage . $whereto : '' . 'tsscript=true' . $whereto);
    $pagenav = ($showgoto ? '<script type="text/javascript">
	if (typeof menu_register == \'undefined\')
	{
		document.write(\'<script type=\\\'text/javascript\\\' src=\\\'' . $BASEURL . '/scripts/menu.js?v=' . O_SCRIPT_VERSION . '\\\'><\\/script>\');
	}
	</script>' : '') . ('' . '
	<table width="100%" border="0" class="none" style="clear: both;">
		<tr>
			<td class="none" width="100%" style="padding: 0px 0px 1px 0px;">
				<div style="float: left;" id="navcontainer_f">
					<ul>
						<li>' . $pagenumber . ' - ' . $totalpages . '</li>
						') . ($show['first'] ? '<li><a class="smalltext" href="' . $address . 'gofirst=true' . $whereto . '" title="' . $lang->global['first_page'] . ' - ' . sprintf ($lang->global['show_results'], $firstnumbers['first'], $firstnumbers['last'], $total) . '">&laquo; ' . $lang->global['first'] . '</a></li>' : '') . ($show['prev'] ? '<li><a class="smalltext" href="' . $address . $prp . '" title="' . $lang->global['prev_page'] . ' - ' . sprintf ($lang->global['show_results'], $prevnumbers['first'], $prevnumbers['last'], $total) . '">&lt;</a></li>' : '') . ('' . '
						' . $pagenav . '
						') . ($show['next'] ? '<li><a class="smalltext" href="' . $address . 'page=' . $nextpage . $whereto . '" title="' . $lang->global['next_page'] . ' - ' . sprintf ($lang->global['show_results'], $nextnumbers['first'], $nextnumbers['last'], $total) . '">&gt;</a></li>' : '') . ($show['last'] ? '<li><a class="smalltext" href="' . $address . 'page=' . $totalpages . '&amp;golast=true' . $whereto . '" title="' . $lang->global['last_page'] . ' - ' . sprintf ($lang->global['show_results'], $lastnumbers['first'], $lastnumbers['last'], $total) . '">' . $lang->global['last'] . ' <strong>&raquo;</strong></a></li>' : '') . ($showgoto ? '<li><a href="#" id="quicknavpage">' . $lang->global['buttongo'] . '</a></li>' : '') . '
					</ul>
				</div>
			</td>
		</tr>
	</table>
	' . ($showgoto ? '
	<script type="text/javascript">
		menu_register("quicknavpage", true);
	</script>
	<div id="quicknavpage_menu" class="menu_popup" style="display:none;">
	<form action="' . $address . '" method="get" onsubmit="return TSGoToPage(\'' . $address . '\', \'' . $whereto . '\')">
		<table border="0" cellpadding="2" cellspacing="1">
			<tbody>
				<tr>
					<td class="thead" nowrap="nowrap">' . $lang->global['gotopage'] . '</td>
				</tr>
				<tr>
					<td class="subheader" title="">
						<input id="Page_Number" style="font-size: 11px;" size="4" type="text">
						<input value="' . $lang->global['buttongo'] . '" type="button" onclick="TSGoToPage(\'' . $address . '\',\'' . $whereto . '\')">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	</div>
	<script type="text/javascript">
		menu.activate(true);
	</script>
	' : '');
    $pagenav2 = str_replace (array ('quicknavpage', 'Page_Number'), array ('quicknavpage2', 'Page_Number2'), $pagenav);
    return array ($pagenav, $pagenav2, '' . 'LIMIT ' . $limitlower . ', ' . $perpage);
  }

  function sanitize_pageresults ($numresults, &$page, &$perpage, $maxperpage = 20, $defaultperpage = 20)
  {
    $perpage = intval ($perpage);
    if ($perpage < 1)
    {
      $perpage = $defaultperpage;
    }
    else
    {
      if ($maxperpage < $perpage)
      {
        $perpage = $maxperpage;
      }
    }

    $numpages = ceil ($numresults / $perpage);
    if ($numpages == 0)
    {
      $numpages = 1;
    }

    if ($page < 1)
    {
      $page = 1;
      return null;
    }

    if ($numpages < $page)
    {
      $page = $numpages;
    }

  }

  function fetch_start_end_total_array ($pagenumber, $perpage, $total)
  {
    $first = $perpage * ($pagenumber - 1);
    $last = $first + $perpage;
    if ($total < $last)
    {
      $last = $total;
    }

    ++$first;
    return array ('first' => ts_nf ($first), 'last' => ts_nf ($last));
  }

  function get_user_color ($username, $namestyle, $white = false)
  {
    if ($white)
    {
      $new_username = '<font color="#ffffff">' . $username . '</font>';
    }
    else
    {
      $new_username = str_replace ('{username}', $username, $namestyle);
    }

    return $new_username;
  }

  function int_check ($value, $stdhead = false, $stdfood = true, $die = true, $log = true)
  {
    global $CURUSER;
    global $BASEURL;
    global $lang;
    $msg = sprintf ($lang->global['invalididlogmsg'], htmlspecialchars_uni ($_SERVER['REQUEST_URI']), '<a href="' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . '">' . $CURUSER['username'] . '</a>', getip (), get_date_time ());
    if (is_array ($value))
    {
      foreach ($value as $val)
      {
        int_check ($val, $stdhead, $stdfood, $die, $log);
      }

      return null;
    }

    if (!is_valid_id ($value))
    {
      if ($stdhead)
      {
        if ($log)
        {
          write_log ($msg);
        }

        stderr ($lang->global['error'], $lang->global['invalididlogged']);
      }
      else
      {
        print $lang->global['invalididlogged2'];
        if ($log)
        {
          write_log ($msg);
        }
      }

      if ($stdfood)
      {
        stdfoot ();
      }

      if ($die)
      {
        exit ();
        return null;
      }
    }
    else
    {
      return true;
    }

  }

  function is_valid_id ($id)
  {
    return ((is_numeric ($id) AND 0 < $id) AND floor ($id) == $id);
  }

  function flood_check ($type = '', $last = '', $shoutbox = false)
  {
    global $lang;
    global $usergroups;
    global $CURUSER;
    $timecut = time () - $usergroups['floodlimit'];
    if (strstr ($last, '-'))
    {
      $last = strtotime ($last);
    }

    if (($timecut <= $last AND $usergroups['floodlimit'] != 0))
    {
      $remaining_time = $usergroups['floodlimit'] - (time () - $last);
      if ($shoutbox == 0)
      {
        stderr ($lang->global['error'], sprintf ($lang->global['flooderror'], $usergroups['floodlimit'], $type, $remaining_time), false);
        return null;
      }

      $msg = '<font color="#9f040b" size="2">' . sprintf ($lang->global['flooderror'], $usergroups['floodlimit'], $type, $remaining_time) . '</font>';
      return $msg;
    }

  }

  function print_no_permission ($log = false, $stdhead = true, $extra = '')
  {
    global $lang;
    global $SITENAME;
    global $BASEURL;
    global $CURUSER;
    if ($log)
    {
      $page = htmlspecialchars_uni ($_SERVER['SCRIPT_NAME']);
      $query = htmlspecialchars_uni ($_SERVER['QUERY_STRING']);
      $message = sprintf ($lang->global['permissionlogmessage'], $page, $query, '<a href="' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . '">' . $CURUSER['username'] . '</a>', $CURUSER['ip']);
      write_log ($message);
    }

    if ($stdhead)
    {
      stdhead ('Permission Denied!');
      echo sprintf ($lang->global['print_no_permission'], $SITENAME, ($extra != '' ? '<font color="#9f040b">' . $extra . '</font>' : $lang->global['print_no_permission_i']));
      stdfoot ();
    }
    else
    {
      echo sprintf ($lang->global['print_no_permission'], $SITENAME, ($extra != '' ? '<font color="#9f040b">' . $extra . '</font>' : $lang->global['print_no_permission_i']));
      stdfoot ();
    }

    exit ();
  }

  function submit_disable ($formname = '', $buttonname = '', $text = '')
  {
    global $lang;
    $value = '' . 'onsubmit="document.' . $formname . '.' . $buttonname . '.value=\'' . ($text ? $text : $lang->global['pleasewait']) . ('' . '\';document.' . $formname . '.' . $buttonname . '.disabled=true"');
    return $value;
  }

  function my_datee ($format, $stamp = '', $offset = '', $ty = 1)
  {
    global $CURUSER;
    global $lang;
    global $dateformat;
    global $timeformat;
    global $regdateformat;
    global $timezoneoffset;
    global $dstcorrection;
    if (empty ($stamp))
    {
      $stamp = time ();
    }
    else
    {
      if (strstr ($stamp, '-'))
      {
        $stamp = strtotime ($stamp);
      }
    }

    if ((!$offset AND $offset != '0'))
    {
      if (($CURUSER['id'] != 0 AND array_key_exists ('tzoffset', $CURUSER)))
      {
        $offset = $CURUSER['tzoffset'];
        $dstcorr = (preg_match ('#O1#is', $CURUSER['options']) ? 'yes' : 'no');
      }
      else
      {
        $offset = $timezoneoffset;
        $dstcorr = $dstcorrection;
      }

      if ($dstcorr == 'yes')
      {
        ++$offset;
        if (my_substrr ($offset, 0, 1) != '-')
        {
          $offset = '+' . $offset;
        }
      }
    }

    if ($offset == '-')
    {
      $offset = 0;
    }

    $date = gmdate ($format, $stamp + $offset * 3600);
    if (($dateformat == $format AND $ty))
    {
      $stamp = time ();
      $todaysdate = gmdate ($format, $stamp + $offset * 3600);
      $yesterdaysdate = gmdate ($format, $stamp - 86400 + $offset * 3600);
      if ($todaysdate == $date)
      {
        $date = $lang->global['today'];
      }
      else
      {
        if ($yesterdaysdate == $date)
        {
          $date = $lang->global['yesterday'];
        }
      }
    }

    return $date;
  }

  function my_substrr ($string, $start, $length = '')
  {
    if (function_exists ('mb_substr'))
    {
      if ($length != '')
      {
        $cut_string = mb_substr ($string, $start, $length);
      }
      else
      {
        $cut_string = mb_substr ($string, $start);
      }
    }
    else
    {
      if ($length != '')
      {
        $cut_string = substr ($string, $start, $length);
      }
      else
      {
        $cut_string = substr ($string, $start);
      }
    }

    return $cut_string;
  }

  function get_date_time ($timestamp = 0)
  {
    if ($timestamp)
    {
      return date ('Y-m-d H:i:s', $timestamp);
    }

    return date ('Y-m-d H:i:s');
  }

  function gmtime ()
  {
    return strtotime (get_date_time ());
  }

  function sqlerr ($file = '', $line = '', $log = true)
  {
    global $CURUSER;
    global $BASEURL;
    global $usergroups;
    $errormsg = htmlspecialchars_uni (mysql_error ()) . (($file != '' AND $line != '') ? ' in <b>' . $file . '</b>, line <b>' . $line . '</b>' : '');
    if ($log)
    {
      $msg = '<font color="#9f040b"><b>SQL ERROR has accured.</b></font>
		<b>Mysql Error:</b> ' . $errormsg . '
		<b>Request URL:</b> ' . htmlspecialchars_uni ($_SERVER['REQUEST_URI']);
      if ($CURUSER)
      {
        $msg .= '
			<b>Username:</b> <a href="' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . '">' . get_user_color ($CURUSER['username'], $usergroups['namestyle']) . '</a>';
      }

      write_log ($msg);
    }

    header ('Location: ' . $BASEURL . '/ts_error.php?errorid=5');
    exit ();
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
