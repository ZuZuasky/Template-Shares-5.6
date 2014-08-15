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

  define ('BE_VERSION', 'BAN EMAIL\'s v.05 by xam');
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'showlist'));
  if ($action == 'showlist')
  {
    stdhead (BE_VERSION . ' - Show List');
    print '<table border=1 cellspacing=0 cellpadding=5 width=100%>
';
    ($sql = sql_query ('SELECT * FROM bannedemails') OR sqlerr (__FILE__, 27));
    $list = mysql_fetch_array ($sql);
    _form_header_open_ ('Banned Emails');
    echo '<form method=post action=\'';
    echo $_SERVER['SCRIPT_NAME'];
    echo '\'>
<input type=hidden name=action value=savelist>
<input type=hidden name=act value=bannedemails>
<tr><td>Enter a list of banned email addresses (separated by spaces):<br />To ban a specific address enter "email@domain.com", to ban an entire domain enter "@domain.com" or "domain.com" or ".com"</td>
<td><textarea name="value" rows="15" cols="40" id="specialboxg">';
    echo $list[value];
    echo '</textarea>
<input type=submit value="save list" class=button></form></td>
</tr></table>
';
    _form_header_close_ ();
    stdfoot ();
    return 1;
  }

  if ($action == 'savelist')
  {
    $value = trim (htmlspecialchars ($_POST['value']));
    (sql_query ('UPDATE bannedemails SET value = ' . sqlesc ($value)) OR sqlerr (__FILE__, 43));
    redirect ('admin/index.php?act=bannedemails');
  }

?>
