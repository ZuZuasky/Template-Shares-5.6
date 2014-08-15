<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_panel ($selected = 1, $pid)
  {
    return '
	<select name="position' . ($pid ? '[' . $pid . ']' : '') . '" class="bginput">
		<option value="1"' . ($selected == 1 ? ' selected="selected"' : '') . '>Left</option>
		<option value="2"' . ($selected == 2 ? ' selected="selected"' : '') . '>Middle</option>
		<option value="3"' . ($selected == 3 ? ' selected="selected"' : '') . '>Right</option>
	</select>
	';
  }

  function update_plugin_cache ()
  {
    global $rootpath;
    global $cache;
    $left = $middle = $right = array ();
    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 1 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $left[] = $query;
    }

    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 2 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $middle[] = $query;
    }

    $_query = sql_query ('SELECT name, description, content, permission FROM ts_plugins WHERE position = 3 AND active = 1 ORDER BY sort');
    while ($query = mysql_fetch_assoc ($_query))
    {
      $right[] = $query;
    }

    $left = var_export ($left, true);
    $middle = var_export ($middle, true);
    $right = var_export ($right, true);
    $_filename = TSDIR . '/' . $cache . '/plugins.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
if (!defined(\'IN_PLUGIN_SYSTEM\')) die("<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>");

';
    $_cachecontents .= '/** TS Generated Cache#3 - Do Not Alter
 * Cache Name: Plugins
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$Plugins_LEFT = ' . $left . ';

';
    $_cachecontents .= '' . '$Plugins_MIDDLE = ' . $middle . ';

';
    $_cachecontents .= '' . '$Plugins_RIGHT = ' . $right . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('MP_VERSION', 'v1.2 by xam');
  define ('IN_PLUGIN_SYSTEM', true);
  if (($do == 'ts_plugins_delete' AND is_valid_id ($pid = intval ($_GET['pid']))))
  {
    sql_query ('DELETE FROM ts_plugins WHERE pid = ' . sqlesc ($pid));
    update_plugin_cache ();
    admin_cp_redirect ('ts_plugins_home', 'Plugin has been Deleted...');
    exit ();
  }

  if (($do == 'ts_plugins_enable' AND is_valid_id ($pid = intval ($_GET['pid']))))
  {
    sql_query ('UPDATE ts_plugins SET active = \'1\' WHERE pid = ' . sqlesc ($pid));
    update_plugin_cache ();
    admin_cp_redirect ('ts_plugins_home', 'Plugin has been Enabled...');
    exit ();
  }

  if (($do == 'ts_plugins_disable' AND is_valid_id ($pid = intval ($_GET['pid']))))
  {
    sql_query ('UPDATE ts_plugins SET active = \'0\' WHERE pid = ' . sqlesc ($pid));
    update_plugin_cache ();
    admin_cp_redirect ('ts_plugins_home', 'Plugin has been Disabled...');
    exit ();
  }

  if ($do == 'ts_plugins_qupdate')
  {
    $sort_array = $_POST['sort'];
    $pos_array = $_POST['position'];
    foreach ($sort_array as $pid => $value)
    {
      if ((is_valid_id ($pid) AND is_valid_id ($value)))
      {
        sql_query ('UPDATE ts_plugins SET sort = ' . sqlesc ($value) . ' WHERE pid = ' . sqlesc ($pid));
        continue;
      }
    }

    foreach ($pos_array as $pid => $value)
    {
      if ((is_valid_id ($pid) AND is_valid_id ($value)))
      {
        sql_query ('UPDATE ts_plugins SET position = ' . sqlesc ($value) . ' WHERE pid = ' . sqlesc ($pid));
        continue;
      }
    }

    update_plugin_cache ();
  }

  if (($do == 'ts_plugins_update' AND is_valid_id ($pid = intval ($_GET['pid']))))
  {
    $gpanel = trim ($_GET['panel']);
    switch ($gpanel)
    {
      case 'left':
      {
        $panel = 1;
        break;
      }

      case 'middle':
      {
        $panel = 2;
        break;
      }

      case 'right':
      {
      }

      case 'default':
      {
        $panel = 3;
      }
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $_to_all = ($_POST['all'] == 0 ? 0 : 1);
      $_guest_only = ($_POST['guest'] == 0 ? 0 : 1);
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $content = trim ($_POST['content']);
      if ((isset ($_POST['position']) AND $_POST['position'] != $panel))
      {
        $panel = intval ($_POST['position']);
      }

      $active = ($_POST['active'] == 0 ? 0 : 1);
      $sort = intval ($_POST['sort']);
      if ($_guest_only == 1)
      {
        $permission = '[guest]';
      }
      else
      {
        if ((is_array ($_POST['usergroup']) AND $_to_all == 0))
        {
          $permission = '';
          foreach ($_POST['usergroup'] as $Ogid)
          {
            $permission .= '[' . $Ogid . ']';
          }
        }
        else
        {
          $permission = '[all]';
        }
      }

      if (strlen ($name) < 2)
      {
        $error[] = 'Please Enter Name!';
      }

      if (strlen ($description) < 3)
      {
        $error[] = 'Please Enter Description!';
      }

      if (!is_valid_id ($panel))
      {
        $error[] = 'Please Select Panel!';
      }

      if (count ($error) == 0)
      {
        sql_query ('UPDATE ts_plugins SET name = ' . sqlesc ($name) . ', description = ' . sqlesc ($description) . ', content = ' . sqlesc ($content) . ', position = ' . $panel . ', sort = ' . $sort . ', permission = ' . sqlesc ($permission) . ', active = ' . $active . ' WHERE pid = ' . sqlesc ($pid));
        update_plugin_cache ();
        admin_cp_redirect ('ts_plugins_home', 'Plugin has been Updated.');
        exit ();
      }
      else
      {
        echo implode ('<br />', $error);
      }
    }
    else
    {
      $query = sql_query ('SELECT name, description, content, position, sort, permission, active FROM ts_plugins WHERE pid = ' . sqlesc ($pid));
      while ($plugin_query = mysql_fetch_assoc ($query))
      {
        $name = $plugin_query['name'];
        $description = $plugin_query['description'];
        $content = $plugin_query['content'];
        $panel = $plugin_query['position'];
        $sort = $plugin_query['sort'];
        $permission = $plugin_query['permission'];
        $active = $plugin_query['active'];
      }
    }

    $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
    $scount = 1;
    $sgids = '
	<script type="text/javascript">
		var checkflag="false";var browserName=navigator.appName;function check(field)
		{if(checkflag=="false")
		{for(i=0;i<field.length;i++)
		{field[i].checked=true;}
		checkflag="true";return \'Uncheck All\';}
		else
		{for(i=0;i<field.length;i++)
		{field[i].checked=false;}
		checkflag="false";return \'Check All\';}};
	</script>
	<fieldset>
		<legend>Select Usergroup(s)</legend>
			<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr>';
    while ($gid = mysql_fetch_assoc ($squery))
    {
      if ($scount % 5 == 1)
      {
        $sgids .= '</tr></td>';
      }

      $sgids .= '	
		<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"' . (($permission AND strstr ($permission, '[' . $gid['gid'] . ']')) ? ' checked="checked"' : '') . '></td>
		<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
      ++$scount;
    }

    $sgids .= '
	<td class="none"></td>
	<td class="none"><a href="#" onClick="check(form_update)"><font color="blue" size="1">check all</font></a></td>
	</table>
	</fieldset>';
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_update&panel=' . htmlspecialchars_uni ($gpanel) . '&pid=' . $pid . '" name="form_update">
	<input type="hidden" name="do" value="ts_plugins_update">
	<table width="100%" cellspacing="0" cellpadding="3">
		<tr>
			<td align="right" width="20%" valign="top">Plugin Name:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you want to use file system, this must be same as file name of this plugin.') . '<input type="text" name="name" value="' . htmlspecialchars_uni ($name) . '" class="bginput" size="30"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Title:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin title which will be automaticly shown on index page.') . '<input type="text" name="description" value="' . htmlspecialchars_uni ($description) . '" class="bginput" size="30"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Content:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you don\\\'t want to use file system, just paste contents of your plugin here.') . '<textarea name="content" id="content" rows="20" cols="150">' . htmlspecialchars_uni ($content) . '</textarea></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Position:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin position.') . show_panel ($panel, false) . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Active:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you want to active this plugin, select yes.') . '<select name="active" class="bginput"><option value="1"' . ($active == 1 ? ' selected="selected"' : '') . '>Yes</option><option value="0"' . ($active == 0 ? ' selected="selected"' : '') . '>No</option></select></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Sort Order:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin sort number.') . '<input type="text" name="sort" value="' . intval ($sort) . '" class="bginput" size="3"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Permissions:</td><td align="left" width="80%" valign="top">' . show_helptip ('Select usergroup(s) that you want to show this plugin to them. If you dont select any usergroup, this plugin will be a public plugin automaticly which means all usergroups can see it.') . $sgids . ' <p><input type="checkbox" name="all" value="1"' . (($permission == '[all]' OR !$permission) ? ' checked="checked"' : '') . '> No permission, show to all usergroups (guests too)</p><p><input type="checkbox" name="guest" value="1"' . ($permission == '[guest]' ? ' checked="checked"' : '') . '> Guests Only</p></td>
		</tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Update Plugin" class="bginput"> <input type="reset" value="Reset Plugin" class="bginput"></td></tr>
	</table>
	</form>	
	';
    exit ();
  }

  if ($do == 'ts_plugins_new')
  {
    $gpanel = trim ($_GET['panel']);
    switch ($gpanel)
    {
      case 'left':
      {
        $panel = 1;
        break;
      }

      case 'middle':
      {
        $panel = 2;
        break;
      }

      case 'right':
      {
      }

      case 'default':
      {
        $panel = 3;
      }
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $_to_all = ($_POST['all'] == 0 ? 0 : 1);
      $_guest_only = ($_POST['guest'] == 0 ? 0 : 1);
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $content = trim ($_POST['content']);
      if ((isset ($_POST['position']) AND $_POST['position'] != $panel))
      {
        $panel = intval ($_POST['position']);
      }

      $active = ($_POST['active'] == 0 ? 0 : 1);
      $sort = intval ($_POST['sort']);
      if ($_guest_only == 1)
      {
        $permission = '[guest]';
      }
      else
      {
        if ((is_array ($_POST['usergroup']) AND $_to_all == 0))
        {
          $permission = '';
          foreach ($_POST['usergroup'] as $Ogid)
          {
            $permission .= '[' . $Ogid . ']';
          }
        }
        else
        {
          $permission = '[all]';
        }
      }

      if (strlen ($name) < 2)
      {
        $error[] = 'Please Enter Name!';
      }

      if (strlen ($description) < 3)
      {
        $error[] = 'Please Enter Description!';
      }

      if (!is_valid_id ($panel))
      {
        $error[] = 'Please Select Panel!';
      }

      if (count ($error) == 0)
      {
        sql_query ('INSERT INTO ts_plugins (name, description, content, position, sort, permission, active) VALUES (' . sqlesc ($name) . ', ' . sqlesc ($description) . ', ' . sqlesc ($content) . ', ' . $panel . ', ' . $sort . ', ' . sqlesc ($permission) . ', ' . $active . ')');
        update_plugin_cache ();
        admin_cp_redirect ('ts_plugins_home', 'New plugin has been created.');
        exit ();
      }
      else
      {
        echo implode ('<br />', $error);
      }
    }

    $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
    $scount = 1;
    $sgids = '
	<script type="text/javascript">
		var checkflag="false";var browserName=navigator.appName;function check(field)
		{if(checkflag=="false")
		{for(i=0;i<field.length;i++)
		{field[i].checked=true;}
		checkflag="true";return \'Uncheck All\';}
		else
		{for(i=0;i<field.length;i++)
		{field[i].checked=false;}
		checkflag="false";return \'Check All\';}};
	</script>
	<fieldset>
		<legend>Select Usergroup(s)</legend>
			<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr>';
    while ($gid = mysql_fetch_assoc ($squery))
    {
      if ($scount % 5 == 1)
      {
        $sgids .= '</tr></td>';
      }

      $sgids .= '	
		<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"' . (($permission AND strstr ($permission, '[' . $gid['gid'] . ']')) ? ' checked="checked"' : '') . '></td>
		<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
      ++$scount;
    }

    $sgids .= '
	<td class="none"></td>
	<td class="none"><a href="#" onClick="check(form_new)"><font color="blue" size="1">check all</font></a></td>
	</table>
	</fieldset>';
    echo '	
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_new&panel=' . htmlspecialchars_uni ($gpanel) . '" name="form_new">
	<input type="hidden" name="do" value="ts_plugins_new">
	<table width="100%" cellspacing="0" cellpadding="3">
		<tr>
			<td align="right" width="20%" valign="top">Plugin Name:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you want to use file system, this must be same as file name of this plugin.') . '<input type="text" name="name" value="' . htmlspecialchars_uni ($name) . '" class="bginput" size="30"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Title:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin title which will be automaticly shown on index page.') . '<input type="text" name="description" value="' . htmlspecialchars_uni ($description) . '" class="bginput" size="30"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Content:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you don\\\'t want to use file system, just paste contents of your plugin here.') . '<textarea id="content" name="content" rows="20" cols="150">' . htmlspecialchars_uni ($content) . '</textarea></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Position:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin position.') . show_panel ($panel, false) . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Active:</td><td align="left" width="80%" valign="top">' . show_helptip ('If you want to active this plugin, select yes.') . '<select name="active" class="bginput"><option value="1"' . ($active == 1 ? ' selected="selected"' : '') . '>Yes</option><option value="0"' . ($active == 0 ? ' selected="selected"' : '') . '>No</option></select></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Sort Order:</td><td align="left" width="80%" valign="top">' . show_helptip ('Please enter plugin sort number.') . '<input type="text" name="sort" value="' . intval ($sort) . '" class="bginput" size="3"></td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">Plugin Permissions:</td><td align="left" width="80%" valign="top">' . show_helptip ('Select usergroup(s) that you want to show this plugin to them. If you dont select any usergroup, this plugin will be a public plugin automaticly which means all usergroups can see it.') . $sgids . ' <p><input type="checkbox" name="all" value="1"' . ($_POST['all'] == 1 ? ' checked="checked"' : '') . '> No permission, show to all usergroups (guests too)</p><p><input type="checkbox" name="guest" value="1"' . ($_POST['guest'] == 1 ? ' checked="checked"' : '') . '> Guests Only</p></td>
		</tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Create Plugin" class="bginput"> <input type="reset" value="Reset Plugin" class="bginput"></td></tr>
	</table>
	</form>
	';
    exit ();
  }

  $header = '
<script type="text/javascript">
	function save_quick_update()
	{
		document.form_qupdate.submit();
	}
	function ConfirmDeletion()
	{
		var confirmdeletion = confirm("Are you sure that you want to delete this plugin?");
		if (confirmdeletion)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_qupdate" name="form_qupdate">
<input type="hidden" name="do" value="ts_plugins_qupdate" />
<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr valign="top">
';
  $footer = '
		</tr>
	</tbody>
</table>
</form>
';
  $div = '
<div style="padding-bottom: 1px;">
	<table align="center" border="0" cellpadding="1" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td colspan="0">
					<span class="smallfont">{1}</span>
				</td>
			</tr>
		</thead>
	</table>
</div>
';
  $left_header = array ('<!-- LEFT Plugins --><td style="padding-right: 1px;" class="none">', '</td><!-- LEFT Plugins -->');
  $middle_header = array ('<!-- MIDDLE Plugins --><td valign="top" class="none">', '</td><!-- MIDDLE Plugins -->');
  $right_header = array ('<!-- RIGHT Plugins --><td style="padding-left: 1px;" valign="top" class="none">', '</td><!-- RIGHT Plugins -->');
  $contents = $header . $left_header[0];
  $LeftPanel = '
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="subheader">Description</td>
		<td class="subheader">Action</td>
		<td class="subheader">Sort</td>
		<td class="subheader">Position</td>
	</tr>
';
  $query = sql_query ('SELECT pid, description, position, sort, active FROM ts_plugins WHERE position = 1 ORDER BY sort');
  while ($results = mysql_fetch_assoc ($query))
  {
    if ($results['active'] == '0')
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_enable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_error.gif" border="0" alt="Plugin is disabled, click to enable." title="Plugin is disabled, click to enable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }
    else
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_disable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_true.gif" border="0" alt="Plugin is enabled, click to disable." title="Plugin is enabled, click to disable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }

    $LeftPanel .= '
	<tr>
		<td>
			' . $image . '
			' . $results['description'] . '
		</td>
		<td>
			<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_update&amp;pid=' . $results['pid'] . '&amp;panel=left"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" border="0" alt="Edit" title="Edit" style="vertical-align: middle;" /></a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_delete&amp;pid=' . $results['pid'] . '" onclick="return ConfirmDeletion();"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" border="0" alt="Delete" title="Delete" style="vertical-align: middle;" /></a>
		</td>
		<td>
			<input type="text" class="bginput" name="sort[' . $results['pid'] . ']" value="' . $results['sort'] . '" size="1" />
		</td>
		<td>
			' . show_panel ($results['position'], $results['pid']) . '
		</td>
	</tr>
	';
  }

  $LeftPanel .= '
	<tr>
		<td colspan="4" align="center">
			<br />
			<br />
			<strong>�</strong> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_new&amp;panel=left">Create new plugin on LEFT Panel</a>
		</td>
	</tr>
</table>';
  $contents .= str_replace ('{1}', $LeftPanel, $div) . $left_header[1] . $middle_header[0];
  $MiddlePanel = '
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="subheader">Description</td>
		<td class="subheader">Action</td>
		<td class="subheader">Sort</td>
		<td class="subheader">Position</td>
	</tr>
';
  $query = sql_query ('SELECT pid, description, position, sort, active FROM ts_plugins WHERE position = 2 ORDER BY sort');
  while ($results = mysql_fetch_assoc ($query))
  {
    if ($results['active'] == '0')
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_enable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_error.gif" border="0" alt="Plugin is disabled, click to enable." title="Plugin is disabled, click to enable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }
    else
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_disable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_true.gif" border="0" alt="Plugin is enabled, click to disable." title="Plugin is enabled, click to disable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }

    $MiddlePanel .= '
	<tr>
		<td>
			' . $image . '
			' . $results['description'] . '
		</td>
		<td>
			<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_update&amp;pid=' . $results['pid'] . '&amp;panel=middle"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" border="0" alt="Edit" title="Edit" style="vertical-align: middle;" /></a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_delete&amp;pid=' . $results['pid'] . '" onclick="return ConfirmDeletion();"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" border="0" alt="Delete" title="Delete" style="vertical-align: middle;" /></a>
		</td>
		<td>
			<input type="text" class="bginput" name="sort[' . $results['pid'] . ']" value="' . $results['sort'] . '" size="1" />
		</td>
		<td>
			' . show_panel ($results['position'], $results['pid']) . '
		</td>
	</tr>
	';
  }

  $MiddlePanel .= '
	<tr>
		<td colspan="4" align="center">
			<br />
			<br />
			<strong>�</strong> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_new&amp;panel=middle">Create new plugin on MIDDLE Panel</a>
		</td>
	</tr>
</table>';
  $contents .= str_replace ('{1}', $MiddlePanel, $div) . $middle_header[1] . $right_header[0];
  $RightPanel = '
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="subheader">Description</td>
		<td class="subheader">Action</td>
		<td class="subheader">Sort</td>
		<td class="subheader">Position</td>
	</tr>
';
  $query = sql_query ('SELECT pid, description, position, sort, active FROM ts_plugins WHERE position = 3 ORDER BY sort');
  while ($results = mysql_fetch_assoc ($query))
  {
    if ($results['active'] == '0')
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_enable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_error.gif" border="0" alt="Plugin is disabled, click to enable." title="Plugin is disabled, click to enable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }
    else
    {
      $image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_disable&amp;pid=' . $results['pid'] . '"><img src="' . $BASEUR . '/' . $pic_base_url . 'input_true.gif" border="0" alt="Plugin is enabled, click to disable." title="Plugin is enabled, click to disable." style="vertical-align: middle; cursor: pointer;" /></a>';
    }

    $RightPanel .= '
	<tr>
		<td>
			' . $image . '
			' . $results['description'] . '
		</td>
		<td>
			<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_update&amp;pid=' . $results['pid'] . '&amp;panel=right"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" border="0" alt="Edit" title="Edit" style="vertical-align: middle;" /></a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_delete&amp;pid=' . $results['pid'] . '" onclick="return ConfirmDeletion();"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" border="0" alt="Delete" title="Delete" style="vertical-align: middle;" /></a>
		</td>
		<td>
			<input type="text" class="bginput" name="sort[' . $results['pid'] . ']" value="' . $results['sort'] . '" size="1" />
		</td>
		<td>
			' . show_panel ($results['position'], $results['pid']) . '
		</td>
	</tr>
	';
  }

  $RightPanel .= '
	<tr>
		<td colspan="4" align="center">
			<br />
			<br />
			<strong>�</strong> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=ts_plugins_new&amp;panel=right">Create new plugin on RIGHT Panel</a>
		</td>
	</tr>
</table>';
  $contents .= str_replace ('{1}', $RightPanel, $div) . $right_header[1] . $footer;
  echo $contents . '
<div align="center" style="padding-top; 10px;">
	<p>
		<br />
		<br />
		<strong>�</strong> <a href="#" onclick="save_quick_update();">Save Quick Panel Settings (sort & position)</a><br /><br />
		<img src="' . $BASEUR . '/' . $pic_base_url . 'input_error.gif" border="0" alt="" title="" style="vertical-align: middle;" /> Plugin is Disabled, click to Enable.<br/><img src="' . $BASEUR . '/' . $pic_base_url . 'input_true.gif" border="0" alt="" title="" style="vertical-align: middle;" /> Plugin is Enabled, click to Disable.<br/><img src="' . $BASEUR . '/' . $pic_base_url . 'edit.gif" border="0" alt="" title="" style="vertical-align: middle;" /> Edit Plugin.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><img src="' . $BASEUR . '/' . $pic_base_url . 'delete.gif" border="0" alt="" title="" style="vertical-align: middle;" /> Delete Plugin.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</p>
	</div>';
?>
