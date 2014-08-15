<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function delete_users ()
  {
    global $deleteinativeusers;
    if ($deleteinativeusers != 'yes')
    {
      stderr ('Error', 'This feature currently disabled by administrator... <br />Are you Admin? If yes, open <b>admin/include/inactiveusers_config.php</b> and enable this feature.', false);
    }

    stdhead ('Inactive Users - Delete');
    _form_header_open_ ('Inactive Users - Delete');
    begin_table (true);
    $str = '<tr><td colspan="2" align="left" class="subheader">Please wait....</td></tr>';
    global $body;
    global $subject;
    global $deleteafter;
    $deletedusers = 0;
    $mailcount = 0;
    ($query = sql_query ('SELECT i.userid, u.id, u.username, u.email FROM ts_inactivity i LEFT JOIN users u ON (i.userid=u.id) WHERE i.inactivitytag != 0 AND UNIX_TIMESTAMP(u.last_access) < i.inactivitytag AND i.inactivitytag < UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - ' . $deleteafter . ' DAY))') OR sqlerr (__FILE__, 101));
    while ($user = mysql_fetch_array ($query))
    {
      $userids .= ',' . intval ($user['id']);
      $userids = '' . '0' . $userids;
      $delete = sql_query ('' . 'DELETE FROM users WHERE id IN (' . $userids . ')');
      if ($delete)
      {
        ++$deletedusers;
        write_log ('Account (' . htmlspecialchars_uni ($user['username']) . ') has been deleted due inactivity..');
        echo '<tr><td colspan="2" align="left">Account <b>-> ' . htmlspecialchars_uni ($user['username']) . ' <-</b> has been deleted due inactivity..</td></tr>';
      }

      $sendmail = sent_mail ($user['email'], $subject['deleted'], sprintf ($body['deleted'], $user['username']), 'inactiveusers', FALSE);
      if ($sendmail)
      {
        ++$mailcount;
        continue;
      }
    }

    echo ($deletedusers == 0 ? '<tr><td colspan="2" align="left">There is no inactive user account to delete!</td></tr>' : '');
    end_table ();
    _form_header_close_ ();
    stdfoot ();
    exit ();
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('IUM_VERSION', '0.9 by xam');
  if (@file_exists ('./include/inactiveusers_config.php'))
  {
    include_once './include/inactiveusers_config.php';
  }
  else
  {
    $maxdays = 60;
    $deleteafter = 15;
    $show_per_page = 30;
    $body = array ('inactive' => '<p>Dear %s,</p>
									<p>It has come to our attention that you have registered at <b>' . $SITENAME . '</b> more then <b>' . $maxdays . ' days ago</b>, but didn\'t login again since.</p>
									<p>Did you forget about us?</p>
									<p>We would be happy to see you around again!</p>
									<p>If you don\'t login again within <b>' . $deleteafter . ' days</b> from now, we will <b><font color=red>delete</font></b> your account.</p>
									<p>&nbsp;</p>
									<p>Sincerely,</p>
									<p>' . $SITENAME . ' Team</p>
									<p><a href="' . $BASEURL . '">' . $BASEURL . '</a></p>
									<p>nbsp;</p>
									<p><b>DO NOT REPLY TO THIS EMAIL!</b></p>', 'deleted' => '<p>Dear %s,</p>
									<p>You have not logged in at <b>' . $SITENAME . '</b> for more then <b>' . $maxdays . ' days</b>.</p>
									<p>You also didn\'t respond to our eMail we sent to you <b>' . $deleteafter . ' days ago</b>.</p>
									<p>Therefor we have decided to <b><font color=red>delete</font></b> your Account, as it seems you are not interested in our site any longer.</p>
									<p>We are sorry to see that you left us, feel free to come back at any time.</p>
									<p>&nbsp;</p>
									<p>Sincerely,</p>
									<p>' . $SITENAME . ' Team</p>
									<p><a href="' . $BASEURL . '">' . $BASEURL . '</a></p>
									<p>nbsp;</p>
									<p><b>DO NOT REPLY TO THIS EMAIL!</b></p>');
    $subject = array ('inactive' => $SITENAME . ' - Account Inactive!', 'deleted' => $SITENAME . ' - Account Deleted!');
  }

  $javascript = '
	<script language="javascript">
        var x2168=' . $waitbeforesend . ';
		function countdown()
		{
			x2168--;	
			if(x2168 == 0)
			{
				document.getElementById("waitzone").innerHTML = \'Sending...\';
				window.location = "' . $_this_script_ . '&do=warn";
			}
			if(x2168 > 0)
			{
				document.getElementById("waitzone").innerHTML = \'I will wait \'+x2168+\' seconds before post next mails.. [<a href="' . $BASEURL . '/admin/index.php?act=inactiveusers">stop</a>]\';
				setTimeout(\'countdown()\',1000);
			}
		};
		countdown();
	</script>
';
  $count_query = sql_query ('SELECT COUNT(id) as count FROM users WHERE enabled = \'yes\' AND status = \'confirmed\' AND UNIX_TIMESTAMP(last_access) < UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - ' . $maxdays . ' DAY))');
  $total_count = mysql_result ($count_query, 0, 'count');
  list ($pagertop, $pagerbottom, $limit) = pager ($show_per_page, $total_count, $_this_script_ . '&amp;');
  $query = array ('inactive' => sql_query ('SELECT u.id,u.username,u.email,u.uploaded,u.downloaded,u.last_access,u.added,i.inactivitytag,g.namestyle FROM users u LEFT JOIN ts_inactivity i ON (u.id=i.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\' AND u.status = \'confirmed\' AND UNIX_TIMESTAMP(u.last_access) < UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - ' . $maxdays . ('' . ' DAY)) ORDER BY i.inactivitytag DESC, u.last_access DESC ' . $limit)), 'warn' => sql_query ('SELECT u.id,u.username,u.email,u.uploaded,u.downloaded,u.last_access,u.added,i.inactivitytag,g.namestyle FROM users u LEFT JOIN ts_inactivity i ON (u.id=i.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\' AND u.status = \'confirmed\' AND IF (i.inactivitytag>0,i.inactivitytag=0,u.id>0) AND UNIX_TIMESTAMP(u.last_access) < UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - ' . $maxdays . ' DAY)) ORDER BY u.last_access LIMIT 0, ' . $postmaillimit . ''));
  if ((isset ($_GET['do']) AND $_GET['do'] == 'check_delete'))
  {
    delete_users ();
    return 1;
  }

  if ((isset ($_GET['do']) AND $_GET['do'] == 'warn'))
  {
    stdhead ('Inactive Users - Send Mail');
    _form_header_open_ ('Inactive Users - Send Mail');
    begin_table (true);
    echo '<tr><td colspan="2" align="left" class="subheader">Please wait....</td></tr>';
    ob_flush ();
    flush ();
    $count = 0;
    if (mysql_num_rows ($query['warn']) == 0)
    {
      echo '<tr><td colspan="2" align="left">All inactive users has already been warned!</td></tr>';
    }
    else
    {
      while ($user = mysql_fetch_array ($query['warn']))
      {
        $email = htmlspecialchars_uni ($user['email']);
        echo '<tr><td align="right">Sending email to: ' . htmlspecialchars_uni ($user['username']) . ' (' . $email . ')</td>';
        ob_flush ();
        flush ();
        $sendmail = sent_mail ($email, $subject['inactive'], sprintf ($body['inactive'], $user['username']), 'inactiveusers', FALSE);
        echo '<td align="center">' . ($sendmail ? '<font color="green">Success!</font>' : '<font color="red">Failed!</font>') . '</td></tr>';
        ob_flush ();
        flush ();
        if (($user['id'] AND $sendmail))
        {
          $update = sql_query ('REPLACE INTO ts_inactivity (userid, inactivitytag) VALUES (' . sqlesc ($user['id']) . ', ' . sqlesc (TIMENOW) . ')');
          ++$count;
          continue;
        }
      }
    }

    echo '<td align="left" colspan="2"><div id="waitzone"></div></td></tr>' . $javascript;
    end_table ();
    _form_header_close_ ();
    stdfoot ();
    return 1;
  }

  $str = '
		<div class="error" style="display: none" id="jumpto">Sending mails... (This might take a long time due to limit of inactive users) </div>
		<tr>
		<td class=subheader align="left">User</td>
		<td class=subheader align="left">Email</td>
		<td class=subheader align="center">Ratio</td>
		<td class=subheader align="left">Joined</td>
		<td class=subheader align="left">Last Access</td>
		<td class=subheader align="center">Status</td>
		</tr>';
  $count = 0;
  include_once INC_PATH . '/functions_ratio.php';
  require_once INC_PATH . '/functions_mkprettytime.php';
  while ($user = mysql_fetch_array ($query['inactive']))
  {
    $secs = $deleteafter * 86400;
    $dt = get_date_time ($user['inactivitytag'] + $secs);
    $left = mkprettytime (strtotime ($dt) - gmtime ());
    $str .= '<tr>
		<td><a href="' . $BASEURL . '/userdetails.php?id=' . $user['id'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a></td>
		<td><a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a></td>
		<td align="center">' . get_user_ratio ($user['uploaded'], $user['downloaded']) . '</td>
		<td>' . $user['added'] . '<br />(' . mkprettytime (time () - strtotime ($user['added'])) . ')</td>
		<td>' . ($user['last_access'] != '0000-00-00 00:00:00' ? $user['last_access'] . '<br />(' . mkprettytime (time () - strtotime ($user['last_access'])) . ')' : 'Never') . '</td>
		<td align="center">' . ($user['inactivitytag'] != 0 ? mkprettytime (time () - $user['inactivitytag']) . ' ago warned via email.<br />Will be deleted within:<br />' . $left . '<br />' . $dt : 'Inactive. No action taken yet.') . '</td>
		</tr>';
    ++$count;
  }

  stdhead ('Inactive Users more than ' . $maxdays . ' days! (Total ' . $total_count . ' users found, Showing ' . $show_per_page . ' per page!)');
  echo '<p align="right"<input type="button" class="hoptobutton" value="send warn email to inactive users" onClick="jumpto(\'' . $_this_script_ . '&do=warn\',\'yes\')"> <input type="button" class="hoptobutton" value="Check and Delete" onClick="jumpto(\'' . $_this_script_ . '&do=check_delete\')"></p>';
  echo $pagertop;
  _form_header_open_ ('Inactive Users more than ' . $maxdays . ' days! (Total ' . $total_count . ' users found, Showing ' . $show_per_page . ' per page!) ' . (0 < $deletedusers ? $deletedusers . ' accounts has been deleted due inactivity!' : ''));
  begin_table (true);
  $str .= '<tr><td colspan="6" align="right"><input type="button" class="hoptobutton" value="send warn email to inactive users" onClick="jumpto(\'' . $_this_script_ . '&do=warn\',\'yes\')"> <input type="button" class="hoptobutton" value="Check and Delete" onClick="jumpto(\'' . $_this_script_ . '&do=check_delete\')"></td></tr>';
  echo $str;
  end_table ();
  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
