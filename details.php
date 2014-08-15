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
require_once(INC_PATH.'/commenttable.php');
gzip();
dbconn(true);
loggedinorreturn();
maxsysop();
if (!defined('NcodeImageResizer'))
{
	define('NcodeImageResizer', true);
}
define('D_VERSION', '3.5.5');

if($usergroups['candownload'] != 'yes')
{
	$lang->load('browse');
	print_no_permission(false,true,sprintf($lang->browse['downloaddisabledmsg'], ''));
}

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : '');
$is_mod = is_mod($usergroups);

if (!is_valid_id($id))
{
	print_no_permission(true);
}

$tab = 'details';
if (isset($_GET['tab']) AND $_GET['tab'] != '')
{
	$tab = htmlspecialchars_uni($_GET['tab']);
}

$query = sql_query('SELECT t.name, t.allowcomments, t.banned, t.descr, t.category, t.size, t.numfiles, t.anonymous, t.added, t.comments, t.hits, t.times_completed, t.leechers, t.seeders, t.owner, t.free, t.sticky, t.offensive, t.silver, t.t_image, t.t_link, t.isnuked, t.WhyNuked, t.isrequest, t.ts_external, t.doubleupload, t.isScene, n.nfo, c.name as categoryname, c.vip, c.pid, c.type, c.id as categoryid, d.video_info, d.audio_info, u.username, u.donor, u.warned, u.leechwarn, g.namestyle FROM torrents t LEFT JOIN ts_nfo n ON (t.id=n.id) LEFT JOIN categories c ON (t.category=c.id) LEFT JOIN ts_torrents_details d ON (t.id=d.tid) LEFT JOIN users u ON (t.owner=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.id = '.sqlesc($id)) or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($query) == 0 OR !$query OR !($torrent = mysql_fetch_assoc($query)))
{
	stderr($lang->global['error'], $lang->global['notorrentid']);
}
elseif ($usergroups['canviewviptorrents'] != 'yes' && $torrent['vip'] == 'yes')
	stderr($lang->global['error'], $lang->global['viptorrent'], false);
elseif ($torrent["banned"] == "yes" && !$is_mod)
	stderr($lang->global['error'], $lang->global['torrentbanned']);
