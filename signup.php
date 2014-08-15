<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require 'global.php';
  dbconn ();
  gzip ();
  require INC_PATH . '/readconfig_signup.php';
  require INC_PATH . '/functions_security.php';
  require INC_PATH . '/functions_login.php';
  failedloginscheck ('Signup');
  cur_user_check ();
  define ('S_VERSION', '2.5.4 ');
  $lang->load ('signup');
  $type = (isset ($_POST['type']) ? htmlspecialchars_uni ($_POST['type']) : (isset ($_GET['type']) ? htmlspecialchars_uni ($_GET['type']) : ''));
  $referrer = (isset ($_POST['referrer']) ? htmlspecialchars_uni ($_POST['referrer']) : (isset ($_GET['referrer']) ? htmlspecialchars_uni ($_GET['referrer']) : ''));
  $ip = getip ();
  if (!empty ($badcountries))
  {
    @require INC_PATH . '/function_country.php';
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

    registration_check ('invitesystem', false, true);
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
      }
    }
  }
  else
  {
    registration_check ('normal');
    $hidden_fields = '';
  }

  if ($pd == 'yes')
  {
    include INC_PATH . '/proxydetector.php';
    checkforproxy ($ip);
  }

  stdhead ($lang->signup['registration'], false);
  if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
  {
    require TSDIR . '/iv/iv.php';
  }

  $str = '
<script type="text/javascript" src="' . $BASEURL . '/scripts/signup.js?v=' . O_SCRIPT_VERSION . '"></script>
<script type="text/javascript" src="' . $BASEURL . '/scripts/ts_ajax.js?v=' . O_SCRIPT_VERSION . '"></script>';
  if ((!empty ($_GET['error']) OR !empty ($_GET['msg'])))
  {
    $error = '<tr><td colspan="2"><div class="error">' . (!empty ($_GET['msg']) ? htmlspecialchars_uni (base64_decode ($_GET['msg'])) : sprintf ($lang->global['invalidimagecode'], '--')) . '</div></td></tr>';
  }

  $str .= '
<form method="post" action="takesignup.php" name="signup" onsubmit="return validatesignup()">
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<tr><td colspan="2" align="center" class="thead">' . $lang->signup['registration'] . '</td></tr>';
  $str .= $hidden_fields;
  if (isset ($error))
  {
    $str .= $error;
  }

  $str .= '<tr><td class="rowhead">' . $lang->signup['username'] . '</td><td align="left">';
  $str .= '<input type="text" size="40" name="wantusername" id="wantusername" class="inputUsername" value="' . (isset ($_GET['wantusername']) ? htmlspecialchars_uni ($_GET['wantusername']) : '') . '" /> <input type="button" value="' . $lang->signup['checkavailability'] . '" onclick="javascript:ts_get(\'wantusername\',\'username\',\'ts_ajax.php\',\'previewusername\',\'loading-layer\');">
<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>' . $lang->global['loading'] . '</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>';
  $str .= '<span id="username"></span> <span name="previewusername" id="previewusername" align="left"></span><br />';
  $str .= '<font class=small>' . $lang->signup['allowedchars'] . '</font></td></tr>';
  $str .= '<tr><td class="rowhead">' . $lang->signup['ps'] . '</td><td align="left"><img src="' . $BASEURL . '/' . $pic_base_url . 'ps/tooshort.jpg" id="strength" alt="" /></td></tr>';
  $str .= '<tr><td class="rowhead">' . $lang->signup['pap'] . '</td><td align="left">';
  $str .= '<input onkeyup="updatestrength( this.value );" type="password" name="wantpassword" id="password" id="specialboxn" size="40" class="inputPassword" /> <span id="pass1"></span></td></tr>';
  $str .= '<tr><td class="rowhead">' . $lang->signup['papr'] . '</td><td align="left">';
  $str .= '<input type="password" size="40" name="passagain" class="inputPassword" /> <span id="pass2"></span>
