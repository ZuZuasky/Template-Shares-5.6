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
Translation by xam Version: 0.6
*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

//  signup.php and takesignup.php
$language['signup'] = array 
(
	'invalidinvitecode'				=>'The invite code you specified is invalid, because it was not found in our database.',
	'registration'					=>'Registration',
	'username'						=>'Desired Username:',
	'allowedchars'					=>'Allowed Characters: (a-z), (A-Z), (0-9)',
	'ps'							=>'Password Strength:',
	'pap'							=>'Password:',
	'papr'							=>'Re-Enter Password:',
	'sq'							=>'Secret Question:',
	'ha'							=>'Hint Answer:',
	'hr0'							=>'What is your name of first school?',
	'hr1'							=>'What is your pet\'s name?',
	'hr2'							=>'What is your mothers maiden name?',
	'hainfo'						=>'This answer will be used to reset your password in case you forget it.<br /> Make sure its something you will not forget!',
	'email'							=>'Email:',
	'emailinfo'						=>'The email address must be valid.',
	'tzsetting'						=>'Timezone Settings:',
	'tzsettinginfo'					=>'Enable Daylight Savings Time Correction?<br />
If you live in a timezone which differs to what this tracker is set at, you can select it from the list below.<br />GMT time now is ',
	'gender'						=>'Gender',
	'male'							=>'Male',
	'female'						=>'Female',
	'verification'					=>'Verification:',
	'verification2'					=>'<input type=checkbox name=uaverify value=yes> 	I have read and agree to the <a href=useragreement.php><u><strong>User Agreement</strong></u></a>.<br /><input type=checkbox name=rulesverify value=yes> 
I have read and agree to the <a href=rules.php><u><strong>RULES</strong>.</u></a><br /><input type=checkbox name=faqverify value=yes> 
I agree to read the <a href=faq.php><u><strong>FAQ</strong></u></a> before asking questions. <br /><input type=checkbox name=ageverify value=yes> I am at least <a href=rules.php><u><strong>13</strong></u></a> years old.</td></tr>',
	'signup'						=>'Sign-Up! (PRESS ONLY ONCE)',
	'country'						=>'Country:',	
	'noagree'						=>'Sorry, you\'re not qualified to become a member of this site.',
	'invalidemail'					=>'That doesn\'t look like a valid email address.',
	'invalidemail2'					=>'This email address banned! We do not accept Email from free email services such as Hotmail, Yahoo, Gmail etc.. (We ONLY accept registrations from non-free email addresses!)',	 // updated in v3.8
	'invalidemail3'					=>'The e-mail address is already in use.',
	'nogender'						=>'Please select gender.',
	'hae1'							=>'Sorry, Hintanswer is too short (min is 6 chars)',
	'hae2'							=>'Sorry, hintanswer cannot be same as user name.',
	'une1'							=>'Sorry, Username is too short (min is 3 chars)',
	'une2'							=>'Sorry, username is too long (max is 12 chars)',
	'une3'							=>'Invalid username.',
	'une4'							=>'Username already exists!',
	'passe1'						=>'The passwords didn\'t match! Must\'ve typoed. Try again.',
	'passe2'						=>'Sorry, password is too short (min is 6 chars)',
	'passe3'						=>'Sorry, password is too long (max is 40 chars)',
	'passe4'						=>'Sorry, password cannot be same as user name.',
	'welcomepmsubject'				=>'Welcome to {1}!',
	'welcomepmbody'					=>'Congratulations {1},

	You are now a member of {2}, we would like to take this opportunity to say hello and welcome to {2}!
	
	Please be sure to read the Rules: ({3}/rules.php) and the Faq: ({3}/faq.php#dl8) and be sure to stop by the Forums: ({3}/tsf_forums) and say Hello!
	
	Enjoy your Stay.
	The Staff of {2}',
	'verifiyemailsubject'			=>'{1} user registration confirmation',
	'verifiyemailbody'				=>'
Hello {1},
This email has been sent from {2}/index.php.

You have received this email because this email address
was used during registration for our tracker.
If you did not register at our tracker, please disregard this
email. You do not need to unsubscribe or take any further action.

------------------------------------------------
Activation Instructions
------------------------------------------------

Thank you for registering.
We require that you "validate" your registration to ensure that
the email address you entered was correct. This protects against
unwanted spam and malicious abuse.

To activate your account, simply click on the following link:

{2}/confirm.php?id={3}&secret={4}

(AOL Email users may need to copy and paste the link into your web browser).

------------------------------------------------
Not working?
------------------------------------------------

If you could not validate your registration by clicking on the link, please
visit this page:

{2}/confirm.php?act=manual

It will ask you for a user id number, and your secret key. These are shown
below:

User ID: {3}

Secret Key: {4}

Please copy and paste, or type those numbers into the corresponding fields in the form.

If you still cannot validate your account, it\'s possible that the account has been removed.
If this is the case, please contact an administrator to rectify the problem.

Thank you for registering and enjoy your stay!

Regards,

The {5} team.
{2}/index.php
', // Updated in v4.1
	'autoconfirm'					=>'Finish signup!',
	'autoconfirm2'					=>'Please click <a href="{1}/confirm.php?id={2}&secret={3}">here</a> to finish signup, thanks!',
	'referrer'				=>'Referrer (optional): ',
	'invalidreferrer'		=>'Invalid Referrer Name!',
	'eavailable'			=>'Email is available!', // Added v3.7
	'uavailable'			=>'Username is available!', // Added v3.7
	'checkavailability' =>'Check availability', //Added v3.7
	'invalidbday'			=>'Invalid birthday!', //Added v3.7
);
?>
