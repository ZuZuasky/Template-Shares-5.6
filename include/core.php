<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ((isset ($_REQUEST['GLOBALS']) OR isset ($_FILES['GLOBALS'])))
  {
    define ('errorid', 1);
    include_once TSDIR . '/ts_error.php';
    exit ();
  }

  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND !defined ('SKIP_REFERRER_CHECK')))
  {
    if (($_SERVER['HTTP_HOST'] OR $_ENV['HTTP_HOST']))
    {
      $http_host = ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
    }
    else
    {
      if (($_SERVER['SERVER_NAME'] OR $_ENV['SERVER_NAME']))
      {
        $http_host = ($_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
      }
    }

    if (($http_host AND $_SERVER['HTTP_REFERER']))
    {
      $referrer_parts = @parse_url ($_SERVER['HTTP_REFERER']);
      $ref_port = intval ($referrer_parts['port']);
      $ref_host = $referrer_parts['host'] . (!empty ($ref_port) ? '' . ':' . $ref_port : '');
      $allowed = preg_split ('#\\s+#', $allowedreferrers, 0 - 1, PREG_SPLIT_NO_EMPTY);
      $allowed[] = preg_replace ('#^www\\.#i', '', $http_host);
      $allowed[] = '.paypal.com';
      $pass_ref_check = false;
      foreach ($allowed as $host)
      {
        if (preg_match ('#' . preg_quote ($host, '#') . '$#siU', $ref_host))
        {
          $pass_ref_check = true;
          break;
        }
      }

      unset ($allowed);
      if ($pass_ref_check == false)
      {
        define ('errorid', 2);
        include_once TSDIR . '/ts_error.php';
        exit ();
      }
    }
  }

  require_once INC_PATH . '/init.php';
  require_once INC_PATH . '/globalfunctions.php';
  require_once INC_PATH . '/ts_functions.php';
  require_once INC_PATH . '/functions.php';
  require_once INC_PATH . '/config.php';
  require_once INC_PATH . '/class_language.php';
  require_once INC_PATH . '/functions_tsseo.php';
  $lang = new trackerlanguage ();
  $lang->set_path (INC_PATH . '/languages');
  if ((empty ($_COOKIE['ts_language']) OR !file_exists (INC_PATH . '/languages/' . $_COOKIE['ts_language'])))
  {
    $lang->set_language ($defaultlanguage);
  }
  else
  {
    $lang->set_language ($_COOKIE['ts_language']);
  }

  $lang->load ('global');
  if ($ctracker == 'yes')
  {
    require_once INC_PATH . '/ctracker.php';
  }

  if (@get_magic_quotes_gpc ())
  {
    function strip_magic_quotes ($arr)
    {
      foreach ($arr as $k => $v)
      {
        if (is_array ($v))
        {
          $arr[$k] = strip_magic_quotes ($v);
          continue;
        }
        else
        {
          $arr[$k] = stripslashes ($v);
          continue;
        }
      }

      return $arr;
    }

    if (!empty ($_GET))
    {
      $_GET = strip_magic_quotes ($_GET);
    }

    if (!empty ($_POST))
    {
      $_POST = strip_magic_quotes ($_POST);
    }

    if (!empty ($_COOKIE))
    {
      $_COOKIE = strip_magic_quotes ($_COOKIE);
    }
  }

  if ((!isset ($HTTP_POST_VARS) AND isset ($_POST)))
  {
    $HTTP_POST_VARS = $_POST;
    $HTTP_GET_VARS = $_GET;
    $HTTP_SERVER_VARS = $_SERVER;
    $HTTP_COOKIE_VARS = $_COOKIE;
    $HTTP_ENV_VARS = $_ENV;
    $HTTP_POST_FILES = $_FILES;
  }

  if (!@get_magic_quotes_gpc ())
  {
    if (is_array ($HTTP_GET_VARS))
    {
      while (list ($k, $v) = each ($HTTP_GET_VARS))
      {
        if (is_array ($HTTP_GET_VARS[$k]))
        {
          while (list ($k2, $v2) = each ($HTTP_GET_VARS[$k]))
          {
            $HTTP_GET_VARS[$k][$k2] = addslashes ($v2);
          }

          @reset ($HTTP_GET_VARS[$k]);
          continue;
        }
        else
        {
          $HTTP_GET_VARS[$k] = addslashes ($v);
          continue;
        }
      }

      @reset ($HTTP_GET_VARS);
    }

    if (is_array ($HTTP_POST_VARS))
    {
      while (list ($k, $v) = each ($HTTP_POST_VARS))
      {
        if (is_array ($HTTP_POST_VARS[$k]))
        {
          while (list ($k2, $v2) = each ($HTTP_POST_VARS[$k]))
          {
            $HTTP_POST_VARS[$k][$k2] = addslashes ($v2);
          }

          @reset ($HTTP_POST_VARS[$k]);
          continue;
        }
        else
        {
          $HTTP_POST_VARS[$k] = addslashes ($v);
          continue;
        }
      }

      @reset ($HTTP_POST_VARS);
    }

    if (is_array ($HTTP_COOKIE_VARS))
    {
      while (list ($k, $v) = each ($HTTP_COOKIE_VARS))
      {
        if (is_array ($HTTP_COOKIE_VARS[$k]))
        {
          while (list ($k2, $v2) = each ($HTTP_COOKIE_VARS[$k]))
          {
            $HTTP_COOKIE_VARS[$k][$k2] = addslashes ($v2);
          }

          @reset ($HTTP_COOKIE_VARS[$k]);
          continue;
        }
        else
        {
          $HTTP_COOKIE_VARS[$k] = addslashes ($v);
          continue;
        }
      }

      @reset ($HTTP_COOKIE_VARS);
    }
  }

?>
