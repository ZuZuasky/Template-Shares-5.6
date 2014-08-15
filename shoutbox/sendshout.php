<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function execcommand_status ($Data)
  {
    global $dateformat;
    global $timeformat;
    global $lang;
    global $rootpath;
    global $is_mod;
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      ($query = mysql_query ('SELECT u.added, u.options, u.avatar, u.id, u.last_access, u.uploaded, u.downloaded, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.username=' . sqlesc ($Data)) OR sqlerr (__FILE__, 138));
      if (0 < mysql_num_rows ($query))
      {
        $lang->load ('tsf_forums');
        include_once INC_PATH . '/functions_icons.php';
        include_once INC_PATH . '/functions_ratio.php';
        $user = mysql_fetch_assoc ($query);
        $lastseen = my_datee ($dateformat, $user['last_access']) . ' ' . my_datee ($timeformat, $user['last_access']);
        $downloaded = mksize ($user['downloaded']);
        $uploaded = mksize ($user['uploaded']);
        $ratio = get_user_ratio ($user['uploaded'], $user['downloaded']);
        $ratio = str_replace ('\'', '\\\'', $ratio);
        $tooltip = '<b>' . $lang->tsf_forums['jdate'] . '</b>' . my_datee ($dateformat, $user['added']) . '<br />' . sprintf ($lang->tsf_forums['tooltip'], $lastseen, $downloaded, $uploaded, $ratio);
        echo '
				<table width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td width="3%">
							' . get_user_avatar ($user['avatar']) . '
						</td>
						<td align="left">
							' . (((preg_match ('#I3#is', $user['options']) OR preg_match ('#I4#is', $user['options'])) AND !$is_mod) ? $lang->tsf_forums['deny'] : '<div class="subheader">Userdetails <a href="' . ts_seo ($user['id'], $Data) . '" target="_blank"><b>' . get_user_color ($Data, $user['namestyle']) . '</b></a> ' . get_user_icons ($user) . '</div>' . $tooltip . '') . '			
						</td>
					</tr>
				</table>';
        return null;
      }

      echo '<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">There is no user with this name!</div>';
    }

  }

  function execcommand_unwarn ($Data)
  {
    global $lang;
    global $CURUSER;
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      ($query = mysql_query ('SELECT id, modcomment FROM users WHERE username=' . sqlesc ($Data)) OR sqlerr (__FILE__, 176));
      if (($query AND 0 < mysql_num_rows ($query)))
      {
        $userid = mysql_result ($query, 0, 'id');
        $modcomment = mysql_result ($query, 0, 'modcomment');
        $modcomment = sprintf ($lang->global['modcommentwarningremovedby'], gmdate ('Y-m-d'), $CURUSER['username'], $modcomment);
        (mysql_query ('UPDATE users SET modcomment = ' . sqlesc ($modcomment) . ', warneduntil = \'0000-00-00 00:00:00\', warned = \'no\', timeswarned = timeswarned - 1 WHERE id=' . sqlesc ($userid)) OR sqlerr (__FILE__, 182));
        require_once INC_PATH . '/functions_pm.php';
        send_pm ($userid, sprintf ($lang->global['warningremovedbymessage'], $CURUSER['username']), $lang->global['warningremovedbysubject']);
        execcommand_message ();
      }
    }

    return true;
  }

  function execcommand_warn ($Data)
  {
    global $lang;
    global $CURUSER;
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      ($query = mysql_query ('SELECT id, modcomment FROM users WHERE username=' . sqlesc ($Data)) OR sqlerr (__FILE__, 198));
      if (($query AND 0 < mysql_num_rows ($query)))
      {
        $userid = mysql_result ($query, 0, 'id');
        $modcomment = mysql_result ($query, 0, 'modcomment');
        $warneduntil = get_date_time (gmtime () + 1 * 604800);
        $dur = sprintf ($lang->global['warningweeks'], 1);
        $modcomment = sprintf ($lang->global['modcommentwarning2'], gmdate ('Y-m-d'), $dur, $CURUSER['username'], 'Warned in Shoutbox', $modcomment);
        $lastwarned = get_date_time ();
        (mysql_query ('UPDATE users SET modcomment = ' . sqlesc ($modcomment) . ', warneduntil = ' . sqlesc ($warneduntil) . ', warned = \'yes\', timeswarned = timeswarned + 1, lastwarned = ' . sqlesc ($lastwarned) . ', warnedby = ' . sqlesc ($CURUSER['id']) . ' WHERE id=' . sqlesc ($userid)) OR sqlerr (__FILE__, 207));
        require_once INC_PATH . '/functions_pm.php';
        send_pm ($userid, sprintf ($lang->global['warningmessage2'], $dur, $CURUSER['username'], 'ShoutBox!'), $lang->global['warningsubject']);
        execcommand_message ();
      }
    }

    return true;
  }

  function execcommand_ban ($Data)
  {
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      $query = mysql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($Data));
      if (0 < mysql_num_rows ($query))
      {
        $Userid = mysql_result ($query, 0, 'id');
        $query = mysql_query ('SELECT userid FROM ts_u_perm WHERE userid = ' . sqlesc ($Userid));
        if (0 < mysql_num_rows ($query))
        {
          mysql_query ('UPDATE ts_u_perm SET canshout = 0 WHERE userid = ' . sqlesc ($Userid));
        }
        else
        {
          mysql_query ('INSERT INTO ts_u_perm (userid, canshout) VALUES (' . sqlesc ($Userid) . ', 0)');
        }

        execcommand_message ('<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">User ' . htmlspecialchars_uni ($Data) . ' has been banned from shoutbox!</div>', true);
      }
    }

    return true;
  }

  function execcommand_unban ($Data)
  {
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      $query = mysql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($Data));
      if (0 < mysql_num_rows ($query))
      {
        $Userid = mysql_result ($query, 0, 'id');
        $query = mysql_query ('SELECT userid FROM ts_u_perm WHERE canshout = 0 AND userid = ' . sqlesc ($Userid));
        if (0 < mysql_num_rows ($query))
        {
          mysql_query ('UPDATE ts_u_perm SET canshout = 1 WHERE userid = ' . sqlesc ($Userid));
        }

        execcommand_message ('<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">User ' . htmlspecialchars_uni ($Data) . ' has been unbanned from shoutbox!</div>', true);
      }
    }

    return true;
  }

  function execcommand_pruneshout ($Data)
  {
    $Data = trim ($Data[0][1]);
    if (!empty ($Data))
    {
      (mysql_query ('delete from shoutbox where content = ' . sqlesc ($Data)) OR sqlerr (__FILE__, 271));
      execcommand_message ();
    }

    return true;
  }

  function execcommand_prune ($Data)
  {
    $Data = trim ($Data[0][1]);
    if (empty ($Data))
    {
      (mysql_query ('delete from shoutbox') OR sqlerr (__FILE__, 284));
      execcommand_message ();
    }
    else
    {
      $query = mysql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($Data));
      if (0 < mysql_num_rows ($query))
      {
        $Userid = mysql_result ($query, 0, 'id');
        (mysql_query ('delete from shoutbox where userid = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 293));
        execcommand_message ();
      }
    }

    return true;
  }

  function execcommand_message ($message = '<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">Your command has been executed. (Results may be shown in next refresh!)</div>', $forcemessage = false)
  {
    if ((mysql_affected_rows () OR $forcemessage))
    {
      echo $message;
    }

  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  $rootpath = './../';
  define ('AS_VERSION', '2.2 by xam');
  require_once $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  dbconn ();
  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
  $msg = '';
  $is_mod = is_mod ($usergroups);
  if (((!$CURUSER OR empty ($_POST['message'])) OR $usergroups['canshout'] != 'yes'))
  {
    exit ('<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">' . $lang->global['shouterror'] . '</div>');
    return 1;
  }

  $query = mysql_query ('SELECT canshout FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id']));
  if (0 < mysql_num_rows ($query))
  {
    $shoutperm = mysql_fetch_assoc ($query);
    if ($shoutperm['canshout'] == '0')
    {
      $msg = $lang->global['shouterror'];
    }
  }

  if (((!$is_mod AND $usergroups['floodlimit'] != '0') AND empty ($msg)))
  {
    $query = mysql_query ('SELECT date FROM shoutbox WHERE userid = ' . sqlesc ($CURUSER['id']) . ' ORDER by date DESC LIMIT 1');
    if (0 < mysql_num_rows ($query))
    {
      $last_shout = mysql_result ($query, 0, 'date');
    }

    $lang->load ('shoutbox');
    $msg = flood_check ($lang->shoutbox['floodcomment'], $last_shout, true);
  }

  if ($msg != '')
  {
    echo '<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">' . $msg . '</div>';
    return 1;
  }

  $Command = urldecode ($_POST['message']);
  if ((preg_match_all ('' . '#^/pruneshout(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_pruneshout ($Matches);
  }

  if ((preg_match_all ('' . '#^/prune(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_prune ($Matches);
  }

  if ((preg_match_all ('' . '#^/ban(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_ban ($Matches);
  }

  if ((preg_match_all ('' . '#^/unban(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_unban ($Matches);
  }

  if ((preg_match_all ('' . '#^/warn(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_warn ($Matches);
  }

  if ((preg_match_all ('' . '#^/unwarn(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
  {
    return execcommand_unwarn ($Matches);
  }

  if (preg_match_all ('' . '#^/status(.*)$#', $Command, $Matches, PREG_SET_ORDER))
  {
    if ($usergroups['canviewotherprofile'] == 'yes')
    {
      return execcommand_status ($Matches);
    }
  }
  else
  {
    $temp_msg = $Command;
    if ((preg_match_all ('' . '#^/notice(.*)$#', $Command, $Matches, PREG_SET_ORDER) AND $is_mod))
    {
      $Command = '{systemnotice}' . trim ($Matches[0][1]);
    }

    $msg = strval ($Command);
    if (strtolower ($shoutboxcharset) != 'utf-8')
    {
      if (function_exists ('iconv'))
      {
        $msg = iconv ('UTF-8', $shoutboxcharset, $msg);
      }
      else
      {
        if (function_exists ('mb_convert_encoding'))
        {
          $msg = mb_convert_encoding ($msg, $shoutboxcharset, 'UTF-8');
        }
        else
        {
          if (strtolower ($shoutboxcharset) == 'iso-8859-1')
          {
            $msg = utf8_decode ($msg);
          }
        }
      }
    }

    if ((!empty ($msg) AND strpos ($temp_msg, 'systemnotice') === false))
    {
      mysql_query ('INSERT INTO shoutbox (userid, date, content) VALUES (' . sqlesc ($CURUSER['id']) . ', \'' . TIMENOW . '\', ' . sqlesc ($msg) . ')');
    }
  }

?>
