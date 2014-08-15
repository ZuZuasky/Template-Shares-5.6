<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '', $error = true)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    if ($error)
    {
      exit ('' . '<error>' . $message . '</error>');
    }

    exit ($message);
  }

  $rootpath = './../../';
  require_once '' . $rootpath . '/global.php';
  define ('TSU_VERSION', '0.4 by xam');
  gzip ();
  dbconn ();
  $id = (isset ($_POST['id']) ? intval ($_POST['id']) : (isset ($_GET['id']) ? intval ($_GET['id']) : ''));
  if (((isset ($_POST['ajax_update']) AND strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST') AND is_valid_id ($id)))
  {
    define ('USE_AJAX', true);
    $ajax = true;
  }
  else
  {
    $ajax = false;
    int_check ($id, true);
    $returnto = (isset ($_SERVER['HTTP_REFERER']) ? htmlspecialchars_uni ($_SERVER['HTTP_REFERER']) : $BASEURL . '/browse.php');
    $returnto .= (strpos ($returnto, '?') ? '&amp;tsuid=' . $id : '?tsuid=' . $id);
    loggedinorreturn ();
    maxsysop ();
    parked ();
  }

  $query = sql_query ('SELECT ts_external_lastupdate FROM torrents WHERE id = ' . sqlesc ($id) . ' AND ts_external = \'yes\'');
  if (mysql_num_rows ($query) == 0)
  {
    if (!$ajax)
    {
      redirect ($returnto, $lang->global['recentlyupdated'], '', '3', false, false);
      exit ();
    }
    else
    {
      show_msg ($lang->global['recentlyupdated']);
    }
  }

  $ts_external_lastupdate = mysql_result ($query, 0, 'ts_external_lastupdate');
  if (time () - $ts_external_lastupdate < 3600)
  {
    if (!$ajax)
    {
      redirect ($returnto, $lang->global['recentlyupdated'], '', '3', false, false);
      exit ();
    }
    else
    {
      show_msg ($lang->global['recentlyupdated']);
    }
  }

  $lang->load ('upload');
  $externaltorrent = TSDIR . '/' . $torrent_dir . '/' . $id . '.torrent';
  include_once INC_PATH . '/ts_external_scrape/ts_external.php';
  if (!$ajax)
  {
    redirect ($returnto, $lang->global['externalupdated'], '', '3', false, false);
    return 1;
  }

  if (!$seeders)
  {
    $seeders = 0;
  }

  if (!$leechers)
  {
    $leechers = 0;
  }

  show_msg ('' . '<span class=\'sticky\'>' . $seeders . '</span>|<span class=\'sticky\'>' . $leechers . '</span>|' . $id, false);
?>
