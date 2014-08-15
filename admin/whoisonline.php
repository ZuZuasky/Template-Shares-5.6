<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function what_user_do ($location)
  {
    $what_user_do = '<font color="red"><b>Unknown Location!</b></font>';
    if ((strstr ($location, 'tsf_forums') AND !strstr ($location, 'returnto')))
    {
      preg_match_all ('#\\/tsf_forums\\/(.*)\\.php#U', $location, $results, PREG_SET_ORDER);
      switch ($results[0][1])
      {
        case 'index':
        {
          $what_user_do = 'Viewing Index.';
          break;
        }

        case 'forumdisplay':
        {
          $what_user_do = 'Viewing Forum.';
          break;
        }

        case 'showthread':
        {
          $what_user_do = 'Viewing Thread.';
          break;
        }

        case 'announcement':
        {
          $what_user_do = 'Viewing Announcement.';
          break;
        }

        case 'deletepost':
        {
        }

        case 'massdelete':
        {
          $what_user_do = 'Deleting Post.';
          break;
        }

        case 'editpost':
        {
          $what_user_do = 'Editing Post.';
          break;
        }

        case 'moderation':
        {
          $what_user_do = 'Moderating Thread/Post.';
          break;
        }

        case 'newreply':
        {
          $what_user_do = 'Posting Reply.';
          break;
        }

        case 'newthread':
        {
          $what_user_do = 'Creating Thread.';
          break;
        }

        case 'poll':
        {
          $what_user_do = 'Voting Poll.';
          break;
        }

        case 'subscription':
        {
          $what_user_do = 'Subscription Thread.';
          break;
        }

        case 'threadrate':
        {
          $what_user_do = 'Rating Thread.';
          break;
        }

        case 'tsf_search':
        {
          $what_user_do = 'Searching Forums.';
          break;
        }

        case 'attachment':
        {
          $what_user_do = 'Viewing Attachment.';
        }

        case 'top_stats':
        {
          $what_user_do = 'Viewing Top 10 Forum Stats.';
        }
      }

      $what_user_do = '' . '<b>Forum:</b> ' . $what_user_do;
    }
    else
    {
      if (strstr ($location, '/admin/'))
      {
        $what_user_do = '<b>Viewing Admin Panel.</b>';
      }
      else
      {
        if (strstr ($location, '/shoutcast/'))
        {
          $what_user_do = '<b>Shoutcast:</b> Listening Music.';
        }
        else
        {
          if (strstr ($location, '/pbar/'))
          {
            $what_user_do = 'Viewing Donation Status.';
          }
          else
          {
            preg_match_all ('#\\/(.*)\\.php#U', $location, $results, PREG_SET_ORDER);
            switch ($results[0][1])
            {
              case 'listen':
              {
                $what_user_do = 'Listening Image Verification Code';
                break;
              }

              case 'ok':
              {
                $what_user_do = 'Viewing Confirmation Page.';
                break;
              }

              case 'index':
              {
                $what_user_do = 'Viewing Index Page.';
                break;
              }

              case 'browse':
              {
                $what_user_do = 'Viewing Browse Page.';
                break;
              }

              case 'comment':
              {
                $what_user_do = 'Viewing Comment Page.';
                break;
              }

              case 'donate':
              {
                $what_user_do = 'Viewing Donation Page.';
                break;
              }

              case 'edit':
              {
                $what_user_do = 'Editing Torrent.';
                break;
              }

              case 'faq':
              {
                $what_user_do = 'Viewing FAQ Page.';
                break;
              }

              case 'finduser':
              {
                $what_user_do = 'Searching User.';
                break;
              }

              case 'friends':
              {
                $what_user_do = 'Viewing Friends Page.';
                break;
              }

              case 'getrss':
              {
              }

              case 'rss':
              {
                $what_user_do = 'Viewing RSS Page.';
                break;
              }

              case 'invite':
              {
                $what_user_do = 'Viewing Invite Page.';
                break;
              }

              case 'logout':
              {
                $what_user_do = 'Logout.';
                break;
              }

              case 'messages':
              {
                $what_user_do = 'Viewing Messages.';
                break;
              }

              case 'sendmessage':
              {
                $what_user_do = 'Sending PM.';
                break;
              }

              case 'mybonus':
              {
                $what_user_do = 'Viewing Bonus Page.';
                break;
              }

              case 'referrals':
              {
                $what_user_do = 'Viewing Referrals Page.';
                break;
              }

              case 'topten':
              {
                $what_user_do = 'Viewing TOPTEN Page.';
                break;
              }

              case 'viewsnatches':
              {
                $what_user_do = 'Viewing Snatches Page.';
                break;
              }

              case 'userdetails':
              {
                $what_user_do = 'Viewing Userdetails Page.';
                break;
              }

              case 'details':
              {
                $what_user_do = 'Viewing Torrent Details.';
                break;
              }

              case 'upload':
              {
                $what_user_do = 'Uploading Torrent.';
                break;
              }

              case 'ts_subtitles':
              {
                $what_user_do = 'Viewing Subtitles Page.';
                break;
              }

              case 'download':
              {
                $what_user_do = 'Downloading Torrent.';
                break;
              }

              case 'badusers':
              {
                $what_user_do = 'Viewing BadUsers Page.';
                break;
              }

              case 'usercp':
              {
                $what_user_do = 'Viewing User Control Panel.';
                break;
              }

              case 'bookmarks':
              {
                $what_user_do = 'Viewing Bookmarks Page.';
                break;
              }

              case 'users':
              {
                $what_user_do = 'Viewing Member List.';
                break;
              }

              case 'rules':
              {
                $what_user_do = 'Viewing Rules Page.';
                break;
              }

              case 'takerate':
              {
                $what_user_do = 'Rating Torrent.';
                break;
              }

              case 'image':
              {
                $what_user_do = 'Showing Image Verification String.';
                break;
              }

              case 'login':
              {
              }

              case 'takelogin':
              {
                $what_user_do = 'Logging.';
                break;
              }

              case 'signup':
              {
                $what_user_do = 'Registering.';
                break;
              }

              case 'recover':
              {
              }

              case 'recoverhint':
              {
                $what_user_do = 'Recovering Password.';
                break;
              }

              case 'confirm':
              {
                $what_user_do = 'Confirming account.';
                break;
              }

              case 'staff':
              {
                $what_user_do = 'Viewing Staff Page.';
                break;
              }

              case 'contactstaff':
              {
                $what_user_do = 'Sending Message to Staff.';
                break;
              }

              case 'contactus':
              {
                $what_user_do = 'Viewing Contact Us Page.';
                break;
              }

              case 'links':
              {
                $what_user_do = 'Viewing Useful Links Page.';
                break;
              }

              case 'redirector_footer':
              {
                $what_user_do = 'Redirecting.';
                break;
              }

              case 'stats':
              {
                $what_user_do = 'Viewing Tracker Statistics Page.';
                break;
              }

              case 'ts_applications':
              {
                $what_user_do = 'Viewing Applications Page';
                break;
              }

              case 'ts_social_groups':
              {
                $what_user_do = 'Viewing Social Groups';
                break;
              }

              case 'viewrequests':
              {
                $what_user_do = 'Viewing Request Page';
              }
            }
          }
        }
      }
    }

    return $what_user_do;
  }

  function headerr ($text, $header = true, $showbutton = true)
  {
    global $action;
    global $_this_script_;
    if ($header)
    {
      stdhead ($text);
    }

    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<div align="right">' . ($action == 'today' ? '<input type="button" class="hoptobutton" value="Go Back" onClick="jumpto(\'' . $_this_script_ . '\')">' : (((empty ($action) OR $action == 'now') AND $showbutton) ? '<input type="button" class="hoptobutton" value="Who was online today" onClick="jumpto(\'' . $_this_script_ . '&action=today\')">' : ($showbutton ? '<input type="button" class="hoptobutton" value="Go Back" onClick="javascript:history.go(-1)">' : ''))) . '</div>';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">' . $text . '</td></tr>';
  }

  function closee ($foot = true)
  {
    echo '</table></tbody></td></tr></table></tbody>';
    if ($foot)
    {
      stdfoot ();
      exit ();
    }

  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('OUM_VERSION', '1.1.4 by xam');
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'now'));
  require_once INC_PATH . '/functions_mkprettytime.php';
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

  if ($action == 'today')
  {
    headerr ('Who Was Online Today');
    echo '<tr class="subheader"><td width="40%" align="left">Username</td><td width="20%" align="center">Time</td><td width="40%" align="left">Last Location</td></tr>';
    $todaycount = 0;
    $stime = time () - 60 * 60 * 24;
    $todayrows = '';
    $query = sql_query ('' . '
		SELECT u.*, g.namestyle
		FROM users u
		LEFT JOIN usergroups g ON (g.gid=u.usergroup)
		WHERE UNIX_TIMESTAMP(u.last_access) > ' . $stime . '
		ORDER BY u.last_access DESC');
    while ($online = mysql_fetch_array ($query))
    {
      if (((!preg_match ('#B1#is', $online['options']) OR is_mod ($usergroups)) OR $online['id'] == $CURUSER['id']))
      {
        if (preg_match ('#B1#is', $online['options']))
        {
          $invisiblemark = '+';
        }
        else
        {
          $invisiblemark = '';
        }

        $username = $online['username'];
        $username = get_user_color ($username, $online['namestyle']);
        $onlinetime = $online['last_access'];
        echo '<tr><td><a href="' . $BASEURL . '/userdetails.php?id=' . $online['id'] . '">' . $username . '</a>' . $invisiblemark . '<br />Ip: ' . $online['ip'] . ' [<a href="' . $_this_script_ . '&action=iplookup&ip=' . $online['ip'] . '">iplookup</a>]</td><td align=center>' . my_datee ($timeformat, $onlinetime) . '</td><td> <a href="' . $BASEURL . $online['page'] . '">' . what_user_do ($online['page']) . '</a> </td></tr>' . $eol;
      }

      ++$todaycount;
    }

    echo '<tr><td colspan=3><strong>' . $todaycount . ' Members Were Online Today</strong></td></tr>';
    closee ();
    return 1;
  }

  if ($action == 'now')
  {
    headerr ('Who\'s Online - Registered Members');
    echo '<tr class="subheader"><td width="40%" align="left">Username</td><td width="20%" align="center">Time</td><td width="40%" align="left">Location</td></tr>';
    $nowcount = 0;
    $stime = TIMENOW - TS_TIMEOUT;
    $nowrows = '';
    $query = sql_query ('' . '
		SELECT distinct s.userid, u.*, g.namestyle
		FROM ts_sessions s
		LEFT JOIN users u ON (s.userid=u.id)
		LEFT JOIN usergroups g ON (g.gid=u.usergroup)
		WHERE s.userid != \'0\' AND s.lastactivity > ' . $stime . '
		ORDER BY s.lastactivity DESC');
    while ($online = mysql_fetch_array ($query))
    {
      if (((!preg_match ('#B1#is', $online['options']) OR is_mod ($usergroups)) OR $online['id'] == $CURUSER['id']))
      {
        if (preg_match ('#B1#is', $online['options']))
        {
          $invisiblemark = '*';
        }
        else
        {
          $invisiblemark = '';
        }

        $username = $online['username'];
        $username = get_user_color ($username, $online['namestyle']);
        $onlinetime = $online['last_access'];
        echo '<tr><td><a href="' . $BASEURL . '/userdetails.php?id=' . $online['id'] . '">' . $username . '</a>' . $invisiblemark . '<br />Ip: ' . $online['ip'] . ' [<a href="' . $_this_script_ . '&action=iplookup&ip=' . $online['ip'] . '">iplookup</a>]</td><td align=center>' . my_datee ($timeformat, $onlinetime) . '</td><td> <a href="' . $BASEURL . $online['page'] . '">' . what_user_do ($online['page']) . '</a> </td></tr>' . $eol;
        what_user_do ($online['page']);
      }

      ++$nowcount;
    }

    echo '
	<tr>
		<td colspan=3>
			<strong>' . $nowcount . ' user(s) active in the past ' . mkprettytime (TS_TIMEOUT) . '</strong>
		</td>
	</tr>
	<script type="text/javascript">
		var autorefreshtime = "0:30";
	</script>
	<script type="text/javascript" src="' . $BASEURL . '/scripts/autorefresh.js?v=' . O_SCRIPT_VERSION . '"></script>';
    closee (false);
    echo '<br />';
    headerr ('Who\'s Online - Guests', false, false);
    echo '<tr class="subheader"><td width="40%" align="left">User Agent</td><td width="20%" align="center">Time</td><td width="40%" align="left">Location</td></tr>';
    $nowcount = 0;
    $nowrows = '';
    $query = sql_query ('' . 'SELECT host, lastactivity, location, useragent FROM ts_sessions WHERE userid = \'0\' AND lastactivity > ' . $stime . ' ORDER BY lastactivity DESC');
    while ($online = mysql_fetch_array ($query))
    {
      $online['host'] = htmlspecialchars_uni ($online['host']);
      echo '
		<tr>
			<td>' . $online['useragent'] . '<br />Ip: ' . $online['host'] . ' [<a href="' . $_this_script_ . '&action=iplookup&ip=' . $online['host'] . '">iplookup</a>]</td><td align=center>' . my_datee ($timeformat, $online['lastactivity']) . '</td>
			<td> <a href="' . $BASEURL . $online['location'] . '">' . what_user_do ($online['location']) . '</a> </td>
		</tr>' . $eol;
      ++$nowcount;
    }

    echo '<tr><td colspan=3><strong>' . $nowcount . ' guest(s) active in the past ' . mkprettytime (TS_TIMEOUT) . '.</strong></td></tr>';
    closee ();
    return 1;
  }

  if ($action == 'iplookup')
  {
    $ip = $_GET['ip'];
    $host = @gethostbyaddr ($ip);
    $ip = htmlspecialchars ($ip);
    if ((!$host OR $host == $ip))
    {
      $ip = false;
    }

    headerr ('IP Address Lookup');
    if ($ip)
    {
      echo '<tr><td align=right>';
      echo 'IP:</td><td><a href="' . $BASEURL . '/admin/index.php?act=ipsearch&ip=' . $ip . '">' . $ip . '</a></td></tr>';
      echo '<tr><td align=right>Hostname: (if resolvable):</td> <td>' . $host . '</td></tr>';
    }
    else
    {
      echo '<tr><td><strong>Unable to detect IP host!</strong></td></tr>';
    }

    closee ();
  }

?>
