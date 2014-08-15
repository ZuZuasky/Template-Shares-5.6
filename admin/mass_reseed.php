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

  define ('MR_VERSION', 'v0.4 by xam');
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : ''));
  if ($do == 'request_reseed_final')
  {
    $requestfrom = $_POST['requestfrom'];
    $sender = intval ($_POST['sender']);
    $postedtorrents = $_POST['torrents'];
    if (!empty ($postedtorrents))
    {
      $subject = trim ($_POST['subject']);
      $message = trim ($_POST['message']);
      if ((!empty ($subject) AND !empty ($message)))
      {
        if ($requestfrom == 'owner')
        {
          $query = sql_query ('' . 'SELECT t.owner, t.name, t.id, u.username FROM torrents t INNER JOIN users u ON (t.owner=u.id) WHERE t.id IN (' . $postedtorrents . ') AND ts_external != \'yes\' AND seeders = 0');
        }
        else
        {
          $query = sql_query ('' . 'SELECT s.userid as owner, s.torrentid as id, t.name, u.username FROM snatched s INNER JOIN torrents t ON (s.torrentid=t.id) INNER JOIN users u ON (s.userid = u.id) WHERE s.finished = \'yes\' AND s.torrentid IN (' . $postedtorrents . ')');
        }

        require_once INC_PATH . '/functions_pm.php';
        while ($torrent = mysql_fetch_assoc ($query))
        {
          $torrenturl = '[url=' . $BASEURL . '/details.php?id=' . $torrent['id'] . ']' . $torrent['name'] . '[/url]';
          $msg = str_replace (array ('{username}', '{torrentname}'), array ($torrent['username'], $torrenturl), $message);
          send_pm ($torrent['owner'], $msg, $subject, $sender);
        }

        if ($_POST['doubleupload'] == 'yes')
        {
          sql_query ('' . 'UPDATE torrents set doubleupload = \'yes\' WHERE id IN (' . $postedtorrents . ')');
        }
      }
    }
  }

  if ($do == 'request_reseed')
  {
    $torrents = $_POST['torrents'];
    $implode = @implode (',', $torrents);
    if (0 < count ($torrents))
    {
      require 'include/staff_languages.php';
      stdhead ('Request Reseed for Weak Torrents - Request Message');
      echo '
		<script type="text/javascript">
			function TSdoubleupload()
			{
				whatselected = document.forms[\'reseed\'].elements[\'doubleupload\'].value;
				TSnewinput = "\\nPlease Note: Once you start to Re-seed this torrent, you will get Double Upload Credits!";
				if (whatselected == "yes")
				{					
					document.forms[\'reseed\'].elements[\'message\'].focus();
					document.forms[\'reseed\'].elements[\'message\'].value =					
					document.forms[\'reseed\'].elements[\'message\'].value + TSnewinput;
					document.forms[\'reseed\'].elements[\'message\'].focus();
				}
				else
				{
					var str = document.forms[\'reseed\'].elements[\'message\'].value;
					var TSnewtext = str.replace(TSnewinput, "");
					document.forms[\'reseed\'].elements[\'message\'].value = TSnewtext;	
				}
			}
		</script>
		<form method="post" action="' . $_this_script_ . '" name="reseed">
		<input type="hidden" name="do" value="request_reseed_final">
		<input type="hidden" name="torrents" value="' . $implode . '">
		';
      _form_header_open_ ('Request Reseed for Weak Torrents - Request Message', 2);
      echo '
		<tr>
			<td>Subject</td><td><input type="text" size="40" value="' . $mass_reseed['message']['subject'] . '" name="subject"></td></tr>
		</tr>
		<tr>
			<td>Message</td><td><textarea name="message" cols="70" rows="15">' . $mass_reseed['message']['body'] . '</textarea></td>
		</tr>
		<tr>
			<td>Double Upload</td><td><select name="doubleupload" onchange="javascript:TSdoubleupload()"><option value="yes">YES</option><option value="no" selected="selected">NO</option></select> Give Double Uploaded amount users who begin to reseed this torrent!</td>
		</tr>
		<tr>
			<td>Sender</td><td><select name="sender"><option value="0">System</option><option value="' . $CURUSER['id'] . '">' . $CURUSER['username'] . '</option></select> <b>Please Note: </b>Do not change {username} and {torrentname} tags which will be automaticly renamed by system.</td>
		</tr>
		<tr>
			<td>Request from</td><td><select name="requestfrom"><option value="owner">Uploader Only</option><option value="all">All snatched users</option></select></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Request Reseed"> <input type="reset" value="Reset Form"></td></tr>
		</tr>';
      echo '</form>';
      _form_header_close_ ();
      stdfoot ();
      exit ();
    }
  }

  $res = sql_query ('SELECT id FROM torrents WHERE ts_external != \'yes\' AND seeders = 0');
  $row = mysql_fetch_array ($res);
  $count = $row[0];
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&');
  stdhead ('Request Reseed for Weak Torrents - Show Torrents');
  echo '
	<form method="post" action="' . $_this_script_ . '" name="reseed">
	<input type="hidden" name="do" value="request_reseed">
	';
  _form_header_open_ ('Request Reseed for Weak Torrents - Show Torrents', 7);
  echo '
<tr>	
	<td class="subheader" align="left" width="45%">Name</td>
	<td class="subheader" align="center" width="20%">Added</td>
	<td class="subheader" align="center" width="10%">Owner</td>
	<td class="subheader" align="center" width="5%">Seeders</td>
	<td class="subheader" align="center" width="5%">Leechers</td>
	<td class="subheader" align="center" width="10%">Snatched</td>
	<td class="subheader" align="center" width="5%"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'reseed\', this, \'group\')"></td>
</tr>
';
  $query = sql_query ('' . 'SELECT t.id, t.name, t.seeders, t.leechers, t.times_completed, t.added, t.owner, u.username, g.namestyle FROM torrents t LEFT JOIN users u ON (t.owner=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE t.ts_external != \'yes\' AND t.seeders = 0 ORDER by t.added DESC ' . $limit);
  if (isset ($postedtorrents))
  {
    $postedtorrents = @explode (',', $postedtorrents);
  }

  if (0 < mysql_num_rows ($query))
  {
    while ($torrent = mysql_fetch_assoc ($query))
    {
      echo '
		<tr>
			<td align="left"><a href="' . $BASEURL . '/details.php?id=' . $torrent['id'] . '">' . $torrent['name'] . '</a> [<a href="' . $BASEURL . '/edit.php?id=' . $torrent['id'] . '">edit</a>] [<a href="' . $BASEURL . '/admin/index.php?act=fastdelete&id=' . $torrent['id'] . '">delete</a>]' . (@in_array ($torrent['id'], $postedtorrents, true) ? '<br /><font color="red">Re-seed request sent!</font>' : '') . '</td>
			<td align="center">' . my_datee ($dateformat, $torrent['added']) . ' ' . my_datee ($timeformat, $torrent['added']) . '</td>
			<td align="center"><a href="' . $BASEURL . '/userdetails.php?id=' . $torrent['owner'] . '">' . get_user_color ($torrent['username'], $torrent['namestyle']) . '</a></td>
			<td align="center">' . ts_nf ($torrent['seeders']) . '</td>
			<td align="center">' . ts_nf ($torrent['leechers']) . '</td>
			<td align="center"><a href="' . $BASEURL . '/viewsnatches.php?id=' . $torrent['id'] . '">' . ts_nf ($torrent['times_completed']) . '</a></td>
			<td align="center"><input type="checkbox" checkme="group" name="torrents[]" value="' . $torrent['id'] . '"></td>
		</tr>
		';
    }

    echo '<tr><td colspan="7" align="right"><input type="submit" value="Request Re-seed for selected torrents"></td></tr>';
  }
  else
  {
    echo '<tr><td colspan="7">There is no weak torrent found!</td></tr>';
  }

  echo '</form>
' . $pagerbottom;
  _form_header_close_ ();
  stdfoot ();
?>
