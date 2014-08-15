<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_misc_errors ()
  {
    global $error;
    global $lang;
    if (0 < count ($error))
    {
      $errors = implode ('<br />', $error);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  define ('TSF_FORUMS_TSSEv56', true);
  define ('NcodeImageResizer', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $error = array ();
  if ($action == 'markread')
  {
    if ((isset ($_GET['fid']) AND is_valid_id ($_GET['fid'])))
    {
      $fid = intval ($_GET['fid']);
      if (!$fid)
      {
        stderr ($lang->global['error'], $lang->tsf_forums['invalidfid']);
      }

      require_once INC_PATH . '/functions_cookies.php';
      ts_set_array_cookie ('forumread', $fid, TIMENOW);
      redirect ('tsf_forums/forumdisplay.php?fid=' . $fid, $lang->tsf_forums['markforumread']);
      exit ();
    }
    else
    {
      if ($CURUSER['id'] != 0)
      {
        sql_query ('UPDATE users SET last_forum_visit = \'' . TIMENOW . '\' WHERE id = ' . sqlesc ($CURUSER['id']));
      }

      redirect ('tsf_forums/index.php', $lang->tsf_forums['markforumsread']);
      exit ();
    }
  }

  if ($action == 'print_thread')
  {
    $threadid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
    if (!is_valid_id ($threadid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    ($query = sql_query ('SELECT 
			t.tid, t.subject, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($threadid) . ' LIMIT 1') OR sqlerr (__FILE__, 74));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no')))
    {
      print_no_permission (true);
      exit ();
    }

    $query = sql_query ('
				SELECT p.*, u.username
				FROM ' . TSF_PREFIX . 'posts p
				LEFT JOIN users u ON (p.uid=u.id)
				WHERE tid = ' . sqlesc ($threadid) . '
				ORDER BY dateline ASC
			');
    echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" />
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=' . $charset . '" />
	<style type="text/css">
		<!--
		td, p, li, div
		{
			font: 10pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
		}
		.smalltext
		{
			font-size: 11px;
		}
		-->
	</style>
	<title>Powered by ' . VERSION . ' &copy; ' . date ('Y') . ' ' . $SITENAME . '</title>		
	</head>
	<body>
	<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td>
		<script type="text/javascript">
			function ts_print_page()
			{
				window.print();  
			}
		</script>
		<b>' . $lang->tsf_forums['pthread'] . ':</b> <a href="' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $threadid . '">' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $threadid . '</a> <input type="button" value="' . $lang->tsf_forums['pthread'] . '" onClick="ts_print_page()" class="smalltext">
		<hr /></td>
	</tr>
	';
    while ($post = mysql_fetch_assoc ($query))
    {
      $reviewpostdate = my_datee ($dateformat, $post['dateline']) . ' ' . my_datee ($timeformat, $post['dateline']);
      $reviewmessage = format_comment ($post['message'], true, true, true, false);
      echo '
		<tr>
			<td>
				<span class="smalltext"><strong>' . $lang->tsf_forums['posted_by'] . ' ' . $post['username'] . ' - ' . $reviewpostdate . '</strong></span><hr />
			</td>
		</tr>
		<tr>
			<td>
				' . $reviewmessage . '
			</td>
		</tr>';
    }

    echo '</table>
	</body>
	</html>';
    exit ();
  }

  if ($action == 'email_thread')
  {
    $threadid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
    if (!is_valid_id ($threadid))
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    ($query = sql_query ('SELECT 
			t.tid, t.subject, f.type, f.fid as currentforumid, ff.fid as deepforumid 
			FROM ' . TSF_PREFIX . 'threads t 			
			LEFT JOIN ' . TSF_PREFIX . 'forums f ON (f.fid=t.fid)
			LEFT JOIN ' . TSF_PREFIX . 'forums ff ON (ff.fid=f.pid)
			WHERE t.tid = ' . sqlesc ($threadid) . ' LIMIT 1') OR sqlerr (__FILE__, 171));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
      exit ();
    }

    $thread = mysql_fetch_assoc ($query);
    $forummoderator = is_forum_mod (($thread['type'] == 's' ? $thread['deepforumid'] : $thread['currentforumid']), $CURUSER['id']);
    if (((!$moderator AND !$forummoderator) AND ($permissions[$thread['deepforumid']]['canview'] == 'no' OR $permissions[$thread['deepforumid']]['canviewthreads'] == 'no')))
    {
      print_no_permission (true);
      exit ();
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $femail = trim ($_POST['femail']);
      $tmsg = trim ($_POST['tmsg']);
      $tsubject = trim ($_POST['tsubject']);
      $fname = trim ($_POST['fname']);
      if ((((((!check_email ($femail) OR empty ($tmsg)) OR strlen ($tmsg) < 10) OR empty ($tsubject)) OR strlen ($tsubject) < 3) OR empty ($fname)))
      {
        $error[] = $lang->global['dontleavefieldsblank'];
      }

      if (count ($error) == 0)
      {
        $m_body = sprintf ($lang->tsf_forums['tmsgs'], $fname, $CURUSER['username'], $CURUSER['email'], $SITENAME, $BASEURL . '/tsf_forums/', htmlspecialchars_uni ($tmsg));
        $m_subject = htmlspecialchars_uni ($tsubject);
        sent_mail ($femail, $m_subject, $m_body, 'email_thread', false);
        header ('' . 'Location: ' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $threadid);
        exit ();
      }
    }

    stdhead ($lang->tsf_forums['ethreadh']);
    show_misc_errors ();
    echo '
	<form method="post" name="email_thread" action="' . $_SERVER['SCRIPT_NAME'] . '?action=email_thread&tid=' . $threadid . '" ' . submit_disable ('email_thread', 'tbutton') . '>
	<table width="100%" border="0" cellpadding="5" celspecing="5">
		<tr>
			<td class="thead" align="center" colspan="2">' . $lang->tsf_forums['ethreadh'] . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">
				<b>' . $lang->tsf_forums['fname'] . '</b>
			</td>
			<td align="left" width="80%" valign="top">
				<input type="text" name="fname" value="' . htmlspecialchars_uni ($fname) . '" size="30">
			</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">
				<b>' . $lang->tsf_forums['femail'] . '</b>
			</td>
			<td align="left" width="80%" valign="top">
				<input type="text" name="femail" value="' . htmlspecialchars_uni ($femail) . '" size="30">
			</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">
				<b>' . $lang->tsf_forums['tsubject'] . '</b>
			</td>
			<td align="left" width="80%" valign="top">
				<input type="text" name="tsubject" value="' . htmlspecialchars_uni (($tsubject ? $tsubject : $thread['subject'])) . '" size="30">
			</td>
		</tr>
		<tr>
			<td align="right" width="20%" valign="top">
				<b>' . $lang->tsf_forums['tmsg'] . '</b>
			</td>
			<td align="left" width="80%" valign="top">
				<textarea name="tmsg" cols="100" rows="10">' . ($tmsg ? htmlspecialchars_uni ($tmsg) : sprintf ($lang->tsf_forums['tmsgh'], $BASEURL . '/tsf_forums/showthread.php?tid=' . $threadid, htmlspecialchars_uni ($CURUSER['username']))) . '</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="' . $lang->global['buttonsend'] . '" name="tbutton"> <input type="reset" value="' . $lang->tsf_forums['button_2'] . '">
		</tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

?>
