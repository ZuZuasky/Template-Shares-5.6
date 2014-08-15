<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function checkconnect ($host, $port)
  {
    global $checkconnectable;
    if ((!$checkconnectable OR $checkconnectable == 'no'))
    {
      return 'yes';
    }

    if ($fp = @fsockopen ($host, $port, $errno, $errstr, 5))
    {
      fclose ($fp);
      return 'yes';
    }

    return 'no';
  }

  function stop ($msg)
  {
    global $db;
    if ($db)
    {
      mysql_close ($db);
    }

    header ('Content-Type: text/plain');
    header ('Pragma: no-cache');
    exit ('d14:failure reason' . strlen ($msg) . ':' . $msg . 'e');
  }

  function sqlesc ($value)
  {
    if (get_magic_quotes_gpc ())
    {
      $value = stripslashes ($value);
    }

    if (!is_numeric ($value))
    {
      $value = '\'' . mysql_real_escape_string ($value) . '\'';
    }

    return $value;
  }

  function send_action ($actionmessage, $resetpasskey = false)
  {
    global $announce_actions;
    global $Tid;
    global $Result;
    global $ip;
    global $passkey;
    if ($announce_actions != 'yes')
    {
      return null;
    }

    @mysql_query ('INSERT DELAYED INTO announce_actions (torrentid, userid, ip, passkey, actionmessage, actiontime) VALUES (' . @implode (',', @array_map ('sqlesc', array ($Tid, $Result['userid'], $ip, $passkey, $actionmessage, $_SERVER['REQUEST_TIME']))) . ')');
    if ($resetpasskey)
    {
      @mysql_query ('UPDATE users SET passkey = \'\' WHERE id = ' . @sqlesc ($Uid) . ' AND passkey = ' . @sqlesc ($passkey));
    }

  }

  error_reporting (E_ALL & ~E_NOTICE);
  set_magic_quotes_runtime (0);
  ini_set ('magic_quotes_sybase', 0);
  define ('IN_ANNOUNCE', true);
  define ('TSDIR', dirname (__FILE__));
  require TSDIR . '/include/config_announce.php';
  require TSDIR . '/include/languages/' . $defaultlanguage . '/announce.lang.php';
  $compact = (isset ($_GET['compact']) ? 0 + $_GET['compact'] : 0);
  $peer_id = (isset ($_GET['peer_id']) ? $_GET['peer_id'] : '');
  $port = (isset ($_GET['port']) ? 0 + $_GET['port'] : '');
  $event = (isset ($_GET['event']) ? $_GET['event'] : '');
  $downloaded = (isset ($_GET['downloaded']) ? 0 + $_GET['downloaded'] : '');
  $uploaded = (isset ($_GET['uploaded']) ? 0 + $_GET['uploaded'] : '');
  $left = (isset ($_GET['left']) ? 0 + $_GET['left'] : '');
  $numwant = min ((isset ($_GET['numwant']) ? 0 + $_GET['numwant'] : (isset ($_GET['num_want']) ? 0 + $_GET['num_want'] : (isset ($_GET['num want']) ? 0 + $_GET['num want'] : 50))), 50);
  $update_user = $update_torrent = $update_snatched = array ();
  if (strpos ($_GET['passkey'], '?'))
  {
    $chop = $_GET['passkey'];
    $delim = '?';
    $half = strtok ($chop, $delim);
    $onehalf = array ();
    while (is_string ($half))
    {
      if ($half)
      {
        $onehalf[] = $half;
      }

      $half = strtok ($delim);
    }

    unset ($chop);
    unset ($delim);
    unset ($half);
    $_GET['passkey'] = $onehalf[0];
    $delim2 = '=';
    $hash = strtok ($onehalf[1], $delim2);
    $onehash = array ();
    while (is_string ($hash))
    {
      if ($hash)
      {
        $onehash[] = $hash;
      }

      $hash = strtok ($delim2);
    }

    $_GET['info_hash'] = $onehash[1];
    unset ($onehalf);
    unset ($delim2);
    unset ($hash);
    unset ($onehash);
  }

  $passkey = (isset ($_GET['passkey']) ? $_GET['passkey'] : '');
  $info_hash = (isset ($_GET['info_hash']) ? $_GET['info_hash'] : '');
  if (get_magic_quotes_gpc ())
  {
    $info_hash = stripslashes ($info_hash);
    $peer_id = stripslashes ($peer_id);
  }

  if (((((strlen ($passkey) === 32 AND strlen ($info_hash) === 20) AND strlen ($peer_id) === 20) AND 0 < $port) AND $port < 65535))
  {
    if (($passkey AND $passkey == 'tssespecialtorrentv1byxamsep2007'))
    {
      stop ($l['registerfirst'] . $BASEURL . '/signup.php');
    }
  }
  else
  {
    stop ($l['error']);
  }

  $ip = htmlspecialchars ($_SERVER['REMOTE_ADDR']);
  $agent = htmlspecialchars ($_SERVER['HTTP_USER_AGENT']);
  $seeder = ($left == 0 ? 'yes' : 'no');
  if (($db = @mysql_connect ($mysql_host, $mysql_user, $mysql_pass) AND $select = @mysql_select_db ($mysql_db, $db)))
  {
  }
  else
  {
    stop ($l['cerror']);
  }

  ($Query = mysql_query ('
					SELECT t.id as tid, t.name, t.category, t.size, t.added, t.visible, t.banned, t.free, t.silver, t.doubleupload,
                    u.id as userid, u.enabled, u.uploaded, u.downloaded, u.usergroup, u.birthday, u.ip,
					g.isbanned, g.candownload, g.canviewviptorrents, g.isvipgroup, g.canfreeleech, g.waitlimit, g.slotlimit,
					c.vip as isviptorrent
 					FROM torrents t
					INNER JOIN users u ON (u.passkey = ' . sqlesc ($passkey) . ')
					INNER JOIN usergroups g ON (u.usergroup = g.gid)
					LEFT JOIN categories c ON (t.category=c.id)
					WHERE (t.info_hash = ' . sqlesc ($info_hash) . ' OR t.info_hash = ' . sqlesc (preg_replace ('' . '/ *$/s', '', $info_hash)) . ')
					LIMIT 1') OR stop ($l['sqlerror'] . ' TU1'));
  if ((((!$Result = mysql_fetch_assoc ($Query) OR !$Tid = $Result['tid']) OR $Result['enabled'] != 'yes') OR !$Result['userid']))
  {
    stop ($l['tuerror']);
  }

  if (($checkip == 'yes' AND $Result['ip'] != $ip))
  {
    stop ($l['invalidip']);
  }

  if (($detectbrowsercheats == 'yes' AND ((isset ($_SERVER['HTTP_COOKIE']) AND isset ($_SERVER['HTTP_ACCEPT_LANGUAGE'])) AND isset ($_SERVER['HTTP_ACCEPT_CHARSET']))))
  {
    send_action ('This user tried to cheat with a browser!', true);
    stop ($l['invalidagent']);
  }

  if ($bannedclientdetect == 'yes')
  {
    $Stop = false;
    if (((isset ($_SERVER['HTTP_ACCEPT']) AND 'text/html, */*' == $_SERVER['HTTP_ACCEPT']) OR ((isset ($_SERVER['HTTP_CONNECTION']) AND 'Close' == $_SERVER['HTTP_CONNECTION']) AND 'gzip, deflate' != $_SERVER['HTTP_ACCEPT_ENCODING'])))
    {
      $Stop = true;
    }
    else
    {
      if (((isset ($_SERVER['HTTP_ACCEPT']) AND $_SERVER['HTTP_ACCEPT'] == 'text/html, */*') AND $_SERVER['HTTP_ACCEPT_ENCODING'] == 'identity'))
      {
        $Stop = true;
      }
      else
      {
        $userclient = substr ($peer_id, 0, 8);
        $allowed_clients = explode (',', $allowed_clients);
        if (!in_array ($userclient, $allowed_clients))
        {
          $Stop = true;
        }
      }
    }

    if ($Stop === true)
    {
      stop ($l['bannedclient']);
    }
  }

  $fields = 'peer_id, ip, port, uploaded, downloaded, seeder, last_action, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_action)) AS announcetime, UNIX_TIMESTAMP(prev_action) AS prevts, connectable, userid';
  unset ($self);
  $gp_eq = ($nc == 'yes' ? ' AND connectable = \'yes\'' : '');
  $wantseeds = ($seeder == 'yes' ? ' AND seeder = \'no\'' : '');
  if ($compact != 1)
  {
    $resp = 'd8:intervali' . $announce_interval . ($privatetrackerpatch == 'yes' ? 'e7:privatei1' : '') . 'e5:peersl';
  }
  else
  {
    $resp = 'd8:intervali' . $announce_interval . 'e5:peers';
  }

  $peer = array ();
  $peer_num = 0;
  $query_peers = mysql_query ('SELECT ' . $fields . ' FROM peers WHERE torrent = ' . $Tid . $gp_eq . $wantseeds . ' ORDER BY last_action DESC LIMIT ' . $numwant);
  if ($compact != 1)
  {
    while ($result_peers = mysql_fetch_assoc ($query_peers))
    {
      if ($result_peers['userid'] === $Result['userid'])
      {
        $self = $result_peers;
        continue;
      }

      $resp .= 'd2:ip' . strlen ($result_peers['ip']) . ':' . $result_peers['ip'] . '4:porti' . $result_peers['port'] . 'ee';
    }

    $resp .= 'ee';
  }
  else
  {
    while ($result_peers = mysql_fetch_assoc ($query_peers))
    {
      $peer_ip = explode ('.', $result_peers['ip']);
      $peer_ip = pack ('C*', $peer_ip[0], $peer_ip[1], $peer_ip[2], $peer_ip[3]);
      $peer_port = pack ('n*', (int)$result_peers['port']);
      $time = intval (time () % 7680 / 60);
      if ($left == 0)
      {
        $time += 128;
      }

      $time = pack ('C', $time);
      $peer[] = $time . $peer_ip . $peer_port;
      ++$peer_num;
    }

    $o = '';
    $i = 0;
    while ($i < $peer_num)
    {
      $o .= substr ($peer[$i], 1, 6);
      ++$i;
    }

    $resp .= strlen ($o) . ':' . $o . 'e';
    unset ($peer);
  }

  $selfwhere = 'torrent = ' . $Tid . ' AND userid = ' . $Result['userid'];
  if (!isset ($self))
  {
    $Query = mysql_query ('SELECT ' . $fields . ' FROM peers WHERE ' . $selfwhere . ' LIMIT 1');
    if (mysql_num_rows ($Query))
    {
      $self = mysql_fetch_assoc ($Query);
    }
  }

  if (((isset ($self) AND 0 < $announce_wait) AND $_SERVER['REQUEST_TIME'] - $announce_wait < $self['prevts']))
  {
    stop ($l['antispam'] . $announce_wait);
  }

  if (!isset ($self))
  {
    if (($Result['canviewviptorrents'] != 'yes' AND $Result['isviptorrent'] == 'yes'))
    {
      send_action ('This user tried to download a VIP torrent!', true);
      stop ($l['dlerror']);
    }
    else
    {
      if ($Result['candownload'] != 'yes')
      {
        stop ($l['dlerror']);
      }
    }

    if (($Result['isvipgroup'] != 'yes' AND ($waitsystem == 'yes' OR $maxdlsystem == 'yes')))
    {
      $gigs = $Result['uploaded'] / (1024 * 1024 * 1024);
      $ratio = (0 < $Result['downloaded'] ? $Result['uploaded'] / $Result['downloaded'] : 0);
      if ($waitsystem == 'yes')
      {
        $elapsed = floor (($_SERVER['REQUEST_TIME'] - strtotime ($Result['added'])) / 3600);
        if ($waitsystemtype == 1)
        {
          if (($ratio < $ratio1 OR $gigs < $upload1))
          {
            $wait = $delay1;
          }
          else
          {
            if (($ratio < $ratio2 OR $gigs < $upload2))
            {
              $wait = $delay2;
            }
            else
            {
              if (($ratio < $ratio3 OR $gigs < $upload3))
              {
                $wait = $delay3;
              }
              else
              {
                if (($ratio < $ratio4 OR $gigs < $upload4))
                {
                  $wait = $delay4;
                }
                else
                {
                  $wait = 0;
                }
              }
            }
          }
        }
        else
        {
          $wait = $Result['waitlimit'];
        }

        if (($elapsed < $wait AND 0 < $wait))
        {
          stop ($l['werror'] . ' (' . ($wait - $elapsed) . $l['hour'] . ')');
        }
      }

      if ($maxdlsystem == 'yes')
      {
        if ($waitsystemtype == 1)
        {
          if (($ratio < $ratio5 OR $gigs < $upload5))
          {
            $max = $slot1;
          }
          else
          {
            if (($ratio < $ratio6 OR $gigs < $upload6))
            {
              $max = $slot2;
            }
            else
            {
              if (($ratio < $ratio7 OR $gigs < $upload7))
              {
                $max = $slot3;
              }
              else
              {
                if (($ratio < $ratio8 OR $gigs < $upload8))
                {
                  $max = $slot4;
                }
                else
                {
                  $max = 0;
                }
              }
            }
          }
        }
        else
        {
          $max = $Result['slotlimit'];
        }

        if (0 < $max)
        {
          ($res = @mysql_query ('SELECT userid FROM peers WHERE userid = \'' . $Result['userid'] . '\' AND seeder = \'no\' LIMIT 1') OR stop ($l['sqlerror'] . ' P1'));
          if ($max < mysql_num_rows ($res))
          {
            stop ($l['merror'] . $max);
          }
        }
      }
    }
  }
  else
  {
    require_once TSDIR . '/' . $cache . '/freeleech.php';
    $TIMENOW = date ('Y-m-d H:i:s');
    if (($__F_START < $TIMENOW AND $TIMENOW < $__F_END))
    {
      switch ($__FLSTYPE)
      {
        case 'freeleech':
        {
          $Result['free'] = 'yes';
          $Result['canfreeleech'] = 'yes';
          break;
        }

        case 'silverleech':
        {
          $Result['silver'] = 'yes';
          break;
        }

        case 'doubleupload':
        {
          $Result['doubleupload'] = 'yes';
        }
      }
    }

    unset ($__F_START);
    unset ($__F_END);
    unset ($__FLSTYPE);
    unset ($TIMENOW);
    if ((($bdayreward == 'yes' AND $bdayrewardtype) AND $Result['birthday']))
    {
      $curuserbday = explode ('-', $Result['birthday']);
      if (date ('j-n') === $curuserbday[0] . '-' . $curuserbday[1])
      {
        switch ($bdayrewardtype)
        {
          case 'freeleech':
          {
            $Result['free'] = 'yes';
            $Result['canfreeleech'] = 'yes';
            break;
          }

          case 'silverleech':
          {
            $Result['silver'] = 'yes';
            break;
          }

          case 'doubleupload':
          {
            $Result['doubleupload'] = 'yes';
          }
        }
      }
    }

    unset ($curuserbday);
    unset ($bdayreward);
    unset ($bdayrewardtype);
    $realupload = max (0, $uploaded - $self['uploaded']);
    $upthis = ($Result['doubleupload'] == 'yes' ? $realupload * 2 : $realupload);
    $downthis = max (0, $downloaded - $self['downloaded']);
    $upspeed = (0 < $realupload ? $realupload / $self['announcetime'] : 0);
    $downspeed = (0 < $downthis ? $downthis / $self['announcetime'] : 0);
    $announcetime = ($self['seeder'] == 'yes' ? 'seedtime = seedtime + \'' . $self['announcetime'] . '\'' : 'leechtime = leechtime + \'' . $self['announcetime'] . '\'');
    if ((0 < $upthis OR 0 < $downthis))
    {
      if ((536870912 < $realupload AND $aggressivecheat == 'yes'))
      {
        send_action ('There was no Leecher on this torrent however this user uploaded ' . $realupload . ' bytes, which might be a cheat attempt with a cheat software such as Ratio Maker, Ratio Faker etc..');
      }

      $dled = ($Result['silver'] == 'yes' ? $downthis / 2 : $downthis);
      if (0 < $upthis)
      {
        $update_user[] = 'uploaded = uploaded + \'' . $upthis . '\'';
      }

      if (((0 < $dled AND $Result['free'] != 'yes') AND $Result['canfreeleech'] != 'yes'))
      {
        $update_user[] = 'downloaded = downloaded + \'' . $dled . '\'';
      }
    }

    if ($max_rate < $upspeed)
    {
      (mysql_query ('INSERT DELAYED INTO cheat_attempts (added, uid, agent, transfer_rate, beforeup, upthis, timediff, ip, torrentid) VALUES(NOW(), ' . $Result['userid'] . ', ' . sqlesc ($agent) . ', ' . sqlesc ($upspeed) . ', ' . sqlesc ($Result['uploaded']) . ', ' . sqlesc ($realupload) . ', ' . sqlesc ($self['announcetime']) . ', ' . sqlesc ($ip) . ', ' . sqlesc ($Tid) . ')') OR stop ($l['sqlerror'] . ' C1'));
    }
  }

  if ($event == 'stopped')
  {
    if (isset ($self))
    {
      if ($snatchmod == 'yes')
      {
        mysql_query ('UPDATE snatched seeder = \'no\' WHERE torrentid = \'' . $Tid . '\' AND userid = \'' . $Result['userid'] . '\'');
      }

      mysql_query ('DELETE FROM peers WHERE ' . $selfwhere);
      if (mysql_affected_rows ())
      {
        $update_torrent[] = ($self['seeder'] == 'yes' ? 'seeders = IF(seeders > 0, seeders - 1, 0)' : 'leechers = IF(leechers > 0, leechers - 1, 0)');
      }
    }
  }
  else
  {
    if ($event == 'completed')
    {
      if ($snatchmod == 'yes')
      {
        $update_snatched[] = 'finished = \'yes\'';
        $update_snatched[] = 'completedat = NOW()';
      }

      $update_torrent[] = 'times_completed = times_completed + 1';
    }

    if (isset ($self))
    {
      $connectable = ($self['connectable'] == 'yes' ? 'yes' : checkconnect ($ip, $port));
      if ($snatchmod == 'yes')
      {
        $update_snatched[] = '' . 'seeder = \'' . $seeder . '\'';
        $update_snatched[] = '' . 'connectable = \'' . $connectable . '\'';
        $update_snatched[] = 'last_action = NOW()';
        $update_snatched[] = '' . 'port = \'' . $port . '\'';
        $update_snatched[] = 'agent = ' . sqlesc ($agent);
        $update_snatched[] = $announcetime;
        if (0 < $upspeed)
        {
          $update_snatched[] = '' . 'upspeed = \'' . $upspeed . '\'';
        }

        if (0 < $downspeed)
        {
          $update_snatched[] = '' . 'downspeed = \'' . $downspeed . '\'';
        }

        $update_snatched[] = 'ip = ' . sqlesc ($ip);
        $update_snatched[] = '' . 'uploaded = uploaded + \'' . $realupload . '\'';
        $update_snatched[] = '' . 'downloaded = downloaded + \'' . $downthis . '\'';
        $update_snatched[] = '' . 'to_go = \'' . $left . '\'';
      }

      mysql_query ('' . 'UPDATE peers SET uploaded = ' . $uploaded . ', downloaded = ' . $downloaded . ', to_go = ' . $left . ', last_action = NOW(), prev_action = \'' . $self['last_action'] . ('' . '\', seeder = \'' . $seeder . '\'') . (($seeder == 'yes' AND $self['seeder'] != $seeder) ? ', finishedat = ' . $_SERVER['REQUEST_TIME'] : '') . ('' . ' WHERE ' . $selfwhere));
      if ((mysql_affected_rows () AND $self['seeder'] != $seeder))
      {
        if ($seeder == 'yes')
        {
          $update_torrent[] = 'seeders = seeders + 1';
          $update_torrent[] = 'leechers = IF(leechers > 0, leechers - 1, 0)';
        }
        else
        {
          $update_torrent[] = 'leechers = leechers + 1';
          $update_torrent[] = 'seeders = IF(seeders > 0, seeders - 1, 0)';
        }
      }
    }
    else
    {
      if (in_array ($port, array (21, 22, 411, 412, 413, 6881, 6882, 6883, 6884, 6885, 6886, 6887, 6889, 1214, 6346, 6347, 4662, 6699, 65535)))
      {
        stop ($l['invalidport']);
      }
      else
      {
        $connectable = checkconnect ($ip, $port);
        if (($connectable == 'no' AND $nc == 'yes'))
        {
          stop ($l['conerror']);
        }
      }

      if ($snatchmod == 'yes')
      {
        $res = @mysql_query ('SELECT 1 FROM snatched WHERE torrentid = \'' . $Tid . '\' AND userid = \'' . $Result['userid'] . '\'');
        if (@mysql_num_rows ($res) == 0)
        {
          mysql_query ('' . 'INSERT DELAYED INTO snatched (torrentid, userid, port, startdat, last_action, agent, ip) VALUES (' . $Tid . ', ' . $Result['userid'] . ('' . ', ' . $port . ', NOW(), NOW(), ') . sqlesc ($agent) . ', ' . sqlesc ($ip) . ')');
        }
      }

      $ret = mysql_query ('' . 'INSERT DELAYED INTO peers (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, uploadoffset, downloadoffset, passkey) VALUES (\'' . $connectable . '\', ' . $Tid . ', ' . sqlesc ($peer_id) . ', ' . sqlesc ($ip) . ('' . ', ' . $port . ', ' . $uploaded . ', ' . $downloaded . ', ' . $left . ', NOW(), NOW(), \'' . $seeder . '\', ') . $Result['userid'] . ', ' . sqlesc ($agent) . ('' . ', ' . $uploaded . ', ' . $downloaded . ', ') . sqlesc ($passkey) . ')');
      if ($ret)
      {
        $update_torrent[] = ($seeder == 'yes' ? 'seeders = seeders + 1' : 'leechers = leechers + 1');
      }
    }
  }

  if ((((0 < $kpsseed AND $seeder == 'yes') AND $announce_interval - 10 < $self['announcetime']) AND ($bonus == 'enable' OR $bonus == 'disablesave')))
  {
    $update_user[] = 'seedbonus = seedbonus + \'' . $kpsseed . '\'';
  }

  if ($seeder == 'yes')
  {
    if (($Result['banned'] != 'yes' AND $Result['visible'] == 'no'))
    {
      $update_torrent[] = 'visible = \'yes\'';
    }

    $update_torrent[] = 'last_action = NOW()';
  }

  if (count ($update_torrent))
  {
    mysql_query ('UPDATE torrents SET ' . implode (',', $update_torrent) . ' WHERE id = \'' . $Tid . '\'');
    unset ($update_torrent);
  }

  if (count ($update_user))
  {
    mysql_query ('UPDATE users SET ' . implode (',', $update_user) . ' WHERE id = \'' . $Result['userid'] . '\'');
    unset ($update_user);
  }

  if (count ($update_snatched))
  {
    mysql_query ('UPDATE snatched SET ' . implode (',', $update_snatched) . ' WHERE torrentid = \'' . $Tid . '\' AND userid = \'' . $Result['userid'] . '\'');
    unset ($update_snatched);
  }

  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('Content-type: text/html; charset=' . $charset);
  if (((($compact != 1 AND isset ($_SERVER['HTTP_ACCEPT_ENCODING'])) AND $_SERVER['HTTP_ACCEPT_ENCODING'] == 'gzip') AND $gzipcompress == 'yes'))
  {
    header ('Content-Encoding: gzip');
    echo gzencode ($resp, 9, FORCE_GZIP);
  }
  else
  {
    if ($compact)
    {
      header ('Content-Type: text/plain');
      echo $resp;
    }
    else
    {
      echo $resp;
    }
  }

  mysql_close ($db);
  exit ();
?>
