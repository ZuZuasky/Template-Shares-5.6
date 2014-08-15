<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function usertable ($res, $frame_caption)
  {
    global $CURUSER;
    global $BASEURL;
    _form_header_open_ ($frame_caption);
    begin_table (true);
    echo '
	<tr>
	<td class=subheader align=left>User</td>
	<td class=subheader align=right>Uploaded</td>
	<td class=subheader align=right>Downloaded</td>
	<td class=subheader align=right>Ratio</td>
	<td class=subheader align=left>Joined</td>
	</tr>';
    $num = 0;
    while ($a = mysql_fetch_array ($res))
    {
      ++$num;
      $highlight = ($CURUSER['id'] == $a['userid'] ? ' bgcolor=#BBAF9B' : '');
      if ($a['downloaded'])
      {
        $ratio = $a['uploaded'] / $a['downloaded'];
        $color = get_ratio_color ($ratio);
        $ratio = number_format ($ratio, 2);
        if ($color)
        {
          $ratio = '' . '<font color=' . $color . '>' . $ratio . '</font>';
        }
      }
      else
      {
        $ratio = 'Inf.';
      }

      print '' . '<tr' . $highlight . '><td align=left' . $highlight . '><a href=' . $BASEURL . '/userdetails.php?id=' . $a['userid'] . '><b>' . get_user_color ($a['username'], $a['namestyle']) . '</b>' . ('' . '</td><td align=right' . $highlight . '>') . mksize ($a['uploaded']) . ('' . '</td><td align=right' . $highlight . '>') . mksize ($a['downloaded']) . ('' . '</td><td align=right' . $highlight . '>') . $ratio . '</td><td align=left>' . gmdate ('Y-m-d', strtotime ($a['added'])) . ' (' . mkprettytime (time () - strtotime ($a['added'])) . ')</td></tr>';
    }

    end_table ();
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  include_once INC_PATH . '/functions_ratio.php';
  require_once INC_PATH . '/functions_mkprettytime.php';
  stdhead ('Ratio is 100 of above');
  $mainquery = 'SELECT u.id as userid, u.username, u.added, u.uploaded, u.downloaded, u.uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(u.added)) AS upspeed, u.downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(u.added)) AS downspeed, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = \'yes\'';
  $limit = 250;
  $order = 'added ASC';
  $extrawhere = ' AND uploaded / downloaded > 100';
  ($r = sql_query ($mainquery . $extrawhere . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 69));
  usertable ($r, 'Ratio Above 100');
  _form_header_close_ ();
  stdfoot ();
?>
