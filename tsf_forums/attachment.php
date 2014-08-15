<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function forum_permissions ($fid = 0)
  {
    global $CURUSER;
    ($query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'forumpermissions WHERE gid = ' . sqlesc ($CURUSER['usergroup']) . ($fid === 0 ? '' : ' WHERE fid = ' . sqlesc ($fid))) OR sqlerr (__FILE__, 253));
    while ($perm = mysql_fetch_assoc ($query))
    {
      $permissions[$perm['fid']] = $perm;
    }

    @mysql_free_result ($query);
    return ($fid === 0 ? $permissions : $permissions[$fid]);
  }

  error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('zlib.output_compression', 'Off');
  @set_time_limit (0);
  if ((@ini_get ('output_handler') == 'ob_gzhandler' AND @ob_get_length () !== false))
  {
    @ob_end_clean ();
    header ('Content-Encoding:');
  }

  $rootpath = './../';
  require_once $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  include_once INC_PATH . '/readconfig_forumcp.php';
  $lang->load ('tsf_forums');
  if ($usergroups['candownload'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  $pid = (isset ($_POST['pid']) ? intval ($_POST['pid']) : (isset ($_GET['pid']) ? intval ($_GET['pid']) : 0));
  $aid = (isset ($_POST['aid']) ? intval ($_POST['aid']) : (isset ($_GET['aid']) ? intval ($_GET['aid']) : 0));
  if ((($usergroups['isforummod'] == 'yes' OR $usergroups['cansettingspanel'] == 'yes') OR $usergroups['issupermod'] == 'yes'))
  {
    $moderator = true;
  }
  else
  {
    $moderator = false;
  }

  $permissions = forum_permissions ();
  if ((isset ($_GET['viewattachments']) AND is_valid_id ($tid)))
  {
    $query = sql_query ('SELECT t.tid, f.pid as parent FROM ' . TSF_PREFIX . 'threads t LEFT JOIN ' . TSF_PREFIX . 'forums f ON (t.fid=f.fid) WHERE t.tid=' . sqlesc ($tid));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $thread = mysql_fetch_assoc ($query);
    if ((!$moderator AND ($permissions[$thread['parent']]['canview'] == 'no' OR $permissions[$thread['parent']]['canviewthreads'] == 'no')))
    {
      print_no_permission (true);
      exit ();
    }

    $a_query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'attachments WHERE a_tid = ' . sqlesc ($tid));
    $columns;
    require_once INC_PATH . '/functions_get_file_icon.php';
    while ($attachment = mysql_fetch_assoc ($a_query))
    {
      $columns .= '
		<tr>
			<td>' . get_file_icon ($attachment['a_name']) . ' <a href="attachment.php?aid=' . intval ($attachment['a_id']) . '&amp;pid=' . intval ($attachment['a_pid']) . '&amp;tid=' . intval ($attachment['a_tid']) . '" target="_blank">' . htmlspecialchars_uni ($attachment['a_name']) . '</a></td>
			<td>' . mksize ($attachment['a_size']) . '</td>
			<td>' . ts_nf ($attachment['a_count']) . '</td>
		</tr>
		';
    }

    echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="https://www.w3.org/1999/xhtml">
	<head profile="https://gmpg.org/xfn/11">
	<title>' . $lang->tsf_forums['a_info'] . '</title>
	<meta http-equiv="Content-Type" content="text/html; charset=' . $charset . '" />
	<link rel="stylesheet" href="' . $BASEURL . '/include/templates/' . $defaulttemplate . '/style/style.css" type="text/css" media="screen" />	
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="4">
			<tr>
				<td class="thead" colspan="3">' . $lang->tsf_forums['a_info'] . '</td>
			</tr>
			<tr>
				<td class="subheader">' . $lang->tsf_forums['attachment'] . '</td>
				<td class="subheader">' . $lang->tsf_forums['a_size'] . '</td>
				<td class="subheader">' . $lang->tsf_forums['a_count'] . '</td>
			</tr>
			' . $columns . '
			<tr>
				<td colspan="3" align="center"><a href="#" onclick="opener.location=(\'showthread.php?tid=' . $tid . '\'); self.close();"><strong>' . $lang->tsf_forums['showandclose'] . '</strong></a></td>
			</tr>
		</table>
	</body>
	</html>
	';
    exit ();
  }

  if ((!is_valid_id ($aid) OR !is_valid_id ($pid)))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['a_error1']);
    exit ();
  }

  ($query = sql_query ('SELECT a.a_name, p.uid as posterid, f.pid as deepforum
							FROM ' . TSF_PREFIX . 'attachments a
							LEFT JOIN ' . TSF_PREFIX . 'posts p ON (a.a_pid=p.pid)
							LEFT JOIN ' . TSF_PREFIX . 'forums f ON (p.fid=f.fid)
							WHERE p.pid = ' . sqlesc ($pid) . ' AND a.a_id = ' . sqlesc ($aid)) OR sqlerr (__FILE__, 137));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['a_error1']);
    exit ();
  }

  $attachment = mysql_fetch_assoc ($query);
  $deepforum = 0 + $attachment['deepforum'];
  if (empty ($attachment['a_name']))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['a_error1']);
    exit ();
  }

  if ((!$moderator AND $permissions[$deepforum]['canview'] == 'no'))
  {
    print_no_permission (true);
    exit ();
  }

  $mtype = '';
  $filename = @rawurlencode (@basename ($attachment['a_name']));
  $filename = @str_replace (array ('"', '\'', '\\', '/'), '', $filename);
  $file_path = $f_upload_path . $filename;
  if (!file_exists ($file_path))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['a_error1']);
    exit ();
  }

  $filesize = @filesize ($file_path);
  if (function_exists ('mime_content_type'))
  {
    $mtype = mime_content_type ($file_path);
  }
  else
  {
    if (function_exists ('finfo_file'))
    {
      $finfo = finfo_open (FILEINFO_MIME);
      $mtype = finfo_file ($finfo, $file_path);
      finfo_close ($finfo);
    }
  }

  if ($mtype == '')
  {
    $mtype = 'application/force-download';
  }

  sql_query ('UPDATE ' . TSF_PREFIX . 'attachments SET a_count = a_count + 1 WHERE a_pid = ' . sqlesc ($pid) . ' AND a_id = ' . sqlesc ($aid));
  $extension = strtolower (get_extension ($attachment['a_name']));
  $imagetypes = array ('jpg', 'bmp', 'png', 'gif', 'jpeg');
  if (in_array ($extension, $imagetypes))
  {
    $filedata = file_get_contents ($file_path);
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-disposition: inline; filename=' . $filename);
    header ('Content-transfer-encoding: binary');
    header ('Content-Length: ' . strlen ($filedata) . '');
    header ('Content-type: image/' . $extension . '');
    echo $filedata;
    exit ();
  }

  require_once INC_PATH . '/functions_browser.php';
  if (is_browser ('ie'))
  {
    header ('Pragma: public');
    header ('Expires: 0');
    header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header ('Content-Disposition: attachment; filename=' . basename ($filename) . ';');
    header ('Content-Transfer-Encoding: binary');
  }
  else
  {
    header ('Expires: Tue, 1 Jan 1980 00:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
    header ('Cache-Control: no-store, no-cache, must-revalidate');
    header ('Cache-Control: post-check=0, pre-check=0', false);
    header ('Pragma: no-cache');
    header ('X-Powered-By: ' . VERSION . ' (c) ' . date ('Y') . ' ' . $SITENAME);
    header ('Accept-Ranges: bytes');
    header ('Connection: close');
    header ('Content-Transfer-Encoding: binary');
    header ('Content-Description: File Transfer');
    header ('' . 'Content-Type: ' . $mtype);
    header (('' . 'Content-Disposition: attachment; filename="' . $filename . '"'));
    header ('Content-Length: ' . $filesize);
  }

  ob_implicit_flush (true);
  $file = @fopen ($file_path, 'rb');
  if ($file)
  {
    while (!feof ($file))
    {
      print fread ($file, 1024 * 8);
      flush ();
      if (connection_status () != 0)
      {
        @fclose ($file);
        exit ();
        continue;
      }
    }

    @fclose ($file);
  }

?>
