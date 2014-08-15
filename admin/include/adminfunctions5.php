<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function check_pincode ($area = 1, $maxfails = 5)
  {
    global $BASEURL;
    global $pic_base_url;
    global $CURUSER;
    global $act;
    global $action;
    global $sechash;
    global $vkeyword;
    if ((isset ($_SESSION['wpincode']) AND $maxfails <= $_SESSION['wpincode']))
    {
      sql_query ('UPDATE users set enabled = \'no\', usergroup = \'' . UC_BANNED . '\' WHERE id = ' . sqlesc ($CURUSER['id']));
      stderr ('Error', 'Access Denied! You have been exceed your max pincode attempts therefore we have been disabled your account.');
      exit ();
    }

    $query = sql_query ('SELECT sechash, pincode FROM pincode WHERE area = 1') OR sqlerr (__FILE__, 36);
    $sechash = mysql_result ($query, 0, 'sechash');
    $pincode = mysql_result ($query, 0, 'pincode');
    if ($area == 2)
    {
      $action = $act;
      $return = 'admin/index.php';
      $session = 'pincode_staff';
      $what = 'Staff Panel';
    }
    else
    {
      $return = 'admin/settings.php?action=showmenu';
      $session = 'pincode_settings';
      $what = 'Setting Panel';
    }

    if ($action == 'checkpincode')
    {
      $userpincode = trim ($_POST['pincode']);
      $userpincode = md5 (md5 ($sechash) . md5 ($userpincode));
      if ((((($userpincode != $pincode OR empty ($userpincode)) OR empty ($pincode)) OR strlen ($userpincode) != 32) OR strlen ($pincode) != 32))
      {
        if (isset ($_SESSION['wpincode']))
        {
          ++$_SESSION['wpincode'];
        }
        else
        {
          session_register ('wpincode');
          ++$_SESSION['wpincode'];
        }

        unset ($_SESSION['' . $session]);
        $action = 'pincode';
        $error = true;
      }
      else
      {
        $error = false;
        unset ($_SESSION[wpincode]);
        $_SESSION['' . $session] = $userpincode;
        redirect ((!empty ($_POST['return']) ? $_POST['return'] : $return), 'You have been logged.. Thank you..', NULL, 3, false, false);
        exit ();
      }
    }

    if ((((($error OR empty ($_SESSION['' . $session])) OR $_SESSION['' . $session] != $pincode) OR $action == 'pincode') OR strlen ($_SESSION['' . $session]) != 32))
    {
      stdhead ('Website Settings by xam ' . S_VERSION . ' - Check Pincode');
      if ($error)
      {
        if (!empty ($_POST['return']))
        {
          $_SERVER['REQUEST_URI'] = $_POST['return'];
        }

        echo '			 
			<table width="100%" border="0" class="none" style="clear: both;" cellpadding="4" cellspacing="0">
				<tr><td class="thead">An error has occcured!</td></tr>
				<tr><td><font color="red"><strong>Invalid Pincode</strong></font></td></tr>
			</table>
			<br />';
      }

      if ($vkeyword == 'yes')
      {
        echo '
			<script type="text/javascript">
				function showkwmessage()
				{				
					document.getElementById(\'hiddenmsg\').innerHTML=" <img src=\\""+dimagedir+"error.gif\\" border=\\"0\\" class=\\"inlineimg\\"><span style=\\"bordercolor:black;color:red;\\"><b>Please use Virtual Keyword to enter your Pincode!</b></span>";
				}
			</script>
			<script type="text/javascript" src="' . $BASEURL . '/scripts/keyboard.js?v=' . O_SCRIPT_VERSION . '" charset="UTF-8"></script>
			<link rel="stylesheet" type="text/css" href="' . $BASEURL . '/scripts/keyboard.css">';
        $isvkeywordenabled = ' class="keyboardInput" onkeypress="showkwmessage();return false;"';
      }

      echo '
		<table border="1" cellspacing="0" cellpadding="5" width="100%" align="center">
			<tr>
				<td colspan="2" align="left" class="thead">
					Security Check
				</td>
			</tr>

			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
			<input type="hidden" name="action" value="checkpincode">
			<input type="hidden" name="act" value="checkpincode">
			<input type="hidden" name="return" value="' . htmlspecialchars_uni ($_SERVER['REQUEST_URI']) . '">
			<tr>
				<td class="tcat" align="left">
					Please Enter ' . $what . ' Pincode: 			
					<input type="password" name="pincode" value="" id="specialboxn"' . $isvkeywordenabled . '> 
					<input type="submit" name="submit" value="check pincode" class=button> <span id="hiddenmsg"> </span>
				</td>
			</tr>
			</form>
		</table>';
      stdfoot ();
      exit ();
    }

  }

  define ('_AF____5', true);
  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
?>
