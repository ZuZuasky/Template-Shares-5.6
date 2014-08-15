<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  $rootpath = './../';
  $thispath = './';
  define ('SETTING_PANEL_TSSEv56', true);
  define ('STAFF_PANEL_TSSEv56', true);
  define ('TEMPLATESHARES', 'OOps');
  define ('SKIP_CRON_JOBS', true);
  define ('IN_SETTING_PANEL', true);
  require_once $rootpath . 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  if (($usergroups['cansettingspanel'] == 'no' OR $usergroups['cansettingspanel'] != 'yes'))
  {
    print_no_permission (true);
    exit ();
  }

  require_once $thispath . 'include/adminfunctions.php';
  if (!defined ('ADMIN_FUNCTIONS_TSSEv56'))
  {
    @stop_script (@base64_decode ('VGhlIGF1dGhlbnRpY2F0aW9uIGhhcyBiZWVuIGJsb2NrZWQgYmVjYXVzZSBvZiBpbnZhbGlkIGZpbGUgZGV0ZWN0ZWQh'));
  }

  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : ''));
  $gid = (isset ($_POST['gid']) ? intval ($_POST['gid']) : (isset ($_GET['gid']) ? intval ($_GET['gid']) : ''));
  check_pincode ();
  require_once $thispath . 'include/settingpanelfunctions.php';
  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    $note = trim ($_POST['note']);
    if ($fp = fopen ('adminnotes.txt', 'w'))
    {
      fwrite ($fp, $note);
      fclose ($fp);
    }
  }

  define ('WYSIWYG_EDITOR', true);
  define ('USE_BB_CODE', true);
  define ('USE_SMILIES', true);
  define ('USE_HTML', false);
  require $thispath . 'wysiwyg/wysiwyg.php';
  if (isset ($_GET['headframe']))
  {
    admin_cp_header ();
    table_open (NULL, false, 0, NULL, 0, '100%', '100%', NULL, 0, 0);
    echo '
		<tr align="center" valign="top">
			<td style="text-align:left"><b>' . O_VERSION . ' - Settings Panel ' . S_VERSION . ' by xam</b></td>
			<td style="white-space:nowrap; text-align:right; font-weight:bold">
				<a href="' . $_SERVER['SCRIPT_NAME'] . '">Setting Panel Home</a>
				|
				<a href="' . $BASEURL . '/admin/index.php" target="_blank">Staff Panel</a>
				|
				<a href="' . $BASEURL . '" target="_blank">Tracker</a>
				|
				<a href="' . $BASEURL . '/logout.php" onclick="return confirm(\'Are you sure you want to log out?\');"  target="_top">Log Out</a>
			</td>
		</tr>
	';
    table_close (false);
    admin_cp_footer ();
    exit ();
  }

  if (isset ($_GET['mainframe']))
  {
    admin_cp_header ();
    define ('SAPI_NAME', php_sapi_name ());
    $mysqlversion = mysql_result (sql_query ('SELECT VERSION() AS version'), 0, 'version');
    if ($variables = mysql_fetch_assoc (sql_query ('SHOW VARIABLES LIKE \'max_allowed_packet\'')))
    {
      $maxpacket = mksize ($variables['Value']);
    }
    else
    {
      $maxpacket = 'N/A';
    }

    if (preg_match ('#(Apache)/([0-9\\.]+)\\s#siU', $_SERVER['SERVER_SOFTWARE'], $wsregs))
    {
      $webserver = '' . $wsregs['1'] . ' v' . $wsregs['2'];
      if ((SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi'))
      {
        $addsapi = true;
      }
    }
    else
    {
      if (preg_match ('#Microsoft-IIS/([0-9\\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
      {
        $webserver = '' . 'IIS v' . $wsregs['1'];
        $addsapi = true;
      }
      else
      {
        if (preg_match ('#Zeus/([0-9\\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
        {
          $webserver = '' . 'Zeus v' . $wsregs['1'];
          $addsapi = true;
        }
        else
        {
          if (strtoupper ($_SERVER['SERVER_SOFTWARE']) == 'APACHE')
          {
            $webserver = 'Apache';
            if ((SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi'))
            {
              $addsapi = true;
            }
          }
          else
          {
            $webserver = SAPI_NAME;
          }
        }
      }
    }

    if ($addsapi)
    {
      $webserver .= ' (' . SAPI_NAME . ')';
    }

    $memorylimit = ini_get ('memory_limit');
    $serverinfo = PHP_OS;
    $phpversion = PHP_VERSION;
    $postmaxsize = (ini_get ('post_max_size') ? ini_get ('post_max_size') : 'N/A');
    $postmaxuploadsize = (ini_get ('upload_max_filesize') ? ini_get ('upload_max_filesize') : 'N/A');
    $serverload = get_server_load ();
    $totalusers = ts_nf (get_count ('totalusers', 'users', 'WHERE status=\'confirmed\''));
    $bannedusers = ts_nf (get_count ('bannedusers', 'users', 'WHERE status=\'confirmed\' AND (enabled=\'no\' OR usergroup=\'' . UC_BANNED . '\')'));
    $warnedusers = ts_nf (get_count ('warnedusers', 'users', 'WHERE status=\'confirmed\' AND (warned=\'yes\' OR leechwarn=\'yes\')'));
    $timecut = time () - 86400;
    $newuserstoday = ts_nf (get_count ('totalnewusers', 'users', 'WHERE UNIX_TIMESTAMP(added) > ' . sqlesc ($timecut)));
    $pendingusers = ts_nf (get_count ('pendingusers', 'users', 'WHERE status = \'pending\''));
    $todaycomments = ts_nf (get_count ('todaycomments', 'comments', 'WHERE UNIX_TIMESTAMP(added) > ' . sqlesc ($timecut)));
    $gd2support = (extension_loaded ('gd') ? '<font color=green>Enabled</font>' : '<font color=red>Disabled</font>');
    $sessionsupport = (function_exists ('session_save_path') ? '<font color=green>Enabled</font>' : '<font color=red>Disabled</font>');
    $todayvisits = ts_nf (get_count ('todayvisits', 'users', 'WHERE UNIX_TIMESTAMP(last_access) > ' . sqlesc ($timecut)));
    table_open ('Welcome to ' . O_VERSION . ' Settings Panel', true, 4);
    print_rows (array ('Server Type', 'Total Users'), array ($serverinfo . ' - ' . $webserver, $totalusers), '20%', '30%');
    print_rows (array ('PHP Version', 'New Users Today'), array ($phpversion, $newuserstoday), '20%', '30%');
    print_rows (array ('PHP Max Post Size', 'Unconfirmed Users'), array ($postmaxsize, $pendingusers), '20%', '30%');
    print_rows (array ('PHP Maximum Upload Size', 'Banned Users'), array ($postmaxuploadsize, $bannedusers), '20%', '30%');
    print_rows (array ('PHP Memory Limit', 'Warned Users'), array ($memorylimit, $warnedusers), '20%', '30%');
    print_rows (array ('MySQL Version', 'New Comments Today'), array ($mysqlversion, $todaycomments), '20%', '30%');
    print_rows (array ('MySQL Packet Size', 'Active Users Today'), array ($maxpacket, $todayvisits), '20%', '30%');
    table_close ();
    //no thanks table_open ('Important TS SE Links', true, 2);
    //print_rows ('TS SE Forums', '<a href="' . TEMPLATESHARES . '/tsf_forums/index.php" target="_blank">' . TEMPLATESHARES . '/tsf_forums/index.php</a>');
    //print_rows ('TS SE Support Desk', '<a href="' . TEMPLATESHARES . '/special/supportdesk.php" target="_blank">' . TEMPLATESHARES . '/special/supportdesk.php</a>');
    //print_rows ('TS SE Bug Report', '<a href="' . TEMPLATESHARES . '/special/bug_report.php" target="_blank">' . TEMPLATESHARES . '/special/bug_report.php</a>');
    //print_rows ('TS SE Tracker Registration', '<a href="' . TEMPLATESHARES . '/special/register_tracker.php" target="_blank">' . TEMPLATESHARES . '/special/register_tracker.php</a>');
    //print_rows ('TS SE Purchase Page', '<a href="' . TEMPLATESHARES . '/special/purchase.php" target="_blank">' . TEMPLATESHARES . '/special/purchase.php</a>');
    //print_rows ('TS SE Feature Request Page', '<a href="' . TEMPLATESHARES . '/special/feature_request.php" target="_blank">' . TEMPLATESHARES . '/special/feature_request.php</a>');
    //print_rows ('TS SE Code Request Page', '<a href="' . TEMPLATESHARES . '/special/code_request.php" target="_blank">' . TEMPLATESHARES . '/special/code_request.php</a>');
    //print_rows ('TS SE Theme Download', '<a href="' . TEMPLATESHARES . '/special/theme_download.php" target="_blank">' . TEMPLATESHARES . '/special/theme_download.php</a>');
    //print_rows ('TS SE Report Privacy', '<a href="' . TEMPLATESHARES . '/special/report_piracy.php" target="_blank">' . TEMPLATESHARES . '/special/report_piracy.php</a>');
    //table_close ();
    table_open ('Quick Administrator Links', true, 2);
    print_rows ('PHP Function Lookup', '
				<form action="http://www.ph' . 'p.net/manual-lookup.ph' . 'p" method="get" style="display:inline">
					<input type="text" class="bginput" name="function" size="30" tabindex="1" />
					<input type="submit" value=" Find " class="button" tabindex="1" />
				</form>');
    print_rows ('Usefull Website Links', '
				<form style="display:inline">
					<select onchange="if (this.options[this.selectedIndex].value != \'\') { window.open(this.options[this.selectedIndex].value); } return false;" tabindex="1" class="bginput">
						<option value="">-- Useful Links --</option>' . construct_select_options (array ('PHP' => array ('http://www.ph' . 'p.net/' => 'Home Page (PHP.net)', 'http://www.ph' . 'p.net/manual/' => 'Reference Manual', 'http://www.ph' . 'p.net/downloads.ph' . 'p' => 'Download Latest Version'), 'MySQL' => array ('http://www.mysql.com/' => 'Home Page (MySQL.com)', 'http://www.mysql.com/documentation/' => 'Reference Manual', 'http://www.mysql.com/downloads/' => 'Download Latest Version'), 'Apache' => array ('http://httpd.apache.org/' => 'Home Page (Apache.org)', 'http://httpd.apache.org/docs/' => 'Reference Manual', 'http://httpd.apache.org/download.cgi' => 'Download Latest Version'))) . '
					</select>
				</form>');
    table_close ();
    open_form ('adminnotes');
    table_open ('Administrator Notes', true, 1);
    print_rows ('
				<textarea name="note" id="note" style="width: 90%" rows="8">' . htmlspecialchars_uni (file_get_contents ('adminnotes.txt')) . '</textarea>', NULL, NULL, NULL, 'tdclass1', NULL, 'center');
    print_rows ('
				<input type="submit" value="save" class="button"> <input type="reset" value="reset" class="button">', NULL, NULL, NULL, 'tdclass1', NULL, 'center');
    table_close (false);
    close_form ();
    admin_cp_footer (true);
    exit ();
  }

  if (isset ($_GET['navframe']))
  {
    admin_cp_header ('<script src="' . $BASEURL . '/scripts/collapse.js?v=' . O_SCRIPT_VERSION . '" type="text/javascript"></script>');
    table_open ('<img src="./include/settingpanelfunctions.php?show_frame_logo=1" border="0" />', true, 1, NULL, 0);
    table_close (false);
    table_open (admin_collapse ('navframe') . ' GENERAL SETTINGS ' . admin_collapse ('navframe', 2), true, 1, 'thead', 1);
    make_nav_menu ('main', 'MAIN Settings');
    make_nav_menu ('announce', 'ANNOUNCE Settings');
    make_nav_menu ('signup', 'SIGNUP Settings');
    make_nav_menu ('database', 'DATABASE Settings');
    make_nav_menu ('smtp', 'SMTP Settings');
    make_nav_menu ('datetime', 'DATE & TIME Settings');
    make_nav_menu ('theme', 'THEME & LANGUAGE Settings');
    table_close ();
    table_open (admin_collapse ('navframe2a') . ' CRONJOB SETTINGS ' . admin_collapse ('navframe2a', 2), true, 1, 'thead', 1);
    make_nav_menu ('cleanup', 'Cleanup Settings');
    make_nav_menu ('cronjobs', 'Manage Cronjobs');
    make_nav_menu ('hitrun', 'Hit & Run Settings');
    table_close ();
    table_open (admin_collapse ('navframe2') . ' PERFORMANCE SETTINGS ' . admin_collapse ('navframe2', 2), true, 1, 'thead', 1);
    make_nav_menu ('tweak', 'TWEAK Settings');
    make_nav_menu ('extra', 'EXTRA Settings');
    table_close ();
    table_open (admin_collapse ('navframe3') . ' SECURITY SETTINGS ' . admin_collapse ('navframe3', 2), true, 1, 'thead', 1);
    make_nav_menu ('security', 'SECURITY Settings');
    make_nav_menu ('pincode', 'Setup Pincode');
    make_nav_menu ('staffteam', 'Manage Staff Team');
    table_close ();
    table_open (admin_collapse ('navframe4') . ' ADDITIONAL SETTINGS ' . admin_collapse ('navframe4', 2), true, 1, 'thead', 1);
    make_nav_menu ('seo', 'SEO Settings');
    make_nav_menu ('download', 'DOWNLOAD Settings');
    make_nav_menu ('waitslot', 'WAIT & SLOT Settings');
    make_nav_menu ('paypal', 'PAYMENT Settings');
    make_nav_menu ('kps', 'KPS & Gift Settings');
    make_nav_menu ('lottery', 'Lottery Settings');
    make_nav_menu ('freeleech', 'Automatic Free-Leech Settings');
    make_nav_menu ('pjirc', 'IRC Settings');
    make_nav_menu ('redirect', 'REDIRECT Settings');
    table_close ();
    table_open (admin_collapse ('navframe5') . ' ADMIN TOOLS ' . admin_collapse ('navframe5', 2), true, 1, 'thead', 1);
    make_nav_menu ('forumcp', 'Manage Forums');
    make_nav_menu ('usergroups', 'Manage Usergroups');
    make_nav_menu ('ts_plugins', 'Manage Plugins');
    make_nav_menu ('smilies', 'Manage Smilies');
    make_nav_menu ('chmod', 'Check Permissions');
    make_nav_menu ('filemanagement', 'File Management');
    make_nav_menu ('ts_update_cache', 'Update Cache');
    make_nav_menu ('ts_templates', 'Manage Templates');
    make_nav_menu ('quicklink', 'Quick Link Management');
    make_nav_menu ('ads', 'Advertisements');
    make_nav_menu ('trackerinfo', 'Tracker & Server Info');
    table_close ();
    table_open (admin_collapse ('navframe8') . ' DATABASE TOOLS ' . admin_collapse ('navframe8', 2), true, 1, 'thead', 1);
    make_nav_menu ('dbbackup', 'Database Backup', '<a href="' . $BASEURL . '/admin/index.php?act=ts_database" target="_blank">Database Backup</a>');
    make_nav_menu ('dboptimize', 'Optimize Tables');
    make_nav_menu ('dboptimize&type=repair', 'Repair Tables');
    make_nav_menu ('ts_execute_sql_query', 'Run SQL Query');
    table_close ();
    table_open (admin_collapse ('navframe8') . ' SHOUTCAST Settings ' . admin_collapse ('navframe8', 2), true, 1, 'thead', 1);
    make_nav_menu ('shoutcast', 'Shoutcast Settings');
    table_close ();
    table_open (admin_collapse ('navframe7') . ' TS SE ERROR LOGS ' . admin_collapse ('navframe7', 2), true, 1, 'thead', 1);
    make_nav_menu ('tracker_errors', 'Show Tracker Errors');
    make_nav_menu ('announce_errors', 'Show Announce Errors');
    make_nav_menu ('cron_errors', 'Show Cron Errors');
    table_close ();
    table_open (admin_collapse ('navframe6') . ' TS SE TOOLS ' . admin_collapse ('navframe6', 2), true, 1, 'thead', 1);
    make_nav_menu ('versioncheck', 'Version Check');
    make_nav_menu ('latestnews', 'Latest TS News');
    table_close ();
    admin_cp_footer ();
    exit ();
  }

  $navframe = '
<frame src="' . $_SERVER['SCRIPT_NAME'] . '?navframe" name="nav" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" />';
  $headframe = '
<frame src="' . $_SERVER['SCRIPT_NAME'] . '?headframe" name="head" scrolling="no" noresize="noresize" frameborder="0" marginwidth="10" marginheight="0" border="no" />';
  $mainframe = '
<frame src="' . $_SERVER['SCRIPT_NAME'] . '?mainframe" name="main" scrolling="yes" frameborder="0" marginwidth="10" marginheight="10" border="no" />';
  echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html dir="ltr" lang="en">
	<head>
		<script type="text/javascript">
			<!--
			if (self.parent.frames.length != 0)
			{
				self.parent.location.replace(document.location.href);
			}
			// -->
		</script>
		<title>' . O_VERSION . ' - Settings Panel</title>
	</head>
	<frameset cols="205,*"  framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
		' . $navframe . '
		<frameset rows="20,*"  framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
			' . $headframe . '
			' . $mainframe . '
		</frameset>
	</frameset>
	<noframes>
		<body>
			<p>I\'m sorry, but you need a frame enabled browser.</p>
		</body>
	</noframes>
</html>';
?>