</td></tr>';
  if ($r_secretquestion == 'yes')
  {
    $question = array ('1' => $lang->signup['hr0'], '2' => $lang->signup['hr1'], '3' => $lang->signup['hr2']);
    $str .= '<tr><td class="rowhead">' . $lang->signup['sq'] . '</td><td><select name="passhint">';
    foreach ($question as $v => $q)
    {
      $str .= '<option value="' . $v . '"' . ((isset ($_GET['passhint']) AND $_GET['passhint'] == $v) ? ' selected="selected"' : '') . '>' . $q . '</option>';
    }

    $str .= '</select></td></tr>';
    $str .= '<tr><td class="rowhead">' . $lang->signup['ha'] . '</td><td align="left"><input type="text" size="40" name="hintanswer" class="inputPassword" value="' . (isset ($_GET['hintanswer']) ? htmlspecialchars_uni ($_GET['hintanswer']) : '') . '" /> <span id="hanswer"></span><br />';
    $str .= '<font class="small">' . $lang->signup['hainfo'] . '</font></td></tr>';
  }

  echo $str;
  show_image_code ();
  $str2 = '<tr><td class="rowhead">' . $lang->signup['email'] . '</td><td align="left"><div id=\'loading-layer2\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>' . $lang->global['loading'] . '</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div><input type="text" id="email" size="40" name="email" class="inputUsername" value="' . (isset ($_GET['email']) ? htmlspecialchars_uni ($_GET['email']) : '') . '" /> <input type="button" value="' . $lang->signup['checkavailability'] . '" onclick="javascript:ts_get(\'email\',\'email\',\'ts_ajax.php\',\'previewemail\',\'loading-layer2\');"> <span id="useremail"></span> <span name="previewemail" id="previewemail" align="left"></span><br /><font class=small>' . $lang->signup['emailinfo'] . '</font></td></tr>';
  if ($r_referrer == 'yes')
  {
    $str2 .= '<tr><td class="rowhead">' . $lang->signup['referrer'] . '</td><td align="left"><input type="text" size="40" name="referrer" value="' . $referrer . '" class="inputUsername" />';
  }

  if ($r_country == 'yes')
  {
    $countries = '<option value="72">---- None selected ----</option>';
    ($ct_r = sql_query ('SELECT id,name FROM countries ORDER BY name') OR sqlerr (__FILE__, 149));
    while ($ct_a = mysql_fetch_array ($ct_r))
    {
      $countries .= '<option value="' . $ct_a['id'] . '"' . ((isset ($_GET['country']) AND $_GET['country'] == $ct_a['id']) ? ' selected="selected"' : '') . '>' . $ct_a['name'] . '</option>';
    }

    $str2 .= tr ($lang->signup['country'], '<select name="country">' . $countries . '</select>', 1);
  }

  if ($r_timezone == 'yes')
  {
    $str2 .= '<tr><td class="rowhead">' . $lang->signup['tzsetting'] . '</td><td align="left">';
    require INC_PATH . '/functions_timezone.php';
    $str2 .= show_timezone ($timezoneoffset, 1) . '</td></tr>';
  }

  if ($r_bday == 'yes')
  {
    if (isset ($_GET['dof']))
    {
      $selecteds = explode (',', $_GET['dof']);
    }

    $lang->load ('usercp');
    $months = explode (',', $lang->usercp['dob5']);
    $days = 31;
    $displaydays = '
	<select name="day"><option value="-1">--------</option>';
    $i = 1;
    while ($i <= $days)
    {
      $displaydays .= '<option value="' . $i . '"' . ((isset ($selecteds[0]) AND $selecteds[0] == $i) ? ' selected="selected"' : '') . '>' . $i . '</option>';
      ++$i;
    }

    $displaydays .= '</select>';
    $displaymonths = '
	<select name="month"><option value="-1">--------</option>';
    $first = 1;
    foreach ($months as $left => $right)
    {
      $displaymonths .= '
		<option value="' . $first . '"' . ((isset ($selecteds[1]) AND $selecteds[1] == $first) ? ' selected="selected"' : '') . '>' . $right . '</option>';
      ++$first;
    }

    $displaymonths .= '</select>';
    $year = ' <input type="text" name="year" value="' . (isset ($selecteds[2]) ? htmlspecialchars_uni ($selecteds[2]) : '') . '" size="4">';
    $str2 .= '<tr><td class="rowhead">' . $lang->usercp['dob1'] . '</td><td align="left">' . $displaydays . $displaymonths . $year . '</td></tr>';
  }

  if ($r_gender == 'yes')
  {
    $str2 .= '<tr><td class="rowhead">' . $lang->signup['gender'] . '</td><td align="left">';
    $str2 .= '<input type="radio" name="gender" value="male"' . ((isset ($_GET['gender']) AND $_GET['gender'] == 'male') ? ' checked="checked"' : (empty ($_GET['gender']) ? ' checked="checked"' : '')) . '>' . $lang->signup['male'] . ' <input type="radio" name="gender" value="female"' . ((isset ($_GET['gender']) AND $_GET['gender'] == 'female') ? ' checked="checked"' : '') . '>' . $lang->signup['female'] . ' </td></tr>';
  }

  if ($r_verification == 'yes')
  {
    $str2 .= '<tr><td class="rowhead">' . $lang->signup['verification'] . '</td><td align="left">';
    $str2 .= $lang->signup['verification2'];
  }

  $str2 .= '<input type="hidden" name="hash" value="' . (isset ($code) ? $code : '') . '">';
  $str2 .= '<tr><td colspan="2" align="center"><font color="red"><b>' . $lang->global['allfieldsrequired'] . '</b><p></font><input name="submit" type="submit" value="' . $lang->signup['signup'] . '"></td></tr></table></form>';
  echo $str2;
  stdfoot ();
?>
