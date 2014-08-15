<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function pm_limit ($showlimit = true, $disablepm = false, $userid = '', $usergroup = '')
  {
    global $lang;
    global $CURUSER;
    global $usergroups;
    if ((!$CURUSER OR !$usergroups))
    {
      return false;
    }

    if ((!$userid AND !$disablepm))
    {
      $userid = intval ($CURUSER['id']);
    }

    if ($disablepm)
    {
      $gid = intval ($usergroup);
      $getamount = sql_query ('SELECT pmquote FROM usergroups WHERE gid = ' . sqlesc ($gid) . ' LIMIT 1');
      $maxpmstorage = intval (mysql_result ($getamount, 0, 'pmquote'));
    }
    else
    {
      $maxpmstorage = intval ($usergroups['pmquote']);
    }

    if ($maxpmstorage == 0)
    {
      return null;
    }

    $count1 = mysql_num_rows (sql_query ('' . 'SELECT m.* FROM messages m WHERE m.receiver=' . $userid . ' and m.location != 0'));
    $count2 = mysql_num_rows (sql_query ('' . 'SELECT m.* FROM messages m WHERE m.sender=' . $userid . ' AND m.saved=\'yes\''));
    $pmscounttotal = intval ($count1 + $count2);
    $overhalf = '';
    if (($maxpmstorage <= $pmscounttotal AND $disablepm))
    {
      return false;
    }

    if ($showlimit)
    {
      if ($maxpmstorage <= $pmscounttotal)
      {
        $spaceused = 100;
        $spaceused2 = 0;
        $belowhalf = '';
        $overhalf = '100%';
        $warnmsg = '<table border="0" cellspacing="0" cellpadding="0" class="tborder">
			<tr>
			<td class="trow1" align="center"><span class="smalltext"><strong><font color="#760306">' . $lang->global['reached_warning'] . '</font></strong><br />' . $lang->global['reached_warning2'] . '</span></td>
			</tr></table><br />';
      }
      else
      {
        $spaceused = $pmscounttotal / $maxpmstorage * 100;
        $spaceused2 = 100 - $spaceused;
        $warnmsg = '';
        if ($spaceused <= '50')
        {
          $belowhalf = round ($spaceused, 0) . '%';
        }
        else
        {
          $overhalf = round ($spaceused, 0) . '%';
        }
      }

      $msg = '
		<table border="0" cellspacing="0" cellpadding="5" class="tborder" width="100%">
		<tr>
		<td class="trow1" align="center"><p>' . sprintf ($lang->global['pmlimitmsg'], $pmscounttotal, $maxpmstorage) . '</p>
		<table align="center" cellspacing="0" cellpadding="0" width="230" style="border: solid 1px #000000;">
			<tr>
				<td width="' . $spaceused . '" bgcolor="#760306" align="center">
				<font color="#000000" size="1"><strong>' . $overhalf . '</font></span></td>
				<td width="' . $spaceused2 . '" bgcolor="#035219" align="center">
				<font color="#000000" size="1"><strong>' . $belowhalf . '</font></span></td>
				<td width="130" align="center"><font color="#000000" size="1"><strong>' . $lang->global['pmspace'] . '</strong></font></td>
			</tr>
		</table></tr></td></table><br />';
      $msg = ($warnmsg ? $warnmsg . $msg : $msg);
      return $msg;
    }

    return true;
  }

  function delete_pms ($pmids)
  {
    global $userid;
    global $mailboxes;
    if (!is_array ($pmids))
    {
      $pmids = array ($pmids);
    }

    foreach ($pmids as $delid)
    {
      if (is_valid_id ($delid))
      {
        $res = sql_query ('SELECT receiver,saved,sender,location FROM messages WHERE id=' . sqlesc ((int)$delid));
        $message = mysql_fetch_assoc ($res);
        if (($message['receiver'] == $userid AND $message['saved'] == 'no'))
        {
          (sql_query ('DELETE FROM messages WHERE id=' . sqlesc ((int)$delid)) OR sqlerr (__FILE__, 90));
          continue;
        }
        else
        {
          if (($message['sender'] == $userid AND $message['location'] == $mailboxes['PMDELETED']))
          {
            (sql_query ('DELETE FROM messages WHERE id=' . sqlesc ((int)$delid)) OR sqlerr (__FILE__, 94));
            continue;
          }
          else
          {
            if (($message['receiver'] == $userid AND $message['saved'] == 'yes'))
            {
              (sql_query ('' . 'UPDATE messages SET location=' . $mailboxes['PMDELETED'] . ' WHERE id=' . sqlesc ((int)$delid)) OR sqlerr (__FILE__, 98));
              continue;
            }
            else
            {
              if (($message['sender'] == $userid AND $message['location'] != $mailboxes['PMDELETED']))
              {
                (sql_query ('UPDATE messages SET saved=\'no\' WHERE id=' . sqlesc ((int)$delid)) OR sqlerr (__FILE__, 102));
                continue;
              }

              continue;
            }

            continue;
          }

          continue;
        }

        continue;
      }
    }

  }

  function action_box ()
  {
    global $lang;
    global $mailbox;
    global $mailboxes;
    $box = '
	<select name="do">
		' . ($mailbox != $mailboxes['SENDBOX'] ? '<option value="move">' . $lang->messages['newtitle3'] . '</option>' : '') . '
		<option value="delete">' . $lang->messages['newtitle4'] . '</option>
		' . ($mailbox != $mailboxes['SENDBOX'] ? '<option value="markasread">' . $lang->messages['newtitle5'] . '</option>
		<option value="markasunread">' . $lang->messages['newtitle6'] . '</option>' : '') . '
	</select>
	';
    return $box;
  }

  function get_pmboxes ()
  {
    global $lang;
    global $userid;
    global $mailboxes;
    global $maxboxs;
    $query = sql_query ('' . 'SELECT name, boxnumber FROM pmboxes WHERE userid = ' . $userid . ' ORDER by boxnumber LIMIT 0, ' . $maxboxs);
    $boxes = '
	<tr><td class="subheader"><a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailboxes['INBOX'] . '">' . $lang->messages['inbox'] . '</a></td></tr>
	<tr><td class="subheader"><a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . $mailboxes['SENDBOX'] . '">' . $lang->messages['sendbox'] . '</a></td></tr>
	';
    if (0 < mysql_num_rows ($query))
    {
      while ($box = mysql_fetch_assoc ($query))
      {
        $boxes .= '<tr><td class="subheader"><a href="' . $_SERVER['SCRIPT_NAME'] . '?userid=' . $userid . '&amp;mailbox=' . intval ($box['boxnumber']) . '">' . htmlspecialchars_uni ($box['name']) . '</a></td></tr>';
      }
    }

    return $boxes;
  }

  function show_message_errors_ ()
  {
    global $_errors;
    global $lang;
    if (0 < count ($_errors))
    {
      $errors = implode ('<br />', $_errors);
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

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
