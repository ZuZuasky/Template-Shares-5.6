
<?php
/*
 --------------------------------------------------------------------------
|   TS Special Edition v.5.3
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: May 5, 2008, 2:44 am
|   Signature Key: TSSE9012008
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
 ---------------------------------------------------------------------------
*/
// Dont change for future reference.
define('TS_P_VERSION', '1.2 by xam');
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
    die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}
// BEGIN Plugin: last30

# begin last30

$query="SELECT id, name, t_image FROM torrents WHERE t_image <> '' ORDER BY added DESC limit 30";
$result=mysql_query($query);
$num = mysql_num_rows($result);

$last30 = '
<br>
<table><tr><td colspan=1 align=center><marquee scrollAmount=3 onMouseover=this.scrollAmount=0 onMouseout=this.scrollAmount=3 scrolldelay=0 direction=left>
';
$i=0;
while ($row = mysql_fetch_assoc($result))  {  $id = $row['id'];
$name = $row['name'];
$poster = $row['t_image'];
$name = str_replace('_', ' ' , $name);
$name = str_replace('.', ' ' , $name);
$name = substr($name, 0, 50);
if($i > 0 && $i % 15 == 0)
$last30 .= '

';
$last30 .= '
<a href="'.$BASEURL.'/details.php?id='.$id.'" title="'.$name.'"><img src="'.$poster.'" width="100" height="120" title="'.$name.'" border=0 /></a>
';
$i++;
}
$last30 .= '
</marquee></td></tr></table><br>
';
# end last30

// END Plugin: last30
?>