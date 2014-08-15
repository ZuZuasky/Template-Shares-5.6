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

  $page = (isset ($_POST['page']) ? intval ($_POST['page']) : (isset ($_GET['page']) ? intval ($_GET['page']) : 0));
  $threadid = (isset ($_POST['threadid']) ? intval ($_POST['threadid']) : (isset ($_GET['threadid']) ? intval ($_GET['threadid']) : 0));
  $userid = intval ($CURUSER['id']);
  $vote = (isset ($_POST['vote']) ? intval ($_POST['vote']) : (isset ($_GET['vote']) ? intval ($_GET['vote']) : 0));
  $ipaddress = htmlspecialchars ($CURUSER['ip']);
  $posthash = (isset ($_POST['posthash']) ? trim ($_POST['posthash']) : (isset ($_GET['posthash']) ? trim ($_GET['posthash']) : ''));
  if (!is_valid_id ($threadid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  if ((empty ($posthash) OR $posthash != sha1 ($threadid . $securehash . $threadid)))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['rateresult4']);
    exit ();
  }

  if (((!is_valid_id ($vote) OR $vote < 1) OR 5 < $vote))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['rateresult3']);
    exit ();
  }

  ($query = sql_query ('SELECT 
			t.tid, t.closed, t.pollid, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($threadid) . ' LIMIT 1') OR sqlerr (__FILE__, 63));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $thread = mysql_fetch_assoc ($query);
  $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
  if (((!$moderator AND !$forummoderator) AND (($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no') OR $usergroups['canrate'] != 'yes')))
  {
    print_no_permission (true);
    exit ();
  }

  if ((($thread['closed'] == 'yes' AND !$moderator) AND !$forummoderator))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['thread_closed']);
    exit ();
  }

  $query1 = sql_query ('SELECT userid FROM ' . TSF_PREFIX . ('' . 'threadrate WHERE userid = ' . $userid . ' AND threadid = ' . $threadid));
  if (0 < mysql_num_rows ($query1))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['rateresult2']);
    exit ();
  }

  sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'threadrate (threadid,userid,vote,ipaddress) VALUES (' . $threadid . ',' . $userid . ',' . $vote . ',') . sqlesc ($ipaddress) . ')');
  sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET votenum = votenum + 1, votetotal = votetotal + ' . $vote . ' WHERE tid = ' . $threadid));
  include_once INC_PATH . '/readconfig_kps.php';
  kps ('+', $kpsrate, $userid);
  redirect ('' . 'tsf_forums/showthread.php?tid=' . $threadid . '&amp;page=' . $page . '&amp;nolastpage=true', $lang->tsf_forums['rateresult1']);
?>
