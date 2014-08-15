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

  define ('SVM_VERSION', '0.1 by xam');
  define ('NcodeImageResizer', true);
  stdhead ('Show Visitor Messages');
  _form_header_open_ ('Show Visitor Messages');
  $lang->load ('userdetails');
  $VisitorMessages .= '
	<tr>
		<td class="thead">' . $lang->userdetails['visitormsg1'] . '</td>
	</tr>
	<div id="VisitorMessages">
	';
  $count = mysql_num_rows (sql_query ('SELECT id FROM ts_visitor_messages'));
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&');
  ($Query = sql_query ('' . 'SELECT v.id as visitormsgid, v.userid, v.visitorid, v.visitormsg, v.added, u.username, g.namestyle, uu.username as vusername, gg.namestyle as vnamestyle FROM ts_visitor_messages v LEFT JOIN users u ON (v.userid=u.id) LEFT JOIN users uu ON (v.visitorid=uu.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN usergroups gg ON (uu.usergroup=gg.gid) ORDER by v.added DESC ' . $limit) OR sqlerr (__FILE__, 33));
  while ($VM = mysql_fetch_assoc ($Query))
  {
    $Username = '<a href="' . $BASEURL . '/userdetails.php?id=' . $VM['userid'] . '">' . get_user_color ($VM['username'], $VM['namestyle']) . '</a>';
    $VUsername = '<a href="' . $BASEURL . '/userdetails.php?id=' . $VM['visitorid'] . '">' . get_user_color ($VM['vusername'], $VM['vnamestyle']) . '</a>';
    $vMessage = format_comment ($VM['visitormsg']);
    $vAdded = my_datee ($dateformat, $VM['added']) . ' ' . my_datee ($timeformat, $VM['added']);
    $VisitorMessages .= '
		<tr>
			<td>				
				<div style="float: left;">' . $Username . '</div>
				<div style="float: right;">[<a href="' . $BASEURL . '/userdetails.php?id=' . $VM['userid'] . '&do=delete_msg&msg_id=' . $VM['visitormsgid'] . '">X</a>]  ' . sprintf ($lang->userdetails['visitormsg5'], $vAdded, $VUsername) . '</div>
				<div style="overflow:auto; padding:4px;">' . $vMessage . '</div>
			</td>
		</tr>
		';
  }

  $VisitorMessages .= '
	</div>';
  echo $pagertop;
  echo $VisitorMessages;
  echo $pagerbottom;
  _form_header_close_ ();
  stdfoot ();
?>
