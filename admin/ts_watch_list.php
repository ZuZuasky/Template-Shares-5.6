<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_wl_errors ()
  {
    global $errors;
    global $lang;
    if (0 < count ($errors))
    {
      $errors = implode ('<br />', $errors);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  $rootpath = './../';
  include $rootpath . '/global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('TWL_VERSION', '0.1 by xam');
  $action = (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : ''));
  $do = (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : ''));
  $userid = (isset ($_POST['userid']) ? (int)$_POST['userid'] : (isset ($_GET['userid']) ? (int)$_GET['userid'] : ''));
  $is_mod = is_mod ($usergroups);
  $errors = array ();
  if (($usergroups['canuserdetails'] != 'yes' OR !$is_mod))
  {
    print_no_permission (true);
  }

  $lang->load ('watch_list');
  if ($action == 'delete')
  {
    if (is_array ($_POST['userids']))
    {
      foreach ($_POST['userids'] as $UID)
      {
        if (!is_valid_id ($UID))
        {
          print_no_permission ();
          continue;
        }
      }

      (sql_query ('DELETE FROM ts_watch_list WHERE userid IN (0, ' . implode (',', $_POST['userids']) . ('' . ') AND added_by = \'' . $CURUSER['id'] . '\'')) OR sqlerr (__FILE__, 80));
    }

    unset ($action);
  }

  if ($action == 'add')
  {
    if (!is_valid_id ($userid))
    {
      stderr ($lang->global['error'], $lang->global['nouserid']);
      exit ();
    }

    ($query = sql_query ('' . 'SELECT id FROM ts_watch_list WHERE userid = \'' . $userid . '\' AND added_by = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 92));
    if (0 < mysql_num_rows ($query))
    {
      $errors[] = $lang->watch_list['e1'];
    }
    else
    {
      if ($CURUSER['id'] == $userid)
      {
        $errors[] = $lang->watch_list['e2'];
      }
    }

    ($query = sql_query ('' . 'SELECT u.username, g.cansettingspanel, g.canstaffpanel, g.issupermod FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE id = \'' . $userid . '\'') OR sqlerr (__FILE__, 102));
    if (mysql_num_rows ($query) < 1)
    {
      stderr ($lang->global['error'], $lang->global['nouserid']);
      exit ();
    }
    else
    {
      if (!$results = mysql_fetch_assoc ($query))
      {
        stderr ($lang->global['error'], $lang->global['nouserid']);
        exit ();
      }
      else
      {
        if ((is_mod ($results) AND $usergroups['cansettingspanel'] != 'yes'))
        {
          $errors[] = $lang->watch_list['e4'];
        }
      }
    }

    $username = htmlspecialchars_uni ($results['username']);
    if (!$username)
    {
      stderr ($lang->global['error'], $lang->global['nouserid']);
      exit ();
    }

    if (($do == 'save' AND count ($errors) == 0))
    {
      $reason = trim ($_POST['reason']);
      $public = ($_POST['public'] == '1' ? '1' : '0');
      if (strlen ($reason) < 3)
      {
        $errors[] = $lang->watch_list['e3'];
      }
      else
      {
        (sql_query ('' . 'INSERT INTO ts_watch_list  VALUES (\'\', \'' . $userid . '\', \'' . $CURUSER['id'] . '\', ' . sqlesc ($reason) . ('' . ', \'' . $public . '\', \'') . time () . '\')') OR sqlerr (__FILE__, 134));
        if (mysql_affected_rows ())
        {
          redirect ($BASEURL . '/userdetails.php?id=' . $userid, $lang->watch_list['m1'], '', 3, false, false);
          exit ();
        }
      }
    }

    stdhead ($lang->watch_list['t1']);
    show_wl_errors ();
    echo '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="action" value="add">
	<input type="hidden" name="do" value="save">
	<input type="hidden" name="userid" value="' . $userid . '">
	<table border="0" width="100%" align="center" cellpadding="4" cellspacing="0">
		<tr>
			<td class="thead">
				' . $lang->watch_list['t1'] . '
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>' . $lang->watch_list['t1'] . ' - ' . $lang->watch_list['t2'] . '</legend>
					<b>' . $lang->watch_list['t3'] . ':</b><br />
					<input type="text" name="username" value="' . $username . '" disabled="disabled" /><br /><br />
					<b>' . $lang->watch_list['t4'] . ':</b><br />
					<textarea rows="3" cols="70" name="reason">' . ($reason ? htmlspecialchars_uni ($reason) : '') . '</textarea><br /><br />
					<input type="checkbox" name="public" class="inlineimg" value="1"' . ($public == '1' ? ' checked="checked"' : '') . ' /> ' . $lang->watch_list['t5'] . '
				</fieldset>
				<fieldset>
					<legend>' . $lang->watch_list['t1'] . ' - ' . $lang->watch_list['t6'] . '</legend>
					<input type="submit" value="' . $lang->watch_list['t6'] . '"> <input type="button" onclick="javascript:jumpto(\'' . $BASEURL . '/userdetails.php?id=' . $userid . '\');" value="' . $lang->watch_list['t7'] . '"> <input type="button" onclick="javascript:jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?action=show_list\');" value="' . $lang->watch_list['s1'] . '">
				</fieldset>
			</td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  if ((empty ($action) OR $action == 'show_list'))
  {
    ($query = sql_query ('' . 'SELECT id FROM ts_watch_list WHERE added_by=\'' . $CURUSER['id'] . '\' OR public = \'1\'') OR sqlerr (__FILE__, 181));
    $count = mysql_num_rows ($query);
    list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_SERVER['SCRIPT_NAME'] . '?action=show_list&');
    stdhead ($lang->watch_list['s1']);
    echo $pagertop;
    $str = '
	<script type="text/javascript">
		function show_details(UserID)
		{
			var WorkZone = document.getElementById("userdetails_"+UserID).style.display;
		
			if (WorkZone == "none")
			{
				document.getElementById("userdetails_"+UserID).style.display = "block";
			}
			else
			{
				document.getElementById("userdetails_"+UserID).style.display = "none";
			}
		}
	</script>
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '" name="delete">
	<input type="hidden" name="action" value="delete">
	<table border="0" width="100%" align="center" cellpadding="4" cellspacing="0">
		<tr>
			<td class="thead" colspan="5">
				' . $lang->watch_list['s1'] . '
			</td>
		</tr>
		<tr>
			<td class="subheader" width="15%">' . $lang->watch_list['t3'] . '</td>
			<td class="subheader" width="15%">' . $lang->watch_list['l2'] . '</td>
			<td class="subheader" width="15%">' . $lang->watch_list['l3'] . '</td>
			<td class="subheader" width="50%">' . $lang->watch_list['l1'] . '</td>			
			<td class="subheader" align="center" width="5%"><input type="checkbox" value="yes" checkall="group1" onclick="javascript: return select_deselectAll (\'delete\', this, \'group1\');"></td>
		</tr>';
    ($query = sql_query ('' . 'SELECT w.id as wid, w.userid, w.added_by, w.reason, w.date, u.uploaded, u.downloaded, u.added, u.last_access, u.username, g.namestyle, uu.username as addeduname, gg.namestyle as addednstyle FROM ts_watch_list w LEFT JOIN users u ON (w.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN users uu ON (w.added_by=uu.id) LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid) WHERE w.added_by = \'' . $CURUSER['id'] . '\' OR w.public = \'1\' ORDER by w.date DESC ' . $limit) OR sqlerr (__FILE__, 217));
    if (0 < mysql_num_rows ($query))
    {
      while ($list = mysql_fetch_assoc ($query))
      {
        $username = '<span style="float: right;">[<a href="#" onclick="javascript:show_details(\'' . $list['userid'] . '\');">' . $lang->watch_list['d3'] . '</a>]</span><a href="' . $BASEURL . '/userdetails.php?id=' . $list['userid'] . '">' . get_user_color ($list['username'], $list['namestyle']) . '</a>';
        $addedby = '<a href="' . $BASEURL . '/userdetails.php?id=' . $list['added_by'] . '">' . get_user_color ($list['addeduname'], $list['addednstyle']) . '</a>';
        $date = my_datee ($dateformat, $list['date']) . ' ' . my_datee ($timeformat, $list['date']);
        $reason = htmlspecialchars_uni ($list['reason']);
        $checkbox = '<input type="checkbox" checkme="group1" name="userids[]" value="' . $list['userid'] . '">';
        $str .= '
			<tr>
				<td width="15%">' . $username . '</td>
				<td width="15%">' . $addedby . '</td>
				<td width="15%">' . $date . '</td>
				<td width="50%">' . $reason . '</td>			
				<td align="center" width="5%">' . $checkbox . '</td>
			</tr>
			<tr>
				<td colspan="5">
					<div id="userdetails_' . $list['userid'] . '" style="display: none;">
						' . sprintf ($lang->watch_list['d4'], my_datee ($dateformat, $list['added']) . ' ' . my_datee ($timeformat, $list['added']), my_datee ($dateformat, $list['last_access']) . ' ' . my_datee ($timeformat, $list['last_access']), mksize ($list['uploaded']), mksize ($list['downloaded']), (0 < $list['downloaded'] ? @number_format ($list['uploaded'] / $list['downloaded'], 1) : '-')) . '
					</div>
				</td>
			</tr>
			';
      }
    }
    else
    {
      $str .= '<tr><td colspan="5">' . $lang->watch_list['d2'] . '</td></tr>';
    }

    $str .= '
		<tr>
			<td colspan="5" align="right"><input type="submit" value="' . $lang->watch_list['d1'] . '"></td>
		</tr>
	</table>
	</form>';
    echo $str . $pagerbottom;
    stdfoot ();
    exit ();
  }

?>
