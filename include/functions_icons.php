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
if(!defined('IN_TRACKER'))
	die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
# Function get_user_icons v.0.8
function get_user_icons($arr, $big = false)
{
	global $rootpath,$pic_base_url, $lang, $BASEURL;
    if ($big)
    {
        $donorpic = "starbig.gif";
        $leechwarnpic = "warnedbig.gif";
        $warnedpic = "warnedbig3.gif";
        $disabledpic = "disabledbig.gif";
		$commentpos = "commentpos.gif";
		$sendpmpos = "sendpmpos.gif";
		$chatpost = "chatpost.gif";
		$downloadpos = "downloadpos.gif";
		$uploadpos = "uploadpos.gif";
        $style = "style=\"vertical-align: middle; margin-center: 4pt; white-space: nowrap;\" /";
    }
    else
    {
        $donorpic = "star.gif";
        $leechwarnpic = "warned.gif";
        $warnedpic = "warned3.gif";
        $disabledpic = "disabled.gif";
		$commentpos = "commentpos.gif";
		$sendpmpos = "sendpmpos.gif";
		$chatpost = "chatpost.gif";
		$downloadpos = "downloadpos.gif";
		$uploadpos = "uploadpos.gif";
        $style = "style=\"vertical-align: middle; margin-center: 4pt; white-space: nowrap;\" /";
    }
    $pics = $arr["donor"] == "yes" ? "<img src=\"".$BASEURL."/".$pic_base_url.$donorpic."\" alt=\"".$lang->global['imgdonated']."\" title=\"".$lang->global['imgdonated']."\" border=\"0\" $style>" : "";
    if ($arr["enabled"] == "yes")
	{
        $pics .= ($arr["leechwarn"] == "yes" ? "<img src=\"".$BASEURL."/".$pic_base_url.$leechwarnpic."\" title=\"".$lang->global['imgwarned']."\" alt=\"".$lang->global['imgwarned']."\" border=\"0\" $style>" : "") . ($arr["warned"] == "yes" ? "<img src=\"".$BASEURL."/".$pic_base_url.$warnedpic."\" alt=\"".$lang->global['imgwarned']."\" title=\"".$lang->global['imgwarned']."\" border=\"0\" $style>" : "");
		$pics .= ($arr["cancomment"] == "0" ? "<img src=\"".$BASEURL."/".$pic_base_url.$commentpos."\" title=\"".$lang->global['imgcommentpos']."\" alt=\"".$lang->global['imgcommentpos']."\" border=\"0\" $style>" : "");
		$pics .= ($arr["canmessage"] == "0" ? "<img src=\"".$BASEURL."/".$pic_base_url.$sendpmpos."\" title=\"".$lang->global['imgsendpmpos']."\" alt=\"".$lang->global['imgsendpmpos']."\" border=\"0\" $style>" : "");
		$pics .= ($arr["canshout"] == "0" ? "<img src=\"".$BASEURL."/".$pic_base_url.$chatpost."\" title=\"".$lang->global['imgchatpost']."\" alt=\"".$lang->global['imgchatpost']."\" border=\"0\" $style>" : "");
		$pics .= ($arr["candownload"] == "0" ? "<img src=\"".$BASEURL."/".$pic_base_url.$downloadpos."\" title=\"".$lang->global['imgdownloadpos']."\" alt=\"".$lang->global['imgdownloadpos']."\" border=\"0\" $style>" : "");
		$pics .= ($arr["canupload"] == "0" ? "<img src=\"".$BASEURL."/".$pic_base_url.$uploadpos."\" title=\"".$lang->global['imguploadpos']."\" alt=\"".$lang->global['imguploadpos']."\" border=\"0\" $style>" : "");
	}
    else $pics .= "<img src=\"".$BASEURL."/".$pic_base_url.$disabledpic."\" alt=\"".$lang->global['disabled']."\"  title=\"".$lang->global['disabled']."\" border=\"0\" $style>\n";
    return $pics;
}
?>
