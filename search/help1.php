<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-2" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<title>Torrent help</title>
    <meta name="description" content="We index Torrent Torrent Reactor Sumo Torrent Mininova BTJunkie The Pirate Bay and more.">
    <meta name="keywords" content="torrent search, torrent finder, search, engine, torrent, bittorrent,scan, torrentsearcher, torrents">
</head>
<body>
	
	<div id="content">
		<div id="header">
			<p id="top_info">You are Visitor Number:<?php include('hits.php');?></p>
			
			<div id="logo">
				<h1><a href="http://search.releasepirate.com" title="Centralized Internet Content">Pirate<span class="title"> Search</span></a></h1>
				<p>Torrent Searching Made Easy- In Beta Testing</p>
			</div>
		</div>
					
		<div id="tabs">
			<ul>	
				<li><a class="current" href="index.php" accesskey="s"><span class="key">H</span>ome</a></li>
				<li><a href="#" accesskey="p"><span class="key">P</span>lugin</a></li>
				<li><a href="help1.php" accesskey="a"><span class="key">H</span>elp</a></li>
				<li><a href="contact1.php" accesskey="c"><span class="key">C</span>ontact Us</a></li>
				<li><a href="cloud1.php" accesskey="s"><span class="key">C</span>loud</a></li>
				<li><a href="http://releasepirate.com" accesskey="a"><span class="key">B</span>log</a></li>
			</ul>
			<div id="search">
				<FORM name="search" ACTION='search.php' METHOD='POST' target="result">
					<input TYPE='TEXT' NAME="text" id="text" class="search" /> 
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
								</select>&nbsp;
					<input TYPE='submit' NAME="submit" VALUE="SUBMIT" class="button" />
				</FORM>
			</div>
		</div>
</div>
<?php include("header.php");?>
<!-- Begin: AdBrite -->
<center><script type="text/javascript">
   var AdBrite_Title_Color = '0000FF';
   var AdBrite_Text_Color = 'ffffff';
   var AdBrite_Background_Color = 'ffffff';
   var AdBrite_Border_Color = '000000';
   var AdBrite_URL_Color = '008000';
</script>
<span style="white-space:nowrap;"><script src="http://ads.adbrite.com/mb/text_group.php?sid=793522&zs=3436385f3630" type="text/javascript"></script><!--
--><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=793522&afsid=1"><img src="http://files.adbrite.com/mb/images/adbrite-your-ad-here-banner-w.gif" style="background-color:#000000;border:none;padding:0;margin:0;" alt="Your Ad Here" width="11" height="60" border="0" /></a></span></center>
<!-- End: AdBrite -->
<center>

<iframe name="result" style="margin-top:10px; border: solid #000000 1px;" width='98%' height='600' src="help.php">
</iframe>
</center>

<?php include("footer.php");?>

</body>
</html>
