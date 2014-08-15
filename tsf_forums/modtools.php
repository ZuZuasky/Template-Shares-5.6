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

  $parentfid = (isset ($_POST['parentfid']) ? intval ($_POST['parentfid']) : '');
  $currentfid = (isset ($_POST['currentfid']) ? intval ($_POST['currentfid']) : '');
  $threadids = ((isset ($_POST['threadids']) AND is_array ($_POST['threadids'])) ? $_POST['threadids'] : explode (',', $_POST['threadids']));
  $postids = (isset ($_POST['postids']) ? $_POST['postids'] : '');
  if (is_array ($threadids))
  {
    foreach ($threadids as $checkid)
    {
      if (!is_valid_id ($checkid))
      {
        print_no_permission (true, true, 'Invalid Thread ID!');
        exit ();
        continue;
      }
    }
  }
  else
  {
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid, 'Please select at least one thread to do this action!');
    exit ();
  }

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
    else
    {
      if (!$moderator)
      {
        ($query = sql_query ('SELECT p.tid, t.closed, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE p.tid IN (0,' . implode (',', $threadids) . ') LIMIT 1') OR sqlerr (__FILE__, 72));
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
  }

  if ($action == 'open')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET closed = \'no\' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 92));
    write_log ('Threads: (' . implode (',', $threadids) . ('' . ') has been opened by ' . $CURUSER['username']));
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid);
    exit ();
    return 1;
  }

  if ($action == 'close')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET closed = \'yes\' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 99));
    write_log ('Threads: (' . implode (',', $threadids) . ('' . ') has been closed by ' . $CURUSER['username']));
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid);
    exit ();
    return 1;
  }

  if ($action == 'sticky')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET sticky = \'1\' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 106));
    write_log ('Threads: (' . implode (',', $threadids) . ('' . ') has been set to sticky by ' . $CURUSER['username']));
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid);
    exit ();
    return 1;
  }

  if ($action == 'unsticky')
  {
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET sticky = \'0\' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 113));
    write_log ('Threads: (' . implode (',', $threadids) . ('' . ') has been set to un-sticky by ' . $CURUSER['username']));
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $currentfid);
    exit ();
    return 1;
  }

  if ($action == 'do_movethreads')
  {
    $newfid = (isset ($_POST['newfid']) ? intval ($_POST['newfid']) : 0);
    if (!is_valid_id ($newfid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      exit ();
    }

    ($query = sql_query ('SELECT type,pid FROM ' . TSF_PREFIX . 'forums WHERE fid = ' . sqlesc ($newfid)) OR sqlerr (__FILE__, 127));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      exit ();
    }

    $type = mysql_result ($query, 0, 'type');
    $pid = mysql_result ($query, 0, 'pid');
    ($query = sql_query ('SELECT fid as oldforum FROM ' . TSF_PREFIX . 'threads WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 143));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $oldforum = mysql_result ($query, 0, 'oldforum');
    (sql_query ('UPDATE ' . TSF_PREFIX . 'posts SET fid = ' . sqlesc ($newfid) . ' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 152));
    (sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET fid = ' . sqlesc ($newfid) . ' WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 153));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($oldforum) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 156));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($oldforum));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $query = sql_query ('SELECT COUNT(*) as totalthreads FROM ' . TSF_PREFIX . 'threads WHERE fid = ' . sqlesc ($oldforum));
    $totalthreads = mysql_result ($query, 0, 'totalthreads');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = \'' . $totalthreads . '\', posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($oldforum)) OR sqlerr (__FILE__, 171));
    ($query = sql_query ('SELECT pid, tid, fid, subject, uid, username, dateline FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 174));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $query = sql_query ('SELECT COUNT(*) as totalthreads FROM ' . TSF_PREFIX . 'threads WHERE fid = ' . sqlesc ($newfid));
    $totalthreads = mysql_result ($query, 0, 'totalthreads');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = \'' . $totalthreads . '\', posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($newfid)) OR sqlerr (__FILE__, 189));
    write_log ('Thread (' . implode (',', $threadids) . ('' . ' has been moved from FORUM: ' . $oldforum . ' to FORUM: ' . $newfid . ' by ' . $CURUSER['username']));
    redirect ('' . 'tsf_forums/forumdisplay.php?fid=' . $newfid);
    exit ();
    return 1;
  }

  if ($action == 'movethreads')
  {
    stdhead ($lang->tsf_forums['mod_options_m']);
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 204));
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
						') OR sqlerr (__FILE__, 219));
    $str = '
			<form action="modtools.php" method="POST" style="margin-top: 0pt; margin-bottom: 0pt;">
			<input type="hidden" name="action" value="do_movethreads">
			<input type="hidden" name="parentfid" value="' . $parentfid . '">
			<input type="hidden" name="currentfid" value="' . $currentfid . '">
			<input type="hidden" name="threadids" value="' . implode (',', $threadids) . '">
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
						') OR sqlerr (__FILE__, 246));
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
    exit ();
    return 1;
  }

  if ($action == 'mergethreads')
  {
    if (count ($threadids) < 2)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['mergeerror']);
    }

    ($query = sql_query ('SELECT tid,subject FROM ' . TSF_PREFIX . 'threads WHERE tid IN (0,' . implode (',', $threadids) . ') ORDER by dateline DESC') OR sqlerr (__FILE__, 275));
    if (mysql_num_rows ($query) == 0)
    {
      print_no_permission (true, true, 'Invalid Thread ID!');
    }

    $merge = '
	<fieldset>
	<legend>' . $lang->tsf_forums['mop6'] . '</legend>
	<div style="padding: 3px;">
	<select name="newtid">
	';
    while ($thread = mysql_fetch_assoc ($query))
    {
      $merge .= '<option value="' . $thread['tid'] . '">[' . $thread['tid'] . '] ' . htmlspecialchars_uni ($thread['subject']) . '</option>';
    }

    $merge .= '
	</select>
	</div>
	</fieldset>';
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 300));
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $deepsubforums[$forum['pid']] = $deepsubforums[$forum['pid']] . '
			<option value="' . $forum['fid'] . '"' . ($forum['fid'] == $currentfid ? ' selected="selected"' : '') . '>&nbsp; &nbsp;' . $forum['name'] . '</option>';
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
						') OR sqlerr (__FILE__, 315));
    $formopen = '
			<form action="modtools.php" method="POST" style="margin-top: 0pt; margin-bottom: 0pt;">
			<input type="hidden" name="action" value="do_mergethreads">
			<input type="hidden" name="parentfid" value="' . $parentfid . '">
			<input type="hidden" name="currentfid" value="' . $currentfid . '">
			<input type="hidden" name="threadids" value="' . implode (',', $threadids) . '">
			<input type="hidden" name="hash" value="' . $forumtokencode . '">';
    $formclose = '
			<input type="submit" value="' . $lang->tsf_forums['mop5'] . '">
			<input value="' . $lang->tsf_forums['cancel'] . '" onclick="jumpto(\'showthread.php?tid=' . $tid . '\');" type="button">
			</form>';
    $move = '
			<fieldset>
			<legend>' . $lang->tsf_forums['mod_move'] . '</legend>
			<div style="padding: 3px;">
			<select name="newfid">
			<optgroup label="' . $SITENAME . ' Forums">	';
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $subforums[$forum['pid']] = $subforums[$forum['pid']] . '
			<option value="' . $forum['fid'] . '"' . ($forum['fid'] == $currentfid ? ' selected="selected"' : '') . '>-- ' . $forum['name'] . '</option>' . $deepsubforums[$forum['fid']];
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
						') OR sqlerr (__FILE__, 349));
    while ($category = mysql_fetch_assoc ($query))
    {
      if ($permissions[$category['fid']]['canview'] != 'no')
      {
        $move .= '<optgroup label="' . $category['name'] . '">' . $subforums[$category['fid']] . '</optgroup>';
        continue;
      }
      else
      {
        continue;
      }
    }

    $move .= '
			</optgroup>
			</select> 
			</div>
			</fieldset>';
    stdhead ($lang->tsf_forums['mop5']);
    echo $formopen . '
	<table border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
	<tbody>
	<tr>
	<td>	
	' . $merge . $move . '
	</td>
	</tr>
	<tr>
	<td>
	' . $formclose . '
	</td>
	</tr>
	</tbody>
	</table>';
    stdfoot ();
    exit ();
    return 1;
  }

  if ($action == 'do_mergethreads')
  {
    $newtid = intval ($_POST['newtid']);
    $newfid = intval ($_POST['newfid']);
    if ((!is_valid_id ($newtid) OR !is_valid_id ($newfid)))
    {
      print_no_permission ();
    }

    foreach ($threadids as $index => $checkid)
    {
      if ((!is_valid_id ($checkid) OR $newtid == $checkid))
      {
        unset ($threadids[$index]);
        continue;
      }
    }

    $views = $replies = $totalposts = $totalthreads = 0;
    ($Query = sql_query ('SELECT views FROM ' . TSF_PREFIX . 'threads WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 401));
    while ($threadarray = mysql_fetch_assoc ($Query))
    {
      $views += $threadarray['views'];
    }

    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'attachments SET a_tid = ' . $newtid . ' WHERE a_tid IN (0,') . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 408));
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'posts SET tid = ' . $newtid . ', fid = ' . $newfid . ' WHERE tid IN (' . $newtid . ',') . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 409));
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'subscribe SET tid = ' . $newtid . ' WHERE tid IN (0,') . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 410));
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threadrate SET threadid = ' . $newtid . ' WHERE threadid IN (0,') . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 411));
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET views = views + ' . $views . ', fid = ' . $newfid . ' WHERE tid = ' . $newtid)) OR sqlerr (__FILE__, 412));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'threads WHERE tid IN (0,' . implode (',', $threadids) . ')') OR sqlerr (__FILE__, 413));
    ($query = sql_query ('SELECT dateline,username,uid,tid,subject FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($currentfid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 417));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($currentfid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $query = sql_query ('SELECT COUNT(*) as totalthreads FROM ' . TSF_PREFIX . 'threads WHERE fid = ' . sqlesc ($currentfid));
    $totalthreads = mysql_result ($query, 0, 'totalthreads');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = \'' . $totalthreads . '\', posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($currentfid)) OR sqlerr (__FILE__, 428));
    ($query = sql_query ('SELECT dateline,username,uid,tid,subject FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 431));
    $lastpostdata = mysql_fetch_assoc ($query);
    $query = sql_query ('SELECT COUNT(*) as totalposts FROM ' . TSF_PREFIX . 'posts WHERE fid = ' . sqlesc ($newfid));
    $totalposts = mysql_result ($query, 0, 'totalposts');
    $query = sql_query ('SELECT COUNT(*) as totalthreads FROM ' . TSF_PREFIX . 'threads WHERE fid = ' . sqlesc ($newfid));
    $totalthreads = mysql_result ($query, 0, 'totalthreads');
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = \'' . $totalthreads . '\', posts = \'' . $totalposts . '\', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ') . sqlesc ($newfid)) OR sqlerr (__FILE__, 442));
    ($query = sql_query ('SELECT dateline,username,uid,tid,subject FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($newtid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 445));
    $lastpostdata = mysql_fetch_assoc ($query);
    $dateline = sqlesc ($lastpostdata['dateline']);
    $username = sqlesc ($lastpostdata['username']);
    $uid = sqlesc ($lastpostdata['uid']);
    $tid = sqlesc ($lastpostdata['tid']);
    $subject = sqlesc ($lastpostdata['subject']);
    $query = sql_query ('SELECT COUNT(*) as totalreplies FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($newtid));
    $totalreplies = mysql_result ($query, 0, 'totalreplies');
    if (0 < $totalreplies)
    {
      $totalreplies = $totalreplies - 1;
    }

    (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET replies = ' . $totalreplies . ', lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ' WHERE tid = ') . sqlesc ($newtid)) OR sqlerr (__FILE__, 458));
    redirect ('' . 'tsf_forums/showthread.php?tid=' . $newtid);
  }

?>
