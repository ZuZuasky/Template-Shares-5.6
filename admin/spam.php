<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('NcodeImageResizer', true);
  define ('SP_VERSION', '1.0 by xam');
  if (($_GET['action'] == 'delete' AND !empty ($_POST['pmid'])))
  {
    (sql_query ('DELETE FROM messages WHERE id IN (' . implode (', ', $_POST['pmid']) . ')') OR sqlerr (__FILE__, 23));
  }
  else
  {
    if ($_GET['action'] == 'search')
    {
      $keywords = (isset ($_POST['keywords']) ? trim ($_POST['keywords']) : (isset ($_GET['keywords']) ? urldecode ($_GET['keywords']) : ''));
      $type = (isset ($_POST['type']) ? trim ($_POST['type']) : (isset ($_GET['type']) ? trim ($_GET['type']) : ''));
      if (((!empty ($keywords) AND 2 < strlen ($keywords)) AND !empty ($type)))
      {
        if (($type == 'receiver' OR $type == 'sender'))
        {
          $query = sql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($keywords));
          if (0 < mysql_num_rows ($query))
          {
            $userid = mysql_result ($query, 0, 'id');
            $extraquery1 = '' . ' AND ' . $type . ' = ' . $userid . ' ';
            $extraquery = '' . ' AND m.' . $type . ' = ' . $userid . ' ';
          }
        }
        else
        {
          $extraquery1 = '' . ' AND ' . $type . ' LIKE \'%' . mysql_real_escape_string ($keywords) . '%\' ';
          $extraquery = '' . ' AND m.' . $type . ' LIKE \'%' . mysql_real_escape_string ($keywords) . '%\' ';
          $hightlight = true;
        }

        $extralink = '&amp;action=search&amp;keywords=' . urlencode (htmlspecialchars_uni ($keywords)) . '&amp;type=' . htmlspecialchars_uni ($type);
      }
    }
  }

  stdhead ('Private Message System for Staff Team - ' . SP_VERSION);
  echo '
	<form method="post" action="' . $_this_script_ . '&amp;action=search">
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">
				Search  in Private Message(s)
			</td>
		</tr>
		<tr>
			<td>
				
				Search keyword(s): <input type="text" name="keywords" size="45" value="' . htmlspecialchars_uni ($keywords) . '"> 
				 Search by <select name="type"><option value="receiver"' . ($type == 'receiver' ? ' selected="selected"' : '') . '>Receiver</option><option value="sender"' . ($type == 'sender' ? ' selected="selected"' : '') . '>Sender</option><option value="subject"' . ($type == 'subject' ? ' selected="selected"' : '') . '>Subject</option><option value="msg"' . ($type == 'msg' ? ' selected="selected"' : '') . '>Message</option></select>
				<input type="submit" value="search">
			</td>
		</tr>		
	</table>
	</form><br />
	';
  $res2 = sql_query ('' . 'SELECT COUNT(*) FROM messages WHERE sender != 0 AND receiver != ' . $CURUSER['id'] . ' AND sender != ' . $CURUSER['id'] . ' ' . $extraquery1);
  $row = mysql_fetch_array ($res2);
  $count = $row[0];
  $perpage = $ts_perpage;
  list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $count, $_this_script_ . $extralink . '&amp;');
  echo $pagertop;
  ($res = sql_query ('' . 'SELECT m.*, u.username as receiverusername, uu.username as senderusername, g.namestyle as receivernamestyle, gg.namestyle as sendernamestyle FROM messages m LEFT JOIN users u ON (u.id=m.receiver) LEFT JOIN users uu ON (uu.id=m.sender) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid) WHERE m.sender != 0 AND m.receiver != ' . $CURUSER['id'] . ' AND m.sender != ' . $CURUSER['id'] . ' ' . $extraquery . ' ORDER BY m.id DESC ' . $limit) OR sqlerr (__FILE__, 80));
  print '<table border=1 cellspacing=0 cellpadding=5 width=100%>
';
  print '<form method="post" action="' . $_this_script_ . '&amp;action=delete" name="form">';
  print '<tr><td class=\'thead\' align=\'left\' width=\'100%\'>Message Details</td></tr>
';
  while ($arr = mysql_fetch_array ($res))
  {
    $receiver = '' . '<a href=' . $BASEURL . '/userdetails.php?id=' . (int)$arr['receiver'] . '><b>' . get_user_color ($arr['receiverusername'], $arr['receivernamestyle']) . '</b></a>';
    $sender = '' . '<a href=' . $BASEURL . '/userdetails.php?id=' . (int)$arr['sender'] . '><b>' . get_user_color ($arr['senderusername'], $arr['sendernamestyle']) . '</b></a>';
    $msg = format_comment ($arr['msg']);
    $msg = ($hightlight ? highlight ($keywords, $msg) : $msg);
    $added = my_datee ($dateformat, $arr['added']) . ' ' . my_datee ($timeformat, $arr['added']);
    echo '
	<tr>
		<td align="left">
			<b>Delete:</b> <input type="checkbox" name="pmid[]" value="' . $arr['id'] . '"><br />
			<b>Sender:</b> ' . $sender . '<br />
			<b>Receiver:</b> ' . $receiver . '<br />
			<b>Subject:</b> ' . ($hightlight ? highlight ($keywords, htmlspecialchars_uni ($arr['subject'])) : htmlspecialchars_uni ($arr['subject'])) . '<br />
			<b>Date:</b> ' . $added . '<br />
			<b>Message:</b><hr /> ' . $msg . '
		</td>
	</tr>
	';
  }

  echo '<tr><td align="center">
<input type="submit" value="Delete Selected!" class=button/>
<input type="button" value="Check all" onClick="this.value=check(form)" class=button>
</td></tr>
</form>
</table>
';
  print $pagerbottom;
  echo '<br />';
  stdfoot ();
?>
