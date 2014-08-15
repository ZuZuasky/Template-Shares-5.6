<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_image ($text, $size = 300)
  {
    $content = 'onmouseover="ddrivetip(\'' . $text . '\', ' . $size . ')"; onmouseout="hideddrivetip()"';
    return $content;
  }

  define ('TSF_FORUMS_TSSEv56', true);
  define ('NcodeImageResizer', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  if (!is_valid_id ($tid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  if ((($action == 'lastpost' OR $pagenumber == 'last') AND !isset ($_GET['nolastpage'])))
  {
    ($query = sql_query ('SELECT p.subject, p.pid, f.pid as parent 
	FROM ' . TSF_PREFIX . 'posts p
	LEFT JOIN ' . TSF_PREFIX . 'forums f ON (p.fid=f.fid)
	WHERE tid = ' . sqlesc ($tid) . ' ORDER BY dateline DESC LIMIT 0,1') OR sqlerr (__FILE__, 46));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $name = mysql_result ($query, 0, 'p.subject');
    $pid = mysql_result ($query, 0, 'p.pid');
    $fid = mysql_result ($query, 0, 'parent');
    if ($permissions[$fid]['canview'] == 'no')
    {
      print_no_permission (true);
      exit ();
    }

    $lastpage = get_last_post ($tid);
    redirect (tsf_seo_clean_text ($name, 't', $tid, '&page=' . $lastpage . '&pid=' . $pid . '#pid' . $pid), $lang->tsf_forums['redirect_last_post'], '', 3, false, false);
    exit ();
  }

  $Query = sql_query ('SELECT uid FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid));
  $totalposts = mysql_num_rows ($Query);
  $UserHasPosted = false;
  if (0 < $totalposts)
  {
    while ($ListUids = mysql_fetch_assoc ($Query))
    {
      if ($ListUids['uid'] === $CURUSER['id'])
      {
        $UserHasPosted = true;
        continue;
      }
    }
  }

  if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
  {
    $perpage = intval ($CURUSER['postsperpage']);
  }
  else
  {
    $perpage = $f_postsperpage;
  }

  sanitize_pageresults ($totalposts, $pagenumber, $perpage, 200);
  if ((isset ($_GET['highlight']) AND !empty ($_GET['highlight'])))
  {
    $h_link = '&amp;highlight=' . htmlspecialchars_uni ($_GET['highlight']);
  }

  $multipage = construct_page_nav ($pagenumber, $perpage, $totalposts, '' . 'showthread.php?tid=' . $tid . (isset ($h_link) ? $h_link : ''));
  $limitlower = ($pagenumber - 1) * $perpage;
  $limitupper = $pagenumber * $perpage;
  if ($totalposts < $limitupper)
  {
    $limitupper = $totalposts;
    if ($totalposts < $limitlower)
    {
      $limitlower = $totalposts - $perpage - 1;
    }
  }

  if ($limitlower < 0)
  {
    $limitlower = 0;
  }

  ($query = sql_query ('
			SELECT p.*, p.subject as postsubject, f.password, f.name as currentforum, f.type, ff.name as realforum, ff.fid as realforumid, f.pid as parent, t.subject as threadsubject, t.closed, t.sticky, t.pollid, t.votenum, t.votetotal, t.firstpost, u.last_access, u.last_login, u.last_forum_visit, u.added, u.username AS userusername, u.totalposts, u.timeswarned, u.downloaded, u.uploaded, u.title as usertitle, u.country, u.avatar, u.options, u.donated, u.usergroup, u.signature, u.enabled, u.donor, u.leechwarn, u.warned, pp.canupload, pp.candownload, pp.cancomment, pp.canmessage, pp.canshout, eu.username AS editusername, g.namestyle, g.title, c.name as countryname, c.flagpic
			FROM ' . TSF_PREFIX . 'posts p 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (p.fid=f.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid)
			LEFT JOIN users u ON (u.id=p.uid)
			LEFT JOIN ts_u_perm pp ON (u.id=pp.userid)
			LEFT JOIN users eu ON (eu.id=p.edituid)
			LEFT JOIN countries c ON (u.country=c.id)
			LEFT JOIN usergroups g ON (u.usergroup=g.gid)
			WHERE p.tid = ' . sqlesc ($tid) . ('' . '
			ORDER BY p.dateline ASC
			LIMIT ' . $limitlower . ', ' . $perpage . '
			')) OR sqlerr (__FILE__, 125));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $a_query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'attachments WHERE a_tid = ' . sqlesc ($tid));
  if (0 < mysql_num_rows ($a_query))
  {
    while ($s_attachments = mysql_fetch_assoc ($a_query))
    {
      $a_array[$s_attachments['a_pid']][] = $s_attachments;
    }
  }

  ($TQuery = sql_query ('SELECT t.pid, t.uid, u.username, g.namestyle FROM ' . TSF_PREFIX . 'thanks t LEFT JOIN users u ON (t.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.tid = \'' . $tid . '\'') OR sqlerr (__FILE__, 148));
  if (0 < mysql_num_rows ($TQuery))
  {
    $TCache = array ();
    while ($Thank = mysql_fetch_assoc ($TQuery))
    {
      $TCache[$Thank['pid']][$Thank['uid']]['userid'] = $Thank['uid'];
      $TCache[$Thank['pid']][$Thank['uid']]['username'] = '<a href="' . ts_seo ($Thank['uid'], $Thank['username']) . '">' . get_user_color ($Thank['username'], $Thank['namestyle']) . '</a>';
      $TCache[$Thank['pid']][$Thank['uid']]['pid'] = $Thank['pid'];
    }
  }

  $lang->load ('quick_editor');
  require_once INC_PATH . '/class_tsquickbbcodeeditor.php';
  $QuickEditor = new TSQuickBBCodeEditor ();
  $QuickEditor->ImagePath = $BASEURL . '/' . $pic_base_url;
  $QuickEditor->SmiliePath = $BASEURL . '/' . $pic_base_url . 'smilies/';
  include_once INC_PATH . '/functions_ratio.php';
  include_once INC_PATH . '/functions_icons.php';
  include_once INC_PATH . '/function_warnlevel.php';
  $subsquery = sql_query ('SELECT userid FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid) . ' AND userid = ' . sqlesc ($CURUSER['id']));
  $subslink = (mysql_num_rows ($subsquery) == 0 ? '<a href="' . $BASEURL . '/tsf_forums/subscription.php?do=addsubscription&amp;tid=' . $tid . '" title="' . $lang->tsf_forums['isubs'] . '"><b>' . $lang->tsf_forums['subs'] . '</b></a>' : '<a href="' . $BASEURL . '/tsf_forums/subscription.php?do=removesubscription&amp;tid=' . $tid . '" title="' . $lang->tsf_forums['delsubs'] . '"><b>' . $lang->tsf_forums['delsubs'] . '</b></a>');
  $count = 1;
  $isthreadclosed = $isstickythread = $checkedpassword = $ajax_quick_edit_loaded = $isfirstpost = false;
  $str2 = $quickmenu = '';
  $showpagenumber = ((isset ($_GET['page']) AND is_valid_id ($_GET['page'])) ? '&amp;page=' . intval ($_GET['page']) : '');
  require_once './include/function_get_user_png.php';
  while ($thread = mysql_fetch_assoc ($query))
  {
    if (1 < $count)
    {
      $isfirstpost = true;
    }

    $tid = 0 + $thread['tid'];
    $fid = 0 + $thread['fid'];
    $pid = 0 + $thread['pid'];
    $realforum = $thread['realforum'];
    $currentforum = $thread['currentforum'];
    $realforumid = 0 + $thread['realforumid'];
    $ftype = $thread['type'];
    $user_rank = '';
    if (!$checkedpassword)
    {
      check_forum_password ($thread['password'], $fid, '' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid . '&amp;do=password');
      $checkedpassword = true;
    }

    if (!isset ($forummoderator))
    {
      $forummoderator = is_forum_mod (($ftype == 's' ? $realforumid : $fid), $CURUSER['id']);
    }

    if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['parent']]['canview'] == 'no' OR $permissions[$thread['parent']]['canviewthreads'] == 'no')))
    {
      print_no_permission (true);
      exit ();
    }

    $editdate = $edittime = $editedby = $editbutton = $deletebutton = $quotebutton = $signature = $avatar = $usertitle = $attachment = $display_attachment = $modnotice = $_warnlevel = '';
    if ((((($moderator OR $forummoderator) OR $thread['uid'] === $CURUSER['id']) OR $UserHasPosted) AND !defined ('IS_THIS_USER_POSTED')))
    {
      define ('IS_THIS_USER_POSTED', true);
    }

    if (preg_match ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', $thread['message']))
    {
      $Othread['message'] = $thread['message'];
      while (preg_match ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', $thread['message']))
      {
        $thread['message'] = preg_replace ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', '', $thread['message']);
      }

      $QuoteTag = htmlspecialchars (mysql_real_escape_string ('[quote=' . $thread['userusername'] . ']' . $thread['message'] . '[/quote]'));
      $thread['message'] = $Othread['message'];
      unset ($Othread[message]);
    }
    else
    {
      $QuoteTag = htmlspecialchars (mysql_real_escape_string ('[quote=' . $thread['userusername'] . ']' . $thread['message'] . '[/quote]'));
    }

    if ($thread['closed'] == 'yes')
    {
      $isthreadclosed = true;
    }

    if ($thread['sticky'] == 1)
    {
      $isstickythread = true;
    }

    if (isset ($a_array[$thread['pid']]))
    {
      require_once INC_PATH . '/functions_get_file_icon.php';
      $display_attachment = '
			<!-- start: attachments -->
			<br />
			<br />
			<fieldset>
				<legend><strong>' . $lang->tsf_forums['a_info'] . '</strong></legend>';
      foreach ($a_array[$thread['pid']] as $_a_left => $showperpost)
      {
        $display_attachment .= get_file_icon ($showperpost['a_name']) . '
				<a href="attachment.php?aid=' . $showperpost['a_id'] . '&amp;tid=' . $showperpost['a_tid'] . '&amp;pid=' . $thread['pid'] . '" target="_blank">' . htmlspecialchars_uni ($showperpost['a_name']) . '</a> (<b>' . $lang->tsf_forums['a_size'] . '</b>' . mksize ($showperpost['a_size']) . ' / <b>' . $lang->tsf_forums['a_count'] . '</b>' . ts_nf ($showperpost['a_count']) . ')<br />';
      }

      $display_attachment .= '
			</fieldset>
			<!-- end: attachments -->
		';
    }

    $realsubject = htmlspecialchars_uni (ts_remove_badwords ($thread['threadsubject']));
    $threadsubject = htmlspecialchars_uni (ts_remove_badwords ($thread['postsubject']));
    if ((!empty ($thread['signature']) AND preg_match ('#H1#is', $CURUSER['options'])))
    {
      $signature = '<hr align="left" size="1" width="65%">' . format_comment ($thread['signature'], true, true, true, true, 'signatures');
    }

    if (preg_match ('#D1#is', $CURUSER['options']))
    {
      $avatar = get_user_avatar ($thread['avatar']);
    }

    $lastseen = my_datee ($dateformat, $thread['last_access']) . ' ' . my_datee ($timeformat, $thread['last_access']);
    $downloaded = mksize ($thread['downloaded']);
    $uploaded = mksize ($thread['uploaded']);
    $ratio = get_user_ratio ($thread['uploaded'], $thread['downloaded']);
    $ratio = str_replace ('\'', '\\\'', $ratio);
    $join_date = $lang->tsf_forums['jdate'] . my_datee ($regdateformat, $thread['added']);
    $totalposts = $lang->tsf_forums['totalposts'] . ts_nf ($thread['totalposts']);
    $dt = TIMENOW - TS_TIMEOUT;
    if ((((preg_match ('#B1#is', $thread['options']) AND !$moderator) AND !$forummoderator) AND $thread['uid'] != $CURUSER['id']))
    {
      $lastseen = my_datee ($dateformat, $thread['last_login']) . ' ' . my_datee ($timeformat, $thread['last_login']);
      $status = $lang->tsf_forums['user_offline'];
    }
    else
    {
      if (($dt <= $thread['last_forum_visit'] OR $thread['uid'] == $CURUSER['id']))
      {
        $status = $lang->tsf_forums['user_online'];
      }
      else
      {
        $status = $lang->tsf_forums['user_offline'];
      }
    }

    $status = $lang->tsf_forums['status'] . $status;
    if ((((preg_match ('#I3#is', $thread['options']) OR preg_match ('#I4#is', $thread['options'])) AND !$moderator) AND !$forummoderator))
    {
      $tooltip = $lang->tsf_forums['deny'];
    }
    else
    {
      $tooltip = sprintf ($lang->tsf_forums['tooltip'], $lastseen, $downloaded, $uploaded, $ratio);
    }

    if (($thread['userusername'] AND $thread['uid']))
    {
      $posterforthanks = get_user_color (htmlspecialchars_uni ($thread['userusername']), $thread['namestyle']);
      $isuser = true;
      $poster = '<a href="#" id="quickmenu' . $pid . ('' . '"><i onmouseover="ddrivetip(\'' . $tooltip . '\', 200)"; onmouseout="hideddrivetip()">') . get_user_color (htmlspecialchars_uni ($thread['userusername']), $thread['namestyle']) . '</i></a>';
    }
    else
    {
      $isuser = false;
      $poster = $posterforthanks = $lang->tsf_forums['guest'];
    }

    if ($thread['usertitle'])
    {
      $usertitle = '<font class="smalltext"><strong>' . htmlspecialchars_uni ($thread['usertitle']) . '</strong></font><br />';
    }

    $poster_title = $lang->tsf_forums['usergroup'] . $thread['title'];
    if ((($moderator OR $forummoderator) OR (($permissions[$thread['parent']]['caneditposts'] == 'yes' AND $thread['closed'] != 'yes') AND $thread['uid'] == $CURUSER['id'])))
    {
      $onclick = 'onclick="jumpto(\'editpost.php?tid=' . $tid . '&amp;pid=' . $pid . $showpagenumber . '\');"';
      if ($useajax == 'yes')
      {
        if (!$ajax_quick_edit_loaded)
        {
          require_once INC_PATH . '/functions_quick_editor.php';
          $str2 .= '
				<script type="text/javascript">
					var l_quick_save_button = "' . $lang->global['buttonsave'] . '";
					var l_quick_cancel_button = "' . $lang->tsf_forums['cancel'] . '";
					var l_quick_adv_button = "' . $lang->tsf_forums['goadvanced'] . '";
					var bbcodes = \'' . trim (str_replace (array ('\'', '
', '
'), array ('\\\'', '', ''), ts_show_bbcode_links ('quick_edit_form', 'newContent'))) . '\';
				</script>				
				<script type="text/javascript" src="./scripts/inline_quick_edit.js?v=' . O_SCRIPT_VERSION . '"></script>';
          $ajax_quick_edit_loaded = 1;
        }

        $onclick = 'onclick="TSQuickEditPost(\'post_message_' . $pid . '\',\'' . $tid . '\',\'editpost.php?tid=' . $tid . '&amp;pid=' . $pid . $showpagenumber . '\');"';
      }

      $editbutton = '<input value="' . $lang->tsf_forums['edit_post'] . '" ' . $onclick . ' type="button" />';
    }

    if ((($moderator OR $forummoderator) OR (($permissions[$thread['parent']]['candeleteposts'] == 'yes' AND $thread['closed'] != 'yes') AND $thread['uid'] == $CURUSER['id'])))
    {
      $deletebutton = '<input value="' . $lang->tsf_forums['delete_post'] . '" onclick="jumpto(\'deletepost.php?tid=' . $tid . '&amp;pid=' . $pid . '&amp;page=' . intval ($_GET['page']) . '\');" type="button" />';
    }

    if ((($moderator OR $forummoderator) OR ($permissions[$thread['parent']]['canpostreplys'] == 'yes' AND $thread['closed'] != 'yes')))
    {
      $quotebutton = '<input value="' . $lang->tsf_forums['quote_post'] . '" onclick="jumpto(\'newreply.php?tid=' . $tid . '&amp;pid=' . $pid . '\');" type="button" />';
    }

    $country = '' . $lang->tsf_forums['country'] . '<img src=\'' . $BASEURL . '/' . $pic_base_url . 'flag/' . $thread['flagpic'] . '\' alt=\'' . $thread['countryname'] . '\' title=\'' . $thread['countryname'] . '\' style=\'margin-center: 2pt\' height=\'10px\' class=\'inlineimg\' />';
    $usericons = get_user_icons ($thread);
    $dateline = my_datee ($dateformat, $thread['dateline']);
    $timeline = my_datee ($timeformat, $thread['dateline']);
    $message = format_comment ($thread['message']);
    if ((isset ($_GET['highlight']) AND !empty ($_GET['highlight'])))
    {
      $message = highlight (htmlspecialchars_uni ($_GET['highlight']), $message);
    }

    if ((!empty ($thread['edittime']) AND !empty ($thread['edituid'])))
    {
      $editdate = my_datee ($dateformat, $thread['edittime']);
      $edittime = my_datee ($timeformat, $thread['edittime']);
      $editedby = sprintf ($lang->tsf_forums['editedby'], $editdate, $edittime, build_profile_link (htmlspecialchars_uni ($thread['editusername']), $thread['edituid']));
    }

    if (!empty ($thread['modnotice']))
    {
      $modnotice_info = @explode ('~', $thread['modnotice_info']);
      $modnotice_info[2] = my_datee ($dateformat, $modnotice_info[2]) . ' ' . my_datee ($timeformat, $modnotice_info[2]);
      $modnotice = '
		<br />
		<div class="modnotice">
			' . sprintf ($lang->global['modnotice'], $modnotice_info[1], $modnotice_info[0], $modnotice_info[2], format_comment ($thread['modnotice'])) . '				
		</div>
		';
    }

    if ((($moderator OR $forummoderator) OR $thread['uid'] == $CURUSER['id']))
    {
      $_warnlevel = '<br />' . get_warn_level ($thread['timeswarned']) . '<br />';
    }

    $ThankButton = '';
    $Listthanks = '';
    $Showthanks = array ();
    $Thanked = false;
    if (isset ($TCache[$thread['pid']]))
    {
      foreach ($TCache[$thread['pid']] as $Thanks)
      {
        $Showthanks[] = $Thanks['username'];
        if ($Thanks['userid'] === $CURUSER['id'])
        {
          $Thanked = true;
          continue;
        }
      }

      $ThanksCount = count ($Showthanks);
      if (0 < $ThanksCount)
      {
        $Listthanks = '			
			<tr>
				<td colspan="2" id="thanks_zone_' . $pid . '">
					<div id="show_thanks_' . $pid . '" style="clear: both;">
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
											' . implode (', ', $Showthanks) . '
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
			';
      }
    }
    else
    {
      $Listthanks = '			
		<tr>
			<td colspan="2" id="thanks_zone_' . $pid . '" class="subheader">
				<div id="show_thanks_' . $pid . '" style="clear: both;">
				</div>
			</td>
		</tr>
		';
    }

    if ((($usergroups['canthanks'] == 'yes' AND $thankssystem == 'yes') AND $CURUSER['id'] != $thread['uid']))
    {
      $ThankButton = '
		<span id="loading-layerT" style="display:none;"><img src="./images/spinner.gif" border="0" alt="" title="" /></span>
		<span id="thanks_button_' . $pid . '" style="display: ' . ($Thanked == false ? 'inline' : 'none') . ';"><input type="button" value="' . $lang->global['buttonthanks'] . '" onclick="javascript:TSFajaxquickthanks(' . $tid . ', ' . $pid . ');" /></span>
		<span id="remove_thanks_button_' . $pid . '" style="display: ' . ($Thanked == false ? 'none' : 'inline') . ';"><input type="button" value="' . $lang->global['buttonthanks2'] . '" onclick="javascript:TSFajaxquickthanks(' . $tid . ', ' . $pid . ', true);" /></span>';
    }

    $str2 .= '
		<!-- start: post#' . $pid . ' -->
		' . (!$isfirstpost ? '' : '</table><br /><table width="100%" border="0" cellspacing="0" cellpadding="4" style="clear: both;">') . '
			<tr>
				<td colspan="2" class="subheader" name="pid' . $pid . '">
					<div style="float: right;">
						<strong>' . $lang->tsf_forums['post'] . '<a href="#pid' . $pid . '">#' . $count . '</a>' . ($usergroups['canmassdelete'] === 'yes' ? ' <input type="checkbox" name="postids[]" value="' . $pid . '" style="margin: 0px 0px 0px 5px; padding: 0px; vertical-align: middle;" />' : '') . '</strong>
					</div>
					<div style="float: left;">
						<a name="pid' . $pid . '" id="pid' . $pid . '"><img src="./images/post_old.gif" border="0" class="inlineimg" /></a> ' . $dateline . ' ' . $timeline . '
					</div>
				</td>
			</tr>
			<tr>
				<td class="trow1" style="text-align: center;" valign="top" width="20%">					
					' . $poster . ' ' . $usericons . '<br />
					' . $usertitle . '
					' . $poster_title . '<br />
					' . $avatar . '<br />
					' . get_user_png ($thread) . '<br />
					' . $join_date . '<br />
					' . $totalposts . '<br />
					' . $status . '<br />
					' . $country . '<br />
					' . $_warnlevel . '
				</td>
				<script type="text/javascript">
					menu_register("quickmenu' . $pid . '");
				</script>
				
				<td class="trow1" style="text-align: left;" valign="top" width="80%">
					<img src="./images/icons/icon' . intval ($thread['iconid']) . '.gif" border="0" class="inlineimg" /> 
					<span class="smalltext"><strong>' . $threadsubject . '</strong></span><hr />
					<div id="post_message_' . $pid . '" style="display: inline;">' . $message . '</div>
					' . $display_attachment . '
					' . $modnotice . '
					<div style="text-align: right; vertical-align: bottom;">' . $editedby . '</div>
					' . $signature . '
				</td>
			</tr>				
			<tr>
				<td class="trow1" width="15%" valign="middle" style="white-space: nowrap; text-align: center;">
				<input value="' . $lang->tsf_forums['top'] . '" onclick="self.scrollTo(0, 0); return false;" type="button" /> <input value="' . $lang->tsf_forums['report_post'] . '" onclick="jumpto(\'' . $BASEURL . '/report.php?action=reportforumpost&amp;reportid=' . $pid . '\');" type="button" />
				</td>
				<td class="trow1" style="text-align: center;" valign="top">
					<div style="float: right;">
						' . $ThankButton . '
						' . $deletebutton . '
						' . $editbutton . '						 
						' . $quotebutton . '
						<input type="button" value="' . $lang->tsf_forums['quick_reply'] . '" onclick="quote(\'message\', \'quickreply\', \'' . $QuoteTag . '\');" />
					</div>
				</td>
			</tr>
			' . $Listthanks . '
		<!-- end: post#' . $pid . ' -->		
	';
    $quickmenu .= '
		<div id="quickmenu' . $pid . '_menu" class="menu_popup" style="display:none;">
			<table border="1" cellspacing="0" cellpadding="5">
				<tr>
					<td align="center" class="thead"><b>' . $lang->global['quickmenu'] . ' ' . $thread['userusername'] . '</b></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . tsf_seo_clean_text (strip_tags ($poster), 'u', $thread['uid'], '', 'ts') . '">' . $lang->global['qinfo1'] . '</a></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/sendmessage.php?receiver=' . $thread['uid'] . '">' . sprintf ($lang->global['qinfo2'], $thread['userusername']) . '</td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserposts&amp;id=' . $thread['uid'] . '">' . sprintf ($lang->global['qinfo3'], $thread['userusername']) . '</a></td>
				</tr>

				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/tsf_forums/tsf_search.php?action=finduserthreads&amp;id=' . $thread['uid'] . '">' . sprintf ($lang->global['qinfo4'], $thread['userusername']) . '</a></td>
				</tr>
				
				<tr>
					<td class="subheader"><a href="' . $BASEURL . '/friends.php?action=add_friend&amp;friendid=' . $thread['uid'] . '">' . sprintf ($lang->global['qinfo5'], $thread['userusername']) . '</td>
				</tr>
				
				' . ($moderator ? '<tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=edituser&amp;userid=' . $thread['uid'] . '">' . $lang->global['qinfo6'] . '</a></td></tr><tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=warnuser&amp;userid=' . $thread['uid'] . '">' . $lang->global['qinfo7'] . '</td></tr>' : '') . '			  
			</table>
		</div>';
    if (($thread['votenum'] AND !isset ($ratingimage)))
    {
      $thread['voteavg'] = number_format ($thread['votetotal'] / $thread['votenum'], 2);
      $thread['rating'] = round ($thread['votetotal'] / $thread['votenum']);
      $ratingimgalt = sprintf ($lang->tsf_forums['tratingimgalt'], $thread['votenum'], $thread['voteavg']);
      $ratingimage = '' . '<img src="images/rating/rating_' . $thread['rating'] . '.gif" alt="' . $ratingimgalt . '" title="' . $ratingimgalt . '" border="0" class="inlineimg" />';
    }

    if (($thread['pollid'] AND !isset ($pollid)))
    {
      $pollid = intval ($thread['pollid']);
    }

    if (!$isfirstpost)
    {
      $str2 .= '
				</table>				
				' . (!empty ($f_ads) ? '
				<br />
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
					<tr>
						<td align="center">
							' . $f_ads . '
						</td>
					</tr>
				</table>' : '');
    }

    ++$count;
  }

  $poll = '';
  if (isset ($pollid))
  {
    $pollbits = '';
    $counter = 1;
    $pollquery = sql_query ('
		SELECT *
		FROM ' . TSF_PREFIX . ('' . 'poll
		WHERE pollid = ' . $pollid . '
	'));
    $pollinfo = mysql_fetch_assoc ($pollquery);
    $pollinfo['question'] = htmlspecialchars_uni ($pollinfo['question']);
    $splitoptions = explode ('~~~', $pollinfo['options']);
    $splitvotes = explode ('~~~', $pollinfo['votes']);
    $showresults = 0;
    $uservoted = 0;
    $voted = 0;
    $skipstatus = 0;
    if ($usergroups['canvote'] != 'yes')
    {
      $nopermission = 1;
    }

    if (((!$pollinfo['active'] OR $isthreadclosed) OR $nopermission))
    {
      $showresults = 1;
    }
    else
    {
      if ((isset ($_COOKIE['showpollresult']) AND $_COOKIE['showpollresult'] == $pollid))
      {
        $voted = true;
        $skipstatus = true;
      }
      else
      {
        $pollquery2 = sql_query ('
			SELECT voteoption
			FROM ' . TSF_PREFIX . ('' . 'pollvote
			WHERE pollid = ' . $pollid . ' AND userid = ' . $CURUSER['id'] . '
			'));
        $voted = (0 < @mysql_num_rows ($pollquery2) ? true : false);
      }

      if ($voted)
      {
        $uservoted = 1;
      }
    }

    foreach ($splitvotes as $index => $value)
    {
      $pollinfo['numbervotes'] += $value;
    }

    if (($showresults OR $uservoted))
    {
      if ($uservoted)
      {
        $uservote = array ();
        while ($pollvote = @mysql_fetch_assoc ($pollquery2))
        {
          $uservote['' . $pollvote['voteoption']] = 1;
        }
      }
    }

    foreach ($splitvotes as $index => $value)
    {
      $arrayindex = $index + 1;
      $option['uservote'] = (isset ($uservote['' . $arrayindex]) ? true : false);
      $option['question'] = htmlspecialchars_uni ($splitoptions['' . $index]);
      $option['votes'] = $value;
      $option['number'] = $counter;
      if (($showresults OR $uservoted))
      {
        if ($value <= 0)
        {
          $option['percent'] = 0;
        }

        $option['percent'] = number_format (($value < $pollinfo['numbervotes'] ? $value / $pollinfo['numbervotes'] * 100 : 100), 2);
        $option['graphicnumber'] = $option['number'] % 6 + 1;
        $option['barnumber'] = round ($option['percent']) * 2;
        if ($nopermission)
        {
          $pollstatus = $lang->tsf_forums['poll13'];
        }
        else
        {
          if ($showresults)
          {
            $pollstatus = $lang->tsf_forums['poll12'];
          }
          else
          {
            if (($uservoted AND !$skipstatus))
            {
              $pollstatus = $lang->tsf_forums['poll11'];
            }
          }
        }

        $pollbits .= '
			<tr>
				<td class="alt1" width="50%" align="left">
					' . ($option['uservote'] ? '<em>' . $option['question'] . '</em> *' : $option['question']) . '
				</td>
				<td class="alt2" width="50%">
					<img src="images/polls/bar' . $option['graphicnumber'] . '-1.gif" alt="" width="3" height="10"/><img src="images/polls/bar' . $option['graphicnumber'] . '.gif" alt="" width="' . $option['barnumber'] . '" height="10"/><img src="images/polls/bar' . $option['graphicnumber'] . '-r.gif" alt="" width="3" height="10" />
				</td>
				<td class="alt1" align="center" title=""><strong>' . $option['votes'] . '</strong></td>
				<td class="alt2" align="right" nowrap="nowrap">' . $option['percent'] . '%</td>
			</tr>
			';
      }
      else
      {
        $pollbits .= '
			<div><label for="rb_optionnumber_' . $option['number'] . '"><input type="radio" name="optionnumber" value="' . $option['number'] . '" id="rb_optionnumber_' . $option['number'] . '" />' . $option['question'] . '</label></div>
			';
      }

      ++$counter;
    }

    if (($showresults OR $uservoted))
    {
      $poll = '
		<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
		<tr>
			<td class="thead" colspan="4">
				' . (($moderator OR $forummoderator) ? '<span class="smallfont" style="float:right"><a href="poll.php?do=polledit&amp;pollid=' . $pollid . '&amp;tid=' . $tid . '">' . $lang->tsf_forums['poll16'] . '</a></span>' : '') . '
				' . $lang->tsf_forums['poll14'] . '<span class="normal">: ' . $pollinfo['question'] . '</span>
			</td>
		</tr>
		' . $pollbits . '
		<tr>
			<td class="tfoot" colspan="4" align="center"><span class="smallfont">' . $lang->tsf_forums['poll15'] . ' <strong>' . $pollinfo['numbervotes'] . '</strong>. ' . $pollstatus . '</span></td>
		</tr>
		</table>
		<br />
		';
    }
    else
    {
      $poll = '
		<form action="poll.php?do=pollvote&amp;pollid=' . $pollid . '&amp;tid=' . $tid . '" method="post">
		<input type="hidden" name="posthash" value="' . sha1 ($pollid . $securehash . $pollid) . '" />
		<input type="hidden" name="do" value="pollvote" />
		<input type="hidden" name="pollid" value="' . $pollid . '" />
		<input type="hidden" name="tid" value="' . $tid . '" />

		<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
		<tr>
			<td class="thead">
				' . (($moderator OR $forummoderator) ? '<span class="smallfont" style="float:right"><a href="poll.php?do=polledit&amp;pollid=' . $pollid . '&amp;tid=' . $tid . '">' . $lang->tsf_forums['poll16'] . '</a></span>' : '') . '
				' . $lang->tsf_forums['poll17'] . '<span class="normal">: ' . $pollinfo['question'] . '</span>
			</td>
		</tr>		
		<tr>
			<td class="panelsurround" align="center">
			<div class="panel">
				<div style="width: 640px;" align="left">				
					<fieldset class="fieldset">
						<legend>' . $lang->tsf_forums['poll5'] . '</legend>
						<div style="padding: 3px;">
							<div style="margin-bottom: 3px;"><strong>' . $pollinfo['question'] . '</strong></div>
							' . $pollbits . '
						</div>
					</fieldset>
					
					<div>
						<span style="float: right;"><a href="poll.php?do=showresults&amp;pollid=' . $pollid . '&amp;tid=' . $tid . '">' . $lang->tsf_forums['poll18'] . '</a></span>
						<input type="submit" class="button" value="' . $lang->tsf_forums['poll19'] . '" />
					</div>
				
				</div>
			</div>
			</td>
		</tr>
		</table>
		</form>
		<br />
		';
    }
  }

  $massdelete = $massdelete2 = '';
  if ($usergroups['canmassdelete'] === 'yes')
  {
    $massdelete = '
	<form method="post" action="' . $BASEURL . '/tsf_forums/massdelete.php" name="massdeleteform">
	<input type="hidden" name="action" value="deleteposts" />
	<input type="hidden" name="parentfid" value="' . $realforumid . '" />
	<input type="hidden" name="currentfid" value="' . $fid . '" />
	<input type="hidden" name="threadids[]" value="' . $tid . '" />
	<input type="hidden" name="hash" value="' . $forumtokencode . '" />';
    $massdelete2 = '
	<input type="button" value="' . $lang->tsf_forums['deleteposts'] . '" onclick="javascript: document.massdeleteform.submit()" />
	</form>';
  }

  $thread_search_options = '<a href="#" id="thread_search_options' . $tid . '">' . $lang->tsf_forums['sthread'] . '</a>
<script type="text/javascript">
	function quote(textarea,form,quote)
	{
		var area=document.forms[form].elements[textarea];
		area.value=area.value+quote+"\\n";
		area.focus();
	};
</script>
<script type="text/javascript">
		menu_register("thread_search_options' . $tid . '",false);
</script>
<div id="thread_search_options' . $tid . '_menu" class="menu_popup" style="display:none;">
	<table border="1" cellspacing="0" cellpadding="5" width="250">
		<tr>
			<td class="thead"><b>' . $lang->tsf_forums['sthread'] . '</b></td>
		</tr>
		<tr>
			<td>
			<form method="post" action="tsf_search.php">
			<input type="hidden" name="action" value="searchinthread" />
			<input type="hidden" name="threadid" value="' . $tid . '" />
			<input type="hidden" name="author" value="" />
			<input type="hidden" name="matchusername" value="" />
			<input type="text" name="keywords" value="" size="20" /> <input type="submit" value="' . $lang->tsf_forums['search'] . '" />
			<br />
			</form>
			</td>
		</tr>
		<tr>
			<td class="subheader" align="center"><a href="tsf_search.php?action=searchthread&amp;threadid=' . $tid . '">' . $lang->tsf_forums['goadvanced'] . '</a></td>
		</tr>
	</table>
</div>';
  $thread_options = '
<a href="#" id="thread_options' . $tid . '">' . $lang->tsf_forums['toptions'] . '</a>
<script type="text/javascript">
		menu_register("thread_options' . $tid . '",false);
</script>
<div id="thread_options' . $tid . '_menu" class="menu_popup" style="display:none;">
	<table border="1" cellspacing="0" cellpadding="5" width="200">
		<tr>
			<td class="thead"><b>' . $lang->tsf_forums['toptions'] . '</b></td>
		</tr>
		<tr>
			<td class="subheader">' . $subslink . '</td>
		</tr>
		<tr>
			<td class="subheader"><a href="./misc.php?action=email_thread&amp;tid=' . $tid . '"><b>' . $lang->tsf_forums['ethread'] . '</b></a></td>
		</tr>
		<tr>
			<td class="subheader"><a href="./misc.php?action=print_thread&amp;tid=' . $tid . '"><b>' . $lang->tsf_forums['pthread'] . '</b></a></td>
		</tr>
	</table>
</div>';
  $str = '
	<script type="text/javascript" src="' . $BASEURL . '/tsf_forums/scripts/quick_thanks.js?v=' . O_SCRIPT_VERSION . '"></script>
	' . $poll . '
	<!-- start: BBcode Styles -->
	' . $QuickEditor->GenerateCSS () . '
	<!-- start: BBcode Styles -->

	<!-- start: forumdisplay_newthread -->
	<a name="top"></a>
	<div style="float: left; margin-bottom: 3px;" id="navcontainer_f">
		' . $multipage . '
	</div>
	<div style="float: right; margin-bottom: 3px;">
		<input value="' . ($isthreadclosed === false ? $lang->tsf_forums['new_reply'] : $lang->tsf_forums['thread_locked']) . '" onclick="jumpto(\'newreply.php?tid=' . $tid . '\');" type="button" />
	</div>
	<!-- end: forumdisplay_newthread -->

	<table width="100%" border="0" cellspacing="0" cellpadding="4" style="clear: both;">
		<tr>
			<td colspan="3" class="thead" align="left">
				<p class="smalltext"><div style="float: right;"><b>' . $thread_options . '&nbsp;' . $thread_search_options . '&nbsp;' . show_rate_button () . '</b></div></p>
			</td>			
		</tr>
		' . $massdelete . '
		' . $str2 . '
		<script type="text/javascript">
			menu.activate(true);
		</script>		
	</table>
	' . ($useajax == 'yes' ? '
		<script type="text/javascript" src="' . $BASEURL . '/scripts/prototype.js?v=' . O_SCRIPT_VERSION . '"></script>
		<script type="text/javascript" src="./scripts/quick_reply.js?v=' . O_SCRIPT_VERSION . '"></script>
		<div id="ajax_quick_reply"></div>
		' : '') . '
	' . $quickmenu . '
	<p></p>';
  $forumjump = build_forum_jump ($fid);
  $quickreplymod = '';
  if (($moderator OR $forummoderator))
  {
    $quickreplymod = '<label><input class="checkbox" name="closethread" value="yes" type="checkbox"' . ($isthreadclosed ? ' checked="checked"' : '') . ' />' . $lang->tsf_forums['mod_options_c'] . '</label> 
				<label><input class="checkbox" name="stickthread" value="yes" type="checkbox"' . ($isstickythread ? ' checked="checked"' : '') . ' />' . $lang->tsf_forums['mod_options_s'] . '</label>
				</span>';
  }
  else
  {
    $quickreplymod = '<input name="closethread" value="no" type="hidden"><input name="stickthread" value="no" type="hidden" />';
  }

  if (($moderator OR $forummoderator))
  {
    $mod_options = '
		<form action="moderation.php" method="get" style="margin-top: 0pt; margin-bottom: 0pt;">
			<input name="tid" value="' . $tid . '" type="hidden" />
			<input type="hidden" name="hash" value="' . $forumtokencode . '" />
			<span class="smalltext">
			<strong>' . $lang->tsf_forums['mod_options'] . '</strong></span><br />
			<select name="action">
			<optgroup label="' . $lang->tsf_forums['mod_options'] . '">
				<option value="sticky">' . $lang->tsf_forums['mod_options_ss'] . '</option>	
				<option value="openclosethread">' . $lang->tsf_forums['mod_options_cc'] . '</option>
				<option value="deletethread">' . $lang->tsf_forums['mod_options_dd'] . '</option>
				<option value="movethread">' . $lang->tsf_forums['mod_options_m'] . '</option>
			</optgroup>
			</select>
			<!-- start: gobutton -->
			<input class="button" value="' . $lang->tsf_forums['go_button'] . '" type="submit" />
			<!-- end: gobutton -->
		</form>';
  }

  $QuickEditor->FormName = 'quickreply';
  $QuickEditor->TextAreaName = 'message';
  $str .= '
		<!-- start: forumdisplay_newthread -->
		<div style="float: left; margin-bottom: 5px;" id="navcontainer_f">
			' . $multipage . '
		</div>
		<div style="float: right; margin-bottom: 5px;">
			<input value="' . ($isthreadclosed === false ? $lang->tsf_forums['new_reply'] : $lang->tsf_forums['thread_locked']) . '" onclick="jumpto(\'newreply.php?tid=' . $tid . '\');" type="button" /> ' . $massdelete2 . '
		</div>
		<!-- end: forumdisplay_newthread -->

		<!-- start: forumdisplay_quickreply -->
		' . $QuickEditor->GenerateJavascript () . '
		<form method="post" action="newreply.php" name="quickreply" id="quickreply">
		<input type="hidden" name="tid" value="' . $tid . '" />
		<input type="hidden" name="subject" value="' . $threadsubject . '" />
		<table border="0" cellspacing="0" cellpadding="5" width="100%" align="center" style="clear: both;">
			<tr>
				<td class="thead" align="center">' . ts_collapse ('quickreply') . '<strong>' . $lang->tsf_forums['quick_reply'] . '</strong></td>
			</tr>
				' . ts_collapse ('quickreply', 2) . '
			<tr>
				<td>
					' . $QuickEditor->GenerateBBCode () . '
				</td>
			</tr>
			<tr>
				<td class="trow2" align="center"><textarea id="message" name="message" style="width:850px;height:120px;"></textarea><br />' . $quickreplymod . '</td>
			</tr>
			<tr>			
				<td class="trow2" align="center">
					<span id="loading-layerS" style="display:none;"><img src="./images/spinner.gif" border="0" alt="" title="" /></span>
					' . ($useajax == 'yes' ? '<input type="button" value="' . $lang->tsf_forums['post_reply'] . '" id="quickreplybutton" name="quickreplybutton" onclick="TSajaxquickreply(' . $tid . ', ' . $count . ', ' . intval ($_GET['page']) . ');" />' : '<input name="submit" value="' . $lang->tsf_forums['post_reply'] . '" tabindex="3" accesskey="s" type="submit" />') . ' <input name="previewpost" value="' . $lang->tsf_forums['preview_reply'] . '" tabindex="4" type="submit" />	<input type="button" value="' . $lang->tsf_forums['goadvanced'] . '" onclick="jumpto(\'' . $BASEURL . '/tsf_forums/newreply.php?tid=' . $tid . '\')" />			
				</td>
			</tr>
		</table>
		</form>
		<!-- end: forumdisplay_quickreply -->
		<br />
		<table border="0" cellspacing="0" cellpadding="5" width="100%" align="center" class="subheader" style="clear: both;">
		<tr>
		<td>
		' . (isset ($mod_options) ? '
			<div style="float: left; margin-bottom: 5px; margin-top: 5px;">
				' . $mod_options . '
			</div>' : '') . '
			<div style="float: right; margin-bottom: 5px; margin-top: 5px;">
				' . $forumjump . '
			</div>
		</td>
		</tr>
		</table>
	';
  stdhead ('' . $SITENAME . ' TSF FORUMS : ' . TSF_VERSION . ' :: ' . str_replace ('&amp;', '&', $currentforum) . ' :: ' . unhtmlspecialchars ($realsubject), true, 'supernote', 'quick_editor');
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  add_breadcrumb ($realforum, tsf_seo_clean_text ($realforum, ($ftype == 's' ? 'fd' : 'f'), $realforumid));
  add_breadcrumb ($currentforum, tsf_seo_clean_text ($currentforum, 'fd', $fid));
  add_breadcrumb ($realsubject, tsf_seo_clean_text ($realsubject, 't', $tid));
  build_breadcrumb ();
  echo $str;
  stdfoot ();
  sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET views = views + 1 WHERE tid = ' . sqlesc ($tid));
  sql_query ('REPLACE INTO ' . TSF_PREFIX . ('' . 'threadsread SET tid=\'' . $tid . '\', uid=\'') . $CURUSER['id'] . '\', dateline=\'' . TIMENOW . '\'');
?>
