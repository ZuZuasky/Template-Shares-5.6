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

$_datearray = array();
$__query = sql_query("SELECT date FROM ts_events");
if (mysql_num_rows($__query) > 0 )
{
	while($__R = mysql_fetch_assoc($__query))
	{
		$_datearray[] = '"'.$__R['date'].'"';
	}
}

// BEGIN Plugin: Calendar
$calendar = '
<style type="text/css">
	.main
	{
		border:1px solid black;
	}
	.month
	{
		background: #e2e1ea;
		color: #000000;
		border: 1px solid #7b81cd;
	}
	.daysofweek
	{
		background: #b7ccfc repeat-x top left;
		color: #FFFFFF;
		font: bold 10px tahoma, verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
	}
	.days
	{
		background: #eff0f4;
		color: #4D528C;
	}
	.days #today
	{
		font-weight: bold;
		color: red;
		font-size: 12px;
	}
	.event
	{
		font-weight: bold;
		font-size: 11px;
		color: green;
	}
	.eventcontents
	{
		font-weight: normal;
		font-size: 10px;
		color: black;
	}

</style>

<script type="text/javascript">
	//<![CDATA[
	function in_array(needle, haystack, strict)
	{	 
		var found = false, key, strict = !!strict;	 
		for (key in haystack) {if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {found = true;	break;}}	 
		return found;
	}
	function buildCal(m, y, cM, cH, cDW, cD, brdr)
	{
		var mn=[\'January\',\'February\',\'March\',\'April\',\'May\',\'June\',\'July\',\'August\',\'September\',\'October\',\'November\',\'December\'];
		var dim=[31,0,31,30,31,30,31,31,30,31,30,31];
		var oD = new Date(y, m-1, 1);
		oD.od=oD.getDay()+1;
		var todaydate=new Date()
		var scanfortoday=(y==todaydate.getFullYear() && m==todaydate.getMonth()+1)? todaydate.getDate() : 0
		dim[1]=(((oD.getFullYear()%100!=0)&&(oD.getFullYear()%4==0))||(oD.getFullYear()%400==0))?29:28;
		var t=\'<div class="\'+cM+\'"><table class="\'+cM+\'" cols="7" cellpadding="1" border="\'+brdr+\'" cellspacing="0" width="100%"><tr align="center">\';
		t+=\'<td colspan="7" align="center" class="\'+cH+\'">\'+mn[m-1]+\' - \'+y+\'</td></tr><tr align="center">\';
		for(s=0;s<7;s++)t+=\'<td class="\'+cDW+\'">\'+"SMTWTFS".substr(s,1)+\'</td>\';
		t+=\'</tr><tr align="center">\';
		for(i=1;i<=42;i++)
		{
			var x=((i-oD.od>=0)&&(i-oD.od<dim[m-1]))? i-oD.od+1 : \'&nbsp;\';
			if (in_array(mn[m-1]+"-"+x+"-"+y, ['.@implode(',', $_datearray).'])) x=\'<a href="index.php?m=\'+mn[m-1]+\'&d=\'+x+\'&y=\'+y+\'#collapseobj_calendar">\'+(x==scanfortoday ? \'<span id="today">\'+x+\'</span>\' : x)+\'</a>\'
			if (x==scanfortoday)
			x=\'<span id="today">\'+x+\'</span></a>\'
			t+=\'<td class="\'+cD+\'">\'+x+\'</td>\';
			if(((i)%7==0)&&(i<36))t+=\'</tr><tr align="center">\';
		}
		return t+=\'</tr></table></div>\';
	}
	//]]>
</script> 
<script type="text/javascript">
	//<![CDATA[
	var todaydate=new Date()
	var curmonth=todaydate.getMonth()+1
	var curyear=todaydate.getFullYear()
	document.write(buildCal(curmonth ,curyear, "main", "subheader", "daysofweek", "days", 1));
	//]]>
</script>';
if (isset($_GET['m']) AND isset($_GET['d']) AND isset($_GET['y']))
{
	$_m = htmlspecialchars_uni($_GET['m']);
	$_d = intval($_GET['d']);
	$_y = intval($_GET['y']);
	$_date = "$_m-$_d-$_y";
	$_query = sql_query("SELECT title, event FROM ts_events WHERE date = ".sqlesc($_date));
	if (mysql_num_rows($_query) > 0)
	{
		while($_event = mysql_fetch_assoc($_query))
		{
			$calendar .= '
			<br />
			<span class="event">
				'.$_date.' <br /> 	'.htmlspecialchars_uni($_event['title']).'
			</span>
			<br />
			<span class="eventcontents" align="justify">		
				'.htmlspecialchars_uni($_event['event']).'
			</span>
			<br />
			';
		}
	}
}
// END Plugin: Calendar
?>
