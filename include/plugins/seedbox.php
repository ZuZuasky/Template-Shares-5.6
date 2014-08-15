<?php

define('TS_P_VERSION', '1.2 by xam');
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
    die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}

$query = sql_query ("SELECT COUNT(seedboxip) as total_seedboxip FROM seedbox");
    $total_seedboxip = mysql_result($query, 0, 'total_seedboxip');
    

    
$query = sql_query ("SELECT COUNT(p.ip) as total_box FROM peers p LEFT JOIN seedbox s ON (p.ip=s.seedboxip) WHERE p.ip=s.seedboxip");
    $total_box = mysql_result($query, 0, 'total_box');


  $seedbox = '
<table border="0" width="100%" height="100">
  <tr>
   <td class=subheader>Seedbox Stats</td><td class=subheader></td>
  </tr>
   

  <tr>
   <td><img border="0" src="'.$BASEURL.'/'.$pic_base_url.'webseede.png.gif" width="50" height="30"><a href="'.$BASEURL.'/admin/index.php?act=Addseedbox.ip">Seedboxes :</a> <a href="'.$BASEURL.'/admin/index.php?act=Addseedbox.ip">('.$total_seedboxip.')</a></td>
   <td><img border="0" src="'.$BASEURL.'/'.$pic_base_url.'webseede.png.gif" width="50" height="30"><a href="'.$BASEURL.'/admin/index.php?act=Addseedbox.ip">Torrents on seedboxes :</a> <a href="'.$BASEURL.'/admin/index.php?act=Addseedbox.ip">('.$total_box.')</a></td>
  </tr>



</table>';


?> 