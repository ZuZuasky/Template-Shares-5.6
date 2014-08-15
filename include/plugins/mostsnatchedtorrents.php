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

// BEGIN Plugin: mostsnatchedtorrents
define('SKIP_CACHE_MESSAGE', true);
require_once(INC_PATH.'/functions_cache2.php');
if (!($mostsnatchedtorrents=cache_check2('mostsnatchedtorrents')))
{
	$mostsnatchedtorrents = '<!-- begin mostsnatchedtorrents -->';
	$query = sql_query("SELECT id, name, times_completed FROM torrents WHERE ts_external = 'no' ORDER BY times_completed DESC LIMIT 5") or sqlerr(__FILE__,__LINE__);
	while($ms=mysql_fetch_assoc($query))
	{
		$seolink = ts_seo($ms['id'], $ms['name'], 's');
		$fullname = htmlspecialchars_uni($ms['name']);
		$mostsnatchedtorrents .= '<a href="'.$seolink.'" title="'.$fullname.'">'.cutename($ms['name'], 20).'</a> ('.ts_nf($ms['times_completed']).')<br />';
	}
	$mostsnatchedtorrents .= '<!-- end mostsnatchedtorrents -->';
	cache_save2('mostsnatchedtorrents', $mostsnatchedtorrents);
}
// END Plugin: mostsnatchedtorrents
?>
