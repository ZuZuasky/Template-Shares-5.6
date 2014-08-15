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
if(!defined('IN_TRACKER')) die('Hacking attempt!');
/* TS Special Edition Default Template by xam - v5.6 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta name="generator" content="<?php echo $title; ?>" />
<meta name="revisit-after" content="3 days" />
<meta name="robots" content="index, follow" />
<meta name="description" content="<?php echo $metadesc; ?>" />
<meta name="keywords" content="<?php echo $metakeywords; ?>" />
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="<?php echo $BASEURL; ?>/include/templates/<?php echo $defaulttemplate; ?>/style/style.css" type="text/css" media="screen" />
<?php echo (isset($includeCSS) ? $includeCSS : ''); ?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo $BASEURL; ?>/rss.php" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php echo $BASEURL; ?>/rss.php" />
<link rel="shortcut icon" href="<?php echo $BASEURL; ?>/favicon.ico" type="image/x-icon" />
<script type="text/javascript">
	//<![CDATA[
	var baseurl="<?php echo htmlspecialchars_uni($BASEURL); ?>";
	var dimagedir="<?php echo $BASEURL; ?>/<?php echo $pic_base_url; ?>";
	var charset="<?php echo $charset; ?>";
	var userid="<?php echo (isset($CURUSER['id']) ? (int)$CURUSER['id'] : 0); ?>";
	//]]>
</script>
<?php
$lang->load('scripts');
if (defined('NcodeImageResizer') OR (isset($CURUSER) AND $CURUSER['announce_read'] == 'no') OR THIS_SCRIPT == 'index.php')
{
	include_once(INC_PATH.'/javascript_resizer.php');
}
echo '
<script type="text/javascript" src="'.$BASEURL.'/scripts/main.js?v='.O_SCRIPT_VERSION.'"></script>
'.(isset($includescripts) ? $includescripts : '').(isset($includescripts2) ? $includescripts2 : '').'
</head>
<body class="yui-skin-sam">
'.(!$CURUSER ? '
<div id="topbar" class="subheader">
	<table width="100%">
		<tr>
			<td width="99%" class="none">'.$lang->global['unregistered'].'</td>
			<td width="1%" class="none"><a href="#" onclick="closebar(); return false"><img style="float: left;" src="'.$BASEURL.'/'.$pic_base_url.'close.gif" border="0" alt="" /></a></td>
		</tr>
	</table>
</div>'
: '').'
<div class="content">
	<div id="top">
		<div style="float: left; color:fff; padding: 20px 25px 0 40px; position:relative;">
		'.(isset($CURUSER) ? '
			<script type="text/javascript">
				//<![CDATA[
				function SearchPanel()
				{
					if (document.getElementById(\'search-torrent\').style.display == \'none\')
					{
						ts_show(\'search-torrent\');
					}
					else
					{
						ts_hide(\'search-torrent\');
					}
				}
				//]]>
			</script>
			<a href="#" onclick="javascript: SearchPanel(); return false;">'.$lang->global['storrent'].'</a>			
			<form method="post" action="'.$BASEURL.'/browse.php?do=search&amp;search_type=t_both">
			<input type="hidden" name="search_type" value="t_both" />
			<input type="hidden" name="do" value="search" />
			<div id="search-torrent" style="display: none; position: absolute;">
				<table border="0" cellpadding="2" cellspacing="0" width="420px;">
					<tr>
						<td class="thead"><span style="float: right; cursor: pointer;" onclick="javascript: SearchPanel(); return false;"><b>X</b></span>'.$lang->global['storrent'].'</td>
					</tr>
					<tr>
						<td>'.$lang->global['storrent2'].' <input type="text" size="40" value="" name="keywords" /> <input type="submit" value="'.$lang->global['buttonsearch'].'" />
					</tr>
				</table>				
			</div>
			</form>
			' : '
			<a href="'.$BASEURL.'/login.php">'.$lang->header['login'].'</a> | <a href="'.$BASEURL.'/signup.php">'.$lang->header['register'].'</a> | '.$lang->header['recoverpassword'].' <a href="'.$BASEURL.'/recover.php">'.$lang->header['viaemail'].'</a> | <a href="'.$BASEURL.'/recoverhint.php">'.$lang->header['viaquestion'].'</a>
			').'
		</div>
		<div class="padding" align="center">';
if (isset($CURUSER))
{
?>
	<span>
		<?php echo $lang->global['welcomeback']; ?> <a href="<?php echo ts_seo($CURUSER['id'], $CURUSER['username']); ?>"><?php echo get_user_color($CURUSER['username'],$usergroups['namestyle'],true); ?></a> <?$medaldon?> <?$warn?> (<?php echo htmlspecialchars_uni($CURUSER['ip']); ?>) <a href="<?php echo $BASEURL?>/logout.php?logouthash=<?php echo $_SESSION['hash']; ?>" onclick="return log_out()"><?php echo $lang->global['logout']; ?></a></span>&nbsp;&nbsp;&nbsp;&nbsp;
		
		<span>
		<?php echo $lang->global['ratio']; ?> <?php echo $ratio?>&nbsp;&nbsp;<?php echo $lang->global['bonus']; ?> <a href="<?php echo $BASEURL?>/mybonus.php"><?php echo number_format($CURUSER['seedbonus'], 2)?></a>&nbsp;&nbsp;<?php echo maxslots().$lang->global['uploaded']; ?> <font color="green"><?php echo mksize($CURUSER['uploaded'])?></font>&nbsp;&nbsp;<?php echo $lang->global['downloaded']; ?> <font color="red"><?php echo mksize($CURUSER['downloaded'])?></font></span>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	echo ($inboxpic ? '<a href="'.$BASEURL.'/messages.php">'.$inboxpic.'</a>' : '').'
	<a href="'.$BASEURL.'/friends.php"><img border="0" alt="'.$lang->header['extrafriends'].'" title="'.$lang->header['extrafriends'].'" src="'.$BASEURL.'/'.$pic_base_url.'buddylist.gif" /></a>
	<a href="'.$BASEURL.'/users.php"><img border="0" alt="'.$lang->header['extramembers'].'" title="'.$lang->header['extramembers'].'" src="'.$BASEURL.'/'.$pic_base_url.'userlist.gif" /></a>
	<a href="'.$BASEURL.'/getrss.php"><img border="0" alt="'.$lang->header['extrarssfeed'].'" title="'.$lang->header['extrarssfeed'].'" src="'.$BASEURL.'/'.$pic_base_url.'rss.gif" width="11" height="12" /></a>';
}
echo '
</div>
</div>
<div id="header">
<div class="f_search">';
$dirlist = '';
$link = 0;
foreach (dir_list(INC_PATH.'/languages') as $language)
{	
	if ($link && $link % 4 == 0)
		$dirlist .= '<br />';
	$dirlist .= '
	<a href="'.$BASEURL.'/set_language.php?language='.$language.'&redirect=yes"><img src="'.$BASEURL.'/include/languages/'.$language.'/flag/flag.gif" alt="'.$language.'" title="'.$language.'" width="32" height="20" border="0" /></a>&nbsp;';
	++$link;
}
if (isset($CURUSER))
{
	$Progress_so_far = ($Progress_so_far >= 100 ? '100' : number_format($Progress_so_far, 1));
	echo '<div id="donation"><font class="small"><a href="'.$BASEURL.'/donate.php" onclick="window.open(\''.$BASEURL.'/scripts/pbar/ts_donation_status.php\',\'ts_donation_status\',\'toolbar=no, scrollbars=no, resizable=no, width=600, height=300, top=250, left=250\'); return false;">'.$lang->header['donate'].'</a></font><div style="width: 80px; border: 1px solid black; text-align: left; background: #376088 repeat;"><div style="padding-left: 0px; color: white; font-weight: bold; width: '.$Progress_so_far.'%; border: 0px solid black; font-size: 8pt; background: #4A81B6 repeat;">&nbsp;'.number_format($Progress_so_far, 1).'%'.($Progress_so_far >= 100 ? '&nbsp;<font class="small">'.$lang->header['thanks'].'</font>' : '').'</div></div></div>';
}
?>
</div>
<div class="title">
<h1>&nbsp;</h1>
<h6>&nbsp;</h6>
<?php echo ($link > 1 ? $dirlist : '' ); unset($link, $dirlist); ?>
</div>
</div>
<div id="subheader">
<div id="menu">
<script type="text/javascript" src="<?php echo $BASEURL; ?>/scripts/dropdown.js<?php echo '?v='.O_SCRIPT_VERSION; ?>"></script>
<ul>
<li class="page_item"><a href="<?php echo $BASEURL; ?>"><?php echo $lang->global['home']; ?></a></li>
<?php if (isset($CURUSER)) { ?>	
	<li class="page_item"><a href="<?php echo $BASEURL; ?>/ts_social_groups.php"><font color="blue">Social Groups</font></a></li>
	<li class="page_item"><a href="<?php echo $BASEURL; ?>/ts_applications.php"><font color="darkred">Applications</font></a></li>	
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu4, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['forums']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu5, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['browse']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu6, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['requests']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu12, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['upload']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu7, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['usercp']; ?></a></li>
	<li class="page_item"><a href="<?php echo $BASEURL; ?>/irc.php"><font color="yellow"><?php echo $lang->global['irc']; ?></font></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu8, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['top10']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu9, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['help']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu11, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['extra']; ?></a></li>
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu10, '150px')" onmouseout="delayhidemenu()"><?php echo $lang->global['staff']; ?></a></li>
<?php
	if ($usergroups['canstaffpanel'] == 'yes' && $usergroups['cansettingspanel'] != 'yes' && $usergroups['issupermod'] != 'yes')
	{
		echo '<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu1, \'150px\')" onmouseout="delayhidemenu()"><font color="yellow">'.$lang->global['staffmenu'].'</font></a></li>';
	}
	elseif ($usergroups['canstaffpanel'] == 'yes' && $usergroups['cansettingspanel'] != 'yes' && $usergroups['issupermod'] == 'yes')
	{	
		echo '<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu2, \'150px\')" onmouseout="delayhidemenu()"><font color="yellow">'.$lang->global['staffmenu'].'</font></a></li>';
	}
	elseif ($usergroups['cansettingspanel'] == 'yes')
	{	
		echo '<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu3, \'150px\')" onmouseout="delayhidemenu()"><font color="yellow">'.$lang->global['staffmenu'].'</font></a></li>';
	}
}
else
{
	echo '
	<li class="page_item"><a href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu9, \'150px\')" onmouseout="delayhidemenu()">'.$lang->global['help'].'</a></li>
	<li class="page_item">
	<a href="'.$BASEURL.'/ts_tags.php">
		<script type="text/javascript">
			//<![CDATA[
			document.write(l_searchcloud);
			//]]>
		</script>
		</a>
	</li>';
}
echo '
	</ul>
	</div>
	</div>
	<div id="main">
	<div class="left_side">
';

if ($offlinemsg)
	$warnmessages[] = sprintf($lang->header['trackeroffline'], $BASEURL);

if (!$__ismod && isset($CURUSER) && $CURUSER['donoruntil'] != '0000-00-00 00:00:00' && warn_donor(strtotime($CURUSER['donoruntil']) - gmtime()))
{
	require_once(INC_PATH.'/functions_mkprettytime.php');
	$warnmessages[] = sprintf($lang->header['warndonor'], $BASEURL, mkprettytime(strtotime($CURUSER['donoruntil']) - gmtime()));
}

if($CURUSER['downloaded'] > 0 && $CURUSER['leechwarn'] == 'yes' AND strtotime($CURUSER['leechwarnuntil']) > TIMENOW)
{
	include_once(INC_PATH.'/readconfig_cleanup.php');
	require_once(INC_PATH.'/functions_mkprettytime.php');
	$warnmessages[] = sprintf($lang->header['warned'], $leechwarn_remove_ratio, mkprettytime(strtotime($CURUSER['leechwarnuntil']) - TIMENOW));
}
if (isset($CURUSER) AND $CURUSER['announce_read'] == 'no')
	$infomessages[] = '<span id="new_ann" style="display: block;"><a href="'.$BASEURL.'/clear_ann.php" title="" rel="iframe.1" rev="width:650 height:350 scrolling:yes">'.$lang->header['newann'].'</a></span>';

if ($CURUSER['pmunread'] > 0 AND $msgalert)
	$infomessages[] = '<a href="'.$BASEURL.'/messages.php">'.sprintf($lang->header['newmessage'], ts_nf($CURUSER['pmunread'])).'</a>';

if (isset($nummessages) AND $nummessages > 0)
	$infomessages[] = '<a href="'.$BASEURL.'/admin/index.php?act=staffbox">'.sprintf($lang->header['staffmess'], $nummessages).'</a>';

if (isset($numreports) AND $numreports > 0)
	$infomessages[] = '<a href="'.$BASEURL.'/admin/index.php?act=reports">'.sprintf($lang->header['newreport'], $numreports).'</a>';

if (isset($warnmessages))
{
	echo show_notice(implode('<br />',$warnmessages), true);
	unset($warnmessages);
}

if (isset($infomessages))
{
	echo show_notice(implode('<br />',$infomessages));
	unset($infomessages);
}

if (!defined('DISABLE_ADS') AND ($ads = @file_get_contents(TSDIR.'/admin/ads.txt')))
{
	$str  = '<table class="main" border="1" cellspacing="0" cellpadding="0" width="100%"><tr><td class="text">';
	if (strstr($ads, '[TS_ADS]'))
	{
		$ts_ads_count = explode('[TS_ADS]', $ads);
		$random_ts_ads = rand(0, (count($ts_ads_count) -1));
		$str .= $ts_ads_count[$random_ts_ads];
	}
	else
		$str .= $ads;
	$str .= '</td></tr></table><br />';
	echo $str;
	unset($ads, $str);
}
?>
