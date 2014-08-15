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

  define ('S_VERSION', '0.3 by xam');
  require_once INC_PATH . '/functions_mkprettytime.php';
  stdhead ('Stats');
  echo '
';
  echo '<S';
  echo 'TYLE TYPE="text/css" MEDIA=screen>
<!--
  a.subheaderlink:link, a.subheaderlink:visited{
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
	}

	a.subheaderlink:hover {
  	text-decoration: underline;
	}
-->
</STYLE>

';
  ($res = sql_query ('SELECT COUNT(*) FROM torrents') OR sqlerr (__FILE__, 39));
  $n = mysql_fetch_row ($res);
  $n_tor = $n[0];
  ($res = sql_query ('SELECT COUNT(*) FROM peers') OR sqlerr (__FILE__, 43));
  $n = mysql_fetch_row ($res);
  $n_peers = $n[0];
  $uporder = $_GET['uporder'];
  $catorder = $_GET['catorder'];
  if ($uporder == 'lastul')
  {
    $orderby = 'last DESC, name';
  }
  else
  {
    if ($uporder == 'torrents')
    {
      $orderby = 'n_t DESC, name';
    }
    else
    {
      if ($uporder == 'peers')
      {
        $orderby = 'n_p DESC, name';
      }
      else
      {
        $orderby = 'name';
      }
    }
  }

  $query = '' . 'SELECT u.id, u.username AS name, g.canupload, g.namestyle, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN peers as p ON t.id = p.torrent LEFT JOIN usergroups as g ON u.usergroup = g.gid WHERE g.canupload = \'yes\'
	GROUP BY u.id UNION SELECT u.id, u.username AS name, g.canupload, g.namestyle, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN peers as p ON t.id = p.torrent LEFT JOIN usergroups as g ON u.usergroup = g.gid WHERE g.canupload = \'yes\' GROUP BY u.id ORDER BY ' . $orderby;
  ($res = sql_query ($query) OR sqlerr (__FILE__, 64));
  if (mysql_num_rows ($res) == 0)
  {
    stdmsg ('Sorry...', 'No uploaders.');
  }
  else
  {
    _form_header_open_ ('Uploader Activity');
    begin_table (true);
    print '<tr>

	<td class=subheader><a href="' . $_this_script_ . ('' . '&uporder=uploader&catorder=' . $catorder . '" class=subheaderlink>Uploader</a></td>

	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=lastul&catorder=' . $catorder . '" class=subheaderlink>Last Upload</a></td>

	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=torrents&catorder=' . $catorder . '" class=subheaderlink>Torrents</a></td>

	<td class=subheader>Perc.</td>

	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=peers&catorder=' . $catorder . '" class=subheaderlink>Peers</a></td>

	<td class=subheader>Perc.</td>

	</tr>
');
    while ($uper = mysql_fetch_array ($res))
    {
      print '' . '<tr><td><a href=' . $BASEURL . '/userdetails.php?id=' . $uper['id'] . '><b>' . get_user_color ($uper['name'], $uper['namestyle']) . '</b></a></td>
';
      print '<td ' . ($uper['last'] ? '>' . $uper['last'] . ' (' . mkprettytime (time () - strtotime ($uper['last'])) . ')' : 'align=center>---') . '</td>
';
      print '<td align=right>' . $uper['n_t'] . '</td>
';
      print '<td align=right>' . (0 < $n_tor ? number_format (100 * $uper['n_t'] / $n_tor, 1) . '%' : '---') . '</td>
';
      print '<td align=right>' . $uper['n_p'] . '</td>
';
      print '<td align=right>' . (0 < $n_peers ? number_format (100 * $uper['n_p'] / $n_peers, 1) . '%' : '---') . '</td></tr>
';
    }

    end_table ();
    _form_header_close_ ();
    echo '<br />';
  }

  if ($n_tor == 0)
  {
    stdmsg ('Sorry...', 'No categories defined!');
  }
  else
  {
    if ($catorder == 'lastul')
    {
      $orderby = 'last DESC, c.name';
    }
    else
    {
      if ($catorder == 'torrents')
      {
        $orderby = 'n_t DESC, c.name';
      }
      else
      {
        if ($catorder == 'peers')
        {
          $orderby = 'n_p DESC, name';
        }
        else
        {
          $orderby = 'c.name';
        }
      }
    }

    ($res = sql_query ('' . 'SELECT c.name, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) AS n_p
	FROM categories as c LEFT JOIN torrents as t ON t.category = c.id LEFT JOIN peers as p
	ON t.id = p.torrent GROUP BY c.id ORDER BY ' . $orderby) OR sqlerr (__FILE__, 110));
    _form_header_open_ ('Category Activity');
    begin_table (true);
    print '<tr><td class=subheader><a href="' . $_this_script_ . ('' . '&uporder=' . $uporder . '&catorder=category" class=subheaderlink>Category</a></td>
	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=' . $uporder . '&catorder=lastul" class=subheaderlink>Last Upload</a></td>
	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=' . $uporder . '&catorder=torrents" class=subheaderlink>Torrents</a></td>
	<td class=subheader>Perc.</td>
	<td class=subheader><a href="') . $_this_script_ . ('' . '&uporder=' . $uporder . '&catorder=peers" class=subheaderlink>Peers</a></td>
	<td class=subheader>Perc.</td></tr>
');
    while ($cat = mysql_fetch_array ($res))
    {
      print '<tr><td class=rowhead>' . $cat['name'] . '</b></a></td>';
      print '<td ' . ($cat['last'] ? '>' . $cat['last'] . ' (' . mkprettytime (time () - strtotime ($cat['last'])) . ')' : 'align = center>---') . '</td>';
      print '<td align=right>' . $cat['n_t'] . '</td>';
      print '<td align=right>' . number_format (100 * $cat['n_t'] / $n_tor, 1) . '%</td>';
      print '<td align=right>' . $cat['n_p'] . '</td>';
      print '<td align=right>' . (0 < $n_peers ? number_format (100 * $cat['n_p'] / $n_peers, 1) . '%' : '---') . '</td>
';
    }

    end_table ();
    _form_header_close_ ();
  }

  stdfoot ();
  exit ();
?>
