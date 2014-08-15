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

  if ((isset ($_GET['fid']) AND is_valid_id ($_GET['fid'])))
  {
    $fid = intval ($_GET['fid']);
  }
  else
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
    exit ();
  }

  $extra_link_nav = '';
  $order_by = 't.sticky DESC, t.lastpost DESC';
  if ((isset ($_GET['sort_by']) AND $sort_by = trim ($_GET['sort_by']) != ''))
  {
    switch ($sort_by)
    {
      case 'thread':
      {
        $order_by = 't.subject DESC';
        break;
      }

      case 'lastpost':
      {
        $order_by = 't.lastpost DESC';
        break;
      }

      case 'replies':
      {
        $order_by = 't.replies DESC';
        break;
      }

      case 'views':
      {
        $order_by = 't.views DESC';
        break;
      }

      case 'rating':
      {
        $order_by = 't.votetotal DESC';
        break;
      }

      case 'starter':
      {
        $order_by = 't.username ASC';
      }
    }

    $extra_link_nav = '&sort_by=' . htmlspecialchars_uni ($sort_by);
  }

  if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
  {
    $postperpage = intval ($CURUSER['postsperpage']);
  }
  else
  {
    $postperpage = $f_postsperpage;
  }

  $totalthreads = tsrowcount ('tid', TSF_PREFIX . 'threads', 'fid=' . sqlesc ($fid));
  sanitize_pageresults ($totalthreads, $pagenumber, $perpage, 200);
  $multipage = construct_page_nav ($pagenumber, $perpage, $totalthreads, '' . 'forumdisplay.php?fid=' . $fid . $extra_link_nav, '', false);
  $limitlower = ($pagenumber - 1) * $perpage;
  $limitupper = $pagenumber * $perpage;
  if ($totalthreads < $limitupper)
  {
    $limitupper = $totalthreads;
    if ($totalthreads < $limitlower)
    {
      $limitlower = $totalthreads - $perpage - 1;
    }
  }

  if ($limitlower < 0)
  {
    $limitlower = 0;
  }

  ($query = sql_query ('
								SELECT t.*, f.password, f.type, f.name as currentforum, f.pid as parent, ff.name as realforum, ff.fid as realforumid, u.username as reallastposterusername, u.id as reallastposteruid, g.namestyle as lastposternamestyle, uu.username as threadstarter, uu.id as threadstarteruid, gg.namestyle as threadstarternamestyle
								FROM ' . TSF_PREFIX . 'threads t 
								LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
								LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
								LEFT JOIN users u ON (t.lastposteruid=u.id)
								LEFT JOIN usergroups g ON (u.usergroup=g.gid)
								LEFT JOIN users uu ON (t.uid=uu.id)
								LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid)
								WHERE t.fid = ' . sqlesc ($fid) . '
								ORDER BY ' . $order_by . ('' . '
								LIMIT ' . $limitlower . ', ' . $perpage . '
							')) OR sqlerr (__FILE__, 108));
  $totalthreads = array ();
  while ($forum = mysql_fetch_assoc ($query))
  {
    if (!isset ($currentforum))
    {
      $currentforum = $forum['currentforum'];
    }

    check_forum_password ($forum['password'], $fid, tsf_seo_clean_text ($currentforum, 'fd', $fid, '&do=password'));
    $threads[$forum['tid']] = $forum;
    $tids[$forum['tid']] = $forum['tid'];
    $realforumid = 0 + $forum['realforumid'];
    $totalthreads[] = $forum['tid'];
  }

  $attach = array ();
  $a_query = sql_query ('SELECT a_tid FROM ' . TSF_PREFIX . 'attachments WHERE a_tid IN (0,' . @implode (',', $totalthreads) . ')');
  if (0 < mysql_num_rows ($a_query))
  {
    while ($s_attachments = mysql_fetch_assoc ($a_query))
    {
      if (isset ($attach[$s_attachments['a_tid']]))
      {
        ++$attach[$s_attachments['a_tid']];
        continue;
      }
      else
      {
        $attach[$s_attachments['a_tid']] = 1;
        continue;
      }
    }
  }

  if ($tids)
  {
    $tids = @implode (',', $tids);
  }

  if ($threads)
  {
    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'threadsread WHERE uid=' . sqlesc ($CURUSER['id']) . ('' . ' AND tid IN (' . $tids . ')')) OR sqlerr (__FILE__, 148));
    while ($readthread = mysql_fetch_assoc ($query))
    {
      $threads[$readthread['tid']]['lastread'] = $readthread['dateline'];
    }
  }

  require_once INC_PATH . '/functions_cookies.php';
  $forumread = ts_get_array_cookie ('forumread', $fid);
  if ($forumread < $CURUSER['last_forum_visit'])
  {
    $forumread = $CURUSER['last_forum_visit'];
  }

  stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION . ' :: ' . @str_replace ('&amp;', '&', $currentforum), true, 'supernote');
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  $forum_options = '
<a href="#" id="forumoptions' . $fid . '"><span class="">&nbsp;' . $lang->tsf_forums['foptions'] . '&nbsp;</span></a>
<script type="text/javascript">
		menu_register("forumoptions' . $fid . '");
