<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>ImageFlow 1.1 slideshow with youtube and high quality video</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta name="language" content="en" />
<meta name="description" content="ImageFlow with youtube slideshow ."/>
<meta name="keywords" content="ImageFlow, automatic slideshow, image flow, CoverFlow, cover flow, Javascript, imageflow in javascript, coverflow in javascript, youtube" />
<meta name="robots" content="index, follow, noarchive" />
<meta name="copyright" content="cfconsultancy" />
<meta name="web_author" content="Ceasar Feijen" />
<link rel="shortcut icon" href="favicon.ico" type="image/ico" />
<!-- Needed for imageflow -->
<link rel="stylesheet" title="Standard" href="screen.css" type="text/css" media="screen" />
<script language="javascript" type="text/javascript" src="imageflow.js"></script>
	<script language="javascript" type="text/javascript">
	/* Optional settings for imageflow */
	conf_reflection_p = 0.5;
	/* Sets the numbers of images on each side of the focussed one */
	conf_focus = 4;
	/* 0 = default, 1 = small to big picture Change this to see the effect */
	sizeAlgo = 0; //
	/* Glide to a picture on startup. For example 10 is the 11th picture
	Use 0 for the starting picture */
	glidetopicture = 10;
    /* Autostart slideshow  */
    slideshowauto = true;
    /* Show slideshow button  */
    slideshowbutton = true;
    /* Slideshow time setting in seconds */
    slideshowtime = 3000;
    /* Video settings */
    videowidht = '512';
    videoheight = '408';
    /* Video position */
	videotop  = '-290px';
	videoleft = '-100px';
	/* Output video, highslide, empty for normal link */
	output = "video";
	</script>
<!-- END imageflow -->
</head>
<body>

</div>
<div id="main">
<h1>youtube</h1>
<!-- remove this if you don't want anyone to change the results  -->
    <form method="get" action="<?php echo basename(__FILE__); ?>">
     <br/>
      <input class="formulier_input" type="text" name="q" value="" />
      <input class="formulier_button" type="submit" name="submit" value="Search" />
    </form>
