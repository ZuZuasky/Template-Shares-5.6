<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_thanks ($Remove = false)
  {
    global $lang;
    global $torrentid;
    $array = array ();
    $Query = mysql_query ('SELECT t.uid, u.username, g.namestyle FROM ts_thanks t LEFT JOIN users u ON (t.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.tid = \'' . $torrentid . '\' ORDER BY u.username');
    if (mysql_num_rows ($Query) == 0)
    {
      exit ();
    }
    else
    {
      while ($T = mysql_fetch_assoc ($Query))
      {
        $array[] = '<a href="' . ts_seo ($T['uid'], $T['username']) . '">' . get_user_color ($T['username'], $T['namestyle']) . '</a>';
      }
    }

    exit ('<div id="thanks_button">' . ($Remove == false ? '<div id="thanks_button"><input type="button" value="' . $lang->global['buttonthanks2'] . '" onclick="javascript:TSajaxquickthanks(' . $torrentid . ', true);" /></div>' : '<input type="button" value="' . $lang->global['buttonthanks'] . '" onclick="javascript:TSajaxquickthanks(' . $torrentid . ');" /></div>') . implode (', ', $array));
  }

  require 'global.php';
  define ('TT_VERSION', '1.0 ');
  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  dbconn ();
  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
  if ((!$CURUSER OR $thankssystem != 'yes'))
  {
    exit ('<error>' . $lang->global['nopermission'] . '</error>');
  }

  if ($usergroups['canthanks'] != 'yes')
  {
    exit ('<error>' . $lang->global['nopermission'] . '</error>');
  }

  $torrentid = 0 + $_POST['torrentid'];
  $userid = 0 + $CURUSER['id'];
  if ((!is_valid_id ($torrentid) OR !is_valid_id ($userid)))
  {
    exit ('<error>' . $lang->global['notorrentid'] . '</error>');
  }

  $res = mysql_query ('SELECT owner FROM torrents WHERE id = \'' . $torrentid . '\'');
  $row = mysql_fetch_assoc ($res);
  if (((!$row OR empty ($row)) OR !$row['owner']))
  {
    exit ('<error>' . $lang->global['notorrentid'] . '</error>');
  }

  if ($row['owner'] == $userid)
  {
    $lang->load ('takewhatever');
    exit ('<error>' . $lang->takewhatever['cantthankowntorrent'] . '</error>');
  }

  if (isset ($_POST['removethanks']))
  {
    mysql_query ('DELETE FROM ts_thanks WHERE tid = \'' . $torrentid . '\' AND uid = \'' . $userid . '\'');
    if (mysql_affected_rows ())
    {
      include INC_PATH . '/readconfig_kps.php';
      kps ('-', $kpsthanks, $userid);
    }

    show_thanks (true);
  }

  $tsql = mysql_query ('SELECT tid FROM ts_thanks WHERE tid=' . sqlesc ($torrentid) . ' AND uid=' . sqlesc ($userid));
  if (0 < mysql_num_rows ($tsql))
  {
    $lang->load ('takewhatever');
    exit ('<error>' . $lang->takewhatever['alreadythanked'] . '</error>');
  }

  mysql_query ('INSERT INTO ts_thanks VALUES (\'' . $torrentid . '\', \'' . $userid . '\')');
  if (mysql_affected_rows ())
  {
    include INC_PATH . '/readconfig_kps.php';
    kps ('+', $kpsthanks, $userid);
    show_thanks ();
    return 1;
  }

  exit ('<error>' . $lang->global['error'] . '</error>');
?>
