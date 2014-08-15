<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  include_once INC_PATH . '/functions_ratio.php';
  define ('I_CHECK_VERSION', '0.7 by xam');
  stdhead ('Duplicate IP users', true, 'collapse');
  if (($_GET['action'] == 'ban' AND !empty ($_POST['userid'])))
  {
    (sql_query ('UPDATE users SET enabled = \'no\' WHERE id IN (' . implode (', ', $_POST['userid']) . ')') OR sqlerr (__FILE__, 24));
  }

  print '<table cellpadding=5 align=center border=0 width=100%>';
  print '<tr><td colspan="10" align="left" class="colhead">' . ts_collapse ('ipcheck') . ($_GET['action'] == 'ban' ? '<font color=red><strong>Total ' . mysql_affected_rows () . ' user(s) has been banned!</strong></font>' : 'Duplicate IP users (by IP)') . '</td></tr>' . ts_collapse ('ipcheck', 2);
  ($res = sql_query ('SELECT count(*) AS dupl, ip, usergroup FROM users WHERE enabled = \'yes\' AND ip <> \'\' AND ip <> \'127.0.0.0\' GROUP BY ip ORDER BY dupl DESC, ip') OR sqlerr (__FILE__, 28));
  print '<tr align=center>
  <td class=subheader align=left>Username</td>
 <td class=subheader align=left>Email</td>
 <td class=subheader>Registered</td>
 <td class=subheader>Last Access</td>
 <td class=subheader>Down</td>
 <td class=subheader>Up</td>
 <td class=subheader>Ratio</td>
 <td class=subheader>Ip Address</td>
 <td class=subheader>Peer</td>
 <td class=subheader>Ban</td></tr>
