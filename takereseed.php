<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function message ($msg, $div = 'error')
  {
    global $lang;
    stdhead ($lang->takewhatever['takereseedhead']);
    stdmsg ($lang->takewhatever['message'], $msg, true, $div);
    stdfoot ();
    exit ();
  }

  function spamcheck ($reseedid = 0, $receiver = 0, $sender = 0, $subject = 0)
  {
    $spamcheck = sql_query ('SELECT sender FROM messages WHERE sender = ' . sqlesc ($sender) . ' AND subject = ' . sqlesc ($subject) . ' AND receiver = ' . sqlesc ($receiver));
    return (0 < mysql_num_rows ($spamcheck) ? false : true);
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('TR_VERSION', '0.5 ');
  $lang->load ('takewhatever');
  $reseedid = intval ($_GET['reseedid']);
  $userid = intval ($CURUSER['id']);
  int_check (array ($reseedid, $userid), true);
  ($res = sql_query ('' . 'SELECT s.uploaded, s.downloaded, s.userid, t.name, u.username FROM snatched s INNER JOIN torrents t ON (s.torrentid=t.id) INNER JOIN users u ON (s.userid = u.id) WHERE s.finished = \'yes\' AND s.torrentid = ' . $reseedid) OR sqlerr (__FILE__, 44));
  if (mysql_num_rows ($res) == 0)
  {
    message ($lang->takewhatever['takereseednouser']);
  }

  $subject = sprintf ($lang->takewhatever['reseedsubject'], $reseedid);
  require_once INC_PATH . '/functions_pm.php';
  while ($row = mysql_fetch_assoc ($res))
  {
    $name_torrent = sqlesc ($row['name']);
    $reseedmsg = sprintf ($lang->takewhatever['reseedmsg'], $row['username'], '[URL=' . $BASEURL . '/details.php?id=' . $reseedid . ']' . $name_torrent . '[/URL]', mksize ($row['uploaded']), mksize ($row['downloaded']));
    if (spamcheck ($reseedid, $row['userid'], $userid, $subject))
    {
      send_pm ($row['userid'], $reseedmsg, $subject, $userid);
      continue;
    }
  }

  redirect ('details.php?id=' . $reseedid);
?>
