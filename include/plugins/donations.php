<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.5.1
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: May 5, 2008, 2:44 am
|   Signature Key: TSSE9012008
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
// Dont change for future reference.
define('TS_P_VERSION', '1.0 by alan');
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
	die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}
$lang->load('header');

// BEGIN Plugin: donations

# begin donations

	$donations = '
		<!-- begin donations -->
		<br />
		<table border="0" cellspacing="0" cellpadding="5" width="100%">
		<tr>';
	include_once(TSDIR.'/'.$cache.'/funds.php');
	include_once(INC_PATH.'/readconfig_paypal.php');
	$funds_difference = $GLOBALS['PAYPAL']['tn'] - $funds['funds_so_far'];
	if ($funds_difference < 0) 
		$funds_difference = 0;
	@$Progress_so_far = $funds['funds_so_far'] / $GLOBALS['PAYPAL']['tn'] * 100;
	$Progress_so_far = ($Progress_so_far >= 100 ? '100' : number_format($Progress_so_far, 1));
    $donations .= '<div id="donation"><font class="small"><a href="'.$BASEURL.'/donate.php">'.$lang->header['donate'].'</a>
		<div style="width: 80px; border: 1px solid black; text-align: left; background: #376088 repeat;"><div style="padding-left: 0px; color: white; font-weight: bold; width: '.$Progress_so_far.'%; border: 0px solid black; font-size: 8pt; background: #4A81B6 repeat;">&nbsp;'.number_format($Progress_so_far, 1).'%'.($Progress_so_far >= 100 ? '&nbsp;<font class="small">'.$lang->header['thanks'].'</font>' : '').'</div></div></div>';
	$donations .= '
		</tr><br />
		<tr>
		Target Amount: $'.number_format($GLOBALS['PAYPAL']['tn'], 2).'<br />
		We Received: $'.number_format($funds['funds_so_far'], 2).'<br />
		Still to Go: $'.number_format($funds_difference, 2).'<br />
		</font>
		</tr>
		</table>
		<!-- end donations -->';

# end donations

// END Plugin: donations
?>