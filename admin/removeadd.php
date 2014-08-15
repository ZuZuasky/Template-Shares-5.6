<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function class_amount ()
  {
    echo '<select name="classamount" style="width: 145px;" id="specialboxes">
	<option value="0" style="color: gray;">( amount )</option>';
    $i = 1;
    while ($i < 51)
    {
      print '' . '<option value=\'' . $i . '\'>' . $i . ' GB</option>
';
      ++$i;
    }

    echo '</select>';
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('RA_VERSION', '0.2 by xam');
  if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
  {
    $eol = '
';
  }
  else
  {
    if (strtoupper (substr (PHP_OS, 0, 3) == 'MAC'))
    {
      $eol = '
';
    }
    else
    {
      $eol = '
';
    }
  }

  if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST')
  {
    $class = (int)$_POST['usergroup'];
    if (($class == '-' OR !is_valid_id ($class)))
    {
      $class = '';
    }

    $query = ($class ? ' WHERE usergroup=' . $class : '');
    if ($_POST['doit'] == 'yes')
    {
      if (($_POST['classamount'] < 1 OR 51 < $_POST['classamount']))
      {
        stderr ('Error', 'Don\'t leave any fields blank!');
      }

      $modcomment = gmdate ('Y-m-d') . ' - Removed ' . mksize ($_POST['classamount'] * 1024 * 1024 * 1024) . ' Upload Amount by ' . $CURUSER['username'] . ' (Upload Remove Tool)' . $eol;
      $ulamount = sqlesc ($_POST['classamount'] * 1024 * 1024 * 1024);
      (sql_query ('' . 'UPDATE users SET uploaded = uploaded - ' . $ulamount . ', modcomment=CONCAT(' . sqlesc ($modcomment . '') . ', modcomment)' . $query) OR sqlerr (__FILE__, 50));
      stderr ('Upload', $_POST['classamount'] . 'GB upload is removed to ' . ($class ? 'following usergroup: ' . get_user_class_name ($class) : 'ALL'));
      exit ();
    }

    if (($_POST['username'] == '' OR $_POST['uploaded'] == ''))
    {
      stderr ('Error', 'Don\'t leave any fields blank!');
    }

    $username = sqlesc ($_POST['username']);
    $uploaded = sqlesc ($_POST['uploaded'] * 1024 * 1024 * 1024);
    $modcomment = gmdate ('Y-m-d') . ' - Removed ' . mksize ($_POST['uploaded'] * 1024 * 1024 * 1024) . ' Upload Amount by ' . $CURUSER['username'] . ' (Upload Remove Tool)' . $eol;
    (sql_query ('' . 'UPDATE users SET uploaded= uploaded - ' . $uploaded . ', modcomment=CONCAT(' . sqlesc ($modcomment . '') . ('' . ', modcomment) WHERE username=' . $username)) OR sqlerr (__FILE__, 61));
    ($res = sql_query ('' . 'SELECT id FROM users WHERE username=' . $username) OR sqlerr (__FILE__, 62));
    $arr = mysql_fetch_row ($res);
    if (!$arr)
    {
      stderr ('Error', 'Unable to update account.');
    }

    header ('' . 'Location: ' . $BASEURL . '/userdetails.php?id=' . $arr['0']);
    exit ();
  }

  $usergroups = _selectbox_ (NULL, 'usergroup');
  stdhead ('Remove Users Upload Amounts');
  _form_header_open_ ('Remove Users Uploaded Amounts');
  echo '<form method="post" action="' . $_this_script_ . '">';
  begin_table (true);
  echo '<tr><td class="rowhead">User name: </td><td class="row1"><input type="text" name="username" id="specialboxn" size="40"/></td></tr>';
  echo '<tr><td class="rowhead">Amount (GB): </td><td class="row1"><input type="text" name="uploaded" size="40" id="specialboxes"/> <input type="submit" value="do it" class=button/></td></tr>';
  echo '</form>';
  end_table ();
  _form_header_close_ ();
  echo '<br />';
  _form_header_open_ ('Remove xGB upload amount from everyone!');
  echo '<form action="' . $_this_script_ . '" method="post">';
  begin_table (true);
  echo '<tr><td class="row1" align="center">Usergroup: 
<input type = "hidden" name = "doit" value = "yes" />';
  echo $usergroups . ' ';
  class_amount ();
  echo '<input type="submit" value="do it" class=button />
</td></tr>';
  end_table ();
  echo '</form>';
  _form_header_close_ ();
  stdfoot ();
?>
