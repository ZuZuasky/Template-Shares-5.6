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

  @ini_set ('memory_limit', '20000M');
  define ('SM_VERSION', '0.8 by xam');
  define ('NcodeImageResizer', true);
  $error = '';
  $msgtext = trim ($_POST['message']);
  $subject = trim ($_POST['subject']);
  $avatar = get_user_avatar ($CURUSER['avatar']);
  if (($_POST['previewpost'] AND !empty ($msgtext)))
  {
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($msgtext) . '</td>
	</tr></table><br />';
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $gids = $_POST['gid'];
    $sender_id = ($_POST['sender'] == 'system' ? 0 : (int)$CURUSER['id']);
    $dt = sqlesc (get_date_time ());
    if (((empty ($msgtext) OR empty ($subject)) OR !is_array ($gids)))
    {
      $error = 'Don\'t leave any fields blank.';
    }

    if (is_array ($gids))
    {
      foreach ($gids as $gid)
      {
        if (is_valid_id ($gid))
        {
          $groupids .= '' . ', ' . $gid;
          $checked[] = $gid;
          continue;
        }
      }
    }

    if ((empty ($error) AND !$_POST['previewpost']))
    {
      require_once INC_PATH . '/functions_pm.php';
      $query = sql_query ('' . 'SELECT id FROM users WHERE usergroup IN (0' . $groupids . ')');
      $qcount = 0;
      while ($dat = mysql_fetch_assoc ($query))
      {
        send_pm ($dat['id'], $msgtext, $subject, $sender_id);
        ++$qcount;
      }

      $error = '<font color="red" size="2"><b>Total ' . ts_nf ($qcount) . ' message(s) has been sent.</b></font>';
    }
  }

  stdhead ('Mass Message to all Staff members and/or Users', false);
  if ((!empty ($error) AND !$_POST['previewpost']))
  {
    echo '
		<table border="0" cellspacing="0" cellpadding="4" class="" width="100%">
		<tr><td class="thead">Status</td></tr>
		<tr><td>' . $error . '</tr></td>
		</table><br />';
  }

  $query = sql_query ('SELECT gid, title, namestyle FROM usergroups');
  $count = 1;
  $sgids = '
<fieldset>
	<legend>Select Usergroup(s)</legend>
		<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr>';
  while ($gid = mysql_fetch_assoc ($query))
  {
    if ($count % 5 == 1)
    {
      $sgids .= '</tr></td>';
    }

    $sgids .= '	
	<td style="border: 0"><input type="checkbox" name="gid[]" value="' . $gid['gid'] . '"' . ((($gid['gid'] AND 0 < count ($checked)) AND in_array ($gid['gid'], $checked)) ? 'checked="checked' : '') . '></td>
	<td style="border: 0">' . get_user_color ($gid['title'], $gid['namestyle']) . '</td>';
    ++$count;
  }

  $sgids .= '
<td style="border: 0"></td>
<td style="border: 0"><a href="#" onClick="check(compose)"><font color="blue" size="1">check all</font></a></td>
</table>
</fieldset>
<br />
<fieldset>
	<legend>Select Sender</legend>
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<td>
					<select name="sender">
					<option value="system">Automatic Message By System</option>
					<option value="' . $CURUSER['username'] . '">' . $CURUSER['username'] . '</option>
				</select>
			</td>
		</tr>
	</table>
	</fieldset>';
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '<form method="post" name="compose" action="' . $_this_script_ . '">';
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  $str .= insert_editor (true, $subject, $msgtext, 'Mass Message to all Staff members and/or Users', $sgids);
  $str .= '</form>';
  echo $str;
  stdfoot ();
?>
