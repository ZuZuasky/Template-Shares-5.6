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
  if (($action != 'deletethread' AND ((empty ($posthash) OR strlen ($posthash) != 32) OR $posthash != $forumtokencode)))
  {
    print_no_permission (true, true, 'Invalid HASH!');
    exit ();
  }

  if (!is_valid_id ($tid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }
  else
  {
    if (!$moderator)
    {
      ($query = sql_query ('SELECT p.tid, t.closed, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE p.tid = ' . sqlesc ($tid) . ' LIMIT 1') OR sqlerr (__FILE__, 54));
      $thread = mysql_fetch_assoc ($query);
      $fid = 0 + $thread['currentforumid'];
      $ftype = $thread['type'];
      $deepforum = $thread['deepforumid'];
      $forummoderator = is_forum_mod (($ftype == 's' ? $deepforum : $fid), $CURUSER['id']);
      if (!$forummoderator)
      {
        print_no_permission (true);
        exit ();
      }
    }
    else
    {
      if (empty ($action))
      {
        print_no_permission (true);
        exit ();
      }
    }
  }

  if ($action == 'sticky')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET sticky = IF(sticky=1,0,1) WHERE tid=' . sqlesc ($tid)) OR sqlerr (__FILE__, 74));
    write_log ('' . 'Thread (' . $tid . ') has been updated (stickey/unsicky) by ' . $CURUSER['username']);
    redirect ('' . 'tsf_forums/showthread.php?tid=' . $tid);
    return 1;
  }

  if ($action == 'openclosethread')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET closed = IF(closed=\'yes\',\'no\',\'yes\') WHERE tid=' . sqlesc ($tid)) OR sqlerr (__FILE__, 80));
    write_log ('' . 'Thread (' . $tid . ') has been updated (Open/Close) by ' . $CURUSER['username']);
    redirect ('' . 'tsf_forums/showthread.php?tid=' . $tid);
    return 1;
  }

  if ($action == 'deletethread')
  {
    ($query = sql_query ('SELECT subject FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 86));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $subject = mysql_result ($query, 0, 'subject');
    $subject = htmlspecialchars_uni (ts_remove_badwords ($subject));
    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = sprintf ($lang->tsf_forums['mod_del_thread'], $subject) . '<br />' . $lang->tsf_forums['mod_del_thread_2'] . '<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?tid=' . $tid . '&action=deletethread&sure=1&hash={1}">' . $lang->tsf_forums['yes'] . '</a> -- <a href="showthread.php?tid=' . $tid . '">' . $lang->tsf_forums['no'] . '</a>';
    $ts_token->redirect = $_SERVER['SCRIPT_NAME'] . ('' . '?tid=' . $tid . '&action=deletethread');
    $ts_token->create ();
    ($query = sql_query ('SELECT pid,fid,uid FROM ' . TSF_PREFIX . 'posts WHERE tid= ' . sqlesc ($tid)) OR sqlerr (__FILE__, 102));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    include_once INC_PATH . '/readconfig_kps.php';
    while ($post = mysql_fetch_assoc ($query))
    {
      delete_attachments ($post['pid'], $tid);
      (sql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE pid = ' . $post['pid']) OR sqlerr (__FILE__, 115));
      if ((!isset ($fid) OR empty ($fid)))
      {
        $fid = $post['fid'];
      }

      (sql_query ('UPDATE users SET totalposts = totalposts - 1 WHERE id = ' . sqlesc ($post['uid'])) OR sqlerr (__FILE__, 121));
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

    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 142));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 143));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 144));
    $orjtid = $tid;
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 149));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($fid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads - 1, posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($fid)) OR sqlerr (__FILE__, 161));
    write_log ('' . 'Thread (' . $orjtid . ') has been deleted by ' . $CURUSER['username']);
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $fid);
    return 1;
  }

  if ($action == 'movethread')
  {
    stdhead ('Move Thread');
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 176));
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $deepsubforums[$forum['pid']] = $deepsubforums[$forum['pid']] . '
			<option value="' . $forum['fid'] . '">&nbsp; &nbsp;' . $forum['name'] . '</option>';
        continue;
      }
      else
      {
        continue;
      }
    }

    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'f\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 191));
    $str = '
			<form action="moderation.php" method="get" style="margin-top: 0pt; margin-bottom: 0pt;">
			<input type="hidden" name="action" value="do_move">
			<input type="hidden" name="tid" value="' . $tid . '">	
			<input type="hidden" name="hash" value="' . $forumtokencode . '">
			<span class="smalltext">
			<strong>' . $lang->tsf_forums['mod_move'] . '</strong></span><br />
			<select name="newfid">
			<optgroup label="' . $SITENAME . ' Forums">	';
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $subforums[$forum['pid']] = $subforums[$forum['pid']] . '
			<option value="' . $forum['fid'] . '">-- ' . $forum['name'] . '</option>' . $deepsubforums[$forum['fid']];
        continue;
      }
      else
      {
        continue;
      }
    }

    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f							
							WHERE f.type = \'c\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 216));
    while ($category = mysql_fetch_assoc ($query))
    {
      if ($permissions[$category['fid']]['canview'] != 'no')
      {
        $str .= '<optgroup label="' . $category['name'] . '">' . $subforums[$category['fid']] . '</optgroup>';
        continue;
      }
      else
      {
        continue;
      }
    }

    $str .= '
			</optgroup>
			</select> 
			<input type="submit" value="' . $lang->tsf_forums['mod_options_m'] . '">
			<input value="' . $lang->tsf_forums['cancel'] . '" onclick="jumpto(\'showthread.php?tid=' . $tid . '\');" type="button">
			</form>';
    echo '
	<table class="tborder" border="0" cellpadding="4" cellspacing="0">
	<tbody><tr><td>' . $str . '</td></tr></tbody></table>';
    stdfoot ();
    return 1;
  }

  if ($action == 'do_move')
  {
    $newfid = (isset ($_GET['newfid']) ? intval ($_GET['newfid']) : 0);
    if (!is_valid_id ($newfid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      exit ();
    }

    ($query = sql_query ('SELECT type,pid FROM ' . TSF_PREFIX . 'forums WHERE fid = ' . sqlesc ($newfid)) OR sqlerr (__FILE__, 247));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      exit ();
    }

    $type = mysql_result ($query, 0, 'type');
    $pid = mysql_result ($query, 0, 'pid');
    ($query = sql_query ('SELECT fid as oldforum FROM ' . TSF_PREFIX . 'threads WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 263));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $oldforum = mysql_result ($query, 0, 'oldforum');
    $orjtid = $tid;
    (sql_query ('UPDATE ' . TSF_PREFIX . 'posts SET fid = ' . sqlesc ($newfid) . ' WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 273));
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET fid = ' . sqlesc ($newfid) . ' WHERE tid = ' . sqlesc ($tid)) OR sqlerr (__FILE__, 274));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($oldforum) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 277));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($oldforum));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads - 1, posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($oldforum)) OR sqlerr (__FILE__, 289));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 292));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads + 1, posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($newfid)) OR sqlerr (__FILE__, 304));
    write_log ('' . 'Thread (' . $orjtid . ' has been moved from FORUM: ' . $oldforum . ' to FORUM: ' . $newfid . ' by ' . $CURUSER['username']);
    redirect ('' . 'tsf_forums/showthread.php?tid=' . $orjtid);
  }

?>
