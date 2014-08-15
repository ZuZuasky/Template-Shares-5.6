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

// BEGIN Plugin: mostvisitedusers
if (!defined('SKIP_CACHE_MESSAGE'))
{
	define('SKIP_CACHE_MESSAGE', true);
}
require_once(INC_PATH.'/functions_cache2.php');
if (!($mostvisitedusers=cache_check2('mostvisitedusers')))
{
	$mostvisitedusers = '<!-- begin mostvisitedusers -->';
	$query = sql_query("SELECT u.id, u.username, u.visitorcount, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.visitorcount > 0 ORDER BY u.visitorcount DESC LIMIT 5") or sqlerr(__FILE__,__LINE__);
	while($mv=mysql_fetch_assoc($query))
	{
		$mostvisitedusers .= '<a href="'.ts_seo($mv['id'], $mv['username']).'">'.get_user_color($mv['username'], $mv['namestyle']).'</a> ('.ts_nf($mv['visitorcount']).')<br />';
	}
	$mostvisitedusers .= '<!-- end mostvisitedusers -->';
	cache_save2('mostvisitedusers', $mostvisitedusers);
}
// END Plugin: mostvisitedusers
?>
