<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


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

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('AU_VERSION', '1.1 by xam');
  $lang->load ('adduser');
  require INC_PATH . '/readconfig_signup.php';
  $allowed_usergroups = $error = array ();
  $ugs .= '<select name="usergroup">';
  $query = sql_query ('SELECT gid, title FROM usergroups WHERE isbanned = \'no\' AND issupermod = \'no\' AND cansettingspanel = \'no\' AND canstaffpanel = \'no\' AND canuserdetails = \'no\' ORDER BY gid');
  while ($ug = mysql_fetch_assoc ($query))
  {
    $allowed_usergroups[] = $ug['gid'];
    $ugs .= '<option value="' . $ug['gid'] . '"' . ($_POST['usergroup'] == $ug['gid'] ? ' selected="selected"' : '') . ' />' . $ug['title'] . '</option>';
  }

  $ugs .= '</select>';
  if (strtoupper ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    require INC_PATH . '/functions_EmailBanned.php';
    $lang->load ('signup');
    $username = trim ($_POST['username']);
    $email = trim ($_POST['email']);
    $password = trim ($_POST['password']);
    $password2 = trim ($_POST['password2']);
    $usergroup = intval ($_POST['usergroup']);
    $modcomment = htmlspecialchars_uni ($_POST['modcomment']);
    $seedbonus = intval ($_POST['seedbonus']);
    $invites = intval ($_POST['invites']);
    $uploaded = intval ($_POST['uploaded']);
    $downloaded = intval ($_POST['downloaded']);
    $confirm = trim ($_POST['confirm']);
    if (strlen ($username) < 3)
    {
      $error[] = $lang->signup['une1'];
    }

    if (12 < strlen ($username))
    {
      $error[] = $lang->signup['une2'];
    }

    if (!validusername ($username))
    {
      $error[] = $lang->signup['une3'];
    }

    if (username_exists ($username))
    {
      $error[] = $lang->signup['une4'];
    }

    if (!check_email ($email))
    {
      $error[] = $lang->signup['invalidemail'];
    }

    if (emailbanned ($email))
    {
      $error[] = $lang->signup['invalidemail2'];
    }

    if (email_exists ($email))
    {
      $error[] = $lang->signup['invalidemail3'];
    }

    if ($password != $password2)
    {
      $error[] = $lang->signup['passe1'];
    }

    if (strlen ($password) < 6)
    {
      $error[] = $lang->signup['passe2'];
    }

    if (40 < strlen ($password))
    {
      $error[] = $lang->signup['passe3'];
    }

    if ($password == $username)
    {
      $error[] = $lang->signup['passe4'];
    }

    if (!in_array ($usergroup, $allowed_usergroups))
    {
      $error[] = $lang->adduser['invalidug'];
    }

    if (count ($error) == 0)
    {
      $secret = mksecret ();
      $passhash = md5 ($secret . $password . $secret);
      $added = get_date_time ();
      ($query = sql_query ('INSERT INTO users (username, passhash, secret, added, status, email, usergroup, modcomment, seedbonus, invites, uploaded, downloaded) VALUES (' . sqlesc ($username) . ', ' . sqlesc ($passhash) . ', ' . sqlesc ($secret) . ', ' . sqlesc ($added) . ', \'' . ($confirm == 'yes' ? 'pending' : 'confirmed') . '\', ' . sqlesc ($email) . ('' . ', \'' . $usergroup . '\', ') . sqlesc (gmdate ('Y-m-d') . ' - ' . $modcomment) . ('' . ', \'' . $seedbonus . '\', \'' . $invites . '\', \'' . $uploaded . '\', \'' . $downloaded . '\')')) OR sqlerr (__FILE__, 122));
      if (mysql_affected_rows ())
      {
        $id = mysql_insert_id ();
        require_once INC_PATH . '/functions_pm.php';
        send_pm ($id, sprintf ($lang->signup['welcomepmbody'], $username, $SITENAME, $BASEURL), sprintf ($lang->signup['welcomepmsubject'], $SITENAME));
        if ($confirm == 'yes')
        {
          $editsecret = mksecret ();
          if (sql_query ('REPLACE INTO ts_user_validation (editsecret, userid) VALUES (' . sqlesc ($editsecret) . ', ' . sqlesc ($id) . ')'))
          {
            $psecret = md5 ($editsecret);
            $body = sprintf ($lang->signup['verifiyemailbody'], $username, $BASEURL, $id, $psecret, $SITENAME);
            sent_mail ($email, sprintf ($lang->signup['verifiyemailsubject'], $SITENAME), $body, 'signup', false);
          }
        }

        write_log ('New Account Created by ' . $CURUSER['username'] . '.  Account Name: ' . htmlspecialchars_uni ($username));
        redirect ($BASEURL . '/' . ($confirm == 'yes' ? 'checkuser' : 'userdetails') . '.php?id=' . $id, '', '', 3, false, false);
        exit ();
      }
      else
      {
        $error[] = $lang->global['error'];
      }
    }
  }

  stdhead ($lang->adduser['title']);
  if (0 < count ($error))
  {
    _form_header_open_ ($lang->global['error']);
    echo '<tr><td class="none"><span style="color: red;">' . implode ('<br />', $error) . '</span></td></tr>';
    unset ($error);
    _form_header_close_ ();
    echo '<br />';
  }

  echo '<form method="POST" action="' . $_this_script_ . '"><input type="hidden" name="act" value="adduser">';
  _form_header_open_ ($lang->adduser['title']);
  echo '
