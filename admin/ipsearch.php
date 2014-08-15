<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function showusertable ($query)
  {
    global $dateformat;
    global $timeformat;
    global $BASEURL;
    global $_this_script_;
    echo '
	<tr>
		<td class="subheader">Username</td>
		<td class="subheader">Email</td>
		<td class="subheader">IP</td>			
		<td class="subheader">Passkey</td>
		<td class="subheader">Last Seen</td>
		<td class="subheader">Registered</td>
		<td class="subheader">UP</td>
		<td class="subheader">Down</td>
		<td class="subheader">Ratio</td>
	</tr>';
    if (mysql_num_rows ($query) == 0)
    {
      echo '<tr><td colspan="9">Nothing Found!</td></tr>';
    }

    include_once INC_PATH . '/functions_ratio.php';
    while ($user = mysql_fetch_assoc ($query))
    {
      if ($user['last_access'] == '0000-00-00 00:00:00')
      {
        $lastseen = 'NEVER';
      }
      else
      {
        $lastseen_date = my_datee ($dateformat, $user['last_access']);
        $lastseen_time = my_datee ($timeformat, $user['last_access']);
        $lastseen = '' . $lastseen_date . ' ' . $lastseen_time;
      }

      if ($user['added'] == '0000-00-00 00:00:00')
      {
        $joindate = 'N/A';
      }
      else
      {
        $joindate_date = my_datee ($dateformat, $user['added']);
        $joindate_time = my_datee ($timeformat, $user['added']);
        $joindate = '' . $joindate_date . ' ' . $joindate_time;
      }

      echo '
		<tr>
			<td><a href="' . $BASEURL . '/userdetails.php?id=' . $user['id'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a></td>
			<td>' . htmlspecialchars_uni ($user['email']) . '</td>
			<td>' . htmlspecialchars_uni ($user['ip']) . '</td>			
			<td>' . htmlspecialchars_uni ($user['passkey']) . ' <b>[<a href="' . $_this_script_ . '&do=2&passkey=' . htmlspecialchars_uni ($user['passkey']) . '">reset</a>]</b></td>
			<td>' . $lastseen . '</td>
			<td>' . $joindate . '</td>
			<td>' . mksize ($user['uploaded']) . '</td>
			<td>' . mksize ($user['downloaded']) . '</td>
			<td>' . get_user_ratio ($user['uploaded'], $user['downloaded']) . '</td>
		</tr>';
    }

  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('IPS_VERSION', 'v0.2 by xam');
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : 0));
  $error = false;
  $title = 'Search Results: ';
  stdhead ('IP Search');
  if (($do == 1 OR isset ($_GET['ip'])))
  {
    $ip = (isset ($_POST['ip']) ? trim (strtolower ($_POST['ip'])) : (isset ($_GET['ip']) ? trim (strtolower ($_GET['ip'])) : ''));
    if (empty ($ip))
    {
      $error = 'Please enter ip!';
    }
    else
    {
      $query1 = sql_query ('SELECT u.*, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.ip = ' . sqlesc ($ip));
      $query2 = sql_query ('SELECT u.*, g.namestyle FROM iplog i LEFT JOIN users u ON (i.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE i.ip = ' . sqlesc ($ip));
      if ((mysql_num_rows ($query1) == 0 AND mysql_num_rows ($query2) == 0))
      {
        $error = 'There is no registered user with this ip!';
      }

      if (empty ($error))
      {
        _form_header_open_ ('Search Results in Users Table: (' . htmlspecialchars_uni ($ip) . ')', 9);
        showusertable ($query1);
        _form_header_close_ ();
        echo '<br />';
        _form_header_open_ ('Search Results in IpLog Table: (' . htmlspecialchars_uni ($ip) . ')', 9);
        showusertable ($query2);
        _form_header_close_ ();
        echo '<br />';
      }
    }
  }

  if (!empty ($error))
  {
    _form_header_open_ ($title . '(' . htmlspecialchars_uni ($ip) . ')');
    echo '<tr><td><font color="red">' . $error . '</font></td></tr>';
    _form_header_close_ ();
    echo '<br />';
  }

  _form_header_open_ ('Search Ip');
  $externalpreview = '<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Scanning Database...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>';
  echo '
<tr><td>' . $externalpreview . '
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="ipsearch">
		<input type="hidden" name="do" value="1">
		Ip: <input type="text" name="ip" value="' . htmlspecialchars_uni ($ip) . '" size="32">  
		<input type="submit" name="submit" value="search" onclick="ts_show(\'loading-layer\')">
</form>
</td></tr>';
  _form_header_close_ ();
  stdfoot ();
?>
