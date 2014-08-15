<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_contactus_errors ()
  {
    global $activation_error;
    global $lang;
    if (0 < count ($activation_error))
    {
      $errors = implode ('<br />', $activation_error);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  function show_process ()
  {
    global $lang;
    echo '
		<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">
				' . $lang->contactus['processheader'] . '
			</td>
		</tr>
		<tr>
			<td>
				<font color="red">
					<strong>
						' . $lang->contactus['processmessage'] . '
					</strong>
				</font>
			</td>
		</tr>
		</table>
		<br />
	';
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  $lang->load ('contactus');
  define ('CU_VERSION', '0.4');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  $activation_error = array ();
  $show_process = false;
  include_once INC_PATH . '/functions_security.php';
  if ($do == 'process')
  {
    $useremail = trim ($_POST['useremail']);
    $message = trim ($_POST['message']);
    $subject = trim ($_POST['subject']);
    $userip = getip ();
    $userbrowser = trim ($_SERVER['HTTP_USER_AGENT']);
    if (!check_email ($useremail))
    {
      $activation_error[] = $lang->contactus['invalidemail'];
    }

    if ((empty ($message) OR strlen ($message) < 10))
    {
      $activation_error[] = $lang->contactus['invalidmessage'];
    }

    if ((empty ($subject) OR strlen ($subject) < 3))
    {
      $activation_error[] = $lang->contactus['invalidsubject'];
    }

    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      $checkcode = check_code ($_POST['imagestring'], '', TRUE, '', TRUE);
      if (!$checkcode)
      {
        $activation_error[] = $lang->contactus['invalidimagecode'];
      }
    }

    if (count ($activation_error) == 0)
    {
      $emailmessage = $message . '
		_________________________________________________________________________________
		User IP: ' . htmlspecialchars_uni ($userip) . '
		User Email: ' . htmlspecialchars_uni ($useremail) . '
		User Browser: ' . htmlspecialchars_uni ($userbrowser) . '
		';
      $sm = sent_mail ($contactemail, $subject, $emailmessage, 'contactus');
      $show_process = true;
    }
  }

  stdhead ($lang->contactus['header'], false, 'collapse');
  if ($show_process)
  {
    show_process ();
    stdfoot ();
    exit ();
  }
  else
  {
    show_contactus_errors ();
  }

  define ('SKIP_RELOAD_CODE', true);
  echo '
<form method="post" name="contactus" action="' . $_SERVER['SCRIPT_NAME'] . '" onsubmit="document.contactus.cbutton.value=\'' . $lang->contactus['pleasewait'] . '\';document.contactus.cbutton.disabled=true">
<input type="hidden" name="do" value="process" />
<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td align="left" class="thead" colspan="2">
			' . $lang->contactus['header'] . '
		</td>
	</tr>
	<tr>
		<td align="right" width="20%" valign="top">
			<b>' . $lang->contactus['email'] . '</b>
		</td>
		<td align="left" width="80%" valign="top">
			<input type="text" name="useremail" value="' . htmlspecialchars_uni ($useremail) . '" size="30" />
		</td>
	</tr>
	<tr>
		<td align="right" width="20%" valign="top">
			<b>' . $lang->contactus['subject'] . '</b>
		</td>
		<td align="left" width="80%" valign="top">
			<input type="text" name="subject" value="' . htmlspecialchars_uni ($subject) . '" size="30" />
		</td>
	</tr>
	<tr>
		<td align="right" width="20%" valign="top">
			<b>' . $lang->contactus['message'] . '</b>
		</td>
		<td align="left" width="80%" valign="top">
			<textarea name="message" cols="100" rows="10">' . htmlspecialchars_uni ($message) . '</textarea>
		</td>
	</tr>';
  show_image_code ();
  echo '
	<tr>
		<td colspan="2" align="center"><input type="submit" value="' . $lang->contactus['button1'] . '" name="cbutton" /> <input type="reset" value="' . $lang->contactus['button2'] . '" /></td>
	</tr>
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
