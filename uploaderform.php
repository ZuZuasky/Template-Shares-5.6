<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/
require_once('global.php');
gzip();
dbconn();
loggedinorreturn();
maxsysop ();
define("VERSION","Request to be Uploader  v.0.8");

$lang->load('uploaderform');
$upreq = 5;

if ($CURUSER["downloaded"] > 0)
		$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
	else if ($CURUSER["uploaded"] > 0)
		$ratio = 1;
	else
		$ratio = 0;

$upreqn = $upreq * 1073741824;
$upreqm=$CURUSER['uploaded']>=$upreqn;
$action = isset($_POST['action']) ? htmlspecialchars($_POST['action']) : (isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'showform');
$allowed_actions = array('showform','sendform');
if (!in_array($action, $allowed_actions))
	$action = 'showform';

function showerror($msg) {
	global $lang;
	stdmsg($lang->global['error'], $msg);
	stdfoot();
	die;
}

stdhead($lang->uploaderform['head']);

if ($action == 'sendform'){

	if ($_POST['rbseed'] != '1' || $_POST['rbstime'] != '1' | $_POST['agree'] != 'yes')
		showerror(sprintf($lang->uploaderform['failed'],$SITENAME));

	$userid = 0+$_POST['userid'];
	$username = trim($_POST['username']);
	$joindate = trim($_POST['joined']);
	$ratio = trim($_POST['ratio']);
	$upk = trim($_POST['upk']);
	$plan = trim($_POST['plans']);
	$comment = trim($_POST['comment']);
	$subject = sqlesc(sprintf($lang->uploaderform['subject'],$username));

	if (empty($plan) || empty($comment))
		showerror($lang->global['dontleavefieldsblank']);

	$added = "'" . get_date_time() . "'";
    $body = sprintf($lang->uploaderform['body'], $username, $BASEURL, $userid, $joindate, ($ratio == 'ok' ? '[color=Green]'.$lang->uploaderform['yes'].'[/color]' : '[color=DarkRed]'.$lang->uploaderform['no'].'[/color]'), $upreq, ($upk == 'yes' ? '[color=Green]'.$lang->uploaderform['yes'].'[/color]' : '[color=DarkRed]'.$lang->uploaderform['no'].'[/color]'), $username, $plan, $comment);

    sql_query("INSERT INTO staffmessages (sender, added, msg, subject) VALUES($userid, $added, ".sqlesc($body).", $subject)") or sqlerr(__FILE__, __LINE__);
    stdmsg($lang->global['success'], $lang->uploaderform['done']);
    stdfoot();
    exit();
}elseif ($action == 'showform') {
	print("<h2>".$lang->uploaderform['head']."</h2>");
	print("<table border='1' cellspacing='0' cellpadding='5' width='100%'>");

echo '<form method=\'post\' action=\'uploaderform.php\'>
<input type=\'hidden\' name=\'action\' value=\'sendform\'>
<input name=\'userid\' type=\'hidden\' value=\''.$CURUSER['id'].'\'>
<input name=\'username\' type=\'hidden\' value=\''.$CURUSER['username'].'\'>';

	tr($lang->uploaderform['user'],"&nbsp;&nbsp;".$CURUSER['username'],1);
	tr($lang->uploaderform['joindate'],"&nbsp;&nbsp;<input name='joined' type='hidden' value='".$CURUSER['added']."'>".$CURUSER['added'],1);
	tr($lang->uploaderform['ratio'],"&nbsp;&nbsp;<input name='ratio' type='hidden' value='".($ratio>=1?"ok":"not ok")."'>".($ratio>=1?"<font color=green>".$lang->uploaderform['yes']."</font>":"<font color=red>".$lang->uploaderform['no']."</font>"),1);
	$upreqm=$CURUSER['uploaded']>=$upreqn;
	tr(sprintf($lang->uploaderform['transfer'], $upreq),"&nbsp;&nbsp;<input name='upk' type='hidden' value='".($upreqm?"yes":"no")."'>".($upreqm?"<font color=green>".$lang->uploaderform['yes']."</font>":"<font color=red>".$lang->uploaderform['no']."</font>"),1);
	tr($lang->uploaderform['plan'],"<textarea name='plans' cols='60' rows='2' wrap='VIRTUAL' id='specialboxg'></textarea>",1);
	tr($lang->uploaderform['comment'],"<textarea name='comment' cols='60' rows='2' wrap='VIRTUAL' id='specialboxg'></textarea>",1);

echo '<tr><td colspan=\'2\' align=\'left\'>
<p><b>'.$lang->uploaderform['rule1'].'</b><br />
<input type=\'radio\' name=\'rbseed\' value=\'1\'>
 '.$lang->uploaderform['yes'].'<br />
 <input name=\'rbseed\' type=\'radio\' value=\'0\' checked>
 '.$lang->uploaderform['no'].'</p>
 </td></tr>
 <tr><td colspan=\'2\' align=\'left\'>
 <p><b>'.$lang->uploaderform['rule2'].'</b><br />
<input type=\'radio\' name=\'rbstime\' value=\'1\'>
'.$lang->uploaderform['yes'].'<br />
<input name=\'rbstime\' type=\'radio\' value=\'0\' checked>
'.$lang->uploaderform['no'].'</p>
 </td></tr>
 <tr><td colspan=\'2\' align=\'left\'>
 <p>
 '.$lang->uploaderform['rule3'].'
  </p>
 </td></tr>
<tr><td colspan=\'2\' align=\'center\'><input type=\'checkbox\' name=\'agree\' value=\'yes\'><b>'.$lang->uploaderform['agree'].'</b> <input type=\'submit\' name=\'Submit\' class=button\' value=\''.$lang->global['buttonsave'].'\'></form></td></tr></table>';
}
stdfoot();
?>
