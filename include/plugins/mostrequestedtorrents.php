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
$MaxRequests = 5;
// Plugin Configuration

// BEGIN Plugin: mostrequestedtorrents
define('SKIP_CACHE_MESSAGE', true);
require_once(INC_PATH.'/functions_cache2.php');
if (!($mostrequestedtorrents=cache_check2('mostrequestedtorrents')))
{
	$mostrequestedtorrents = '<!-- begin mostrequestedtorrents -->';
	$query = sql_query("SELECT r.id, r.userid, r.request, r.hits, u.username, g.namestyle FROM requests r LEFT JOIN users u ON (u.id=r.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE r.filled='no' ORDER BY r.hits DESC LIMIT $MaxRequests") or sqlerr(__FILE__,__LINE__);
	$mostrequestedtorrents .= '
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="subheader" align="left" width="80%">Request</td>
			<td class="subheader" align="center" width="10%">Username</td>
			<td class="subheader" align="center" width="10%">Votes</td>
		</tr>';
	while($mr=mysql_fetch_assoc($query))
	{
		$mostrequestedtorrents .= '
		<tr>
			<td align="left" width="80%"><a href="'.$BASEURL.'/viewrequests.php?do=view_request&rid='.$mr['id'].'">'.htmlspecialchars_uni($mr['request']).'</a></td>
			<td align="center" width="10%"><a href="'.ts_seo($mr['userid'], $mr['username']).'">'.get_user_color($mr['username'], $mr['namestyle']).'</a></td>
			<td align="center" width="10%">'.ts_nf($mr['hits']).'</td>
		</tr>';
	}
	$mostrequestedtorrents .= '
	</table>
	<!-- end mostrequestedtorrents -->';
	cache_save2('mostrequestedtorrents', $mostrequestedtorrents);
}
// END Plugin: mostrequestedtorrents
?>
