<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function clean ($data)
  {
    $data = trim (strval ($data));
    $data = str_replace (chr (0), '', $data);
    return $data;
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  define ('R_VERSION', '0.4 ');
  $url = fix_url ($_GET['url']);
  $url = clean ($url);
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" CONTENT="text/html; charset=';
  echo $charset;
  echo '" />
<TITLE>';
  echo $SITENAME;
  echo '</TITLE>
';
  echo '<s';
  echo 'tyle type="text/css">
body {
	font-family: Verdana, Tahoma, Georgia;
	font-size: 10px;
}

.link a{
	color: #FFFFFF;
	text-decoration: none;
	font-weight:normal;
}

.link a:hover {
	text-decoration: underline;
}

.linkOrange a{
	color: #EC8749;
	text-decoration: none;
}

.linkOrange a:hover {
	text-decoration: underline;
}

.SmallText, .Link, .OrangeSmallText, .GraySmallTex';
  echo 't, .BoldSmallText{
	font-family: Verdana;
	font-size: 10;
}

.BoldSmallText {
	font-weight: bold;
}

.OrangeSmallText {
	color: #EC8749;
}

.GraySmallText {
	color: #999999;
}

.WhiteSmallText {
	color: #FFFFFF;
}
</style>
</HEAD>

<body bgcolor="#444444">
<!--javascript:top.location = parent.document.referrer;"-->
<div align="center">
<table cellpadding="0" cellspacing="0" b';
  echo 'order="0" height="20" width="600">
	<tr>
		<td align="left" class="link"><a href="';
  echo $BASEURL;
  echo '" target="_top">';
  echo '<s';
  echo 'pan style="font-weight:bold;font-size:10px;">';
  echo $BASEURL;
  echo '</span></a></td>

		<td align="left" class="link"><b class="OrangeSmallText">';
  echo $lang->global['invalidlink'];
  echo '</b> <a href="';
  echo $BASEURL;
  echo '/contactstaff.php?subject=invalid_link&link=';
  echo $url;
  echo '" target="_top">';
  echo $lang->global['clicktoreport'];
  echo '</a></td>

		<td align="right" class="link"><a href="';
  echo $url;
  echo '" target="_top"><img src="';
  echo $BASEURL;
  echo '/';
  echo $pic_base_url;
  echo '/close.gif" title="';
  echo $lang->global['buttonremoveframe'];
  echo '" border="0" /></a></td>
	</tr>
</table>
</div>
</body>
</HTML>
';
?>
