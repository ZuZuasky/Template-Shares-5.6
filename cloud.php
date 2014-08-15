<?php
require "global.php";
include "/seng/configtorrent.php";
include "/seng/trackerlist.php";
include "/seng/details.php";
include "/seng/teleport.php";
dbconn();
loggedinorreturn();
stdhead("blagues");
begin_main_frame();
$search = htmlentities(strip_tags(trim($_GET[search])));
$orderby = htmlentities(strip_tags(trim($_GET[orderby])));
$page = htmlentities(strip_tags(trim($_GET['page'])));

if (!empty($search)) {
	
	$title = $search." - ".$CFG['title']." | ".$CFG['site_name'];
	$description = urldecode($search)." torrent downloads | Download Latest and Verified Torrents at ".$CFG['site_name'];
	$keywords = $search.", torrent,download, verified, links, axxo, klaxxon, movies, tv series, tv shows, ripped, hdtv"; 
		
	} else {
		
	$title = $CFG['title']." | ".$CFG['site_name'];;
	$description = "Download Latest and Verified Torrents at ".$CFG['site_name'];
	$keywords = "torrent, download, verified, links, axxo, klaxxon, movies, tv series, tv shows, ripped, hdtv"; 
		
		}	//END of META
	
if ($page < 0) {

	$pagex = 1;
	
	}else {
	
	$pagex = $page + 1;
	
		}

if (empty($page))
	{
	
	$pagex = 1;
	
	} else {
	
	$title = $search." (Page: ".$pagex.") - ".$CFG['title'];
	
		}
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title><?=$title?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" >

		<meta name="keywords" content="<?=$keywords?>" />
		<meta name="description" content="<?=$description?>" />


		<link rel="stylesheet" href="<?=$CFG['domain']?>/style.css" type="text/css" >
       
                <script type="text/javascript">
                      <!--
                      function popitup(url) {
                      	newwindow=window.open(url,'name','height=500,width=500');
                      	if (window.focus) {newwindow.focus()}
                      	return false;
                      }
                      
                      // -->
                </script>

	</head>
<body>

<!-- header -->
<?

