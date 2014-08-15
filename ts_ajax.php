<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function isvalidusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  function show_response ($message)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/plain; charset=' . $shoutboxcharset);
    exit ($message);
  }

  function show_msg ($message = '', $error = true, $color = 'red', $strong = true, $extra = '', $extra2 = '')
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    if ($error)
    {
      exit ('<error>' . $message . '</error>');
    }

    exit ($extra . (!empty ($color) ? '<font color="' . $color . '">' : '') . ($strong ? '<strong>' : '') . $message . ($strong ? '</strong>' : '') . (!empty ($color) ? '</font>' : '') . $extra2);
  }

  function is_forum_mod ($forumid = 0, $userid = 0)
  {
    if ((!$forumid OR !$userid))
    {
      return false;
    }

    $query = sql_query ('SELECT userid FROM ' . TSF_PREFIX . ('' . 'moderators WHERE forumid=' . $forumid . ' AND userid=' . $userid));
    return (0 < mysql_num_rows ($query) ? true : false);
  }

  function allowcomments ($torrentid = 0)
  {
    global $usergroups;
    global $is_mod;
    if ($is_mod)
    {
      return true;
    }

    $query = @sql_query ('' . 'SELECT allowcomments FROM torrents WHERE id = ' . $torrentid);
    $allowcomments = @mysql_result ($query, 0, 'allowcomments');
    if ($allowcomments != 'yes')
    {
      return false;
    }

    return true;
  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  require_once 'global.php';
  gzip ();
  dbconn ();
  define ('TS_AJAX_VERSION', '1.2.2 ');
  define ('NcodeImageResizer', true);
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ();
  }

  if (((strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST' AND $_GET['action'] != 'quick_edit') AND $_GET['action'] != 'autocomplete'))
  {
    exit ();
  }

  $is_mod = is_mod ($usergroups);
  if ((isset ($_GET['action']) AND $_GET['action'] == 'autocomplete'))
  {
    $keyword = trim ($_GET['keyword']);
    $field = htmlspecialchars_uni ($_GET['field']);
    $type = htmlspecialchars_uni ($_GET['type']);
    $fieldtypes = array ('name', 'username');
    $types = array ('torrent', 'users');
    if (((2 < strlen ($keyword) AND in_array ($field, $fieldtypes, true)) AND in_array ($type, $types, true)))
    {
      $matches = array ();
      if ($type == 'torrent')
      {
        $query = sql_query ('SELECT name FROM torrents WHERE name LIKE ' . sqlesc ('%' . $keyword . '%') . ' OR descr LIKE ' . sqlesc ('%' . $keyword . '%') . ('' . ' LIMIT ' . $ts_perpage));
      }
      else
      {
        if ($type == 'users')
        {
          $query = sql_query ('SELECT username FROM users WHERE username LIKE ' . sqlesc ('%' . $keyword . '%'));
        }
        else
        {
          exit ();
        }
      }

      if (0 < mysql_num_rows ($query))
      {
        while ($results = mysql_fetch_assoc ($query))
        {
          $matches[] = htmlspecialchars_uni ($results[$field]);
        }
      }

      $response = join ('
', $matches);
      show_response ($response);
      unset ($response);
      unset ($matches);
      return 1;
    }
  }
  else
  {
    if ((isset ($_POST['action']) AND $_POST['action'] == 'save_quick_edit'))
    {
      $lang->load ('comment');
      $commentid = intval ($_POST['cid']);
      if (!is_valid_id ($commentid))
      {
        show_msg ($lang->global['notorrentid']);
      }

      if ($usergroups['cancomment'] != 'yes')
      {
        show_msg ($lang->global['nopermission']);
      }

      $query = sql_query ('SELECT cancomment FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id']));
      if (0 < mysql_num_rows ($query))
      {
        $commentperm = mysql_fetch_assoc ($query);
        if ($commentperm['cancomment'] == '0')
        {
          show_msg ($lang->global['nopermission']);
        }
      }

      $res = sql_query ('SELECT c.text, c.user, t.id as torrentid FROM comments AS c JOIN torrents AS t ON c.torrent = t.id WHERE c.id= ' . sqlesc ($commentid));
      $arr = mysql_fetch_assoc ($res);
      if (!$arr)
      {
        show_msg ($lang->global['notorrentid']);
      }

      if (($arr['user'] != $CURUSER['id'] AND !$is_mod))
      {
        show_msg ($lang->global['nopermission']);
      }

      if (allowcomments ($arr['torrentid']) == false)
      {
        show_msg ($lang->comment['closed']);
      }

      if ($_POST['text'] != $arr['text'])
      {
        $msgtext = urldecode ($_POST['text']);
        if ($msgtext == '')
        {
          show_msg ($lang->global['dontleavefieldsblank']);
        }

        if (strtolower ($shoutboxcharset) != 'utf-8')
        {
          if (function_exists ('iconv'))
          {
            $msgtext = iconv ('UTF-8', $shoutboxcharset, $msgtext);
          }
          else
          {
            if (function_exists ('mb_convert_encoding'))
            {
              $msgtext = mb_convert_encoding ($msgtext, $shoutboxcharset, 'UTF-8');
            }
            else
            {
              if (strtolower ($shoutboxcharset) == 'iso-8859-1')
              {
                $msgtext = utf8_decode ($msgtext);
              }
            }
          }
        }

        $editedat = get_date_time ();
        sql_query ('UPDATE comments SET text = ' . sqlesc ($msgtext) . ', editedat=' . sqlesc ($editedat) . ', editedby=' . sqlesc ($CURUSER['id']) . ' WHERE id= ' . sqlesc ($commentid));
        $edit_date = my_datee ($dateformat, $editedat);
        $edit_time = my_datee ($timeformat, $editedat);
        $p_text = '<p><font size=\'1\' class=\'small\'>' . $lang->global['lastedited'] . ' <a href=\'' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . '\'><b>' . $CURUSER['username'] . ('' . '</b></a> ' . $edit_date . ' ' . $edit_time . '</font></p>
');
      }

      show_msg (format_comment ($_POST['text']) . $p_text, false, NULL, false);
      return 1;
    }

    if ((isset ($_GET['action']) AND $_GET['action'] == 'quick_edit'))
    {
      $lang->load ('comment');
      $commentid = intval ($_GET['cid']);
      if (!is_valid_id ($commentid))
      {
        show_msg ($lang->global['notorrentid']);
      }

      if ($usergroups['cancomment'] != 'yes')
      {
        show_msg ($lang->global['nopermission']);
      }

      $query = sql_query ('SELECT cancomment FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id']));
      if (0 < mysql_num_rows ($query))
      {
        $commentperm = mysql_fetch_assoc ($query);
        if ($commentperm['cancomment'] == '0')
        {
          show_msg ($lang->global['nopermission']);
        }
      }

      $res = sql_query ('SELECT c.text, c.user, t.id as torrentid FROM comments AS c JOIN torrents AS t ON c.torrent = t.id WHERE c.id= ' . sqlesc ($commentid));
      $arr = mysql_fetch_assoc ($res);
      if (!$arr)
      {
        show_msg ($lang->global['notorrentid']);
      }

      if (($arr['user'] != $CURUSER['id'] AND !$is_mod))
      {
        show_msg ($lang->global['nopermission']);
      }

      if (allowcomments ($arr['torrentid']) == false)
      {
        show_msg ($lang->comment['closed']);
      }

      show_msg (htmlspecialchars_uni ($arr['text']), false, NULL, false);
      return 1;
    }

    if ((((isset ($_POST['ajax_quick_reply']) AND isset ($_POST['tid'])) AND isset ($_POST['message'])) AND $CURUSER))
    {
      if ((($usergroups['isforummod'] == 'yes' OR $usergroups['cansettingspanel'] == 'yes') OR $usergroups['issupermod'] == 'yes'))
      {
        $moderator = true;
      }
      else
      {
        $moderator = false;
      }

      $lang->load ('tsf_forums');
      $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : 0);
      if (!is_valid_id ($tid))
      {
        show_msg ($lang->tsf_forums['invalid_tid']);
      }

      ($query = mysql_query ('SELECT
			t.subject as threadsubject, t.closed, t.sticky, f.type, f.name as currentforum, f.fid as currentforumid, ff.name as deepforum, ff.fid as deepforumid
			FROM ' . TSF_PREFIX . 'threads t
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($tid) . ' LIMIT 0, 1') OR show_msg ('dberror1'));
      if (mysql_num_rows ($query) == 0)
      {
        show_msg ($lang->tsf_forums['invalid_tid']);
      }

      $thread = mysql_fetch_assoc ($query);
      $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
      ($query = mysql_query ('SELECT * FROM ' . TSF_PREFIX . 'forumpermissions WHERE gid = ' . sqlesc ($CURUSER['usergroup'])) OR show_msg ('dberror2'));
      while ($perm = mysql_fetch_assoc ($query))
      {
        $permissions[$perm['fid']] = $perm;
      }

      if (($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canpostreplys'] == 'no'))
      {
        show_msg ($lang->tsf_forums['invalid_tid']);
      }
      else
      {
        if ((($thread['closed'] == 'yes' AND !$moderator) AND !$forummoderator))
        {
          show_msg ($lang->tsf_forums['thread_closed']);
        }
      }

      $useparent = false;
      if ($thread['type'] == 's')
      {
        $useparent = true;
      }

      $subject = $lang->tsf_forums['re'] . $thread['threadsubject'];
      $threadsubject = ts_remove_badwords ($subject);
      $replyto = 0;
      $fid = 0 + $thread['currentforumid'];
      $error = '';
      $uid = sqlesc ($CURUSER['id']);
      $username = sqlesc ($CURUSER['username']);
      $dateline = sqlesc (TIMENOW);
      $message = urldecode ($_POST['message']);
      $message = strval ($message);
      if (strtolower ($shoutboxcharset) != 'utf-8')
      {
        if (function_exists ('iconv'))
        {
          $message = iconv ('UTF-8', $shoutboxcharset, $message);
        }
        else
        {
          if (function_exists ('mb_convert_encoding'))
          {
            $message = mb_convert_encoding ($message, $shoutboxcharset, 'UTF-8');
          }
          else
          {
            if (strtolower ($shoutboxcharset) == 'iso-8859-1')
            {
              $message = utf8_decode ($message);
            }
          }
        }
      }

      $ipaddress = sqlesc ($CURUSER['ip']);
      $closed = (($_POST['closethread'] == '1' AND ($moderator OR $forummoderator)) ? 'yes' : 'no');
      $sticky = (($_POST['stickthread'] == '1' AND ($moderator OR $forummoderator)) ? 1 : 0);
      $subscribe = ($_POST['subscribe'] == 'yes' ? 1 : 0);
      if ($subscribe)
      {
        ($query = mysql_query ('SELECT userid FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid) . ' AND userid = ' . $uid) OR show_msg ('dberror3'));
        if (mysql_num_rows ($query) == 0)
        {
          (mysql_query ('INSERT INTO ' . TSF_PREFIX . 'subscribe (tid,userid) VALUES (' . sqlesc ($tid) . ',' . $uid . ')') OR show_msg ('dberror4'));
        }
      }

      if (($moderator OR $forummoderator))
      {
        $extraquery = ', closed = ' . sqlesc ($closed) . ', sticky = ' . sqlesc ($sticky);
      }

      include_once INC_PATH . '/readconfig_forumcp.php';
      if (strlen ($_POST['message']) < $f_minmsglength)
      {
        show_msg ($lang->tsf_forums['too_short']);
      }

      $query = sql_query ('SELECT dateline FROM ' . TSF_PREFIX . 'posts WHERE uid = ' . sqlesc ($CURUSER['id']) . ' ORDER by dateline DESC LIMIT 1');
      if (0 < mysql_num_rows ($query))
      {
        $last_post = mysql_result ($query, 0, 'dateline');
      }

      $floodcheck = flood_check ($lang->tsf_forums['a_post'], $last_post, true);
      if ($floodcheck != '')
      {
        show_msg (str_replace (array ('<font color="#9f040b" size="2">', '</font>', '<b>', '</b>'), '', $floodcheck));
      }

      $stop = false;
      $dp_query = sql_query ('SELECT lastpost FROM ' . TSF_PREFIX . ('' . 'threads WHERE lastposteruid = ' . $uid . ' AND tid=') . sqlesc ($tid) . ' LIMIT 1');
      $dp_thread = @mysql_fetch_assoc ($dp_query);
      if (60 * 60 < time () - $dp_thread['lastpost'])
      {
        $stop = true;
      }

      if (((($dp_thread AND !$stop) AND !$moderator) AND !$forummoderator))
      {
        $query = sql_query ('SELECT pid, message FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid) . ('' . ' AND uid = ' . $uid . ' AND dateline = ') . sqlesc ($dp_thread['lastpost']) . ' ORDER BY pid DESC LIMIT 1');
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

        $message = $oldmessage . $eol . $eol . $message;
        sql_query ('UPDATE ' . TSF_PREFIX . 'posts SET message = ' . sqlesc ($message) . ' WHERE pid = ' . sqlesc ($pid));
      }
      else
      {
        (mysql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'posts (tid,replyto,fid,subject,uid,username,dateline,message,ipaddress' . $eq1 . ') VALUES (' . $tid . ',' . $replyto . ',' . $fid . ', ') . sqlesc ($subject) . ('' . ', ' . $uid . ', ' . $username . ', ' . $dateline . ', ') . sqlesc ($message) . ('' . ', ' . $ipaddress . ')')) OR show_msg ('dberror5'));
        $pid = mysql_insert_id ();
        (mysql_query ('UPDATE ' . TSF_PREFIX . ('' . 'threads SET replies = replies + 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . $extraquery . ' WHERE tid = ') . sqlesc ($tid)) OR show_msg ('dberror6'));
        (mysql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET posts = posts + 1, lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ') . sqlesc ($subject) . ('' . ' WHERE fid = ' . $fid)) OR show_msg ('dberror7'));
        if ($useparent)
        {
          (mysql_query ('UPDATE ' . TSF_PREFIX . ('' . 'forums SET lastpost = ' . $dateline . ', lastposter = ' . $username . ', lastposteruid = ' . $uid . ', lastposttid = ' . $tid . ', lastpostsubject = ') . sqlesc ($subject) . ('' . ' WHERE fid = ' . $thread['deepforumid'])) OR show_msg ('dberror7'));
        }

        (mysql_query ('' . 'UPDATE users SET totalposts = totalposts + 1 WHERE id = ' . $uid) OR show_msg ('dberror8'));
        mysql_query ('REPLACE INTO ' . TSF_PREFIX . ('' . 'threadsread SET tid=\'' . $tid . '\', uid=\'') . $CURUSER['id'] . '\', dateline=\'' . TIMENOW . '\'');
        include_once INC_PATH . '/readconfig_kps.php';
        kps ('+', $kpscomment, $CURUSER['id']);
      }

      $lastseen = my_datee ($dateformat, $CURUSER['last_access']) . ' ' . my_datee ($timeformat, $CURUSER['last_access']);
      $downloaded = mksize ($CURUSER['downloaded']);
      $uploaded = mksize ($CURUSER['uploaded']);
      include_once INC_PATH . '/functions_ratio.php';
      $ratio = get_user_ratio ($CURUSER['uploaded'], $CURUSER['downloaded']);
      $ratio = str_replace ('\'', '\\\'', $ratio);
      if ((((preg_match ('#I3#is', $CURUSER['options']) OR preg_match ('#I4#is', $CURUSER['options'])) AND !$moderator) AND !$forummoderator))
      {
        $tooltip = $lang->tsf_forums['deny'];
      }
      else
      {
        $tooltip = sprintf ($lang->tsf_forums['tooltip'], $lastseen, $downloaded, $uploaded, $ratio);
      }

      $poster = '' . '<a href="' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . '" onmouseover="ddrivetip(\'' . $tooltip . '\', 200)"; onmouseout="hideddrivetip()">' . get_user_color (htmlspecialchars_uni ($CURUSER['username']), $usergroups['namestyle']) . '</a>';
      include_once INC_PATH . '/functions_icons.php';
      $usericons = get_user_icons (array_merge ($CURUSER, $usergroups));
      if (!empty ($CURUSER['title']))
      {
        $usertitle = '<font class="smalltext"><strong>' . htmlspecialchars_uni ($CURUSER['title']) . '</strong></font><br />';
      }

      $poster_title = $lang->tsf_forums['usergroup'] . $usergroups['title'];
      if (preg_match ('#D1#is', $CURUSER['options']))
      {
        $avatar = get_user_avatar ($CURUSER['avatar']);
      }

      $png = 'rank_0';
      if ((10 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 30))
      {
        $png = 'rank_1';
      }
      else
      {
        if ((30 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 70))
        {
          $png = 'rank_2';
        }
        else
        {
          if ((70 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 120))
          {
            $png = 'rank_3';
          }
          else
          {
            if ((120 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 170))
            {
              $png = 'rank_4';
            }
            else
            {
              if ((170 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 250))
              {
                $png = 'rank_5';
              }
              else
              {
                if ((250 < $CURUSER['totalposts'] AND $CURUSER['totalposts'] <= 500))
                {
                  $png = 'rank_6';
                }
                else
                {
                  if (500 < $CURUSER['totalposts'])
                  {
                    $png = 'rank_postwhore';
                  }
                }
              }
            }
          }
        }
      }

      if ($CURUSER['enabled'] != 'yes')
      {
        $png = 'rank_banned';
      }
      else
      {
        if (in_array ($CURUSER['usergroup'], array (UC_SUPERMOD, UC_MODERATOR, UC_FORUMMOD)))
        {
          $png = 'rank_moderator';
        }
        else
        {
          if (in_array ($CURUSER['usergroup'], array (UC_STAFFLEADER, UC_SYSOP, UC_ADMINISTRATOR)))
          {
            $png = 'rank_admin';
          }
          else
          {
            if ($CURUSER['usergroup'] == UC_UPLOADER)
            {
              $png = 'rank_founder';
            }
            else
            {
              if (($CURUSER['usergroup'] == UC_VIP OR $CURUSER['donor'] == 'yes'))
              {
                $png = 'rank_mvp';
              }
            }
          }
        }
      }

      $user_rank = '<img src="images/ranks/' . $png . '.gif" border="0">';
      $join_date = $lang->tsf_forums['jdate'] . my_datee ($regdateformat, $CURUSER['added']);
      $totalposts = $lang->tsf_forums['totalposts'] . ts_nf ($CURUSER['totalposts'] + 1);
      $status = $lang->tsf_forums['status'] . $lang->tsf_forums['user_online'];
      ($query = @mysql_query ('SELECT flagpic,name as countryname FROM countries WHERE id = ' . @sqlesc ($CURUSER['country'])) OR show_msg ('dberror9'));
      if (0 < mysql_num_rows ($query))
      {
        $CURUSER['countryname'] = mysql_result ($query, 0, 'countryname');
        $CURUSER['flagpic'] = mysql_result ($query, 0, 'flagpic');
      }

      $country = '' . $lang->tsf_forums['country'] . '<img src=\'' . $BASEURL . '/' . $pic_base_url . 'flag/' . $CURUSER[flagpic] . '\' alt=\'' . $CURUSER[countryname] . '\' title=\'' . $CURUSER[countryname] . '\' style=\'margin-center: 2pt\' height=\'10px\' class=\'inlineimg\'>';
      if ((!empty ($CURUSER['signature']) AND preg_match ('#H1#is', $CURUSER['options'])))
      {
        $signature = '<hr align="left" size="1" width="65%">' . format_comment ($CURUSER['signature']);
      }

      $deletebutton = '<input value="' . $lang->tsf_forums['delete_post'] . '" onclick="jumpto(\'deletepost.php?tid=' . $tid . '\\&amp;pid=' . $pid . '&amp;page=' . intval ($_POST['page']) . '\');" type="button">';
      $editbutton = '<input value="' . $lang->tsf_forums['edit_post'] . '" onclick="jumpto(\'editpost.php?tid=' . $tid . '\\&amp;pid=' . $pid . '&amp;page=' . intval ($_POST['page']) . '\');" type="button">';
      $post_date = my_datee ($dateformat, time ()) . ' ' . my_datee ($timeformat, time ());
      define ('IS_THIS_USER_POSTED', true);
      $str2 = '
		<!-- start: post#' . $pid . ' -->
		<br />
		<table width="100%" border="0" cellspacing="0" cellpadding="5" style="clear: both;">
		<tr>
				<td colspan="2" class="subheader">
					<div style="float: right;">
						<strong>' . $lang->tsf_forums['post'] . '<a href="#pid' . $pid . '">#' . intval ($_POST['postcount']) . '</a>' . ($usergroups['canmassdelete'] === 'yes' ? ' <input type="checkbox" name="postids[]" value="' . $pid . '" style="margin: 0px 0px 0px 5px; padding: 0px; vertical-align: middle;">' : '') . '</strong>
					</div>
					<div style="float: left;">
						<a name="pid' . $pid . '" id="pid' . $pid . '"><img src="./images/post_old.gif" border="0" class="inlineimg"></a> ' . $post_date . '
					</div>
				</td>
			</tr>
			<tr>
				<td class="trow1" style="text-align: center;" valign="top" width="20%">
					' . $poster . ' ' . $usericons . '<br />
					' . $usertitle . '
					' . $poster_title . '<br />
					' . $avatar . '<br />
					' . $user_rank . '<br />
					' . $join_date . '<br />
					' . $totalposts . '<br />
					' . $status . '<br />
					' . $country . '<br />
				</td>
				<td class="trow1" style="text-align: left;" valign="top" width="80%">
					<span class="smalltext"><strong>' . htmlspecialchars_uni ($threadsubject) . '</strong></span><hr />
					<div id="pid_' . $pid . '">
						<p>
							' . format_comment ($message) . '
						</p>
					</div>
					<div style="text-align: right; vertical-align: bottom;">
				</div>
					' . $signature . '
				</td>
			</tr>
			<tr>
				<td class="trow1" width="15%" valign="middle" style="white-space: nowrap; text-align: center;">
					<input value="' . $lang->tsf_forums['top'] . '" onclick="self.scrollTo(0, 0); return false;" type="button"> <input value="' . $lang->tsf_forums['report_post'] . '" onclick="jumpto(\'' . $BASEURL . '/report.php?action=reportforumpost&amp;reportid=' . $pid . '\');" type="button">
				</td>
				<td class="trow1" style="text-align: center;" valign="top">
					<div style="float: right;">
						' . $deletebutton . '
						' . $editbutton . '
					</div>
				</td>
			</tr>
		</table>
		<!-- end: post#' . $pid . ' -->
	';
      function send_sub_mails ()
      {
        global $CURUSER;
        global $SITENAME;
        global $SITEEMAIL;
        global $BASEURL;
        global $tid;
        global $subject;
        global $lang;
        global $rootpath;
        require_once INC_PATH . '/functions_pm.php';
        $query = sql_query ('SELECT s.*, u.email, u.username FROM ' . TSF_PREFIX . 'subscribe s LEFT JOIN users u ON (s.userid=u.id) WHERE s.tid = ' . sqlesc ($tid) . ' AND s.userid != ' . sqlesc ($CURUSER['id']));
        if (0 < mysql_num_rows ($query))
        {
          while ($sub = mysql_fetch_assoc ($query))
          {
            send_pm ($sub['userid'], sprintf ($lang->tsf_forums['msubs'], $sub['username'], $subject, $CURUSER['username'], $BASEURL, $tid, $SITENAME), $subject);
            sent_mail ($sub['email'], $subject, sprintf ($lang->tsf_forums['msubs'], $sub['username'], $subject, $CURUSER['username'], $BASEURL, $tid, $SITENAME), 'subs', false);
          }
        }

      }

      send_sub_mails ();
      show_msg ($str2, false, '', false);
      return 1;
    }

    if ((((isset ($_POST['ajax_quick_comment']) AND isset ($_POST['id'])) AND isset ($_POST['text'])) AND $CURUSER))
    {
      if ($usergroups['cancomment'] != 'yes')
      {
        show_msg ($lang->global['nopermission']);
      }

      $query = sql_query ('SELECT cancomment FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id']));
      if (0 < mysql_num_rows ($query))
      {
        $commentperm = mysql_fetch_assoc ($query);
        if ($commentperm['cancomment'] == '0')
        {
          show_msg ($lang->global['nopermission']);
        }
      }

      $torrentid = intval ($_POST['id']);
      $lang->load ('comment');
      if (allowcomments ($torrentid) == false)
      {
        show_msg ($lang->comment['closed']);
      }

      $text = urldecode ($_POST['text']);
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

      $query = sql_query ('SELECT added FROM comments WHERE user = ' . sqlesc ($CURUSER['id']) . ' ORDER by added DESC LIMIT 1');
      if (0 < mysql_num_rows ($query))
      {
        $last_comment = mysql_result ($query, 0, 'added');
      }

      $floodmsg = flood_check ($lang->comment['floodcomment'], $last_comment, true);
      $res = mysql_query ('SELECT name, owner FROM torrents WHERE id = ' . sqlesc ($torrentid));
      $arr = mysql_fetch_assoc ($res);
      if (!empty ($floodmsg))
      {
        show_msg (str_replace (array ('<font color="#9f040b" size="2">', '</font>', '<b>', '</b>'), '', $floodmsg));
      }
      else
      {
        if (!$arr)
        {
          show_msg ($lang->global['notorrentid']);
        }
        else
        {
          if (((empty ($text) OR empty ($torrentid)) OR !is_valid_id ($torrentid)))
          {
            show_msg ($lang->global['dontleavefieldsblank']);
          }
        }
      }

      $commentposted = false;
      if (!$is_mod)
      {
        $query = mysql_query ('SELECT id, user, text FROM comments WHERE torrent = ' . sqlesc ($torrentid) . ' ORDER by added DESC LIMIT 1');
        if (0 < mysql_num_rows ($query))
        {
          $lastcommentuserid = mysql_result ($query, 0, 'user');
          if ($lastcommentuserid == $CURUSER['id'])
          {
            $oldtext = mysql_result ($query, 0, 'text');
            $newid = $cid = mysql_result ($query, 0, 'id');
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

            $newtext = $text = $oldtext . $eol . $eol . $text;
            mysql_query ('UPDATE comments SET text = ' . sqlesc ($newtext) . ('' . ' WHERE id = \'' . $newid . '\''));
            if (mysql_affected_rows ())
            {
              $commentposted = true;
            }
          }
        }
      }

      if (!$commentposted)
      {
        mysql_query ('INSERT INTO comments (user, torrent, added, text) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($torrentid) . ', ' . sqlesc (get_date_time ()) . ', ' . sqlesc ($text) . ')');
        $cid = mysql_insert_id ();
        mysql_query ('UPDATE torrents SET comments = comments + 1 WHERE id = ' . sqlesc ($torrentid));
        $ras = mysql_query ('SELECT options FROM users WHERE id = ' . sqlesc ($arr['owner']));
        $arg = mysql_fetch_assoc ($ras);
        if ((preg_match ('#C1#is', $arg['options']) AND $CURUSER['id'] != $arr['owner']))
        {
          require_once INC_PATH . '/functions_pm.php';
          send_pm ($arr['owner'], sprintf ($lang->comment['newcommenttxt'], '[url=' . $BASEURL . '/details.php?id=' . $torrentid . '#startcomments]' . $arr['name'] . '[/url]'), $lang->comment['newcommentsub']);
        }

        include_once INC_PATH . '/readconfig_kps.php';
        kps ('+', '' . $kpscomment, $CURUSER['id']);
      }

      include_once INC_PATH . '/functions_ratio.php';
      $p_edit = '<a href="' . $BASEURL . '/' . $edit . 'comment.php?action=edit&amp;cid=' . $cid . '&amp;page=' . intval ($_GET['page']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'p_edit.gif" border="0" class="inlineimg" /></a>';
      if ($is_mod)
      {
        $p_delete = '<a href="' . $BASEURL . '/comment.php?action=delete&amp;cid=' . $cid . '&amp;tid=' . $torrentid . '&amp;page=' . intval ($_GET['page']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'p_delete.gif" border="0" class="inlineimg" /></a>';
        $p_commenthistory = '<div style="float: left;"><input type="button" class="button" value="View Comment History" onclick="jumpto(\'' . $BASEURL . '/userhistory.php?action=viewcomments&id=' . $CURUSER['id'] . '\'); return false;" /></div>';
      }

      if (($is_mod OR $usergroups['canreport'] == 'yes'))
      {
        $p_report = '<a href="' . $BASEURL . '/report.php?action=reportcomment&amp;reportid=' . $cid . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'report.gif" border="0" title="' . $lang->global['reportcomment'] . '" style="display: inline;" id="report_image_' . $cid . '" class="inlineimg" /></a>';
      }

      $signature = (!empty ($CURUSER['signature']) ? '<br /><hr size="1" width="50%"  align="left" />' . format_comment ($CURUSER['signature'], true, true, true, true, 'signatures') : '');
      $textbody = format_comment ($text);
      $IsUserOnline = '<img src="' . $BASEURL . '/' . $pic_base_url . 'user_online.gif" border="0" class="inlineimg" />';
      $SendPM = ' <a href="' . $BASEURL . '/sendmessage.php?receiver=' . $CURUSER['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'pm.gif" border="0" title="' . $lang->global['sendmessageto'] . htmlspecialchars_uni ($CURUSER['username']) . '" class="inlineimg" /></a>';
      $Ratio = get_user_ratio ($CURUSER['uploaded'], $CURUSER['downloaded']);
      $UserStats = '<b>' . $lang->global['added'] . ':</b> ' . my_datee ($regdateformat, $CURUSER['added']) . '<br /><b>' . $lang->global['uploaded'] . '</b> ' . mksize ($CURUSER['uploaded']) . '<br /><b>' . $lang->global['downloaded'] . '</b> ' . mksize ($CURUSER['downloaded']) . '<br /><b>' . $lang->global['ratio'] . '</b> ' . strip_tags ($Ratio);
      $OnMouseOver = '' . 'onmouseover="ddrivetip(\'' . $UserStats . '\', 200)"; onmouseout="hideddrivetip()" ';
      $username = ($CURUSER['username'] ? '<a ' . $OnMouseOver . 'href="' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '" alt="' . $CURUSER['username'] . '">' . get_user_color ($CURUSER['username'], $usergroups['namestyle']) . '</a> (' . ($CURUSER['title'] ? htmlspecialchars_uni ($CURUSER['title']) : get_user_color ($usergroups['title'], $usergroups['namestyle'])) . ') ' . ($CURUSER['donor'] == 'yes' ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'star.gif" alt="' . $lang->global['imgdonated'] . '" title="' . $lang->global['imgdonated'] . '" border="0" class="inlineimg" />' : '') . (($CURUSER['warned'] == 'yes' OR $CURUSER['leechwarn'] == 'yes') ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'warned.gif" alt="' . $lang->global['imgwarned'] . '" title="' . $lang->global['imgwarned'] . '" border="0" class="inlineimg" />' : '') : $lang->global['guest']);
      $showcommentstable .= '<br />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tbody>
			<tr>
				<td colspan="2" class="subheader">
					<div style="float: right;"></div>
					<div style="float: left;"><a name="cid' . $cid . '" id="cid' . $cid . '"></a><a href="#cid' . $cid . '">#' . ($ts_perpage + 1) . '</a> by ' . $username . ' ' . my_datee ($dateformat, TIMENOW) . ' ' . my_datee ($timeformat, TIMENOW) . '</div>
				</td>
			</tr>
			<tr>
				<td align="center" valign="top" height="1%" width="1%">
					' . get_user_avatar ($CURUSER['avatar'], false, 100, 100) . '
				</td>
				<td align="left" valign="top">
					<div id="post_message_' . $cid . '" style="display: inline;">' . $textbody . '</div>
					' . $signature . '
				</td>
			</tr>
			<tr>
				<td align="center" height="32" width="100">' . $IsUserOnline . $SendPM . '</td>
				<td><div style="float: right;">' . $p_report . ' ' . $p_delete . ' ' . $p_edit . ' ' . $p_quote . '</div>' . $p_commenthistory . '</td>
			</tr>
		</tbody>
	</table>
	';
      show_msg ($showcommentstable, false, '', false);
      return 1;
    }

    if (!empty ($_POST['username']))
    {
      $lang->load ('signup');
      $username = @trim ($_POST['username']);
      if ((empty ($username) OR !isvalidusername ($username)))
      {
        show_msg ($lang->signup['une3'], false);
      }

      if (strlen ($username) < 3)
      {
        show_msg ($lang->signup['une1'], false);
      }

      if (12 < strlen ($username))
      {
        show_msg ($lang->signup['une2'], false);
      }

      $query = mysql_query ('SELECT username FROM users WHERE username = ' . sqlesc ($username));
      if (0 < mysql_num_rows ($query))
      {
        show_msg ($lang->signup['une4'], false);
        return 1;
      }

      require INC_PATH . '/readconfig_signup.php';
      $usernames = preg_split ('/\\s+/', $illegalusernames, 0 - 1, PREG_SPLIT_NO_EMPTY);
      foreach ($usernames as $val)
      {
        if (strpos (strtolower ($username), strtolower ($val)) !== false)
        {
          show_msg ($lang->signup['une4'], false);
          continue;
        }
      }

      show_msg ($lang->signup['uavailable'], false, 'green');
      return 1;
    }

    if (!empty ($_POST['email']))
    {
      $lang->load ('signup');
      $email = @trim ($_POST['email']);
      require_once INC_PATH . '/functions_EmailBanned.php';
      if ((empty ($email) OR !check_email ($email)))
      {
        show_msg ($lang->signup['invalidemail'], false);
      }
      else
      {
        if (emailbanned ($email))
        {
          show_msg ($lang->signup['invalidemail2'], false);
        }
      }

      $query = mysql_query ('SELECT email FROM users WHERE email = ' . sqlesc ($email));
      if (mysql_num_rows ($query) == 0)
      {
        show_msg ($lang->signup['eavailable'], false, 'green');
        return 1;
      }

      show_msg ($lang->signup['invalidemail3'], false);
      return 1;
    }

    if ((isset ($_POST['vid']) AND !empty ($_POST['cid'])))
    {
      $Cid = intval ($_POST['cid']);
      $Uid = intval ($CURUSER['id']);
      $Vid = ($_POST['vid'] == '1' ? '1' : '-1');
      if ((is_valid_id ($Cid) AND is_valid_id ($Uid)))
      {
        sql_query ('REPLACE INTO comments_votes VALUES (\'' . $Cid . '\', \'' . $Uid . '\', \'' . $Vid . '\')');
        $Query = sql_query ('SELECT vid FROM comments_votes WHERE cid = \'' . $Cid . '\'');
        $Negative = 0;
        $Positive = 0;
        if (0 < mysql_num_rows ($Query))
        {
          while ($Votes = mysql_fetch_assoc ($Query))
          {
            if ($Votes['vid'] == '-1')
            {
              $Negative += 1;
              continue;
            }
            else
            {
              $Positive += 1;
              continue;
            }
          }
        }
        else
        {
          if ($Vid == '-1')
          {
            $Negative += 1;
          }
          else
          {
            $Positive += 1;
          }
        }

        sql_query ('UPDATE comments SET totalvotes = \'' . $Positive . '|' . $Negative . '\' WHERE id = \'' . $Cid . '\'');
        echo $Positive - $Negative;
        exit ();
      }
    }
  }

?>
