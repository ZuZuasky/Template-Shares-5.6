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

  define ('TSEM_VERSION', '0.1 by xam');
  $do = ($_GET['do'] ? $_GET['do'] : '');
  $months = array ('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  if (($do == 'delete' AND is_valid_id ($_GET['event_id'])))
  {
    $eventid = intval ($_GET['event_id']);
    sql_query ('DELETE from ts_events WHERE id = ' . sqlesc ($eventid));
  }

  if (($do == 'edit' AND is_valid_id ($_GET['event_id'])))
  {
    $eventid = intval ($_GET['event_id']);
    $query = sql_query ('SELECT title, event, date FROM ts_events WHERE id = ' . sqlesc ($eventid));
    if (mysql_num_rows ($query) < 1)
    {
      stderr ('Error', 'There is no event with this id');
    }
    else
    {
      $_event = mysql_fetch_assoc ($query);
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $title = trim ($_POST['title']);
      $event = trim ($_POST['event']);
      $date = htmlspecialchars_uni ($_POST['month']) . '-' . intval ($_POST['day']) . '-' . intval ($_POST['year']);
      if ((!empty ($title) AND !empty ($event)))
      {
        sql_query ('UPDATE ts_events SET title = ' . sqlesc ($title) . ', event = ' . sqlesc ($event) . ', date = ' . sqlesc ($date) . ' WHERE id = ' . sqlesc ($eventid));
        header ('Location: ' . $_this_script_);
        exit ();
      }
    }

    $_date = explode ('-', $_event['date']);
    $showmonths = '<select name="month">';
    foreach ($months as $_m)
    {
      $showmonths .= '<option value="' . $_m . '"' . (($_POST['month'] == $_m OR $_m == $_date[0]) ? ' selected="selected"' : '') . '>' . $_m . '</option>';
    }

    $showmonths .= '</select>';
    stdhead ('TS Event Manager - Edit Event');
    _form_header_open_ ('TS Event Manager - Edit Event');
    echo '
	<form method="POST" action="' . $_this_script_ . '&do=edit&event_id=' . $eventid . '">
	<input type="hidden" name="do" value="edit">
	<input type="hidden" name="event_id" value="' . $eventid . '">
	<tr>
		<td class="subheader">Title</td>
		<td><input type="text" size="50" name="title" value="' . htmlspecialchars_uni (($_POST['title'] ? $_POST['title'] : $_event['title'])) . '"></td>
	</tr>
		<td class="subheader">Event</td>
		<td><textarea name="event" cols="48" rows="5">' . htmlspecialchars_uni (($_POST['event'] ? $_POST['event'] : $_event['event'])) . '</textarea></td>
	</tr>
		<td class="subheader">Date</td>
		<td>' . $showmonths . ' - <input type="text" name="day" size="2" value="' . htmlspecialchars_uni (($_POST['day'] ? $_POST['day'] : $_date[1])) . '"> - <input type="text" name="year" size="4" value="' . htmlspecialchars_uni (($_POST['year'] ? $_POST['year'] : $_date[2])) . '"> (Example: January - 10 - 2008)</td>
	</tr>
	<tr><td colspan="2"><input type="submit" value="Save Event"> <input type="reset" value="Reset Event"></td></tr>
	</form>
	';
    _form_header_close_ ();
    stdfoot ();
    exit ();
  }

  if ($do == 'new')
  {
    $showmonths = '<select name="month">';
    foreach ($months as $_m)
    {
      $showmonths .= '<option value="' . $_m . '">' . $_m . '</option>';
    }

    $showmonths .= '</select>';
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $title = trim ($_POST['title']);
      $event = trim ($_POST['event']);
      $date = htmlspecialchars_uni ($_POST['month']) . '-' . intval ($_POST['day']) . '-' . intval ($_POST['year']);
      if ((!empty ($title) AND !empty ($event)))
      {
        sql_query ('INSERT INTO ts_events (title, event, date) VALUES (' . sqlesc ($title) . ', ' . sqlesc ($event) . ', ' . sqlesc ($date) . ')');
        header ('Location: ' . $_this_script_);
        exit ();
      }
    }

    stdhead ('TS Event Manager - New Event');
    _form_header_open_ ('TS Event Manager - New Event', 2);
    echo '
	<form method="POST" action="' . $_this_script_ . '&do=new">
	<input type="hidden" name="do" value="new">
	<tr>
		<td class="subheader">Title</td>
		<td><input type="text" size="50" name="title"></td>
	</tr>
		<td class="subheader">Event</td>
		<td><textarea name="event" cols="48" rows="5"></textarea></td>
	</tr>
		<td class="subheader">Date</td>
		<td>' . $showmonths . ' - <input type="text" name="day" size="2"> - <input type="text" name="year" size="4" value="' . date ('Y') . '"> (Example: January - 10 - 2008)</td>
	</tr>
	<tr><td colspan="2"><input type="submit" value="Save Event"> <input type="reset" value="Reset Event"></td></tr>
	</form>
	';
    _form_header_close_ ();
    stdfoot ();
    exit ();
  }

  stdhead ('TS Event Manager');
  $res = sql_query ('SELECT COUNT(*) FROM ts_events');
  $row = mysql_fetch_row ($res);
  $count = $row[0];
  list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $count, $_this_script_ . '&');
  echo jumpbutton (array ('New Event' => $_this_script_ . '&do=new'));
  echo $pagertop;
  _form_header_open_ ('TS Event Manager');
  $query = sql_query ('' . 'SELECT * FROM ts_events ' . $limit);
  echo '
<tr>
	<td class="subheader" width="15%" align="center">Date</td>
	<td class="subheader" width="30%" align="left">Title</td>
	<td class="subheader" width="50%" align="left">Event</td>
	<td class="subheader" width="5%" align="center">Action</td>
</tr>';
  while ($e = mysql_fetch_assoc ($query))
  {
    echo '
	<tr>
		<td align="center">' . htmlspecialchars_uni ($e['date']) . '</td>
		<td align="left">' . htmlspecialchars_uni ($e['title']) . '</td>
		<td align="left">' . htmlspecialchars_uni ($e['event']) . '</td>
		<td align="center"><a href="' . $_this_script_ . '&do=edit&event_id=' . $e['id'] . '">E</a> - <a href="' . $_this_script_ . '&do=delete&event_id=' . $e['id'] . '">X</a></td>
	</tr>
	';
  }

  _form_header_close_ ();
  echo $pagerbottom;
  stdfoot ();
?>
