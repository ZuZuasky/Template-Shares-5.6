<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function sgpermission ($Option, $UG = '')
  {
    global $usergroups;
    $Work = $usergroups;
    if ($UG)
    {
      $Work['sgperms'] = $UG;
    }

    $Options = array ('canview' => '0', 'cancreate' => '1', 'canpost' => '2', 'candelete' => '3', 'canjoin' => '4', 'canedit' => '5', 'canmanagemsg' => '6', 'canmanagegroup' => '7');
    $What = (isset ($Options[$Option]) ? $Options[$Option] : 0);
    return ($Work['sgperms'][$What] == '1' ? true : false);
  }

  function show_sg_errors ()
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

  error_reporting (E_ALL & ~E_NOTICE);
  define ('NcodeImageResizer', true);
  define ('THIS_SCRIPT', 'ts_social_groups.php');
  define ('SG_VERSION', '0.7 ');
  require 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  if (!sgpermission ('canview'))
  {
    print_no_permission ();
  }

  $errors = array ();
  $is_mod = is_mod ($usergroups);
  $do = (isset ($_GET['do']) ? trim ($_GET['do']) : (isset ($_POST['do']) ? trim ($_POST['do']) : ''));
  $groupid = (isset ($_GET['groupid']) ? intval ($_GET['groupid']) : (isset ($_POST['groupid']) ? intval ($_POST['groupid']) : 0));
  $lang->load ('ts_social_groups');
  $str = '
