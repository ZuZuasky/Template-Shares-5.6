<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function bark ($msg, $redirect = false)
  {
    global $lang;
    global $where;
    if ($redirect)
    {
      $where .= '&msg=' . base64_encode ($msg);
      header ('' . 'Location: ' . $where);
      exit ();
    }

    stdhead ($lang->global['error']);
    stdmsg ($lang->global['error'], $msg, false);
    stdfoot ();
    exit ();
  }

  function username_exists ($username)
  {
    global $illegalusernames;
    $tracker_query = sql_query ('SELECT username FROM users WHERE username=' . sqlesc ($username) . ' LIMIT 1');
    if (0 < mysql_num_rows ($tracker_query))
    {
      return true;
    }

    $usernames = preg_split ('/\\s+/', $illegalusernames, 0 - 1, PREG_SPLIT_NO_EMPTY);
    foreach ($usernames as $val)
    {
      if (strpos (strtolower ($username), strtolower ($val)) !== false)
      {
        return true;
      }
    }

    return false;
  }

  function email_exists ($email)
  {
    $tracker_query = sql_query ('SELECT email FROM users WHERE email=' . sqlesc ($email) . ' LIMIT 1');
    return (0 < mysql_num_rows ($tracker_query) ? true : false);
  }

  function validusername ($username)
  {
    return (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username) ? true : false);
  }

  function mkglobal ($vars)
  {
    if (!is_array ($vars))
    {
      $vars = explode (':', $vars);
    }

    foreach ($vars as $v)
    {
      if (isset ($_GET[$v]))
      {
        $GLOBALS[$v] = unesc ($_GET[$v]);
        continue;
      }
      else
      {
        if (isset ($_POST[$v]))
        {
          $GLOBALS[$v] = unesc ($_POST[$v]);
          continue;
        }
        else
        {
          return 0;
        }

        continue;
      }
    }

    return 1;
  }

  function safe_email ($email)
  {
    return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
  }

  function unesc ($x)
  {
    return (get_magic_quotes_gpc () ? stripslashes ($x) : $x);
  }

  require 'global.php';
  dbconn ();
  gzip ();
  require INC_PATH . '/readconfig_signup.php';
  require INC_PATH . '/functions_security.php';
  require INC_PATH . '/functions_login.php';
  require INC_PATH . '/functions_EmailBanned.php';
  failedloginscheck ('Signup');
  cur_user_check ();
  define ('TS_VERSION', '2.5.3 ');
  $lang->load ('signup');
  $type = (isset ($_POST['type']) ? htmlspecialchars_uni ($_POST['type']) : (isset ($_GET['type']) ? htmlspecialchars_uni ($_GET['type']) : ''));
  $ip = getip ();
  if (!empty ($badcountries))
  {
    require INC_PATH . '/function_country.php';
    $two_letter_country_code = @detect_user_country ();
    $badcountries = @explode (',', $badcountries);
    if (@in_array (@strtoupper ($two_letter_country_code), $badcountries))
    {
      stderr ($lang->global['error'], $lang->global['signupdisabled']);
    }
  }

  if ($type == 'invite')
  {
    $hash = (isset ($_POST['invitehash']) ? htmlspecialchars_uni ($_POST['invitehash']) : (isset ($_GET['invitehash']) ? htmlspecialchars_uni ($_GET['invitehash']) : ''));
    if ((empty ($hash) OR strlen ($hash) != 32))
    {
      stderr ($lang->global['error'], $lang->signup['invalidinvitecode']);
    }

    registration_check ('invitesystem', false, true, true, $hash);
    ($getinviter = sql_query ('SELECT inviter FROM invites WHERE hash = \'' . mysql_real_escape_string ($hash) . '\'') OR stderr ($lang->global['error'], $lang->signup['invalidinvitecode']));
    if ((!$getinviter OR mysql_num_rows ($getinviter) == 0))
    {
      stderr ($lang->global['error'], $lang->signup['invalidinvitecode']);
    }
    else
    {
      $getinviter_results = mysql_fetch_assoc ($getinviter);
      $inviter = (int)$getinviter_results['inviter'];
      if ((!$getinviter_results OR !$inviter))
      {
        stderr ($lang->global['error'], $lang->signup['invalidinvitecode']);
      }
      else
      {
        $hidden_fields = '
			<input type="hidden" name="inviter" value="' . (int)$inviter . '" />
			<input type="hidden" name="type" value="invite" />
			<input type="hidden" name="invitehash" value="' . $hash . '" />';
        $useinvitesystem = true;
        $where = 'signup.php?invitehash=' . $hash . '&type=invite' . (!empty ($_POST['referrer']) ? '&referrer=' . htmlspecialchars_uni ($_POST['referrer']) . '&' : '&');
      }
    }
  }
  else
  {
    registration_check ('normal');
    $hidden_fields = '';
    $useinvitesystem = false;
    $where = 'signup.php' . (!empty ($_POST['referrer']) ? '?referrer=' . htmlspecialchars_uni ($_POST['referrer']) . '&' : '?');
  }

  $where .= 'wantusername=' . htmlspecialchars_uni ($_POST['wantusername']) . '&email=' . htmlspecialchars_uni (urlencode ($_POST['email'])) . '&passhint=' . intval ($_POST['passhint']) . '&hintanswer=' . htmlspecialchars_uni ($_POST['hintanswer']) . '&country=' . intval ($_POST['country']) . '&gender=' . htmlspecialchars_uni ($_POST['gender']) . '&dof=' . intval ($_POST['day']) . ',' . intval ($_POST['month']) . ',' . intval ($_POST['year']);
  if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
  {
    check_code ($_POST['imagestring'], $where, false);
  }

  if (!mkglobal ('wantusername:wantpassword:passagain:email'))
  {
    bark ($lang->global['dontleavefieldsblank'], true);
  }

  if ((((($_POST['uaverify'] != 'yes' OR $_POST['rulesverify'] != 'yes') OR $_POST['faqverify'] != 'yes') OR $_POST['ageverify'] != 'yes') AND $r_verification == 'yes'))
  {
    bark ($lang->signup['noagree'], true);
  }

  $country = (int)$_POST['country'];
  $passhint = (int)$_POST['passhint'];
  $hintanswer = htmlspecialchars_uni ($_POST['hintanswer']);
  $email = htmlspecialchars_uni ($email);
  $tzoffset = htmlspecialchars_uni ($_POST['tzoffset']);
  $gender = htmlspecialchars_uni ($_POST['gender']);
  $email = safe_email ($email);
  if (!check_email ($email))
  {
    bark ($lang->signup['invalidemail'], true);
  }

  if (emailbanned ($email))
  {
    bark ($lang->signup['invalidemail2'], true);
  }

  if (email_exists ($email))
  {
    bark ($lang->signup['invalidemail3'], true);
  }

  $allowed_genders = array ('male', 'female');
  if ((!in_array ($gender, $allowed_genders, true) AND $r_gender == 'yes'))
  {
    bark ($lang->signup['nogender'], true);
  }

  if (((((!is_valid_id ($country) OR $country == '') OR $country == '72') AND $r_country == 'yes') OR (!is_valid_id ($passhint) AND $r_secretquestion == 'yes')))
  {
    bark ($lang->global['dontleavefieldsblank'], true);
  }

  if ((strlen ($hintanswer) < 6 AND $r_secretquestion == 'yes'))
  {
    bark ($lang->signup['hae1'], true);
  }

  if (($hintanswer == $wantusername AND $r_secretquestion == 'yes'))
  {
    bark ($lang->signup['hae2'], true);
  }

  if (strlen ($wantusername) < 3)
  {
    bark ($lang->signup['une1'], true);
  }

  if (12 < strlen ($wantusername))
  {
    bark ($lang->signup['une2'], true);
  }

  if (!validusername ($wantusername))
  {
    bark ($lang->signup['une3'], true);
  }

  if (username_exists ($wantusername))
  {
    bark ($lang->signup['une4'], true);
  }

  if ($wantpassword != $passagain)
  {
    bark ($lang->signup['passe1'], true);
  }

  if (strlen ($wantpassword) < 6)
  {
    bark ($lang->signup['passe2'], true);
  }

  if (40 < strlen ($wantpassword))
  {
    bark ($lang->signup['passe3'], true);
  }

  if ($wantpassword == $wantusername)
  {
    bark ($lang->signup['passe4'], true);
  }

  if ($r_bday == 'yes')
  {
    if ((((((!empty ($_POST['day']) AND !empty ($_POST['month'])) AND !empty ($_POST['year'])) AND is_valid_id ($_POST['day'])) AND is_valid_id ($_POST['month'])) AND is_valid_id ($_POST['year'])))
    {
      $day = htmlspecialchars_uni ($_POST['day']);
      $month = htmlspecialchars_uni ($_POST['month']);
      $year = intval ($_POST['year']);
      $bday = array ($day, $month, $year);
      $bday = implode ('-', $bday);
    }
    else
    {
      bark ($lang->signup['invalidbday'], true);
    }
  }

  $referrer = 0;
  if (((!empty ($_POST['referrer']) AND validusername ($_POST['referrer'])) AND $r_referrer == 'yes'))
  {
    ($r_query = sql_query ('SELECT id FROM users WHERE enabled = \'yes\' AND username = ' . sqlesc ($_POST['referrer'])) OR sqlerr (__FILE__, 236));
    if (0 < mysql_num_rows ($r_query))
    {
      $referrer = mysql_result ($r_query, 0, 'id');
    }
  }

  $secret = mksecret ();
  $wantpasshash = md5 ($secret . $wantpassword . $secret);
  $editsecret = ($verification == 'admin' ? '' : mksecret ());
  $jd = get_date_time ();
  $uploaded = (0 < $autogigsignup ? $autogigsignup * 1024 * 1024 * 1024 : 0);
  $seedbonus = (0 < $autosbsignup ? $autosbsignup : 0);
  $defaultuseroptions = 'A0B0C0D1E1F0G1H1I2K1L1M1N1O0P1';
  if ($_POST['dst'] == '2')
  {
    $dst = '0';
    $autodst = '1';
  }
  else
  {
    if ($_POST['dst'] == '1')
    {
      $dst = '1';
      $autodst = '0';
    }
    else
    {
      $dst = '0';
      $autodst = '0';
    }
  }

  $gender = ($gender == 'male' ? '1' : '2');
  $options = str_replace (array ('N1', 'O0', 'L1'), array ('N' . $autodst, 'O' . $dst, 'L' . $gender), $defaultuseroptions);
  $ret = sql_query ('INSERT INTO users (username, passhash, secret, ip, uploaded, seedbonus, email, country, tzoffset, status, usergroup, invites, invited_by, birthday, added, options) VALUES (' . implode (',', array_map ('sqlesc', array ($wantusername, $wantpasshash, $secret, $ip, $uploaded, $seedbonus, $email, $country, $tzoffset, 'pending', $_d_usergroup, $invite_count, $inviter, $bday, $jd, $options))) . ')');
  if (!$ret)
  {
    bark ($lang->global['dberror'], true);
  }

  $id = mysql_insert_id ();
  if ($verification != 'admin')
  {
    (sql_query ('REPLACE INTO ts_user_validation (editsecret, userid) VALUES (' . sqlesc ($editsecret) . ', ' . sqlesc ($id) . ')') OR bark ($lang->global['dberror']));
  }

  if ((0 < $id AND 0 < $referrer))
  {
    $credit = 107374182;
    (sql_query ('' . 'INSERT INTO referrals (uid,referring,credit) VALUES (\'' . $referrer . '\', \'' . $id . '\', \'' . $credit . '\')') OR sqlerr (__FILE__, 285));
    (sql_query ('' . 'UPDATE users SET uploaded = uploaded + ' . $credit . ' WHERE id = \'' . $referrer . '\'') OR sqlerr (__FILE__, 286));
  }

  if ($useinvitesystem)
  {
    sql_query ('INSERT INTO friends VALUES (0,' . sqlesc ($id) . ', ' . sqlesc ($inviter) . ',\'c\')');
    sql_query ('INSERT INTO friends VALUES (0,' . sqlesc ($inviter) . ', ' . sqlesc ($id) . ',\'c\')');
    sql_query ('DELETE FROM invites WHERE inviter = ' . sqlesc ($inviter) . ' AND hash = ' . sqlesc ($hash));
  }

  if ((($r_secretquestion == 'yes' AND $hintanswer) AND $passhint))
  {
    sql_query ('' . 'REPLACE INTO ts_secret_questions (userid, passhint, hintanswer) VALUES (\'' . $id . '\', \'' . $passhint . '\', ' . sqlesc (md5 ($hintanswer)) . ')');
  }

  $psecret = md5 ($editsecret);
  $usern = htmlspecialchars_uni ($wantusername);
  require_once INC_PATH . '/functions_pm.php';
  send_pm ($id, sprintf ($lang->signup['welcomepmbody'], $usern, $SITENAME, $BASEURL), sprintf ($lang->signup['welcomepmsubject'], $SITENAME));
  if ($verification == 'automatic')
  {
    stdhead ();
    stdmsg ($lang->signup['autoconfirm'], sprintf ($lang->signup['autoconfirm2'], $BASEURL, $id, $psecret), false);
    stdfoot ();
    exit ();
    return 1;
  }

  if ($verification == 'admin')
  {
    header ('' . 'Location: ' . $BASEURL . '/ok.php?type=adminactivate');
    return 1;
  }

  $body = sprintf ($lang->signup['verifiyemailbody'], $usern, $BASEURL, $id, $psecret, $SITENAME);
  sent_mail ($email, sprintf ($lang->signup['verifiyemailsubject'], $SITENAME), $body, 'signup', false);
  header ('' . 'Location: ' . $BASEURL . '/ok.php?type=signup&email=' . urlencode ($email));
?>
