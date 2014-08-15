<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_us_errors ()
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
					' . $lang->global['error'] . '
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

  define ('US_VERSION', 'v1.1.2 by xam');
  $str = $matches = $Quicksearch = '';
  $errors = array ();
  $lang->load ('usersearch');
  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    $HighlightColumns = array ();
    $Queries[] = '1=1';
    foreach ($_POST as $Name => $Value)
    {
      if ((!empty ($Name) AND !empty ($Value)))
      {
        switch ($Name)
        {
          case 'username':
          {
            if ($_POST['exactmatch'] != 'yes')
            {
              $Queries[] = 'username LIKE \'%' . mysql_real_escape_string (trim ($Value)) . '%\'';
            }
            else
            {
              $Queries[] = 'username = \'' . mysql_real_escape_string (trim ($Value)) . '\'';
            }

            $HighlightColumns[] = 'username';
            break;
          }

          case 'usergroup':
          {
            if ($Value != '-1')
            {
              $Queries[] = '(usergroup = \'' . intval ($Value) . '\'' . ($Value == UC_BANNED ? ' OR enabled = \'no\'' : '') . ')';
              $HighlightColumns[] = 'usergroup';
            }

            break;
          }

          case 'email':
          {
            $Queries[] = 'email LIKE \'%' . mysql_real_escape_string (trim ($Value)) . '%\'';
            $HighlightColumns[] = 'email';
            break;
          }

          case 'ip':
          {
            $Queries[] = 'ip LIKE \'%' . mysql_real_escape_string (trim ($Value)) . '%\'';
            $HighlightColumns[] = 'ip';
            break;
          }

          case 'added':
          {
            $Queries[] = 'UNIX_TIMESTAMP(added) > \'' . mysql_real_escape_string (strtotime ($Value)) . '\'';
            $HighlightColumns[] = 'added';
            break;
          }

          case 'added2':
          {
            $Queries[] = 'UNIX_TIMESTAMP(added) < \'' . mysql_real_escape_string (strtotime ($Value)) . '\'';
            $HighlightColumns[] = 'added2';
            break;
          }

          case 'last_access':
          {
            $Queries[] = 'UNIX_TIMESTAMP(last_access) > \'' . mysql_real_escape_string (strtotime ($Value)) . '\'';
            $HighlightColumns[] = 'last_access';
            break;
          }

          case 'last_access2':
          {
            $Queries[] = 'UNIX_TIMESTAMP(last_access) < \'' . mysql_real_escape_string (strtotime ($Value)) . '\'';
            $HighlightColumns[] = 'last_access2';
            break;
          }

          case 'birthday':
          {
            $Queries[] = 'birthday > \'' . mysql_real_escape_string (trim ($Value)) . '\'';
            $HighlightColumns[] = 'birthday';
            break;
          }

          case 'birthday2':
          {
            $Queries[] = 'birthday < \'' . mysql_real_escape_string (trim ($Value)) . '\'';
            $HighlightColumns[] = 'birthday2';
            break;
          }

          case 'totalposts':
          {
            $Queries[] = 'totalposts >= \'' . mysql_real_escape_string (intval ($Value)) . '\'';
            $HighlightColumns[] = 'totalposts';
            break;
          }

          case 'totalposts2':
          {
            $Queries[] = 'totalposts < \'' . mysql_real_escape_string (intval ($Value)) . '\'';
            $HighlightColumns[] = 'totalposts2';
            break;
          }

          case 'timeswarned':
          {
            $Queries[] = 'timeswarned >= \'' . mysql_real_escape_string (intval ($Value)) . '\'';
            $HighlightColumns[] = 'timeswarned';
            break;
          }

          case 'timeswarned2':
          {
            $Queries[] = 'timeswarned < \'' . mysql_real_escape_string (intval ($Value)) . '\'';
            $HighlightColumns[] = 'timeswarned2';
            break;
          }

          case 'uploaded':
          {
            $Queries[] = 'uploaded >= \'' . mysql_real_escape_string ($Value * 1024 * 1024 * 1024) . '\'';
            $HighlightColumns[] = 'uploaded';
            break;
          }

          case 'uploaded2':
          {
            $Queries[] = 'uploaded < \'' . mysql_real_escape_string ($Value * 1024 * 1024 * 1024) . '\'';
            $HighlightColumns[] = 'uploaded2';
            break;
          }

          case 'downloaded':
          {
            $Queries[] = 'downloaded >= \'' . mysql_real_escape_string ($Value * 1024 * 1024 * 1024) . '\'';
            $HighlightColumns[] = 'downloaded';
            break;
          }

          case 'downloaded2':
          {
            $Queries[] = 'downloaded < \'' . mysql_real_escape_string ($Value * 1024 * 1024 * 1024) . '\'';
            $HighlightColumns[] = 'downloaded2';
            break;
          }

          case 'ratio':
          {
            $Queries[] = 'uploaded / downloaded >= \'' . mysql_real_escape_string ($Value) . '\'';
            $HighlightColumns[] = 'ratio';
            break;
          }

          case 'ratio2':
          {
            $Queries[] = 'uploaded / downloaded < \'' . mysql_real_escape_string ($Value) . '\'';
            $HighlightColumns[] = 'ratio2';
            break;
          }

          case 'id':
          {
            $Queries[] = 'id >= \'' . mysql_real_escape_string ($Value) . '\'';
            $HighlightColumns[] = 'id';
            break;
          }

          case 'id2':
          {
            $Queries[] = 'id < \'' . mysql_real_escape_string ($Value) . '\'';
            $HighlightColumns[] = 'id2';
          }
        }

        continue;
      }
    }

    if (0 < count ($Queries))
    {
      switch ($_POST['orderby1'])
      {
        case 'username':
        {
        }

        case 'usergroup':
        {
        }

        case 'email':
        {
        }

        case 'added':
        {
        }

        case 'last_access':
        {
        }

        case 'birthday':
        {
        }

        case 'totalposts':
        {
        }

        case 'timeswarned':
        {
        }

        case 'uploaded':
        {
        }

        case 'downloaded':
        {
        }

        case 'id':
        {
          break;
        }

        default:
        {
          $_POST['orderby1'] = 'username';
        }
      }

      if ($_POST['orderby2'] != 'DESC')
      {
        $_POST['orderby2'] = 'ASC';
      }

      if ((empty ($_POST['limit1']) OR $_POST['limit1'] == 0))
      {
        $_POST['limit1'] = 0;
      }
      else
      {
        0 + $_POST['limit1']--;
      }

      if ((empty ($_POST['limit2']) OR $_POST['limit2'] == 0))
      {
        $_POST['limit2'] = $ts_perpage;
      }

      $Queries = implode (' AND ', $Queries);
      ($Query = sql_query ('SELECT users.*, g.title, g.namestyle FROM users LEFT JOIN usergroups g ON (users.usergroup=g.gid) WHERE ' . $Queries) OR sqlerr (__FILE__, 172));
      $TotalCount = mysql_num_rows ($Query);
      ($Query = sql_query ('SELECT users.*, g.title, g.namestyle FROM users LEFT JOIN usergroups g ON (users.usergroup=g.gid) WHERE ' . $Queries . ' ORDER BY ' . $_POST['orderby1'] . ' ' . $_POST['orderby2'] . ' LIMIT ' . $_POST['limit1'] . ', ' . $_POST['limit2']) OR sqlerr (__FILE__, 174));
      if (0 < $TotalCount)
      {
        $matches .= $pagertop . '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead" colspan="13">' . ts_collapse ('found') . sprintf ($lang->usersearch['found'], $TotalCount, ts_nf ($_POST['limit1']), ts_nf ($_POST['limit2'])) . '</td>
				</tr>
				' . ts_collapse ('found', 2) . '
				<tr>
					<td class="subheader">' . $lang->usersearch['r0'] . '</td>
					<td class="subheader">' . $lang->usersearch['as1'] . '</td>
					<td class="subheader">' . $lang->usersearch['as2'] . '</td>
					<td class="subheader">' . $lang->usersearch['as3'] . '</td>
					<td class="subheader">' . $lang->usersearch['as14'] . '</td>
					<td class="subheader">' . $lang->usersearch['r1'] . '</td>
					<td class="subheader">' . $lang->usersearch['r2'] . '</td>
					<td class="subheader">' . $lang->usersearch['r3'] . '</td>
					<td class="subheader">' . $lang->usersearch['r4'] . '</td>
					<td class="subheader">' . $lang->usersearch['r5'] . '</td>
					<td class="subheader">' . $lang->usersearch['r6'] . '</td>
					<td class="subheader">' . $lang->usersearch['r7'] . '</td>
					<td class="subheader">' . $lang->usersearch['r8'] . '</td>
				</tr>
			';
        require_once INC_PATH . '/functions_ratio.php';
        while ($Users = mysql_fetch_assoc ($Query))
        {
          $matches .= '
				<tr>
					<td>' . (0 + $Users['id']) . '</td>
					<td><a href="' . ts_seo ($Users['id'], $Users['username']) . '">' . get_user_color ($Users['username'], $Users['namestyle']) . '</a></td>
					<td>' . get_user_color ($Users['title'], $Users['namestyle']) . '</td>
					<td>' . htmlspecialchars_uni ($Users['email']) . '</td>
					<td>' . htmlspecialchars_uni ($Users['ip']) . '</td>
					<td>' . my_datee ($regdateformat, $Users['added']) . '</td>
					<td>' . my_datee ($dateformat, $Users['last_access']) . ' ' . my_datee ($timeformat, $Users['last_access']) . '</td>
					<td>' . htmlspecialchars_uni ($Users['birthday']) . '</td>
					<td>' . ts_nf (0 + $Users['totalposts']) . '</td>
					<td>' . ts_nf (0 + $Users['timeswarned']) . '</td>
					<td>' . mksize ($Users['uploaded']) . '</td>
					<td>' . mksize ($Users['downloaded']) . '</td>
					<td>' . get_user_ratio ($Users['uploaded'], $Users['downloaded']) . '</td>
				</tr>';
        }

        $matches .= '
				</tbody>
			</table>' . $pagerbottom . '
			<br />';
      }
      else
      {
        $errors[] = $lang->usersearch['error'];
      }
    }
  }

  $showusergroups = '<select name="usergroup" style="width:207px;"><option value="-1">---------------</option>';
  ($Query = sql_query ('SELECT gid,title FROM usergroups') OR sqlerr (__FILE__, 232));
  while ($UG = mysql_fetch_assoc ($Query))
  {
    $showusergroups .= '<option value="' . $UG['gid'] . '"' . ((isset ($_POST['usergroup']) AND $_POST['usergroup'] == $UG['gid']) ? ' selected="selected"' : '') . '>' . $UG['title'] . '</option>';
  }

  $showusergroups .= '</select>';
  stdhead ($lang->usersearch['head'], true, 'collapse');
  show_us_errors ();
  $Quicksearch .= '
