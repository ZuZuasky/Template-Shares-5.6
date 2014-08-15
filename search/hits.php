<?php
// Use this script at own risk. www.releasepirate.com cant be held responsible for the use of this code.  This script is AS IS

$filename = "hits.txt" ;

if(!file_exists($filename)){
$fd = fopen ($filename , "w+");
fclose($fd);
}

$file = file($filename);
$file = array_unique($file);
$hits = count($file);
echo $hits;

$fd = fopen ($filename , "r");
$fstring = fread ($fd , filesize ($filename)) ;
fclose($fd) ;
$fd = fopen ($filename , "w");
$fcounted = $fstring."\n".getenv("REMOTE_ADDR");
$fout= fwrite ($fd , $fcounted );
fclose($fd);



?>