<?

/***********************************************/

/*=========[TS Special Edition v.5.6]==========*/

/*=============[Special Thanks To]=============*/

/*        DrNet - wWw.SpecialCoders.CoM        */

/*          Vinson - wWw.Decode4u.CoM          */

/*    MrDecoder - wWw.Fearless-Releases.CoM    */

/*           Fynnon - wWw.BvList.CoM           */

/***********************************************/





  function show_shoutbox_commands ()

  {

    echo '

	<table class=main border=0 cellspacing=0 cellpadding=0 width=100%><tr><td class=embedded>

	<table width=100% border=1 cellspacing=0 cellpadding=10>

	<table class="none" border="0" cellpadding="4" cellspacing="0" width="100%">



	<tr>

	<td class="thead" colspan="10" align="center"><strong><font color=#FF0000>SHOUTBOX COMMANDS - USE THE COPY AND PASTE FUNCTION TO COPY COMMAND INTO SHOUTBOX</a></font></strong></td>

	</tr>



	<tr>

	<td align="center"><b>Prune delets all text within the shoutbox with no chance of recover.  Use wisely. To use type /prune</b></td></td>

	<td align="center"><b>Pruneshout will delete a single word throughout the shout or a group of words. See Example #1 Below. </b></td>

	<td align="center"><b>Shouts a notice to all users within the shoutbox. To use type [/notice your text here without the brackets]</b></td>

	<td align="center"><b>Ban will ban a user from the shoutbox till you unban them. To use type /ban [username here without the brackets]</b></td>

	<td align="center"><b>Unban will unban a user from the shoutbox giving them access to shout again. To use type /unban [username here without the brackets]</b></td>

	</tr>



	<tr>

	<td align="center"><b><input type="text" size="20" value="/prune"></b></td>

	<td align="center"><b><input type="text" size="20" value="/pruneshout"></b></td>

	<td align="center"><b><input type="text" size="20" value="/notice"></b></td>

	<td align="center"><b><input type="text" size="20" value="/ban"></b></td>

	<td align="center"><b><input type="text" size="20" value="/unban"></b></td>

	</tr>



	<tr>

	<td align="center"><b>Warn will give a user a warning for not following the rules of the shoutbox. To use type /warn [username here without the brackets]</b></td></td>

	<td align="center"><b>Unwarn will remove a user a warning allowing them to use the shoutbox again. To use type /unwarn [username here without the brackets]</b></td>

	<td align="center"><b>Status show the details of the user within the shoutbox. To use type /status [username here without the brackets]</b></td>

	<td align="center"><b>Pruneshout will delete a single word. To use type [/pruneshout hello] which will delete the word hello throughout the shoutbox</b></td>

	<td align="center"><b>Pruneshout will delete a phrase of words. To use type [/pruneshout hello how is everyone] which will delete the phrase "hello how is everyone" from the shoutbox</b></td>

	</tr>



	<tr>

	<td align="center"><b><input type="text" size="20" value="/warn"></b></td>

	<td align="center"><b><input type="text" size="20" value="/unwarn"></b></td>

	<td align="center"><b><input type="text" size="20" value="/status"></b></td>

	<td align="center"><b><input type="text" size="20" value="example#1"></b></td>

	<td align="center"><b><input type="text" size="20" value="example#2"></b></td>

	</tr>



	</table><br />';

  }



  define ('AS_VERSION', '2.1.7 by xam');

  define ('SKIP_LOCATION_SAVE', true);

  define ('DEBUGMODE', false);



  $rootpath = './../';

  require_once $rootpath . 'global.php';

  if (!defined ('IN_SCRIPT_TSSEv56'))

  {

    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');

  }



  gzip ();

  dbconn (false, true, false);

  $is_mod = is_mod ($usergroups);

  if ((!$CURUSER OR $usergroups['canshout'] != 'yes'))

  {

    exit ('<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">' . $lang->global['shouterror'] . '</div>');

  }



  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');

  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');

  header ('Cache-Control: no-cache, must-revalidate');

  header ('Pragma: no-cache');

  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Pragma" content="no-cache">

<meta http-equiv="expires" content="0">

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="text/html; charset=';

  echo $shoutboxcharset;

  echo '">

