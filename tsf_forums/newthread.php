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

  $fid = (isset ($_POST['fid']) ? intval ($_POST['fid']) : (isset ($_GET['fid']) ? intval ($_GET['fid']) : 0));
  $polloptions = (isset ($_POST['polloptions']) ? intval ($_POST['polloptions']) : 4);
  $createpoll = ((isset ($_POST['createpoll']) AND $_POST['createpoll'] == 'yes') ? 'yes' : 'no');
  $canpostattachments = false;
  if (is_valid_id ($fid))
  {
    if (($permissions[$fid]['canview'] == 'no' OR $permissions[$fid]['canpostthreads'] == 'no'))
    {
      print_no_permission (true);
      exit ();
    }
    else
    {
      ($query = @sql_query ('SELECT f.name,f.pid,f.type,ff.name as realforum, ff.fid as realforumid FROM ' . TSF_PREFIX . 'forums f LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid) WHERE f.fid = ' . @sqlesc ($fid)) OR sqlerr (__FILE__, 47));
      if (mysql_num_rows ($query) == 0)
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
        exit ();
      }

      $realforum = mysql_result ($query, 0, 'realforum');
      $realforumid = mysql_result ($query, 0, 'realforumid');
      $forumname = mysql_result ($query, 0, 'f.name');
      $parent = mysql_result ($query, 0, 'f.pid');
      $type = mysql_result ($query, 0, 'f.type');
      $forummoderator = is_forum_mod (($type == 's' ? $realforumid : $fid), $CURUSER['id']);
      if ($permissions[$parent]['canpostattachments'] == 'yes')
      {
        $canpostattachments = true;
      }

      if (($permissions[$parent]['canview'] == 'no' OR $permissions[$parent]['canpostthreads'] == 'no'))
      {
        print_no_permission (true);
        exit ();
      }
      else
      {
        if ($type == 'c')
        {
          stderr ($lang->global['error'], $lang->tsf_forums['cant_post']);
          exit ();
        }
      }

      $useparent = false;
      if ($type == 's')
      {
        $useparent = true;
      }

      add_breadcrumb ($realforum, ($useparent ? 'forumdisplay' : 'index') . ('' . '.php?fid=' . $realforumid));
      add_breadcrumb ($forumname, '' . 'forumdisplay.php?fid=' . $fid);
      add_breadcrumb ($lang->tsf_forums['new_thread']);
    }
  }
  else
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
    exit ();
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
      $iq1 = $iq2 = '';
      $iconid = intval ($_POST['iconid']);
      if (is_valid_id ($iconid))
      {
        $iq1 = 'iconid,';
        $iq2 = '' . $iconid . ',';
      }

      (@sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'posts (' . $iq1 . 'fid,subject,uid,username,dateline,message,ipaddress) VALUES (' . $iq2 . $fid . ', ' . $subject . ', ' . $uid . ', ' . $username . ', ' . $dateline . ', ' . $message . ', ' . $ipaddress . ')')) OR sqlerr (__FILE__, 130));
      $pid = mysql_insert_id ();
      (@sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'threads (' . $iq1 . 'fid,subject,uid,username,dateline,firstpost,lastpost,lastposter,lastposteruid,closed,sticky) VALUES (' . $iq2 . $fid . ',' . $subject . ',' . $uid . ',' . $username . ',' . $dateline . ',' . $pid . ',' . $dateline . ',' . $username . ',' . $uid . ',') . @sqlesc ($closed) . ('' . ',' . $sticky . ')')) OR sqlerr (__FILE__, 133));
      $tid = mysql_insert_id ();
      if ($subscribe)
      {
        sql_query ('INSERT INTO ' . TSF_PREFIX . 'subscribe (tid,userid) VALUES (' . sqlesc ($tid) . ',' . $uid . ')');
      }

      (@sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'posts SET tid = ' . $tid . ' WHERE pid = ' . $pid)) OR sqlerr (__FILE__, 141));
      (@sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET threads = threads + 1, posts = posts + 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ' . $fid)) OR sqlerr (__FILE__, 143));
      if ($useparent)
      {
        (@sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ' . $subject . ' WHERE fid = ' . $realforumid)) OR sqlerr (__FILE__, 147));
      }

      (@sql_query ('' . 'UPDATE users SET totalposts = totalposts + 1 WHERE id = ' . $uid) OR sqlerr (__FILE__, 150));
      include_once INC_PATH . '/readconfig_kps.php';
      kps ('+', $kpscomment, $uid);
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

      if (($createpoll == 'yes' AND $usergroups['cancreatepoll'] == 'yes'))
      {
        redirect ('' . 'tsf_forums/poll.php?do=new&amp;tid=' . $tid . '&amp;polloptions=' . $polloptions, $lang->tsf_forums['poll10'] . '<br />' . ((is_array ($error) AND 0 < count ($error)) ? @implode ('<br />', $error) : ''), '', 6);
        exit ();
      }

      redirect ('' . 'tsf_forums/showthread.php?tid=' . $tid, $lang->tsf_forums['thread_created'] . '<br />' . ((is_array ($error) AND 0 < count ($error)) ? @implode ('<br />', $error) : ''), '', 6);
      exit ();
    }
  }

  $new_thread_in = sprintf ($lang->tsf_forums['new_thread_in'], str_replace ('&amp;', '&', $forumname));
  stdhead ($new_thread_in);
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  build_breadcrumb ();
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

  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '
