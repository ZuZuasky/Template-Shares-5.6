<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('D_VERSION', '0.7');
  include_once INC_PATH . '/readconfig_kps.php';
  $lang->load ('delete');
  require_once INC_PATH . '/class_page_check.php';
  $newpage = new page_verify ();
  $newpage->check ('delete');
  $id = (isset ($_POST['id']) ? (int)$_POST['id'] : (isset ($_GET['id']) ? (int)$_GET['id'] : ''));
  int_check ($id, true);
  $res = sql_query ('SELECT name,owner FROM torrents WHERE id = ' . sqlesc ($id));
  $row = mysql_fetch_assoc ($res);
  if (!$row)
  {
    stderr ($lang->global['error'], $lang->global['notorrentid']);
  }

  if ((is_mod ($usergroups) OR ($usergroups['candeletetorrent'] == 'yes' AND $CURUSER['id'] == $row['owner'])))
  {
    $rt = (int)$_POST['reasontype'];
    if (((!is_int ($rt) OR $rt < 1) OR 5 < $rt))
    {
      stderr ($lang->global['error'], sprintf ($lang->delete['invalidreason'], $rt));
    }

    $r = $_POST['r'];
    $reason = $_POST['reason'];
    if ($rt == 1)
    {
      $reasonstr = $lang->delete['reasonstr1'];
    }
    else
    {
      if ($rt == 2)
      {
        $reasonstr = $lang->delete['reasonstr2'] . ($reason[0] ? ': ' . trim ($reason[0]) : '!');
      }
      else
      {
        if ($rt == 3)
        {
          $reasonstr = $lang->delete['reasonstr3'] . ($reason[1] ? ': ' . trim ($reason[1]) : '!');
        }
        else
        {
          if ($rt == 4)
          {
            if (!$reason[2])
            {
              stderr ($lang->global['error'], $lang->delete['violaterule']);
            }

            $reasonstr = sprintf ($lang->delete['reasonstr4'], $SITENAME) . trim ($reason[2]);
          }
          else
          {
            if (!$reason[3])
            {
              stderr ($lang->global['error'], $lang->delete['enterreason']);
            }

            $reasonstr = trim ($reason[3]);
          }
        }
      }
    }

    require_once INC_PATH . '/functions_deletetorrent.php';
    deletetorrent ($id, true);
    if (($CURUSER['anonymous'] == 'yes' AND is_mod ($usergroups)))
    {
      write_log (sprintf ($lang->delete['logmsg1'], $id, $row['name'], htmlspecialchars ($reasonstr)));
    }
    else
    {
      write_log (sprintf ($lang->delete['logmsg2'], $id, $row['name'], $CURUSER['username'], htmlspecialchars ($reasonstr)));
    }

    if ($row['owner'] != $CURUSER['id'])
    {
      require_once INC_PATH . '/functions_pm.php';
      send_pm ($row['owner'], sprintf ($lang->delete['logmsg2'], $id, $row['name'], $CURUSER['username'], htmlspecialchars ($reasonstr)), $lang->delete['deleted']);
    }

    kps ('-', $kpsupload, $row['owner']);
    if (file_exists (TSDIR . '/' . $cache . '/latesttorrents.html'))
    {
      @unlink (TSDIR . '/' . $cache . '/latesttorrents.html');
    }

    redirect ('' . $BASEURL . '/browse.php', $lang->delete['deleted'], '', 3, false, false);
    exit ();
    return 1;
  }

  print_no_permission (true);
?>
