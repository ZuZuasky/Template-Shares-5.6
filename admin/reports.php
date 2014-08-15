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

  define ('R_VERSION', '0.8 by xam');
  stdhead ('Active Reports');
  _form_header_open_ ('Active Reports');
  sql_query ('DELETE FROM reports WHERE dealtwith=\'1\' AND UNIX_TIMESTAMP(added) < \'' . (TIMENOW - 172800) . '\'');
  $action = htmlspecialchars ($_GET['action']);
  $type = htmlspecialchars ($_GET['type']);
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
        if ($type == 'request')
        {
          $where = ' WHERE type = \'request\'';
        }
        else
        {
          if ($type == 'reqcomment')
          {
            $where = ' WHERE type = \'reqcomment\'';
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
    }
  }

  if (($action == 'update' AND !empty ($_POST['dealreport'])))
  {
    $res = sql_query ('SELECT id FROM reports WHERE dealtwith=0 AND id IN (' . implode (', ', $_POST['dealreport']) . ')');
    while ($arr = mysql_fetch_array ($res))
    {
      (sql_query ('UPDATE reports SET dealtwith=1, dealtby = ' . (int)$CURUSER['id'] . ' WHERE id = ' . sqlesc ($arr['id'])) OR sqlerr (__FILE__, 48));
    }
  }

  ($res = sql_query ('' . 'SELECT count(id) FROM reports ' . $where) OR sqlerr (__FILE__, 51));
  $row = mysql_fetch_array ($res);
  $count = $row[0];
  $perpage = $ts_perpage;
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $_this_script_ . '&type=' . htmlspecialchars ($_GET['type']) . '&');
  print '<table border=1 cellspacing=0 cellpadding=5 align=center width=100%>
