<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function admin_scripts ()
  {
    global $lang;
    echo '
	<script type="text/javascript">
		function ts_check_field(TSarrayLength)
		{			
			for (var TSloop=1; TSloop <= TSarrayLength; TSloop++)
			{
				var checkField = document.forms[0].elements[TSloop];
				if (checkField.value == "")
				{
					alert("Please don\'t leave required fields blank!\\n\\nEmpty Field: "+checkField.name);
					document.forms[0].elements[TSloop].focus();
					return false;
				}
			}
		};
	</script>
	';
  }

  function get_list ()
  {
    global $thispath;
    global $_this_script_no_act;
    global $CURUSER;
    global $eol;
    $query = sql_query ('SELECT * FROM staffpanel WHERE usergroups LIKE \'%[' . intval ($CURUSER['usergroup']) . ']%\' ORDER BY name');
    $str = '
	<style type="text/css">
	.alt1, .alt1Active
	{
		background: #ffffff;
		color: #000000;
		cursor: pointer;
		font: 8pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		border: 1px solid #AEB6CD;
	}
	.alt2, .alt2Active
	{
		background: #ec1308;
		color: #ffffff;
		cursor: pointer;
		font: 8pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		border: 1px solid #AEB6CD;
	}
	.smalltext
	{
		font: 7pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		color: #848282;
	}
	</style>' . $eol;
    $count = 0;
    $str .= '<tr>';
    while ($tools = mysql_fetch_array ($query))
    {
      $usergroups = explode (',', $tools['usergroups']);
      if (((@file_exists ($thispath . $tools['filename']) AND strstr ($tools['usergroups'], '[' . $CURUSER['usergroup'] . ']')) AND in_array ('[' . $CURUSER['usergroup'] . ']', $usergroups, true)))
      {
        if (($count AND $count % 4 == 0))
        {
          $str .= '</tr><tr>' . $eol;
        }

        $str .= '<td class="alt1Active" onmouseover="this.className=\'alt2Active\';" onmouseout="this.className=\'alt1Active\';" onclick="window.location.href=\'' . $_this_script_no_act . '?act=' . $tools['name'] . '\';">' . strtoupper ($tools['name']) . '<p class="smalltext">' . $tools['description'] . '</p></td>' . $eol;
        ++$count;
        continue;
      }
    }

    $str .= '</tr>' . $eol;
    $str .= '<tr><td colspan="6" align="center" class="alt1Active">Total ' . $count . ' tools found.</td></tr>' . $eol;
    echo $str;
  }

  function get_list2 ()
  {
    global $thispath;
    global $_this_script_;
    global $_this_script_no_act;
    global $eol;
    $query = sql_query ('SELECT * FROM staffpanel ORDER BY name');
    $str = '
	<style type="text/css">
	.alt1, .alt1Active
	{
		background: #ffffff;
		color: #000000;
		cursor: pointer;
		font: 8pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		border: 1px solid #AEB6CD;
	}
	.alt2, .alt2Active
	{
		background: #ec1308;
		color: #ffffff;
		cursor: pointer;
		font: 8pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		border: 1px solid #AEB6CD;
	}
	.smalltext
	{
		font: 7pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		color: #848282;
	}
	</style>' . $eol;
    $count = 0;
    $str .= '<tr>';
    while ($tools = mysql_fetch_array ($query))
    {
      if (@file_exists ($thispath . $tools['filename']))
      {
        $usergroups = str_replace (array ('[', ']'), '', $tools['usergroups']);
        if (($count AND $count % 2 == 0))
        {
          $str .= '</tr><tr>' . $eol;
        }

        $str .= '<td>' . strtoupper ($tools['name']) . '<p class="smalltext">' . $tools['description'] . '</p>Usergroups: <b>' . $usergroups . '</b></td>
			<td class="alt1Active" onmouseover="this.className=\'alt2Active\';" onmouseout="this.className=\'alt1Active\';" onclick="window.location.href=\'' . $_this_script_ . '&do=edit&id=' . $tools['id'] . '\';">Edit</td>
			<td class="alt1Active" onmouseover="this.className=\'alt2Active\';" onmouseout="this.className=\'alt1Active\';" onclick="window.location.href=\'' . $_this_script_ . '&do=delete&id=' . $tools['id'] . '\';">Delete</td>			
			' . $eol;
        ++$count;
        continue;
      }
    }

    $str .= '</tr>' . $eol;
    $str .= '<tr><td colspan="6" align="center" class="alt1Active">Total ' . $count . ' tools found.</td></tr>' . $eol;
    echo $str;
  }

  function _end_ ($head = true)
  {
    if ($head)
    {
      stdhead ('Permission Denied!');
    }

    echo '<br /><font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> You have no permission!</font><br />';
    if ($head)
    {
      stdfoot ();
    }

    exit ();
  }

  function _access_check_ ()
  {
    global $usergroups;
    if ($usergroups['cansettingspanel'] != 'yes')
    {
      print_no_permission (true);
      exit ();
      return null;
    }

  }

  function _file_access_check_ ($name)
  {
    global $CURUSER;
    $query = sql_query ('SELECT usergroups FROM staffpanel WHERE name = ' . sqlesc ($name));
    if (mysql_num_rows ($query) == 0)
    {
      return null;
    }

    $result = mysql_fetch_assoc ($query);
    $usergroups = explode (',', $result['usergroups']);
    if ((!strstr ($result['usergroups'], '[' . $CURUSER['usergroup'] . ']') OR !in_array ('[' . $CURUSER['usergroup'] . ']', $usergroups, true)))
    {
      print_no_permission (true);
      exit ();
      return null;
    }

  }

  function _calculate_ ($value)
  {
    return mksize ($value);
  }

  function _form_open_ ($values = '', $hidden_values = '')
  {
    global $_this_script_;
    global $act;
    echo '<form method="post" action="' . $_this_script_ . '">
	<input type="hidden" name="act" value="' . $act . '">';
    if (is_array ($values))
    {
      foreach ($values as $val)
      {
        echo $val;
      }
    }
    else
    {
      if (!empty ($values))
      {
        echo $values;
      }
    }

    if (is_array ($hidden_values))
    {
      foreach ($hidden_values as $hidden)
      {
        echo $hidden;
      }

      return null;
    }

    if (!empty ($hidden_values))
    {
      echo $hidden_values;
    }

  }

  function _form_close_ ($button = 'save')
  {
    echo '<input type="submit" value="' . $button . '" class=button></form>';
  }

  function _form_header_open_ ($text, $colspan = 4)
  {
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="thead" colspan="' . $colspan . '" align="center">' . $text . '</td></tr>';
  }

  function _form_header_close_ ()
  {
    echo '</table></tbody></td></tr></table></tbody>';
  }

  function _selectbox_ ($text = '', $name = '', $any = true, $anytext = 'any usergroup (all)', $selected = '')
  {
    $selectbox = (!empty ($text) ? $text . ':' : '') . ' <select name=' . $name . ' id=specialboxn>
	' . ($any ? '<option value="-" style="color: gray;">' . $anytext . '</option>' : '');
    $query_ug = sql_query ('SELECT gid,title FROM usergroups');
    while ($tclass = mysql_fetch_array ($query_ug))
    {
      $selectbox .= '<option value="' . $tclass['gid'] . '" ' . ($selected == $tclass['gid'] ? 'SELECTED' : '') . '>' . $tclass['title'] . '</option>';
    }

    $selectbox .= '</select>';
    return $selectbox;
  }

  function _get_file_type_ ($file)
  {
    $path_chunks = explode ('/', $file);
    $thefile = $path_chunks[count ($path_chunks) - 1];
    $dotpos = strrpos ($thefile, '.');
    return strtolower (substr ($thefile, $dotpos + 1));
  }

  function menu ($selected = '')
  {
    global $usergroups;
    global $_this_script_;
    global $_this_script_no_act;
    print '<table border=1 cellspacing=0 cellpadding=10 width=100% align=center><tr><td class=text align=left colspan=2>';
    print '<div class="shadetabs"><ul>';
    print '<li' . ($selected == 'welcome' ? ' class=selected' : '') . ('' . '><a href="' . $_this_script_no_act . '">Welcome</a></li>');
    print '<li' . ($selected == 'stafftools' ? ' class=selected' : '') . ('' . '><a href="' . $_this_script_no_act . '?act=stafftools">Staff Tools</a></li>');
    if ($usergroups['cansettingspanel'] == 'yes')
    {
      print '<li' . ($selected == 'managestafftools' ? ' class=selected' : '') . ('' . '><a href="' . $_this_script_no_act . '?act=managestafftools">Manage Staff Tools</a></li>');
      print '<li' . ($_GET['do'] == 'newtool' ? ' class=selected' : '') . ('' . '><a href="' . $_this_script_no_act . '?act=managestafftools&do=newtool">Add New Tool</a></li>');
      print '<li' . ($selected == 'securitycheck' ? ' class=selected' : '') . ('' . '><a href="' . $_this_script_no_act . '?act=securitycheck">Security Console</a></li>');
      print '<li><a href="settings.php">Tracker Settings</a></li>';
    }

    print '</ul></div>';
  }

  function close_menu ()
  {
    echo '</td></tr></table>';
  }

  function stop_script ($msg = 'Your Script License has been Terminated!')
  {
    echo '<style type="text/css">
	<!--
	.warnbox
	{
		line-height: 1.4em; 
		float:center;
		background: lightyellow; 
		border:1px solid black;
		border-color:#6D90B0;
		font:normal 12px verdana;
		line-height:18px;
		z-index:100;
		border-right: 4px solid black;
		border-bottom: 4px solid black;
		padding: 0 0 3px 31px;
	}
	.red
	{
		color: #9f0808;
		font:bold 12px verdana;
	}
	a { color: #9f0808; background: inherit; text-decoration:none; }
	a:hover { background: inherit; text-decoration:underline; }
	-->
	</style>
	<div class="warnbox" align="center">
	<p align="center" class="red">	
	' . $msg . ' Please contact the TS Team regarding the issue by clicking following link: no thanks!	
	</font>
	</p>
	<p align="center">
	<strong>This could be because of one of the following reasons:</strong>
	<ul>
	<li>Your account has either been suspended or you have been banned from accessing this resource.</li>
	<li>Your account may still be awaiting activation or moderation.</li>
	<li>Feel free to contact us about this error message.</li>
	</ul></p>';
    exit ();
  }

  if ((!defined ('SETTING_PANEL_TSSEv56') AND !defined ('STAFF_PANEL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  define ('ADMIN_FUNCTIONS_TSSEv56', true);
  define ('AP_VERSION', 'v6.2 by xam');
  define ('S_VERSION', 'v7.9');
  define ('T_VERSION', '5.6');
  define ('O_VERSION', 'TS Special Edition v.5.6 NULLED by Nightcrawler');
  define ('TYPE', 99);
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

  if (file_exists (TSDIR . '/install/install.php') == true)
  {
    $alertmessage = '
	install.php still remains in the /install/ directory.<br /><br />
	This poses a security risk, so please delete that file/folder immediately. You can not access the control panel until you do so.';
    stderr ('Security Alert!', $alertmessage, false);
  }

  require_once $thispath . 'include/adminfunctions2.php';
  if (!defined ('_AF_2'))
  {
    exit ('The authentication has been blocked because of invalid file detected!');
  }

  include_once INC_PATH . '/functions_icons.php';
  if (!function_exists ('file_put_contents'))
  {
    function file_put_contents ($filename, $contents)
    {
      if (is_writable ($filename))
      {
        if ($handle = fopen ($filename, 'w'))
        {
          if (fwrite ($handle, $contents) === FALSE)
          {
            return false;
          }

          fclose ($filename);
          return true;
        }
      }

      return false;
    }
  }

?>