<form method="post" name="newthread" action="' . $_SERVER['SCRIPT_NAME'] . '" enctype="multipart/form-data">
<input type="hidden" name="fid" value="' . $fid . '">';
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
      array_push ($postoptions, '<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($_POST['closethread'] == 'yes' ? ' checked="checked"' : '') . '>' . $lang->tsf_forums['mod_options_c'] . '</label><br /><label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($_POST['stickthread'] == 'yes' ? ' checked="checked"' : '') . '>' . $lang->tsf_forums['mod_options_s'] . '</label></span>');
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['mod_options']);
      $postoptions = array ('1' => '
					<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($_POST['closethread'] == 'yes' ? ' checked="checked"' : '') . '>' . $lang->tsf_forums['mod_options_c'] . '</label><br />
					<label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($_POST['stickthread'] == 'yes' ? ' checked="checked"' : '') . '>' . $lang->tsf_forums['mod_options_s'] . '</label></span>');
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

  if ($usergroups['cancreatepoll'] == 'yes')
  {
    if ((isset ($postoptionstitle) AND isset ($postoptions)))
    {
      array_push ($postoptionstitle, $lang->tsf_forums['poll1'] . ':');
      array_push ($postoptions, '<label><input class="checkbox" name="createpoll" value="yes" type="checkbox"' . ($createpoll == 'yes' ? ' checked="checked"' : '') . '> ' . $lang->tsf_forums['poll2'] . '</label><br />' . $lang->tsf_forums['poll3'] . ' <label><input size="2" name="polloptions" value="' . $polloptions . '" type="text"></label>');
    }
    else
    {
      $postoptionstitle = array ('1' => $lang->tsf_forums['poll1'] . ':');
      $postoptions = array ('1' => '<label><input class="checkbox" name="createpoll" value="yes" type="checkbox"' . ($createpoll == 'yes' ? ' checked="checked"' : '') . '> ' . $lang->tsf_forums['poll2'] . '</label><br />' . $lang->tsf_forums['poll3'] . ' <label><input size="2" name="polloptions" value="' . $polloptions . '" type="text"></label>');
    }
  }

  $str .= insert_editor (true, ($_POST['subject'] ? $_POST['subject'] : ''), (isset ($_POST['message']) ? $_POST['message'] : ''), $lang->tsf_forums['new_thread_head'], $new_thread_in, $postoptionstitle, $postoptions);
  echo $str;
  stdfoot ();
?>