<!-- END -->
</div>
<!-- Needed for imageflow -->
<div id="imageflowstart">
  <div id="imageflow">
	<div id="loading">
		<b>Loading......</b><br/>
		<img src="loading.gif" width="208" height="13" alt="loading" />
	</div>
	<div id="images">
	<?php
	/***************************************************************************
	//ImageFlow + youtube   : 1.1 build 1
	//released:             : Aug 5 2009
	//copyright             : Copyright © 2008 cfconsultancy
	//email                 : info@cfconsultancy.nl
	//website               : http://www.cfconsultancy.nl
	//Imageflow with youtube is released under a Creative Commons Attribution 3.0 License !
	***************************************************************************/

	error_reporting(E_ALL);
	//remove this if you don't want anyone to change the results
	if (!isset($_GET['q']) || empty($_GET['q'])) {
    //END

	//Default keywords
	$videocode = "Phil Collins";

    //remove this if you don't want anyone to change the results
	    } else {

	$videocode = $_GET['q'];
	}
	//END

	//Settings for the youtube feed !
	//&author=user  - Search only video's uploaded by a particular YouTube user - add this by $feedURL
	//Sorting - can be relevance (default), published, viewCount, rating
	$sorteren    = "relevance";
    //Maximum results (cannot not be higher then 50)
	$maximaal    = 25;
	//Background for video player and div layer
	$background  = "#FFFFFF";
	//Reflection bgcolor
	$reflection  = "#000000";
    //The safeSearch parameter none , moderate, strict
	$safeSearch = "&safeSearch=strict";
    //Directory where the images will be stored and cached (needs to be chmod to 775 or 777)
	$imagesdir   = "./img/";
	//Cache the xml feed too true or false
	$cachexml    = true;
	//Previous and next button
	$prevnext    = true;
    //Empty cache after one hour 60*60, one day 24*60*60 or 7*24*60*60 one week)
	$cacheLife   = 60*60;
	//Maximum video's to retrieve (default 500)
	$maxvideo = 500;


    //Check if start index has been set ( max. 500 video's)
	if(isset($_GET['start-index']) && is_numeric($_GET['start-index']) && $_GET['start-index'] > 0  && $_GET['start-index'] <= $maxvideo){
		$start = $_GET['start-index'];
	 }elseif (isset($_GET['start-index']) <= $maxvideo){
		$start = 1;
	}else{
        $start = 1;
    }

	//Remove all the images after some time
	$handle = opendir($imagesdir);
	while ($file = readdir($handle))
	{
		if ($file != '.' AND $file != '..' AND eregi('(.*)\.jpg',$file) | eregi('(.*)\.png',$file) | eregi('(.*)\.xml',$file))
		{
	     if (time()-fileatime($imagesdir.$file) > $cacheLife)
	     unlink($imagesdir.$file);
	    }
	}

	$videocode = ereg_replace('[[:space:]]+', ' ', trim($videocode));
	$videocode = urlencode($videocode);

      // set feed URL to get the info
      $feedURL = 'http://gdata.youtube.com/feeds/api/videos?q=' . $videocode . '' . $safeSearch . '&format=5&v=2&start-index=' . $start . '&max-results=' .$maximaal . '&orderby=' . $sorteren;

		//Check if youtube is alive
		$youHeaders = get_headers($feedURL);

			if (preg_match('/^HTTP\/\d\.\d\s+(200)/', $youHeaders[0])) {

      if ( $cachexml )
      {
      // Filename for the retrieved rss feed
      $cachefile = $imagesdir . $videocode . '-' . $start . '.xml';

        // Check if exists
	    if (file_exists($cachefile) AND (filemtime($cachefile) > time() - $cacheLife))
	    {
          //Read feed into SimpleXML object
          $sxml = simplexml_load_file($cachefile);

        } else {

	        if(function_exists('curl_init')){
	            $chf = curl_init();
	            $timeout = 15; // set to zero for no timeout
	            curl_setopt ($chf, CURLOPT_URL, $feedURL);
	            curl_setopt ($chf, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt ($chf, CURLOPT_CONNECTTIMEOUT, $timeout);
	            $feedcontents = curl_exec($chf);
	            curl_close($chf);

	        } else {

	            $feedcontents = file_get_contents($feedURL);
	        }

        // Write to disk and save the filename
	    file_put_contents($cachefile, $feedcontents);
        //Read cach feed into SimpleXML object
        $sxml = simplexml_load_file($cachefile);
	  	}
	  } else {

        //Read feed into SimpleXML object
		$sxml = simplexml_load_file($feedURL);

	  }

      // get nodes in media: namespace for media information
      $media = $sxml->entry->children('http://search.yahoo.com/mrss/');

	   if(@empty($media)){
	    $notube = "Sorry, no video's found with these keywords !";
	   }

	    $links = $sxml->children('http://www.w3.org/2005/Atom');

        //Find out if we have next and previous items
        $properties = array('next','previous');
        foreach($properties as $name){
	        $$name = null;
	        $exit = false;
	        foreach($links as $link) {
	            if($exit){
	                continue;
	            }
	            foreach($link->attributes() as $key => $value) {
	                if($key == 'href'){
	                    $$name = (string) $value;
	                }
	                if (stristr ($value , $name) == TRUE) {
	                    $exit = true;
	                }

	            }
	        }
	        if(!$exit){
            	$$name = null;
            }
            if($$name != null){
				preg_match("/start\-index=([0-9]+)/i",$$name,$matches);
				if(count($matches) > 0){
                	$$name = $matches[1];
                }else{
                	$$name = null;
                }
            }
		}
       if ($prevnext)
       {
    	if($previous != null){
        	echo '<img src="previous.gif" id="imfl_previous" longdesc="' . basename(__FILE__) . '?vq=' . $videocode . '&amp;start-index=' . $previous . '" alt="Previous '. $maximaal . ' video\'s" border="0" />';
		}
       }
      foreach ($sxml->entry as $entry) {
        // get nodes in media: namespace for media information
        $media = $entry->children('http://search.yahoo.com/mrss/');

        // get video titles
        $titles = $media->group->title;
        $titles = str_replace(array('&nbsp;', '&', '’', '“', '”', '`', '"', "'",), array('', ' ', '', '', '', '', ''), $titles);

	    // get video thumbnail (hqdefault.jpg)
	    foreach($media->group->thumbnail as $th){
	    	$attrs = $th->attributes();
	    	if(!isset($attrs['time'])){
	    $thumbnail = $attrs['url'];
            }
		}

        //First let's first get the picture name's to see if it's already there
		$path = explode('/', $thumbnail);
		$filename = $path[count($path)-2];
        $ext = ".jpg"; //File extension
        $youname = $imagesdir.$filename.$ext;

      //If already there skip the download
      if (!file_exists($youname)) {

        //Get the thumbnail
        if(function_exists('curl_init')){
	        $ch = curl_init();
	        $timeout = 15; // set to zero for no timeout
	        curl_setopt ($ch, CURLOPT_URL, $thumbnail);
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	        $contents = curl_exec($ch);
			curl_close($ch);
	    } else {
	        $contents = file_get_contents($thumbnail);
	    }

        //Get the picture's name again
		$path = explode('/', $thumbnail);
		$filename = $path[count($path)-2];

        //Put it in the cache dir and give it a file name
        $ext = ".jpg"; //File extension
        $youname = $imagesdir.$filename.$ext;

        //Write the file to disk
        file_put_contents($youname, $contents);
      }

        // get video player URL
        $links = $media->group->children('http://gdata.youtube.com/schemas/2007');
        $id = $links->videoid;

        //For more information about the php reflection and settings got to http://reflection.corephp.co.uk
?>
		<img src="reflect.php?img=<?=$youname; ?>&amp;bgc=<?=$reflection = str_replace("#", "", $reflection); ?>&amp;cache=1&amp;procent=0.8" longdesc="http://www.youtube.com/v/<?=$id; ?>&amp;fs=1&amp;rel=1&amp;autoplay=1&amp;egm=1&amp;color1=<?=$background = str_replace("#", "0x", $background); ?>&amp;color2=<?=$background = str_replace("#", "0x", $background); ?>" alt="<?= html_entity_decode(utf8_decode($titles)); ?>" title="Click to play this video"/>
<?php
    }
      if ($prevnext)
      {
    	if($next != null && $next < $maxvideo){
	    	echo '<img src="next.gif" id="imfl_next" longdesc="' . basename(__FILE__) . '?vq=' . $videocode . '&amp;start-index=' . $next . '" alt="Next ' . $maximaal . ' video\'s" border="0" />';
      }
		}
  } else {
	//Youtube server can not be reached
	$notubexml = "Sorry, no connection to the youtube server at this moment!";
}
?>
	</div>
	<div id="captions"><?=( isset($notube) ? $notube : '' ); ?> <?=( isset($notubexml) ? $notubexml : '' ); ?></div>
	<div id="slideshow" title="play slideshow" onclick="slideshow(1);"></div>
	<div id="scrollbar">
	<div id="youtubepopup" class="vidFrame" style="background-color: <?=$background = str_replace("0x", "#", $background); ?>;">
	</div>
		<div id="slider" title="Click and drag to the left or right or use your arrow keys or scrollbutton"></div>
	</div>
  </div>
</div>
<!-- END imageflow -->

</body>
</html>
