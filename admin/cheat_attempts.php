<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_image ($text, $size = 300)
  {
    $content = 'onmouseover="ddrivetip(\'' . $text . '\', ' . $size . ')"; onmouseout="hideddrivetip()"';
    return $content;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('CA_VERSION', '0.6 by xam');
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

  if ($_POST['do'] == 'apply')
  {
    if (is_array ($_POST['ban']))
    {
      $modcomment = gmdate ('Y-m-d') . ' - Banned by ' . $CURUSER['username'] . ' (Cheat Attempt)' . $eol;
      (sql_query ('UPDATE users SET enabled = \'no\', passkey=\'\', modcomment=CONCAT(' . sqlesc ($modcomment . '') . ', modcomment) WHERE id IN (' . implode (', ', $_POST['ban']) . ')') OR sqlerr (__FILE__, 37));
      $ca_message = 'Users has been banned';
    }

    if (is_array ($_POST['warn']))
    {
      $warneduntil = get_date_time (gmtime () + 1 * 604800);
      $lastwarned = sqlesc (get_date_time ());
      $query = '' . 'warned = \'yes\', timeswarned = timeswarned + 1, lastwarned = ' . $lastwarned . ', warnedby = ' . $CURUSER['id'] . ', warneduntil = ' . sqlesc ($warneduntil);
      $modcomment = gmdate ('Y-m-d') . ' - Warned by ' . $CURUSER['username'] . ' (Cheat Attempt)' . $eol;
      (sql_query ('' . 'UPDATE users SET ' . $query . ', modcomment=CONCAT(' . sqlesc ($modcomment . '') . ', modcomment) WHERE id IN (' . implode (', ', $_POST['warn']) . ')') OR sqlerr (__FILE__, 46));
      ($res = sql_query ('SELECT id FROM users WHERE id IN (' . implode (', ', $_POST['warn']) . ')') OR sqlerr (__FILE__, 47));
      require_once INC_PATH . '/functions_pm.php';
      while ($arr = mysql_fetch_assoc ($res))
      {
        send_pm ($arr['id'], 'You have been warned for 1 week because of Possible Cheat Attempt!', 'You have been warned!');
      }

      $ca_message = 'Users has been warned';
    }

    if (is_array ($_POST['delete']))
    {
      (sql_query ('DELETE FROM cheat_attempts WHERE id IN (' . implode (', ', $_POST['delete']) . ')') OR sqlerr (__FILE__, 57));
      $ca_message = 'Cheat Attempts has been deleted!';
    }
  }

  $res = sql_query ('SELECT COUNT(*) FROM cheat_attempts');
  $row = mysql_fetch_row ($res);
  $count = $row[0];
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&');
  stdhead ('Cheat Attemps');
  echo $pagertop;
  _form_header_open_ ('Cheat Attempts' . (isset ($ca_message) ? ' -> <font color="red"> ' . $ca_message : ''), 12);
  $hidden_values = '<input type="hidden" name="do" value="apply">';
  _form_open_ ('', $hidden_values);
  echo '
	<tr align=center>	
	<td class="subheader" align="center">User</td>
	<td class="subheader" align="center">Added</td>
	<td class="subheader" align="center">Torrent</td>
	<td class="subheader" align="center">Agent</td>
	<td class="subheader" align="center">Upload Speed</td>
	<td class="subheader" align="center">Uploaded</td>
	<td class="subheader" align="center">Within</td>
	<td class="subheader" align="center">IP</td>
	<td class="subheader" align="center">Ban</td>
	<td class="subheader" align="center">Warn</td>
	<td class="subheader" align="center">Del</td>
	</tr>';
  ($res = sql_query ('' . 'SELECT c.*, u.id as userid, u.username, u.usergroup, u.uploaded, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, t.name, g.namestyle FROM cheat_attempts c LEFT JOIN users u ON (c.uid=u.id) LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN torrents t ON (c.torrentid=t.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY c.added DESC ' . $limit) OR sqlerr (__FILE__, 89));
  require_once INC_PATH . '/functions_mkprettytime.php';
  while ($arr = mysql_fetch_assoc ($res))
  {
    $uppd = mksize ($arr['upthis']);
    echo '
	<tr>	
	<td align="center"><a href="' . $BASEURL . '/userdetails.php?id=' . intval ($arr['userid']) . '">' . get_user_color (htmlspecialchars_uni ($arr['username']), $arr['namestyle']) . '</a> <span style="white-space: nowrap;">' . get_user_icons ($arr) . '</span></td>
	<td align="center">' . my_datee ($dateformat, $arr['added']) . '<br />' . my_datee ($timeformat, $arr['added']) . '</td>
	<td align="center"><a href="' . $BASEURL . '/details.php?id=' . intval ($arr['torrentid']) . '" ' . show_image (htmlspecialchars_uni ($arr['name']), strlen ($arr['name']) * 8) . '>' . intval ($arr['torrentid']) . '</a></td>
	<td align="center">' . htmlspecialchars_uni ($arr['agent']) . '</td>
	<td align="center">' . mksize ($arr['transfer_rate']) . '/s</td>
	<td align="center">' . $uppd . '</td>
	<td align="center">' . mkprettytime ($arr['timediff']) . '</td>
	<td align="center">' . htmlspecialchars_uni ($arr['ip']) . '</td>
	<td align="center"><input type="checkbox" name="ban[]" value="' . intval ($arr['userid']) . '"></td>
	<td align="center"><input type="checkbox" name="warn[]" value="' . intval ($arr['userid']) . '"></td>
	<td align="center"><input type="checkbox" name="delete[]" value="' . intval ($arr['id']) . '"></td>
	</tr>';
  }

  echo '
	<tr>
	<td colspan="12" align="right">
	<input class=button type="button" value="check all -ban-" onclick="this.value=check(this.form.elements[\'ban[]\'])"/> 
	<input class=button type="button" value="check all -warn-" onclick="this.value=check(this.form.elements[\'warn[]\'])"/>
	<input class=button type="button" value="check all -delete-" onclick="this.value=check(this.form.elements[\'delete[]\'])"/> 
	<input type="hidden" name="nowarned" value="nowarned">
	<input type="submit" name="submit" value="Apply Changes" class=button>
	</td>
	</tr>
	</table></form>
	';
  echo $pagertop;
  _form_header_close_ ();
  stdfoot ();
?>
