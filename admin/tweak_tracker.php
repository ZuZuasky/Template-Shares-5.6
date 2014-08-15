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

  @ini_set ('memory_limit', '20000M');
  define ('TT_VERSION', '2.1 by xam');
  if (!isset ($_GET['begin_optimization']))
  {
    stderr ('Sanity Check', 'Are you sure that you want to optimize your tracker tables now? (Please backup your database first!) <a href="' . $_this_script_ . '&begin_optimization=true">Click to Begin</a>', false);
  }

  $torrents = array ();
  ($Query = sql_query ('SELECT id FROM torrents') OR sqlerr (__FILE__, 29));
  while ($torrent = mysql_fetch_assoc ($Query))
  {
    $torrents[] = $torrent['id'];
  }

  $users = array ();
  ($Query = sql_query ('SELECT id FROM users WHERE enabled = \'yes\' AND status = \'confirmed\'') OR sqlerr (__FILE__, 36));
  while ($user = mysql_fetch_assoc ($Query))
  {
    $users[] = $user['id'];
  }

  if ((!$ValidTorrents = implode (',', $torrents) OR !$ValidUsers = implode (',', $users)))
  {
    stderr ($lang->global['error'], 'There is no torrent/user found. You must have at least one torrent/user to use this tool.');
  }

  unset ($torrents);
  unset ($users);
  ($Query = sql_query ('SELECT id FROM addedrequests WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 49));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM addedrequests WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 54));
    }
  }

  ($Query = sql_query ('SELECT id FROM announce_actions WHERE userid NOT IN (' . $ValidUsers . ') OR torrentid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 58));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM announce_actions WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 63));
    }
  }

  ($Query = sql_query ('SELECT id FROM bookmarks WHERE userid NOT IN (' . $ValidUsers . ') OR torrentid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 67));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM bookmarks WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 72));
    }
  }

  ($Query = sql_query ('SELECT id FROM cheat_attempts WHERE uid NOT IN (' . $ValidUsers . ') OR torrentid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 76));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM cheat_attempts WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 81));
    }
  }

  ($Query = sql_query ('SELECT id FROM comments WHERE user NOT IN (' . $ValidUsers . ') OR torrent NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 85));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM comments WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 90));
    }
  }

  ($Query = sql_query ('SELECT id FROM friends WHERE userid NOT IN (' . $ValidUsers . ') OR friendid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 94));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM friends WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 99));
    }
  }

  ($Query = sql_query ('SELECT id FROM invites WHERE inviter NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 103));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM invites WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 108));
    }
  }

  ($Query = sql_query ('SELECT id FROM leecherspmlog WHERE user NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 112));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM leecherspmlog WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 117));
    }
  }

  ($Query = sql_query ('SELECT id FROM messages WHERE receiver NOT IN (' . $ValidUsers . ') OR (sender != \'0\' AND sender NOT IN (' . $ValidUsers . '))') OR sqlerr (__FILE__, 121));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM messages WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 126));
    }
  }

  ($Query = sql_query ('SELECT id FROM notconnectablepmlog WHERE user NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 130));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM notconnectablepmlog WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 135));
    }
  }

  ($Query = sql_query ('SELECT id FROM pmboxes WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 139));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM pmboxes WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 144));
    }
  }

  ($Query = sql_query ('SELECT id FROM referrals WHERE uid NOT IN (' . $ValidUsers . ') OR referring NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 148));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM referrals WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 153));
    }
  }

  ($Query = sql_query ('SELECT id FROM reports WHERE addedby NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 157));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM reports WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 162));
    }
  }

  ($Query = sql_query ('SELECT id FROM requests WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 166));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM requests WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 171));
    }
  }

  ($Query = sql_query ('SELECT id FROM shoutbox WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 175));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM shoutbox WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 180));
    }
  }

  ($Query = sql_query ('SELECT id FROM snatched WHERE userid NOT IN (' . $ValidUsers . ') OR torrentid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 184));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM snatched WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 189));
    }
  }

  ($Query = sql_query ('SELECT id FROM staffmessages WHERE sender NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 193));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM staffmessages WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 198));
    }
  }

  ($Query = sql_query ('SELECT userid FROM ts_auto_vip WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 202));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_auto_vip WHERE userid = \'' . $Delete['userid'] . '\'') OR sqlerr (__FILE__, 207));
    }
  }

  ($Query = sql_query ('SELECT id FROM ts_hit_and_run WHERE userid NOT IN (' . $ValidUsers . ') OR torrentid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 211));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_hit_and_run WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 216));
    }
  }

  ($Query = sql_query ('SELECT userid FROM ts_inactivity WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 220));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_inactivity WHERE userid = \'' . $Delete['userid'] . '\'') OR sqlerr (__FILE__, 225));
    }
  }

  ($Query = sql_query ('SELECT userid FROM ts_lottery_tickets WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 229));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_lottery_tickets WHERE userid = \'' . $Delete['userid'] . '\'') OR sqlerr (__FILE__, 234));
    }
  }

  ($Query = sql_query ('SELECT userid FROM ts_profilevisitor WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 238));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_profilevisitor WHERE userid = \'' . $Delete['userid'] . '\'') OR sqlerr (__FILE__, 243));
    }
  }

  ($Query = sql_query ('SELECT visitorid FROM ts_profilevisitor WHERE visitorid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 247));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_profilevisitor WHERE visitorid = \'' . $Delete['visitorid'] . '\'') OR sqlerr (__FILE__, 252));
    }
  }

  ($Query = sql_query ('SELECT tid FROM ts_thanks WHERE tid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 256));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_thanks WHERE tid = \'' . $Delete['tid'] . '\'') OR sqlerr (__FILE__, 261));
    }
  }

  ($Query = sql_query ('SELECT uid FROM ts_thanks WHERE uid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 265));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_thanks WHERE uid = \'' . $Delete['uid'] . '\'') OR sqlerr (__FILE__, 270));
    }
  }

  ($Query = sql_query ('SELECT did FROM ts_torrents_details WHERE tid NOT IN (' . $ValidTorrents . ')') OR sqlerr (__FILE__, 274));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_torrents_details WHERE did = \'' . $Delete['did'] . '\'') OR sqlerr (__FILE__, 279));
    }
  }

  ($Query = sql_query ('SELECT userid FROM ts_u_perm WHERE userid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 283));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_u_perm WHERE userid = \'' . $Delete['userid'] . '\'') OR sqlerr (__FILE__, 288));
    }
  }

  ($Query = sql_query ('SELECT id FROM ts_visitor_messages WHERE userid NOT IN (' . $ValidUsers . ') OR visitorid NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 292));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_visitor_messages WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 297));
    }
  }

  ($Query = sql_query ('SELECT id FROM ts_watch_list WHERE userid NOT IN (' . $ValidUsers . ') OR added_by NOT IN (' . $ValidUsers . ')') OR sqlerr (__FILE__, 301));
  if (0 < mysql_num_rows ($Query))
  {
    while ($Delete = mysql_fetch_assoc ($Query))
    {
      (sql_query ('DELETE FROM ts_watch_list WHERE id = \'' . $Delete['id'] . '\'') OR sqlerr (__FILE__, 306));
    }
  }

  stderr ('Done', 'The tracker tables has been optimized. Please Optimize the tracker database in Setting Panel!');
?>