<link rel="stylesheet" href="';

  echo $BASEURL;

  echo '/include/templates/';

  echo $defaulttemplate;

  echo '/style/style.css" type="text/css" media="screen" />

<link rel="stylesheet" href="';

  echo $BASEURL;

  echo '/shoutbox/shoutbox.css" type="text/css" media="screen">

';

  echo '<s';

  echo 'cript type="text/javascript">

<!--

var baseurl="';

  echo htmlspecialchars ($BASEURL);

  echo '"

var dimagedir="';

  echo $BASEURL;

  echo '/';

  echo $pic_base_url;

  echo '"

var charset="';

  echo $shoutboxcharset;

  echo '"

var invites="';

  echo ($CURUSER ? (int)$CURUSER['invites'] : 'Login First');

  echo '"

var bonus="';

  echo ($CURUSER ? (int)$CURUSER['seedbonus'] : 'Login First');

  echo '"

var username="';

  echo ($CURUSER ? $CURUSER['username'] : 'Guest');

  echo '"

var userid="';

  echo ($CURUSER ? (int)$CURUSER['id'] : 'Guest_' . rand (1000, 9999));

  echo '"

var userip="';

  echo htmlspecialchars ($_SERVER['REMOTE_ADDR']);

  echo '"

// -->

</script>

