<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function email_exists ($email)
  {
    $tracker_query = sql_query ('SELECT email FROM users WHERE email=' . sqlesc ($email) . ' LIMIT 1');
    if (1 <= mysql_num_rows ($tracker_query))
    {
      return false;
    }

    return true;
  }

  function validusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  $lang->load ('referrals');
  define ('R_VERSION', '1.2.2 ');
  $query = sql_query ('SELECT r.uid, uu.modcomment FROM referrals r INNER JOIN users u ON (r.referring=u.id) LEFT JOIN users uu ON (uu.id=r.uid) WHERE r.done = \'no\' AND u.usergroup > \'' . UC_USER . '\' AND u.usergroup != \'' . UC_BANNED . '\' AND u.enabled = \'yes\' AND u.status = \'confirmed\'');
  if (0 < mysql_num_rows ($query))
  {
    $lang->load ('cronjobs');
    require_once INC_PATH . '/readconfig_cleanup.php';
    require_once INC_PATH . '/functions_pm.php';
    $credit = $referrergift * 1024 * 1024 * 1024;
    $mksizecredit = mksize ($credit);
    while ($arr = mysql_fetch_assoc ($query))
    {
      sql_query ('UPDATE referrals SET credit = credit + ' . $credit . ', done = \'yes\' WHERE uid = \'' . $arr['uid'] . '\'');
      send_pm ($arr['uid'], sprintf ($lang->cronjobs['r_message'], $mksizecredit), $lang->cronjobs['r_subject']);
      sql_query ('UPDATE users SET uploaded = uploaded + ' . $credit . ', modcomment = ' . sqlesc (gmdate ('Y-m-d') . ' - Earned ' . $mksizecredit . ' by Referral System.
' . $arr['modcomment']) . ' WHERE id = \'' . $arr['uid'] . '\'');
    }

    unset ($credit);
    unset ($mksizecredit);
  }

  $act = (isset ($_POST['act']) ? htmlspecialchars_uni ($_POST['act']) : '');
  if (((isset ($_GET['id']) AND is_valid_id ($_GET['id'])) AND is_mod ($usergroups)))
  {
    $userid = 0 + $_GET['id'];
    $query = sql_query ('SELECT username FROM users WHERE id = ' . sqlesc ($userid));
    $result = mysql_fetch_assoc ($query);
    $username = htmlspecialchars_uni ($result['username']);
  }
  else
  {
    $userid = 0 + $CURUSER['id'];
    $username = htmlspecialchars_uni ($CURUSER['username']);
  }

  $rlink = '' . $BASEURL . '/signup.php?referrer=' . $username;
  include_once INC_PATH . '/readconfig_cleanup.php';
  $amount = $referrergift;
  if ($act == 'send')
  {
    $fname = $_POST['name'];
    $femail = $_POST['email'];
    if ((empty ($fname) OR empty ($femail)))
    {
      $error = $lang->global['dontleavefieldsblank'];
    }
    else
    {
      if (!validusername ($fname))
      {
        $error = $lang->referrals['error_1'];
      }
      else
      {
        if (!check_email ($femail))
        {
          $error = $lang->referrals['error_2'];
        }
        else
        {
          if (!email_exists ($femail))
          {
            $error = $lang->referrals['error_3'];
          }
        }
      }
    }

    if (!isset ($error))
    {
      $success = $lang->referrals['done'];
      $subject = sprintf ($lang->referrals['subject'], $SITENAME);
      $message = sprintf ($lang->referrals['message'], htmlspecialchars_uni ($fname), $username, $SITENAME, $rlink);
      sent_mail ($femail, $subject, $message, 'referrals', false);
    }

    unset ($act);
  }

  if (empty ($act))
  {
    stdhead ($lang->referrals['title'], true, 'collapse');
    echo '
	<div class="success">' . sprintf ($lang->referrals['head'], $SITENAME, $amount) . '</div>
	' . (isset ($error) ? '<div class="error">' . $error . '</div>' : (isset ($success) ? '<div class="success">' . $success . '</div>' : '')) . '
	<table border="1" width="100%" cellspacing="0" cellpadding="5">
		<tr class="tabletitle">
			<td colspan="8" class="colhead">
				' . ts_collapse ('referrer1') . '<b>' . sprintf ($lang->referrals['subhead'], $amount) . '</b>
			</td>
		</tr>
		' . ts_collapse ('referrer1', 2);
    echo '<tr>
	<td class="subheader">' . $lang->referrals['uname'] . '</td>
	<td class="subheader">' . $lang->referrals['ugroup'] . '</td>
	<td class="subheader">' . $lang->referrals['regdate'] . '</td>
	<td class="subheader">' . $lang->referrals['lseen'] . '</td>
	<td class="subheader">' . $lang->referrals['ul'] . '</td>
	<td class="subheader">' . $lang->referrals['dl'] . '</td>
	<td class="subheader">' . $lang->referrals['ratio'] . '</td>
	<td class="subheader">' . $lang->referrals['status'] . '</td>
	</tr>';
    ($query = sql_query ('' . 'SELECT r.credit, u.*, g.namestyle, g.title FROM referrals r INNER JOIN users u ON (r.referring=u.id) INNER JOIN usergroups g ON (u.usergroup=g.gid) WHERE r.uid=' . $userid) OR sqlerr (__FILE__, 135));
    if (mysql_num_rows ($query) == 0)
    {
      echo '<tr class="tableb"><td colspan="8">' . $lang->referrals['noref'] . '</td></tr>';
    }
    else
    {
      while ($user = mysql_fetch_assoc ($query))
      {
        $uid = 0 + $user['id'];
        $linktouser = '<a href="' . ts_seo ($uid, $user['username']) . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a>';
        $ugroup = get_user_color ($user['title'], $user['namestyle']);
        $regdate = my_datee ($dateformat, $user['added']) . ' ' . my_datee ($timeformat, $user['added']);
        if ($user['last_access'] != '0000-00-00 00:00:00')
        {
          $lseen = my_datee ($dateformat, $user['last_access']) . ' ' . my_datee ($timeformat, $user['last_access']);
        }
        else
        {
          $lseen = '---';
        }

        $ul = mksize ($user['uploaded']);
        $dl = mksize ($user['downloaded']);
        if (0 < $user['downloaded'])
        {
          include_once INC_PATH . '/functions_ratio.php';
          $ratio = number_format ($user['uploaded'] / $user['downloaded'], 2);
          $ratio = '<font color=' . get_ratio_color ($ratio) . ('' . '>' . $ratio . '</font>');
        }
        else
        {
          if (0 < $user['uploaded'])
          {
            $ratio = 'Inf.';
          }
          else
          {
            $ratio = '---';
          }
        }

        $status = mksize ($user['credit']);
        echo '' . '<tr><td>' . $linktouser . '</td><td>' . $ugroup . '</td><td>' . $regdate . '</td><td>' . $lseen . '</td><td>' . $ul . '</td><td>' . $dl . '</td><td>' . $ratio . '</td><td>' . $status . '</td>';
        $totalcredit += $user['credit'];
      }
    }

    echo '<tr><td colspan="7" align="right"><b>' . $lang->referrals['total'] . '</b></td><td colspan="1">' . mksize ($totalcredit) . '</td></tr>';
    echo '</table>';
    echo '
	<br />
	<table border="1" width="100%" cellspacing="0" cellpadding="5">
		<tr class="tabletitle">
			<td colspan="2" class="colhead">
				' . ts_collapse ('referrer2') . '<b>' . $lang->referrals['rhead'] . '</b>
			</td>
		</tr>
		' . ts_collapse ('referrer2', 2);
    echo '
	<tr>
		<td colspan="2">
			<table border="1" width="100%" cellspacing="0" cellpadding="5">
				<tr>
					<td class="subheader">
						(1) ' . sprintf ($lang->referrals['info1'], $rlink) . '
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<table border="1" width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<td class="subheader">
					(2) ' . $lang->referrals['info2'] . '
				</td>
			</tr>
				<tr>
					<td>
						<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="send" ' . submit_disable ('send', 'submit') . '>
							<input type="hidden" name="act" value="send">
							 ' . $lang->referrals['fname'] . ' <input type="text" name="name" value="' . (isset ($_POST['name']) ? htmlspecialchars_uni ($_POST['name']) : '') . '">
							 ' . $lang->referrals['femail'] . ' <input type="text" name="email" value="' . (isset ($_POST['email']) ? htmlspecialchars_uni ($_POST['email']) : '') . '">
							 <input type="submit" name="submit" value="' . $lang->referrals['sbutton'] . '">
						 </form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	';
    echo '</table>';
    stdfoot ();
  }

?>
