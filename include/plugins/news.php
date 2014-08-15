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
define('NcodeImageResizer', true);
// BEGIN Plugin: News
# Settings
$news = ''; //Must be empty and same as plugin and file name.
if (!defined('SKIP_CACHE_MESSAGE'))
{
	define('SKIP_CACHE_MESSAGE', true);
}
require_once(INC_PATH.'/functions_cache2.php');
if (!($newscached = cache_check2('news')))
{	
	# Query
	$news_query = sql_query('SELECT added, body, title FROM news ORDER BY added DESC LIMIT 0,'.MAX_NEWS);
	if (mysql_num_rows($news_query) > 0)
	{
		while($news_results = mysql_fetch_assoc($news_query))
		{
			$news .= '<span class="subheader"><strong>'.$news_results['title'].' - '.my_datee($dateformat, $news_results['added']).' '.my_datee($timeformat, $news_results['added']).'</strong></span><hr />'.format_comment($news_results['body']).(MAX_NEWS > 1 ? '<br /><br />' : '');
		}
	}
	cache_save2('news', $news);
}
else
{
	$news .= ($is_mod ? '<span style="float: right;">[<a href="'.$BASEURL.'/admin/index.php?act=news">'.$lang->index['newspage'].'</a>]</span><br /><hr />' : '<hr />');
	$news .= $newscached;
}
// END Plugin: News
?>
