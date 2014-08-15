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
dbconn(true);
maxsysop();
define('B_VERSION', '4.6.3');

if ($MEMBERSONLY == 'yes')
{
	loggedinorreturn();
	parked();
}

$lang->load('browse');
$is_mod = is_mod($usergroups);
$special_search = (isset($_GET['special_search']) ? trim($_GET['special_search']) : '');
$do = isset($_POST['do']) ? $_POST['do'] : (isset($_GET['do']) ? $_GET['do'] : '');
$quick_search = isset($_POST['quick_search']) ? trim($_POST['quick_search']) : (isset($_GET['quick_search']) ? trim($_GET['quick_search']) : '');
$search_type = $javaalert = $from = '';
$pagelinks = array();
$showvisible = true;
$is_bookmark_page = false;
$UseQuickMenu = preg_match('#P1#is', $CURUSER['options']);

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' AND $is_mod AND !empty($_POST['tid']))
{
	if (($tid = intval($_POST['tid'])) AND is_valid_id($tid) AND ($torrent = trim($_POST['subject'])) AND !empty($torrent))
	{
		$torrent = unesc($torrent);
		$torrent = str_replace('_', ' ', $torrent);
		sql_query("UPDATE torrents SET name = ".sqlesc($torrent)." WHERE id = ".sqlesc($tid));
	}
}

if ($special_search == 'myreseeds')
{
	$extraqueries = " AND t.seeders = 0 AND t.leechers > 0 AND t.owner = ".sqlesc($CURUSER['id']);
	$pagelinks[] = "special_search=myreseeds";
	$sqfield = "&amp;special_search=myreseeds";
	$showvisible = false;
}
elseif ($special_search == 'mybookmarks')
{
	$from = 'FROM bookmarks b LEFT JOIN torrents t ON (b.torrentid = t.id)';
	$extraqueries = " AND b.userid = ".sqlesc($CURUSER['id']);
	$pagelinks[] = "special_search=mybookmarks";
	$sqfield = "&amp;special_search=mybookmarks";
	$showvisible = false;
	$is_bookmark_page = true;
}
elseif ($special_search == 'mytorrents')
{
	$extraqueries = " AND t.owner = ".sqlesc($CURUSER['id']);
	$pagelinks[] = "special_search=mytorrents";
	$sqfield = "&amp;special_search=mytorrents";
	$showvisible = false;
}
elseif ($special_search == 'weaktorrents')
{
	$extraqueries = " AND t.visible = 'no' OR (t.leechers > 0 AND t.seeders = 0) OR (t.leechers = 0 AND t.seeders = 0)";
	$pagelinks[] = "special_search=weaktorrents";
	$sqfield = "&amp;special_search=weaktorrents";
	$showvisible = false;
	$lang->browse['btitle'] = $lang->global['weaktorrents'];
}
elseif ($do == 'search')
{
	$extraquery = array();
	$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : (isset($_GET['keywords']) ? $_GET['keywords'] : '');
	if ($_GET['tags'] AND $_GET['tags'] == 'true' AND !empty($keywords))
	{
		$keywords = urldecode($keywords);
	}
	elseif (!empty($keywords))
	{
		$keywords = trim($keywords);
	}
	$category = isset($_POST['category']) ? intval($_POST['category']) : (isset($_GET['category']) ? intval($_GET['category']) : 0);
	$search_type = isset($_POST['search_type']) ? trim($_POST['search_type']) : (isset($_GET['search_type']) ? trim($_GET['search_type']) : '');

	$query = sql_query("SHOW VARIABLES LIKE 'ft_min_word_len';");
	$array = mysql_fetch_assoc($query);
	$min_length = $array['Value'];
	if(is_numeric($min_length))
	{
		$minsearchword = $min_length;
	}
	else
	{
		$minsearchword = 3;
	}

	if (strlen($keywords) >= $minsearchword && !empty($search_type))
	{
		switch ($search_type)
		{
			case 't_name':
				$extraquery[] = "(MATCH (t.name) AGAINST ('".mysql_real_escape_string($keywords)."*' IN BOOLEAN MODE))";
				break;
			case 't_description':
				$extraquery[] = "(MATCH (t.descr) AGAINST ('".mysql_real_escape_string($keywords)."*' IN BOOLEAN MODE))";
				break;
			case 't_both':
				$extraquery[] = "(MATCH (t.name,t.descr) AGAINST ('".mysql_real_escape_string($keywords)."*' IN BOOLEAN MODE))";
				break;
			case 't_uploader':
				$query = sql_query("SELECT id FROM users WHERE username = ".sqlesc($keywords)." LIMIT 1");
				if (mysql_num_rows($query) > 0)
				{
					$user = mysql_fetch_assoc($query);
					$extraquery[] = "t.owner = ".sqlesc($user['id']).(!$is_mod ? " AND t.anonymous != 'yes'" : "");
				}
				else
					$extraquery[] = "t.owner = ".sqlesc($keywords);
				break;
			case 't_genre':
				$extraquery[] = "(MATCH (t.t_link) AGAINST ('".mysql_real_escape_string($keywords)."*' IN BOOLEAN MODE))";
			break;
		}
	}
	else
	{
		$javaalert = '
		<script type="text/javascript">
			alert("'.sprintf($lang->browse['serror'], $minsearchword).'");
		</script>
		';
	}
	if (is_valid_id($category) && $category > 0)
	{
		$extraquery[] = "t.category = ".$category;
	}
	if (count($extraquery) > 0)
	{
		$extraqueries = ' AND ';
		$extraqueries .= implode(' AND ', $extraquery);
		$pagelinks[] = 'do=search';
		$pagelinks[] = 'keywords='.urlencode(htmlspecialchars_uni($keywords));
		$pagelinks[] = 'category='.$category;
		$pagelinks[] = 'search_type='.urlencode(htmlspecialchars_uni($search_type));
		$showvisible = false;
	}
}
elseif (isset($_GET['category']) && is_valid_id($_GET['category']) && empty($quick_search))
{
	$category = intval($_GET['category']);
	$query = sql_query("SELECT id FROM categories WHERE type='s' AND pid = $category");
	if (mysql_num_rows($query) > 0)
	{
		$squerycats=array();
		while ($squery=mysql_fetch_assoc($query))
		{
			$squerycats[] = $squery['id'];
		}
		$extraqueries = ' AND t.category IN ('.$category.','.implode(',', $squerycats).')';
	}
	else
	{
		$extraqueries = ' AND t.category = '.$category;
	}
	$pagelinks[] = 'category='.$category;
}
elseif (!empty($quick_search))
{
	$category = isset($_POST['category']) ? intval($_POST['category']) : (isset($_GET['category']) ? intval($_GET['category']) : '');
	switch($quick_search)
	{
		case 'show_daily_torrents':
			$stime = TIMENOW-(60*60*24); // daily
			$extraquery[] = "UNIX_TIMESTAMP(t.added) > '$stime'";
			$pagelinks[] = 'quick_search=show_daily_torrents';
			break;
		case 'show_weekly_torrents':
			$stime = TIMENOW-(60*60*(24*7)); // weekly
			$extraquery[] = "UNIX_TIMESTAMP(t.added) > '$stime'";
			$pagelinks[] = 'quick_search=show_today_torrents';
			break;
		case 'show_montly_torrents':
			$stime = TIMENOW-(60*60*(24*28)); // montly
			$extraquery[] = "UNIX_TIMESTAMP(t.added) > '$stime'";
			$pagelinks[] = 'quick_search=show_today_torrents';
			break;
		case 'show_dead_torrents':
			$showvisible = false;
			$extraquery[] = "t.visible = 'no'";
			$pagelinks[] = 'quick_search=show_dead_torrents';
			break;
		case 'show_recommend_torrents':
			$extraquery[] = "t.sticky = 'yes'";
			$pagelinks[] = 'quick_search=show_recommend_torrents';
			break;
		case 'show_free_torrents':
			$extraquery[] = "t.free = 'yes'";
			$pagelinks[] = 'quick_search=show_free_torrents';
			break;
		case 'show_silver_torrents':
			$extraquery[] = "t.silver = 'yes'";
			$pagelinks[] = 'quick_search=show_silver_torrents';
			break;
		case 'show_doubleupload_torrents':
			$extraquery[] = "t.doubleupload = 'yes'";
			$pagelinks[] = 'quick_search=show_doubleupload_torrents';
			break;
		case 'show_external_torrents':
			$extraquery[] = "t.ts_external = 'yes'";
			$pagelinks[] = 'quick_search=show_external_torrents';
			break;
		case 'show_scene_torrents':
			$extraquery[] = "t.isScene > 0";
			$pagelinks[] = 'quick_search=show_scene_torrents';
			break;
	}
	if ($category > 0)
	{
		$extraquery[] = 't.category IN ('.$category.')';
		$pagelinks[] = 'category='.$category;
	}
	if (count($extraquery) > 0)
	{
		$extraqueries = ' AND ';
		$extraqueries .= implode(' AND ', $extraquery);
	}
}

