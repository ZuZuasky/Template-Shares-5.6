<?
if (file_exists($file)) {
			clearstatcache();  // filemtime info gets cached so we must ensure that the cache is empty
			$time_difference = time() - filemtime($file); //   echo "$file was last modified: " . date ("F d Y H:i:s.", filemtime($file)) . "( " . $time_difference . " seconds ago) <br>" . "The cache is set to update every " . $cache_tolerance . " seconds.<br>";
		} else {
			$time_difference = $cache_tolerance;  // force update
		}

      // Parses shoutcasts xml to make an effective stats thing for any website
		$scfp = @fsockopen($scip, $scport, &$errno, &$errstr, 1); // Connect to the server
	
if($scfp){		
		if($time_difference >= $cache_tolerance){	// update the cache if need be
					// Get XML feed from server
					if($scsuccs!=1){
						fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
						while(!feof($scfp)){
  					$xmlfeed .= fgets($scfp, 8192);
					}
					fclose($scfp);
			}
        // Output to cache file
				$tmpfile = fopen($file,"w+"); 
				$fp = fwrite($tmpfile,$xmlfeed); 
				fclose($tmpfile); 
				flush ();
        // Outputs the cached file after new data
				$xmlcache = fopen($file,"r");
				$page = '';
			if($xmlcache){
 				while (!feof($xmlcache)) {
   				$page .= fread($xmlcache, 8192);
 				}
				fclose($xmlcache);
			}		
			}else{
        // outputs the cached file
				$xmlcache = fopen($file,"r");
				$page = '';
			if($xmlcache){
 				while (!feof($xmlcache)) {
   				$page .= fread($xmlcache, 8192);
 				}
				fclose($xmlcache);
			}
			}
				$loop = array(
							"AVERAGETIME",
							"CURRENTLISTENERS",
							"PEAKLISTENERS",
							"MAXLISTENERS",
							"SERVERGENRE",
							"SERVERURL",
							"SERVERTITLE",
							"SONGTITLE",
							"SONGURL",
							"IRC",
							"ICQ",
							"AIM",
							"WEBHITS",
							"STREAMHITS",
							"LISTEN",
							"STREAMSTATUS",
							"BITRATE",
							"CONTENT"
							);

				//define all the variables to get (delte any ones you don't want)
				$y=0;
				while($loop[$y]!=''){
				  $pageed = ereg_replace(".*<$loop[$y]>", "", $page);
				  $scphp = strtolower($loop[$y]);
				  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
				  if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
				   $$scphp = urldecode($$scphp);
				;
				  $y++;
				}
				//end intro xml elements
				
				//get listener info 
				$sPageRep = ereg_replace(".*<LISTENERS>", "", $page);
				$aListenerAtTime = explode("<LISTENER>", $sPageRep);
				$iR=1;
				while($aListenerAtTime[$iR]!=""){
				  $iT=$iR-1;
				  $aListener[$iT] = ereg_replace(".*<HOSTNAME>", "", $aListenerAtTime[$iR]);
				  $aListener[$iT] = ereg_replace("</HOSTNAME>.*", "", $aListener[$iT]);
				  $iR++;
				}
				//end listener info
				
				//get song info and history
				$pageed = ereg_replace(".*<SONGHISTORY>", "", $page);
				$pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
				$songatime = explode("<SONG>", $pageed);
				$r=1;
				while($songatime[$r]!=""){
				  $t=$r-1;
				  $playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
				  $playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
				  $song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
				  $song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
				  $song[$t] = urldecode($song[$t]);
				  $dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $page);
				  $dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
				$r++;
				}
//end song info


$averagemin = round($averagetime/60,2);
$irclink = 'irc://'.$ircsite.'/'.$irc.'';
$listenamp = 'http://'.$scip.':'.$scport.'/listen.pls';
$listenlnk = 'http://'.$scip.':'.$scport.'';



}
?>