//XXX Torrents Confirm Dialog by Danix
elseif ($torrent["category"] == "9")
{
    $alert = ('
                <script type="text/javascript">
                    //<![CDATA[
                    var alert = confirm("'.$lang->global['xxxtorrent'].'")
                    if (alert)
                    {
                        alert("'.$lang->global['xxxtorrent'].'")
                    }
                    else
                    {
                        window.location = "'.$BASEURL.'/";
                    }
                    //]]>
                </script>');
    echo $alert;
} 
$lang->load('details');
$lang->load('upload');
$lang->load('quick_editor');
require_once(INC_PATH.'/class_tsquickbbcodeeditor.php');
$QuickEditor = new TSQuickBBCodeEditor();
$QuickEditor->ImagePath = $BASEURL.'/'.$pic_base_url;
$QuickEditor->SmiliePath = $BASEURL.'/'.$pic_base_url.'smilies/';

include(INC_PATH.'/functions_quick_editor.php');
define('TOTAL_FILES', $torrent['numfiles']);
include(INC_PATH.'/functions_filelist.php');
require_once(INC_PATH.'/functions_mkprettytime.php');

if ($torrent['type'] == 's')
{
	require(TSDIR.'/'.$cache.'/categories.php');
	foreach ($_categoriesC as $catarray)
	{
		if ($catarray['id'] == $torrent['pid'])
		{
			$parentcategory = $catarray['name'];
			$parentcatid = $catarray['id'];
			break;
		}
	}
	if ($parentcategory && $parentcatid)
	{
		$seolink = ts_seo($parentcatid,$parentcategory,'c');
		$seolink2 = ts_seo($torrent['categoryid'],$torrent['categoryname'],'c');
		$torrent["categoryname"] = '<a href="'.$seolink.'" target="_self" alt="'.$parentcategory.'" title="'.$parentcategory.'" />'.$parentcategory.'</a> / <a href="'.$seolink2.'" target="_self" alt="'.$torrent['categoryname'].'" title="'.$torrent['categoryname'].'" />'.$torrent['categoryname'].'</a>';
	}
}
else
{
	$seolink2 = ts_seo($torrent['categoryid'],$torrent['categoryname'],'c');
	$torrent["categoryname"] = '<a href="'.$seolink2.'" target="_self" alt="'.$torrent['categoryname'].'" title="'.$torrent['categoryname'].'" />'.$torrent['categoryname'].'</a>';
}

$isfree = ($torrent['free'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'freedownload.gif" class="inlineimg" />' : '');
$issilver = ($torrent['silver'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'silverdownload.gif" class="inlineimg" />' : '');
$isdoubleupload = ($torrent['doubleupload'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'x2.gif" class="inlineimg" />' : '');

stdhead(sprintf($lang->details['detailsfor'], $torrent['name']), true, 'supernote','INDETAILS');

$gigs = $CURUSER['downloaded'] / (1024*1024*1024);
$ratio = ($CURUSER['downloaded'] > 0 ? $CURUSER['uploaded'] / $CURUSER['downloaded'] : 0);
$percentage = $ratio * 100;
if ($gigs > $hitrun_gig AND $ratio <= $hitrun_ratio AND $torrent['owner'] != $CURUSER['id'] AND !$is_mod AND $CURUSER['downloaded'] <> 0)
{
	$warning_message = show_notice(sprintf($lang->details['hitrunwarning'], number_format($ratio, 2), number_format($percentage, 3), $hitrun_ratio), true);
}
elseif ($ratio <= ($hitrun_ratio + 0.2) AND $torrent['owner'] != $CURUSER['id'] AND !$is_mod AND $CURUSER['downloaded'] <> 0)
{
	$warning_message = show_notice(sprintf($lang->details['hitrunwarning2'], number_format($ratio, 2), mksize($percentage), $hitrun_ratio, $id), true);
}

if (isset($warning_message))
{
	echo $warning_message;
}

$sratio = $torrent['leechers'] > 0 ? $torrent['seeders'] / $torrent['leechers'] : 1;
$lratio = $torrent['seeders'] > 0 ? $torrent['leechers'] / $torrent['seeders'] : 1;
$video_info = @explode('~', $torrent['video_info']);
$audio_info = @explode('~', $torrent['audio_info']);

if ($usergroups['canreport'] == 'yes')
{
	$lang->load('report');
	$head = $lang->report['report'].' TORRENT';
	$report = '
	'.($useajax == 'yes' ? '<script type="text/javascript" src="./scripts/quick_report.js"></script>
	<div id="QuickReportDone" name="QuickReportDone" style="display: none;">'.$lang->report['done'].'</div>' : '').'
	<div id="ShowQuickReport" name="ShowQuickReport" style="display: inline;">
		<form method="post" action="report.php" name="quickreport" id="quickreport">
		<input type="hidden" name="action" value="reporttorrent">
		<input type="hidden" name="do" value="save">
		<input type="hidden" name="siv" value="false">
		<input type="hidden" name="returnto" value="details.php?id='.$id.'&tab=report&reported=true">
		<input type="hidden" name="reportid" value="'.$id.'">
		<table border="0" width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<td class="rowhead">'.$lang->report['reporttype'].'</td>
				<td>'.$head.'</td>
			</tr>
			<tr>
				<td class="rowhead">'.$lang->report['reportid'].'</td>
				<td>'.$id.'</td>
			</tr>
			<tr>
				<td class="rowhead">'.$lang->report['reason'].'</td>
				<td><textarea name="reason" id="reason" rows="6" cols="90"></textarea><br />
				'.($useajax == 'yes' ? '<input type="button" class="button" value="'.$lang->global['buttonreport'].'" name="quickreportbutton" id="quickreportbutton" onclick="javascript:TSajaxquickreport(\''.$id.'\');" />' : '<input type="submit" name="submit" value="'.$lang->global['buttonreport'].'" class="button" />').' <img src="'.$BASEURL.'/'.$pic_base_url.'ajax-loader.gif" class="inlineimg" border="0" alt="" title="" id="report-loading-layer" style="display:none;" /></td>
			</tr>
		</table>
		</form>
	</div>';
}
else
{
	$report = $lang->global['nopermission'];
}
$showcommenttable = '';
$count = TSRowCount('id', 'comments', 'torrent='.$id);
if (!$count)
{
	$showcommenttable .= '
	<table class="none" border="0" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td>
				<div style="display: block;" id="ajax_comment_preview">'.$lang->details['nocommentsyet'].'</div>
				<div style="display: block;" id="ajax_comment_preview2"></div>
			</td>
		</tr>
	</table>
	';
}
else
{
	list($pagertop, $pagerbottom, $limit) = pager($ts_perpage, $count, $_SERVER['SCRIPT_NAME'].'?id='.$id.'&tab=comments&', array('lastpagedefault' => '1'), false);
	$subres = sql_query("SELECT c.id, c.torrent as torrentid, c.text, c.user, c.added, c.editedby, c.editedat, c.modnotice, c.modeditid, c.modeditusername, c.modedittime, c.totalvotes, uu.username as editedbyuname, gg.namestyle as editbynamestyle, u.added as registered, u.enabled, u.warned, u.leechwarn, u.username, u.title, u.usergroup, u.last_access, u.options, u.donor, u.uploaded, u.downloaded, u.avatar as useravatar, u.signature, g.title as grouptitle, g.namestyle FROM comments c LEFT JOIN users uu ON (c.editedby=uu.id) LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid) LEFT JOIN users u ON (c.user=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE c.torrent = ".sqlesc($id)." ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);

	$allrows = array();
	while ($subrow = mysql_fetch_assoc($subres))
	{
		$allrows[] = $subrow;
	}
	$showcommenttable .= $pagertop.commenttable($allrows,'','',false,true,true).$pagerbottom;
}

$rowspan = 9;
$reseed = '';
if ($torrent['seeders'] == 0 && $torrent['ts_external'] == 'no')
{
	$reseed = '
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['askreseed'].'</td>
		<td valign="top" style="padding-left: 5px;">'.sprintf($lang->details['askreseed2'], $id).'</td>
	</tr>';
	$rowspan++;
}

if ($torrent['isScene'] > 0)
{
	$rowspan++;
	$isScene = '
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->upload['scene'].'</td>
		<td valign="top" style="padding-left: 5px;">'.$lang->global['greenyes'].'</td>
	</tr>
	';
	$rowspan++;
	$isScene .= '
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['scene3'].'</td>
		<td valign="top" style="padding-left: 5px;">'.mkprettytime($torrent['isScene']).'</td>
	</tr>
	';
}

if (isset($_GET['cerror']))
{
	switch ($_GET['cerror'])
	{
		case 1:
			$cerror = $lang->global['notorrentid'];
		break;
		case 2:
			$cerror = $lang->global['dontleavefieldsblank'];
		break;
		case 3:
			$cerror = sprintf($lang->global['flooderror'], $usergroups['floodlimit'], $lang->comment['floodcomment'], "-");
		break;
		default:
			$cerror = $lang->global['error'];
		break;
	}
}

if ($usergroups['canpeers'] != 'yes' OR $torrent['ts_external'] == 'yes')
{
	$peerstable = sprintf($lang->details['peers3'], ts_nf($torrent['seeders']), ts_nf($torrent['leechers']), (ts_nf($torrent['seeders'] + $torrent['leechers']))).($torrent['seeders'] == 0 && $torrent['ts_external'] == 'no' ? '<br />'.sprintf($lang->details['askreseed2'],$id) : '');
}
else
{
	function getagent($httpagent='', $peer_id="")
	{
		global $lang;
		return ($httpagent ? $httpagent : ($peer_id ? $peer_id : $lang->global['unknown']));
	}

	function dltable($name, $arr, $torrent)
	{
		global $CURUSER,$pic_base_url, $lang,$usergroups,$is_mod, $BASEURL;
		$totalcount = count($arr);
		$p = '<b>'.$totalcount.' '.$name.'</b>';
		if (!count($totalcount))
			return $p;
		$s = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td colspan="11" class="thead">'.$p.'</td>
					</tr>
					<tr>
						<td class="subheader">'.$lang->details['userip'].'</td>
						<td class="subheader" align="center">'.$lang->details['conn'].'</td>
						<td class="subheader" align="center">'.$lang->details['up'].'</td>
						<td class="subheader" align="center">'.$lang->details['urate'].'</td>
						<td class="subheader" align="center">'.$lang->details['down'].'</td>
						<td class="subheader" align="center">'.$lang->details['drate'].'</td>
						<td class="subheader" align="center">'.$lang->details['ratio'].'</td>
						<td class="subheader" align="center">'.$lang->details['done'].'</td>
						<td class="subheader" align="center">'.$lang->details['since'].'</td>
						<td class="subheader" align="center">'.$lang->details['idle'].'</td>
						<td class="subheader" align="center">'.$lang->details['client'].'</td>
				</tr>';
		$now = TIMENOW;
		include_once(INC_PATH.'/functions_ratio.php');
		foreach ($arr as $e)
		{

			if ((preg_match('#I3#is', $e['options']) OR preg_match('#I4#is', $e['options'])) AND !$is_mod AND $CURUSER['id'] != $e['id'])
			{
				$s .= '
				<tr>
					<td align="center">'.$lang->global['anonymous'].'</td>
					'.str_repeat('<td align="center">---</td>', 10).'
				</tr>';
				continue;
			}

			if (isset($num))
			{
				++$num;
			}
			else
			{
				$num = 1;
			}

			$dnsstuff = "<br /><a href=\"http://dnsstuff.com/tools/whois.ch?ip=".htmlspecialchars_uni($e["ip"])."\" target=_blank><font class=\"small\" color=\"brown\"><b>".htmlspecialchars_uni($e["ip"])."</b></font></a>:<u><font class=\"small\" color=\"red\"><b>". $e['port'] . "</b></font></u></td>\n";
			$pregreplace = "<br /><font class=\"small\" color=\"brown\"><b>".preg_replace('/\.\d+\.\d+$/', "***", htmlspecialchars_uni($e["ip"])) . "</b></font></a>:<u><font class=\"small\" color=\"red\"><b>". (int)$e['port'] . "</b></font></u></td>\n";
			$highlight = $CURUSER["id"] == $e["id"] ? " bgcolor=#BBAF9B" : "";
			$s .= "<tr$highlight>\n";
			if (!empty($e["username"]))
			{

				if ($is_mod || $torrent['anonymous'] != 'yes' || $e['id'] != $torrent['owner'])
				{
					$s .= "<td style=\"white-space: nowrap; text-align: center;\"><a href=\"".ts_seo($e['userid'], $e['username'])."\"><b>".get_user_color($e["username"],$e["namestyle"])."</b></a>" . ($e["donor"] == "yes" ? "<img src=".$pic_base_url."star.gif title='".$lang->global['imgdonated']."'>" : "") . ($e["enabled"] == "no" ? "<img src=".$pic_base_url."disabled.gif title=\"".$lang->global['imgdisabled']."\" style='margin-left: 2px'>" : ($e["warned"] == "yes" ? "<a href=\"rules.php#warning\" class=\"altlink\"><img src=\"".$pic_base_url."warned.gif\" title=\"".$lang->global['imgwarned']."\" border=\"0\"></a>" : ""));
					$s .= ($is_mod ? $dnsstuff : $pregreplace);
				}
				else
					$s .= "<td>".$lang->global['anonymous']."</a></td>\n";
			}
			else
				$s .= "<td>".$lang->global['unknown']."</td>\n";

			$secs = max(1, ($now - $e["st"]) - ($now - $e["la"]));
			$s .= "<td align=\"center\">" . ($e['connectable'] == "yes" ? "<font color=green>".$lang->details['yes']."</font>" : "<font color=red>".$lang->details['no']."</font>") . "</td>\n";
			$s .= "<td align=\"right\">" . mksize($e["uploaded"]) . "</td>\n";
			$s .= "<td align=\"right\"><span style=\"white-space: nowrap;\">" . mksize(($e["uploaded"] - $e["uploadoffset"]) / $secs) . "/s</span></td>\n";
			$s .= "<td align=\"right\">" . mksize($e["downloaded"]) . "</td>\n";
			if ($e["seeder"] == "no")
				$s .= "<td align=\"right\"><span style=\"white-space: nowrap;\">" . mksize(($e["downloaded"] - $e["downloadoffset"]) / $secs) . "/s</span></td>\n";
			else
				$s .= "<td align=\"right\"><span style=\"white-space: nowrap;\">" . mksize(($e["downloaded"] - $e["downloadoffset"]) / max(1, $e["finishedat"] - $e['st'])) .	"/s</span></td>\n";
			if ($e["downloaded"])
			{
			  $ratio = floor(($e["uploaded"] / $e["downloaded"]) * 1000) / 1000;
				$s .= "<td align=\"right\"><font color=" . get_ratio_color($ratio) . ">" . number_format($ratio, 2) . "</font></td>\n";
			}
			   else
			  if ($e["uploaded"])
				$s .= "<td align=\"right\">".$lang->details['inf']."</td>\n";
			  else
				$s .= "<td align=right>---</td>\n";
			$s .= "<td align=\"right\">" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</td>\n";
			$s .= "<td align=\"right\">" . mkprettytime($now - $e["st"]) . "</td>\n";
			$s .= "<td align=\"right\">" . mkprettytime($now - $e["la"]) . "</td>\n";
			$s .= "<td align=\"left\">" . htmlspecialchars_uni(getagent($e["agent"], $e["peer_id"])) . "</td>\n";
			$s .= "</tr>\n";
		}
		$s .= "</table>\n";
		return $s;
	}
	$downloaders = array();
	$seeders = array();
	$subres = sql_query("SELECT p.seeder, p.finishedat, p.downloadoffset, p.uploadoffset, p.ip, p.port, p.uploaded, p.downloaded, p.to_go, UNIX_TIMESTAMP(p.started) AS st, p.connectable, p.agent, p.peer_id, UNIX_TIMESTAMP(p.last_action) AS la, p.userid,  u.id, u.enabled, u.username, u.options, u.warned, u.donor, g.namestyle FROM peers p LEFT JOIN users u ON (p.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE p.torrent = ".sqlesc($id)) or sqlerr(__FILE__,__LINE__);
	while ($subrow = mysql_fetch_array($subres))
	{
		if ($subrow["seeder"] == "yes")
			$seeders[] = $subrow;
		else
			$downloaders[] = $subrow;
	}

	function leech_sort($a,$b)
	{
		if ( isset( $_GET["usort"] ) ) return seed_sort($a,$b);
		$x = $a["to_go"];
		$y = $b["to_go"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return -1;
		return 1;
	}
	function seed_sort($a,$b)
	{
		$x = $a["uploaded"];
		$y = $b["uploaded"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return 1;
		return -1;
	}

	usort($seeders, "seed_sort");
	usort($downloaders, "leech_sort");

	$peerstable = dltable($lang->details['seeders2'], $seeders, $torrent);
	$peerstable .= '<br />'.dltable($lang->details['leechers2'], $downloaders, $torrent);
}

$QuickEditor->FormName='comment';
$QuickEditor->TextAreaName='message';
$showcommenttable .= '
<br />
<!-- start: BBcode Styles -->
'.$QuickEditor->GenerateCSS().'
'.$QuickEditor->GenerateJavascript().'
<!-- start: BBcode Styles -->
'.(!empty($cerror) ? '<div class="error">'.$cerror.'</div>' : '').'
'.($useajax == 'yes' ? '<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_comment.js"></script>' : '').'
<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_preview.js"></script>
<form name="comment" id="comment" method="post" action="comment.php?action=add&tid='.$id.'">
<input type="hidden" name="ctype" value="quickcomment">
<input type="hidden" name="page" value="'.intval(isset($_GET['page']) ? $_GET['page'] : 0).'">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="thead">'.ts_collapse('quickcomment').'<a name="startquickcomment">'.$lang->details['quickcomment'].'</a></td>
		</tr>
		'.ts_collapse('quickcomment', 2).'
		<tr>
			<td>
				'.$QuickEditor->GenerateBBCode().'
			</td>
		</tr>
		<tr>
			<td align="center"><textarea name="message" style="width:850px;height:120px;" id="message"></textarea></td>
		</tr>
		<tr>
			<td align="center">
			'.($useajax == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'ajax-loader.gif" class="inlineimg" border="0" alt="" title="" id="loading-layer" style="display:none;" /> <input type="button" class="button" value="'.$lang->global['buttonsubmit'].'" name="quickcomment" id="quickcomment" onclick="javascript:TSajaxquickcomment(\''.$id.'\');" />' : '<input type="submit" name="submit" value="'.$lang->global['buttonsubmit'].'" class="button" />').'
			<input type="button" class="button" name="button" value="'.$lang->global['buttonpreview'].'" onclick="javascript:TSajaxquickpreview();" /> <input type="button" value="'.$lang->global['advancedbutton'].'" class="button" onclick="jumpto(\''.$BASEURL.'/comment.php?action=add&tid='.$id.'\')" />
			</td>
		</tr>
	</table>
</form>';

if ($usergroups['canbookmark'] == 'yes')
{
	$onclick1 = $onclick2 = '';
	if ($useajax == 'yes')
	{
		$onclick1 = ' onclick="TSajaxquickbookmark('.$id.',\'add\'); return false;"';
		$onclick2 = ' onclick="TSajaxquickbookmark('.$id.',\'delete\'); return false;"';
	}
	$bookmark = '
	'.($useajax == 'yes' ? '
	<script type="text/javascript" src="./scripts/quick_bookmark.js"></script>' : '').'
	<p>
	<span id="bookmark-loading-layer" style="display:none; float: left;"><img src="'.$BASEURL.'/'.$pic_base_url.'ajax-loader.gif" border="0" alt="" title="" class="inlineimg"></span>
	<span id="bookmark-done-layer" style="display:none; float: left;"><img src="'.$BASEURL.'/'.$pic_base_url.'input_true.gif" border="0" alt="" title="" class="inlineimg"></span>
	<a href="'.$BASEURL.'/bookmarks.php?action=add&torrentid='.$id.'"'.$onclick1.'>'.$lang->details['bookmark'].'</a> - <a href="'.$BASEURL.'/bookmarks.php?action=delete&torrentid='.$id.'"'.$onclick2.'>'.$lang->details['removebookmark'].'</a>
	</p>';
}
else
{
	$bookmark = $lang->global['nopermission'];
}

if($torrent['anonymous'] == 'yes' AND $torrent['owner'] != $CURUSER['id'] AND !$is_mod)
{
		$username = $lang->global['anonymous'];
}
else
{
	$username = '<a href="'.ts_seo($torrent['owner'], $torrent['username']).'">'.get_user_color($torrent['username'], $torrent['namestyle']).'</a>'.($torrent['donor'] == 'yes' ? ' <img src="'.$BASEURL.'/'.$pic_base_url.'star.gif" alt="'.$lang->global['imgdonated'].'" title="'.$lang->global['imgdonated'].'">' : '').($torrent['warned'] == 'yes' || $torrent['leechwarn'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'warned.gif" alt="'.$lang->global['imgwarned'].'" title="'.$lang->global['imgwarned'].'">' : '');
}

if ($ratingsystem == 'yes' AND $usergroups['canrate'] == 'yes')
{
	require('ratings/includes/rating_functions.php');
	$rating = show_rating($id, $CURUSER['id']);
}

if ($thankssystem == 'yes')
{
	$AllThanks = '
	<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_thanks.js?v='.O_SCRIPT_VERSION.'"></script>
	<img src="'.$BASEURL.'/'.$pic_base_url.'ajax-loader.gif" class="inlineimg" border="0" alt="" title="" id="thanks-loading-layer" style="display:none; float: left;" />
	';
	$IsThanked=false;
	$ThanksArray=array();
	$Tquery = sql_query('SELECT t.uid, u.username, g.namestyle FROM ts_thanks t LEFT JOIN users u ON (u.id=t.uid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.tid = \''.$id.'\' ORDER BY u.username');
	if (mysql_num_rows($Tquery) > 0)
	{
		while($thanks=mysql_fetch_assoc($Tquery))
		{
			if ($thanks['uid'] == $CURUSER['id'])
			{
				$IsThanked=true;
			}
			$ThanksArray[] = '<a href="'.ts_seo($thanks['uid'], $thanks['username']).'">'.get_user_color($thanks['username'], $thanks['namestyle']).'</a>';
		}
	}

	$TButton = '';
	if (!$IsThanked AND $torrent['owner'] != $CURUSER['id'])
	{
		$TButton = '<div id="thanks_button"><input type="button" value="'.$lang->global['buttonthanks'].'" onclick="javascript:TSajaxquickthanks('.$id.');" /></div>';
	}
	elseif ($IsThanked)
	{
		$TButton = '<div id="thanks_button"><input type="button" value="'.$lang->global['buttonthanks2'].'" onclick="javascript:TSajaxquickthanks('.$id.', true);" /></div>';
	}

	if (count($ThanksArray) == 0)
	{
		$AllThanks = $AllThanks.$TButton.'<div id="torrent_thanks" name="torrent_thanks"><b><i>'.$lang->details['nothanksyet'].'</i></b></div>';
	}
	else
	{
		$AllThanks = $AllThanks.$TButton.'<div id="torrent_thanks" name="torrent_thanks">'.implode(', ', $ThanksArray).'</div>';
	}
}

$ShowTLINK = '';
if (!empty($torrent['t_link']))
{
	require_once(INC_PATH.'/functions_imdb_rating.php');
	if ($IMDBRating = TSSEGetIMDBRatingImage($torrent['t_link']))
	{
		$torrent['t_link'] = str_replace('<b>User Rating:</b>', '<b>User Rating:</b> '.$IMDBRating['image'], $torrent['t_link']);
	}
	else
	{
		$torrent['t_link'] = format_urls(str_replace('"', '&quot;', $torrent['t_link']), '_blank');
	}
	$ShowTLINK = '
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead">
				'.($is_mod ? '
				<span style="float: right;"><div id="imdbupdatebutton" name="imdbupdatebutton"><a href="#" onclick="TS_IMDB(\''.$id.'\'); return false;"><b><u><i>'.$lang->global['refresh'].'</i></u></b></a></div></span>' : '').$lang->details['t_link'].'
			</td>
		</tr>
		<tr>
			<td>
				<div id="imdbdetails" name="imdbdetails">'.$torrent['t_link'].'</div>
			</td>
		</tr>
	</table>
	<br />';
}

$details = '
<table cellspacing="0" border="0" cellpadding="4" width="100%">
	<tr>
		<td colspan="3" class="thead">'.$isfree.$issilver.$isdoubleupload.' '.htmlspecialchars_uni($torrent['name']).'</td>
	</tr>
	<tr>
		<td rowspan="'.$rowspan.'" align="center" valign="top" width="175">'.($torrent['t_image'] != '' ? '<a href="#" onclick="javascript:popImage(\''.$torrent['t_image'].'\',\'Image Preview\')"><img src="'.htmlspecialchars_uni($torrent['t_image']).'" border="0" width="175" height="175">' : '<img src="'.$BASEURL.'/'.$pic_base_url.'nopreview.gif" border="0">').'</a>'.($ratingsystem == 'yes' ? '<br /><br />'.$rating : '').'</td>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['download'].'</td>
		<td style="padding-left: 5px;" valign="top" width="430"><a href="'.ts_seo($id,$torrent['name'],'d').'" alt="'.$lang->details['dltorrent'].'" title="'.$lang->details['dltorrent'].'">'.$lang->details['dltorrent'].'</a></td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['added'].'</td>
		<td valign="top" style="padding-left: 5px;">'.my_datee($dateformat, $torrent['added']).' '.my_datee($timeformat, $torrent['added']).'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['type'].'</td>
		<td valign="top" style="padding-left: 5px;">'.$torrent['categoryname'].'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['size'].'</td>
		<td valign="top" style="padding-left: 5px;">'.mksize($torrent['size']).' '.sprintf($lang->details['numfiles2'], ts_nf($torrent['numfiles'])).'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['hits'].'</td>
		<td valign="top" style="padding-left: 5px;">'.ts_nf($torrent['hits']).'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['snatched'].'</td>
		<td valign="top" style="padding-left: 5px;"><a href="'.$BASEURL.'/viewsnatches.php?id='.$id.'">'.ts_nf($torrent['times_completed']).'</a> '.$lang->details['snatched2'].'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['uppedby'].'</td>
		<td valign="top" style="padding-left: 5px;">'.$username.'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['comments'].'</td>
		<td valign="top" style="padding-left: 5px;">'.ts_nf($torrent['comments']).' '.$lang->details['comments'].'</td>
	</tr>
	<tr>
		<td style="padding-left: 5px;" class="subheader" valign="top" width="147">'.$lang->details['peersb'].'</td>
		<td valign="top" style="padding-left: 5px;">'.sprintf($lang->details['peers2'], '<font color="'.get_slr_color($sratio).'">'.ts_nf($torrent['seeders']).'</font>', '<font color="'.get_slr_color($lratio).'">'.ts_nf($torrent['leechers']).'</font>', ts_nf($torrent['seeders']+$torrent['leechers'])).'</td>
	</tr>
	'.(isset($reseed) ? $reseed : '').(isset($isScene) ? $isScene : '').'
	<tr>
		<td align="center" valign="top" width="175">
			<table cellpadding="2" cellspacing="0" width="100%" align="center">
				<tr>
					<td colspan="2" class="subheader">'.$lang->upload['video'].'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['codec'].'</td><td>'.($video_info[0] ? htmlspecialchars_uni($video_info[0]) : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['bitrate'].'</td><td>'.($video_info[1] ? htmlspecialchars_uni($video_info[1]).' kbps' : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['resulation'].'</td><td>'.($video_info[2] ? htmlspecialchars_uni($video_info[2]) : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['length'].'</td><td>'.($video_info[3] ? htmlspecialchars_uni($video_info[3]).' '.$lang->global['minutes'] : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['quality'].'</td><td>'.($video_info[4] ? htmlspecialchars_uni($video_info[4]).'/10' : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td colspan="2" class="subheader">'.$lang->upload['audio'].'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['codec'].'</td><td>'.($audio_info[0] ? htmlspecialchars_uni($audio_info[0]) : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['bitrate'].'</td><td>'.($audio_info[1] ? htmlspecialchars_uni($audio_info[1]).' kbps' : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['frequency'].'</td><td>'.($audio_info[2] ? htmlspecialchars_uni($audio_info[2]).' HZ' : $lang->details['na']).'</td>
				</tr>
				<tr>
					<td valign="top" align="right" width="40%">'.$lang->upload['language'].'</td><td>'.($audio_info[3] ? htmlspecialchars_uni($audio_info[3]) : $lang->details['na']).'</td>
				</tr>
			</table>
		</td>
		<td colspan="2" valign="top">
			'.($thankssystem == 'yes' ? '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td class="thead">'.$lang->details['thanksby'].'</td>
				</tr>
				<tr>
					<td>
						<div style="overflow: auto; height: 200px;">'.$AllThanks.'</div>
					</td>
				</tr>
			</table>' : '').'
		</td>
	</tr>
	</table>
	<br />
	'.$ShowTLINK.'
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead">'.sprintf($lang->details['detailsfor'], $torrent['name']).'</td>
		</tr>
		<tr>
			<td>
				'.format_comment($torrent['descr']).'
			</td>
		</tr>
	</table>
';

echo '
<link rel="stylesheet" type="text/css" href="'.$BASEURL.'/scripts/yui/tabview/assets/skins/sam/tabview.css" />
<script type="text/javascript" src="'.$BASEURL.'/scripts/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/yui/element/element-beta-min.js"></script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/yui/tabview/tabview-min.js"></script>'.($is_mod ? '
<script type="text/javascript">
	l_updated = "'.$lang->global['imgupdated'].'";
	l_refresh = "'.$lang->global['refresh'].'";
</script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_imdb.js"></script>' : '');

$show_manage = '';
if ($CURUSER['id'] === $torrent['owner'] OR $is_mod)
{
	$show_manage .= '<a href="'.$BASEURL.'/edit.php?id='.$id.'"  onmouseout="window.status=\'\'; return true;" onMouseOver="window.status=\''.$lang->details['editorrent'].'\'; return true;">'.$lang->details['editorrent'].'</a> - ';
}
if ($is_mod)
{
	$show_manage .= '<a href="'.$BASEURL.'/admin/index.php?act=fastdelete&amp;id='.$id.'" onmouseout="window.status=\'\'; return true;" onMouseOver="window.status=\'\'; return true;">Delete Torrent</a> -
	<a href="'.$BASEURL.'/comment.php?tid='.$id.'&action='.($torrent['allowcomments'] != 'yes' ? 'open' : 'close').'"  onmouseout="window.status=\'\'; return true;" onMouseOver="window.status=\''.($torrent['allowcomments'] == 'no' ? $lang->details['open'] : $lang->details['close']).'\'; return true;">'.($torrent['allowcomments'] != 'yes' ? $lang->details['open'] : $lang->details['close']).'</a> -
	<a href="'.$BASEURL.'/admin/index.php?act=torrent_info&id='.$id.'" onmouseout="window.status=\'\'; return true;" onMouseOver="window.status=\'\'; return true;">Torrent Info</a>';
}

$show_nfo = '';
if (!empty($torrent['nfo']))
{
	$show_nfo .= '<img src="'.$BASEURL.'/viewnfo.php?id='.$id.'" border="0" alt="'.$torrent['name'].'" title="'.$torrent['name'].'" />';
}

echo '
<div class="yui-skin-sam">
	<div id="details" class="yui-navset">
		<ul class="yui-nav">
			<li'.($tab == 'details' ? ' class="selected"' : '').'><a href="#details"><em>'.$lang->details['torrentinfo'].'</em></a></li>
			<li'.($tab == 'comments' ? ' class="selected"' : '').'><a href="#comments"><em>'.$lang->details['comments'].'</em></a></li>
			<li'.($tab == 'filelist' ? ' class="selected"' : '').'><a href="#filelist"><em>'.$lang->details['numfiles3'].'</em></a></li>
			<li'.($tab == 'peers' ? ' class="selected"' : '').'><a href="#peers"><em>'.$lang->details['peersb'].'</em></a></li>
			<li'.($tab == 'report' ? ' class="selected"' : '').'><a href="#report"><em>'.$lang->details['report'].'</em></a></li>
			<li'.($tab == 'bookmark' ? ' class="selected"' : '').'><a href="#bookmark"><em>'.$lang->details['bookmark'].'</em></a></li>
			'.($show_nfo != '' ? '<li'.($tab == 'nfo' ? ' class="selected"' : '').'><a href="#nfo"><em>NFO</em></a></li>' : '').'
			'.($show_manage != '' ? '<li'.($tab == 'manage' ? ' class="selected"' : '').'><a href="#manage"><em>Manage Torrent</em></a></li>' : '').'
		</ul>
		<div class="yui-content">
			<div id="details">'.$details.'</div>
			<div id="comments">'.$showcommenttable.'</div>
			<div id="filelist">'.$s.'</div>
			<div id="peers">'.$peerstable.'</div>
			<div id="report">'.$report.'</div>
			<div id="bookmark">'.$bookmark.'</div>
			'.($show_nfo != '' ? '<div id="nfo">'.$show_nfo.'</div>' : '').'
			'.($show_manage != '' ? '<div id="manage">'.$show_manage.'</div>' : '').'
		</div>
	</div>
	<script type="text/javascript">
	(function() {
		var tabView = new YAHOO.widget.TabView("details");
	})();
	</script>
</div>';
function get_slr_color($ratio)
{
	if ($ratio < 0.025) return "#ff0000";
	if ($ratio < 0.05) return "#ee0000";
	if ($ratio < 0.075) return "#dd0000";
	if ($ratio < 0.1) return "#cc0000";
	if ($ratio < 0.125) return "#bb0000";
	if ($ratio < 0.15) return "#aa0000";
	if ($ratio < 0.175) return "#990000";
	if ($ratio < 0.2) return "#880000";
	if ($ratio < 0.225) return "#770000";
	if ($ratio < 0.25) return "#660000";
	if ($ratio < 0.275) return "#550000";
	if ($ratio < 0.3) return "#440000";
	if ($ratio < 0.325) return "#330000";
	if ($ratio < 0.35) return "#220000";
	if ($ratio < 0.375) return "#110000";
	return "#000000";
}
stdfoot();
?>
