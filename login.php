<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  include_once INC_PATH . '/functions_security.php';
  include_once INC_PATH . '/functions_login.php';
  gzip ();
  dbconn ();
  failedloginscheck ();
  cur_user_check ();
  $lang->load ('login');
  define ('L_VERSION', '1.5.4');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  if ($do == 'activation_code')
  {
    function show_activation_errors ()
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      $activation_error = array ();
      $lang->load ('signup');
      $email = (isset ($_POST['email']) ? htmlspecialchars_uni ($_POST['email']) : '');
      require_once INC_PATH . '/functions_EmailBanned.php';
      if ((empty ($email) OR !check_email ($email)))
      {
        $activation_error[] = $lang->signup['invalidemail'];
      }
      else
      {
        if (emailbanned ($email))
        {
          $activation_error[] = $lang->signup['invalidemail2'];
        }
      }

      if (count ($activation_error) == 0)
      {
        function safe_email ($email)
        {
          return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
        }

        $email = safe_email ($email);
        $res = sql_query ('SELECT u.id, u.username, e.editsecret FROM users u LEFT JOIN ts_user_validation e ON (u.id=e.userid) WHERE u.enabled = \'yes\' AND u.status = \'pending\' AND u.email = ' . sqlesc ($email) . ' LIMIT 1');
        if (mysql_num_rows ($res) == 0)
        {
          $activation_error[] = $lang->login['resend4'];
        }
        else
        {
          ($row = @mysql_fetch_assoc ($res) OR stderr ($lang->global['error'], $lang->global['dberror']));
          $body = sprintf ($lang->signup['verifiyemailbody'], $row['username'], $BASEURL, $row['id'], md5 ($row['editsecret']), $SITENAME);
          sent_mail ($email, sprintf ($lang->signup['verifiyemailsubject'], $SITENAME), $body, 'signup', false);
          header ('' . 'Location: ' . $BASEURL . '/ok.php?type=signup&email=' . urlencode ($email));
          exit ();
        }
      }
    }

    stdhead ($lang->login['resend'], false, 'collapse');
    show_activation_errors ();
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="activation_code" />
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td align="left" class="thead" colspan="2">
				' . $lang->login['resend'] . '
			</td>
		</tr>
		<tr>
			<td align="right" width="60%">
				<b>' . sprintf ($lang->login['resend2'], $SITENAME) . '</b>
			</td>
			<td align="left" width="40%">
				<input type="text" name="email" value="" /> <input type="submit" value="' . $lang->login['resend3'] . '" />
			</td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  stdhead ($lang->login['head'], false, 'collapse');
  require_once INC_PATH . '/class_page_check.php';
  $newpage = new page_verify ();
  $newpage->create ('login');
  $username = (isset ($_GET['username']) ? htmlspecialchars_uni ($_GET['username']) : (!empty ($_COOKIE['ts_username']) ? htmlspecialchars_uni ($_COOKIE['ts_username']) : ''));
  $error = '';
  $returnto = '';
  if (!empty ($_GET['returnto']))
  {
    $returnto = urldecode ($_GET['returnto']);
    if (!isset ($_GET['nowarn']))
    {
      $error = $lang->login['loginfirst'];
    }
  }
  else
  {
    if (!empty ($_GET['error']))
    {
      if ($_GET['error'] == 1)
      {
        $error = sprintf ($lang->login['error1'], remaining ());
      }
      else
      {
        if ($_GET['error'] == 2)
        {
          $error = sprintf ($lang->global['invalidimagecode'], remaining ());
        }
        else
        {
          if ($_GET['error'] == 3)
          {
            $error = $lang->global['dontleavefieldsblank'];
          }
          else
          {
            if ($_GET['error'] == 4)
            {
              $error = sprintf ($lang->global['incorrectlogin'], '<a href="' . $BASEURL . '/recover.php">');
            }
          }
        }
      }
    }
  }

  if ($showlastxtorrents == 'multi')
  {
    $lang->load ('index');
    $extra1 = ($showimages == 'yes' ? ',torrents.t_image,' : ',torrents.added,torrents.seeders,torrents.leechers,');
    $extra2 = ($showimages == 'yes' ? ' AND torrents.t_image != \'\' ' : '');
    $colspan = ($showimages == 'yes' ? '5' : '4');
    $sql = 'SELECT torrents.id,torrents.name' . $extra1 . 'categories.vip FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.visible = \'yes\' AND torrents.banned=\'no\'' . $extra2 . 'ORDER BY added DESC LIMIT 0,' . $i_torrent_limit;
    $result = sql_query ($sql);
    if (mysql_num_rows ($result) != 0)
    {
      $showlastXtorrents = '
			<!-- begin showlastXtorrents -->
			<br />
			<script type="text/javascript">
				function borderit(which,color)
				{
					if (document.all||document.getElementById)
					{
						which.style.borderColor=color
					}
				};
			</script>
			<table border="0" cellspacing="0" cellpadding="5" width="100%">
				<tr>
					<td align="center" class="thead" colspan="' . $colspan . '">
						' . ts_collapse ('showlastXtorrents') . '
						' . $SITENAME . '&nbsp;' . sprintf ($lang->index['lasttorrents'], $i_torrent_limit) . '
					</td>
				</tr>' . ts_collapse ('showlastXtorrents', 2);
      if ($showimages != 'yes')
      {
        $showlastXtorrents .= '
						<tr>
							<td class="subheader" align="left">' . $lang->index['name'] . '</td>
							<td class="subheader" align="center">' . $lang->index['uploaddat'] . '</td>
							<td class="subheader" align="center">' . $lang->index['seeders'] . '</td>
							<td class="subheader" align="center">' . $lang->index['leechers'] . '</td>
						</tr>';
      }
      else
      {
        $showlastXtorrents .= '<tr><td align="center">';
      }

      while ($row = mysql_fetch_assoc ($result))
      {
        if (($usergroups['canviewviptorrents'] != 'yes' AND $row['vip'] == 'yes'))
        {
          continue;
        }

        $seolink = ts_seo ($row['id'], $row['name'], 's');
        $fullname = htmlspecialchars_uni ($row['name']);
        if ($showimages != 'yes')
        {
          $added = my_datee ($dateformat, $row['added']) . ' ' . my_datee ($timeformat, $row['added']);
          $showlastXtorrents .= '
					<tr>
						<td align="left">
							<a href="' . $seolink . '" alt="' . $fullname . '" title="' . $fullname . '"><b>' . cutename ($row['name'], 50) . '</b></a>
						</td>
						<td align="center">
							' . $added . '
						</td>
						<td align="center">
							' . ts_nf ($row['seeders']) . '
						</td>
						<td align="center">
							' . ts_nf ($row['leechers']) . '
						</td>
					</tr>';
          continue;
        }
        else
        {
          $showlastXtorrents .= '<span style="padding-right: 5px;"><a href="' . $seolink . '"><img src="' . htmlspecialchars_uni ($row['t_image']) . '" width="125" height="125" alt="' . $fullname . '" title="' . $fullname . '" class="borderimage" onmouseover="borderit(this,\'black\')" onmouseout="borderit(this,\'white\')" /></a></span>';
          continue;
        }
      }

      $showlastXtorrents .= ($showimages != 'yes' ? '' : '</td></tr>') . '
				</tbody>
			</table><br />
			<!-- end showlastXtorrents -->';
      echo $showlastXtorrents;
    }
  }

  if (!empty ($error))
  {
    echo '
	<table border="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">' . $lang->global['error'] . '</td>
		</tr>
		<tr>
			<td>
				' . $error . '
			</td>
		</tr>
	</table>
	<br />';
  }

  echo '
