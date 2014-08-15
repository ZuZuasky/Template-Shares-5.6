<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('TSCR_VERSION', '1.2 ');
  define ('IN_CRON', true);
  define ('IN_TRACKER', true);
  define ('TIMENOW', time ());
  define ('THIS_PATH', dirname (__FILE__));
  define ('CONFIG_DIR', THIS_PATH . '/config/');
  define ('CRON_PATH', THIS_PATH . '/include/cron/');
  define ('INC_PATH', THIS_PATH . '/include');
  define ('TSDIR', THIS_PATH);
  require INC_PATH . '/init.php';
  require CRON_PATH . '/cron_functions.php';
  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  define ('LOGFILE', 'cron_error_logs');
  require_once INC_PATH . '/functions_ts_error_handler.php';
  set_error_handler ('TS_Error_Handler');
  $_FileData = base64_decode ('R0lGODlhAQABAIAAAMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
  $_FileSize = strlen ($_FileData);
  header ('Content-type: image/gif');
  if (!(strpos ($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false AND strpos (php_sapi_name (), 'cgi') !== false))
  {
    header ('Content-Length: ' . $_FileSize);
    header ('Connection: Close');
  }

  echo $_FileData;
  flush ();
  if (!databaseconnect ())
  {
    exit ();
  }

  readconfig (array ('CLEANUP', 'THEME'));
  $lang = new trackerlanguage ();
  $lang->set_path (INC_PATH . '/languages');
  $lang->set_language (((isset ($_COOKIE['ts_language']) AND file_exists (INC_PATH . '/languages/' . $_COOKIE['ts_language'])) ? $_COOKIE['ts_language'] : $defaultlanguage));
  $lang->load ('cronjobs');
  $_CQuery = @mysql_query ('SELECT cronid, minutes, filename, loglevel FROM ts_cron WHERE nextrun < \'' . TIMENOW . '\' AND active = \'1\'');
  if (0 < @mysql_num_rows ($_CQuery))
  {
    while ($_RunCron = @mysql_fetch_assoc ($_CQuery))
    {
      if (file_exists (CRON_PATH . $_RunCron['filename']))
      {
        $CQueryCount = 0;
        $_CStart = array_sum (explode (' ', microtime ()));
        include CRON_PATH . $_RunCron['filename'];
        if ($_RunCron['loglevel'] == '1')
        {
          logcronaction ($_RunCron['filename'], $CQueryCount, round (array_sum (explode (' ', microtime ())) - $_CStart, 4));
        }

        @mysql_query ('UPDATE ts_cron SET nextrun = \'' . (TIMENOW + $_RunCron['minutes']) . '\' WHERE cronid = \'' . $_RunCron['cronid'] . '\'');
        continue;
      }
    }
  }

?>