<script language="JavaScript" src="scripts/calendar1.js"></script>
<script language="JavaScript" src="scripts/calendar3.js"></script>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead">' . ts_collapse ('quicksearch') . $lang->usersearch['title1'] . '</td>
	</tr>
	' . ts_collapse ('quicksearch', 2) . '
		<tr>
			<td><a href="index.php?act=userslist&amp;searchby=all">' . $lang->usersearch['qs0'] . '</a> - <a href="index.php?act=whoisonline&amp;action=today">' . $lang->usersearch['qs1'] . '</a> - <a href="index.php?act=latest_users">' . $lang->usersearch['qs2'] . '</a> - <a href="index.php?act=unco">' . $lang->usersearch['qs3'] . '</a> - <a href="index.php?act=warned">' . $lang->usersearch['qs4'] . '</a> - <a href="index.php?act=donations">' . $lang->usersearch['qs5'] . '</a> - <a href="index.php?act=inactiveusers">' . $lang->usersearch['qs6'] . '</a> - <a href="index.php?act=uploaders">' . $lang->usersearch['qs7'] . '</a> - <a href="index.php?act=leechers">' . $lang->usersearch['qs8'] . '</a></td>
		</tr>
	</tbody>
</table>
<br />';
  $str .= '
<form method="post" action="' . $_this_script_ . '" name="usersearch">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead" colspan="2">' . ts_collapse ('advancedsearch') . $lang->usersearch['title2'] . '</td>
	</tr>
	' . ts_collapse ('advancedsearch', 2) . '
		<tr>
			<td align="right"><b>' . $lang->usersearch['as1'] . '</b></td>
			<td align="left"' . (in_array ('username', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="username" value="' . (isset ($_POST['username']) ? htmlspecialchars_uni ($_POST['username']) : '') . '" size="30" /> <input type="checkbox" name="exactmatch" value="yes"' . ($_POST['exactmatch'] == 'yes' ? ' checked="checked"' : '') . ' /> <b>' . $lang->usersearch['match'] . '</b></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as2'] . '</b></td>
			<td align="left"' . (in_array ('usergroup', $HighlightColumns) ? ' class="highlight"' : '') . '>' . $showusergroups . '</td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as3'] . '</b></td>
			<td align="left"' . (in_array ('email', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="email" value="' . (isset ($_POST['email']) ? htmlspecialchars_uni ($_POST['email']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as14'] . '</b></td>
			<td align="left"' . (in_array ('ip', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="ip" value="' . (isset ($_POST['ip']) ? htmlspecialchars_uni ($_POST['ip']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as4'] . '</b></td>
			<td align="left"' . (in_array ('added', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="added" value="' . (isset ($_POST['added']) ? htmlspecialchars_uni ($_POST['added']) : '') . '" size="30" /> <a href="javascript:JoinDateAfter.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">
				var siteadrr = "' . $BASEURL . '/admin/scripts/";
				var JoinDateAfter = new calendar3(document.forms[\'usersearch\'].elements[\'added\']);
				JoinDateAfter.year_scroll = true;
				JoinDateAfter.time_comp = true;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as5'] . '</b></td>
			<td align="left"' . (in_array ('added2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="added2" value="' . (isset ($_POST['added2']) ? htmlspecialchars_uni ($_POST['added2']) : '') . '" size="30" /> <a href="javascript:JoinDateAfter2.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">			
				var JoinDateAfter2 = new calendar3(document.forms[\'usersearch\'].elements[\'added2\']);
				JoinDateAfter2.year_scroll = true;
				JoinDateAfter2.time_comp = true;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as6'] . '</b></td>
			<td align="left"' . (in_array ('last_access', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="last_access" value="' . (isset ($_POST['last_access']) ? htmlspecialchars_uni ($_POST['last_access']) : '') . '" size="30" /> <a href="javascript:last_access.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">			
				var last_access = new calendar3(document.forms[\'usersearch\'].elements[\'last_access\']);
				last_access.year_scroll = true;
				last_access.time_comp = true;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as7'] . '</b></td>
			<td align="left"' . (in_array ('last_access2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="last_access2" value="' . (isset ($_POST['last_access2']) ? htmlspecialchars_uni ($_POST['last_access2']) : '') . '" size="30" /> <a href="javascript:last_access2.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">			
				var last_access2 = new calendar3(document.forms[\'usersearch\'].elements[\'last_access2\']);
				last_access2.year_scroll = true;
				last_access2.time_comp = true;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as8'] . '</b></td>
			<td align="left"' . (in_array ('birthday', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="birthday" value="' . (isset ($_POST['birthday']) ? htmlspecialchars_uni ($_POST['birthday']) : '') . '" size="30" /> <a href="javascript:birthday.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">			
				var birthday = new calendar1(document.forms[\'usersearch\'].elements[\'birthday\']);
				birthday.year_scroll = true;
				birthday.time_comp = false;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as9'] . '</b></td>
			<td align="left"' . (in_array ('birthday2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="birthday2" value="' . (isset ($_POST['birthday2']) ? htmlspecialchars_uni ($_POST['birthday2']) : '') . '" size="30" /> <a href="javascript:birthday2.popup();"><img src="scripts/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" title="Click Here to Pick up the date"></a>
			 <script type="text/javascript">			
				var birthday2 = new calendar1(document.forms[\'usersearch\'].elements[\'birthday2\']);
				birthday2.year_scroll = true;
				birthday2.time_comp = false;
			</script></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as10'] . '</b></td>
			<td align="left"' . (in_array ('totalposts', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="totalposts" value="' . (isset ($_POST['totalposts']) ? htmlspecialchars_uni ($_POST['totalposts']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as11'] . '</b></td>
			<td align="left"' . (in_array ('totalposts2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="totalposts2" value="' . (isset ($_POST['totalposts2']) ? htmlspecialchars_uni ($_POST['totalposts2']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as12'] . '</b></td>
			<td align="left"' . (in_array ('timeswarned', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="timeswarned" value="' . (isset ($_POST['timeswarned']) ? htmlspecialchars_uni ($_POST['timeswarned']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as13'] . '</b></td>
			<td align="left"' . (in_array ('timeswarned2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="timeswarned2" value="' . (isset ($_POST['timeswarned2']) ? htmlspecialchars_uni ($_POST['timeswarned2']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as15'] . '</b></td>
			<td align="left"' . (in_array ('uploaded', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="uploaded" value="' . (isset ($_POST['uploaded']) ? htmlspecialchars_uni ($_POST['uploaded']) : '') . '" size="30" /> [GB]</td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as16'] . '</b></td>
			<td align="left"' . (in_array ('uploaded2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="uploaded2" value="' . (isset ($_POST['uploaded2']) ? htmlspecialchars_uni ($_POST['uploaded2']) : '') . '" size="30" /> [GB]</td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as17'] . '</b></td>
			<td align="left"' . (in_array ('downloaded', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="downloaded" value="' . (isset ($_POST['downloaded']) ? htmlspecialchars_uni ($_POST['downloaded']) : '') . '" size="30" /> [GB]</td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as18'] . '</b></td>
			<td align="left"' . (in_array ('downloaded2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="downloaded2" value="' . (isset ($_POST['downloaded2']) ? htmlspecialchars_uni ($_POST['downloaded2']) : '') . '" size="30" /> [GB]</td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as19'] . '</b></td>
			<td align="left"' . (in_array ('ratio', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="ratio" value="' . (isset ($_POST['ratio']) ? htmlspecialchars_uni ($_POST['ratio']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as20'] . '</b></td>
			<td align="left"' . (in_array ('ratio2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="ratio2" value="' . (isset ($_POST['ratio2']) ? htmlspecialchars_uni ($_POST['ratio2']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as21'] . '</b></td>
			<td align="left"' . (in_array ('id', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="id" value="' . (isset ($_POST['id']) ? htmlspecialchars_uni ($_POST['id']) : '') . '" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['as22'] . '</b></td>
			<td align="left"' . (in_array ('id2', $HighlightColumns) ? ' class="highlight"' : '') . '><input type="text" name="id2" value="' . (isset ($_POST['id2']) ? htmlspecialchars_uni ($_POST['id2']) : '') . '" size="30" /></td>
		</tr>		
	</tbody>
</table>
<br />
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead" colspan="2">' . ts_collapse ('sortoptions') . $lang->usersearch['title3'] . '</td>
	</tr>
	' . ts_collapse ('sortoptions', 2) . '
		<tr>
			<td align="right"><b>' . $lang->usersearch['s0'] . '</b></td>
			<td align="left">
				<select name="orderby1">
					<option value="username"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'username') ? ' selected="selected"' : '') . '>' . $lang->usersearch['as1'] . '</option>
					<option value="usergroup"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'usergroup') ? ' selected="selected"' : '') . '>' . $lang->usersearch['as2'] . '</option>
					<option value="email"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'email') ? ' selected="selected"' : '') . '>' . $lang->usersearch['as3'] . '</option>
					<option value="added"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'added') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r1'] . '</option>
					<option value="last_access"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'last_access') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r2'] . '</option>
					<option value="birthday"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'birthday') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r3'] . '</option>
					<option value="totalposts"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'totalposts') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r4'] . '</option>
					<option value="timeswarned"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'timeswarned') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r5'] . '</option>
					<option value="uploaded"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'uploaded') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r6'] . '</option>
					<option value="downloaded"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'downloaded') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r7'] . '</option>					
					<option value="id"' . ((isset ($_POST['orderby1']) AND $_POST['orderby1'] == 'id') ? ' selected="selected"' : '') . '>' . $lang->usersearch['r0'] . '</option>
				</select>
				<select name="orderby2">
					<option value="ASC"' . ((isset ($_POST['orderby2']) AND $_POST['orderby2'] == 'ASC') ? ' selected="selected"' : '') . '>' . $lang->usersearch['s3'] . '</option>
					<option value="DESC"' . ((isset ($_POST['orderby2']) AND $_POST['orderby2'] == 'DESC') ? ' selected="selected"' : '') . '>' . $lang->usersearch['s4'] . '</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td align="right"><b>' . $lang->usersearch['s1'] . '</b></td>
			<td align="left"><input type="text" name="limit1" value="' . (isset ($_POST['limit1']) ? intval ($_POST['limit1']) : 1) . '" size="10" /></td>
		</tr>
		<tr>
			<td align="right"><b>' . $lang->usersearch['s2'] . '</b></td>
			<td align="left"><input type="text" name="limit2" value="' . (isset ($_POST['limit2']) ? intval ($_POST['limit2']) : $ts_perpage) . '" size="10" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center" class="subheader"><input type="submit" value="' . $lang->usersearch['find'] . '" /> <input type="reset" value="' . $lang->usersearch['reset'] . '" /></td>
		</tr>
	<tbody>
</table>
</form>
';
  echo $Quicksearch . $matches . $str;
  stdfoot ();
  exit ();
?>
