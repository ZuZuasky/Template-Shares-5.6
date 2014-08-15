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

  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  check_pincode ();
  require_once $thispath . 'include/settingpanelfunctions.php';
  admin_cp_header ('
<script src="' . $BASEURL . '/scripts/collapse.js?v=' . O_SCRIPT_VERSION . '" type="text/javascript"></script>');
  echo '<script src="' . $BASEURL . '/admin/templates/wz_tooltip.js?v=' . O_SCRIPT_VERSION . '" type="text/javascript"></script>';
  if (((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND (0 < count ($_POST['configoption']) OR strtoupper ($_POST['configname']) == 'STAFFTEAM')) AND !empty ($do)))
  {
    save_settings ($do);
  }

  define ('WYSIWYG_EDITOR', true);
  define ('USE_BB_CODE', true);
  define ('USE_SMILIES', true);
  define ('USE_HTML', false);
  require $thispath . 'wysiwyg/wysiwyg.php';
  if ((preg_match ('#forum(.*)#', $do) OR preg_match ('#forum(.*)#', ($_GET['action'] ? $_GET['action'] : $_POST['action']))))
  {
    table_open (admin_collapse ($do) . ' TS Manage Forums ' . admin_collapse ($do, 2));
    echo '
	<tr>
		<td class="tdclass1">
			<script type="text/javascript">
				function jumpto(url,message)
				{
					if (typeof message != "undefined")
					{
						document.getElementById("jumpto").style.display = "block"; 
					}
				window.location = url;
				};
			</script>
	';
    require $rootpath . '/admin/forumcp.php';
    echo '
		</td>
	</tr>';
    table_close (false);
    admin_cp_footer (true);
    exit ();
  }
  else
  {
    if (preg_match ('#ts_update_cache(.*)#', $do))
    {
      table_open (admin_collapse ($do) . ' TS Update Cache ' . admin_collapse ($do, 2));
      echo '
	<tr>
		<td class="tdclass1">
	';
      require $rootpath . '/admin/ts_update_cache.php';
      echo '
		</td>
	</tr>';
      table_close (false);
      admin_cp_footer (true);
      exit ();
    }
    else
    {
      if (preg_match ('#ts_plugins(.*)#', $do))
      {
        table_open (admin_collapse ($do) . ' TS Manage Plugins ' . admin_collapse ($do, 2));
        echo '
	<tr>
		<td class="tdclass1">
	';
        require $rootpath . '/admin/ts_plugins.php';
        echo '
		</td>
	</tr>';
        table_close (false);
        admin_cp_footer (true);
        exit ();
      }
      else
      {
        if (preg_match ('#ts_templates(.*)#', $do))
        {
          table_open (admin_collapse ($do) . ' TS Manage Templates ' . admin_collapse ($do, 2));
          echo '
	<tr>
		<td class="tdclass1">
			<script type="text/javascript">
				function jumpto(url,message)
				{
					if (typeof message != "undefined")
					{
						document.getElementById("jumpto").style.display = "block"; 
					}
				window.location = url;
				};
			</script>
	';
          require $rootpath . '/admin/ts_templates.php';
          echo '
		</td>
	</tr>';
          table_close (false);
          admin_cp_footer (true);
          exit ();
        }
        else
        {
          if (preg_match ('#group(.*)#', $do))
          {
            table_open (admin_collapse ($do) . ' TS Manage Usergroups ' . admin_collapse ($do, 2));
            echo '
	<tr>
		<td class="tdclass1">
			<script type="text/javascript">
				function jumpto(url,message)
				{
					if (typeof message != "undefined")
					{
						document.getElementById("jumpto").style.display = "block"; 
					}
				window.location = url;
				};
			</script>
	';
            require $rootpath . '/admin/usergroups.php';
            echo '
		</td>
	</tr>';
            table_close (false);
            admin_cp_footer (true);
            exit ();
          }
          else
          {
            if ($do == 'chmod')
            {
              $str = $str2 = '';
              $addfiles = $addfiles2 = $addfiles3 = array ();
              table_open (admin_collapse ($do) . ' TS Check Folder & File Permissions ' . admin_collapse ($do, 2));
              $folders = array ('admin/backup', 'cache', 'config', 'error_logs', 'include/avatars', 'torrents', 'torrents/images', 'tsf_forums/uploads');
              $files = array ('admin/adminnotes.txt', 'admin/ads.txt', 'admin/quicklinks.txt', 'include/config_announce.php', 'shoutcast/cache.xml', 'shoutcast/lps.dat');
              if ($handle = opendir ($rootpath . '/cache'))
              {
                while (false !== $file = readdir ($handle))
                {
                  if ((($file != '.' AND $file != '..') AND get_extension ($file) == 'php'))
                  {
                    $addfiles[] = 'cache/' . $file;
                    continue;
                  }
                }

                $files = array_merge ($files, $addfiles);
                closedir ($handle);
              }

              if ($handle = opendir ($rootpath . '/config'))
              {
                while (false !== $file = readdir ($handle))
                {
                  if ((($file != '.' AND $file != '..') AND get_extension ($file) == ''))
                  {
                    $addfiles2[] = 'config/' . $file;
                    continue;
                  }
                }

                $files = array_merge ($files, $addfiles2);
                closedir ($handle);
              }

              if ($handle = opendir ($rootpath . '/error_logs'))
              {
                while (false !== $file = readdir ($handle))
                {
                  if ((($file != '.' AND $file != '..') AND get_extension ($file) == 'php'))
                  {
                    $addfiles3[] = 'error_logs/' . $file;
                    continue;
                  }
                }

                $files = array_merge ($files, $addfiles3);
                closedir ($handle);
              }

              sort ($folders);
              sort ($files);
              foreach ($folders as $folder)
              {
                $str .= '
				<tr>
					<td align="left">' . $rootpath . $folder . '</td>
					<td align="center"><span style="color: ' . ((is_dir ($rootpath . $folder) AND is_writable ($rootpath . $folder)) ? 'green;">Writable' : 'red;">Not Writable') . '</span></td>
				</tr>
				';
              }

              unset ($folders);
              unset ($folder);
              foreach ($files as $file)
              {
                $str2 .= '
				<tr>
					<td align="left">' . $rootpath . $file . '</td>
					<td align="center"><span style="color: ' . ((is_file ($rootpath . $file) AND is_writable ($rootpath . $file)) ? 'green;">Writable' : 'red;">Not Writable') . '</span></td>
				</tr>
				';
              }

              unset ($files);
              unset ($file);
              echo '
	<tr>
		<td class="none" align="center" valign="top">
			<table width="600" cellpadding="3" cellspacing="0" border="1">
				<tr>
					<td class="subheader" width="70%" align="left">File Name</td>
					<td class="subheader" width="30%" align="center">Is Writable?</td>
				</tr>
				' . $str2 . '
			</table>	
		</td>
		<td class="none" align="center" valign="top">
			<table width="600" cellpadding="3" cellspacing="0" border="1">
				<tr>
					<td class="subheader" width="70%" align="left">Folder Name</td>
					<td class="subheader" width="30%" align="center">Is Writable?</td>
				</tr>
				' . $str . '
			</table>	
		</td>		
	</tr>
	';
              table_close (false);
              admin_cp_footer (true);
              exit ();
            }
            else
            {
              if ($do == 'ts_execute_sql_query')
              {
                table_open (admin_collapse ($do) . ' TS Run SQL Query Tool ' . admin_collapse ($do, 2));
                echo '
	<tr>
		<td class="tdclass1">';
                require $rootpath . '/admin/ts_execute_sql_query.php';
                echo '
		</td>
	</tr>';
                table_close (false);
                admin_cp_footer (true);
                exit ();
              }
              else
              {
                if ($do == 'reset_funds')
                {
                  sql_query ('TRUNCATE TABLE `funds`');
                  unset ($_COOKIE[ts_psf]);
                  @setcookie ('ts_psf', '0', TIMENOW, '/');
                  $name = 'funds';
                  $contents = array ('funds_so_far' => 0);
                  $filename = TSDIR . ('' . '/' . $cache . '/' . $name . '.php');
                  $cachefile = @fopen ('' . $filename, 'w');
                  $cachecontents = '' . '<?php

/** TS Generated Cache - Do Not Alter
 * Cache Name: ' . $name . '
 * Generated: ' . gmdate ('r') . '
*/

';
                  $cachecontents .= ('' . '$') . $name . ' = ' . @var_export ($contents, true) . ';

?>';
                  @fwrite ($cachefile, $cachecontents);
                  @fclose ($cachefile);
                  admin_cp_redirect ('paypal', 'Funds Table has been reset.');
                  exit ();
                }
                else
                {
                  if ($do == 'filemanagement')
                  {
                    include 'tssebeditor.php';
                    admin_cp_footer (true);
                    exit ();
                  }
                  else
                  {
                    if ($do == 'image_test')
                    {
                      table_open (admin_collapse ($do) . ' Image Verification Test Script ' . admin_collapse ($do, 2), 2);
                      echo '<tr><td>';
                      include 'image_test.php';
                      echo '</td></tr>';
                      echo '<tr><td>
	GD2<br />
	<img src="' . $BASEURL . '/include/class_tscaptcha.php?width=100&height=30" id="regimage" border="0" alt="" /><br />GD<br /><img src="' . $BASEURL . '/include/class_tscaptcha.php?width=100&height=30&type=2" id="regimage" border="0" alt="" /></td></tr>';
                      table_close (false);
                      admin_cp_footer (true);
                      exit ();
                    }
                    else
                    {
                      if ($do == 'dboptimize')
                      {
                        table_open (admin_collapse ($do) . ' Database Optimization ' . admin_collapse ($do, 2));
                        echo '<tr><td>';
                        include 'optimizetables.php';
                        echo '</td></tr>';
                        table_close (false);
                        admin_cp_footer (true);
                        exit ();
                      }
                      else
                      {
                        if ($do == 'quicklink')
                        {
                          if ($_SERVER['REQUEST_METHOD'] == 'POST')
                          {
                            $quicklinks = $_POST['quicklinks'];
                            $saved = file_put_contents ('./quicklinks.txt', $quicklinks);
                            $msg = 'QuickLinks has been saved!';
                            admin_cp_redirect ($do);
                            exit ();
                          }

                          $quicklinks = file_get_contents ('./quicklinks.txt');
                          table_open (admin_collapse ($do) . ' Manage Quick Links for Staff Leader ' . admin_collapse ($do, 2), 2);
                          print_rows ('QuickLinks', show_helptip ('' . 'Saperate links with ,<br />Note: use ' . $BASEURL . ' value to get server url (see below).<br />Use { } to write link description (see below).<br />Example: ' . $BASEURL . '/admin/index.php?act=log{Show Logs},' . $BASEURL . '/admin/index.php?act=ipcheck{Check Ip}') . '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=quicklink">
	<input type="hidden" name="do" value="quicklink">
	<textarea rows="20" cols="110" name="quicklinks">' . $quicklinks . '</textarea>
	');
                          print_submit_rows ();
                          close_form ();
                          table_close (false);
                          admin_cp_footer (true);
                          exit ();
                        }
                        else
                        {
                          if ($do == 'ads')
                          {
                            if ($_SERVER['REQUEST_METHOD'] == 'POST')
                            {
                              $ads = $_POST['ads'];
                              $saved = file_put_contents ('./ads.txt', $ads);
                              $msg = 'Advertisements has been saved!';
                              admin_cp_redirect ($do);
                              exit ();
                            }

                            $ads = file_get_contents ('./ads.txt');
                            table_open (admin_collapse ($do) . ' Manage Advertisements ' . admin_collapse ($do, 2), 2);
                            print_rows ('Advertisement Code(s):', show_helptip ('HTML allowed. BBCODE disabled. <br />To enable auto banner rotator, separete ads with [TS_ADS]<br />Example: adscode1[TS_ADS]adscode2[TS_ADS]adscode3<br />Leave it blank to disable this feature.') . '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=ads">
	<input type="hidden" name="do" value="ads">
	<textarea rows="30" cols="150" name="ads">' . $ads . '</textarea>
	');
                            print_submit_rows ();
                            close_form ();
                            table_close (false);
                            admin_cp_footer (true);
                            exit ();
                          }
                          else
                          {
                            if ($do == 'trackerinfo')
                            {
                              $latest_php = ts_get_url_contents ('http://www.php.net/downloads.php');
                              $find_php = preg_match_all ('#<h1>PHP [0-9+]\\.[0-9+]\\.[0-9+]</h1>#U', $latest_php, $output, PREG_SET_ORDER);
                              $latest_mysql = ts_get_url_contents ('http://dev.mysql.com/downloads/mysql/5.0.html');
                              $find_mysql = preg_match_all ('#<td>[0-9+]+\\.[0-9+]+\\.[0-9+]+<\\/td>#U', $latest_mysql, $output2, PREG_SET_ORDER);
                              $latest_apache = str_replace (array ('
', '
'), '', ts_get_url_contents ('http://httpd.apache.org/download.cgi'));
                              $find_apache = preg_match_all ('#<strong>Apache HTTP Server [0-9+]\\.[0-9+]\\.[0-9+] is the best available version</strong>#U', $latest_apache, $output3, PREG_SET_ORDER);
                              $phpversion = PHP_VERSION;
                              $mysqlversion = mysql_result (sql_query ('SELECT VERSION() AS version'), 0, 'version');
                              $serverload = get_server_load ();
                              $totalusers = get_count ('totalusers', 'users', 'WHERE status=\'confirmed\'');
                              $timecut = time () - 86400;
                              $newuserstoday = get_count ('totalnewusers', 'users', 'WHERE UNIX_TIMESTAMP(added) > ' . sqlesc ($timecut));
                              $pendingusers = get_count ('pendingusers', 'users', 'WHERE status = \'pending\'');
                              $todaycomments = get_count ('todaycomments', 'comments', 'WHERE UNIX_TIMESTAMP(added) > ' . sqlesc ($timecut));
                              $gd2support = (extension_loaded ('gd') ? '<font color=green>Enabled</font>' : '<font color=red>Disabled</font>');
                              $sessionsupport = (function_exists ('session_save_path') ? '<font color=green>Enabled</font>' : '<font color=red>Disabled</font>');
                              $todayvisits = get_count ('todayvisits', 'users', 'WHERE UNIX_TIMESTAMP(last_access) > ' . sqlesc ($timecut));
                              table_open (admin_collapse ($do) . ' Tracker & Server Info ' . admin_collapse ($do, 2), true, 4);
                              echo '	
		<tr>
			<td class="tdclass1"><div align="left"><b>PHP Version</b></div></td>
			<td class="tdclass2"><div align="left">' . $phpversion . '</div></td>
			<td class="tdclass1"><div align="left"><b>Total Users</b></div></td>
			<td class="tdclass2"><div align="left">' . $totalusers . '</div></td>
		</tr>
		<tr>
			<td class="tdclass1"><div align="left"><b>MYSQL Version</b></div></td>
			<td class="tdclass2"><div align="left">' . $mysqlversion . '</div></td>
			<td class="tdclass1"><div align="left"><b>New Users Today</b></div></td>
			<td class="tdclass2"><div align="left">' . $newuserstoday . '</div></td>
		</tr>
		<tr>
			<td class="tdclass1"><div align="left"><b>GD2 Support</b></div></td>
			<td class="tdclass2"><div align="left">' . $gd2support . '</div></td>
			<td class="tdclass1"><div align="left"><b>Unconfirmed Users</b></div></td>
			<td class="tdclass2"><div align="left">' . $pendingusers . '</div></td>
		</tr>
		<tr>
			<td class="tdclass1"><div align="left"><b>Server Load</b></div></td>
			<td class="tdclass2"><div align="left">' . $serverload . '</div></td>
			<td class="tdclass1"><div align="left"><b>New Comments Today</b></div></td>
			<td class="tdclass2"><div align="left">' . $todaycomments . '</div></td>
		</tr>
		<tr>
			<td class="tdclass1"><div align="left"><b>Session Support</b></div></td>
			<td class="tdclass2"><div align="left">' . $sessionsupport . '</div></td>
			<td class="tdclass1"><div align="left"><b>Active Users Today</b></div></td>
			<td class="tdclass2"><div align="left">' . $todayvisits . '</div></td>
		</tr>
		<tr><td colspan="4" class="tdclass1">Latest Apache Version available at http://httpd.apache.org: ' . $output3[0][0] . '</td></tr>
		<tr><td colspan="4" class="tdclass1">Latest PHP Version(s) available at www.php.net:<b> ' . str_replace (array ('<h1>', '</h1>'), '', $output[0][0]) . ' ' . str_replace (array ('<h1>', '</h1>'), '', $output[1][0]) . '</b></td></tr>
		<tr><td colspan="4" class="tdclass1">Latest MYSQL Version available at www.mysql.com: <b>' . str_replace (array ('<td>', '</td>'), '', $output2[0][0]) . '</b></td></tr>
	';
                              table_close (false);
                              admin_cp_footer (true);
                              exit ();
                            }
                            else
                            {
                              if ($do == 'versioncheck')
                              {
                                if (T_VERSION)
                                {
                                  $remote = 'OOps';
                                  $results = @file ($remote);
                                  if (!$results)
                                  {
                                    $info = '<font color=red><b>There was a problem communicating with the version server. Because we dont want to :)</b></font>';
                                  }
                                  else
                                  {
                                    foreach ($results as $version)
                                    {
                                      if ($version < T_VERSION)
                                      {
                                        $info = '<font color=darkred><b>You are currently using illegal or fake version of TS Special Edition. We already know that though xam!</b></font></div>';
                                        continue;
                                      }
                                      else
                                      {
                                        if ((T_VERSION == $version OR $version <= T_VERSION))
                                        {
                                          $info = '<font color=green><b>You are currently using the Nulled version of TS Special Edition.</b></font></div>';
                                          continue;
                                        }
                                        else
                                        {
                                          $info = '<b><font color=red>No updates on this Nulled version Sorry pal</b></div>';
                                          continue;
                                        }

                                        continue;
                                      }
                                    }
                                  }

                                  $str = '<b>Here you can tell if your TS SE is up-to-date.<h3></h3></b>';
                                  $str .= '
		<div align="justify">Your version of TS Special Edition: <b>' . T_VERSION . '&nbsp; Nulled by Nightcrawler</b><br />
		Latest version of TS Special Edition: <b>' . ($version ? $version : 'Error') . '</b><br /><br />';
                                  $str .= $info;
                                  table_open (admin_collapse ($do) . ' Version Check ' . admin_collapse ($do, 2));
                                  echo '<tr><td class="tdclass1">' . $str . '</td></tr>';
                                  table_close (false);
                                  admin_cp_footer (true);
                                  exit ();
                                }
                              }
                              else
                              {
                                if ($do == 'latestnews')
                                {
                                  $str = '<b><font color=green>Latest News From TS</font></b><h3></h3></b>';
                                  $remote = 'oops';
                                  $results = @file ($remote);
                                  if (!$results)
                                  {
                                    $str .= '<font color=red><b>There was a problem communicating with the version server. Cos we dont want to!</b></font>';
                                  }
                                  else
                                  {
                                    foreach ($results as $news)
                                    {
                                      $str .= $news;
                                    }
                                  }

                                  table_open (admin_collapse ($do) . ' Latest News ' . admin_collapse ($do, 2));
                                  echo '<tr><td class="tdclass1">' . $str . '</td></tr>';
                                  table_close (false);
                                  admin_cp_footer (true);
                                  exit ();
                                }
                                else
                                {
                                  if ($do == 'smilies')
                                  {
                                    $SmilieDir = $rootpath . $pic_base_url . 'smilies';
                                    if ($_GET['action'] == 'new')
                                    {
                                      if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
                                      {
                                        $stitle = htmlspecialchars_uni ($_POST['stitle']);
                                        $stext = htmlspecialchars_uni ($_POST['stext']);
                                        $spath = htmlspecialchars_uni ($_POST['spath']);
                                        $sorder = 0 + $_POST['sorder'];
                                        if (((!$stitle OR !$stext) OR !$spath))
                                        {
                                          $error = 'Please fill required details!';
                                        }
                                        else
                                        {
                                          if (!file_exists ($SmilieDir . '/' . $spath))
                                          {
                                            $error = 'This smilie does not exists!';
                                          }
                                          else
                                          {
                                            sql_query ('INSERT INTO ts_smilies (stitle, stext, spath, sorder) VALUES (' . sqlesc ($stitle) . ', ' . sqlesc ($stext) . ', ' . sqlesc ($spath) . ('' . ', \'' . $sorder . '\')'));
                                            update_smilies_cache ();
                                            $message = 'Smilie has been added!';
                                            $do = 'smilies';
                                            admin_cp_redirect ($do, $message);
                                            exit ();
                                          }
                                        }
                                      }

                                      table_open (admin_collapse ($do) . ' Manage Smilies - Add New Smilie ' . admin_collapse ($do, 2), true, 1);
                                      if ($error)
                                      {
                                        echo '<td class="subheader"><font color="red">' . $error . '</font></td></tr><tr>';
                                      }

                                      echo '
		<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=new">
		<td>
			<fieldset>
				<legend>Title:</legend>
				<input type="text" size="20" value="' . ($stitle ? $stitle : '') . '" name="stitle" />
			</fieldset>
			<fieldset>
				<legend>Text to Replace:</legend>
				<input type="text" size="20" value="' . ($stext ? $stext : '') . '" name="stext" />
			</fieldset>
			<fieldset>
				<legend>Image:</legend>
				<input type="text" size="20" value="' . ($spath ? $spath : '') . '" name="spath" /> <img src="' . $SmilieDir . '/' . ($spath ? $spath : '') . '" alt="' . ($stitle ? $stitle : '') . '" class="inlineimg" border="0">
			</fieldset>
			<fieldset>
				<legend>Display Order:</legend>
				<input type="text" size="5" value="' . ($sorder ? $sorder : 1) . '" name="sorder" />
			</fieldset>
			<input type="submit" value="save" />
		</td>			
		</form>
		';
                                      table_close (false);
                                      admin_cp_footer (true);
                                      exit ();
                                    }

                                    if ($_GET['action'] == 'edit')
                                    {
                                      $sid = intval ($_GET['sid']);
                                      $query = sql_query ('' . 'SELECT stitle, stext, spath, sorder FROM ts_smilies WHERE sid = \'' . $sid . '\'');
                                      if (mysql_num_rows ($query) == 0)
                                      {
                                        exit ('Invalid Smilie ID!');
                                      }

                                      $sarray = mysql_fetch_assoc ($query);
                                      if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
                                      {
                                        $stitle = htmlspecialchars_uni ($_POST['stitle']);
                                        $stext = htmlspecialchars_uni ($_POST['stext']);
                                        $spath = htmlspecialchars_uni ($_POST['spath']);
                                        $sorder = 0 + $_POST['sorder'];
                                        if (((!$stitle OR !$stext) OR !$spath))
                                        {
                                          $error = 'Please fill required details!';
                                        }
                                        else
                                        {
                                          if (!file_exists ($SmilieDir . '/' . $spath))
                                          {
                                            $error = 'This smilie does not exists!';
                                          }
                                          else
                                          {
                                            sql_query ('UPDATE ts_smilies SET stitle = ' . sqlesc ($stitle) . ', stext = ' . sqlesc ($stext) . ', spath = ' . sqlesc ($spath) . ('' . ', sorder = \'' . $sorder . '\' WHERE sid = \'' . $sid . '\''));
                                            update_smilies_cache ();
                                            $message = 'Smilie has been updated!';
                                            $do = 'smilies';
                                            admin_cp_redirect ($do, $message);
                                            exit ();
                                          }
                                        }
                                      }

                                      table_open (admin_collapse ($do) . ' Manage Smilies - Edit Smilie ' . admin_collapse ($do, 2), true, 1);
                                      if ($error)
                                      {
                                        echo '<td class="subheader"><font color="red">' . $error . '</font></td></tr><tr>';
                                      }

                                      echo '
		<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=edit&sid=' . intval ($_GET['sid']) . '">
		<td>
			<fieldset>
				<legend>Title:</legend>
				<input type="text" size="20" value="' . ($stitle ? $stitle : $sarray['stitle']) . '" name="stitle" />
			</fieldset>
			<fieldset>
				<legend>Text to Replace:</legend>
				<input type="text" size="20" value="' . ($stext ? $stext : $sarray['stext']) . '" name="stext" />
			</fieldset>
			<fieldset>
				<legend>Image:</legend>
				<input type="text" size="20" value="' . ($spath ? $spath : $sarray['spath']) . '" name="spath" /> <img src="' . $SmilieDir . '/' . ($spath ? $spath : $sarray['spath']) . '" alt="' . ($stitle ? $stitle : $sarray['stitle']) . '" class="inlineimg" border="0">
			</fieldset>
			<fieldset>
				<legend>Display Order:</legend>
				<input type="text" size="5" value="' . ($sorder ? $sorder : $sarray['sorder']) . '" name="sorder" />
			</fieldset>
			<input type="submit" value="save" />
		</td>			
		</form>
		';
                                      table_close (false);
                                      admin_cp_footer (true);
                                      exit ();
                                    }

                                    if (($_GET['action'] == 'delete' AND is_valid_id ($_GET['sid'])))
                                    {
                                      sql_query ('DELETE FROM ts_smilies WHERE sid = ' . intval ($_GET['sid']));
                                      update_smilies_cache ();
                                    }

                                    if ($_GET['action'] == 'update_sorder')
                                    {
                                      if (is_array ($_POST['sorder']))
                                      {
                                        foreach ($_POST['sorder'] as $sid => $sorder)
                                        {
                                          if (is_valid_id ($sid))
                                          {
                                            $sorder = 0 + $sorder;
                                            sql_query ('' . 'UPDATE ts_smilies SET sorder = \'' . $sorder . '\' WHERE sid = \'' . $sid . '\'');
                                            continue;
                                          }
                                        }

                                        update_smilies_cache ();
                                      }
                                    }

                                    $query = sql_query ('SELECT sid, stitle, stext, spath, sorder FROM ts_smilies ORDER BY sorder, stitle');
                                    while ($Sa = mysql_fetch_assoc ($query))
                                    {
                                      $SimilieArray[] = '<b>' . $Sa['stitle'] . '</b><br><br><img src="' . $SmilieDir . '/' . $Sa['spath'] . '" alt="' . $Sa['stitle'] . '" class="inlineimg" border="0"><br> <span class="smallfont">' . $Sa['stext'] . '</span><br> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=edit&sid=' . $Sa['sid'] . '">[Edit]</a>  <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=delete&sid=' . $Sa['sid'] . '">[Delete]</a> <input type="text" size="2" name="sorder[' . $Sa['sid'] . ']" value="' . $Sa['sorder'] . '" />';
                                    }

                                    table_open (admin_collapse ($do) . ' Manage Smilies ' . admin_collapse ($do, 2), true, 6);
                                    echo '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=update_sorder">
	<td class="subheader" colspan="6" align="center"><a href="' . $_SERVER['SCRIPT_NAME'] . '?do=smilies&action=new">[Add a New Smilie]</a> - <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_update_cache">[Update Smilies Cache]</a></td>';
                                    $count = 0;
                                    foreach ($SimilieArray as $showsmilie)
                                    {
                                      if ($count % 6 == 0)
                                      {
                                        echo '</tr><tr>';
                                      }

                                      echo '
		<td>' . $showsmilie . '</td>
		';
                                      ++$count;
                                    }

                                    echo '	
	</tr>
	<tr>
		<td colspan="6" class="subheader" align="center"><input type="submit" value="Save Display Order" /></td>
	</tr>
	</form>';
                                    table_close (false);
                                    admin_cp_footer (true);
                                    exit ();
                                  }
                                  else
                                  {
                                    if ((($do == 'tracker_errors' OR $do == 'announce_errors') OR $do == 'cron_errors'))
                                    {
                                      $Title = ($do == 'tracker_errors' ? 'Tracker' : ($do == 'announce_errors' ? 'Announce' : 'Cron'));
                                      table_open (admin_collapse ($do) . ' Show ' . $Title . ' Errors Log ' . admin_collapse ($do, 2), true, 6);
                                      $LogFile = TSDIR . '/error_logs/' . ($do == 'tracker_errors' ? 'tracker' : ($do == 'announce_errors' ? 'announce' : 'cron')) . '_error_logs.php';
                                      if ((isset ($_GET['emptylog']) AND is_writable ($LogFile)))
                                      {
                                        $FP = fopen ($LogFile, 'w');
                                        fwrite ($FP, '');
                                        fclose ($FP);
                                      }

                                      if (!file_exists ($LogFile))
                                      {
                                        echo '<tr><td>Can\'t read the log file. (' . $LogFile . ')</td></tr>';
                                      }
                                      else
                                      {
                                        if (!$ErrorLogs = @file ($LogFile, FILE_SKIP_EMPTY_LINES))
                                        {
                                          echo '<tr><td colspan="5">There is no error log to show!</td></tr>';
                                        }
                                        else
                                        {
                                          echo '
		<tr>
			<td class="subheader">Date</td>
			<td class="subheader">Error</td>
			<td class="subheader">File</td>
			<td class="subheader">Line</td>
			<td class="subheader">URL</td>
		</tr>
			';
                                          foreach ($ErrorLogs as $Line)
                                          {
                                            $errors = explode ('|', $Line);
                                            echo '
			<tr>
				<td>' . my_datee ($dateformat, $errors[0]) . ' ' . my_datee ($timeformat, $errors[0]) . '</td>
				<td>' . htmlspecialchars_uni ($errors[1]) . ': ' . htmlspecialchars_uni (base64_decode ($errors[2])) . '</td>
				<td>' . htmlspecialchars_uni ($errors[3]) . '</td>
				<td>' . ts_nf ($errors[4]) . '</td>
				<td>' . htmlspecialchars_uni ($errors[5]) . '</td>
			</tr>
			';
                                          }

                                          echo '<tr><td colspan="7" align="center">' . frame_link (($do == 'tracker_errors' ? 'tracker' : ($do == 'announce_errors' ? 'announce' : 'cron')) . '_errors&emptylog=true', 'Empty Log File') . '</td></tr>';
                                        }
                                      }

                                      table_close (false);
                                      admin_cp_footer (true);
                                      exit ();
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  open_form ($do);
  table_open (admin_collapse ($do) . ' ' . strtoupper ($do) . ' Settings ' . admin_collapse ($do, 2), 2);
  switch ($do)
  {
    case 'main':
    {
      print_rows ('Site Online?', show_helptip ('From time to time, you may want to turn your tracker off to the public while you perform maintenance, update versions, etc. When you turn your forum off, visitors will receive a message that states that the tracker is temporarily unavailable. Administrators will still be able to see the tracker.') . ' 
		<select class="bginput" name="configoption[site_online]">
						<option value="yes"' . iif ($SITE_ONLINE == 'yes', ' selected="selected"') . '>Online</option>
						<option value="no"' . iif ($SITE_ONLINE == 'no', ' selected="selected"') . '>Offline</option>
					</select>');
      print_rows ('Active Ajax Features?', show_helptip ('AJAX uses javascript and features of recent browsers to allow additional data to be retrieved without doing a page refresh, such as posting with quick reply or editing a thread title inline.<br /><br />These features may cause problems with tracker not running in English. You may use this setting to disable same AJAX features.') . ' 
		<select class="bginput" name="configoption[useajax]">
						<option value="yes"' . iif ($useajax == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($useajax == 'no', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Active External Scrape?', show_helptip ('Users can also upload torrents tracked by other public trackers!') . ' 
		<select class="bginput" name="configoption[externalscrape]">
						<option value="yes"' . iif ($externalscrape == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($externalscrape == 'no', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Include External Peers?', show_helptip ('Include External Peers to Internal Peers which will update stats automaticly.') . ' 
		<select class="bginput" name="configoption[includeexpeers]">
						<option value="yes"' . iif ($includeexpeers == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($includeexpeers == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Registered Members Only?', show_helptip ('If you set this to \\\'NO\\\', guests can acces to some pages such as index, browse, forums etc..') . ' 
		<select class="bginput" name="configoption[MEMBERSONLY]">
						<option value="yes"' . iif ($MEMBERSONLY == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($MEMBERSONLY == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Active Wait System Limitations?', show_helptip ('Once a new torrent uploaded, user must have wait to allow download it. You can configure this feature by clicking <a href=' . $_SERVER['SCRIPT_NAME'] . '?do=wait_slot&amp;sessionhash=' . session_id () . '&amp;tshash=' . $_SESSION['hash'] . '><font color=red>here<\\/font><\\/a>.') . ' 
		<select class="bginput" name="configoption[waitsystem]">
						<option value="yes"' . iif ($waitsystem == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($waitsystem == 'no', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Active Max. Concurrent Download Limitations?', show_helptip ('Active this feature to set maximum slot usage at a same time. You can configure this feature by clicking <a href=' . $_SERVER['SCRIPT_NAME'] . '?do=wait_slot&amp;sessionhash=' . session_id () . '&amp;tshash=' . $_SESSION['hash'] . '><font color=red>here<\\/font><\\/a>.') . ' 
		<select class="bginput" name="configoption[maxdlsystem]">
						<option value="yes"' . iif ($maxdlsystem == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($maxdlsystem == 'no', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Show Latest Torrents?', show_helptip ('Show Latest Torrents on Login Page.') . ' 
		<select class="bginput" name="configoption[showlastxtorrents]">						
						<option value="no"' . iif ($showlastxtorrents == 'no', ' selected="selected"') . '>No</option>						
						<option value="multi"' . iif ($showlastxtorrents == 'multi', ' selected="selected"') . '>Yes</option>
					</select> Limit: <input type="text" size="2" name="configoption[i_torrent_limit]" value="' . $i_torrent_limit . '" class="bginput">');
      print_rows ('Show Latest Torrents With Images?', show_helptip ('Show Latest Torrents on Index/Login Page with Images.') . ' 
		<select class="bginput" name="configoption[showimages]">
						<option value="yes"' . iif ($showimages == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($showimages == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Tracker Name', show_helptip ('Enter your Tracker name here.') . '
		<input type="text" size="50" name="configoption[SITENAME]" value="' . $SITENAME . '" class="bginput">');
      print_rows ('Announce URL', show_helptip ('Enter your announce URL here. ie: http://yourwebsiteurl.com/announce.php') . '
		<input type="text" size="50" name="configoption[announce_urls]" value="' . $announce_urls[0] . '" class="bginput">');
      print_rows ('BASE URL', show_helptip ('Enter your tracker URL here. ie: http://yourwebsiteurl.com<br />Note: NO a trailing slash (/) at the end!') . '
		<input type="text" size="50" name="configoption[BASEURL]" value="' . $BASEURL . '" class="bginput">');
      print_rows ('Site E-MAIL', show_helptip ('Enter your tracker contact email here. ie: contact@sitename.com') . '
		<input type="text" size="50" name="configoption[SITEEMAIL]" value="' . $SITEEMAIL . '" class="bginput">');
      print_rows ('Report E-MAIL', show_helptip ('Enter your tracker report email here. ie: report@sitename.com') . '
		<input type="text" size="50" name="configoption[reportemail]" value="' . $REPORTMAIL . '" class="bginput">');
      print_rows ('Contact E-MAIL(s)', show_helptip ('Enter your tracker contact E-mail addresses here. They will be used on Contact Us Page. Saperate multiple E-mails with ,<br /><br />Example: contacus@sitename.com,john@hotmail.com') . '
		<input type="text" size="50" name="configoption[contactemail]" value="' . $contactemail . '" class="bginput">');
      print_rows ('Torrent Directory Path?', show_helptip ('Enter Tracker Torrent Directory Path.<br />Note: NO a trailing slash (/) at the end!') . '
		<input type="text" size="50" name="configoption[torrent_dir]" value="' . $torrent_dir . '" class="bginput">');
      print_rows ('Image Directory Path?', show_helptip ('Enter Tracker Image Directory Path.<br />Note: ADD a trailing slash (/) at the end!') . '
		<input type="text" size="50" name="configoption[pic_base_url]" value="' . $pic_base_url . '" class="bginput">');
      print_rows ('Category Directory Path?', show_helptip ('Enter Tracker Category Directory Path.<br />Note: NO a trailing slash (/) at the end!') . '
		<input type="text" size="50" name="configoption[table_cat]" value="' . $table_cat . '" class="bginput">');
      print_rows ('Cache Directory Path', show_helptip ('Enter Tracker Cache Directory Path. <b>Do not forget to CHMOD 0777 this path.</b><br />Note: NO a trailing slash (/) at the end!') . '
		<input type="text" size="50" name="configoption[cache]" value="' . $cache . '" class="bginput">');
      print_rows ('Max. characters Limit?', show_helptip ('Max. characters limit for User Signatures and Info.') . '
		<input type="text" size="10" name="configoption[maxchar]" value="' . $maxchar . '" class="bginput">');
      print_rows ('Max. Torrent Size?', show_helptip ('Max. Torrent Limit for Upload. ' . 10 * 1024 * 1024 . ' (10 gb)') . '
		<input type="text" size="10" name="configoption[max_torrent_size]" value="' . $max_torrent_size . '" class="bginput">');
      print_submit_rows ();
      break;
    }

    case 'database':
    {
      print_rows ('MYSQL Host?', show_helptip ('Please enter your mysql host name here.') . '
		<input type="text" size="30" name="configoption[mysql_host]" value="' . $mysql_host . '" class="bginput">');
      print_rows ('MYSQL User?', show_helptip ('Please enter your mysql user name here.') . '
		<input type="text" size="30" name="configoption[mysql_user]" value="' . $mysql_user . '" class="bginput">');
      print_rows ('MYSQL Password?', show_helptip ('Please enter your mysql password here.') . '
		<input type="password" size="30" name="configoption[mysql_pass]" value="" class="bginput">');
      print_rows ('MYSQL Database Name?', show_helptip ('Please enter your mysql database name here.') . '
		<input type="text" size="30" name="configoption[mysql_db]" value="' . $mysql_db . '" class="bginput">');
      print_submit_rows ();
      break;
    }

    case 'smtp':
    {
      echo '
		<script type="text/javascript">
			function change_div(WhatSelected)
			{
				if (WhatSelected == 0)
				{
					document.getElementById("smtp_setting_0").style.display = "block";
					document.getElementById("smtp_setting_1").style.display = "none";
					document.getElementById("smtp_setting_2").style.display = "none";
				}
				else if (WhatSelected == 1)
				{
					document.getElementById("smtp_setting_0").style.display = "none";
					document.getElementById("smtp_setting_1").style.display = "block";
					document.getElementById("smtp_setting_2").style.display = "none";
				}
				else if (WhatSelected == 2)
				{
					document.getElementById("smtp_setting_0").style.display = "none";
					document.getElementById("smtp_setting_1").style.display = "none";
					document.getElementById("smtp_setting_2").style.display = "block";
				}
			}
		</script>
		';
      require INC_PATH . '/readconfig_smtp.php';
      print_rows ('Type of PHP Mail Function?', show_helptip ('You may select your Default PHP Mail Function here. If you get error while sending mail, select different type of this function.') . '
		<select class="bginput" name="configoption[smtptype]" onchange="change_div(this.selectedIndex)">
						<option value="default"' . iif ($smtptype == 'default', ' selected="selected"') . '>Default PHP Mail Function</option>
						<option value="advanced"' . iif ($smtptype == 'advanced', ' selected="selected"') . '>Advanced PHP Mail Function</option>
						<option value="external"' . iif ($smtptype == 'external', ' selected="selected"') . '>External PHP Mail Function</option>
					</select>');
      echo '<div id="smtp_setting_0" style="display:' . iif ($smtptype == 'default', 'block', 'none') . '"></div>';
      echo '		
		</table>
		<table width="100%" cellpadding="5" cellspacing="0" border="1" id="smtp_setting_1" style="display:' . iif ($smtptype == 'advanced', 'block', 'none') . '">
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">SMTP Host?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[smtp_host]" value="' . $smtp_host . '" class="bginput"></td>
			</tr>
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">SMTP Port?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[smtp_port]" value="' . $smtp_port . '" class="bginput"></td>
			</tr>';
      if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
      {
        echo '
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">SMTP Sendmail From?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[smtp_from]" value="' . $smtp_from . '" class="bginput"></td>
			</tr>';
      }
      else
      {
        echo '
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">SMTP Sendmail Path?</td>
				<td class="tdclass2" align="left" valign="top" width="80%">Please setup your sendmail_path by editing php.ini</td>
			</tr>';
      }

      echo '
		</table>
		';
      echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="1" id="smtp_setting_2" style="display:' . iif ($smtptype == 'external', 'block', 'none') . '">
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">Outgoing Mail (SMTP) Address?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[smtpaddress]" value="' . $smtpaddress . '" class="bginput"></td>
			</tr>
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">Outgoing Mail (SMTP) Port?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[smtpport]" value="' . $smtpport . '" class="bginput"></td>
			</tr>
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">Account Name?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="text" size="30" name="configoption[accountname]" value="' . $accountname . '" class="bginput"></td>
			</tr>
			<tr>
				<td class="tdclass1" align="left" valign="top" width="20%">Account Password?</td>
				<td class="tdclass2" align="left" valign="top" width="80%"><input type="password" size="30" name="configoption[accountpassword]" value="' . $accountpassword . '" class="bginput"></td>
			</tr>
		</table>
		<table width="100%" cellpadding="5" cellspacing="0" border="1">
		';
      print_submit_rows ();
      break;
    }

    case 'datetime':
    {
      $timezoneoffset = str_replace ('n', '-', $timezoneoffset);
      $selzonetime = abs (intval ($timezoneoffset) * 10);
      if (my_substrr ($timezoneoffset, 0, 1) == '-')
      {
        $selzoneway = 'n';
      }
      else
      {
        $selzoneway = '';
      }

      $selzone = $selzoneway . $selzonetime;
      $timezoneselect[$selzone] = 'selected="selected"';
      $timenow = my_datee ($timeformat, time (), '-');
      $lang->time_offset_desc = sprintf ($lang->time_offset_desc, $timenow);
      $i = 0 - 12;
      while ($i <= 12)
      {
        if ($i == 0)
        {
          $i2 = '-';
        }
        else
        {
          $i2 = $i;
        }

        $temptime = my_datee ($timeformat, time (), $i2);
        $zone = $i * 10;
        $zone = str_replace ('-', 'n', $zone);
        $timein[$zone] = $temptime;
        ++$i;
      }

      $timein['n35'] = my_datee ($timeformat, time (), 0 - 3.5);
      $timein['35'] = my_datee ($timeformat, time (), 3.5);
      $timein['45'] = my_datee ($timeformat, time (), 4.5);
      $timein['55'] = my_datee ($timeformat, time (), 5.5);
      $timein['575'] = my_datee ($timeformat, time (), 5.75);
      $timein['95'] = my_datee ($timeformat, time (), 9.5);
      $timein['105'] = my_datee ($timeformat, time (), 10.5);
      $str2 .= '<select name="configoption[timezoneoffset]" class="bginput">';
      $str2 .= '<option value="-12" ' . $timezoneselect['n120'] . '>' . $lang->global['GMT'] . ' -12:00 ' . $lang->global['hours'] . ' (' . $timein['n120'] . ')</option>';
      $str2 .= '<option value="-11" ' . $timezoneselect['n110'] . '>' . $lang->global['GMT'] . ' -11:00 ' . $lang->global['hours'] . ' (' . $timein['n110'] . ')</option>';
      $str2 .= '<option value="-10" ' . $timezoneselect['n100'] . '>' . $lang->global['GMT'] . ' -10:00 ' . $lang->global['hours'] . ' (' . $timein['n100'] . ')</option>';
      $str2 .= '<option value="-9" ' . $timezoneselect['n90'] . '>' . $lang->global['GMT'] . ' -9:00 ' . $lang->global['hours'] . ' (' . $timein['n90'] . ')</option>';
      $str2 .= '<option value="-8" ' . $timezoneselect['n80'] . '>' . $lang->global['GMT'] . ' -8:00 ' . $lang->global['hours'] . ' (' . $timein['n80'] . ')</option>';
      $str2 .= '<option value="-7" ' . $timezoneselect['n70'] . '>' . $lang->global['GMT'] . ' -7:00 ' . $lang->global['hours'] . ' (' . $timein['n70'] . ')</option>';
      $str2 .= '<option value="-6" ' . $timezoneselect['n60'] . '>' . $lang->global['GMT'] . ' -6:00 ' . $lang->global['hours'] . ' (' . $timein['n60'] . ')</option>';
      $str2 .= '<option value="-5" ' . $timezoneselect['n50'] . '>' . $lang->global['GMT'] . ' -5:00 ' . $lang->global['hours'] . ' (' . $timein['n50'] . ')</option>';
      $str2 .= '<option value="-4" ' . $timezoneselect['n40'] . '>' . $lang->global['GMT'] . ' -4:00 ' . $lang->global['hours'] . ' (' . $timein['n40'] . ')</option>';
      $str2 .= '<option value="-3.5" ' . $timezoneselect['n35'] . '>' . $lang->global['GMT'] . ' -3:30 ' . $lang->global['hours'] . ' (' . $timein['n35'] . ')</option>';
      $str2 .= '<option value="-3" ' . $timezoneselect['n30'] . '>' . $lang->global['GMT'] . ' -3:00 ' . $lang->global['hours'] . ' (' . $timein['n30'] . ')</option>';
      $str2 .= '<option value="-2" ' . $timezoneselect['n20'] . '>' . $lang->global['GMT'] . ' -2:00 ' . $lang->global['hours'] . ' (' . $timein['n20'] . ')</option>';
      $str2 .= '<option value="-1" ' . $timezoneselect['n10'] . '>' . $lang->global['GMT'] . ' -1:00 ' . $lang->global['hours'] . ' (' . $timein['n10'] . ')</option>';
      $str2 .= '<option value="0" ' . $timezoneselect['0'] . '>' . $lang->global['GMT'] . ' (' . $timein['0'] . ')</option>';
      $str2 .= '<option value="+1" ' . $timezoneselect['10'] . '>' . $lang->global['GMT'] . ' +1:00 ' . $lang->global['hours'] . ' (' . $timein['10'] . ')</option>';
      $str2 .= '<option value="+2" ' . $timezoneselect['20'] . '>' . $lang->global['GMT'] . ' +2:00 ' . $lang->global['hours'] . ' (' . $timein['20'] . ')</option>';
      $str2 .= '<option value="+3" ' . $timezoneselect['30'] . '>' . $lang->global['GMT'] . ' +3:00 ' . $lang->global['hours'] . ' (' . $timein['30'] . ')</option>';
      $str2 .= '<option value="+3.5" ' . $timezoneselect['35'] . '>' . $lang->global['GMT'] . ' +3:30 ' . $lang->global['hours'] . ' (' . $timein['35'] . ')</option>';
      $str2 .= '<option value="+4" ' . $timezoneselect['40'] . '>' . $lang->global['GMT'] . ' +4:00 ' . $lang->global['hours'] . ' (' . $timein['40'] . ')</option>';
      $str2 .= '<option value="+4.5" ' . $timezoneselect['45'] . '>' . $lang->global['GMT'] . ' +4:30 ' . $lang->global['hours'] . ' (' . $timein['45'] . ')</option>';
      $str2 .= '<option value="+5" ' . $timezoneselect['50'] . '>' . $lang->global['GMT'] . ' +5:00 ' . $lang->global['hours'] . ' (' . $timein['50'] . ')</option>';
      $str2 .= '<option value="+5.5" ' . $timezoneselect['55'] . '>' . $lang->global['GMT'] . ' +5:30 ' . $lang->global['hours'] . ' (' . $timein['55'] . ')</option>';
      $str2 .= '<option value="+5.75" ' . $timezoneselect['575'] . '>' . $lang->global['GMT'] . ' +5:45 ' . $lang->global['hours'] . ' (' . $timein['575'] . ')</option>';
      $str2 .= '<option value="+6" ' . $timezoneselect['60'] . '>' . $lang->global['GMT'] . ' +6:00 ' . $lang->global['hours'] . ' (' . $timein['60'] . ')</option>';
      $str2 .= '<option value="+7" ' . $timezoneselect['70'] . '>' . $lang->global['GMT'] . ' +7:00 ' . $lang->global['hours'] . ' (' . $timein['70'] . ')</option>';
      $str2 .= '<option value="+8" ' . $timezoneselect['80'] . '>' . $lang->global['GMT'] . ' +8:00 ' . $lang->global['hours'] . ' (' . $timein['80'] . ')</option>';
      $str2 .= '<option value="+9" ' . $timezoneselect['90'] . '>' . $lang->global['GMT'] . ' +9:00 ' . $lang->global['hours'] . ' (' . $timein['90'] . ')</option>';
      $str2 .= '<option value="+9.5" ' . $timezoneselect['95'] . '>' . $lang->global['GMT'] . ' +9:30 ' . $lang->global['hours'] . ' (' . $timein['95'] . ')</option>';
      $str2 .= '<option value="+10" ' . $timezoneselect['100'] . '>' . $lang->global['GMT'] . ' +10:00 ' . $lang->global['hours'] . ' (' . $timein['100'] . ')</option>';
      $str2 .= '<option value="+10.5" ' . $timezoneselect['105'] . '>' . $lang->global['GMT'] . ' +10:30 ' . $lang->global['hours'] . ' (' . $timein['105'] . ')</option>';
      $str2 .= '<option value="+11" ' . $timezoneselect['110'] . '>' . $lang->global['GMT'] . ' +11:00 ' . $lang->global['hours'] . ' (' . $timein['110'] . ')</option>';
      $str2 .= '<option value="+12" ' . $timezoneselect['120'] . '>' . $lang->global['GMT'] . ' +12:00 ' . $lang->global['hours'] . ' (' . $timein['120'] . ')</option>';
      $str2 .= '</select></td></tr>';
      print_rows ('Date Format?', show_helptip ('The format of the dates used on the tracker/forum. This format uses the PHP date() function. We recommend not changing this unless you know what you are doing.') . '
		<input type="text" size="30" name="configoption[dateformat]" value="' . $dateformat . '" class="bginput">');
      print_rows ('Time Format?', show_helptip ('The format of the dates used on the tracker/forum. This format uses the PHP date() function. We recommend not changing this unless you know what you are doing.') . '
		<input type="text" size="30" name="configoption[timeformat]" value="' . $timeformat . '" class="bginput">');
      print_rows ('Registered Date Format?', show_helptip ('The format used on showthread/userdetails etc.. where it shows when the user registered.') . '
		<input type="text" size="30" name="configoption[regdateformat]" value="' . $regdateformat . '" class="bginput">');
      print_rows ('Default Timezone Offset?', show_helptip ('Here you can set the default timezone offset for guests and members using the default offset.') . '
		' . $str2);
      print_rows ('Day Light Savings Time?', show_helptip ('If times are an hour out above and your timezone is selected correctly, enable day light savings time correction.') . '
		<select class="bginput" name="configoption[dstcorrection]">
						<option value="yes"' . iif ($dstcorrection == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($dstcorrection == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_submit_rows ();
      break;
    }

    case 'theme':
    {
      $template_dirs = dir_list (INC_PATH . '/templates');
      $language_dirs = dir_list (INC_PATH . '/languages');
      if (empty ($template_dirs))
      {
        $dirlist = '<option value="">There is no template</option>';
      }
      else
      {
        foreach ($template_dirs as $dir)
        {
          $dirlist .= '<option value="' . $dir . '"' . iif ($defaulttemplate == $dir, ' selected="selected"') . '>' . $dir . '</option>';
        }
      }

      if (empty ($language_dirs))
      {
        $dirlist2 .= '<option value="">There is no language</option>';
      }
      else
      {
        foreach ($language_dirs as $dir2)
        {
          $dirlist2 .= '<option value="' . $dir2 . '"' . iif ($defaultlanguage == $dir2, ' selected="selected"') . '>' . $dir2 . '</option>';
        }
      }

      print_rows ('Select Default Template?', show_helptip ('Select the default theme for your tracker. This theme will be used for all guests, and any members who have not expressed a style preference in their options, or are attempting to use a theme that does not exist or is forbidden.') . '
		<select class="bginput" name="configoption[defaulttemplate]">
						' . $dirlist . '
					</select>');
      print_rows ('Select Default Language?', show_helptip ('Select the default language for your tracker. This language will be used for all guests, and any members who have not expressed a language preference in their options.') . '
		<select class="bginput" name="configoption[defaultlanguage]">
						' . $dirlist2 . '
					</select>');
      print_rows ('Default Character Set?', show_helptip ('Enter the charset for the language you are exporting.') . '
		<input type="text" size="20" name="configoption[charset]" value="' . $charset . '" class="bginput">');
      print_rows ('Character Set for Ajax Scripts?', show_helptip ('Leave this default (utf-8) if you have any problem on ajax scripts such as shoutbox, poll etc..') . '
		<input type="text" size="20" name="configoption[shoutboxcharset]" value="' . $shoutboxcharset . '" class="bginput">');
      print_rows ('Meta Keywords?', show_helptip ('Type in keywords separated by commas that describe your website. These keywords will help your site be listed in search engines.') . '
		<textarea name="configoption[metakeywords]" style="width: 600px; height: 100px;">' . $metakeywords . '</textarea>');
      print_rows ('Meta Description?', show_helptip ('Description of your website: Helps your website\\\'s position in search engines.') . '
		<textarea name="configoption[metadesc]" style="width: 600px; height: 100px;">' . $metadesc . '</textarea>');
      print_rows ('Tracker Slogan?', show_helptip ('Set your tracker slogan.') . '
		<input type="text" style="width: 600px;" name="configoption[slogan]" value="' . $slogan . '" class="bginput">');
      print_submit_rows ();
      break;
    }

    case 'cronjobs':
    {
      if ((((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND $_GET['act'] == 'save') AND is_valid_id ($_GET['cronid'])) AND $cronid = intval ($_GET['cronid'])))
      {
        $IsNew = ($_POST['save_new'] ? true : false);
        $mosecs = 31 * 24 * 60 * 60;
        $wsecs = 7 * 24 * 60 * 60;
        $dsecs = 24 * 60 * 60;
        $hsecs = 60 * 60;
        $msecs = 60;
        $minutes = 0;
        if (0 < $_POST['months'])
        {
          $minutes += $mosecs * $_POST['months'];
        }

        if (0 < $_POST['weeks'])
        {
          $minutes += $wsecs * $_POST['weeks'];
        }

        if (0 < $_POST['days'])
        {
          $minutes += $dsecs * $_POST['days'];
        }

        if (0 < $_POST['hours'])
        {
          $minutes += $hsecs * $_POST['hours'];
        }

        if (0 < $_POST['minutes'])
        {
          $minutes += $msecs * $_POST['minutes'];
        }

        $Filename = trim ($_POST['filename']);
        $Description = trim ($_POST['description']);
        $Active = iif ($_POST['active'] == 'yes', '1', '0');
        $Loglevel = iif ($_POST['loglevel'] == 'yes', '1', '0');
        if (!$IsNew)
        {
          sql_query ('UPDATE ts_cron SET filename = ' . sqlesc ($Filename) . ', description = ' . sqlesc ($Description) . ('' . ', minutes = \'' . $minutes . '\', active = \'' . $Active . '\', loglevel = \'' . $Loglevel . '\' WHERE cronid = \'' . $cronid . '\''));
        }
        else
        {
          sql_query ('' . 'INSERT INTO ts_cron (minutes, filename, description, active, loglevel) VALUES (\'' . $minutes . '\', ' . sqlesc ($Filename) . ', ' . sqlesc ($Description) . ('' . ', \'' . $Active . '\', \'' . $Loglevel . '\')'));
        }

        unset ($_GET[act]);
        unset ($_POST);
        unset ($_GET);
        unset ($_GET[cronid]);
        unset ($minutes);
        unset ($Filename);
        unset ($Description);
        unset ($Active);
        unset ($Loglevel);
      }

      require_once INC_PATH . '/functions_mkprettytime.php';
      $showcrons = '';
      if ((((isset ($_GET['act']) AND $_GET['act'] == 'run') AND is_valid_id ($_GET['cronid'])) AND $cronid = intval ($_GET['cronid'])))
      {
        sql_query ('' . 'UPDATE ts_cron SET nextrun=\'0\' WHERE cronid = \'' . $cronid . '\'');
        echo '<img src="' . $BASEURL . '/ts_cron.php?rand=' . TIMENOW . '" alt="" title="" width="1" height="1" border="0" />';
      }
      else
      {
        if ((((isset ($_GET['act']) AND $_GET['act'] == 'delete') AND is_valid_id ($_GET['cronid'])) AND $cronid = intval ($_GET['cronid'])))
        {
          sql_query ('' . 'DELETE FROM ts_cron WHERE cronid = \'' . $cronid . '\'');
        }
        else
        {
          if ((((isset ($_GET['act']) AND ($_GET['act'] == 'disable' OR $_GET['act'] == 'active')) AND is_valid_id ($_GET['cronid'])) AND $cronid = intval ($_GET['cronid'])))
          {
            sql_query ('' . 'UPDATE ts_cron SET active = IF(active=1, 0, 1) WHERE cronid = \'' . $cronid . '\'');
          }
          else
          {
            if ((((isset ($_GET['act']) AND ($_GET['act'] == 'edit' OR $_GET['act'] == 'save_new')) AND is_valid_id ($_GET['cronid'])) AND $cronid = intval ($_GET['cronid'])))
            {
              $numrows = 0;
              $IsNew = ($_GET['act'] == 'save_new' ? true : false);
              if (!$IsNew)
              {
                $query = sql_query ('' . 'SELECT * FROM ts_cron WHERE cronid = \'' . $cronid . '\'');
                $numrows = mysql_num_rows ($query);
              }

              if ((0 < $numrows OR $IsNew))
              {
                if (!$IsNew)
                {
                  $Cron = mysql_fetch_assoc ($query);
                  $TArray = calc_cron_time ($Cron['minutes']);
                }
                else
                {
                  $Cron['cronid'] = '999';
                  $Cron['filename'] = '';
                  $Cron['description'] = '';
                  $TArray = array ();
                }

                $i = 0;
                while ($i <= 12)
                {
                  $months .= '<option value="' . $i . '"' . iif ($TArray['months'] == $i, ' selected="selected"') . '>' . $i . ' Month' . iif (1 < $i, 's') . '</option>';
                  ++$i;
                }

                $i = 0;
                while ($i <= 4)
                {
                  $weeks .= '<option value="' . $i . '"' . iif ($TArray['weeks'] == $i, ' selected="selected"') . '>' . $i . ' Week' . iif (1 < $i, 's') . '</option>';
                  ++$i;
                }

                $i = 0;
                while ($i <= 31)
                {
                  $days .= '<option value="' . $i . '"' . iif ($TArray['days'] == $i, ' selected="selected"') . '>' . $i . ' Day' . iif (1 < $i, 's') . '</option>';
                  ++$i;
                }

                $i = 0;
                while ($i <= 24)
                {
                  $hours .= '<option value="' . $i . '"' . iif ($TArray['hours'] == $i, ' selected="selected"') . '>' . $i . ' Hour' . iif (1 < $i, 's') . '</option>';
                  ++$i;
                }

                $i = 0;
                while ($i <= 60)
                {
                  $minutes .= '<option value="' . $i . '"' . iif ($TArray['minutes'] == $i, ' selected="selected"') . '>' . $i . ' Minute' . iif (1 < $i, 's') . '</option>';
                  ++$i;
                }

                $showcrons .= '
				</form>
				<form method="POST" action="managesettings.php?do=cronjobs&act=save&cronid=' . $Cron['cronid'] . '">
				' . iif ($IsNew, '<input type="hidden" name="save_new" value="true" />') . '
				<input type="hidden" name="do" value="cronjobs" />
				<table width="100%" border="1" cellpadding="3" cellspacing="0">
					<tr>
						<td class="subheader">' . iif ($IsNew, 'New', 'Edit') . ' Cron</td>
					</tr>
					<tr>
						<td>
							<fieldset>
								<legend>Filename</legend>
								<input type="text" size="90" name="filename" value="' . iif ($Filename, htmlspecialchars_uni ($Filename), $Cron['filename']) . '" class="bginput" />
							</fieldset>						
							<fieldset>
								<legend>Description</legend>
								<input type="text" size="90" name="description" value="' . iif ($Description, htmlspecialchars_uni ($Description), $Cron['description']) . '" class="bginput" />
							</fieldset>
							<fieldset>
								<legend>Run Period</legend>
								<table border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											Month<br />
											<select name="months" class="bginput">
												' . $months . '
											</select>
										</td>
										<td>
											Week<br />
											<select name="weeks" class="bginput">
												' . $weeks . '
											</select>
										</td>
										<td>
											Day<br />
											<select name="days" class="bginput">
												' . $days . '
											</select>
										</td>
										<td>
											Hour<br />
											<select name="hours" class="bginput">
												' . $hours . '
											</select>
										</td>
										<td>
											Minute<br />
											<select name="minutes" class="bginput">
												' . $minutes . '
											</select>
										</td>
									</tr>					
								</table>
							</fieldset>
							<fieldset>
								<legend>Cron Settings</legend>
								<input class="inlineimg" type="checkbox" name="active" value="yes"' . iif ($Cron['active'], ' checked="checked"') . ' class="bginput" /> Check this box to active this cron. Uncheck to disable this cron.<br />
								<input class="inlineimg" type="checkbox" name="loglevel" value="yes"' . iif ($Cron['loglevel'], ' checked="checked"') . ' class="bginput" /> Check this box to log cron action into database. Uncheck to disable this feature.
							</fieldset>
							<fieldset>
								<legend>Save Cron</legend>
								<input type="submit" value="Save Cron" /> <input type="reset" value="Reset Cron" />
							</fieldset>
						</td>
					</tr>
				</table>
				</form>
				<br />
				';
              }
            }
          }
        }
      }

      $showcrons .= '
		<table width="100%" border="1" cellpadding="3" cellspacing="0">
			<tr>
				<td class="subheader" align="left">Filename</td>
				<td class="subheader" align="left">Description</td>
				<td class="subheader" align="center">Run Period</td>
				<td class="subheader" align="center">Next Run</td>
				<td class="subheader" align="center">Log Action</td>
				<td class="subheader" align="center">Active</td>
				<td class="subheader" align="center">Action</td>
			</tr>';
      $query = sql_query ('SELECT * FROM ts_cron ORDER BY cronid');
      while ($crons = mysql_fetch_assoc ($query))
      {
        $showcrons .= '
			<tr>
				<td align="left">' . $crons['filename'] . '</td>
				<td align="left">' . $crons['description'] . '</td>
				<td align="center">' . mkprettytime ($crons['minutes']) . '</td>
				<td align="center">' . my_datee ($dateformat, $crons['nextrun']) . ' ' . my_datee ($timeformat, $crons['nextrun']) . '</td>
				<td align="center"><font color="' . iif ($crons['loglevel'] == '1', 'green">YES', 'red">NO') . '</font></td>
				<td align="center"><font color="' . iif ($crons['active'] == '1', 'green">YES', 'red">NO') . '</font></td>
				<td align="center">' . frame_link ('cronjobs&amp;act=run&amp;cronid=' . $crons['cronid'], '[run]') . ' ' . frame_link ('cronjobs&amp;act=edit&amp;cronid=' . $crons['cronid'], '[edit]') . ' ' . frame_link ('cronjobs&amp;act=' . iif ($crons['active'] == '1', 'disable', 'active') . '&amp;cronid=' . $crons['cronid'], '[' . iif ($crons['active'] == '1', 'disable', 'active') . ']') . ' ' . frame_link ('cronjobs&amp;act=delete&amp;cronid=' . $crons['cronid'], '[delete]') . '</td>
			</tr>
			';
      }

      $showcrons .= '</table>';
      echo $showcrons;
      $showlogs = '
		<br />
		<table border="1" cellpadding="3" cellspacing="0" align="center" width="800">
			<tr>
				<td colspan="4" class="thead">Show Cron Logs</td>
			</tr>
			<tr>
				<td class="subheader" align="left">Filename</td>
				<td class="subheader" align="center">Query Count</td>
				<td class="subheader" align="center">Execute Time</td>
				<td class="subheader" align="center">Last Run</td>
			</tr>';
      $query = sql_query ('SELECT * FROM ts_cron_log ORDER BY querycount DESC, executetime ASC');
      while ($logs = mysql_fetch_assoc ($query))
      {
        $showlogs .= '
			<tr>
				<td align="left">' . $logs['filename'] . '</td>
				<td align="center">' . ts_nf ($logs['querycount']) . '</td>
				<td align="center">' . $logs['executetime'] . '</td>
				<td align="center">' . my_datee ($dateformat, $logs['runtime']) . ' ' . my_datee ($timeformat, $logs['runtime']) . '</td>				
			</tr>
			';
      }

      $showlogs .= '</table>';
      echo $showlogs . '<p align="center">' . frame_link ('cronjobs&amp;act=save_new&cronid=999', 'Create New Cronjob') . '</p>';
      break;
    }

    case 'cleanup':
    {
      require INC_PATH . '/readconfig_cleanup.php';
      print_rows ('Automatic Invite?', show_helptip ('' . 'Give x Invites every ' . $autoinvitetime . ' days if usergroup have this feature.') . '
		<select class="bginput" name="configoption[ai]">
						<option value="yes"' . iif ($ai == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($ai == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Automatic Invite Time?', show_helptip ('Give x Invites every X days if usergroup have this feature.') . '
		<input type="text" size="2" name="configoption[autoinvitetime]" value="' . $autoinvitetime . '" class="bginput">');
      print_rows ('Mark Torrents Invisible?', show_helptip ('Mark torrents as invisible after X days. (Torrent Last Action < X days)') . '
		<input type="text" size="2" name="configoption[max_dead_torrent_time]" value="' . $max_dead_torrent_time . '" class="bginput">');
      print_rows ('Promote Users GB Limit?', show_helptip ('Once Regular User reach this Limit, his/her account will be promoted automaticly to Power User. Leave 0 to disable this feature.') . '
		<input type="text" size="2" name="configoption[promote_gig_limit]" value="' . $promote_gig_limit . '" class="bginput">');
      print_rows ('Promote Users RATIO Limit?', show_helptip ('Once Regular User reach this Limit, his/her account will be promoted automaticly to Power User.') . '
		<input type="text" size="2" name="configoption[promote_min_ratio]" value="' . $promote_min_ratio . '" class="bginput">');
      print_rows ('Promote Users DAYS Limit?', show_helptip ('Min. DAYS Limit for promote.') . '
		<input type="text" size="2" name="configoption[promote_min_reg_days]" value="' . $promote_min_reg_days . '" class="bginput">');
      print_rows ('Demote Users RATIO Limit?', show_helptip ('Whenever user have below ratio from this limit, his/her account will be demoted automaticly to User.') . '
		<input type="text" size="2" name="configoption[demote_min_ratio]" value="' . $demote_min_ratio . '" class="bginput">');
      print_rows ('Referrer Gift?', show_helptip ('Referrer will receive X GB Upload when his referred users reach Power User Level.') . '
		<input type="text" size="2" name="configoption[referrergift]" value="' . $referrergift . '" class="bginput">');
      print_rows ('Warn User MIN. Ratio?', show_helptip ('Min. Ratio for LeechWarning.') . '
		<input type="text" size="2" name="configoption[leechwarn_min_ratio]" value="' . $leechwarn_min_ratio . '" class="bginput">');
      print_rows ('Warn User GB Limit?', show_helptip ('Min. GB Limit for LeechWarning.') . '
		<input type="text" size="2" name="configoption[leechwarn_gig_limit]" value="' . $leechwarn_gig_limit . '" class="bginput">');
      print_rows ('Warning Length?', show_helptip ('LeechWarning Length (weeks).') . '
		<input type="text" size="2" name="configoption[leechwarn_length]" value="' . $leechwarn_length . '" class="bginput">');
      print_rows ('Remove Warning Min. Ratio?', show_helptip ('Min. Ratio Limit to Remove LeechWarning.') . '
		<input type="text" size="2" name="configoption[leechwarn_remove_ratio]" value="' . $leechwarn_remove_ratio . '" class="bginput">');
      print_rows ('Ban Warned Users?', show_helptip ('Once an user has reached this limit, he will be automaticly banned.') . '
		<input type="text" size="2" name="configoption[ban_user_limit]" value="' . $ban_user_limit . '" class="bginput">');
      print_submit_rows ();
      break;
    }

    case 'tweak':
    {
      print_rows ('Save User Location?', show_helptip ('Save user location into database.') . '
		<select class="bginput" name="configoption[where]">
						<option value="yes"' . iif ($where == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($where == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Save User IP?', show_helptip ('When user login with a different IP, save it into database.') . '
		<select class="bginput" name="configoption[iplog1]">
						<option value="yes"' . iif ($iplog1 == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($iplog1 == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Cracker Tracker Protection Enabled?', show_helptip ('Extra Protection against Hacker Attacks. (URL Protection)') . '
		<select class="bginput" name="configoption[ctracker]">
						<option value="yes"' . iif ($ctracker == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($ctracker == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Refresh Page?', show_helptip ('Refresh page every x minutes. Enter refresh time in \\\'minutes:seconds\\\' Minutes should range from 0 to inifinity. <br />Seconds should range from 0 to 59..') . '
		<select class="bginput" name="configoption[autorefresh]">
						<option value="yes"' . iif ($autorefresh == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($autorefresh == 'no', ' selected="selected"') . '>No</option>
					</select> Refresh Time: <input type="text" size="3" name="configoption[autorefreshtime]" value="' . $autorefreshtime . '" class="bginput">');
      print_rows ('Left Menu Enabled?', show_helptip ('Enable/Disable Javascript Left Menu.') . '
		<select class="bginput" name="configoption[leftmenu]">
						<option value="yes"' . iif ($leftmenu == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($leftmenu == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('GZIP Compression Enabled?', show_helptip ('If your server is running off of apache, then you can turn this setting on to compress your pages potentially making your site much quicker.') . '
		<select class="bginput" name="configoption[gzipcompress]">
						<option value="yes"' . iif ($gzipcompress == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($gzipcompress == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('CACHE System Enabled?', show_helptip ('Turn this setting on to cache your pages potentially making your site much quicker. (INDEX, FAQ, TOPTEN, RULES will be cached for x hours).') . '
		<select class="bginput" name="configoption[cachesystem]">
						<option value="yes"' . iif ($cachesystem == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($cachesystem == 'no', ' selected="selected"') . '>No</option>
					</select> Cache Time: <input type="text" size="3" name="configoption[cachetime]" value="' . $cachetime . '" class="bginput">');
      print_rows ('Snatch Mod Enabled?', show_helptip ('Turn off this mod for better performance..') . '
		<select class="bginput" name="configoption[snatchmod]">
						<option value="yes"' . iif ($snatchmod == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($snatchmod == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('TorrentSpeed Mod Enabled?', show_helptip ('Turn off this mod for better performance..') . '
		<select class="bginput" name="configoption[torrentspeed]">
						<option value="yes"' . iif ($torrentspeed == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($torrentspeed == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('ProgressBar Mod Enabled?', show_helptip ('Turn off this mod for better performance..') . '
		<select class="bginput" name="configoption[progressbar]">
						<option value="yes"' . iif ($progressbar == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($progressbar == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Rating Mod Enabled?', show_helptip ('Turn off this mod for better performance..') . '
		<select class="bginput" name="configoption[ratingsystem]">
						<option value="yes"' . iif ($ratingsystem == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($ratingsystem == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Thanks Mod Enabled?', show_helptip ('User can thank on Torrents and Posts. Turn off this mod for better performance..') . '
		<select class="bginput" name="configoption[thankssystem]">
						<option value="yes"' . iif ($thankssystem == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($thankssystem == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Perpage Limit?', show_helptip ('Perpage for all pages which using pager function.') . '
		<input type="text" size="3" name="configoption[ts_perpage]" value="' . $ts_perpage . '" class="bginput">');
      print_rows ('*NIX Server Load Limit?', show_helptip ('TS SE can read the overall load of the server on certain *NIX setups (including Linux). On certain *NIX setups, including Linux, TS SE can read the server\\\'s load as reported by the operating system. TS SE can then use this information turn away users if the server load passes this threshold. Load on *NIX systems is measured in numbers. Usually load should stay below 1, however spikes can occasionally occur, so you should not set this number too low. A setting of 10 to 20 would be a reasonable threshold.<br /><br />If you do not want to use this option, set it to 0.') . '
		<input type="text" size="3" name="configoption[loadlimit]" value="' . $loadlimit . '" class="bginput">');
      print_submit_rows ();
      break;
    }

    case 'announce':
    {
      require INC_PATH . '/readconfig_announce.php';
      print_rows ('Log Cheat Attempts?', show_helptip ('Disable this for better performance.') . '
		<select class="bginput" name="configoption[announce_actions]">
						<option value="yes"' . iif ($announce_actions == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($announce_actions == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Aggressive Cheat Detection?', show_helptip ('Aggressive Cheat Detection system which will allow you to detect cheat programs such as Ratio Master, Ratio Faker etc..') . '
		<select class="bginput" name="configoption[aggressivecheat]">
						<option value="yes"' . iif ($aggressivecheat == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($aggressivecheat == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Disable DL/UL?', show_helptip ('Disable download/upload of not connectable users. Please note: This feature may help you to stop/detect cheaters.') . '
		<select class="bginput" name="configoption[nc]">
						<option value="yes"' . iif ($nc == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($nc == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Min. Announce Refresh Time?', show_helptip ('Minimum announce refresh time (floot limit). Leave 0 to disable this feature.') . '
		<input type="text" size="10" name="configoption[announce_wait]" value="' . $announce_wait . '" class="bginput">');
      print_rows ('Announce Interval?', show_helptip ('Announce Update Time in Seconds. Leave this high to better performance.') . '
		<input type="text" size="10" name="configoption[announce_interval]" value="' . $announce_interval . '" class="bginput">');
      print_rows ('Max. Transfer Rate?', show_helptip ('Once user has reached this transfer rate, we will try to detect his upload speed..') . '
		<input type="text" class="bginput" size="10" name="configoption[max_rate]" value="' . $max_rate . '">');
      print_rows ('Banned Client Detection Enabled?', show_helptip ('Disable downloads if a banned client detected.<br /><br />If \\\'Banned Client Detection Enabled\\\', only \\\'Allowed Client\\\' list allowed.<br />Separated by , ie: -UT1610-,-AZ2504-,-AZ3012- To catch peer ids, click <a href=index.php?act=allagents><font color=red>here</font></a>.') . '
		<select class="bginput" name="configoption[bannedclientdetect]">
						<option value="yes"' . iif ($bannedclientdetect == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($bannedclientdetect == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Allowed Clients', show_helptip ('This is a white list of clients.') . '
		<textarea name="configoption[allowed_clients]" rows="4" cols="150">' . $allowed_clients . '</textarea>
		');
      print_rows ('Detect Browser Cheats?', show_helptip ('Enable this feature to detect Browser Cheat Attempts.') . '
		<select class="bginput" name="configoption[detectbrowsercheats]">
						<option value="yes"' . iif ($detectbrowsercheats == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($detectbrowsercheats == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Detect Connectable?', show_helptip ('Enable this feature to detect user connectable status.<br />Note: This will decrease system performance.<br /> Note2: If you disable this feature, system will show all users as connectable.') . '
		<select class="bginput" name="configoption[checkconnectable]">
						<option value="yes"' . iif ($checkconnectable == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($checkconnectable == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Check IP?', show_helptip ('Check User IP Before Send Peer List. If you enable this feature, system will check user last ip in users table, user last ip must be equal to client ip.') . '
		<select class="bginput" name="configoption[checkip]">
						<option value="yes"' . iif ($checkip == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($checkip == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_submit_rows ();
      break;
    }

    case 'signup':
    {
      require INC_PATH . '/readconfig_signup.php';
      $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
      $sgids = '
		<fieldset>
			<legend>Select Usergroup</legend>
				<table border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<select name="configoption[_d_usergroup]" class="bginput">';
      while ($gid = mysql_fetch_assoc ($squery))
      {
        $sgids .= '	
								<option value="' . $gid['gid'] . '"' . ($_d_usergroup == $gid['gid'] ? ' selected="selected"' : '') . '>' . get_user_color ($gid['title'], $gid['namestyle']) . '</option>';
      }

      $sgids .= '
							</select>
						</td>
					</tr>
				</table>
		</fieldset>';
      print_rows ('Active Registration System?', show_helptip ('Enable this feature to Allow Normal Registrations (not need invite code).') . ' 
		<select class="bginput" name="configoption[registration]">
						<option value="on"' . iif ($registration == 'on', ' selected="selected"') . '>Active</option>
						<option value="off"' . iif ($registration == 'off', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Active Invite System?', show_helptip ('Enable this feature to Allow Registrations via Invite System.') . ' 
		<select class="bginput" name="configoption[invitesystem]">
						<option value="on"' . iif ($invitesystem == 'on', ' selected="selected"') . '>Active</option>
						<option value="off"' . iif ($invitesystem == 'off', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('New User Verification Type?', show_helptip ('Select Verification Type of Registration.<br /><br /><b>EMAIL:</b> Sent confirmation email.<br /><b>ADMIN</b>: Manual activate by Staff.<br /><b>AUTOMATIC</b>: Automaticly activate after registration by system.') . ' 
		<select class="bginput" name="configoption[verification]">
						<option value="email"' . iif ($verification == 'email', ' selected="selected"') . '>E-Mail Activation</option>
						<option value="admin"' . iif ($verification == 'admin', ' selected="selected"') . '>Admin Moderation</option>
						<option value="automatic"' . iif ($verification == 'automatic', ' selected="selected"') . '>Automaticly by System</option>
					</select>');
      print_rows ('Proxie Detection?', show_helptip ('Disable Registrations via Proxies.') . '
		<select class="bginput" name="configoption[pd]">
						<option value="yes"' . iif ($pd == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($pd == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Max. IP\'s?', show_helptip ('Disable registration with same IP Address. Leave it \\\'disable\\\' to disable this feature.') . '
		<input type="text" class="bginput" size="3" name="configoption[maxip]" value="' . $maxip . '">');
      print_rows ('Max. Users?', show_helptip ('Disable registration whenever this limit will be reached.') . '
		<input type="text" size="10" name="configoption[maxusers]" value="' . $maxusers . '" class="bginput">');
      print_rows ('Default Usergroup?', show_helptip ('Once user confirm his/her account, he/she will be promoted to this usergroup.') . $sgids);
      print_rows ('Initial Number of Invites?', show_helptip ('How many invites should each user be given upon registration? Leave 0 to disable this.') . '
		<input type="text" size="10" name="configoption[invite_count]" value="' . $invite_count . '" class="bginput">');
      print_rows ('Auto GB on Signup?', show_helptip ('How much GB should each user be given upon registration? Leave 0 to disable this.') . '
		<input type="text" size="10" name="configoption[autogigsignup]" value="' . $autogigsignup . '" class="bginput">');
      print_rows ('Auto SeedBonus on Signup?', show_helptip ('How much Seedbonus should each user be given upon registration? Leave 0 to disable this.') . '
		<input type="text" size="10" name="configoption[autosbsignup]" value="' . $autosbsignup . '" class="bginput">');
      print_rows ('Invalid Countries?', show_helptip ('Enter two letter country codes in here that you do not want people to be able to register. If any of the codes here are included within the user country, the user will told that there is an error.<br />Separate country names with a single comma ,<br />Example: TR,NL,FR<br />See country codes: include/ip_files/countries.php') . '
		<textarea name="configoption[badcountries]" rows="5" cols="75" class="bginput">' . $badcountries . '</textarea>');
      print_rows ('Illegal User Names?', show_helptip ('Enter names in here that you do not want people to be able to register. If any of the names here are included within the username, the user will told that there is an error. For example, if you make the name John illegal, the name Johnathan will also be disallowed. Separate names by spaces.') . '
		<textarea name="configoption[illegalusernames]" rows="5" cols="75" class="bginput">' . $illegalusernames . '</textarea>');
      print_rows ('Verification Fields?', show_helptip ('User must agree rules before registering.') . '
		<select class="bginput" name="configoption[r_verification]">
						<option value="yes"' . iif ($r_verification == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_verification == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Gender?', show_helptip ('User can enter his gender while registering.') . '
		<select class="bginput" name="configoption[r_gender]">
						<option value="yes"' . iif ($r_gender == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_gender == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Birthday?', show_helptip ('User can enter his birthday while registering.') . '
		<select class="bginput" name="configoption[r_bday]">
						<option value="yes"' . iif ($r_bday == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_bday == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Timezone?', show_helptip ('User can select Timezone while registering.') . '
		<select class="bginput" name="configoption[r_timezone]">
						<option value="yes"' . iif ($r_timezone == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_timezone == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Referrer?', show_helptip ('User can enter Referrer username while registering.') . '
		<select class="bginput" name="configoption[r_referrer]">
						<option value="yes"' . iif ($r_referrer == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_referrer == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Country Select?', show_helptip ('User can select his Country while registering.') . '
		<select class="bginput" name="configoption[r_country]">
						<option value="yes"' . iif ($r_country == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_country == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Ask for Secret Question?', show_helptip ('User can select Secret question and enter secret answer while registering.') . '
		<select class="bginput" name="configoption[r_secretquestion]">
						<option value="yes"' . iif ($r_secretquestion == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($r_secretquestion == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_submit_rows ();
      break;
    }

    case 'extra':
    {
      print_rows ('Check & Show Connectable?', show_helptip ('Show a warning message to user if his/her connectable status is NO.') . '
		<select class="bginput" name="configoption[checkconnectable]">
						<option value="yes"' . iif ($checkconnectable == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($checkconnectable == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Save Referrers?', show_helptip ('Detect referrers and save into database.') . '
		<select class="bginput" name="configoption[ref]">
						<option value="yes"' . iif ($ref == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($ref == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('HIT & RUN System Enabled?', show_helptip ('Check user ratio before download torrent.') . '
		<select class="bginput" name="configoption[hitrun]">
						<option value="yes"' . iif ($hitrun == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($hitrun == 'no', ' selected="selected"') . '>No</option>
					</select> Min. Ratio for HIT & RUN: <input type="text" size="3" name="configoption[hitrun_ratio]" value="' . $hitrun_ratio . '" class="bginput"> Min. GB limit for HIT & RUN: <input type="text" size="3" name="configoption[hitrun_gig]" value="' . $hitrun_gig . '" class="bginput">');
      print_rows ('Request Section Enabled?', show_helptip ('Turn OFF Request section by selecting \\\'NO\\\'.') . '
		<select class="bginput" name="configoption[rqs]">
						<option value="yes"' . iif ($rqs == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($rqs == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('ShoutBot Enabled?', show_helptip ('This bot will announce new users, new torrents and requests on shout area.<br /><br />To announce all actions enter <b>upload,newuser,request</b> to options area. To announce only new uploads just enter <b>upload</b><br /><br />Examples for Options area:</b><br />Announce ALL:</b> upload,newuser,request</b><br />Announce Upload and Request:</b> upload,request</b><br />Announce Upload and New Users:</b> upload,newuser</b>') . '
		<select class="bginput" name="configoption[tsshoutbot]">
						<option value="yes"' . iif ($tsshoutbot == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($tsshoutbot == 'no', ' selected="selected"') . '>No</option>
					</select> ShoutBot Name: <input type="text" size="10" name="configoption[tsshoutbotname]" value="' . $tsshoutbotname . '" class="bginput"> Shoutbot Options: <input type="text" name="configoption[tsshoutboxoptions]"  value="' . $tsshoutboxoptions . '" class="bginput" ');
      print_submit_rows ();
      break;
    }

    case 'security':
    {
      print_rows ('Virtual Keyboard Enabled?', show_helptip ('Enable this feature to prevent Keylogger hacks.') . '
		<select class="bginput" name="configoption[vkeyword]">
						<option value="yes"' . iif ($vkeyword == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($vkeyword == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Secure Login Enabled?', show_helptip ('YES: Enabled<br />NO: Disabled<br />OPTIONAL: Selectable by Users.') . '
		<select class="bginput" name="configoption[securelogin]">
						<option value="yes"' . iif ($securelogin == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($securelogin == 'no', ' selected="selected"') . '>No</option>
						<option value="op"' . iif ($securelogin == 'op', ' selected="selected"') . '>Optional</option>
					</select>');
      print_rows ('Private Tracker Patch Enabled?', show_helptip ('Re-download is necessary for seed after upload a torrent.') . '
		<select class="bginput" name="configoption[privatetrackerpatch]">
						<option value="yes"' . iif ($privatetrackerpatch == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($privatetrackerpatch == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Image Verification Enabled?', show_helptip ('Require users to enter a Visual Verify Code to register/login/recover? (GD OR GD2 Library required) If you see the image on this page, this feature should be work on your server. Click <a href=?do=image_test><font color=red>here</font></a> to check your server.') . '
		<select class="bginput" name="configoption[iv]">
						<option value="yes"' . iif ($iv == 'yes', ' selected="selected"') . '>Yes, use Image Verification (GD/GD2).</option>
						<option value="reCAPTCHA"' . iif ($iv == 'reCAPTCHA', ' selected="selected"') . '>Yes, use reCAPTCHA™</option>
						<option value="no"' . iif ($iv == 'no', ' selected="selected"') . '>No, disabled.</option>
					</select>');
      if ($iv == 'reCAPTCHA')
      {
        print_rows ('reCAPTCHA™ Public Key?', show_helptip ('Public key provided to you by <a href=http://recaptcha.net/api/getkey target=_blank><font color=red>reCAPTCHA™</font></a>') . '
			<input type="text" class="bginput" name="configoption[reCAPTCHAPublickey]" size="35" value="' . $reCAPTCHAPublickey . '">
			');
        print_rows ('reCAPTCHA™ Private Key?', show_helptip ('Private key provided to you by <a href=http://recaptcha.net/api/getkey target=_blank><font color=red>reCAPTCHA™</font></a>') . '
			<input type="text" class="bginput" name="configoption[reCAPTCHAPrivatekey]" size="35" value="' . $reCAPTCHAPrivatekey . '">
			');
        print_rows ('reCAPTCHA™ Theme?', show_helptip ('reCAPTCHA™ provides different themes for their CAPTCHA\\\'s. This option allows you to select the theme to use within your website.') . '
			<select class="bginput" name="configoption[reCAPTCHATheme]">
						<option value="red"' . iif ($reCAPTCHATheme == 'red', ' selected="selected"') . '>Red</option>
						<option value="white"' . iif ($reCAPTCHATheme == 'white', ' selected="selected"') . '>White</option>
						<option value="blackglass"' . iif ($reCAPTCHATheme == 'blackglass', ' selected="selected"') . '>Black Glass</option>
					</select>');
        print_rows ('reCAPTCHA™ Language?', show_helptip ('reCAPTCHA™ provides different languages for their CAPTCHA\\\'s. This option allows you to select the default language of reCAPTCHA™') . '
			<select class="bginput" name="configoption[reCAPTCHALanguage]">
						<option value="en"' . iif ($reCAPTCHALanguage == 'en', ' selected="selected"') . '>English</option>
						<option value="nl"' . iif ($reCAPTCHALanguage == 'nl', ' selected="selected"') . '>Dutch</option>
						<option value="fr"' . iif ($reCAPTCHALanguage == 'fr', ' selected="selected"') . '>French</option>
						<option value="de"' . iif ($reCAPTCHALanguage == 'de', ' selected="selected"') . '>German</option>
						<option value="pt"' . iif ($reCAPTCHALanguage == 'pt', ' selected="selected"') . '>Portuguese</option>
						<option value="ru"' . iif ($reCAPTCHALanguage == 'ru', ' selected="selected"') . '>Russian</option>
						<option value="es"' . iif ($reCAPTCHALanguage == 'es', ' selected="selected"') . '>Spanish</option>
						<option value="tr"' . iif ($reCAPTCHALanguage == 'tr', ' selected="selected"') . '>Turkish</option>
					</select>');
      }

      print_rows ('Disable Right Mouse Click?', show_helptip ('Use this feature to stop surfers from easily saving your web page, viewing its source, or lifting images off your site when using either IE 4+ or NS 4+.') . '
		<select class="bginput" name="configoption[disablerightclick]">
						<option value="yes"' . iif ($disablerightclick == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($disablerightclick == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Aggressive IP Ban?', show_helptip ('Check banned IP\\\'s in Aggressive mode. This might decrease server load.') . '
		<select class="bginput" name="configoption[aggressivecheckip]">
						<option value="yes"' . iif ($aggressivecheckip == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($aggressivecheckip == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Aggressive EMAIL Ban?', show_helptip ('Check banned EMAIL\\\'s in Aggressive mode.') . '
		<select class="bginput" name="configoption[aggressivecheckemail]">
						<option value="yes"' . iif ($aggressivecheckemail == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($aggressivecheckemail == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Max. Login Attempts?', show_helptip ('Disable/Ban IP address who exceed this limit.') . '
		<input type="text" class="bginput" size="3" name="configoption[maxloginattempts]" value="' . $maxloginattempts . '">');
      print_rows ('Secure Hash?', show_helptip ('Please enter a secure word that only known by you. Whenever you change this, all users must re-login.') . '
		<input type="text" class="bginput" size="30" name="configoption[securehash]" value="' . $securehash . '">');
      print_rows ('Censored Words?', show_helptip ('Censored Words. Separated by ,') . '
		<input type="text" class="bginput" size="30" name="configoption[badwords]" value="' . $badwords . '">');
      print_rows ('Post Referrer Whitelist?', show_helptip ('For example, if you have multiple sites that tie into this tracker, or if you have TS-integrated mods that POST data externally, then you may want to put those referrers on the whitelist.<br /><br />For security purposes, TS SE only allows data to be submitted via post from within the domain the tracker is installed on. If you are submitting post requests from a different domain or subdomain, you must add them here.<br /><br />Enter domains in the form of .domain.com (including the leading dot). Separate multiple domains by line breaks.') . '
		<textarea name="configoption[allowedreferrers]" rows="5" cols="75" class="bginput">' . $allowedreferrers . '</textarea>');
      print_submit_rows ();
      break;
    }

    case 'pincode':
    {
      print_rows ('Enter your new pincode (For settings panel)', show_helptip ('Leave blank if you want to keep this.') . '
		<input type="password" class="bginput" size="15" name="configoption[pincode1]" value="">', '30%', '70%');
      print_rows ('Re-enter your new pincode (For settings panel)', show_helptip ('Re-enter pincode, same as above pincode.') . '
		<input type="password" class="bginput" size="15" name="configoption[re_pincode1]" value="">', '30%', '70%');
      print_rows ('Enter your new pincode (For staff panel)', show_helptip ('Leave blank if you want to keep this.') . '
		<input type="password" class="bginput" size="15" name="configoption[pincode2]" value="">', '30%', '70%');
      print_rows ('Re-enter your new pincode (For staff panel)', show_helptip ('Re-enter pincode, same as above pincode.') . '
		<input type="password" class="bginput" size="15" name="configoption[re_pincode2]" value="">', '30%', '70%');
      print_submit_rows ();
      break;
    }

    case 'staffteam':
    {
      $filename = CONFIG_DIR . '/STAFFTEAM';
      $handle = fopen ($filename, 'r');
      $staffteam = fread ($handle, filesize ($filename));
      $staffteam = explode (',', $staffteam);
      $staffarray = array ();
      foreach ($staffteam as $staff)
      {
        $staff = explode (':', $staff);
        $staffarray[] = array ('name' => '<input type="text" name="staffnames[]" value="' . $staff[0] . '" class="bginput" />', 'id' => '<input type="text" name="staffids[]" value="' . $staff[1] . '" class="bginput" />');
      }

      $printrows = '
		<table align="left" border="1" cellpadding="3" cellspacing="0">
			<tr>
				<td class="subheader">Username</td>
				<td class="subheader">Userid</td>
			</tr>';
      foreach ($staffarray as $array)
      {
        $printrows .= '
			<tr>
				<td>' . $array['name'] . '</td>
				<td>' . $array['id'] . '</td>
			</tr>';
      }

      $printrows .= '
			<tr>
				<td><input type="text" name="staffnames[]" value="" class="bginput" /></td>
				<td><input type="text" name="staffids[]" value="" class="bginput" /></td>
			</tr>
			<tr>
				<td><input type="text" name="staffnames[]" value="" class="bginput" /></td>
				<td><input type="text" name="staffids[]" value="" class="bginput" /></td>
			</tr>
			<tr>
				<td><input type="text" name="staffnames[]" value="" class="bginput" /></td>
				<td><input type="text" name="staffids[]" value="" class="bginput" /></td>
			</tr>';
      $printrows .= '		
		</table>		
		';
      print_rows ('Manage Staff Team<br /><br /><input type="button" onclick="window.open(\'' . $BASEURL . '/users.php#searchuser\',\'finduser\',\'toolbar=no, scrollbars=yes, resizable=no, width=800, height=300, top=50, left=50\'); return false;" value="' . $lang->global['finduser'] . '" class="button" />', show_helptip ('To insert a new staff member please enter username, userid and click on save button.<br />To remove a staff member delete username and userid from the input field and click on save button.', 'Quick Help', 600) . $printrows);
      print_submit_rows ();
      break;
    }

    case 'download':
    {
      require INC_PATH . '/readconfig_download.php';
      print_rows ('Active ZIP Feature?', show_helptip ('Download .zip instead of .torrent files and put a description file inside the .zip arcive. (e.g.: This file downloaded from X)') . ' 
		<select class="bginput" name="configoption[usezip]">
						<option value="yes"' . iif ($usezip == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($usezip == 'no', ' selected="selected"') . '>Disable</option>
					</select>');
      print_rows ('Thank Before Download Enabled?', show_helptip ('User who want to download a torrent, must click on thankyou button before download it.') . '
		<select class="bginput" name="configoption[thankbeforedl]">
						<option value="yes"' . iif ($thankbeforedl == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($thankbeforedl == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_submit_rows ();
      break;
    }

    case 'seo':
    {
      if ($ts_seo == 'yes')
      {
        seo_activate ();
      }

      echo '
			<tr>
				<td colspan="2" class="tdclass1">Friendly URL\'s are a very important thing to have when developing a dymanic content site that uses long query strings. A query string is something similiar to a URL like you\'ll see on Ebay, Google and other big sites. If you\'re running a small to medium size website and you want to make your URL\'s a little easier to find you can do so via Apache\'s mod_rewrite by following this tutorial.<br /><br />
				Prerequisites<br />
				You\'ll need to have Apache\'s mod_rewrite compiled into your Apache Web Server and you\'ll need to have access to setting some options up in your httpd.conf or at least have a server admin who\'s willing to do it for you. For quick reference, the configure options for mod_rewrite are:<br /><br />
				./configure<br />
				--enable-module=rewrite<br />
				--enable-shared=rewrite<br /><br />
				I strongly urge you to carefully consider what other options you need to compile into your Apache installation. The above example is only for this one module.<br /><br />
				Once you\'ve compiled Apache to use mod_rewrite, you will need to setup the httpd.conf file. Here\'s how I set mine up for this particular situtation.<br /><br />
				' . htmlspecialchars ('<Directory /www/htdocs/yoursite>') . '<br />
				Options ExecCGI FollowSymLinks Includes MultiViews<br />
				' . htmlspecialchars ('</Directory>') . '<br /><br />
				Some of the above directory options may not be necessary for what you are trying to do. You\'ll have to decide if you need them. If you are having problems you can also try to use AllowOverride All to see if you can get it to work.<br /></td>
			</tr>
			<tr>
				<td width="20%" align="right" class="tdclass1"><strong>Active SEO?</strong></td>
				<td width="80%" align="left" class="tdclass2">
					<select class="bginput" name="configoption[ts_seo]">
						<option value="yes"' . iif ($ts_seo == 'yes', ' selected="selected"') . '>Active</option>
						<option value="no"' . iif ($ts_seo == 'no', ' selected="selected"') . '>Disable</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center" class="tdclass1"><input type="submit" value="save" class="button"> <input type="reset" value="reset" class="button"></td>
			</tr>	
		';
      break;
    }

    case 'waitslot':
    {
      require INC_PATH . '/readconfig_waitslot.php';
      print_rows ('Type of Wait & Slot System?', show_helptip ('Please select type of Wait & Slot System... If you choose Usergroup based option, you should configure it by using Usergroup permissions, otherwise use below settings.') . '
		<select class="bginput" name="configoption[waitsystemtype]">
						<option value="1"' . iif ($waitsystemtype == '1', ' selected="selected"') . '>Ratio & GB Based</option>
						<option value="2"' . iif ($waitsystemtype == '2', ' selected="selected"') . '>Usergroup Based</option>
					</select>');
      print_row_header ('Wait System Limitations');
      print_rows ('Wait System Limitation #1', show_helptip ('Setup Wait System Limitation #1') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio1]" value="' . $ratio1 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload1]" value="' . $upload1 . '">
		delay of <input type="text" class="bginput" size="3" name="configoption[delay1]" value="' . $delay1 . '"> hours.');
      print_rows ('Wait System Limitation #2', show_helptip ('Setup Wait System Limitation #2') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio2]" value="' . $ratio2 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload2]" value="' . $upload2 . '">
		delay of <input type="text" class="bginput" size="3" name="configoption[delay2]" value="' . $delay2 . '"> hours.');
      print_rows ('Wait System Limitation #3', show_helptip ('Setup Wait System Limitation #3') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio3]" value="' . $ratio3 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload3]" value="' . $upload3 . '">
		delay of <input type="text" class="bginput" size="3" name="configoption[delay3]" value="' . $delay3 . '"> hours.');
      print_rows ('Wait System Limitation #4', show_helptip ('Setup Wait System Limitation #4') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio4]" value="' . $ratio4 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload4]" value="' . $upload4 . '">
		delay of <input type="text" class="bginput" size="3" name="configoption[delay4]" value="' . $delay4 . '"> hours.');
      print_row_header ('Slot System Limitations');
      print_rows ('Slot System Limitation #1', show_helptip ('Setup Slot System Limitation #1') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio5]" value="' . $ratio5 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload5]" value="' . $upload5 . '">
		available <input type="text" class="bginput" size="3" name="configoption[slot1]" value="' . $slot1 . '"> slot(s).');
      print_rows ('Slot System Limitation #2', show_helptip ('Setup Slot System Limitation #2') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio6]" value="' . $ratio6 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload6]" value="' . $upload6 . '">
		available <input type="text" class="bginput" size="3" name="configoption[slot2]" value="' . $slot2 . '"> slot(s).');
      print_rows ('Slot System Limitation #3', show_helptip ('Setup Slot System Limitation #3') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio7]" value="' . $ratio7 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload7]" value="' . $upload7 . '">
		available <input type="text" class="bginput" size="3" name="configoption[slot3]" value="' . $slot3 . '"> slot(s).');
      print_rows ('Slot System Limitation #4', show_helptip ('Setup Slot System Limitation #4') . '
		Ratio below <input type="text" class="bginput" size="3" name="configoption[ratio8]" value="' . $ratio8 . '">
		and/or upload below (GB) <input type="text" class="bginput" size="3" name="configoption[upload8]" value="' . $upload8 . '">
		available <input type="text" class="bginput" size="3" name="configoption[slot4]" value="' . $slot4 . '"> slot(s).');
      print_submit_rows ();
      break;
    }

    case 'paypal':
    {
      require INC_PATH . '/readconfig_paypal.php';
      $get_total_funds = sql_query ('SELECT SUM(cash) AS total_funds FROM funds WHERE cash > 0');
      $currentfunds = mysql_result ($get_total_funds, 0, 'total_funds');
      print_row_header ('General PAYPAL Settings');
      print_rows ('Paypal Demo Mode?', show_helptip ('Test Paypal system on PayPal\\\'s Sandbox.') . '
		<select class="bginput" name="configoption[paypal_demo_mode]">
						<option value="yes"' . iif ($paypal_demo_mode == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($paypal_demo_mode == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Auto-Promote Active?', show_helptip ('Once a  user makes a PayPal payment his account will automatically be promoted.') . '
		<select class="bginput" name="configoption[paypal_auto_mode]">
						<option value="yes"' . iif ($paypal_auto_mode == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($paypal_auto_mode == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('PayPal E-Mail Address?', show_helptip ('Enter your PayPal email address where donations will be stored.') . '
		<input type="text" class="bginput" size="50" name="configoption[pmail]" value="' . $pmail . '">');
      print_rows ('Identity Token?', show_helptip ('Login to your Paypal Account. Click on Profile, click on Website Payment Preferences, Turn on Payment Data Transfer and get your Identity Token code and paste it here.') . '
		<input type="text" class="bginput" size="70" name="configoption[paypal_auth_token]" value="' . $paypal_auth_token . '">');
      print_row_header ('General MoneyBookers Settings');
      print_rows ('MoneyBookers E-Mail Address?', show_helptip ('Enter your MoneyBookers email address where donations will be stored.') . '
		<input type="text" class="bginput" size="50" name="configoption[moneybookersemail]" value="' . $moneybookersemail . '">');
      print_row_header ('General Wire Transfer Settings');
      print_rows ('Wire Transfer Details?', show_helptip ('Enter your Wire Transfer Details here which will be shown on donation page.<br />HTML Allowed..') . '		
		<textarea name="configoption[wire_form]" id="textarea1">' . $wire_form . '</textarea>');
      print_row_header ('Other Settings');
      print_rows ('Server Fee?', show_helptip ('' . 'Set this to your monthly wanted amount for your server fee. Current Total Donations: ' . $currentfunds . ' USD [<a href=managesettings.php?do=reset_funds&amp;sessionhash=' . session_id () . '&amp;tshash=' . $_SESSION['hash'] . '><font color=red>reset</font></a>]') . '
		<input type="text" class="bginput" size="10" name="configoption[tn]" value="' . $tn . '">');
      print_rows ('Currency?', show_helptip ('Set Currency Code.<br /><br /><b>Example:</b>USD, EUR etc..') . '
		<input type="text" class="bginput" size="10" name="configoption[pcc]" value="' . $pcc . '">');
      print_rows ('Donation Amount(s)?', show_helptip ('Set donation amounts.<br /><b>Note:</b> separated by <b>:</b><br /><br /><b>Example:</b> 10:20:25:35:50') . '
		<input type="text" class="bginput" size="50" name="configoption[donationamounts]" value="' . $donationamounts . '">');
      print_rows ('Show donor List?', show_helptip ('Show Donor List on Donation Page.') . '
		<select class="bginput" name="configoption[showdonorlist]">
						<option value="10"' . iif ($showdonorlist == '10', ' selected="selected"') . '>Yes (Show 10)</option>
						<option value="20"' . iif ($showdonorlist == '20', ' selected="selected"') . '>Yes (Show 20)</option>
						<option value="10a"' . iif ($showdonorlist == '10a', ' selected="selected"') . '>Yes (Show 10 - Staff Only)</option>
						<option value="20a"' . iif ($showdonorlist == '20a', ' selected="selected"') . '>Yes (Show 20 - Staff Only)</option>
						<option value="no"' . iif ($showdonorlist == 'no', ' selected="selected"') . '>No (Hide it)</option>
					</select>');
      print_submit_rows (2, NULL);
      echo '
		<script>

		(function() {
			var Dom = YAHOO.util.Dom,
				Event = YAHOO.util.Event;
			
			var myConfig = {
				height: "300px",
				width: "600px",
				dompath: true,
				focusAtStart: true,
				handleSubmit: true
			};
			
			var myEditor = new YAHOO.widget.Editor("textarea1", myConfig);
			myEditor._defaultToolbar.buttonType = "basic";
			myEditor.render();
		})();
	</script>';
      break;
    }

    case 'kps':
    {
      require INC_PATH . '/readconfig_kps.php';
      print_rows ('KPS System Enabled?', show_helptip ('Once user earn points by seeding a torrent, posting a comment etc.. they can trade this points on KPS page.') . '
		<select class="bginput" name="configoption[bonus]">
						<option value="enable"' . iif ($bonus == 'enable', ' selected="selected"') . '>Yes, Enabled.</option>
						<option value="disablesave"' . iif ($bonus == 'disablesave', ' selected="selected"') . '>No, But Save Points.</option>
						<option value="disable"' . iif ($bonus == 'disable', ' selected="selected"') . '>No, Disabled.</option>
					</select>');
      print_rows ('Seed Point?', show_helptip ('This points depending on your Announce Interval value.<br /><br />Example: Set this to 0.5 and set Announce Interval to 1800 so seeders will get 1 point per hour.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpsseed]" value="' . $kpsseed . '">');
      print_rows ('Upload Point?', show_helptip ('Give seeding bonus when user upload a torrent.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpsupload]" value="' . $kpsupload . '">');
      print_rows ('Post/Comment/Thread Point?', show_helptip ('Give seeding bonus when user submit a comment/post/thread.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpscomment]" value="' . $kpscomment . '">');
      print_rows ('Thanks Point?', show_helptip ('Give seeding bonus when user say thanks.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpsthanks]" value="' . $kpsthanks . '">');
      print_rows ('Rating Point?', show_helptip ('Give seeding bonus when user rate a torrent.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpsrate]" value="' . $kpsrate . '">');
      print_rows ('Poll Point?', show_helptip ('Give seeding bonus when user vote a poll.') . '
		<input type="text" class="bginput" size="5" name="configoption[kpspoll]" value="' . $kpspoll . '">');
      print_rows ('Max. Bonus Point?', show_helptip ('Once user reach this limit he can only use trade for GIFT!') . '
		<input type="text" class="bginput" size="5" name="configoption[kpsmaxpoint]" value="' . $kpsmaxpoint . '">');
      print_rows ('Enable Invite Usage?', show_helptip ('Enable/Disable Invite Gift for KPS Page.') . '
		<select class="bginput" name="configoption[kpsinvite]">
						<option value="yes"' . iif ($kpsinvite == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpsinvite == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Enable Custom Title Usage?', show_helptip ('Enable/Disable Custom Title Gift for KPS Page.') . '
		<select class="bginput" name="configoption[kpstitle]">
						<option value="yes"' . iif ($kpstitle == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpstitle == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Enable VIP Status Usage?', show_helptip ('Enable/Disable VIP Gift for KPS Page.') . '
		<select class="bginput" name="configoption[kpsvip]">
						<option value="yes"' . iif ($kpsvip == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpsvip == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Enable Give A Karma Gift Usage?', show_helptip ('Enable/Disable Give A Karma Gift Usage for KPS Page.') . '
		<select class="bginput" name="configoption[kpsgift]">
						<option value="yes"' . iif ($kpsgift == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpsgift == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Enable Remove Warning Usage?', show_helptip ('Enable/Disable Remove Warning Usage for KPS Page.') . '
		<select class="bginput" name="configoption[kpswarning]">
						<option value="yes"' . iif ($kpswarning == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpswarning == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Enable Fix Torrent Ratio Usage?', show_helptip ('Enable/Disable Fix Torrent Ratio Usage for KPS Page.') . '
		<select class="bginput" name="configoption[kpsratiofix]">
						<option value="yes"' . iif ($kpsratiofix == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($kpsratiofix == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_row_header ('Birthday Reward Settings');
      print_rows ('Birthday Reward System Enabled?', show_helptip ('Free/Silver/Double leech/seed on user\\\'s birthday!') . '
		<select class="bginput" name="configoption[bdayreward]">
						<option value="yes"' . iif ($bdayreward == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($bdayreward == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Birthday Reward TYpe?', show_helptip ('Select Birthday Reward Type:<br /><br />Free Leech: Free Torrents download when set gives the users upload credit only and no download credit is posted to the users stats.<br /><br />Silver Leech: Silver Torrents when set only record 50% of the users download credit on that file.<br /><br />x2 Upload: x2 Double Upload Credit for seeding back files.') . '
		<select class="bginput" name="configoption[bdayrewardtype]">
						<option value="freeleech"' . iif ($bdayrewardtype == 'freeleech', ' selected="selected"') . '>Free Leech</option>
						<option value="silverleech"' . iif ($bdayrewardtype == 'silverleech', ' selected="selected"') . '>Silver Leech</option>
						<option value="doubleupload"' . iif ($bdayrewardtype == 'doubleupload', ' selected="selected"') . '>x2 Upload</option>
					</select>');
      print_submit_rows ();
      break;
    }

    case 'lottery':
    {
      require INC_PATH . '/readconfig_lottery.php';
      if ($lottery_allowed_usergroups = explode (',', $lottery_allowed_usergroups))
      {
      }
      else
      {
        $lottery_allowed_usergroups = array ();
      }

      $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
      $scount = 1;
      $sgids = '
		<fieldset>
			<legend>Select Usergroup(s)</legend>
				<table border="0" cellspacing="0" cellpadding="2" width="100%">
					<tr>';
      while ($gid = mysql_fetch_assoc ($squery))
      {
        if ($scount % 4 == 1)
        {
          $sgids .= '</tr><tr>';
        }

        $sgids .= '	
			<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"' . (in_array ('[' . $gid['gid'] . ']', $lottery_allowed_usergroups, true) ? ' checked="checked"' : '') . '></td>
			<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
        ++$scount;
      }

      $sgids .= '
					</tr>
				</table>
		</fieldset>';
      print_rows ('Lottery System Enabled?', show_helptip ('Enable/Disable Lottery System. Note: Staff Team will still be able to see the lottery page.') . '
		<select class="bginput" name="configoption[lottery_enabled]">
			<option value="yes"' . iif ($lottery_enabled == 'yes', ' selected="selected"') . '>Yes, Enabled.</option>
			<option value="no"' . iif ($lottery_enabled == 'no', ' selected="selected"') . '>No, Disabled.</option>
		</select>');
      print_rows ('Allowed Usergroups?', show_helptip ('Select allowed usergroups to use Lottery System.') . '
		 ' . $sgids);
      print_rows ('Ticket Amount?', show_helptip ('Enter Ticket Amount') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_ticket_amount]" value="' . $lottery_ticket_amount . '">');
      print_rows ('Winner Amount?', show_helptip ('If any user win the lottery, they will get this amount of upload value.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_winner_amount]" value="' . $lottery_winner_amount . '">');
      print_rows ('Ticket & Winner Amount Type?', show_helptip ('Select Amount Type (MB, GB)') . '
		 <select class="bginput" name="configoption[lottery_amount_type]">
			<option value="MB"' . iif ($lottery_amount_type == 'MB', ' selected="selected"') . '>MB (Mega Byte)</option>
			<option value="GB"' . iif ($lottery_amount_type == 'GB', ' selected="selected"') . '>GB (Giga Byte)</option>
		</select>');
      print_rows ('Max. Ticket Per User?', show_helptip ('Enter: How many tickets can purchase an user?') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_max_tickets_per_user]" value="' . $lottery_max_tickets_per_user . '">');
      print_rows ('Max. Winners?', show_helptip ('Enter Max. Winners per a lottery.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_max_winners]" value="' . $lottery_max_winners . '">');
      print_rows ('Lottery Begin Date?', show_helptip ('Enter Lottery Begin Date.') . '
		<script language="JavaScript" src="scripts/calendar3.js"></script>
		 <input type="text" class="bginput" size="25" name="configoption[lottery_begin_date]" value="' . $lottery_begin_date . '">
		 <a href="javascript:lotterybegindate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">
			var siteadrr = "' . $BASEURL . '/admin/scripts/";
			var lotterybegindate = new calendar3(document.forms[\'lottery\'].elements[\'configoption[lottery_begin_date]\']);
			lotterybegindate.year_scroll = true;
			lotterybegindate.time_comp = true;
		</script>
		 ');
      print_rows ('Lottery End Date?', show_helptip ('Enter Lottery End Date.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_end_date]" value="' . $lottery_end_date . '">
		 <a href="javascript:lotteryenddate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">
			var siteadrr = "' . $BASEURL . '/admin/scripts/";
			var lotteryenddate = new calendar3(document.forms[\'lottery\'].elements[\'configoption[lottery_end_date]\']);
			lotteryenddate.year_scroll = true;
			lotteryenddate.time_comp = true;
		</script>
		 ');
      print_rows ('Lottery Last Winners?', show_helptip ('Do not edit this field which will be automaticly updated by the script.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_last_winners]" value="' . $lottery_last_winners . '">');
      print_rows ('Lottery Last Winners Amount?', show_helptip ('Do not edit this field which will be automaticly updated by the script.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_last_winners_amount]" value="' . $lottery_last_winners_amount . '">');
      print_rows ('Last Lottery End Date?', show_helptip ('Do not edit this field which will be automaticly updated by the script.') . '
		 <input type="text" class="bginput" size="25" name="configoption[lottery_last_winners_date]" value="' . $lottery_last_winners_date . '">
		 <a href="javascript:lastlotteryenddate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">
			var siteadrr = "' . $BASEURL . '/admin/scripts/";
			var lastlotteryenddate = new calendar3(document.forms[\'lottery\'].elements[\'configoption[lottery_last_winners_date]\']);
			lastlotteryenddate.year_scroll = true;
			lastlotteryenddate.time_comp = true;
		</script>
		 ');
      print_submit_rows ();
      break;
    }

    case 'freeleech':
    {
      require TSDIR . '/' . $cache . '/freeleech.php';
      print_rows ('Select System Type?', show_helptip ('Please select system type. Free Leech, Silver or DoubleUpload..') . '
		<select class="bginput" name="configoption[system]">
			<option value="freeleech"' . iif ($__FLSTYPE == 'freeleech', ' selected="selected"') . '>Free Leech</option>
			<option value="silverleech"' . iif ($__FLSTYPE == 'silverleech', ' selected="selected"') . '>Silver Leech</option>
			<option value="doubleupload"' . iif ($__FLSTYPE == 'doubleupload', ' selected="selected"') . '>Double Upload</option>
		</select>
		');
      print_rows ('Begin Date?', show_helptip ('Enter Free Days Begin Date. ALL torrents Will be free, silver or doubleupload...') . '
		<script language="JavaScript" src="scripts/calendar3.js"></script>
		 <input type="text" class="bginput" size="25" name="configoption[start]" value="' . ($__F_START != '0000-00-00 00:00:00' ? $__F_START : '') . '">
		 <a href="javascript:FLbegindate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">
			var siteadrr = "' . $BASEURL . '/admin/scripts/";
			var FLbegindate = new calendar3(document.forms[\'freeleech\'].elements[\'configoption[start]\']);
			FLbegindate.year_scroll = true;
			FLbegindate.time_comp = true;
		</script>
		 ');
      print_rows ('End Date?', show_helptip ('Enter Free Days End Date. Automatic System will be stoped at this date.') . '		
		 <input type="text" class="bginput" size="25" name="configoption[end]" value="' . ($__F_END != '0000-00-00 00:00:00' ? $__F_END : '') . '">
		 <a href="javascript:FLenddate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">			
			var FLenddate = new calendar3(document.forms[\'freeleech\'].elements[\'configoption[end]\']);
			FLenddate.year_scroll = true;
			FLenddate.time_comp = true;
		</script>
		 ');
      print_submit_rows ();
      break;
    }

    case 'shoutcast':
    {
      require_once INC_PATH . '/readconfig_shoutcast.php';
      if ($s_allowedusergroups = explode (',', $s_allowedusergroups))
      {
      }
      else
      {
        $s_allowedusergroups = array ();
      }

      $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
      $scount = 1;
      $sgids = '
		<fieldset>
			<legend>Select Usergroup(s)</legend>
				<table border="0" cellspacing="0" cellpadding="2" width="100%">
					<tr>';
      while ($gid = mysql_fetch_assoc ($squery))
      {
        if ($scount % 4 == 1)
        {
          $sgids .= '</tr><tr>';
        }

        $sgids .= '	
			<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"' . (in_array ($gid['gid'], $s_allowedusergroups, true) ? ' checked="checked"' : '') . '></td>
			<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
        ++$scount;
      }

      $sgids .= '
					</tr>
				</table>
		</fieldset>';
      print_rows ('Server Name', show_helptip ('Default station name to display when server or stream is down.') . '
		<input type="text" class="bginput" size="45" name="configoption[s_servername]" value="' . $s_servername . '" /> Please open shoutcast/flashradioconfig.xml file and enter your details there too.
		');
      print_rows ('Server IP', show_helptip ('IP or URL of shoutcast server. <br /><br />Example: www.myshoutcastserver.com<br />Example: 122.155.666.5') . '
		<input type="text" class="bginput" size="45" name="configoption[s_serverip]" value="' . $s_serverip . '" /> Please do not use http://. Click on ? Icon to get more info.
		');
      print_rows ('Server PORT', show_helptip ('PORT of shoutcast server.') . '
		<input type="text" class="bginput" size="45" name="configoption[s_serverport]" value="' . $s_serverport . '" />
		');
      print_rows ('Server Password', show_helptip ('Password to shoutcast server.') . '
		<input type="text" class="bginput" size="45" name="configoption[s_serverpassword]" value="' . $s_serverpassword . '" />
		');
      print_rows ('Server Cache File', show_helptip ('Please enter in the Cache File name of your shoutcast station.<br>Note: CHMOD 777 to not get errors. /shoutcast/' . $s_servercachefile . ' and /shoutcast/lps.dat') . '
		<input type="text" class="bginput" size="45" name="configoption[s_servercachefile]" value="' . $s_servercachefile . '" /> Please Chmod 777 to following files: shoutcast/' . $s_servercachefile . ' and shoutcast/lps.dat
		');
      print_rows ('Server Update', show_helptip ('Please enter in the Update time of your shoutcast station.<br>Note: seconds untill cache update.') . '
		<input type="text" class="bginput" size="45" name="configoption[s_servercachetime]" value="' . $s_servercachetime . '" />
		');
      print_rows ('IRC Site', show_helptip ('Please enter your IRC Site URL.') . '
		<input type="text" class="bginput" size="45" name="configoption[s_serverirc]" value="' . $s_serverirc . '" />
		');
      print_rows ('Usergroup Alloweds to View', show_helptip ('Select usergroups that you can see the shoutcast server page.') . '
		' . $sgids);
      print_submit_rows ();
      break;
    }

    case 'pjirc':
    {
      require INC_PATH . '/readconfig_pjirc.php';
      print_rows ('IRC Host?', show_helptip ('Example: irc.p2p-irc.net') . '
		<input type="text" class="bginput" size="25" name="configoption[pjirchost]" value="' . $pjirchost . '">');
      print_rows ('IRC Channel?', show_helptip ('Example: #templateshares') . '
		<input type="text" class="bginput" size="25" name="configoption[pjircchannel]" value="' . $pjircchannel . '">');
      print_row_header ('Setup Your IRC Bot');
      print_rows ('IRC Bot Enabled?', show_helptip ('Announce new uploads on IRC server.') . '
		<select class="bginput" name="configoption[ircbot]">
						<option value="yes"' . iif ($ircbot == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($ircbot == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Bot IP?', show_helptip ('Enter your Bot Ip address here.') . '
		<input type="text" class="bginput" size="25" name="configoption[botip]" value="' . $botip . '">');
      print_rows ('Bot Port?', show_helptip ('Enter your Bot Port number here') . '
		<input type="text" class="bginput" size="25" name="configoption[botport]" value="' . $botport . '">');
      print_submit_rows ();
      break;
    }

    case 'redirect':
    {
      require INC_PATH . '/readconfig_redirect.php';
      print_rows ('Redirect System Enabled?', show_helptip ('If you want to anonymize URL\\\'s, enable this feature.') . '
		<select class="bginput" name="configoption[redirect]">
						<option value="yes"' . iif ($redirect == 'yes', ' selected="selected"') . '>Yes</option>
						<option value="no"' . iif ($redirect == 'no', ' selected="selected"') . '>No</option>
					</select>');
      print_rows ('Local Addresses?', show_helptip ('Local addresses are not anonymized.<br /><br />Do not use a http:// prefix.<br />If you start the name with a dot (.mydomain.com) all server addresses ending with this will be considered local.<br />If you want to add mulitple domain/servernames, seperate them by a single space.') . '
		<textarea class="bginput" name="configoption[localaddresses]" rows="4" cols="75">' . $localaddresses . '</textarea>');
      print_rows ('Protocols to Ignore?', show_helptip ('You can put a list of protocols that dont need to be anonymized here seperated by commas. <br />Example: ftp, ed2k, https.') . '
		<textarea class="bginput" name="configoption[protocol]" rows="4" cols="75">' . $protocol . '</textarea>');
      print_submit_rows ();
      break;
    }

    case 'hitrun':
    {
      readconfig ('HITRUN');
      if ($HRSkipUsergroups = explode (',', $HITRUN['HRSkipUsergroups']))
      {
      }
      else
      {
        $HRSkipUsergroups = array ();
      }

      $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
      $scount = 1;
      $sgids = '
		<fieldset>
			<legend>Select Usergroup(s)</legend>
				<table border="0" cellspacing="0" cellpadding="2" width="100%">
					<tr>';
      while ($gid = mysql_fetch_assoc ($squery))
      {
        if ($scount % 4 == 1)
        {
          $sgids .= '</tr><tr>';
        }

        $sgids .= '	
			<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"' . (in_array ($gid['gid'], $HRSkipUsergroups, true) ? ' checked="checked"' : '') . '></td>
			<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
        ++$scount;
      }

      $sgids .= '
					</tr>
				</table>
		</fieldset>';
      print_rows ('Enable Auto Hit-RUN Feature?', show_helptip ('You might want to enable/disable this feature.') . '	
			<select name="configoption[Enabled]" class="bginput">
				<option value="yes"' . ($HITRUN['Enabled'] == 'yes' ? ' selected="selected"' : '') . '>Yes</option>
				<option value="no"' . ($HITRUN['Enabled'] == 'no' ? ' selected="selected"' : '') . '>No</option>
			</select>
		 ');
      print_rows ('Minimum Ratio Per Torrent?', show_helptip ('Please Enter Minimum Ratio. User must get X ratio after finished. Default: 0.5 (Leave 0 to disable this system)') . ' <input type="text" name="configoption[MinRatio]" value="' . (isset ($HITRUN['MinRatio']) ? $HITRUN['MinRatio'] : '0.5') . '" class="bginput" />');
      print_rows ('Minimum Seed Time Per Torrent?', show_helptip ('Please Enter Minimum Seed Time In Hours. User must seed a torrent at least X hours after finished. Default: 24 (Leave 0 to disable this system)') . ' <input type="text" name="configoption[MinSeedTime]" value="' . (isset ($HITRUN['MinSeedTime']) ? $HITRUN['MinSeedTime'] : 24) . '" class="bginput" />');
      print_rows ('Minimum Finish Date?', show_helptip ('Check date of finished torrents (Do not count warns for old torrents), this option will allow system to save queries. Set this 0 to count ALL torrents (not recommend)') . '
		<script language="JavaScript" src="scripts/calendar3.js"></script>
		 <input type="text" class="bginput" size="25" name="configoption[MinFinishDate]" value="' . date ('Y-m-d H:i:s', ($HITRUN['MinFinishDate'] ? $HITRUN['MinFinishDate'] : time ())) . '">
		 <a href="javascript:FinishDate.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
		 <script type="text/javascript">
			var siteadrr = "' . $BASEURL . '/admin/scripts/";
			var FinishDate = new calendar3(document.forms[\'hitrun\'].elements[\'configoption[MinFinishDate]\']);
			FinishDate.year_scroll = true;
			FinishDate.time_comp = true;
		</script>
		 ');
      print_rows ('Skip (Protect) Usergroups?', show_helptip ('Select usergroups to skip from Automatic HIT & RUN System. Do not warn them.') . '
		 ' . $sgids);
      print_submit_rows ();
    }
  }

  table_close (false);
  close_form ();
  admin_cp_footer (true);
  exit ();
?>
