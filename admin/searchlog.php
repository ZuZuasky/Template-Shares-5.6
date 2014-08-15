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

  stdhead ('Search Log Page');
  $query = htmlspecialchars (trim ($_POST['query']));
  ($res = sql_query ('SELECT * FROM sitelog WHERE txt LIKE \'%' . mysql_real_escape_string ($query) . '%\' ORDER BY txt DESC') OR sqlerr (__FILE__, 22));
  $num = mysql_num_rows ($res);
  print '<table border=1 cellspacing=0 cellpadding=5 width=100%>
';
  print '<tr><td class=tabletitle align=left>Date</td><td class=tabletitle align=left>Time</td><td class=tabletitle align=left>Event</td></tr>
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

    if (strpos ($arr['txt'], 'was added to the Request section'))
    {
      $color = 'purple';
    }

    if (strpos ($arr['txt'], 'was edited by'))
    {
      $color = 'blue';
    }

    if (strpos ($arr['txt'], 'Attempt'))
    {
      $color = 'red';
    }

    if (strpos ($arr['txt'], 'settings updated'))
    {
      $color = 'blue';
    }

    $date = substr ($arr['added'], 0, strpos ($arr['added'], ' '));
    $time = substr ($arr['added'], strpos ($arr['added'], ' ') + 1);
    print '' . '<tr class=tableb><td>' . $date . '</td><td>' . $time . '</td><td align=left><font color=\'' . $color . '\'>' . $arr['txt'] . '</font></td></tr>
';
  }

  print '' . 'Click <a href=' . $BASEURL . '/admin/index.php?act=log>here</a> to back logs.</table>';
  stdfoot ();
?>
