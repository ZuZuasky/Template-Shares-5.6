<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('FD_VERSION', '1.0 by xam');
  include_once INC_PATH . '/readconfig_kps.php';
  if ((is_mod ($usergroups) OR ($usergroups['candeletetorrent'] == 'yes' AND $CURUSER['id'] == $row['owner'])))
  {
    $lang->load ('delete');
    require INC_PATH . '/functions_getvar.php';
    getvar ('id');
    $id = 0 + $id;
    $hash = $_GET['hash'];
    $reason = ($_POST['reason'] ? $_POST['reason'] : ($_GET['reason'] ? $_GET['reason'] : ''));
    int_check ($id, true);
    $res = sql_query ('SELECT name,owner FROM torrents WHERE id = ' . sqlesc ($id));
    $row = mysql_fetch_assoc ($res);
    if (!$row)
    {
      stderr ($lang->global['error'], $lang->global['notorrentid']);
    }

    if ((empty ($reason) OR strlen ($reason) < 3))
    {
      stdhead ('Fast Delete');
      _form_header_open_ ('Fast Delete');
      echo '
		<form method="post" action="' . $_this_script_ . '&id=' . $id . '">		
		<tr><td align="right" width="20%"><strong>Please enter delete reason:</strong></td><td align="left" width="80%"><input type="text" name="reason" value="' . htmlspecialchars_uni ($reason) . '" size="60"> <input type="submit" value="Fast Delete"></td></tr>
		</form>
		';
      _form_header_close_ ();
      stdfoot ();
      exit ();
    }

    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->url = '' . 'You are about to delete a torrent. Click
<a href=\'' . $_this_script_ . '&id=' . $id . '&sure=1&hash={1}&reason=' . base64_encode ($reason) . ('' . '\'>here</a> if you are sure. Click <a href=\'' . $BASEURL . '/browse.php\'>here</a> to go back.');
    $ts_token->redirect = '' . $_this_script_ . '&id=' . $id;
    $ts_token->create ();
    $reason = base64_decode ($reason);
    require_once INC_PATH . '/functions_deletetorrent.php';
    deletetorrent ($id);
    if (($CURUSER['anonymous'] == 'yes' AND is_mod ($usergroups)))
    {
      write_log (sprintf ($lang->delete['logmsg1'], $id, $row['name'], 'Fast Deleted! Reason: ' . htmlspecialchars_uni ($reason)));
    }
    else
    {
      write_log (sprintf ($lang->delete['logmsg2'], $id, $row['name'], $CURUSER['username'], 'Fast Deleted! Reason: ' . htmlspecialchars_uni ($reason)));
    }

    $lang->load ('delete');
    if ($row['owner'] != $CURUSER['id'])
    {
      require_once INC_PATH . '/functions_pm.php';
      send_pm ($row['owner'], sprintf ($lang->delete['logmsg2'], $id, $row['name'], $CURUSER['username'], $reason), $lang->delete['deleted']);
    }

    kps ('-', $kpsupload, $row['owner']);
    unset ($hash);
    unset ($_SESSION[token_code]);
    unset ($_SESSION[token_created]);
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
