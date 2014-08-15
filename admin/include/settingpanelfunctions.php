<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_smilies_cache ($array = array ())
  {
    global $SmilieDir;
    global $cache;
    global $rootpath;
    global $pic_base_url;
    if (!is_dir ($SmilieDir))
    {
      $SmilieDir = $rootpath . $pic_base_url . 'smilies';
    }

    $SimilieArray = '$smilies = array (';
    if (count ($array) == 0)
    {
      $query = sql_query ('SELECT stext, spath FROM ts_smilies ORDER BY sorder, stitle');
      while ($Sml = mysql_fetch_assoc ($query))
      {
        $SimilieArray2[] = '\'' . $Sml['stext'] . '\' => \'' . $Sml['spath'] . '\'';
      }
    }
    else
    {
      foreach ($array as $Smiliename => $file)
      {
        $SimilieArray2[] = '\'' . $Smiliename . '\' => \'' . $file . '\'';
      }
    }

    $SimilieArray = $SimilieArray . implode (', ', $SimilieArray2) . ');';
    $_filename = TSDIR . '/' . $cache . '/smilies.php';
    $_cachefile = @fopen ($_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#14 - Do Not Alter
 * Cache Name: Smilies
 * Generated: ' . gmdate ('r') . '
*/
';
    $_cachecontents .= '' . $SimilieArray . '
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function show_helptip ($text, $title = 'Quick Help', $width = 500, $style = 'style=" float: right;"')
  {
    global $BASEURL;
    global $pic_base_url;
    return '
	<span align="justify"' . $style . '>
	<a href="javascript:void(0);" onmouseover="Tip(\'' . $text . '\', WIDTH, ' . $width . ', TITLE, \'' . $title . '\', SHADOW, false, FADEIN, 300, FADEOUT, 300, STICKY, 1, OFFSETX, -20, CLOSEBTN, true, CLICKCLOSE, true)"><img src="' . $BASEURL . '/' . $pic_base_url . 'help_on.gif" border="0" style="vertical-align: middle;"></a></span>';
  }

  function print_submit_rows ($colspan = 2, $class = 'button')
  {
    echo '
	<td class="tdclass1" align="center" valign="top" width="100%" colspan="' . $colspan . '"><input type="submit" value="save" class="' . $class . '"> <input type="reset" value="reset" class="' . $class . '"></td>
	';
  }

  function print_row_header ($text, $colspan = 2)
  {
    echo '
	<tr>
		<td colspan="' . $colspan . '" align="center" class="thead">' . $text . '</td>
	</tr>
	';
  }

  function print_rows ($a, $b, $width1 = '20%', $width2 = '80%', $class1 = 'tdclass1', $class2 = 'tdclass2', $align1 = 'left', $align2 = 'left', $valign = 'top')
  {
    $output = '
	<tr>
	';
    if ((is_array ($a) AND is_array ($b)))
    {
      $array = array_combine ($a, $b);
      foreach ($array as $left => $right)
      {
        $output .= '
			<td align="' . $align1 . '" width="' . $width1 . '" class="' . $class1 . '" valign="' . $valign . '">' . $left . '</td>
			<td align="' . $align2 . '" width="' . $width2 . '" class="' . $class2 . '" valign="' . $valign . '">' . $right . '</td>
			';
      }
    }
    else
    {
      $output .= '
			<td align="' . $align1 . '" width="' . iif ($b, $width1, '100%') . '" class="' . $class1 . '" valign="' . $valign . '">' . $a . '</td>
			' . iif ($b, '<td align="' . $align2 . '" width="' . $width2 . '" class="' . $class2 . '" valign="' . $valign . '">' . $b . '</td>') . '
			';
    }

    $output .= '
	</tr>';
    echo $output;
  }

  function table_open ($title = '', $tr = true, $colspan = 2, $class = 'thead', $border = 1, $width = '100%', $height = '', $align = 'center', $cellpadding = 5, $cellspacing = 0)
  {
    echo '
	<table width="' . $width . '"' . iif ($height, ' height="' . $height . '"') . ' cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '" border="' . $border . '">
		' . iif ($tr, '
		<tr>
			<td class="' . $class . '" align="' . $align . '" colspan="' . $colspan . '"><strong>' . $title . '</strong></td>
		</tr>') . '
	';
  }

  function table_close ($br = true)
  {
    echo '
	</table>
	' . iif ($br, '<br />') . '
	';
  }

  function construct_select_options ($array, $selectedid = '', $htmlise = false)
  {
    if (is_array ($array))
    {
      $options = '';
      foreach ($array as $key => $val)
      {
        if (is_array ($val))
        {
          $options .= '		<optgroup label="' . iif ($htmlise, htmlspecialchars_uni ($key), $key) . '">
';
          $options .= construct_select_options ($val, $selectedid, $tabindex, $htmlise);
          $options .= '		</optgroup>
';
          continue;
        }
        else
        {
          if (is_array ($selectedid))
          {
            $selected = iif (in_array ($key, $selectedid), ' selected="selected"', '');
          }
          else
          {
            $selected = iif ($key == $selectedid, ' selected="selected"', '');
          }

          $options .= '		<option value="' . iif ($key !== 'no_value', $key) . (('' . '"') . $selected . '>') . iif ($htmlise, htmlspecialchars_uni ($val), $val) . '</option>
';
          continue;
        }
      }
    }

    return $options;
  }

  function open_form ($configname)
  {
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=' . $configname . '&sessionhash=' . session_id () . '&tshash=' . $_SESSION['hash'] . '" name="' . strtolower ($configname) . '">
	<input type="hidden" name="configname" value="' . strtoupper ($configname) . '">
	<input type="hidden" name="do" value="' . $configname . '">';
  }

  function close_form ()
  {
    echo '
	</form>
	';
  }

  function save_settings ($do = '', $log = true, $writemsg = true)
  {
    global $_POST;
    global $CURUSER;
    global $BASEURL;
    global $rootpath;
    global $cache;
    if (@preg_match ('#^DATET+#', $_POST['configname']))
    {
      $CFGname = 'DATETIME';
    }
    else
    {
      $CFGname = strtoupper ($_POST['configname']);
    }

    if ($CFGname == 'STAFFTEAM')
    {
      $STAFFTEAM = array ();
      $_Query = sql_query ('SELECT u.id, u.username, g.gid FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\' AND (g.cansettingspanel = \'yes\' OR g.issupermod = \'yes\' OR g.canstaffpanel = \'yes\')');
      if (0 < mysql_num_rows ($_Query))
      {
        while ($Buffer = mysql_fetch_assoc ($_Query))
        {
          $STAFFTEAM[$Buffer['id']][$Buffer['username']] = $Buffer['gid'];
        }

        $i = 0;
        while ($i < count ($_POST['staffnames']))
        {
          if (($_POST['staffids'][$i] AND $_POST['staffnames'][$i]))
          {
            if (isset ($STAFFTEAM[$_POST['staffids'][$i]][$_POST['staffnames'][$i]]))
            {
              $save[] = $_POST['staffnames'][$i] . ':' . $_POST['staffids'][$i];
            }
            else
            {
              admin_cp_critical_error ('Invalid Username of Userid Entered. ' . htmlspecialchars_uni ($_POST['staffnames'][$i] . ' : ' . $_POST['staffids'][$i]));
            }
          }

          ++$i;
        }

        $staffteam = @implode (',', $save);
        $filename = CONFIG_DIR . '/STAFFTEAM';
        if (is_writable ($filename))
        {
          if (!$handle = fopen ($filename, 'w'))
          {
            admin_cp_critical_error ('' . 'Cannot open file (' . $filename . ')');
          }

          if (fwrite ($handle, $staffteam) === FALSE)
          {
            admin_cp_critical_error ('' . 'Cannot write to file (' . $filename . ')');
          }

          fclose ($handle);
        }
        else
        {
          admin_cp_critical_error ('' . 'The file ' . $filename . ' is not writable!');
        }
      }
      else
      {
        admin_cp_critical_error ('There is no any Staff Member.');
      }
    }
    else
    {
      if ($CFGname == 'FREELEECH')
      {
        $_START = $_POST['configoption']['start'];
        $_END = $_POST['configoption']['end'];
        $_FLSTYPE = $_POST['configoption']['system'];
        $_filename = TSDIR . '/' . $cache . '/freeleech.php';
        $_cachefile = @fopen ('' . $_filename, 'w');
        $_cachecontents = '<?php
/** TS Generated Cache#10 - Do Not Alter
 * Cache Name: FreeLeech
 * Generated: ' . gmdate ('r') . '
*/
';
        $_cachecontents .= '$__FLSTYPE = \'' . $_FLSTYPE . '\';
';
        $_cachecontents .= '$__F_START = \'' . $_START . '\';
';
        $_cachecontents .= '$__F_END = \'' . $_END . '\';
?>';
        @fwrite ($_cachefile, $_cachecontents);
        @fclose ($_cachefile);
      }
      else
      {
        if ($CFGname == 'PINCODE')
        {
          $pincode1 = trim ($_POST['configoption']['pincode1']);
          $re_pincode1 = trim ($_POST['configoption']['re_pincode1']);
          $pincode2 = trim ($_POST['configoption']['pincode2']);
          $re_pincode2 = trim ($_POST['configoption']['re_pincode2']);
          $sechash = md5 ($SITENAME);
          if (((!empty ($pincode1) AND !empty ($re_pincode1)) AND $pincode1 === $re_pincode1))
          {
            $pincode_s = md5 (md5 ($sechash) . md5 ($pincode1));
            $get_s = sql_query ('SELECT * FROM pincode WHERE area = 1 LIMIT 1');
            if (1 <= mysql_num_rows ($get_s))
            {
              sql_query ('UPDATE pincode SET pincode = ' . sqlesc ($pincode_s) . ', sechash = ' . sqlesc ($sechash) . ', area = 1 WHERE area = 1 LIMIT 1');
            }
            else
            {
              sql_query ('INSERT INTO pincode (pincode,sechash,area) VALUES (' . sqlesc ($pincode_s) . ', ' . sqlesc ($sechash) . ', 1)');
            }
          }

          if (((!empty ($pincode2) AND !empty ($re_pincode2)) AND $pincode2 === $re_pincode2))
          {
            $pincode_d = md5 (md5 ($sechash) . md5 ($pincode2));
            $get_d = sql_query ('SELECT * FROM pincode WHERE area = 2 LIMIT 1');
            if (1 <= mysql_num_rows ($get_d))
            {
              sql_query ('UPDATE pincode SET pincode = ' . sqlesc ($pincode_d) . ', sechash = ' . sqlesc ($sechash) . ', area = 2 WHERE area = 2 LIMIT 1');
            }
            else
            {
              sql_query ('INSERT INTO pincode (pincode,sechash,area) VALUES (' . sqlesc ($pincode_d) . ', ' . sqlesc ($sechash) . ', 2)');
            }
          }
        }
        else
        {
          if ($CFGname == 'LOTTERY')
          {
            if (is_array ($_POST['usergroup']))
            {
              $_value = array ();
              foreach ($_POST['usergroup'] as $ugid)
              {
                $_value[] = '[' . $ugid . ']';
              }

              $_POST['configoption']['lottery_allowed_usergroups'] = implode (',', $_value);
            }
          }

          if ($CFGname == 'HITRUN')
          {
            $_POST['configoption']['MinFinishDate'] = strtotime ($_POST['configoption']['MinFinishDate']);
            if (is_array ($_POST['usergroup']))
            {
              $_value = array ();
              foreach ($_POST['usergroup'] as $ugid)
              {
                $_value[] = $ugid;
              }

              $_POST['configoption']['HRSkipUsergroups'] = implode (',', $_value);
            }
          }

          if ($CFGname == 'SHOUTCAST')
          {
            if (is_array ($_POST['usergroup']))
            {
              $_value = array ();
              foreach ($_POST['usergroup'] as $ugid)
              {
                $_value[] = $ugid;
              }

              $_POST['configoption']['s_allowedusergroups'] = implode (',', $_value);
            }
          }

          $array = array ();
          foreach ($_POST['configoption'] as $configoption => $configvalue)
          {
            $array['' . $configoption] = $configvalue;
          }

          require_once INC_PATH . '/functions_writeconfig.php';
          writeconfig ($CFGname, $array);
          rebuild_announce_settings ();
        }
      }
    }

    $msg = $CFGname . ' Settings has been saved!';
    if ($log)
    {
      write_log ('' . $msg . ' (' . $CURUSER['username'] . ')');
    }

    if ($writemsg)
    {
      admin_cp_redirect ($do, $msg);
      exit ();
    }

  }

  function admin_cp_redirect ($do = '', $msg = '', $extra = '', $delay = '3000')
  {
    global $BASEURL;
    echo '
		<script type="text/javascript">
		<!--
			function delayer()
			{
				window.location = "' . $BASEURL . '/admin/managesettings.php?do=' . $do . ($extra ? '&' . $extra : '') . '&sessionhash=' . session_id () . '&tshash=' . $_SESSION['hash'] . '"
			}
			setTimeout("delayer()", ' . $delay . ')
		//-->
		</script>
		<div class="bluediv"><font color="red"><strong>' . $msg . '</strong></font><br /><br />
		Redirecting....</div>
		';
  }

  function admin_cp_header ($headarea = '', $title = 'TS SE Admin Control Panel', $target = '<base target="main" />', $body = ' class="yui-skin-sam"')
  {
    echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html dir="ltr" lang="en">
		<head>
			<title>' . $title . '</title>
			<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
			<link rel="stylesheet" type="text/css" href="./templates/default.css" />
			<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/menu/assets/skins/sam/menu.css" />
			<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/button/assets/skins/sam/button.css" />
			<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/fonts/fonts-min.css" />
			<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/container/assets/skins/sam/container.css" />
			<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/editor/assets/skins/sam/editor.css" />
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/element/element-beta-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/menu/menu-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/button/button-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/editor/editor-min.js"></script> 
			' . $target . '
			' . $headarea . '
		</head>
		<body' . $body . '>
	';
  }

  function admin_cp_footer ($showcopyright = false)
  {
    echo ($showcopyright ? '
		<div class="tdclass2" align="center" style="border: 2px solid rgb(131, 168, 204);">Powered by ' . O_VERSION . ' © 2006-' . date ('Y') . ' <a href="' . TEMPLATESHARES . '" target="_blank">Template Shares</a></div><br />' : '') . '
		</body>
	</html>
	';
  }

  function admin_collapse ($id, $type = 1)
  {
    global $BASEURL;
    global $pic_base_url;
    global $tscollapse;
    if ($type === 1)
    {
      return '<span style="float: right;"><img id="collapseimg_' . $id . '" src="' . $BASEURL . '/' . $pic_base_url . 'minus' . $tscollapse['collapseimg_' . $id . ''] . '.gif" alt="" border="0" onclick="return toggle_collapse(\'' . $id . '\')" /></span>';
    }

    if ($type === 2)
    {
      return '<tbody id="collapseobj_' . $id . '" style="' . ($tscollapse['' . 'collapseobj_' . $id] ? $tscollapse['' . 'collapseobj_' . $id] : 'none') . '">';
    }

  }

  function make_nav_menu ($first, $second, $islink = false)
  {
    echo '
	<tr>
		<td class="tdclass1">
			' . (!$islink ? frame_link ($first, $second) : $islink) . '
		</td>
	</tr>
	';
  }

  function frame_link ($do, $dotext)
  {
    return '
	<span class="smallfont">
		<a href="managesettings.php?do=' . $do . '&amp;sessionhash=' . session_id () . '&amp;tshash=' . $_SESSION['hash'] . '">' . $dotext . '</a>
	</span>
	';
  }

  function admin_cp_critical_error ($text)
  {
    admin_cp_header ();
    echo '
	<div class="bluediv"><font color="red">' . $text . '</font></div>
	';
    admin_cp_footer (true);
    exit ();
  }

  function calc_cron_time ($stamp)
  {
    $ysecs = 365 * 24 * 60 * 60;
    $mosecs = 31 * 24 * 60 * 60;
    $wsecs = 7 * 24 * 60 * 60;
    $dsecs = 24 * 60 * 60;
    $hsecs = 60 * 60;
    $msecs = 60;
    $years = floor ($stamp / $ysecs);
    $stamp %= $ysecs;
    $months = floor ($stamp / $mosecs);
    $stamp %= $mosecs;
    $weeks = floor ($stamp / $wsecs);
    $stamp %= $wsecs;
    $days = floor ($stamp / $dsecs);
    $stamp %= $dsecs;
    $hours = floor ($stamp / $hsecs);
    $stamp %= $hsecs;
    $minutes = floor ($stamp / $msecs);
    $stamp %= $msecs;
    $seconds = $stamp;
    return array ('years' => $years, 'months' => $months, 'weeks' => $weeks, 'days' => $days, 'hours' => $hours, 'minutes' => $minutes);
  }

  function iif ($expression, $returntrue, $returnfalse = '')
  {
    return ($expression ? $returntrue : $returnfalse);
  }

  function get_count ($name, $where = '', $extra = '')
  {
    ($res = sql_query ('SELECT COUNT(*) as ' . $name . ' FROM ' . $where . ' ' . ($extra ? $extra : '')) OR sqlerr (__FILE__, 490));
    list ($info[$name]) = mysql_fetch_array ($res);
    return $info[$name];
  }

  function get_server_load ()
  {
    if (strtolower (substr (PHP_OS, 0, 3)) === 'win')
    {
      return '<font color=red>Unknown</font>';
    }

    if (@file_exists ('/proc/loadavg'))
    {
      $load = @file_get_contents ('/proc/loadavg');
      $serverload = explode (' ', $load);
      $serverload[0] = round ($serverload[0], 4);
      if (!$serverload)
      {
        $load = @exec ('uptime');
        $load = split ('load averages?: ', $load);
        $serverload = explode (',', $load[1]);
      }
    }
    else
    {
      $load = @exec ('uptime');
      $load = split ('load averages?: ', $load);
      $serverload = explode (',', $load[1]);
    }

    $returnload = trim ($serverload[0]);
    if (!$returnload)
    {
      $returnload = '<font color=red>Unknown</font>';
    }

    return $returnload;
  }

  function rebuild_announce_settings ()
  {
    clearstatcache ();
    $var_array = unserialize (file_get_contents (CONFIG_DIR . '/MAIN'));
    extract ($var_array, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array1 = unserialize (file_get_contents (CONFIG_DIR . '/WAITSLOT'));
    extract ($var_array1, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array2 = unserialize (file_get_contents (CONFIG_DIR . '/KPS'));
    extract ($var_array2, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array3 = unserialize (file_get_contents (CONFIG_DIR . '/TWEAK'));
    extract ($var_array3, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array4 = unserialize (file_get_contents (CONFIG_DIR . '/DATABASE'));
    extract ($var_array4, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array5 = unserialize (file_get_contents (CONFIG_DIR . '/EXTRA'));
    extract ($var_array5, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array6 = unserialize (file_get_contents (CONFIG_DIR . '/SECURITY'));
    extract ($var_array6, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array7 = unserialize (file_get_contents (CONFIG_DIR . '/THEME'));
    extract ($var_array7, EXTR_PREFIX_SAME, 'wddx');
    clearstatcache ();
    $var_array8 = unserialize (file_get_contents (CONFIG_DIR . '/ANNOUNCE'));
    extract ($var_array8, EXTR_PREFIX_SAME, 'wddx');
    if (!file_exists (INC_PATH . '/config_announce.php'))
    {
      $mode = 'x';
    }
    else
    {
      $mode = 'w';
    }

    $settings = '';
    $settings .= '$announce_interval = ' . $announce_interval . ';';
    $settings .= '$BASEURL = \'' . $BASEURL . '\';';
    $settings .= '$SITENAME = \'' . $SITENAME . '\';';
    $settings .= '$waitsystem = \'' . $waitsystem . '\';';
    $settings .= '$maxdlsystem = \'' . $maxdlsystem . '\';';
    $settings .= '$mysql_host = \'' . $mysql_host . '\';';
    $settings .= '$mysql_user = \'' . $mysql_user . '\';';
    $settings .= '$mysql_pass = \'' . $mysql_pass . '\';';
    $settings .= '$mysql_db = \'' . $mysql_db . '\';';
    $settings .= '$nc = \'' . $nc . '\';';
    $settings .= '$privatetrackerpatch = \'' . $privatetrackerpatch . '\';';
    $settings .= '$bannedclientdetect = \'' . $bannedclientdetect . '\';';
    $settings .= '$allowed_clients = \'' . $allowed_clients . '\';';
    $settings .= '$snatchmod = \'' . $snatchmod . '\';';
    $settings .= '$announce_actions = \'' . $announce_actions . '\';';
    $settings .= '$max_rate = \'' . $max_rate . '\';';
    $settings .= '$waitsystemtype = \'' . $waitsystemtype . '\';';
    $settings .= '$ratio1 = \'' . $ratio1 . '\';';
    $settings .= '$ratio2 = \'' . $ratio2 . '\';';
    $settings .= '$ratio3 = \'' . $ratio3 . '\';';
    $settings .= '$ratio4 = \'' . $ratio4 . '\';';
    $settings .= '$ratio5 = \'' . $ratio5 . '\';';
    $settings .= '$ratio6 = \'' . $ratio6 . '\';';
    $settings .= '$ratio7 = \'' . $ratio7 . '\';';
    $settings .= '$ratio8 = \'' . $ratio8 . '\';';
    $settings .= '$upload1 = \'' . $upload1 . '\';';
    $settings .= '$upload2 = \'' . $upload2 . '\';';
    $settings .= '$upload3 = \'' . $upload3 . '\';';
    $settings .= '$upload4 = \'' . $upload4 . '\';';
    $settings .= '$upload5 = \'' . $upload5 . '\';';
    $settings .= '$upload6 = \'' . $upload6 . '\';';
    $settings .= '$upload7 = \'' . $upload7 . '\';';
    $settings .= '$upload8 = \'' . $upload8 . '\';';
    $settings .= '$delay1 = \'' . $delay1 . '\';';
    $settings .= '$delay2 = \'' . $delay2 . '\';';
    $settings .= '$delay3 = \'' . $delay3 . '\';';
    $settings .= '$delay4 = \'' . $delay4 . '\';';
    $settings .= '$slot1 = \'' . $slot1 . '\';';
    $settings .= '$slot2 = \'' . $slot2 . '\';';
    $settings .= '$slot3 = \'' . $slot3 . '\';';
    $settings .= '$slot4 = \'' . $slot4 . '\';';
    $settings .= '$announce_wait = \'' . $announce_wait . '\';';
    $settings .= '$gzipcompress = \'' . $gzipcompress . '\';';
    $settings .= '$defaultlanguage = \'' . $defaultlanguage . '\';';
    $settings .= '$charset = \'' . $charset . '\';';
    $settings .= '$aggressivecheat = \'' . $aggressivecheat . '\';';
    $settings .= '$aggressivecheckip = \'' . $aggressivecheckip . '\';';
    $settings .= '$cache = \'' . $cache . '\';';
    $settings .= '$bdayreward = \'' . $bdayreward . '\';';
    $settings .= '$bdayrewardtype = \'' . $bdayrewardtype . '\';';
    $settings .= '$bonus = \'' . $bonus . '\';';
    $settings .= '$kpsseed = \'' . $kpsseed . '\';';
    $settings .= '$detectbrowsercheats = \'' . $detectbrowsercheats . '\';';
    $settings .= '$checkconnectable = \'' . $checkconnectable . '\';';
    $settings .= '$checkip = \'' . $checkip . '\';';
    $settings = '<' . ('' . '?php #DO NOT EDIT THIS FILE, PLEASE USE THE SETTINGS PANEL!!
if(!defined(\'IN_ANNOUNCE\')) die(\'Hacking attempt!\');' . $settings . '?') . '>';
    $file = @fopen (INC_PATH . '/config_announce.php', $mode);
    @fwrite ($file, $settings);
    @fclose ($file);
  }

  function show_frame_logo ()
  {
    $image = base64_decode ('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');
    @header ('Content-Type: image/png');
    echo $image;
    exit ();
  }

  if ((@array_key_exists ('show_frame_logo', $_GET) AND $_GET['show_frame_logo'] == 1))
  {
    show_frame_logo ();
    exit ();
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  @define ('TEMPLATESHARES', 'oops');
  if (!function_exists ('array_combine'))
  {
    function array_combine ($arr1, $arr2)
    {
      $out = array ();
      $arr1 = array_values ($arr1);
      $arr2 = array_values ($arr2);
      foreach ($arr1 as $key1 => $value1)
      {
        $out[(string)$value1] = $arr2[$key1];
      }

      return $out;
    }
  }

?>
