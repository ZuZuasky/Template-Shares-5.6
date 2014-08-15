<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('PS_VERSION', 'v0.1 by xam');
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : 0));
  $error = false;
  $title = 'Search Results: ';
  stdhead ('Passkey Search');
  if ($do == 2)
  {
    $title = 'Passkey Reset: ';
    $passkey = trim (strtolower ($_GET['passkey']));
    if (empty ($passkey))
    {
      $error = 'Please enter passkey!';
    }
    else
    {
      if (strlen ($passkey) != 32)
      {
        $error = 'Invalid Passkey!';
      }
      else
      {
        sql_query ('UPDATE users SET passkey = \'\' WHERE passkey = ' . sqlesc ($passkey));
        $error = 'User passkey has been cleared.';
      }
    }
  }

  if ($do == 1)
  {
    $passkey = trim (strtolower ($_POST['passkey']));
    if (empty ($passkey))
    {
      $error = 'Please enter passkey!';
    }
    else
    {
      if (strlen ($passkey) != 32)
      {
        $error = 'Invalid Passkey!';
      }
      else
      {
        $query = sql_query ('SELECT u.*, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.passkey = ' . sqlesc ($passkey));
        if (mysql_num_rows ($query) == 0)
        {
          $error = 'There is no registered user with this passkey!';
        }
        else
        {
          $user = mysql_fetch_assoc ($query);
          if ($user['last_access'] == '0000-00-00 00:00:00')
          {
            $lastseen = 'NEVER';
          }
          else
          {
            $lastseen_date = my_datee ($dateformat, $user['last_access']);
            $lastseen_time = my_datee ($timeformat, $user['last_access']);
            $lastseen = '' . $lastseen_date . ' ' . $lastseen_time;
          }

          if ($user['added'] == '0000-00-00 00:00:00')
          {
            $joindate = 'N/A';
          }
          else
          {
            $joindate_date = my_datee ($dateformat, $user['added']);
            $joindate_time = my_datee ($timeformat, $user['added']);
            $joindate = '' . $joindate_date . ' ' . $joindate_time;
          }

          _form_header_open_ ($title . '(' . htmlspecialchars_uni ($passkey) . ')', 9);
          echo '
			<tr>
				<td class="subheader">Username</td>
				<td class="subheader">Email</td>
				<td class="subheader">IP</td>			
				<td class="subheader">Passkey</td>
				<td class="subheader">Last Seen</td>
				<td class="subheader">Registered</td>
				<td class="subheader">UP</td>
				<td class="subheader">Down</td>
				<td class="subheader">Ratio</td>
			</tr>';
          include_once INC_PATH . '/functions_ratio.php';
          echo '
			<tr>
				<td><a href="' . $BASEURL . '/userdetails.php?id=' . $user['id'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a></td>
				<td>' . htmlspecialchars_uni ($user['email']) . '</td>
				<td>' . htmlspecialchars_uni ($user['ip']) . '</td>			
				<td>' . htmlspecialchars_uni ($user['passkey']) . ' <b>[<a href="' . $_this_script_ . '&do=2&passkey=' . htmlspecialchars_uni ($user['passkey']) . '">reset</a>]</b></td>
				<td>' . $lastseen . '</td>
				<td>' . $joindate . '</td>
				<td>' . mksize ($user['uploaded']) . '</td>
				<td>' . mksize ($user['downloaded']) . '</td>
				<td>' . get_user_ratio ($user['uploaded'], $user['downloaded']) . '</td>
			</tr>';
          _form_header_close_ ();
          echo '<br />';
        }
      }
    }
  }

  if (!empty ($error))
  {
    _form_header_open_ ($title . '(' . htmlspecialchars_uni ($passkey) . ')');
    echo '<tr><td><font color="red">' . $error . '</font></td></tr>';
    _form_header_close_ ();
    echo '<br />';
  }

  _form_header_open_ ('Search Passkey');
  echo '
<tr><td>
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="passkeysearch">
		<input type="hidden" name="do" value="1">
		Passkey: <input type="text" name="passkey" value="' . htmlspecialchars_uni ($passkey) . '" size="42">  
		<input type="submit" name="submit" value="search">
</form>
</td></tr>';
  _form_header_close_ ();
  stdfoot ();
?>