';
  echo '<form method="post" action="' . $_this_script_ . '&action=ban">';
  $uc = 0;
  while ($ras = mysql_fetch_array ($res))
  {
    if ($ras['dupl'] <= 1)
    {
      continue;
    }

    if ($ras['usergroup'] == UC_STAFFLEADER)
    {
      continue;
    }

    if ($ip != $ras['ip'])
    {
      ($ros = sql_query ('
	  SELECT 
	  u.*,
	  p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout,
	  g.cansettingspanel, g.namestyle,
	  pp.ip as peerip
	  FROM users u
	  LEFT JOIN ts_u_perm p ON (u.id=p.userid) 
	  LEFT JOIN usergroups g ON (u.usergroup=g.gid)
	  LEFT JOIN peers pp ON (u.ip=pp.ip&&u.id=pp.userid)
	  WHERE u.ip = ' . sqlesc ($ras['ip']) . '
	  GROUP BY u.username
	  ') OR sqlerr (__FILE__, 65));
      $num2 = mysql_num_rows ($ros);
      if (1 < $num2)
      {
        ++$uc;
        while ($arr = mysql_fetch_array ($ros))
        {
          if ($arr['cansettingspanel'] == 'yes')
          {
            continue;
          }

          if ($arr['added'] == '0000-00-00 00:00:00')
          {
            $arr['added'] = '-';
          }

          if ($arr['last_access'] == '0000-00-00 00:00:00')
          {
            $arr['last_access'] = '-';
          }

          if ($arr['downloaded'] != 0)
          {
            $ratio = number_format ($arr['uploaded'] / $arr['downloaded'], 2);
          }
          else
          {
            $ratio = '---';
          }

          $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
          $uploaded = mksize ($arr['uploaded']);
          $downloaded = mksize ($arr['downloaded']);
          $added = my_datee ($dateformat, $arr['added']) . '<br />' . my_datee ($timeformat, $arr['added']);
          $last_access = my_datee ($dateformat, $arr['last_access']) . '<br />' . my_datee ($timeformat, $arr['last_access']);
          if ($uc % 2 == 0)
          {
            $utc = '';
          }
          else
          {
            $utc = ' bgcolor="ECE9D8"';
          }

          print '' . '<tr' . $utc . '><td align=left><b><a href=\'' . $BASEURL . '/userdetails.php?id=' . $arr['id'] . '\'>' . get_user_color ($arr['username'], $arr['namestyle']) . '</b></a>' . get_user_icons ($arr) . ('' . '</td>
				  <td align=left>' . $arr['email'] . '</td>
				  <td align=center>' . $added . '<br /></td>
				  <td align=center>' . $last_access . '<br /></td>
				  <td align=center>' . $downloaded . '</td>
				  <td align=center>' . $uploaded . '</td>
				  <td align=center>' . $ratio . '</td>
				  <td align=center><a href="' . $BASEURL . '/redirector.php?url=http://www.whois.sc/' . $arr['ip'] . '" target="_blank">' . $arr['ip'] . '</a></td>
<td align=center>') . (!empty ($arr['peerip']) ? '<font color=green>YES</font>' : '<font color=red>NO</font>') . '</td>
				  <td align=center><input type=checkbox name=userid[] value=' . (int)$arr['id'] . '></td></tr>
';
          $ip = $arr['ip'];
        }

        continue;
      }

      echo '<tr><td colspan=10 align=left>Nothing Found!</td></tr>';
      continue;
    }
  }

  echo '<tr><td colspan=10 align=right><input type=submit value="ban selected" class=button> <input type="button" value="check all" class=button onClick="this.value=check(form)"></td></tr>';
  print '</form></table><br />';
  print '<table cellpadding=5 align=center border=0 width=100%>';
  print '<tr><td colspan="10" align="left" class="colhead">' . ts_collapse ('ipcheck2') . ($_GET['action'] == 'ban' ? '<font color=red><strong>Total ' . mysql_affected_rows () . ' user(s) has been banned!</strong></font>' : 'Duplicate IP users (by Password)') . '</td></tr>' . ts_collapse ('ipcheck2', 2);
  print '<tr align=center>
<td class=subheader align=left>Username</td>
 <td class=subheader align=left>Email</td>
 <td class=subheader>Registered</td>
 <td class=subheader>Last Access</td>
 <td class=subheader>Down</td>
 <td class=subheader>Up</td>
 <td class=subheader>Ratio</td>
 <td class=subheader>Ip Address</td>
 <td class=subheader>Peer</td>
 <td class=subheader>Ban</td></tr>
';
  echo '<form method="post" action="' . $_this_script_ . '&action=ban">';
  ($res = sql_query ('SELECT count(*) AS dupl, passhash, usergroup FROM users WHERE 1=1 GROUP BY passhash ORDER BY dupl DESC, passhash') OR sqlerr (__FILE__, 127));
  $uc = 0;
  while ($ras = mysql_fetch_array ($res))
  {
    if ($ras['dupl'] <= 1)
    {
      continue;
    }

    if ($ras['usergroup'] == UC_STAFFLEADER)
    {
      continue;
    }

    if ($passhash != $ras['passhash'])
    {
      ($ros = sql_query ('SELECT  u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.cansettingspanel, g.namestyle, pp.ip as peerip FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid)  LEFT JOIN peers pp ON (u.ip=pp.ip AND u.id=pp.userid) WHERE u.passhash=\'' . $ras['passhash'] . '\' GROUP BY u.username ORDER BY u.id DESC') OR sqlerr (__FILE__, 139));
      $num2 = mysql_num_rows ($ros);
      if (1 < $num2)
      {
        ++$uc;
        while ($arr = mysql_fetch_array ($ros))
        {
          if ($arr['cansettingspanel'] == 'yes')
          {
            continue;
          }

          if ($arr['added'] == '0000-00-00 00:00:00')
          {
            $arr['added'] = '-';
          }

          if ($arr['last_access'] == '0000-00-00 00:00:00')
          {
            $arr['last_access'] = '-';
          }

          if ($arr['downloaded'] != 0)
          {
            $ratio = number_format ($arr['uploaded'] / $arr['downloaded'], 2);
          }
          else
          {
            $ratio = '---';
          }

          $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
          $uploaded = mksize ($arr['uploaded']);
          $downloaded = mksize ($arr['downloaded']);
          $added = my_datee ($dateformat, $arr['added']) . '<br />' . my_datee ($timeformat, $arr['added']);
          $last_access = my_datee ($dateformat, $arr['last_access']) . '<br />' . my_datee ($timeformat, $arr['last_access']);
          if ($uc % 2 == 0)
          {
            $utc = '';
          }
          else
          {
            $utc = ' bgcolor="ECE9D8"';
          }

          print '' . '<tr' . $utc . '><td align=left><b><a href=\'' . $BASEURL . '/userdetails.php?id=' . $arr['id'] . '\'>' . get_user_color ($arr['username'], $arr['namestyle']) . '</b></a>' . get_user_icons ($arr) . '<br />' . $arr['passhash'] . ('' . '</td>
				  <td align=left>' . $arr['email'] . '</td>
				  <td align=center>' . $added . '<br /></td>
				  <td align=center>' . $last_access . '<br /></td>
				  <td align=center>' . $downloaded . '</td>
				  <td align=center>' . $uploaded . '</td>
				  <td align=center>' . $ratio . '</td>
				  <td align=center><a href="' . $BASEURL . '/redirector.php?url=http://www.whois.sc/' . $arr['ip'] . '" target="_blank">' . $arr['ip'] . '</a></td>
<td align=center>') . (!empty ($arr['peerip']) ? '<font color=green>YES</font>' : '<font color=red>NO</font>') . '</td>
				  <td align=center><input type=checkbox name=userid[] value=' . (int)$arr['id'] . '></td></tr>
';
          $passhash = $arr['passhash'];
        }

        continue;
      }

      echo '<tr><td colspan=10 align=left>Nothing Found!</td></tr>';
      continue;
    }
  }

  echo '<tr><td colspan=10 align=right><input type=submit value="ban selected" class=button> <input type="button" value="check all" class=button onClick="this.value=check(form)"></td></tr>';
  print '</form></table>';
  stdfoot ();
?>
