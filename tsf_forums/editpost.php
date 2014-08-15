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
  if ((!is_valid_id ($tid) OR !is_valid_id ($pid)))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  ($query = sql_query ('SELECT p.modnotice, p.pid, p.tid, p.subject as postsubject, p.uid as posterid, p.message,
			t.subject as threadsubject, t.closed, t.firstpost, t.sticky, f.type, f.name as currentforum, f.fid as currentforumid, ff.name as deepforum, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)	
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE p.tid = ' . sqlesc ($tid) . ' AND p.pid = ' . sqlesc ($pid) . ' LIMIT 0, 1') OR sqlerr (__FILE__, 49));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $thread = mysql_fetch_assoc ($query);
  $tid = 0 + $thread['tid'];
  $pid = 0 + $thread['pid'];
  $fid = 0 + $thread['currentforumid'];
  $ftype = $thread['type'];
  $firstpost = 0 + $thread['firstpost'];
  $threadsubject = ts_remove_badwords ($thread['threadsubject']);
  $postsubject = ts_remove_badwords ($thread['postsubject']);
  $message = $thread['message'];
  $attachment = $display_attachment = '';
  $forummoderator = is_forum_mod (($ftype == 's' ? $thread['deepforumid'] : $fid), $CURUSER['id']);
  if ($permissions[$thread['deepforumid']]['canpostattachments'] == 'yes')
  {
    $canpostattachments = true;
  }

  if (((!$moderator AND !$forummoderator) AND (($permissions[$thread['deepforumid']]['caneditposts'] == 'no' OR $permissions[$thread['deepforumid']]['canview'] == 'no') OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no')))
  {
    print_no_permission ();
    exit ();
  }
  else
  {
    if (((!$moderator AND !$forummoderator) AND $thread['closed'] == 'yes'))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['thread_closed']);
      exit ();
    }
    else
    {
      if (((!$moderator AND !$forummoderator) AND $thread['posterid'] != $CURUSER['id']))
      {
        print_no_permission ();
        exit ();
      }
    }
  }

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
    $dateline = sqlesc (TIMENOW);
    $message = sqlesc ($_POST['message']);
    $closed = (($_POST['closethread'] == 'yes' AND ($moderator OR $forummoderator)) ? 'yes' : 'no');
    $sticky = (($_POST['stickthread'] == 'yes' AND ($moderator OR $forummoderator)) ? 1 : 0);
    $modnotice = trim ($_POST['modnotice']);
    $remove_modnotice = $_POST['remove_modnotice'];
    if (($moderator OR $forummoderator))
    {
      $extraquery = 'UPDATE ' . TSF_PREFIX . 'threads SET closed = ' . sqlesc ($closed) . ', sticky = ' . sqlesc ($sticky) . ' WHERE tid = ' . sqlesc ($tid);
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

    $eq0 = $eq2 = '';
    if (empty ($error))
    {
      if ($usergroups['cansettingspanel'] != 'yes')
      {
        $eq0 = '' . ', edituid = ' . $uid . ', edittime = ' . $dateline;
      }

      if (($moderator OR $forummoderator))
      {
        if ($remove_modnotice == 'yes')
        {
          $eq2 = ', modnotice = \'\', modnotice_info = \'\'';
        }
        else
        {
          if ((!empty ($modnotice) AND $modnotice != $thread['modnotice']))
          {
            $modnotice_info = implode ('~', array ($CURUSER['username'], $CURUSER['id'], TIMENOW));
            $eq2 = ', modnotice = ' . sqlesc ($modnotice) . ', modnotice_info = ' . sqlesc ($modnotice_info);
          }
        }
      }

      (@sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'posts SET subject = ' . $subject . ', message = ' . $message . $eq0 . $eq2 . ' WHERE tid = ') . @sqlesc ($tid) . ' AND pid = ' . @sqlesc ($pid)) OR sqlerr (__FILE__, 156));
      if ($pid == $firstpost)
      {
        @sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET subject = ' . $subject . ' WHERE tid = ') . @sqlesc ($tid));
      }

      @sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET lastpostsubject = ' . $subject . ' WHERE 	lastposttid = ' . $tid . ' AND fid = ' . $fid . ' LIMIT 1'));
      if ((isset ($extraquery) AND ($moderator OR $forummoderator)))
      {
        (@sql_query ($extraquery) OR sqlerr (__FILE__, 165));
      }

      $lastpage = ((isset ($_GET['page']) AND is_valid_id ($_GET['page'])) ? '&amp;page=' . intval ($_GET['page']) : '');
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
                    $filename = preg_replace (array ('' . '#/$#', '/\\s+/'), '_', $_FILES['attachment']['name'][$i]);
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

      redirect ('' . 'tsf_forums/showthread.php?tid=' . $tid . $lastpage . '&amp;pid=' . $pid . '#pid' . $pid, $lang->tsf_forums['post_edited'] . ((is_array ($error) AND 0 < count ($error)) ? @implode ('<br />', $error) : ''), '', 6);
      exit ();
    }
  }

  if (((isset ($_GET['do']) AND $_GET['do'] == 'removefile') AND $aid = intval ($_GET['aid'])))
  {
    if (is_valid_id ($aid))
    {
      delete_attachments ($pid, $tid, $aid);
    }
  }

  $a_query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'attachments WHERE a_pid = ' . sqlesc ($pid) . ' AND a_tid = ' . sqlesc ($tid));
  if (0 < mysql_num_rows ($a_query))
  {
    while ($s_attachments = mysql_fetch_assoc ($a_query))
    {
      $a_array[$s_attachments['a_pid']][] = $s_attachments;
    }
  }

  add_breadcrumb ($thread['deepforum'], '' . 'index.php?fid=' . $thread['deepforumid']);
  add_breadcrumb ($thread['currentforum'], '' . 'forumdisplay.php?fid=' . $fid);
  add_breadcrumb (htmlspecialchars_uni ($threadsubject), '' . 'showthread.php?tid=' . $tid);
  add_breadcrumb (htmlspecialchars_uni ($postsubject), '' . 'showthread.php?tid=' . $tid . '&amp;pid=' . $pid . '#pid' . $pid);
  add_breadcrumb ($lang->tsf_forums['edit_this_post']);
  stdhead ('' . $SITENAME . ' TSF FORUMS : ' . TSF_VERSION . ' :: ' . str_replace ('&amp;', '&', $thread['currentforum']), true, 'collapse');
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  build_breadcrumb ();
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '
<form method="post" name="editpost" action="' . $_SERVER['SCRIPT_NAME'] . ((isset ($_GET['page']) AND is_valid_id ($_GET['page'])) ? '?page=' . intval ($_GET['page']) : '') . '" enctype="multipart/form-data">
<input type="hidden" name="tid" value="' . $tid . '">
<input type="hidden" name="pid" value="' . $pid . '">';
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  if (isset ($error))
  {
    stdmsg ($lang->global['error'], $error, false);
  }

  if (($moderator OR $forummoderator))
  {
    $postoptionstitle = array ('1' => $lang->tsf_forums['mod_options'], '2' => $lang->tsf_forums['modnotice1']);
    $postoptions = array ('1' => '
				<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($_POST['closethread'] == 'yes' ? ' checked="checked"' : ($thread['closed'] == 'yes' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_c'] . '</label><br />
				<label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($_POST['stickthread'] == 'yes' ? ' checked="checked"' : ($thread['sticky'] == '1' ? ' checked="checked"' : '')) . '>' . $lang->tsf_forums['mod_options_s'] . '</label>
				</span>', '2' => '<textarea name="modnotice" id="modnotice" rows="4" cols="70" tabindex="3">' . htmlspecialchars_uni (($_POST['modnotice'] ? $_POST['modnotice'] : $thread['modnotice'])) . '</textarea><br />
				<label><input style="vertical-align: middle;" class="checkbox" name="remove_modnotice" value="yes" tabindex="6" type="checkbox"' . ($_POST['remove_modnotice'] == 'yes' ? ' checked=\'checked\'' : '') . '> ' . $lang->tsf_forums['modnotice2'] . '</label>');
  }

  if ($a_array[$pid])
  {
    $display_attachment = '
		<!-- start: attachments -->
		<label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label>
		<br />
		<fieldset>
			<legend><strong>' . $lang->tsf_forums['a_info'] . '</strong></legend>';
    require_once INC_PATH . '/functions_get_file_icon.php';
    foreach ($a_array[$thread['pid']] as $_a_left => $showperpost)
    {
      $display_attachment .= get_file_icon ($showperpost['a_name']) . ' <a href="attachment.php?aid=' . $showperpost['a_id'] . '&tid=' . $showperpost['a_tid'] . '&pid=' . $thread['pid'] . '" target="_blank">' . htmlspecialchars_uni ($showperpost['a_name']) . '</a> (<b>' . $lang->tsf_forums['a_size'] . '</b>' . mksize ($showperpost['a_size']) . ' / <b>' . $lang->tsf_forums['a_count'] . '</b>' . ts_nf ($showperpost['a_count']) . ') [<a href="' . $_SERVER['SCRIPT_NAME'] . '?tid=' . $tid . '&pid=' . $pid . '&aid=' . $showperpost['a_id'] . '&do=removefile">X</a>]<br />';
    }

    $display_attachment .= '
		</fieldset>
		<!-- end: attachments -->
	';
  }
  else
  {
    $display_attachment = '
	<label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label><br /><label><input name="attachment[]" size="50" type="file"></label>';
  }

  if ($display_attachment != '')
  {
    if ((isset ($postoptionstitle) AND isset ($postoptions)))
    {
      array_push ($postoptionstitle, $lang->tsf_forums['attachment']);
      array_push ($postoptions, '<label>' . $display_attachment . '</label>');
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['attachment']);
      $postoptions = array ('1' => '<label>' . $display_attachment . '</label>');
    }
  }

  $str .= insert_editor (true, ($_POST['subject'] ? $_POST['subject'] : $postsubject), (isset ($_POST['message']) ? $_POST['message'] : (isset ($message) ? $message : '')), $lang->tsf_forums['edit_this_post'], '', $postoptionstitle, $postoptions);
  echo $str;
  stdfoot ();
?>
