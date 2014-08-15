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

  stdhead ('Mass invite');
  $selectbox = _selectbox_ (NULL, 'usergroup');
  if ($_POST['doit'] == 'yes')
  {
    $amount = 0 + $_POST['amount'];
    $type = $_POST['type'];
    $allowed_type = array ('-', '+');
    if (!in_array ($type, $allowed_type))
    {
      $type = '+';
    }

    $query = 'enabled=\'yes\' AND status=\'confirmed\'';
    $usergroup = $_POST['usergroup'];
    if (($usergroup == '-' OR !is_valid_id ($usergroup)))
    {
      $usergroup = '';
    }

    if ($usergroup)
    {
      $query .= '' . ' AND usergroup=' . $usergroup;
    }

    (sql_query ('' . 'UPDATE users SET invites = invites ' . $type . ' ' . $amount . ' WHERE ' . $query) OR sqlerr (__FILE__, 35));
    stdmsg ('Message', 'Done...', true, 'success');
    stdfoot ();
    exit ();
  }

  echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
  echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Mass Invite</td></tr>';
  echo '<tr class="subheader"><td width="20%" align="left">Amount</td><td width="30%" align="left">Usergroup</td><td width="50%" align="left">Type</td></tr>';
  echo '<form action="';
  echo $_this_script_;
  echo '" method="post">
<tr>
<td align="left">
<input type = "hidden" name = "doit" value = "yes" />
<input type="text" name="amount" value="5" size="5" id="specialboxes">
</td>
<td align="left">
';
  echo $selectbox;
  echo '</td>
<td align="left">
';
  echo '<s';
  echo 'elect name=type>
<option value="0" style="color: gray;">(Select invite type - / +)</option>
<option value="+">+</option>
<option value="-">-</option>
</select> <input type="submit" value="Update Users" class=button /></td>
</tr>
</table></table>
</form>
';
  stdfoot ();
?>
