<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_error_handler ($errno, $errstr, $errfile, $errline)
  {
    global $usergroups;
    switch ($errno)
    {
      case E_WARNING:
      {
      }

      case E_USER_WARNING:
      {
        if ((!error_reporting () OR !ini_get ('display_errors')))
        {
          return null;
        }

        echo '' . '<br /><strong>Warning</strong>: ' . $errstr . ' in <strong>' . $errfile . '</strong> on line <strong>' . $errline . '</strong><br />';
        break;
      }

      case E_USER_ERROR:
      {
        if ((($LogFile = TSDIR . '/error_logs/' . LOGFILE . '.php' AND is_writable ($LogFile)) AND $FP = fopen ($LogFile, 'a')))
        {
          if (fwrite ($FP, time () . '|' . base64_encode ($errstr) . ('' . '|' . $errfile . '|' . $errline . '|' . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] . '
')) === FALSE)
          {
            return '';
          }

          fclose ($FP);
        }

        if (!headers_sent ())
        {
          define ('SAPI_NAME', php_sapi_name ());
          if ((SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi'))
          {
            header ('Status: 500 Internal Server Error');
          }
          else
          {
            header ('HTTP/1.1 500 Internal Server Error');
          }
        }

        if ((error_reporting () OR ini_get ('display_errors')))
        {
          echo '' . '<br /><strong>Fatal error:</strong> ' . $errstr . ' in <strong>' . $errfile . '</strong> on line <strong>' . $errline . '</strong><br />';
          if ((function_exists ('debug_print_backtrace') AND is_mod ($usergroups)))
          {
            echo str_repeat (' ', 512);
            debug_print_backtrace ();
          }
        }

        exit ();
      }
    }

  }

?>
