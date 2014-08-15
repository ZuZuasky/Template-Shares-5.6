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

  define ('DR_VERSION', '0.4 by xam');
  $action = htmlspecialchars ($_POST['action']);
  if ($action = (('deletereports' AND !empty ($_POST['delreports'])) AND is_array ($_POST['delreports'])))
  {
    ($res = sql_query ('SELECT id FROM reports WHERE id IN (' . implode (', ', $_POST['delreports']) . ')') OR sqlerr (__FILE__, 22));
    while ($arr = mysql_fetch_array ($res))
    {
      (sql_query ('DELETE from reports WHERE id = ' . (int)$arr['id']) OR sqlerr (__FILE__, 24));
    }
  }

  stdhead ('Delete Reports');
  $type = $_GET['type'];
  if ($type == 'user')
  {
    $where = ' WHERE type = \'user\'';
  }
  else
  {
    if ($type == 'torrent')
    {
      $where = ' WHERE type = \'torrent\'';
    }
    else
    {
      if ($type == 'comment')
      {
        $where = ' WHERE type = \'comment\'';
      }
      else
      {
        if ($type == 'forumpost')
        {
          $where = ' WHERE type = \'forumpost\'';
        }
        else
        {
          if ($type == 'visitormsg')
          {
            $where = ' WHERE type = \'visitormsg\'';
          }
          else
          {
            $where = '';
          }
        }
      }
    }
  }

  ($res = sql_query ('' . 'SELECT count(id) FROM reports ' . $where) OR sqlerr (__FILE__, 42));
  $row = mysql_fetch_array ($res);
  $count = $row[0];
  $perpage = $ts_perpage;
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $_this_script_ . '&type=' . htmlspecialchars ($_GET['type']) . '&');
  echo $pagerbottom;
  _form_header_open_ ('Delete Reports');
  print '<table border=1 cellspacing=0 cellpadding=5 align=center width=100%>
