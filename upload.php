<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/
require_once('global.php');
gzip();
dbconn();
loggedinorreturn();
maxsysop();
parked();

define ('UL_VERSION', '2.6.2 ');

$lang->load('upload');
$is_mod = is_mod($usergroups);

if ($usergroups['canupload'] != 'yes')
{
	print_no_permission(false,true,$lang->upload['uploaderform']);
	exit;
}

$query = sql_query("SELECT canupload FROM ts_u_perm WHERE userid = ".sqlesc($CURUSER['id'])) or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($query) > 0)
{
	$uploadperm = mysql_fetch_assoc($query);
	if ($uploadperm['canupload'] == '0')
	{
		print_no_permission(false,true,$lang->upload['uploaderform']);
		exit;
	}
}

$upload_step = isset($_GET['upload_step']) ? intval($_GET['upload_step']) : 1;

if ($upload_step == 1 AND strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$subject = trim($_POST['subject']);
	$message = trim($_POST['message']);
	if (!empty($subject))
	{
		$query = sql_query("SELECT id, name FROM torrents WHERE (name LIKE ".sqlesc("%".$subject."%")." OR descr LIKE ".sqlesc("%".$subject."%").")") or sqlerr(__FILE__,__LINE__);
		if (($total_results=mysql_num_rows($query)) > 0)
		{
			$ptr='
			<form method="post" action="upload.php?upload_step=2">
			<input type="hidden" name="subject" value="'.htmlspecialchars_uni($subject).'">
			<input type="hidden" name="message" value="'.htmlspecialchars_uni($message).'">
			';
			while ($torrent=mysql_fetch_assoc($query))
			{
				$ptr .= '
				<tr>
					<td><a href="'.$BASEURL.'/details.php?id='.intval($torrent['id']).'">'.htmlspecialchars_uni($torrent['name']).'</a></td>
				</tr>
				';
			}
			stdhead($lang->upload['head'].' - '.$lang->upload['u_step'].'2');
			echo '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">
						'.$lang->upload['s_results'].' ('.$total_results.')
					</td>
				</tr>
				<tr>
					<td class="subheader">
						'.$lang->upload['s_results_title'].'
					</td>
				</tr>
				'.$ptr.'
				<tr>
					<td align="center"><input type="submit" value="'.$lang->upload['s_button1'].'"> <input type="button" value="'.$lang->upload['s_button2'].'" onclick="jumpto(\''.$BASEURL.'\')"></td>
				</tr>
			</table>
			</form>
			<br />
			';
			stdfoot();
			die;
		}
		else
		{
			$upload_step = 2;
		}
	}
}
else if ($upload_step == 2 AND strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$subject = trim($_POST['subject']);
	$message = trim($_POST['message']);
}
else if($upload_step == 3)
{
	$tid = intval($_GET['tid']);
	if (!is_valid_id($tid))
	{
		print_no_permission(true);
	}
	$query = sql_query("SELECT owner, name FROM torrents WHERE id = ".sqlesc($tid));
	$row = mysql_fetch_assoc($query);
	if (!$row)
		stderr($lang->global['error'], $lang->global['notorrentid']);

	if ($CURUSER['id'] != $row['owner'] && !$is_mod)
		print_no_permission(true);

	$query = sql_query("SELECT tid FROM ts_torrents_details WHERE tid = ".sqlesc($tid));
	if (mysql_num_rows($query) > 0)
	{
		stderr($lang->global['error'], $lang->upload['fierror']);
	}
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
	{
		$torrentname = htmlspecialchars_uni($row['name']);
		$video_info = implode('~', $_POST['video']);
		$audio_info = implode('~', $_POST['audio']);
		sql_query("INSERT INTO ts_torrents_details (tid,video_info,audio_info) VALUES ($tid, ".sqlesc($video_info).",".sqlesc($audio_info).")");
		redirect('details.php?id='.$tid.'&uploaded=1', sprintf($lang->upload['writelog2'], $tid, $torrentname, $CURUSER['username']));
		exit();
	}
	stdhead($lang->upload['head'].' - '.$lang->upload['u_step'].$upload_step, true, 'supernote');
	echo '
	<form method="post" action="'.$_SERVER['SCRIPT_NAME'].'?upload_step=3&tid='.$tid.'">
	<table width="100%" border="0" cellpadding="4" cellspacing="0">
		<tr>
			<td class="thead" colspan="2" align="center">'.$lang->upload['head'].' - '.$lang->upload['u_step'].$upload_step.': '.$lang->upload['finfoh'].'</td>
		</tr>
		<tr>
			<td valign="top" align="right" width="10%">'.$lang->upload['finfo'].'</td>
			<td>
				<table width="85%" border="0" cellpadding="2" cellspacing="0">
					<tr>
						<td colspan="2" class="subheader">'.$lang->upload['video'].'</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['codec'].'</td><td><input type="text" size="15" name="video[codec]"></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['bitrate'].'</td><td><input type="text" size="15" name="video[bitrate]"> kbps</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['resulation'].'</td><td><input type="text" size="15" name="video[resulation]"></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['length'].'</td><td><input type="text" size="15" name="video[length]"> '.$lang->global['minutes'].'</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['quality'].'</td><td><input type="text" size="15" name="video[quality]"> 1-10</td>
					</tr>
					<tr>
						<td colspan="2" class="subheader">'.$lang->upload['audio'].'</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['codec'].'</td><td><input type="text" size="15" name="audio[codec]"></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['bitrate'].'</td><td><input type="text" size="15" name="audio[bitrate]"> kbps</td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['frequency'].'</td><td><input type="text" size="15" name="audio[frequency]"></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="20%">'.$lang->upload['language'].'</td><td><input type="text" size="15" name="audio[language]"></td>
					</tr>
					<tr><td colspan="2" align="center">'.$lang->upload['enote'].'</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="'.$lang->upload['n_step'].'"> <input type="reset" value="'.$lang->global['buttonreset'].'"></td>
		</tr>
	</table>
	</form>
	';
	stdfoot();
	die();
}

