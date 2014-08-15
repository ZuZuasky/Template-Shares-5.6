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
	echo '<strong><center><font color="#ffffff" face="Arial,Helvetica" size="4">'.$SITENAME.' '.$lang->tsse_games['java_games26'].'</font></center></strong>';

print("	<tr><td style='padding: 4px; background: url(../images/mainbox_bg.jpg) repeat-x left top'>
		<table style='padding: 4px; background: url(../images/mainbox_bg.jpg) left top' border='0' cellpadding='4' cellspacing='1' width='100%'>\n");

	echo '<table class="main" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td class="embedded">';
	echo '<table width="100%" border="1" cellspacing="0" cellpadding="10">';
	echo '<table style=\'padding: 4px; background: url(../images/mainbox_bg.jpg) left top\' border="0" cellpadding="4" cellspacing="1" width="100%">';

echo '<tr><td colspan="10" align="center">
		<FORM>
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games'].'"		ONCLICK="window.location.href=\'../jigsaw.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games2'].'"		ONCLICK="window.location.href=\'../puzzle.php\'">
		<INPUT TYPE="button" VALUE="'.$lang->tsse_games['java_games3'].'"		ONCLICK="window.location.href=\'../realcheckers/realcheckers.php\'">
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
		  <font color="#cc6600" face="Arial,Helvetica" size="4"><b>'.$lang->tsse_games['java_games27'].'</b>
		  </font></center></td></tr>';

?>
<p><center><p>
<table width="100%" border="1" cellspacing="0" cellpadding="10" align="center">
<tr><td colspan="10" align="center">


<table class="none" border="0" cellpadding="0" cellspacing="1" width="100%">
<tbody>
<tr>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games39'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games41'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games43'].'';?></font></b></td>
</tr>
<tr>
<td align="center"><? echo'<img src="graphics/klondike.gif" alt="'.$lang->tsse_games['java_games38'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/freecell.gif" alt="'.$lang->tsse_games['java_games40'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/canfield.gif" alt="'.$lang->tsse_games['java_games42'].'" width="100%" height="110" </img>';?></td>
</tr>
<tr>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'klondike.html\'></a>';?></td>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'freecell.html\'></a>';?></td>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'canfield.html\'></a>';?></td>
</tr>
<tr>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games45'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games47'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games49'].'';?></font></b></td>
</tr>
<tr>
<td align="center"><? echo'<img src="graphics/golf.gif" alt="'.$lang->tsse_games['java_games44'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/pyramid.gif" alt="'.$lang->tsse_games['java_games46'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/spider.gif" alt="'.$lang->tsse_games['java_games48'].'" width="100%" height="110" </img>';?></td>
</tr>
<tr>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'golf.html\'></a>';?></td>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'pyramid.html\'></a>';?></td>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'spider.html\'></a>';?></td>
</tr>
<tr>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games51'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games53'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games55'].'';?></font></b></td>
</tr>
<tr>
<td align="center"><? echo'<img src="graphics/clock.gif" alt="'.$lang->tsse_games['java_games50'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/calculation.gif" alt="'.$lang->tsse_games['java_games52'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/shamrocks.gif" alt="'.$lang->tsse_games['java_games54'].'" width="100%" height="110" </img>';?></td>
</tr>
<tr>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'clock.html\'></a>';?></td>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'calculation.html\'></a>';?></td>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'shamrocks.html\'></a>';?></td>
</tr>
<tr>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games57'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games59'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games61'].'';?></font></b></td>
</tr>
<tr>
<td align="center"><? echo'<img src="graphics/scorpion.gif" alt="'.$lang->tsse_games['java_games56'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/kingalbert.gif" alt="'.$lang->tsse_games['java_games58'].'" width="100%" height="110" </img>';?></td>
<td align="center"><? echo'<img src="graphics/yukon.gif" alt="'.$lang->tsse_games['java_games60'].'" width="100%" height="110" </img>';?></td>
</tr>
<tr>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'scorpion.html\'></a>';?></td>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'kingalbert.html\'></a>';?></td>
<td align="center"><img src="graphics/button1.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'yukon.html\'></a>';?></td>
</tr>
<tr>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games63'].'';?></font></b></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"><b><? echo''.$lang->tsse_games['java_games65'].'';?></font></b></td>
</tr>
<tr>
<td align="center"><? echo'<img src="graphics/castle.gif" alt="'.$lang->tsse_games['java_games62'].'" width="100%" height="110" </img>';?></td>
<td align="center"></td>
<td align="center"><? echo'<img src="graphics/flowergarden.gif" alt="'.$lang->tsse_games['java_games64'].'" width="100%" height="110" </img>';?></td>
</tr>
<tr>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'castle.html\'></a>';?></td>
<td align="center" style='padding: 4px; background: url(../images/cellpic3.gif)'><font color="#ffffff" face="Arial,Helvetica" size="2"></td>
<td align="center"><img src="graphics/button2.gif" width="25" height="25" align="center"><? echo '<input type="button" value="'.$lang->tsse_games['java_games67'].'"	onclick=window.location.href=\'flowergarden.html\'></a>';?></td>
</tr>
</tbody></table>
	<? echo '<tr><td style=\'padding: 4px; background: url(../images/cellpic3.gif)\' colspan="40">
		  <center><body bgcolor="#ffffff" link="#990000" vlink="#999999">
		  <font color="#cc6600" face="Arial,Helvetica" size="4"><b>'.$lang->tsse_games['java_games66'].'</b>
		  </font></center></td></tr>';?>
<table class="none" border="0" cellpadding="0" cellspacing="1" width="100%">
<tbody>
<tr>
<td align="center">
<? echo '<input type="button" value="'.$lang->tsse_games['java_games39'].'"	onclick=window.location.href=\'klondike-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games41'].'"	onclick=window.location.href=\'freecell-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games43'].'"	onclick=window.location.href=\'canfield-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games45'].'"	onclick=window.location.href=\'golf-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games47'].'"	onclick=window.location.href=\'pyramid-help.html\'></a>';?><br>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games49'].'"	onclick=window.location.href=\'spider-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games51'].'"	onclick=window.location.href=\'clock-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games53'].'"	onclick=window.location.href=\'calculation-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games55'].'"	onclick=window.location.href=\'shamrocks-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games57'].'"	onclick=window.location.href=\'scorpion-help.html\'></a>';?><br>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games59'].'"	onclick=window.location.href=\'kingalbert-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games61'].'"	onclick=window.location.href=\'yukon-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games63'].'"	onclick=window.location.href=\'castle-help.html\'></a>';?>
<? echo '<input type="button" value="'.$lang->tsse_games['java_games65'].'"	onclick=window.location.href=\'flowergarden-help.html\'></a>';?>
</td>
</tr>
</tbody></table>
</td>
	</tr>
		</td>
	</tr>
</table>

<?

end_frame();

end_main_frame();

?>

