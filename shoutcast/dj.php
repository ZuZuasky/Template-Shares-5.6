<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  $rootpath = './../';
  require $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('shoutcast');
  require INC_PATH . '/readconfig_shoutcast.php';
  if ($s_allowedusergroups = explode (',', $s_allowedusergroups))
  {
    if (!in_array ($CURUSER['usergroup'], $s_allowedusergroups))
    {
      print_no_permission ();
    }
  }

  if ($_GET['do'] == 'manage')
  {
    ($query = sql_query ('SELECT activedays, activetime, genre FROM ts_shoutcastdj WHERE active = \'1\' AND uid = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 40));
    if (mysql_num_rows ($query) == 0)
    {
      print_no_permission (true);
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $availabledays = array (1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');
      $activedays = $_POST['activedays'];
      $activetime = trim ($_POST['activetime']);
      $genre = trim ($_POST['genre']);
      if ((((is_array ($activedays) AND count ($activedays)) AND 5 < strlen ($activetime)) AND 2 < strlen ($genre)))
      {
        $selectedadays = array ();
        foreach ($activedays as $ad)
        {
          if ($availabledays[$ad])
          {
            $selectedadays[] = $availabledays[$ad];
            continue;
          }
        }

        if (count ($selectedadays))
        {
          $activedays = implode (',', $selectedadays);
          (sql_query ('UPDATE ts_shoutcastdj SET activedays = ' . sqlesc ($activedays) . ', activetime = ' . sqlesc ($activetime) . ', genre = ' . sqlesc ($genre) . ' WHERE active = \'1\' AND uid = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 65));
          redirect ('shoutcast/index.php');
          exit ();
        }
        else
        {
          stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        }
      }
      else
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }
    }

    $DJ = mysql_fetch_assoc ($query);
    stdhead ($lang->shoutcast['bedj']);
    $availabledays = explode (',', $lang->shoutcast['days']);
    $days = '';
    $i = 0;
    while ($i < 7)
    {
      $days .= '
		<input type="checkbox" value="' . ($i + 1) . '" name="activedays[]"' . (in_array (substr ($availabledays[$i], 0, 3), explode (',', $DJ['activedays'])) ? ' checked="checked"' : '') . ' /> ' . $availabledays[$i] . ' ';
      ++$i;
    }

    echo '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=manage">
	<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="thead">' . $lang->shoutcast['bedj'] . '</td>
		</tr>
		<tr>
			<td align="left">
				<fieldset>
					<legend>' . sprintf ($lang->shoutcast['f1'], $SITENAME) . '</legend>
					' . $days . '
					<div style="padding-top:10px;">
						<b>' . $lang->shoutcast['f2'] . '</b> <input type="text" name="activetime" value="' . htmlspecialchars_uni ($DJ['activetime']) . '" /> <b>' . $lang->shoutcast['example'] . '</b>
					</div>
				</fieldset>
				<fieldset>
					<legend>' . $lang->shoutcast['f5'] . '</legend>
						<input type="text" name="genre" value="' . htmlspecialchars_uni ($DJ['genre']) . '" size="50" />
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="center" class="subheader">
				<input type="submit" value="' . $lang->shoutcast['f3'] . '" /> <input type="reset" value="' . $lang->shoutcast['f4'] . '" />
			</td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  if ((($_GET['do'] == 'edit' AND is_valid_id ($_GET['id'])) AND is_mod ($usergroups)))
  {
    $Updated = false;
    ($Query = sql_query ('SELECT * FROM ts_shoutcastdj WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 125));
    if (0 < mysql_num_rows ($Query))
    {
      $Updated = false;
      if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
      {
        $availabledays = array (1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');
        $activedays = $_POST['activedays'];
        $activetime = trim ($_POST['activetime']);
        $genre = trim ($_POST['genre']);
        if ((((is_array ($activedays) AND count ($activedays)) AND 5 < strlen ($activetime)) AND 2 < strlen ($genre)))
        {
          $selectedadays = array ();
          foreach ($activedays as $ad)
          {
            if ($availabledays[$ad])
            {
              $selectedadays[] = $availabledays[$ad];
              continue;
            }
          }

          if (count ($selectedadays))
          {
            $activedays = implode (',', $selectedadays);
            (sql_query ('UPDATE ts_shoutcastdj SET activedays = ' . sqlesc ($activedays) . ', activetime = ' . sqlesc ($activetime) . ', genre = ' . sqlesc ($genre) . ' WHERE active = \'1\' AND uid = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 149));
            $Updated = true;
          }
          else
          {
            stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
          }
        }
        else
        {
          stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        }
      }

      if (!$Updated)
      {
        stdhead ($lang->shoutcast['bedj']);
        $DJ = mysql_fetch_assoc ($Query);
        $availabledays = explode (',', $lang->shoutcast['days']);
        $days = '';
        $i = 0;
        while ($i < 7)
        {
          $days .= '
				<input type="checkbox" value="' . ($i + 1) . '" name="activedays[]"' . (in_array (substr ($availabledays[$i], 0, 3), explode (',', $DJ['activedays'])) ? ' checked="checked"' : '') . ' /> ' . $availabledays[$i] . ' ';
          ++$i;
        }

        echo '
			<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=edit&amp;id=' . $DJ['id'] . '">
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td class="thead">' . $lang->shoutcast['bedj'] . '</td>
				</tr>
				<tr>
					<td align="left">
						<fieldset>
							<legend>' . sprintf ($lang->shoutcast['f1'], $SITENAME) . '</legend>
							' . $days . '
							<div style="padding-top:10px;">
								<b>' . $lang->shoutcast['f2'] . '</b> <input type="text" name="activetime" value="' . htmlspecialchars_uni ($DJ['activetime']) . '" /> <b>' . $lang->shoutcast['example'] . '</b>
							</div>
						</fieldset>
						<fieldset>
							<legend>' . $lang->shoutcast['f5'] . '</legend>
								<input type="text" name="genre" value="' . htmlspecialchars_uni ($DJ['genre']) . '" size="50" />
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" class="subheader">
						<input type="submit" value="' . $lang->shoutcast['f3'] . '" /> <input type="reset" value="' . $lang->shoutcast['f4'] . '" />
					</td>
				</tr>
			</table>
			</form>
			';
        stdfoot ();
        exit ();
      }
    }

    $_GET['do'] = 'list';
    $_GET['id'] = 0 + $_GET['id'];
  }

  if ((($_GET['do'] == 'approve' AND is_valid_id ($_GET['id'])) AND is_mod ($usergroups)))
  {
    (sql_query ('UPDATE ts_shoutcastdj SET active = \'1\' WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 213));
    if (mysql_affected_rows ())
    {
      ($Query = sql_query ('SELECT uid FROM ts_shoutcastdj WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 216));
      require_once INC_PATH . '/functions_pm.php';
      send_pm (mysql_result ($Query, 0, 'uid'), sprintf ($lang->shoutcast['amsg'], '[URL]' . $BASEURL . '/shoutcast/dj_faq.php[/URL]'), $lang->shoutcast['subject']);
    }

    $_GET['do'] = 'list';
    $_GET['id'] = 0 + $_GET['id'];
  }

  if ((($_GET['do'] == 'deny' AND is_valid_id ($_GET['id'])) AND is_mod ($usergroups)))
  {
    (sql_query ('UPDATE ts_shoutcastdj SET active = \'2\' WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 226));
    if (mysql_affected_rows ())
    {
      ($Query = sql_query ('SELECT uid FROM ts_shoutcastdj WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 229));
      require_once INC_PATH . '/functions_pm.php';
      send_pm (mysql_result ($Query, 0, 'uid'), $lang->shoutcast['dmsg'], $lang->shoutcast['subject']);
    }

    $_GET['do'] = 'list';
    $_GET['id'] = 0 + $_GET['id'];
  }

  if ((($_GET['do'] == 'kick' AND is_valid_id ($_GET['id'])) AND is_mod ($usergroups)))
  {
    (sql_query ('UPDATE ts_shoutcastdj SET active = \'3\' WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 239));
    if (mysql_affected_rows ())
    {
      ($Query = sql_query ('SELECT uid FROM ts_shoutcastdj WHERE id = \'' . (0 + $_GET['id']) . '\'') OR sqlerr (__FILE__, 242));
      require_once INC_PATH . '/functions_pm.php';
      send_pm (mysql_result ($Query, 0, 'uid'), $lang->shoutcast['kmsg'], sprintf ($lang->shoutcast['subject2'], $SITENAME));
    }

    $_GET['do'] = 'list';
    $_GET['id'] = 0 + $_GET['id'];
  }

  if ($_GET['do'] == 'request')
  {
    ($query = sql_query ('SELECT uid FROM ts_shoutcastdj WHERE uid = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 252));
    if (0 < mysql_num_rows ($query))
    {
      print_no_permission ();
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $availabledays = array (1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');
      $activedays = $_POST['activedays'];
      $activetime = trim ($_POST['activetime']);
      $genre = trim ($_POST['genre']);
      if ((((is_array ($activedays) AND count ($activedays)) AND 5 < strlen ($activetime)) AND 2 < strlen ($genre)))
      {
        $selectedadays = array ();
        foreach ($activedays as $ad)
        {
          if ($availabledays[$ad])
          {
            $selectedadays[] = $availabledays[$ad];
            continue;
          }
        }

        if (count ($selectedadays))
        {
          $activedays = implode (',', $selectedadays);
          (sql_query ('INSERT INTO ts_shoutcastdj VALUES (NULL, \'' . $CURUSER['id'] . '\', \'0\', ' . sqlesc ($activedays) . ', ' . sqlesc ($activetime) . ', ' . sqlesc ($genre) . ')') OR sqlerr (__FILE__, 277));
          $id = mysql_insert_id ();
          ($query = sql_query ('SELECT u.id, g.gid FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\' AND (g.cansettingspanel = \'yes\' OR g.canstaffpanel = \'yes\' OR g.issupermod=\'yes\')') OR sqlerr (__FILE__, 279));
          require_once INC_PATH . '/functions_pm.php';
          while ($si = mysql_fetch_assoc ($query))
          {
            send_pm ($si['id'], sprintf ($lang->shoutcast['msg'], $CURUSER['username'], '[URL]' . $BASEURL . '/shoutcast/dj.php?do=list&id=' . $id . '[/URL]'), $lang->shoutcast['subject']);
          }

          stdhead ($lang->shoutcast['bedj']);
          echo show_notice (sprintf ($lang->shoutcast['thanks'], $SITENAME), false, $lang->shoutcast['bedj']);
          stdfoot ();
          exit ();
        }
        else
        {
          stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        }
      }
      else
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }
    }

    stdhead ($lang->shoutcast['bedj']);
    $availabledays = explode (',', $lang->shoutcast['days']);
    $days = '';
    $i = 0;
    while ($i < 7)
    {
      $days .= '
		<input type="checkbox" value="' . ($i + 1) . '" name="activedays[]" /> ' . $availabledays[$i] . ' ';
      ++$i;
    }

    echo '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=request">
	<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="thead">' . $lang->shoutcast['bedj'] . '</td>
		</tr>
		<tr>
			<td align="left">
				<fieldset>
					<legend>' . sprintf ($lang->shoutcast['f1'], $SITENAME) . '</legend>
					' . $days . '
					<div style="padding-top:10px;">
						<b>' . $lang->shoutcast['f2'] . '</b> <input type="text" name="activetime" value="00:00-00:00" /> <b>' . $lang->shoutcast['example'] . '</b>
					</div>
				</fieldset>
				<fieldset>
					<legend>' . $lang->shoutcast['f5'] . '</legend>
						<input type="text" name="genre" value="" size="50" />
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="center" class="subheader">
				<input type="submit" value="' . $lang->shoutcast['f3'] . '" /> <input type="reset" value="' . $lang->shoutcast['f4'] . '" />
			</td>
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  if ($_GET['do'] == 'list')
  {
    $is_mod = is_mod ($usergroups);
    ($Query = sql_query ('SELECT t.*, u.username, g.namestyle FROM ts_shoutcastdj t LEFT JOIN users u ON (t.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER by t.active ASC') OR sqlerr (__FILE__, 345));
    if (mysql_num_rows ($Query))
    {
      $activedjlist = '
		<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<td colspan="5" class="thead">' . $lang->shoutcast['djlist'] . '</td>
			</tr>
			<tr>
				<td class="subheader">' . $lang->shoutcast['djname'] . '</td>
				<td class="subheader">' . $lang->shoutcast['adays'] . '</td>
				<td class="subheader">' . $lang->shoutcast['atime'] . '</td>
				<td class="subheader">' . $lang->shoutcast['genre'] . '</td>
				<td class="subheader">' . $lang->shoutcast['status'] . '</td>
			</tr>';
      while ($List = mysql_fetch_assoc ($Query))
      {
        $activedjlist .= '
			<tr' . ((isset ($_GET['id']) AND $_GET['id'] == $List['id']) ? ' class="highlight"' : '') . '>
				<td><a href="' . ts_seo ($List['uid'], $List['username']) . '">' . get_user_color ($List['username'], $List['namestyle']) . '</a></td>
				<td>' . htmlspecialchars_uni ($List['activedays']) . '</td>
				<td>' . htmlspecialchars_uni ($List['activetime']) . '</td>
				<td>' . htmlspecialchars_uni ($List['genre']) . '</td>
				<td>' . ($is_mod ? '<span style="float: right;"><a href="' . $_SERVER['SCRIPT_NAME'] . '?do=approve&amp;id=' . $List['id'] . '">[' . $lang->shoutcast['approve'] . ']</a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=deny&amp;id=' . $List['id'] . '">[' . $lang->shoutcast['deny'] . ']</a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=kick&amp;id=' . $List['id'] . '">[' . $lang->shoutcast['kick'] . ']</a> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=edit&amp;id=' . $List['id'] . '">[' . $lang->shoutcast['edit'] . ']</a></span>' : '') . '<font color="' . ($List['active'] == '0' ? 'red">' . $lang->shoutcast['pending'] : ($List['active'] == '1' ? 'green">' . $lang->shoutcast['approved'] : ($List['active'] == '2' ? 'blue">' . $lang->shoutcast['denied'] : 'darkred">' . $lang->shoutcast['kicked']))) . '</font></td>
			</tr>
			';
      }
    }
    else
    {
      stderr ($lang->global['error'], $lang->shoutcast['down2']);
    }

    stdhead ($lang->shoutcast['djlist']);
    echo $activedjlist . '
	</table>';
    stdfoot ();
    exit ();
  }

?>
