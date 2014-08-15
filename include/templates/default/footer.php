<?php
/* TS SE Default Template (Footer)
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
**************************************************************************************
* You have the permission to modify this file because it's Nulled and Free Product!  *
**************************************************************************************
*/
if(!defined('IN_TRACKER')) die('Hacking attempt!');
echo '
		<br />
		</div>
	</div>
		<div id="footer">
			<div class="padding">
				Powered by <font color="white"><strong><a href="http://templateshares.net" target="_blank">'.VERSION.'</a></strong></font> &copy; '.@date('Y').' <font color="white"><a href="'.$BASEURL.'" target="_self"><strong>'.$SITENAME.'</strong></a></font> ';
				if (!defined('DEBUGMODE')) $_SESSION['totaltime'] = round((array_sum(explode(" ",microtime())) - $GLOBALS['ts_start_time'] ),4);				
				echo ' [Executed in <b> '.$_SESSION['totaltime'].' </b>seconds'.($usergroups['cansettingspanel'] == 'yes' ? ' with <b><a href="'.$BASEURL.'/admin/ts_query_explain.php">'.intval($_SESSION['totalqueries']).'</a></b> queries!]' : ']').'				
			</div>
		</div>
	</div>	
'.$alertpm.'
'.($CURUSER['options'] && preg_match('#N1#is', $CURUSER['options']) ?'
<!-- TS Auto DST Correction Code -->
<form action="'.$BASEURL.'/usercp.php?act=auto_dst" method="post" name="dstform">
	<input type="hidden" name="act" value="auto_dst" />
</form>
<script type="text/javascript">
<!--
	var tzOffset = '.$CURUSER['tzoffset'].' + '.(preg_match('#O1#is', $CURUSER['options']) ? '1' : '0').';
	var utcOffset = new Date().getTimezoneOffset() / 60;
	if (Math.abs(tzOffset + utcOffset) == 1)
	{	// Dst offset is 1 so its changed
		document.forms.dstform.submit();
	}
//-->
</script>
<!-- TS Auto DST Correction Code -->
' : '').($GLOBALS['ts_cron_image'] ? '
<!-- TS Auto Cronjobs code -->
	<img src="'.$BASEURL.'/ts_cron.php?rand='.TIMENOW.'" alt="" title="" width="1" height="1" border="0" />
<!-- TS Auto Cronjobs code -->
' : '').'
</body>
</html>
';
/*
+-------------------------------------------------------------------------------------
| You have no permission to modify this file unless you purchase a Brading Free Product!
+-------------------------------------------------------------------------------------
*/
?>
