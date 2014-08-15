<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function validusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  function username_exists ($username)
  {
    $tracker_query = sql_query ('SELECT username FROM users WHERE username=' . sqlesc ($username) . ' LIMIT 1');
    if (1 <= mysql_num_rows ($tracker_query))
    {
      return false;
    }

    return true;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('CU_VERSION', '0.3 by xam');
  if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
  {
    if ((($_POST['username'] == '' OR $_POST['id'] == '') OR !is_valid_id ($_POST['id'])))
    {
      stderr ('Error', 'Missing form data.');
    }

    $sure = htmlspecialchars ($_POST['sure']);
    $id = sqlesc ((int)$_POST['id']);
    $username = $_POST['username'];
    if ((!validusername ($username) OR !username_exists ($username)))
    {
      stderr ('Error', 'Invalid Username or Username already taken.');
    }

    $username = sqlesc ($username);
    if (($sure == 'yes' AND !empty ($_POST['oldusername'])))
    {
      sql_query ('' . 'UPDATE users SET username=' . $username . ' WHERE id=' . $id);
      write_log ('' . $_POST['oldusername'] . '\'s account name has been changed to ' . $username . ' by ' . $CURUSER['username'] . ' (Change Username Tool)');
      header ('' . 'Location: ' . $BASEURL . '/userdetails.php?id=' . $id);
      exit ();
    }
    else
    {
      ($get_user = sql_query ('' . 'SELECT id,username FROM users WHERE id=' . $id) OR sqlerr (__FILE__, 57));
      $user = mysql_fetch_array ($get_user);
      if (empty ($user))
      {
        stderr ('Error', 'No user with this id!');
      }

      stdhead ('Change Username');
      echo '
		<h2>Sanity Check</h2>
		<form method=post action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type=hidden name=act value=changeusername>
		<input type=hidden name=oldusername value="' . $user['username'] . '">
		<table border=1 cellspacing=0 cellpadding=5 width=100%>
		<tr><td class=rowhead>User ID</td><td>
		<input type=text name=id value="' . $id . '" id=specialboxes> (' . $user['username'] . ')
		</td></tr>
		<tr><td class=rowhead>New Username</td><td>
		<input type=text name=username value="' . htmlspecialchars (str_replace ('\'', '', $username)) . '" id=specialboxs>
		<input type=checkbox name=sure value=yes style="vertical-align: middle;" checked> <input type=submit value="I\'m Sure Update Account" class=button>
		</td></tr>
		</table>
		</form>';
      stdfoot ();
      exit ();
    }
  }

  stdhead ('Change Username');
  _form_header_open_ ('Change Username');
  echo '
<form method=post action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type=hidden name=act value=changeusername>
<table border=1 cellspacing=0 cellpadding=5 width=100%>
<tr><td class=rowhead>User ID</td><td>
<input type=text name=id id=specialboxes>
</td></tr>
<tr><td class=rowhead>New Username</td><td>
<input type=text name=username id=specialboxs>
<input type=submit value="Update" class=button>
</td></tr>
</table>
</form>';
  _form_header_close_ ();
  stdfoot ();
?>