if ($usergroups['isvipgroup'] != 'yes' && !$is_mod && $waitsystem == 'yes')
{
	include_once(INC_PATH.'/readconfig_waitslot.php');
	$gigs = $CURUSER['uploaded'] / (1024*1024*1024);
	$ratio = (($CURUSER['downloaded'] > 0) ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : 0);
	if ($waitsystemtype == 1)
	{
	  if ($ratio < $ratio1 || $gigs < $upload1) $wait = $delay1;
		elseif ($ratio < $ratio2 || $gigs < $upload2) $wait = $delay2;
			elseif ($ratio < $ratio3 || $gigs < $upload3) $wait = $delay3;
				elseif ($ratio < $ratio4 || $gigs < $upload4) $wait = $delay4;
					else $wait = 0;
	}
	else
	{
	  $wait = $usergroups['waitlimit'];
	}
}
else
{
	$wait = '';
}

stdhead($lang->browse['btitle'],true,'supernote', $javaalert);
$_freelechmod = $_silverleechmod = $_x2mod = false;
include(TSDIR.'/'.$cache.'/freeleech.php');
include(INC_PATH.'/readconfig_kps.php');
if ($__F_START < get_date_time() && $__F_END > get_date_time())
{
	switch($__FLSTYPE)
	{
		case 'freeleech';
			$___notice = show_notice(sprintf($lang->browse['f_leech'], $__F_START, $__F_END),false,$lang->browse['f_leech_h']);
			$_freelechmod = true;
		break;
		case 'silverleech';
			$___notice = show_notice(sprintf($lang->browse['s_leech'], $__F_START, $__F_END),false,$lang->browse['s_leech_h']);
			$_silverleechmod = true;
		break;
		case 'doubleupload';
			$___notice = show_notice(sprintf($lang->browse['d_leech'], $__F_START, $__F_END),false,$lang->browse['d_leech_h']);
			$_x2mod = true;
		break;
	}
}
elseif ($bdayreward == 'yes' AND $bdayrewardtype)
{
	$curuserbday = explode('-', $CURUSER['birthday']);
	if (date('j-n') === $curuserbday[0].'-'.$curuserbday[1])
	{
		switch ($bdayrewardtype)
		{
			case 'freeleech';
				$___notice = show_notice(sprintf($lang->browse['f_leech'], $curuserbday[0].'-'.$curuserbday[1].'-'.date('Y'), ($curuserbday[0] + 1).'-'.$curuserbday[1].'-'.date('Y')),false,$lang->browse['f_leech_h']);
			break;
			case 'silverleech';
				$___notice = show_notice(sprintf($lang->browse['s_leech'], $curuserbday[0].'-'.$curuserbday[1].'-'.date('Y'), ($curuserbday[0] + 1).'-'.$curuserbday[1].'-'.date('Y')),false,$lang->browse['s_leech_h']);
			break;
			case 'doubleupload';
				$___notice = show_notice(sprintf($lang->browse['d_leech'], $curuserbday[0].'-'.$curuserbday[1].'-'.date('Y'), ($curuserbday[0] + 1).'-'.$curuserbday[1].'-'.date('Y')),false,$lang->browse['d_leech_h']);
			break;
		}
	}
}

require(TSDIR.'/'.$cache.'/categories.php');
$subcategories = array();
$searcincategories = array();
if (count($_categoriesS) > 0)
{
	foreach ($_categoriesS as $sc)
	{
		$sc['name'] = htmlspecialchars_uni($sc['name']);
		$searcincategories[] = $sc['id'];
		$seolink = ts_seo($sc['id'],$sc['name'],'c');
		$scdesc = htmlspecialchars_uni($sc['cat_desc']);
		$subcategories[$sc['pid']][] = '<font class="main"><a href="'.$seolink.'" target="_self" alt="'.$scdesc.'" title="'.$scdesc.'" />'.(isset($category) && $category == $sc['id'] || strpos($CURUSER['notifs'], '[cat'.$sc['id'].']') !== FALSE ? '<span style="background-color: rgb( 149, 206, 145);">'.$sc['name'].'</span>' : $sc['name']).'</a></font>';
	}
}