</script>
<div id="forumoptions' . $fid . '_menu" class="menu_popup" style="display:none;">
	<table cellspacing="0" cellpadding="5" border="0" width="200">
		<tr>
			<td class="thead"><b>' . $lang->tsf_forums['foptions'] . '</b></td>
		</tr>
		<tr>
			<td class="subheader"><a href="./newthread.php?fid=' . $fid . '">' . $lang->tsf_forums['new_thread'] . '</a></td>
		</tr>
		<tr>
			<td class="subheader"><a href="./misc.php?action=markread&amp;fid=' . $fid . '">' . $lang->tsf_forums['mark_read'] . '</a></td>
		</tr>
		<tr>
			<td class="subheader"><a href="./tsf_search.php?sfid=' . $fid . '">' . $lang->tsf_forums['search_forum'] . '</a></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
		menu.activate(true);
</script>
';
  $unreadpost = $load_javascript = 0;
  $class = 'trow1';
  $str = '';
  if ((@is_array ($threads) AND 0 < count ($threads)))
  {
    foreach ($threads as $thread)
    {
      if ($permissions[$thread['parent']]['canview'] == 'no')
      {
        if ($fid != $thread['parent'])
        {
          print_no_permission (true, false);
          exit ();
        }
        else
        {
          continue;
        }
      }

      $lastread = 0;
      if ($forumread < $thread['lastpost'])
      {
        $cutoff = TIMENOW - 7 * 60 * 60 * 24;
        if ($cutoff < $thread['lastpost'])
        {
          if (isset ($thread['lastread']))
          {
            $lastread = $thread['lastread'];
          }
          else
          {
            $lastread = 1;
          }
        }
      }

      if (!$lastread)
      {
        $readcookie = $threadread = ts_get_array_cookie ('threadread', $thread['tid']);
        if ($forumread < $readcookie)
        {
          $lastread = $readcookie;
        }
        else
        {
          $lastread = $forumread;
        }
      }

      if (($lastread < $thread['lastpost'] AND $lastread))
      {
        $images = show_forum_images ('on');
        $unreadpost = 1;
      }
      else
      {
        if ($thread['closed'] == 'yes')
        {
          $images = show_forum_images ('offlock');
        }
        else
        {
          $images = show_forum_images ('off');
        }
      }

      $lastpost_data = $_clean_subject = '';
      $lastpost_data = array ('lastpost' => $thread['lastpost'], 'lastposter' => get_user_color (htmlspecialchars_uni ($thread['reallastposterusername']), $thread['lastposternamestyle']), 'lastposteruid' => $thread['reallastposteruid']);
      $desc = $stickyimg = $ratingimage = $threadtags = $attachimage = $pollimage = '';
      $subject = htmlspecialchars_uni (ts_remove_badwords ($thread['subject']));
      if ($thread['sticky'] == 1)
      {
        $stickyimg = '<img src="images/sticky.gif" class="inlineimg" border="0" alt="' . $lang->tsf_forums['stickythread'] . '" title="' . $lang->tsf_forums['stickythread'] . '" />';
        $desc = $lang->tsf_forums['sticky'];
      }

      if ($thread['votenum'])
      {
        $thread['voteavg'] = number_format ($thread['votetotal'] / $thread['votenum'], 2);
        $thread['rating'] = round ($thread['votetotal'] / $thread['votenum']);
        $ratingimgalt = sprintf ($lang->tsf_forums['tratingimgalt'], $thread['votenum'], $thread['voteavg']);
        $ratingimage = '' . '<img class="inlineimg" src="images/rating/rating_' . $thread['rating'] . '.gif" alt="' . $ratingimgalt . '" title="' . $ratingimgalt . '" border="0" />';
      }

      if ($thread['pollid'])
      {
        $pollimgalt = $lang->tsf_forums['poll17'];
        $pollimage = '' . '<img class="inlineimg" src="images/poll.gif" alt="' . $pollimgalt . '" title="' . $pollimgalt . '" border="0" />';
        $desc = '<strong>' . $lang->tsf_forums['poll17'] . ':</strong> ';
      }

      if (isset ($attach[$thread['tid']]))
      {
        $attachimgalt = ts_nf ($attach[$thread['tid']]) . ' ' . $lang->tsf_forums['a_info'];
        $attachimage = '<a href="#" onclick="ts_open_popup(\'attachment.php?viewattachments=true&amp;tid=' . $thread['tid'] . ('' . '\'); return false"> <img class="inlineimg" src="images/attachment.gif" alt="' . $attachimgalt . '" title="' . $attachimgalt . '" border="0" /></a>');
      }

      if (((($stickyimg OR $ratingimage) OR $attachimage) OR $pollimage))
      {
        $threadtags = '<span style="float: right;">' . $stickyimg . ' ' . $pollimage . ' ' . $attachimage . ($ratingimage ? '</span><span style="clear: both; float: right;">' . $ratingimage : '') . '</span>';
      }

      if (($lastpost_data['lastpost'] == 0 OR $lastpost_data['lastposter'] == ''))
      {
        $lastpost = '' . '<td class="' . $class . '" style="white-space: nowrap;"><span style="text-align: center;">' . $lang->tsf_forums['lastpost_never'] . '</span></td>';
      }
      else
      {
        $lastpost_date = my_datee ($dateformat, $thread['lastpost']);
        $lastpost_time = my_datee ($timeformat, $thread['lastpost']);
        $lastpost_profilelink = build_profile_link ($lastpost_data['lastposter'], $lastpost_data['lastposteruid']);
        $lastpost = '
			<td class="' . $class . '">
				<span class="smalltext" style="text-align: right; white-space: nowrap;">' . $lastpost_date . ' ' . $lastpost_time . '<br />
				' . $lang->tsf_forums['by'] . ' ' . $lastpost_profilelink . '</span> <a href="' . tsf_seo_clean_text ($subject, 't', $thread['tid'], '&action=lastpost') . '" alt="" title=""><img src="images/lastpost.gif" class="inlineimg" border="0" alt="' . $lang->tsf_forums['gotolastpost'] . '" title="' . $lang->tsf_forums['gotolastpost'] . '"></a>
			</td>';
      }

      if ($thread['threadstarter'])
      {
        $author = get_user_color (htmlspecialchars_uni ($thread['threadstarter']), $thread['threadstarternamestyle']);
      }
      else
      {
        $author = $lang->tsf_forums['guest'];
      }

      $replies = ts_nf ($thread['replies']);
      $views = ts_nf ($thread['views']);
      $thread['pages'] = 0;
      $thread['multipage'] = '';
      $threadpages = '';
      $morelink = '';
      $thread['posts'] = $thread['replies'] + 1;
      if ($postperpage < $thread['posts'])
      {
        $thread['pages'] = $thread['posts'] / $postperpage;
        $thread['pages'] = @ceil ($thread['pages']);
        if (4 < $thread['pages'])
        {
          $pagesstop = 4;
          $morelink = '... <a href="' . tsf_seo_clean_text ($subject, 't', $thread['tid'], '&page=last') . ('' . '">' . $lang->global['last'] . '</a>');
        }
        else
        {
          $pagesstop = $thread['pages'];
        }

        $i = 1;
        while ($i <= $pagesstop)
        {
          $threadpages .= ' <a href="' . tsf_seo_clean_text ($subject, 't', $thread['tid'], '&page=' . $i) . ('' . '">' . $i . '</a> ');
          ++$i;
        }

        $thread['multipage'] = '' . ' <span class="smalltext">(<img src="images/multipage.gif" border="0" alt="' . $lang->tsf_forums['multithread'] . '" title="' . $lang->tsf_forums['multithread'] . '" class="inlineimg"> ' . $lang->tsf_forums['pages'] . ' ' . $threadpages . $morelink . ')</span>';
      }
      else
      {
        $threadpages = '';
        $morelink = '';
        $thread['multipage'] = '';
      }

      if (!isset ($moderatorquerydone))
      {
        $forummoderator = is_forum_mod (($thread['type'] == 's' ? $realforumid : $fid), $CURUSER['id']);
        $moderatorquerydone = 1;
      }

      if ((((($thread['uid'] == $CURUSER['id'] AND $thread['closed'] != 'yes') AND $permissions[$thread['parent']]['caneditposts']) OR $moderator == true) OR $forummoderator == true))
      {
        $inline_edit_class = 'subject_editable';
        $load_javascript = 1;
      }
      else
      {
        $inline_edit_class = '';
      }

      $str .= '' . '
			<tr>
				<td class="trow1" align="center">' . $images . '</td>
				<td class="trow1" align="center"><img src="./images/icons/icon' . $thread['iconid'] . '.gif" border="0"></td>
				<td class="trow1" align="left">' . $threadtags . $desc . '<a class="' . $inline_edit_class . '" id="tid_' . $thread['tid'] . '" href="' . tsf_seo_clean_text ($subject, 't', $thread['tid']) . ('' . '">' . $subject . '</a>' . $thread['multipage'] . '<br /><a href="') . tsf_seo_clean_text ($thread['threadstarter'], 'u', $thread['threadstarteruid'], '', 'ts') . ('' . '">' . $author . '</a></td>
				' . $lastpost . '
				<td class="' . $class . '" align="center">' . $replies . '</td>
				<td class="' . $class . '" align="center">' . $views . '</td>		
				') . (($moderator OR $forummoderator) ? '<td class="' . $class . '" align="center"><input type="checkbox" id="threadids" checkme="group1" name="threadids[]" value="' . $thread['tid'] . '"></td>' : '') . '
			</tr>';
      if (($unreadpost == 0 AND ($pagenumber == 1 OR !$pagenumber)))
      {
        require_once INC_PATH . '/functions_cookies.php';
        ts_set_array_cookie ('forumread', $fid, TIMENOW);
        continue;
      }
    }

    add_breadcrumb ($thread['realforum'], tsf_seo_clean_text ($thread['realforum'], ($thread['type'] == 's' ? 'fd' : 'f'), $thread['realforumid']));
    add_breadcrumb ($currentforum);
  }
  else
  {
    $str .= '' . '<tr><td class="trow1" align="left" colspan="7"><strong>' . $lang->tsf_forums['no_thread'] . '</strong></td></tr>';
    add_breadcrumb ($lang->tsf_forums['no_thread']);
  }

  $str .= '</table>';
  $colspan = 6;
  $moderation_script = '';
  if (($moderator OR $forummoderator))
  {
    ++$colspan;
    $moderation_script = '
	<script type="text/javascript">
		function TSdo_action()
		{
			var WorkArea = document.getElementById("mod_tools").value;
			switch(WorkArea)
			{
				case "newthread":
					jumpto(\'newthread.php?fid=' . $fid . '\');
				break;
				' . ($usergroups['canmassdelete'] == 'yes' ? 'case "deletethreads":
					document.moderation.action ="massdelete.php?action=deletethreads";
					document.moderation.submit();
				break;' : '') . '
				case "open":
					document.moderation.action = "modtools.php?action=open";
					document.moderation.submit();
				break;
				case "close":
					document.moderation.action = "modtools.php?action=close";
					document.moderation.submit();
				break;
				case "sticky":
					document.moderation.action = "modtools.php?action=sticky";
					document.moderation.submit();
				break;
				case "unsticky":
					document.moderation.action = "modtools.php?action=unsticky";
					document.moderation.submit();
				break;
				case "movethreads":
					document.moderation.action = "modtools.php?action=movethreads";
					document.moderation.submit();
				break;
				case "mergethreads":
					document.moderation.action = "modtools.php?action=mergethreads";
					document.moderation.submit();
				break;
				default:
					return false;
				break;
			}
		}
	</script>
	';
  }

  $str0 = '	
	<!-- start: forumdisplay_newthread/Pagination -->
	<div style="float: left; margin-bottom: 3px;" id="navcontainer_f">
		' . $multipage . '
	</div>
	<div style="float: right; margin-bottom: 3px;">
		<input value="' . $lang->tsf_forums['new_thread'] . '" onclick="jumpto(\'newthread.php?fid=' . $fid . '\');" type="button">
	</div>				
	<!-- end: forumdisplay_newthread/Pagination -->	
	
	<table border="0" cellspacing="0" cellpadding="5" class="tborder" style="clear: both;">
		<tr>
			<td class="thead" colspan="' . ($colspan - 3) . '" align="left">
				<strong>' . $currentforum . '</strong>
			</td>
			<td class="thead" colspan="3" align="center">
				<strong>' . $forum_options . '</strong>
			</td>
		</tr>

		<tr>
			<td class="tcat" align="center" width="1%" colspan="2"></td>
			<td class="tcat" align="left" width="50%"><span class="smalltext" style="float: right">' . ((isset ($sort_by) AND $sort_by == 'rating') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=rating&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['rating'] . '</u></strong></a></span> ' . ((isset ($sort_by) AND $sort_by == 'thread') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<span class="smalltext"><a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=thread&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['thread'] . '</u></strong></a></span> / ' . ((isset ($sort_by) AND $sort_by == 'starter') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<span class="smalltext"><a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=starter&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['starter'] . '</u></strong></a></span></td>
			<td class="tcat" align="left" width="10%">' . ((isset ($sort_by) AND $sort_by == 'lastpost') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<span class="smalltext"><a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=lastpost&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['lastpost'] . '</u></strong></a></span></td>
			<td class="tcat" align="center" width="1%">' . ((isset ($sort_by) AND $sort_by == 'replies') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<span class="smalltext"><a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=replies&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['replies'] . '</u></strong></a></span></td>
			<td class="tcat" align="center" width="1%">' . ((isset ($sort_by) AND $sort_by == 'views') ? '<img src="./images/selected.gif" border="0" class="inlineimg"> ' : '') . '<span class="smalltext"><a href="' . tsf_seo_clean_text ($currentforum, 'fd', $fid, '&sort_by=views&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0))) . '"><strong><u>' . $lang->tsf_forums['views'] . '</u></strong></a></span></td>
			' . (($moderator OR $forummoderator) ? '<td align="center" width="1%"><input type="checkbox" checkall="group1" onclick="javascript: return select_deselectAll (\'moderation\', this, \'group1\');" type="checkbox"></td>' : '') . '
		</tr>';
  $moderation = '<input value="' . $lang->tsf_forums['new_thread'] . '" onclick="jumpto(\'newthread.php?fid=' . $fid . '\');" type="button">';
  if (($moderator OR $forummoderator))
  {
    $str0 .= '
	<form method="post" action="" name="moderation">
	<input type="hidden" name="parentfid" value="' . $realforumid . '" />
	<input type="hidden" name="currentfid" value="' . $fid . '" />
	<input type="hidden" name="hash" value="' . $forumtokencode . '" />';
    $moderation = '
		<select name="mod_tools" onchange="TSdo_action();" id="mod_tools">
			<option value="" selected="Selected">' . $lang->tsf_forums['mod_options'] . '</option>\\
			<option value="">--------------------------</option>
			' . ($usergroups['canmassdelete'] == 'yes' ? '<option value="deletethreads">' . $lang->tsf_forums['deletethreads'] . '</option>' : '') . '
			<option value="open">' . $lang->tsf_forums['mop1'] . '</option>
			<option value="close">' . $lang->tsf_forums['mop2'] . '</option>
			<option value="sticky">' . $lang->tsf_forums['mop3'] . '</option>
			<option value="unsticky">' . $lang->tsf_forums['mop4'] . '</option>
			<option value="movethreads">' . $lang->tsf_forums['mod_options_m'] . '</option>
			<option value="mergethreads">' . $lang->tsf_forums['mop5'] . '</option>
		</select> 
		<input value="' . $lang->tsf_forums['new_thread'] . '" onclick="jumpto(\'newthread.php?fid=' . $fid . '\');" type="button" />
	</form>';
  }

  $str .= '
	<!-- start: forumdisplay_newthread -->
	<div style="float: left; margin-bottom: 5px; margin-top: 3px;" id="navcontainer_f">
		' . $multipage . '
	</div>
	<div style="float: right; margin-bottom: 5px; margin-top: 3px;">
		' . $moderation . '
	</div>
	<!-- end: forumdisplay_newthread -->	';
  $str .= '
	<!-- begin: footer -->
	<table class="subheader" border="0" cellspacing="0" cellpadding="5" align="center" width="100%" style="clear: both;">
		<tbody>
			<tr>
				<td align="center" style="padding: 10px 0px 10px 0px; margin: 0px 0px 0px 0px;">
					<img src="images/on.gif" alt="' . $lang->tsf_forums['t_new_posts'] . '" title="' . $lang->tsf_forums['t_new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['t_new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="images/off.gif" alt="' . $lang->tsf_forums['t_no_new_posts'] . '" title="' . $lang->tsf_forums['t_no_new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['t_no_new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="images/offlock.gif" alt="' . $lang->tsf_forums['thread_locked'] . '" title="' . $lang->tsf_forums['thread_locked'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['thread_locked'] . '</span>									
				</td>
			</tr>
		</tbody>
	</table>	
	<!-- end: footer -->';
  $query = sql_query ('SELECT m.userid, m.forumid, u.username, g.namestyle
							FROM ' . TSF_PREFIX . 'moderators m 
							INNER JOIN users u ON (m.userid=u.id)
							INNER JOIN usergroups g ON (u.usergroup=g.gid)');
  $imodcache = array ();
  while ($forummoderators = mysql_fetch_assoc ($query))
  {
    $imodcache['' . $forummoderators['forumid']]['' . $forummoderators['userid']] = $forummoderators;
  }

  ($query = sql_query ('
							SELECT f.*, u.username as realrealusername, u.id as reallastposteruserid, g.namestyle 
							FROM ' . TSF_PREFIX . 'forums f 
							LEFT JOIN users u ON (f.lastposteruid=u.id) 
							LEFT JOIN usergroups g ON (g.gid=u.usergroup) 
							WHERE f.type = \'s\' AND f.pid = ' . sqlesc ($fid) . ' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 548));
  $subforums = '
		<!-- start: subforums -->
			<table class="tborder" border="0" cellspacing="0" cellpadding="5">
				<thead>
					<tr>
						<td class="thead" colspan="6">
							' . ts_collapse ('subforums#' . $fid) . '
							<strong>' . $lang->tsf_forums['sforums'] . ' ' . $currentforum . '</a></strong>
						</td>
					</tr>
				</thead>
				' . ts_collapse ('subforums#' . $fid, 2) . '
					<tr>
						<td class="tcat" width="32">&nbsp;</td>
						<td class="tcat" width="32">&nbsp;</td>
						<td class="tcat"><strong>' . $lang->tsf_forums['forum'] . '</strong></td>
						<td class="tcat" style="white-space: nowrap;" align="center" width="85"><strong>' . $lang->tsf_forums['threads'] . '</strong></td>
						<td class="tcat" style="white-space: nowrap;" align="center" width="85"><strong>' . $lang->tsf_forums['posts'] . '</strong></td>
						<td class="tcat" align="center" width="200"><strong>' . $lang->tsf_forums['lastpost'] . '</strong></td>
					</tr>
		';
  $showsubforums = false;
  while ($forum = mysql_fetch_assoc ($query))
  {
    if ($permissions[$forum['pid']]['canview'] == 'no')
    {
      continue;
    }

    $showsubforums = true;
    $lastpost_data = '';
    $hideinfo = false;
    $posts = ts_nf ($forum['posts']);
    $threads = ts_nf ($forum['threads']);
    if (($forum['password'] != '' AND (($_COOKIE['forumpass_' . $forum['fid']] != md5 ($CURUSER['id'] . $forum['password'] . $securehash) OR empty ($_COOKIE['forumpass_' . $forum['fid']])) OR strlen ($_COOKIE['forumpass_' . $forum['fid']]) != 32)))
    {
      $hideinfo = true;
    }

    $lastpost_data = array ('lastpost' => $forum['lastpost'], 'lastpostsubject' => $forum['lastpostsubject'], 'lastposter' => get_user_color (htmlspecialchars_uni ($forum['realrealusername']), $forum['namestyle']), 'lastposttid' => $forum['lastposttid'], 'lastposteruid' => $forum['reallastposteruserid']);
    if ($hideinfo == true)
    {
      unset ($lastpost_data);
    }

    if ((($lastpost_data['lastpost'] == 0 OR $lastpost_data['lastposter'] == '') AND $hideinfo != true))
    {
      $lastpost = '<span style="text-align: center;">' . $lang->tsf_forums['lastpost_never'] . '</span>';
    }
    else
    {
      if ($hideinfo != 1)
      {
        $lastpost_date = my_datee ($dateformat, $forum['lastpost']);
        $lastpost_time = my_datee ($timeformat, $forum['lastpost']);
        $lastpost_profilelink = build_profile_link ($lastpost_data['lastposter'], $lastpost_data['lastposteruid']);
        $lastposttid = $lastpost_data['lastposttid'];
        $lastpost_subject = $full_lastpost_subject = $lastpost_data['lastpostsubject'];
        if (25 < @strlen ($lastpost_subject))
        {
          $lastpost_subject = my_substrr ($lastpost_subject, 0, 25) . '...';
        }

        $full_lastpost_subject = htmlspecialchars_uni (ts_remove_badwords ($full_lastpost_subject));
        $_clean_subject = htmlspecialchars_uni (ts_remove_badwords ($lastpost_subject));
        $lastpost = '
		<span class="smalltext">
			<a href="' . tsf_seo_clean_text ($_clean_subject, 't', $lastposttid, '&action=lastpost') . '" title="' . $full_lastpost_subject . '" title="' . $full_lastpost_subject . '"><strong>' . $_clean_subject . '</strong></a>
			<br />' . $lastpost_date . ' ' . $lastpost_time . '<br />' . $lang->tsf_forums['by'] . ' ' . $lastpost_profilelink . ' <a href="' . tsf_seo_clean_text ($_clean_subject, 't', $lastposttid, '&action=lastpost') . '" alt="' . $full_lastpost_subject . '" title="' . $full_lastpost_subject . '"><img src="images/lastpost.gif" class="inlineimg" border="0" alt="' . $lang->tsf_forums['gotolastpost'] . '" title="' . $lang->tsf_forums['gotolastpost'] . '"></a>
		</span>';
      }
    }

    $forumread = ts_get_array_cookie ('forumread', $forum['fid']);
    if ((($CURUSER['last_forum_visit'] < $lastpost_data['lastpost'] AND $forumread < $lastpost_data['lastpost']) AND $lastpost_data['lastpost'] != 0))
    {
      $folder = 'on';
      $altonoff = $lang->tsf_forums['new_posts'];
    }
    else
    {
      $folder = 'off';
      $altonoff = $lang->tsf_forums['no_new_posts'];
    }

    $moderatorslist = '';
    if ((is_array ($imodcache['' . $forum['pid']]) AND 0 < count ($imodcache['' . $forum['pid']])))
    {
      foreach ($imodcache['' . $forum['pid']] as $fmoderator)
      {
        if ($moderatorslist == '')
        {
          $moderatorslist = '<a href="' . tsf_seo_clean_text ($fmoderator['username'], 'u', $fmoderator['userid'], '', 'ts') . '" rel="nofollow">' . get_user_color ($fmoderator['username'], $fmoderator['namestyle']) . '</a>';
          continue;
        }
        else
        {
          $moderatorslist .= ', <a href="' . tsf_seo_clean_text ($fmoderator['username'], 'u', $fmoderator['userid'], '', 'ts') . '" rel="nofollow">' . get_user_color ($fmoderator['username'], $fmoderator['namestyle']) . '</a>';
          continue;
        }
      }
    }

    $subforums .= '
	
		<!-- start: subforums -->

			<tr>
				<td class="trow1" align="center" valign="top">
					<img src="images/' . $folder . '.gif" alt="' . $altonoff . '" title="' . $altonoff . '">
				</td>
				<td class="trow1" align="center" valign="top">
					' . ($forum['image'] ? '<img src="images/forumicons/' . $forum['image'] . '" alt="" title="" />' : '') . '
				</td>
				<td class="trow1" valign="top">
					<strong><a href="' . tsf_seo_clean_text ($forum['name'], 'fd', $forum['fid']) . '">' . $forum['name'] . '</a></strong>
					<div class="smalltext">' . $forum['description'] . '</div>
					' . ($moderatorslist ? '<div class="smalltext">' . sprintf ($lang->tsf_forums['modlist'], $moderatorslist) . '</div>' : '') . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="center" valign="top">
					' . $threads . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="center" valign="top">
					' . $posts . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="right" valign="top">
					' . $lastpost . '
				</td>
			</tr>

		<!-- end: subforums -->';
  }

  $subforums .= '
		</tbody>
			</table>
			<br />
		<!-- end: subforums -->
';
  build_breadcrumb ();
  if ($load_javascript)
  {
    $str .= '
			<script type="text/javascript" src="scripts/prototype.lite.js?v=' . O_SCRIPT_VERSION . '"></script>
			<script type="text/javascript" src="scripts/moo.ajax.js?v=' . O_SCRIPT_VERSION . '"></script>
			<script type="text/javascript" src="scripts/inline_edit.js?v=' . O_SCRIPT_VERSION . (('' . '"></script>
			<script type="text/javascript">
				var loading_text = \'' . $lang->tsf_forums['ajax_loading'] . '\';
				var saving_changes = \'' . $lang->tsf_forums['saving_changes'] . '\';
				new inlineEditor("tsf_ajax.php?action=edit_subject", {className: "subject_editable", spinnerImage: "images/spinner.gif", lang_click_edit: "' . $lang->tsf_forums['click_hold_edit'] . '"') . '});
			</script>' . $moderation_script);
  }

  echo show_announcements ($realforumid) . ($showsubforums ? $subforums : '') . $str0 . $str;
  stdfoot ();
?>
