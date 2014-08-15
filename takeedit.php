<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function validfilename ($name)
  {
    return preg_match ('/^[^\\0-\\x1f:\\\\\\/?*\\xff#<>|]+$/si', $name);
  }

  function isscene ($name, $type = 1)
  {
    if (50 <= strlen ($name))
    {
      $name = substr ($name, 0, 50);
    }

    $pre['regexp'] = '|<td>(.*)<td>(.*)<td>(.*)</table>|';
    $pre['url'] = 'http://doopes.com/?cat=454647&lang=0&num=2&mode=0&from=&to=&exc=&inc=' . $name . '&opt=0';
    $pre['file'] = @file_get_contents ($pre['url']);
    $pre['file'] = @str_replace (array ('\\r', '\\n'), '', $pre['file']);
    @preg_match ($pre['regexp'], $pre['file'], $pre['matches']);
    return $pre['matches'][$type];
  }

  function dict_check ($d, $s)
  {
    global $lang;
    if ($d['type'] != 'dictionary')
    {
      stderr ($lang->global['error'], $lang->upload['dicterror1']);
    }

    $a = explode (':', $s);
    $dd = $d['value'];
    $ret = array ();
    foreach ($a as $k)
    {
      unset ($t);
      if (preg_match ('/^(.*)\\((.*)\\)$/', $k, $m))
      {
        $k = $m[1];
        $t = $m[2];
      }

      if (!isset ($dd[$k]))
      {
        stderr ($lang->global['error'], $lang->upload['dicterror2']);
      }

      if (isset ($t))
      {
        if ($dd[$k]['type'] != $t)
        {
          stderr ($lang->global['error'], $lang->upload['dicterror3']);
        }

        $ret[] = $dd[$k]['value'];
        continue;
      }
      else
      {
        $ret[] = $dd[$k];
        continue;
      }
    }

    return $ret;
  }

  function dict_get ($d, $k, $t)
  {
    global $lang;
    if ($d['type'] != 'dictionary')
    {
      stderr ($lang->global['error'], $lang->upload['dicterror1']);
    }

    $dd = $d['value'];
    if (!isset ($dd[$k]))
    {
      return null;
    }

    $v = $dd[$k];
    if ($v['type'] != $t)
    {
      stderr ($lang->global['error'], $lang->upload['dicterror4']);
    }

    return $v['value'];
  }

  function unesc ($x)
  {
    if (get_magic_quotes_gpc ())
    {
      return stripslashes ($x);
    }

    return $x;
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('TE_VERSION', '1.6 ');
  define ('TU_VERSION', true);
  $lang->load ('edit');
  $is_mod = is_mod ($usergroups);
  $updateset = array ();
  require INC_PATH . '/functions_getvar.php';
  getvar (array ('id', 'subject', 'message', 'type'));
  $id = (int)$id;
  int_check ($id, true);
  if (((((empty ($subject) OR empty ($message)) OR empty ($type)) AND !isset ($_GET['remove_image'])) AND !isset ($_GET['remove_link'])))
  {
    stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
  }

  $res = sql_query ('SELECT owner, filename, t_image, t_link, added, ts_external FROM torrents WHERE id = ' . sqlesc ($id));
  $row = mysql_fetch_assoc ($res);
  if (!$row)
  {
    stderr ($lang->global['error'], $lang->global['notorrentid']);
  }

  if (($CURUSER['id'] != $row['owner'] AND !$is_mod))
  {
    print_no_permission (true);
  }

  if (($_POST['scene'] == 'yes' AND $_contents = isscene (trim ($subject))))
  {
    $pretime = strtotime ($row['added']) - strtotime ($_contents);
    $updateset[] = '' . 'isScene = \'' . $pretime . '\'';
  }
  else
  {
    $updateset[] = 'isScene = \'0\'';
  }

  if (isset ($_GET['remove_image']))
  {
    $image = str_replace ('' . $BASEURL . '/', './', $row['t_image']);
    if (file_exists ($image))
    {
      unlink ($image);
    }

    sql_query ('UPDATE torrents SET t_image = \'\' WHERE id = ' . sqlesc ($id));
    redirect ('edit.php?id=' . $id);
    exit ();
  }
  else
  {
    if (isset ($_GET['remove_link']))
    {
      sql_query ('UPDATE torrents SET t_link = \'\' WHERE id = ' . sqlesc ($id));
      redirect ('edit.php?id=' . $id);
      exit ();
    }
  }

  $fname = (((((!empty ($_POST['filename']) AND $_POST['filename'] != $row['filename']) AND get_extension ($_POST['filename']) == 'torrent') AND validfilename ($_POST['filename'])) AND $is_mod) ? trim ($_POST['filename']) : $row['filename']);
  $fname = preg_replace ('#\\s+#', '_', $fname);
  preg_match ('/^(.+)\\.torrent$/si', $fname, $matches);
  $shortfname = $matches[1];
  $nfoaction = $_POST['nfoaction'];
  if ($nfoaction == 'update')
  {
    $nfofile = $_FILES['nfo'];
    if (!$nfofile)
    {
      print_no_permission ();
    }

    if (65535 < $nfofile['size'])
    {
      stderr ($lang->global['error'], $lang->edit['nfotoobig']);
    }

    $nfofilename = $nfofile['tmp_name'];
    if ((@is_uploaded_file ($nfofilename) AND 0 < @filesize ($nfofilename)))
    {
      (sql_query ('' . 'REPLACE INTO ts_nfo (id, nfo) VALUES (\'' . $id . '\', ' . sqlesc (str_replace ('\\x0d\\x0d\\x0a', '\\x0d\\x0a', file_get_contents ($nfofilename))) . ')') OR sqlerr (__FILE__, 92));
    }
  }
  else
  {
    if ($nfoaction == 'remove')
    {
      sql_query ('' . 'DELETE FROM ts_nfo WHERE id = \'' . $id . '\'');
    }
  }

  if (((!empty ($_FILES['t_image_file']) OR !empty ($_POST['t_image_url'])) OR !empty ($_POST['t_link'])))
  {
    $lang->load ('upload');
    include_once INC_PATH . '/class_upload.php';
    $upload = new ts_upload ();
    if (((!empty ($_POST['t_image_url']) AND $_POST['t_image_url'] != $lang->upload['field23']) AND $_POST['t_image_url'] != $row['t_image']))
    {
      $t_image = fix_url ($_POST['t_image_url']);
      $upload->url = $t_image;
      $upload->file_type = 'image';
      $upload->allowed_ext = array ('gif', 'jpg', 'png');
      $upload->check_url ();
      $updateset[] = 't_image = ' . sqlesc ($t_image);
    }
    else
    {
      if ((((!empty ($_FILES['t_image_file']) AND $_FILES['t_image_file'] != $row['t_image']) AND !empty ($_FILES['t_image_file']['name'])) AND !empty ($_FILES['t_image_file']['tmp_name'])))
      {
        include_once INC_PATH . '/class_upload2.php';
        $handle = new Upload ($_FILES['t_image_file']);
        $handle->allowed = array ('image/gif', 'image/jpg', 'image/jpeg', 'image/png');
        $allowed = implode (',', $handle->allowed);
        $allowed = str_replace ('image/', '', $allowed);
        $handle->file_new_name_body = $id;
        $handle->image_text = $SITENAME;
        $handle->image_text_direction = 'v';
        $handle->image_text_background = '#000000';
        $handle->image_text_font = 1;
        $handle->image_text_position = 'BL';
        $handle->image_text_padding_x = 2;
        $handle->image_text_padding_y = 8;
        $handle->Process (TSDIR . '/' . $torrent_dir . '/images/');
        if ($handle->processed)
        {
          $t_image = $BASEURL . '/' . $torrent_dir . '/images/' . $handle->file_dst_name;
          $updateset[] = 't_image = ' . sqlesc ($t_image);
        }
        else
        {
          stderr ($lang->global['error'], sprintf ($lang->upload['invalid_image'], $allowed));
        }

        $handle->Clean ();
      }
    }

    if ((!empty ($_POST['t_link']) AND $_POST['t_link'] != $row['t_link']))
    {
      $t_link = fix_url ($_POST['t_link']);
      if (substr ($t_link, 0 - 1, 1) != '/')
      {
        $t_link = '' . $t_link . '/';
      }

      $upload->url = $t_link;
      $upload->valid_link = array ('http://www.imdb.com/title/');
      $upload->file_type = 'imdb';
      $upload->check_url ();
      if (strstr ($t_link, 'imdb'))
      {
        include_once INC_PATH . '/ts_imdb.php';
      }

      $updateset[] = 't_link = ' . sqlesc ($t_link);
    }
  }

  if ((isset ($_FILES['file']) AND !empty ($_FILES['file']['name'])))
  {
    @set_time_limit (300);
    @ini_set ('upload_max_filesize', (1000 < $max_torrent_size ? $max_torrent_size : 10485760));
    @ini_set ('memory_limit', '20000M');
    @ignore_user_abort (1);
    require_once INC_PATH . '/benc.php';
    $lang->load ('upload');
    $f = $_FILES['file'];
    $f = preg_replace ('#\\s+#', '_', $f);
    $fname = unesc ($f['name']);
    if (empty ($fname))
    {
      stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
    }

    if (!validfilename ($fname))
    {
      stderr ($lang->global['error'], $lang->upload['fileerror1']);
    }

    if (!preg_match ('/^(.+)\\.torrent$/si', $fname, $matches))
    {
      stderr ($lang->global['error'], $lang->upload['fileerror2']);
    }

    $shortfname = $matches[1];
    $tmpname = $f['tmp_name'];
    if (!@is_uploaded_file ($tmpname))
    {
      stderr ($lang->global['error'], $lang->upload['uploaderror1']);
    }

    if (!@filesize ($tmpname))
    {
      stderr ($lang->global['error'], $lang->upload['uploaderror2']);
    }

    if ($privatetrackerpatch == 'yes')
    {
      $alink = $announce_urls[0];
    }
    else
    {
      $alink = $announce_urls[0] . '?passkey=' . $CURUSER['passkey'];
    }

    $dict = bdec_file ($tmpname, $max_torrent_size);
    if (!isset ($dict))
    {
      stderr ($lang->global['error'], $lang->upload['uploaderror3']);
    }

    list ($ann, $info) = dict_check ($dict, 'announce(string):info');
    list ($dname, $plen, $pieces) = dict_check ($info, 'name(string):piece length(integer):pieces(string)');
    $external = false;
    if (($externalscrape == 'yes' AND $ann != $alink))
    {
      $external = true;
      $updateset[] = 'ts_external = \'yes\'';
      $updateset[] = 'ts_external_url = ' . sqlesc ($ann);
      $updateset[] = 'visible = \'yes\'';
      $updateset[] = 'ts_external_lastupdate = \'0\'';
    }
    else
    {
      $updateset[] = 'ts_external = \'no\'';
      $updateset[] = 'ts_external_url = \'\'';
    }

    if (($external AND $usergroups['canexternal'] != 'yes'))
    {
      stderr ($lang->global['error'], $lang->upload['externalerror']);
    }

    if (($privatetrackerpatch == 'no' AND !$external))
    {
      if (!in_array ($ann, $announce_urls, 1))
      {
        $aok = false;
        foreach ($announce_urls as $au)
        {
          if ($ann == '' . $au . '?passkey=' . $CURUSER['passkey'])
          {
            $aok = true;
            continue;
          }
        }

        if (!$aok)
        {
          stderr ($lang->global['error'], $lang->upload['invalidannounceurl'] . $announce_urls[0] . '?passkey=' . $CURUSER['passkey']);
        }
      }
    }

    if (strlen ($pieces) % 20 != 0)
    {
      stderr ($lang->global['error'], $lang->upload['invalidpieces']);
    }

    if (($privatetrackerpatch == 'yes' AND !$external))
    {
      if (((isset ($dict['value']['announce-list']) OR isset ($dict['value']['nodes'])) OR (isset ($dict['value']['azureus_properties']['value']['dht_backup_enable']) AND $dict['value']['azureus_properties']['value']['dht_backup_enable']['value'] != 0)))
      {
        stderr ($lang->global['error'], $lang->upload['dhterror']);
      }
    }

    $filelist = array ();
    $totallen = dict_get ($info, 'length', 'integer');
    if (isset ($totallen))
    {
      $filelist[] = array ($dname, $totallen);
    }
    else
    {
      $flist = dict_get ($info, 'files', 'list');
      if (!isset ($flist))
      {
        stderr ($lang->global['error'], $lang->upload['dicterror5']);
      }

      if (!count ($flist))
      {
        stderr ($lang->global['error'], $lang->upload['dicterror6']);
      }

      $totallen = 0;
      foreach ($flist as $fn)
      {
        list ($ll, $ff) = dict_check ($fn, 'length(integer):path(list)');
        $totallen += $ll;
        $ffa = array ();
        foreach ($ff as $ffe)
        {
          if ($ffe['type'] != 'string')
          {
            stderr ($lang->global['error'], $lang->upload['dicterror7']);
          }

          $ffa[] = $ffe['value'];
        }

        if (!count ($ffa))
        {
          stderr ($lang->global['error'], $lang->upload['dicterror7']);
        }

        $ffe = implode ('/', $ffa);
        $filelist[] = array ($ffe, $ll);
      }
    }

    $updateset[] = 'size = ' . sqlesc ($totallen);
    $updateset[] = 'numfiles = ' . sqlesc (count ($filelist));
    if (($privatetrackerpatch == 'yes' AND !$external))
    {
      $dict['value']['announce'] = bdec (benc_str ($announce_urls[0]));
      $dict['value']['info']['value']['private'] = bdec ('i1e');
      $dict['value']['info']['value']['source'] = bdec (benc_str (('' . '[') . $BASEURL . '] ' . $SITENAME));
      unset ($dict['value'][{'created by'}]);
      unset ($dict['value'][{'announce-list'}]);
      unset ($dict['value'][nodes]);
      $dict = bdec (benc ($dict));
      list ($ann, $info) = dict_check ($dict, 'announce(string):info');
    }

    $infohash = pack ('H*', sha1 ($info['string']));
    $updateset[] = 'info_hash = ' . sqlesc ($infohash);
  }

  $updateset[] = 'filename = ' . sqlesc ($fname);
  $updateset[] = 'offensive = \'' . ($_POST['offensive'] ? 'yes' : 'no') . '\'';
  $updateset[] = 'anonymous = \'' . ($_POST['anonymous'] ? 'yes' : 'no') . '\'';
  $updateset[] = 'name = ' . sqlesc ($subject);
  $updateset[] = 'descr = ' . sqlesc ($message);
  $updateset[] = 'category = ' . (0 + $type);
  if ($is_mod)
  {
    $updateset[] = 'free = \'' . ($_POST['free'] == 1 ? 'yes' : 'no') . '\'';
    $updateset[] = 'isnuked = \'' . ($_POST['isnuked'] == 1 ? 'yes' : 'no') . '\'';
    $updateset[] = 'isrequest = \'' . ($_POST['isrequest'] == 1 ? 'yes' : 'no') . '\'';
    $updateset[] = 'silver = \'' . (($_POST['silver'] == 1 AND $_POST['free'] != 1) ? 'yes' : 'no') . '\'';
    if ($_POST['banned'] == 1)
    {
      $updateset[] = 'banned = \'yes\'';
      $_POST['visible'] = 0;
    }
    else
    {
      $updateset[] = 'banned = \'no\'';
    }

    if ($_POST['sticky'] == 'yes')
    {
      $updateset[] = 'sticky = \'yes\'';
    }
    else
    {
      $updateset[] = 'sticky = \'no\'';
    }

    if ($_POST['doubleupload'] == 'yes')
    {
      $updateset[] = 'doubleupload = \'yes\'';
    }
    else
    {
      $updateset[] = 'doubleupload = \'no\'';
    }

    if ($_POST['allowcomments'] == 'yes')
    {
      $updateset[] = 'allowcomments = \'yes\'';
    }
    else
    {
      $updateset[] = 'allowcomments = \'no\'';
    }
  }

  $updateset[] = 'visible = \'' . ($_POST['visible'] ? 'yes' : 'no') . '\'';
  sql_query ('UPDATE torrents SET ' . join (',', $updateset) . ' WHERE id = ' . sqlesc ($id));
  if ((mysql_affected_rows () AND $tmpname))
  {
    if (($privatetrackerpatch == 'yes' AND !$external))
    {
      @unlink ('' . $torrent_dir . '/' . $id . '.torrent');
      $fp = @fopen ('' . $torrent_dir . '/' . $id . '.torrent', 'w');
      if ($fp)
      {
        @fwrite ($fp, @benc ($dict), @strlen (@benc ($dict)));
        @fclose ($fp);
      }
    }

    if (($privatetrackerpatch == 'no' AND !$external))
    {
      @move_uploaded_file ($tmpname, '' . $torrent_dir . '/' . $id . '.torrent');
    }

    if ($external)
    {
      $externaltorrent = '' . $torrent_dir . '/' . $id . '.torrent';
      @move_uploaded_file ($tmpname, $externaltorrent);
      include_once INC_PATH . '/ts_external_scrape/ts_external.php';
    }
  }

  if (file_exists (TSDIR . '/' . $cache . '/latesttorrents.html'))
  {
    @unlink (TSDIR . '/' . $cache . '/latesttorrents.html');
  }

  $video_info = implode ('~', $_POST['video']);
  $audio_info = implode ('~', $_POST['audio']);
  $query = sql_query ('SELECT tid FROM ts_torrents_details WHERE tid = ' . sqlesc ($id));
  if (0 < mysql_num_rows ($query))
  {
    sql_query ('UPDATE ts_torrents_details SET video_info = ' . sqlesc ($video_info) . ', audio_info = ' . sqlesc ($audio_info) . ' WHERE tid = ' . sqlesc ($id));
  }
  else
  {
    sql_query ('' . 'INSERT INTO ts_torrents_details (tid,video_info,audio_info) VALUES (' . $id . ', ' . sqlesc ($video_info) . ',' . sqlesc ($audio_info) . ')');
  }

  write_log ('Torrent ' . intval ($id) . ' (' . htmlspecialchars_uni ($subject) . ('' . ') was edited by ' . $CURUSER['username']));
  redirect ('details.php?id=' . $id);
?>
