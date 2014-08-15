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

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  $polloptions = (isset ($_POST['polloptions']) ? intval ($_POST['polloptions']) : (isset ($_GET['polloptions']) ? intval ($_GET['polloptions']) : 4));
  $polloptions = (((!is_valid_id ($polloptions) OR $polloptions < 2) OR 20 < $polloptions) ? 4 : $polloptions);
  $do = (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : ''));
  $question = (isset ($_POST['question']) ? trim ($_POST['question']) : (isset ($_GET['question']) ? trim ($_GET['question']) : ''));
  $options = (isset ($_POST['options']) ? $_POST['options'] : (isset ($_GET['options']) ? $_GET['options'] : ''));
  $pollid = (isset ($_POST['pollid']) ? intval ($_POST['pollid']) : (isset ($_GET['pollid']) ? intval ($_GET['pollid']) : 0));
  $posthash = (isset ($_POST['posthash']) ? trim ($_POST['posthash']) : (isset ($_GET['posthash']) ? trim ($_GET['posthash']) : ''));
  if (!is_valid_id ($tid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  ($query = sql_query ('SELECT 
			t.tid, t.closed, t.pollid, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($tid) . ' LIMIT 1') OR sqlerr (__FILE__, 53));
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

  if ((($do == 'updatepoll' AND is_valid_id ($pollid)) AND ($moderator OR $forummoderator)))
  {
    if ((empty ($posthash) OR $posthash != sha1 ($pollid . $securehash . $pollid)))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['rateresult4']);
      exit ();
    }

    $query = sql_query ('SELECT * FROM ' . TSF_PREFIX . ('' . 'poll WHERE pollid = ' . $pollid . ' LIMIT 1'));
    $pollinfo = mysql_fetch_assoc ($query);
    if ((!$pollinfo OR !$thread['pollid']))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll21']);
      exit ();
    }

    if ((((empty ($question) OR strlen ($question) < $f_minmsglength) OR count ($options) < 2) OR 20 < count ($options)))
    {
      $error = $lang->tsf_forums['poll8'];
    }
    else
    {
      $optionscount = 0;
      $optionsarray = $votesarray = array ();
      foreach ($options as $optionid => $optiontext)
      {
        if ((!empty ($optiontext) AND $f_minmsglength < strlen ($optiontext)))
        {
          $optionsarray[$optionid] = trim ($optiontext);
          $votesarray[$optionid] = 0;
          ++$optionscount;
          continue;
        }
        else
        {
          continue;
        }
      }

      if ($optionscount < 2)
      {
        $error = $lang->tsf_forums['poll8'];
      }
    }

    if ($error)
    {
      $do = 'polledit';
    }
    else
    {
      $extraquery = ', active = 1';
      if ((isset ($_POST['closepoll']) AND $_POST['closepoll'] == 'yes'))
      {
        $extraquery = ', active = 0';
      }

      $question = sqlesc ($question);
      $options = sqlesc (implode ('~~~', $optionsarray));
      sql_query ('UPDATE ' . TSF_PREFIX . ('' . 'poll SET question = ' . $question . ', options = ' . $options . $extraquery . ' WHERE pollid = ' . $pollid));
      header ('' . 'Location: ' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid);
      exit ();
    }
  }

  if ((($do == 'polledit' AND is_valid_id ($pollid)) AND ($moderator OR $forummoderator)))
  {
    stdhead ($lang->tsf_forums['poll16']);
    if (isset ($error))
    {
      stdmsg ($lang->global['error'], $error, false);
    }

    $query = sql_query ('SELECT * FROM ' . TSF_PREFIX . ('' . 'poll WHERE pollid = ' . $pollid . ' LIMIT 1'));
    $pollinfo = mysql_fetch_assoc ($query);
    if ((!$pollinfo OR !$thread['pollid']))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll21']);
      exit ();
    }

    $options = explode ('~~~', $pollinfo['options']);
    $optionid = 1;
    foreach ($options as $oid => $value)
    {
      $showpolloptions .= '
		' . sprintf ($lang->tsf_forums['poll6'], $optionid) . '<br />
		<input type="text" name="options[' . $optionid . ']" value="' . htmlspecialchars_uni ($value) . '" size="50"><br />
		';
      ++$optionid;
    }

    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="updatepoll">
	<input type="hidden" name="tid" value="' . $tid . '">
	<input type="hidden" name="pollid" value="' . $pollid . '">
	<input type="hidden" name="posthash" value="' . sha1 ($pollid . $securehash . $pollid) . '">
	<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
		<tr>
			<td class="thead">' . $lang->tsf_forums['poll16'] . '</td>
		</tr>
		<tr>
			<td>
				<FIELDSET>
					<LEGEND>' . $lang->tsf_forums['poll4'] . '</LEGEND>
					<input type="text" size="50" name="question" value="' . htmlspecialchars_uni ($pollinfo['question']) . '">
				</FIELDSET>
				<FIELDSET>			
					<LEGEND>' . $lang->tsf_forums['poll5'] . '</LEGEND>
					' . $showpolloptions . '
				</FIELDSET>

				<FIELDSET>
					<LEGEND>' . $lang->tsf_forums['poll22'] . '</LEGEND>
					<input name="closepoll" value="yes" type="checkbox"' . ($pollinfo['active'] == 0 ? ' checked="checked"' : '') . '> ' . $lang->tsf_forums['poll23'] . '
				</FIELDSET>
			</td>
		</tr>
		<tr>
			<td align="center"><input type="submit" value="' . $lang->global['buttonsave'] . '"></td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  if (($do == 'showresults' AND is_valid_id ($pollid)))
  {
    $query = sql_query ('SELECT * FROM ' . TSF_PREFIX . ('' . 'poll WHERE pollid = ' . $pollid . ' LIMIT 1'));
    $pollinfo = mysql_fetch_assoc ($query);
    if (!$pollinfo)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll21']);
      exit ();
    }

    setcookie ('showpollresult', $pollid, TIMENOW + 60);
    header ('' . 'Location: ' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid);
    exit ();
  }

  if ((($do == 'pollvote' AND is_valid_id ($pollid)) AND $usergroups['canvote'] == 'yes'))
  {
    if ((empty ($posthash) OR $posthash != sha1 ($pollid . $securehash . $pollid)))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['rateresult4']);
      exit ();
    }

    if ((!$thread['pollid'] OR $thread['pollid'] != $pollid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $optionnumber = intval ($_POST['optionnumber']);
    if (!is_valid_id ($optionnumber))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll20']);
      exit ();
    }

    $query = sql_query ('SELECT * FROM ' . TSF_PREFIX . ('' . 'poll WHERE pollid = ' . $pollid . ' LIMIT 1'));
    $pollinfo = mysql_fetch_assoc ($query);
    if ((((!$pollinfo['active'] OR $thread['closed'] == 'yes') AND !$moderator) AND !$forummoderator))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll13']);
      exit ();
    }

    $pollquery2 = sql_query ('
		SELECT voteoption
		FROM ' . TSF_PREFIX . ('' . 'pollvote
		WHERE pollid = ' . $pollid . ' AND userid = ' . $CURUSER['id'] . '
		'));
    $voted = (0 < mysql_num_rows ($pollquery2) ? true : false);
    if ($voted)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll11']);
      exit ();
    }

    $totaloptions = substr_count ($pollinfo['options'], '~~~') + 1;
    if ((0 < $optionnumber AND $optionnumber <= $totaloptions))
    {
      sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'pollvote (pollid,userid,votedate,voteoption) VALUES (' . $pollid . ',' . $CURUSER['id'] . ',') . sqlesc (TIMENOW) . ('' . ',' . $optionnumber . ')'));
      $old_votes_array = explode ('~~~', $pollinfo['votes']);
      ++$old_votes_array[$optionnumber - 1];
      $new_votes_array = implode ('~~~', $old_votes_array);
      sql_query ('UPDATE ' . TSF_PREFIX . 'poll SET voters = voters + 1, votes = ' . sqlesc ($new_votes_array) . ('' . ' WHERE pollid = ' . $pollid));
      include_once INC_PATH . '/readconfig_kps.php';
      kps ('+', $kpspoll, $CURUSER['id']);
      header ('' . 'Location: ' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid);
      exit ();
    }
    else
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll20']);
      exit ();
    }
  }

  if (($do == 'createnewpoll' AND $usergroups['cancreatepoll'] == 'yes'))
  {
    if ($thread['pollid'])
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll9']);
      exit ();
    }

    if ((((empty ($question) OR strlen ($question) < $f_minmsglength) OR count ($options) < 2) OR 20 < count ($options)))
    {
      $error = $lang->tsf_forums['poll8'];
    }
    else
    {
      $optionscount = 0;
      $optionsarray = $votesarray = array ();
      foreach ($options as $optionid => $optiontext)
      {
        if ((!empty ($optiontext) AND $f_minmsglength < strlen ($optiontext)))
        {
          $optionsarray[$optionid] = trim ($optiontext);
          $votesarray[$optionid] = 0;
          ++$optionscount;
          continue;
        }
        else
        {
          continue;
        }
      }

      if ($optionscount < 2)
      {
        $error = $lang->tsf_forums['poll8'];
      }
    }

    if ($error)
    {
      $do = 'new';
    }
    else
    {
      $question = sqlesc ($question);
      $dateline = sqlesc (TIMENOW);
      $options = sqlesc (implode ('~~~', $optionsarray));
      $votes = sqlesc (implode ('~~~', $votesarray));
      $numberoptions = sqlesc (intval ($optionscount));
      (sql_query ('INSERT INTO ' . TSF_PREFIX . ('' . 'poll (question, dateline, options, votes, numberoptions) VALUES (' . $question . ', ' . $dateline . ', ' . $options . ', ' . $votes . ', ' . $numberoptions . ')')) OR sqlerr (__FILE__, 321));
      $pollid = mysql_insert_id ();
      sql_query ('UPDATE ' . TSF_PREFIX . 'threads SET pollid = ' . sqlesc ($pollid) . ' WHERE tid = ' . sqlesc ($tid));
      header ('' . 'Location: ' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid);
      exit ();
    }
  }

  if (($do == 'new' AND $usergroups['cancreatepoll'] == 'yes'))
  {
    if ($thread['pollid'])
    {
      stderr ($lang->global['error'], $lang->tsf_forums['poll9']);
      exit ();
    }

    $showpolloptions;
    $i = 1;
    while ($i <= $polloptions)
    {
      $showpolloptions .= '
		' . sprintf ($lang->tsf_forums['poll6'], $i) . '<br />
		<input type="text" name="options[' . $i . ']" value="' . ($options[$i] ? htmlspecialchars_uni ($options[$i]) : '') . '" size="50"><br />
		';
      ++$i;
    }

    stdhead ($lang->tsf_forums['poll1']);
    if (isset ($error))
    {
      stdmsg ($lang->global['error'], $error, false);
    }

    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="createnewpoll">
	<input type="hidden" name="tid" value="' . $tid . '">
	<input type="hidden" name="polloptions" value="' . $polloptions . '">
	<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
		<tr>
			<td class="thead">' . $lang->tsf_forums['poll1'] . '</td>
		</tr>
		<tr>
			<td>
				<FIELDSET>
					<LEGEND>' . $lang->tsf_forums['poll4'] . '</LEGEND>
					<input type="text" size="50" name="question" value="' . htmlspecialchars_uni ($question) . '">
				</FIELDSET>
				<FIELDSET>			
					<LEGEND>' . $lang->tsf_forums['poll5'] . '</LEGEND>
					' . $showpolloptions . '
				</FIELDSET>
			</td>
		</tr>
		<tr>
			<td align="center"><input type="submit" value="' . $lang->tsf_forums['poll7'] . '"></td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

?>
