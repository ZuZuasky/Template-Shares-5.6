<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_charset ()
  {
    $path = CONFIG_DIR . '/THEME';
    $fp = fopen ($path, 'r');
    if (!$fp)
    {
      return 'UTF-8';
    }

    $content = '';
    while (!feof ($fp))
    {
      $content .= fread ($fp, 102400);
    }

    fclose ($fp);
    $tmp = unserialize ($content);
    if (empty ($tmp))
    {
      return 'UTF-8';
    }

    return $tmp['charset'];
  }

  require_once 'global.php';
  include_once INC_PATH . '/functions_login.php';
  dbconn ();
  define ('L_VERSION', '0.8');
  if ($CURUSER)
  {
    if (((((empty ($_SESSION['hash']) OR strlen ($_SESSION['hash']) != 32) OR empty ($_GET['logouthash'])) OR strlen ($_GET['logouthash']) != 32) OR $_GET['logouthash'] !== $_SESSION['hash']))
    {
      include_once INC_PATH . '/ts_token.php';
      $ts_token = new ts_token ();
      $hash = $ts_token->create_return ();
      $charset = get_charset ();
      header ('' . 'Content-type: text/html; charset=' . $charset);
      exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>' . sprintf ($lang->global['logout_error'], $hash) . '</b></font>');
      return 1;
    }

    unset ($_SESSION[hash]);
    unset ($_SESSION[hash_time]);
    logoutcookie ();
    logoutsession ();
    $host = getip ();
    $useragent = htmlspecialchars_uni (strtolower ($_SERVER['HTTP_USER_AGENT']));
    sql_query ('DELETE FROM ts_sessions WHERE sessionhash = ' . sqlesc (md5 ($host . $useragent)));
    header ('' . 'Location: ' . $BASEURL . '/login.php');
    exit ();
    return 1;
  }

  unset ($_SESSION[hash]);
  unset ($_SESSION[hash_time]);
  header ('' . 'Location: ' . $BASEURL . '/login.php');
  exit ();
?>
