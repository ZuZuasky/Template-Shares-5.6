<?php

include "$hm/browser.php";

$tot = 0;
$mons = $_GET['mons'];
if($mons == "prev"){
	$tot = $pmozilla+$pie+$plynx+$popera+$pwebtv+$pkon+$pfirefox+$psafari+$poth+$pflock;
	$arr = array("Mozilla" => $pmozilla, "IE" => $pie, "Lynx" => $plynx , "Opera" => $popera, "Webtv" => $pwebtv, "Konqueror" => $pkon, 
	"Firefox" => $pfirefox, "Safari" => $psafari, "Others" => $poth, "Flock" => $pflock );
	$mona = $pmonth; 
}
else{
	$tot = $mozilla+$ie+$lynx+$opera+$webtv+$kon+$firefox+$safari+$oth+$flock;
	$arr = array("Mozilla" => $mozilla, "IE" => $ie, "Lynx" => $lynx , "Opera" => $opera, "Webtv" => $webtv, "Konqueror" => $kon, 
	"Firefox" => $firefox, "Safari" => $safari, "Others" => $oth, "Flock" => $flock );
	$mona = $month;
	
}

if($tot == 0)
	$tot = 1;
arsort($arr);

echo "<table width=150 style=\" font-family: verdana,arial,san-serif; 
	font-size:10pt; border: ridge inehit 1px; background-color:inehit;\" border=0>";
echo "<tr><td align=center colspan=4> <a href=\"http://www.hscripts.com\"
	 style=\"text-decoration:none; color: inehit; font-weight: bold;\">
	 Stats of $mona</a></td></tr>";
echo "<tr align=center><td><b>Browser</b></td>
	<td><b>HITS</b></td><td><b>Percent</b></td></tr>";

$vaal = true;
while (list($key, $val) = each($arr)) {
	$lkey = strtolower($key);
	if($vaal === true)
	{
		echo "<tr align=center><td>";
		echo "<img src=\"$hm2/images/$lkey.png\" alt=\"$key\" border=\"0\" title=\"$key\">";
		echo"</td><td> $val </td>
		<td> ".(round($val*10000/$tot)/100)." %</td></tr>";
		$vaal = false;
	}
	else
	{
		echo "<tr align=center><td>";
		echo "<img src=\"$hm2/images/$lkey.png\" alt=\"$key\" border=\"0\" title=\"$key\">";
		echo"</td><td> $val </td>
		<td> ".(round($val*10000/$tot)/100)." %</td></tr>";
		$vaal = true;
	}
}
echo "</table>";


?>
