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

  define ('U_VERSION', '0.8 by xam');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  $accountid = ((isset ($_GET['userid']) AND is_valid_id ($_GET['userid'])) ? intval ($_GET['userid']) : '');
  if (((strtoupper ($_SERVER['REQUEST_METHOD'] == 'POST') AND $do == 'mass_update') AND 0 < count ($userids = $_POST['userids'])))
  {
    $type = $_POST['type'];
    if ($type == 'delete')
    {
      sql_query ('DELETE FROM users WHERE status = \'pending\' AND id IN (0,' . implode (',', $userids) . ')');
      if (0 < mysql_affected_rows ())
      {
        require INC_PATH . '/function_log_user_deletion.php';
        log_user_deletion ('Following users has been deleted by ' . $CURUSER['username'] . ' (Unco tool - Staff Panel). Userids: ' . implode (',', $userids));
        redirect ($_this_script_, 'Account(s) has been deleted!');
      }
    }
    else
    {
      if ($type == 'confirm')
      {
        sql_query ('UPDATE users SET status = \'confirmed\', last_access = ' . sqlesc (get_date_time ()) . ' WHERE id IN (0,' . implode (',', $userids) . ') AND status=\'pending\'');
        if (0 < mysql_affected_rows ())
        {
          sql_query ('DELETE FROM ts_user_validation WHERE userid IN (0,' . implode (',', $userids) . ')');
          redirect ($_this_script_, 'Account(s) has been confirmed!');
        }
      }
      else
      {
        if ($type == 'resend')
        {
          $query = sql_query ('SELECT u.username, u.email, u.id, e.editsecret FROM users u LEFT JOIN ts_user_validation e ON (u.id=e.userid) WHERE u.id IN (0,' . implode (',', $userids) . ') AND u.status=\'pending\'');
          if (0 < mysql_num_rows ($query))
          {
            $lang->load ('signup');
            while ($user = mysql_fetch_assoc ($query))
            {
              $body = sprintf ($lang->signup['verifiyemailbody'], $user['username'], $BASEURL, $user['id'], md5 ($user['editsecret']), $SITENAME);
              sent_mail ($user['email'], sprintf ($lang->signup['verifiyemailsubject'], $SITENAME), $body, 'signup', false);
            }

            redirect ($_this_script_ . '&resend=' . implode (',', $userids), 'Activation code(s) has been sent.');
          }
        }
      }
    }
  }
  else
  {
    if ((!empty ($accountid) AND !empty ($do)))
    {
      if ($do == 'delete')
      {
        sql_query ('DELETE FROM users WHERE status = \'pending\' AND id = ' . sqlesc ($accountid) . ' LIMIT 1');
        if (0 < mysql_affected_rows ())
        {
          require INC_PATH . '/function_log_user_deletion.php';
          log_user_deletion ('Following user has been deleted by ' . $CURUSER['username'] . ' (Unco tool - Staff Panel). Userid: ' . $accountid);
          redirect ($_this_script_, 'Account has been deleted!');
        }
      }
      else
      {
        if ($do == 'confirm')
        {
          sql_query ('UPDATE users SET status = \'confirmed\', last_access = ' . sqlesc (get_date_time ()) . ' WHERE id=' . sqlesc ($accountid) . ' AND status=\'pending\' LIMIT 1');
          if (0 < mysql_affected_rows ())
          {
            sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($accountid));
            redirect ($_this_script_, 'Account has been confirmed!');
          }
        }
        else
        {
          if ($do == 'resend')
          {
            $query = sql_query ('SELECT u.username, u.email, u.id, e.editsecret FROM users u LEFT JOIN ts_user_validation e ON (u.id=e.userid) WHERE u.id=' . sqlesc ($accountid) . ' AND u.status=\'pending\' LIMIT 1');
            if (0 < mysql_num_rows ($query))
            {
              $lang->load ('signup');
              $user = mysql_fetch_assoc ($query);
              $body = sprintf ($lang->signup['verifiyemailbody'], $user['username'], $BASEURL, $user['id'], md5 ($user['editsecret']), $SITENAME);
              sent_mail ($user['email'], sprintf ($lang->signup['verifiyemailsubject'], $SITENAME), $body, 'signup', false);
              redirect ($_this_script_ . '&resend=' . $accountid, 'Activation code has been sent.');
            }
          }
        }
      }
    }
  }

  stdhead ('Manage Unconfirmed User Accounts');
  require_once INC_PATH . '/functions_mkprettytime.php';
  _form_header_open_ ('Manage Unconfirmed User Accounts (To keep records updated reguarly, all pending accounts will be deleted after X days (X = Setting panel > Cleanupsettings))', 7);
  ($query = sql_query ('SELECT u.id, u.username, u.email, u.ip, u.added, u.country, c.name, c.flagpic FROM users u LEFT JOIN countries c ON (u.country=c.id) WHERE u.status = \'pending\' ORDER by u.added DESC') OR sqlerr (__FILE__, 101));
  if (mysql_num_rows ($query) == 0)
  {
    echo '
	<tr>
	<td colspan="7"><font color="red">There is no unconfirmed user!</font></td>
	</tr>';
  }
  else
  {
    echo '
	<script type="text/javascript">
		function check_type()
		{
			var action_type = document.forms[0].elements[\'type\'].value;
			if (action_type == "resend")
			{
				ts_show(\'loading-layer\');
			}
			return true;
		}
	</script>
	<form method="post" action="' . $_this_script_ . '" name="mass_update" onsubmit="return check_type()">
	<input type="hidden" name="do" value="mass_update">
	<tr>	
	<td class="subheader" align="center" width="19%">Username</td>
	<td class="subheader" align="center" width="18%">Email</td>
	<td class="subheader" align="center" width="15%">IP Address</td>
	<td class="subheader" align="center" width="18%">Registered at</td>
	<td class="subheader" align="center" width="7%">Country</td>
	<td class="subheader" align="center" width="18%">Action</td>
	<td class="subheader" align="center" width="5%"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'mass_update\', this, \'group\');"></td>
	<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Sending Activation Code...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>
	</tr>';
    if ((isset ($_GET['resend']) AND strstr ($_GET['resend'], ',')))
    {
      $userids = explode (',', $_GET['resend']);
    }

    while ($u = mysql_fetch_assoc ($query))
    {
      $bgcolor = ((isset ($_GET['resend']) AND $_GET['resend'] == $u['id']) ? ' bgcolor="gray"' : ((is_array ($userids) AND @in_array ($u['id'], $userids)) ? ' bgcolor="gray"' : ''));
      $userid = intval ($u['id']);
      echo '
		<tr' . $bgcolor . '>		
		<td align="center"><a href="' . $BASEURL . '/checkuser.php?id=' . $userid . '"><b>' . htmlspecialchars_uni ($u['username']) . '</b></a></td>
		<td align="center">' . htmlspecialchars_uni ($u['email']) . '</td>
		<td align="center">' . (!empty ($u['ip']) ? htmlspecialchars_uni ($u['ip']) : 'N/A') . '</td>
		<td align="center">' . my_datee ($dateformat, $u['added']) . ' ' . my_datee ($timeformat, $u['added']) . '</td>
		<td align="center"><img src=' . $BASEURL . '/' . $pic_base_url . 'flag/' . $u['flagpic'] . ' alt="' . $u['name'] . '" title="' . $u['name'] . '" style="margin-left: 8pt"></td>
		<td align="center"><a href="' . $_this_script_ . '&do=delete&userid=' . $userid . '" alt="Delete this account" title="Delete this account"><font color="red">Delete</font></a> / <a href="' . $_this_script_ . '&do=confirm&userid=' . $userid . '" alt="Confirm this account" title="Confirm this account"><font color="green">Confirm</font></a> / <a href="' . $_this_script_ . '&do=resend&userid=' . $userid . '" alt="Resend activation code" title="Resend activation code" onclick="ts_show(\'loading-layer\')"><font color="blue">Resend</font></a></td>
		<td align="center"><input type="checkbox" name="userids[]" value="' . $userid . '" checkme="group"></td>
		</tr>
		';
    }

    echo '
	<tr>
		<td colspan="7" align="right">
			<select name="type" id="type">
				<option value="delete">Delete Accounts</option>
				<option value="confirm">Confirm Accounts</option>
				<option value="resend">Resend activation codes</option>
			</select> 
			<input type="submit" value="Mass-Update Selected Accounts">
		</td>
	</tr>
	</form>';
  }

  _form_header_close_ ();
  stdfoot ();
?>
