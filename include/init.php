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
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
if(!defined('IN_TRACKER')) die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
# IMPORTANT CONSTANTS - DO NOT CHANGE!
define ('VERSION','TS Special Edition v.5.6');
define ('TS_MESSAGE','Powered by TS Special Edition');
define ('TSF_PREFIX', 'tsf_');
# Session Timeout in Seconds.
define ('TS_TIMEOUT', 3600); #This is the time in seconds that a user must remain inactive before their login session expires. This setting also controls how long a user will remain on Who's Online after their last activity.
define ('PROFILE_MAX_VISITOR', 5); // Store max. visitor message per user. remove profile visits beyond the first PROFILE_MAX_VISITOR

# Default Usergroups for Tracker

/* Do not remove this line */
define ('UC_GUEST', 0);
define ('UC_USER', 1);
define ('UC_POWER_USER', 2);
define ('UC_VIP', 3);
define ('UC_UPLOADER', 4);
define ('UC_MODERATOR', 5);
define ('UC_ADMINISTRATOR', 6);
define ('UC_SYSOP', 7);
define ('UC_STAFFLEADER', 8);
define ('UC_BANNED', 9);
define ('UC_SUPERMOD', 10);
define ('UC_FORUMMOD', 11);
/* Do not remove this line */

# Set locale information
# http://www.unet.univie.ac.at/aix/libs/basetrf2/setlocale.htm - http://tr2.php.net/manual/en/function.setlocale.php
//setlocale(LC_ALL, 'ru_RU','ru','RUS','russian'); // Russian for all platforms.
//setlocale(LC_ALL,'tr_TR','tr','turkish'); // turkish for all platforms.
//setlocale(LC_ALL,'It_IT','it','ita','Italian'); // italian for all platforms.
?>
