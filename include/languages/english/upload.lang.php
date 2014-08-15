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
Translation by xam Version: 1.4

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// upload.php and takeupload.php
$language['upload'] = array 
(
	'anonymous'		=>'Anonymous',
	'nfoerror1'			=>'0-byte NFO!',
	'nfoerror2'			=>'NFO is too big! Max 65,535 bytes!',
	'nfoerror3'			=>'NFO upload failed!',
	'selectcategory'	=>'You must select a category to put the torrent in!',
	'dicterror1'			=>'Not a dictionary!',
	'dicterror2'			=>'Dictionary is missing key(s)!',
	'dicterror3'			=>'Invalid entry in dictionary!',
	'dicterror4'			=>'Invalid dictionary entry type!',
	'dicterror5'			=>'Missing both length and files!',
	'dicterror6'			=>'No files!',	
	'dicterror7'			=>'Filename error!',
	'fileerror1'			=>'Invalid filename!',
	'fileerror2'			=>'Invalid filename (not a .torrent)!',
	'uploaderror1'		=>'Unable to upload torrent!',
	'uploaderror2'		=>'Empty file!',
	'uploaderror3'		=>'What the hell did you upload? This is not a bencoded file!',
	'sqlerror1'			=>'Torrent already uploaded!',
	'sqlerror2'			=>'Mysql puked: ',
	'invalidannounceurl'=>'Invalid announce url! Must be: ',
	'invalidpieces'		=>'invalid pieces!',
	'dhterror'				=>'Multi-tracker/DHT torrents not allowed!',
	'writelog1'			=>'Torrent {1} ({2}) was uploaded by Anonymous.',
	'writelog2'			=>'Torrent {2} ({2}) was uploaded by {3}.',	
	'emailbody'			=>'Hi,

A new torrent has been uploaded.

Name: {1}
Size: {2}
Category: {3}
Uploaded by: {4}

Description
-------------------------------------------------------------------------------
{5}
-------------------------------------------------------------------------------

You can use the URL below to download the torrent (you may have to login).

{6}/details.php?id={7}

Yours,
The {8} Team.',
	'emailsubject'		=>'{1} New torrent - {2}',
	'mailerror'			=>'Your torrent has been been uploaded. DO NOT RELOAD THE PAGE!	
								There was however a problem delivering the e-mail notifcations. Please let an administrator know about this error!',
	'head'					=>'Upload a Torrent',
	'info'					=>'The tracker announce URL is: <b>{1}</b><br />',
	'alert1'				=>'<b>Please Note:</b> Private Tracker Patch is currently enabled so re-download is necessary for seeding after upload this torrent!<br />',
	'alert2'				=>'<b>ATTENTION</b>: Torrent directory isn\'t writable. Please contact the administrator about this problem!<br />',
	'alert3'				=>'<b>ATTENTION</b>: Max. Torrent Size not set. Please contact the administrator about this problem!<br />',
	'field1'				=>'Torrent File',			
	'field2'				=>'Torrent Name',
	'field3'				=>'(Taken from filename if not specified. <b>Please use descriptive names.</b>)',
	'field4'				=>'NFO File',
	'field5'				=>'(<b>Optional.</b> Can only be viewed by power users. </b> insert only file ending to <b>.nfo</b>)',
	'field6'				=>'Description:', // Changed in 3.7
	'field7'				=>'(choose one)',
	'field8'				=>'Type',
	'field12'				=>'Don\'t show my username in \'Uploaded By\' field in browse.',
	'field13'				=>'Sticky',
	'field14'				=>'Set sticky this torrent.',
	'field15'				=>'Offensive',
	'field16'				=>'Check this box if your torrent depicts nudity, or may otherwise be potentially offensive or unsuitable for minors.',
	'field17'				=>'I read the rules before this uploading.',
	'field18'				=>'Upload',
	'field19'				=>'NFO Ripper',
	'field20'				=>'Check this if you want to RIP above NFO file as description of this torrent.',	
	'uploaderform'		=>'Please click <a href=uploaderform.php>here</a> to fill uploader form.',
	'mindesclimit'		=>'Description is too short. Minimum 10 chars. are required!',
	'silver'				=>'Silver Download',
	'silver2'				=>'50% Download stats will be recorded!',
	'field21'				=>'<input type="radio" name="uploadtype" onclick="toggleuploadmode(1)" checked>Torrent Image (url)<br />
								<input type="radio" name="uploadtype" onclick="toggleuploadmode(0)">Torrent Image (file)',
	'field22'				=>'IMDB/Web Link',
	'field23'				=>'paste image url here',
	'invalid_url'			=>'Retrieval of remote file failed!',
	'invalid_url_empty'=>'URL can not be empty!',
	'invalid_url_link'	=>'URL must begin with: http://',
	'invalid_url_imdb'	=>'Invalid IMDB URL! Must begin with: http://www.imdb.com/title/',
	'curl_error'			=>'CURL Error!',
	'remote_failed'		=>'Retrieval of remote file failed!',
	'invalid_image'		=>'Invalid Image type! Allowed image types: {1}',
	'shoutbOT'          =>'The [url={1}]{2}[/url] has just been uploaded by {3}.', // Updated in v4.2
	'fileerror3'			=>'Invalid filename (not a .nfo)!',  // Added v3.6
	'showprogress'		=>'Torrent is being uploaded. This might take a few minutes.<br />Please DO NOT close this window!!', // Added v3.6
	'atypes'				=>'<b>Allowed file types: Jpg, Gif, Png</b>',  // Added v3.6
	'freesilvererror'		=>'You can not select both bonus types.', // Added v3.6
	'nforippempty'		=>'Don\'t forget to browse NFO file first.', // Added v3.6
	'field0'				=>'Tracker URL', //Added v3.7
	'trackerurlinfo'		=>'You can also upload torrents tracked by other public trackers (External Torrent)!', //Added v3.7
	'externalerror'		=>'You have no permission to upload an external torrent!', // Addded v3.9
	'sbum'				=>'Please use below form to search on torrents before Upload! Duplicate torrents will be marked as Nuked!', // Added v4.2
	'u_step'				=>'Upload Step: ', //Added v4.3
	's_results'			=>'Search Results', //Added v4.3
	's_results_title'		=>'Are you sure that you searched before you submitted your torrent? We found the following torrent(s) that seem to be similar to yours; please check them before submitting the torrent.<br /><br />
If you\'re sure that your torrent is a genuine torrent that has not been uploaded before, you can continute to upload this torent.', // Added 4.3
	'n_step'				=>'Next Step',//Added v4.3
	's_button1'			=>'NO, I want to continue my Upload!',//Added v4.3
	's_button2'			=>'YES, I want to cancel this Upload!',//Added v4.3
	'finfoh'			=>'Add the file information',//Added v4.3
	'finfo'				=>'File Info',//Added in v5.0
	'video'			=>'Video:',//Added in v5.0
	'audio'			=>'Audio:',//Added in v5.0
	'codec'			=>'Codec',//Added in v5.0
	'bitrate'			=>'Bitrate',//Added in v5.0
	'resulation'		=>'Resolution',//Added in v5.0
	'length'			=>'Length',//Added in v5.0
	'quality'			=>'Quality',//Added in v5.0
	'language'		=>'Language',//Added in v5.0
	'frequency'=>'Frequency',//Added in v5.0
	'enote'=>'If you do not know this information, use <a href="http://www.headbands.com/gspot/" target="_blank">GSpot Codec Information Appliance</a>',//Added in v5.0
	'fierror'=>'Your torrent has been uploaded, however you can\'t insert new file information data (step 3) because it was inserted before by someone else. Please edit torrent details to change file information.',//Added in v5.0
	'scene'=>'Scene Release',//Added in v5.2
	'scene2'=>'Check this box if your torrent is a scene release.',//Added in v5.2
);
?>
