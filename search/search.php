<?php	
// Use this script at own risk. www.releasepirate.com cant be held responsible for the use of this code.  This script is AS IS

$text = $_POST['text'];
switch($_POST['engine']) {
	case 1:
	  for($i=0;$i<strlen($text);$i++) { if($text[$i]==" ") { $text[$i]="+"; } }
	  $data = "http://releasepirate.com/index.php?s=".$text;
	  break;
	case 2:
	  for($i=0;$i<strlen($text);$i++) { if($text[$i]==" ") { $text[$i]="+"; } }
	  $data = "http://www.torrentreactor.net/search.php?search=&words=".$text."&cid=0";
	  break;
   	case 3:
	  $data = "http://www.seedpeer.com/search.php?search=".$text;
	  break;
	case 4:
	  $data = "http://btjunkie.org/search?q=".$text;
	  break;
	case 5:
	  $data = "http://www.mininova.org/search/?search=".$text;
	  break;
	case 6:
	  $data = "http://btjunkie.org/search?q=".$text;
	  break;
	case 7:
	  $data = "http://www.bushtorrent.com/torrents.php?search=&words=".$text;
	  break;
	case 8:
	  $data = "http://fenopy.com/?keyword=".$text."&select=0&order=0&sort=0&minsize=&maxsize=&search.x=0&search.y=0&search=Search";
	  break;
	case 9:
	  $data = "http://isohunt.com/torrents/?ihq=".$text;
	  break;
	case 10:
	  $data = "http://www.mybittorrent.com/?keywords=".$text."&x=0&y=0";
	  break;
	case 11:
	  $data = "http://thepiratebay.org/search/".$text."/0/3/0";
	  break;
	case 12:
	  $data = "http://www.torrentbox.com/torrents-search.php?search=".$text."&cat=0&submit=TBox+Search";
	  break;
	  case 13:
	  $data = "http://h33t.com/search/".$text."0/3/0";
	  break;
	default:
	  echo "Wrong";
	  break;
}
header("Location: $data");

?>
