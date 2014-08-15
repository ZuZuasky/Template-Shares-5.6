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

// BEGIN Plugin: Shoutbox
$lang->load('quick_editor');
require_once(INC_PATH."/functions_quick_editor.php");
require_once($rootpath.'/'.$cache.'/smilies.php'); // Load smilies.
$_shoutbox_smilies = $smilies;
$_shoutbox_defaultsmilies = $_shoutbox_smilies;
$_shoutbox_smilies = '
<div style="display: none;" id="show_TSsmilies">
<table width="100%" border="0">
	<tr>';
$count = 0;
foreach ($_shoutbox_defaultsmilies as $_shoutbox_code => $_shoutbox_url)
{
	$_shoutbox_code = addslashes($_shoutbox_code);
	$_shoutbox_url = addslashes($_shoutbox_url);
	$_shoutbox_url = htmlspecialchars_uni($_shoutbox_url);
	if ($count < 59)
	{
		$_shoutbox_smilies .= "<img src=\"{$BASEURL}/{$pic_base_url}smilies/{$_shoutbox_url}\"  class=\"Shighlightit\" alt=\"{$_shoutbox_code}\" onclick=\"SmileIT('{$_shoutbox_code}','shoutbox','shouter_comment');\" style=\"cursor: pointer;\">\n";
		$count++;
	}
}
$_shoutbox_smilies .= '</tr></table></div>';
$shoutbox = '
<!-- begin shoutbox -->
<style type="text/css">
	.Shighlightit
	{
		border: 1px solid #ccc;
	}
	.Shighlightit:hover
	{
		border: 1px solid navy;
	}
	.Shighlightit:hover
	{
		color: red; /* Dummy definition to overcome IE bug */
	}
	.date
	{
		font-size: 7pt;
		font-family: tahoma;
	}
	#TSShoutbox a:link, #TSShoutbox a:visited {	
		padding:2px;
		text-decoration:none;
	}
	#TSShoutbox a:hover, #TSShoutbox a:active {
		color:#000;
		position:relative;
	}
	#TSShoutbox a:link span, #TSShoutbox a:visited span {
		background:#FFF;
		display:none;
		z-index:10;
	}
	#TSShoutbox a:hover span, #TSShoutbox a:active span {
	position:absolute;
	top:15px;
	left:0px;
	display:block;
	z-index:10;	
	padding:3px;	
	border:1px solid #444;
	width:105px;
	cursor:pointer;	
}
</style>
<script language="javascript" type="text/javascript">
	//<![CDATA[
	var popupshoutbox = "no";		
	function show_hidden(WhatToShow)
	{
		if (document.getElementByID)
		{
			stdBrowser = true;
		}
		else
		{
			stdBrowser = false;
		}

		if (stdBrowser || navigator.appName != "Microsoft Internet Explorer")
		{
			if (document.getElementById(WhatToShow).style.display == \'none\')
			{
				document.getElementById(WhatToShow).style.display = \'block\';
			}
			else
			{
				document.getElementById(WhatToShow).style.display = \'none\';
			}
		}
		else
		{
			if (document.all[WhatToShow].style.display == \'none\')
			{
				document.all[WhatToShow].style.display = \'block\';
			}
			else
			{
				document.all[WhatToShow].style.display = \'none\';
			}
		}
	}
	//]]>
</script>
<a id="shoutbox"></a>
<form id="shout" name="shoutbox" onSubmit="saveData(); return false;">	
<table border="0" cellspacing="0" cellpadding="5" width="100%">			
	
<td align="left">
			<div id="loading-layer" name="loading-layer" style="float:right; display:none;"><img src="pic/codebuttons/ajax-loader.gif" border="0" class="inlineimg" /></div><center>
			'.$_shoutbox_smilies.'
			'.ts_load_colors_shoutbox().'			
			<br>			
			<input maxlength="250" name="shouter_comment" type="text" id="shoutbox" size="50" /> 
			<img width=65  src="pic/codebuttons/boutonEnvoyer.gif" onClick="saveData(); return false;"></center><BR><center>
			

<img src="'.$BASEURL.'/'.$pic_base_url.'whip.gif"  onclick="show_hidden(\'show_TSsmilies\');" class="button" />
			'.ts_show_shoutbox_bbcode_links().'
			<img src="'.$BASEURL.'/'.$pic_base_url.'palette.gif" width="25" height="25" border="0" onclick="show_hidden(\'show_TScolors\');" class="button" /></center>
		

                     



</td>

<tr>
		<td>
			'.($is_mod ? '<span id="adminarea" align="center"><a href="#" onclick="window.open(\''.$BASEURL.'/shoutbox/shoutbox.php?popupshoutbox=yes\',\'shoutbox\',\'toolbar=no, scrollbars=yes, resizable=no, width=880, height=400, top=250, left=250\'); return false;"><b>'.$lang->index['showlast'].'</b></a> - <a href="#" onclick="window.open(\''.$BASEURL.'/shoutbox/shoutbox.php?show_shoutbox_commands=yes\',\'shoutbox\',\'toolbar=no, scrollbars=yes, resizable=no, width=900, height=300, top=250, left=250\'); return false;"><b>Show Shoutbox Commands</b></a><hr /></span>' : '').'
			<span id="errorarea" align="left" class="smalltext" style="display: block;"></span>
			<span id="shoutbox_frame"></span>
		</td>
	</tr>
	
		
	</tr>
</table>
</form>	
<script type="text/javascript" src="./shoutbox/shoutbox.js?v='.O_SCRIPT_VERSION.'"></script>
<!-- end shoutbox -->';
// END Plugin: Shoutbox
?>