';
  print '<tr><td class=subheader align=left>Added</td><td class=subheader align=left>Reported by</td><td class=subheader align=left>Reporting</td><td class=subheader align=left>Type</td><td class=subheader align=left>Reason</td><td class=subheader align=left>Dealt With</td><td class=subheader align=left>Dealt With</td>';
  print '<form method=post action=\'' . $_this_script_ . '&action=update\'>';
  $res = sql_query ('' . 'SELECT reports.id, reports.dealtwith, reports.dealtby, reports.addedby, reports.votedfor, reports.votedfor_xtra, reports.reason, reports.type, reports.added, users.username FROM reports INNER JOIN users on reports.addedby = users.id ' . $where . ' ORDER BY id desc ' . $limit);
  if (mysql_num_rows ($res) == 0)
  {
    echo '<tr><td colspan=7>There is no active reports.</td></tr>';
  }
  else
  {
    require INC_PATH . '/functions_find_post.php';
    while ($arr = mysql_fetch_array ($res))
    {
      if ($arr['dealtwith'])
      {
        $res3 = sql_query ('' . 'SELECT username FROM users WHERE id=' . $arr['dealtby']);
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
        $res2 = sql_query ('' . 'SELECT username FROM users WHERE id=' . $arr['votedfor']);
        $arr2 = mysql_fetch_array ($res2);
        $name = $arr2[username];
      }
      else
      {
        if ($arr['type'] == 'torrent')
        {
          $type = 'details';
          $res2 = sql_query ('' . 'SELECT name FROM torrents WHERE id=' . $arr['votedfor']);
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
            $res2 = sql_query ('' . 'SELECT torrent, user FROM comments WHERE id=' . $arr['votedfor']);
            $arr2 = mysql_fetch_array ($res2);
            $torrent = $arr2['torrent'];
            $user_id = $arr2['user'];
            $res_tn = sql_query ('' . 'SELECT name FROM torrents WHERE id=' . $torrent);
            $arr_tn = mysql_fetch_array ($res_tn);
            $torrent_name = $arr_tn[name];
            $res_usr = sql_query ('' . 'SELECT username FROM users WHERE id=' . $user_id);
            $arr_usr = mysql_fetch_array ($res_usr);
            $comment_username = $arr_usr[username];
          }
          else
          {
            if ($arr['type'] == 'request')
            {
              $type = 'viewrequests';
              $res2 = sql_query ('' . 'SELECT request FROM requests WHERE id=' . $arr['votedfor']);
              $arr2 = mysql_fetch_array ($res2);
              $name = $arr2['request'];
            }
            else
            {
              if ($arr['type'] == 'reqcomment')
              {
                $type = 'viewrequests';
                $res2 = sql_query ('' . 'SELECT user,request FROM comments WHERE id=' . $arr['votedfor'] . ' AND request > 0');
                $arr2 = mysql_fetch_array ($res2);
                $user_id = $arr2['user'];
                $res_usr = sql_query ('' . 'SELECT username FROM users WHERE id=' . $user_id);
                $arr_usr = mysql_fetch_array ($res_usr);
                $name = $arr_usr['username'];
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
                    $query = sql_query ('SELECT v.visitorid, u.username FROM ts_visitor_messages v LEFT JOIN users u ON (v.visitorid=u.id) WHERE v.id = ' . sqlesc ($arr['votedfor_xtra']));
                    $name = mysql_result ($query, 0, 'u.username');
                  }
                }
              }
            }
          }
        }
      }

      if ($arr['type'] == 'comment')
      {
        $subres = @sql_query ('' . 'SELECT COUNT(*) FROM comments WHERE torrent = ' . $torrent . ' AND id < ' . $arr['votedfor']);
        $subrow = @mysql_fetch_row ($subres);
        $count = $subrow[0];
        $comm_page = @floor ($count / $perpage) + 1;
        $page_url = '' . $BASEURL . '/' . $type . '.php?id=' . $torrent . '&tab=comments&page=' . $comm_page . '&viewcomm=' . $arr['votedfor'] . '#cid' . $arr['votedfor'];
        print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $page_url . '><b>' . $comment_username . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
        continue;
      }
      else
      {
        if ($arr['type'] == 'request')
        {
          print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr['votedfor'] . '&req_details=1><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
          continue;
        }
        else
        {
          if ($arr['type'] == 'reqcomment')
          {
            print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr2['request'] . '&req_details=1&' . $arr['votedfor'] . '#comm' . $arr['votedfor'] . '><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
            continue;
          }
          else
          {
            if ($arr['type'] == 'forumpost')
            {
              print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $postlink . '><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
              continue;
            }
            else
            {
              if ($arr['type'] == 'visitormsg')
              {
                print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr['votedfor'] . '&vmsg_id=' . $arr['votedfor_xtra'] . '#msg' . $arr['votedfor_xtra'] . '><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
                continue;
              }
              else
              {
                print '' . '<tr><td>' . $arr['added'] . '</td><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr['addedby'] . '><b>' . $arr['username'] . '</b></a></td><td align=left><a href=' . $BASEURL . '/' . $type . '.php?id=' . $arr['votedfor'] . '><b>' . $name . '</b></a></td><td align=left>' . $arr['type'] . '</td><td align=left><div style="border: thin inset ; padding: 5px; overflow: auto; width: 350px;" align="justify">' . $arr['reason'] . '</div></td><td align=left>' . $dealtwith . '</td><td><input type="checkbox" name="dealreport[]" value="' . $arr['id'] . '" ' . ($arr['dealtwith'] == 1 ? 'CHECKED' : '') . '/></td></tr>
';
                continue;
              }

              continue;
            }

            continue;
          }

          continue;
        }

        continue;
      }
    }

    echo ' 
	<tr><td colspan="7" align="right"><input type="submit" value="Confirm!" class=button /></td></tr> 
	</form> 
	';
    print '<tr><td align=center colspan=7><form method="get" action="index.php"><input type=hidden name=act value=delreports><input type="submit" value="Delete Reports" class=button" /></form></td></tr>
';
    print '</table>';
  }

  _form_header_close_ ();
  stdfoot ();
?>
