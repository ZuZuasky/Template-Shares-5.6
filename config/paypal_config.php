<?php
# PayPal Configuration v.0.2 by xam. Do not change below lines unless you know what you are doing.
if(!defined('IN_PAYPAL'))
	die('Hacking attempt!');

# Must match with $paypals arrays as shown below.
$accepted_amounts = array("5.00","10.00","20.00","30.00","40.00","50.00","100.00");

$paypals = array (
	# User has donated $5
	"5.00"		=>	 array(
				"t_usergroup"	=> UC_POWER_USER,		# New UserGroup. Leave empty to disable group promote.
				"until"				=> 2,								# Promote Length. (Weeks. 4 = 1 month) Note: 255 = unlimited!
				"upload"			=> 2,								# Upload Credits. (GB)
				"invite"			=> 2,								# Invite Amount.
				"bonus"			=> 50),							# Bonus Points.

	# User has donated $10
	"10.00"	=>	 array(
				"t_usergroup"	=> UC_POWER_USER,		# New UserGroup. Leave empty to disable group promote.
				"until"				=> 5,								# Promote Length. (Weeks. 6 = 1.5 months) Note: 255 = unlimited!
				"upload"			=> 5,								# Upload Credits. (GB)
				"invite"			=> 5,								# Invite Amount.
				"bonus"			=> 100),							# Bonus Points.

	# User has donated $20
	"20.00"	=>	 array(
				"t_usergroup"	=> UC_POWER_USER,		# New UserGroup. Leave empty to disable group promote.
				"until"				=> 12,							# Promote Length. (Weeks. 8 = 2 months) Note: 255 = unlimited!
				"upload"			=> 12,							# Upload Credits. (GB)
				"invite"			=> 12,							# Invite Amount.
				"bonus"			=> 200),							# Bonus Points.
				
	# User has donated $30
	"30.00"	=>	 array(
				"t_usergroup"	=> UC_VIP,						# New UserGroup. Leave empty to disable group promote.
				"until"				=> 3,								# Promote Length. (Weeks. 12 = 3 months) Note: 255 = unlimited!
				"upload"			=> 3,								# Upload Credits. (GB)
				"invite"			=> 8,								# Invite Amount.
				"bonus"			=> 250),							# Bonus Points.
				
	# User has donated $40
	"40.00"	=>	 array(
				"t_usergroup"	=> UC_VIP,						# New UserGroup. Leave empty to disable group promote.
				"until"				=> 5,								# Promote Length. (Weeks. 24 = 6 months) Note: 255 = unlimited!
				"upload"			=> 5,								# Upload Credits. (GB)
				"invite"			=> 10,							# Invite Amount.
				"bonus"			=> 300),							# Bonus Points.
				
	# User has donated $50
	"50.00"	=>	 array(
				"t_usergroup"	=> UC_VIP,						# New UserGroup. Leave empty to disable group promote.
				"until"				=> 7,								# Promote Length. (Weeks. 48 = 1 year) Note: 255 = unlimited!
				"upload"			=> 7,								# Upload Credits. (GB)
				"invite"			=> 15,							# Invite Amount.
				"bonus"			=> 350),							# Bonus Points.

	# User has donated $50
	"100.00"	=>	 array(
				"t_usergroup"	=> UC_VIP,						# New UserGroup. Leave empty to disable group promote.
				"until"				=> 9,								# Promote Length. (Weeks. 255 = unlimited!)
				"upload"			=> 9,								# Upload Credits. (GB)
				"invite"			=> 20,							# Invite Amount.
				"bonus"			=> 400),							# Bonus Points.
);
?>