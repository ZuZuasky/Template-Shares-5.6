<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  $rootpath = './../../';
  require $rootpath . 'global.php';
  gzip ();
  dbconn ();
  define ('TDS_VERSION', '0.2 by xam');
  if (((!$CURUSER OR $SITENAME == 'Templateshares') AND $CURUSER['username'] != 'xam'))
  {
    print_no_permission ();
  }

  $lang->load ('donate');
  include_once TSDIR . '/' . $cache . '/funds.php';
  include_once INC_PATH . '/readconfig_paypal.php';
  $funds_difference = $GLOBALS['PAYPAL']['tn'] - $funds['funds_so_far'];
  if ($funds_difference < 0)
  {
    $funds_difference = 0;
  }

  $Progress_so_far = $funds['funds_so_far'] / $GLOBALS['PAYPAL']['tn'] * 100;
  $Progress_so_far = (100 <= $Progress_so_far ? '100' : number_format ($Progress_so_far, 1));
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title>';
  echo $lang->donate['header'];
  echo '</title>
	<meta http-equiv="content-type" content="text/html; charset=';
  echo $charset;
  echo '" />
	';
  echo '<s';
  echo 'cript type="text/javascript" src="';
  echo $BASEURL;
  echo '/scripts/prototype.js"></script>
	';
  echo '<s';
  echo 'cript type="text/javascript" src="';
  echo $BASEURL;
  echo '/scripts/pbar/js/jsProgressBarHandler.js"></script>
	';
  echo '<s';
  echo 'tyle type = "text/css">
	a:link { text-decoration : none; color : #3366cc; border: 0px;}
	a:active { text-decoration : underline; color : #3366cc; border: 0px;}
	a:visited { text-decoration : none; color : #3366cc; border: 0px;}
	a:hover { text-decoration : underline; color : #ff5a00; border: 0px;}
	img { padding: 0px; margin: 0px; border: none;}
	body
	{
		margin : 0 auto;
		width:100%;
';
  echo '
		font-family: \'Verdana\';
		color: #40454b;
		font-size: 12px;
	}
	.text
	{
		color:#006600;
		font-weight:bold;
	}
	</style>	
	';
  echo '<s';
  echo 'cript type="text/javascript">
	<!--
	var message="';
  echo $lang->global['notavailable'];
  echo '";
	function clickIE4(){
	if (event.button==2){
	alert(message);
	return false;
	}
	}
	function clickNS4(e){
	if (document.layers||document.getElementById&&!document.all){
	if (e.which==2||e.which==3){
	alert(message);
	return false;
	}
	}
	}
	if (document.layers){
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS4;
	}
	else if (document.all&&!document.getE';
  echo 'lementById){
	document.onmousedown=clickIE4;
	}
	document.oncontextmenu=new Function("alert(message);return false")
	// --> 
	</script>
	</head>

	<body>		
		<table border="0" cellpadding="3" align="center">
			<tr>
				<td colspan="2" align="center">
					<p style="width:540px; background: #ffff99; text-align: left; color: #000; border: 1px solid #ff9900; padding: 5px; font-size: 12px; font-';
  echo 'weight: bold;">';
  echo sprintf ($lang->donate['systemmessage'], $SITENAME);
  echo '</p>
				</td>
			</tr>
			<tr>
				<td>
					';
  echo '<s';
  echo 'pan class="text">';
  echo $lang->donate['received'];
  echo ':
				</td>
				<td>
					';
  echo number_format ($funds['funds_so_far'], 2);
  echo '</span> ';
  echo '<s';
  echo 'pan class="progressBar" id="element1">';
  echo $Progress_so_far;
  echo '%</span>
				</td>
			</tr>
			<tr>
				<td>
					';
  echo '<s';
  echo 'pan class="text">';
  echo $lang->donate['targetamount'];
  echo ':
				</td>
				<td>
					';
  echo number_format ($GLOBALS['PAYPAL']['tn'], 2);
  echo '</span>
				</td>
			</tr>			
			<tr>
				<td>
					';
  echo '<s';
  echo 'pan class="text">';
  echo $lang->donate['stilltogo'];
  echo ':
				</td>
				<td>
					';
  echo number_format ($funds_difference);
  echo '</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<p style="width:540px; background: #ffff99; text-align: center; color: #000; border: 1px solid #ff9900; padding: 5px; font-size: 12px; font-weight: bold;">';
  echo sprintf ($lang->donate['clicktodonate'], $BASEURL);
  echo '</p>
				</td>
			</tr>
		</table>
	</body>
</html>';
?>
