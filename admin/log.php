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

  stdhead ('Site Log');
  if (($_POST['clear'] == 'yes' AND $usergroups['cansettingspanel'] == 'yes'))
  {
    sql_query ('TRUNCATE TABLE sitelog');
    echo '<font color=red><strong>Log table has been cleared!</strong></font>';
  }
  else
  {
    if ((($_POST['action'] == 'delete' AND !empty ($_POST['logid'])) AND $usergroups['cansettingspanel'] == 'yes'))
    {
      (sql_query ('DELETE FROM sitelog WHERE id IN (' . implode (', ', $_POST['logid']) . ')') OR sqlerr (__FILE__, 29));
      echo '<font color=red><strong>Total ' . mysql_affected_rows () . ' log(s) has been deleted!</strong></font>';
    }
  }

  print '<table border=1 cellspacing=0 width=100% cellpadding=5>
';
  print '<tr><td class=tableb align=center><form method="post" action=' . $_this_script_no_act . '?act=searchlog>
';
  print 'Search Log: <input type="text" name="query" size="40" id=specialboxn value="' . htmlspecialchars ($searchstr) . '">
';
  print '<input type=submit value=search class=button /><br /><br /></form>';
  print '<form method=post action=' . $_this_script_ . '><input type=hidden name=clear value=yes><input type=submit value=\'clear logs\' class=button></form>
';
  print '</td></tr></table>
';
  $res = sql_query ('SELECT COUNT(*) FROM sitelog');
  $row = mysql_fetch_array ($res);
  $count = $row[0];
  $perpage = $ts_perpage;
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $_this_script_ . '&');
  ($res = sql_query ('' . 'SELECT id, added, txt FROM sitelog ORDER BY added DESC ' . $limit) OR sqlerr (__FILE__, 48));
  if (mysql_num_rows ($res) == 0)
  {
    print '<b>Log is empty</b>
';
  }
  else
  {
    echo '<form method=post action="' . $_this_script_ . '"><input type=hidden name=action value=delete>';
    print '<table border=1 cellspacing=0 cellpadding=5 width=100%>
';
    print '<tr><td class=subheader align=center>Date</td><td class=subheader align=center>Time</td><td class=subheader align=left>Event</td><td align=center class=subheader>Delete</td></tr>
';
    while ($arr = mysql_fetch_array ($res))
    {
      $color = 'black';
      if (strpos ($arr['txt'], 'was uploaded by'))
      {
        $color = 'green';
      }

      if (strpos ($arr['txt'], 'was deleted by'))
      {
        $color = 'red';
      }

      if (strpos ($arr['txt'], 'has downloaded'))
      {
        $color = 'darkred';
      }

      if (strpos ($arr['txt'], 'was added to the Request section'))
      {
        $color = 'purple';
      }

      if (strpos ($arr['txt'], 'was edited by'))
      {
        $color = 'blue';
      }

      if (strpos ($arr['txt'], 'site settings updated by'))
      {
        $color = 'darkred';
      }

      if (strpos ($arr['txt'], 'Attempt'))
      {
        $color = 'red';
      }

      if (strpos ($arr['txt'], 'unwanted'))
      {
        $color = 'red';
      }

      if ((strpos ($arr['txt'], 'has been saved') OR strpos ($arr['txt'], 'settings updated')))
      {
        $color = 'blue';
      }

      $date = substr ($arr['added'], 0, strpos ($arr['added'], ' '));
      $time = substr ($arr['added'], strpos ($arr['added'], ' ') + 1);
      print '' . '<tr class=tableb><td align=center>' . $date . '</td><td align=center>' . $time . '</td><td align=left><font color=\'' . $color . '\'>' . nl2br ($arr['txt']) . '</font></td><td align=center><input type=checkbox name=logid[] value=\'' . (int)$arr['id'] . '\'></tr>
';
    }

    echo '<tr><td colspan=4 align=right><input type=submit value="delete selected" class=button> <INPUT type="button" value="check all" onClick="this.value=check(form)" class=button></td></tr>';
    print '</form></table>';
  }

  echo $pagerbottom;
  print '<p>Times are in GMT.</p>
';
  stdfoot ();
?>