';

  if (isset ($_GET['show_shoutbox_commands']))

  {

    if ($is_mod)

    {

      show_shoutbox_commands ();

    }



    exit ();

  }



  if (((isset ($_GET['popupshoutbox']) AND $_GET['popupshoutbox'] == 'yes') AND $is_mod))

  {

    $popupshoutbox = true;

    echo '

	<script type="text/javascript">

		function show_smilies()

		{

			if (document.getElementByID)

			{

				stdBrowser = true;

			}

			else

			{

				stdBrowser = false;

			}



			if (stdBrowser || navigator.appName != "Microsoft Internet Explorer")

			{

				if (document.getElementById(\'show_TSsmilies\').style.display == \'none\')

				{

					document.getElementById(\'show_TSsmilies\').style.display = \'block\';

				}

				else

				{

					document.getElementById(\'show_TSsmilies\').style.display = \'none\';

				}

			}

			else

			{

				if (document.all[\'show_TSsmilies\'].style.display == \'none\')

				{

					document.all[\'show_TSsmilies\'].style.display = \'block\';

				}

				else

				{

					document.all[\'show_TSsmilies\'].style.display = \'none\';

				}

			}

		}

		var popupshoutbox = "yes";

		var message="";

		function clickIE() {if (document.all) {(message);return false;}}

		function clickNS(e) {if

		(document.layers||(document.getElementById&&!document.all)) {

		if (e.which==2||e.which==3) {(message);return false;}}}

		if (document.layers)

		{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}

		else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}

		document.oncontextmenu=new Function("return false");

		function jumpto(url,message)

		{

			if (typeof message != "undefined")

			{

				document.getElementById("jumpto").style.display = "block";

			}

			window.location = url;

		};

	</script>';

  }

  else

  {

    echo '

	<script type="text/javascript">

		var popupshoutbox = "no"

	</script>';

  }



  echo '

<div id="loading-layer" name="loading-layer" style="position: absolute; display:none; left:300px; top:110px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000"><div style="font-weight:bold" id="loading-layer-text" class="small">' . $lang->global['loading'] . '</div><br /><img src="' . $BASEURL . '/' . $pic_base_url . 'await.gif" border="0" /></div>';

  echo '<s';

  echo 'cript language="javascript" type="text/javascript" src="shoutbox.js';

  echo '?v=' . O_SCRIPT_VERSION;

  echo '"></script>

<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

</head>

<body>

<br />

';

  if ((($CURUSER OR $is_mod AND is_valid_id ($_GET['id'])) AND $_GET['do'] == 'edit'))

  {

    $id = intval ($_GET['id']);

    $query = @sql_query ('SELECT content FROM shoutbox WHERE id = ' . @sqlesc ($id));

    if (mysql_num_rows ($query) == 0)

    {

      exit ();

    }

    else

    {

      $shout = mysql_fetch_assoc ($query);

    }



    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);

    echo '

	<script type="text/javascript">

		ChangeLayer("none");

	</script>

	<br />

	<center>

	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . (($popupshoutbox AND $is_mod) ? '?popupshoutbox=yes' : '') . '">

	<input type="hidden" name="do" value="save">

	<input type="hidden" name="id" value="' . $id . '">

	<textarea id="shoutbox" name="shouter_comment" maxlength="250" rows="5">' . htmlspecialchars_uni ($shout['content']) . '</textarea>

	<br /><p>

	<input type="submit" value="' . $lang->global['buttonsave'] . '" class=button>  <input type="reset" value="' . $lang->global['buttonclear'] . '" class=button></p>

	</center>

	';

    exit ();

  }

  else

  {

    if ((($CURUSER OR $is_mod AND is_valid_id ($_POST['id'])) AND $_POST['do'] == 'save'))

    {

      $id = intval ($_POST['id']);

      $content = $_POST['shouter_comment'];

      @sql_query ('UPDATE shoutbox SET content = ' . @sqlesc ($content) . ' WHERE id = ' . @sqlesc ($id));

      if (!$popupshoutbox)

      {

        echo '

		<p class="date"><strong>Update successfull.. <br />This window will be closed automaticly in a few seconds...</strong></p>

		<script type="text/javascript">

			setInterval("window.close()",3000);

			opener.location.reload();

		</script>';

      }

      else

      {

        header ('Location: shoutbox.php?popupshoutbox=yes');

      }



      exit ();

    }

    else

    {

      if ((($CURUSER OR $is_mod AND is_valid_id ($_GET['id'])) AND $_GET['do'] == 'delete'))

      {

        $id = intval ($_GET['id']);

        @sql_query ('DELETE FROM shoutbox WHERE id = ' . @sqlesc ($id));

        if (!$popupshoutbox)

        {

          echo '

		<p class="date"><strong>Shout has been deleted..<br />This window will be closed automaticly in a few seconds...</strong></p>

		<script type="text/javascript">

			setInterval("window.close()",3000);

			opener.location.reload();

		</script>';

        }

        else

        {

          header ('Location: shoutbox.php?popupshoutbox=yes');

        }



        exit ();

      }

    }

  }



  if ((isset ($_GET['popupshoutbox']) AND $_GET['popupshoutbox'] == 'yes'))

  {

    echo '

	<script language=javascript type=text/javascript>

		function stopRKey(evt)

		{

			var evt = (evt) ? evt : ((event) ? event : null);

			var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);

			if ((evt.keyCode == 13) && (node.type=="text"))

			{

				alert("' . $lang->global['noenter'] . '");

				return false;

			}

		};



		document.onkeypress = stopRKey;

	</script>

	<form id="shout" name="shoutbox">

		<p align="center">

			<input maxlength="250" name="shouter_comment" type="text" id="shoutbox" />

			<input type="button" value="' . $lang->global['buttonshout'] . '" class=button onClick="saveData(); return false;">

			<input type="reset" value="' . $lang->global['buttonclear'] . '" class=button>

			<input type="button" value="' . strtolower ($lang->global['smilies']) . '" onclick="show_smilies()" class=button>

			<input type="button" class=button name="refresh" onClick="javascript:location.reload(true);" value="' . $lang->global['refresh'] . '">

		</p>

	</form>

	';

    require_once $rootpath . '/' . $cache . '/smilies.php';

    $defaultsmilies = $smilies;

    $smilies = '

	<span style="display: none;" id="show_TSsmilies">

	<table width="100%" border="0">

		<tr>';

    $count = 0;

    foreach ($defaultsmilies as $code => $url)

    {

      $code = addslashes ($code);

      $url = addslashes ($url);

      $url = htmlspecialchars_uni ($url);

      if ($count < 70)

      {

        $smilies .= '' . '<img src="' . $BASEURL . '/' . $pic_base_url . 'smilies/' . $url . '" alt="' . $code . '" onclick="SmileIT(\'' . $code . '\',\'shoutbox\',\'shouter_comment\');" style="cursor: pointer;">

';

        ++$count;

        continue;

      }

    }



    $smilies .= '</tr></table></span>';

    echo $smilies;

  }



echo '

 '.$unread.'

<table width="100%" border="0">

        <tr>

                <span id="errorarea" align="left" class="small"></span>

                <td id="shoutbox_frame" align="left" class="small"></td>

        </tr>

</table>

</body>

</html>';

?>
