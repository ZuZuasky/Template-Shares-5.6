<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  @ini_set ('session.gc_maxlifetime', '18000');
  @session_cache_expire (1440);
  @set_time_limit (0);
  @set_magic_quotes_runtime (0);
  @ini_set ('magic_quotes_sybase', 0);
  @session_name ('TSSE_Session');
  @session_start ();
  define ('IN_TRACKER', true);
  define ('IN_SCRIPT_TSSEv56', true);
  define ('O_SCRIPT_VERSION', '5.6');
  define ('TIMENOW', time ());
  define ('TSDIR', dirname (__FILE__));
  define ('INC_PATH', TSDIR . '/include');
  define ('CONFIG_DIR', TSDIR . '/config');
  $rootpath = (isset ($rootpath) ? $rootpath : TSDIR);
  if (!defined ('DEBUGMODE'))
  {
    $GLOBALS['ts_start_time'] = array_sum (explode (' ', microtime ()));
    unset ($_SESSION[totaltime]);
    unset ($_SESSION[totalqueries]);
    $_SESSION['queries'] = array ();
  }

  if (((empty ($_SESSION['hash']) OR empty ($_SESSION['hash_time'])) OR 1800 < TIMENOW - $_SESSION['hash_time']))
  {
    $_SESSION['hash'] = md5 (uniqid (rand (), true));
    $_SESSION['hash_time'] = TIMENOW;
  }

  define ('LOGFILE', 'tracker_error_logs');
  require INC_PATH . '/functions_ts_error_handler.php';
  set_error_handler ('TS_Error_Handler');
  require INC_PATH . '/core.php';
?>