stdhead($lang->upload['head'].' - '.$lang->upload['u_step'].$upload_step, true, 'supernote');
if (!empty($_GET['msg']))
{
	stdmsg(htmlspecialchars_uni(base64_decode(trim($_GET['msg']))));
}
$str2 = '';

if ($privatetrackerpatch == 'yes')
{
	$alink = $announce_urls[0];
	$str2 .= $lang->upload['alert1'];
	$str2 .= sprintf($lang->upload['info'], $announce_urls[0]);
}
else
{
	$str2 .= sprintf($lang->upload['info'], $announce_urls[0].'?passkey='.$CURUSER['passkey']);
	$alink = $announce_urls[0].'?passkey='.$CURUSER['passkey'];
}

if(!is_writable($torrent_dir))
	$str2 .= $lang->upload['alert2'];

if(empty($max_torrent_size))
	$str2 .= $lang->upload['alert3'];

define('IN_EDITOR', true);
include_once(INC_PATH.'/editor.php');

if ($upload_step == 2)
{
	$str = '<form enctype="multipart/form-data" action="takeupload.php" method="post" name="upload" onsubmit="document.upload.submit.value=\' '.$lang->global['pleasewait'].'\';document.upload.submit.disabled=true">
	<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_torrent_size.'">';

	require_once(INC_PATH.'/functions_category.php');
	$showcategories = ts_category_list('type',intval($_GET['type']));
	$lang->load('edit');

	if($is_mod)
	{
		$fa = $lang->edit['fd'];
		$fb = '<input type="checkbox" name="free" value="1" onclick="check_click()" /> '.$lang->edit['fd2'];

		$ra = $lang->upload['silver'];
		$rb = '<input type="checkbox" name="silver" value="1" onclick="check_click()" /> '.$lang->upload['silver2'];

		$sa = $lang->upload['field13'];
		$sb = '<input type="checkbox" name="sticky" value="yes">'.$lang->upload['field14'];
	}

	$postoptionstitle = array(
		'1'	=> $lang->upload['field0'],
		'2'	=>	$lang->upload['field1'],
		'3'	=>	$lang->upload['field4'],
		'4'	=>	$lang->upload['field21'],
		'5'	=>	$lang->upload['field22'],
		'6'	=>	$lang->upload['field8'],
		'7'	=>	$lang->upload['field19'],
		'9'	=>	$lang->upload['anonymous'],
		'10'	=>	$fa,
		'11'	=>	$ra,
		'12'	=>	$sa,
		'13'	=>	$lang->upload['field15'],
		'14'	=> 	$lang->upload['scene'],
	);
	?>
	<script type="text/javascript">
	function toggleuploadmode(mode)
	{
		switch (mode)
		{
			case 0:
				show('upfile', 'block');
				hide('upurl');
				break;
			case 1:
				hide('upfile');
				show('upurl', 'block');
				break;
		}
	};
	function focusfield(fl)
	{
		if (fl.value=="<?=$lang->upload['field23'];?>")
		{
			fl.value='';
			fl.style.color='black';
		}
	};
	function show(id, type)
	{
		var o = document.getElementById(id);
		if (o)
			o.style.display = type || '';
	};

	function hide(id)
	{
		var o = document.getElementById(id);
		if (o)
			o.style.display = 'none';
	};

	function isUrl(s)
	{
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
		return regexp.test(s);
	};

	function check_click ()
	{
		var error = "<?php echo $lang->upload['freesilvererror']; ?>";
		var free = document.forms[0].free;
		var silver = document.forms[0].silver;
		if (free.checked == true && silver.checked == true)
		{
			alert(error);
			free.checked = false;
			silver.checked = false;
			free.focus();
			return false;
		}
	};

	function check_click2 ()
	{
		var error = "<?php echo $lang->upload['nforippempty']; ?>";
		var nfofile = document.forms[0].nfo;
		if (nfofile.value.lastIndexOf(".nfo") == -1 && document.forms[0].nforip.checked == true)
		{
			alert(error);
			nfofile.focus();
			return false;
		}
	};

	function check_upload ()
	{
		var error1 = "<?php echo $lang->global['dontleavefieldsblank']; ?>";
		var error2 = "<?php echo $lang->upload['mindesclimit']; ?>";
		var error3 = "<?php echo $lang->upload['selectcategory']; ?>";
		var error4 = "<?php echo $lang->upload['invalid_url_link']; ?>";
		var error5 = "<?php echo $lang->upload['fileerror2']; ?>";
		var error6 = "<?php echo $lang->upload['fileerror3']; ?>";
		var error7 = "<?php echo sprintf($lang->upload['invalid_image'], 'GIF, JPG, PNG'); ?>";
		var error8 = "<?php echo $lang->upload['invalid_url_link']; ?>";

		var mindesclimit = 10;
		var message = document.forms[0].message_new;
		var torrentfile = document.forms[0].file;
		var type = document.forms[0].type;
		var nfofile = document.forms[0].nfo;
		var nforip = document.forms[0].nforip;
		var torrentimage = document.forms[0].t_image_file;
		var torrenturl = document.forms[0].t_image_url;
		var imdb = document.forms[0].t_link;

		if (message.value == "" && nforip.checked == false)
		{
			alert(error1);
			message.focus();
			return false;
		}
		else if (message.value.length < mindesclimit && nforip.checked == false)
		{
			alert(error2);
			message.focus();
			return false;
		}
		else if (torrentfile.value == "")
		{
			alert(error1);
			torrentfile.focus();
			return false;
		}
		else if (torrentfile.value.lastIndexOf(".torrent") == -1)
		{
			alert(error5);
			torrentfile.focus();
			return false;
		}
		else if (nfofile.value != "" && nfofile.value.lastIndexOf(".nfo") == -1)
		{
			alert(error6);
			nfofile.focus();
			return false;
		}
		else if (nforip.checked == true && nfofile.value.lastIndexOf(".nfo") == -1)
		{
			alert(error6);
			nfofile.focus();
			return false;
		}
		else if (torrentimage.value != "" && torrentimage.value.lastIndexOf(".gif") == -1 && torrentimage.value.lastIndexOf(".jpg") == -1 && torrentimage.value.lastIndexOf(".png") == -1)
		{
			alert(error7);
			torrentimage.focus();
			return false;
		}
		else if (torrenturl.value != "" && torrenturl.value != "<?php echo $lang->upload['field23']; ?>" && torrenturl.value.lastIndexOf(".gif") == -1 && torrenturl.value.lastIndexOf(".jpg") == -1 && torrenturl.value.lastIndexOf(".png") == -1)
		{
			alert(error7);
			torrenturl.focus();
			return false;
		}
		else if (torrenturl.value != "" && torrenturl.value != "<?php echo $lang->upload['field23']; ?>" && !isUrl(torrenturl.value))
		{
			alert(error8);
			torrenturl.focus();
			return false;
		}
		else if (imdb.value != "" && !isUrl(imdb.value))
		{
			alert(error8);
			imdb.focus();
			return false;
		}
		else if (type.value == "0")
		{
			alert(error3);
			type.focus();
			return false;
		}
		else
		{
			document.getElementById('loading-layer').style.display = 'block';
		}
	};
	</script>
	<?
	$enabledisable = ' disabled="disabled"';
	$info = '';
	if ($externalscrape == 'yes' && $usergroups['canexternal'] == 'yes')
	{
		$enabledisable = '';
		$info = '<br />'.$lang->upload['trackerurlinfo'];
	}

	$postoptions = array(
		'1'		=>'<input type="text" name="trackerurl" id="specialboxg" size="70" value="'.($_GET['trackerurl'] ? htmlspecialchars_uni(base64_decode($_GET['trackerurl'])) : $alink).'"'.$enabledisable.'>'.$info.'',
		'2'		=>	'<input type="file" name="file" id="specialboxn" size="70">',
		'3'		=>	'<input type="file" name="nfo" id="specialboxn" size="70"><br />'.$lang->upload['field5'],
		'4'		=>'
		<div id="upurl">
			<input type="text" name="t_image_url" size="70" id="specialboxg" value="'.($_GET['t_image_url'] ? htmlspecialchars_uni(base64_decode($_GET['t_image_url'])) : $lang->upload['field23']).'" onfocus="focusfield(this)">
		</div>
		<div id="upfile" style="display: none">
			<input type="file" name="t_image_file" size="70" id="specialboxg"'.($_GET['t_image_url'] ? htmlspecialchars_uni(base64_decode($_GET['t_image_url'])) : '').'><br />
		</div>
		'.$lang->upload['atypes'],
		'5'		=>'<input type="text" name="t_link" id="specialboxg" size="70" value="'.($_GET['t_link'] ? htmlspecialchars_uni(base64_decode($_GET['t_link'])) : '').'">',
		'6'		=>	$showcategories,
		'7'		=>	'<input type="checkbox" name="nforip" value="yes" onClick="check_click2()"'.($_GET['nforip'] == 'yes' ? ' checked="checked"' : '').'> '.$lang->upload['field20'],
		'9'		=>	'<input type="checkbox" name="uplver" value="yes"'.($_GET['uplver'] == 'yes' ? ' checked="checked"' : (preg_match('#I3#is', $CURUSER['options']) || preg_match('#I4#is', $CURUSER['options']) ? ' checked="checked"' : '')).'> '.$lang->upload['field12'],
		'10'		=>	$fb,
		'11'	=>$rb,
		'12'	=>	$sb,
		'13'	=>	'<input type="checkbox" name="offensive" value="yes"'.($_GET['offensive'] == 'yes' ? ' checked="checked"' : '').'> '.$lang->upload['field16'],
		'14'	=>	'<input type="checkbox" name="scene" value="yes"'.($_GET['scene'] == 'yes' ? ' checked="checked"' : '').'> '.$lang->upload['scene2']
	);
}
else
{
	$str = '<form action="upload.php" method="post" name="upload">';
}

$str .= insert_editor(true, ($_GET['subject'] ? base64_decode($_GET['subject']) : (!empty($subject) ? $subject : '')), ($_GET['message'] ? base64_decode($_GET['message']) : (!empty($message) ? $message : '')), $lang->upload['head'].' - '.$lang->upload['u_step'].$upload_step, $str2, $postoptionstitle, $postoptions, false, NULL, $lang->upload['n_step'], $upload_step == 2 ? ' onClick="return check_upload()"' : '', $lang->upload['field6']);
$str .= '</form>';

$str .= '
<a name=\'uploading\'></a>
<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; top: 1000px; width:300px;height:75px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'><font color=red><b>'.$lang->upload['showprogress'].'</b></font></div><br /><img src=\''.$BASEURL.'/'.$pic_base_url.'await.gif\' border=\'0\' /></div>';

echo $str;
stdfoot();
?>
