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

  define ('L_VERSION', '0.6 by xam');
  include_once INC_PATH . '/functions_ratio.php';
  $maxratio = $hitrun_ratio;
  $mindownload = $hitrun_gig * 1024 * 1024 * 1024;
  if ($_GET['action'] == '')
  {
    if ($_GET['godcomplex'] == 'yes')
    {
      foreach ($_POST as $key => $ertek)
      {
        if ((strpos ($key, 'cb_') != 0 OR $ertek == 0 - 1))
        {
          continue;
        }

        $username = substr ($key, 3);
        function writecomment ($userid, $comment)
        {
          $res = sql_query ('SELECT modcomment FROM users WHERE id = ' . sqlesc ($userid) . ' LIMIT 0,1');
          if (1 <= mysql_num_rows ($res))
          {
            $arr = mysql_fetch_assoc ($res);
            $modcomment = gmdate ('Y-m-d') . ' - ' . $comment . '' . ($arr['modcomment'] != '' ? '
' : '') . ('' . $arr['modcomment']);
            $modcom = sqlesc ($modcomment);
            return sql_query ('' . 'UPDATE users SET modcomment = ' . $modcom . ' WHERE id = ' . sqlesc ($userid));
          }

        }

        if ($_POST['warn'])
        {
          $req = 'UPDATE users SET warned = \'yes\', warneduntil = DATE_ADD(NOW(), INTERVAL ' . 2 * 7 . ' DAY) WHERE id = ' . sqlesc ($ertek) . '';
          $res = sql_query ($req);
          $get = sql_query ('SELECT username,id FROM users WHERE id = ' . sqlesc ($ertek));
          $length = 2 * 7;
          $ratio = $maxratio + 0.100000000000000005551115;
          $until = sqlesc (get_date_time (gmtime () + $length * 86400));
          require_once INC_PATH . '/functions_pm.php';
          while ($arr = mysql_fetch_array ($get))
          {
            writecomment ($arr['id'], 'LeechWarned by System - Low Ratio.');
            sql_query ('' . 'UPDATE users SET leechwarn = \'yes\', leechwarnuntil = ' . $until . ' WHERE id=' . $arr['id']);
            send_pm ($arr['id'], 'You have been warned because of having low ratio. You need to get a ratio $maxratio before next 2 weeks or your account will be banned.', 'You have been warned!');
          }
        }
        else
        {
          if ($_POST['delete'])
          {
            $req = 'DELETE FROM users WHERE id = ' . sqlesc ($ertek) . '';
            $res = sql_query ($req);
            write_log ('' . 'User ' . $username . ' was deleted by ' . $CURUSER['username']);
          }
        }

        if ($res == '')
        {
          print '<script language="javascript">alert(\'No users Warned or Deleted!\');</script>';
          continue;
        }
      }
    }

    require_once INC_PATH . '/functions_mkprettytime.php';
    function usertable ($res, $frame_caption)
    {
      global $CURUSER;
      global $rootpath;
      global $pic_base_url;
      global $BASEURL;
      global $_this_script_;
      begin_frame ($frame_caption, true);
      begin_table (true);
      echo '<tr>
<td class="colhead" align="left">User</td>
<td class="colhead" align="right">Uploaded</td>
<td class="colhead" align="right">Downloaded</td>
<td class="colhead" align="right">Ratio</td>
<td class="colhead" align="left">Joined</td>
<td class="colhead" align="center">Delete/Warn</td>
</tr>
';
      $cba = '';
      if (isset ($_GET['select']))
      {
        $select = $_GET['select'];
        if ($select == 'all')
        {
          $cba = 'checked';
        }
        else
        {
          if ($select == 'none')
          {
            $cba = '';
          }
        }
      }

      $num = 0;
      print '<form method=\'post\' action=\'' . $_this_script_ . '&godcomplex=yes\'>';
      while ($a = mysql_fetch_array ($res))
      {
        foreach ($a as $key => $ertek)
        {
          ++$num;
        }

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

        print '' . '<tr class=row1 ' . $highlight . '><td align=left' . $highlight . '><a href=' . $BASEURL . '/userdetails.php?id=' . $a['userid'] . '><strong>' . $a['username'] . '</strong></a>';
        if (($a['warned'] == 'yes' OR $a['leechwarn'] == 'yes'))
        {
          print '<img src="' . $BASEURL . '/' . $pic_base_url . 'warned.gif" />';
        }

        print '' . '</td><td class=row1 align=right ' . $highlight . '>' . mksize ($a['uploaded']) . ('' . '</td><td class=row1 align=right ' . $highlight . '>') . mksize ($a['downloaded']) . ('' . '</td><td class=row1 align=right ' . $highlight . '>') . $ratio . '</td><td class=row1 align=left>' . gmdate ('Y-m-d', strtotime ($a['added'])) . ' (' . mkprettytime (time () - strtotime ($a['added'])) . ')</td>
<td align=center><input type=checkbox name="cb_' . $a['username'] . '" value="' . $a['userid'] . '" ' . $cba . ' /></td>
</tr>';
      }

      end_table ();
      end_frame ();
    }

    stdhead ('' . 'Ratio Under ' . $maxratio . '/' . $mindownload . ' byte Downloaded');
    $mainquery = 'SELECT id as userid, username, added, uploaded, downloaded, warned, leechwarn, uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS upspeed, downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS downspeed FROM users WHERE enabled = \'yes\'';
    $limit = 250;
    $order = 'added ASC';
    $extrawhere = '' . ' AND uploaded / downloaded < ' . $maxratio . ' AND downloaded > ' . $mindownload;
    ($r = sql_query ($mainquery . $extrawhere . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 142));
    print '<a href=' . $_this_script_ . '&action=sendpm>Send Mass PM to All Low Ratio Users</a>';
    $mindownloadprint = mksize ($mindownload);
    usertable ($r, '' . 'Ratio Under ' . $maxratio . ' / ' . $mindownloadprint . ' Downloaded');
    print '<a href="' . $_this_script_ . '&select=all">Select all</a> | <a href="' . $_this_script_ . '&select=none">Select none</a>';
    print '<br /><input type="submit" name="warn" value="Warn selected" class=button onclick="return confirm(\'Warn all selected users?\');" />';
    print '<input type="submit" name="delete" value="Delete selected" class=button onclick="return confirm(\'Are you bloody sure you want to delete all these users!?\');" />';
    print '</form>';
    $getlog = sql_query ('SELECT l.id, l.user, l.date, UNIX_TIMESTAMP(l.date) as utadded, u.username, g.namestyle
			FROM leecherspmlog l
			LEFT JOIN users u ON (l.user=u.id)
	   LEFT JOIN usergroups g ON (u.usergroup=g.gid)
			LIMIT 10');
    print '<br /><p><br />Leecher PM-Log.</p><p>';
    print '<table border=1 cellspacing=0 cellpadding=5 width=100% >
';
    print '<tr><td class=colhead>By User</td><td class=colhead>Date</td><td class=colhead>elapsed</td></tr>';
    while ($arr2 = mysql_fetch_array ($getlog))
    {
      $elapsed = mkprettytime (time () - $arr2['utadded']);
      print '' . '<tr><td><a href=' . $BASEURL . '/userdetails.php?id=' . $arr2['user'] . '>' . get_user_color (htmlspecialchars_uni ($arr2['username']), $arr2['namestyle']) . ('' . '</a></td><td>' . $arr2['date'] . '</td><td>' . $elapsed . '</td></tr>');
    }

    print '</table>';
  }

  if ($_GET['taking'] == 'takepm')
  {
    if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
    {
      if (!$msg)
      {
        stderr ('Error', 'Please Type In Some Text');
      }

      require_once INC_PATH . '/functions_pm.php';
      $query = 'SELECT id as userid, username, added, uploaded, downloaded, warned, uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS upspeed, downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS downspeed FROM users WHERE enabled = \'yes\'';
      $limit = 250;
      $order = 'added ASC';
      $extrawhere = '' . ' AND uploaded / downloaded < ' . $maxratio . ' AND downloaded > ' . $mindownload;
      ($r = sql_query ($mainquery . $extrawhere . ('' . ' ORDER BY ' . $order . ' ') . ('' . ' LIMIT ' . $limit)) OR sqlerr (__FILE__, 179));
      while ($dat = mysql_fetch_array ($r))
      {
        send_pm ($dat['userid'], trim ($_POST['msg']), 'Warning!');
      }

      (sql_query ('' . 'INSERT INTO leecherspmlog ( user , date ) VALUES ( ' . $CURUSER['id'] . ', ' . $dt . ')') OR sqlerr (__FILE__, 184));
      header ('Location: ' . $_this_script_);
      exit ();
    }
  }

  if ($_GET['action'] == 'sendpm')
  {
    stdhead ('Users that are bad');
    echo '<table class="main" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="embedded">
<div align="center">
<h2>Mass Message to All Bad Users</a></h2>
<form method="post" action="';
    echo $_this_script_;
    echo '&taking=takepm">
';
    if (($_GET['returnto'] OR $_SERVER['HTTP_REFERER']))
    {
      echo '<input type=hidden name=returnto value="';
      echo ($_GET['returnto'] ? htmlspecialchars_uni ($_GET['returnto']) : htmlspecialchars_uni ($_SERVER['HTTP_REFERER']));
      echo '">
';
    }

    $body = 'You have been warned due to low ratio. You have two weeks to improve it. If you dont, you will be banned. Always check the needseed-function for a ratio-boost!

This is a system generated message. Account deletion is also system controlled, so get to it!';
    echo '<table cellspacing=0 cellpadding=5 width=100% >
<tr>
<td>Send Mass Messege To All Bad Users<br />
<table style="border: 0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="border: 0">&nbsp;</td>
<td style="border: 0">&nbsp;</td>
</tr>
</table>
</td>
</tr>
<tr><td><textarea name=msg cols=70 rows=10>';
    echo $body;
    echo '</textarea></td></tr>
<tr>
<tr><td colspan=2 align=center><input type="submit" value="Send" class=button></td></tr>
</table>
<input type="hidden" name="receiver" value=';
    echo $receiver;
    echo '>
</form>

</div></td></tr></table>
<br />
NOTE: No HTML Code Allowed. (NO HTML)
';
  }

  stdfoot ();
?>
