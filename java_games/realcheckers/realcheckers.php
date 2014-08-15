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
|   Signature Key: TSSE9882008
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
$rootpath = "./../../";
include($rootpath . 'global.php');
gzip();
dbconn(true);
loggedinorreturn(true);
maxsysop();

define('IN_CHARACTER', true);

$lang->load('tsse_games');

begin_main_frame();

begin_frame();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<title><?=$title;?></title>
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.3)" />
<meta http-equiv="refresh" content="<?=$wait;?>;URL=<?=$url;?>" />
<link rel="stylesheet" href="<?=$BASEURL;?>/java_games/style/style.css" type="text/css" media="screen" />
	</head>
</html>
<?
print("	<table class='main' border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='embedded'>
		<table width='100%' border='1' cellspacing='0' cellpadding='10'>
		<tr><td style='padding: 4px; background: url(../images/mainbox_bg.jpg) repeat-x left top' border='0' cellpadding='4' cellspacing='1' width='100%'>\n");
	echo '<strong><center><font color="#ffffff" face="Arial,Helvetica" size="4">'.$SITENAME.' '.$lang->tsse_games['java_games30'].'</font></center></strong>';

print("	<tr><td style='padding: 4px; background: url(../images/mainbox_bg.jpg) repeat-x left top'>
		<table style='padding: 4px; background: url(../images/mainbox_bg.jpg) left top' border='0' cellpadding='4' cellspacing='1' width='100%'>\n");

	echo '<table class="main" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td class="embedded">';
	echo '<table width="100%" border="1" cellspacing="0" cellpadding="10">';
	echo '<table style=\'padding: 4px; background: url(../images/mainbox_bg.jpg) left top\' border="0" cellpadding="4" cellspacing="1" width="100%">';

echo '<tr><td colspan="10" align="center">
		<FORM>
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games'].'"		ONCLICK="window.location.href=\'../jigsaw.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games1'].'"		ONCLICK="window.location.href=\'../jsolitaire/jsolitaire.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games2'].'"		ONCLICK="window.location.href=\'../puzzle.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games4'].'"		ONCLICK="window.location.href=\'../solitare.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games5'].'"		ONCLICK="window.location.href=\'../wordhunt/wordhunt.php\'"><p><p>
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games6'].'"		ONCLICK="window.location.href=\'../wordsearch/wordsearch.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games7'].'"		ONCLICK="history.go(-1);return true;">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games8'].'"		ONCLICK="history.go(0)">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games9'].'"		ONCLICK="window.location.href=\'../java_readme.php\'">
		<INPUT TYPE="button" value="'.$lang->tsse_games['java_games10'].'"		ONCLICK="window.close()">
		</FORM>
</td></tr>';

echo '<tr><td style=\'padding: 4px; background: url(../images/cellpic3.gif)\' colspan="40">
<center><body bgcolor="#ffffff" link="#990000" vlink="#999999">
<font color="#cc6600" face="Arial,Helvetica" size="4"><b>'.$lang->tsse_games['java_games31'].'</b>
</font></center></td></tr>';

?>
<p><center><p>
<table width="100%" border="1" cellspacing="0" cellpadding="10" align="center">
<tr><td colspan="10" align="center">

<HTML>
<HEAD><TITLE>Real Checkers</TITLE></HEAD>
<BODY>
<P ALIGN="center">
	<APPLET NAME="RealCheckers" CODE="RealCheckers.java" ARCHIVE="RealCheckers.jar" WIDTH="200" HEIGHT="230">
		<A HREF="http://www.realapplets.com/">RealApplets.com</A>
	</APPLET>
<p>
		</BODY>
			</HTML>
</td>
	</tr>
		</td>
	</tr>
</table>

<?

end_frame();

end_main_frame();

?>

