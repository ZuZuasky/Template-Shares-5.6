<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function registration_check ($type = 'invitesystem', $maxuserscheck = true, $ipcheck = true)
  {
    global $invitesystem;
    global $registration;
    global $maxusers;
    global $maxip;
    global $lang;
    global $ip;
    global $cache;
    if (($type == 'invitesystem' AND $invitesystem == 'off'))
    {
      stderr ($lang->global['error'], $lang->global['invitedisabled']);
    }

    if (($type == 'normal' AND $registration == 'off'))
    {
      stderr ($lang->global['error'], ($invitesystem == 'on' ? $lang->global['inviteonly'] : $lang->global['signupdisabled']), false);
    }

    if ($maxuserscheck)
    {
      require_once TSDIR . '/' . $cache . '/indexstats.php';
      if ($maxusers <= $indexstats['registered'])
      {
        stderr ($lang->global['error'], $lang->global['signuplimitreached']);
      }
    }

    ($a = mysql_fetch_row (@sql_query ('SELECT COUNT(ip) FROM users WHERE ip=' . @sqlesc ($ip))) OR sqlerr (__FILE__, 36));
    if (($maxip != 'disable' AND $maxip <= $a[0]))
    {
      stderr ($lang->global['error'], sprintf ($lang->global['nodupeaccount'], htmlspecialchars_uni ($ip)), false);
      return null;
    }

    ($a = mysql_fetch_row (@sql_query ('SELECT COUNT(ip) FROM iplog WHERE ip=' . @sqlesc ($ip))) OR sqlerr (__FILE__, 43));
    if ((0 < $a[0] AND $maxip != 'disable'))
    {
      stderr ($lang->global['error'], $lang->global['nodupeaccount2']);
    }

  }

  function cur_user_check ()
  {
    global $CURUSER;
    global $lang;
    if ($CURUSER)
    {
      redirect ($BASEURL . '/index.php', $lang->global['alreadylogged']);
    }

  }

  function sessioncookie ($id, $passhash, $expires = false, $do = false)
  {
    global $securelogin;
    if (($securelogin == 'yes' OR $do))
    {
      $passhash = securehash ($passhash);
      if ($expires)
      {
        $GLOBALS[$sessioncacheexpire] = true;
      }

      if ($do)
      {
        setcookie ('s_secure_access', securehash ($_SERVER['REMOTE_ADDR']), 2147483647, '/');
      }

      $_SESSION['s_secure_uid'] = $id;
      $_SESSION['s_secure_pass'] = $passhash;
      if (isset ($sessioncacheexpire))
      {
        return $sessioncacheexpire;
      }

      return null;
    }

  }

  function logincookie ($id, $passhash, $expires = 2147483647)
  {
    $passhash = securehash ($passhash);
    if ($expires != 2147483647)
    {
      $expires = TIMENOW + 900;
    }

    setcookie ('c_secure_uid', $id, $expires, '/');
    setcookie ('c_secure_pass', $passhash, $expires, '/');
  }

  function logoutsession ()
  {
    if (session_name () == '')
    {
      @session_name ('TSSE_Session');
      session_start ();
    }

    session_unset ();
    session_destroy ();
  }

  function logoutcookie ()
  {
    setcookie ('c_secure_uid', '', 2147483647, '/');
    setcookie ('c_secure_pass', '', 2147483647, '/');
    setcookie ('s_secure_access', '', 2147483647, '/');
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
