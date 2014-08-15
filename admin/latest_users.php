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

  define ('LU_VERSION', '0.2 by xam');
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : '');
  $accountid = ((isset ($_GET['userid']) AND is_valid_id ($_GET['userid'])) ? intval ($_GET['userid']) : '');
  if (!empty ($accountid))
  {
    if ($do == 'delete')
    {
      sql_query ('DELETE FROM users WHERE id = ' . sqlesc ($accountid) . ' LIMIT 1');
      if (0 < mysql_affected_rows ())
      {
        require INC_PATH . '/function_log_user_deletion.php';
        log_user_deletion ('Following user has been deleted by ' . $CURUSER['username'] . ' (latest_users tool - Staff Panel): Userid: ' . $accountid);
        redirect ($_this_script_, 'Account has been deleted!');
      }
    }
  }

  $stime = time () - 60 * 60 * (24 * 7);
  $query = sql_query ('' . 'SELECT COUNT(*) as total FROM users WHERE status = \'confirmed\' AND UNIX_TIMESTAMP(added) > \'' . $stime . '\' AND enabled=\'yes\'');
  $count = mysql_result ($query, 0, 'total');
  list ($pagertop, $pagerbottom, $limit) = pager (10, $count, $_this_script_ . '&');
  stdhead ('Latest Registered User(s)');
  ($query = sql_query ('' . 'SELECT u.id, u.username, u.email, u.ip, u.added, u.country, g.namestyle, c.name, c.flagpic FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN countries c ON (u.country=c.id) WHERE u.status = \'confirmed\' AND UNIX_TIMESTAMP(u.added) > \'' . $stime . '\' AND u.enabled=\'yes\' ORDER by u.added DESC ' . $limit) OR sqlerr (__FILE__, 47));
  echo $pagertop;
  _form_header_open_ ('Latest Registered User(s)', 7);
  if (mysql_num_rows ($query) == 0)
  {
    echo '
	<tr>
	<td colspan="7"><font color="red">There is no latest user(s)..</font></td>
	</tr>';
  }
  else
  {
    echo '
	<tr>
	<td class="subheader" align="center" width="5%">Userid</td>
	<td class="subheader" align="center" width="19%">Username</td>
	<td class="subheader" align="center" width="18%">Email</td>
	<td class="subheader" align="center" width="15%">IP Address</td>
	<td class="subheader" align="center" width="18%">Registered at</td>
	<td class="subheader" align="center" width="7%">Country</td>
	<td class="subheader" align="center" width="18%">Action</td>
	<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Sending Activation Code...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>
	</tr>';
    while ($u = mysql_fetch_assoc ($query))
    {
      $userid = intval ($u['id']);
      echo '
		<tr' . $bgcolor . '>
		<td align="center"><a href="' . $BASEURL . '/userdetails.php?id=' . $userid . '">' . $userid . '</a></td>
		<td align="center"><a href="' . $BASEURL . '/userdetails.php?id=' . $userid . '"><b>' . get_user_color ($u['namestyle'], $u['username']) . '</b></a></td>
		<td align="center">' . htmlspecialchars_uni ($u['email']) . '</td>
		<td align="center">' . (!empty ($u['ip']) ? htmlspecialchars_uni ($u['ip']) : 'N/A') . '</td>
		<td align="center">' . my_datee ($dateformat, $u['added']) . ' ' . my_datee ($timeformat, $u['added']) . '</td>
		<td align="center"><img src=' . $BASEURL . '/' . $pic_base_url . 'flag/' . $u['flagpic'] . ' alt="' . $u['name'] . '" title="' . $u['name'] . '" style="margin-left: 8pt"></td>
		<td align="center"><a href="' . $_this_script_ . '&do=delete&userid=' . $userid . '" alt="Delete this account" title="Delete this account"><font color="red">Delete</font></a></td>
		</tr>
		';
    }
  }

  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
