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
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  $lang->load ('messages');
  define ('MSG_VERSION', '2.7.3');
  define ('NcodeImageResizer', true);
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  $mailboxes = array ('INBOX' => 1, 'SENDBOX' => 0 - 1, 'PMDELETED' => 0);
  $userid = (((isset ($_GET['userid']) AND is_valid_id ($_GET['userid'])) AND $usergroups['cansettingspanel'] == 'yes') ? intval ($_GET['userid']) : intval ($CURUSER['id']));
  $_errors = array ();
  $moderator = is_mod ($usergroups);
  $maxboxs = (!$moderator ? 3 : 6);
  if (isset ($_GET['mailbox']))
  {
    $mailbox = intval ($_GET['mailbox']);
  }
  else
  {
    $mailbox = $mailboxes['INBOX'];
  }

  $mailbox = ($mailbox == 0 ? $mailboxes['INBOX'] : $mailbox);
  require_once INC_PATH . '/functions_message.php';
  switch ($mailbox)
  {
    case '1':
    {
      $foldername = $lang->messages['inbox'];
      $folderid = $mailbox;
      break;
    }

    case '-1':
    {
      $foldername = $lang->messages['sendbox'];
      break;
    }

    default:
    {
      $query = sql_query ('' . 'SELECT name, boxnumber FROM pmboxes WHERE boxnumber = ' . $mailbox . ' AND userid = ' . $userid . ' LIMIT 1');
      if (0 < mysql_num_rows ($query))
      {
        $pmboxes = mysql_fetch_assoc ($query);
        $foldername = $pmboxes['name'];
        $foldername = (empty ($foldername) ? $lang->messages['Unknown'] : htmlspecialchars_uni ($foldername));
        $boxnumber = intval ($pmboxes['boxnumber']);
        $folderid = ($boxnumber == 0 ? $mailboxes['INBOX'] : $boxnumber);
        break;
      }
      else
      {
        $foldername = $lang->messages['inbox'];
        $folderid = $mailboxes['INBOX'];
      }
    }
  }

  if ((((!empty ($do) AND $do != 'showpm') AND $do != 'editfolders') AND $do != 'emptybox'))
  {
    $pmids = $_POST['pmid'];
    if ((!is_array ($pmids) OR count ($pmids) < 1))
    {
      unset ($do);
      $_errors[] = $lang->messages['newtitle9'];
    }
    else
    {
      foreach ($pmids as $checkid)
      {
        if (!is_valid_id ($checkid))
        {
          unset ($do);
          break;
        }
      }
    }
  }

  if ($do == 'editfolders')
  {
    if (($_SERVER['REQUEST_METHOD'] == 'POST' AND isset ($_POST['update'])))
    {
      if ((is_array ($_POST['customfolders']) AND 0 < count ($_POST['customfolders'])))
      {
        foreach ($_POST['customfolders'] as $Uboxnumber => $Uname)
        {
          if (empty ($Uname))
          {
            $deletepmids = array ();
            $deletepmbx = sql_query ('DELETE FROM pmboxes WHERE boxnumber=' . sqlesc ($Uboxnumber) . ('' . ' AND userid=' . $userid));
            if (mysql_affected_rows ())
            {
              $query = sql_query ('SELECT id FROM messages WHERE location = ' . sqlesc ($Uboxnumber));
              while ($Dpmids = mysql_fetch_assoc ($query))
              {
                $deletepmids[] = $Dpmids['id'];
              }

              if (0 < count ($deletepmids))
              {
                delete_pms ($deletepmids);
                continue;
              }

              continue;
            }

            continue;
          }
          else
          {
            if (is_valid_id ($Uboxnumber))
            {
              sql_query ('UPDATE pmboxes SET name = ' . sqlesc (trim ($Uname)) . ' WHERE boxnumber = ' . sqlesc ($Uboxnumber) . ('' . ' AND userid = ' . $userid));
              continue;
            }

            continue;
          }
        }
      }

      $i = 0;
      while (($i < $maxboxs AND $i < 3))
      {
        if ((!empty ($_POST['newfolder' . $i]) AND 2 < strlen ($_POST['newfolder' . $i])))
        {
          $newfolders[] = trim ($_POST['newfolder' . $i]);
        }

        ++$i;
      }

      $newfolderscount = count ($newfolders);
      if (0 < $newfolderscount)
      {
        $query = sql_query ('' . 'SELECT boxnumber FROM pmboxes WHERE userid = ' . $userid);
        $usedboxes = mysql_num_rows ($query);
        if (($maxboxs < $usedboxes OR $maxboxs < $usedboxes + $newfolderscount))
        {
          $_errors[] = sprintf ($lang->messages['newtitle32'], $maxboxs, $usedboxes);
        }
        else
        {
          $query = sql_query ('' . 'SELECT MAX(boxnumber) as lastboxnumber FROM pmboxes WHERE userid = ' . $userid);
          $lastboxnumber = mysql_result ($query, 0, 'lastboxnumber');
          if ((((!$lastboxnumber OR $lastboxnumber < 2) OR $lastboxnumber == 0) OR !is_valid_id ($lastboxnumber)))
          {
            $lastboxnumber = 1;
          }

          $i = 0;
          while ($i < $newfolderscount)
          {
            ++$lastboxnumber;
            sql_query ('' . 'INSERT INTO pmboxes (userid,boxnumber,name) VALUES (' . $userid . ',' . $lastboxnumber . ',' . sqlesc (trim ($newfolders[$i])) . ')');
            ++$i;
          }
        }
      }
    }

    $standartfolders = '
	<FIELDSET>
		<legend>' . $lang->messages['newtitle21'] . '</legend>
		<a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailboxes['INBOX'] . '">' . $lang->messages['inbox'] . '</a><br />
		<a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailboxes['SENDBOX'] . '">' . $lang->messages['sendbox'] . '</a>
	</FIELDSET>
	';
    $query = sql_query ('' . 'SELECT id,name,boxnumber FROM pmboxes WHERE userid = ' . $userid . ' ORDER by boxnumber LIMIT 0, ' . $maxboxs);
    if (0 < mysql_num_rows ($query))
    {
      $customfolderscount = 0;
      $customfolders = '
		<FIELDSET>
				<legend>' . $lang->messages['newtitle22'] . '</legend>';
      while ($cf = mysql_fetch_assoc ($query))
      {
        ++$customfolderscount;
        $customfolders .= '
				<p>' . $lang->messages['newtitle24'] . '<br /><input type="text" size="30" name="customfolders[' . $cf['boxnumber'] . ']" value="' . htmlspecialchars_uni ($cf['name']) . '" maxlength="14">';
      }

      $customfolders .= '
		<br />
		' . $lang->messages['newtitle25'] . '
		</FIELDSET>';
    }

    if ($customfolderscount < $maxboxs)
    {
      $shownewfolders = '
		<FIELDSET>
			<legend>' . $lang->messages['newtitle23'] . '</legend>';
      $i = 0;
      while (($i < $maxboxs AND $i < 3))
      {
        $shownewfolders .= '<p>' . $lang->messages['newtitle24'] . '<br /><input type="text" size="30" name="newfolder' . $i . '" value="" maxlength="14"></p>';
        ++$i;
      }

      $shownewfolders .= '
			' . $lang->messages['newtitle25'] . '
		</FIELDSET>
		';
    }

    stdhead ($lang->messages['newtitle19'], false);
    show_message_errors_ ();
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '" name="messageform">
	<input type="hidden" name="do" value="editfolders">
	<input type="hidden" name="update" value="yes">

	<table align="center" border="0" cellpadding="6" cellspacing="0" width="100%">
		<tr>
			<td class="thead" align="left">' . $lang->messages['newtitle19'] . '
		</tr>
		<tr>
			<td>' . $standartfolders . ' ' . $customfolders . ' ' . $shownewfolders . '</td>
		</tr>
		<tr><td align="center"><input type="submit" value="' . $lang->messages['newtitle20'] . '"> <input type="button" value="' . $lang->messages['newtitle17'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '\')"></td></tr>
	</table>
	</form>
	';
    stdfoot ();
    exit ();
  }

  if ($do == 'move')
  {
    if (($_SERVER['REQUEST_METHOD'] == 'POST' AND isset ($_POST['destination'])))
    {
      $newfolder = intval ($_POST['destination']);
      $query = sql_query ('' . 'SELECT name FROM pmboxes WHERE userid = ' . $userid . ' AND boxnumber = ' . $newfolder);
      if (((mysql_num_rows ($query) == 0 OR empty ($newfolder)) AND $newfolder != $mailboxes['INBOX']))
      {
        $_errors[] = sprintf ($lang->messages['newtitle14'], $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;do=editfolders&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']));
      }
      else
      {
        sql_query ('' . 'UPDATE messages SET location = ' . $newfolder . ' WHERE id IN (0,' . implode (',', $pmids) . ('' . ') AND receiver = ' . $userid));
        $mailbox = $newfolder;
      }
    }
    else
    {
      $query = sql_query ('' . 'SELECT boxnumber, name FROM pmboxes WHERE userid = ' . $userid . ' ORDER by boxnumber LIMIT 0, ' . $maxboxs);
      if (mysql_num_rows ($query) == 0)
      {
        $_errors[] = sprintf ($lang->messages['newtitle14'], $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;do=editfolders&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']));
      }
      else
      {
        $showbox = '<select name="destination">';
        if ($mailbox == $mailboxes['INBOX'])
        {
          while ($userboxes = mysql_fetch_assoc ($query))
          {
            $showbox .= '<option value="' . intval ($userboxes['boxnumber']) . '">' . htmlspecialchars_uni ($userboxes['name']) . '</option>';
          }
        }
        else
        {
          $showbox .= '<option value="' . intval ($mailboxes['INBOX']) . '">' . $lang->messages['inbox'] . '</option>';
        }

        $showbox .= '</select>';
        $hiddenvalues;
        foreach ($pmids as $pmid)
        {
          $hiddenvalues .= '<input type="hidden" name="pmid[]" value="' . intval ($pmid) . '">';
        }

        stdhead ($lang->messages['newtitle18'], false);
        echo '
			<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '" name="messageform">
			<input type="hidden" name="do" value="move">
			' . $hiddenvalues . '
			<table align="center" border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
					<td class="thead" align="left">' . $lang->messages['newtitle18'] . '
				</tr>
				<tr>
					<td>' . $lang->messages['newtitle15'] . '<br />' . $lang->messages['newtitle10'] . '<br />' . $showbox . '</td>
				</tr>
				<tr><td align="center"><input type="submit" value="' . $lang->messages['newtitle16'] . '"> <input type="button" value="' . $lang->messages['newtitle17'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '\')"></td></tr>
				</table>
				</form>
			';
        stdfoot ();
        exit ();
      }
    }
  }

  if ($do == 'delete')
  {
    $query = sql_query ('SELECT unread FROM messages WHERE id IN (0,' . implode (',', $pmids) . ') AND unread = \'yes\'');
    if (mysql_num_rows ($query) == 0)
    {
      delete_pms ($pmids);
    }
    else
    {
      $_errors[] = $lang->messages['newtitle30'];
    }
  }

  if ($do == 'markasread')
  {
    sql_query ('' . 'UPDATE messages SET unread = \'no\' WHERE receiver = ' . $userid . ' AND id IN (0,' . implode (',', $pmids) . ')');
  }

  if ($do == 'markasunread')
  {
    sql_query ('' . 'UPDATE messages SET unread = \'yes\' WHERE receiver = ' . $userid . ' AND id IN (0,' . implode (',', $pmids) . ')');
  }

  if ((($do == 'emptybox' AND isset ($_GET['sure'])) AND $_GET['sure'] == 'yes'))
  {
    if ($mailbox == 1)
    {
      (sql_query ('' . 'DELETE FROM messages WHERE receiver = ' . $userid . ' AND location = ' . $mailbox . ' AND unread = \'no\'') OR sqlerr (__FILE__, 307));
    }
    else
    {
      if ($mailbox == 0 - 1)
      {
        (sql_query ('' . 'DELETE FROM messages WHERE sender = ' . $userid . ' AND saved = \'yes\' AND unread = \'no\'') OR sqlerr (__FILE__, 311));
      }
      else
      {
        if (is_valid_id ($mailbox))
        {
          (sql_query ('' . 'DELETE FROM messages WHERE receiver = ' . $userid . ' AND location = ' . $mailbox . ' AND unread = \'no\'') OR sqlerr (__FILE__, 315));
        }
      }
    }
  }

  if ($do == 'showpm')
  {
    $quickmenu = '';
    include_once INC_PATH . '/functions_icons.php';
    $pmid = intval ($_GET['pmid']);
    if (empty ($pmid))
    {
      $_errors[] = $lang->messages['newtitle11'];
    }

    $res = sql_query ('SELECT * FROM messages WHERE id = ' . sqlesc ($pmid) . ' AND (receiver=' . sqlesc ($userid) . ' OR (sender=' . sqlesc ($userid) . ' AND saved=\'yes\')) LIMIT 1');
    if (mysql_num_rows ($res) == 0)
    {
      $_errors[] = $lang->messages['newtitle11'];
    }

    if (count ($_errors) == 0)
    {
      $message = mysql_fetch_assoc ($res);
      $subject = htmlspecialchars_uni ($message['subject']);
      $text = format_comment ($message['msg']);
      $reply = '';
      if ($message['sender'] == $CURUSER['id'])
      {
        ($res2 = sql_query ('SELECT u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle, g.title FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($message['receiver'])) OR sqlerr (__FILE__, 337));
        $sender = mysql_fetch_assoc ($res2);
        $username = $sender['username'];
        $joindate = $sender['added'];
        $lastseen = $sender['last_access'];
        if ((preg_match ('#B1#is', $sender['options']) AND !$moderator))
        {
          $lastseen = $sender['last_login'];
        }

        $susergroup = $sender['usergroup'];
        $stitle = $sender['title'];
        $sender = '' . '<a href="#" id="quickmenu' . $pmid . '">' . get_user_color ($username, $sender['namestyle']) . ' ' . get_user_icons ($sender) . ' </a>';
        $sender2 = $message['receiver'];
      }
      else
      {
        if ($message['sender'] == 0)
        {
          $sender = $lang->messages['system'];
        }
        else
        {
          ($res2 = sql_query ('SELECT u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle, g.title, g.cansettingspanel, g.canstaffpanel, g.issupermod FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($message['sender'])) OR sqlerr (__FILE__, 359));
          $sender = $arraysender = mysql_fetch_assoc ($res2);
          $username = $sender['username'];
          $joindate = $sender['added'];
          $lastseen = $sender['last_access'];
          if ((preg_match ('#B1#is', $sender['options']) AND !$moderator))
          {
            $lastseen = $sender['last_login'];
          }

          $susergroup = $sender['usergroup'];
          $stitle = $sender['title'];
          $sender = '' . '<a href="#" id="quickmenu' . $pmid . '">' . get_user_color ($username, $sender['namestyle']) . ' ' . get_user_icons ($sender) . ' </a>';
          $sender2 = $message['sender'];
          $replylink = '
				<input type="button" value="' . $lang->messages['reply'] . '" onclick="jumpto(\'' . $BASEURL . '/sendmessage.php?receiver=' . $message['sender'] . '&amp;replyto=' . $pmid . '\')">';
        }
      }

      switch ($susergroup)
      {
        case 0:
        {
        }

        case 1:
        {
        }

        case 2:
        {
          $png = 'rank_full_blank';
          break;
        }

        default:
        {
          $png = 'rank_star_blank';
          break;
        }
      }

      $image_hash = $_SESSION['image_hash'] = md5 (TIMENOW . $securehash);
      $image = ($message['sender'] != 0 ? '<img src=\'' . $BASEURL . '/include/class_user_title.php?str=' . base64_encode ($stitle) . '&png=' . base64_encode ($png) . '\' border=\'0\'>' : '');
      $forwardlink = '
				<input type="button" value="' . $lang->messages['forwardpm'] . '" onclick="jumpto(\'' . $BASEURL . '/sendmessage.php?receiver=' . $message['sender'] . '&amp;replyto=' . $pmid . '&amp;type=forward\')">';
      $deletelink = '
				<input type="submit" value="' . $lang->messages['newtitle4'] . '" onclick="return confirm_delete()">
				';
      $returnlink = '
				<input type="button" value="' . $lang->messages['newtitle31'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '\')">
				';
      if ($message['sender'] != 0)
      {
        $quickmenu .= '
			<div id="quickmenu' . $pmid . '_menu" class="menu_popup" style="display:none;">
				<table border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center" class="thead"><b>' . $lang->global['quickmenu'] . ' ' . (isset ($username) ? $username : '') . '</b></td>
					</tr>

					<tr>
						<td class="subheader"><a href="' . ts_seo ((isset ($sender2) ? $sender2 : ''), $username) . '">' . $lang->global['qinfo1'] . '</a></td>
					</tr>

					<tr>
						<td class="subheader"><a href="' . $BASEURL . '/sendmessage.php?receiver=' . (isset ($sender2) ? $sender2 : '') . '">' . sprintf ($lang->global['qinfo2'], $username) . '</td>
					</tr>

					<tr>
						<td class="subheader"><a href="' . $BASEURL . '/friends.php?action=add_friend&friendid=' . (isset ($sender2) ? $sender2 : '') . '">' . sprintf ($lang->global['qinfo5'], $username) . '</td>
					</tr>

					' . ($moderator ? '<tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=edituser&userid=' . $sender2 . '">' . $lang->global['qinfo6'] . '</a></td></tr><tr><td class="subheader"><a href="' . $BASEURL . '/admin/edituser.php?action=warnuser&userid=' . (isset ($sender2) ? $sender2 : '') . '">' . $lang->global['qinfo7'] . '</td></tr>' : '') . '
				</table>
				</div>';
      }

      if ($mailbox != $mailboxes['SENDBOX'])
      {
        (sql_query ('UPDATE messages SET unread=\'no\' WHERE id=' . sqlesc ($pmid)) OR sqlerr (__FILE__, 428));
        if ($message['unread'] == 'yes')
        {
          (sql_query ('' . 'UPDATE users SET pmunread = IF(pmunread > 0, pmunread - 1, 0) WHERE id = \'' . $userid . '\'') OR sqlerr (__FILE__, 431));
        }
      }

      $verified = '';
      if ((is_mod ($arraysender) OR $arraysender['id'] == 0))
      {
        $verified = show_notice ('
			<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center" style="border-bottom-width:0px">
				<tr>
					<td width="30" class="none"><img src="' . $BASEURL . '/' . $pic_base_url . 'verified.gif" width="27" height="32" alt="Verified!" /></td>
					<td class="none"><strong><font size="2" color="#339900">' . $lang->messages['verified1'] . '</font></strong><br /><font size="1">' . sprintf ($lang->messages['verified2'], $SITENAME) . '</font></td>
				</tr>
			</table>');
      }

      stdhead (strip_tags (sprintf ($lang->messages['newtitle12'], $subject)), false, 'supernote');
      echo '
		<script type="text/javascript">
		function confirm_delete()
		{
			var deletepm = confirm("' . $lang->messages['newtitle13'] . '")
			if (deletepm)
			{
				return true;
			}
			return false;
		}
		</script>
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ($_GET['page']) . '" name="messageform">
		<input type="hidden" name="do" value="delete">
		<input type="hidden" name="pmid[]" value="' . $pmid . '">
		' . $verified . '
		<table align="center" border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr>
				<td class="thead" align="left">' . sprintf ($lang->messages['newtitle12'], $subject) . '
			</tr>
			<tr>
				<td class="subheader" align="left">' . my_datee ($dateformat, $message['added']) . ' ' . my_datee ($timeformat, $message['added']) . '</td>
			</tr>
			<tr>
				<td align="left">
					<div>' . ($message['sender'] != 0 ? '
					<span style="float: right;" class="smallfont">' . sprintf ($lang->messages['qinfo8'], my_datee ($dateformat, $joindate)) . '</span>' : '') . '
					' . $sender . '
					</div>
					<div class="smalltext">' . ($message['sender'] != 0 ? '
					<span style="float: right;" class="smallfont">' . sprintf ($lang->messages['qinfo9'], my_datee ($dateformat, $lastseen), my_datee ($timeformat, $lastseen)) . '</span>' : '') . '
					' . $image . '
					</div>
				' . ($message['sender'] != 0 ? '
				<script type="text/javascript">
					menu_register("quickmenu' . $pmid . '");
				</script>' : '') . '
				</td>
			</tr>
			<tr>
				<td align="left">
				<strong>' . $subject . '</strong>
				<hr size="1">
				' . $text . '
				<br />
				<div>
					<span style="float: right;">' . (isset ($returnlink) ? $returnlink : '') . ' ' . (isset ($deletelink) ? $deletelink : '') . ' ' . (isset ($forwardlink) ? $forwardlink : '') . ' ' . (isset ($replylink) ? $replylink : '') . '</span>
				</div>
				</td>
			</tr>
		</table>
		</form>
		' . $quickmenu . ($message['sender'] != 0 ? '
		<script type="text/javascript">
			menu.activate(true);
		</script>' : '');
      stdfoot ();
      exit ();
    }
  }

  $eq = ($mailbox != $mailboxes['SENDBOX'] ? array ('m.sender', 'm.receiver', '') : array ('m.receiver ', 'm.sender', ' AND m.saved=\'yes\''));
  $count = mysql_num_rows (sql_query ('' . 'SELECT m.* FROM messages m WHERE ' . $eq[1] . '=' . $userid . ' ' . ($folderid ? '' . 'AND m.location=' . $folderid : '') . ('' . $eq[2])));
  list ($pagertop, $pagerbottom, $limit) = pager (15, $count, '' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;');
  include_once INC_PATH . '/functions_icons.php';
  stdhead (strip_tags (sprintf ($lang->messages['newtitle1'], $foldername)), false, 'supernote');
  echo pm_limit ();
  show_message_errors_ ();
  echo $pagertop;
  echo '
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailbox . '&amp;page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0)) . '" name="messageform">
<table align="center" border="0" cellpadding="6" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="left" class="thead">' . ts_collapse ('messages') . '
		' . sprintf ($lang->messages['newtitle1'], '<a href="#" id="quickmenu1" />' . $foldername . '</a>') . '</td>
		<td class="thead" align="center" width="1%"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'messageform\', this, \'group\');"></td>
	</tr>
	' . ts_collapse ('messages', 2) . '
';
  $str = '';
  ($query = sql_query ('' . 'SELECT m.*, u.username, u.id as senderid, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM messages m LEFT JOIN users u ON (u.id=' . $eq[0] . ') LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE ' . $eq[1] . '=' . $userid . ' ' . ($folderid ? '' . 'AND m.location=' . $folderid : '') . ('' . $eq[2] . ' ORDER by m.added DESC ' . $limit)) OR sqlerr (__FILE__, 527));
  if (0 < mysql_num_rows ($query))
  {
    if ($mailbox != $mailboxes['SENDBOX'])
    {
      ($QueryF = sql_query ('' . 'SELECT m.id FROM messages m WHERE m.unread = \'yes\' AND ' . $eq[1] . '=' . $userid . ' ' . ($folderid ? '' . 'AND m.location=' . $folderid : '') . ('' . $eq[2])) OR sqlerr (__FILE__, 532));
      (sql_query ('UPDATE users SET pmunread = \'' . mysql_num_rows ($QueryF) . ('' . '\' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 533));
    }

    while ($message = mysql_fetch_assoc ($query))
    {
      $sender = ($message['sender'] == 0 ? '<b>System<b>' : '<a href="' . ts_seo ($message['senderid'], $message['username']) . '">' . get_user_color ($message['username'], $message['namestyle']) . '</a> ' . get_user_icons ($message));
      $msgimg = ($message['unread'] == 'yes' ? 'unreadpm' : 'readpm');
      $imagetitle = (($message['unread'] == 'yes' AND $mailbox != $mailboxes['SENDBOX']) ? '' : (($message['unread'] == 'yes' AND $mailbox == $mailboxes['SENDBOX']) ? $lang->messages['newtitle29'] : ''));
      $msgtxtpreview = htmlspecialchars (mysql_real_escape_string (substr ($message['msg'], 0, 100))) . ' ...';
      $message['subject'] = ($message['unread'] == 'yes' ? '<strong>' . htmlspecialchars_uni ($message['subject']) . '</strong>' : htmlspecialchars_uni ($message['subject']));
      $str .= '
		<tr>
			<td width="1%"><img src="' . $BASEURL . '/' . $pic_base_url . $msgimg . '.gif" border="0" alt="' . $imagetitle . '" title="' . $imagetitle . '" /></td>
			<td>
				<div>
					<span style="float: right;" class="smallfont">' . my_datee ($dateformat, $message['added']) . '</span>
					<a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;do=showpm&amp;pmid=' . $message['id'] . '&amp;page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0)) . '&amp;mailbox=' . $mailbox . '" onmouseover="ddrivetip(\'' . $msgtxtpreview . '\', 600)"; onmouseout="hideddrivetip()">' . $message['subject'] . '</a>
					</div>
					<div class="smalltext">
					<span style="float: right;" class="time">' . my_datee ($timeformat, $message['added']) . '</span>
					' . $sender . '
				</div>
			</td>
			<td width="1%" style="padding: 0px;" align="center">
				<input name="pmid[]" value="' . $message['id'] . '" type="checkbox" checkme="group">
			</td>
		</tr>';
    }
  }
  else
  {
    $str .= '<tr><td colspan="3" align="left">' . sprintf ($lang->messages['newtitle8'], $foldername, 0) . '</td></tr>';
  }

  $str .= '
		<script type="text/javascript">
			function confirm_emptyfolder()
			{
				var emptyfolder = confirm("' . $lang->messages['newtitle34'] . '");
				if (emptyfolder)
				{
					jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&do=emptybox&mailbox=' . $mailbox . '&page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0)) . '&sure=yes\');
				}
				return false;
			}
		</script>
		<tr>
			<td colspan="3" class="subheader">
			<div>
			<span style="float:right;">
				' . $lang->messages['newtitle2'] . ' ' . action_box () . '
				<input type="submit" value="' . $lang->messages['newtitle7'] . '"></span>
				<input type="button" value="' . $lang->messages['newtitle28'] . '" onclick="jumpto(\'' . $BASEURL . '/sendmessage.php?action=compose\')">
				<input type="button" value="' . $lang->messages['newtitle27'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;do=editfolders&amp;mailbox=' . $mailbox . '&amp;page=' . intval ((isset ($_GET['page']) ? $_GET['page'] : 0)) . '\')">
				<input type="button" value="' . $lang->messages['newtitle33'] . '" onclick="confirm_emptyfolder()">
			</div>
			</td>
		</tr>
	</table>
	</form>
	<script type="text/javascript">
		menu_register("quickmenu1");
	</script>
	<div id="quickmenu1_menu" class="menu_popup" style="display:none;">
		<table border="1" cellspacing="0" cellpadding="2">
		<tr><td class="thead">' . $lang->messages['newtitle10'] . '</td></tr>
		' . get_pmboxes () . '
		</table>
	</div>
	<script type="text/javascript">
		menu.activate(true);
	</script>';
  echo $str;
  echo $pagerbottom;
  stdfoot ();
?>
