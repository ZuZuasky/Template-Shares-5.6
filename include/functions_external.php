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
# Function ts_external_share_ratio v.0.1
function ts_external_share_ratio($shareratio)
{
	global $BASEURL;
	$imagepath = "{$BASEURL}/include/ts_external_scrape/images/";
	$images = array(0 => 'No health!',1 => 'Health: Horrible',2 => 'Health: Bad',3 => 'Health: Poor',4 => 'Health: Below average',5 => 'Health: Average',6 => 'Health: Above average',7 => 'Health: Good',8 => 'Health: Very Good',9 => 'Health: Excellent',10 => 'Health: Perfect');
	if ($shareratio == 0 || ($shareratio >= 1 && $shareratio < 10)) return '<img src="'.$imagepath.'0.png" border="0" alt="'.$images['0'].'" title="'.$images['0'].'">';
	if ($shareratio == 100 || $shareratio > 100) return '<img src="'.$imagepath.'10.png" border="0" alt="'.$images['10'].'" title="'.$images['10'].'">';
	if ($shareratio >= 10 && $shareratio < 20) return '<img src="'.$imagepath.'1.png" border="0" alt="'.$images['1'].'" title="'.$images['1'].'">';
	if ($shareratio >= 20 && $shareratio < 30) return '<img src="'.$imagepath.'2.png" border="0" alt="'.$images['2'].'" title="'.$images['2'].'">';
	if ($shareratio >= 30 && $shareratio < 40) return '<img src="'.$imagepath.'3.png" border="0" alt="'.$images['3'].'" title="'.$images['3'].'">';
	if ($shareratio >= 40 && $shareratio < 50) return '<img src="'.$imagepath.'4.png" border="0" alt="'.$images['4'].'" title="'.$images['4'].'">';
	if ($shareratio >= 50 && $shareratio < 60) return '<img src="'.$imagepath.'5.png" border="0" alt="'.$images['5'].'" title="'.$images['5'].'">';
	if ($shareratio >= 60 && $shareratio < 70) return '<img src="'.$imagepath.'6.png" border="0" alt="'.$images['6'].'" title="'.$images['6'].'">';
	if ($shareratio >= 70 && $shareratio < 80) return '<img src="'.$imagepath.'7.png" border="0" alt="'.$images['7'].'" title="'.$images['7'].'">';
	if ($shareratio >= 80 && $shareratio < 90) return '<img src="'.$imagepath.'8.png" border="0" alt="'.$images['8'].'" title="'.$images['8'].'">';
	if ($shareratio >= 90 && $shareratio < 100) return '<img src="'.$imagepath.'9.png" border="0" alt="'.$images['9'].'" title="'.$images['9'].'">';
	return '<img src="'.$imagepath.'1.png" border="0" alt="'.$images['1'].'" title="'.$images['1'].'">';
}
?>
