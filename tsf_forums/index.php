<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_semi_stats ()
  {
    global $lang;
    echo '	
	<!-- begin: footer -->	
	<table class="tborder" cellspacing="0" cellpadding="5" border="0" width="100%" align="center">
		<tbody>
			<tr>
				<td class="trow1">					
					<table width="100%" align="center">
						<tbody>
							<tr>
								<td align="center" style="padding: 10px 0px 10px 0px; margin: 0px 0px 0px 0px;">									
									<img src="images/on.gif" alt="' . $lang->tsf_forums['new_posts'] . '" title="' . $lang->tsf_forums['new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;
									<img src="images/off.gif" alt="' . $lang->tsf_forums['no_new_posts'] . '" title="' . $lang->tsf_forums['no_new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['no_new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;
									<img src="images/offlock.gif" alt="' . $lang->tsf_forums['forum_locked'] . '" title="' . $lang->tsf_forums['forum_locked'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['forum_locked'] . '</span>
									 <span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="misc.php?action=markread">' . $lang->tsf_forums['markallread'] . '</a>] </span>									
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- end: footer -->';
  }

  function tsf_forum_stats ()
  {
    global $cache;
    global $lang;
    global $rootpath;
    global $BASEURL;
    global $usergroups;
    global $CURUSER;
    global $pic_base_url;
    global $moderator;
    global $cachesystem;
    include_once INC_PATH . '/ts_cache.php';
    update_cache ('indexstats');
    include_once TSDIR . '/' . $cache . '/indexstats.php';
    include_once INC_PATH . '/functions_icons.php';
    $dt = TIMENOW - TS_TIMEOUT;
    ($res = sql_query ('SELECT u.id, u.username, u.usergroup, u.options, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle, g.title FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.last_forum_active >= ' . $dt . ' ORDER BY u.username') OR sqlerr (__FILE__, 344));
    $webtotal = 0;
    $activeusers = '';
    while ($arr = mysql_fetch_array ($res))
    {
      if (((preg_match ('#B1#is', $arr['options']) AND !$moderator) AND $arr['id'] != $CURUSER['id']))
      {
        continue;
      }

      if ($activeusers)
      {
        $activeusers .= ', ';
      }

      if ($CURUSER)
      {
        $activeusers .= '<a href="' . tsf_seo_clean_text ($arr['username'], 'u', $arr['id'], '', 'ts') . '"><b>' . get_user_color ($arr['username'], $arr['namestyle']) . '</b></a>';
      }
      else
      {
        $activeusers .= '<b>' . get_user_color ($arr['username'], $arr['namestyle']) . '</b>';
      }

      if (preg_match ('#B1#is', $arr['options']))
      {
        $activeusers .= '+';
      }

      $activeusers .= get_user_icons ($arr);
      ++$webtotal;
    }

    if (!$activeusers)
    {
      $activeusers = $lang->global['noactiveusersonline'];
    }

    define ('SKIP_CACHE_MESSAGE', true);
    require_once INC_PATH . '/functions_cache2.php';
    $no_cache = false;
    if (!$showbday = cache_check2 ('tsf_forums_bday'))
    {
      $no_cache = true;
    }

    if ($no_cache)
    {
      $todaybday = date ('j-n');
      $query = sql_query ('' . 'SELECT u.id,u.username,u.birthday,g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.birthday REGEXP \'^' . $todaybday . '-([1-9][0-9][0-9][0-9])\' AND u.enabled = \'yes\' AND u.status=\'confirmed\' AND u.usergroup != ' . UC_BANNED);
      $bdaycount = mysql_num_rows ($query);
      if (0 < $bdaycount)
      {
        $showbday = '
				<tr>
					<td class="tcat" colspan="2">
						<b>' . $lang->tsf_forums['tbdays'] . '</b>
					</td>
				</tr>
				<tr>
					<td class="trow1" width="1%"><img src="images/bday.gif" alt="" title="" /></td>
					<td class="trow1">' . sprintf ($lang->tsf_forums['tbdayss'], $bdaycount) . '<br />
			';
        while ($bday = mysql_fetch_assoc ($query))
        {
          $userbday = explode ('-', $bday['birthday']);
          $yearsold = date ('Y') - $userbday[2];
          $showbday .= ' <a href="' . tsf_seo_clean_text ($bday['username'], 'u', $bday['id'], '', 'ts') . '">' . get_user_color ($bday['username'], $bday['namestyle']) . '</a> (<b>' . $yearsold . '</b>) ';
        }
      }

      cache_save2 ('tsf_forums_bday', $showbday, '</td></tr>');
    }

    if (($cachesystem != 'yes' OR $no_cache))
    {
      $showbday .= '</td></tr>';
    }

    echo '
	
	<!-- start: tsf forum stats -->
	<table class="tborder" cellspacing="0" cellpadding="5" border="0" width="100%" align="center">
		<thead>
			<tr>
				<td class="thead" colspan="2">
					' . ts_collapse ('forumstats') . '
					<strong>' . $lang->tsf_forums['stats'] . '</strong>
				</td>
			</tr>
		</thead>
		' . ts_collapse ('forumstats', 2) . '
			<tr>
				<td class="tcat" colspan="2">
					' . ($moderator ? '<a href="' . $BASEURL . '/admin/index.php?act=whoisonline">' . $lang->tsf_forums['whosonline'] . '</a>' : $lang->tsf_forums['whosonline']) . ' 
				</td>
			</tr>
			<tr>
				<td class="trow1" width="1%"><img src="images/whoisonline.gif" alt="" title="" /></td>
				<td class="trow1">
					' . @sprintf ($lang->tsf_forums['activeusers'], @ts_nf ($webtotal), @floor (TS_TIMEOUT / 60)) . '
					' . $activeusers . '
				</td>
			</tr>
			' . $showbday . '
			<td class="tcat" colspan="2">
			<div style="float: right"><a href="misc.php?action=markread">' . $lang->tsf_forums['markallread'] . '</a></div>
				' . $lang->tsf_forums['stats'] . '
			</td>
			<tr>
				<td class="trow1" width="1%"><img src="images/stats.gif" alt="" title="" /></td>
				<td class="trow1">
					<span class="smalltext">
						' . @sprintf ($lang->tsf_forums['stats_info'], @ts_nf ($indexstats['totalposts']), @ts_nf ($indexstats['totalthreads']), @ts_nf ($indexstats['registered']), $indexstats['latestuser']) . '
					</span>
				</td>
			</tr>
		</tbody>
	</table>
	<br />
	<!-- end: tsf forum stats -->
	
	<!-- begin: footer -->
	<table class="subheader" cellspacing="0" cellpadding="5" border="0" width="100%" align="center">
		<tbody>
			<tr>
				<td align="center" style="padding: 10px 0px 10px 0px; margin: 0px 0px 0px 0px;">									
					<img src="images/on.gif" alt="' . $lang->tsf_forums['new_posts'] . '" title="' . $lang->tsf_forums['new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="images/off.gif" alt="' . $lang->tsf_forums['no_new_posts'] . '" title="' . $lang->tsf_forums['no_new_posts'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['no_new_posts'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="images/offlock.gif" alt="' . $lang->tsf_forums['forum_locked'] . '" title="' . $lang->tsf_forums['forum_locked'] . '" class="inlineimg"> <span class="smalltext">' . $lang->tsf_forums['forum_locked'] . '</span>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- end: footer -->';
  }

  define ('TSF_FORUMS_TSSEv56', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ((isset ($_GET['fid']) AND is_valid_id ($_GET['fid'])))
  {
    $fid = @intval ($_GET['fid']);
    if ($permissions[$fid]['canview'] == 'no')
    {
      print_no_permission (true);
      exit ();
    }

    $oneforum = $addnavbar = true;
  }

  stdhead ('' . $SITENAME . ' TSF FORUMS : ' . TSF_VERSION, true, 'collapse');
  if (isset ($warningmessage))
  {
    echo $warningmessage;
  }

  ($query = sql_query ('
							SELECT f.fid, f.pid, f.name, f.posts as sposts, f.threads as sthreads
							FROM ' . TSF_PREFIX . 'forums f							
							WHERE f.type = \'s\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 54));
  $deepsubforums = array ();
  while ($subforum = mysql_fetch_assoc ($query))
  {
    $deepposts[$subforum['pid']] = (isset ($deepposts[$subforum['pid']]) ? $deepposts[$subforum['pid']] + $subforum['sposts'] : $subforum['sposts']);
    $deepthreads[$subforum['pid']] = (isset ($deepthreads[$subforum['pid']]) ? $deepthreads[$subforum['pid']] + $subforum['sthreads'] : $subforum['sthreads']);
    $deepsubforums[$subforum['pid']] = (isset ($deepsubforums[$subforum['pid']]) ? $deepsubforums[$subforum['pid']] : '') . '<img src="' . $BASEURL . '/tsf_forums/images/subforums.gif" alt="' . $subforum['name'] . '" title="' . $subforum['name'] . '" /> <a href="' . tsf_seo_clean_text ($subforum['name'], 'fd', $subforum['fid']) . '">' . $subforum['name'] . '</a> <font size="1">(' . ts_nf ($subforum['sthreads']) . '/' . ts_nf ($subforum['sposts']) . ')</font>~~~';
  }

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
							WHERE f.type = \'f\' ORDER by f.pid, f.disporder
						') OR sqlerr (__FILE__, 81));
  require_once INC_PATH . '/functions_cookies.php';
  while ($forum = mysql_fetch_assoc ($query))
  {
    if ((isset ($permissions[$forum['fid']]['canview']) AND $permissions[$forum['fid']]['canview'] == 'no'))
    {
      continue;
    }

    $moderatorslist = '';
    if (isset ($imodcache['' . $forum['fid']]))
    {
      foreach ($imodcache['' . $forum['fid']] as $fmoderator)
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

    $lastpost_data = $_clean_subject = '';
    $hideinfo = false;
    $posts = ts_nf ($forum['posts'] + (isset ($deepposts[$forum['fid']]) ? $deepposts[$forum['fid']] : 0));
    $threads = ts_nf ($forum['threads'] + (isset ($deepthreads[$forum['fid']]) ? $deepthreads[$forum['fid']] : 0));
    if (($forum['password'] != '' AND (((!isset ($_COOKIE['forumpass_' . $forum['fid']]) OR $_COOKIE['forumpass_' . $forum['fid']] != md5 ($CURUSER['id'] . $forum['password'] . $securehash)) OR empty ($_COOKIE['forumpass_' . $forum['fid']])) OR strlen ($_COOKIE['forumpass_' . $forum['fid']]) != 32)))
    {
      $hideinfo = true;
    }

    $lastpost_data = array ('lastpost' => $forum['lastpost'], 'lastpostsubject' => $forum['lastpostsubject'], 'lastposter' => get_user_color (htmlspecialchars_uni ($forum['realrealusername']), $forum['namestyle']), 'lastposttid' => $forum['lastposttid'], 'lastposteruid' => $forum['reallastposteruserid']);
    if ($hideinfo == true)
    {
      unset ($lastpost_data);
    }

    if ((((!isset ($lastpost_data['lastpost']) OR $lastpost_data['lastpost'] == 0) OR $lastpost_data['lastposter'] == '') AND $hideinfo != true))
    {
      $lastpost = '<span style="text-align: center;">' . $lang->tsf_forums['lastpost_never'] . '</span>';
    }
    else
    {
      if ($hideinfo != true)
      {
        $lastpost_date = my_datee ($dateformat, $forum['lastpost']);
        $lastpost_time = my_datee ($timeformat, $forum['lastpost']);
        $lastpost_profilelink = build_profile_link ($lastpost_data['lastposter'], $lastpost_data['lastposteruid']);
        $lastposttid = $lastpost_data['lastposttid'];
        $lastpost_subject = $full_lastpost_subject = $lastpost_data['lastpostsubject'];
        if (30 < @strlen ($lastpost_subject))
        {
          $lastpost_subject = my_substrr ($lastpost_subject, 0, 30) . '..';
        }

        $full_lastpost_subject = htmlspecialchars_uni (ts_remove_badwords ($full_lastpost_subject));
        $_clean_subject = htmlspecialchars_uni (ts_remove_badwords ($lastpost_subject));
        $lastpost = '
		<div class="smalltext" align="left">
			<div>
				<span style="white-space: nowrap;">		
					<a href="' . tsf_seo_clean_text ($_clean_subject, 't', $lastposttid, '&action=lastpost') . '" title="' . $full_lastpost_subject . '" title="' . $full_lastpost_subject . '"><strong>' . $_clean_subject . '</strong></a>
				</span>
			</div>
			<div style="white-space: nowrap;">
				' . $lang->tsf_forums['by'] . ' ' . $lastpost_profilelink . '
			</div>
			<div style="white-space: nowrap;" align="right">
				' . $lastpost_date . ' <span class="time">' . $lastpost_time . '</span>
				<a href="' . tsf_seo_clean_text ($_clean_subject, 't', $lastposttid, '&action=lastpost') . '" alt="' . $full_lastpost_subject . '" title="' . $full_lastpost_subject . '"><img src="images/lastpost.gif" class="inlineimg" border="0" alt="' . $lang->tsf_forums['gotolastpost'] . '" title="' . $lang->tsf_forums['gotolastpost'] . '"></a>
			</div>
		</div>';
      }
    }

    $forumread = ts_get_array_cookie ('forumread', $forum['fid']);
    if ((((isset ($lastpost_data['lastpost']) AND $CURUSER['last_forum_visit'] < $lastpost_data['lastpost']) AND $forumread < $lastpost_data['lastpost']) AND $lastpost_data['lastpost'] != 0))
    {
      $folder = 'on';
      $altonoff = $lang->tsf_forums['new_posts'];
    }
    else
    {
      $folder = 'off';
      $altonoff = $lang->tsf_forums['no_new_posts'];
    }

    $Showsubforums = '';
    if (isset ($deepsubforums[$forum['fid']]))
    {
      $DSFCount = 0;
      $ManageSFArray = explode ('~~~', $deepsubforums[$forum['fid']]);
      $Showsubforums .= '<fieldset><legend>' . $lang->tsf_forums['sforums'] . '</legend><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr>';
      foreach ($ManageSFArray as $DSF)
      {
        if ($DSFCount % $f_sfpertr == 0)
        {
          $Showsubforums .= '</tr><tr>';
        }

        $Showsubforums .= '<td class="none">' . $DSF . '</td>';
        ++$DSFCount;
      }

      $Showsubforums .= '</tr></table></fieldset>';
    }

    $subforums[$forum['pid']] = (isset ($subforums[$forum['pid']]) ? $subforums[$forum['pid']] : '') . '
	
		<!-- start: forums#' . $forum['fid'] . ' for category#' . $forum['pid'] . ' -->
			<tr>
				<td class="trow1" align="center" valign="top">
					<img src="images/' . $folder . '.gif" alt="' . $altonoff . '" title="' . $altonoff . '" />
				</td>
				<td class="trow1" align="center" valign="top">
					' . ($forum['image'] ? '<img src="images/forumicons/' . $forum['image'] . '" alt="" title="" />' : '') . '
				</td>
				<td class="trow1" valign="top">
					<strong><a href="' . tsf_seo_clean_text ($forum['name'], 'fd', $forum['fid']) . '">' . $forum['name'] . '</a></strong>
					<div class="smalltext">' . $forum['description'] . '</div>
					' . $Showsubforums . '
					' . ($moderatorslist ? '<div class="smalltext">' . sprintf ($lang->tsf_forums['modlist'], $moderatorslist) . '</div>' : '') . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="left" valign="top">
					' . ($hideinfo === false ? $lastpost : $lang->tsf_forums['hidden']) . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="center" valign="top">
					' . $threads . '
				</td>
				<td class="trow1" style="white-space: nowrap;" align="center" valign="top">
					' . $posts . '
				</td>				
			</tr>			
		<!-- end: forums#' . $forum['fid'] . ' for category#' . $forum['pid'] . ' -->';
  }

  if ((isset ($oneforum) AND $oneforum === true))
  {
    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forums WHERE type = \'c\' AND fid = ' . sqlesc ($fid) . ' ORDER by pid, disporder') OR sqlerr (__FILE__, 235));
    if (mysql_num_rows ($query) == 0)
    {
      stdmsg ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      stdfoot ();
      exit ();
    }
  }
  else
  {
    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forums WHERE type = \'c\' ORDER by pid, disporder') OR sqlerr (__FILE__, 245));
    if (mysql_num_rows ($query) == 0)
    {
      stdmsg ($lang->global['error'], $lang->tsf_forums['noforumsyet']);
      stdfoot ();
      exit ();
    }
  }

  $str = '';
  while ($category = mysql_fetch_assoc ($query))
  {
    if ((isset ($addnavbar) AND $addnavbar == true))
    {
      add_breadcrumb ($category['name']);
      $addnavbar = false;
    }

    if ($permissions[$category['fid']]['canview'] == 'no')
    {
      continue;
    }

    if (isset ($subforums[$category['fid']]))
    {
      $str .= '
		<!-- start: category#' . $category['fid'] . ' -->
			<table class="tborder" cellspacing="0" cellpadding="5" border="0" width="100%" align="center">
				<thead>
					<tr>
						<td class="thead" colspan="6">
							' . ts_collapse ('forum#' . $category['fid']) . '
							<strong><a href="' . tsf_seo_clean_text ($category['name'], 'f', $category['fid']) . '">' . $category['name'] . '</a></strong>
						</td>
					</tr>
				</thead>
				' . ts_collapse ('forum#' . $category['fid'], 2) . '
					<tr>
						<td class="tcat" width="32">&nbsp;</td>
						<td class="tcat" width="32">&nbsp;</td>
						<td class="tcat"><strong>' . $lang->tsf_forums['forum'] . '</strong></td>
						<td class="tcat" align="center" width="200"><strong>' . $lang->tsf_forums['lastpost'] . '</strong></td>
						<td class="tcat" style="white-space: nowrap;" align="center" width="50"><strong>' . $lang->tsf_forums['threads'] . '</strong></td>
						<td class="tcat" style="white-space: nowrap;" align="center" width="50"><strong>' . $lang->tsf_forums['posts'] . '</strong></td>						
					</tr>
					' . $subforums[$category['fid']] . '
					<tr><td class="tcat" style="margin: 0px; padding: 0px; line-height: 0px;" colspan="6"><img src="images/clear.gif" alt="" width="1" height="8"></td></tr>
				</tbody>
			</table>			
			<br />
		<!-- end: category#' . $category['fid'] . ' -->		
		';
      continue;
    }
  }

  build_breadcrumb ();
  echo $str;
  unset ($str);
  if (($f_showstats == 'yes' OR ($f_showstats == 'staffonly' AND $moderator)))
  {
    tsf_forum_stats ();
  }
  else
  {
    show_semi_stats ();
  }

  stdfoot ();
?>
