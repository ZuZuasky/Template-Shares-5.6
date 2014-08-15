<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '')
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    exit ('<error>' . $message . '</error>');
  }

  require_once 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  define ('BK_VERSION', '0.6');
  $ajax_quick_bookmark = ($_POST['ajax_quick_bookmark'] ? true : false);
  if ($usergroups['canbookmark'] != 'yes')
  {
    if (!$ajax_quick_bookmark)
    {
      print_no_permission ();
    }
    else
    {
      show_msg ($lang->global['nopermission']);
    }
  }

  $action = (isset ($_POST['action']) ? $_POST['action'] : (isset ($_GET['action']) ? $_GET['action'] : ''));
  $torrentid = (isset ($_POST['torrentid']) ? intval ($_POST['torrentid']) : (isset ($_GET['torrentid']) ? intval ($_GET['torrentid']) : ''));
  $user = intval ($CURUSER['id']);
  if (!is_valid_id ($torrentid))
  {
    if (!$ajax_quick_bookmark)
    {
      print_no_permission (true);
    }
    else
    {
      show_msg ($lang->global['nopermission']);
    }
  }

  if ($action == 'delete')
  {
    $query = @sql_query ('SELECT userid,torrentid FROM bookmarks WHERE userid=' . @sqlesc ($user) . ' AND torrentid = ' . @sqlesc ($torrentid) . ' LIMIT 1');
    if (mysql_num_rows ($query) != 0)
    {
      @sql_query ('DELETE FROM bookmarks WHERE userid=' . @sqlesc ($user) . ' AND torrentid = ' . @sqlesc ($torrentid) . ' LIMIT 1');
    }
  }
  else
  {
    if ($action == 'add')
    {
      $query = @sql_query ('SELECT userid,torrentid FROM bookmarks WHERE userid=' . @sqlesc ($user) . ' AND torrentid = ' . @sqlesc ($torrentid) . ' LIMIT 1');
      if (mysql_num_rows ($query) == 0)
      {
        @sql_query ('INSERT INTO bookmarks (userid, torrentid) VALUES (' . @sqlesc ($user) . ',' . @sqlesc ($torrentid) . ')');
      }
    }
  }

  if (!$ajax_quick_bookmark)
  {
    redirect ('browse.php?special_search=mybookmarks');
  }

?>
