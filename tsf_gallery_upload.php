<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.5.3
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: August 27, 2008, 10:43 pm
|   Signature Key:
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
require_once('global.php');
gzip();
dbconn(true);
loggedinorreturn(true);
maxsysop();

define ('UL_VERSION', '1.1 by xam');

$lang->load('tsse_gallery');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<title><?=$title;?></title>
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.3)" />
<meta http-equiv="refresh" content="<?=$wait;?>;URL=<?=$url;?>" />
<link rel="stylesheet" href="<?=$BASEURL;?>/gallery/style/style.css" type="text/css" media="screen" />
	</head>
</html>
<?

// Configure Start
$serverpath = "gallery/";
$urltoimages = "gallery";
$maxsize = 256 * 102476;
// Configure End

begin_main_frame();

begin_frame();

print("	<table class='main' border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='embedded'>
		<table width='100%' border='1' cellspacing='0' cellpadding='10'>
		<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top'>\n");
echo'	<strong><center><font color="#ffffff">'.$SITENAME.' '.$lang->tsse_gallery['gallery15'].'</font></center></strong>';

print("	<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top'>
		<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top' border='0' cellpadding='5' cellspacing='1' width='100%'>\n");

	echo '<tr><td align="center">
		<FORM>
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery'].'"		ONCLICK="window.location.href=\'tsf_gallery.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery2'].'" 	ONCLICK="window.location.href=\'tsf_gallery_upload.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery4'].'"	ONCLICK="history.go(-1);return true;">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery6'].'" 	ONCLICK="history.go(0)">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery8'].'" 	ONCLICK="window.location.href=\'tsf_gallery_readme.php\'">
			<INPUT TYPE="button" value="'.$lang->tsse_gallery['gallery10'].'"  	ONCLICK="window.close()">
		</FORM>
		</td></tr></table>';

$mode = $_GET['mode'];
if ($mode == "") { $mode = "form"; }

if ($mode == "form") {
?>
<div align='center'>

	<form method='post' action="?mode=upload" enctype="multipart/form-data">
		<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' border='1' cellspacing='0' cellpadding='5' width='100%'>
			<tr><td class='rowhead'><? echo'<center>'.$lang->tsse_gallery['gallery21'].'</center>';?></td>
		<td><input type='file' name='file' size='60'></td></tr>
	<tr><td colspan='2' align='center'><input type='submit' name='Submit' value='<? echo''.$lang->tsse_gallery['gallery18'].'';?>' class='btn'>
		</td></tr></table></form>


	<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top' border='0' cellpadding='5' cellspacing='1' width='100%'>
		<td class='rowhead' style='padding: 4px; background: #000000' align='center'>
			<? echo'<strong><center>'.$lang->tsse_gallery['gallery20'].'</center></strong>';?>
		</td></tr></table></td></tr></table></div>
	<?
}

if ($mode == "upload") {
$file = $_FILES['file']['name'];
$allowedfiles[] = "gif";
$allowedfiles[] = "jpg";
$allowedfiles[] = "jpeg";
$allowedfiles[] = "png";
$allowedfiles[] = "GIF";
$allowedfiles[] = "JPG";
$allowedfiles[] = "JPEG";
$allowedfiles[] = "PNG";


if($_FILES['file']['size'] > $maxsize)
	{

	echo'<p><strong><center>'.$lang->tsse_gallery['gallery24'].'</center></strong></p>';

	} else {

$path = "$serverpath/$file";

foreach($allowedfiles as $allowedfile) {

if ($done <> "yes") {

	if (file_exists($path)) {

echo '<p><strong><center>'.$lang->tsse_gallery['gallery22'].'</center></strong></p>';

exit;
}
	}

if (substr($file, -3) == $allowedfile) {
move_uploaded_file($_FILES['file']['tmp_name'], "$path");
$done = "yes";

echo '<center>';
echo '<p><form><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery12'].'"	ONCLICK=window.location.href=\'tsf_gallery_upload.php\'></a></form></p>';
echo '<p><form><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery13'].'"	ONCLICK=window.location.href=\'tsf_gallery.php\'></a></form></p>';
echo '<p>'.$lang->tsse_gallery['gallery14'].'</p>';
echo '<p>'.$lang->tsse_gallery['gallery17'].'</p>';
echo "<p><A href='$BASEURL/$urltoimages/$file' target='_blank'><strong><font color='#ffffff'>$BASEURL/$urltoimages/$file</color></strong></a></center></p>";
echo "<p><center><img src='$urltoimages/$file' border='0'>";
echo '</center>';
?>
	<script type="text/javascript">
		alert("Thank you for using <?=$SITENAME;?> Gallery to host your pictures.")
	</script>
<?
$name = sqlesc($file);
	$added = sqlesc(get_date_time());
		mysql_query("INSERT INTO tsf_gallery (owner, name, added) VALUES ($CURUSER[id], $name, $added)") or sqlerr(__FILE__, __LINE__);

	}

		}

	if ($done <> "yes") { echo'<p><strong><center>'.$lang->tsse_gallery['gallery23'].'</center></strong></p>'; }

	}
}

end_frame();

end_main_frame();

?>
