<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.5.7
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: April 7, 2009, 10:07 pm
|   Signature Key:
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
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

readconfig('SHOUTCAST');
    $scport = (!empty ($SHOUTCAST['s_serverport'])? $SHOUTCAST['s_serverport']: '');
    $scip = (!empty ($SHOUTCAST['s_serverip'])? $SHOUTCAST['s_serverip']: '');
    $scdef = (!empty ($SHOUTCAST['s_servername'])? $SHOUTCAST['s_servername']: '');
    $scpass = (!empty ($SHOUTCAST['s_serverpassword'])? $SHOUTCAST['s_serverpassword']: '');

$shoutcast_on_off ='';
$listenlink = ''.$scdef.'';
$scfp = fsockopen("$scip", $scport, &$errno, &$errstr, 30);
 if(!$scfp) {
  $scsuccs=1;
}
if($scsuccs!=1){
 fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
 while(!feof($scfp)) {
  $page .= fgets($scfp, 1000);
}
 $loop = array("STREAMSTATUS");
 $y=0;
 while($loop[$y]!=''){
  $pageed = ereg_replace(".*<$loop[$y]>", "", $page);
  $scphp = strtolower($loop[$y]);
  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
  $y++;
 }
fclose($scfp);
}

if ($streamstatus == "1") {
$online1 = '<center><img src="'.$BASEURL.'/pic/radio-online.png" border="0" height="150"></center><br>
		<center><a href="'.$BASEURL.':8000/listen.pls"><img src="'.$BASEURL.'/pic/tunein.gif" height="15"></a></center>';
}
	else
{
$online2 = '<center><img src="'.$BASEURL.'/pic/radio-offline.png" border="0" height="150"></center>';
}

// BEGIN Plugin: shoutcast_on_off
$shoutcast_on_off .='
<!-- begin shoutcast_on_off -->
<div align="justify">
<table cellspacing="0" border="0" cellpadding="1" width="100%">
	<tr>
		<td style="padding-left: 2px;" valign="top" width="150">'.$online1.''.$online2.'</td>
	</tr>
</table>
</div>
<!-- end shoutcast_on_off -->';
// END Plugin: shoutcast_on_off
?>