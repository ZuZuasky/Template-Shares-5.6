<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_error ()
  {
    global $errormessage;
    if (!empty ($errormessage))
    {
      echo '	
		<table width="100%" border="0" class="none" style="clear: both;" cellpadding="4" cellspacing="0">
			<tr><td class="thead">An error has occcured!</td></tr>
			<tr><td><font color="red"><strong>' . $errormessage . '</strong></font></td></tr>
			</table>
		<br />';
    }

  }

  function form ()
  {
    global $waitbeforeredirect;
    global $max_results;
    global $sgids;
    global $_this_script_;
    echo '<form method="post" action="';
    echo $_this_script_;
    echo '" name="massmail" onsubmit="document.massmail.submit.value=\'Please wait ...\';document.massmail.submit.disabled=true">
<input type="hidden" name="action" value="sent">
<table cellspacing="0" cellpadding="4" border="0" align="center" width="100%">
	 <tr>
		<td colspan="2" class="thead" align="center">Mass Mail to Tracker Users</td>
	</tr>		
	<tr>
	  <td align="right" style="color:black"><b>Sleep Time ';
    echo '(in seconds)</b></td>
	  <td><input type="text" name="waitbeforeredirect" maxlength="100" id="specialboxes" value="30" /> Wait X seconds and post next part. Leave this high to better performance.</td>
	</tr>
	<tr>
	  <td align="right" style="color:black"><b>Total</b></td>
	  <td><input type="text" name="max_results" maxlength="100" id="specialboxes" value="10" /> Post X mails per job. Leave this low to be';
    echo 'tter performance.</td>
	</tr>
	<tr>
	  <td align="right" style="color:black"><b>Recipients</b></td>
	  <td align="left" style="color:black">';
    echo $sgids;
    echo '</td>
	</tr>
	<tr>
	  <td align="right" style="color:black"><b>Subject</b></td>
	  <td><input type="text" name="subject" maxlength="100" id="specialboxg" value="" style="width: 744px;" /></td>
	</tr>
	<tr>
	  <td align="right" valign="top" style="color:black"> ';
    echo '<s';
    echo 'pan class="gen"><b>Message</b></span>
	  <td><textarea style="height: 250px; width: 750px;" name="message" id="message"></textarea>
	</tr>	
	<tr>
	  <td align="center" colspan="2"><input type="submit" value="post mail" name="submit" /> <input type="reset" value="reset" name="reset" /></td>
	</tr>
</table>
</form>
';
    echo '<s';
    echo 'cript>
	(function() {
		var Dom = YAHOO.util.Dom,
			Event = YAHOO.util.Event;
		
		var myConfig = {
			height: "300px",
			width: "600px",
			dompath: true,
			focusAtStart: true,
			handleSubmit: true
		};
		
		var myEditor = new YAHOO.widget.Editor("message", myConfig);
		myEditor._defaultToolbar.buttonType = "basic";
		myEditor.render();
		
	})();
</script>
';
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  set_time_limit (0);
  define ('M_VERSION', 'Mass Mail v.2.6 by xam');
  if ((isset ($_GET['do']) AND $_GET['do'] == 'stop'))
  {
    $action = 'start';
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $filename = TSDIR . '/' . $cache . '/massmail_config.php';
    if ((!file_exists ($filename) OR !is_writable ($filename)))
    {
      stderr ('Error', '<b>' . $filename . '</b> doesn\'t exists or isn\'t writable!', false);
    }

    $waitbeforeredirect = intval ($_POST['waitbeforeredirect']);
    $max_results = intval ($_POST['max_results']);
    $mmusergroups = implode (',', $_POST['usergroup']);
    $subject = trim ($_POST['subject']);
    $message = trim ($_POST['message']);
    $page = 1;
    $contents = '<?php
if (!defined(\'M_VERSION\')) die(\'<font face="verdana" size="2" color="darkred"><b>Error!</b> Direct initialization of this file is not allowed.</font>\');

/** TS Generated Massmail Cache#9 - Do Not Alter
 * Cache Name: massmail_config
 * Generated: ' . gmdate ('r') . '
*/

$waitbeforeredirect = ' . $waitbeforeredirect . ';
$max_results = ' . $max_results . ';
$mmusergroups = "' . $mmusergroups . '";
$subject = "' . addslashes ($subject) . '";
$message = "' . addslashes ($message) . '";
?>';
    $save_config = file_put_contents ($filename, $contents);
    if (!$save_config)
    {
      stderr ('Error', 'I can\'t write contents into <b>' . $filename . '</b>! Please check permissions and try again!', false);
    }

    $action = 'sent';
  }
  else
  {
    if (((isset ($_GET['action']) AND $_GET['action'] == 'sent') AND isset ($_GET['page'])))
    {
      $action = 'sent';
      $page = intval ($_GET['page']);
      include_once TSDIR . '/' . $cache . '/massmail_config.php';
    }
    else
    {
      $action = 'start';
    }
  }

  $from = $page * $max_results - $max_results;
  $c = 0;
  $squery = sql_query ('SELECT gid, title, namestyle FROM usergroups');
  $scount = 1;
  $sgids = '
<fieldset>
	<legend>Select Usergroup(s)</legend>
		<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr>';
  while ($gid = mysql_fetch_assoc ($squery))
  {
    if ($scount % 5 == 1)
    {
      $sgids .= '</tr></td>';
    }

    $sgids .= '	
	<td class="none"><input type="checkbox" name="usergroup[]" value="' . $gid['gid'] . '"></td>
	<td class="none">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
    ++$scount;
  }

  $sgids .= '
<td class="none"></td>
<td class="none"><a href="#" onClick="check(massmail)"><font color="blue" size="1">check all</font></a></td>
</table>
</fieldset>';
  $externalpreview = '<div id=\'loading-layer\' style=\'position: absolute; display:block; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Sending... Please wait...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>';
  if ($action == 'sent')
  {
    if (((($subject == '' OR $message == '') OR $message == '<br />') OR empty ($mmusergroups)))
    {
      $errormessage = 'Subject or Message or Usergroups field can not be empty!';
      $action = 'start';
    }
    else
    {
      ($sql = sql_query ('SELECT COUNT(email) FROM users WHERE enabled=\'yes\' AND status=\'confirmed\' ' . ($mmusergroups == '-' ? '' : '' . 'AND usergroup IN (0,' . $mmusergroups . ')')) OR sqlerr (__FILE__, 189));
      $counter = mysql_fetch_row ($sql);
      $result = number_format ($counter['0']);
      if (0 < $result)
      {
        stdhead (VERSION . ' - SEND');
        echo '<table width=100% border=1><tr><td><p align=center><b>' . $result . '</b> emails found. We\'ll send <b>' . $max_results . '</b> messages at a time, then sleep for a while (<b>' . $waitbeforeredirect . '</b> sec.), then continue like this sending emails until done. </p><br /><br /><p align=center>Please wait...<br /><img src=' . $BASEURL . '/' . $pic_base_url . 'loadAnim.gif border=0><br />Sending mail...<br /><a href="' . $_this_script_ . '&do=stop">stop</a></p>';
        echo '</table>';
        ($emails = sql_query ('SELECT email FROM users WHERE enabled=\'yes\' AND status=\'confirmed\' ' . ($mmusergroups == '-' ? '' : '' . 'AND usergroup IN (0,' . $mmusergroups . ')') . ('' . ' LIMIT ' . $from . ', ' . $max_results)) OR sqlerr (__FILE__, 197));
        echo '<hr /><font color=red>Sending...</font><br /><hr />';
        include_once $rootpath . '/admin/include/staff_languages.php';
        while ($email = mysql_fetch_array ($emails))
        {
          echo 'Message to: ' . $email['email'] . ' => ';
          $to = $email['email'];
          $body = $adminlang['massmail']['header'] . '<br />
					<hr />
					<br />
					' . $message . '<br />
					<br />
					<hr /><br />
					' . $adminlang['massmail']['footer'];
          $msendmail = sent_mail ($to, $subject, $body, 'massmail', false);
          $mresult = ($msendmail ? '<font color="green">DONE!</font>' : '<font color="red">ERROR!</font>') . '<br />';
          echo $mresult;
          unset ($to);
          unset ($headers);
          unset ($msendmail);
          unset ($mresult);
          ++$c;
        }

        $total_results = mysql_result (@sql_query ('SELECT COUNT(*) FROM users WHERE enabled=\'yes\' AND status=\'confirmed\' ' . ($mmusergroups == '-' ? '' : '' . 'AND usergroup IN (0,' . $mmusergroups . ')')), 0);
        $total_pages = ceil ($total_results / $max_results);
        if ($page < $total_pages)
        {
          $next = $page + 1;
          echo '<br /><div id="waitmessage">Please wait...</div><br />';
          $jumpto = $_this_script_ . '&action=sent&page=' . $next;
          echo '				';
          echo '<s';
          echo 'cript language="javascript">
					x6115=';
          echo $waitbeforeredirect;
          echo ';
					function countdown() 
					{
						if ((0 <= 100) || (0 > 0))
						{
							x6115--;
							if(x6115 == 0)
							{
								document.getElementById("waitmessage").innerHTML = "';
          echo $externalpreview;
          echo '";
								jumpto(\'';
          echo $jumpto;
          echo '\');
							}
							if(x6115 > 0)
							{
								document.getElementById("waitmessage").innerHTML = \'Please wait <font size="3"><b>\'+x6115+\'</b></font> seconds..\';
								setTimeout(\'countdown()\',1000);
							}
						}
					}
					countdown();
				</script>
				';
          stdfoot ();
          exit ();
        }
        else
        {
          echo '<hr /><font color=red>We coo .... sent ' . $result . ' emails, to ' . $max_results . ' addys at a time</font><hr />';
          stdfoot ();
          exit ();
        }

        stdfoot ();
      }
      else
      {
        $errormessage = 'No email address found in database!';
        $action = 'start';
      }
    }
  }

  if ($action == 'start')
  {
    stdhead (VERSION . ' - START', true, '', '
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/element/element-beta-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/menu/menu-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/editor/editor-min.js"></script>', '
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/menu/assets/skins/sam/menu.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/editor/assets/skins/sam/editor.css" />');
    show_error ();
    form ();
    stdfoot ();
  }

?>
