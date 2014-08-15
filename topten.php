<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function usertable ($res, $frame_caption)
  {
    global $CURUSER;
    global $lang;
    global $regdateformat;
    begin_frame ($frame_caption, true);
    begin_table (true);
    echo '<tr>
	<td class="colhead" align="center" width="5%">' . $lang->topten['rank'] . '</td>
	<td class="colhead" align="left" width="15%">' . $lang->topten['user'] . '</td>
	<td class="colhead" align="right" width="15%">' . $lang->topten['uploaded'] . '</td>
	<td class="colhead" align="right" width="15%">' . $lang->topten['ulspeed'] . '</td>
	<td class="colhead" align="right" width="15%">' . $lang->topten['downloaded'] . '</td>
	<td class="colhead" align="right" width="15%">' . $lang->topten['dlspeed'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['ratio'] . '</td>
	<td class="colhead" align="center" width="10%">' . $lang->topten['joined'] . '</td>
	</tr>';
    $num = 0;
    while ($a = mysql_fetch_array ($res))
    {
      ++$num;
      $highlight = '';
      if ($a['downloaded'])
      {
        $ratio = $a['uploaded'] / $a['downloaded'];
        $color = get_ratio_color ($ratio);
        $ratio = number_format ($ratio, 2);
        if ($color)
        {
          $ratio = '' . '<font color="' . $color . '">' . $ratio . '</font>';
        }
      }
      else
      {
        $ratio = 'Inf.';
      }

      if ($a['added'] == '0000-00-00 00:00:00')
      {
        $joindate = $lang->users['na'];
      }
      else
      {
        $joindate_date = my_datee ($regdateformat, $a['added']);
      }

      print '' . '<tr' . $highlight . '><td align="center">' . $num . '</td><td align="left"' . $highlight . '><a href="' . ts_seo ($a['userid'], $a['username']) . '"><b>' . get_user_color ($a['username'], $a['namestyle']) . '</b>' . ('' . '</td><td align="right"' . $highlight . '>') . mksize ($a['uploaded']) . ('' . '</td><td align="right"' . $highlight . '>') . mksize ($a['upspeed']) . '/s' . ('' . '</td><td align="right"' . $highlight . '>') . mksize ($a['downloaded']) . ('' . '</td><td align="right"' . $highlight . '>') . mksize ($a['downspeed']) . '/s' . ('' . '</td><td align="right"' . $highlight . '>') . $ratio . ('' . '</td><td align="center"' . $highlight . '>') . $joindate_date . '</td></tr>';
    }

    end_table ();
    end_frame ();
  }

  function _torrenttable ($res, $frame_caption)
  {
    global $lang;
    begin_frame ($frame_caption, true);
    begin_table (true);
    echo '<td class="colhead" align="center" width="5%">' . $lang->topten['rank'] . '</td>
	<td class="colhead" align="left" width="35%">' . $lang->topten['name'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['snatched'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['data'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['seeders'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['leechers'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['total'] . '</td>
	<td class="colhead" align="right" width="10%">' . $lang->topten['ratio'] . '</td>
	</tr>';
    $num = 0;
    while ($a = mysql_fetch_array ($res))
    {
      ++$num;
      if ($a['leechers'])
      {
        $r = $a['seeders'] / $a['leechers'];
        $ratio = '<font color="' . get_ratio_color ($r) . '">' . number_format ($r, 2) . '</font>';
      }
      else
      {
        $ratio = 'Inf.';
      }

      print '' . '<tr><td align="center">' . $num . '</td><td align="left"><a href="' . ts_seo ($a['id'], $a['name'], 's') . '"><b>' . cutename ($a['name'], 55) . '</b></a></td><td align="right">' . number_format ($a['times_completed']) . '</td><td align="right">' . mksize ($a['data']) . '</td><td align="right">' . number_format ($a['seeders']) . '</td><td align="right">' . number_format ($a['leechers']) . '</td><td align="right">' . ($a['leechers'] + $a['seeders']) . ('' . '</td><td align="right">' . $ratio . '</td>
');
    }

    end_table ();
    end_frame ();
  }

  function countriestable ($res, $frame_caption, $what)
  {
    global $CURUSER;
    global $pic_base_url;
    global $lang;
    begin_frame ($frame_caption, true);
    begin_table (true);
    echo '<tr>
	<td class="colhead" align="center" width="10%">' . $lang->topten['rank'] . '</td>
	<td class="colhead" align="left" width="70%">' . $lang->topten['country'] . '</td>
	<td class="colhead" align="center" width="20%">' . $what . '</td>
	</tr>';
    $num = 0;
    while ($a = mysql_fetch_array ($res))
    {
      ++$num;
      if ($what == 'Users')
      {
        $value = number_format ($a['num']);
      }
      else
      {
        if ($what == 'Uploaded')
        {
          $value = mksize ($a['ul']);
        }
        else
        {
          if ($what == 'Average')
          {
            $value = mksize ($a['ul_avg']);
          }
          else
          {
            if ($what == 'Ratio')
            {
              $value = number_format ($a['r'], 2);
            }
          }
        }
      }

      print '' . '<tr><td align="center">' . $num . '</td><td align="left"><img style="vertical-align: middle;" src="' . $pic_base_url . ('' . 'flag/' . $a['flagpic'] . '"> <b>' . $a['name'] . '</b></td><td align="center">' . $value . '</td></tr>
');
    }

    end_table ();
    end_frame ();
  }

  function peerstable ($res, $frame_caption)
  {
    global $lang;
    begin_frame ($frame_caption, true);
    begin_table (true);
    echo '<tr>
	<td class="colhead" align="center" width="10%">' . $lang->topten['rank'] . '</td>
	<td class="colhead" align="left" width="20%">' . $lang->topten['user'] . '</td>
	<td class="colhead" align="left" width="15%">' . $lang->topten['ulspeed'] . '</td>
	<td class="colhead" align="left" width="15%">' . $lang->topten['dlspeed'] . '</td>
	</tr>';
    $n = 1;
    while ($arr = mysql_fetch_array ($res))
    {
      print '' . '<tr><td' . $highlight . ' align="center">' . $n . '</td><td' . $highlight . '><a href="' . ts_seo ($arr['userid'], $arr['username']) . '"><b>' . get_user_color ($arr['username'], $arr['namestyle']) . ('' . '</b></td><td' . $highlight . '>') . mksize ($arr['uprate']) . ('' . '/s</td><td' . $highlight . '>') . mksize ($arr['downrate']) . '/s</td></tr>
';
      ++$n;
    }

    end_table ();
    end_frame ();
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('T_VERSION', 'v.1.0 ');
  if ($usergroups['cantopten'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  if ((isset ($_GET['type']) AND $_GET['type'] == 5))
  {
    header ('Location: ' . $BASEURL . '/tsf_forums/top_stats.php?from=topten');
    exit ();
  }

  include_once INC_PATH . '/functions_cache.php';
  include_once INC_PATH . '/functions_ratio.php';
  $lang->load ('topten');
  $notin = UC_STAFFLEADER . ',' . UC_SYSOP . ',' . UC_ADMINISTRATOR . ',' . UC_MODERATOR . ',' . UC_BANNED . ',' . UC_SUPERMOD . ',' . UC_FORUMMOD;
  stdhead ($lang->topten['head']);
  begin_main_frame ();
  $type = (isset ($_GET['type']) ? intval ($_GET['type']) : 1);
  if (!in_array ($type, array (1, 2, 3, 4)))
  {
    $type = 1;
  }

  $limit = (isset ($_GET['lim']) ? 0 + $_GET['lim'] : false);
  $subtype = (isset ($_GET['subtype']) ? $_GET['subtype'] : false);
  print '<p align="center">' . (($type == 1 AND !$limit) ? '<b>' . $lang->topten['users'] . '</b>' : '<a href="topten.php?type=1">' . $lang->topten['users'] . '</a>') . ' | ' . (($type == 2 AND !$limit) ? '<b>' . $lang->topten['torrents'] . '</b>' : '<a href="topten.php?type=2">' . $lang->topten['torrents'] . '</a>') . ' | ' . (($type == 3 AND !$limit) ? '<b>' . $lang->topten['countries'] . '</b>' : '<a href="topten.php?type=3">' . $lang->topten['countries'] . '</a>') . ' | ' . (($type == 4 AND !$limit) ? '<b>' . $lang->topten['peers'] . '</b>' : '<a href="topten.php?type=4">' . $lang->topten['peers'] . '</a>') . ' | ' . (($type == 5 AND !$limit) ? '<b>' . $lang->topten['forums'] . '</b>' : '<a href="topten.php?type=5">' . $lang->topten['forums'] . '</a>') . ' </p>
';
  $pu = (is_mod ($usergroups) ? true : false);
  if (!$pu)
  {
    $limit = 10;
  }

  if ($type == 1)
  {
    $cachefile = 'topten-type-' . $type . '-limit-' . $lim . '-poweruser-' . $pu . '-subtype-' . $subtype;
    cache_check ($cachefile);
    $mainquery = '' . 'SELECT u.id as userid, u.username, u.usergroup, u.options, u.added, u.uploaded, u.downloaded, u.uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(u.added)) AS upspeed, u.downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(u.added)) AS downspeed, g.namestyle, g.canstaffpanel, g.issupermod, g.cansettingspanel FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\' AND u.usergroup NOT IN (' . $notin . ')';
    if ((!$limit OR 250 < $limit))
    {
      $limit = 10;
    }

    if (($limit == 10 OR $subtype == 'ul'))
    {
      $order = 'uploaded DESC';
      ($r = sql_query ($mainquery . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 234));
      usertable ($r, sprintf ($lang->topten['type1_title1'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=ul>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=ul>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    if (($limit == 10 OR $subtype == 'dl'))
    {
      $order = 'downloaded DESC';
      ($r = sql_query ($mainquery . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 241));
      usertable ($r, sprintf ($lang->topten['type1_title2'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=dl>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=dl>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    if (($limit == 10 OR $subtype == 'uls'))
    {
      $order = 'upspeed DESC';
      ($r = sql_query ($mainquery . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 248));
      usertable ($r, sprintf ($lang->topten['type1_title3'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=uls>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=uls>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    if (($limit == 10 OR $subtype == 'dls'))
    {
      $order = 'downspeed DESC';
      ($r = sql_query ($mainquery . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 255));
      usertable ($r, sprintf ($lang->topten['type1_title4'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=dls>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=dls>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    if (($limit == 10 OR $subtype == 'bsh'))
    {
      $order = 'uploaded / downloaded DESC';
      $extrawhere = ' AND downloaded > 1073741824';
      ($r = sql_query ($mainquery . $extrawhere . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 263));
      usertable ($r, sprintf ($lang->topten['type1_title5'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=bsh>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=bsh>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    if (($limit == 10 OR $subtype == 'wsh'))
    {
      $order = 'uploaded / downloaded ASC, downloaded DESC';
      $extrawhere = ' AND downloaded > 1073741824';
      ($r = sql_query ($mainquery . $extrawhere . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 271));
      usertable ($r, sprintf ($lang->topten['type1_title6'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=1&lim=100&subtype=wsh>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=1&lim=250&subtype=wsh>' . $lang->topten['top250'] . '</a>]</font>' : ''));
    }

    cache_save ($cachefile);
  }
  else
  {
    if ($type == 2)
    {
      $cachefile = 'topten-type-' . $type . '-limit-' . $lim . '-poweruser-' . $pu . '-subtype-' . $subtype;
      cache_check ($cachefile);
      if ((!$limit OR 50 < $limit))
      {
        $limit = 10;
      }

      if (($limit == 10 OR $subtype == 'act'))
      {
        ($r = sql_query ('' . 'SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' GROUP BY t.id ORDER BY seeders + leechers DESC, seeders DESC, added ASC LIMIT ' . $limit) OR sqlerr ());
        _torrenttable ($r, sprintf ($lang->topten['type2_title1'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=2&lim=25&subtype=act>' . $lang->topten['top25'] . '</a>] - [<a href=topten.php?type=2&lim=50&subtype=act>' . $lang->topten['top50'] . '</a>]</font>' : ''));
      }

      if (($limit == 10 OR $subtype == 'sna'))
      {
        ($r = sql_query ('' . 'SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' GROUP BY t.id ORDER BY times_completed DESC LIMIT ' . $limit) OR sqlerr ());
        _torrenttable ($r, sprintf ($lang->topten['type2_title2'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=2&lim=25&subtype=sna>' . $lang->topten['top25'] . '</a>] - [<a href=topten.php?type=2&lim=50&subtype=sna>' . $lang->topten['top50'] . '</a>]</font>' : ''));
      }

      if (($limit == 10 OR $subtype == 'mdt'))
      {
        ($r = sql_query ('' . 'SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY data DESC, added ASC LIMIT ' . $limit) OR sqlerr ());
        _torrenttable ($r, sprintf ($lang->topten['type2_title3'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=2&lim=25&subtype=mdt>' . $lang->topten['top25'] . '</a>] - [<a href=topten.php?type=2&lim=50&subtype=mdt>' . $lang->topten['top50'] . '</a>]</font>' : ''));
      }

      if (($limit == 10 OR $subtype == 'bse'))
      {
        ($r = sql_query ('' . 'SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' AND seeders >= 5 GROUP BY t.id ORDER BY seeders / leechers DESC, seeders DESC, added ASC LIMIT ' . $limit) OR sqlerr ());
        _torrenttable ($r, sprintf ($lang->topten['type2_title4'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=2&lim=25&subtype=bse>' . $lang->topten['top25'] . '</a>] - [<a href=topten.php?type=2&lim=50&subtype=bse>' . $lang->topten['top50'] . '</a>]</font>' : ''));
      }

      if (($limit == 10 OR $subtype == 'wse'))
      {
        ($r = sql_query ('' . 'SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY seeders / leechers ASC, leechers DESC LIMIT ' . $limit) OR sqlerr ());
        _torrenttable ($r, sprintf ($lang->topten['type2_title5'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=2&lim=25&subtype=wse>' . $lang->topten['top25'] . '</a>] - [<a href=topten.php?type=2&lim=50&subtype=wse>' . $lang->topten['top50'] . '</a>]</font>' : ''));
      }

      cache_save ($cachefile);
    }
    else
    {
      if ($type == 3)
      {
        $cachefile = 'topten-type-' . $type . '-limit-' . $lim . '-poweruser-' . $pu . '-subtype-' . $subtype;
        cache_check ($cachefile);
        if ((!$limit OR 25 < $limit))
        {
          $limit = 10;
        }

        if (($limit == 10 OR $subtype == 'us'))
        {
          ($r = sql_query ('' . 'SELECT name, flagpic, COUNT(users.country) as num FROM countries LEFT JOIN users ON users.country = countries.id GROUP BY name ORDER BY num DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 329));
          countriestable ($r, sprintf ($lang->topten['type3_title1'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=3&lim=25&subtype=us>' . $lang->topten['top25'] . '</a>]</font>' : ''), 'Users');
        }

        if (($limit == 10 OR $subtype == 'ul'))
        {
          ($r = sql_query ('' . 'SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = \'yes\' GROUP BY c.name ORDER BY ul DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 335));
          countriestable ($r, sprintf ($lang->topten['type3_title2'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=3&lim=25&subtype=ul>' . $lang->topten['top25'] . '</a>]</font>' : ''), 'Uploaded');
        }

        if (($limit == 10 OR $subtype == 'avg'))
        {
          ($r = sql_query ('' . 'SELECT c.name, c.flagpic, sum(u.uploaded)/count(u.id) AS ul_avg FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = \'yes\' GROUP BY c.name HAVING sum(u.uploaded) > 1099511627776 AND count(u.id) >= 100 ORDER BY ul_avg DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 341));
          countriestable ($r, sprintf ($lang->topten['type3_title3'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=3&lim=25&subtype=avg>' . $lang->topten['top25'] . '</a>]</font>' : ''), 'Average');
        }

        if (($limit == 10 OR $subtype == 'r'))
        {
          ($r = sql_query ('' . 'SELECT c.name, c.flagpic, sum(u.uploaded)/sum(u.downloaded) AS r FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = \'yes\' GROUP BY c.name HAVING sum(u.uploaded) > 1099511627776 AND sum(u.downloaded) > 1099511627776 AND count(u.id) >= 100 ORDER BY r DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 347));
          countriestable ($r, sprintf ($lang->topten['type3_title4'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=3&lim=25&subtype=r>' . $lang->topten['top25'] . '</a>]</font>' : ''), 'Ratio');
        }

        cache_save ($cachefile);
      }
      else
      {
        if ($type == 4)
        {
          $cachefile = 'topten-type-' . $type . '-limit-' . $lim . '-poweruser-' . $pu . '-subtype-' . $subtype;
          cache_check ($cachefile);
          if ((!$limit OR 250 < $limit))
          {
            $limit = 10;
          }

          if (($limit == 10 OR $subtype == 'ul'))
          {
            ($r = sql_query ('' . 'SELECT users.id AS userid, usergroup, options, username, (peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, IF(seeder = \'yes\',(peers.downloaded - peers.downloadoffset)  / (finishedat - UNIX_TIMESTAMP(started)),(peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started))) AS downrate, g.namestyle, g.canstaffpanel, g.issupermod, g.cansettingspanel FROM peers LEFT JOIN users ON peers.userid = users.id LEFT JOIN usergroups g ON (users.usergroup=g.gid) WHERE usergroup NOT IN (' . $notin . ') ORDER BY uprate DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 364));
            peerstable ($r, sprintf ($lang->topten['type4_title1'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=4&lim=100&subtype=ul>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=4&lim=250&subtype=ul>' . $lang->topten['top25'] . '0</a>]</font>' : ''));
          }

          if (($limit == 10 OR $subtype == 'dl'))
          {
            ($r = sql_query ('' . 'SELECT users.id AS userid, usergroup, options, peers.id AS peerid, username, peers.uploaded, peers.downloaded,(peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, IF(seeder = \'yes\',(peers.downloaded - peers.downloadoffset)  / (finishedat - UNIX_TIMESTAMP(started)),(peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started))) AS downrate, g.namestyle FROM peers LEFT JOIN users ON peers.userid = users.id LEFT JOIN usergroups g ON (users.usergroup=g.gid) ORDER BY downrate DESC LIMIT ' . $limit) OR sqlerr (__FILE__, 370));
            peerstable ($r, sprintf ($lang->topten['type4_title2'], $limit) . (($limit == 10 AND $pu) ? ' <font class=small> - [<a href=topten.php?type=4&lim=100&subtype=dl>' . $lang->topten['top100'] . '</a>] - [<a href=topten.php?type=4&lim=250&subtype=dl>' . $lang->topten['top25'] . '0</a>]</font>' : ''));
          }

          cache_save ($cachefile);
        }
      }
    }
  }

  end_main_frame ();
  stdfoot ();
?>
