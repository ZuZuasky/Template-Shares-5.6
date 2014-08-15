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
define('D_VERSION', '1.4.1');
define('DISABLE_ADS', true); // user may not want to see advertisements on donation page :)
require_once('global.php');
gzip();
dbconn();
$return_to_address=false;
if (isset($_GET['skip_member_check']) AND (!empty($_GET['skip_member_check']) AND !empty($_SESSION['skip_member_check']) AND $_GET['skip_member_check'] === $_SESSION['skip_member_check']))
{
	define('skip_member_check', true);
	$form_values = '?skip_member_check='.$_SESSION['skip_member_check'];
	$return_to_address = array(
		'true' => $BASEURL.'/vip_account.php?paypal_done=true&skip_member_check='.$_SESSION['skip_member_check'],
		'false'	 =>$BASEURL.'/vip_account.php?paypal_done=false&skip_member_check='.$_SESSION['skip_member_check']
		);
}
if ($MEMBERSONLY == 'yes' AND !defined('skip_member_check'))
{
	loggedinorreturn();
	maxsysop();
}

include_once(INC_PATH.'/readconfig_paypal.php');
$lang->load('donate');

$do = isset($_GET['do']) ? $_GET['do'] : '';

if ($do == 'moneybookers_thanks')
{
	stdhead();
	stdmsg($lang->donate['thanks1'],$lang->donate['thanks2'],false,'success');
	stdfoot();
	exit;
}

function get_user_class_name($class = '')
{
	global $cache;
	if ($class == 'all') return "ALL Usergroups";
	require(TSDIR.'/'.$cache.'/usergroups.php');
	foreach ($usergroupscache as $arr)
	{
		if ($arr['gid'] == $class) return $arr['title'];
	}
	unset($usergroupscache);
}
function show_donor_list()
{
	global $lang, $BASEURL, $pcc;
	global $showdonorlist;
	global $usergroups;
	if ($showdonorlist == 'no')
		return '';
	else if (($showdonorlist == '10a' OR $showdonorlist == '20a') && !is_mod($usergroups))
		return '';

	$limit = ($showdonorlist == '10' || $showdonorlist == '10a' ? '10' : '20');

	$query = sql_query("SELECT u.username, u.id, u.donor, u.donated, u.total_donated, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = 'yes' AND u.donor = 'yes' ORDER by u.total_donated DESC LIMIT 0,{$limit}");
	if (mysql_num_rows($query) > 0)
	{
		$donors = '
		<table width="100%" border="0" class="none" style="clear: both;" cellpadding="4" cellspacing="0">
			<tr>
				<td class="thead" align="center" colspan="10">
				'.ts_collapse('donorlist').'
				'.$lang->donate['donorlist'].'</td>
			</tr>
			'.ts_collapse('donorlist', 2).'
			<tr>
		';
		$count=0;
		while($donor = mysql_fetch_assoc($query))
		{
			if ($count % 5 == 0)
			{
				$donors .= '</tr><tr>';
			}
			$donors .= '
			<td align="center"><a href="'.ts_seo($donor['id'], $donor['username']).'">'.get_user_color($donor['username'], $donor['namestyle']).'</a></td><td align="center">'.$donor['total_donated'].' '.$pcc.'</td>';
			++$count;
		}
		$donors .= '
		</tr>
		</table><br />';
		return $donors;
	}
}

function wire_form()
{
	global $wire_form;
	global $lang;

	if (empty($wire_form) OR $wire_form == '<br />')
	{
		return;
	}
	$form = '
		<table width="100%" border="0" class="none" style="clear: both;" cellpadding="4" cellspacing="0">
			<tr>
				<td class="thead" align="center">'.$lang->donate['wiretransfer'].'</td>
			</tr>

			<tr>
				<td>'.$wire_form.'</td>
			</tr>
		</table><br />';

	return $form;
}

