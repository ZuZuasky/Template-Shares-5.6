<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function mask ($str, $start = 0, $length = NULL)
  {
    $mask = preg_replace ('/\\S/', '*', $str);
    if (is_null ($length))
    {
      $mask = substr ($mask, $start);
      $str = substr_replace ($str, $mask, $start);
    }
    else
    {
      $mask = substr ($mask, $start, $length);
      $str = substr_replace ($str, $mask, $start, $length);
    }

    return $str;
  }

  require_once 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('badusers');
  define ('BU_VERSION', '0.5');
  $act = (isset ($_POST['act']) ? htmlspecialchars ($_POST['act']) : (isset ($_GET['act']) ? htmlspecialchars ($_GET['act']) : 'showlist'));
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  if ($act == 'delete')
  {
    if (!is_mod ($usergroups))
    {
      print_no_permission (true);
      exit ();
    }

    $id = intval ($_GET['id']);
    int_check ($id, true);
    include_once INC_PATH . '/ts_token.php';
    $ts_token = new ts_token ();
    $ts_token->redirect = '' . $_SERVER['SCRIPT_NAME'] . '?act=delete&id=' . $id;
    $ts_token->url = '' . 'You are about to delete a bad user. Click
<a href=\'' . $ts_token->redirect . '&sure=1&hash={1}\'>here</a> if you are sure. Click <a href=\'' . $_SERVER['SCRIPT_NAME'] . '\'>here</a> to go back.';
    $ts_token->create ();
    sql_query ('DELETE FROM badusers WHERE id = ' . sqlesc ($id) . ' LIMIT 1');
    write_log ('BAD USER (id: ' . $id . ') deleted by ' . htmlspecialchars_uni ($CURUSER['username']));
    redirect ($_SERVER['SCRIPT_NAME'], $lang->badusers['deleted']);
    return 1;
  }

  if ($act == 'edit')
  {
    if (!is_mod ($usergroups))
    {
      print_no_permission (true);
      exit ();
    }

    if ($do == 'save')
    {
      $id = intval ($_POST['id']);
      int_check ($id, true);
      $username = (isset ($_POST['username']) ? trim ($_POST['username']) : (isset ($_GET['username']) ? trim ($_GET['username']) : ''));
      $email = (isset ($_POST['email']) ? trim ($_POST['email']) : (isset ($_GET['email']) ? trim ($_GET['email']) : ''));
      $ipaddress = (isset ($_POST['ipaddress']) ? trim ($_POST['ipaddress']) : (isset ($_GET['ipaddress']) ? trim ($_GET['ipaddress']) : ''));
      $comment = (isset ($_POST['comment']) ? trim ($_POST['comment']) : (isset ($_GET['comment']) ? trim ($_GET['comment']) : ''));
      if (((empty ($username) OR empty ($email)) OR empty ($comment)))
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        exit ();
      }

      sql_query ('UPDATE badusers SET username = ' . sqlesc ($username) . ', email = ' . sqlesc ($email) . ', ipaddress = ' . sqlesc ($ipaddress) . ', comment = ' . sqlesc ($comment) . ' WHERE id = ' . sqlesc ($id));
      write_log ('BAD USER (id: ' . $id . ') edited by ' . htmlspecialchars_uni ($CURUSER['username']));
      redirect ($_SERVER['SCRIPT_NAME'], $lang->badusers['edited']);
      return 1;
    }

    $id = intval ($_GET['id']);
    int_check ($id, true);
    $query = sql_query ('SELECT username,email,ipaddress,comment FROM badusers WHERE id = ' . sqlesc ($id));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->global['nothingfound']);
      exit ();
    }

    $baduser = mysql_fetch_assoc ($query);
    stdhead ($lang->badusers['edith']);
    $str = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    $str .= '<tbody>
		<tr>
		<td>
		<table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%">
		<tbody>
		<tr>
		<td class="thead" colspan="4" align="center">' . $lang->badusers['edith'] . '</td>
		</tr>';
    $str .= '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="edit">
		<input type="hidden" name="do" value="save">
		<input type="hidden" name="id" value="' . $id . '">
		<tr><td class="rowhead">' . $lang->badusers['username'] . '</td><td><input type="text" name="username" id="specialboxn" value="' . htmlspecialchars_uni ($baduser['username']) . '"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['email'] . '</td><td><input type="text" name="email" id="specialboxn" value="' . htmlspecialchars_uni ($baduser['email']) . '"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['ipaddress'] . '</td><td><input type="text" name="ipaddress" id="specialboxn" value="' . htmlspecialchars_uni ($baduser['ipaddress']) . '"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['comment'] . '</td><td><input type="text" name="comment" id="specialboxn" value="' . htmlspecialchars_uni ($baduser['comment']) . '">
		<input type="submit" value="' . $lang->global['buttonsave'] . '" class=button></td></tr>
		</tr></form>';
    $str .= '</table></td></tr></table>';
    echo $str;
    stdfoot ();
    return 1;
  }

  if ($act == 'insert')
  {
    if ($usergroups['canbaduser'] != 'yes')
    {
      print_no_permission (true);
      exit ();
    }

    if ($do == 'save')
    {
      $username = (isset ($_POST['username']) ? trim ($_POST['username']) : (isset ($_GET['username']) ? urldecode ($_GET['username']) : ''));
      $email = (isset ($_POST['email']) ? trim ($_POST['email']) : (isset ($_GET['email']) ? urldecode ($_GET['email']) : ''));
      $ipaddress = (isset ($_POST['ipaddress']) ? trim ($_POST['ipaddress']) : (isset ($_GET['ipaddress']) ? urldecode ($_GET['ipaddress']) : ''));
      $comment = (isset ($_POST['comment']) ? trim ($_POST['comment']) : (isset ($_GET['comment']) ? urldecode ($_GET['comment']) : ''));
      $userid = (isset ($_POST['userid']) ? intval ($_POST['userid']) : (isset ($_GET['userid']) ? intval ($_GET['userid']) : 0));
      $addedby = $CURUSER['username'] . ':' . intval ($CURUSER['id']);
      if (((empty ($username) OR empty ($email)) OR empty ($comment)))
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
        exit ();
      }

      $query = sql_query ('SELECT * FROM badusers WHERE username = ' . sqlesc ($username) . ' LIMIT 0 , 1');
      if (mysql_num_rows ($query) != 0)
      {
        stderr ($lang->global['error'], $lang->badusers['alreadyexists'], false);
      }

      (sql_query ('INSERT INTO badusers (userid,username,email,ipaddress,comment,added,addedby) VALUES (' . sqlesc ($userid) . ', ' . sqlesc ($username) . ', ' . sqlesc ($email) . ', ' . sqlesc ($ipaddress) . ', ' . sqlesc ($comment) . ', ' . sqlesc (get_date_time ()) . ', ' . sqlesc ($addedby) . ')') OR sqlerr (__FILE__, 138));
      redirect ($_SERVER['SCRIPT_NAME'], $lang->badusers['saved']);
      return 1;
    }

    stdhead ($lang->badusers['insert']);
    $str = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    $str .= '<tbody>
		<tr>
		<td>
		<table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%">
		<tbody>
		<tr>
		<td class="thead" colspan="4" align="center">' . $lang->badusers['insert'] . '</td>
		</tr>';
    $str .= '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="insert">
		<input type="hidden" name="do" value="save">
		<tr><td class="rowhead">' . $lang->badusers['username'] . '</td><td><input type="text" name="username" id="specialboxn"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['email'] . '</td><td><input type="text" name="email" id="specialboxn"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['ipaddress'] . '</td><td><input type="text" name="ipaddress" id="specialboxn"></td></tr>
		<tr><td class="rowhead">' . $lang->badusers['comment'] . '</td><td><input type="text" name="comment" id="specialboxn">
		<input type="submit" value="' . $lang->global['buttonsave'] . '" class=button></td></tr>
		</tr></form>';
    $str .= '</table></td></tr></table>';
    echo $str;
    stdfoot ();
    return 1;
  }

  if ($act == 'showlist')
  {
    $countrows = number_format (tsrowcount ('id', 'badusers'));
    $page = intval ($_GET['page']);
    list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $countrows, $_SERVER['SCRIPT_NAME'] . '?act=' . $act . '&');
    $query = sql_query ('SELECT * FROM badusers ORDER by added DESC ' . $limit);
    stdhead ($lang->badusers['head']);
    if ($usergroups['canbaduser'] == 'yes')
    {
      echo '<p align="right">
		<input type="button" value="' . $lang->badusers['insert'] . '" onClick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?act=insert\')" class="hoptobutton">
		</p>';
    }

    $str = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    $str .= '<tbody>
	<tr>
	<td>
	<table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%">
	<tbody>
	<tr>
	<td class="thead" colspan="6" align="center">' . $lang->badusers['head'] . '</td>
	</tr>';
    $str .= '<tr class="subheader">
	<td align="left" width="15%">' . $lang->badusers['username'] . '</td>
	<td align="left" width="15%">' . $lang->badusers['email'] . '</td>
	<td align="center" width="15%">' . $lang->badusers['ipaddress'] . '</td>
	<td align="left" width="35%">' . $lang->badusers['comment'] . '</td>
	' . (is_mod ($usergroups) ? '<td align="center" width="10%">' . $lang->badusers['addedby'] . '</td>
	<td align="center" width="10%">' . $lang->badusers['action'] . '</td>' : '') . '
	</tr>';
    if (mysql_num_rows ($query) == 0)
    {
      $str .= '<tr><td colspan="6">' . $lang->global['nothingfound'] . '</td></tr>';
    }
    else
    {
      while ($baduser = mysql_fetch_assoc ($query))
      {
        $ipaddress = (!is_mod ($usergroups) ? mask ($baduser['ipaddress'], 0 - 2) : $baduser['ipaddress']);
        $addedby = explode (':', $baduser['addedby']);
        $str .= '
			<tr>
			<td align="left" width="15%">' . htmlspecialchars_uni ($baduser['username']) . '</td>
			<td align="left" width="15%">' . htmlspecialchars_uni ($baduser['email']) . '</td>
			<td align="center" width="15%">' . htmlspecialchars_uni ($ipaddress) . '</td>
			<td align="left" width="35%"><font color="red">' . htmlspecialchars_uni ($baduser['comment']) . '</font></td>
			' . (is_mod ($usergroups) ? '<td align="center" width="10%"><a href="' . ts_seo ($addedby[1], $addedby[0]) . '">' . $addedby[0] . '</a></td>
			<td align="center" width="10%"><a href="' . $_SERVER['SCRIPT_NAME'] . '?act=edit&id=' . intval ($baduser['id']) . '"><img src="' . $pic_base_url . 'edit.gif" alt="' . $lang->badusers['edit'] . '" title="' . $lang->badusers['edit'] . '" border="0"></a>&nbsp;&nbsp;<a href="' . $_SERVER['SCRIPT_NAME'] . '?act=delete&id=' . intval ($baduser['id']) . '"><img src="' . $pic_base_url . 'delete.gif" alt="' . $lang->badusers['delete'] . '" title="' . $lang->badusers['delete'] . '" border="0"></a></td>' : '') . '
			</tr>';
      }
    }

    $str .= '</table></td></tr></table>';
    $str .= $pagerbottom;
    echo $str;
    stdfoot ();
  }

?>
