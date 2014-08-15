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
TS Special Edition English Language File
Translation by xam Version: 0.6
*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// edit.php, takedit.php
$language['edit'] = array
(
	'edittorrent1'			=>'Edit torrent: {1}',
	'edittorrent2'			=>'Edit Torrent',
	'torrentname'			=>'Torrent Name',
	'nfofile'				=>'NFO File',
	'keepcurrent'			=>'Keep Current',
	'update'				=>'Update:',
	'description'			=>'Description',
	'type'					=>'Type',
	'visible'				=>'Visible',
	'visible2'				=>'Visible on main page<br />Note: That the torrent will automatically become visible when there\'s a seeder, and will become automatically invisible (dead) when there has been no seeder for a while. Use this switch to speed the process up manually. Also note that invisible (dead) torrents can still be viewed or searched for, it\'s just not the default.',
	'au'					=>'Anonymous Uploader',
	'au2'					=>'Check this box to hide the uploader of the torrent.',
	'fd'					=>'Free Download',
	'fd2'					=>'Free download (only upload stats are recorded).',
	'banned'				=>'Banned',
	'banned2'				=>'Ban this torrent!',
	'sticky'				=>'Sticky',
	'sticky2'				=>'Set sticky this torrent!',
	'deletetorrent'			=>'<b>Delete torrent.</b> Reason:',
	'other'					=>'Other',
	'req'					=>'<font color=red>(req)</font>',
	'dead'					=>'Dead',
	'dead2'					=>'0 seeders, 0 leechers = 0 peers total',
	'dupe'					=>'Dupe',
	'nuked'					=>'Nuked',
	'rules'					=>'Rules broken:',
	'nfotoobig'				=>'NFO is too big! Max 65,535 bytes!',
	'offensive'				=>'Offensive',
	'offensive2'			=>'Please check this box if your torrent depicts nudity, or may
otherwise be potentially offensive or unsuitable for minors.',
	'request'	 =>'Request', //Added v.3.9
	'request2'=>'Is this a requested torrent?', //Added v.3.9
	'nuked2'=>'Is nuked torrent?',//Added v.3.9
	'da'=>'Double Upload?', //Added v4.1
	'db'=>'Give x2 Upload Credit for this torrent.',//Added v4.1
	'ca'=>'Allow Comments?',//Added v4.1
	'cb'=>'Uncheck this to disable comments for this torrent!',//Added v4.1
	'tf'	=>'Reup File Torrent :',//Added v5.6
	'tf2'	=>'Lascia Vuoto per saltare questo',//Added v5.6
);
?>