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
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  $pid = (isset ($_POST['pid']) ? intval ($_POST['pid']) : (isset ($_GET['pid']) ? intval ($_GET['pid']) : 0));
  if ((!is_valid_id ($tid) OR !is_valid_id ($pid)))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  ($query = sql_query ('SELECT p.pid, p.tid, p.fid, p.uid as posterid, p.subject as postsubject, f.type, f.pid as deepforum, t.closed 
							FROM ' . TSF_PREFIX . 'posts p
							LEFT JOIN ' . TSF_PREFIX . 'forums f ON (p.fid=f.fid)			
							LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)
							WHERE p.pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 46));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $post = mysql_fetch_assoc ($query);
  $tid = 0 + $post['tid'];
  $pid = 0 + $post['pid'];
  $fid = 0 + $post['fid'];
  $ftype = $post['type'];
  $deepforum = 0 + $post['deepforum'];
  $closed = $post['closed'];
  $forummoderator = is_forum_mod (($ftype == 's' ? $deepforum : $fid), $CURUSER['id']);
  $subject = htmlspecialchars_uni (ts_remove_badwords ($post['postsubject']));
  if (((!$tid OR !$pid) OR !$fid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  if (((!$moderator AND !$forummoderator) AND (($post['posterid'] != $CURUSER['id'] OR $permissions[$deepforum]['canview'] == 'no') OR $permissions[$deepforum]['candeleteposts'] == 'no')))
  {
    print_no_permission ();
    exit ();
  }
  else
  {
    if (((!$moderator AND !$forummoderator) AND $closed == 'yes'))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['thread_closed']);
      exit ();
    }
  }

  ($query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 83));
  $count = mysql_result ($query, 0, 'totalposts');
  if ($count <= 1)
  {
    if (((!$moderator AND !$forummoderator) AND ($post['posterid'] != $CURUSER['id'] OR $permissions[$deepforum]['candeletethreads'] == 'no')))
    {
      print_no_permission ();
      exit ();
    }

    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = sprintf ($lang->tsf_forums['mod_del_thread'], $subject) . '<br />' . $lang->tsf_forums['mod_del_thread_2'] . '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?tid=' . $tid . '&pid=' . $pid . '&sure=1&hash={1}&page=' . intval ($_GET['page']) . '">' . $lang->tsf_forums['yes'] . '</a> -- <a href="showthread.php?tid=' . $tid . '&page=' . intval ($_GET['page']) . '#pid' . $pid . '">' . $lang->tsf_forums['no'] . '</a>';
    $ts_token->redirect = $_SERVER['SCRIPT_NAME'] . ('' . '?tid=' . $tid . '&pid=' . $pid . '&page=') . intval ($_GET['page']) . '#pid' . $pid;
    $ts_token->create ();
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 102));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 103));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 104));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 105));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 108));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads - 1, posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($fid)) OR sqlerr (__FILE__, 120));
    (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['posterid'])) OR sqlerr (__FILE__, 123));
    write_log ('' . 'Thread (' . $tid . ' - ' . $subject . ') has been deleted by ' . $CURUSER['username']);
    delete_attachments ($pid, $tid);
    include_once INC_PATH . '/readconfig_kps.php';
    kps ('-', $kpscomment, $post['posterid']);
    $return = '' . 'tsf_forums/forumdisplay.php?fid=' . $fid;
  }
  else
  {
    $orjtid = $tid;
    if (((!$moderator AND !$forummoderator) AND ($post['posterid'] != $CURUSER['id'] OR $permissions[$deepforum]['candeleteposts'] == 'no')))
    {
      print_no_permission ();
      exit ();
    }

    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = sprintf ($lang->tsf_forums['mod_del_post'], $subject) . '<br />' . $lang->tsf_forums['mod_del_post_2'] . '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?tid=' . $tid . '&pid=' . $pid . '&sure=1&hash={1}&page=' . intval ($_GET['page']) . '">' . $lang->tsf_forums['yes'] . '</a> -- <a href="showthread.php?tid=' . $tid . '&page=' . intval ($_GET['page']) . '#pid' . $pid . '">' . $lang->tsf_forums['no'] . '</a>';
    $ts_token->redirect = $_SERVER['SCRIPT_NAME'] . ('' . '?tid=' . $tid . '&pid=' . $pid . '&page=') . intval ($_GET['page']) . '#pid' . $pid;
    $ts_token->create ();
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 153));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 156));
    (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['posterid'])) OR sqlerr (__FILE__, 159));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 162));
    $lastpostdata = mysql_fetch_assoc ($query);
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET replies = replies - 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ' WHERE tid = ') . sqlesc ($tid)) OR sqlerr (__FILE__, 170));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 173));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($fid)) OR sqlerr (__FILE__, 184));
    write_log ('' . 'Post (' . $pid . ' - ' . $subject . ') has been deleted by ' . $CURUSER['username']);
    delete_attachments ($pid, $tid);
    include_once INC_PATH . '/readconfig_kps.php';
    kps ('-', $kpscomment, $post['posterid']);
    $return = '' . 'tsf_forums/showthread.php?tid=' . $orjtid . '&amp;page=' . intval ($_GET['page']);
  }

  redirect ($return);
?>
