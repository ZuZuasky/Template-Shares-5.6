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

function quick_replace_tags($s)
{
	$simple_search = array(
										"/\[b\]((\s|.)+?)\[\/b\]/is",
										"#\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\](.*?)\[/size\]#si",
										"#\[align=(left|center|right|justify)\](.*?)\[/align\]#si",
										"/\[i\]((\s|.)+?)\[\/i\]/is",
										"/\[h\]((\s|.)+?)\[\/h\]/is",
										"/\[u\]((\s|.)+?)\[\/u\]/is",
										"/\[img\]((http|https):\/\/[^\s'\"<>]+(\.(jpg|gif|png)))\[\/img\]/is",
										"/\[img=((http|https):\/\/[^\s'\"<>]+(\.(gif|jpg|png)))\]/is",
										"/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/is",
										"/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\]((\s|.)+?)\[\/color\]/is",
										"/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/is",
										"/\[url\]([^()<>\s]+?)\[\/url\]/is",
										"/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^()<>\s]+)/i"
		);

	$simple_replace = "\\1";
	$s = preg_replace ($simple_search, $simple_replace, $s);
	$s = str_replace(array("\n","\r"), "", $s);
	$s = addslashes($s);
	return $s;
}
// BEGIN Plugin: latesttorrents
//define('SKIP_CACHE_MESSAGE', true);
//require_once(INC_PATH.'/functions_cache2.php');
//if (!($latesttorrents=cache_check2('latesttorrents')))
//{
	$lt_query = sql_query('SELECT t.id,t.name,t.size,t.descr,t.offensive,t.added,t.seeders,t.leechers,t.t_image,c.vip FROM torrents t LEFT JOIN categories c ON t.category = c.id WHERE t.visible = \'yes\' AND t.banned=\'no\' AND t_image != \'\' ORDER BY added DESC LIMIT 0,'.$i_torrent_limit);		
	if(mysql_num_rows($lt_query) > 0)
	{
		$latesttorrents = '<!-- begin showlastXtorrents -->';
		$count=0;
		$slide_show=array();
		while($row = mysql_fetch_assoc($lt_query))
		{
			if ($usergroups['canviewviptorrents'] != 'yes' && $row['vip'] == 'yes') continue;			
			elseif ($row['offensive'] == 'yes' && preg_match('#E0#is', $CURUSER['options'])) continue;
			$seolink = ts_seo($row['id'], $row['name'], 's');
			$fullname = $javascriptname = htmlspecialchars_uni($row['name']);
			$javascriptname = str_replace("'", "\'", $javascriptname);
			$added = my_datee($dateformat, $row['added']).' '.my_datee($timeformat, $row['added']);
			$show_latesttorrent_contents = '<table><tr>'.($row['t_image'] ? '<td rowspan=2><img src='.htmlspecialchars_uni($row['t_image']).' border=0 height=150 width=150 /></td>' : '').'<td class=none valign=top><b>'.$lang->index['name'].':</b> '.$javascriptname.'<br /><b>'.$lang->index['size'].':</b> '.mksize($row['size']).'<br /><b>'.$lang->index['uploaddat'].':</b> '.$added.'<br /><b>'.$lang->index['seeders'].':</b> 	'.ts_nf($row['seeders']).'<br /><b>'.$lang->index['leechers'].':</b> '.ts_nf($row['leechers']).'<br />'.cutename(quick_replace_tags($row['descr']), 170).'</tr></td></table>';
			$latesttorrents .= $_title_bracket.' <a href="'.$seolink.'" onmouseover="ddrivetip(\''.$show_latesttorrent_contents.'\', 500)" onmouseout="hideddrivetip()">'.cutename($row['name'], $__cute).'</a><br />';		
			if ($row['t_image'])
			{
				$slide_show[] = 'leftrightslide['.$count.']=\'<a href="'.$seolink.'"><img src="'.htmlspecialchars_uni($row['t_image']).'" border="1" height="150" width="150" alt="'.$javascriptname.'" title="'.$javascriptname.'" /></a>\';';
				$count++;
			}
		}
		if (count($slide_show) > 0)
		{
			$latesttorrents .= '
			<script type="text/javascript">
				var sliderwidth="'.$__width.'px"
				var sliderheight="150px"
				var slidespeed=2
				slidebgcolor="#EAEAEA"
				var leftrightslide=new Array()
				var finalslide=""				
				'.implode('
				', $slide_show).'
				var imagegap=" "
				var slideshowgap=5
			</script>
			<script type="text/javascript" src="'.$BASEURL.'/scripts/sshowlr.js"></script>
			';
		}
		$latesttorrents .= '<!-- end latesttorrents -->';
		//cache_save2('latesttorrents', $latesttorrents);
	}
//}
// END Plugin: latesttorrents
?>