<script type="text/javascript">
	function reload ()
	{
		TSGetID(\'regimage\').src = "' . $BASEURL . '/include/class_tscaptcha.php?" + (new Date()).getTime();
		return;
	};
</script>
<form method="post" action="takelogin.php">
<table border="0" cellpadding="5" width="100%">
<tr><td colspan="2" class="thead" align="center">
' . $SITENAME . ' ' . $lang->login['head'] . '
</td></tr>
<tr>
<td class="rowhead">' . $lang->login['username'] . '</td>
<td align="left"><input type="text" name="username" class="inputUsername" value="' . $username . '" /></td>
</tr>
<tr>
<td class="rowhead">' . $lang->login['password'] . '</td>
<td align="left"><input type="password" name="password" class="inputPassword" value="" /></td>
</tr>';
  define ('SKIP_RELOAD_CODE', true);
  show_image_code ();
  if ($securelogin == 'yes')
  {
    $sec = 'checked="checked" disabled="disabled" /';
  }
  else
  {
    if ($securelogin == 'no')
    {
      $sec = 'disabled="disabled" /';
    }
    else
    {
      if ($securelogin == 'op')
      {
        $sec = ' /';
      }
    }
  }

  echo '
<tr><td class="rowhead"><input type="checkbox" class="none" name="logout" style="vertical-align: middle;" value="yes" />' . $lang->login['logout15'] . '
<input type="checkbox" class="none" name="logintype" style="vertical-align: middle;" value="yes" ' . $sec . '>' . $lang->login['securelogin'] . '</td>
<td align="left"><input type="submit" value="' . $lang->login['login'] . '" /> <input type="reset" value="' . $lang->login['reset'] . '" /></td></tr>
';
  if (!empty ($returnto))
  {
    print '<input type="hidden" name="returnto" value="' . htmlspecialchars_uni ($returnto) . '" />
';
  }

  echo '
</table></form>
' . $lang->login['footer'];
  stdfoot ();
?>
