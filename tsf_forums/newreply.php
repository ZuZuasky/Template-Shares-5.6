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
  define ('NcodeImageResizer', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  $pid = (isset ($_POST['pid']) ? intval ($_POST['pid']) : (isset ($_GET['pid']) ? intval ($_GET['pid']) : 0));
  $canpostattachments = false;
  if ((!is_valid_id ($tid) OR (!empty ($pid) AND !is_valid_id ($pid))))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  ($query = sql_query ('SELECT 
			t.subject as threadsubject, t.closed, t.sticky, f.type, f.name as currentforum, f.fid as currentforumid, ff.name as deepforum, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($tid) . ' LIMIT 0, 1') OR sqlerr (__FILE__, 48));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $thread = mysql_fetch_assoc ($query);
  $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
  if (($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no'))
  {
    print_no_permission (true);
    exit ();
  }
  else
  {
    if ((($thread['closed'] == 'yes' AND !$moderator) AND !$forummoderator))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['thread_closed']);
      exit ();
    }
  }

  $useparent = false;
  if ($thread['type'] == 's')
  {
    $useparent = true;
  }

  if ($permissions[$thread['deepforumid']]['canpostattachments'] == 'yes')
  {
    $canpostattachments = true;
  }

  if (!empty ($pid))
  {
    ($query = sql_query ('SELECT p.message, p.tid, p.subject, u.username FROM ' . TSF_PREFIX . 'posts p LEFT JOIN users u ON (p.uid=u.id) WHERE p.pid = ' . sqlesc ($pid)) OR sqlerr (__FILE__, 84));
    $p_tid = @mysql_result ($query, 0, 'p.tid');
    if ($p_tid != $tid)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_post']);
      exit ();
    }

    $subject = mysql_result ($query, 0, 'p.subject');
    $message = mysql_result ($query, 0, 'p.message');
    if (((!$forummoderator AND !$moderator) AND preg_match ('/\\[hide\\](.*?)\\[\\/hide\\]/is', $message)))
    {
      while (preg_match ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', $message))
      {
        $message = preg_replace ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', '', $message);
      }
    }

    $username = mysql_result ($query, 0, 'u.username');
    $subject = preg_replace ('#RE:\\s?#i', '', $subject);
    $subject = $lang->tsf_forums['re'] . $subject;
    $threadsubject = ts_remove_badwords ($subject);
    $message = ('' . '[quote=' . $username . ']') . $message . '[/quote]';
    $replyto = $pid;
  }
  else
  {
    $subject = $lang->tsf_forums['re'] . $thread['threadsubject'];
    $threadsubject = ts_remove_badwords ($subject);
  }

  if (!isset ($replyto))
  {
    $replyto = 0;
  }

  $fid = 0 + $thread['currentforumid'];
  if (($_POST['previewpost'] AND !empty ($_POST['message'])))
  {
    $avatar = get_user_avatar ($CURUSER['avatar']);
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
	</tr></table><br />';
  }

  if (($_SERVER['REQUEST_METHOD'] == 'POST' AND isset ($_POST['submit'])))
  {
    $error = '';
    $subject = sqlesc ($_POST['subject']);
    $uid = sqlesc ($CURUSER['id']);
    $username = sqlesc ($CURUSER['username']);
    $dateline = sqlesc (TIMENOW);
    $message = sqlesc ($_POST['message']);
    $ipaddress = sqlesc ($CURUSER['ip']);
    $closed = (($_POST['closethread'] == 'yes' AND ($moderator OR $forummoderator)) ? 'yes' : 'no');
    $sticky = (($_POST['stickthread'] == 'yes' AND ($moderator OR $forummoderator)) ? 1 : 0);
    $subscribe = ($_POST['subscribe'] == 'yes' ? 1 : 0);
    if ($subscribe)
    {
      $query = sql_query ('SELECT userid FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid) . ' AND userid = ' . $uid);
      if (mysql_num_rows ($query) == 0)
      {
        sql_query ('INSERT INTO ' . TSF_PREFIX . 'subscribe (tid,userid) VALUES (' . sqlesc ($tid) . ',' . $uid . ')');
      }
    }

    if (($moderator OR $forummoderator))
    {
      $extraquery = ', closed = ' . sqlesc ($closed) . ', sticky = ' . sqlesc ($sticky);
    }

    if ((strlen ($_POST['subject']) < $f_minmsglength OR strlen ($_POST['message']) < $f_minmsglength))
    {
      $error = $lang->tsf_forums['too_short'];
    }

    $query = sql_query ('SELECT dateline FROM ' . TSF_PREFIX . 'posts WHERE uid = ' . sqlesc ($CURUSER['id']) . ' ORDER by dateline DESC LIMIT 1');
    if (0 < mysql_num_rows ($query))
    {
      $last_post = mysql_result ($query, 0, 'dateline');
    }

    $floodcheck = flood_check ($lang->tsf_forums['a_post'], $last_post, true);
    if ($floodcheck != '')
    {
      $error = $floodcheck;
    }

    if (empty ($error))
    {
      $stop = false;
      $dp_query = sql_query ('SELECT lastpost FROM ' . TSF_PREFIX . ('' . 'threads WHERE lastposteruid = ' . $uid . ' AND tid=') . sqlesc ($tid) . ' LIMIT 1');
      $dp_thread = @mysql_fetch_assoc ($dp_query);
      if (60 * 60 < time () - $dp_thread['lastpost'])
      {
        $stop = true;
      }

      if (((($dp_thread AND !$stop) AND !$moderator) AND !$forummoderator))
      {
        ($query = sql_query ('SELECT pid, message FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid) . ('' . ' AND uid = ' . $uid . ' AND dateline = ') . sqlesc ($dp_thread['lastpost']) . ' ORDER BY pid DESC LIMIT 1') OR sqlerr (__FILE__, 189));
        $oldmessage = mysql_result ($query, 0, 'message');
        $pid = mysql_result ($query, 0, 'pid');
        if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
        {
          $eol = '
';
        }
        else
        {
          if (strtoupper (substr (PHP_OS, 0, 3) == 'MAC'))
          {
            $eol = '
';
          }
          else
          {
            $eol = '
';
          }
        }

        $message = $oldmessage . $eol . $eol . $_POST['message'];
        sql_query ('UPDATE ' . TSF_PREFIX . 'posts SET message = ' . sqlesc ($message) . ' WHERE pid = ' . sqlesc ($pid));
      }
      else
      {
        $iq1 = $iq2 = '';
        $iconid = intval ($_POST['iconid']);
        if (is_valid_id ($iconid))
        {
          $iq1 = 'iconid,';
          $iq2 = '' . $iconid . ',';
        }

        (sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'posts (' . $iq1 . 'tid,replyto,fid,subject,uid,username,dateline,message,ipaddress) VALUES (' . $iq2 . $tid . ',' . $replyto . ',' . $fid . ', ' . $subject . ', ' . $uid . ', ' . $username . ', ' . $dateline . ', ' . $message . ', ' . $ipaddress . ')')) OR sqlerr (__FILE__, 208));
        $pid = mysql_insert_id ();
        (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET replies = replies + 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . $extraquery . ' WHERE tid = ') . sqlesc ($tid)) OR sqlerr (__FILE__, 211));
        (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET posts = posts + 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ' . $fid)) OR sqlerr (__FILE__, 213));
        if ($useparent)
        {
          (sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ' . $thread['deepforumid'])) OR sqlerr (__FILE__, 217));
        }

        include_once INC_PATH . '/readconfig_kps.php';
        kps ('+', $kpscomment, $uid);
        send_sub_mails ();
        (sql_query ('' . 'UPDATE users SET totalposts = totalposts + 1 WHERE id = ' . $uid) OR sqlerr (__FILE__, 222));
      }

      if ((($canpostattachments AND $pid) AND $tid))
      {
        $error = array ();
        $i = 0;
        while ($i < 3)
        {
          if (0 < $_FILES['attachment']['size'][$i])
          {
            if ((!is_uploaded_file ($_FILES['attachment']['tmp_name'][$i]) OR empty ($_FILES['attachment']['tmp_name'][$i])))
            {
              $error[] = $lang->tsf_forums['a_error2'] . ' (' . htmlspecialchars_uni ($_FILES['attachment']['name'][$i]) . ')';
            }
            else
            {
              $ext = get_extension ($_FILES['attachment']['name'][$i]);
              $allowed_ext = explode (',', $f_allowed_types);
              if (!in_array ($ext, $allowed_ext, true))
              {
                $error[] = $lang->tsf_forums['a_error3'] . ' (' . htmlspecialchars_uni ($_FILES['attachment']['name'][$i]) . ')';
              }
              else
              {
                if ($f_upload_maxsize * 1024 < $_FILES['attachment']['size'][$i])
                {
                  $error[] = sprintf ($lang->tsf_forums['a_error4'], mksize ($f_upload_maxsize * 1024)) . ' (' . htmlspecialchars_uni ($_FILES['attachment']['name'][$i]) . ')';
                }
                else
                {
                  if (file_exists ($f_upload_path . $_FILES['attachment']['name'][$i]))
                  {
                    $error[] = $lang->tsf_forums['a_error5'] . ' (' . htmlspecialchars_uni ($_FILES['attachment']['name'][$i]) . ')';
                  }
                  else
                  {
                    $_FILES['attachment']['name'][$i] = str_replace ('.' . $ext, '', $_FILES['attachment']['name'][$i]);
                    $find = array ('/[^a-zA-Z0-9\\s]/', '/\\s+/');
                    $replace = array ('_', '_');
                    $filename = strtolower (preg_replace ($find, $replace, $_FILES['attachment']['name'][$i])) . '.' . $ext;
                    $moved = @move_uploaded_file ($_FILES['attachment']['tmp_name'][$i], $f_upload_path . $filename);
                    if (!$moved)
                    {
                      $error[] = $lang->tsf_forums['a_error2'] . ' (' . htmlspecialchars_uni ($_FILES['attachment']['name'][$i]) . ')';
                    }
                  }
                }
              }
            }

            if (count ($error) == 0)
            {
              $a_name = sqlesc ($filename);
              $a_size = sqlesc (0 + $_FILES['attachment']['size'][$i]);
              sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'attachments (a_name,a_size,a_tid,a_pid) VALUES (' . $a_name . ',' . $a_size . ',' . $tid . ',' . $pid . ')'));
            }
          }

          ++$i;
        }
      }

      $lastpage = get_last_post ($tid);
      redirect ('' . 'tsf_forums/showthread.php?tid=' . $tid . '&amp;page=' . $lastpage . '&amp;pid=' . $pid . '#pid' . $pid, $lang->tsf_forums['post_done'] . '<br />' . ((is_array ($error) AND 0 < count ($error)) ? @implode ('<br />', $error) : ''), '', 6);
      exit ();
    }
  }

  add_breadcrumb ($thread['deepforum'], ($useparent ? 'forumdisplay' : 'index') . ('' . '.php?fid=' . $thread['deepforumid']));
  add_breadcrumb ($thread['currentforum'], '' . 'forumdisplay.php?fid=' . $fid);
  add_breadcrumb (htmlspecialchars_uni ($threadsubject), '' . 'showthread.php?tid=' . $tid);
  add_breadcrumb ($lang->tsf_forums['new_reply']);
  stdhead ('' . $SITENAME . ' TSF FORUMS : ' . TSF_VERSION . ' :: ' . str_replace ('&amp;', '&', $thread['currentforum']), true, 'collapse');
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  build_breadcrumb ();
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '
<form method="post" name="newreply" action="' . $_SERVER['SCRIPT_NAME'] . '" enctype="multipart/form-data">
<input type="hidden" name="tid" value="' . $tid . '">
<input type="hidden" name="replyto" value="' . $replyto . '">';
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  if (isset ($error))
  {
    stdmsg ($lang->global['error'], $error, false);
  }

  if ($array_icon_list = show_icon_list ())
  {
    $postoptionstitle = array ('1' => $lang->tsf_forums['picons1']);
    $postoptions = array ('1' => $array_icon_list);
  }

  if (($moderator OR $forummoderator))
  {
    if ((isset ($postoptionstitle) AND isset ($postoptions)))
    {
      array_push ($postoptionstitle, $lang->tsf_forums['mod_options']);
      array_push ($postoptions, '<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($_POST['closethread'] == 'yes' ? ' checked="checked"' : ($thread['closed'] == 'yes' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_c'] . '</label><br />
				<label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($_POST['stickthread'] == 'yes' ? ' checked="checked"' : ($thread['sticky'] == '1' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_s'] . '</label></span>');
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['mod_options']);
      $postoptions = array ('1' => '<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($_POST['closethread'] == 'yes' ? ' checked="checked"' : ($thread['closed'] == 'yes' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_c'] . '</label><br />
				<label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($_POST['stickthread'] == 'yes' ? ' checked="checked"' : ($thread['sticky'] == '1' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_s'] . '</label></span>');
    }
  }

  if ($canpostattachments)
  {
    if ((isset ($postoptionstitle) AND isset ($postoptions)))
    {
      array_push ($postoptionstitle, $lang->tsf_forums['attachment']);
      array_push ($postoptions, '<label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label>');
      array_push ($postoptionstitle, '<b>' . $lang->tsf_forums['subs'] . ':</b>');
      array_push ($postoptions, '<label><input class="checkbox" name="subscribe" value="yes" type="checkbox"' . ($_POST['subscribe'] == 'yes' ? ' checked="checked"' : '') . '></label> ' . $lang->tsf_forums['isubs']);
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['attachment'], '2' => '<b>' . $lang->tsf_forums['subs'] . ':</b>');
      $postoptions = array ('1' => '<label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label>', '2' => '<label><input class="checkbox" name="subscribe" value="yes" type="checkbox"' . ($_POST['subscribe'] == 'yes' ? ' checked="checked"' : '') . '></label> ' . $lang->tsf_forums['isubs']);
    }
  }
  else
  {
    if ((isset ($postoptionstitle) AND isset ($postoptions)))
    {
      array_push ($postoptionstitle, $lang->tsf_forums['subs'] . ':');
      array_push ($postoptions, '<label><input class="checkbox" name="subscribe" value="yes" type="checkbox"' . ($_POST['subscribe'] == 'yes' ? ' checked="checked"' : '') . '></label> ' . $lang->tsf_forums['isubs']);
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['subs'] . ':');
      $postoptions = array ('1' => '<label><input class="checkbox" name="subscribe" value="yes" type="checkbox"' . ($_POST['subscribe'] == 'yes' ? ' checked="checked"' : '') . '></label> ' . $lang->tsf_forums['isubs']);
    }
  }

  $str .= insert_editor (true, ($_POST['subject'] ? $_POST['subject'] : $threadsubject), (isset ($_POST['message']) ? $_POST['message'] : (isset ($message) ? $message : '')), $lang->tsf_forums['new_reply_head'], $lang->tsf_forums['new_reply_head2'] . htmlspecialchars_uni ($threadsubject), $postoptionstitle, $postoptions);
  echo $str;
  $query = sql_query ('
			SELECT p.*, u.username
			FROM ' . TSF_PREFIX . ('' . 'posts p
			LEFT JOIN users u ON (p.uid=u.id)
			WHERE tid = \'' . $tid . '\'
			ORDER BY dateline DESC LIMIT 0, 5
		'));
  echo '<br />
<table border="0" cellspacing="0" cellpadding="5" class="tborder">
<tr>
<td class="thead" align="center"><strong>' . $lang->tsf_forums['thread_review'] . '</strong></td>
</tr>';
  while ($post = mysql_fetch_assoc ($query))
  {
    $reviewpostdate = my_datee ($dateformat, $post['dateline']) . ' ' . my_datee ($timeformat, $post['dateline']);
    $reviewmessage = format_comment ($post['message']);
    echo '
	<tr>
		<td class="tcat">
			<span class="smalltext"><strong>' . $lang->tsf_forums['posted_by'] . ' ' . $post['username'] . ' - ' . $reviewpostdate . '</strong></span>
		</td>
	</tr>
	<tr>
		<td class="trow1">
			' . $reviewmessage . '
		</td>
	</tr>';
  }

  echo '</table>';
  stdfoot ();
?>
