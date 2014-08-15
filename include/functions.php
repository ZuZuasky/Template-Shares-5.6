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
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
if(!defined('IN_TRACKER')) die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
# Function stdhead v.2.6
function stdhead($title = '', $msgalert = true, $script = '', $script2 = '', $incCSS = '')
{
	global $CURUSER, $SITE_ONLINE, $SITENAME, $SITEEMAIL, $BASEURL, $offlinemsg, $disablerightclick, $autorefreshtime, $autorefresh, $leftmenu, $gzipcompress, $delay, $url, $rootpath, $pic_base_url, $charset, $metadesc, $metakeywords, $lang, $slogan, $usergroups, $leechwarn_remove_ratio, $cache, $dateformat, $timeformat, $cachetime, $checkconnectable, $timezoneoffset;
	if ($SITE_ONLINE != 'yes' && $CURUSER)
	{
		if ($usergroups['canaccessoffline'] != 'yes')
			die('
			<table align="center" width="100%">
				<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
				<p align="center"><img src="'.$BASEURL.'/misc/ts_message.php"></p>
			</table>');
		else
			$offlinemsg = true;
	}
	$lang->load('header');
	$script_name = $_SERVER['SCRIPT_NAME'];
	$includescripts = $includescripts2 = $includeCSS = '';
	$ts_tzoffset = $CURUSER['tzoffset'] ? $CURUSER['tzoffset'] : $timezoneoffset;
	if (!empty($incCSS))
	{
		$includeCSS = $incCSS;
	}
	if (!empty($script2))
	{
		if ($script2 == 'INDETAILS')
		{
			$includeCSS .= '
			<link rel="stylesheet" type="text/css" href="'.$BASEURL.'/ratings/css/tsbox.css?v='.O_SCRIPT_VERSION.'" />
			<link rel="stylesheet" href="'.$BASEURL.'/scripts/quick_editor.css?v='.O_SCRIPT_VERSION.'" type="text/css" media="screen" />';
			$includescripts .= '
			<script type="text/javascript" src="'.$BASEURL.'/scripts/prototype.js?v='.O_SCRIPT_VERSION.'"></script>
			<script type="text/javascript" src="'.$BASEURL.'/ratings/js/scriptaculous.js?load=effects&v='.O_SCRIPT_VERSION.'"></script>
			<script type="text/javascript" src="'.$BASEURL.'/ratings/js/tsbox.js?v='.O_SCRIPT_VERSION.'"></script>
			<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_editor.js?v='.O_SCRIPT_VERSION.'"></script>
			';
		}
		else
		{
			$includescripts .= ($script2 == 'quick_editor' ? '<script type="text/javascript" src="'.$BASEURL.'/scripts/quick_editor.js?v='.O_SCRIPT_VERSION.'"></script><link rel="stylesheet" href="'.$BASEURL.'/scripts/quick_editor.css?v='.O_SCRIPT_VERSION.'" type="text/css" media="screen" />' : $script2);
		}		
	}
	$title = $SITENAME.' :: '.($title != '' ? htmlspecialchars_uni($title) : TS_MESSAGE);
	$inboxpic = ($CURUSER['pmunread'] > 0 ? '<img height="14" style="border:none" alt="'.sprintf($lang->header['newmessage'], ts_nf($CURUSER['pmunread'])).'" title="'.sprintf($lang->header['newmessage'], ts_nf($CURUSER['pmunread'])).'" src="'.$BASEURL.'/'.$pic_base_url.'pn_inboxnew.gif" />' : ($CURUSER ? '<img height="14" style="border:none" alt="'.$lang->global['nonewmessage'].'" title="'.$lang->global['nonewmessage'].'" src="'.$BASEURL.'/'.$pic_base_url.'pn_inbox.gif" />' : ''));
	if ($script == 'supernote')
	{
		$includescripts .= '
		<script type="text/javascript">
			//<![CDATA[
			PositionX = 100;
			PositionY = 100;
			defaultWidth  = 500;
			defaultHeight = 500;
			var AutoClose = true;
			if (parseInt(navigator.appVersion.charAt(0))>=4){
			var isNN=(navigator.appName=="Netscape")?1:0;
			var isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}
			var optNN=\'scrollbars=no,width=\'+defaultWidth+\',height=\'+defaultHeight+\',left=\'+PositionX+\',top=\'+PositionY;
			var optIE=\'scrollbars=no,width=150,height=100,left=\'+PositionX+\',top=\'+PositionY;
			function popImage(imageURL,imageTitle){
			if (isNN){imgWin=window.open(\'about:blank\',\'\',optNN);}
			if (isIE){imgWin=window.open(\'about:blank\',\'\',optIE);}
			with (imgWin.document){
			writeln(\'<html><head><title>Loading...</title><style>body{margin:0px;}</style>\');writeln(\'<sc\'+\'ript>\');
			writeln(\'var isNN,isIE;\');writeln(\'if (parseInt(navigator.appVersion.charAt(0))>=4){\');
			writeln(\'isNN=(navigator.appName=="Netscape")?1:0;\');writeln(\'isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}\');
			writeln(\'function reSizeToImage(){\');writeln(\'if (isIE){\');writeln(\'window.resizeTo(300,300);\');
			writeln(\'width=300-(document.body.clientWidth-document.images[0].width);\');
			writeln(\'height=300-(document.body.clientHeight-document.images[0].height);\');
			writeln(\'window.resizeTo(width,height);}\');writeln(\'if (isNN){\');       
			writeln(\'window.innerWidth=document.images["George"].width;\');writeln(\'window.innerHeight=document.images["George"].height;}}\');
			writeln(\'function doTitle(){document.title="\'+imageTitle+\'";}\');writeln(\'</sc\'+\'ript>\');
			if (!AutoClose) writeln(\'</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()">\')
			else writeln(\'</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()" onblur="self.close()">\');
			writeln(\'<img name="George" src=\'+imageURL+\' style="display:block"></body></html>\');
			close();		
			}}
			//]]>
		</script>
		<script type="text/javascript" src="'.$BASEURL.'/scripts/menu.js?v='.O_SCRIPT_VERSION.'"></script>';
		$script = 'collapse';
	}
	if ($usergroups['cansettingspanel'] == 'yes' && file_exists(TSDIR.'/admin/quicklinks.txt'))
	{
		$Oquicklinks = file_get_contents(TSDIR.'/admin/quicklinks.txt');		
		preg_match_all("#(\{.*\})#U", $Oquicklinks, $Fquicklinksfound, PREG_SET_ORDER);		
		$EXquicklinks = explode(',', $Oquicklinks);
		$FFtotallinksfound = count($EXquicklinks);
		$Lquicklinks = preg_replace("#(\{.*\})#U", "", $EXquicklinks);	
		if ($FFtotallinksfound > 0)
		{
			$includequicklinks = '
			<script type="text/javascript">
				//<![CDATA[
				var menu3=new Array();
			';
			for ($i=0;$i<$FFtotallinksfound;$i++)
				$includequicklinks .= '
					menu3['.($i+3).']="<a href=\"'.str_replace('$BASEURL', $BASEURL, $Lquicklinks[$i]).'\">'.str_replace(array('{','}'), '', $Fquicklinksfound[$i][1]).'</a>";';
			$includequicklinks .= '
				//]]>
			</script>';
		}
		$includescripts .= $includequicklinks;
	}
	
	if ($script == 'collapse')
	{
		$tscollapse = array();
		if (!empty($_COOKIE['ts_collapse']))
		{
			$val = preg_split('#\n#', $_COOKIE['ts_collapse'], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($val AS $key)
			{
				$tscollapse["collapseobj_".htmlspecialchars_uni($key).""] = 'display:none;';
				$tscollapse["collapseimg_".htmlspecialchars_uni($key).""] = '_collapsed';
				$tscollapse["collapsecel_".htmlspecialchars_uni($key).""] = '_collapsed';
			}
			$GLOBALS["tscollapse"] = $tscollapse;
			unset($val);
		}
		$includescripts .= '<script type="text/javascript" src="'.$BASEURL.'/scripts/collapse.js?v='.O_SCRIPT_VERSION.'"></script>';	
	}
	
	if ($leftmenu == 'yes' AND !preg_match('#G0#is', $CURUSER['options']))
	{
		$includescripts .= '
		<script type="text/javascript">
			//<![CDATA[
			var sitename="'.$SITENAME.'";
			var invites="'.($CURUSER ? (int)$CURUSER['invites'] : 'Login First').'";
			var bonus="'.($CURUSER ? (int)$CURUSER['seedbonus'] : 'Login First').'";
			var username="'.($CURUSER ? $CURUSER['username'] : 'Guest').'";
			//]]>
		</script>
		<script src="'.$BASEURL.'/scripts/ssm.js?v='.O_SCRIPT_VERSION.'" type="text/javascript"></script>';
		if ($CURUSER)
			$includescripts .= '<script src="'.$BASEURL.'/scripts/ssm_registered.js?v='.O_SCRIPT_VERSION.'" type="text/javascript"></script>';
		else
			$includescripts .= '<script src="'.$BASEURL.'/scripts/ssm_guest.js?v='.O_SCRIPT_VERSION.'" type="text/javascript"></script>';
	}	
	if ($autorefresh == 'yes' && !preg_match('/(irc|settings|staffpanel|statistics|upload|sendmessage|signup|takesignup|donate)/i', $script_name))
		$includescripts .= '
	<script type="text/javascript">
		//<![CDATA[
		var autorefreshtime="'.$autorefreshtime.'";
		//]]>
	</script>
	<script type="text/javascript" src="'.$BASEURL.'/scripts/autorefresh.js?v='.O_SCRIPT_VERSION.'"></script>';
	if (preg_match('/(settings|showthread|browse|index|messages|details)/i', $script_name))
		$includescripts2 .= '<script type="text/javascript" src="'.$BASEURL.'/scripts/tooltip.js?v='.O_SCRIPT_VERSION.'"></script>';
	if ($disablerightclick == 'yes')
		$includescripts2 .= '<script type="text/javascript" src="'.$BASEURL.'/scripts/disablerightclick.js?v='.O_SCRIPT_VERSION.'"></script>';
	if ($CURUSER)
	{
		include_once(INC_PATH.'/functions_ratio.php');
		$ratio = get_user_ratio($CURUSER['uploaded'],$CURUSER['downloaded'],true);
		if ($CURUSER['donor'] == 'yes')
			$medaldon = '<img src="'.$BASEURL.'/'.$pic_base_url.'star.gif" alt="'.$lang->global['imgdonated'].'" title="'.$lang->global['imgdonated'].'">';
		if ($CURUSER['warned'] == 'yes')
			$warn = '<img src="'.$BASEURL.'/'.$pic_base_url.'warned.gif" alt="'.$lang->global['imgwarned'].'" title="'.$lang->global['imgwarned'].'">';

		if ($checkconnectable == 'yes')
		{
			$connectablequery = sql_query("SELECT userid FROM peers WHERE connectable = 'no' AND userid = ".sqlesc($CURUSER['id']));
			$c_count = mysql_num_rows($connectablequery);
			if ($c_count[0] > 0)
			{
				$connectablealert = sprintf($lang->global['connectablealert'] , $c_count[0], $BASEURL.'/tsf_forums/', $BASEURL.'/faq.php');
				$warnmessages[] = $connectablealert;
			}
		}
	}
	
	if (empty($_COOKIE['ts_psf']) || TIMENOW - $_COOKIE['ts_last_cache'] > (60 * $cachetime))
	{
		@setcookie('ts_last_cache', TIMENOW, TIMENOW + 90 * $cachetime, "/");
		define('IN_CACHE', true);
		include_once(INC_PATH.'/ts_cache.php');
		update_cache('funds',true);
		include_once(TSDIR.'/'.$cache.'/funds.php');
		include_once(INC_PATH.'/readconfig_paypal.php');
		$funds_difference = $GLOBALS['PAYPAL']['tn'] - $funds['funds_so_far'];
		@$Progress_so_far = $funds['funds_so_far'] / $GLOBALS['PAYPAL']['tn'] * 100;
		if($Progress_so_far >= 100) $Progress_so_far = '100';
		@setcookie('ts_psf', $Progress_so_far, TIMENOW + 90 * $cachetime, "/");
	}
	else $Progress_so_far = 0+$_COOKIE['ts_psf'];
	$__ismod=is_mod($usergroups);
	if ($__ismod && (TIMENOW - $_COOKIE['ts_last_sc'] > (60 * $cachetime)))
	{
		$numreports = TSRowCount('id', 'reports', 'dealtwith=0');
		$nummessages = TSRowCount('id', 'staffmessages', 'answered=0');
		@setcookie('ts_last_sc', TIMENOW, TIMENOW + 90 * $cachetime, "/");
	}
	$defaulttemplate = ts_template();
	if (!$CURUSER)
	{
		include_once(INC_PATH.'/unregistered.php');
		$includescripts .= $UNREGISTERED;
	}
	include(INC_PATH.'/templates/'.$defaulttemplate.'/header.php');	
}
# Function stdfoot v.1.4
function stdfoot()
{	
	global $SITENAME,$BASEURL,$CURUSER,$rootpath,$lang,$usergroups;	
	$defaulttemplate = ts_template();
	$script_name = $_SERVER['SCRIPT_NAME'];	
	$alertpm = (!preg_match('/(message|supportdesk)/i', $script_name) && $CURUSER['pmunread'] > 0 && preg_match('#F1#is', $CURUSER['options']) ? '
				<script type="text/javascript">
					//<![CDATA[
					var newpm = confirm("'.$lang->global['newmessagebox'].'")
					if (newpm)
					{
						window.location = "'.$BASEURL.'/messages.php";
					}
					//]]>
				</script>' : '');
	
	include(INC_PATH.'/templates/'.$defaulttemplate.'/footer.php');	
}
# Function get_user_avatar v.0.2
function get_user_avatar($url,$return=false,$width='',$height='')
{
	global $BASEURL, $CURUSER, $pic_base_url;	
	if (!empty($url)) $avatar = "<img src=\"".fix_url($url)."\" alt=\"\" title=\"\" border=\"0\"".($width ? ' width="'.$width.'"' : '').($height ? ' height="'.$height.'"' : '')." />";
	else $avatar = "<img src=\"".$BASEURL."/".$pic_base_url."default_avatar.gif\" width=\"".($width ? $width : '100')."\" height=\"".($height ? $height : '100')."\" alt=\"\" title=\"\" border=\"0\" />";
	if (preg_match('#D1#is', $CURUSER['options']) || $return) return $avatar;
	else return;
}
# function jumpbutton v.0.2
function jumpbutton($where)
{
	$str  = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="none">
	<tbody><div class="hoptobuttons">';
	if (!is_array($where)) array($where);
	foreach ($where as $value => $jump)
	{
		if (!empty($value) && !empty($jump)) $str .= '<input value="'.$value.'" onclick="jumpto(\''.$jump.'\');" class="hoptobutton" type="button">';
	}
	$str .= '</div></tbody></table>';
	return $str;
}
# Function tr v.0.2
function tr($x,$y,$noesc=0,$relation='')
{
    if ($noesc)
        $a = $y;
    else
	{
        $a = htmlspecialchars_uni($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr".( $relation ? " relation = \"$relation\"" : "")."><td class=\"heading\" valign=\"top\" align=\"right\" width=\"20%\">$x</td><td valign=\"top\" align=\"left\" width=\"80%\">$a</td></tr>\n");
}
# Function redirect v.0.7
function redirect($url, $message='', $title='', $wait=3, $usephp=false, $withbaseurl=true)
{
	global $SITENAME,$BASEURL,$lang;
	if (empty($message))
		$message = $lang->global['redirect'];
	if(empty($title))
		$title = $SITENAME;		
	$url = fix_url($url);
	if ($withbaseurl)
		$url = $BASEURL.(substr($url, 0, 1) == '/' ? '' : '/').$url;
	if ($usephp)
	{		
		@header ('Location: '.$url);
		exit;
	}
	$defaulttemplate = ts_template();
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<title><?=$title;?></title>
<meta http-equiv="refresh" content="<?=$wait;?>;URL=<?=$url;?>">
<link rel="stylesheet" href="<?=$BASEURL;?>/include/templates/<?=$defaulttemplate;?>/style/style.css" type="text/css" media="screen">
</head>
<body>
<br />
<br />
<br />
<br />
<div style="margin: auto auto; width: 50%" align="center">
<table border="0" cellspacing="0" cellpadding="4" class="tborder">
<tr>
<td class="thead"><strong><a href="<?=$BASEURL;?>"><?=$title;?></a></strong></td>
</tr>
<tr>
<td class="trow1" align="center"><p><font color="#000000"><?=$message;?></font></p></td>
</tr>
<tr>
<td class="trow2" align="right"><a href="<?=$url;?>">
<span class="smalltext"><?=$lang->global['nowaitmessage'];?></span></a></td>
</tr>
</table>
</div>
</body>
</html>
<?php
	ob_end_flush();
	exit;
}
# Function stdmsg v.0.3
function stdmsg($heading = '', $text = '', $htmlstrip = true, $div = 'error')
{
    if ($htmlstrip)
	{
        $heading = htmlspecialchars_uni($heading);
        $text = htmlspecialchars_uni($text);
    }
    echo show_notice($text, ($div == 'error' ? true : false), $heading);
}
# Function stderr v.0.2
function stderr($heading = '', $text = '', $htmlstrip = true, $head = true, $foot = true, $die = true, $div = 'error')
{
	if ($head) stdhead();	
	stdmsg($heading, $text, $htmlstrip, $div);	
	if ($foot) stdfoot();	
	if ($die) die;
}
# Function begin_main_frame v.0.1
function begin_main_frame()
{
	print("<table class=\"main\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"embedded\">\n");
}

# Function end_main_frame v.0.1
function end_main_frame()
{
	print("</td></tr></table>\n");
}
# Function begin_frame v.0.1
function begin_frame($caption = "", $center = false, $padding = 10)
{
	$tdextra = "";
	if ($caption) print("<h2>$caption</h2>\n");
	if ($center) $tdextra .= " align=\"center\"";
	print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"$padding\"><tr><td$tdextra>\n");
}
# Function end_frame v.0.1
function end_frame()
{
	print("</td></tr></table>\n");
}
# Function begin_table v.0.1
function begin_table($fullwidth = false, $padding = 5)
{
	$width = "";
	if ($fullwidth) $width .= " width=\"100%\"";
	print("<table class=\"main\"$width border=\"1\" cellspacing=\"0\" cellpadding=\"$padding\">\n");
}
# Function end_table v.0.1
function end_table()
{
	print("</td></tr></table>\n");
}
?>