function paypal_form($amount)
{
	global $pmail, $pcc, $lang, $return_to_address, $paypal_demo_mode;
	global $CURUSER, $BASEURL, $SITEEMAIL;
	if (!$CURUSER)
	{
		$CURUSER['id'] = 0;
		$CURUSER['username'] = 'Guest';
		$CURUSER['email'] = '';
	}
	$form = '
	<html>
		<head><title>'.$lang->donate['processing'].'</title></head>
		<body onload="document.paypal.submit();">
			<center><h3>'.$lang->donate['processing'].'</h3></center>
			<form action="https://www.'.($paypal_demo_mode == 'yes' ? 'sandbox.' : '').'paypal.com/cgi-bin/webscr" method="post" name="paypal">
				<input type="hidden" name="cmd" value="_xclick" />
				<input type="hidden" name="no_note" value="1" />
				<input type="hidden" name="no_shipping" value="1" />
				<input type="hidden" name="business" value="'.$pmail.'" />
				<input type="hidden" name="item_number" value="'.$CURUSER['username'].'-'.$CURUSER['id'].'" />
				<input type="hidden" name="item_name" value="Donation from uid: '.$CURUSER['id'].'" />
				<input type="hidden" name="quantity" value="1" />
				<input type="hidden" name="amount" value="'.$amount.'" />
				<input type="hidden" name="currency_code" value="'.$pcc.'" />
				<input type="hidden" name="email" value="'.$CURUSER['email'].'" />
				<input type="hidden" name="address1" value="" />
				<input type="hidden" name="address2" value="" />
				<input type="hidden" name="city" value="" />
				<input type="hidden" name="country" value="" />
				<input type="hidden" name="zip" value="" />
				<input type="hidden" name="night_phone_a" value="" />
				<input type="hidden" name="night_phone_b" value="" />
				<input type="hidden" name="return" value="'.($return_to_address ? $return_to_address['true'] : $BASEURL.'/paypal.php').'" />
				<input type="hidden" name="cancel_return" value="'.($return_to_address ? $return_to_address['false'] : $BASEURL.$_SERVER['SCRIPT_NAME'].'?do=cancel').'" />
			</form>
		</body>
	</html>';
	if ($CURUSER['id'] === 0 OR $CURUSER['username'] === 'Guest')
	{
		unset($CURUSER);
	}
	return $form;
}

function moneybookers_form($amount)
{
	global $moneybookersemail, $pcc, $return_to_address;
	global $CURUSER, $BASEURL, $SITEEMAIL;
	global $lang;
	if (!$CURUSER)
	{
		$CURUSER['id'] = 0;
		$CURUSER['username'] = 'Guest';
		$CURUSER['email'] = '';
	}
	$form =
	'<html>
		<head><title>'.$lang->donate['processing'].'</title></head>
		<body onload="document.moneybookers.submit();">
			<center><h3>'.$lang->donate['processing'].'</h3></center>
			<form action="https://www.moneybookers.com/app/payment.pl" name="moneybookers">
				<input type="hidden" name="pay_to_email" value="'.$moneybookersemail.'">
				<input type="hidden" name="pay_from_email" value="'.$CURUSER['email'].'">
				<input type="hidden" name="language" value="EN">
				<input type="hidden" name="amount" value="'.$amount.'">
				<input type="hidden" name="currency" value="'.$pcc.'">
				<input type="hidden" name="detail1_description" value="Donation from uid: '.$CURUSER['id'].'">
				<input type="hidden" name="detail1_text" value="'.$CURUSER['username'].'-'.$CURUSER['id'].'">
				<input type="hidden" name="return_url" value="'.($return_to_address ? $return_to_address['true'] : $BASEURL.$_SERVER['SCRIPT_NAME'].'?do=moneybookers_thanks').'">
				<input type="hidden" name="cancel_url" value="'.($return_to_address ? $return_to_address['false'] : $BASEURL.$_SERVER['SCRIPT_NAME'].'?do=cancel').'">
				<input type="hidden" name="status_url" value="'.$SITEEMAIL.'">
				<input type="hidden" name="transaction_id" value="userid'.$CURUSER['id'].'">
				<input type="hidden" name="firstname" value="'.$CURUSER['username'].'">
				<input type="hidden" name="lastname" value="">
				<input type="hidden" name="address" value="">
				<input type="hidden" name="city" value="">
				<input type="hidden" name="state" value="">
				<input type="hidden" name="postal_code" value="">
				<input type="hidden" name="country" value="">
			</form>
		</body>
	</html>';
	if ($CURUSER['id'] === 0 OR $CURUSER['username'] === 'Guest')
	{
		unset($CURUSER);
	}
	return $form;
}

