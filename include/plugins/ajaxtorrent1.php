<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.7.2
|   ========================================
|   by xam
|   (c) 2005 - 2010 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: $_ts_date_
|   Signature Key: $_ts_signature_key_
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
// Dont change for future reference.
if (!defined('TS_P_VERSION'))
{
	define('TS_P_VERSION', '1.1 by xam');
}
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
	die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}

// BEGIN Plugin: Ajax Torrentanzeige


$ajaxtorrent1 = '
<!-- begin Ajax Torrentanzeige -->
 <table style="width: 100%">
  <div id="ajax" align="center">
	 <tr><td><center>
	  <ul id="countrytabs" class="shadetabs">
     <center>
    <li class="selected"><a href="top_last.php" rel="ajaxcontentarea">Latest Torrents </a></li>
   <li><a href="top_50.php" rel="ajaxcontentarea">Latest Streaming</a></li>
<li><a href="top_60.php" rel="ajaxcontentarea">Latest Ddl</a></li>
<li><a href="top_sticky.php" rel="ajaxcontentarea">Recommended</a></li>
  <li><a href="top_onlyups.php" rel="ajaxcontentarea">free</a></li>
 <li><a href="top_xxx.php" rel="ajaxcontentarea"> XXX</a></li>
<!--ende-->

</center>
 </ul>
  <div id="countrydivcontainer" style="border:1px solid gray; width:97%; margin-bottom: 1em; padding: 8px">
   <br>
    </div>
     <script type="text/javascript">

     var countries=new ddajaxtabs("countrytabs", "countrydivcontainer")
     countries.setpersist(true)
     countries.setselectedClassTarget("link") //"link" or "linkparent"
     countries.init()
    </script>
   </td></tr>
	</div>
 </table>
<!-- end Ajax Torrentanzeige -->';

// END Plugin: Ajax Torrentanzeige
?>
