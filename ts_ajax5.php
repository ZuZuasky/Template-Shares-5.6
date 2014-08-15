<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '', $error = false)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    if ($error)
    {
      exit ('<error>' . $message . '</error>');
    }

    exit ($message);
  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  require 'global.php';
  gzip ();
  dbconn ();
  define ('TS_AJAX_VERSION', '1.2.0 ');
  define ('NcodeImageResizer', true);
  define ('TU_VERSION', '2.6.6 ');
  if ((((!defined ('IN_SCRIPT_TSSEv56') OR strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST') OR !$CURUSER) OR !is_mod ($usergroups)))
  {
    exit ();
  }

  if ((!isset ($_POST['tid']) OR !is_valid_id ($_POST['tid'])))
  {
    show_msg ($lang->global['notorrentid'], true);
  }

  $id = intval ($_POST['tid']);
  $Query = mysql_query ('SELECT t_link FROM torrents WHERE id = \'' . $id . '\'');
  if (mysql_num_rows ($Query) == 0)
  {
    show_msg ($lang->global['notorrentid'], true);
  }

  $oldt_link = mysql_result ($Query, 0, 't_link');
  if (!$oldt_link)
  {
    show_msg ($lang->global['notorrentid'], true);
  }

  preg_match ('@<a href=\'(.*)\'@U', $oldt_link, $imdblink);
  $t_link = $imdblink[1];
  if ($t_link)
  {
    include_once INC_PATH . '/ts_imdb.php';
    if ($t_link)
    {
      mysql_query ('UPDATE torrents SET t_link = ' . sqlesc ($t_link) . ' WHERE id = \'' . $id . '\'');
      require_once INC_PATH . '/functions_imdb_rating.php';
      if ($IMDBRating = tssegetimdbratingimage ($t_link))
      {
        $t_link = str_replace ('<b>User Rating:</b>', '<b>User Rating:</b> ' . $IMDBRating['image'], $t_link);
      }

      show_msg ($t_link);
    }
  }

?>
