<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function perform_search_mysql_ft ($search)
  {
    global $lang;
    global $CURUSER;
    global $usergroups;
    global $securehash;
    global $SITENAME;
    $keywords = clean_keywords_ft ($search['keywords']);
    if ((!$keywords AND !$search['author']))
    {
      add_breadcrumb ($lang->tsf_forums['search_results']);
      stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
      build_breadcrumb ();
      stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror2'], false);
      stdfoot ();
      exit ();
    }

    $query = sql_query ('SHOW VARIABLES LIKE \'ft_min_word_len\';');
    $array = mysql_fetch_assoc ($query);
    $min_length = $array['Value'];
    if (is_numeric ($min_length))
    {
      $minsearchword = $min_length;
    }
    else
    {
      $minsearchword = 3;
    }

    if ($keywords)
    {
      $keywords_exp = explode ('"', $keywords);
      $inquote = false;
      foreach ($keywords_exp as $phrase)
      {
        if (!$inquote)
        {
          $split_words = preg_split ('#\\s{1,}#', $phrase, 0 - 1);
          foreach ($split_words as $word)
          {
            $word = str_replace (array ('+', '-', '*'), '', $word);
            if (!$word)
            {
              continue;
            }

            if (strlen ($word) < $minsearchword)
            {
              $lang->error_minsearchlength = sprintf ($lang->tsf_forums['searcherror3'], $minsearchword);
              add_breadcrumb ($lang->tsf_forums['search_results']);
              stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
              build_breadcrumb ();
              stdmsg ($lang->global['error'], $lang->error_minsearchlength, false);
              stdfoot ();
              exit ();
              continue;
            }
          }
        }
        else
        {
          $phrase = str_replace (array ('+', '-', '*'), '', $phrase);
          if (strlen ($phrase) < $minsearchword)
          {
            $lang->error_minsearchlength = sprintf ($lang->tsf_forums['searcherror3'], $minsearchword);
            add_breadcrumb ($lang->tsf_forums['search_results']);
            stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
            build_breadcrumb ();
            stdmsg ($lang->global['error'], $lang->error_minsearchlength, false);
            stdfoot ();
            exit ();
          }
        }

        $inquote = !$inquote;
      }

      $message_lookin = 'AND MATCH(message) AGAINST(\'' . mysql_real_escape_string ($keywords) . '\' IN BOOLEAN MODE)';
      $subject_lookin = 'AND MATCH(subject) AGAINST(\'' . mysql_real_escape_string ($keywords) . '\' IN BOOLEAN MODE)';
    }

    $post_usersql = $thread_usersql = '';
    if ($search['author'])
    {
      $userids = array ();
      if ($search['matchusername'])
      {
        ($query = sql_query ('SELECT id FROM users WHERE username=\'' . mysql_real_escape_string ($search['author']) . '\'') OR sqlerr (__FILE__, 1135));
      }
      else
      {
        $search['author'] = strtolower ($search['author']);
        ($query = sql_query ('SELECT id FROM users WHERE LOWER(username) LIKE \'%' . mysql_real_escape_string ($search['author']) . '%\'') OR sqlerr (__FILE__, 1140));
      }

      while ($user = mysql_fetch_assoc ($query))
      {
        $userids[] = $user['id'];
      }

      if (count ($userids) < 1)
      {
        add_breadcrumb ($lang->tsf_forums['search_results']);
        stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
        build_breadcrumb ();
        stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
        stdfoot ();
        exit ();
      }
      else
      {
        $userids = implode (',', $userids);
        $post_usersql = ' AND p.uid IN (' . $userids . ')';
        $thread_usersql = ' AND t.uid IN (' . $userids . ')';
      }
    }

    $forumin = '';
    $fidlist = array ();
    if (($search['forums'] != 'all' AND $search['forums'] != 'skip'))
    {
      if (!is_array ($search['forums']))
      {
        $search['forums'] = array (intval ($search['forums']));
      }

      foreach ($search['forums'] as $forum)
      {
        $forum = intval ($forum);
        if (!$searchin[$forum])
        {
          ($query = sql_query ('SELECT f.fid FROM ' . TSF_PREFIX . 'forums f LEFT JOIN ' . TSF_PREFIX . 'forumpermissions p ON (f.fid=p.fid AND p.gid=\'' . $CURUSER['usergroup'] . ('' . '\') WHERE INSTR(CONCAT(\',\',parentlist,\',\'),\',' . $forum . ',\') > 0 AND (ISNULL(p.fid) OR p.cansearch=\'yes\')')) OR sqlerr (__FILE__, 1176));
          if (mysql_num_rows ($query) == 1)
          {
            $forumin .= '' . ' AND t.fid=\'' . $forum . '\' ';
            $searchin[$fid] = 1;
            continue;
          }
          else
          {
            while ($sforum = mysql_fetch_assoc ($query))
            {
              $fidlist[] = $sforum['fid'];
            }

            if (1 < count ($fidlist))
            {
              $forumin = ' AND t.fid IN (' . implode (',', $fidlist) . ')';
              continue;
            }

            continue;
          }

          continue;
        }
      }
    }

    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE (fp.canview = \'no\' OR fp.cansearch = \'no\') AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 1197));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if ($_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash))
        {
          $uf2[] = 0 + $notin['fid'];
          continue;
        }
      }

      if (0 < count ($uf2))
      {
        if ($unsearchforums)
        {
          $unsearchforums .= ',' . implode (',', $uf2);
        }
        else
        {
          $unsearchforums = implode (',', $uf2);
        }
      }
    }

    if ($unsearchforums)
    {
      $permsql = '' . ' AND t.fid NOT IN (' . $unsearchforums . ')';
    }

    $threadsql = '';
    if (is_valid_id ($search['threadid']))
    {
      $threadsql = ' AND t.tid = \'' . $search['threadid'] . '\'';
    }

    $threads = array ();
    $posts = array ();
    $firstposts = array ();
    if ($search['postthread'] == 1)
    {
      $searchtype = 'titles';
      ($query = sql_query ('
			SELECT t.tid, t.firstpost
			FROM ' . TSF_PREFIX . ('' . 'threads t
			WHERE 1=1 ' . $forumin . ' ' . $thread_usersql . ' ' . $threadsql . ' ' . $permsql . ' ' . $subject_lookin . '
		')) OR sqlerr (__FILE__, 1249));
      while ($thread = mysql_fetch_assoc ($query))
      {
        $threads[$thread['tid']] = $thread['tid'];
        if ($thread['firstpost'])
        {
          $posts[$thread['tid']] = $thread['firstpost'];
          continue;
        }
      }

      ($query = sql_query ('
			SELECT p.pid, p.tid
			FROM ' . TSF_PREFIX . 'posts p
			LEFT JOIN ' . TSF_PREFIX . ('' . 'threads t ON (t.tid=p.tid)
			WHERE 1=1 ' . $forumin . ' ' . $post_usersql . ' ' . $threadsql . ' ' . $permsql . ' ' . $message_lookin . '
		')) OR sqlerr (__FILE__, 1263));
      while ($post = mysql_fetch_assoc ($query))
      {
        $posts[$post['pid']] = $post['pid'];
        $threads[$post['tid']] = $post['tid'];
      }

      if ((count ($posts) < 1 AND count ($threads) < 1))
      {
        add_breadcrumb ($lang->tsf_forums['search_results']);
        stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
        build_breadcrumb ();
        stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
        stdfoot ();
        exit ();
      }

      $threads = implode (',', $threads);
      $posts = implode (',', $posts);
    }
    else
    {
      $searchtype = 'posts';
      ($query = sql_query ('
			SELECT t.tid, t.firstpost
			FROM ' . TSF_PREFIX . ('' . 'threads t
			WHERE 1=1 ' . $forumin . ' ' . $thread_usersql . ' ' . $permsql . ' ' . $subject_lookin . '
		')) OR sqlerr (__FILE__, 1289));
      while ($thread = mysql_fetch_assoc ($query))
      {
        $threads[$thread['tid']] = $thread['tid'];
        if ($thread['firstpost'])
        {
          $firstposts[$thread['tid']] = $thread['firstpost'];
          continue;
        }
      }

      if (count ($threads) < 1)
      {
        add_breadcrumb ($lang->tsf_forums['search_results']);
        stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
        build_breadcrumb ();
        stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
        stdfoot ();
        exit ();
      }

      $threads = implode (',', $threads);
      $firstposts = implode (',', $firstposts);
      if ($firstposts)
      {
        ($query = sql_query ('
				SELECT p.pid
				FROM ' . TSF_PREFIX . ('' . 'posts p
				WHERE p.pid IN (' . $firstposts . ')
			')) OR sqlerr (__FILE__, 1316));
        while ($post = mysql_fetch_assoc ($query))
        {
          $posts[$post['pid']] = $post['pid'];
        }

        $posts = implode (',', $posts);
      }
    }

    return array ('searchtype' => $searchtype, 'threads' => $threads, 'posts' => $posts, 'querycache' => '');
  }

  function clean_keywords_ft ($keywords)
  {
    if (!$keywords)
    {
      return false;
    }

    $keywords = strtolower ($keywords);
    $keywords = str_replace ('%', '\\%', $keywords);
    $keywords = preg_replace ('#\\*{2,}#s', '*', $keywords);
    $keywords = preg_replace ('#([\\[\\]\\|\\.\\,:])#s', ' ', $keywords);
    $keywords = preg_replace ('#\\s+#s', ' ', $keywords);
    if (strpos ($keywords, '"') !== false)
    {
      $inquote = false;
      $keywords = explode ('"', $keywords);
      foreach ($keywords as $phrase)
      {
        if ($phrase != '')
        {
          if ($inquote)
          {
            $words[] = '"' . trim ($phrase) . '"';
          }
          else
          {
            $split_words = preg_split ('#\\s{1,}#', $phrase, 0 - 1);
            if (!is_array ($split_words))
            {
              continue;
            }

            foreach ($split_words as $word)
            {
              if (!$word)
              {
                continue;
              }

              $words[] = trim ($word);
            }
          }
        }

        $inquote = !$inquote;
      }
    }
    else
    {
      $split_words = preg_split ('#\\s{1,}#', $keywords, 0 - 1);
      if (!is_array ($split_words))
      {
        continue;
      }

      foreach ($split_words as $word)
      {
        if (!$word)
        {
          continue;
        }

        $words[] = trim ($word);
      }
    }

    $keywords = '';
    if (0 < count ($words))
    {
      foreach ($words as $word)
      {
        if ($word == 'or')
        {
          $boolean = '';
          continue;
        }
        else
        {
          if ($word == 'and')
          {
            $boolean = '+';
            continue;
          }
          else
          {
            if ($word == 'not')
            {
              $boolean = '-';
              continue;
            }
            else
            {
              $keywords .= ' ' . $boolean . $word;
              $boolean = '';
              continue;
            }

            continue;
          }

          continue;
        }
      }
    }

    $keywords = '+' . trim ($keywords);
    return $keywords;
  }

  define ('TSF_FORUMS_TSSEv56', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
  {
    $postperpage = intval ($CURUSER['postsperpage']);
  }
  else
  {
    $postperpage = $f_postsperpage;
  }

  $timecut = TIMENOW - 60 * 60 * 24 * 1;
  sql_query ('DELETE FROM ' . TSF_PREFIX . ('' . 'searchlog WHERE dateline<=\'' . $timecut . '\''));
  add_breadcrumb ($lang->tsf_forums['search'], $_SERVER['SCRIPT_NAME']);
  if ((!$action OR $action == 'searchthread'))
  {
    $Inthread = ($action == 'searchthread' ? true : false);
    if ($Inthread)
    {
      $threadid = (isset ($_POST['threadid']) ? intval ($_POST['threadid']) : (isset ($_GET['threadid']) ? intval ($_GET['threadid']) : 0));
      if (!is_valid_id ($threadid))
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
        exit ();
      }

      ($query = sql_query ('SELECT 
			t.tid, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($threadid) . ' LIMIT 1') OR sqlerr (__FILE__, 60));
      if (mysql_num_rows ($query) == 0)
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
        exit ();
      }

      $thread = mysql_fetch_assoc ($query);
      $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
      if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no')))
      {
        print_no_permission (true);
        exit ();
      }
    }

    stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION . ' :: ' . $lang->tsf_forums['search'], true, 'supernote');
    $sfid = (isset ($_GET['sfid']) ? intval ($_GET['sfid']) : 'all');
    if (isset ($warningmessage))
    {
      echo $warningmessage;
    }

    build_breadcrumb ();
    if (!$Inthread)
    {
      ($query = sql_query ('
								SELECT f.password, f.fid, f.pid, f.name
								FROM ' . TSF_PREFIX . 'forums f
								WHERE f.type = \'s\' ORDER by f.pid, f.disporder
							') OR sqlerr (__FILE__, 91));
      while ($forum = mysql_fetch_assoc ($query))
      {
        if (($forum['password'] != '' AND $_COOKIE['forumpass_' . $forum['fid']] != md5 ($CURUSER['id'] . $forum['password'] . $securehash)))
        {
          continue;
        }

        if ($permissions[$forum['pid']]['canview'] != 'no')
        {
          $deepsubforums[$forum['pid']] = (isset ($deepsubforums[$forum['pid']]) ? $deepsubforums[$forum['pid']] : '') . '
				<option value="' . $forum['fid'] . '"' . ($sfid == $forum['fid'] ? ' selected = "selected"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $forum['name'] . '</option>';
          continue;
        }
        else
        {
          continue;
        }
      }

      ($query = sql_query ('
								SELECT f.password, f.fid, f.pid, f.name
								FROM ' . TSF_PREFIX . 'forums f
								WHERE f.type = \'f\' ORDER by f.pid, f.disporder
							') OR sqlerr (__FILE__, 108));
      $str = '
			<select name="forums" size="13" multiple="multiple" style="width: 350px;">
			<optgroup label="' . $SITENAME . ' Forums">
				<option value="all"' . ($sfid == 'all' ? ' selected = "selected"' : '') . '>' . $lang->tsf_forums['select1'] . '</option>';
      while ($forum = mysql_fetch_assoc ($query))
      {
        if (($forum['password'] != '' AND $_COOKIE['forumpass_' . $forum['fid']] != md5 ($CURUSER['id'] . $forum['password'] . $securehash)))
        {
          continue;
        }

        if ($permissions[$forum['pid']]['canview'] != 'no')
        {
          $subforums[$forum['pid']] = (isset ($subforums[$forum['pid']]) ? $subforums[$forum['pid']] : '') . '
					<option value="' . $forum['fid'] . '"' . ($sfid == $forum['fid'] ? ' selected = "selected"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $forum['name'] . '</option>' . (isset ($deepsubforums[$forum['fid']]) ? $deepsubforums[$forum['fid']] : '');
          continue;
        }
        else
        {
          continue;
        }
      }

      ($query = sql_query ('
								SELECT f.password, f.fid, f.pid, f.name
								FROM ' . TSF_PREFIX . 'forums f							
								WHERE f.type = \'c\' ORDER by f.pid, f.disporder
							') OR sqlerr (__FILE__, 129));
      while ($category = mysql_fetch_assoc ($query))
      {
        if (($category['password'] != '' AND $_COOKIE['forumpass_' . $category['fid']] != md5 ($CURUSER['id'] . $category['password'] . $securehash)))
        {
          continue;
        }

        if (($permissions[$category['fid']]['canview'] != 'no' AND $subforums[$category['fid']]))
        {
          $str .= '
					<option value="' . $category['fid'] . '">' . $category['name'] . '</option>' . $subforums[$category['fid']] . '';
          continue;
        }
        else
        {
          continue;
        }
      }

      $str .= '
					</optgroup>
				</select> ';
    }

    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">	
	' . ($Inthread ? '<input type="hidden" name="action" value="searchinthread"><input type="hidden" name="threadid" value="' . $threadid . '">' : '<input type="hidden" name="action" value="do_search">') . '
	<table border="0" cellspacing="0" cellpadding="4" class="tborder" align="center">

		<tr>
			<td colspan="2" class="thead"><strong>' . $lang->tsf_forums['title'] . '</strong></td>
		</tr>		
		<tr>

			<td class="trow1" style="border: 0;">
				<fieldset class="fieldset" style="padding: 5px 10px 10px 5px;">
					<legend>' . $lang->tsf_forums['title1'] . '</legend>
					<div>
						' . $lang->tsf_forums['option1'] . '
					</div>
					<div>
						<input type="text" name="keywords" value="" id="specialboxn">
					</div>
					' . (!$Inthread ? '
					<div style="padding: 5px 0px 0px 0px;">
						<select name="postthread">
							<option value="1" selected="selected">' . $lang->tsf_forums['option2'] . '</option>
							<option value="0" >' . $lang->tsf_forums['option3'] . '</option>
						</select>
					</div>' : '') . '
				</fieldset>
			</td>
			' . (!$Inthread ? '
			<td class="trow1" style="border: 0;" rowspan="2">
				<fieldset class="fieldset" style="padding: 5px 10px 10px 5px;">
					<legend>' . $lang->tsf_forums['option8'] . '</legend>
					<div>
						' . $str . '
					</div>
				</fieldset>
			</td>' : '') . '
		</tr>
		<tr>

			<td class="trow1" style="border: 0;">
				<fieldset class="fieldset" style="padding: 5px 10px 10px 5px;">
					<legend>' . $lang->tsf_forums['title2'] . '</legend>
					<div>
						' . $lang->tsf_forums['option4'] . '
					</div>
					<div>
						<input type="text" name="author" value="" id="specialboxn">
					</div>
					<div style="padding: 5px 0px 0px 0px;">						
						<input name="matchusername" value="1" checked="checked" type="checkbox" class="inlineimg">' . $lang->tsf_forums['option7'] . '
					</div>
				</fieldset>
			</td>
		</tr>
		<tr><td colspan="2" align="center"> <input type="submit" name="submit" value="' . $lang->tsf_forums['button_1'] . '"> <input type="reset" name="reset" value="' . $lang->tsf_forums['button_2'] . '"></td></tr>
	</table>';
    stdfoot ();
    exit ();
  }

  if ($action == 'finduserthreads')
  {
    if ((empty ($_GET['id']) OR !is_valid_id ($_GET['id'])))
    {
      print_no_permission (true);
    }

    $where_sql = 't.uid=\'' . intval ($_GET['id']) . '\'';
    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE (fp.canview = \'no\' OR fp.cansearch = \'no\') AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 216));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if ($_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash))
        {
          $uf2[] = 0 + $notin['fid'];
          continue;
        }
      }

      if (0 < count ($uf2))
      {
        if ($unsearchforums)
        {
          $unsearchforums .= ',' . implode (',', $uf2);
        }
        else
        {
          $unsearchforums = implode (',', $uf2);
        }
      }
    }

    if ($unsearchforums)
    {
      $where_sql .= '' . ' AND t.fid NOT IN (' . $unsearchforums . ')';
    }

    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => '', 'posts' => '', 'searchtype' => 'titles', 'resulttype' => 'threads', 'querycache' => $where_sql);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 263));
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'searchinthread')
  {
    $threadid = (isset ($_POST['threadid']) ? intval ($_POST['threadid']) : (isset ($_GET['threadid']) ? intval ($_GET['threadid']) : 0));
    if (!is_valid_id ($threadid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    ($query = sql_query ('SELECT 
		t.tid, f.type, f.fid as currentforumid, ff.fid as deepforumid 
		FROM ' . TSF_PREFIX . 'threads t 			
		LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
		LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
		WHERE t.tid = ' . sqlesc ($threadid) . ' LIMIT 1') OR sqlerr (__FILE__, 282));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no')))
    {
      print_no_permission (true);
      exit ();
    }

    if (!empty ($_SESSION['last_search']))
    {
      flood_check ($lang->tsf_forums['search'], $_SESSION['last_search']);
    }

    $resulttype = 'posts';
    $search_data = array ('keywords' => $_POST['keywords'], 'author' => $_POST['author'], 'matchusername' => $_POST['matchusername'], 'postthread' => 1, 'forums' => 'skip', 'threadid' => $threadid);
    $search_results = perform_search_mysql_ft ($search_data);
    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => $search_results['threads'], 'posts' => $search_results['posts'], 'searchtype' => $search_results['searchtype'], 'resulttype' => $resulttype, 'querycache' => $search_results['querycache']);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 327));
    $_SESSION['last_search'] = TIMENOW;
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'finduserposts')
  {
    if ((empty ($_GET['id']) OR !is_valid_id ($_GET['id'])))
    {
      print_no_permission (true);
    }

    $where_sql = 'p.uid=\'' . intval ($_GET['id']) . '\'';
    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE (fp.canview = \'no\' OR fp.cansearch = \'no\') AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 341));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if ($_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash))
        {
          $uf2[] = 0 + $notin['fid'];
          continue;
        }
      }

      if (0 < count ($uf2))
      {
        if ($unsearchforums)
        {
          $unsearchforums .= ',' . implode (',', $uf2);
        }
        else
        {
          $unsearchforums = implode (',', $uf2);
        }
      }
    }

    if ($unsearchforums)
    {
      $where_sql .= '' . ' AND p.fid NOT IN (' . $unsearchforums . ')';
    }

    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => '', 'posts' => '', 'searchtype' => 'titles', 'resulttype' => 'posts', 'querycache' => $where_sql);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 388));
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'getnew')
  {
    $where_sql = 't.lastpost >= \'' . mysql_real_escape_string ($CURUSER['last_forum_visit']) . '\'';
    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE (fp.canview = \'no\' OR fp.cansearch = \'no\') AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 398));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if ($_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash))
        {
          $uf2[] = 0 + $notin['fid'];
          continue;
        }
      }

      if (0 < count ($uf2))
      {
        if ($unsearchforums)
        {
          $unsearchforums .= ',' . implode (',', $uf2);
        }
        else
        {
          $unsearchforums = implode (',', $uf2);
        }
      }
    }

    if ($unsearchforums)
    {
      $where_sql .= '' . ' AND t.fid NOT IN (' . $unsearchforums . ')';
    }

    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => '', 'posts' => '', 'searchtype' => 'titles', 'resulttype' => 'threads', 'querycache' => $where_sql);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 445));
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'daily')
  {
    if ($_GET['days'] < 1)
    {
      $days = 1;
    }
    else
    {
      $days = intval ($_GET['days']);
    }

    $datecut = TIMENOW - 86400 * $days;
    $where_sql = 't.lastpost >=\'' . mysql_real_escape_string ($datecut) . '\'';
    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE (fp.canview = \'no\' OR fp.cansearch = \'no\') AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 465));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if ($_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash))
        {
          $uf2[] = 0 + $notin['fid'];
          continue;
        }
      }

      if (0 < count ($uf2))
      {
        if ($unsearchforums)
        {
          $unsearchforums .= ',' . implode (',', $uf2);
        }
        else
        {
          $unsearchforums = implode (',', $uf2);
        }
      }
    }

    if ($unsearchforums)
    {
      $where_sql .= '' . ' AND t.fid NOT IN (' . $unsearchforums . ')';
    }

    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => '', 'posts' => '', 'searchtype' => 'titles', 'resulttype' => 'threads', 'querycache' => $where_sql);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 512));
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'do_search')
  {
    if (!empty ($_SESSION['last_search']))
    {
      flood_check ($lang->tsf_forums['search'], $_SESSION['last_search']);
    }

    $resulttype = 'threads';
    $search_data = array ('keywords' => $_POST['keywords'], 'author' => $_POST['author'], 'postthread' => $_POST['postthread'], 'matchusername' => $_POST['matchusername'], 'forums' => $_POST['forums']);
    $search_results = perform_search_mysql_ft ($search_data);
    $sid = md5 (uniqid (microtime (), 1));
    $searcharray = array ('sid' => $sid, 'uid' => intval ($CURUSER['id']), 'dateline' => TIMENOW, 'ipaddress' => $CURUSER['ip'], 'threads' => $search_results['threads'], 'posts' => $search_results['posts'], 'searchtype' => $search_results['searchtype'], 'resulttype' => $resulttype, 'querycache' => $search_results['querycache']);
    (sql_query ('INSERT INTO ' . TSF_PREFIX . 'searchlog (sid,uid,dateline,ipaddress,threads,posts,searchtype,resulttype,querycache) VALUES (' . sqlesc ($searcharray['sid']) . ',' . sqlesc ($searcharray['uid']) . ',' . sqlesc ($searcharray['dateline']) . ',' . sqlesc ($searcharray['ipaddress']) . ',' . sqlesc ($searcharray['threads']) . ',' . sqlesc ($searcharray['posts']) . ',' . sqlesc ($searcharray['searchtype']) . ',' . sqlesc ($searcharray['resulttype']) . ',' . sqlesc ($searcharray['querycache']) . ')') OR sqlerr (__FILE__, 548));
    $_SESSION['last_search'] = TIMENOW;
    redirect ('' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&searchid=' . $sid, $lang->tsf_forums['searchresults'], NULL, 3, FALSE, FALSE);
    exit ();
  }

  if ($action == 'show_search_results')
  {
    require_once INC_PATH . '/functions_cookies.php';
    $sid = (isset ($_GET['searchid']) ? $_GET['searchid'] : '');
    if (empty ($sid))
    {
      add_breadcrumb ($lang->tsf_forums['search_results']);
      stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
      build_breadcrumb ();
      stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror4'], false);
      stdfoot ();
      exit ();
    }

    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'searchlog WHERE sid = ' . sqlesc ($sid)) OR sqlerr (__FILE__, 567));
    $search = mysql_fetch_assoc ($query);
    if ((!$search['sid'] OR ($search['uid'] != $CURUSER['id'] AND !$moderator)))
    {
      add_breadcrumb ($lang->tsf_forums['search_results']);
      stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
      build_breadcrumb ();
      stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror4'], false);
      stdfoot ();
      exit ();
    }

    $str = '		
		<table border="0" cellspacing="0" cellpadding="4" class="tborder" style="clear: both;" >
			<tr>
				<td class="thead" colspan="7">					
					<div>
						<strong>' . $lang->tsf_forums['search_results'] . '</strong>
					</div>
				</td>
			</tr>

			<tr>
				<td class="tcat" align="center" width="1%"><span class="smalltext"><strong>' . $lang->tsf_forums['status'] . '</strong></span></td>
				<td class="tcat" align="left" width="40%"><span class="smalltext"><strong>' . $lang->tsf_forums['thread'] . '</strong></span></td>
				<td class="tcat" align="left" width="25%"><span class="smalltext"><strong>' . $lang->tsf_forums['forum'] . '</strong></span></td>
				<td class="tcat" align="center" width="10%"><span class="smalltext"><strong>' . $lang->tsf_forums['author'] . '</strong></span></td>
				<td class="tcat" align="center" width="1%"><span class="smalltext"><strong>' . $lang->tsf_forums['replies'] . '</strong></span></td>
				<td class="tcat" align="center" width="1%"><span class="smalltext"><strong>' . $lang->tsf_forums['views'] . '</strong></span></td>
				<td class="tcat" align="left" width="15%"><span class="smalltext"><strong>' . $lang->tsf_forums['lastpost'] . '</strong></span></td>
			</tr>
		';
    if ($search['resulttype'] == 'threads')
    {
      $sortfield = 't.lastpost';
    }
    else
    {
      $sortfield = 'p.dateline';
    }

    $order = 'desc';
    $threads = array ();
    if ($search['resulttype'] == 'threads')
    {
      $threadcount = 0;
      if ($search['querycache'] != '')
      {
        $where_conditions = $search['querycache'];
        ($query = sql_query ('SELECT t.tid FROM ' . TSF_PREFIX . ('' . 'threads t WHERE ' . $where_conditions)) OR sqlerr (__FILE__, 621));
        while ($thread = mysql_fetch_assoc ($query))
        {
          $threads[$thread['tid']] = $thread['tid'];
          ++$threadcount;
        }

        if (0 < $threadcount)
        {
          $search['threads'] = implode (',', $threads);
        }
        else
        {
          add_breadcrumb ($lang->tsf_forums['search_results']);
          stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
          build_breadcrumb ();
          stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
          stdfoot ();
          exit ();
        }

        $where_conditions = 't.tid IN (' . $search['threads'] . ')';
      }
      else
      {
        $where_conditions = 't.tid IN (' . $search['threads'] . ')';
        ($query = sql_query ('SELECT COUNT(t.tid) AS resultcount FROM ' . TSF_PREFIX . ('' . 'threads t WHERE ' . $where_conditions)) OR sqlerr (__FILE__, 650));
        $count = mysql_fetch_assoc ($query);
        if (!$count['resultcount'])
        {
          add_breadcrumb ($lang->tsf_forums['search_results']);
          stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
          build_breadcrumb ();
          stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
          stdfoot ();
          exit ();
        }

        $threadcount = $count['resultcount'];
      }

      $sorturl = '' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&amp;searchid=' . htmlspecialchars_uni ($sid);
      sanitize_pageresults ($threadcount, $pagenumber, $perpage, 200);
      $multipage = construct_page_nav ($pagenumber, $perpage, $threadcount, $sorturl);
      $limitlower = ($pagenumber - 1) * $perpage;
      $limitupper = $pagenumber * $perpage;
      if ($threadcount < $limitupper)
      {
        $limitupper = $threadcount;
        if ($threadcount < $limitlower)
        {
          $limitlower = $threadcount - $perpage - 1;
        }
      }

      if ($limitlower < 0)
      {
        $limitlower = 0;
      }

      ($query = sql_query ('
			SELECT t.*, f.name as currentforum, f.pid as parent, ff.name as realforum, ff.fid as realforumid, u.username as reallastposterusername, u.id as reallastposteruid, g.namestyle as lastposternamestyle, uu.username as threadstarter, uu.id as threadstarteruid, gg.namestyle as threadstarternamestyle
			FROM ' . TSF_PREFIX . 'threads t 
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . ('' . 'forums ff ON (ff.fid=f.pid)
			LEFT JOIN users u ON (t.lastposteruid=u.id)
			LEFT JOIN usergroups g ON (u.usergroup=g.gid)
			LEFT JOIN users uu ON (t.uid=uu.id)
			LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid)
			WHERE ' . $where_conditions . ' 
			ORDER BY ' . $sortfield . ' ' . $order . '
			LIMIT ' . $limitlower . ', ' . $perpage . '
		')) OR sqlerr (__FILE__, 698));
      $thread_cache = array ();
      while ($thread = mysql_fetch_assoc ($query))
      {
        $thread_cache[$thread['tid']] = $thread;
      }

      $thread_ids = implode (',', array_keys ($thread_cache));
      ($query = sql_query ('SELECT tid,dateline FROM ' . TSF_PREFIX . 'threadsread WHERE uid = ' . sqlesc ($CURUSER['id']) . ' AND tid IN(' . $thread_ids . ')') OR sqlerr (__FILE__, 707));
      while ($readthread = mysql_fetch_assoc ($query))
      {
        $thread_cache[$readthread['tid']]['lastread'] = $readthread['dateline'];
      }

      foreach ($thread_cache as $thread)
      {
        $lastread = 0;
        if ($forumread < $thread['lastpost'])
        {
          $cutoff = TIMENOW - 7 * 60 * 60 * 24;
          if ($cutoff < $thread['lastpost'])
          {
            if ($thread['lastread'])
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

        $subject = htmlspecialchars_uni (ts_remove_badwords ($thread['subject']));
        $lastpost_data = '';
        $lastpost_data = array ('lastpost' => $thread['lastpost'], 'lastposter' => get_user_color (htmlspecialchars_uni ($thread['reallastposterusername']), $thread['lastposternamestyle']), 'lastposteruid' => $thread['reallastposteruid']);
        if ($thread['sticky'] == 1)
        {
          $class = 'sticky';
          $desc = $lang->tsf_forums['stickythread'];
        }
        else
        {
          $class = 'trow1';
          $desc = '';
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
				<td class="' . $class . '" style="white-space: nowrap;">
					<span class="smalltext">' . $lastpost_date . ' ' . $lastpost_time . '<br />
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

          $thread['multipage'] = '' . '<br /><span class="smalltext">(<img src="images/multipage.gif" border="0" alt="' . $lang->tsf_forums['multithread'] . '" title="' . $lang->tsf_forums['multithread'] . '" class="inlineimg"> ' . $lang->tsf_forums['pages'] . ' ' . $threadpages . $morelink . ')</span>';
        }
        else
        {
          $threadpages = '';
          $morelink = '';
          $thread['multipage'] = '';
        }

        $desc = $stickyimg = $ratingimage = $threadtags = $pollimage = '';
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

        if ((($stickyimg OR $ratingimage) OR $pollimage))
        {
          $threadtags = '<span style="float: right;">' . $stickyimg . ' ' . $pollimage . ($ratingimage ? '<br />' . $ratingimage : '') . '</span>';
        }

        $str .= '' . '
				<tr>
					<td class="trow1" align="center">' . $images . '</td>
					<td class="' . $class . '" align="left">' . $threadtags . $desc . '<a href="' . tsf_seo_clean_text ($subject, 't', $thread['tid']) . ('' . '">' . $subject . '</a>' . $thread['multipage'] . '</td>
					<td class="' . $class . '" align="left"><a href="') . tsf_seo_clean_text ($thread['currentforum'], 'fd', $thread['fid']) . ('' . '">' . $thread['currentforum'] . '</a></td>
					<td class="' . $class . '" align="center"><a href="') . tsf_seo_clean_text (strip_tags ($author), 'u', $thread['threadstarteruid'], '', 'ts') . ('' . '">' . $author . '</a></td>
					<td class="' . $class . '" align="center">' . $replies . '</td>
					<td class="' . $class . '" align="center">' . $views . '</td>
					' . $lastpost . '
				</tr>');
        if (($unreadpost == 0 AND ($page == 1 OR !$page)))
        {
          require_once INC_PATH . '/functions_cookies.php';
          ts_set_array_cookie ('forumread', $fid, TIMENOW);
          continue;
        }
      }
    }
    else
    {
      $postcount = 0;
      if ($search['querycache'] != '')
      {
        $where_conditions = $search['querycache'];
        ($query = sql_query ('SELECT p.pid FROM ' . TSF_PREFIX . ('' . 'posts p WHERE ' . $where_conditions)) OR sqlerr (__FILE__, 893));
        while ($post = mysql_fetch_assoc ($query))
        {
          $posts[$post['pid']] = $post['pid'];
          ++$postcount;
        }

        if (0 < $postcount)
        {
          $search['posts'] = implode (',', $posts);
        }
        else
        {
          add_breadcrumb ($lang->tsf_forums['search_results']);
          stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
          build_breadcrumb ();
          stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
          stdfoot ();
          exit ();
        }

        $where_conditions = 'p.pid IN (' . $search['posts'] . ')';
      }
      else
      {
        $where_conditions = 'p.tid IN (' . $search['threads'] . ')';
        if ($search['posts'] != '')
        {
          $where_conditions .= ' AND p.pid IN (' . $search['posts'] . ')';
        }

        ($query = sql_query ('SELECT COUNT(p.tid) AS resultcount FROM ' . TSF_PREFIX . ('' . 'posts p WHERE ' . $where_conditions)) OR sqlerr (__FILE__, 925));
        $count = mysql_fetch_assoc ($query);
        if (!$count['resultcount'])
        {
          add_breadcrumb ($lang->tsf_forums['search_results']);
          stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
          build_breadcrumb ();
          stdmsg ($lang->global['error'], $lang->tsf_forums['searcherror'], false);
          stdfoot ();
          exit ();
        }

        $postcount = $count['resultcount'];
      }

      $sorturl = '' . $_SERVER['SCRIPT_NAME'] . '?action=show_search_results&amp;searchid=' . htmlspecialchars_uni ($sid);
      sanitize_pageresults ($postcount, $pagenumber, $perpage, 200);
      $multipage = construct_page_nav ($pagenumber, $perpage, $postcount, $sorturl);
      $limitlower = ($pagenumber - 1) * $perpage;
      $limitupper = $pagenumber * $perpage;
      if ($postcount < $limitupper)
      {
        $limitupper = $postcount;
        if ($postcount < $limitlower)
        {
          $limitlower = $postcount - $perpage - 1;
        }
      }

      if ($limitlower < 0)
      {
        $limitlower = 0;
      }

      ($query = sql_query ('
					SELECT 
					p.pid, p.tid, p.fid, p.subject, u.id as uid, u.username, p.dateline, p.message, f.name, t.subject as threadsubject,
					g.namestyle
					FROM 
					' . TSF_PREFIX . 'posts p 					
					LEFT JOIN users u ON (p.uid=u.id)
					LEFT JOIN usergroups g ON (u.usergroup=g.gid)
					LEFT JOIN ' . TSF_PREFIX . 'threads t ON (p.tid=t.tid) 
					LEFT JOIN ' . TSF_PREFIX . ('' . 'forums f ON (p.fid=f.fid)
					WHERE ' . $where_conditions . ' 
					ORDER BY ' . $sortfield . ' ' . $order . '
					LIMIT ' . $limitlower . ', ' . $perpage)) OR sqlerr (__FILE__, 972));
      $str = '		
		<table border="0" cellspacing="0" cellpadding="4" style="clear: both;" >
			<tr>
				<td class="thead" colspan="4">					
					<div>
						<strong>' . $lang->tsf_forums['search_results'] . '</strong>
					</div>
				</td>
			</tr>

			<tr>				
				<td class="subheader" align="left" width="50%"><span class="smalltext"><strong>' . $lang->tsf_forums['post'] . '</strong></span></td>				
				<td class="subheader" align="center" width="10%"><span class="smalltext"><strong>' . $lang->tsf_forums['author'] . '</strong></span></td>	
				<td class="subheader" align="left" width="25%"><span class="smalltext"><strong>' . $lang->tsf_forums['forum'] . '</strong></span></td>
				<td class="subheader" align="center" width="15%"><span class="smalltext"><strong>' . $lang->tsf_forums['posted'] . '</strong></span></td>
			</tr>
		';
      while ($post = mysql_fetch_assoc ($query))
      {
        $Query = sql_query ('SELECT pid FROM ' . TSF_PREFIX . 'posts WHERE tid =' . $post['tid'] . ' AND pid <= ' . $post['pid']);
        $Count = mysql_num_rows ($Query);
        if ($Count <= $postperpage)
        {
          $P = 0;
        }
        else
        {
          $P = ceil ($Count / $postperpage);
        }

        while (preg_match ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', $post['message']))
        {
          $post['message'] = preg_replace ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', '', $post['message']);
        }

        $str .= '
			<tr>
			<td align="left">' . $lang->tsf_forums['thread'] . ': <a href="' . tsf_seo_clean_text ($post['threadsubject'], 't', $post['tid']) . '"><b>' . htmlspecialchars_uni ($post['threadsubject']) . '</b></a><br />' . $lang->tsf_forums['post'] . ' <a href="' . tsf_seo_clean_text ($post['subject'], 't', $post['tid'], '&pid=' . $post['pid'] . '&amp;nolastpage=true&amp;page=' . $P . '#pid' . $post['pid']) . '"><b>' . htmlspecialchars_uni ($post['subject']) . '</b></a><br />' . htmlspecialchars_uni ($post['message']) . '</td>
			<td align="center" valign="top"><a href="' . tsf_seo_clean_text (strip_tags ($post['username']), 'u', $post['uid'], '', 'ts') . '">' . get_user_color ($post['username'], $post['namestyle']) . '</a></td>
			<td align="left" valign="top"><a href="' . tsf_seo_clean_text ($post['name'], 'fd', $post['fid']) . '"><b>' . $post['name'] . '</b></a></td>
			<td align="center" valign="top">' . my_datee ($dateformat, $post['dateline']) . ' ' . my_datee ($timeformat, $post['dateline']) . '</td>
			</tr>';
      }
    }

    add_breadcrumb ($lang->tsf_forums['search_results']);
    stdhead ('' . $SITENAME . ' TSF FORUMS ' . TSF_VERSION, true, 'supernote');
    build_breadcrumb ();
    $str .= '</table>';
    $ptr = '
	<!-- start: forumdisplay_newthread -->
		<table width="100%" border="0" class="none" style="clear: both;">
			<tr>				
				<td class="none" width="82%" style="padding: 0px 0px 5px 0px;">
					<div style="float: left;" id="navcontainer_f">
						' . $multipage . '
					</div>
				</td>					
			</tr>
		</table>
	<!-- end: forumdisplay_newthread -->';
    $str .= '
	<!-- start: forumdisplay_newthread -->
			<table width="100%" border="0" class="none" style="clear: both;">
				<tr>				
					<td class="none" width="82%">
						<div style="float: left;" id="navcontainer_f">
							' . $multipage . '
						</div>
					</td>						
				</tr>
			</table>
		<!-- end: forumdisplay_newthread -->	';
    echo $ptr . $str;
    stdfoot ();
    exit ();
  }

?>