<script type="text/javascript" src="' . $BASEURL . '/scripts/prototype.js?V=' . O_SCRIPT_VERSION . '"></script>';
  if (($do == 'manage' AND is_valid_id ($groupid)))
  {
    ($Query = sql_query ('SELECT name, owner FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 45));
    if (0 < mysql_num_rows ($Query))
    {
      $Owner = mysql_result ($Query, 0, 'owner');
      $Name = mysql_result ($Query, 0, 'name');
      if ((($Owner != $CURUSER['id'] AND !$is_mod) OR !sgpermission ('canmanagegroup')))
      {
        print_no_permission (true);
      }
      else
      {
        if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
        {
          if ((!empty ($_POST['username']) AND !empty ($_POST['reason'])))
          {
            $Username = trim ($_POST['username']);
            $Reason = trim ($_POST['reason']);
            ($Query = sql_query ('SELECT u.id, m.userid FROM users u LEFT JOIN ts_social_group_members m ON (u.id=m.userid) WHERE m.userid=u.id AND m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'public\' AND u.username = ' . sqlesc ($Username)) OR sqlerr (__FILE__, 62));
            if (0 < mysql_num_rows ($Query))
            {
              $User = mysql_fetch_assoc ($Query);
              if ($User['id'] == $Owner)
              {
                $errors[] = $lang->ts_social_groups['error14'];
              }
              else
              {
                ($Query = sql_query ('DELETE FROM ts_social_group_members WHERE groupid = ' . sqlesc ($groupid) . ' AND type = \'public\' AND userid = ' . sqlesc ($User['id'])) OR sqlerr (__FILE__, 72));
                if (mysql_affected_rows ())
                {
                  sql_query ('UPDATE ts_social_groups SET members = IF(members > 0, members - 1, 0) WHERE groupid = ' . sqlesc ($groupid));
                  require_once INC_PATH . '/functions_pm.php';
                  $subject = $lang->ts_social_groups['kicktitle'];
                  $msg = sprintf ($lang->ts_social_groups['kickmsg'], '[URL=' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '][b]' . $CURUSER['username'] . '[/b][/URL]', '[b]' . htmlspecialchars_uni ($Name) . '[/b]', $Reason);
                  send_pm ($User['id'], $msg, $subject);
                  redirect ($_SERVER['SCRIPT_NAME'] . '?do=manage&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : ''));
                }
                else
                {
                  $errors[] = $lang->ts_social_groups['error8'];
                }
              }
            }
            else
            {
              $errors[] = $lang->ts_social_groups['error8'];
            }
          }
        }
        else
        {
          if ((!empty ($_GET['userid']) AND is_valid_id ($_GET['userid'])))
          {
            $Userid = intval ($_GET['userid']);
            ($Query = sql_query ('SELECT u.id, m.userid FROM users u LEFT JOIN ts_social_group_members m ON (u.id=m.userid) WHERE m.userid=u.id AND m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'inviteonly\' AND u.id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 97));
            if (0 < mysql_num_rows ($Query))
            {
              $User = mysql_fetch_assoc ($Query);
              if ($User['id'] == $Owner)
              {
                $errors[] = $lang->ts_social_groups['error14'];
              }
              else
              {
                ($Query = sql_query ('DELETE FROM ts_social_group_members WHERE groupid = ' . sqlesc ($groupid) . ' AND type = \'inviteonly\' AND userid = ' . sqlesc ($User['id'])) OR sqlerr (__FILE__, 107));
                if (mysql_affected_rows ())
                {
                  require_once INC_PATH . '/functions_pm.php';
                  $subject = $lang->ts_social_groups['invitectitle'];
                  $msg = sprintf ($lang->ts_social_groups['invitecmsg'], '[b]' . htmlspecialchars_uni ($Name) . '[/b]', '[URL=' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '][b]' . $CURUSER['username'] . '[/b][/URL]');
                  send_pm ($User['id'], $msg, $subject);
                  redirect ($_SERVER['SCRIPT_NAME'] . '?do=manage&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : ''));
                }
                else
                {
                  $errors[] = $lang->ts_social_groups['error8'];
                }
              }
            }
            else
            {
              $errors[] = $lang->ts_social_groups['error8'];
            }
          }
        }

        $Query = sql_query ('SELECT m.userid, m.type, u.username, u.avatar, g.namestyle FROM ts_social_group_members m LEFT JOIN users u ON (u.id=m.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'public\' ORDER by u.username ASC, m.joined DESC');
        $TotalMembers = mysql_num_rows ($Query);
        $ShowMembers = '';
        if (0 < $TotalMembers)
        {
          $ShowMembers = '
				<table border="0" cellpadding="3" cellspacing="0">
					<tr>
				';
          $count = 0;
          while ($Members = mysql_fetch_assoc ($Query))
          {
            if ($count % 15 == 0)
            {
              $ShowMembers .= '</tr><tr>';
            }

            $ULink = '<a href="' . ts_seo ($Members['userid'], $Members['username']) . '">' . get_user_color ($Members['username'], $Members['namestyle']) . '</a>';
            $UAvatar = get_user_avatar ($Members['avatar'], true, '50', '50');
            $ShowMembers .= '
							<td class="none">' . $UAvatar . '<br />' . $ULink . '</td>
					';
            ++$count;
          }

          $ShowMembers .= '
					</tr>
				</table>';
        }

        $str .= '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('managemembers') . '
						' . $lang->ts_social_groups['title1'] . ' (' . ts_nf ($TotalMembers) . ')
					</td>
				</tr>
				' . ts_collapse ('managemembers', 2) . '
					<tr>
						<td>
							' . $ShowMembers . '
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			';
        $str .= '
			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=manage&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">
			<input type="hidden" name="do" value="manage" />
			<input type="hidden" name="groupid" value="' . intval ($groupid) . '" />
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('kickmember') . '
						' . $lang->ts_social_groups['kickm'] . '
					</td>
				</tr>
				' . ts_collapse ('kickmember', 2) . '
					<tr>
						<td>
							<fieldset>
								<legend>' . $lang->ts_social_groups['username'] . '</legend>
								<input type="text" size="30" name="username" value="' . (isset ($Username) ? htmlspecialchars_uni ($Username) : '') . '" />
							</fieldset>
							<fieldset>
								<legend>' . $lang->ts_social_groups['kickreason'] . '</legend>
								<textarea name="reason" rows="6" cols="85">' . (isset ($Reason) ? htmlspecialchars_uni ($Reason) : '') . '</textarea><br />
								<input type="submit" value="' . $lang->ts_social_groups['kickm'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			';
        $Query = sql_query ('SELECT m.userid, m.type, u.username, u.avatar, g.namestyle FROM ts_social_group_members m LEFT JOIN users u ON (u.id=m.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'inviteonly\' ORDER by u.username ASC, m.joined DESC');
        $TotalMembers = mysql_num_rows ($Query);
        $ShowMembers = '';
        if (0 < $TotalMembers)
        {
          $ShowMembers = '
				<table border="0" cellpadding="3" cellspacing="0">
					<tr>
				';
          $count = 0;
          while ($Members = mysql_fetch_assoc ($Query))
          {
            if ($count % 15 == 0)
            {
              $ShowMembers .= '</tr><tr>';
            }

            $ULink = '<a href="' . ts_seo ($Members['userid'], $Members['username']) . '">' . get_user_color ($Members['username'], $Members['namestyle']) . '</a>';
            $UAvatar = get_user_avatar ($Members['avatar'], true, '50', '50');
            $ShowMembers .= '
							<td class="none">' . $UAvatar . '<br />' . $ULink . ' [<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=manage&amp;userid=' . $Members['userid'] . '&amp;groupid=' . $groupid . '" alt="' . $lang->ts_social_groups['deleteinv'] . '" title="' . $lang->ts_social_groups['deleteinv'] . '"><b>x</b></a>]</td>
					';
            ++$count;
          }

          $ShowMembers .= '
					</tr>
				</table>';
        }
        else
        {
          $ShowMembers .= '<tr><td>' . $lang->ts_social_groups['nopending'] . '</td></tr>';
        }

        $str .= '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('pending') . '
						' . $lang->ts_social_groups['pending'] . ' (' . ts_nf ($TotalMembers) . ')
					</td>
				</tr>
				' . ts_collapse ('pending', 2) . '
					<tr>
						<td>
							' . $ShowMembers . '
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			';
        stdhead ($lang->ts_social_groups['managem'], true, 'collapse');
        show_sg_errors ();
        echo $str;
        stdfoot ();
        exit ();
      }
    }
    else
    {
      $errors[] = $lang->ts_social_groups['invalid'];
    }
  }

  if (((($do == 'delete_report' AND is_valid_id ($groupid)) AND isset ($_GET['rid'])) AND is_valid_id ($_GET['rid'])))
  {
    $Rid = intval ($_GET['rid']);
    ($Query = sql_query ('SELECT r.rid, sg.owner FROM ts_social_group_reports r LEFT JOIN ts_social_groups sg ON (r.groupid=sg.groupid) WHERE r.rid = ' . sqlesc ($Rid) . ' AND r.groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 274));
    if (0 < mysql_num_rows ($Query))
    {
      $Report = mysql_fetch_assoc ($Query);
      if ((($Report['owner'] == $CURUSER['id'] OR $is_mod) AND sgpermission ('canmanagegroup')))
      {
        (sql_query ('DELETE FROM ts_social_group_reports WHERE rid = ' . sqlesc ($Rid) . ' AND groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 280));
        redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : ''));
        exit ();
      }
    }
  }

  if (((($do == 'report_msg' AND is_valid_id ($groupid)) AND isset ($_GET['mid'])) AND is_valid_id ($_GET['mid'])))
  {
    $Mid = intval ($_GET['mid']);
    ($Query = sql_query ('SELECT m.mid, sg.name FROM ts_social_group_messages m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.mid = ' . sqlesc ($Mid) . ' AND m.groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 290));
    if (0 < mysql_num_rows ($Query))
    {
      ($Query = sql_query ('SELECT rid FROM ts_social_group_reports WHERE mid = ' . sqlesc ($Mid) . ' AND groupid = ' . sqlesc ($groupid) . ' AND userid = ' . sqlesc ($CURUSER['id'])) OR sqlerr (__FILE__, 293));
      if (0 < mysql_num_rows ($Query))
      {
        stderr ($lang->global['error'], $lang->ts_social_groups['error12']);
      }
      else
      {
        if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
        {
          $Report = trim ($_POST['reason']);
          $Dateline = time ();
          $SavePage = (isset ($_GET['page']) ? intval ($_GET['page']) : 0);
          if ((!$Report OR strlen ($Report) < 3))
          {
            $errors[] = $lang->ts_social_groups['error13'];
          }
          else
          {
            (sql_query ('INSERT INTO ts_social_group_reports (mid, groupid, userid, dateline, report, page) VALUES (' . sqlesc ($Mid) . ', ' . sqlesc ($groupid) . ', ' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($Dateline) . ', ' . sqlesc ($Report) . ', ' . sqlesc ($SavePage) . ')') OR sqlerr (__FILE__, 311));
            redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '#message_' . $Mid);
            exit ();
          }
        }

        stdhead ($lang->ts_social_groups['reportpost'], true, 'collapse');
        show_sg_errors ();
        $str .= '
			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=report_msg&amp;mid=' . $Mid . '&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">
			<input type="hidden" name="do" value="report_msg" />
			<input type="hidden" name="groupid" value="' . intval ($groupid) . '" />
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('reportpost') . '
						' . $lang->ts_social_groups['reportpost'] . '
					</td>
				</tr>
				' . ts_collapse ('reportpost', 2) . '
					<tr>
						<td>
							<fieldset>
								<legend>' . $lang->ts_social_groups['reason'] . '</legend>
								<textarea name="reason" rows="6" cols="85">' . (isset ($Report) ? htmlspecialchars_uni ($Report) : '') . '</textarea><br />
								<input type="submit" value="' . $lang->ts_social_groups['save'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
							</fieldset>
						</td>
					</tr>
				</tbody>
			';
        $str .= '
			</table>
			</form>';
        echo $str;
        stdfoot ();
        exit ();
      }
    }
    else
    {
      print_no_permission ();
    }
  }

  if (((($do == 'edit_msg' AND is_valid_id ($groupid)) AND isset ($_GET['mid'])) AND is_valid_id ($_GET['mid'])))
  {
    $Mid = intval ($_GET['mid']);
    ($Query = sql_query ('SELECT m.userid, m.message, sg.owner FROM ts_social_group_messages m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.mid = ' . sqlesc ($Mid) . ' AND m.groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 358));
    if (0 < mysql_num_rows ($Query))
    {
      $Msg = mysql_fetch_assoc ($Query);
      if (((($Msg['owner'] == $CURUSER['id'] AND sgpermission ('canmanagegroup')) OR ($is_mod AND sgpermission ('canmanagegroup'))) OR ($Msg['userid'] == $CURUSER['id'] AND sgpermission ('canmanagemsg'))))
      {
        if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
        {
          $Message = trim ($_POST['message']);
          if ((!$Message OR strlen ($Message) < 3))
          {
            $errors[] = $lang->ts_social_groups['error1'];
          }
          else
          {
            (sql_query ('UPDATE ts_social_group_messages SET message = ' . sqlesc ($Message) . ' WHERE mid = ' . sqlesc ($Mid) . ' AND groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 373));
            redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '#message_' . $Mid);
            exit ();
          }
        }

        stdhead ($lang->ts_social_groups['editpost'], true, 'collapse');
        show_sg_errors ();
        $str .= '
			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=edit_msg&amp;mid=' . $Mid . '&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">
			<input type="hidden" name="do" value="edit_msg" />
			<input type="hidden" name="groupid" value="' . intval ($groupid) . '" />
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('editmsg') . '
						' . $lang->ts_social_groups['editpost'] . '
					</td>
				</tr>
				' . ts_collapse ('editmsg', 2) . '
					<tr>
						<td>
							<fieldset>
								<legend>' . $lang->ts_social_groups['message'] . '</legend>
								<textarea name="message" rows="6" cols="85">' . (isset ($Message) ? htmlspecialchars_uni ($Message) : htmlspecialchars_uni ($Msg['message'])) . '</textarea><br />
								<input type="submit" value="' . $lang->ts_social_groups['save'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
							</fieldset>
						</td>
					</tr>
				</tbody>
			';
        $str .= '
			</table>
			</form>';
        echo $str;
        stdfoot ();
        exit ();
      }
      else
      {
        print_no_permission (true);
      }
    }
    else
    {
      print_no_permission (true);
    }
  }

  if (((($do == 'delete_msg' AND is_valid_id ($groupid)) AND isset ($_GET['mid'])) AND is_valid_id ($_GET['mid'])))
  {
    $Mid = intval ($_GET['mid']);
    ($Query = sql_query ('SELECT m.userid, sg.owner FROM ts_social_group_messages m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.mid = ' . sqlesc ($Mid) . ' AND m.groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 424));
    if (0 < mysql_num_rows ($Query))
    {
      $Msg = mysql_fetch_assoc ($Query);
      if (((($Msg['owner'] == $CURUSER['id'] AND sgpermission ('canmanagegroup')) OR ($is_mod AND sgpermission ('canmanagegroup'))) OR ($Msg['userid'] == $CURUSER['id'] AND sgpermission ('canmanagemsg'))))
      {
        sql_query ('DELETE FROM ts_social_group_messages WHERE mid = ' . sqlesc ($Mid) . ' AND groupid = ' . sqlesc ($groupid));
        if (mysql_affected_rows ())
        {
          $LastPostResults = array ('userid' => '', 'posted' => '');
          $Query = sql_query (' SELECT userid, posted FROM ts_social_group_messages WHERE groupid = ' . sqlesc ($groupid) . ' ORDER by posted DESC LIMIT 1');
          if (0 < mysql_num_rows ($Query))
          {
            $LastPostResults = mysql_fetch_assoc ($Query);
          }

          sql_query ('UPDATE ts_social_groups SET messages = IF(messages > 0, messages - 1, 0), lastpostdate = \'' . $LastPostResults['posted'] . '\', lastposter = \'' . $LastPostResults['userid'] . '\' WHERE groupid = ' . sqlesc ($groupid));
        }

        redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : ''));
        exit ();
      }
      else
      {
        print_no_permission (true);
      }
    }
    else
    {
      print_no_permission (true);
    }
  }

  if (($do == 'deny_invite' AND is_valid_id ($groupid)))
  {
    ($Query = sql_query ('SELECT m.type, sg.name, sg.owner FROM ts_social_group_members m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.userid = ' . sqlesc ($CURUSER['id']) . ' AND m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'inviteonly\'') OR sqlerr (__FILE__, 457));
    if (mysql_num_rows ($Query) == 0)
    {
      stderr ($lang->global['error'], $lang->ts_social_groups['error4']);
    }
    else
    {
      $Res = mysql_fetch_assoc ($Query);
      if ((!$Res['name'] OR $Res['owner'] == $CURUSER['id']))
      {
        stderr ($lang->global['error'], $lang->ts_social_groups['invalid']);
      }
      else
      {
        (sql_query ('DELETE FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND groupid = ' . sqlesc ($groupid) . ' AND type = \'inviteonly\'') OR sqlerr (__FILE__, 471));
        redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid));
        exit ();
      }
    }
  }

  if ((($do == 'accept_invite' AND is_valid_id ($groupid)) AND sgpermission ('canjoin')))
  {
    ($Query = sql_query ('SELECT m.type, sg.name, sg.owner FROM ts_social_group_members m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.userid = ' . sqlesc ($CURUSER['id']) . ' AND m.groupid = ' . sqlesc ($groupid) . ' AND m.type = \'inviteonly\'') OR sqlerr (__FILE__, 480));
    if (mysql_num_rows ($Query) == 0)
    {
      stderr ($lang->global['error'], $lang->ts_social_groups['error4']);
    }
    else
    {
      $Res = mysql_fetch_assoc ($Query);
      if ((!$Res['name'] OR $Res['owner'] == $CURUSER['id']))
      {
        stderr ($lang->global['error'], $lang->ts_social_groups['invalid']);
      }
      else
      {
        (sql_query ('UPDATE ts_social_group_members SET type = \'public\' WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND groupid = ' . sqlesc ($groupid) . ' AND type = \'inviteonly\'') OR sqlerr (__FILE__, 494));
        (sql_query ('UPDATE ts_social_groups SET members = members + 1 WHERE groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 495));
        redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid));
        exit ();
      }
    }
  }

  if (($do == 'invite' AND is_valid_id ($groupid)))
  {
    $Query = sql_query ('SELECT name, owner FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
    if (0 < mysql_num_rows ($Query))
    {
      $Owner = mysql_result ($Query, 0, 'owner');
      $Name = mysql_result ($Query, 0, 'name');
      if (($Owner != $CURUSER['id'] AND !$is_mod))
      {
        print_no_permission (true);
      }
      else
      {
        if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
        {
          $Username = trim ($_POST['username']);
          $Query = sql_query ('SELECT u.id, g.sgperms FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.username = ' . sqlesc ($Username) . ' AND u.status=\'confirmed\' AND u.enabled=\'yes\' AND u.usergroup != \'' . UC_BANNED . '\'');
          if (mysql_num_rows ($Query) == 0)
          {
            $errors[] = $lang->ts_social_groups['error8'];
          }
          else
          {
            $User = mysql_fetch_assoc ($Query);
            if ($Owner == $User['id'])
            {
              $errors[] = $lang->ts_social_groups['error10'];
            }
            else
            {
              if (!sgpermission ('canjoin', $User['sgperms']))
              {
                $errors[] = $lang->ts_social_groups['error11'];
              }
              else
              {
                $Query = sql_query ('SELECT userid FROM ts_social_group_members WHERE userid = ' . sqlesc ($User['id']) . ' AND groupid = ' . sqlesc ($groupid));
                if (0 < mysql_num_rows ($Query))
                {
                  $errors[] = $lang->ts_social_groups['error9'];
                }
                else
                {
                  sql_query ('REPLACE INTO ts_social_group_members VALUES (' . sqlesc ($User['id']) . ', ' . sqlesc ($groupid) . ', ' . sqlesc (time ()) . ', \'inviteonly\')');
                  require_once INC_PATH . '/functions_pm.php';
                  $subject = $lang->ts_social_groups['invitetitle'];
                  $msg = sprintf ($lang->ts_social_groups['invitemsg'], '[b]' . htmlspecialchars_uni ($Name) . '[/b]', '[URL=' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '][b]' . $CURUSER['username'] . '[/b][/URL]', '[URL]' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?do=accept_invite&groupid=' . intval ($groupid) . '[/URL]', '[URL]' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?do=deny_invite&groupid=' . intval ($groupid) . '[/URL]');
                  send_pm ($User['id'], $msg, $subject);
                  redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : ''));
                  exit ();
                }
              }
            }
          }
        }

        stdhead ($lang->ts_social_groups['invitemem'], true, 'collapse');
        show_sg_errors ();
        $str .= '
			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=invite&amp;groupid=' . intval ($groupid) . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">
			<input type="hidden" name="do" value="invite" />
			<input type="hidden" name="groupid" value="' . intval ($groupid) . '" />
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('invite') . '
						' . $lang->ts_social_groups['invitemem'] . '
					</td>
				</tr>
				' . ts_collapse ('invite', 2) . '
					<tr>
						<td>
							<fieldset>
								<legend>' . $lang->ts_social_groups['username'] . '</legend>
								<input type="text" size="30" name="username" value="' . (isset ($Username) ? htmlspecialchars_uni ($Username) : '') . '" />
								<input type="submit" value="' . $lang->ts_social_groups['invite'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
							</fieldset>
						</td>
					</tr>
				</tbody>
			';
        $str .= '
			</table>
			</form>';
        echo $str;
        stdfoot ();
        exit ();
      }
    }
    else
    {
      $errors[] = $lang->ts_social_groups['invalid'];
    }
  }

  if ((($do == 'delete' AND is_valid_id ($groupid)) AND sgpermission ('candelete')))
  {
    $Query = sql_query ('SELECT owner FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
    if (0 < mysql_num_rows ($Query))
    {
      $Owner = mysql_result ($Query, 0, 'owner');
      if (($Owner != $CURUSER['id'] AND !$is_mod))
      {
        print_no_permission (true);
      }
      else
      {
        sql_query ('DELETE FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
        sql_query ('DELETE FROM ts_social_group_members WHERE groupid = ' . sqlesc ($groupid));
        sql_query ('DELETE FROM ts_social_group_messages WHERE groupid = ' . sqlesc ($groupid));
        sql_query ('DELETE FROM ts_social_group_reports WHERE groupid = ' . sqlesc ($groupid));
      }
    }
    else
    {
      $errors[] = $lang->ts_social_groups['invalid'];
    }
  }

  if (($do == 'leave' AND is_valid_id ($groupid)))
  {
    $Query = sql_query ('SELECT owner FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
    if (0 < mysql_num_rows ($Query))
    {
      $Owner = mysql_result ($Query, 0, 'owner');
      if ($Owner == $CURUSER['id'])
      {
        $errors[] = $lang->ts_social_groups['error5'];
      }
      else
      {
        sql_query ('DELETE FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND groupid = ' . sqlesc ($groupid));
        if (mysql_affected_rows ())
        {
          sql_query ('UPDATE ts_social_groups SET members = IF(members > 0, members - 1, 0) WHERE groupid = ' . sqlesc ($groupid));
        }
        else
        {
          $errors[] = $lang->ts_social_groups['error7'];
        }
      }
    }
    else
    {
      $errors[] = $lang->ts_social_groups['invalid'];
    }
  }

  if ((($do == 'join' AND is_valid_id ($groupid)) AND sgpermission ('canjoin')))
  {
    $Query = sql_query ('SELECT userid FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND groupid = ' . sqlesc ($groupid));
    if (mysql_num_rows ($Query) == 0)
    {
      $Query = sql_query ('SELECT type FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
      if (0 < mysql_num_rows ($Query))
      {
        $type = mysql_result ($Query, 0, 'type');
        if ($type == 'public')
        {
          sql_query ('REPLACE INTO ts_social_group_members VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($groupid) . ', ' . sqlesc (time ()) . ', \'public\')');
          if (mysql_affected_rows ())
          {
            (sql_query ('UPDATE ts_social_groups SET members = members + 1 WHERE groupid = ' . sqlesc ($groupid)) OR sqlerr (__FILE__, 660));
          }
        }
        else
        {
          $errors[] = $lang->ts_social_groups['error4'];
        }
      }
      else
      {
        $errors[] = $lang->ts_social_groups['invalid'];
      }
    }
    else
    {
      $errors[] = $lang->ts_social_groups['error6'];
    }
  }

  if ((($do == 'edit' AND sgpermission ('canedit')) AND is_valid_id ($groupid)))
  {
    $Query = sql_query ('SELECT name, description, type, owner FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
    if (0 < mysql_num_rows ($Query))
    {
      $EditGroup = mysql_fetch_assoc ($Query);
      if (($EditGroup['owner'] != $CURUSER['id'] AND !$is_mod))
      {
        print_no_permission (true);
      }
    }
    else
    {
      print_no_permission ();
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $type = ($_POST['type'] == 'public' ? 'public' : 'inviteonly');
      if (strlen ($name) < 3)
      {
        $errors[] = $lang->ts_social_groups['error2'];
      }

      if (strlen ($description) < 10)
      {
        $errors[] = $lang->ts_social_groups['error3'];
      }

      if ((count ($errors) == 0 AND (($name != $EditGroup['name'] OR $description != $EditGroup['description']) OR $type != $EditGroup['type'])))
      {
        (sql_query ('UPDATE ts_social_groups SET name = ' . sqlesc ($name) . ', description = ' . sqlesc ($description) . ', type = \'' . $type . '\' WHERE groupid=' . sqlesc ($groupid)) OR sqlerr (__FILE__, 709));
        if (mysql_affected_rows ())
        {
          redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid));
          exit ();
        }
        else
        {
          $errors[] = $lang->ts_social_groups['dberror'];
        }
      }
      else
      {
        redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . intval ($groupid));
        exit ();
      }
    }

    stdhead ($lang->ts_social_groups['edit'], true, 'collapse');
    show_sg_errors ();
    $str .= '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=edit&amp;groupid=' . intval ($groupid) . '">
	<input type="hidden" name="do" value="edit" />
	<input type="hidden" name="groupid" value="' . intval ($groupid) . '" />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead">
				' . ts_collapse ('create') . '
				' . $lang->ts_social_groups['create'] . '
			</td>
		</tr>
		' . ts_collapse ('create', 2) . '
			<tr>
				<td>
					<fieldset>
						<legend>' . $lang->ts_social_groups['name'] . '</legend>
						<input type="text" size="100" name="name" value="' . (isset ($name) ? htmlspecialchars_uni ($name) : htmlspecialchars_uni ($EditGroup['name'])) . '" />
					</fieldset>
					<fieldset>
						<legend>' . $lang->ts_social_groups['description'] . '</legend>
						<textarea name="description" rows="6" cols="85">' . (isset ($description) ? htmlspecialchars_uni ($description) : htmlspecialchars_uni ($EditGroup['description'])) . '</textarea>
					</fieldset>
					<fieldset>
						<legend>' . $lang->ts_social_groups['type'] . '</legend>
						<select name="type">
							<option value="public"' . ((isset ($type) AND $type == 'public') ? ' selected="selected"' : ($EditGroup['type'] == 'public' ? ' selected="selected"' : '')) . '>' . $lang->ts_social_groups['public'] . '</option>
							<option value="inviteonly"' . ((isset ($type) AND $type == 'inviteonly') ? ' selected="selected"' : ($EditGroup['type'] == 'inviteonly' ? ' selected="selected"' : '')) . '>' . $lang->ts_social_groups['inviteonly'] . '</option>
						</select>
						<input type="submit" value="' . $lang->ts_social_groups['save'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	';
    $str .= '
	</table>
	</form>';
    echo $str;
    stdfoot ();
    exit ();
  }

  if ((($do == 'create' AND sgpermission ('cancreate')) AND sgpermission ('canjoin')))
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $type = ($_POST['type'] == 'public' ? 'public' : 'inviteonly');
      if (strlen ($name) < 3)
      {
        $errors[] = $lang->ts_social_groups['error2'];
      }

      if (strlen ($description) < 10)
      {
        $errors[] = $lang->ts_social_groups['error3'];
      }

      if (count ($errors) == 0)
      {
        $owner = 0 + $CURUSER['id'];
        $dateline = time ();
        (sql_query ('INSERT INTO ts_social_groups (name, description, owner, dateline, members, type) VALUES (' . sqlesc ($name) . ', ' . sqlesc ($description) . ', \'' . $owner . '\', \'' . $dateline . '\', \'1\', \'' . $type . '\')') OR sqlerr (__FILE__, 790));
        $NewGroupid = mysql_insert_id ();
        if ((mysql_affected_rows () AND $NewGroupid))
        {
          (sql_query ('INSERT INTO ts_social_group_members VALUES (\'' . $owner . '\', \'' . $NewGroupid . '\', \'' . $dateline . '\', \'public\')') OR sqlerr (__FILE__, 794));
          redirect ($_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . $NewGroupid);
          exit ();
        }
        else
        {
          $errors[] = $lang->ts_social_groups['dberror'];
        }
      }
    }

    stdhead ($lang->ts_social_groups['create'], true, 'collapse');
    show_sg_errors ();
    $str .= '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=create">
	<input type="hidden" name="do" value="create" />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead">
				' . ts_collapse ('create') . '
				' . $lang->ts_social_groups['create'] . '
			</td>
		</tr>
		' . ts_collapse ('create', 2) . '
			<tr>
				<td>
					<fieldset>
						<legend>' . $lang->ts_social_groups['name'] . '</legend>
						<input type="text" size="100" name="name" value="' . (isset ($name) ? htmlspecialchars_uni ($name) : '') . '" />
					</fieldset>
					<fieldset>
						<legend>' . $lang->ts_social_groups['description'] . '</legend>
						<textarea name="description" rows="6" cols="85">' . (isset ($description) ? htmlspecialchars_uni ($description) : '') . '</textarea>
					</fieldset>
					<fieldset>
						<legend>' . $lang->ts_social_groups['type'] . '</legend>
						<select name="type">
							<option value="public"' . ((isset ($type) AND $type == 'public') ? ' selected="selected"' : '') . '>' . $lang->ts_social_groups['public'] . '</option>
							<option value="inviteonly"' . ((isset ($type) AND $type == 'inviteonly') ? ' selected="selected"' : '') . '>' . $lang->ts_social_groups['inviteonly'] . '</option>
						</select>
						<input type="submit" value="' . $lang->ts_social_groups['create'] . '" /> <input type="reset" value="' . $lang->ts_social_groups['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	';
    $str .= '
	</table>
	</form>';
    echo $str;
    stdfoot ();
    exit ();
  }

  if (($do == 'showgroup' AND is_valid_id ($groupid)))
  {
    ($Query = sql_query ('SELECT sg.name, sg.description, sg.owner, sg.type, u.username, g.namestyle FROM ts_social_groups sg LEFT JOIN users u ON (u.id=sg.owner) LEFT JOIN usergroups g ON (g.gid=u.usergroup) WHERE sg.groupid=' . sqlesc ($groupid)) OR sqlerr (__FILE__, 849));
    if (0 < mysql_num_rows ($Query))
    {
      if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND sgpermission ('canpost')))
      {
        $query = sql_query ('SELECT userid FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND type = \'public\'');
        if (mysql_num_rows ($query) == 0)
        {
          $errors[] = $lang->ts_social_groups['error7'];
        }
        else
        {
          $message = trim ($_POST['message']);
          if ((!$message OR strlen ($message) < 3))
          {
            $errors[] = $lang->ts_social_groups['error1'];
          }
          else
          {
            $userid = intval ($CURUSER['id']);
            $posted = time ();
            (sql_query ('INSERT INTO ts_social_group_messages (groupid, userid, posted, message) VALUES (' . sqlesc ($groupid) . ', ' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($posted) . ', ' . sqlesc ($message) . ')') OR sqlerr (__FILE__, 870));
            $mid = mysql_insert_id ();
            if (mysql_affected_rows ())
            {
              sql_query ('UPDATE ts_social_groups SET messages = messages + 1, lastpostdate = \'' . $posted . '\', lastposter = \'' . $CURUSER['id'] . '\' WHERE groupid = ' . sqlesc ($groupid));
            }
          }
        }
      }

      $SG = mysql_fetch_assoc ($Query);
      $InviteButton = ((($SG['type'] == 'inviteonly' AND $SG['owner'] == $CURUSER['id']) OR ($SG['type'] == 'inviteonly' AND $is_mod)) ? '<input type="button" value="' . $lang->ts_social_groups['invitemem'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=invite&amp;groupid=' . $groupid . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '\'); return false;" />' : '');
      $ManageButton = ((($is_mod AND sgpermission ('canmanagegroup')) OR ($SG['owner'] == $CURUSER['id'] AND sgpermission ('canmanagegroup'))) ? '<input type="button" value="' . $lang->ts_social_groups['managem'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=manage&amp;groupid=' . $groupid . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '\'); return false;" />' : '');
      $str .= ((!empty ($ManageButton) OR !empty ($InviteButton)) ? '<p style="float: right;">' . $ManageButton . ' ' . $InviteButton . '</p>' : '') . '
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td class="thead">
					' . ts_collapse ('groupss') . '
					' . $lang->ts_social_groups['head2'] . '
				</td>
			</tr>
			' . ts_collapse ('groupss', 2) . '
				<tr>
					<td>
						<span style="float: right;">' . sprintf (($SG['type'] == 'public' ? $lang->ts_social_groups['type1'] : $lang->ts_social_groups['type2']), '<a href="' . ts_seo ($SG['owner'], $SG['username']) . '">' . get_user_color ($SG['username'], $SG['namestyle']) . '</a>') . '</span>
						<h1>' . htmlspecialchars_uni ($SG['name']) . '</h1>
						' . htmlspecialchars_uni ($SG['description']) . '
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		';
      $Query = sql_query ('SELECT m.userid, m.type, u.username, u.avatar, g.namestyle FROM ts_social_group_members m LEFT JOIN users u ON (u.id=m.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE m.groupid = ' . sqlesc ($groupid) . ' ORDER by u.username ASC, m.joined DESC');
      $TotalMembers = mysql_num_rows ($Query);
      $ShowMembers = '';
      if (0 < $TotalMembers)
      {
        $ShowMembers = '
			<style type="text/css" id="TSSE_Social_Groups_CSS">
				#scg_member_list
				{
					margin: 0px;
					padding: 0px;
					list-style-type: none;
				}
				#scg_member_list .group_members_small
				{
					overflow: hidden;
					float: left;
					text-align: center;
					margin: 1px;
					height: 100px;
					width: 76px;
				}
			</style>
			<table border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td class="none">
						<div>
							<ul id="scg_member_list">
			';
        $IsGroupMember = false;
        while ($Members = mysql_fetch_assoc ($Query))
        {
          if (($Members['userid'] === $CURUSER['id'] AND $Members['type'] == 'public'))
          {
            $IsGroupMember = true;
          }

          if ($Members['type'] != 'inviteonly')
          {
            $Link = ts_seo ($Members['userid'], $Members['username']);
            $ShowMembers .= '
								<li class="group_members_small">
									<a href="' . $Link . '">' . get_user_avatar ($Members['avatar'], true, '60', '60') . '</a>
									<div class="smallfont" title="' . $Members['username'] . '">
										<a href="' . $Link . '">' . get_user_color ($Members['username'], $Members['namestyle']) . '</a>
									</div>
								</li>';
            continue;
          }
          else
          {
            --$TotalMembers;
            continue;
          }
        }

        $ShowMembers .= '
							</ul>
						</div>
					</td>
				</tr>
			</table>';
      }

      if ((($SG['type'] == 'inviteonly' AND !$IsGroupMember) AND !$is_mod))
      {
        print_no_permission ();
      }

      $str .= '
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td class="thead">
					' . ts_collapse ('memberss') . '
					' . $lang->ts_social_groups['title1'] . ' (' . ts_nf ($TotalMembers) . ')
				</td>
			</tr>
			' . ts_collapse ('memberss', 2) . '
				<tr>
					<td>
						' . $ShowMembers . '
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		';
      $ShowReports = '';
      if ((($SG['owner'] == $CURUSER['id'] AND sgpermission ('canmanagegroup')) OR (sgpermission ('canmanagegroup') AND $is_mod)))
      {
        $Query = sql_query ('SELECT r.*, u.username, g.namestyle FROM ts_social_group_reports r LEFT JOIN users u ON (r.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE r.groupid = ' . sqlesc ($groupid));
        if (0 < mysql_num_rows ($Query))
        {
          $ShowReports .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
					<tr>
						<td class="thead" colspan="4">
							' . ts_collapse ('shwreports') . '
							' . $lang->ts_social_groups['shwreports'] . '
						</td>
					</tr>
					' . ts_collapse ('shwreports', 2) . '
					<tr>
						<td class="subheader" width="15%">' . $lang->ts_social_groups['reportby'] . '</td>
						<td class="subheader" width="15%" align="center">' . $lang->ts_social_groups['created'] . '</td>
						<td class="subheader" width="45%">' . $lang->ts_social_groups['reason'] . '</td>
						<td class="subheader" width="25%" align="center">' . $lang->ts_social_groups['options'] . '</td>
					</tr>
				';
          while ($Reports = mysql_fetch_assoc ($Query))
          {
            $RLink = '<a href="' . ts_seo ($Reports['userid'], $Reports['username']) . '">' . get_user_color ($Reports['username'], $Reports['namestyle']) . '</a>';
            $RMsg = format_comment ($Reports['report']);
            $RPosted = my_datee ($dateformat, $Reports['dateline']) . ' ' . my_datee ($timeformat, $Reports['dateline']);
            $ShowReports .= '
					<tr>
						<td width="15%">' . $RLink . '</td>
						<td width="15%" align="center">' . $RPosted . '</td>
						<td width="45%">' . $RMsg . '</td>
						<td width="25%" align="center"><input type="button" value="' . $lang->ts_social_groups['showpost'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . $groupid . '&amp;page=' . $Reports['page'] . '#message_' . $Reports['mid'] . '\'); return false;" /> <input type="button" value="' . $lang->ts_social_groups['delete'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=delete_report&amp;rid=' . $Reports['rid'] . '&amp;groupid=' . $groupid . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '\'); return false;" /></td>
					</tr>
					';
          }

          $ShowReports .= '
					</tbody>
				</table>
				<br />';
        }
      }

      include_once INC_PATH . '/readconfig_forumcp.php';
      if (((0 < $CURUSER['postsperpage'] AND is_valid_id ($CURUSER['postsperpage'])) AND $CURUSER['postsperpage'] <= 50))
      {
        $perpage = intval ($CURUSER['postsperpage']);
      }
      else
      {
        $perpage = $f_postsperpage;
      }

      $Query = sql_query ('SELECT mid FROM ts_social_group_messages WHERE groupid = ' . sqlesc ($groupid));
      $TotalMessages = mysql_num_rows ($Query);
      list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $TotalMessages, $_SERVER['SCRIPT_NAME'] . '?do=showgroup&groupid=' . $groupid . '&amp;');
      if (((sgpermission ('canpost') AND $IsGroupMember) OR $is_mod))
      {
        $lang->load ('quick_editor');
        require INC_PATH . '/functions_quick_editor.php';
        require_once INC_PATH . '/class_tsquickbbcodeeditor.php';
        $QuickEditor = new TSQuickBBCodeEditor ();
        $QuickEditor->ImagePath = $BASEURL . '/' . $pic_base_url;
        $QuickEditor->SmiliePath = $BASEURL . '/' . $pic_base_url . 'smilies/';
        $QuickEditor->FormName = 'quickreply';
        $QuickEditor->TextAreaName = 'message';
        $SGMessageForm = '
			' . $QuickEditor->GenerateCSS () . '
			' . $QuickEditor->GenerateJavascript () . '
			' . ($useajax == 'yes' ? '<script type="text/javascript" src="' . $BASEURL . '/scripts/quick_sgm.js"></script>' : '') . '
			<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . $groupid . '" name="quickreply" id="quickreply">
			<input type="hidden" name="groupid" value="' . $groupid . '" />
			<input type="hidden" name="do" value="showgroup" />
			<br />
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						' . ts_collapse ('postmsg') . '
						' . $lang->ts_social_groups['postmsg'] . '
					</td>
				</tr>
				' . ts_collapse ('postmsg', 2) . '
					<tr>
						<td>
							' . $QuickEditor->GenerateBBCode () . '
							<br />
							<textarea name="message" style="width:670px;height:100px;" id="message"></textarea><br />
							<span id="loading-layer" style="display:none;"><img src="' . $BASEURL . '/' . $pic_base_url . 'ajax-loader.gif" border="0" alt="" title="" class="inlineimg"></span>
							' . ($useajax == 'yes' ? '<input type="button" class="button" value="' . $lang->ts_social_groups['postmsg'] . '" name="submitsgm" id="submitsgm" onclick="javascript:TSajaxquicksgm(\'' . $groupid . '\');" />' : '<input type="submit" name="submit" value="' . $lang->ts_social_groups['postmsg'] . '" class="button" />') . '
							<input type="reset" value="' . $lang->ts_social_groups['reset'] . '" class=button />
						</td>
					</tr>
				</tbody>
			</table>
			</form>
			';
      }

      $str .= $ShowReports . $pagertop . '
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td class="thead">
					' . ts_collapse ('messagess') . '
					' . $lang->ts_social_groups['title2'] . ' (' . ts_nf ($TotalMessages) . ')
				</td>
			</tr>
			' . ts_collapse ('messagess', 2) . '
			<tr>
				<td id="PostedQuickMessage" name="PostedQuickMessage" style="display: none;">
				</td>
			</tr>
		';
      $Query = sql_query ('SELECT m.*, sg.owner, u.username, u.avatar, g.namestyle FROM ts_social_group_messages m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) LEFT JOIN users u ON (u.id=m.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE m.groupid = ' . sqlesc ($groupid) . ' ORDER BY m.posted DESC ' . $limit);
      if (0 < mysql_num_rows ($Query))
      {
        while ($Msg = mysql_fetch_assoc ($Query))
        {
          $ManageLinks = '[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=report_msg&amp;mid=' . $Msg['mid'] . '&amp;groupid=' . $Msg['groupid'] . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">' . $lang->ts_social_groups['reportpost'] . '</a>] ';
          if (((($Msg['owner'] == $CURUSER['id'] AND sgpermission ('canmanagegroup')) OR (sgpermission ('canmanagegroup') AND $is_mod)) OR ($Msg['userid'] == $CURUSER['id'] AND sgpermission ('canmanagemsg'))))
          {
            $ManageLinks .= ' [<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=edit_msg&amp;mid=' . $Msg['mid'] . '&amp;groupid=' . $Msg['groupid'] . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">' . $lang->ts_social_groups['editpost'] . '</a>] [<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=delete_msg&amp;mid=' . $Msg['mid'] . '&amp;groupid=' . $Msg['groupid'] . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">' . $lang->ts_social_groups['delpost'] . '</a>]';
          }

          $ULink = '<a href="' . ts_seo ($Msg['userid'], $Msg['username']) . '">' . get_user_color ($Msg['username'], $Msg['namestyle']) . '</a>';
          $UAvatar = get_user_avatar ($Msg['avatar'], true, '80', '80');
          $UMsg = format_comment ($Msg['message']);
          $Posted = my_datee ($dateformat, $Msg['posted']) . ' ' . my_datee ($timeformat, $Msg['posted']);
          $str .= '
				<tr>
					<td valign="top">
						<table width="100%" cellpadding="1" cellspacing="0" border="0">
							<tr>
								<th rowspan="2" class="none" width="80" height="80" valign="top">
									' . $UAvatar . '
								</th>
								<td class="none" valign="top">
									<div class="subheader"><span style="float: right;">' . $ManageLinks . '</span>' . sprintf ($lang->ts_social_groups['by2'], $Posted, $ULink) . '</div>
								</td>
							</tr>
							<tr>
								<td class="none" valign="top">
									<div id="message_' . $Msg['mid'] . '" name="message_' . $Msg['mid'] . '">
										' . $UMsg . '
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				';
        }
      }
      else
      {
        $str .= '<tr><td id="NoMessageYet" name="NoMessageYet">' . $lang->ts_social_groups['nomsg'] . '</td></tr>';
      }

      $str .= '
			</tbody>
		</table>
		' . $pagerbottom . $SGMessageForm;
      stdhead ($lang->ts_social_groups['head2'], true, 'collapse');
      show_sg_errors ();
      echo $str;
      stdfoot ();
      exit ();
    }
    else
    {
      $errors[] = $lang->ts_social_groups['invalid'];
    }
  }

  ($Query = sql_query ('SELECT m.*, sg.name, sg.description, sg.owner FROM ts_social_group_members m LEFT JOIN ts_social_groups sg ON (m.groupid=sg.groupid) WHERE m.userid = \'' . $CURUSER['id'] . '\' AND m.type = \'public\' ORDER BY m.joined DESC, sg.name ASC') OR sqlerr (__FILE__, 1163));
  if (0 < mysql_num_rows ($Query))
  {
    $str .= '
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td colspan="2" class="thead">
				' . ts_collapse ('groupsM') . '
				' . $lang->ts_social_groups['in'] . '
			</td>
		</tr>
		<tr>
			<td class="subheader" width="75%" align="left">
				' . $lang->ts_social_groups['name'] . '
			</td>
			<td class="subheader" width="25%" align="left">
				' . $lang->ts_social_groups['joined'] . '
			</td>
		</tr>
		' . ts_collapse ('groupsM', 2) . '
	';
    while ($M = mysql_fetch_assoc ($Query))
    {
      $LeaveButton = (($M['owner'] != $CURUSER['id'] AND $M['type'] == 'public') ? '<span style="float: right;">[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=leave&amp;groupid=' . $M['groupid'] . '"><b>' . $lang->ts_social_groups['leave'] . '</b></a>]</span>' : '');
      $Name = $LeaveButton . '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . $M['groupid'] . '"><strong>' . htmlspecialchars_uni ($M['name']) . '</strong></a><br />' . htmlspecialchars_uni ($M['description']);
      $Joined = my_datee ($dateformat, $M['joined']) . ' ' . my_datee ($timeformat, $M['joined']);
      $str .= '
		<tr>
			<td width="75%" align="left">' . $Name . '</td>
			<td width="25%" align="left">' . $Joined . '</td>
		</tr>
		';
    }

    $str .= '
		</tbody>
	</table>
	<br />
	';
  }

  $MemberOf = array ();
  $Query = sql_query ('SELECT groupid FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Mof = mysql_fetch_assoc ($Query))
    {
      $Memberof[$Mof['groupid']] = '1';
    }
  }

  $CreateGroupButton = ((sgpermission ('cancreate') AND sgpermission ('canjoin')) ? '<p style="float: right;"><input type="button" value="' . $lang->ts_social_groups['create'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=create\'); return false;" /></p>' : '');
  ($Query = sql_query ('SELECT sg.*, u.username, g.namestyle FROM ts_social_groups sg LEFT JOIN users u ON (sg.lastposter = u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY sg.name') OR sqlerr (__FILE__, 1214));
  stdhead ($lang->ts_social_groups['head'], true, 'collapse');
  show_sg_errors ();
  $str .= '
<script type="text/javascript">
	function ConfirmDeletion(GroupID)
	{
		var Delete = confirm("' . $lang->ts_social_groups['sure'] . '");
		if (Delete)
		{
			jumpto("' . $_SERVER['SCRIPT_NAME'] . '?do=delete&groupid="+GroupID);
		}
		else
		{
			return false;
		}
	}
</script>
' . $CreateGroupButton . '
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td colspan="6" class="thead">
			' . ts_collapse ('showgroupsss') . '
			' . $lang->ts_social_groups['groups'] . '
		</td>
	</tr>
	<tr>
		<td class="subheader" width="45%" align="left">
			' . $lang->ts_social_groups['name'] . '
		</td>
		<td class="subheader" width="15%" align="left">
			' . $lang->ts_social_groups['created'] . '
		</td>
		<td class="subheader" width="5%" align="center">
			' . $lang->ts_social_groups['members'] . '
		</td>
		<td class="subheader" width="5%" align="center">
			' . $lang->ts_social_groups['messages'] . '
		</td>
		<td class="subheader" width="15%" align="left">
			' . $lang->ts_social_groups['lastpost'] . '
		</td>
		<td class="subheader" width="15%" align="center">
			' . $lang->ts_social_groups['options'] . '
		</td>
	</tr>
	' . ts_collapse ('showgroupsss', 2);
  if (0 < mysql_num_rows ($Query))
  {
    $Images = array ('public' => $BASEURL . '/' . $pic_base_url . 'public.gif', 'inviteonly' => $BASEURL . '/' . $pic_base_url . 'private.gif');
    while ($SG = mysql_fetch_assoc ($Query))
    {
      $JoinButton = (((($SG['type'] == 'public' AND sgpermission ('canjoin')) AND $SG['owner'] != $CURUSER['id']) AND !isset ($Memberof[$SG['groupid']])) ? '<input type="button" value="' . $lang->ts_social_groups['join'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=join&amp;groupid=' . $SG['groupid'] . '\'); return false;" />' : ((($SG['owner'] != $CURUSER['id'] AND isset ($Memberof[$SG['groupid']])) AND $SG['type'] == 'public') ? '<input type="button" value="' . $lang->ts_social_groups['leave'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=leave&amp;groupid=' . $SG['groupid'] . '\'); return false;" />' : ''));
      $DeleteButton = (($is_mod OR (sgpermission ('candelete') AND $SG['owner'] == $CURUSER['id'])) ? '<input type="button" value="' . $lang->ts_social_groups['delete'] . '" onclick="ConfirmDeletion(' . $SG['groupid'] . ');" />' : '');
      $EditButton = (($is_mod OR (sgpermission ('canedit') AND $SG['owner'] == $CURUSER['id'])) ? '<input type="button" value="' . $lang->ts_social_groups['edit'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?do=edit&amp;groupid=' . $SG['groupid'] . '\'); return false;" />' : '');
      $Name = '<span style="float: right;"><img src="' . $Images[$SG['type']] . '" border="0" alt="' . $SG['type'] . '" title="' . $SG['type'] . '" /></span> <a href="' . $_SERVER['SCRIPT_NAME'] . '?do=showgroup&amp;groupid=' . $SG['groupid'] . '"><strong>' . htmlspecialchars_uni ($SG['name']) . '</strong></a><br />' . cutename ($SG['description'], 100);
      $Created = my_datee ($dateformat, $SG['dateline']) . ' ' . my_datee ($timeformat, $SG['dateline']);
      $Members = ts_nf ($SG['members']);
      $Messages = ts_nf ($SG['messages']);
      if (($SG['lastpostdate'] != '0' AND $SG['lastposter'] != '0'))
      {
        $Lastpost = '<div style="text-align: left;">' . my_datee ($dateformat, $SG['lastpostdate']) . ' ' . my_datee ($timeformat, $SG['lastpostdate']) . '</div><div style="text-align: right;">' . sprintf ($lang->ts_social_groups['by'], '<a href="' . ts_seo ($SG['lastposter'], $SG['username']) . '">' . get_user_color ($SG['username'], $SG['namestyle']) . '</a>') . '</div>';
      }
      else
      {
        $Lastpost = $lang->ts_social_groups['never'];
      }

      $str .= '
		<tr>
			<td width="45%" align="left">' . $Name . '</td>
			<td width="15%" align="left">' . $Created . '</td>
			<td width="5%" align="center">' . $Members . '</td>
			<td width="5%" align="center">' . $Messages . '</td>
			<td width="15%" align="left">' . $Lastpost . '</td>
			<td width="15%" align="center">' . $JoinButton . ' ' . $EditButton . ' ' . $DeleteButton . '</td>
		</tr>
		';
    }
  }
  else
  {
    $str .= '
	<tr>
		<td colspan="6">
			' . $lang->ts_social_groups['nogroup'] . '
		</td>
	</tr>';
  }

  $str .= '</tbody></table>';
  echo $str;
  stdfoot ();
?>
