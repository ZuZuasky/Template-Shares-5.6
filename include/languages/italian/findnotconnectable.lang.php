<?php
/*
+--------------------------------------------------------------------------
|  TS Special Edition v.5.6 
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: January 22, 2009, 11:27 pm
|   Signature Key: TSSE48342009
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
/* 
TS Special Edition English (Admin) Language File
Translation by xam Version: 0.1
*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// admin/findnotconnectable.php
$language['findnotconnectable'] = array 
(
	'head'			=>	'Unconnectable Peers',
	'pm'				=>	'Send PM',
	'pm2'			=>	'Send Mass Messege To All Non-Connectable Users',
	'pm3'			=>	'Send Message to Selected Users',
	'showlist'		=>	'Show List',
	'showlist2'	=>	'List Unconnectable Users',
	'showlist3'	=>	'Total {1} unique users that are not connectable',
	'log'				=>	'Unconnectable Peers Mass PM Log',
	'action'		=>	'Action',
	'delete'		=>	'Delete',
	'home'			=>	'Home',
	'sender'		=>	'Sender',
	'date'			=>	'Date',
	'username'	=>	'Username',
	'torrent'		=>	'Torrent Name',
	'client'			=>	'Client',
	'ip'				=>	'IP / PORT',
	'seeder'		=>	'Seeder',
	'nolog'			=>	'There is no PM Log to show!',
	'error1'		=>	'Please enter message to send!',
	'error2'		=>	'There is no Unconnectable Peer!',
	'error3'		=>	'Please select an user to send a message!',
	'reset'			=>	'Reset',
	'subject'		=>	'Warning!',
	'msg'			=>	'Hi,

The tracker has determined that you are firewalled or NATed and cannot accept incoming connections.
This means that other peers in the swarm will be unable to connect to you, only you to them.
Even worse, if two peers are both in this state they will not be able to connect at all.
This has obviously a detrimental effect on the overall speed. 

The way to solve the problem involves opening the ports used for incoming connections (the same range you defined in your client) on the firewall and/or configuring your NAT server to use a basic form of NAT for that range instead of NAPT (the actual process differs widely between different router models. Check your router documentation and/or support forum. You will also find lots of information on the subject at PortForward (http://portforward.com). 

Also if you need help please come into our IRC chat room or post in the forums your problems.
We are always glad to help out.

Thank You
',
);
?>