$count = 0;
$categories = '
<table width="100%" border="1" cellspacing="0" cellpadding="5" align="center">
	<tr>
		<td class="thead">'.ts_collapse('cats').'
			<div align="center">
				<strong>
					'.$lang->browse['tcategory'].'
				</strong>
			</div>
		</td>
	</tr>
		'.ts_collapse('cats',2).'
	<tr>
		<td align="center">
			<table border="0" cellspacing="0" cellpadding="0" align="center">
				<tr class="none">
';

if (($rows = count($_categoriesC)) > 0)
{
	foreach ($_categoriesC as $c)
	{
		$searcincategories[] = $c['id'];
		if ($count && $count % 3 == 0)
		{
			$categories .= '</tr><tr class="none">';
		}
		$seolink = ts_seo($c['id'],$c['name'],'c');
		$cname = htmlspecialchars_uni($c['name']);
		$cdesc = htmlspecialchars_uni($c['cat_desc']);
		$categories .= '
		<td class="none">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="48" class="none" height="36" valign="top" align="center" style="padding: 3px;">
						<a href="'.$seolink.'" target="_self" /><img src="'.$BASEURL.'/'.$pic_base_url.$table_cat.'/'.$c['image'].'" border="0" alt="'.$cname.'" title="'.$cname.'" /></a>
					</td>
					<td width="200" class="none" valign="top" align="left" style="padding-left: 7px; padding-top: 3px; padding-right: 3px;">
						<font class="cat_link" style="font-size: 16px;"><a href="'.$seolink.'" target="_self" alt="'.$cdesc.'" title="'.$cdesc.'" /><b>'.(isset($category) && $category == $c['id'] || strpos($CURUSER['notifs'], '[cat'.$c['id'].']') !== FALSE ? '<span style="background-color: rgb( 149, 206, 145);">'.$cname.'</span>' : $cname).'</b></a></font><br />
						'.($subcategories[$c['id']] ? implode(', ', $subcategories[$c['id']]) : '').'
					</td>
				</tr>
			</table>
		</td>';
		$count++;
	}
}

$categories .= '
	</tr></table></td></tr></table><p></p>';

if (preg_match("#\[cat.+#i", $CURUSER['notifs']) AND count($extraquery) == 0 AND count($pagelinks) == 0)
{
	$defaultcategories = array();
	foreach ($searcincategories as $catid)
	{
		if (strpos($CURUSER['notifs'], '[cat'.$catid.']') !== FALSE)
		{
			$defaultcategories[] = $catid;
		}
	}
	if (count($defaultcategories) > 0)
	{
		$extraqueries = ' AND t.category IN ('.implode(',', $defaultcategories).')';
	}
}

require_once(INC_PATH.'/functions_category.php');
$catdropdown = ts_category_list('category',(isset($category) ? $category : ''),'<option value="0" style="color: gray;">'.$lang->browse['alltypes'].'</option>');

