<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function scan_file ($filename)
  {
    global $f_upload_path;
    $form = array ('file' => '@' . $f_upload_path . $filename, 'hidearc' => '1', 'showlink' => '1', 'usedaemon' => '1', 'dochk' => 'Submit');
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_URL, 'http://www.kaspersky.com/scanforvirus/');
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $form);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    $CURLResults = curl_exec ($ch);
    curl_close ($ch);
    $CURLResults = str_replace (array ('
', '
'), '', $CURLResults);
    $contents = preg_match_all ('#<table border=0 cellspacing=0 cellpadding=0 width=100%><td><p><b>Scanned file:(.*)</td></tr></table></td></tr></table>#U', $CURLResults, $results, PREG_SET_ORDER);
    return $results[0][0];
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('MA_VERSION', '0.3 by xam');
  include INC_PATH . '/readconfig_forumcp.php';
  $f_upload_path = preg_replace ('#[^a-z|A-Z]#', '', $f_upload_path);
  $f_upload_path = $_SERVER['DOCUMENT_ROOT'] . '/tsf_forums/' . $f_upload_path . '/';
  if ((isset ($_GET['scan_file']) AND !empty ($_GET['scan_file'])))
  {
    $filename = trim ($_GET['scan_file']);
    $filename = base64_decode ($filename);
    $filename = @rawurlencode (@basename ($filename));
    $filename = @str_replace (array ('"', '\'', '\\', '/'), '', $filename);
    if (!file_exists ($f_upload_path . $filename))
    {
      $scan_file = 'File does not exists!';
    }
    else
    {
      $scan_file = scan_file ($filename);
    }
  }

  if ((((isset ($_GET['delete_file']) AND !empty ($_GET['delete_file'])) AND $a_id = intval ($_GET['a_id'])) AND is_valid_id ($a_id)))
  {
    $filename = trim ($_GET['delete_file']);
    $filename = base64_decode ($filename);
    $filename = @rawurlencode (@basename ($filename));
    $filename = @str_replace (array ('"', '\'', '\\', '/'), '', $filename);
    if (!file_exists ($f_upload_path . $filename))
    {
      $delete_file = 'File does not exists!';
    }
    else
    {
      $query = sql_query ('DELETE FROM ' . TSF_PREFIX . 'attachments WHERE a_id = ' . sqlesc ($a_id));
      @unlink ($f_upload_path . $filename);
      $delete_file = 'Attachment has been deleted!';
    }
  }

  $_orderby = ' ORDER BY a.a_name ASC';
  $_type = ($_GET['type'] == 'ASC' ? '&type=DESC' : '&type=ASC');
  $_allowed = array ('a_id', 'a_name', 'a_size', 'username', 'a_count');
  if ((isset ($_GET['order_by']) AND @in_array ($_GET['order_by'], $_allowed)))
  {
    $type = str_replace ('&type=', ' ', $_type);
    switch ($_GET['order_by'])
    {
      case 'a_id':
      {
        $_orderby = ' ORDER BY a.a_id' . $type;
        $_link .= '&order_by=a_id' . ($_GET['type'] ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '');
        break;
      }

      case 'a_name':
      {
        $_orderby = ' ORDER BY a.a_name' . $type;
        $_link .= '&order_by=a_name' . ($_GET['type'] ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '');
        break;
      }

      case 'a_size':
      {
        $_orderby = ' ORDER BY a.a_size' . $type;
        $_link .= '&order_by=a_size' . ($_GET['type'] ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '');
        break;
      }

      case 'username':
      {
        $_orderby = ' ORDER BY u.username' . $type;
        $_link .= '&order_by=username' . ($_GET['type'] ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '');
        break;
      }

      case 'a_count':
      {
        $_orderby = ' ORDER BY a.a_count' . $type;
        $_link .= '&order_by=a_count' . ($_GET['type'] ? '&type=' . htmlspecialchars_uni ($_GET['type']) : '');
      }
    }
  }

  $res = sql_query ('SELECT COUNT(*) FROM ' . TSF_PREFIX . 'attachments');
  $row = mysql_fetch_row ($res);
  $total = $row[0];
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $total, $_this_script_ . $_link . '&');
  ($query = sql_query ('SELECT a.*, p.uid, u.username, g.namestyle FROM ' . TSF_PREFIX . 'attachments a LEFT JOIN ' . TSF_PREFIX . 'posts p ON (a.a_pid=p.pid) LEFT JOIN users u ON (u.id=p.uid) LEFT JOIN usergroups g ON (u.usergroup=g.gid)' . $_orderby . ' ' . $limit) OR sqlerr (__FILE__, 121));
  stdhead ('Manage Attachments');
  if (isset ($scan_file))
  {
    _form_header_open_ ('Powered by Kaspersky Anti-Virus File Scanner');
    echo '<tr><td>' . $scan_file . '</td></tr>';
    _form_header_close_ () . '<br />';
  }

  if (isset ($delete_file))
  {
    _form_header_open_ ('Delete Attachment');
    echo '<tr><td>' . $delete_file . '</td></tr>';
    _form_header_close_ () . '<br />';
  }

  echo $pagertop;
  $externalpreview = '<div style=\'position: absolute; left:500px; width:200px;height:20px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\'>Scanning file... Please wait...</div></div>';
  $externalpreview2 = '<div style=\'position: absolute; left:500px; width:200px;height:20px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\'>Deleting file... Please wait...</div></div>';
  _form_header_open_ ('Manage Attachments', 6);
  $str = '
<script type="text/javascript">
	function scan_file(FileName,Filesize)
	{
		document.getElementById(FileName).innerHTML = "' . $externalpreview . '"
		if (Filesize > 999999)
		{
			document.getElementById(FileName).innerHTML = "";
			alert("Only one file of up to 1 MB can be checked at any one time.");
			return false;
		}
		else
		{
			window.location = "' . $_this_script_ . ($_link ? $_link . '&page=' . intval ($_GET['page']) : '&page=' . intval ($_GET['page'])) . '&scan_file="+FileName;
		}
	}
	function confirm_delete(FileName, fID)
	{
		var CONFIRM = confirm("Are you sure that you want to delete this file?");
		if (CONFIRM)
		{
			document.getElementById(FileName).innerHTML = "' . $externalpreview2 . '"
			window.location = "' . $_this_script_ . ($_link ? $_link . '&page=' . intval ($_GET['page']) : '&page=' . intval ($_GET['page'])) . '&a_id="+fID+"&delete_file="+FileName;
		}
		else
		{
			return false;
		}
	}
</script>
<tr>
	<td class="subheader" width="5%" align="center"><a href="' . $_this_script_ . '&order_by=a_id&page=' . intval ($_GET['page']) . $_type . '">ID</a></td>
	<td class="subheader" width="45%" align="left"><a href="' . $_this_script_ . '&order_by=a_name&page=' . intval ($_GET['page']) . $_type . '">Filename</a></td>
	<td class="subheader" width="10%" align="center"><a href="' . $_this_script_ . '&order_by=a_size&page=' . intval ($_GET['page']) . $_type . '">Filesize</a></td>
	<td class="subheader" width="15%" align="left"><a href="' . $_this_script_ . '&order_by=username&page=' . intval ($_GET['page']) . $_type . '">Uploader</a></td>
	<td class="subheader" width="10%" align="center"><a href="' . $_this_script_ . '&order_by=a_count&page=' . intval ($_GET['page']) . $_type . '">Downloads</a></td>
	<td class="subheader" width="15%" align="center">Action</td>
</tr>';
  if (0 < $total)
  {
    $total_file_size = 0;
    while ($a = mysql_fetch_assoc ($query))
    {
      $total_file_size += $a['a_size'];
      $encodefilename = base64_encode ($a['a_name']);
      $str .= '
		<tr>
			<td align="center">' . ts_nf ($a['a_id']) . '</td>
			<td align="left"><a href="' . $BASEURL . '/tsf_forums/attachment.php?aid=' . $a['a_id'] . '&tid=' . $a['a_tid'] . '&pid=' . $a['a_pid'] . '" target="_blank">' . htmlspecialchars_uni ($a['a_name']) . '</a> <span id="' . $encodefilename . '"></span></td>
			<td align="center">' . mksize ($a['a_size']) . '</td>
			<td align="left"><a href="' . $BASEURL . '/userdetails.php?id=' . $a['uid'] . '">' . get_user_color ($a['username'], $a['namestyle']) . '</a></td>
			<td align="center">' . ts_nf ($a['a_count']) . '</td>
			<td align="center"><input type="button" name="delete" value="delete" class=button onclick="confirm_delete(\'' . $encodefilename . '\', \'' . $a['a_id'] . '\')"> <input type="button" name="scan" value="scan" class=button onclick="scan_file(\'' . $encodefilename . '\', \'' . $a['a_size'] . '\');"></td>
		</tr>
		';
    }

    $str .= '<tr><td colspan="2" align="right">Total Size:</td><td colspan="4" align="left">' . mksize ($total_file_size) . '</td></tr>';
  }
  else
  {
    $str .= '<tr><td colspan="6">There is no attachment to show.</td></tr>';
  }

  echo $str;
  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
