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
// Dont change for future reference.
if (!defined('TS_P_VERSION'))
{
	define('TS_P_VERSION', '1.1 by xam');
}
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
	die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}

// Plugin Configuration
$i_post_limit= 4; // How many threads do you wanna show?
//Plugin Configuration

// BEGIN Plugin: latestthreads
include_once(INC_PATH.'/readconfig_forumcp.php');
$_res_lt_query = sql_query("SELECT fp.fid,f.fid FROM ".TSF_PREFIX."forumpermissions fp LEFT JOIN ".TSF_PREFIX."forums f ON (fp.fid=f.pid) WHERE (fp.canview = 'no' OR fp.cansearch = 'no') AND fp.gid = ".sqlesc(($CURUSER ? $CURUSER['usergroup'] : UC_USER)));
if (mysql_num_rows($_res_lt_query) > 0)
{
	while ($notin = mysql_fetch_assoc($_res_lt_query))
		$uf[] = 0+$notin['fid'];
	$unsearchforums = implode(',', $uf);
}

$_res_lt_query = sql_query("SELECT fid,password FROM ".TSF_PREFIX."forums WHERE password != ''");
if (mysql_num_rows($_res_lt_query) > 0)
{
	require_once(INC_PATH.'/functions_cookies.php');
	while ($notin = mysql_fetch_assoc($_res_lt_query))
	{
		if (ts_get_array_cookie("forumpass", $notin['fid']) != md5($CURUSER['id'].$notin['password']))
		{
			$uf2[] = 0+$notin['fid'];
		}
	}
	if (count($uf2) > 0)
	{
		if (isset($unsearchforums))
			$unsearchforums .= ','.implode(',', $uf2);
		else
			$unsearchforums = implode(',', $uf2);
	}
}

if(isset($unsearchforums)) $where_sql = " AND t.fid NOT IN ($unsearchforums)";		
$_res_lt_query = sql_query("SELECT t.tid, t.iconid, t.subject, t.dateline, t.uid, t.username, t.replies, t.lastpost, t.lastposter, t.lastposteruid, t.views FROM ".TSF_PREFIX."threads t WHERE 1=1 {$where_sql} ORDER BY t.lastpost DESC LIMIT 0, ".$i_post_limit);

$latestthreads = '<!-- begin lastXforumposts -->';
while($thread = mysql_fetch_assoc($_res_lt_query))
{
	$thread_title = '
	<img src="'.$BASEURL.'/tsf_forums/images/icons/icon'.$thread['iconid'].'.gif" border="0" class="inlineimg" alt="" /> <strong><a href="'.tsf_seo_clean_text($thread['subject'], 't', $thread['tid']).'" title="'.htmlspecialchars_uni($thread['subject']).'">'.cutename($thread['subject'], $__cute).'</a></strong>';
	$thread_details = '
	<div>'.my_datee($dateformat, $thread['dateline']).' '.my_datee($timeformat, $thread['dateline']).'</div>
	<div>'.sprintf($lang->index['by'], '<a href="'.ts_seo($thread['uid'], $thread['username']).'">'.$thread['username'].'</a></div>');

	$lastpost_details = '
	<div style="padding-top: 6px;"><a href="'.tsf_seo_clean_text($thread['subject'], 't', $thread['tid'], '&amp;page=last').'"><img src="'.$BASEURL.'/tsf_forums/images/lastpost.gif" class="inlineimg" border="0" alt="'.$lang->index['last'].'" title="'.$lang->index['last'].'" /></a> '.$lang->index['lastposter'].' <a href="'.ts_seo($thread['lastposteruid'], $thread['lastposter']).'">'.$thread['lastposter'].'</a></div> 
	<div>'.my_datee($dateformat, $thread['lastpost']).' '.my_datee($timeformat, $thread['lastpost']).'</div>';	
	$replies_views = '
	<div style="padding-top: 6px;"><strong>'.ts_nf($thread['replies']).'</strong> '.$lang->index['replies'].', <strong>'.ts_nf($thread['views']).'</strong> '.$lang->index['views'].'</div>
	';

	$latestthreads .= $thread_title.$thread_details.$lastpost_details.$replies_views.'<br />';
}
$latestthreads .= '	
<!-- end lastXforumposts -->';
// END Plugin: latestthreads
?>
