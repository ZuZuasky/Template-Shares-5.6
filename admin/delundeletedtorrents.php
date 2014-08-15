<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function deepdelete ($id)
  {
    global $torrent_dir;
    if (is_valid_id ($id))
    {
      $id = intval ($id);
      $file = TSDIR . '/' . $torrent_dir . '/' . $id . '.torrent';
      if (@file_exists ($file))
      {
        $image_types = array ('gif', 'jpg', 'png');
        foreach ($image_types as $image)
        {
          if (@file_exists (TSDIR . '/' . $torrent_dir . '/images/' . $id . '.' . $image))
          {
            @unlink (TSDIR . '/' . $torrent_dir . '/images/' . $id . '.' . $image);
            continue;
          }
        }

        @unlink ($file);
      }

      @sql_query ('DELETE FROM peers WHERE torrent = ' . @sqlesc ($id));
      @sql_query ('DELETE FROM comments WHERE torrent = ' . @sqlesc ($id));
      @sql_query ('DELETE FROM bookmarks WHERE torrentid = ' . @sqlesc ($id));
      @sql_query ('DELETE FROM snatched WHERE torrentid = ' . @sqlesc ($id));
      @sql_query ('DELETE FROM torrents WHERE id=' . @sqlesc ($id));
      @sql_query ('DELETE FROM ts_torrents_details WHERE tid=' . @sqlesc ($id));
      @sql_query ('DELETE FROM ts_thanks WHERE tid=' . @sqlesc ($id));
      @sql_query ('DELETE FROM ratings WHERE type=\'1\' AND rating_id=' . @sqlesc ($id));
      @sql_query ('DELETE FROM reports WHERE type=\'torrent\' AND votedfor = ' . @sqlesc ($id));
      @sql_query ('DELETE FROM ts_nfo  WHERE id = ' . @sqlesc ($id));
      return null;
    }

    print_no_permission (true);
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('DT_VERSION', '0.6 by xam');
  $torrentids = array ();
  $sql = sql_query ('SELECT id FROM torrents');
  while ($torrent = mysql_fetch_assoc ($sql))
  {
    $torrentids[] = intval ($torrent['id']);
  }

  $files = array ();
  if ($handle = opendir (TSDIR . '/' . $torrent_dir))
  {
    while (false !== $file = readdir ($handle))
    {
      if ((($file != '.' AND $file != '..') AND substr ($file, 0 - 8) == '.torrent'))
      {
        $file = str_replace ('.torrent', '', $file);
        $file = intval ($file);
        $files[] = $file;
        continue;
      }
    }

    closedir ($handle);
  }

  $delete = array ();
  foreach ($files as $file)
  {
    if (!in_array ($file, $torrentids, true))
    {
      if ((isset ($_GET['sure']) AND $_GET['sure'] == 'yes'))
      {
        deepdelete ($file);
        continue;
      }
      else
      {
        $delete[] = $file;
        continue;
      }

      continue;
    }
  }

  stdhead ('Delete Undeleted Torrent Files');
  _form_header_open_ ('Delete Undeleted Torrent Files');
  $str = '<tr><td>';
  if (!empty ($delete))
  {
    $str .= 'Total <b>' . count ($delete) . '</b> files found under ' . $torrent_dir . ' folder.';
    $str .= 'Click <a href="' . $_this_script_ . '&sure=yes">here</a> to delete them.</td></tr>';
  }
  else
  {
    $str .= 'There is no undeleted torrents found.';
  }

  $str .= '</td></tr>';
  echo $str;
  _form_header_close_ ();
  stdfoot ();
?>
