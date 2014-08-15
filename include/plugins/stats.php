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

// BEGIN Plugin: Stats
include_once(INC_PATH.'/ts_cache.php');
define('CACHE_INCLUDED', true);
update_cache('indexstats');
include_once("$cache/indexstats.php");

$stats = '
<b>'.$lang->index['members'].':</b> '.ts_nf($indexstats['registered']).'<br />
<b>'.$lang->index['torrents'].':</b> '.ts_nf($indexstats['torrents']).'<br />
<b>'.$lang->index['seeders'].':</b> '.ts_nf($indexstats['seeders']).'<br />
<b>'.$lang->index['leechers'].':</b> '.ts_nf($indexstats['leechers']).'<br />
<b>'.$lang->index['peers'].':</b> '.ts_nf($indexstats['peers']).'<br />
<b>'.$lang->index['threads'].':</b> '.ts_nf($indexstats['totalthreads']).'<br />
<b>'.$lang->index['posts'].':</b> '.ts_nf($indexstats['totalposts']).'<br /><br />
'.sprintf($lang->index['newestmember'], $indexstats['latestuser']).'
';
// END Plugin: Stats
?>