function main_form()
{
	global $lang, $SITENAME, $form_values, $paypal_demo_mode;
	global $donationamounts, $pcc, $moneybookersemail;
	$amounts = explode(':', $donationamounts);
	$value = '<select name="amount">';
	foreach ($amounts as $amount)
		$value .= '<option value="'.$amount.'">'.sprintf($lang->donate['donation'], $amount, $pcc).'</option>';
	$value .= '</select>';

	$form = '
		<form method="post" action="'.$_SERVER['SCRIPT_NAME'].$form_values.'">
		<table width="100%" border="0" class="none" style="clear: both;" cellpadding="4" cellspacing="0">
			<tr>
				<td class="thead" align="center" colspan="2">'.$lang->donate['supportusdonate'].'</td>
			</tr>
			<tr>
				<td align="right" width="40%">'.$lang->donate['select1'].'</td>
				<td align="left" width="60%"><select name="processor"><option value="paypal">PayPal'.($paypal_demo_mode == 'yes' ? ' (DEMO MODE)' : '').'</option>'.(!empty($moneybookersemail) ? '<option value="moneybookers">MoneyBookers</option>' : '').'</select></td>
			</tr>
			<tr>
				<td align="right" width="40%">'.$lang->donate['chooseamount'].'</td>
				<td align="left" width="60%">'.$value.'</td>
			</tr>
			<tr>
				<td align="center" colspan="2"><input type="submit" value="'.$lang->donate['donatebutton'].'"> <input type="reset" value="'.$lang->donate['donatebutton2'].'"></td>
			</tr>
		</table>
		</form><br />';
	return $form;
}

function show_promotions()
{
	global $rootpath, $lang, $pcc, $paypal_auto_mode;
	define('IN_PAYPAL', true);
	include_once(CONFIG_DIR.'/paypal_config.php');

	$str = '
	<table width="100%"border="1" cellspacing="0" cellpadding="5">
	<tr>
    <td colspan="4" class="thead">'.ts_collapse('promotions').'<div align="center">'.$lang->donate['promotions'].'</div></td>
	</tr>
	'.ts_collapse('promotions',2).'
	<tr>';

	$firstcount=0;
	reset($paypals);
	krsort($paypals);
	foreach ($paypals as $amount => $value)
	{
		if ($firstcount < 8)
		{
			if ($firstcount % 4 == 0)
				$str .= '</tr><tr>';
			$str .= '
			<td><table border="1" width="100%" height="150" cellspacing="0" cellpadding="12">
			<tr>
			<td valign="top"><div align="center" class="subheader"><b>'.sprintf($lang->donate['donatex'], $amount, $pcc).'</b></div><br />'.sprintf($lang->donate['donatexreceive'], $amount, $pcc);

			$str .= '<ul>';
			$str .= '<li>'.$value['until'].' '.sprintf($lang->donate['q1'], get_user_class_name($value['t_usergroup'])).'</li>';
			$str .= '<li>'.$value['upload'].' '.$lang->donate['q2'].'</li>';
			$str .= '<li>'.$value['invite'].' '.$lang->donate['q3'].'</li>';
			$str .= '<li>'.$value['bonus'].' '.$lang->donate['q4'].'</li>';
			$str .= '</ul>';

			$str .= '
			</td>
			</tr>
			</table></td>';
			++$firstcount;
		}
		else
		{
			break;
		}
	}
	$str .= '
	<td><table border="1" width="100%" height="150" cellspacing="0" cellpadding="12">
	<tr>
	<td valign="top">'.$lang->donate['default'];
	$str .= '
	</td>
	</tr>
	</table></td>';

	$str .= ($paypal_auto_mode == 'yes' ? '
	<tr>
    <td colspan="4" align="center"><b>'.$lang->donate['ipninfo'].'</b></td>
	</tr>' : '').'
	</table>';
	return $str;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$amount = 0+$_POST['amount'];
	$processor = htmlspecialchars_uni($_POST['processor']);

	if (!empty($amount) && $processor == 'paypal')
	{
		echo paypal_form($amount);
		exit;
	}
	elseif (!empty($amount) && $processor == 'moneybookers' && !empty($moneybookersemail))
	{
		$show_main_form = false;
		echo moneybookers_form($amount);
		exit;
	}
}

stdhead($lang->donate['header'],true,'collapse');
echo main_form().wire_form().show_donor_list().show_promotions();
stdfoot();
?>
