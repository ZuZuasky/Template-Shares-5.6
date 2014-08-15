<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_ipban_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM ipbans');
    $_ucache = mysql_fetch_assoc ($query);
    $content = var_export ($_ucache, true);
    $_filename = TSDIR . '/' . $cache . '/ipbans.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#6 - Do Not Alter
 * Cache Name: IPBans
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$ipbanscache = ' . $content . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('IPBANVERSION', 'Manage Banned Ip\'s and Hosts v0.5 by xam');
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'showlist'));
  $allowed_actions = array ('showlist', 'modify');
  if (!in_array ($action, $allowed_actions, 1))
  {
    $action = 'showlist';
  }

  if ($action == 'modify')
  {
    $value = htmlspecialchars (trim ($_POST['value']));
    $date = sqlesc (get_date_time ());
    $modifier = (int)$CURUSER['id'];
    sql_query ('UPDATE ipbans SET value=' . sqlesc ($value) . ('' . ', date=' . $date . ', modifier=') . sqlesc ($modifier));
    update_ipban_cache ();
    redirect ($_this_script_, 'Ipban table has been updated...');
    return 1;
  }

  if ($action == 'showlist')
  {
    stdhead ('' . 'Manage Banned Ip\'s and Hosts :: ' . $action . ' ::');
    $info = '<b><font color=red>Enter a list of banned ip/host addresses (<u>separated by spaces</u>)<br />You can ban entire subnets (0-255) by using wildcard characters (192.168.0.*, 192.168.*.*, aol.*, aol.com) or enter a full ip/host address.</b></font>';
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">' . IPBANVERSION . '</td></tr>';
    echo '<tr><td class=subheader align=left>Banned IP/HOST Addresses (separated by spaces)</td><td class=subheader align=center>Last Modifier</td></tr>';
    echo '<form method=post action="' . $_this_script_ . '">';
    echo '<input type=hidden name=action value=modify>';
    $res = sql_query ('SELECT * FROM ipbans');
    if (mysql_num_rows ($res) == 0)
    {
      echo '<tr><td colspan=3>Nothing Found</td></tr>';
      echo '</table>';
      stdfoot ();
      exit ();
    }

    while ($arr = mysql_fetch_array ($res))
    {
      $getusername = sql_query ('SELECT id,username FROM users WHERE id=' . sqlesc ($arr['modifier']));
      $results = mysql_fetch_array ($getusername);
      echo '<tr><td align=left><textarea name=value rows=30 id=specialboxta>' . $arr['value'] . '</textarea></td><td align=center><a href=userdetails.php?id=' . $results['id'] . '>' . $results['username'] . '</a><br />' . $arr['date'] . '</td></tr>';
    }

    echo '<tr><td colspan=1>' . $info . '</td><td colspan=2 align=center><input type=submit name=modify value=\'Modify\' class=button> <input type=reset name=reset value=\'Reset\' class=button></td></tr>';
    echo '</form></table></table>';
    stdfoot ();
  }

?>
