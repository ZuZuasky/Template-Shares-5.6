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

  define ('VP_VERSION', '0.2 by xam');
  stdhead ('Peerlist');
  ($res4 = sql_query ('SELECT COUNT(*) FROM peers') OR sqlerr (__FILE__, 22));
  $row4 = mysql_fetch_array ($res4);
  $count = $row4[0];
  $peersperpage = $ts_perpage;
  _form_header_open_ ('Peerlist - <font class=small>We have ' . $count . ' peers</font>');
  list ($pagertop, $pagerbottom, $limit) = pager ($peersperpage, $count, $_this_script_ . '&');
  $sql = '' . 'SELECT p.*, t.name, u.username, g.namestyle 
		FROM peers p 
		LEFT JOIN torrents t ON (p.torrent=t.id)
		LEFT JOIN users u ON (p.userid=u.id)
		LEFT JOIN usergroups g ON (u.usergroup=g.gid)
		ORDER BY p.started DESC ' . $limit;
  ($result = sql_query ($sql) OR sqlerr (__FILE__, 35));
  if (mysql_num_rows ($result) != 0)
  {
    print '<table width=100% border=1 cellspacing=0 cellpadding=5 align=center>';
    print '<tr>';
    print '<td class=subheader align=center class="smalltext">User</td>';
    print '<td class=subheader align=center class="smalltext">Torrent</td>';
    print '<td class=subheader align=center class="smalltext">IP/PORT</td>';
    print '<td class=subheader align=center class="smalltext">Upl.</td>';
    print '<td class=subheader align=center class="smalltext">Downl.</td>';
    print '<td class=subheader align=center class="smalltext">Peer-ID</td>';
    print '<td class=subheader align=center class="smalltext">Conn.</td>';
    print '<td class=subheader align=center class="smalltext">Status</td>';
    print '<td class=subheader align=center class="smalltext">Started</td>';
    print '<td class=subheader align=center class="smalltext">Last<br />Action</td>';
    print '<td class=subheader align=center class="smalltext">Prev.<br />Action</td>';
    print '<td class=subheader align=center class="smalltext">Upload<br />Offset</td>';
    print '<td class=subheader align=center class="smalltext">Download<br />Offset</td>';
    print '<td class=subheader align=center class="smalltext">To<br />Go</td>';
    print '</tr>';
    while ($row = mysql_fetch_array ($result))
    {
      print '<tr>';
      print '<td class="smalltext"><a href="' . $BASEURL . '/userdetails.php?id=' . $row['userid'] . '">' . get_user_color (htmlspecialchars_uni ($row['username']), $row['namestyle']) . '</a></td>';
      print '<td class="smalltext"><a href="' . $BASEURL . '/details.php?id=' . $row['torrent'] . '" alt="' . htmlspecialchars_uni ($row['name']) . '" title="' . htmlspecialchars_uni ($row['name']) . '">' . htmlspecialchars_uni (cutename ($row['name'], 5)) . '</td>';
      print '<td align=center class="smalltext">' . htmlspecialchars_uni ($row['ip']) . '<br />' . htmlspecialchars_uni ($row['port']) . '</td>';
      if ($row['uploaded'] < $row['downloaded'])
      {
        print '<td align=center class="smalltext"><font color=red>' . mksize ($row['uploaded']) . '</font></td>';
      }
      else
      {
        if ($row['uploaded'] == '0')
        {
          print '<td align=center class="smalltext">' . mksize ($row['uploaded']) . '</td>';
        }
        else
        {
          print '<td align=center class="smalltext"><font color=green>' . mksize ($row['uploaded']) . '</font></td>';
        }
      }

      print '<td align=center class="smalltext">' . mksize ($row['downloaded']) . '</td>';
      print '<td align=center class="smalltext">' . $row['peer_id'] . '</td>';
      if ($row['connectable'] == 'yes')
      {
        print '<td align=center class="smalltext"><font color=green>' . $row['connectable'] . '</font></td>';
      }
      else
      {
        print '<td align=center class="smalltext"><font color=red>' . $row['connectable'] . '</font></td>';
      }

      if ($row['seeder'] == 'yes')
      {
        print '<td align=center class="smalltext"><font color=green>seed</font></td>';
      }
      else
      {
        print '<td align=center class="smalltext"><font color=red>leech</font></td>';
      }

      print '<td align=center class="smalltext">' . my_datee ($dateformat, $row['started']) . '<br />' . my_datee ($timeformat, $row['started']) . '</td>';
      print '<td align=center class="smalltext">' . my_datee ($dateformat, $row['last_action']) . '<br />' . my_datee ($timeformat, $row['last_action']) . '</td>';
      print '<td align=center class="smalltext">' . my_datee ($dateformat, $row['prev_action']) . '<br />' . my_datee ($timeformat, $row['prev_action']) . '</td>';
      print '<td align=center class="smalltext">' . mksize ($row['uploadoffset']) . '</td>';
      print '<td align=center class="smalltext">' . mksize ($row['downloadoffset']) . '</td>';
      print '<td align=center class="smalltext">' . mksize ($row['to_go']) . '</td>';
      print '</tr>';
    }

    print '</table>';
    print $pagerbottom;
  }
  else
  {
    print 'Nothing here!';
  }

  print '</td></tr></table>
';
  _form_header_close_ ();
  stdfoot ();
?>