if (isset($_GET[search]))
{

$search = urlencode($search);

  if (mysql_affected_rows()){
        mysql_query("UPDATE torrent_tags SET count=count+1 WHERE tag_name=\"".$search."\"");
  }
      if (!mysql_affected_rows()){
        mysql_query("INSERT INTO torrent_tags  SET tag_name=\"". $search ."\", count=1, search_date=NOW()");
}

?>

<div align="center" style="margin-top:30px;">

 <div id="srcbox">
 		<form action="<?=$CFG['domain']?>/index.php" method="get" onSubmit="this.submit();return false;">
			<input class="src" name="search" type="text" autocomplete="off" delay="1500" value="type and go..." onBlur="if(this.value=='') this.value='type and go...';" onFocus="if(this.value=='type and go...') this.value='';">
			
			<input type="image" src="<?=$CFG['domain']?>/img/submit.png" name="Submit" value="Submit">
		</form>
 </div> 
</div>

<?php
if (empty($orderby) || $orderby == "peers") {

	$url = "http://www.torrentz.com/search?q=".$search."&p=".$page;
	$orderbymenu = "
	<div class=\"orderby\"><span>Order by</span> <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=name\">name</a> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=date\">date</a> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=size\">size</a> | <span class=\"active\">peers</span></div>";
	
	}elseif ($orderby == "name"){

	$url = "http://www.torrentz.com/searchN?q=".$search."&p=".$page;
	
	$orderbymenu = "
	<div class=\"orderby\"><span>Order by</span>
	<span class=\"active\">name</span> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=date\">date</a> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=size\">size</a> | <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=peers\">peers</a></div>";
	
	}elseif ($orderby == "date"){

	$url = "http://www.torrentz.com/searchA?q=".$search."&p=".$page;
	
		
	$orderbymenu = "
	<div class=\"orderby\"><span>Order by</span>
	<a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=name\">name</a> | 
	<span class=\"active\">date</span> | <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=size\">size</a> | <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=peers\">peers</a></div>";
	
	}elseif ($orderby == "size"){

	$url = "http://www.torrentz.com/searchS?q=".$search."&p=".$page;
	$orderbymenu = "
	<div class=\"orderby\"><span>Order by</span>
	<a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=name\">name</a> | <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=date\">date</a> | <span class=\"active\">size</span> | <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=peers\">peers</a></div>";
	
	}else {

	$url = "http://www.torrentz.com/search?q=".$search."&p=".$page;
	$orderbymenu = "
	<div class=\"orderby\"><span>Order by</span> <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=name\">name</a> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=date\">date</a> |  <a href=\"".$CFG['domain']."/index.php?search=".$search."&orderby=size\">size</a> | <span class=\"active\">peers</span></div>";
	}
$gurl = @file_get_contents($url);
$resultsnum = explode("<h2>", $gurl);
$resultsnum = explode(" for", $resultsnum[1]);
$resultsnumx = str_replace("Results ", "", $resultsnum[0]);
$resultsnummax1 = explode("Results ", $resultsnum[0]);
$resultsnummax1 = explode(" for", $resultsnummax1[1]);
$resultsnummax = explode("of ", $resultsnum[0]);
$resultsnummax = explode(" for", $resultsnummax[1]);
$page_rows = 50;	
$resultsnummax[0] = str_replace(",", "", $resultsnummax[0]);	
$last = ceil($resultsnummax[0]/$page_rows);
$lastx = $last - 1;

$check = explode("<dl>", $gurl);
$check = explode("</dl>", $check[1]);

// can be translated
if (!empty($check[0]))
   {
echo "
<div class=\"files\"><h2>Search results for <b>".urldecode($search)."</b>&nbsp;&nbsp;&nbsp;($resultsnumx Results) $orderbymenu</h2> </div>
<table border=\"0\" class=\"results\" width=\"%100\">
"; 
}

$i=1;
$r=50;
while ($i <= $r){

$aud = explode("<dl>", $gurl);
$aud = explode("</dl>", $aud[$i]);

/// FIND CLASS A //

$classa = explode("<span class=\"a\">", $aud[0]);
$classa = explode("</span>", $classa[1]);

/// FIND CLASS A //

$classs = explode("<span class=\"s\">", $aud[0]);
$classs = explode("</span>", $classa[1]);

/// FIND CLASS U //

$classu = explode("<span class=\"u\">", $aud[0]);
$classu = explode("</span>", $classu[1]);

/// FIND CLASS D //

$classd = explode("<span class=\"d\">", $aud[0]);
$classd = explode("</span>", $classd[1]);

//TAGS //
$tagz = explode("&#187;", $aud[0]);
$tagz = explode("</dt>", $tagz[1]);

//LINKz

$linkz = explode("href=\"/", $aud[0]);
$linkz = explode("\">", $linkz[1]);


//NAMEZ

$namez = explode($linkz[0]."\">", $aud[0]);
$namez = explode("</a>", $namez[1]);

//STYLE

$stylez = explode("style=\"", $aud[0]);
$stylez = explode("><a href=", $stylez[1]);

//echo $namez[0];

   //echo $aud[0]." ".$link[0]."<br>\n";
   
   if (!empty($aud[0]))
   {
$name = $aud[0];
$link = $link[0];

$classa = $classa[0]; // 2 DAYS AGO
$classs = $classs[0]; // 699MB
$classu = $classu[0]; // YES›L YAZILI UPLOADERS
$classd = $classd[0]; // DOWNLOADERS
$tagz = $tagz[0]; // TAGS 
$linkz = $linkz[0]; //links
$namez = $namez[0]; //names
$stylez = $stylez[0]; //names

$stylez = str_replace("/img/", $CFG['domain']."/img/", $stylez);

//echo $namez;

if (preg_match("|accept|", $aud[0]))
	 {

 	}

$namezx=strtr($namez,"¿¡¬√ƒ≈∆«»… ÀÃÕŒœ—“”‘’÷ÿŸ⁄€‹ﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸ˇ‹¸??????",
     "AAAAAAACEEEEIIIINOOOOOOUUUUsaaaaaaaceeeeiiiinoooooouuuuyUuSsGgIi");

$namezx = strip_tags($namezx);
    
	$beforeseo = array("/[^a-zA-Z0-9]/", "/-+/", "/-$/");
	$afterseo = array("-", "-", "");

	$namezx = strtolower(preg_replace($beforeseo, $afterseo , $namezx));      
     
echo "
<tr class=\"resultsrow\">
	<td class=\"name\" style=\"".$stylez."\"><a href=\"".$CFG['domain']."/torrents/".$namezx."/".$linkz."\" title=\"".strip_tags($namez)."\">".$namez."</a> &#187; ".$tagz."
	</td>
	<td>
		<td class=\"a\">".$classa."</td><td class=\"s\">".$classs."</td><td class=\"u\">".$classu."</td><td class=\"d\">".$classd."</td>
	</td>
</tr>\n\n";

}

$i++;

}
echo      "</table><br><br>";

if (!empty($name)) {
	
	echo "<div id=\"paginate\" align=\"center\">";
	echo "<p>Page: <b>$pagex</b> of <b>$last</b></p>";


		if ($page > 0) {
		
	echo "<span class=\"paginate\"> <a href='{$_SERVER['PHP_SELF']}?search=$search&orderby=$orderby'>&nbsp; <b>&lt;&lt;</b> FIRST &nbsp;</a></span> ";
	}else {
		echo "<span class=\"disabled\">&nbsp; <b>&lt;&lt;</b> FIRST &nbsp;</span> ";
	}

		if ($page > 1) {
	$previous = $page-1;

	echo "<span class=\"paginate\"> <a href='{$_SERVER['PHP_SELF']}?search=$search&page=$previous&orderby=$orderby'>&nbsp; <b>&lt;</b> PREVIOUS &nbsp;</a></span> ";
	}else {
		echo "<span class=\"paginate\">&nbsp; <b>&lt;</b> PREVIOUS &nbsp;</span> ";
		}
	
		if ($page == $lastx) {
		
	$lastx = $page; 
	
	echo " <span class=\"paginate\"> &nbsp; NEXT <b>&gt;</b> &nbsp;</span>";
		echo " <span class=\"paginate\"> &nbsp; LAST <b>&gt;&gt;</b> &nbsp;</span> ";
	} else {
	
		$next = $page+1;
		echo " <span class=\"paginate\"> <a href='{$_SERVER['PHP_SELF']}?search=$search&page=$next&orderby=$orderby'>&nbsp; NEXT <b>&gt;</b> &nbsp;</a></span>";
		echo " <span class=\"paginate\"> <a href='{$_SERVER['PHP_SELF']}?search=$search&page=$lastx&orderby=$orderby'>&nbsp; LAST <b>&gt;&gt;</b> &nbsp;</a></span> ";
		}
echo "</div><br><br>";


	}else  { echo "<center><div class=\"error\" >nothing found for <b>". $_GET['search']." </b>\n</div></center>"; }
}
  else
{ ?>
<div align="center" style="margin-top:30px;">


 	<div id="srcbox">
 		<form action="<?=$CFG['domain']?>/index.php" method="get" onSubmit="this.submit();return false;">
			<input class="src" name="search" type="text" autocomplete="off" delay="1500" value="type and go..." onBlur="if(this.value=='') this.value='type and go...';" onFocus="if(this.value=='type and go...') this.value='';">
			
			<input type="image" src="<?=$CFG['domain']?>/img/submit.png" name="Submit" value="Submit">
		</form>
 	</div> 
</div>

<?php 

print "<center><div align=\"center\" class=\"tagcloud\" >".tag_cloud()."</div></center>";

$urlmain = "http://www.torrentz.com/index.php";

$gurlmain = @file_get_contents($urlmain);

/// START OF LATEST MOVIE ///
$latestmovie= explode("Movie</a> torrents</h2>", $gurlmain);

$latestmovie = explode("</dl></div>", $latestmovie[1]);


if (!empty($latestmovie[0]))
   {
echo "
<div class=\"files\"><h2>Latest Movie Torrents</h2></div>
<table border=\"0\" class=\"results\" width=\"%100\">

";
 
}

$i=1;
$r=10;
while ($i <= $r){

$aud = explode("<dl>", $latestmovie[0]);
$aud = explode("</dl>", $aud[$i]);

/// FIND CLASS A //

$classa = explode("<span class=\"a\">", $aud[0]);
$classa = explode("</span>", $classa[1]);

/// FIND CLASS A //

$classs = explode("<span class=\"s\">", $aud[0]);
$classs = explode("</span>", $classa[1]);

/// FIND CLASS U //

$classu = explode("<span class=\"u\">", $aud[0]);
$classu = explode("</span>", $classu[1]);

/// FIND CLASS D //

$classd = explode("<span class=\"d\">", $aud[0]);
$classd = explode("</span>", $classd[1]);


//TAGS //
$tagz = explode("&#187;", $aud[0]);
$tagz = explode("</dt>", $tagz[1]);

//LINKz

$linkz = explode("href=\"/", $aud[0]);
$linkz = explode("\">", $linkz[1]);


//NAMEZ

$namez = explode($linkz[0]."\">", $aud[0]);
$namez = explode("</a>", $namez[1]);

//STYLE

$stylez = explode("style=\"", $aud[0]);
$stylez = explode("><a href=", $stylez[1]);

//echo $namez[0];

   //echo $aud[0]." ".$link[0]."<br>\n";
   
   if (!empty($aud[0]))
   {
$name = $aud[0];
$link = $link[0];


$classa = $classa[0]; // 2 DAYS AGO
$classs = $classs[0]; // 699MB
$classu = $classu[0]; // YES›L YAZILI UPLOADERS
$classd = $classd[0]; // DOWNLOADERS
$tagz = $tagz[0]; // TAGS 
$linkz = $linkz[0]; //links
$namez = $namez[0]; //names
$stylez = $stylez[0]; //names

$stylez = str_replace("/img/", $CFG['domain']."/img/", $stylez);

//echo $namez;

if (preg_match("|accept|", $aud[0]))
	 {

 	}

$namezx=strtr($namez,"¿¡¬√ƒ≈∆«»… ÀÃÕŒœ—“”‘’÷ÿŸ⁄€‹ﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸ˇ‹¸??????",
     "AAAAAAACEEEEIIIINOOOOOOUUUUsaaaaaaaceeeeiiiinoooooouuuuyUuSsGgIi");

$namezx = strip_tags($namezx);


     
	$beforeseo = array("/[^a-zA-Z0-9]/", "/-+/", "/-$/");
	$afterseo = array("-", "-", "");

	$namezx = strtolower(preg_replace($beforeseo, $afterseo , $namezx));      
     
echo "
<tr class=\"resultsrow\">
	<td class=\"name\" style=\"".$stylez."\"><a href=\"".$CFG['domain']."/torrents/".$namezx."/".$linkz."\" title=\"".strip_tags($namez)."\">".$namez."</a> &#187; ".$tagz."
	</td>
	<td>
		<td class=\"a\">".$classa."</td><td class=\"s\">".$classs."</td><td class=\"u\">".$classu."</td><td class=\"d\">".$classd."</td>
	</td>
</tr>\n\n";

}

$i++;

}
echo      "</table><br><br>";
/// END OF LATEST MOVIE ///

/// START OF LATEST TV SHOWS ///
$latesttv= explode("TV Show</a> torrents</h2><dl>", $gurlmain);

$latesttv = explode("</dl></div>", $latesttv[1]);


if (!empty($latesttv[0]))
   {
echo "
<div class=\"files\"><h2>Latest Tv Show Torrents</h2></div>
<table border=\"0\" class=\"results\" width=\"%100\">

";
 
}

$i=1;
$r=10;
while ($i <= $r){

$aud = explode("<dl>", $latesttv[0]);
$aud = explode("</dl>", $aud[$i]);

/// FIND CLASS A //

$classa = explode("<span class=\"a\">", $aud[0]);
$classa = explode("</span>", $classa[1]);

/// FIND CLASS A //

$classs = explode("<span class=\"s\">", $aud[0]);
$classs = explode("</span>", $classa[1]);

/// FIND CLASS U //

$classu = explode("<span class=\"u\">", $aud[0]);
$classu = explode("</span>", $classu[1]);

/// FIND CLASS D //

$classd = explode("<span class=\"d\">", $aud[0]);
$classd = explode("</span>", $classd[1]);


//TAGS //
$tagz = explode("&#187;", $aud[0]);
$tagz = explode("</dt>", $tagz[1]);

//LINKz

$linkz = explode("href=\"/", $aud[0]);
$linkz = explode("\">", $linkz[1]);


//NAMEZ

$namez = explode($linkz[0]."\">", $aud[0]);
$namez = explode("</a>", $namez[1]);

//STYLE

$stylez = explode("style=\"", $aud[0]);
$stylez = explode("><a href=", $stylez[1]);

//echo $namez[0];

   //echo $aud[0]." ".$link[0]."<br>\n";
   
   if (!empty($aud[0]))
   {
$name = $aud[0];
$link = $link[0];


$classa = $classa[0]; // 2 DAYS AGO
$classs = $classs[0]; // 699MB
$classu = $classu[0]; // YES›L YAZILI UPLOADERS
$classd = $classd[0]; // DOWNLOADERS
$tagz = $tagz[0]; // TAGS 
$linkz = $linkz[0]; //links
$namez = $namez[0]; //names
$stylez = $stylez[0]; //names

$stylez = str_replace("/img/", $CFG['domain']."/img/", $stylez);

//echo $namez;

if (preg_match("|accept|", $aud[0]))
	 {

 	}
$namezx=strtr($namez,"¿¡¬√ƒ≈∆«»… ÀÃÕŒœ—“”‘’÷ÿŸ⁄€‹ﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸ˇ‹¸??????",
     "AAAAAAACEEEEIIIINOOOOOOUUUUsaaaaaaaceeeeiiiinoooooouuuuyUuSsGgIi");

$namezx = strip_tags($namezx);    
	$beforeseo = array("/[^a-zA-Z0-9]/", "/-+/", "/-$/");
	$afterseo = array("-", "-", "");

	$namezx = strtolower(preg_replace($beforeseo, $afterseo , $namezx));          
echo "
<tr class=\"resultsrow\">
	<td class=\"name\" style=\"".$stylez."\"><a href=\"".$CFG['domain']."/torrents/".$namezx."/".$linkz."\" title=\"".strip_tags($namez)."\">".$namez."</a> &#187; ".$tagz."
	</td>
	<td>
		<td class=\"a\">".$classa."</td><td class=\"s\">".$classs."</td><td class=\"u\">".$classu."</td><td class=\"d\">".$classd."</td>
	</td>
</tr>\n\n";
}
$i++;
}
echo      "</table><br><br>";
/// END OF LATEST TV SHOWS ///
} 

?>
 </body>
 </html>
include ("footer.php");

end_main_frame();
stdfoot();
?>
