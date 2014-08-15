<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function xmlhttp_show ($message)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    echo $message;
    exit ();
  }

  function xmlhttp_error ($message)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    echo '<error>' . $message . '</error>';
    exit ();
  }

  function show_thanks ($Remove = false)
  {
    global $lang;
    global $tid;
    global $pid;
    global $posterforthanks;
    $array = array ();
    $Query = mysql_query ('SELECT t.uid, u.username, g.namestyle FROM tsf_thanks t LEFT JOIN users u ON (t.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.tid = \'' . $tid . '\' AND t.pid = \'' . $pid . '\' ORDER BY u.username');
    if (mysql_num_rows ($Query) == 0)
    {
      exit ();
    }
    else
    {
      while ($T = mysql_fetch_assoc ($Query))
      {
        $array[] = '<a href="' . ts_seo ($T['uid'], $T['username']) . '">' . get_user_color ($T['username'], $T['namestyle']) . '</a>';
      }
    }

    $ThanksCount = count ($array);
    exit ('
	<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" style="clear: both;">
		<tbody>
			<tr>
				<td class="subheader" style="padding: 0px;">
					<strong>' . (1 < $ThanksCount ? sprintf ($lang->tsf_forums['thanks'], ts_nf ($ThanksCount), $posterforthanks) : sprintf ($lang->tsf_forums['thank'], $posterforthanks)) . '</strong>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						' . implode (', ', $array) . '
					</div>
				</td>
			</tr>
		</tbody>
	</table>');
  }

  define ('TSF_FORUMS_TSSEv56', true);
  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  define ('NcodeImageResizer', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $ajax_action = (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : ''));
  if (($ajax_action == 'thanks' AND strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST'))
  {
    if (((!$CURUSER OR $thankssystem != 'yes') OR $usergroups['canthanks'] != 'yes'))
    {
      xmlhttp_error ($lang->global['nopermission']);
    }

    $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : 0);
    $pid = (isset ($_POST['pid']) ? intval ($_POST['pid']) : 0);
    if ((!is_valid_id ($tid) OR !is_valid_id ($pid)))
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $query = sql_query ('SELECT p.uid as posterid, t.closed, f.type, f.fid as currentforumid, ff.fid as deepforumid, u.username, g.namestyle
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)	
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			LEFT JOIN users u ON (p.uid=u.id)
			LEFT JOIN usergroups g ON (u.usergroup=g.gid)
			WHERE p.tid = ' . sqlesc ($tid) . ' AND p.pid = ' . sqlesc ($pid) . ' LIMIT 1');
    if (mysql_num_rows ($query) == 0)
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND $permissions[$thread['deepforumid']]['canview'] == 'no'))
    {
      xmlhttp_error ($lang->tsf_forums['noperm']);
    }

    $posterforthanks = get_user_color ($thread['username'], $thread['namestyle']);
    $kpsuserid = $thread['posterid'];
    if ($kpsuserid == $CURUSER['id'])
    {
      xmlhttp_error ($lang->tsf_forums['noperm']);
    }

    if (isset ($_POST['removethanks']))
    {
      mysql_query ('DELETE FROM ' . TSF_PREFIX . 'thanks WHERE tid = \'' . $tid . '\' AND pid = \'' . $pid . '\' AND uid = \'' . $CURUSER['id'] . '\'');
      if (mysql_affected_rows ())
      {
        include INC_PATH . '/readconfig_kps.php';
        kps ('-', $kpsthanks, $kpsuserid);
      }

      show_thanks (true);
    }
    else
    {
      $query = mysql_query ('SELECT uid FROM ' . TSF_PREFIX . 'thanks WHERE tid = \'' . $tid . '\' AND pid = \'' . $pid . '\' AND uid = \'' . $CURUSER['id'] . '\'');
      if (0 < mysql_num_rows ($query))
      {
        xmlhttp_error ($lang->tsf_forums['thanked']);
      }

      mysql_query ('INSERT INTO ' . TSF_PREFIX . 'thanks VALUES (\'' . $tid . '\', \'' . $pid . '\', \'' . $CURUSER['id'] . '\')');
      if (mysql_affected_rows ())
      {
        include INC_PATH . '/readconfig_kps.php';
        kps ('+', $kpsthanks, $kpsuserid);
        show_thanks ();
      }
      else
      {
        xmlhttp_error ($lang->global['error']);
      }
    }
  }

  if (($ajax_action == 'save_quick_edit' AND strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST'))
  {
    $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : 0);
    $pid = (isset ($_POST['pid']) ? intval ($_POST['pid']) : 0);
    if ((!is_valid_id ($tid) OR !is_valid_id ($pid)))
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    ($query = sql_query ('SELECT p.uid as posterid,  p.message, t.closed, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)	
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE p.tid = ' . sqlesc ($tid) . ' AND p.pid = ' . sqlesc ($pid) . ' LIMIT 1') OR sqlerr (__FILE__, 127));
    if (mysql_num_rows ($query) == 0)
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND (($permissions[$thread['deepforumid']]['caneditposts'] == 'no' OR $permissions[$thread['deepforumid']]['canview'] == 'no') OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no')))
    {
      xmlhttp_error ($lang->tsf_forums['noperm']);
    }
    else
    {
      if (((!$moderator AND !$forummoderator) AND $thread['closed'] == 'yes'))
      {
        xmlhttp_error ($lang->tsf_forums['thread_closed']);
      }
      else
      {
        if (((!$moderator AND !$forummoderator) AND $thread['posterid'] != $CURUSER['id']))
        {
          xmlhttp_error ($lang->tsf_forums['noperm']);
        }
      }
    }

    $text = urldecode ($_POST['text']);
    if ($text != $thread['message'])
    {
      $uid = sqlesc ($CURUSER['id']);
      $dateline = sqlesc (TIMENOW);
      $text = strval ($text);
      if (strtolower ($shoutboxcharset) != 'utf-8')
      {
        if (function_exists ('iconv'))
        {
          $text = iconv ('UTF-8', $shoutboxcharset, $text);
        }
        else
        {
          if (function_exists ('mb_convert_encoding'))
          {
            $text = mb_convert_encoding ($text, $shoutboxcharset, 'UTF-8');
          }
          else
          {
            if (strtolower ($shoutboxcharset) == 'iso-8859-1')
            {
              $text = utf8_decode ($text);
            }
          }
        }
      }

      if (strlen ($text) < $f_minmsglength)
      {
        xmlhttp_error ($lang->tsf_forums['too_short']);
      }

      $query = sql_query ('SELECT dateline FROM ' . TSF_PREFIX . ('' . 'posts WHERE uid = ' . $uid . ' ORDER by dateline DESC LIMIT 1'));
      if (0 < mysql_num_rows ($query))
      {
        $last_post = mysql_result ($query, 0, 'dateline');
      }

      $floodcheck = flood_check ($lang->tsf_forums['a_post'], $last_post, true);
      if ($floodcheck != '')
      {
        xmlhttp_error ($floodcheck);
      }

      if ($usergroups['cansettingspanel'] != 'yes')
      {
        $eq0 = '' . ', edituid = ' . $uid . ', edittime = ' . $dateline;
      }

      @sql_query ('UPDATE ' . TSF_PREFIX . 'posts SET message = ' . @sqlesc ($text) . ('' . $eq0 . ' WHERE tid = ') . @sqlesc ($tid) . ' AND pid = ' . @sqlesc ($pid));
    }

    define ('IS_THIS_USER_POSTED', true);
    xmlhttp_show (format_comment ($text));
  }

  if (($ajax_action == 'quick_edit' AND strtoupper ($_SERVER['REQUEST_METHOD']) == 'GET'))
  {
    $tid = (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0);
    $pid = (isset ($_GET['pid']) ? intval ($_GET['pid']) : 0);
    if ((!is_valid_id ($tid) OR !is_valid_id ($pid)))
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    ($query = sql_query ('SELECT p.uid as posterid,  p.message, t.closed, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)	
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE p.tid = ' . sqlesc ($tid) . ' AND p.pid = ' . sqlesc ($pid) . ' LIMIT 1') OR sqlerr (__FILE__, 210));
    if (mysql_num_rows ($query) == 0)
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND (($permissions[$thread['deepforumid']]['caneditposts'] == 'no' OR $permissions[$thread['deepforumid']]['canview'] == 'no') OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no')))
    {
      xmlhttp_error ($lang->tsf_forums['noperm']);
    }
    else
    {
      if (((!$moderator AND !$forummoderator) AND $thread['closed'] == 'yes'))
      {
        xmlhttp_error ($lang->tsf_forums['thread_closed']);
      }
      else
      {
        if (((!$moderator AND !$forummoderator) AND $thread['posterid'] != $CURUSER['id']))
        {
          xmlhttp_error ($lang->tsf_forums['noperm']);
        }
      }
    }

    xmlhttp_show (htmlspecialchars_uni ($thread['message']));
  }

  if (($ajax_action == 'edit_subject' AND strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST'))
  {
    $ajax_tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : '');
    if (!is_valid_id ($ajax_tid))
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $query = sql_query ('SELECT t.subject, t.fid as ofid, t.closed, t.uid as posterid, t.firstpost, f.type, f.name as currentforum, f.fid as currentforumid, ff.name as deepforum, ff.fid as deepforumid 
				FROM ' . TSF_PREFIX . 'threads t
				LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
				LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
				WHERE t.tid = ' . sqlesc ($ajax_tid) . ' LIMIT 1');
    if (mysql_num_rows ($query) == 0)
    {
      xmlhttp_error ($lang->tsf_forums['invalid_tid']);
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND (($permissions[$thread['deepforumid']]['caneditposts'] == 'no' OR $permissions[$thread['deepforumid']]['canview'] == 'no') OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no')))
    {
      xmlhttp_error ($lang->tsf_forums['noperm']);
    }
    else
    {
      if (((!$moderator AND !$forummoderator) AND $thread['closed'] == 'yes'))
      {
        xmlhttp_error ($lang->tsf_forums['thread_closed']);
      }
      else
      {
        if (((!$moderator AND !$forummoderator) AND $thread['posterid'] != $CURUSER['id']))
        {
          xmlhttp_error ($lang->tsf_forums['noperm']);
        }
      }
    }

    if (strlen ($_POST['value']) < $f_minmsglength)
    {
      xmlhttp_error ($lang->tsf_forums['too_short']);
    }

    $query = sql_query ('SELECT dateline FROM ' . TSF_PREFIX . 'posts WHERE uid = ' . sqlesc ($CURUSER['id']) . ' ORDER by dateline DESC LIMIT 1');
    if (0 < mysql_num_rows ($query))
    {
      $last_post = mysql_result ($query, 0, 'dateline');
    }

    $floodcheck = flood_check ($lang->tsf_forums['a_post'], $last_post, true);
    if ($floodcheck != '')
    {
      xmlhttp_error ($floodcheck);
    }

    $subject = $_POST['value'];
    if (strtolower ($shoutboxcharset) != 'utf-8')
    {
      if (function_exists ('iconv'))
      {
        $subject = iconv ('UTF-8', $shoutboxcharset, $subject);
      }
      else
      {
        if (function_exists ('mb_convert_encoding'))
        {
          $subject = mb_convert_encoding ($subject, $shoutboxcharset, 'UTF-8');
        }
        else
        {
          if (strtolower ($shoutboxcharset) == 'iso-8859-1')
          {
            $subject = utf8_decode ($subject);
          }
        }
      }
    }

    mysql_query ('UPDATE ' . TSF_PREFIX . 'threads SET subject = ' . sqlesc ($subject) . ' WHERE tid = ' . sqlesc ($ajax_tid));
    mysql_query ('UPDATE ' . TSF_PREFIX . 'posts SET subject = ' . sqlesc ($subject) . ('' . ', edituid = ' . $CURUSER['id'] . ', edittime = ') . TIMENOW . ' WHERE tid = ' . sqlesc ($ajax_tid) . ' AND pid = ' . sqlesc ($thread['firstpost']));
    mysql_query ('UPDATE ' . TSF_PREFIX . 'forums SET lastpostsubject = ' . sqlesc ($subject) . ' WHERE 	lastposttid = ' . sqlesc ($ajax_tid) . ' AND fid = ' . sqlesc ($thread['ofid']) . ' LIMIT 1');
    xmlhttp_show ($_POST['value']);
  }

?>
