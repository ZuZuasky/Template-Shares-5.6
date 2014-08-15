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

  define ('DA_VERSION', '0.2 by xam');
  if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
  {
    $username = trim ($_POST['username']);
    if (!$username)
    {
      stderr ('Error', 'Please fill out the form correctly.');
    }

    ($res = sql_query ('SELECT id FROM users WHERE username=' . sqlesc ($username)) OR sqlerr (__FILE__, 27));
    if (mysql_num_rows ($res) != 1)
    {
      stderr ('Error', 'No user with this name.');
    }

    $arr = mysql_fetch_array ($res);
    $id = (int)$arr['id'];
    ($res = sql_query ('' . 'DELETE FROM users WHERE id=' . $id . ' LIMIT 1') OR sqlerr (__FILE__, 33));
    if (mysql_affected_rows () != 1)
    {
      stderr ('Error', 'Unable to delete the account.');
    }

    require INC_PATH . '/function_log_user_deletion.php';
    log_user_deletion ('Following user has been deleted by ' . $CURUSER['username'] . ' (delactadmin Tool - Staff Panel): Userid: ' . $id);
    stderr ('Success', 'The account <b>' . htmlspecialchars_uni ($username) . '</b> was deleted.', false);
  }

  stdhead ('Delete account');
  _form_header_open_ ('Delete User Account');
  echo '<table border=1 cellspacing=0 cellpadding=5 width=100%>
<form method=post action="' . $_this_script_ . '">
<tr><td class=rowhead>User name</td><td><input size=40 name=username id=specialboxn> <input type=submit class=button value=Delete></td></tr>
</form>
</table>';
  _form_header_close_ ();
  stdfoot ();
?>
