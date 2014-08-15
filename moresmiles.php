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

  define ('MS_VERSION', '0.5');
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  require_once $rootpath . '/' . $cache . '/smilies.php';
  $defaultsmilies = $smilies;
  $smilies = '';
  $editor = htmlspecialchars ($_GET['editor']);
  $e = 1;
  $class = 'trow1';
  $smilies = '<tr>';
  while (list ($code, $url) = each ($defaultsmilies))
  {
    $url = addslashes ($url);
    $url = htmlspecialchars_uni ($url);
    $smilies .= '' . '
	<td class="' . $class . '" align="center"><img src="' . $BASEURL . '/' . $pic_base_url . 'smilies/' . $url . '" alt="' . $code . '" onclick="insertSmilie(\'' . $code . '\');" style="cursor: pointer;" /></a></td>
	<td class="' . $class . '">' . $code . '</td>';
    if ($e == 2)
    {
      $smilies .= '</tr><tr>';
      $e = 1;
      continue;
    }
    else
    {
      $e = 2;
      continue;
    }
  }

  if ($e == 2)
  {
    $smilies .= '' . '<td colspan="2" class="' . $class . '">&nbsp;</td>';
  }

  $str = '
<script type="text/javascript" src="' . $BASEURL . '/scripts/prototype.lite.js?v=' . O_SCRIPT_VERSION . '"></script>
<script type="text/javascript" src="' . $BASEURL . '/scripts/general.js?v=' . O_SCRIPT_VERSION . '"></script>
<script type="text/javascript" src="' . $BASEURL . '/scripts/editor.js?v=' . O_SCRIPT_VERSION . '"></script>';
  $norightclick = '
<script language=javascript>
<!--
var message="";
///////////////////////////////////
function clickIE() {if (document.all) {(message);return false;}}
function clickNS(e) {if
(document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {(message);return false;}}}
if (document.layers)
{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}

document.oncontextmenu=new Function("return false")
// -->
</script>';
  $defaulttemplate = ts_template ();
  echo '' . '
<html>
<head>
<title>' . $SITENAME . ' - ' . $lang->global['smilies_listing'] . '</title>

<script type="text/javascript">
	var editor = eval(\'opener.\' + \'' . $editor . '\');
	function insertSmilie(code)
	{
		if(editor)
		{
			editor.performInsert(code, "", true, false);
		}
	}
</script>
' . $str . '
<link rel="stylesheet" href="' . $BASEURL . '/include/templates/' . $defaulttemplate . '/style/style.css" type="text/css" media="screen" />
</head>
<body>
' . $norightclick . '
<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<tr>
<td class="thead" colspan="4"><strong>' . $lang->global['smilies_listing'] . '</strong></td>
</tr>
<tr>
<td class="tcat" colspan="4"><span class="smalltext">' . $lang->global['click_to_add'] . '</span></td>
</tr>
' . $smilies . '
<tr>
<td class="thead" colspan="4" align="center"><span class="smalltext">[<a href="javascript:window.close();">' . $lang->global['buttonclosewindow'] . '</a>]</span></td>
</tr>
</table>
</body>
</html>';
?>
