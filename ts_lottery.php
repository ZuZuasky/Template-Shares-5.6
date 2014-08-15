<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_lottery_errors ()
  {
    global $error;
    global $lang;
    if (0 < count ($error))
    {
      $errors = implode ('<br />', $error);
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

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('L_VERSION', '0.9 ');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  require INC_PATH . '/readconfig_lottery.php';
  $lang->load ('ts_lottery');
  $act = (isset ($_GET['act']) ? $_GET['act'] : (isset ($_POST['act']) ? $_POST['act'] : ''));
  $is_mod = is_mod ($usergroups);
  $error = array ();
  $lottery_title = sprintf ($lang->ts_lottery['title'], $SITENAME);
  $userid = ((($is_mod AND isset ($_GET['userid'])) AND is_valid_id ($_GET['userid'])) ? intval ($_GET['userid']) : intval ($CURUSER['id']));
  if ((($lottery_begin_date != '' AND $lottery_end_date != '') AND $lottery_end_date < get_date_time ()))
  {
    $_winners_array = array ();
    $__winner_amount = $lottery_winner_amount * ($lottery_amount_type == 'MB' ? 1024 * 1024 : 1024 * 1024 * 1024);
    ($_query = sql_query ('' . 'SELECT DISTINCT userid FROM `ts_lottery_tickets` ORDER BY RAND() LIMIT ' . $lottery_max_winners) OR sqlerr (__FILE__, 43));
    if (0 < mysql_num_rows ($_query))
    {
      $_subject = $lang->ts_lottery['msg_subject'];
      $_msg = sprintf ($lang->ts_lottery['msg_body'], mksize ($__winner_amount));
      require_once INC_PATH . '/functions_pm.php';
      while ($_winners = mysql_fetch_assoc ($_query))
      {
        if (!in_array ($_winners['userid'], $_winners_array))
        {
          $_winners_array[] = $_winners['userid'];
          $modcomment = get_date_time () . ' - ' . sprintf ($lang->ts_lottery['modcomment'], mksize ($__winner_amount)) . '
';
          (sql_query ('' . 'UPDATE users SET uploaded = uploaded + ' . $__winner_amount . ', modcomment = CONCAT(' . sqlesc ($modcomment . '') . ', modcomment) WHERE id = ' . sqlesc ($_winners['userid'])) OR sqlerr (__FILE__, 55));
          send_pm ($_winners['userid'], $_msg, $_subject);
          continue;
        }
      }
    }

    $LOTTERY['lottery_enabled'] = $lottery_enabled;
    $LOTTERY['lottery_allowed_usergroups'] = $lottery_allowed_usergroups;
    $LOTTERY['lottery_ticket_amount'] = $lottery_ticket_amount;
    $LOTTERY['lottery_winner_amount'] = $lottery_winner_amount;
    $LOTTERY['lottery_amount_type'] = $lottery_amount_type;
    $LOTTERY['lottery_max_tickets_per_user'] = $lottery_max_tickets_per_user;
    $LOTTERY['lottery_max_winners'] = $lottery_max_winners;
    $LOTTERY['lottery_begin_date'] = '';
    $LOTTERY['lottery_end_date'] = '';
    $LOTTERY['lottery_last_winners'] = (0 < count ($_winners_array) ? implode (',', $_winners_array) : '');
    $LOTTERY['lottery_last_winners_amount'] = $lottery_winner_amount;
    $LOTTERY['lottery_last_winners_date'] = $lottery_end_date;
    require_once INC_PATH . '/functions_writeconfig.php';
    writeconfig ('LOTTERY', $LOTTERY);
    sql_query ('TRUNCATE TABLE `ts_lottery_tickets`');
    sql_query ('OPTIMIZE TABLE `ts_lottery_tickets`');
    redirect ($_SERVER['SCRIPT_NAME']);
    exit ();
  }

  if (($lottery_enabled != 'yes' AND !$is_mod))
  {
    stderr ($lang->global['error'], $lang->ts_lottery['disabled']);
  }

  if (($lottery_allowed_usergroups != 'ALL' AND !$is_mod))
  {
    if (!preg_match ('#\\[' . $CURUSER['usergroup'] . '\\]#', $lottery_allowed_usergroups))
    {
      stderr ($lang->global['error'], $lang->ts_lottery['no_permission']);
    }
  }

  if (($lottery_begin_date != '' AND $lottery_end_date != ''))
  {
    $user_p_tickets = 0;
    ($query = sql_query ('SELECT COUNT(ticketid) as user_p_tickets FROM ts_lottery_tickets WHERE userid = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 97));
    if (0 < mysql_num_rows ($query))
    {
      $user_p_tickets = mysql_result ($query, 0, 'user_p_tickets');
    }

    $user_available_tickets = $lottery_max_tickets_per_user - $user_p_tickets;
    if ($user_available_tickets < 0)
    {
      $user_available_tickets = 0;
    }

    $status_message = sprintf ($lang->ts_lottery['ticket_status'], ts_nf ($user_p_tickets), ts_nf ($user_available_tickets));
    if ($act == 'show_list')
    {
      ($query = sql_query ('SELECT l.userid, u.username, u.uploaded, u.downloaded, u.options, g.namestyle FROM ts_lottery_tickets l LEFT JOIN users u ON (l.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid)') OR sqlerr (__FILE__, 115));
      if (0 < mysql_num_rows ($query))
      {
        while ($showusers = mysql_fetch_assoc ($query))
        {
          ++$show_list_user_count[$showusers['username']];
          $show_list_user_array[$showusers['username']] = array ('username' => '<a href="' . ts_seo ($showusers['userid'], $showusers['username']) . '">' . get_user_color ($showusers['username'], $showusers['namestyle']) . '</a>', 'uploaded' => (((preg_match ('#I3#is', $showusers['options']) OR preg_match ('#I4#is', $showusers['options'])) AND !$is_mod) ? '0' : $showusers['uploaded']), 'downloaded' => (((preg_match ('#I3#is', $showusers['options']) OR preg_match ('#I4#is', $showusers['options'])) AND !$is_mod) ? '0' : $showusers['downloaded']), 'ratio' => (((preg_match ('#I3#is', $showusers['options']) OR preg_match ('#I4#is', $showusers['options'])) AND !$is_mod) ? '0' : (0 < $showusers['downloaded'] ? number_format ($showusers['uploaded'] / $showusers['downloaded'], 2) : '0')), 'userid' => $showusers['userid']);
        }

        $show_list = '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead" colspan="5" align="center">' . $lang->ts_lottery['show_list_title'] . '</td>
			</tr>
			<tr>
				<td class="subheader" align="left" width="20%">' . $lang->ts_lottery['owner_name'] . '</td>
				<td class="subheader" align="center" width="20%">' . $lang->ts_lottery['uploaded'] . '</td>
				<td class="subheader" align="center" width="20%">' . $lang->ts_lottery['downloaded'] . '</td>
				<td class="subheader" align="center" width="20%">' . $lang->ts_lottery['ratio'] . '</td>
				<td class="subheader" align="center" width="20%">' . $lang->ts_lottery['total_tickets'] . '</td>
			</tr>';
        foreach ($show_list_user_array as $username => $user_array_value)
        {
          $bgcolor = ($user_array_value['userid'] == $CURUSER['id'] ? ' class="highlight"' : '');
          $show_list .= '
				<tr>
					<td align="left" width="20%"' . $bgcolor . '>' . $user_array_value['username'] . '</td>
					<td align="center" width="20%"' . $bgcolor . '>' . mksize ($user_array_value['uploaded']) . '</td>
					<td align="center" width="20%"' . $bgcolor . '>' . mksize ($user_array_value['downloaded']) . '</td>
					<td align="center" width="20%"' . $bgcolor . '>' . $user_array_value['ratio'] . '</td>
					<td align="center" width="20%"' . $bgcolor . '>' . ts_nf ($show_list_user_count[$username]) . '</td>
				</tr>';
        }

        $show_list .= '
			</table>
			<br />
			';
      }
    }

    if ($act == 'purchase_ticket')
    {
      if ($lottery_end_date < get_date_time ())
      {
        $error[] = $lang->ts_lottery['end_of_date'];
      }
      else
      {
        $_ta = intval ($_GET['ticket_amount']);
        if ((!is_valid_id ($_ta) OR $user_available_tickets < $_ta))
        {
          $error[] = $status_message;
        }
        else
        {
          $_uuploads = $CURUSER['uploaded'];
          $_total_tickets_cost = $_ta * $lottery_ticket_amount * ($lottery_amount_type == 'MB' ? 1024 * 1024 : 1024 * 1024 * 1024);
          if ($_uuploads < $_total_tickets_cost)
          {
            $_diff = mksize ($_total_tickets_cost - $_uuploads);
            $error[] = sprintf ($lang->ts_lottery['cant_purchase'], ts_nf ($_ta), mksize ($_total_tickets_cost), mksize ($_uuploads), $_diff);
          }
          else
          {
            $i = 0;
            while ($i < $_ta)
            {
              (sql_query ('' . 'INSERT INTO ts_lottery_tickets (userid) VALUES (' . $userid . ')') OR sqlerr (__FILE__, 182));
              ++$i;
            }

            (sql_query ('' . 'UPDATE users SET uploaded = uploaded - ' . $_total_tickets_cost . ' WHERE id = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 184));
            redirect ($_SERVER['SCRIPT_NAME'] . '?userid=' . $userid, $lang->ts_lottery['thank_you']);
            exit ();
          }
        }
      }
    }

    $total_p_tickets = $total_p_users = 0;
    ($query = sql_query ('SELECT COUNT(DISTINCT userid) as total_p_users, COUNT(ticketid) as total_p_tickets FROM ts_lottery_tickets') OR sqlerr (__FILE__, 194));
    if (0 < mysql_num_rows ($query))
    {
      $total_p_users = mysql_result ($query, 0, 'total_p_users');
      $total_p_tickets = mysql_result ($query, 0, 'total_p_tickets');
    }

    $_calc = 1 / $total_p_tickets * $user_p_tickets * 100;
    $your_win_ratio = sprintf ($lang->ts_lottery['your_win_ratio'], @number_format ($_calc, 2)) . '%';
  }

  $winners_array = array ();
  if ($lottery_last_winners != '')
  {
    ($query = sql_query ('' . 'SELECT u.id, u.username, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE id IN (0,' . $lottery_last_winners . ')') OR sqlerr (__FILE__, 208));
    if (0 < mysql_num_rows ($query))
    {
      while ($user = mysql_fetch_assoc ($query))
      {
        $winners_array[] = '<a href="' . ts_seo ($user['id'], $user['username']) . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a>';
      }
    }
  }

  $lottery_total_winners = ts_nf (count ($winners_array));
  $lottery_last_winners_amount = mksize (($lottery_amount_type == 'MB' ? 1024 * 1024 * $lottery_last_winners_amount : 1024 * 1024 * 1024 * $lottery_last_winners_amount)) . ' ' . $lang->ts_lottery['per_user'];
  $show_purchase_button = '';
  if ((($lottery_begin_date != '' AND $lottery_end_date != '') AND $user_available_tickets <= $lottery_max_tickets_per_user))
  {
    $show_purchase_button = '
	<script type="text/javascript">
		function EnterTicketAmount()
		{
			CanPurchase = ' . $user_available_tickets . ';
			UserUploaded = ' . $CURUSER['uploaded'] . ';
			TicketAmount = prompt("' . $lang->ts_lottery['info'] . '", "1");
			if (TicketAmount && !isNaN(TicketAmount) && parseInt(TicketAmount, 10) > 0)
			{
				if (TicketAmount > CanPurchase)
				{
					alert("' . $lang->global['error'] . '\\n\\n' . strip_tags ($status_message) . '");
					return false;
				}
				else
				{
					TicketCost = TicketAmount*' . ($lottery_amount_type == 'MB' ? 1024 * 1024 * $lottery_ticket_amount : 1024 * 1024 * 1024 * $lottery_ticket_amount) . ';
					if (UserUploaded < TicketCost)
					{
						alert("' . $lang->ts_lottery['java_error'] . '");
						return false;
					}
					else
					{
						FormAction = "' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&act=purchase_ticket&ticket_amount="+TicketAmount;
						document.purchase.action = FormAction;
						return true;
					}
				}
			}
			else
			{
				return false;
			}
		}
	</script>
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&act=purchase_ticket" name="purchase" onsubmit="return EnterTicketAmount();">
		<input type="hidden" name="act" value="purchase_ticket">
		<input type="hidden" name="userid" value="' . $userid . '">
		<input type="submit" value="' . $lang->ts_lottery['purchase_button'] . '" class=button>
	</form>
	';
  }

  stdhead ($lottery_title);
  show_lottery_errors ();
  echo $show_list . '
<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td class="thead" colspan="4" align="center">' . $lang->ts_lottery['open_raffles'] . '</td>
	</tr>
	<tr>
		<td class="subheader" align="left" width="55%">' . $lang->ts_lottery['ticket_limit'] . '</td>
		<td class="subheader" align="center" width="12%">' . $lang->ts_lottery['start_date'] . '</td>
		<td class="subheader" align="center" width="12%">' . $lang->ts_lottery['end_date'] . '</td>
		<td class="subheader" align="center" width="11%">' . $lang->ts_lottery['purchase_button'] . '</td>
	</tr>';
  if (($lottery_begin_date != '' AND $lottery_end_date != ''))
  {
    echo '
	<tr>
		<td align="left" width="55%">' . $status_message . ($your_win_ratio ? '<br />' . $your_win_ratio : '') . '</td>
		<td align="center" width="12%">' . my_datee ($dateformat, $lottery_begin_date) . '<br />' . my_datee ($timeformat, $lottery_begin_date) . '</td>
		<td align="center" width="12%">' . my_datee ($dateformat, $lottery_end_date) . '<br />' . my_datee ($timeformat, $lottery_end_date) . '</td>
		<td align="center" width="11%">' . $show_purchase_button . '</td>
	</tr>
	<tr><td colspan="4">' . sprintf ($lang->ts_lottery['total_purchased'], ts_nf ($total_p_tickets), ts_nf ($total_p_users)) . ' ' . sprintf ($lang->ts_lottery['show_list_button'], '<a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&act=show_list">', '</a>') . '<br />' . sprintf ($lang->ts_lottery['rules'], ts_nf ($lottery_max_tickets_per_user), mksize (($lottery_amount_type == 'MB' ? 1024 * 1024 * $lottery_ticket_amount : 1024 * 1024 * 1024 * $lottery_ticket_amount)), mksize (($lottery_amount_type == 'MB' ? 1024 * 1024 * $lottery_winner_amount : 1024 * 1024 * 1024 * $lottery_winner_amount))) . '</td></tr

	';
  }
  else
  {
    echo '<tr><td colspan="4">' . $lang->ts_lottery['no_active_lottery'] . '</td></tr>';
  }

  echo '
</table>
<br />
<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td class="thead" colspan="3" align="center">' . $lang->ts_lottery['closed_raffles'] . '</td>
	</tr>
	<tr>
		<td class="subheader" align="left" width="60%">' . $lang->ts_lottery['last_lottery_winners'] . '</td>
		<td class="subheader" align="center" width="20%">' . $lang->ts_lottery['last_earn'] . '</td>
		<td class="subheader" align="left" width="20%">' . $lang->ts_lottery['end_date'] . '</td>
	</tr>
	<tr>
		<td align="left" width="45%">' . sprintf ($lang->ts_lottery['winners'], $lottery_total_winners) . ' ' . implode (', ', $winners_array) . '</td>
		<td align="center" width="15%">' . $lottery_last_winners_amount . '</td>
		<td align="left" width="30%">' . my_datee ($dateformat, $lottery_last_winners_date) . ' ' . my_datee ($timeformat, $lottery_last_winners_date) . '</td>
	</tr>
</table>
<br />
';
  stdfoot ();
?>
