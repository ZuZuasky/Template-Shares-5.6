<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  define ('L_VERSION', '0.1');
  require_once INC_PATH . '/ts_listen.php';
  if (!createwavefile ($_SESSION['security_code']))
  {
    header ('HTTP/1.1 400 Bad Request');
  }

  $context['browser'] = array ('is_opera' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false, 'is_opera6' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera 6') !== false, 'is_opera7' => (strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera 7') !== false OR strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera/7') !== false), 'is_opera8' => (strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera 8') !== false OR strpos ($_SERVER['HTTP_USER_AGENT'], 'Opera/8') !== false), 'is_ie4' => (strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 4') !== false AND strpos ($_SERVER['HTTP_USER_AGENT'], 'WebTV') === false), 'is_safari' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false, 'is_mac_ie' => (strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.') !== false AND strpos ($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false), 'is_web_tv' => strpos ($_SERVER['HTTP_USER_AGENT'], 'WebTV') !== false, 'is_konqueror' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Konqueror') !== false, 'is_firefox' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false, 'is_firefox1' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Firefox/1.') !== false, 'is_firefox2' => strpos ($_SERVER['HTTP_USER_AGENT'], 'Firefox/2.') !== false);
  $context['browser']['is_gecko'] = ((strpos ($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false AND !$context['browser']['is_safari']) AND !$context['browser']['is_konqueror']);
  $context['browser']['is_ie7'] = (((strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false AND !$context['browser']['is_opera']) AND !$context['browser']['is_gecko']) AND !$context['browser']['is_web_tv']);
  $context['browser']['is_ie6'] = (((strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false AND !$context['browser']['is_opera']) AND !$context['browser']['is_gecko']) AND !$context['browser']['is_web_tv']);
  $context['browser']['is_ie5.5'] = (((strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.5') !== false AND !$context['browser']['is_opera']) AND !$context['browser']['is_gecko']) AND !$context['browser']['is_web_tv']);
  $context['browser']['is_ie5'] = (((strpos ($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.0') !== false AND !$context['browser']['is_opera']) AND !$context['browser']['is_gecko']) AND !$context['browser']['is_web_tv']);
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=';
  echo $charset;
  echo '" />
		<title>Listen</title>
		<link rel="stylesheet" href="';
  echo $BASEURL;
  echo '/include/templates/';
  echo $defaulttemplate;
  echo '/style/style.css" type="text/css" media="screen" />
		';
  echo '
	</head>
	<body style="margin: 1ex;">
		<div class="popuptext">';
  if ($context['browser']['is_ie'])
  {
    echo '
			<object classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95" type="audio/x-wav">
				<param name="AutoStart" value="1" />
				<param name="FileName" value="';
    echo $context['verificiation_sound_href'];
    echo ';format=.wav" />
			</object>';
  }
  else
  {
    echo '
			<object type="audio/x-wav" data="';
    echo $context['verificiation_sound_href'];
    echo ';format=.wav">
				<a href="';
    echo $context['verificiation_sound_href'];
    echo ';format=.wav">';
    echo $context['verificiation_sound_href'];
    echo ';format=.wav</a>
			</object>';
  }

  echo '
			<br />
			<a href="listen.php">Play Again</a><br />
			<a href="javascript:self.close();">Close</a><br />
		</div>
	</body>
</html>';
?>
