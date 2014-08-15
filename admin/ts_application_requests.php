<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_app_errors ($text = '')
  {
    global $errors;
    global $lang;
    if (0 < count ($errors))
    {
      $error = implode ('<br />', $errors);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . ($text ? $text : $lang->global['error']) . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $error . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TSAR_VERSION', '0.1 by xam');
  define ('NcodeImageResizer', true);
  $lang->load ('ts_application_requests');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  $uid = 0 + $CURUSER['id'];
  $username = $CURUSER['username'];
  if ((($do == 'deny' AND $rid = intval ($_GET['rid'])) AND is_valid_id ($rid)))
  {
    (sql_query ('' . 'UPDATE ts_application_requests SET status = \'1\' WHERE rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 57));
    if (mysql_affected_rows ())
    {
      ($query = sql_query ('' . 'SELECT r.uid, a.title FROM ts_application_requests r LEFT JOIN ts_applications a ON (r.aid=a.aid) WHERE r.rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 60));
      if (0 < mysql_num_rows ($query))
      {
        require_once INC_PATH . '/functions_pm.php';
        $result = mysql_fetch_assoc ($query);
        send_pm ($result['uid'], sprintf ($lang->ts_application_requests['deny_msg'], $result['title'], $CURUSER['username']), $lang->ts_application_requests['subject']);
      }
      else
      {
        stderr ($lang->global['error'], $lang->ts_application_requests['error']);
      }
    }

    unset ($do);
  }

  if ((($do == 'accept' AND $rid = intval ($_GET['rid'])) AND is_valid_id ($rid)))
  {
    (sql_query ('' . 'UPDATE ts_application_requests SET status = \'2\' WHERE rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 77));
    if (mysql_affected_rows ())
    {
      ($query = sql_query ('' . 'SELECT r.uid, a.title, a.aid FROM ts_application_requests r LEFT JOIN ts_applications a ON (r.aid=a.aid) WHERE r.rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 80));
      if (0 < mysql_num_rows ($query))
      {
        require_once INC_PATH . '/functions_pm.php';
        $result = mysql_fetch_assoc ($query);
        send_pm ($result['uid'], sprintf ($lang->ts_application_requests['accept_msg'], $result['title'], $CURUSER['username']), $lang->ts_application_requests['subject']);
      }
      else
      {
        stderr ($lang->global['error'], $lang->ts_application_requests['error']);
      }
    }

    unset ($do);
  }

  if ((($do == 'delete' AND $rid = intval ($_GET['rid'])) AND is_valid_id ($rid)))
  {
    (sql_query ('' . 'DELETE FROM ts_application_requests WHERE rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 97));
    unset ($do);
  }

  if ((($do == 'delete_app' AND $aid = intval ($_GET['aid'])) AND is_valid_id ($aid)))
  {
    (sql_query ('' . 'DELETE FROM ts_applications WHERE aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 103));
    (sql_query ('' . 'DELETE FROM ts_application_requests WHERE aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 104));
    unset ($do);
  }

  if ((($do == 'view' AND $rid = intval ($_GET['rid'])) AND is_valid_id ($rid)))
  {
    ($query = sql_query ('' . 'SELECT 
		r.*,
		a.title, a.description, a.requirements, a.created as appcreated, a.by,
		u.username as requsername, g.namestyle as reqnamestyle,
		uu.username as appusername, gg.namestyle as appnamestyle
		FROM ts_application_requests r 
		LEFT JOIN ts_applications a ON (r.aid=a.aid)
		LEFT JOIN users u ON (r.uid=u.id) 
		LEFT JOIN usergroups g ON (u.usergroup=g.gid) 
		LEFT JOIN users uu ON (a.by=uu.id) 
		LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid)
		WHERE r.rid = \'' . $rid . '\'') OR sqlerr (__FILE__, 121));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->ts_application_requests['error']);
    }

    $app = mysql_fetch_assoc ($query);
    $title = $lang->ts_application_requests['head'];
    stdhead ($title);
    echo '
	<script type="text/javascript">
		function ConfirmDelete(Rid)
		{
			var RDelete = confirm("' . $lang->ts_application_requests['confirm'] . '");
			if (RDelete)
			{
				jumpto("' . $_this_script_ . '&do=delete&rid="+Rid);
			}
			return false;
		}
	</script>
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td align="left" class="thead">
				' . $title . '
			</td>		
		</tr>
		<tr>
			<td align="left" class="subheader">
				' . $lang->ts_application_requests['head2'] . '
			</td>		
		</tr>	
		<tr>
			<td>
				<fieldset>
					<legend>' . $lang->ts_application_requests['title'] . '</legend>
					' . nl2br ($app['title']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['desc'] . '</legend>
					' . nl2br ($app['description']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['req'] . '</legend>
					' . nl2br ($app['requirements']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['details'] . '</legend>
					' . sprintf ($lang->ts_application_requests['detailsinfo'], '<a href="' . $BASEURL . '/userdetails.php?id=' . $app['by'] . '">' . get_user_color ($app['appusername'], $app['appnamestyle']) . '</a>', my_datee ($dateformat, $app['appcreated']) . ' ' . my_datee ($timeformat, $app['appcreated'])) . '
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="left" class="subheader">
				' . $lang->ts_application_requests['head3'] . '
			</td>		
		</tr>	
		<tr>
			<td>
				<fieldset>
					<legend>' . $lang->ts_application_requests['url'] . '</legend>
					' . format_comment ($app['url']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['info'] . '</legend>
					' . format_comment ($app['info']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['details'] . '</legend>
					' . sprintf ($lang->ts_application_requests['detailsinfo2'], '<a target="_blank" href="' . $BASEURL . '/userdetails.php?id=' . $app['uid'] . '">' . get_user_color ($app['requsername'], $app['reqnamestyle']) . '</a>', my_datee ($dateformat, $app['created']) . ' ' . my_datee ($timeformat, $app['created'])) . '
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="left" class="subheader">
				' . $lang->ts_application_requests['head4'] . '
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" value="' . $lang->ts_application_requests['accept'] . '" onclick="jumpto(\'' . $_this_script_ . '&do=accept&rid=' . $rid . '\'); return false;" /> 
				<input type="button" value="' . $lang->ts_application_requests['deny'] . '" onclick="jumpto(\'' . $_this_script_ . '&do=deny&rid=' . $rid . '\'); return false;" /> 
				<input type="button" value="' . $lang->ts_application_requests['delete'] . '" onclick="ConfirmDelete(' . $rid . '); return false;" />
			</td>
		</tr>	
	</table>
	';
    stdfoot ();
    exit ();
  }

  if (($do == 'new_app' OR (($do == 'edit_app' AND $aid = intval ($_GET['aid'])) AND is_valid_id ($aid))))
  {
    $IsEdit = (($do == 'edit_app' AND $aid) ? true : false);
    if ($IsEdit)
    {
      $query = sql_query ('' . 'SELECT title, description, requirements, enabled FROM ts_applications WHERE aid = \'' . $aid . '\'');
      if (mysql_num_rows ($query) == 0)
      {
        stderr ($lang->global['error'], $lang->ts_application_requests['error1']);
      }

      $EditApp = mysql_fetch_assoc ($query);
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $title = trim ($_POST['title']);
      $description = trim ($_POST['description']);
      $requirements = trim ($_POST['requirements']);
      $enabled = ($_POST['enabled'] == '1' ? '1' : '0');
      if (((!empty ($title) AND !empty ($description)) AND !empty ($requirements)))
      {
        if (!$IsEdit)
        {
          (sql_query ('INSERT INTO ts_applications (title, description, requirements, created, `by`) VALUES (' . sqlesc ($title) . ', ' . sqlesc ($description) . ', ' . sqlesc ($requirements) . ', \'' . time () . '\', \'' . $CURUSER['id'] . '\')') OR sqlerr (__FILE__, 243));
          redirect ($_this_script_ . '&do=view_single&aid=' . mysql_insert_id (), $lang->ts_application_requests['new2'], '', 3, false, false);
        }
        else
        {
          (sql_query ('UPDATE ts_applications SET title = ' . sqlesc ($title) . ', description = ' . sqlesc ($description) . ', requirements = ' . sqlesc ($requirements) . ('' . ', enabled = \'' . $enabled . '\' WHERE aid = \'' . $aid . '\'')) OR sqlerr (__FILE__, 248));
          redirect ($_this_script_ . '&do=view_single&aid=' . $aid, $lang->ts_application_requests['edit3'], '', 3, false, false);
        }

        exit ();
      }
    }

    stdhead ((!$IsEdit ? $lang->ts_application_requests['new'] : $lang->ts_application_requests['edit2']));
    echo '
	<form method="POST" action="' . $_this_script_ . '&do=' . (!$IsEdit ? 'new_app' : 'edit_app&aid=' . $aid) . '">
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td align="left" class="thead">
				' . (!$IsEdit ? $lang->ts_application_requests['new'] : $lang->ts_application_requests['edit2']) . '
			</td>		
		</tr>		
		<tr>
			<td>
				<fieldset>
					<legend>' . $lang->ts_application_requests['title'] . '</legend>
					<input type="text" size="80" name="title" value="' . ($title ? htmlspecialchars_uni ($title) : htmlspecialchars_uni ($EditApp['title'])) . '" />
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['desc'] . '</legend>
					<textarea name="description" style="width: 500px; height: 90px;">' . ($description ? htmlspecialchars_uni ($description) : htmlspecialchars_uni ($EditApp['description'])) . '</textarea>
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['req'] . '</legend>
					<textarea name="requirements" style="width: 500px; height: 90px;">' . ($requirements ? htmlspecialchars_uni ($requirements) : htmlspecialchars_uni ($EditApp['requirements'])) . '</textarea>
				</fieldset>
				' . ($IsEdit ? '
				<fieldset>
					<legend>' . $lang->ts_application_requests['vstatus'] . ':</legend>
					<input type="checkbox" class="inlineimg" name="enabled" value="1"' . ($enabled ? ' checked="checked"' : ($EditApp['enabled'] == '1' ? ' checked="checked"' : '')) . ' /> ' . $lang->ts_application_requests['enabled'] . '
				</fieldset>
				' : '') . '
			</td>
		</tr>
			<tr>
				<td align="center">
					<input type="submit" value="' . (!$IsEdit ? $lang->ts_application_requests['new'] : $lang->ts_application_requests['edit2']) . '" />
				</td>
			</tr>
	</table>
	</form>';
    stdfoot ();
    exit ();
    exit ();
  }

  if ((($do == 'view_single' AND $aid = intval ($_GET['aid'])) AND is_valid_id ($aid)))
  {
    ($query = sql_query ('' . 'SELECT 
		a.title, a.description, a.requirements, a.created as appcreated, a.by,
		u.username as appusername, g.namestyle as appnamestyle
		FROM ts_applications a
		LEFT JOIN users u ON (a.by=u.id) 
		LEFT JOIN usergroups g ON (u.usergroup=g.gid) 
		WHERE a.aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 306));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->ts_application_requests['error1']);
    }

    $app = mysql_fetch_assoc ($query);
    $title = $lang->ts_application_requests['head'];
    stdhead ($title);
    echo '
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td align="left" class="thead">
				' . $title . '
			</td>		
		</tr>
		<tr>
			<td align="left" class="subheader">
				' . $lang->ts_application_requests['head2'] . '
			</td>		
		</tr>	
		<tr>
			<td>
				<fieldset>
					<legend>' . $lang->ts_application_requests['title'] . '</legend>
					' . nl2br ($app['title']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['desc'] . '</legend>
					' . nl2br ($app['description']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['req'] . '</legend>
					' . nl2br ($app['requirements']) . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->ts_application_requests['details'] . '</legend>
					' . sprintf ($lang->ts_application_requests['detailsinfo'], '<a href="' . $BASEURL . '/userdetails.php?id=' . $app['by'] . '">' . get_user_color ($app['appusername'], $app['appnamestyle']) . '</a>', my_datee ($dateformat, $app['appcreated']) . ' ' . my_datee ($timeformat, $app['appcreated'])) . '
				</fieldset>
			</td>
		</tr>
		</table>
		<br />
		<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td align="left" class="thead" colspan="4">
				' . $title . ' - ' . $lang->ts_application_requests['head3'] . '
			</td>		
		</tr>
		<tr>
			<td align="left" class="subheader" width="15%">' . $lang->ts_application_requests['username'] . '</td>
			<td align="left" class="subheader" width="55%">' . $lang->ts_application_requests['info'] . '</td>
			<td align="left" class="subheader" width="15%">' . $lang->ts_application_requests['vcreated'] . '</td>
			<td align="left" class="subheader" width="15%">' . $lang->ts_application_requests['vstatus'] . '</td>
		</tr>';
    ($query = sql_query ('' . 'SELECT 
		r.*,
		a.by,
		u.username as requsername, g.namestyle as reqnamestyle
		FROM ts_application_requests r 
		LEFT JOIN ts_applications a ON (r.aid=a.aid)
		LEFT JOIN users u ON (r.uid=u.id) 
		LEFT JOIN usergroups g ON (u.usergroup=g.gid) 
		WHERE a.aid = \'' . $aid . '\'') OR sqlerr (__FILE__, 370));
    if (0 < mysql_num_rows ($query))
    {
      while ($app = mysql_fetch_assoc ($query))
      {
        switch ($app['status'])
        {
          case 0:
          {
            $status = $lang->ts_application_requests['s0'];
            break;
          }

          case 1:
          {
            $status = $lang->ts_application_requests['s1'];
            break;
          }

          case 2:
          {
            $status = $lang->ts_application_requests['s2'];
          }
        }

        $created = my_datee ($dateformat, $app['created']) . ' ' . my_datee ($timeformat, $app['created']);
        $username = '<a href="' . $BASEURL . '/userdetails.php?id=' . $app['uid'] . '" target="_blank">' . get_user_color ($app['requsername'], $app['reqnamestyle']) . '</a>';
        $info = format_comment ($app['info']);
        echo '
			<tr>
				<td>' . $username . '</td>
				<td><span style="float: right;">[<a href="' . $_this_script_ . '&do=view&rid=' . $app['rid'] . '"><b>' . $lang->ts_application_requests['viewreq'] . '</b></a>]</span>' . $info . '</td>
				<td>' . $created . '</td>
				<td>' . $status . '</td>
			</tr>
			';
      }
    }
    else
    {
      echo '<tr><td colspan="4">' . $lang->ts_application_requests['norequest'] . '</td></tr>';
    }

    echo '</table>';
    stdfoot ();
    exit ();
  }

  $title = $lang->ts_application_requests['head'];
  $query = sql_query ('SELECT a.aid, a.title, a.description, a.created, a.enabled, a.by, u.username, g.namestyle  FROM ts_applications a LEFT JOIN users u ON (a.by=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY a.created');
  if (0 < mysql_num_rows ($query))
  {
    while ($app = mysql_fetch_assoc ($query))
    {
      $created = my_datee ($dateformat, $app['created']) . ' ' . my_datee ($timeformat, $app['created']);
      $username = '<a href="' . $BASEURL . '/userdetails.php?id=' . $app['by'] . '" target="_blank">' . get_user_color ($app['username'], $app['namestyle']) . '</a>';
      $status = ($app['enabled'] == 1 ? $lang->ts_application_requests['enabled'] : $lang->ts_application_requests['disabled']);
      $str .= '
		<tr>
			<td><span style="float: right;">[<a href="' . $_this_script_ . '&do=view_single&aid=' . $app['aid'] . '"><b>' . $lang->ts_application_requests['head2'] . '</b></a>] [<a href="' . $_this_script_ . '&do=edit_app&aid=' . $app['aid'] . '"><b>' . $lang->ts_application_requests['edit'] . '</b></a>] [<a href="' . $_this_script_ . '&do=delete_app&aid=' . $app['aid'] . '" onclick="ConfirmDelete(' . $app['aid'] . '); return false;"><b>' . $lang->ts_application_requests['delete2'] . '</b></a>]</span>' . $app['title'] . '<br />' . $app['description'] . '</td>
			<td>' . $created . '</td>
			<td>' . $username . '</td>
			<td>' . $status . '</td>
		</tr>
		';
    }
  }
  else
  {
    $str = '<tr><td colspan="4">' . $lang->ts_application_requests['noapp'] . '</td></tr>';
  }

  stdhead ($title);
  echo '
<script type="text/javascript">
	function ConfirmDelete(Aid)
	{
		var ADelete = confirm("' . $lang->ts_application_requests['confirm2'] . '");
		if (ADelete)
		{
			jumpto("' . $_this_script_ . '&do=delete_app&aid="+Aid);
		}
		return false;
	}
</script>
<p style="float: right;"><input type="button" value="' . $lang->ts_application_requests['new'] . '" onclick="jumpto(\'' . $_this_script_ . '&do=new_app\'); return false;"></p>';
  _form_header_open_ ($title, 4);
  echo '
<tr>
	<td class="subheader" width="55%">' . $lang->ts_application_requests['vtitle'] . '</td>
	<td class="subheader" width="20%">' . $lang->ts_application_requests['vcreated'] . '</td>
	<td class="subheader" width="15%">' . $lang->ts_application_requests['vusername'] . '</td>
	<td class="subheader" width="10%">' . $lang->ts_application_requests['vstatus'] . '</td>
</td>
' . $str;
  _form_header_close_ ();
  stdfoot ();
?>
