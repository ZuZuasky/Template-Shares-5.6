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

  define ('D_VERSION', '0.2 by xam');
  include_once INC_PATH . '/readconfig_paypal.php';
  if ($_GET['total_donors'] == '1')
  {
    $res = sql_query ('SELECT COUNT(*) FROM users WHERE total_donated != \'0.00\'');
    $row = mysql_fetch_array ($res);
    $count = $row[0];
    list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&total_donors=1&');
    if (mysql_num_rows ($res) == 0)
    {
      stderr ('Sorry', 'No donors found!');
    }

    stdhead ('Donor List:: All Donations');
    begin_frame ('' . 'Donor List: All Donations [ ' . $count . ' ]', true);
    ($res = sql_query ('' . 'SELECT u.id,u.username,u.email,u.added,u.donated,u.total_donated,u.donoruntil,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE total_donated != \'0.00\' ORDER BY donoruntil DESC ' . $limit) OR print mysql_error ());
  }
  else
  {
    $res = sql_query ('SELECT COUNT(*) FROM users WHERE donor=\'yes\'');
    $row = mysql_fetch_array ($res);
    $count = $row[0];
    list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&');
    if (mysql_num_rows ($res) == 0)
    {
      stderr ('Sorry', 'No donors found!');
    }

    stdhead ('Donor List:: Current Donors');
    begin_frame ('' . 'Donor List: Current Donors [ ' . $count . ' ]', true);
    ($res = sql_query ('' . 'SELECT u.id,u.username,u.email,u.added,u.donated,u.total_donated,u.donoruntil,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE donor=\'yes\' ORDER BY donoruntil DESC ' . $limit) OR print mysql_error ());
  }

  begin_table (true);
  echo '<p align=center><a class=altlink href="' . $_this_script_ . '">Current Donors</a> || <a class=altlink href="' . $_this_script_ . '&total_donors=1">All Donations</a></p>';
  echo '<tr><td class=subheader align=left>Username</td><td class=subheader align=left>e-mail</td>' . '<td class=subheader align=left>Joined</td><td class=subheader align=left>Donor Until?</td><td class=subheader align=center>' . 'Current</td><td class=subheader align=center>Total</td><td class=subheader align=center>PM</td></tr>';
  require_once INC_PATH . '/functions_mkprettytime.php';
  while ($arr = @mysql_fetch_array ($res))
  {
    echo '<tr><td align=left><b><a class=altlink href=' . $BASEURL . '/userdetails.php?id=' . $arr['id'] . '>' . str_replace ('{username}', $arr['username'], $arr['namestyle']) . '</b>' . '</td><td align=left><a class=altlink href=\'' . $BASEURL . '/admin/index.php?act=sendmail&amp;email=' . htmlspecialchars_uni ($arr['email']) . '\'>' . htmlspecialchars_uni ($arr['email']) . '</a>' . '</td><td align=left><font size="-3">' . $arr['added'] . '</font></a>' . '</td><td align=left>';
    $donoruntil = $arr['donoruntil'];
    if ($donoruntil == '0000-00-00 00:00:00')
    {
      echo 'n/a';
    }
    else
    {
      echo '' . '<font size="-3"><p>' . $donoruntil . '<br />[ ' . mkprettytime (strtotime ($donoruntil) - gmtime ()) . ' left ]</font></p>';
    }

    echo '</td><td align=center><b>' . $arr['donated'] . ('' . ' ' . $pcc . '</b></td>') . '<td align=center><b>' . $arr['total_donated'] . ('' . ' ' . $pcc . '</b></td>') . '<td align=center><b><a class=altlink href=' . $BASEURL . '/sendmessage.php?receiver=' . $arr[id] . '>PM</a></b></td></tr>';
  }

  end_table ();
  end_frame ();
  echo $pagerbottom;
  end_main_frame ();
  stdfoot ();
  exit ();
?>
