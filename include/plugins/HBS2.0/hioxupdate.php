<!-- This utility is provided by HIOX INDIA   -->
<!-- This is a copyright product of HIOXINDIA -->
<!--     Visit us at hioxindia.com            -->


<?php

$mozilla = $firefox = $opera = $safari = $webtv = $kon = $opera = $ie = $lynx = $flock = 0;

//print_r($_SERVER['HTTP_USER_AGENT']);

if(ereg("Opera", getenv("HTTP_USER_AGENT"))) $browser = "opera";
else if(ereg("Safari", getenv("HTTP_USER_AGENT"))) $browser = "safari";
else if(ereg("WebTV", getenv("HTTP_USER_AGENT"))) $browser = "webtv";
else if(ereg("Konqueror", getenv("HTTP_USER_AGENT"))) $browser = "kon";
else if(ereg("Firefox", getenv("HTTP_USER_AGENT"))) $browser = "firefox";
else if(ereg("MSIE", getenv("HTTP_USER_AGENT"))) $browser = "ie";
else if(ereg("Lynx", getenv("HTTP_USER_AGENT"))) $browser = "lynx";
else if(ereg("Flock", getenv("HTTP_USER_AGENT"))) $browser = "flock";
else if((ereg("Nav", getenv("HTTP_USER_AGENT"))) || (ereg("Gold", getenv("HTTP_USER_AGENT"))) || 
(ereg("X11", getenv("HTTP_USER_AGENT"))) || (ereg("Mozilla", getenv("HTTP_USER_AGENT"))) || 
(ereg("Netscape", getenv("HTTP_USER_AGENT"))) ) 
$browser = "mozilla";
else $browser = "oth";

/*if($browser == "oth")
{
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$ua = $ua."\n";
	$open = fopen("./HBS2.0/others.txt", "a");
	fwrite($open,$ua);
	fclose($open);
}*/

$mozilla=$ie=$lynx=$firefox=$safari=$opera=$webtv=$kon=$oth=$flock=0;

include "$hm/browser.php";
$cm = date('M');

if($month=="")
	$month = $cm;

if($cm == $month)
{
	if($browser == "mozilla")
	$mozilla = $mozilla+1;
	else if($browser == "firefox")
	$firefox = $firefox+1;
	else if($browser == "ie")
	$ie = $ie+1;
	else if($browser == "lynx")
	$lynx = $lynx+1;
	else if($browser == "opera")
	$opera = $opera+1;
	else if($browser == "webtv")
	$webtv = $webtv+1;
	else if($browser == "kon")
	$kon = $kon+1;
	else if($browser == "safari")
	$safari = $safari+1;
	else if($browser == "oth")
	$oth = $oth+1;
	else if($browser == "flock")
	$oth = $flock+1;

}
else
{
$pmonth = $month;
$month = $cm;

$pmozilla = $mozilla;
$pie = $ie;
$pfirefox = $firefox;
$plynx = $lynx;
$pwebtv = $webtv;
$pkon = $kon;
$popera = $opera;
$psafari = $safari;
$poth = $oth;
$pflock = $flock;

$mozilla=$ie=$lynx=$firefox=$safari=$opera=$webtv=$kon=$oth=$flock=0;

if($browser == "mozilla")$mozilla = $mozilla+1;
else if($browser == "firefox")$firefox = $firefox+1;
else if($browser == "ie")$ie = $ie+1;
else if($browser == "lynx")$lynx = $lynx+1;
else if($browser == "opera")$opera = $opera+1;
else if($browser == "webtv")$webtv = $webtv+1;
else if($browser == "kon")$kon = $kon+1;
else if($browser == "safari")$safari = $safari+1;
else if($browser == "oth")$oth = $oth+1;
else if($browser == "flock")$flock = $flock+1;

}
if($ie === "" || $ie === NULL)
{
	$mozilla = $firefox = $opera = $safari = $webtv = $kon = $opera = $ie = $lynx = $flock = 0;
}
if($pie === "" || $pie === NULL)
{
	$pmozilla = $pfirefox = $popera = $psafari = $pwebtv = $pkon = $popera = $pie = $plynx = $pflock = 0;
}
$str = "<?php\n\n\$month = \"$month\";\n\n\$ie = $ie;\n\$mozilla = $mozilla;\n\$firefox = $firefox;
	\n\$opera = $opera;\n\$safari = $safari;\n\$kon = $kon;\n\$lynx = $lynx;\n\$webtv = $webtv;
	\n\$oth = $oth;\n\n\$pmonth = \"$pmonth\";\n\n\$pie = $pie;\n\$pmozilla = $pmozilla;
	\n\$pfirefox = $pfirefox;\n\$popera = $popera;\n\$psafari = $psafari;\n\$pkon = $pkon;
	\n\$plynx = $plynx;\n\$pwebtv = $pwebtv;\n\$poth = $poth;\n\$pflock = $pflock;\n\n?>";

$open = fopen("$hm/browser.php", "w");
fwrite($open,$str);
fclose($open);

?>
