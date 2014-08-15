<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_error ()
  {
    global $errormessage;
    global $lang;
    global $ts_template;
    if (!empty ($errormessage))
    {
      eval ($ts_template['show_error']);
    }

  }

  function ts_remove_whitespaces ($text = '', $replace = '_')
  {
    return preg_replace ('#\\s+#', $replace, $text);
  }

  require_once 'global.php';
  $action = (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : ''));
  if ($action != 'download')
  {
    gzip ();
  }

  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('TS_SUBTITLE_VERSION', '1.1 ');
  require_once './admin/include/global_config.php';
  $lang->load ('ts_subtitles');
  $is_mod = is_mod ($usergroups);
  $canupload = ($usergroups['canupload'] == 'yes' ? true : false);
  $candownload = ($usergroups['candownload'] == 'yes' ? true : false);
  $errormessage = $title = '';
  $allowed_file_types = $config['subtitles']['allowed_file_types'];
  $maxsize = $config['subtitles']['max_upload_size'];
  $userid = intval ($CURUSER['id']);
  $username = htmlspecialchars_uni ($CURUSER['username']);
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : ''));
  $id = (isset ($_GET['id']) ? intval ($_GET['id']) : (isset ($_POST['id']) ? intval ($_POST['id']) : ''));
  require_once INC_PATH . '/class_template.php';
  $new_ts_template = new ts_template ();
  $ts_template = $new_ts_template->get_ts_template ('ts_subtitles');
  if ((($action == 'delete' AND $is_mod) AND is_valid_id ($id)))
  {
    $query = sql_query ('SELECT title,filename FROM ts_subtitles WHERE id = ' . sqlesc ($id));
    if (0 < mysql_num_rows ($query))
    {
      $filename = mysql_result ($query, 0, 'filename');
      $stitle = htmlspecialchars_uni (mysql_result ($query, 0, 'title'));
      if (file_exists ($torrent_dir . '/' . $filename))
      {
        @unlink ($torrent_dir . '/' . $filename);
      }

      sql_query ('DELETE FROM ts_subtitles WHERE id = ' . sqlesc ($id) . ' LIMIT 1');
      write_log ('' . 'Subtitle: ' . $stitle . ' deleted by ' . $username);
    }
  }

  if (($action == 'edit' AND is_valid_id ($id)))
  {
    $query = sql_query ('SELECT uid, title, language, cds, fps FROM ts_subtitles WHERE id = ' . sqlesc ($id));
    $subtitle = mysql_fetch_assoc ($query);
    $canedit = ($subtitle['uid'] == $euid ? true : false);
    if ((mysql_num_rows ($query) == 0 OR (!$canedit AND !$is_mod)))
    {
      print_no_permission ();
    }

    if (($do == 'save' AND empty ($errormessage)))
    {
      $title = trim ($_POST['title']);
      $language = intval ($_POST['language']);
      $cds = intval ($_POST['cds']);
      $fps = trim ($_POST['fps']);
      if ((((empty ($title) OR empty ($language)) OR empty ($cds)) OR empty ($fps)))
      {
        $errormessage = $lang->global['dontleavefieldsblank'];
      }
      else
      {
        sql_query ('UPDATE ts_subtitles SET title = ' . sqlesc ($title) . ', language = ' . sqlesc ($language) . ', cds = ' . sqlesc ($cds) . ', fps = ' . sqlesc ($fps) . ' WHERE id = ' . sqlesc ($id));
        header ('Location: ' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?id=' . $id);
        exit ();
      }
    }

    stdhead ($lang->ts_subtitles['head2']);
    show_error ();
    $countries = '<option value=\'0\'>---------------</option>
';
    ($ct_r = sql_query ('SELECT id,name FROM countries ORDER BY name') OR sqlerr (__FILE__, 111));
    while ($ct_a = mysql_fetch_assoc ($ct_r))
    {
      $countries .= '<option value=\'' . intval ($ct_a['id']) . '\'' . ($subtitle['language'] == $ct_a['id'] ? ' selected=\'selected\'' : '') . '>' . htmlspecialchars_uni ($ct_a['name']) . '</option>
';
    }

    $where = array ($lang->ts_subtitles['cancel'] => $BASEURL . $_SERVER['SCRIPT_NAME']);
    eval ($ts_template['edit']);
    stdfoot ();
    exit ();
  }

  if (($action == 'upload' AND ($canupload OR $is_mod)))
  {
    if ($do == 'save')
    {
      $title = trim ($_POST['title']);
      $language = intval ($_POST['language']);
      $cds = intval ($_POST['cds']);
      $fps = trim ($_POST['fps']);
      if ((((empty ($title) OR empty ($language)) OR empty ($cds)) OR empty ($fps)))
      {
        $errormessage = $lang->global['dontleavefieldsblank'];
      }

      $subtitlefile = $_FILES['subtitlefile'];
      if (((((empty ($subtitlefile) OR empty ($subtitlefile['name'])) OR empty ($subtitlefile['tmp_name'])) OR empty ($subtitlefile['size'])) OR !is_uploaded_file ($subtitlefile['tmp_name'])))
      {
        $errormessage = $lang->ts_subtitles['uploaderror'];
      }
      else
      {
        if ($maxsize < filesize ($subtitlefile['tmp_name']))
        {
          $errormessage = sprintf ($lang->ts_subtitles['sizeerror'], mksize ($maxsize));
        }
      }

      if (empty ($errormessage))
      {
        $ext = strtolower (get_extension ($subtitlefile['name']));
        if (!in_array ($ext, $allowed_file_types, true))
        {
          $errormessage = $lang->ts_subtitles['uploaderror'];
        }
        else
        {
          if (file_exists ($torrent_dir . '/' . $subtitlefile['name']))
          {
            $errormessage = $lang->ts_subtitles['fileexists'];
          }
        }

        if (empty ($errormessage))
        {
          $filename = ts_remove_whitespaces ($subtitlefile['name']);
          if (move_uploaded_file ($subtitlefile['tmp_name'], $torrent_dir . '/' . $filename))
          {
            $date = time ();
            sql_query ('INSERT INTO ts_subtitles (title,language,cds,fps,uid,date,filename) VALUES (' . sqlesc ($title) . ', ' . sqlesc ($language) . ', ' . sqlesc ($cds) . ', ' . sqlesc ($fps) . ', ' . sqlesc ($userid) . ', ' . sqlesc ($date) . ', ' . sqlesc ($filename) . ')');
            $id = mysql_insert_id ();
            write_log ('' . 'Subtitle: ' . $stitle . ' uploaded by ' . $username);
            header ('Location: ' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?id=' . $id);
            exit ();
          }
          else
          {
            $errormessage = $lang->ts_subtitles['uploaderror'];
          }
        }
      }
    }

    stdhead ($lang->ts_subtitles['upload']);
    show_error ();
    $countries = '<option value=\'0\'>---------------</option>
';
    ($ct_r = sql_query ('SELECT id,name FROM countries ORDER BY name') OR sqlerr (__FILE__, 185));
    while ($ct_a = mysql_fetch_assoc ($ct_r))
    {
      $countries .= '<option value=\'' . intval ($ct_a['id']) . '\'' . ($language == $ct_a['id'] ? ' selected=\'selected\'' : '') . '>' . htmlspecialchars_uni ($ct_a['name']) . '</option>
';
    }

    $where = array ($lang->ts_subtitles['cancel'] => $BASEURL . $_SERVER['SCRIPT_NAME']);
    eval ($ts_template['upload']);
    stdfoot ();
    exit ();
  }

  if ((($action == 'download' AND is_valid_id ($id)) AND ($candownload OR $is_mod)))
  {
    function download ($name)
    {
      global $SITENAME;
      $status = FALSE;
      $path = $name;
      if ((!is_file ($path) OR connection_status () != 0))
      {
        return FALSE;
      }

      require_once INC_PATH . '/functions_browser.php';
      if (is_browser ('ie'))
      {
        header ('Pragma: public');
        header ('Expires: 0');
        header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header ('Content-Disposition: attachment; filename=' . basename ($name) . ';');
        header ('Content-Transfer-Encoding: binary');
      }
      else
      {
        header ('Expires: Tue, 1 Jan 1980 00:00:00 GMT');
        header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
        header ('Cache-Control: no-store, no-cache, must-revalidate');
        header ('Cache-Control: post-check=0, pre-check=0', false);
        header ('Pragma: no-cache');
        header ('X-Powered-By: ' . VERSION . ' (c) ' . date ('Y') . ' ' . $SITENAME . '');
        header ('Accept-Ranges: bytes');
        header ('Connection: close');
        header ('Content-Transfer-Encoding: binary');
        header ('Content-Type: application/force-download');
        header ('Content-Length: ' . filesize ($path));
        header ('Content-Disposition: attachment; filename=' . basename ($name) . ';');
      }

      ob_implicit_flush (true);
      if ($file = fopen ($path, 'rb'))
      {
        while ((!feof ($file) AND connection_status () == 0))
        {
          print fread ($file, 1024 * 8);
          flush ();
        }

        $status = connection_status () == 0;
        fclose ($file);
      }

      return $status;
    }

    $query = sql_query ('SELECT filename FROM ts_subtitles WHERE id = ' . sqlesc ($id));
    if (0 < mysql_num_rows ($query))
    {
      $filename = mysql_result ($query, 0, 'filename');
      $path = '' . $torrent_dir . '/' . $filename;
      if (!file_exists ($path))
      {
        $errormessage = $lang->ts_subtitles['filenotexists'];
      }
      else
      {
        sql_query ('UPDATE ts_subtitles SET dlcount = dlcount + 1 WHERE id = ' . sqlesc ($id));
        download ($path);
        exit ();
      }
    }
    else
    {
      $errormessage = $lang->ts_subtitles['invalidid'];
    }
  }

  $extraquery1 = $extraquery2 = '';
  $extralink = '?';
  if ($action == 'search')
  {
    $keywords = (isset ($_GET['keywords']) ? trim ($_GET['keywords']) : (isset ($_POST['keywords']) ? trim ($_POST['keywords']) : ''));
    if (strlen ($keywords) < 3)
    {
      $errormessage = $lang->ts_subtitles['searcherror'];
    }
    else
    {
      $extraquery1 = '`title` LIKE \'%' . mysql_real_escape_string ($keywords) . '%\'';
      $extraquery2 = ' WHERE s.title LIKE \'%' . mysql_real_escape_string ($keywords) . '%\'';
      $extralink = '?action=search&amp;keywords=' . htmlspecialchars_uni (urlencode ($keywords)) . '&amp;';
    }
  }

  if (($canupload OR $is_mod))
  {
    $uploadlink = '<a href="' . $BASEURL . $_SERVER['SCRIPT_NAME'] . '?action=upload">' . $lang->ts_subtitles['upload'] . '</a>';
  }
  else
  {
    $uploadlink = '';
  }

  $torrentsperpage = ($CURUSER['torrentsperpage'] != 0 ? intval ($CURUSER['torrentsperpage']) : $ts_perpage);
  $count = tsrowcount ('id', 'ts_subtitles', $extraquery1);
  list ($pagertop, $pagerbottom, $limit) = pager ($torrentsperpage, $count, $_SERVER['SCRIPT_NAME'] . $extralink);
  stdhead ($SITENAME . ' ' . $lang->ts_subtitles['head'], true, 'collapse');
  show_error ();
  eval ($ts_template['main']);
  $query = sql_query ('' . 'SELECT s.*, u.username, g.namestyle, c.name, c.flagpic FROM ts_subtitles s LEFT JOIN users u ON (s.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN countries c ON (s.language=c.id)' . $extraquery2 . ' ORDER by s.date DESC ' . $limit);
  if (mysql_num_rows ($query) == 0)
  {
    eval ($ts_template['no_row']);
  }
  else
  {
    while ($subtitle = mysql_fetch_assoc ($query))
    {
      $sstitle = (($action == 'search' AND !empty ($keywords)) ? highlight ($keywords, htmlspecialchars_uni ($subtitle['title'])) : htmlspecialchars_uni ($subtitle['title']));
      $adminlink = ($is_mod ? ' [<b><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=delete&amp;id=' . $subtitle['id'] . '">' . $lang->ts_subtitles['delete'] . '</a></b>]' : '');
      $editlink = (($is_mod OR $subtitle['uid'] == $userid) ? '[<b><a href="' . $_SERVER['SCRIPT_NAME'] . '?action=edit&amp;id=' . $subtitle['id'] . '">' . $lang->ts_subtitles['edit'] . '</a></b>]' : '');
      eval ($ts_template['while']);
    }
  }

  eval ($ts_template['end']);
  stdfoot ();
?>
