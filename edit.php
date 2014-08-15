<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('E_VERSION', '1.6');
  $is_mod = is_mod ($usergroups);
  $lang->load ('edit');
  $lang->load ('upload');
  $id = (isset ($_GET['id']) ? intval ($_GET['id']) : (isset ($_POST['id']) ? intval ($_POST['id']) : 0));
  if (!is_valid_id ($id))
  {
    print_no_permission (true);
    exit ();
  }

  $res = sql_query ('SELECT filename,owner,name,descr,category,visible,anonymous,free,silver,banned,sticky,offensive,t_image,t_link,isnuked,isrequest,doubleupload,allowcomments,isScene FROM torrents WHERE id = ' . sqlesc ($id));
  $row = mysql_fetch_assoc ($res);
  if (!$row)
  {
    stderr ($lang->global['error'], $lang->global['notorrentid']);
  }

  if (($CURUSER['id'] != $row['owner'] AND !$is_mod))
  {
    print_no_permission (true);
  }

  stdhead (sprintf ($lang->edit['edittorrent1'], $row['name']));
  $returnto = (isset ($_GET['returnto']) ? fix_url ($_GET['returnto']) : fix_url ($_SERVER['HTTP_REFERER']));
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '
<form method="post" name="edittorrent" action="takeedit.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="' . $id . '">
<input type="hidden" name="returnto" value="' . $returnto . '">';
  require_once INC_PATH . '/functions_category.php';
  $s = ts_category_list ('type', $row['category']);
  echo '<s';
  echo 'cript type="text/javascript">
