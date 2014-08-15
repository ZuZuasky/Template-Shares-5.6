<?php

require_once('global.php');
gzip();
dbconn(true);
maxsysop();
define('B_VERSION', '4.3 by xam');

if ($MEMBERSONLY == 'yes')
{
    loggedinorreturn();    
    parked();
}

stdhead(" youtube ");




?>
<iframe src="imageflow-youtube/index.php?action=neu_torrents" frameborder="0" scrolling="no" style="width:100%;" height="550"></iframe>


<?

stdfoot();

?> 