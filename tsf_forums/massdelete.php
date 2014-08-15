<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('TSF_FORUMS_TSSEv56', true);
  require_once 'global.php';
  include_once INC_PATH . '/readconfig_kps.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ($usergroups['canmassdelete'] != 'yes')
  {
    print_no_permission (true);
  }

  $parentfid = (isset ($_POST['parentfid']) ? intval ($_POST['parentfid']) : (isset ($_GET['parentfid']) ? intval ($_GET['parentfid']) : ''));
  $currentfid = (isset ($_POST['currentfid']) ? intval ($_POST['currentfid']) : (isset ($_GET['currentfid']) ? intval ($_GET['currentfid']) : ''));
  $threadids = (isset ($_POST['threadids']) ? $_POST['threadids'] : (isset ($_GET['threadids']) ? explode (':', $_GET['threadids']) : ''));
  $postids = (isset ($_POST['postids']) ? $_POST['postids'] : (isset ($_GET['postids']) ? explode (':', $_GET['postids']) : ''));
  if (((!is_valid_id ($parentfid) OR !is_valid_id ($currentfid)) OR strlen ($posthash) != 32))
  {
    print_no_permission (true, true, 'Invalid Thread/Post Id or Secure Hash!');
    exit ();
  }
  else
  {
    if ($posthash != $forumtokencode)
    {
      print_no_permission (true, true, 'Invalid Secure Hash!');
      exit ();
    }
  }

  if (is_array ($threadids))
  {
    foreach ($threadids as $checkid)
    {
      if (!is_valid_id ($checkid))
      {
        print_no_permission (true, true, 'Invalid Thread ID!');
        exit ();
      }

      unset ($checkid);
    }
  }

  if (is_array ($postids))
  {
    foreach ($postids as $checkid)
    {
      if (!is_valid_id ($checkid))
      {
        print_no_permission (true, true, 'Invalid Post ID!');
        exit ();
      }

      unset ($checkid);
    }
  }

  if ((!is_array ($threadids) AND !is_array ($postids)))
  {
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid, 'Please select at least one thread/post to do this action!');
    exit ();
  }

  ($query = sql_query ('SELECT 
			t.tid, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 92));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $thread = mysql_fetch_assoc ($query);
  $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
  if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no')))
  {
    print_no_permission (true);
    exit ();
  }

  if (($action == 'deletethreads' AND is_array ($threadids)))
  {
    $sure = (isset ($_GET['sure']) ? intval ($_GET['sure']) : 0);
    if ($sure != 1)
    {
      foreach ($threadids as $stid)
      {
        $showtids[] = '<a href="' . $BASEURL . '/tsf_forums/showthread.php?tid=' . intval ($stid) . '">' . intval ($stid) . '</a>';
      }

      $showtid = implode (' , ', $showtids);
      stderr ('Sanity Check', sprintf ($lang->tsf_forums['mod_del_thread'], $showtid) . '<br />' . $lang->tsf_forums['mod_del_thread_2'] . '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?parentfid=' . $parentfid . '&currentfid=' . $currentfid . '&sure=1&hash=' . $forumtokencode . '&threadids=' . implode (':', $threadids) . '&action=deletethreads">' . $lang->tsf_forums['yes'] . '</a> -- <a href="forumdisplay.php?fid=' . $currentfid . '">' . $lang->tsf_forums['no'] . '</a>', false);
      exit ();
    }

    foreach ($threadids as $tid)
    {
      ($query = sql_query ('SELECT pid,fid,uid FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid)) OR sqlerr (__FILE__, 127));
      if (mysql_num_rows ($query) == 0)
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
        exit ();
      }

      while ($post = mysql_fetch_assoc ($query))
      {
        delete_attachments ($post['pid'], $tid);
        (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . $post['pid']) OR sqlerr (__FILE__, 139));
        if ((!isset ($fid) OR empty ($fid)))
        {
          $fid = $post['fid'];
        }

        (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['uid'])) OR sqlerr (__FILE__, 145));
        kps ('-', $kpscomment, $post['uid']);
      }

      $query = sql_query ('SELECT pollid FROM ' . TSF_PREFIX . ('' . 'threads WHERE tid = ' . $tid));
      while ($delpoll = mysql_fetch_assoc ($query))
      {
        if ($delpoll['pollid'])
        {
          $deletepolls[] = intval ($delpoll['pollid']);
          continue;
        }
      }

      if (count ($deletepolls))
      {
        sql_query ('DELETE FROM ' . TSF_PREFIX . 'poll WHERE pollid IN (0,' . implode (',', $deletepolls) . ')');
        sql_query ('DELETE FROM ' . TSF_PREFIX . 'pollvote WHERE pollid IN (0,' . implode (',', $deletepolls) . ')');
      }

      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 165));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 166));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 167));
      $orjtid = $tid;
      ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 172));
      $lastpostdata = mysql_fetch_assoc ($query);
      $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid));
      $totalposts = mysql_result ($query, 0, 'totalposts');
      $dateline = sqlesc ($lastpostdata['dateline']);
      $username = sqlesc ($lastpostdata['username']);
      $uid = sqlesc ($lastpostdata['uid']);
      $tid = sqlesc ($lastpostdata['tid']);
      $subject = sqlesc ($lastpostdata['subject']);
      (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads - 1, posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($fid)) OR sqlerr (__FILE__, 184));
      write_log ('' . 'Mass Delete: Threadid:  ' . $orjtid . ' has been deleted by ' . $CURUSER['username']);
    }

    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $fid);
    return 1;
  }

  if (($action == 'deleteposts' AND is_array ($postids)))
  {
    $sure = (isset ($_GET['sure']) ? intval ($_GET['sure']) : 0);
    if ($sure != 1)
    {
      foreach ($postids as $spid)
      {
        $showspid[] = '<a href="' . $BASEURL . '/tsf_forums/showthread.php?tid=' . intval ($threadids[0]) . '&pid=' . $spid . '#pid16175">' . intval ($spid) . '</a>';
      }

      $showpid = implode (' , ', $showspid);
      stderr ('Sanity Check', sprintf ($lang->tsf_forums['mod_del_post'], $showpid) . '<br />' . $lang->tsf_forums['mod_del_post_2'] . '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?parentfid=' . $parentfid . '&currentfid=' . $currentfid . '&sure=1&hash=' . $forumtokencode . '&threadids=' . implode (':', $threadids) . '&postids=' . implode (':', $postids) . '&action=deleteposts">' . $lang->tsf_forums['yes'] . '</a> -- <a href="' . $BASEURL . '/tsf_forums/showthread.php?tid=' . intval ($threadids[0]) . '">' . $lang->tsf_forums['no'] . '</a>', false);
      exit ();
    }

    $tid = 0 + $threadids[0];
    $fid = 0 + $currentfid;
    if (count ($postids) <= 1)
    {
      $pid = 0 + $postids[0];
      ($query = sql_query ('SELECT fid,uid FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid) . ' AND pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 213));
      if (mysql_num_rows ($query) == 0)
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalid_post']);
        exit ();
      }

      $post = mysql_fetch_assoc ($query);
      delete_attachments ($pid, $tid);
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . $pid) OR sqlerr (__FILE__, 227));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid) . ' AND pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 229));
      (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['uid'])) OR sqlerr (__FILE__, 230));
      kps ('-', $kpscomment, $post['uid']);
      $foreachdone = false;
    }
    else
    {
      $foreachdone = true;
      foreach ($postids as $pid)
      {
        ($query = sql_query ('SELECT fid,uid FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid) . ' AND pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 239));
        if (mysql_num_rows ($query) == 0)
        {
          stderr ($lang->global['error'], $lang->tsf_forums['invalid_post']);
          exit ();
        }

        $post = mysql_fetch_assoc ($query);
        delete_attachments ($pid, $tid);
        (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . $pid) OR sqlerr (__FILE__, 251));
        (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid) . ' AND pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 253));
        (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET replies = replies - 1 WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 254));
        (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['uid'])) OR sqlerr (__FILE__, 255));
        kps ('-', $kpscomment, $post['uid']);
      }
    }

    ($query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 261));
    $count = mysql_result ($query, 0, 'totalposts');
    if (0 < $count)
    {
      ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 268));
      $lastpostdata = mysql_fetch_assoc ($query);
      $dateline = sqlesc ($lastpostdata['dateline']);
      $username = sqlesc ($lastpostdata['username']);
      $uid = sqlesc ($lastpostdata['uid']);
      $tid = sqlesc ($lastpostdata['tid']);
      $subject = sqlesc ($lastpostdata['subject']);
      (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET ' . (!$foreachdone ? 'replies = replies - 1, ' : '') . ('' . 'lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ' WHERE tid = ') . sqlesc ($tid)) OR sqlerr (__FILE__, 276));
    }
    else
    {
      $query = sql_query ('SELECT pollid FROM ' . TSF_PREFIX . ('' . 'threads WHERE tid = ' . $tid));
      while ($delpoll = mysql_fetch_assoc ($query))
      {
        if ($delpoll['pollid'])
        {
          $deletepolls[] = intval ($delpoll['pollid']);
          continue;
        }
      }

      if (count ($deletepolls))
      {
        sql_query ('DELETE FROM ' . TSF_PREFIX . 'poll WHERE pollid IN (0,' . implode (',', $deletepolls) . ')');
        sql_query ('DELETE FROM ' . TSF_PREFIX . 'pollvote WHERE pollid IN (0,' . implode (',', $deletepolls) . ')');
      }

      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 294));
      (sql_query ('UPDATE ' . TSF_PREFIX . 'forums SET threads = threads - 1 WHERE fid = ' . sqlesc ($fid)) OR sqlerr (__FILE__, 295));
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 296));
    }

    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 300));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($fid)) OR sqlerr (__FILE__, 311));
    write_log ('' . 'Mass Delete: Threadid:  ' . $tid . ' / Postid: ' . $pid . ' has been deleted by ' . $CURUSER['username']);
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $fid);
    return 1;
  }

  print_no_permission (true, true, 'Invalid action!');
?>
