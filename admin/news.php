<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_news_cache ()
  {
    require_once INC_PATH . '/functions_cache2.php';
    clear_cache ('news');
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('N_VERSION', '0.5 by xam');
  define ('NcodeImageResizer', true);
  $action = (isset ($_GET['action']) ? $_GET['action'] : '');
  $returnto = 'admin/index.php?act=news';
  if ($action == 'delete')
  {
    $newsid = 0 + $_GET['newsid'];
    int_check ($newsid, true);
    $sure = 0 + $_GET['sure'];
    if (!$sure)
    {
      stderr ('Delete news item', 'Do you really want to delete a news item? Click <a href=\'' . $_this_script_ . ('' . '&action=delete&newsid=' . $newsid . '&sure=1\'>here</a> if you are sure.'), false);
    }

    (sql_query ('DELETE FROM news WHERE id=' . sqlesc ($newsid)) OR sqlerr (__FILE__, 41));
    update_news_cache ();
    redirect ($returnto, 'News item was deleted successfully.');
  }

  if ($action == 'edit')
  {
    $newsid = 0 + $_GET['newsid'];
    int_check ($newsid, true);
    ($res = sql_query ('SELECT * FROM news WHERE id=' . sqlesc ($newsid)) OR sqlerr (__FILE__, 52));
    if (mysql_num_rows ($res) != 1)
    {
      stderr ('Error', 'No news item with ID');
    }

    $arr = mysql_fetch_assoc ($res);
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if (($_POST['previewpost'] AND !empty ($_POST['message'])))
      {
        $avatar = get_user_avatar ($CURUSER['avatar']);
        $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
			<tr>
			<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
			</tr>
			<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
			</tr></table><br />';
      }

      if (isset ($_POST['submit']))
      {
        $body = trim ($_POST['message']);
        if ($body == '')
        {
          stderr ('Error', 'Body cannot be empty!');
        }

        $title = trim ($_POST['subject']);
        if ($title == '')
        {
          stderr ('Error', 'Title cannot be empty!');
        }

        $body = sqlesc ($body);
        $editedat = sqlesc (get_date_time ());
        $title = sqlesc ($title);
        (sql_query ('' . 'UPDATE news SET body=' . $body . ', title=' . $title . ' WHERE id=' . sqlesc ($newsid)) OR sqlerr (__FILE__, 83));
        update_news_cache ();
        redirect ('index.php', 'News item was edited successfully.');
      }
    }

    stdhead ('Editing Site news');
    define ('IN_EDITOR', true);
    include_once INC_PATH . '/editor.php';
    $str = '<form method="post" name="compose" action="' . $_this_script_ . '&action=edit&newsid=' . $newsid . '">';
    if (!empty ($prvp))
    {
      $str .= $prvp;
    }

    $str .= insert_editor (true, $arr['title'], (!empty ($_POST['message']) ? $_POST['message'] : $arr['body']), 'Editing Site news', 'Edit New Item');
    $str .= '</form>';
    echo $str;
    stdfoot ();
    exit ();
  }

  if (($_POST['previewpost'] AND !empty ($_POST['message'])))
  {
    $avatar = get_user_avatar ($CURUSER['avatar']);
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
	</tr></table><br />';
  }

  if (isset ($_POST['submit']))
  {
    $body = trim ($_POST['message']);
    if (!$body)
    {
      stderr ('Error', 'The news item cannot be empty!');
    }

    $title = trim ($_POST['subject']);
    if (!$title)
    {
      $title = 'TS SE v5.6 has been released!';
    }

    $added = $_POST['added'];
    if (!$added)
    {
      $added = get_date_time ();
    }

    (sql_query ('INSERT INTO news (userid, added, body, title) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($added) . ', ' . sqlesc ($body) . ', ' . sqlesc ($title) . ')') OR sqlerr (__FILE__, 133));
    update_news_cache ();
    redirect ($returnto, 'News item was added successfully.');
    exit ();
  }

  stdhead ('Site news');
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '<form method="post" name="compose" action="' . $_this_script_ . '">';
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  $str .= insert_editor (true, $_POST['subject'], (!empty ($_POST['message']) ? $_POST['message'] : ''), 'Submit News Item', 'Submit News Item');
  $str .= '</form>';
  echo $str;
  ($res = sql_query ('SELECT n.*, u.username,u.donor, g.namestyle FROM news n LEFT JOIN users u ON (u.id=n.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY n.added DESC') OR sqlerr (__FILE__, 150));
  if (0 < mysql_num_rows ($res))
  {
    begin_main_frame ();
    begin_frame ();
    require_once INC_PATH . '/functions_mkprettytime.php';
    while ($arr = mysql_fetch_assoc ($res))
    {
      $newsid = $arr['id'];
      $body = format_comment ($arr['body']);
      $title = htmlspecialchars_uni ($arr['title']);
      $userid = 0 + $arr['userid'];
      $added = $arr['added'] . ' GMT (' . mkprettytime (time () - strtotime ($arr['added'])) . ')';
      $postername = get_user_color ($arr['username'], $arr['namestyle']);
      if ($postername == '')
      {
        $by = ('' . 'unknown[' . $userid . ']');
      }
      else
      {
        $by = '' . '<a href=' . $BASEURL . '/userdetails.php?id=' . $userid . '><b>' . $postername . '</b></a>' . ($arr['donor'] == 'yes' ? '<img src=' . $BASEURL . '/' . $pic_base_url . 'star.gif alt=\'Donor\'>' : '');
      }

      print '<p class=sub><table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>';
      print '' . $added . '&nbsp;---&nbsp;by&nbsp' . $by;
      print ' - [<a href=' . $_this_script_ . ('' . '&action=edit&newsid=' . $newsid . '><b>Edit</b></a>]');
      print ' - [<a href=' . $_this_script_ . ('' . '&action=delete&newsid=' . $newsid . '><b>Delete</b></a>]');
      print '</td></tr></table></p>
';
      begin_table (true);
      print '' . '<tr valign=top><td class=comment><b>' . $title . '</b><br />' . $body . '</td></tr>
';
      end_table ();
    }

    end_frame ();
    end_main_frame ();
  }
  else
  {
    stdmsg ('Sorry', 'No news available!');
  }

  stdfoot ();
  exit ();
?>
