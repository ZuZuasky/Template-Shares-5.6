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
<script type="text/javascript">
var sitename="<?=htmlspecialchars_uni($SITENAME);?>"
var baseurl="<?=htmlspecialchars_uni($BASEURL);?>"
var dimagedir="<?=$BASEURL;?>/<?=$pic_base_url;?>"
var imagedir = "<?=$BASEURL;?>/include/templates/<?=$defaulttemplate;?>/images/";
var cssdir = "<?=$BASEURL;?>/include/templates/<?=$defaulttemplate;?>/style/";
var charset="<?=$charset;?>"
var defaulttemplate="<?=$defaulttemplate;?>"
var autorefreshtime="<?=$autorefreshtime;?>"
var requesturl="<?=fix_url($_SERVER['REQUEST_URI']);?>"
var invites="<?=($CURUSER ? (int)$CURUSER['invites'] : 'Login First');?>"
var bonus="<?=($CURUSER ? (int)$CURUSER['seedbonus'] : 'Login First');?>"
var username="<?=($CURUSER ? $CURUSER['username'] : 'Guest');?>"
var userid="<?=($CURUSER ? (int)$CURUSER['id'] : 'Guest_'.rand(1000, 9999));?>"
var userip="<?=htmlspecialchars_uni($_SERVER['REMOTE_ADDR']);?>"
</script>
<link type="text/css" rel="stylesheet" href="<?=$BASEURL;?>/scripts/floatbox/floatbox.css<?php echo '?v='.O_SCRIPT_VERSION; ?>">
<script type="text/javascript" src="<?=$BASEURL;?>/scripts/floatbox/floatbox.js<?php echo '?v='.O_SCRIPT_VERSION; ?>"></script>
            <script type="text/javascript">
                function borderit(which,color)
                {
                    if (document.all||document.getElementById)
                    {
                        which.style.borderColor=color
                    }
                }
            </script>
		</head>
	</html>
<script type="text/javascript" src="<?=$BASEURL;?>/scripts/tooltip.js<?php echo '?v='.O_SCRIPT_VERSION; ?>"></script>
<?

if (!$moderator)
	$delete = $HTTP_GET_VARS["delete"];
if (is_valid_id($delete))
	{
		$r = mysql_query("SELECT * FROM tsf_gallery WHERE id=$delete") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($r) == 1)
	{
		$a = mysql_fetch_assoc($r);
if (!$moderator)
	{
		mysql_query("DELETE FROM tsf_gallery WHERE id=$delete") or sqlerr(__FILE__, __LINE__);

if (!unlink("gallery/$a[name]"))

	redirect("tsf_gallery.php",sprintf($lang->tsse_gallery['gallery19'], false),NULL,5);

		}
	}
}


$res = mysql_query("SELECT count(*) FROM tsf_gallery") or die(mysql_error());
	$row = mysql_fetch_array($res);
		$count = $row[0];
	$perpage = 10;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?out=" . $_GET["out"] . "&" );

begin_main_frame();

begin_frame();

print("	<table class='main' border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='embedded'>
		<table width='100%' border='1' cellspacing='0' cellpadding='10'>
		<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top'>\n");
echo'	<strong><center><font color="#ffffff">'.$SITENAME.' '.$lang->tsse_gallery['gallery16'].'</font></center></strong>';

print("	<tr><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top'>
		<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top' border='0' cellpadding='4' cellspacing='1' width='100%'>\n");


	echo '<tr><td align="center">
			<FORM>
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery'].'"		ONCLICK="window.location.href=\'tsf_gallery.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery2'].'" 	ONCLICK="window.location.href=\'tsf_gallery_upload.php\'">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery4'].'"	ONCLICK="history.go(-1);return true;">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery6'].'" 	ONCLICK="history.go(0)">
			<INPUT TYPE="button" VALUE="'.$lang->tsse_gallery['gallery8'].'" 	ONCLICK="window.location.href=\'tsf_gallery_readme.php\'">
			<INPUT TYPE="button" value="'.$lang->tsse_gallery['gallery10'].'"  	ONCLICK="window.close()">
			</FORM>
		  </td></tr>';

echo $pagertop;

	$res = mysql_query("SELECT added, id, owner, name FROM tsf_gallery ORDER BY added DESC $limit") or sqlerr(__FILE__, __LINE__);


if (mysql_num_rows($res) == 0)
	echo'<p><strong><center>'.$lang->tsse_gallery['gallery26'].'</center></strong><p>';
else
	{
		$mod = is_mod($usergroups);

print("<table style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) left top' border='1' cellpadding='4' cellspacing='1' width='100%'>\n");
echo '<tr><td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\'  align="center" width="25%"><strong><center>'.$lang->tsse_gallery['gallery27'].'</center></strong></td>
<td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\'  align="center" width="25%"><strong><center>'.$lang->tsse_gallery['gallery28'].'</center></strong></td>
<td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\'  align="center" width="25%"><strong><center>'.$lang->tsse_gallery['gallery29'].'</center></strong></td>
<td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\'  align="center" width="25%"><strong><center>'.$lang->tsse_gallery['gallery30'].'</center></strong></td>
<td style=\'padding: 4px; background: url(gallery/images/cellpic3.gif) left top\'  align="center" width="25%"><strong><center>'.$lang->tsse_gallery['gallery31'].'</center></strong></td>';
print("" .($mod ? "<td style='padding: 4px; background: url(gallery/images/cellpic3.gif) left top' align='center' width='25%'><strong><center>".$lang->tsse_gallery['gallery32']."</center></strong></td>" : ""). "</tr>\n");

while ($arr = mysql_fetch_assoc($res))
	{
		$r2 = mysql_query("SELECT username FROM users WHERE id=$arr[owner]") or sqlerr();
			$a2 = mysql_fetch_assoc($r2);
				$date = substr($arr['added'], 0, strpos($arr['added'], " "));
			$time = substr($arr['added'], strpos($arr['added'], " ") + 1);
		$name = $arr["name"];
			$url = str_replace(" ", "%20", htmlspecialchars("gallery/$name"));

print("<tr bgcolor=$bgcolor><td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' align='center' width='25%'><font color='#ffffff'>$date</font></td>
<td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' align='center' width='25%'><font color='#ffffff'>$time</font></td>
<td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' align='center' width='25%'><userdetails.php?id=$arr[owner]><b><font color='#ffffff'>$a2[username]</font></b></td>

<td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' align='center'>
<a href='$url' border='0' onMouseover=\"ddrivetip('".$lang->tsse_gallery[gallery34]."', 300)\"; onMouseout=\"hideddrivetip()\">
<font color='#ffffff'>$name</font></a></td>

<td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' class='tcat' width='25%'><a href=$url rel='gallery.group'><img src='$url' width='75' height='75' alt='$name' title=$name class='borderimage' onmouseover=\"borderit(this,'black')\" onmouseout=\"borderit(this,'white')\" /></a></td>
" .($mod ? "<td style='padding: 4px; background: url(gallery/images/mainbox_bg.jpg) repeat-x left top' width='25%'><form><INPUT TYPE='button' VALUE=".$lang->tsse_gallery['gallery33']."	ONCLICK=\"window.location.href='?delete=$arr[id]'\"></a></form></td>" : ""). "</tr>\n");

	}

print("</tbody></table>");

	}

echo $pagerbottom;

end_frame();

end_main_frame();

?>
