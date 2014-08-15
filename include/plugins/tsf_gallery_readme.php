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

define('IN_CHARACTER', true);

$lang->load('tsse_gallery');

begin_main_frame();

begin_frame();

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

print("	<table class='main' border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='embedded'>
		<table width='100%' border='1' cellspacing='0' cellpadding='10'>
		<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' border='0' cellpadding='4' cellspacing='1' width='100%'>\n");
	echo'	<strong><center><font color="#ffffff">'.$SITENAME.' '.$lang->tsse_gallery['gallery25'].'</font></center></strong>';

print("	<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top'>
		<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top' border='0' cellpadding='4' cellspacing='1' width='99%'>\n");

	echo '<table class="main" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td class="embedded">';
	echo '<table width="99%" border="1" cellspacing="0" cellpadding="10">';
	echo '<table style=\'padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top\' border="0" cellpadding="4" cellspacing="1" width="99%">';

	echo '<tr><td colspan="10" align="center">
		<FORM>
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery'].'"		ONCLICK="window.location.href=\'tsf_gallery.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery2'].'" 	ONCLICK="window.location.href=\'tsf_gallery_upload.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery4'].'"	ONCLICK="history.go(-1);return true;">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery6'].'" 	ONCLICK="history.go(0)">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery8'].'" 	ONCLICK="window.location.href=\'tsf_gallery_readme.php\'">
			<INPUT TYPE="button" value="'.$lang->tsse_gallery['gallery10'].'"  	ONCLICK="window.close()">
		</FORM>
		</td></tr>';
echo '<tr><td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif)\' colspan="40"><center><strong>'.$lang->tsse_gallery['gallery35'].'</strong></center></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery'].'"		ONCLICK="window.location.href=\'tsf_gallery.php\'"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery1'].'</b></font></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery2'].'" 	ONCLICK="window.location.href=\'tsf_gallery_gallery.php\'"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery3'].'</b></font></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery4'].'&nbsp;&nbsp;&nbsp;&nbsp;" onClick="history.go(-1);return true;"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery5'].'</b></font></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery6'].'&nbsp;&nbsp;" onClick="history.go(0)"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery7'].'</b></font></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery8'].'" 	ONCLICK="window.location.href=\'tsf_gallery_readme.php\'"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery9'].'</b></font></td></tr>';

echo '<tr><td width="100"><INPUT TYPE="button" value="'.$lang->tsse_gallery['gallery10'].'&nbsp;&nbsp;&nbsp;&nbsp;" onclick="window.close()"></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery11'].'</b></font></td></tr>';

echo '<table style=\'padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top\' border="0" cellpadding="4" cellspacing="1" width="100%">';
echo '<tr><td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\' colspan="10"><center><strong><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery15'].'</b></font></strong></center></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery37'].' </font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery38'].'</b></font></td></tr>';

echo '<tr><td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\' colspan="10"><center><strong><font color="#ffffff">'.$lang->tsse_gallery['gallery16'].'</font></strong></center></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery27'].'</font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery40'].'</b></font></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery28'].'</font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery41'].'</b></font></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery29'].'</font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery42'].'</b></font></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery30'].'</font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery43'].'</b></font></td></tr>';

echo '<tr><td width="100" align="center"><font color="#ffffff">'.$lang->tsse_gallery['gallery31'].'</font></td>';
echo '<td align="center"><font color="#ffffff"><b>'.$lang->tsse_gallery['gallery44'].'</b></font></td></tr>';

echo '</table><br /><p>';

echo '<table class="main" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td class="embedded">';
echo '<td style=\'padding: 5px; background: black\' align="center">'.$lang->tsse_gallery['gallery20'].'</td></tr></table>';

end_frame();

end_main_frame();

?>