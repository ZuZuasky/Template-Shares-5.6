<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function deletetorrent ($id, $permission = false)
  {
    global $torrent_dir;
    global $usergroups;
    if ((($permission OR is_mod ($usergroups)) AND is_valid_id ($id)))
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

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
