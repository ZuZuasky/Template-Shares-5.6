<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function inform_staff_leader ()
  {
    global $CURUSER;
    global $amount;
    global $payment_status;
    global $currency;
    global $firstname;
    global $lastname;
    global $payer_email;
    if (empty ($_SESSION['message_done']))
    {
      require_once INC_PATH . '/functions_pm.php';
      send_pm (1, 'Username: [url=' . ts_seo ($CURUSER['id'], $CURUSER['username']) . ']' . $CURUSER['username'] . '[/url]

Details:
Amount: ' . $amount . '
Status: ' . $payment_status . '
Currency: ' . $currency . '
Name: ' . $firstname . ' ' . $lastname . '
Email: ' . $payer_email . '
', 'New Donation from ' . $CURUSER['username']);
      sql_query ('INSERT INTO funds (cash, user, added) VALUES (' . sqlesc ($amount) . ', ' . sqlesc (intval ($CURUSER['id'])) . ', NOW())');
      $_SESSION['message_done'] = TIMENOW;
    }

  }

  function promote_account ($keys = array ())
  {
    global $lang;
    global $usergroups;
    global $amount;
    global $CURUSER;
    global $SITENAME;
    $status = $lang->donate['paypal_subheader'];
    if ((((!is_mod ($usergroups) AND $usergroups['isvipgroup'] != 'yes') AND $CURUSER['usergroup'] != $keys['t_usergroup']) AND !empty ($keys['t_usergroup'])))
    {
      $t_updateset[] = 'usergroup = ' . sqlesc (intval ($keys['t_usergroup']));
    }

    if ($keys['until'] == '255')
    {
      $t_updateset[] = 'donoruntil = \'0000-00-00 00:00:00\', donor = \'yes\'';
    }
    else
    {
      if ($CURUSER['donoruntil'] == '0000-00-00 00:00:00')
      {
        $donoruntil = get_date_time (gmtime () + $keys['until'] * 604800);
        $t_updateset[] = 'donoruntil = ' . sqlesc ($donoruntil) . ', donor = \'yes\'';
      }
      else
      {
        $donorlengthadd = $keys['until'] * 7;
        $t_updateset[] = '' . 'donoruntil = ADDDATE( donoruntil, INTERVAL ' . $donorlengthadd . ' DAY ), donor = \'yes\'';
      }
    }

    $uploaded = $keys['upload'] * 1024 * 1024 * 1024;
    $dur = sprintf ($lang->donate['paypal_dur'], $keys['until']);
    $bonuscomment = gmdate ('Y-m-d') . ' - +' . $keys['bonus'] . ' Points by System-PayPal
' . $CURUSER['bonuscomment'];
    $modcomment = gmdate ('Y-m-d') . ('' . ' - Donator status set for ' . $dur . ' by System-PayPal
') . $CURUSER['modcomment'];
    $t_updateset[] = 'uploaded = uploaded + ' . sqlesc ($uploaded) . ', seedbonus = seedbonus + ' . sqlesc ($keys['bonus']) . ', invites = invites + ' . sqlesc ($keys['invite']) . ', donated = ' . (int)$amount . ', total_donated = ' . $CURUSER['total_donated'] . ' + ' . (int)$amount . ', modcomment = ' . sqlesc ($modcomment) . ', bonuscomment = ' . sqlesc ($bonuscomment);
    if ((is_array ($t_updateset) AND !empty ($t_updateset)))
    {
      (sql_query ('UPDATE users SET  ' . implode (', ', $t_updateset) . ' WHERE id=' . sqlesc (intval ($CURUSER['id']))) OR stderr ($lang->global['error'], $lang->donate['paypal_error4']));
    }

    require_once INC_PATH . '/functions_pm.php';
    send_pm ($CURUSER['id'], sprintf ($lang->donate['paypal_msg_body'], $CURUSER['username'], $SITENAME, $dur), $lang->donate['paypal_msg_subject']);
    $status .= sprintf ($lang->donate['paypal_finish'], $keys['invite'], $keys['upload'], $keys['bonus'], $dur);
    echo $status . '<h3></h3>';
    $promote_account = md5 (mksecret (5));
    $_SESSION['promote_user_account_done'] = $promote_account;
  }

  require_once 'global.php';
  include_once INC_PATH . '/readconfig_paypal.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  define ('AP_VERSION', 'v1.2');
  @set_time_limit (0);
  $lang->load ('donate');
  $req = 'cmd=_notify-synch';
  $tx_token = $_GET['tx'];
  $req .= '' . '&tx=' . $tx_token . '&at=' . $paypal_auth_token;
  $header .= 'POST /cgi-bin/webscr HTTP/1.0
';
  $header .= 'Content-Type: application/x-www-form-urlencoded
';
  $header .= 'Content-Length: ' . strlen ($req) . '

';
  $fp = @fsockopen ('ssl://www.' . ($paypal_demo_mode == 'yes' ? 'sandbox.' : '') . 'paypal.com', 443, $errno, $errstr, 30);
  if (!$fp)
  {
    $fp = @fsockopen ('www.' . ($paypal_demo_mode == 'yes' ? 'sandbox.' : '') . 'paypal.com', 80, $errno, $errstr, 30);
  }

  if (!$fp)
  {
    @fclose ($fp);
    stderr ($lang->global['error'], $lang->donate['paypal_error1']);
  }
  else
  {
    fputs ($fp, $header . $req);
    $res = '';
    $headerdone = false;
    while (!feof ($fp))
    {
      $line = fgets ($fp, 1024);
      if (strcmp ($line, '
') == 0)
      {
        $headerdone = true;
        continue;
      }
      else
      {
        if ($headerdone)
        {
          $res .= $line;
          continue;
        }

        continue;
      }
    }

    $lines = explode ('
', $res);
    $keyarray = array ();
    if (strcmp ($lines[0], 'SUCCESS') == 0)
    {
      $i = 1;
      while ($i < count ($lines))
      {
        list ($key, $val) = explode ('=', $lines[$i]);
        $keyarray[urldecode ($key)] = urldecode ($val);
        ++$i;
      }

      $firstname = htmlspecialchars_uni ($keyarray['first_name']);
      $lastname = htmlspecialchars_uni ($keyarray['last_name']);
      $payer_email = htmlspecialchars_uni ($keyarray['payer_email']);
      $itemname = htmlspecialchars_uni ($keyarray['item_name']);
      $amount = htmlspecialchars_uni ($keyarray['mc_gross']);
      $currency = htmlspecialchars_uni ($keyarray['mc_currency']);
      $payment_status = htmlspecialchars_uni ($keyarray['payment_status']);
      stdhead ($lang->donate['paypal_head']);
      echo $lang->donate['paypal_info'];
      echo sprintf ($lang->donate['paypal_results'], $firstname, $lastname, $payer_email, $itemname, $amount, $currency, $payment_status);
      define ('IN_PAYPAL', true);
      include_once CONFIG_DIR . '/paypal_config.php';
      if (in_array ($amount, $accepted_amounts))
      {
        $keys = $paypals[$amount];
        $key_done = true;
      }
      else
      {
        if (max ($accepted_amounts) < $amount)
        {
          $keys = $paypals[max ($accepted_amounts)];
          $key_done = true;
        }
        else
        {
          if ($amount < min ($accepted_amounts))
          {
            $key_done = false;
          }
          else
          {
            foreach ($accepted_amounts as $accepted)
            {
              $i = 0;
              while ($i <= intval ($accepted))
              {
                if (intval ($amount) == $i)
                {
                  $keys = $paypals[$accepted];
                  $key_done = true;
                  break 2;
                }

                ++$i;
              }
            }
          }
        }
      }

      if (!$key_done)
      {
        $keys = false;
      }

      if (((is_array ($keys) AND $paypal_auto_mode == 'yes') AND empty ($_SESSION['promote_user_account_done'])))
      {
        promote_account ($keys);
      }

      inform_staff_leader ();
      stdfoot ();
    }
    else
    {
      if (strcmp ($lines[0], 'FAIL') == 0)
      {
        @fclose ($fp);
        $msg = 'Not verified paypal access: Username: <a href="' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '">' . $CURUSER['username'] . '</a> - UserID: ' . $CURUSER['id'] . ' - UserIP : ' . getip ();
        write_log ($msg);
        stderr ($lang->global['error'], $lang->donate['paypal_error3']);
      }
    }
  }

  @fclose ($fp);
?>