function toggleuploadmode(mode)
{
    switch (mode)
    {
        case 0:
            show(\'upfile\', \'block\');
            hide(\'upurl\');
            break;
        case 1:
            hide(\'upfile\');
            show(\'upurl\', \'block\');
            break;
    }
}
function focusfield(fl) {
    if (fl.value=="';
  echo $lang->upload['field23'];
  echo '")
	{
        fl.value=\'\';
        fl.style.color=\'black\';
    }
}
function show(id, type)
{
    var o = document.getElementById(id);
    if (o)
        o.style.display = type || \'\';
}

function hide(id)
{
    var o = document.getElementById(id);
    if (o)
        o.style.display = \'none\';
}
</script>
';
  if ($is_mod)
  {
    $fa = $lang->edit['fd'];
    $fb = '<input type="checkbox" name="free"' . ($row['free'] == 'yes' ? ' checked="checked"' : '') . ' value="1"> ' . $lang->edit['fd2'];
    $ra = $lang->upload['silver'];
    $rb = '<input type="checkbox" name="silver"' . ($row['silver'] == 'yes' ? ' checked="checked"' : '') . ' value="1"> ' . $lang->upload['silver2'];
    $ba = $lang->edit['banned'];
    $bb = '<input type="checkbox" name="banned"' . ($row['banned'] == 'yes' ? ' checked="checked"' : '') . ' value="1"> ' . $lang->edit['banned2'];
    $na = $lang->edit['nuked'];
    $nb = '<input type="checkbox" name="isnuked"' . ($row['isnuked'] == 'yes' ? ' checked="checked"' : '') . ' value="1"> ' . $lang->edit['nuked2'];
    $za = $lang->edit['request'];
    $zb = '<input type="checkbox" name="isrequest"' . ($row['isrequest'] == 'yes' ? ' checked="checked"' : '') . ' value="1"> ' . $lang->edit['request2'];
    $sa = $lang->edit['sticky'];
    $sb = '<input type="checkbox" name="sticky"' . ($row['sticky'] == 'yes' ? ' checked="checked"' : '') . ' value="yes"> ' . $lang->edit['sticky2'];
    $da = $lang->edit['da'];
    $db = '<input type="checkbox" name="doubleupload"' . ($row['doubleupload'] == 'yes' ? ' checked="checked"' : '') . ' value="yes"> ' . $lang->edit['db'];
    $ca = $lang->edit['ca'];
    $cb = '<input type="checkbox" name="allowcomments"' . ($row['allowcomments'] == 'yes' ? ' checked="checked"' : '') . ' value="yes"> ' . $lang->edit['cb'];
  }

  $postoptionstitle = array ('19' => $lang->edit['tf'], '1' => $lang->edit['torrentname'], '2' => $lang->edit['nfofile'], '3' => $lang->upload['field21'], '4' => $lang->upload['field22'], '5' => $lang->edit['type'], '6' => $lang->edit['visible'], '7' => $lang->edit['au'], '8' => $fa, '9' => $ra, '10' => $ba, '11' => $sa, '12' => $lang->edit['offensive'], '13' => $na, '14' => $za, '15' => $da, '16' => $ca, '17' => $lang->upload['scene'], '18' => $lang->upload['finfo']);
  $query = sql_query ('SELECT video_info, audio_info FROM ts_torrents_details WHERE tid = ' . sqlesc ($id));
  if (0 < mysql_num_rows ($query))
  {
    $Torrent_Details = mysql_fetch_assoc ($query);
    $video_info = @explode ('~', $Torrent_Details['video_info']);
    $audio_info = @explode ('~', $Torrent_Details['audio_info']);
  }

  $postoptions = array ('19' => '
			<input type="file" name="file" size="60" /><br />' . $lang->edit['tf2'], '1' => '
			<input type="text" size="60" name="filename" value="' . htmlspecialchars ($row['filename']) . '"' . (!$is_mod ? ' disabled="disabled"' : '') . ' />
			', '2' => '
			<input type="radio" name="nfoaction" value="keep" checked="checked" />' . $lang->edit['keepcurrent'] . '<br />
			<input type="radio" name="nfoaction" value="update" />' . $lang->edit['update'] . '<br />
			<input type="file" name="nfo" size="60">', '3' => '
			<div id="upurl">
			<input type="text" name="t_image_url" size="70" id="specialboxg" value="' . (!empty ($row['t_image']) ? unhtmlspecialchars ($row['t_image']) : $lang->upload['field23']) . '" onfocus="focusfield(this)" /> ' . (!empty ($row['t_image']) ? '[<b><a href="' . $BASEURL . '/takeedit.php?id=' . $id . '&remove_image=true" />X</a></b>]' : '') . '
			</div>
			<div id="upfile" style="display: none">
			<input type="file" name="t_image_file" size="70" id="specialboxg" /><br />
			</div>
			<b>Allowed file types: Jpg, Gif, Png</b>', '4' => '<input type="text" name="t_link" id="specialboxg" size="70" value="' . htmlspecialchars_uni ($row['t_link']) . '"> ' . (!empty ($row['t_link']) ? '[<b><a href="' . $BASEURL . '/takeedit.php?id=' . $id . '&remove_link=true" />X</a></b>]' : '') . '', '5' => $s, '6' => '<input type="checkbox" name="visible"' . ($row['visible'] == 'yes' ? ' checked="checked"' : '') . ' value="1" /> ' . $lang->edit['visible2'], '7' => '<input type="checkbox" name="anonymous"' . ($row['anonymous'] == 'yes' ? ' checked="checked"' : '') . ' value="1" />  ' . $lang->edit['au2'], '8' => $fb, '9' => $rb, '10' => $bb, '11' => $sb, '12' => '<input type="checkbox" name="offensive"' . ($row['offensive'] == 'yes' ? ' checked="checked"' : '') . ' value="yes" /> ' . $lang->edit['offensive2'], '13' => $nb, '14' => $zb, '15' => $db, '16' => $cb, '17' => '<input type="checkbox" name="scene"' . (0 < $row['isScene'] ? ' checked="checked"' : '') . ' value="yes" /> ' . $lang->upload['scene2'], '18' => '<table width="85%" border="0" cellpadding="2" cellspacing="0">
					<tr>
						<td colspan="2" class="subheader">' . $lang->upload['video'] . '</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['codec'] . '</td><td><input type="text" size="15" name="video[codec]" value="' . htmlspecialchars_uni ($video_info[0]) . '" /></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['bitrate'] . '</td><td><input type="text" size="15" name="video[bitrate]" value="' . htmlspecialchars_uni ($video_info[1]) . '" /> kbps</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['resulation'] . '</td><td><input type="text" size="15" name="video[resulation]" value="' . htmlspecialchars_uni ($video_info[2]) . '" /></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['length'] . '</td><td><input type="text" size="15" name="video[length]" value="' . htmlspecialchars_uni ($video_info[3]) . '" /> ' . $lang->global['minutes'] . '</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['quality'] . '</td><td><input type="text" size="15" name="video[quality]" value="' . htmlspecialchars_uni ($video_info[4]) . '" /> 1-10</td>
					</tr>
					<tr>
						<td colspan="2" class="subheader">' . $lang->upload['audio'] . '</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['codec'] . '</td><td><input type="text" size="15" name="audio[codec]" value="' . htmlspecialchars_uni ($audio_info[0]) . '" /></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['bitrate'] . '</td><td><input type="text" size="15" name="audio[bitrate]" value="' . htmlspecialchars_uni ($audio_info[1]) . '" /> kbps</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['frequency'] . '</td><td><input type="text" size="15" name="audio[frequency]" value="' . htmlspecialchars_uni ($audio_info[2]) . '" /></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">' . $lang->upload['language'] . '</td><td><input type="text" size="15" name="audio[language]" value="' . htmlspecialchars_uni ($audio_info[3]) . '" /></td>
					</tr>
					<tr><td colspan="2" align="center">' . $lang->upload['enote'] . '</td></tr>
				</table>
	');
  $str .= insert_editor (true, $row['name'], $row['descr'], $lang->edit['edittorrent2'], sprintf ($lang->edit['edittorrent1'], htmlspecialchars_uni ($row['name'])), $postoptionstitle, $postoptions, false);
  $str .= '</form>';
  echo $str;
  if (($is_mod OR ($usergroups['candeletetorrent'] == 'yes' AND $CURUSER['id'] == $row['owner'])))
  {
    require_once INC_PATH . '/class_page_check.php';
    $newpage = new page_verify ();
    $newpage->create ('delete');
    print '<form method="post" action="delete.php">
';
    print '<table border="0" cellspacing="0" cellpadding="5" width="100%">
';
    print '<tr><td colspan="2" class="thead">' . $lang->edit['deletetorrent'] . '</td></tr>';
    print '<td><input name="reasontype" type="radio" value="1"> ' . $lang->edit['dead'] . '</td><td> ' . $lang->edit['dead2'] . '</td></tr>
';
    print '<tr><td><input name="reasontype" type="radio" value="2"> ' . $lang->edit['dupe'] . '</td><td><input type="text" size="40" name="reason[]"  id="specialboxn"></td></tr>
';
    print '<tr><td><input name="reasontype" type="radio" value="3"> ' . $lang->edit['nuked'] . '</td><td><input type="text" size="40" name="reason[]"  id="specialboxn"></td></tr>
';
    print '<tr><td><input name="reasontype" type="radio" value="4"> ' . $lang->edit['rules'] . '</td><td><input type="text" size="40" name="reason[]"  id="specialboxn"> <strong>' . $lang->edit['req'] . '</strong></td></tr>';
    print '<tr><td><input name="reasontype" type="radio" value="5" checked> ' . $lang->edit['other'] . '</td><td><input type="text" size="40" name="reason[]" id="specialboxn"> <strong>' . $lang->edit['req'] . '</strong></td></tr>
';
    print '' . '<input type="hidden" name="id" value="' . $id . '">
';
    print '<input type="hidden" name="returnto" value="' . $returnto . '" />
';
    print '<td colspan="2" align="center"><input type=submit value=\'' . $lang->global['buttondelete'] . '\' class=button></td></tr>
';
    print '</table></form></p>';
  }

  stdfoot ();
?>