<tr>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['username'] . '</legend>
			<input type="text" size="30" name="username" value="' . ($username ? htmlspecialchars_uni ($username) : '') . '" />
		</fieldset>
	</td>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['email'] . '</legend>
			<input type="text" size="30" name="email" value="' . ($email ? htmlspecialchars_uni ($email) : '') . '" />
		</fieldset>
	</td>	
</tr>
<tr>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['password'] . '</legend>
			<input type="password" size="30" name="password" value="" />
		</fieldset>
	</td>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['password2'] . '</legend>
			<input type="password" size="30" name="password2" value="" />
		</fieldset>
	</td>	
</tr>
<tr>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['usergroup'] . '</legend>
			' . $ugs . '
		</fieldset>
	</td>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['comment'] . '</legend>
			<input type="text" size="30" name="modcomment" value="' . ($modcomment ? $modcomment : '') . '" />
		</fieldset>
	</td>	
</tr>
<tr>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['bonus'] . '</legend>
			<input type="text" size="30" name="seedbonus" value="' . ($seedbonus ? htmlspecialchars_uni ($seedbonus) : '') . '" />
		</fieldset>
	</td>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['invites'] . '</legend>
			<input type="text" size="30" name="invites" value="' . ($invites ? htmlspecialchars_uni ($invites) : '') . '" />
		</fieldset>
	</td>	
</tr>
<tr>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['uploaded'] . '</legend>
			<input type="text" size="30" name="uploaded" value="' . ($uploaded ? htmlspecialchars_uni ($uploaded) : '') . '" />
		</fieldset>
	</td>
	<td class="none">
		<fieldset>					
			<legend>' . $lang->adduser['downloaded'] . '</legend>
			<input type="text" size="30" name="downloaded" value="' . ($downloaded ? htmlspecialchars_uni ($downloaded) : '') . '" />
		</fieldset>
	</td>	
</tr>
<tr>
	<td class="none">
		<fieldset>
			<legend>' . $lang->adduser['options'] . '</legend>
			<input type="checkbox" name="confirm" class="inlineimg" value="yes"' . ($confirm == 'yes' ? ' checked="checked"' : '') . ' /> ' . $lang->adduser['o1'] . '			
		</fieldset>
	</td>
	<td class="none">
		<fieldset>
			<legend>' . $lang->adduser['title'] . '</legend>
			<input type="submit" value="' . $lang->adduser['title'] . '" />
		</fieldset>
	</td>
</tr>
';
  _form_header_close_ ();
  echo '</form>';
  stdfoot ();
?>
