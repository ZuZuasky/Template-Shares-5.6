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
  mysql_query ('DELETE FROM shoutbox WHERE date < \'' . (TIMENOW - 604800) . '\'');
  ++$CQueryCount;
  mysql_query ('DELETE FROM loginattempts WHERE banned=\'no\' AND UNIX_TIMESTAMP(added) < \'' . (TIMENOW - 86400) . '\'');
  ++$CQueryCount;
  mysql_query ('DELETE FROM invites WHERE UNIX_TIMESTAMP(time_invited) < \'' . (TIMENOW - 172800) . '\'');
  ++$CQueryCount;
  mysql_query ('DELETE FROM ts_social_group_members WHERE type=\'inviteonly\' AND joined < \'' . (TIMENOW - 172800) . '\'');
  ++$CQueryCount;
  $query = mysql_query ('SELECT id, added FROM funds');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $nowmonth = date ('m');
    $dfid = array ();
    while ($funds = mysql_fetch_assoc ($query))
    {
      $funds['added'] = @explode ('-', $funds['added']);
      if ($funds['added'][1] != $nowmonth)
      {
        $dfid[] = $funds['id'];
        continue;
      }
    }

    if (count ($dfid))
    {
      mysql_query ('DELETE FROM funds WHERE id IN (0, ' . implode (',', $dfid) . ')');
      ++$CQueryCount;
    }

    unset ($nowmonth);
    unset ($dfid);
    unset ($funds);
  }

  $query = mysql_query ('SELECT id FROM users WHERE status = \'pending\' AND UNIX_TIMESTAMP(added) < \'' . (TIMENOW - 172800) . '\'');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $deleteuncousers = '';
    while ($arr = mysql_fetch_assoc ($query))
    {
      $deleteuncousers .= ',' . intval ($arr['id']);
    }

    if (!empty ($deleteuncousers))
    {
      mysql_query ('DELETE FROM users WHERE id IN (0' . $deleteuncousers . ')');
      ++$CQueryCount;
    }

    unset ($deleteuncousers);
    unset ($arr);
  }

  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE warned=\'yes\' AND warneduntil < NOW() AND enabled=\'yes\'');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET warned = \'no\', timeswarned = IF(timeswarned > 0, timeswarned - 1, 0), warneduntil = \'0000-00-00 00:00:00\', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Warning removed by System.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
      ++$CQueryCount;
    }

    unset ($userids);
  }

  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE enabled=\'yes\' AND timeswarned >= \'' . $ban_user_limit . '\'');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    $reason = 'Reason: Automaticly banned system. (Max. Warn Limit [' . $ban_user_limit . '] reached!';
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET enabled = \'no\', usergroup = \'' . UC_BANNED . '\', notifs = ' . sqlesc ($reason) . ', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ('' . ' - ' . $reason . '
\', modcomment) WHERE id IN (0,') . implode (',', $userids) . ')');
      ++$CQueryCount;
      savelog ('Following user(s) has been banned: ' . implode (', ', $userids) . '. ' . $reason);
      ++$CQueryCount;
    }

    unset ($userids);
    unset ($reason);
  }

  $query = mysql_query ('' . 'SELECT DISTINCT id FROM users WHERE leechwarn = \'yes\' AND uploaded / downloaded >= ' . $leechwarn_remove_ratio . ' AND enabled=\'yes\'');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET leechwarn = \'no\', leechwarnuntil = \'0000-00-00 00:00:00\', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Leech-Warning removed by System.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
      ++$CQueryCount;
      unset ($userids);
    }
  }

  $downloaded = $leechwarn_gig_limit * 1024 * 1024 * 1024;
  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE usergroup = \'' . UC_USER . ('' . '\' AND leechwarn = \'no\' AND enabled=\'yes\' AND uploaded / downloaded < ' . $leechwarn_min_ratio . ' AND downloaded >= ' . $downloaded));
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    $until = strtotime ('+' . $leechwarn_length . ' week' . (1 < $leechwarn_length ? 's' : ''));
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET leechwarn = \'yes\', leechwarnuntil = FROM_UNIXTIME(' . $until . '), modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Leech-Warned by System - Low Ratio.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
      ++$CQueryCount;
      savelog ('Following user(s) has been leech-warned: ' . implode (', ', $userids) . '. Reason: Automatic Leech-Warn System!');
      ++$CQueryCount;
      foreach ($userids as $wid)
      {
        send_pm ($wid, sprintf ($lang->cronjobs['lwarning_message'], $leechwarn_remove_ratio, $leechwarn_length), $lang->cronjobs['lwarning_subject']);
        ++$CQueryCount;
      }

      unset ($downloaded);
      unset ($userids);
      unset ($until);
      unset ($wid);
    }
  }

  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE usergroup = \'' . UC_USER . '\' AND enabled = \'yes\' AND leechwarn = \'yes\' AND leechwarnuntil < NOW()');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    $reason = 'Reason: Banned by System because of Leech-Warning expired!';
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET enabled = \'no\', usergroup = \'' . UC_BANNED . '\', notifs = ' . sqlesc ($reason) . ', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ('' . ' - ' . $reason . '
\', modcomment) WHERE id IN (0,') . implode (',', $userids) . ')');
      ++$CQueryCount;
      savelog ('Following user(s) has been banned: ' . implode (', ', $userids) . '. ' . $reason);
      ++$CQueryCount;
    }

    unset ($reason);
    unset ($userids);
  }

  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE donor = \'yes\' AND donoruntil < NOW() AND donoruntil <> \'0000-00-00 00:00:00\' AND enabled = \'yes\'');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET usergroup = IF(usergroup < ' . UC_UPLOADER . ', ' . UC_POWER_USER . ', ' . UC_USER . '), donor = \'no\', donoruntil = \'0000-00-00 00:00:00\', title=\'\', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Donor status removed by -AutoSystem.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
      ++$CQueryCount;
      savelog ('Following user(s) has been demoted: ' . implode (', ', $userids) . '. Reason: Donor status has been expired!');
      ++$CQueryCount;
      foreach ($userids as $rid)
      {
        send_pm ($rid, $lang->cronjobs['donor_message'], $lang->cronjobs['donor_subject']);
        ++$CQueryCount;
      }
    }

    unset ($userids);
    unset ($rid);
  }

  $query = mysql_query ('SELECT v.userid as id, v.old_gid, u.modcomment, g.gid FROM ts_auto_vip v LEFT JOIN users u ON (v.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE v.vip_until < NOW() AND g.cansettingspanel != \'yes\' AND g.canstaffpanel != \'yes\' AND g.issupermod != \'yes\' AND g.isforummod != \'yes\' AND u.enabled=\'yes\' AND u.usergroup != ' . UC_BANNED . '');
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $RemoveVStatus = array ();
    while ($arr = mysql_fetch_assoc ($query))
    {
      send_pm ($arr['id'], $lang->cronjobs['vip_message'], $lang->cronjobs['vip_subject']);
      ++$CQueryCount;
      $RemoveVStatus[] = $arr['id'];
      mysql_query ('UPDATE users SET usergroup = \'' . ($arr['old_gid'] ? $arr['old_gid'] : UC_POWER_USER) . '\', modcomment = ' . sqlesc (gmdate ('Y-m-d') . ' - VIP status removed by -AutoSystem.
' . $arr['modcomment']) . ' WHERE id = ' . sqlesc ($arr['id']));
      ++$CQueryCount;
      mysql_query ('DELETE FROM ts_auto_vip WHERE userid = ' . sqlesc ($arr['id']));
      ++$CQueryCount;
    }

    savelog ('Following user(s) has been demoted: ' . implode (', ', $RemoveVStatus) . '. Reason: KPS VIP status has been expired!');
    ++$CQueryCount;
    unset ($RemoveVStatus);
  }

  if (0 < intval ($promote_gig_limit))
  {
    $limit = $promote_gig_limit * 1024 * 1024 * 1024;
    $maxdt = TIMENOW - 86400 * $promote_min_reg_days;
    $query = mysql_query ('SELECT DISTINCT id FROM users WHERE usergroup = \'' . UC_USER . ('' . '\' AND enabled = \'yes\' AND uploaded >= ' . $limit . ' AND uploaded / downloaded >= ' . $promote_min_ratio . ' AND UNIX_TIMESTAMP(added) < ' . $maxdt));
    ++$CQueryCount;
    if (0 < mysql_num_rows ($query))
    {
      $userids = array ();
      while ($arr = mysql_fetch_assoc ($query))
      {
        $userids[] = $arr['id'];
      }

      if (count ($userids))
      {
        mysql_query ('UPDATE users SET usergroup = \'' . UC_POWER_USER . '\', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Promoted to POWER USER by -AutoSystem.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
        ++$CQueryCount;
        savelog ('Following user(s) has been promoted to Power User Class: ' . implode (', ', $userids) . '. Reason: Automatic Promotion System!');
        ++$CQueryCount;
        foreach ($userids as $pid)
        {
          send_pm ($pid, $lang->cronjobs['promote_message'], $lang->cronjobs['promote_subject']);
          ++$CQueryCount;
        }
      }

      unset ($limit);
      unset ($maxdt);
      unset ($userids);
      unset ($pid);
    }
  }

  $query = mysql_query ('SELECT DISTINCT id FROM users WHERE usergroup = \'' . UC_POWER_USER . ('' . '\' AND uploaded / downloaded < ' . $demote_min_ratio . ' AND enabled=\'yes\''));
  ++$CQueryCount;
  if (0 < mysql_num_rows ($query))
  {
    $userids = array ();
    while ($arr = mysql_fetch_assoc ($query))
    {
      $userids[] = $arr['id'];
    }

    if (count ($userids))
    {
      mysql_query ('UPDATE users SET usergroup = \'' . UC_USER . '\', modcomment = CONCAT(\'' . gmdate ('Y-m-d') . ' - Demoted to USER by -AutoSystem.
\', modcomment) WHERE id IN (0,' . implode (',', $userids) . ')');
      ++$CQueryCount;
      savelog ('Following user(s) has been demoted to User Class: ' . implode (', ', $userids) . '. Reason: Automatic Demotion System!');
      ++$CQueryCount;
      foreach ($userids as $did)
      {
        send_pm ($did, sprintf ($lang->cronjobs['demote_message'], $demote_min_ratio), $lang->cronjobs['demote_subject']);
        ++$CQueryCount;
      }
    }

    unset ($userids);
    unset ($did);
  }

?>
