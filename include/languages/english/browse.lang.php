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
Translation by xam Version: 1.1

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// browse.php *** RE-CODED SINCE v3.9 ***
$language['browse'] = array
(
	'bykeyword'	 =>'Keyword(s)',
	'alltypes'		=>'(all categories)',
	'tryagain'	=>'Try again with a refined search string.',
	't_name'		=>'Torrent name',
	't_description'=>'Torrent description',
	't_both'	=>'Name & Description',
	't_uploader' =>'Uploader',
	'in'	 =>'in',
	'tsearch'	 =>'Torrent Search',
	'tcategory' => 'Tracker Categories',
	'downloadinfo'=> 'Download torrent: {1}',
	'detailsinfo'	=> 'View torrent details: {1}',
	'categoryinfo'=> 'View category: {1}',	
	'info3'	 => '{1} x time(s)',	
	'newtorrent'	 =>'New torrent',
	'freedownload'	=>'Free torrent (only upload stats are recorded!).',
	'silverdownload' =>'Silver torrent (only 50% download stats are recorded!).',
	'requested'	=>'This torrent was requested by a member.',
	'nuked'	=>'This torrent has been nuked! Reason: {1}',	
	'download'	=>'Download torrent',
	'viewtorrent'	=>'View torrent details',	
	'viewcomments'	=>'View comments',	
	'viewsnatch'	=>'View snatch list',
	'tinfo'		=>'View torrent info',
	'edit'	=>'Edit torrent',
	'nuke'	 =>'Nuke torrent',
	'delete'	=>'Delete torrent',
	'nopreview'	=>'There is no preview image for this torrent!',
	'sticky'	=>'Recommend Torrents',
	'updating'	=>'Updating torrent stats...',
	'update'	=>'External Torrent! Click here to update torrent stats!',
	'updated'	=>'Torrent stats has been updated!',
	'show_daily_torrents' => 'Show daily torrents',
	'show_weekly_torrents' => 'Show weekly torrents',
	'show_montly_torrents' => 'Show montly torrents',
	'show_dead_torrents' => 'Show dead torrents',
	'show_recommend_torrents' => 'Show recommend torrents',
	'show_free_torrents' => 'Show free torrents',
	'show_silver_torrents' => 'Show silver torrents',
	'show_external_torrents' => 'Show external torrents',
	'sastype'	 =>'Select search type ',
	'btitle'	=>'Browse Torrents',
	't_image'	 =>'Click to view full size',
	'warnexternal'				=>'Warning!!!\n----------------\nYou are about to download an external torrent which means download and upload stats aren\'t recorded for this torrent!\n\nClick \"OK\" to continue downloading!',
	'sortby1'=>'Sorted by', // Added v4.0
	'sortby2'=>'Filelist', // Added v4.0
	'sortby3'=>'Comments', // Added v4.0
	'sortby4'=>'Seeders', // Added v4.0
	'sortby5'=>'Leechers', // Added v4.0
	'sortby6'=>'Size', // Added v4.0
	'sortby7'=>'Snatched', // Added v4.0
	'sortby8'=>'Uploader', // Added v4.0
	'sortby9'=>'Recommend', // Added v4.0
	'orderby1'=>'Sort order', // Added v4.0
	'orderby2'=>'Descending', // Added v4.0
	'orderby3'=>'Ascending', // Added v4.0
	'sobutton'=>'Show Torrents', // Added v4.0
	'serror'=>'An error has occured!\nOne or more of your search terms were shorter than the minimum length.\nThe minimum search term length is {1} characters.\n\nSearch terminated!', // Changed in v5.5
	'dupload'=>'Seed this torent to get double upload credits!',//Changed in v4.2
	'legend_browse' =>'
<img src="|link|freedownload.gif" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Free Torrents download when set gives the users upload credit only and no download credit is posted to the users stats.  This in turn is a great opportunity to build a users upload stats to improve a users ratio to good standings.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|silverdownload.gif" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Silver Torrents when set only record 50% of the users download credit on that file. This means the user still has a responsibility to seed back a file they download to help reach the sites required ratio or seed time. You will still receive full upload credit for what you help upload to others.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|isnuked.gif" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Nuked Torrent Files are files that have been defined as files that are out of sync with audio or video.  Nuked Files can also be defined as files that are missing segments of the file which can not be recovered. These can also be nuked by scene release groups for bad aspect ratios. A nuked file sometimes are replaced with a updated version soon after it is nuked.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|isrequest.gif" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Requested Torrents are files that users request and a member of the uploader team uploaded the file requested.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|x2.gif" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>x2 Double Upload Credit for seeding back files.  When set any user who downloaded this file and recieves a message asking them to reseed this file because another user is trying to download it, will give any user who reseeds this file double the upload credit for helping out reseed.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|external.gif" height="12" width="12" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>External Torrents are files that are uploaded to the site from a non-private site that does not require membership to download the file.  All External Torrents are considered Free Torrents and no stats are recorded for downloading it.  Users generally do not have to seed this file back.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|sticky.gif" height="12" width="12" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Recommend Torrents are files that the uploader or admin of site has recommended that this movie is one to have or view.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|down1.gif" height="12" width="12" border="0" class="inlineimg" onmouseover="ddrivetip(\'<font color=#347C17>Download File Icon.  This icon is available to the user so they can direct download the .torrent file. This icon does not mean the user can get away from giving thanks to the uploader for uploading the file.</font>\', 300)"; onmouseout="hideddrivetip()">&nbsp;&nbsp;
', // Added v4.2
	'b_info'	=>'<b>Legend: <small>Scroll Over Pics For Description</small></b>',// Added v4.2
	'f_options'=>'<b>Filter Options</b>',// Added v4.2
	'show_double_upload_torrents'=>'show double upload torrents',// Added v4.2
	'type'=>'Type',//Added in v5.0
	'speed'=>'Speed',//Added in v5.0
	'external'=>'(External)',//Added in v5.0
	'notraffic'=>'(No Traffic)',//Added in v5.0
	't_genre'=>'IMDB Genre',//Added in v5.0
	'quickedit'=>'Quick edit torrent subject', //Added in v5.1
	'f_leech_h' => 'Free Leech Days',//Added in v5.1
	'f_leech'	=>	 'All torrents are Free Leech between {1} - {2}. (Please do not hit \'n\' run, keep the torrents seeded!)', //Added in v5.1
	's_leech_h' => 'Silver Leech Days',//Added in v5.1
	's_leech'	=>	 'All torrents are Silver Leech between {1} - {2}. (Please do not hit \'n\' run, keep the torrents seeded!)', //Added in v5.1
	'd_leech_h' => 'Double Upload Days',//Added in v5.1
	'd_leech'	=>	 'All torrents are Double Upload (x2) between {1} - {2}. (Please do not hit \'n\' run, keep the torrents seeded!)', //Added in v5.1
	'scene3'=>'<b>Pre-Time:</b> {1}',//Added in v5.2
	'scene4'=>'Show scene releases',//Added in v5.2
	'show_latest'=>'Show latest torrents',//Added in v5.4
);
?>
