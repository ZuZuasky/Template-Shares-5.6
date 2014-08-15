<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $userid = (int)$_GET['id'];
  int_check ($userid, true);
  define ('UH_VERSION', '0.6 ');
  define ('NcodeImageResizer', true);
  if (($CURUSER['id'] != $userid AND $usergroups['canuserdetails'] != 'yes'))
  {
    print_no_permission ();
  }

  $action = htmlspecialchars ($_GET['action']);
  $lang->load ('userdetails');
  $perpage = $ts_perpage;
  if ($action == 'viewcomments')
  {
    require_once INC_PATH . '/functions_mkprettytime.php';
    $from_is = 'comments AS c LEFT JOIN torrents as t ON c.torrent = t.id';
    $where_is = '' . 'c.user = ' . $userid;
    $order_is = 'c.id DESC';
    $commentcount = tsrowcount ('id', 'comments', 'user=' . $userid);
    list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $commentcount, $_SERVER['SCRIPT_NAME'] . ('' . '?action=viewcomments&id=' . $userid . '&'));
    ($res = sql_query ('SELECT u.username, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($userid)) OR sqlerr (__FILE__, 55));
    if (mysql_num_rows ($res) == 1)
    {
      $arr = mysql_fetch_array ($res);
      include_once INC_PATH . '/functions_icons.php';
      $subject = '' . '<a href=\'userdetails.php?id=' . $userid . '\'>' . get_user_color ($arr['username'], $arr['namestyle']) . '</a> ' . get_user_icons ($arr, true);
    }
    else
    {
      $subject = ('' . 'unknown[' . $userid . ']');
    }

    $select_is = 't.name, c.torrent AS t_id, c.id, c.added, c.text';
    $query = '' . 'SELECT ' . $select_is . ' FROM ' . $from_is . ' WHERE ' . $where_is . ' ORDER BY ' . $order_is . ' ' . $limit;
    ($res = sql_query ($query) OR sqlerr (__FILE__, 72));
    if (mysql_num_rows ($res) == 0)
    {
      stderr ($lang->global['error'], $lang->userdetails['nocomment']);
    }

    stdhead (sprintf ($lang->userdetails['chistory'], $arr['username']));
    print '<h2>' . sprintf ($lang->userdetails['chistory'], $subject) . '</h2>
';
    if ($perpage < $commentcount)
    {
      echo $pagertop;
    }

    begin_main_frame ();
    begin_frame ();
    while ($arr = mysql_fetch_array ($res))
    {
      $commentid = $arr['id'];
      $torrent = $arr['name'];
      if (55 < strlen ($torrent))
      {
        $torrent = substr ($torrent, 0, 52) . '...';
      }

      $torrentid = $arr['t_id'];
      $Query = sql_query ('SELECT id FROM comments WHERE torrent =' . $torrentid . ' AND id <= ' . $commentid);
      $Count = mysql_num_rows ($Query);
      if ($Count <= $perpage)
      {
        $P = 0;
      }
      else
      {
        $P = ceil ($Count / $perpage);
      }

      $page_url = ($P ? '' . '&page=' . $P : '');
      $added = $arr['added'] . ' GMT (' . mkprettytime (time () - strtotime ($arr['added'])) . ')<br />';
      print '<p class=sub><table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>' . ('' . $added . '<b>') . $lang->userdetails['torrentname'] . '&nbsp;</b>' . ($torrent ? '' . '<a href=details.php?id=' . $torrentid . '&tab=comments>' . $torrent . '</a>' : ' [Deleted] ') . '&nbsp;---&nbsp;<b>' . $lang->userdetails['comment'] . ('' . '&nbsp;</b>#<a href=details.php?tab=comments&id=' . $torrentid . $page_url . '#cid' . $commentid . '>' . $commentid . '</a>
	  </td></tr></table></p>
');
      begin_table (true);
      $body = format_comment ($arr['text']);
      print '' . '<tr valign=top><td class=comment>' . $body . '</td></tr>
';
      end_table ();
    }

    end_frame ();
    end_main_frame ();
    if ($perpage < $commentcount)
    {
      echo $pagerbottom;
    }

    stdfoot ();
    exit ();
  }

  print_no_permission ();
?>
