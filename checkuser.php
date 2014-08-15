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
  parked ();
  define ('CU_VERSION', '0.7');
  $lang->load ('checkuser');
  $id = 0 + $_GET['id'];
  int_check ($id, true);
  ($r = @sql_query ('SELECT u.*, c.flagpic FROM users u LEFT JOIN countries c ON (u.country=c.id) WHERE u.status = \'pending\' AND u.id = ' . @sqlesc ($id)) OR sqlerr (__FILE__, 29));
  ($user = mysql_fetch_array ($r) OR stderr ($lang->global['error'], $lang->global['nouserid']));
  if ((!is_mod ($usergroups) AND $user['invited_by'] != $CURUSER['id']))
  {
    print_no_permission ();
  }

  if ($user['added'] == '0000-00-00 00:00:00')
  {
    $joindate = $lang->checkuser['na'];
  }
  else
  {
    require_once INC_PATH . '/functions_mkprettytime.php';
    $joindate = my_datee ($regdateformat, $user['added']) . ' (' . mkprettytime (TIMENOW - strtotime ($user['added'])) . ')';
  }

  if ($user['country'])
  {
    $country = '<td class=embedded><img src=' . $pic_base_url . ('' . 'flag/' . $user['flagpic'] . ' alt="' . $user['name'] . '" title="' . $user['name'] . '" style=\'margin-left: 8pt\'></td>');
  }

  stdhead (sprintf ($lang->checkuser['details'], $user['username']));
  $enabled = $user['enabled'] == 'yes';
  print '<p><table class=main border=0 cellspacing=0 cellpadding=0>' . '<tr><td class=embedded><h1 style=\'margin:0px\'>' . sprintf ($lang->checkuser['details'], $user['username']) . ('' . '</h1></td>' . $country . '</tr></table></p><br />
');
  if (!$enabled)
  {
    print $lang->global['accountdisabled'];
  }

  echo '<table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead width=10%>';
  echo $lang->checkuser['joindate'];
  echo '</td><td align=left width=90%>';
  echo $joindate;
  echo '</td></tr>
<tr><td class=rowhead width=10%>';
  echo $lang->checkuser['email'];
  echo '</td><td align=left width=90%><a href=mailto:';
  echo $user['email'];
  echo '>';
  echo $user['email'];
  echo '</a></td></tr>
';
  if ((is_mod ($usergroups) AND $user['ip'] != ''))
  {
    print '<tr><td class=rowhead width=1%>' . $lang->checkuser['ip'] . ('' . '</td><td align=left width=99%>' . $user['ip'] . '</td></tr>');
  }

  print '<tr><td class=rowhead width=1%>' . $lang->checkuser['status'] . '</td><td align=left width=99%>' . ($user['status'] == 'pending' ? '<font color=#ca0226>' . $lang->checkuser['pending'] . '</font>' : '<font color=#1f7309>' . $lang->checkuser['confirmed'] . '</font>') . '</td></tr>';
  print '</table>';
  stdfoot ();
?>
