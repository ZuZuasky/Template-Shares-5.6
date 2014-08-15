<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('L_VERSION', '0.2');
  require_once 'global.php';
  include 'search/header.php';
  include 'search/jump.php';
  include 'search/index.html';
  gzip ();
  dbconn ();

  stdhead ($lang->links['head']);
 //<---------------------------------Put what you want here------------------------------------->
 ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	
	
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-2" />
	<link rel="stylesheet" href="search/style.css" type="search/text/css" />
	<title>Bit Torrent Search Engine</title>
    <meta name="description" content="We index Torrent Torrent Reactor Sumo Torrent Mininova BTJunkie The Pirate Bay and more.">
    <meta name="keywords" content="torrent search, torrent finder, search, engine, torrent, bittorrent,scan, torrentsearcher, torrents">
</head>
<body>
<center><img src="search/TorrentsearchzLogo3.gif" alt="" width="512" height="114" /></center>
	
	
			<center>
			<p>&nbsp;</p>
			<div id="search">
				<FORM name="search" ACTION='search/search.php' METHOD='POST' target="result">
					<input TYPE='text' NAME="text" id="text" class="search" /> 
						&nbsp;<font face="verdana">Engine: </font><select name="engine">
								 <option value="1">Release Pirate</option>
									<option value="2">Torrent Reactor</option>
									<option value="3">SeedPeer</option>
									<option value="4">Sumo Torrent</option>
									<option value="5">Mininova</option>
									<option value="6">BTJunkie</option>
									<option value="7">Bush Torrent</option>
									<option value="8">Fenopy</option>
									<option value="9">IsoHunt</option>
									<option value="10">myBitTorrent</option>
									<option value="11">The Pirate Bay</option>
									<option value="12">Torrent Box</option>
                                    <option value="13">H33T</option>
								</select>&nbsp;
					<input TYPE='submit' NAME="submit" VALUE="SUBMIT" class="button" />
			  </FORM>
</div>
		</div>
</div>
<?php include("header.php");?>

<center>

<iframe name="result" style="margin-top:10px; border: solid #000000 1px;" width='75%' height='400' src="search/jump.html">
</iframe>
</center>
</body>
</html>
<?
  stdfoot ();
?>
