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

  require INC_PATH . '/functions_pm.php';
  clearstatcache ();
  extract (unserialize (file_get_contents (CONFIG_DIR . 'HITRUN')), EXTR_PREFIX_SAME, 'wddx');
  if (($Enabled == 'yes' AND (0 < $MinSeedTime OR 0 < $MinRatio)))
  {
    $Queries = array ();
    $Queries[] = 's.finished = \'yes\'';
    $Queries[] = 's.seeder = \'no\'';
    $Queries[] = 't.banned = \'no\'';
    $Queries[] = 'u.enabled = \'yes\'';
    if ($HRSkipUsergroups)
    {
      $Queries[] = '' . 'u.usergroup NOT IN (0,' . $HRSkipUsergroups . ')';
    }

    if (0 < $MinSeedTime)
    {
      $Queries[] = '' . 's.seedtime < \'(' . $MinSeedTime . ' * 60 * 60)\'';
    }

    if (0 < $MinRatio)
    {
      $Queries[] = '' . 's.uploaded / s.downloaded < \'' . $MinRatio . '\'';
    }

    if (0 < $MinFinishDate)
    {
      $Queries[] = '' . 'UNIX_TIMESTAMP(s.completedat) > \'' . $MinFinishDate . '\'';
    }

    $WarnUsers = array ();
    $query = mysql_query ('SELECT s.torrentid, s.userid, s.seedtime, t.name, u.username FROM snatched s INNER JOIN torrents t ON (s.torrentid=t.id) INNER JOIN users u ON (s.userid=u.id) WHERE ' . implode (' AND ', $Queries));
    ++$CQueryCount;
    if (0 < mysql_num_rows ($query))
    {
      while ($HR = mysql_fetch_assoc ($query))
      {
        if (!in_array ($HR['userid'], $WarnUsers))
        {
          send_pm ($HR['userid'], sprintf ($lang->cronjobs['hr_warn_message'], $HR['username'], '[URL=details.php?id=' . $HR['torrentid'] . ']' . htmlspecialchars ($HR['name']) . '[/URL]', (0 < $HR['seedtime'] ? floor ($HR['seedtime'] / (60 * 60)) : 0), $MinSeedTime, '[URL=download.php?id=' . $HR['torrentid'] . ']' . htmlspecialchars ($HR['name']) . '[/URL]', $MinSeedTime), $lang->cronjobs['hr_warn_subject']);
          ++$CQueryCount;
          $WarnUsers[] = $HR['userid'];
          continue;
        }
      }

      if (count ($WarnUsers))
      {
        mysql_query ('UPDATE users SET timeswarned = timeswarned + 1 WHERE id IN (' . implode (',', $WarnUsers) . ')');
        ++$CQueryCount;
        unset ($WarnUsers);
      }
    }
  }

?>
