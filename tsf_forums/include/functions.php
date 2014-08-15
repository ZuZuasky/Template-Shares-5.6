<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function delete_attachments ($pid, $tid, $aid = '')
  {
    global $f_upload_path;
    $delete_files = array ();
    $query = sql_query ('SELECT a_name FROM ' . TSF_PREFIX . 'attachments WHERE a_pid = ' . sqlesc ($pid) . ' AND a_tid = ' . sqlesc ($tid));
    if (0 < mysql_num_rows ($query))
    {
      while ($delete = mysql_fetch_assoc ($query))
      {
        $delete_files[] = $delete['a_name'];
      }
    }

    if (0 < count ($delete_files))
    {
      foreach ($delete_files as $nowdelete)
      {
        if (file_exists ($f_upload_path . $nowdelete))
        {
          unlink ($f_upload_path . $nowdelete);
          continue;
        }
      }
    }

    sql_query ('DELETE FROM ' . TSF_PREFIX . 'attachments WHERE a_pid = ' . sqlesc ($pid) . ' AND a_tid = ' . sqlesc ($tid) . ($aid ? ' AND a_id = ' . sqlesc ($aid) : ''));
  }

  function show_icon_list ()
  {
    global $lang;
    $icon_path = './images/icons/';
    $icon_list = array ();
    if ($handle = opendir ($icon_path))
    {
      while (false !== $file = readdir ($handle))
      {
        if (((($file != '.' AND $file != '..') AND get_extension ($file) == 'gif') AND $file != 'icon1.gif'))
        {
          $icon_number = str_replace (array ('icon', 'gif', '.'), '', $file);
          $icon_list[] = '
				<td class="none"><input name="iconid" value="' . $icon_number . '" type="radio"' . ($_POST['iconid'] == $icon_number ? ' checked="checked"' : '') . ' /></td>
				<td width="12%" class="none"><img src="' . $icon_path . $file . '" border="0" /></td>';
          continue;
        }
      }

      closedir ($handle);
      $show_icons = '
		<div style="padding: 3px;">
			<table border="0" cellpadding="1" cellspacing="0" width="95%">
				<tbody>
					<tr>
						<td colspan="15" class="none"><div style="margin-bottom: 3px;"><b>' . $lang->tsf_forums['picons2'] . '</b><hr /></div></td>
					</tr>
					<tr>
		';
      $count = 1;
      foreach ($icon_list as $icon)
      {
        if ($count % 7 == 1)
        {
          $show_icons .= '</tr><tr>';
        }

        $show_icons .= $icon;
        ++$count;
      }

      $show_icons .= '
					<td class="none"><input name="iconid" value="0" type="radio"' . ((!$_POST['iconid'] OR $_POST['iconid'] == 0) ? ' checked="checked"' : '') . ' /></td>
					<td width="12%" class="none"><b>' . $lang->tsf_forums['pcions3'] . '</b></td>
					</tr>
				</tbody>
			</table>
		</div>
		';
      return $show_icons;
    }

    return false;
  }

  function check_forum_password ($password = '', $fid = 0, $redirect = '')
  {
    global $CURUSER;
    global $securehash;
    if ((isset ($_GET['do']) AND $_GET['do'] == 'password'))
    {
      if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND !empty ($_POST['password'])))
      {
        $query = sql_query ('SELECT password FROM ' . TSF_PREFIX . 'forums WHERE password=' . sqlesc ($_POST['password']) . ('' . ' AND fid=' . $fid));
        if (0 < mysql_num_rows ($query))
        {
          $expires = 60 * 60 * 24 * 30;
          $password = md5 ($CURUSER['id'] . $_POST['password'] . $securehash);
          @setcookie ('forumpass_' . $fid, $password, TIMENOW + $expires, '/');
          return null;
        }

        password_forum ($fid, $redirect);
        return null;
      }

      password_forum ($fid, $redirect);
      return null;
    }

    if (($password != '' AND (($_COOKIE['forumpass_' . $fid] != md5 ($CURUSER['id'] . $password . $securehash) OR empty ($_COOKIE['forumpass_' . $fid])) OR strlen ($_COOKIE['forumpass_' . $fid]) != 32)))
    {
      header ('' . 'Location: ' . $redirect);
      exit ();
    }

  }

  function password_forum ($fid, $redirect)
  {
    global $lang;
    global $BASEURL;
    global $rootpath;
    global $vkeyword;
    stdhead ($lang->tsf_forums['fpassword']);
    if ($vkeyword == 'yes')
    {
      echo '
		<script type="text/javascript">
			function showkwmessage()
			{				
				alert("' . $lang->global['vkeyword'] . '");
			}
		</script>
		<script type="text/javascript" src="' . $BASEURL . '/scripts/keyboard.js?v=' . O_SCRIPT_VERSION . '" charset="UTF-8"></script>
		<link rel="stylesheet" type="text/css" href="' . $BASEURL . '/scripts/keyboard.css?v=' . O_SCRIPT_VERSION . '">';
      $isvkeywordenabled = ' class="keyboardInput" onkeypress="showkwmessage();return false;"';
    }

    echo '
	<form method="post" action="' . $redirect . '">
	<table width="100%" border="0" class="none" style="clear: both;" cellspacing="0" cellpadding="5">
	<tr>
		<td class="thead">
			' . $lang->tsf_forums['fpassword'] . '
		</td>
	</tr>
	<tr>
		<td>
			' . $lang->tsf_forums['fpassword2'] . ' <input type="password" name="password" value="" size="32"' . $isvkeywordenabled . '> <input type="submit" value="' . $lang->tsf_forums['fpassword3'] . '" />
		</td>
	</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
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
    $query = sql_query ('SELECT s.*, u.email, u.username FROM ' . TSF_PREFIX . 'subscribe s LEFT JOIN users u ON (s.userid=u.id) WHERE s.tid = ' . sqlesc ($tid) . ' AND s.userid != ' . sqlesc ($CURUSER['id']));
    if (0 < mysql_num_rows ($query))
    {
      require_once INC_PATH . '/functions_pm.php';
      while ($sub = mysql_fetch_assoc ($query))
      {
        send_pm ($sub['userid'], sprintf ($lang->tsf_forums['msubs'], $sub['username'], $subject, $CURUSER['username'], $BASEURL, $tid, $SITENAME), $subject);
        sent_mail ($sub['email'], $subject, sprintf ($lang->tsf_forums['msubs'], $sub['username'], $subject, $CURUSER['username'], $BASEURL, $tid, $SITENAME), 'subs', false);
      }
    }

  }

  function show_announcements ($forumid = '')
  {
    global $lang;
    global $BASEURL;
    global $dateformat;
    global $timeformat;
    global $pic_base_url;
    if ((empty ($forumid) OR !is_valid_id ($forumid)))
    {
      return null;
    }

    ($query = sql_query ('SELECT a.*, u.id, u.username, g.namestyle, g.title as usergrouptitle FROM ' . TSF_PREFIX . 'announcement a LEFT JOIN users u ON (a.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE a.forumid = ' . sqlesc ($forumid) . ' ORDER by a.posted DESC') OR sqlerr (__FILE__, 201));
    if (mysql_num_rows ($query) == 0)
    {
      return null;
    }

    $str = '
		<!-- start: Forumdisplay/Announcements -->
		<table border="0" cellspacing="0" cellpadding="5" style="clear: both; margin-bottom: 5px;" width="100%">
		<tr>
			<td class="thead" colspan="7">
				<strong>' . $lang->tsf_forums['atitle'] . '</strong>
			</td>
		</tr>';
    while ($a = mysql_fetch_assoc ($query))
    {
      $str .= '
			<tr>
				<td class="alt1" width="5%" align="center"><a href="#" onclick="return ts_open_popup(\'' . $BASEURL . '/tsf_forums/announcement.php?aid=' . intval ($a['announcementid']) . '\', 650, 450);"><img src="' . $BASEURL . '/tsf_forums/images/announcement_new.gif" border="0" alt="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '" title="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '" /></a></td>
				<td class="alt2" colspan="6">
					<div>
						<span class="smallfont" style="float: right;">' . $lang->tsf_forums['views'] . ': <strong>' . $a['views'] . '</strong> <a href="#" onclick="return ts_open_popup(\'' . $BASEURL . '/tsf_forums/announcement.php?aid=' . intval ($a['announcementid']) . '\', 650, 450);"><img class="inlineimg" src="' . $BASEURL . '/' . $pic_base_url . 'comments2.gif" alt="" border="0" /></a></span>
						<strong>' . $lang->tsf_forums['announcements'] . '</strong> <a href="#" <a href="#" onclick="return ts_open_popup(\'' . $BASEURL . '/tsf_forums/announcement.php?aid=' . intval ($a['announcementid']) . '\', 650, 450);">' . htmlspecialchars_uni ($a['title']) . '</a>
					</div>
					<div>
						<span style="float: right;"><span class="smallfont">' . my_datee ($dateformat, $a['posted']) . ' ' . my_datee ($timeformat, $a['posted']) . '</span></span>
						<span class="smallfont"><a href="' . ts_seo ($a['id'], $a['username']) . '">' . get_user_color ($a['username'], $a['namestyle']) . '</a> (' . $a['usergrouptitle'] . ')</span>
					</div>
				</td>
			</tr>';
    }

    $str .= '
		</table>
		<!-- end: Forumdisplay/Announcements -->';
    return $str;
  }

  function forum_permissions ($fid = 0)
  {
    global $CURUSER;
    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forumpermissions WHERE gid = ' . sqlesc ($CURUSER['usergroup']) . ($fid === 0 ? '' : ' WHERE fid = ' . sqlesc ($fid))) OR sqlerr (__FILE__, 242));
    while ($perm = mysql_fetch_assoc ($query))
    {
      $permissions[$perm['fid']] = $perm;
    }

    @mysql_free_result ($query);
    return ($fid === 0 ? $permissions : $permissions[$fid]);
  }

  function build_breadcrumb ()
  {
    global $nav;
    global $navbits;
    global $lang;
    $navsep = ' / ';
    if (@is_array ($navbits))
    {
      @reset ($navbits);
      foreach ($navbits as $key => $navbit)
      {
        if (isset ($navbits[$key + 1]))
        {
          if (isset ($navbits[$key + 2]))
          {
            $sep = $navsep;
          }
          else
          {
            $sep = '';
          }

          $nav .= '' . '<a href="' . $navbit['url'] . '">' . $navbit['name'] . '</a>' . $sep;
          continue;
        }
      }
    }

    $navsize = @count ($navbits);
    $navbit = $navbits[$navsize - 1];
    $activesep = '';
    if ($nav)
    {
      $activesep = ' / ';
    }

    $activebit = '' . $navbit['name'];
    $donenav = '' . '<div id="shadetabs">' . $nav . $activesep . $activebit . '</div>';
    echo '' . '<div style="margin-bottom: 3px;">' . $donenav . '</div>';
  }

  function add_breadcrumb ($name, $url = '')
  {
    global $navbits;
    $navsize = @count ($navbits);
    $navbits[$navsize]['name'] = $name;
    $navbits[$navsize]['url'] = $url;
  }

  function reset_breadcrumb ()
  {
    global $navbits;
    $newnav[0]['name'] = $navbits[0]['name'];
    $newnav[0]['url'] = $navbits[0]['url'];
    unset ($GLOBALS[navbits]);
    $GLOBALS['navbits'] = $newnav;
  }

  function build_profile_link ($username = '', $uid = 0, $target = '')
  {
    global $lang;
    global $BASEURL;
    if ((!$username OR !is_valid_id ($uid)))
    {
      return $lang->tsf_forums['guest'];
    }

    if (!empty ($target))
    {
      $target = ('' . ' target="' . $target . '"');
    }

    return '<a href="' . tsf_seo_clean_text (strip_tags ($username), 'u', $uid, '', 'ts') . (('' . '"') . $target . '>') . $username . '</a>';
  }

  function show_forum_images ($type)
  {
    global $lang;
    $images = array ('offlock' => '<img src="images/offlock.gif" title="' . $lang->tsf_forums['forum_locked'] . '" alt="' . $lang->tsf_forums['forum_locked'] . '" class="inlineimg" />', 'off' => '<img src="images/off.gif" title="' . $lang->tsf_forums['no_new_posts'] . '" alt="' . $lang->tsf_forums['no_new_posts'] . '" class="inlineimg" />', 'on' => '<img src="images/on.gif" title="' . $lang->tsf_forums['new_posts'] . '" alt="' . $lang->tsf_forums['new_posts'] . '" class="inlineimg" />');
    return $images[$type];
  }

  function construct_page_nav ($pagenumber, $perpage, $results, $address, $address2 = '', $usegotopage = true)
  {
    global $lang;
    global $BASEURL;
    global $pagenavsarr;
    $curpage = 0;
    $pagenav = $firstlink = $prevlink = $lastlink = $nextlink = '';
    if ($results <= $perpage)
    {
      $show['pagenav'] = false;
      return '';
    }

    $show['pagenav'] = true;
    $total = ts_nf ($results);
    $totalpages = ceil ($results / $perpage);
    $show['prev'] = $show['next'] = $show['first'] = $show['last'] = false;
    if (1 < $pagenumber)
    {
      $prevpage = $pagenumber - 1;
      $prevnumbers = fetch_start_end_total_array ($prevpage, $perpage, $results);
      $show['prev'] = true;
    }

    if ($pagenumber < $totalpages)
    {
      $nextpage = $pagenumber + 1;
      $nextnumbers = fetch_start_end_total_array ($nextpage, $perpage, $results);
      $show['next'] = true;
    }

    $pagenavpages = '3';
    if (!is_array ($pagenavsarr))
    {
      $pagenavs = '10 50 100 500 1000';
      $pagenavsarr[] = preg_split ('#\\s+#s', $pagenavs, 0 - 1, PREG_SPLIT_NO_EMPTY);
    }

    while ($curpage++ < $totalpages)
    {
      if (($pagenavpages <= abs ($curpage - $pagenumber) AND $pagenavpages != 0))
      {
        if ($curpage == 1)
        {
          $firstnumbers = fetch_start_end_total_array (1, $perpage, $results);
          $show['first'] = true;
        }

        if ($curpage == $totalpages)
        {
          $lastnumbers = fetch_start_end_total_array ($totalpages, $perpage, $results);
          $show['last'] = true;
        }

        if (((in_array (abs ($curpage - $pagenumber), $pagenavsarr) AND $curpage != 1) AND $curpage != $totalpages))
        {
          $pagenumbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $relpage = $curpage - $pagenumber;
          if (0 < $relpage)
          {
            $relpage = '+' . $relpage;
          }

          $pagenav .= '' . '<li><a class="smalltext" href="' . $address . ($curpage != 1 ? '&amp;page=' . $curpage : '') . ('' . $address2 . '" title="') . sprintf ($lang->global['show_results'], $pagenumbers['first'], $pagenumbers['last'], $total) . ('' . '"><!--' . $relpage . '-->' . $curpage . '</a></li>');
          continue;
        }

        continue;
      }
      else
      {
        if ($curpage == $pagenumber)
        {
          $numbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '<li><a name="current" class="current" title="' . sprintf ($lang->global['showing_results'], $numbers['first'], $numbers['last'], $total) . ('' . '">' . $curpage . '</li>');
          continue;
        }
        else
        {
          $pagenumbers = fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '' . '<li><a href="' . $address . ($curpage != 1 ? '&amp;page=' . $curpage : '') . ('' . $address2 . '" title="') . sprintf ($lang->global['show_results'], $pagenumbers['first'], $pagenumbers['last'], $total) . ('' . '">' . $curpage . '</a></li>');
          continue;
        }

        continue;
      }
    }

    $prp = ((isset ($prevpage) AND $prevpage != 1) ? '&amp;page=' . $prevpage : '');
    $pagenav = '
	<script type="text/javascript">
	if (typeof menu_register == \'undefined\')
	{
		document.write(\'<script type=\\\'text/javascript\\\' src=\\\'' . $BASEURL . '/scripts/menu.js?v=' . O_SCRIPT_VERSION . ('' . '\\\'><\\/script>\');
	}
	</script>
	<ul>
	<li>' . $pagenumber . ' - ' . $totalpages . '</li>
	') . ($show['first'] ? '<li><a class="smalltext" href="' . $address . $address2 . '" title="' . $lang->global['first_page'] . ' - ' . sprintf ($lang->global['show_results'], $firstnumbers['first'], $firstnumbers['last'], $total) . '">&laquo; ' . $lang->global['first'] . '</a></li>' : '') . ($show['prev'] ? '<li><a class="smalltext" href="' . $address . $prp . $address2 . '" title="' . $lang->global['prev_page'] . ' - ' . sprintf ($lang->global['show_results'], $prevnumbers['first'], $prevnumbers['last'], $total) . '">&lt;</a></li>' : '') . ('' . '
	' . $pagenav . '
	') . ($show['next'] ? '<li><a class="smalltext" href="' . $address . '&amp;page=' . $nextpage . $address2 . '" title="' . $lang->global['next_page'] . ' - ' . sprintf ($lang->global['show_results'], $nextnumbers['first'], $nextnumbers['last'], $total) . '">&gt;</a></li>' : '') . ($show['last'] ? '<li><a class="smalltext" href="' . $address . '&amp;page=' . $totalpages . $address2 . '" title="' . $lang->global['last_page'] . ' - ' . sprintf ($lang->global['show_results'], $lastnumbers['first'], $lastnumbers['last'], $total) . '">' . $lang->global['last'] . ' <strong>&raquo;</strong></a></li>' : '') . '
	' . ($usegotopage ? '
	<li><a href="#" id="quicknavpage">' . $lang->global['buttongo'] . '</a></li>' : '') . '
	</ul>
	' . ($usegotopage ? '
	<script type="text/javascript">
		menu_register("quicknavpage", true);
	</script>
	<div id="quicknavpage_menu" class="menu_popup" style="display:none;">
		<table border="0" cellspacing="0" cellpadding="5">
			<tbody>
				<tr>
					<td class="thead" nowrap="nowrap">' . $lang->global['gotopage'] . '</td>
				</tr>
				<tr>
					<td class="subheader" title="">
						<form action="' . $address . '" method="get" onsubmit="return TSGoToPage(\'' . $address . '&\')">
							<input id="Page_Number" style="font-size: 11px;" size="4" type="text" />
							<input value="' . $lang->global['buttongo'] . '" type="button" onclick="TSGoToPage(\'' . $address . '&\')" />
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		menu.activate(true);
	</script>
	' : '');
    return $pagenav;
  }

  function build_forum_jump ($fid)
  {
    global $lang;
    global $permissions;
    global $SITENAME;
    ($query = sql_query ('
							SELECT f.fid, f.pid, f.name
							FROM ' . TSF_PREFIX . 'forums f
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 446));
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $deepsubforums[$forum['pid']] = (isset ($deepsubforums[$forum['pid']]) ? $deepsubforums[$forum['pid']] : '') . '
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
						') OR sqlerr (__FILE__, 461));
    $str = '
			<form action="forumdisplay.php" method="get" style="margin-top: 0pt; margin-bottom: 0pt;">
			<span class="smalltext">
			<strong>' . $lang->tsf_forums['jump_text'] . '</strong></span><br />
			<select name="fid">
			<optgroup label="' . $SITENAME . ' Forums">	';
    while ($forum = mysql_fetch_assoc ($query))
    {
      if ($permissions[$forum['pid']]['canview'] != 'no')
      {
        $subforums[$forum['pid']] = (isset ($subforums[$forum['pid']]) ? $subforums[$forum['pid']] : '') . '
			<option value="' . $forum['fid'] . '">-- ' . $forum['name'] . '</option>
			' . ((isset ($deepsubforums) AND isset ($deepsubforums[$forum['fid']])) ? $deepsubforums[$forum['fid']] : '');
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
						') OR sqlerr (__FILE__, 484));
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
			<input type="submit" value="' . $lang->tsf_forums['go_button'] . '" />
			</form>';
    return $str;
  }

  function get_last_post ($tid = 0)
  {
    global $CURUSER;
    global $f_postsperpage;
    if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
    {
      $postperpage = intval ($CURUSER['postsperpage']);
    }
    else
    {
      $postperpage = $f_postsperpage;
    }

    $totalposts = tsrowcount ('pid', TSF_PREFIX . 'posts', 'tid=' . sqlesc ($tid));
    $lastpage = @ceil ($totalposts / $postperpage);
    return ($lastpage ? $lastpage : 0);
  }

  function show_rate_button ()
  {
    global $lang;
    global $tid;
    global $securehash;
    global $usergroups;
    global $ratingimage;
    if ($usergroups['canrate'] != 'yes')
    {
      return '';
    }

    $ratethread = '
	<a href="#" id="ratethread' . $tid . '">' . $lang->tsf_forums['rate1'] . ' &nbsp;' . $ratingimage . '</a>
		<script type="text/javascript">
			menu_register("ratethread' . $tid . '",false);
		</script>
	<div id="ratethread' . $tid . '_menu" class="menu_popup" style="display:none;">
		<form method="post" action="threadrate.php" name="threadrate">
		<input type="hidden" name="threadid" value="' . $tid . '" />
		<input type="hidden" name="posthash" value="' . sha1 ($tid . $securehash . $tid) . '" />
		<input type="hidden" name="page" value="' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0)) . '" />
		<table border="1" cellspacing="0" cellpadding="5" width="200">
			<tr>
				<td align="center" class="thead"><b>' . $lang->tsf_forums['rate2'] . '</b></td>
			</tr>

			<tr>
				<td>';
    $i = $showrateimages = '';
    while ($i < 5)
    {
      ++$i;
      $showrateimages .= '
				<div><img src="images/rating/rating_' . $i . '.gif" class="inlineimg" alt="' . $lang->tsf_forums['rateop' . $i . ''] . '" title="' . $lang->tsf_forums['rateop' . $i . ''] . '" /><input name="vote" id="vote' . $i . '" value="' . $i . '" type="radio" /> ' . $lang->tsf_forums['rateop' . $i . ''] . '</div>
				';
    }

    $ratethread .= $showrateimages . '
				</td>
			</tr>
			<tr>
				<td align="center" class="subheader"><input type="submit" value="' . $lang->tsf_forums['ratenow'] . '" /></td>
			</tr>
		</table>
		</form>
	</div>
	';
    return $ratethread;
  }

  if (((!defined ('TSF_FORUMS_TSSEv56') OR !defined ('IN_SCRIPT_TSSEv56')) OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
