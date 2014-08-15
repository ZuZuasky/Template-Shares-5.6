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
dbconn();
loggedinorreturn();
maxsysop ();
parked();
define('IRC_VERSION', '0.6');
if ($usergroups['canshout'] != 'yes')
{
	print_no_permission();
	exit;
}

$lang->load('irc');
stdhead(sprintf($lang->irc['head'], $SITENAME));

require(INC_PATH.'/readconfig_pjirc.php');
$nickname = isset($CURUSER['username']) && !empty($CURUSER['username']) ? $CURUSER['username'] : 'TSGuest_'.time();
$uri = 'http://embed.mibbit.com/?server='.urlencode($pjirchost).'&channel='.urlencode($pjircchannel).'&autoConnect=true&authmethod=pass&nick='.urlencode($nickname);
echo '
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead">'.sprintf($lang->irc['head'], $SITENAME).'</td>
	</tr>
	<tr>
		<td align="center"><iframe width="800" height="600" scrolling="no" src="'.$uri.'" frameborder="0" style="border: 0;"></iframe></td>
	</tr>
</table>';
stdfoot();
?>
