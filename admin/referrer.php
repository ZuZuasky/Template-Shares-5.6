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

  define ('NcodeImageResizer', true);
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'showlist'));
  $allowed_actions = array ('showlist', 'delete');
  if (!in_array ($action, $allowed_actions))
  {
    $action = 'showlist';
  }

  if ($action == 'showlist')
  {
    ($res = sql_query ('SELECT COUNT(referrer_url) FROM referrer') OR sqlerr (__FILE__, 25));
    $arr = mysql_fetch_row ($res);
    $countrows = $arr[0];
    $orderby = 'referrer_url ASC';
    $page = 0 + $_GET['page'];
    $perpage = $ts_perpage;
    list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $countrows, $_this_script_ . '&order=' . $order . '&');
    stdhead ('Referrer List v.04 by xam.');
    print '<h2>Referrer List</h2>';
    print '<table border=1 cellspacing=0 cellpadding=5 align=center width=100%>
';
    print '' . '<form method=post action=' . $_this_script_ . ' name=referrer><input type=hidden name=action value=delete>';
    print '<tr><td class=colhead align=left><a href=referrer.php?order=url>URL</a></td><td class=colhead align=center><INPUT type="button" value="Check all" class=button onClick="this.value=check(form)">
</td></tr>';
    ($query = sql_query ('' . 'SELECT * FROM referrer ORDER BY ' . $orderby . ' ' . $limit) OR sqlerr (__FILE__, 41));
    if (mysql_num_rows ($query) == '0')
    {
      print '<tr><td colspan=3 align=left>Nothing found!</td></tr></table</form>';
    }
    else
    {
      while ($arr = mysql_fetch_array ($query))
      {
        print '<tr><td align=left>' . ($arr['referrer_url'] ? format_comment ($arr['referrer_url']) : '<font color=red>No referrer detected.</font>') . '</td><td align=center><INPUT type="checkbox" name="url[]" value="' . $arr['referrer_url'] . '"></td></tr>';
      }

      print '<tr><td colspan=3 align=right><input type=submit name=submit class=button value=\'Delete selected\'></td></tr>';
      print '</form></table><br />';
      echo '<tr><td colspan=2>' . $pagerbottom . '</td></tr>';
    }

    stdfoot ();
    return 1;
  }

  if ($action == 'delete')
  {
    $url = array ();
    $url = $_POST['url'];
    foreach ($url as $key => $u)
    {
      (sql_query ('DELETE FROM referrer WHERE referrer_url=' . sqlesc ($u)) OR sqlerr (__FILE__, 56));
    }

    if (@mysql_affected_rows () == 0)
    {
      stderr ('Error', 'URL\'s couldn\'t be deleted! ');
      return 1;
    }

    header ('' . 'Location: ' . $_this_script_);
    exit ();
  }

?>
