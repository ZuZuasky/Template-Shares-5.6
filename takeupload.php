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

  function bark ($msg, $redirect = true)
  {
    global $lang;
    global $where;
    if ($redirect)
    {
      $where .= '&msg=' . base64_encode ($msg);
      header ('' . 'Location: ' . $where);
      exit ();
    }

    stderr ($lang->global['error'], $msg);
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
      bark ($lang->upload['dicterror1']);
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
        bark ($lang->upload['dicterror2']);
      }

      if (isset ($t))
      {
        if ($dd[$k]['type'] != $t)
        {
          bark ($lang->upload['dicterror3']);
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
      bark ($lang->upload['dicterror1']);
    }

    $dd = $d['value'];
    if (!isset ($dd[$k]))
    {
      return null;
    }

    $v = $dd[$k];
    if ($v['type'] != $t)
    {
      bark ($lang->upload['dicterror4']);
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

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  @ini_set ('log_errors', '1');
  require_once 'global.php';
  require_once INC_PATH . '/benc.php';
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  @set_time_limit (300);
  @ini_set ('upload_max_filesize', (1000 < $max_torrent_size ? $max_torrent_size : 10485760));
  @ini_set ('memory_limit', '20000M');
  @ignore_user_abort (1);
  define ('TU_VERSION', '2.6.7 ');
  if ($usergroups['canupload'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }
  else
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST')
    {
      header ('' . 'Location: ' . $BASEURL . '/upload.php');
      exit ();
    }
  }

  $lang->load ('upload');
  $where = 'upload.php?upload_step=2&subject=' . base64_encode ($_POST['subject']) . '&type=' . htmlspecialchars_uni ($_POST['type']) . '&trackerurl=' . base64_encode ($_POST['trackerurl']) . '&scene=' . base64_encode ($_POST['scene']) . '&t_link=' . base64_encode ($_POST['t_link']) . '&nforip=' . htmlspecialchars_uni ($_POST['nforip']) . '&uplver=' . htmlspecialchars_uni ($_POST['uplver']) . '&offensive=' . htmlspecialchars_uni ($_POST['offensive']) . '&t_image_url=' . base64_encode ($_POST['t_image_url']) . '&t_image_file=' . base64_encode ($_POST['t_image_file']) . '&message=' . base64_encode ($_POST['message']);
  foreach (explode (':', 'message:type:subject') as $v)
  {
    if (!isset ($_POST[$v]))
    {
      bark ($lang->global['dontleavefieldsblank']);
      continue;
    }
  }

  if (!isset ($_FILES['file']))
  {
    bark ($lang->global['dontleavefieldsblank']);
  }

  $f = $_FILES['file'];
  $f = preg_replace ('#\\s+#', '_', $f);
  $fname = unesc ($f['name']);
  if (empty ($fname))
  {
    bark ($lang->global['dontleavefieldsblank']);
  }

  if ($_POST['uplver'] == 'yes')
  {
    $anonymous = 'yes';
    $anon = $lang->upload['anonymous'];
  }
  else
  {
    $anonymous = 'no';
    $anon = $CURUSER['username'];
  }

  if (($_POST['free'] == '1' AND is_mod ($usergroups)))
  {
    $free = 'yes';
  }
  else
  {
    $free = 'no';
  }

  if ((($_POST['silver'] == '1' AND $free == 'no') AND is_mod ($usergroups)))
  {
    $silver = 'yes';
  }
  else
  {
    $silver = 'no';
  }

  if (($_POST['sticky'] == 'yes' AND is_mod ($usergroups)))
  {
    $sticky = 'yes';
  }
  else
  {
    $sticky = 'no';
  }

  if ($_POST['offensive'] == 'yes')
  {
    $offensive = 'yes';
  }
  else
  {
    $offensive = 'no';
  }

  $nfofile = $_FILES['nfo'];
  $NFOUPLOADED = false;
  if ($nfofile['name'] != '')
  {
    if ($nfofile['size'] == 0)
    {
      bark ($lang->upload['nfoerror1']);
    }

    if (655350 < $nfofile['size'])
    {
      bark ($lang->upload['nfoerror2']);
    }

    $nfofilename = $nfofile['tmp_name'];
    if (!@is_uploaded_file ($nfofilename))
    {
      bark ($lang->upload['nfoerror3']);
    }

    $NFOCONTENTS = str_replace ('

', '
', @file_get_contents ($nfofilename));
    if (!$NFOCONTENTS)
    {
      bark ($lang->upload['nfoerror1']);
    }

    $NFOUPLOADED = true;
  }

  $descr = ($_POST['message'] ? unesc ($_POST['message']) : '');
  if (!$descr)
  {
    if ((($_POST['nforip'] == 'yes' AND $NFOCONTENTS) AND $NFOUPLOADED))
    {
    }
    else
    {
      bark ($lang->global['dontleavefieldsblank']);
    }
  }

  if (strlen ($descr) < 10)
  {
    if ((($_POST['nforip'] == 'yes' AND $NFOCONTENTS) AND $NFOUPLOADED))
    {
    }
    else
    {
      bark ($lang->upload['mindesclimit']);
    }
  }

  $catid = (int)$_POST['type'];
  if (!is_valid_id ($catid))
  {
    bark ($lang->upload['selectcategory']);
  }

  if (!validfilename ($fname))
  {
    bark ($lang->upload['fileerror1']);
  }

  if (!preg_match ('/^(.+)\\.torrent$/si', $fname, $matches))
  {
    bark ($lang->upload['fileerror2']);
  }

  $shortfname = $torrent = $matches[1];
  if (!empty ($_POST['subject']))
  {
    $torrent = unesc ($_POST['subject']);
  }

  $tmpname = $f['tmp_name'];
  if (!@is_uploaded_file ($tmpname))
  {
    bark ($lang->upload['uploaderror1']);
  }

  if (!@filesize ($tmpname))
  {
    bark ($lang->upload['uploaderror2']);
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
    bark ($lang->upload['uploaderror3']);
  }

  list ($ann, $info) = dict_check ($dict, 'announce(string):info');
  list ($dname, $plen, $pieces) = dict_check ($info, 'name(string):piece length(integer):pieces(string)');
  $external = false;
  $sql1 = $sql2 = $trackerurl = '';
  $visible = 'no';
  if (($externalscrape == 'yes' AND $ann != $alink))
  {
    $external = true;
    $trackerurl = trim ($ann);
    $sql1 = ',ts_external, ts_external_url';
    $sql2 = ', \'yes\', ' . sqlesc ($ann) . '';
    $visible = 'yes';
  }

  if (($external AND $usergroups['canexternal'] != 'yes'))
  {
    bark ($lang->upload['externalerror']);
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
        bark ($lang->upload['invalidannounceurl'] . $announce_urls[0] . '?passkey=' . $CURUSER['passkey']);
      }
    }
  }

  if (strlen ($pieces) % 20 != 0)
  {
    bark ($lang->upload['invalidpieces']);
  }

  if (($privatetrackerpatch == 'yes' AND !$external))
  {
    if (((isset ($dict['value']['announce-list']) OR isset ($dict['value']['nodes'])) OR (isset ($dict['value']['azureus_properties']['value']['dht_backup_enable']) AND $dict['value']['azureus_properties']['value']['dht_backup_enable']['value'] != 0)))
    {
      bark ($lang->upload['dhterror']);
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
      bark ($lang->upload['dicterror5']);
    }

    if (!count ($flist))
    {
      bark ($lang->upload['dicterror6']);
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
          bark ($lang->upload['dicterror7']);
        }

        $ffa[] = $ffe['value'];
      }

      if (!count ($ffa))
      {
        bark ($lang->upload['dicterror7']);
      }

      $ffe = implode ('/', $ffa);
      $filelist[] = array ($ffe, $ll);
    }
  }

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
  if ((strtolower ($_POST['scene']) == 'yes' AND $_contents = isscene (trim ($torrent))))
  {
    $pretime = time () - strtotime ($_contents);
    $q1 = 'isScene, ';
    $q2 = '' . '\'' . $pretime . '\', ';
  }
  else
  {
    $pretime = $q1 = $q2 = '';
  }

  $torrent = str_replace ('_', ' ', $torrent);
  if (((!empty ($_FILES['t_image_file']) OR !empty ($_POST['t_image_url'])) OR !empty ($_POST['t_link'])))
  {
    include_once INC_PATH . '/class_upload.php';
    $upload = new ts_upload ();
    if ((!empty ($_POST['t_image_url']) AND $_POST['t_image_url'] != $lang->upload['field23']))
    {
      $t_image = fix_url ($_POST['t_image_url']);
      $upload->url = $t_image;
      $upload->file_type = 'image';
      $upload->allowed_ext = array ('gif', 'jpg', 'png');
      $upload->check_url ();
    }
    else
    {
      if (((!empty ($_FILES['t_image_file']) AND !empty ($_FILES['t_image_file']['name'])) AND !empty ($_FILES['t_image_file']['tmp_name'])))
      {
        include_once INC_PATH . '/class_upload2.php';
        $handle = new Upload ($_FILES['t_image_file']);
        if ($handle->uploaded)
        {
          $SQL = '' . 'SHOW
            TABLE STATUS
			FROM
				' . $mysql_db . '
			LIKE
				\'torrents\'';
          $result = sql_query ($SQL);
          $row = mysql_fetch_assoc ($result);
          $nextInsertId = $row['Auto_increment'];
          $handle->allowed = array ('image/gif', 'image/jpg', 'image/jpeg', 'image/png');
          $allowed = implode (',', $handle->allowed);
          $allowed = str_replace ('image/', '', $allowed);
          $handle->file_new_name_body = $nextInsertId;
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
          }
          else
          {
            stderr ($lang->global['error'], sprintf ($lang->upload['invalid_image'], $allowed));
          }

          $handle->Clean ();
        }
      }
    }

    if (!empty ($_POST['t_link']))
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
    }
  }

  $ret = sql_query ('INSERT INTO torrents (t_image, t_link, filename, owner, visible, anonymous, free, silver, sticky, offensive, info_hash, name, size, numfiles, descr, category, added, ' . $q1 . 'last_action' . $sql1 . ') VALUES (' . implode (',', array_map ('sqlesc', array ((!empty ($t_image) ? $t_image : (!empty ($cover_photo_name) ? $BASEURL . '/' . $cover_photo_name : '')), (!empty ($t_link) ? $t_link : ''), $fname, $CURUSER['id'], $visible, $anonymous, $free, $silver, $sticky, $offensive, $infohash, $torrent, $totallen, count ($filelist), $descr, 0 + $_POST['type']))) . ', \'' . get_date_time () . '\', ' . $q2 . '\'' . get_date_time () . '\'' . $sql2 . ')');
  if (!$ret)
  {
    if (mysql_errno () == 1062)
    {
      bark ($lang->upload['sqlerror1']);
    }
    else
    {
      bark ($lang->upload['sqlerror2'] . mysql_error ());
    }
  }

  $id = $tid = mysql_insert_id ();
  if (($NFOUPLOADED AND $NFOCONTENTS))
  {
    if ($_POST['nforip'] == 'yes')
    {
      $NewDescr = $BASEURL . '/viewnfo.php?id=' . $id;
      sql_query ('UPDATE torrents SET descr = ' . sqlesc ($NewDescr) . ('' . ' WHERE id = \'' . $id . '\''));
    }

    sql_query ('' . 'REPLACE INTO ts_nfo (id, nfo) VALUES (\'' . $id . '\', ' . sqlesc ($NFOCONTENTS) . ')');
  }

  if (($privatetrackerpatch == 'yes' AND !$external))
  {
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

  include_once INC_PATH . '/readconfig_kps.php';
  kps ('+', $kpsupload, $CURUSER['id']);
  if ($CURUSER['anonymous'] == 'yes')
  {
    write_log (sprintf ($lang->upload['writelog1'], $id, $torrent));
  }
  else
  {
    write_log (sprintf ($lang->upload['writelog2'], $id, $torrent, $CURUSER['username']));
  }

  $res = sql_query ('SELECT name FROM categories WHERE id=' . sqlesc ($catid));
  $arr = mysql_fetch_assoc ($res);
  $cat = $arr['name'];
  $res = sql_query ('' . 'SELECT u.email FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' AND u.status=\'confirmed\' AND u.notifs LIKE \'%[cat' . $catid . ']%\' AND u.notifs LIKE \'%[email]%\' AND u.notifs != \'\' AND g.isvipgroup=\'yes\'');
  $size = mksize ($totallen);
  $body = sprintf ($lang->upload['emailbody'], $torrent, $size, $cat, $anon, $descr, $BASEURL, $id, $SITENAME);
  $to = '';
  $nmax = 100;
  $nthis = $ntotal = 0;
  $total = mysql_num_rows ($res);
  if (0 < $total)
  {
    while ($arr = mysql_fetch_row ($res))
    {
      if ($nthis == 0)
      {
        $to = $arr[0];
      }
      else
      {
        $to .= ',' . $arr[0];
      }

      ++$nthis;
      ++$ntotal;
      if (($nthis == $nmax OR $ntotal == $total))
      {
        $sm = sent_mail ($to, sprintf ($lang->upload['emailsubject'], $SITENAME, $torrent), $body, 'takeupload', false);
        $nthis = 0;
        continue;
      }
    }
  }

  include_once INC_PATH . '/readconfig_pjirc.php';
  if (($ircbot == 'yes' AND $connect = @fsockopen ($botip, $botport, $errno, $errstr)))
  {
    $botmessage = chr (3) . '9' . chr (2) . ('' . ' ' . $SITENAME) . chr (2) . ' -' . chr (3) . '10 New Torrent: (' . chr (3) . ('' . '13 ' . $torrent) . chr (3) . '10 ) Size: (' . chr (3) . '13 ' . $size . chr (3) . '10 )  Category: (' . chr (3) . '13 ' . $cat . chr (3) . '10 ) Uploader: (' . chr (3) . ('' . '13 ' . $anon) . chr (3) . '10 ) Link: (' . chr (3) . ('' . '13 ' . $BASEURL . '/details.php?id=' . $id) . chr (3) . '10 )
';
    @fwrite ($connect, $botmessage);
    @fclose ($connect);
  }

  if (($tsshoutbot == 'yes' AND preg_match ('#upload#', $tsshoutboxoptions)))
  {
    $seo_link = ts_seo ($id, $torrent, 's');
    $shoutbOT = sprintf ($lang->upload['shoutbOT'], $seo_link, $torrent, $anon);
    $shout_sql = 'INSERT INTO shoutbox (userid, date, content) VALUES (\'999999999\', \'' . TIMENOW . '\', ' . sqlesc ('{systemnotice}' . $shoutbOT) . ')';
    $shout_result = sql_query ($shout_sql);
  }

  if (file_exists (TSDIR . '/' . $cache . '/latesttorrents.html'))
  {
    @unlink (TSDIR . '/' . $cache . '/latesttorrents.html');
  }

  header ('' . 'Location: ' . $BASEURL . '/upload.php?upload_step=3&tid=' . $tid);
?>
