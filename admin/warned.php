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

  define ('W_VERSION', '0.8 by xam');
  include_once INC_PATH . '/functions_ratio.php';
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'showlist'));
  if ($action == 'remove')
  {
    sql_query ('UPDATE users SET warned = \'no\', leechwarn = \'no\', warneduntil = \'0000-00-00 00:00:00\', leechwarnuntil = \'0000-00-00 00:00:00\' WHERE id IN (' . implode (', ', $_POST['userid']) . ')');
    $action = 'showlist';
  }

  if ($action == 'showlist')
  {
    stdhead ('Warned Users');
    $countrows = number_format (tsrowcount ('id', 'users', 'enabled = \'yes\' AND usergroup != \'' . UC_BANNED . '\' AND (warned = \'yes\' OR leechwarn = \'yes\')'));
    $page = 0 + $_GET['page'];
    $perpage = $ts_perpage;
    list ($pagertop, $pagerbottom, $limit) = pager ($perpage, $countrows, $_this_script_ . '&action=showlist&', '', false);
    echo $pagertop;
    _form_header_open_ ('Warned Users');
    echo '
	<script language=\'JavaScript\'>
	checked = false;
	function checkedAll ()
	{
		if (checked == false){checked = true}else{checked = false}
		for (var i = 0; i < document.getElementById(\'warned\').elements.length; i++)
		{
			document.getElementById(\'warned\').elements[i].checked = checked;
		}
	}
    </script>
	<form method="post" action="' . $_this_script_ . '" name="update">
	<table border="1" width="100%" cellspacing="0" cellpadding="5">	
	<input type="hidden" name="action" value="remove">
	<tr>
	<td class=subheader width=10%>User</td>
	<td class=subheader width=10%>Registered</td>
	<td class=subheader width=10%>Last Access</td>  
	<td class=subheader width=8%>DL</td>
	<td class=subheader width=8%>UL</td>
	<td class=subheader width=5%>Ratio</td>
	<td class=subheader width=40%>Until</td>
	<td class=subheader align="center" width=5%>Type</td>
	<td class=subheader align="center" width=4%><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'update\', this, \'group\');"></td>';
    $query = sql_query ('SELECT u.*, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM users u LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.usergroup != \'' . UC_BANNED . '\' AND u.enabled = \'yes\' AND (u.warned = \'yes\' OR u.leechwarn = \'yes\') ' . $limit);
    if (mysql_num_rows ($query) == 0)
    {
      echo '<tr><td colspan="10">No User Found...</tr></td>';
    }
    else
    {
      require_once INC_PATH . '/functions_mkprettytime.php';
      while ($res = mysql_fetch_array ($query))
      {
        $icons = get_user_icons ($res);
        $user = '<a href=' . $BASEURL . '/userdetails.php?id=' . $res['id'] . '>' . get_user_color ($res['username'], $res['namestyle']) . '</a>' . $icons;
        $registered = my_datee ($dateformat, $res['added']) . '<br />' . my_datee ($timeformat, $res['added']);
        $lastaccess = my_datee ($dateformat, $res['last_access']) . '<br />' . my_datee ($timeformat, $res['last_access']);
        $downloaded = mksize ($res['downloaded']);
        $uploaded = mksize ($res['uploaded']);
        $ratio = ($res['downloaded'] != 0 ? number_format ($res['uploaded'] / $res['downloaded'], 3) : '---');
        $ratio = '<font color=' . get_ratio_color ($ratio) . '>' . $ratio . '</font>';
        $warneduntil = ($res['warneduntil'] != '0000-00-00 00:00:00' ? $res['warneduntil'] : $res['leechwarnuntil']);
        if ($warneduntil == '0000-00-00 00:00:00')
        {
          $warneduntil = '<font color=red>(Arbitrary duration)</font>';
        }
        else
        {
          $warneduntil = $warneduntil . '<br />(' . mkprettytime (strtotime ($warneduntil) - gmtime ()) . ' to go)';
        }

        $warntype = ($res['warned'] == 'yes' ? 'Normal' : ($res['leechwarn'] == 'yes' ? '<strong>Leech-Warn</strong>' : 'Unknown'));
        $remove = '<input type="checkbox" name="userid[]" value="' . $res['id'] . '" checkme="group">';
        echo '<tr>
			<td>' . $user . '</td>
			<td>' . $registered . '</td>
			<td>' . $lastaccess . '</td>			
			<td>' . $downloaded . '</td>
			<td>' . $uploaded . '</td>
			<td>' . $ratio . '</td>
			<td>' . $warneduntil . '</td>
			<td align="center">' . $warntype . '</td>
			<td align="center">' . $remove . '</td>
			</tr>';
      }

      echo '<tr><td colspan="9" align="right">' . $pagerbottom . '<input type="submit" value="Remove Warning" class=button></td></tr>';
    }

    echo '</form></table>';
    _form_header_close_ ();
    stdfoot ();
  }

?>
