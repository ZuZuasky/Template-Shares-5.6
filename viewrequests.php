<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function check_r_count ()
  {
    global $lang;
    global $CURUSER;
    global $error;
    global $usergroups;
    global $is_mod;
    $query = sql_query ('SELECT COUNT(id) as total FROM requests WHERE filled=\'no\' AND userid = ' . sqlesc ($CURUSER['id']));
    $count = @mysql_result ($query, 0, 'total');
    if (((0 < $count AND !$is_mod) AND $usergroups['isvipgroup'] != 'yes'))
    {
      $error[] = $lang->requests['can_not_add'];
      return null;
    }

    if ($usergroups['canrequest'] != 'yes')
    {
      $error[] = $lang->requests['no_perm'];
      return null;
    }

    return '';
  }

  function check_rid ()
  {
    global $rid;
    global $lang;
    $query = sql_query ('SELECT id FROM requests WHERE id = ' . sqlesc ($rid));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ($lang->global['error'], $lang->requests['noreqid']);
      return null;
    }

    return '';
  }

  function check_rid_permission ()
  {
    global $rid;
    global $is_mod;
    global $CURUSER;
    $query = sql_query ('SELECT userid FROM requests WHERE id = ' . sqlesc ($rid));
    $userid = mysql_result ($query, 0, 'userid');
    if (($CURUSER['id'] != $userid AND !$is_mod))
    {
      print_no_permission ();
      return null;
    }

    return '';
  }

  function check_fill_permission ()
  {
    global $is_mod;
    global $usergroups;
    global $CURUSER;
    if (($is_mod OR $usergroups['canupload'] == 'yes'))
    {
      return '';
    }

    print_no_permission ();
  }

  function show_request_errors ()
  {
    global $error;
    global $lang;
    if (0 < count ($error))
    {
      $errors = implode ('<br />', $error);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  function unesc ($x)
  {
    if (get_magic_quotes_gpc ())
    {
      return stripslashes ($x);
    }

    return $x;
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('VR_VERSION', '2.2.5 ');
  define ('NcodeImageResizer', true);
  $lang->load ('requests');
  $is_mod = is_mod ($usergroups);
  if (($rqs == 'no' AND !$is_mod))
  {
    stderr ($lang->global['error'], $lang->requests['offline']);
  }

  $do = (isset ($_GET['do']) ? trim ($_GET['do']) : (isset ($_POST['do']) ? trim ($_POST['do']) : ''));
  $rid = (isset ($_GET['rid']) ? intval ($_GET['rid']) : (isset ($_POST['rid']) ? intval ($_POST['rid']) : 0));
  if (($do == 'delete_request' AND is_valid_id ($rid)))
  {
    check_rid ();
    check_rid_permission ();
    sql_query ('DELETE FROM requests WHERE id = ' . sqlesc ($rid));
    sql_query ('DELETE FROM addedrequests WHERE requestid = ' . sqlesc ($rid));
  }

  if (($do == 'add_vote' AND is_valid_id ($rid)))
  {
    check_rid ();
    $query = sql_query ('SELECT filled FROM requests WHERE id = ' . sqlesc ($rid));
    $is_filled = mysql_result ($query, 0, 'filled');
    if ($is_filled == 'yes')
    {
      $error[] = $lang->requests['not_voted_yet'];
    }
    else
    {
      $query = sql_query ('SELECT userid FROM addedrequests WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND requestid = ' . sqlesc ($rid));
      if (0 < mysql_num_rows ($query))
      {
        $error[] = $lang->requests['already_voted'];
      }
      else
      {
        sql_query ('UPDATE requests SET hits = hits + 1 WHERE id = ' . sqlesc ($rid));
        sql_query ('INSERT INTO addedrequests (requestid, userid) VALUES (' . sqlesc ($rid) . ', ' . sqlesc ($CURUSER['id']) . ')');
      }
    }
  }

  if (($do == 'remove_vote' AND is_valid_id ($rid)))
  {
    check_rid ();
    $query = sql_query ('SELECT filled FROM requests WHERE id = ' . sqlesc ($rid));
    $is_filled = mysql_result ($query, 0, 'filled');
    if ($is_filled == 'yes')
    {
      $error[] = $lang->requests['not_voted_yet'];
    }
    else
    {
      $query = sql_query ('SELECT userid FROM addedrequests WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND requestid = ' . sqlesc ($rid));
      if (0 < mysql_num_rows ($query))
      {
        sql_query ('UPDATE requests SET hits = hits - 1 WHERE id = ' . sqlesc ($rid));
        sql_query ('DELETE FROM addedrequests WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND requestid = ' . sqlesc ($rid));
      }
      else
      {
        $error[] = $lang->requests['not_voted_yet'];
      }
    }
  }

  if (($do == 'edit_request' AND is_valid_id ($rid)))
  {
    check_rid ();
    check_rid_permission ();
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      if (($_POST['previewpost'] AND !empty ($_POST['message'])))
      {
        $avatar = get_user_avatar ($CURUSER['avatar']);
        $prvp = '
			<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
				<tr>
					<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
				</tr>
				<tr>
					<td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
				</tr>
			</table>
			<br />';
      }

      if (isset ($_POST['submit']))
      {
        $title = trim ($_POST['subject']);
        $descr = trim ($_POST['message']);
        $cat = intval ($_POST['category']);
        if ($is_mod)
        {
          $filled = ($_POST['filled'] == 'yes' ? 'yes' : 'no');
          $filledurl = $_POST['filledurl'];
        }

        if ((empty ($title) OR strlen ($title) < 3))
        {
          $error[] = $lang->requests['error1'];
        }

        if ((empty ($descr) OR strlen ($descr) < 3))
        {
          $error[] = $lang->requests['error3'];
        }

        if (!is_valid_id ($cat))
        {
          $error[] = $lang->requests['error2'];
        }

        $query = sql_query ('SELECT id FROM categories WHERE id = ' . sqlesc ($cat));
        if (mysql_num_rows ($query) == 0)
        {
          $error[] = $lang->requests['error2'];
        }

        $set = '';
        if (($filled == 'yes' AND $is_mod))
        {
          if (!preg_match ('#^' . preg_quote ('' . $BASEURL . '/details.php?id=') . ('' . '([0-9]{1,6})$#'), $filledurl))
          {
            $error[] = sprintf ($lang->requests['error6'], $BASEURL);
          }
          else
          {
            $set = ', filled=' . sqlesc ($filled) . ', filledurl = ' . sqlesc ($filledurl);
          }
        }
        else
        {
          if ($is_mod)
          {
            $set = ', filled=' . sqlesc ($filled) . ', filledurl = \'\'';
          }
        }

        if (count ($error) == 0)
        {
          (sql_query ('UPDATE requests SET request = ' . sqlesc ($title) . ', descr = ' . sqlesc ($descr) . ', cat = ' . sqlesc ($cat) . $set . ' WHERE id = ' . sqlesc ($rid)) OR sqlerr (__FILE__, 257));
          redirect ('viewrequests.php?do=view_request&rid=' . $rid);
          exit ();
        }
      }
    }

    define ('IN_EDITOR', true);
    include_once INC_PATH . '/editor.php';
    stdhead ($lang->requests['rhead'] . ' - ' . $lang->requests['field11']);
    show_request_errors ();
    $query = sql_query ('SELECT request, descr, cat, filled, filledurl FROM requests WHERE id = ' . sqlesc ($rid));
    $request = mysql_fetch_assoc ($query);
    $str = '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=edit_request&rid=' . $rid . '">';
    if (!empty ($prvp))
    {
      $str .= $prvp;
    }

    require_once INC_PATH . '/functions_category.php';
    $catdropdown = ts_category_list ('category', intval (($_POST['category'] ? $_POST['category'] : $request['cat'])));
    $postoptionstitle = array ('1' => $lang->global['type'], '2' => ($is_mod ? $lang->requests['filled'] : ''), '3' => ($is_mod ? $lang->requests['filledurl'] : ''));
    $postoptions = array ('1' => $catdropdown, '2' => ($is_mod ? '<select name="filled"><option values="yes"' . (($request['filled'] == 'yes' OR $_POST['filled'] == 'yes') ? ' selected="selected"' : '') . '>' . $lang->global['yes'] . '</option><option values="no"' . (($request['filled'] == 'no' OR $_POST['filled'] == 'no') ? ' selected="selected"' : '') . '>' . $lang->global['no'] . '</option></select>' : ''), '3' => ($is_mod ? '<input type="text" size="50" name="filledurl" value="' . htmlspecialchars_uni (($_POST['filledurl'] ? $_POST['filledurl'] : $request['filledurl'])) . '">' : ''));
    $str .= insert_editor (true, (!empty ($_POST['subject']) ? $_POST['subject'] : unesc ($request['request'])), (!empty ($_POST['message']) ? $_POST['message'] : $request['descr']), $lang->requests['rhead3'], $lang->requests['rhead3'] . ': ' . htmlspecialchars_uni ($request['request']), $postoptionstitle, $postoptions);
    $str .= '</form>';
    echo $str;
    stdfoot ();
    exit ();
  }

  if ($do == 'add_request')
  {
    check_r_count ();
    if (count ($error) == 0)
    {
      if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
      {
        if (($_POST['previewpost'] AND !empty ($_POST['message'])))
        {
          $avatar = get_user_avatar ($CURUSER['avatar']);
          $prvp = '
				<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
					<tr>
						<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
					</tr>
					<tr>
						<td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td>
						<td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
					</tr>
				</table>
				<br />';
        }

        if (isset ($_POST['submit']))
        {
          $title = trim ($_POST['subject']);
          $descr = trim ($_POST['message']);
          $cat = intval ($_POST['category']);
          if ((empty ($title) OR strlen ($title) < 3))
          {
            $error[] = $lang->requests['error1'];
          }

          if ((empty ($descr) OR strlen ($descr) < 3))
          {
            $error[] = $lang->requests['error3'];
          }

          if (!is_valid_id ($cat))
          {
            $error[] = $lang->requests['error2'];
          }

          $query = sql_query ('SELECT id FROM categories WHERE id = ' . sqlesc ($cat));
          if (mysql_num_rows ($query) == 0)
          {
            $error[] = $lang->requests['error2'];
          }

          if (count ($error) == 0)
          {
            (sql_query ('INSERT INTO requests (userid,request,descr,added,hits,cat) VALUES (' . sqlesc ($CURUSER['id']) . ',' . sqlesc ($title) . ',' . sqlesc ($descr) . ',' . sqlesc (get_date_time ()) . ',1,' . sqlesc ($cat) . ')') OR sqlerr (__FILE__, 348));
            $rid = mysql_insert_id ();
            (sql_query ('' . 'INSERT INTO addedrequests VALUES(0, ' . $rid . ', ' . sqlesc ($CURUSER['id']) . ')') OR sqlerr (__FILE__, 350));
            (sql_query ('UPDATE users SET seedbonus = seedbonus-5.0 WHERE id = ' . sqlesc ($CURUSER['id'])) OR sqlerr (__FILE__, 351));
            write_log ('' . 'Request (' . $title . ') was added to the Request section by ' . $CURUSER['username']);
            if (($tsshoutbot == 'yes' AND preg_match ('#request#', $tsshoutboxoptions)))
            {
              $shoutbOT = sprintf ($lang->requests['shoutbOT'], '[URL=' . $BASEURL . '/viewrequests.php?do=view_request&rid=' . $rid . ']' . $title . '[/URL]', '[URL=' . $BASEURL . '/userdetails.php?id=' . $CURUSER['id'] . ']' . $CURUSER['username'] . '[/URL]');
              $shout_sql = 'INSERT INTO shoutbox (userid, date, content) VALUES (\'999999999\', \'' . TIMENOW . '\', ' . sqlesc ('{systemnotice}' . $shoutbOT) . ')';
              $shout_result = sql_query ($shout_sql);
            }

            redirect ('viewrequests.php?do=view_request&rid=' . $rid);
            exit ();
          }
        }
      }

      require_once INC_PATH . '/functions_category.php';
      define ('IN_EDITOR', true);
      include_once INC_PATH . '/editor.php';
      stdhead ($lang->requests['rhead'] . ' - ' . $lang->requests['makereq']);
      show_request_errors ();
      $str = '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=add_request">';
      if (!empty ($prvp))
      {
        $str .= $prvp;
      }

      require_once INC_PATH . '/functions_category.php';
      $catdropdown = ts_category_list ('category', intval (($_POST['category'] ? $_POST['category'] : 0)));
      $postoptionstitle = array ('1' => $lang->global['type']);
      $postoptions = array ('1' => $catdropdown);
      $str .= insert_editor (true, (!empty ($_POST['subject']) ? $_POST['subject'] : ''), (!empty ($_POST['message']) ? $_POST['message'] : ''), $lang->requests['makereq'], $lang->requests['makereq'], $postoptionstitle, $postoptions);
      $str .= '</form>';
      echo $str;
      stdfoot ();
      exit ();
    }
  }

  if (($do == 'view_request' AND is_valid_id ($rid)))
  {
    check_rid ();
    ($query = sql_query ('SELECT r.id, r.userid, r.filledby, r.filledurl, r.request, r.descr, r.added, r.hits, r.cat, r.filled, c.image as category_image, c.name as category_name, u.username, g.namestyle FROM requests r LEFT JOIN categories c ON (r.cat=c.id) LEFT JOIN users u ON (r.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE r.id = ' . sqlesc ($rid)) OR sqlerr (__FILE__, 401));
    $request = mysql_fetch_assoc ($query);
    stdhead ($lang->requests['rhead'] . ' - ' . $lang->requests['viewreq'] . ' : ' . htmlspecialchars_uni ($request['request']));
    $delete_image = (($is_mod OR $request['userid'] == $CURUSER['id']) ? '[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=delete_request&amp;rid=' . $request['id'] . '" onclick="return confirm_delete_request()">' . $lang->requests['field12'] . '</a>]&nbsp;&nbsp;' : '');
    $edit_image = (($is_mod OR $request['userid'] == $CURUSER['id']) ? '[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=edit_request&amp;rid=' . $request['id'] . '">' . $lang->requests['field11'] . '</a>]&nbsp;&nbsp;' : '');
    $fillrequest = (($request['filled'] == 'no' AND ($is_mod OR $usergroups['canupload'] == 'yes')) ? '[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=fill_request&amp;rid=' . $request['id'] . '">' . $lang->requests['field18'] . '</a>]&nbsp;&nbsp;' : '');
    $reset_request = (($request['filled'] == 'yes' AND ($is_mod OR $CURUSER['id'] == $request['userid'])) ? '[<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=reset_request&amp;rid=' . $request['id'] . '">' . $lang->requests['field13'] . '</a>]&nbsp;&nbsp;' : '');
    $back = '[<a href="' . $_SERVER['SCRIPT_NAME'] . '">' . $lang->requests['return'] . '</a>]&nbsp;&nbsp;';
    echo '
	<script type="text/javascript">
		function confirm_delete_request()
		{
			var confirm_delete = confirm("' . $lang->requests['are_you_sure'] . '");
			if (confirm_delete)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	</script>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead" colspan="2" align="center">' . $lang->requests['viewreq'] . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['rtitle'] . '</td>
			<td align="left" width="80%">' . htmlspecialchars_uni ($request['request']) . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['field9'] . '</td>
			<td align="left" width="80%"><a href="' . $BASEURL . '/userdetails.php?id=' . $request['userid'] . '">' . get_user_color ($request['username'], $request['namestyle']) . '</a></td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['field8'] . '</td>
			<td align="left" width="80%">' . my_datee ($dateformat, $request['added']) . ' ' . my_datee ($timeformat, $request['added']) . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['field6'] . '</td>
			<td align="left" width="80%">' . $request['category_name'] . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['votes'] . '</td>
			<td align="left" width="80%">' . $request['hits'] . '</td>
		</tr>
		<tr>
			<td align="right" width="20%" class="subheader">' . $lang->requests['field5'] . '</td>
			<td align="left" width="80%">' . format_comment ($request['descr']) . '</td>
		</tr>';
    if ($request['filled'] == 'yes')
    {
      ($query = sql_query ('SELECT u.username, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.id=' . sqlesc ($request['filledby'])) OR sqlerr (__FILE__, 455));
      $fillerdetails = mysql_fetch_assoc ($query);
      echo '
			<tr>
				<td align="right" width="20%" class="subheader">' . $lang->requests['filledby'] . '</td>
				<td align="left" width="80%"><a href="' . $BASEURL . '/userdetails.php?id=' . $request['filledby'] . '">' . get_user_color ($fillerdetails['username'], $fillerdetails['namestyle']) . '</a>&nbsp;&nbsp;[<a href="' . $request['filledurl'] . '">' . $lang->requests['view_details'] . '</a>]</td>
			</tr>';
    }

    echo '
		<tr>
			<td colspan="2" align="center" class="subheader">' . $back . $delete_image . $edit_image . $fillrequest . $reset_request . '</td>
		</tr>
	</table>
	';
    stdfoot ();
    exit ();
  }

  if (($do == 'reset_request' AND is_valid_id ($rid)))
  {
    check_rid ();
    check_rid_permission ();
    sql_query ('UPDATE requests SET filledby = 0, filledurl = \'\', filled = \'no\' WHERE id = ' . sqlesc ($rid));
  }

  if (($do == 'fill_request' AND is_valid_id ($rid)))
  {
    check_rid ();
    check_fill_permission ();
    if (strtoupper ($_SERVER['REQUEST_METHOD'] == 'POST'))
    {
      $torrentid = intval ($_POST['torrentid']);
      $query = sql_query ('SELECT id FROM torrents WHERE id = ' . sqlesc ($torrentid));
      if (mysql_num_rows ($query) == 0)
      {
        $error[] = $lang->global['notorrentid'];
      }
      else
      {
        $filledurl = '' . $BASEURL . '/details.php?id=' . $torrentid;
        ($res = sql_query ('SELECT users.username, requests.userid, requests.filled, requests.request FROM requests INNER JOIN users ON (requests.userid = users.id) WHERE requests.id = ' . sqlesc ($rid)) OR sqlerr (__FILE__, 495));
        $arr = mysql_fetch_assoc ($res);
        if ($arr['filled'] == 'no')
        {
          $msg = sprintf ($lang->requests['filledmsg'], $arr['request'], $CURUSER['username'], $filledurl, $BASEURL, $rid);
          (sql_query ('UPDATE requests SET filled = \'yes\', filledurl = ' . sqlesc ($filledurl) . ', filledby = ' . sqlesc ($CURUSER['id']) . ' WHERE id = ' . sqlesc ($rid)) OR sqlerr (__FILE__, 501));
          require_once INC_PATH . '/functions_pm.php';
          send_pm ($arr['userid'], $msg, $lang->requests['filledmsgsubject']);
          (sql_query ('UPDATE users SET seedbonus = seedbonus+10.0 WHERE id = ' . sqlesc ($CURUSER['id'])) OR sqlerr (__FILE__, 507));
          ($res = sql_query ('SELECT userid FROM addedrequests WHERE requestid = ' . sqlesc ($rid) . ' AND userid != ' . sqlesc ($arr['userid'])) OR sqlerr (__FILE__, 510));
          $pn_msg = sqlesc (sprintf ($lang->requests['filledvotemsg'], $arr['request'], $CURUSER['username'], $filledurl));
          $subject = sqlesc (sprintf ($lang->requests['filledvotesubject'], $arr['request']));
          while ($row = mysql_fetch_array ($res))
          {
            send_pm ($row['userid'], $pn_msg, $subject);
          }

          sql_query ('UPDATE torrents SET isrequest = \'yes\' WHERE id = ' . sqlesc ($torrentid));
        }

        redirect ('' . 'viewrequests.php?do=view_request&rid=' . $rid);
        exit ();
      }
    }

    stdhead ($lang->requests['rhead'] . ' - ' . $lang->requests['field18']);
    show_request_errors ();
    echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=fill_request&rid=' . $rid . '">
		<input type="hidden" name="do" value="fill_request">
		<input type="hidden" name="rid" value="rid">
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td class="thead" colspan="2" align="center">' . $lang->requests['rhead'] . ' - ' . $lang->requests['field18'] . '</td>
		</tr>
		<tr>
			<td align="right" width="25%">' . $lang->requests['field17'] . '</td>
			<td align="left"><input type="text" size="5" value="' . $torrentid . '" name="torrentid"> <input type="submit" value="' . $lang->requests['field18'] . '"></td>
		</tr>
		';
    echo '</table></form>';
    stdfoot ();
    exit ();
  }

  $link = $query1 = $query2 = '';
  if ($do == 'search_request')
  {
    $searchwords = trim ($_POST['searchwords']);
    if ((!empty ($searchwords) AND 2 < strlen ($searchwords)))
    {
      $query1 = ' WHERE (request LIKE ' . sqlesc ('%' . $searchwords . '%') . ' OR descr LIKE ' . sqlesc ('%' . $searchwords . '%') . ')';
      $query2 = ' WHERE (r.request LIKE ' . sqlesc ('%' . $searchwords . '%') . ' OR r.descr LIKE ' . sqlesc ('%' . $searchwords . '%') . ')';
      $link = 'do=search_request&searchwords=' . htmlspecialchars_uni ($searchwords) . '&';
    }
    else
    {
      $error[] = $lang->requests['searcherror2'];
    }
  }

  ($query = sql_query ('SELECT COUNT(id) as total FROM requests' . $query1) OR sqlerr (__FILE__, 560));
  $count = mysql_result ($query, 0, 'total');
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_SERVER['SCRIPT_NAME'] . '?' . $link);
  stdhead ($lang->requests['rhead']);
  show_request_errors ();
  $where = array ($lang->requests['makereq'] => $_SERVER['SCRIPT_NAME'] . '?do=add_request');
  echo '
<script type="text/javascript">
	function confirm_delete_request()
	{
		var confirm_delete = confirm("' . $lang->requests['are_you_sure'] . '");
		if (confirm_delete)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?do=search_request">
<input type="hidden" name="do" value="search_request">
<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="thead" align="center">' . $lang->requests['searchreq'] . '</td>
	</tr>
	<tr>
		<td align="left">' . $lang->requests['words'] . ' <input type="text" size="50" value="' . htmlspecialchars_uni ($searchwords) . '" name="searchwords"> <input type="submit" value="' . $lang->requests['searchreq'] . '"></td>
	</tr>
</table>
</form>
<br />
' . ($usergroups['canrequest'] == 'yes' ? jumpbutton ($where) : '');
  ($query = sql_query ('SELECT r.id, r.userid, r.request, r.descr, r.added, r.hits, r.cat, r.filled, c.image as category_image, c.name as category_name, u.username, g.namestyle FROM requests r LEFT JOIN categories c ON (r.cat=c.id) LEFT JOIN users u ON (r.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid)' . $query2 . ('' . ' ORDER BY r.added DESC ' . $limit)) OR sqlerr (__FILE__, 599));
  if (mysql_num_rows ($query) == 0)
  {
    echo '
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr>
			<td class="thead" align="center">' . $lang->requests['searchreq'] . '</td>
		</tr>
	<tr>
		<td>' . $lang->requests['searcherror'] . '</td>
	</tr>
	</table>';
    stdfoot ();
    exit ();
  }
  else
  {
    echo '
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr>
			<td class="thead" colspan="6" align="center">' . $lang->requests['rhead'] . '</td>
		</tr>
		<tr>
			<td width="1%" style="padding: 1px;" class="subheader"></td>
			<td width="1%" style="padding: 1px;" class="subheader"><span class="small">' . $lang->requests['field6'] . '</span></td>
			<td width="60%" style="padding: 1px;" class="subheader"><span class="small">' . $lang->requests['field7'] . '</span></td>
			<td width="15%" style="padding: 1px;" class="subheader"><span class="small">' . $lang->requests['field9'] . ' / ' . $lang->requests['field8'] . '</span></td>
			<td width="10%" style="padding: 1px;" class="subheader" align="center"><span class="small">' . $lang->requests['votes'] . '</span></td>
			<td width="13%" style="padding: 1px;" class="subheader" align="center"><span class="small">' . $lang->requests['action'] . '</span></td>
		</tr>';
  }

  while ($request = mysql_fetch_assoc ($query))
  {
    $fillimage = ($request['filled'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'filled.gif" width="8" height="36" border="0" alt="' . $lang->requests['f_image_filled'] . '" title="' . $lang->requests['f_image_filled'] . '">' : '<img src="' . $BASEURL . '/' . $pic_base_url . 'not_filled.gif" width="8" height="36" border="0" alt="' . $lang->requests['f_image_not_filled'] . '" title="' . $lang->requests['f_image_not_filled'] . '">');
    $category_image = '<img src="' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . $request['category_image'] . '" border="0" width="48" height="36" alt="' . $request['category_name'] . '" title="' . $request['category_name'] . '">';
    $title = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=view_request&amp;rid=' . $request['id'] . '"><b>' . htmlspecialchars_uni ($request['request']) . '</b></a>';
    $desc = '<br />' . htmlspecialchars_uni (cutename ($request['descr'], 100));
    $requester = '<a href="' . $BASEURL . '/userdetails.php?id=' . $request['userid'] . '">' . get_user_color ($request['username'], $request['namestyle']) . '</a>';
    $added = '<br />' . my_datee ($dateformat, $request['added']) . ' ' . my_datee ($timeformat, $request['added']);
    $votes = ts_nf ($request['hits']);
    $vote_image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=add_vote&amp;rid=' . $request['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'add_vote.gif" border="0" width="10" height="12" alt="' . $lang->requests['add_vote'] . '" title="' . $lang->requests['add_vote'] . '"></a>&nbsp;&nbsp;';
    $remove_vote_image = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=remove_vote&amp;rid=' . $request['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'remove_vote.gif" border="0" width="10" height="12" alt="' . $lang->requests['remove_vote'] . '" title="' . $lang->requests['remove_vote'] . '"></a>&nbsp;&nbsp;';
    $delete_image = (($is_mod OR $request['userid'] == $CURUSER['id']) ? '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=delete_request&amp;rid=' . $request['id'] . '" onclick="return confirm_delete_request()"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" border="0" width="10" height="12" alt="' . $lang->requests['field12'] . '" title="' . $lang->requests['field12'] . '"></a>&nbsp;&nbsp;' : '');
    $edit_image = (($is_mod OR $request['userid'] == $CURUSER['id']) ? '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=edit_request&amp;rid=' . $request['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" border="0" width="10" height="12" alt="' . $lang->requests['field11'] . '" title="' . $lang->requests['field11'] . '"></a>&nbsp;&nbsp;' : '');
    $fillrequest = (($request['filled'] == 'no' AND ($is_mod OR $usergroups['canupload'] == 'yes')) ? '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=fill_request&amp;rid=' . $request['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'input_true.gif" border="0" width="10" height="12" alt="' . $lang->requests['field18'] . '" title="' . $lang->requests['field18'] . '"></a>&nbsp;&nbsp;' : '');
    $reset_request = (($request['filled'] == 'yes' AND ($is_mod OR $CURUSER['id'] == $request['userid'])) ? '<a href="' . $_SERVER['SCRIPT_NAME'] . '?do=reset_request&amp;rid=' . $request['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'isnuked.gif" border="0" width="10" height="12" alt="' . $lang->requests['field13'] . '" title="' . $lang->requests['field13'] . '"></a>&nbsp;&nbsp;' : '');
    echo '
	<tr>
		<td>' . $fillimage . '</td>
		<td>' . $category_image . '</td>
		<td valign="top">' . $title . $desc . '</td>
		<td valign="top">' . $requester . $added . '</td>
		<td align="center">' . $votes . '</td>
		<td align="center">' . $vote_image . $remove_vote_image . $fillrequest . $delete_image . $reset_request . $edit_image . '</td>
	</tr>';
  }

  echo '</table>' . $pagerbottom;
  stdfoot ();
?>
