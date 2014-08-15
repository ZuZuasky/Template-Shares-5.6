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

  define ('ST_VERSION', '0.4 by xam');
  stdhead ('All snatched torrents');
  $count1 = number_format (tsrowcount ('id', 'snatched'));
  _form_header_open_ ('All snatched torrents (<font class=small>We have ' . $count1 . ' snatched torrents</font>)');
  print '<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text align=center>
';
  ($res1 = sql_query ('' . 'SELECT COUNT(*) FROM snatched ' . $limit) OR sqlerr (__FILE__, 25));
  $row1 = mysql_fetch_array ($res1);
  $count = $row1[0];
  $torrentsperpage = ($CURUSER['torrentsperpage'] != 0 ? intval ($CURUSER['torrentsperpage']) : $ts_perpage);
  list ($pagertop, $pagerbottom, $limit) = pager ($torrentsperpage, $count, $_this_script_ . '&');
  print '' . $pagertop;
  $sql = '' . 'SELECT s.*, t.name, u.username as uname, u.id as uid, g.namestyle FROM snatched s LEFT JOIN torrents t ON (s.torrentid=t.id) LEFT JOIN users u ON (s.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY s.to_go DESC ' . $limit;
  $result = sql_query ($sql);
  if (mysql_num_rows ($result) != 0)
  {
    print '<table width=100% border=1 cellspacing=0 cellpadding=5 align=center class=mainouter>';
    print '<tr>';
    print '<td class=subheader align=center>User</td>';
    print '<td class=subheader align=center>Torrentname</td>';
    print '<td class=subheader align=center>Uploaded</td>';
    print '<td class=subheader align=center>downloaded</td>';
    print '<td class=subheader align=center>Startdat</td>';
    print '<td class=subheader align=center>Completedat</td>';
    print '<td class=subheader align=center>Seeding</td>';
    print '</tr>';
    while ($row = mysql_fetch_array ($result))
    {
      print '<tr><td><a href="' . $BASEURL . '/userdetails.php?id=' . $row['uid'] . '"><b>' . get_user_color ($row['uname'], $row['namestyle']) . '</b></a></td>';
      print '<td><a href="' . $BASEURL . '/details.php?id=' . $row['torrentid'] . '"><b>' . cutename ($row['name']) . '</b></a></td>';
      print '<td align=center><b>' . mksize ($row['uploaded']) . '</b></td>';
      print '<td align=center><b>' . mksize ($row['downloaded']) . '</b></td>';
      print '<td align=center><b>' . $row['startdat'] . '</b></td>';
      if (0 < $row['completedat'])
      {
        print '<td align=center><b>' . $row['completedat'] . '</b></td>';
      }
      else
      {
        print '<td align=center><b><font color=red>Not completed</font></b></td>';
      }

      print '<td align=center><b>' . ($row['seeder'] == 'yes' ? '<font color=green>YES</font>' : '<font color=red>NO</font>') . '</b></td>';
      print '</tr>';
    }

    print '</table>';
  }
  else
  {
    print 'Nothing here.';
  }

  print '' . $pagerbottom;
  print '</td></tr></table>
';
  _form_header_close_ ();
  stdfoot ();
?>
