<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_data ($from = '', $where = '')
  {
    global $lang;
    $get_data = sql_query ('SELECT * FROM ' . $from . ' WHERE ' . $where);
    if (mysql_num_rows ($get_data) < 1)
    {
      stderr ($lang->global['error'], $lang->global['noresultswiththisid']);
      return null;
    }

    return mysql_fetch_array ($get_data);
  }

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

  function dupe_check ($from = '', $where = '')
  {
    global $lang;
    $get_data = sql_query ('SELECT * FROM ' . $from . ' WHERE ' . $where);
    if (1 <= mysql_num_rows ($get_data))
    {
      if (!$_POST['ajax_quick_report'])
      {
        stderr ($lang->global['error'], $lang->report['dupe']);
        return null;
      }

      return false;
    }

    return true;
  }

  function show_form ($formaction, $save = true, $votedfor_xtra = '')
  {
    global $lang;
    global $reportid;
    global $backto;
    global $error;
    global $iv;
    global $action;
    $head = $lang->report['report'] . strtoupper (str_replace ('report', '', $action));
    stdhead ($head);
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="action" value="' . $formaction . '">
	<table border="0" width="100%" cellspacing="0" cellpadding="5">';
    if ($save)
    {
      echo '<input type="hidden" name="do" value="save">';
    }

    if ($votedfor_xtra)
    {
      echo '<input type="hidden" name="votedfor_xtra" value="' . intval ($votedfor_xtra) . '">';
    }

    echo '<input type="hidden" name="returnto" value="' . $backto . '">';
    echo '<input type="hidden" name="reportid" value="' . $reportid . '">';
    if ($error == 1)
    {
      echo '<tr><td colspan="2" align="center"><font color=red>' . sprintf ($lang->global['invalidimagecode'], remaining ()) . '</td></tr>';
    }

    echo '<tr><td class="thead" colspan="2" align="center">' . $head . '</td></tr>';
    echo '<tr><td class="rowhead">' . $lang->report['reportid'] . '</td><td>' . $reportid . '</td></tr>';
    echo '<tr><td class="rowhead">' . $lang->report['reason'] . '</td><td><textarea name="reason" id="specialboxg" rows="6"></textarea> ' . ($iv == 'no' ? '<br /><input type="submit" class=button value="' . $lang->global['buttonreport'] . '">' : '') . '</td></tr>';
    show_image_code (true, $lang->global['buttonreport']);
    echo '</table></form>';
    stdfoot ();
    exit ();
  }

  require_once 'global.php';
  include_once INC_PATH . '/functions_security.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('R_VERSION', 'REPORT SYSTEM v.1.0.3 ');
  if ($usergroups['canreport'] != 'yes')
  {
    if (!$_POST['ajax_quick_report'])
    {
      print_no_permission ();
    }
    else
    {
      show_msg ($lang->global['nopermission']);
    }

    exit ();
  }

  if (($_POST['ajax_quick_report'] AND $_POST['reason']))
  {
    $_POST['reason'] = urldecode ($_POST['reason']);
  }

  $lang->load ('report');
  $action = (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : ''));
  $do = (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : ''));
  $reason = (isset ($_POST['reason']) ? htmlspecialchars_uni ($_POST['reason']) : (isset ($_GET['reason']) ? htmlspecialchars_uni ($_GET['reason']) : ''));
  $reportid = (isset ($_POST['reportid']) ? intval ($_POST['reportid']) : (isset ($_GET['reportid']) ? intval ($_GET['reportid']) : ''));
  $error = (isset ($_POST['error']) ? intval ($_POST['error']) : (isset ($_GET['error']) ? intval ($_GET['error']) : ''));
  $backto = (isset ($_GET['returnto']) ? htmlspecialchars_uni ($_GET['returnto']) : (isset ($_SERVER['HTTP_REFERER']) ? htmlspecialchars_uni ($_SERVER['HTTP_REFERER']) : $BASEURL));
  $returnto = (isset ($_GET['returnto']) ? $_GET['returnto'] : (isset ($_POST['returnto']) ? $_POST['returnto'] : ''));
  if (($action == 'reportrequest' AND $do == 'save'))
  {
    int_check ($reportid, true);
    if (empty ($reason))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'request\''))
    {
      $date = sqlesc (get_date_time ());
      (sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ', \'request\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')')) OR sqlerr (__FILE__, 148));
      redirect ($returnto, $lang->report['done'], $SITENAME, 4, false, false);
    }

    exit ();
  }
  else
  {
    if (($action == 'reportrequest' AND empty ($do)))
    {
      int_check ($reportid, true);
      show_form ('reportrequest');
      exit ();
    }
  }

  if (($action == 'reportuser' AND $do == 'save'))
  {
    int_check ($reportid, true);
    if (empty ($reason))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'user\''))
    {
      $date = sqlesc (get_date_time ());
      (sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ', \'user\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')')) OR sqlerr (__FILE__, 174));
      redirect ($returnto, $lang->report['done'], $SITENAME, 4, false, false);
    }

    exit ();
  }
  else
  {
    if (($action == 'reportuser' AND empty ($do)))
    {
      int_check ($reportid, true);
      if ($reportid == $CURUSER['id'])
      {
        print_no_permission ();
      }

      $get = sql_query ('SELECT u.*, g.cansettingspanel, g.issupermod FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($reportid));
      if (mysql_num_rows ($get) < 1)
      {
        stderr ($lang->global['error'], $lang->global['noresultswiththisid']);
      }
      else
      {
        $arr = mysql_fetch_array ($get);
      }

      if ((($arr['cansettingspanel'] == 'yes' OR $arr['issupermod'] == 'yes') AND $usergroups['cansettingspanel'] != 'yes'))
      {
        print_no_permission ();
      }
      else
      {
        int_check ($reportid, true);
        show_form ('reportuser');
      }

      exit ();
    }
  }

  if (($action == 'reporttorrent' AND $do == 'save'))
  {
    if (!is_valid_id ($reportid))
    {
      if (!$_POST['ajax_quick_report'])
      {
        stderr ($lang->global['error'], $lang->global['notorrentid']);
      }
      else
      {
        show_msg ($lang->global['notorrentid']);
      }
    }

    if (empty ($reason))
    {
      if (!$_POST['ajax_quick_report'])
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }
      else
      {
        show_msg ($lang->global['dontleavefieldsblank']);
      }
    }

    if (((($iv == 'yes' OR $iv == 'reCAPTCHA') AND $_POST['siv'] != 'false') AND !$_POST['ajax_quick_report']))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'torrent\''))
    {
      $date = sqlesc (get_date_time ());
      sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ', \'torrent\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')'));
      if (!$_POST['ajax_quick_report'])
      {
        redirect ($returnto, $lang->report['done'], $SITENAME, 4, false, false);
      }
    }
    else
    {
      show_msg ($lang->report['dupe']);
    }

    exit ();
  }
  else
  {
    if (($action == 'reporttorrent' AND empty ($do)))
    {
      int_check ($reportid, true);
      show_form ('reporttorrent');
      exit ();
    }
  }

  if (($action == 'reportcomment' AND $do == 'save'))
  {
    if (!is_valid_id ($reportid))
    {
      if (!$_POST['ajax_quick_report'])
      {
        stderr ($lang->global['error'], $lang->report['invalidcommentid']);
      }
      else
      {
        show_msg ($lang->report['invalidcommentid']);
      }
    }

    if (empty ($reason))
    {
      if (!$_POST['ajax_quick_report'])
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }
      else
      {
        show_msg ($lang->global['dontleavefieldsblank']);
      }
    }

    if (((($iv == 'yes' OR $iv == 'reCAPTCHA') AND $_POST['siv'] != 'false') AND !$_POST['ajax_quick_report']))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'comment\''))
    {
      $date = sqlesc (get_date_time ());
      sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ', \'comment\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')'));
      if (!$_POST['ajax_quick_report'])
      {
        redirect ($returnto, $lang->report['done'], $SITENAME, 4, false, false);
      }
    }
    else
    {
      show_msg ($lang->report['dupe']);
    }

    exit ();
  }
  else
  {
    if (($action == 'reportcomment' AND empty ($do)))
    {
      int_check ($reportid, true);
      show_form ('reportcomment');
      exit ();
    }
  }

  if (($action == 'reportrequestcomment' AND $do == 'save'))
  {
    int_check ($reportid, true);
    if (empty ($reason))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'reqcomment\''))
    {
      $date = sqlesc (get_date_time ());
      (sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ',\'reqcomment\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')')) OR sqlerr (__FILE__, 319));
      redirect ($returnto, $lang->report['done'], $SITENAME, 4, false, false);
    }

    exit ();
  }
  else
  {
    if (($action == 'reportrequestcomment' AND empty ($do)))
    {
      int_check ($reportid, true);
      show_form ('reportrequestcomment');
      exit ();
    }
  }

  if (($action == 'reportforumpost' AND $do == 'save'))
  {
    int_check ($reportid, true);
    if (empty ($reason))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    $res2 = sql_query ('SELECT pid,tid FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($reportid));
    if (mysql_num_rows ($res2) == 0)
    {
      stderr ($lang->global['error'], $lang->report['invalidpost']);
    }

    $arr2 = mysql_fetch_array ($res2);
    $pid = $arr2['pid'];
    $tid = $arr2['tid'];
    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'forumpost\''))
    {
      $date = sqlesc (get_date_time ());
      (sql_query ('INSERT INTO reports (addedby, votedfor, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ',\'forumpost\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')')) OR sqlerr (__FILE__, 355));
      redirect ($BASEURL . '/tsf_forums/showthread.php?tid=' . $tid . '&pid=' . $pid . '#pid' . $pid, $lang->report['done'], $SITENAME, 4, false, false);
    }

    exit ();
  }
  else
  {
    if (($action == 'reportforumpost' AND empty ($do)))
    {
      int_check ($reportid, true);
      $res2 = sql_query ('SELECT pid,tid FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($reportid));
      if (mysql_num_rows ($res2) == 0)
      {
        stderr ($lang->global['error'], $lang->report['invalidpost']);
      }

      show_form ('reportforumpost');
      exit ();
    }
  }

  if (($action == 'reportvisitormessage' AND $do == 'save'))
  {
    int_check ($reportid, true);
    if (empty ($reason))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    $votedfor_xtra = intval ($_POST['votedfor_xtra']);
    int_check ($votedfor_xtra, true);
    if (($iv == 'yes' OR $iv == 'reCAPTCHA'))
    {
      check_code ($_POST['imagestring'], 'report.php?action=' . $action . '&reportid=' . $reportid . '&error=1&returnto=' . htmlspecialchars_uni ($returnto));
    }

    if (dupe_check ('reports', 'addedby = ' . sqlesc ($CURUSER['id']) . ' AND votedfor=' . sqlesc ($reportid) . ' AND type = \'visitormsg\' AND votedfor_xtra = ' . sqlesc ($votedfor_xtra)))
    {
      $date = sqlesc (get_date_time ());
      (sql_query ('INSERT INTO reports (addedby, votedfor, votedfor_xtra, type, reason, added) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($reportid) . ', ' . sqlesc ($votedfor_xtra) . ', \'visitormsg\', ' . sqlesc ($reason) . ('' . ', ' . $date . ')')) OR sqlerr (__FILE__, 389));
      redirect (ts_seo ($reportid, 'REPORT VISITOR MESSAGE'), $lang->report['done'], $SITENAME, 4, false, false);
    }

    exit ();
    return 1;
  }

  if (($action == 'reportvisitormessage' AND empty ($do)))
  {
    int_check ($reportid, true);
    $votedfor_xtra = intval ($_GET['votedfor_xtra']);
    int_check ($votedfor_xtra, true);
    show_form ('reportvisitormessage', true, $votedfor_xtra);
    exit ();
  }

?>
