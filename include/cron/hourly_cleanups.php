<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('IN_CRON'))
  {
    exit ();
  }

  mysql_query ('DELETE FROM ' . TSF_PREFIX . 'threadsread WHERE dateline < \'' . (TIMENOW - 604800) . '\'');
  ++$CQueryCount;
  mysql_query ('DELETE FROM ts_sessions WHERE lastactivity < \'' . (TIMENOW - TS_TIMEOUT) . '\'');
  ++$CQueryCount;
  $deadtime = deadtime ();
  mysql_query ('' . 'DELETE FROM peers WHERE last_action < FROM_UNIXTIME(' . $deadtime . ')');
  ++$CQueryCount;
  mysql_query ('' . 'UPDATE snatched SET seeder=\'no\' WHERE seeder=\'yes\' AND last_action < FROM_UNIXTIME(' . $deadtime . ')');
  ++$CQueryCount;
  unset ($deadtime);
  $cut = TIMENOW - $max_dead_torrent_time * 24 * 60 * 60;
  mysql_query ('' . 'UPDATE torrents SET visible=\'no\' WHERE visible=\'yes\' AND UNIX_TIMESTAMP(last_action) < ' . $cut . ' AND ts_external = \'no\'');
  ++$CQueryCount;
  unset ($cut);
  $torrents = array ();
  $fields = explode (':', 'comments:leechers:seeders:times_completed');
  $query = mysql_query ('SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder');
  ++$CQueryCount;
  while ($row = mysql_fetch_assoc ($query))
  {
    if ($row['seeder'] == 'yes')
    {
      $key = 'seeders';
    }
    else
    {
      $key = 'leechers';
    }

    $torrents[$row['torrent']][$key] = $row['c'];
  }

  $query = mysql_query ('SELECT torrentid, COUNT(*) as s FROM snatched WHERE 	finished=\'yes\' GROUP BY torrentid');
  ++$CQueryCount;
  while ($row = mysql_fetch_assoc ($query))
  {
    $torrents[$row['torrentid']]['times_completed'] = $row['s'];
  }

  $query = mysql_query ('SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent');
  ++$CQueryCount;
  while ($row = mysql_fetch_assoc ($query))
  {
    $torrents[$row['torrent']]['comments'] = $row['c'];
  }

  $query = mysql_query ('SELECT id, seeders, leechers, comments, times_completed, ts_external FROM torrents');
  ++$CQueryCount;
  while ($row = mysql_fetch_assoc ($query))
  {
    $id = $row['id'];
    $torr = $torrents[$id];
    foreach ($fields as $field)
    {
      if (!isset ($torr[$field]))
      {
        $torr[$field] = 0;
        continue;
      }
    }

    $update = array ();
    foreach ($fields as $field)
    {
      if ((($torr[$field] != $row[$field] AND $row['ts_external'] == 'no') AND $row[$field] != 'times_completed'))
      {
        $update[] = $field . ' = ' . $torr[$field];
        continue;
      }
    }

    if (count ($update))
    {
      mysql_query ('UPDATE torrents SET ' . implode (',', $update) . ('' . ' WHERE id = ' . $id));
      ++$CQueryCount;
      continue;
    }
  }

  unset ($torrents);
  unset ($fields);
  unset ($torr);
  unset ($update);
  unset ($key);
  readconfig (array ('MAIN', 'TWEAK'));
  require INC_PATH . '/ts_cache.php';
  update_cache ('indexstats');
?>
