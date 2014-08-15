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

  $action = htmlspecialchars ($_POST['action']);
  if ($action == 'update')
  {
    if ((($_POST['username'] == '' OR $_POST['uploaded'] == '') OR $_POST['downloaded'] == ''))
    {
      stderr ('Error', 'Missing form data.');
    }

    $username = sqlesc ($_POST['username']);
    $uploaded = sqlesc ($_POST['uploaded']);
    $downloaded = sqlesc ($_POST['downloaded']);
    (sql_query ('' . 'UPDATE users SET uploaded=' . $uploaded . ', downloaded=' . $downloaded . ' WHERE username=' . $username) OR sqlerr (__FILE__, 30));
    $res = sql_query ('' . 'SELECT id FROM users WHERE username=' . $username);
    $arr = mysql_fetch_row ($res);
    if (!$arr)
    {
      stderr ('Error', 'Unable to update account.');
    }

    header ('' . 'Location: ' . $BASEURL . '/userdetails.php?id=' . $arr['0']);
    exit ();
  }
  else
  {
    if ($action == 'calculate')
    {
      $value = _calculate_ ($_POST['value']);
    }
  }

  stdhead ('Update Users Ratio');
  _form_header_open_ ('Update Users Ratio');
  echo '
<form method="post" action="' . $_this_script_ . '">
<input type=hidden name=action value=update>
<table border=1 cellspacing=0 cellpadding=5 width=100%>
<tr><td class=rowhead>User name</td><td><input type=text name=username size=40 id=specialboxn></td></tr>
<tr><td class=rowhead>Uploaded</td><td><input type=uploaded name=uploaded size=40 id=specialboxn></td></tr>
<tr><td class=rowhead>Downloaded</td><td><input type=downloaded name=downloaded size=40 id=specialboxn> <input type=submit value="Update" class=button></td></tr>
</table>
</form>';
  _form_header_close_ ();
  echo '<br />';
  _form_header_open_ ('Calculate');
  echo '<form method="post" action="' . $_this_script_ . '">
<input type=hidden name=action value=calculate>';
  echo '<table border=1 cellspacing=0 cellpadding=5 width=100%>
<tr><td class=rowhead>Value</td><td><input type=text name=value size=20 id=specialboxn>
<input type=submit value="Calculate" class=button>
</td></tr>' . (isset ($value) ? '<tr><td class=rowhead>Result:</td><td>' . $value . '</tr></td>' : '') . '</table>';
  _form_header_close_ ();
  stdfoot ();
  unset ($value);
?>
