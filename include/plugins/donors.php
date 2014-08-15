<?php
/*
 --------------------------------------------------------------------------
|   TS Special Edition v.5.3
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
 ---------------------------------------------------------------------------
*/
// Dont change for future reference.
define('TS_P_VERSION', '1.2 by xam');
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
    die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}
// BEGIN Plugin: donors

# begin donors
$query = sql_query("SELECT u.username, u.id, u.donor, u.donated, u.total_donated, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = 'yes' AND u.donor = 'yes' ORDER by u.total_donated DESC LIMIT 0,20");
	if (mysql_num_rows($query) > 0)
	{
		$donors = '<center>
<marquee bgcolor="#000000" direction="left" behavior="scroll" scroll="continuous" scrollamount="6" vspace="5" hspace="5" align="center" width="700" height="26" style="font-size:20px;color:#1589FF;font-family:verdana;">We would like to thank the following users for supporting '.$SITENAME.' ';

		while($donor = mysql_fetch_assoc($query))
		{
			$donors .=
			get_user_color($donor['username'], $donor['namestyle']);
			if ($donor['donor'] == 'yes')
				$donors .= '<img src="'.$pic_base_url.'star.gif" border="0" width="11" height="11" />, ';
			else
				$donors .= ', ';
		}
		$donors .= '
		</marquee>
		</center>';
	}
# end donors

// END Plugin: donors
?>