$search = '
<script type="text/javascript" src="'.$BASEURL.'/scripts/prototype.js?v='.O_SCRIPT_VERSION.'"></script>
<script type="text/javascript" src="'.$BASEURL.'/ratings/js/scriptaculous.js?v='.O_SCRIPT_VERSION.'"></script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/autocomplete.js?v='.O_SCRIPT_VERSION.'"></script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_torrents.js?v='.O_SCRIPT_VERSION.'"></script>
<table width="100%" border="1" cellspacing="0" cellpadding="5" align="center">
	<tr>
		<td class="thead"><center><b>Newest Torrents</b></center></td></tr><tr>
               
 <td class=tablea><iframe src="carbrowse.php" frameborder="0" scrolling="no" style="width:100%;" height="100"></iframe></td></tr></table><br><table width="100%" border="1" cellspacing="0" cellpadding="5" align="center">
	<tr>
		<td class="thead">'.ts_collapse('cats_search').'
			<div align="center">
				<strong>
					'.$lang->browse['tsearch'].'
				</strong>
			</div>
		</td>	'.ts_collapse('cats_search',2).'
	</tr>
	<tr>
		<td align="right">
			<form method="post" action="'.$_SERVER['SCRIPT_NAME'].'?">
			<input type="hidden" name="do" value="search" />
			'.$lang->browse['bykeyword'].' <input type="text" id="auto_keywords" autocomplete="off" name="keywords" size="40" value="'.(isset($keywords) ? htmlspecialchars_uni($keywords) : '').'" />
			<script type="text/javascript">
				new AutoComplete(\'auto_keywords\', \'ts_ajax.php?action=autocomplete&type=torrent&field=name&keyword=\', { delay: 0.25, resultFormat: AutoComplete.Options.RESULT_FORMAT_TEXT });
			</script>
			<select name="search_type">
				<option value="t_name"'.($search_type == 't_name' ? ' selected="selected"' : '').'>'.$lang->browse['t_name'].'</option>
				<option value="t_description"'.($search_type == 't_description' ? ' selected="selected"' : '').'>'.$lang->browse['t_description'].'</option>
				<option value="t_both"'.($search_type == 't_both' ? ' selected="selected"' : '').'>'.$lang->browse['t_both'].'</option>
				<option value="t_uploader"'.($search_type == 't_uploader' ? ' selected="selected"' : '').'>'.$lang->browse['t_uploader'].'</option>
				<option value="t_genre"'.($search_type == 't_genre' ? ' selected="selected"' : '').'>'.$lang->browse['t_genre'].'</option>
			</select>
			'.$lang->browse['in'].'
				'.$catdropdown.'
			<input type="image" class="none" style="vertical-align: middle;" src="'.$BASEURL.'/'.$pic_base_url.'torrent_search.gif" alt="'.$lang->browse['tsearch'].'" />
			</form>
		<p align="center">
			<form method="post" action="'.$_SERVER['SCRIPT_NAME'].'?advanced_search">
			'.$lang->browse['sastype'].'
			<select name="quick_search">
				<option value="show_daily_torrents"'.($quick_search == 'show_daily_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_daily_torrents'].'</option>
				<option value="show_weekly_torrents"'.($quick_search == 'show_weekly_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_weekly_torrents'].'</option>
				<option value="show_montly_torrents"'.($quick_search == 'show_montly_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_montly_torrents'].'</option>
				<option value="show_dead_torrents"'.($quick_search == 'show_dead_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_dead_torrents'].'</option>
				<option value="show_recommend_torrents"'.($quick_search == 'show_recommend_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_recommend_torrents'].'</option>
				<option value="show_free_torrents"'.($quick_search == 'show_free_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_free_torrents'].'</option>
				<option value="show_silver_torrents"'.($quick_search == 'show_silver_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_silver_torrents'].'</option>
				<option value="show_doubleupload_torrents"'.($quick_search == 'show_doubleupload_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_double_upload_torrents'].'</option>
				<option value="show_external_torrents"'.($quick_search == 'show_external_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['show_external_torrents'].'</option>
				<option value="show_scene_torrents"'.($quick_search == 'show_scene_torrents' ? ' selected="selected"' : '').'>'.$lang->browse['scene4'].'</option>
			</select>
			'.$lang->browse['in'].'
				'.$catdropdown.'
			<input type="image" class="none" style="vertical-align: middle;" src="'.$BASEURL.'/'.$pic_base_url.'torrent_search.gif" alt="'.$lang->browse['tsearch'].'" />
			</form>
		</p>
		<p align="center"><span style="float: right;"><img src="'.$BASEURL.'/'.$pic_base_url.'ajax-loader.gif" alt="" title="" border="0" id="loadingimg" class="inlineimg" name="loadingimg" style="display: none" /> [<a href="#TSShowLatestTorrents" onclick="TSShowTorrents(\'new\'); return false;">'.$lang->browse['show_latest'].'</a>] [<a href="#TSShowLatestTorrents" onclick="TSShowTorrents(\'sticky\'); return false;">'.$lang->browse['show_recommend_torrents'].'</a>] [<a href="#TSShowLatestTorrents" onclick="TSShowTorrents(\'free\'); return false;">'.$lang->browse['show_free_torrents'].'</a>] [<a href="#TSShowLatestTorrents" onclick="TSShowTorrents(\'silver\'); return false;">'.$lang->browse['show_silver_torrents'].'</a>]</span></p>
		</td>
	</tr>
</table>
<div id="quickedit" name="quickedit"></div>
';

$orderbyvalue = 'ORDER by t.sticky, t.added DESC';
if ((isset($_POST['sort_order']) AND $_POST['sort_order'] == 'yes') OR (isset($_GET['sort_order']) AND $_GET['sort_order'] == 'yes'))
{
	$allowedsortbys = array('added','numfiles','comments','seeders','leechers','size','times_completed','owner','sticky');
	if ($torrentspeed == 'yes')
	{
		$allowedsortbys = array_merge($allowedsortbys, array('totalspeed'));
	}
	$sortby = isset($_POST['sortby']) && in_array($_POST['sortby'], $allowedsortbys) ? trim($_POST['sortby']) : (isset($_GET['sortby']) && in_array($_GET['sortby'], $allowedsortbys) ? trim($_GET['sortby']) : '');
	$allowedorderbys = array('DESC', 'ASC');
	$orderby = isset($_POST['orderby']) && in_array($_POST['orderby'], $allowedorderbys) ? trim($_POST['orderby']) : (isset($_GET['orderby']) && in_array($_GET['orderby'], $allowedorderbys) ? trim($_GET['orderby']) : '');
	$orderbyvalue = 'ORDER by '.($sortby != 'totalspeed' ? 't.' : '').$sortby.' '.$orderby;

	if (!empty($sortby) OR !empty($orderby))
	{
		$pagelinks2 = array();
		$pagelinks2[] = 'sort_order=yes';
		$pagelinks2[] = 'sortby='.htmlspecialchars_uni($sortby);
		$pagelinks2[] = 'orderby='.htmlspecialchars_uni($orderby);
	}
}

$from = !empty($from) ? $from : 'FROM torrents t';
$showtorrents = array();
$query = sql_query("SELECT t.id, c.vip {$from} LEFT JOIN categories c ON (t.category=c.id) WHERE ".($showvisible ? "t.visible = 'yes' AND " : "").(preg_match('#E0#is', $CURUSER['options']) ? "t.offensive = 'no' AND " : "").($usergroups['canviewviptorrents'] != 'yes' ? "c.vip = 'no' AND " : "")."t.banned = 'no'".(isset($extraqueries) ? $extraqueries : '')) or sqlerr(__FILE__,__LINE__);
$count = mysql_num_rows($query);
$torrentsperpage = ($CURUSER['torrentsperpage'] <> 0 ? intval($CURUSER['torrentsperpage']) : $ts_perpage);
list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, $_SERVER['SCRIPT_NAME'].'?'.(isset($pagelinks) && count($pagelinks) > 0 ? implode('&amp;', $pagelinks).'&amp;' : '').(isset($pagelinks2) && count($pagelinks2) > 0 ? implode('&amp;', $pagelinks2).'&amp;' : ''));
$groupby = $torrentspeed == 'yes' ? ' GROUP by t.id ' : '';

$query = sql_query("SELECT ".($torrentspeed == 'yes' ? '(t.size * t.times_completed + SUM(p.downloaded)) / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(t.added)) AS totalspeed,' : '')." t.*, c.id as categoryid, c.image, c.name as categoryname, c.vip as isviptorrent, u.username, g.namestyle {$from} LEFT JOIN categories c ON (t.category=c.id) LEFT JOIN users u ON (t.owner=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ".($torrentspeed == 'yes' ? 'LEFT JOIN peers p ON (t.id=p.torrent)' : '')." WHERE ".($showvisible ? "t.visible = 'yes' AND " : "")."t.banned = 'no'".(isset($extraqueries) ? $extraqueries : '')." {$groupby}{$orderbyvalue} $limit") or sqlerr(__FILE__,__LINE__);

if ($progressbar == 'yes')
{
	include_once(INC_PATH.'/functions_external.php');
}