';
  print '<tr><td class=subheader align=center>Added</td><td class=subheader align=center>Reported by</td><td class=subheader align=center>Reporting</td><td class=subheader align=center>Type</td><td class=subheader align=center>Reason</td><td class=subheader align=center>Dealt With</td><td class=subheader align=center>Del</td></tr>';
  print '<form method=post action=\'' . $_this_script_ . '\'><input type=hidden name=action value=deletereports>';
  ($res = sql_query ('' . 'SELECT reports.id, reports.dealtwith, reports.dealtby, reports.addedby, reports.votedfor, reports.votedfor_xtra, reports.reason, reports.type, reports.added, users.username FROM reports INNER JOIN users on reports.addedby = users.id ' . $where . ' ORDER BY id desc ' . $limit) OR sqlerr (__FILE__, 56));
  if (mysql_num_rows ($res) == 0)
  {
    echo '<tr><td colspan=7>There is no report to delete.</td></tr>';
  }
  else
  {
    require INC_PATH . '/functions_find_post.php';
    while ($arr = mysql_fetch_array ($res))
    {
      if ($arr['dealtwith'])
      {
        ($res3 = sql_query ('' . 'SELECT username FROM users WHERE id=' . $arr['dealtby']) OR sqlerr (__FILE__, 66));
        $arr3 = mysql_fetch_array ($res3);
        $dealtwith = '' . '<font color=green><b>Yes - <a href=' . $BASEURL . '/userdetails.php?id=' . $arr['dealtby'] . '><b>' . $arr3['username'] . '</b></a></b></font>';
      }
      else
      {
        $dealtwith = '<font color=red><b>No</b></font>';
      }

      if ($arr['type'] == 'user')
      {
        $type = 'userdetails';
        ($res2 = sql_query ('' . 'SELECT username FROM users WHERE id=' . $arr['votedfor']) OR sqlerr (__FILE__, 75));
        $arr2 = mysql_fetch_array ($res2);
        $name = $arr2[username];
      }
      else
      {
        if ($arr['type'] == 'torrent')
        {
          $type = 'details';
          ($res2 = sql_query ('' . 'SELECT name FROM torrents WHERE id=' . $arr['votedfor']) OR sqlerr (__FILE__, 82));
          $arr2 = mysql_fetch_array ($res2);
          $name = $arr2[name];
          if ($name == '')
          {
            $name = '<b>[Deleted]</b>';
          }
        }
        else
        {
          if ($arr['type'] == 'comment')
          {
            $type = 'details';
            ($res2 = sql_query ('' . 'SELECT torrent, user FROM comments WHERE id=' . $arr['votedfor']) OR sqlerr (__FILE__, 91));
            $arr2 = mysql_fetch_array ($res2);
            $torrent = $arr2['torrent'];
            $user_id = $arr2['user'];
            ($res_tn = sql_query ('' . 'SELECT name FROM torrents WHERE id=' . $torrent) OR sqlerr (__FILE__, 97));
            $arr_tn = mysql_fetch_array ($res_tn);
            $torrent_name = $arr_tn[name];
            ($res_usr = sql_query ('' . 'SELECT username FROM users WHERE id=' . $user_id) OR sqlerr (__FILE__, 102));
            $arr_usr = mysql_fetch_array ($res_usr);
            $comment_username = $arr_usr['username'];
          }
          else
          {
            if ($arr['type'] == 'forumpost')
            {
              $type = 'showthread';
              $res2 = sql_query ('SELECT subject FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($arr['votedfor']));
              $arr2 = mysql_fetch_assoc ($res2);
              $name = htmlspecialchars_uni (ts_remove_badwords ($arr2['subject']));
              $postlink = find_post ($arr['votedfor']);
            }
            else
            {
              if ($arr['type'] == 'visitormsg')
              {
                $type = 'userdetails';
                ($query = sql_query ('SELECT v.visitorid, u.username FROM ts_visitor_messages v LEFT JOIN users u ON (v.visitorid=u.id) WHERE v.id = ' . sqlesc ($arr['votedfor_xtra'])) OR sqlerr (__FILE__, 116));
                $name = mysql_result ($query, 0, 'u.username');
              }
            }
          }
        }
      }

      if ($arr['type'] == 'comment')
      {
        print '' . '<tr><td align="center">' . $arr['added'] . '</td><td align="center"><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align="center"><a href=' . $BASEURL . '/' . $type . '.php?id=' . $torrent . '&tab=comments&viewcomm=' . $arr['votedfor'] . '#cid' . $arr['votedfor'] . '><b>' . $comment_username . '</b></a></td><td align="center">' . $arr['type'] . '</td><td align="center"><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align="center">' . $dealtwith . '</td><td align="center"><input type="checkbox" name="delreports[]" value="' . $arr['id'] . '" /></td></tr>
';
        continue;
      }
      else
      {
        if ($arr['type'] == 'forumpost')
        {
          print '' . '<tr><td align="center">' . $arr['added'] . '</td><td align="center"><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align="center"><a href=' . $postlink . '><b>' . $name . '</b></a></td><td align="center">' . $arr['type'] . '</td><td align="center"><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align="center">' . $dealtwith . '</td><td align="center"><input type="checkbox" name="delreports[]" value="' . $arr['id'] . '" /></td></tr>
';
          continue;
        }
        else
        {
          if ($arr['type'] == 'visitormsg')
          {
            print '' . '<tr><td align="center">' . $arr['added'] . '</td><td align="center"><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align="center"><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr['votedfor'] . '&vmsg_id=' . $arr['votedfor_xtra'] . '#msg' . $arr['votedfor_xtra'] . '><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="delreports[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
            continue;
          }
          else
          {
            print '' . '<tr><td align="center">' . $arr['added'] . '</td><td align="center"><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align="center"><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr['votedfor'] . '><b>' . $name . '</b></a></td><td align="center">' . $arr['type'] . '</td><td align="center"><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align="center">' . $dealtwith . '</td><td align="center"><input type="checkbox" name="delreports[]" value="' . $arr['id'] . '" /></td></tr>
';
            continue;
          }

          continue;
        }

        continue;
      }
    }

    print '<tr><td align=right colspan=6><input type=button class=button value="check all" onClick="this.value=check(form)"> <input type=submit class=button value="delete selected"></form></td><td align=left colspan=1><form method="get" action="' . $_this_script_no_act . '"><input type=hidden name=act value=reports> <input type="submit" value="Return" class=button /></form></td></tr>
';
  }

  print '</table>';
  _form_header_close_ ();
  stdfoot ();
?>
