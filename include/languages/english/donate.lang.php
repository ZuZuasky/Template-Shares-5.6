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
/* 
TS Special Edition English Language File
Translation by xam Version: 0.5
*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// donate.php
$language['donate'] = array
(
	'header'				=>'Donation Page',
	'welcome'				=>'Welcome to {1} Donation Page.</h2>',
	'donation'				=>'{1} {2} Donation',
	'thanks'				=>'Thanks for your interest in donating. <br />
We make no profit from the web site & all donations go to keep the server running.<br />Anything you could donate would be gratefully received no matter how small.<br /><center><b><br />Please select a donation amount and click the PayPal button below if you wish to make a donation!</b><br /><br />',
	'item_name'				=>'Donation from {1} (UID: {2})',
	'chooseamount'			=>'Choose Donation Amount',
	'otheramount'			=>'Other Donation Amount',	
	'paypal_error1'			=>'We could not connect to paypal site... (Error NO: PA_1)',
	'paypal_error2'			=>'<p class=error><b>STATUS:</b> FAILED! You have already donated.</p>',
	'paypal_error3'			=>'Please contact us!. (Error NO: PA_2) Action LOGGED.',
	'paypal_error4'			=>'Please contact us!. (Error NO: TE_1)',
	'paypal_head'			=>'Thank you for your donation! Your transaction has been completed.!',
	'paypal_subheader'		=>'<h3><b><br /><font color=blue>AUTOMATICLY ACCOUNT UPDATE</b></h3></font>',
	'paypal_info'			=>'<h3><b><font color=darkgreen>Thank you for your donation! Your transaction has been completed.</b></font></h3><br /><h3><b>PAYMENT DETAILS <font color=red>(Please print this page for your records. A receipt for your purchase has also been emailed to you.)</font></b></h3>',
	'paypal_results'		=>'<ul>
	<li><b>NAME:</b> {1} {2}</li>
	<li><b>EMAIL:</b> {3}</li>
	<li><b>ITEM:</b> {4}</li>
	<li><b>AMOUNT:</b> {5}</li>
	<li><b>CURRENCY:</b> {6}</li>
	<li><b>STATUS:</b> {7}</li></ul>',
	'paypal_dur'			=>'{1} weeks',
	'paypal_msg_subject'	=>'Thank You for Your Donation!',
	'paypal_msg_body'		=>'Dear {1}
				
	Thanks for your support to {2}!
	Your donation helps us in the costs of running the site!

	Please note: Your donator status will last for {3} and can be found on your user details page and can only be seen by you.

	We would like to thank you again for your support,
	With best regards,
	{2} Staff.',
	'paypal_finish'			=>'<p class=success><b>STATUS:</b> SUCCESS! <ul><li> Invites: +{1}</li> <li> Upload Amount: +{2} GB</li> <li> Bonus Points: +{3}</li> <li> Donor status: {4}</li></ul></p>',
	'donorlist'				=>'Donor List - TOP 20', // Added in v3.8
	'ipninfo'					=>'All donations will be processed with Paypal IPN, this means that right after you will complete the donation process, it will instantly credit your account.',// Added in v3.8
	'processing'				=>'Please wait... Processing Payment...', // Added in v3.8
	'supportusdonate'	 =>'Support Us - Donate', // Added in v3.8
	'select1'	=>'Select Payment Processor', // Added in v3.8
	'donatebutton'	 =>'Donate', // Added in v3.8
	'donatebutton2'	=>'Reset', // Added in v3.8
	'donotlist'	=>'Donor List - TOP 20', // Added in v3.8
	'promotions'	 =>'Promotions', // Added in v3.8
	'donatexreceive' =>'Donate {1} {2} and receive:', // Added in v3.8
	'donatex'	 =>'Donate {1} {2}', // Added in v3.8
	'q1'=>'weeks {1} class', // Added in v3.8
	'q2' => 'GB Upload Credits', // Added in v3.8
	'q3' =>'Invite Credits', // Added in v3.8
	'q4'=>'Bonus Points', // Added in v3.8
	'default'	 =>'
	<div align="center" class="subheader"><b>Default for all donations:</b></div><br />Donate and receive:
	<ul>
	<li>No wait time restrictions, regardless your ratio.</li>
	<li>No slot restrictions, regardless your ratio.</li>		
	<li>Immunity to the auto-ban because of low ratio</li>
	<li>Donor star on nick</li>
	</ul>', // Added in v3.8
	'wiretransfer'	=>'Wire Transfer', //Added in v3.8
	'thanks1'	 =>'Thank you for Your Support',//Added in v3.8
	'thanks2'	 =>'Your payment has been completed and your account will be promoted as soon as possible.<br />Please send us your MoneyBookers email adress and trans.id so we can credit your account.<br /><br />Thank you again!',//Added in v3.8
	'received'=>'We Received',//Added in v5.3
	'targetamount'=>'Target Amount',//Added in v5.3
	'stilltogo'=>'Still to Go',//Added in v5.3
	'clicktodonate'=>'Click <a href="{1}/donate.php" onclick="window.opener.location.href=this;window.close();return false;"><b>here</b></a> to donate us',//Added in v5.3
	'systemmessage'=>'By donating to {1}, you will ensure the future of the website. We love being here for you. We ask you to donate so that the site may continue to provide these same benefits to you in the future, as well as many others. Lastly, we want to thank you for being here and thank you for being a member. We also want to thank all of those who have donated so far. Your generosity helps out in so many ways. If you\'d like to make a donation, please use the button below. If you\'d like to donate but don\'t want to use the Internet, you may contact us.'//Added in v5.3
);