$contentheader = ($is_mod ? '
<script type="text/javascript">
	function check_it(wHAT)
	{
		if (wHAT.value == "move")
		{
			document.getElementById("movetorrent").style.display = "block";
		}
		else
		{
			document.getElementById("movetorrent").style.display = "none";
		}
	}
</script>
<form method="post" action="'.$BASEURL.'/admin/index.php?act=manage_torrents" name="manage_torrents">
<input type="hidden" name="do" value="update" />
<input type="hidden" name="return" value="yes" />
<input type="hidden" name="return_address" value="'.$_SERVER['SCRIPT_NAME'].'?page='.intval(isset($_GET['page']) ? $_GET['page'] : 0).'&amp;'.(isset($pagelinks) && count($pagelinks) > 0 ? implode('&amp;', $pagelinks).'&amp;' : '').(isset($pagelinks2) && count($pagelinks2) > 0 ? implode('&amp;', $pagelinks2) : '').'" />
' : '').'
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="thead" align="center" style="padding: 10px 0 0 0;">'.$lang->browse['type'].'</td>
		<td class="thead" align="left" style="padding: 10px 0 0 0;">&nbsp;'.$lang->browse['t_name'].'</td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;"><img src="'.$BASEURL.'/'.$pic_base_url.'/down1.gif" border="0" class="inlineimg"></td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;"><img src="'.$BASEURL.'/'.$pic_base_url.'/files.gif" border="0" class="inlineimg"></td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;"><img src="'.$BASEURL.'/'.$pic_base_url.'/comments.gif" border="0" class="inlineimg"></td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;"><img src="'.$BASEURL.'/'.$pic_base_url.'/seeders.gif" border="0" class="inlineimg"></td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;"><img src="'.$BASEURL.'/'.$pic_base_url.'/leechers.gif" border="0" class="inlineimg"></td>
		'.($progressbar == 'yes' ? '<td class="thead" align="center" style="padding: 10px 0 0 0;">'.$lang->global['avprogress'].'</td>' : '').'
		'.($torrentspeed == 'yes' ? '<td class="thead" align="center" style="padding: 10px 0 0 0;">'.$lang->global['speed'].'</td>' : '').'
		<td class="thead" align="center" style="padding: 10px 0 0 0;">'.$lang->global['size'].' / '.$lang->global['snatched'].'</td>
		<td class="thead" align="center" style="padding: 10px 0 0 0;">'.$lang->global['uploader'].'</td>
		'.($is_mod ? '<td class="thead" align="center" style="padding: 10px 0 0 0;"><input checkall="group1" onclick="javascript: return select_deselectAll (\'manage_torrents\', this, \'group1\');" type="checkbox" /></td>' : '').'
	</tr>
';

$__colspan = 12;
($progressbar != 'yes' ? $__colspan-- : '');
($torrentspeed != 'yes' ? $__colspan-- : '');
(!$is_mod ? $__colspan-- : '');
$contentmiddle = $menu_nav = '';
require_once(INC_PATH.'/functions_mkprettytime.php');
if (mysql_num_rows($query) > 0)
{
	require_once(INC_PATH.'/functions_imdb_rating.php');
	while ($torrents = mysql_fetch_assoc($query))
	{
		if (($torrents['offensive'] == 'yes' && preg_match('#E0#is', $CURUSER['options'])) OR ($usergroups['canviewviptorrents'] != 'yes' && $torrents['isviptorrent'] == 'yes'))
		{
			continue;
		}

		$showupdatebutton=true;

		if (time() - $torrents['ts_external_lastupdate'] < (TS_TIMEOUT*4))
		{
			$showupdatebutton=false;
		}

		$showwait=$elapsed=$color='';

		if ($wait > 0)
		{
			$elapsed = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($torrents['added'])) / 3600);
			if ($elapsed < $wait AND $torrents['ts_external'] != 'yes')
			{
				 $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
				 $showwait = "<span style='float: right'><a href=\"$BASEURL/faq.php#46\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></span>";
			}
		}

		$orj_name_ = $torrents['name'];
		$torrents['name'] = htmlspecialchars_uni($torrents['name']);
		$uploader='<a href="#" id="torrentuser'.$torrents['owner'].$torrents['id'].'">'.get_user_color($torrents['username'], $torrents['namestyle']).'</a>';
		$disable_user_menu=false;

		if ($torrents['anonymous'] == 'yes')
		{
			if ($torrents['owner'] != $CURUSER['id'] && !$is_mod)
			{
				$uploader = $lang->global['anonymous'];
				$disable_user_menu=true;
			}
			else
			{
				$uploader = $uploader.'<br />'.$lang->global['anonymous'];
			}
		}

		$isScene = '';

		if ($torrents['isScene'] > 0)
		{
			$isScene = sprintf($lang->browse['scene3'], mkprettytime($torrents['isScene']));
		}

		$seolink = ts_seo($torrents['categoryid'],$torrents['categoryname'],'c');
		$seolink2 = ts_seo($torrents['id'],$torrents['name'],'s');
		$seolink3 = ts_seo($torrents['id'],$torrents['name'],'d');

		$downloadinfo = sprintf($lang->browse['downloadinfo'], $torrents['name']);
		$categoryinfo = sprintf($lang->browse['categoryinfo'], $torrents['categoryname']);
		$sratio = $torrents["leechers"] > 0 ? $torrents["seeders"] / $torrents["leechers"] : 1;
		$lratio = $torrents["seeders"] > 0 ? $torrents["leechers"] / $torrents["seeders"] : 1;

		$torrent_files = '<b>'.($torrents['numfiles'] > 1 ? '<a href="'.$BASEURL.'/details.php?id='.$torrents['id'].'&tab=filelist">' : '').ts_nf($torrents['numfiles']).($torrents['numfiles'] > 1 ? '</a>' : '').'</b>';

		$torrent_seeders = ($torrents['ts_external'] == 'no' ? '<font color="'.get_slr_color($sratio).'">'.($torrents['seeders'] > 0 && $usergroups['canpeers'] == 'yes' ? '<a href="'.$BASEURL.'/details.php?id='.$torrents['id'].'&tab=peers">' : '').'<b>'.ts_nf($torrents['seeders']).'</b>'.($torrents['seeders'] > 0 && $usergroups['canpeers'] == 'yes' ? '</a>' : '').'</font>' : '<b>'.ts_nf($torrents['seeders']).'</b>');

		$torrent_leechers = ($torrents['ts_external'] == 'no' ? '<font color="'.get_slr_color($lratio).'">'.($torrents['leechers'] > 0 && $usergroups['canpeers'] == 'yes' ? '<a href="'.$BASEURL.'/details.php?id='.$torrents['id'].'&tab=peers">' : '').'<b>'.ts_nf($torrents['leechers']).'</b>'.($torrents['leechers'] > 0 && $usergroups['canpeers'] == 'yes' ? '</a>' : '').'</font>' : '<b>'.ts_nf($torrents['leechers']).'</b>');

		$torrents['times_completed'] = ($torrents['ts_external'] == 'yes' && $torrents['seeders'] > 0 && $torrents['times_completed'] == 0 ? $torrents['seeders'] : $torrents['times_completed']);
		$torrent_snatched = ($torrents['ts_external'] == 'no' && $torrents['times_completed'] > 0 && $usergroups['cansnatch'] == 'yes' ? sprintf($lang->browse['info3'], '<a href="'.$BASEURL.'/viewsnatches.php?id='.$torrents['id'].'"><b>'.ts_nf($torrents['times_completed']).'</b></a>') : sprintf($lang->browse['info3'], '<b>'.ts_nf($torrents['times_completed']).'</b>'));
		$torrent_comments = ($torrents['comments'] > 0 ? '<a href="'.$BASEURL.'/details.php?id='.$torrents['id'].'&tab=comments">' : '').'<b>'.ts_nf($torrents['comments']).'</b>'.($torrents['comments'] > 0 ? '</a>' : '');

		$isnew = ($torrents['added'] > $CURUSER['last_login'] ? '<img src="'.$BASEURL.'/'.$pic_base_url.'newdownload.gif" class="inlineimg" alt="'.$lang->browse['newtorrent'].'" title="'.$lang->browse['newtorrent'].'" />' : '');
		$isfree = ($torrents['free'] == 'yes' || ($_freelechmod && $torrents['ts_external'] != 'yes') ? '<img src="'.$BASEURL.'/'.$pic_base_url.'freedownload.gif" class="inlineimg" alt="'.$lang->browse['freedownload'].'" title="'.$lang->browse['freedownload'].'" />' : '');
		$issilver = ($torrents['silver'] == 'yes' || ($_silverleechmod && $torrents['ts_external'] != 'yes') ? '<img src="'.$BASEURL.'/'.$pic_base_url.'silverdownload.gif" class="inlineimg" alt="'.$lang->browse['silverdownload'].'" title="'.$lang->browse['silverdownload'].'" />' : '');
		$isrequest = ($torrents['isrequest'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'isrequest.gif" class="inlineimg" alt="'.$lang->browse['requested'].'" title="'.$lang->browse['requested'].'" />' : '');
		$isnuked = ($torrents['isnuked'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'isnuked.gif" class="inlineimg" alt="'.sprintf($lang->browse['nuked'], $torrents['WhyNuked']).'" title="'.sprintf($lang->browse['nuked'], $torrents['WhyNuked']).'" />' : '');
		$issticky = ($torrents['sticky'] == 'yes' ? '<img src="'.$BASEURL.'/'.$pic_base_url.'sticky.gif" alt="'.$lang->browse['sticky'].'" title="'.$lang->browse['sticky'].'" />' : '');
		$isexternal = ($torrents['ts_external'] == 'yes' && $showupdatebutton ? "<a href=\"#showtorrent".$torrents['id']."\" onclick=\"UpdateExternalTorrent('./include/ts_external_scrape/ts_update.php','id=".$torrents['id']."&ajax_update=true',".$torrents['id'].")\"><img src='".$BASEURL."/".$pic_base_url."external.gif' border='0' alt='".$lang->browse['update']."' title='".$lang->browse['update']."' class='inlineimg' /></a>" : (isset($_GET['tsuid']) && $_GET['tsuid'] == $torrents['id'] ? "<img src='".$BASEURL."/".$pic_base_url."input_true.gif' border='0' alt='".$lang->browse['updated']."' title='".$lang->browse['updated']."' />" : ""));
		$isdoubleupload = ($torrents['doubleupload'] == 'yes' || ($_x2mod && $torrents['ts_external'] != 'yes') ? '<img src="'.$BASEURL.'/'.$pic_base_url.'x2.gif" alt="'.$lang->browse['dupload'].'" title="'.$lang->browse['dupload'].'" class="inlineimg" />' : '');
		$javascript_tname = addslashes(htmlspecialchars($torrents['name']));
		$torrents['name'] = cutename($orj_name_,60);
		$torrent_image = '<a href="'.$seolink.'" target="_self" /><img src="'.$BASEURL.'/'.$pic_base_url.$table_cat.'/'.$torrents['image'].'" border="0" alt="'.$categoryinfo.'" title="'.$categoryinfo.'" /></a>';
		$name_torrent = '<a href="'.($UseQuickMenu ? '#' : $seolink2).'" id="torrentmenu'.$torrents['id'].'" /><b>'.(!empty($keywords) ? highlight(htmlspecialchars_uni($keywords), $torrents['name']) : $torrents['name']).'</b></a> '.$isnew.' '.$issticky.' '.$isfree.' '.$issilver.' '.$isdoubleupload.' '.$isrequest.' '.$isnuked.' <span id="isexternal_'.$torrents['id'].'">'.$isexternal.'</span>';
		$torrent_download_link = (!$is_bookmark_page ? '<a href="'.$seolink3.'" title="'.$downloadinfo.'" alt="'.$downloadinfo.'" /><img src="'.$BASEURL.'/'.$pic_base_url.'dl.gif"></a>' : '<a href="'.$BASEURL.'/bookmarks.php?torrentid='.$torrents['id'].'&amp;action=delete" title="" alt="" /><img src="'.$BASEURL.'/'.$pic_base_url.'delete.gif"></a>');

		if ($progressbar == 'yes')
		{
			$shareratio = ($torrents['seeders'] == 0 ? 0 : ($torrents['leechers'] == 0 ? 100 : sprintf("%.2f", ($torrents['seeders'] / $torrents['leechers']) * 100)));
			$health = ts_external_share_ratio($shareratio);
			$torrent_info = '<td align="center">'.$health.'</td>';
		}

		if ($torrentspeed == 'yes')
		{
			if ($torrents['ts_external'] == 'yes')
			{
				$speed = $lang->browse['external'];
			}
			else
			{
				if ($torrents['seeders'] > 0 && $torrents['leechers'] > 0)
				{
					$speed = mksize($torrents['totalspeed']) . '/s';
				}
				else
				{
					$speed = $lang->browse['notraffic'];
				}
			}
			$speed = '<td align="center">'.$speed.'</td>';
		}

		$torrents['size'] = mksize($torrents['size']);

		$ShowImdb=false;
		if ($IMDBRating = TSSEGetIMDBRatingImage($torrents['t_link']))
		{
			$ShowImdb=true;
		}

		$TorrentAdded = '<br />&nbsp;<b>'.$lang->global['added'].':</b> '.my_datee($dateformat, $torrents['added']).' '.my_datee($timeformat, $torrents['added']).($ShowImdb ? '<br />&nbsp;'.$IMDBRating['image'].' '.$IMDBRating['rating'] : '');
		$contentmiddle .= '
		<tr'.($torrents['sticky'] == 'yes' ? ' class="sticky"' : '').'>
			<td width="1">'.$torrent_image.'</td>
			<td align="left">'.$showwait.'<a name="#showtorrent'.$torrents['id'].'"></a>&nbsp;'.$name_torrent.$TorrentAdded.($isScene ? '<br />&nbsp;'.$isScene : '').'</td>
			<td align="center">'.$torrent_download_link.'</td>
			<td align="center">'.$torrent_files.'</td>
			<td align="center">'.$torrent_comments.'</td>
			<td align="center"><div id="seeders_'.$torrents['id'].'">'.$torrent_seeders.'</div></td>
			<td align="center"><div id="leechers_'.$torrents['id'].'">'.$torrent_leechers.'</div></td>
			'.$torrent_info.'
			'.$speed.'
			<td align="center">'.$torrents['size'].'<br />'.$torrent_snatched.'</td>
			<td align="center">'.$uploader.'</td>
			'.($is_mod ? '
			<td align="center"><input type="checkbox" name="torrentid[]" value="'.$torrents['id'].'" checkme="group1" /></td>' : '').'
		</tr>';

		$menu_nav .= ($UseQuickMenu ? '
		<script type="text/javascript">
			menu_register("torrentmenu'.$torrents['id'].'", true);
		</script>
		<div id="torrentmenu'.$torrents['id'].'_menu" class="menu_popup" style="display:none;">
			<table border="1" cellspacing="0" cellpadding="2">
				<tr>
					<td colspan="2" align="center" class="thead"><b>'.$lang->global['quickmenu'].'</b></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$seolink3.'" title="'.$downloadinfo.'" alt="'.$downloadinfo.'"'.$warnexternal.' /><b>'.$lang->browse['download'].'</b></a></td>
					<td rowspan="'.($is_mod ? 8 : 3).'" align="center" valign="middle"><div align="center">'.(!empty($torrents['t_image']) ? '<a href="javascript:popImage(\''.htmlspecialchars_uni($torrents['t_image']).'\',\'Image Preview\')"><span class="smalltext">'.$lang->browse['t_image'].'</span></a><br /><img src="'.htmlspecialchars_uni($torrents['t_image']).'" border="0" height="150" width="150" alt="'.strip_tags($torrents['name']).'" title="'.strip_tags($torrents['name']).'" \>' : $lang->browse['nopreview']).'</div></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$seolink2.'"><b>'.$lang->browse['viewtorrent'].'</b></a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/details.php?id='.$torrents['id'].'&tab=comments"><b>'.$lang->browse['viewcomments'].'</b></a></td>
				</tr>'.($is_mod ? '
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/admin/index.php?act=torrent_info&amp;id='.$torrents['id'].'"><b>'.$lang->browse['tinfo'].'</b></a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/edit.php?id='.$torrents['id'].'"><b>'.$lang->browse['edit'].'</b></a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="#quickedit" onClick="QuickEditTorrentSubject(\''.$torrents['id'].'\', \''.$javascript_tname.'\');"><b>'.$lang->browse['quickedit'].'</b></a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/admin/index.php?act=nuketorrent&amp;id='.$torrents['id'].'"><b>'.$lang->browse['nuke'].'</b></a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/admin/index.php?act=fastdelete&amp;id='.$torrents['id'].'"><b>'.$lang->browse['delete'].'</b></a></td></td>
				</tr>' : '').'
			</table>
		</div>' : '').($disable_user_menu == false ? '
		<script type="text/javascript">
			menu_register("torrentuser'.$torrents['owner'].$torrents['id'].'", true);
		</script>
		<div id="torrentuser'.$torrents['owner'].$torrents['id'].'_menu" class="menu_popup" style="display:none;">
			<table border="1" cellspacing="0" cellpadding="2">
				<tr>
					<td align="center" class="thead"><b>'.$lang->global['quickmenu'].' '.$torrents['username'].'</b></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.ts_seo($torrents['owner'], $torrents['username']).'">'.$lang->global['qinfo1'].'</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/browse.php?do=search&keywords='.htmlspecialchars_uni($torrents['username']).'&category=0&search_type=t_uploader">'.sprintf($lang->global['qinfo9'], $torrents['username']).'</a></td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/sendmessage.php?receiver='.$torrents['owner'].'">'.sprintf($lang->global['qinfo2'], $torrents['username']).'</td>
				</tr>
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/friends.php?action=add_friend&friendid='.$torrents['owner'].'">'.sprintf($lang->global['qinfo5'], $torrents['username']).'</td>
				</tr>
				'.($is_mod ? '
				<tr>
					<td class="subheader"><a href="'.$BASEURL.'/admin/edituser.php?action=edituser&userid='.$torrents['owner'].'">'.$lang->global['qinfo6'].'</a></td></tr><tr><td class="subheader"><a href="'.$BASEURL.'/admin/edituser.php?action=warnuser&userid='.$torrents['owner'].'">'.$lang->global['qinfo7'].'</td>
				</tr>' : '').'
			</table>
		</div>' : '');
	}
}
else
{
	$contentmiddle = '
		<tr>
			<td colspan="'.$__colspan.'">
				'.$lang->browse['tryagain'].'
			</td>
		</tr>';
}

$contentmiddle .= ($is_mod ? '
<tr>
	<td colspan="'.$__colspan.'" align="right">
		<p id="selectaction" style="display:block; margin-top: 5px; margin-right: 5px;">
			Select Action:
			<select name="actiontype" onchange="check_it(this)">
				<option value="0">Select action</option>
				<option value="move">Move selected torrents</option>
				<option value="delete">Delete selected torrents</option>
				<option value="sticky">Sticky/Unsticky selected torrents</option>
				<option value="free">Set Free/NonFree selected torrents</option>
				<option value="silver">Set Silver/NonSilver selected torrents</option>
				<option value="visible">Set Visible/Unvisible selected torrents</option>
				<option value="anonymous">Anonymize/Non Anonymize selected torrents</option>
				<option value="banned">Ban/UnBan selected torrents</option>
				<option value="nuke">Nuke/UnNuke selected torrents</option>
				<option value="doubleupload">Set Double Upload YES/NO</option>
				<option value="openclose">Open/Close for Comment Posting</option>
			</select>
		</p>
		<p id="movetorrent" style="display:none; margin-right: 5px;">
			Select Category: '.$catdropdown.'
		</p>
		<p id="doaction" style="display:block; margin-right: 5px;">
			<input type="submit" value="do it"> <input type="reset" value="reset fields" />
		</p>
	</td>
</tr>
</form>
</table>
' : '</table>').'
'.$menu_nav.'
<script type="text/javascript">
	menu.activate(true);
</script>
<script type="text/javascript" src="'.$BASEURL.'/scripts/ts_update.js?v='.O_SCRIPT_VERSION.'"></script>';

if (count($pagelinks) > 0)
{
	$hiddenvalues;
	foreach ($pagelinks as $name)
	{
		$values = explode('=', $name);
		$hiddenvalues .= '
		<input type="hidden" name="'.$values[0].'" value="'.$values[1].'" />
		';
	}
}

if (isset($_GET['category']) && is_valid_id($_GET['category']) && empty($quick_search))
{
	$sqfield = "&amp;browse_categories&amp;category=".intval($_GET['category']);
}

$finishcontent = '
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
		<td class="none" width="40%">
			<fieldset style="text-align: center; line-height: 25px; padding: 5px; border: solid 1px #000;">
				<LEGEND>'.$lang->browse['b_info'].'</LEGEND>
				'.str_replace("|link|", "$BASEURL/$pic_base_url", $lang->browse['legend_browse']).'
			</fieldset>
		</td>
		<td class="none" width="60%">
			<fieldset style="text-align: center; line-height: 25px; padding: 5px; border: solid 1px #000;">
				<LEGEND>'.$lang->browse['f_options'].'</LEGEND>
				<span style="float: right;">
					<form method="post" action="'.$_SERVER['SCRIPT_NAME'].'?page='.intval(isset($_GET['page']) ? $_GET['page']: 0).(isset($sqfield) ? $sqfield : '').'">
						<input type="hidden" name="sort_order" value="yes" />
						<input type="hidden" name="page" value="'.intval(isset($_GET['page']) ? $_GET['page']: 0).'" />
						'.(isset($hiddenvalues) ? $hiddenvalues : '').'
						'.$lang->browse['sortby1'].'
						<select name="sortby">
							<option value="added"'.(isset($sortby) && $sortby == 'added' ? ' selected="selected"' : '').'>'.$lang->global['added'].'</option>
							<option value="numfiles"'.(isset($sortby) && $sortby == 'numfiles' ? ' selected="selected"' : '').'>'.$lang->browse['sortby2'].'</option>
							<option value="comments"'.(isset($sortby) && $sortby == 'comments' ? ' selected="selected"' : '').'>'.$lang->browse['sortby3'].'</option>
							<option value="seeders"'.(isset($sortby) && $sortby == 'seeders' ? ' selected="selected"' : '').'>'.$lang->browse['sortby4'].'</option>
							<option value="leechers"'.(isset($sortby) && $sortby == 'leechers' ? ' selected="selected"' : '').'>'.$lang->browse['sortby5'].'</option>
							<option value="size"'.(isset($sortby) && $sortby == 'size' ? ' selected="selected"' : '').'>'.$lang->browse['sortby6'].'</option>
							<option value="times_completed"'.(isset($sortby) && $sortby == 'times_completed' ? ' selected="selected"' : '').'>'.$lang->browse['sortby7'].'</option>
							<option value="owner"'.(isset($sortby) && $sortby == 'owner' ? ' selected="selected"' : '').'>'.$lang->browse['sortby8'].'</option>
							<option value="sticky"'.(isset($sortby) && $sortby == 'sticky' ? ' selected="selected"' : '').'>'.$lang->browse['sortby9'].'</option>
							'.($torrentspeed == 'yes' ? '<option value="totalspeed"'.(isset($sortby) && $sortby == 'totalspeed' ? ' selected="selected"' : '').'>'.$lang->browse['speed'].'</option>' : '').'
						</select>
						'.$lang->browse['orderby1'].'
						<select name="orderby">
							<option value="ASC"'.(isset($orderby) && $orderby == 'ASC' ? ' selected="selected"' : '').'>'.$lang->browse['orderby3'].'</option>
							<option value="DESC"'.(isset($orderby) && $orderby == 'DESC' ? ' selected="selected"' : '').'>'.$lang->browse['orderby2'].'</option>
						</select>
						<input type="image" class="none" style="vertical-align: middle;" src="'.$BASEURL.'/'.$pic_base_url.'torrent_search.gif" alt="'.$lang->browse['tsearch'].'" />&nbsp;&nbsp;
					</form>
				</span>
			</fieldset>
		</td>
	</tr>
</table>
';

$QuickEditTorrentSubject='';
if ($is_mod)
{
	$QuickEditTorrentSubject = '
	<script type="text/javascript">
		function checkSubject()
		{
			var userEntered = document.getElementById(\'subject\').value;
			if (userEntered == "")
			{
				alert("'.$lang->global['dontleavefieldsblank'].'");
				document.QuickEditForm.subject.focus();
				return false;
			}
			else
			{
				return true;
			}
		}
		function QuickEditTorrentSubject(TorrentID,TorrentSubject)
		{
			document.getElementById(\'quickedit\').innerHTML = \'<br /><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center"><tr><td class="thead">'.$lang->browse['quickedit'].'</td></tr><tr><td><form method="post" action="'.$_SERVER['SCRIPT_NAME'].'?page='.intval(isset($_GET['page']) ? $_GET['page'] : 0).'&amp;'.(isset($pagelinks) && count($pagelinks) > 0 ? implode('&amp;', $pagelinks).'&amp;' : '').(isset($pagelinks2) && count($pagelinks2) > 0 ? implode('&amp;', $pagelinks2) : '').'" onSubmit="return checkSubject()" name="QuickEditForm"><input type="hidden" name="tid" value="\'+TorrentID+\'" /> <input type="text" id="subject" name="subject" value="\'+TorrentSubject+\'" size="100" /> <input type="submit" value="'.$lang->global['buttonsave'].'" class="button" /> <input type="reset" value="'.$lang->global['buttonreset'].'" class="button" /></form></td></tr></table>\';
		}
	</script>
	';
}
echo $___notice.$categories.$search.($pagertop ? $pagertop : '<p></p>').'<div style="display:block;" id="showcontents">'.$contentheader.$contentmiddle.'</div>'.($pagerbottom ? $pagerbottom : '').$finishcontent.$QuickEditTorrentSubject;
unset($categories,$search,$contentheader,$contentmiddle,$finishcontent);
stdfoot();
# Function get_slr_color v.0.1
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
# Function unesc v.0.1
function unesc($x)
{
    if (get_magic_quotes_gpc()) return stripslashes($x);
    return $x;
}
?>
