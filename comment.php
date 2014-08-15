<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function allowcomments ($torrentid = 0)
  {
    global $is_mod;
    $query = @sql_query ('' . 'SELECT allowcomments FROM torrents WHERE id = ' . $torrentid);
    $allowcomments = @mysql_result ($query, 0, 'allowcomments');
    return (($allowcomments != 'yes' AND !$is_mod) ? false : true);
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  include_once INC_PATH . '/readconfig_kps.php';
  define ('C_VERSION', '1.8.8');
  define ('NcodeImageResizer', true);
  if ($usergroups['cancomment'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  ($query = sql_query ('SELECT cancomment FROM ts_u_perm WHERE userid = ' . sqlesc ($CURUSER['id'])) OR sqlerr (__FILE__, 32));
  if (0 < mysql_num_rows ($query))
  {
    $commentperm = mysql_fetch_assoc ($query);
    if ($commentperm['cancomment'] == '0')
    {
      print_no_permission ();
      exit ();
    }
  }

  $lang->load ('comment');
  include INC_PATH . '/functions_quick_editor.php';
  require INC_PATH . '/commenttable.php';
  $is_mod = is_mod ($usergroups);
  $action = htmlspecialchars_uni ($_GET['action']);
  $msgtext = trim ($_POST['message']);
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

  if ($action == 'close')
  {
    $torrentid = 0 + $_GET['tid'];
    int_check ($torrentid, true);
    sql_query ('' . 'UPDATE torrents SET allowcomments = \'no\' WHERE id = ' . $torrentid);
    redirect ('' . 'details.php?id=' . $torrentid . '&tab=comments');
    exit ();
  }

  if ($action == 'open')
  {
    $torrentid = 0 + $_GET['tid'];
    int_check ($torrentid, true);
    sql_query ('' . 'UPDATE torrents SET allowcomments = \'yes\' WHERE id = ' . $torrentid);
    redirect ('' . 'details.php?id=' . $torrentid . '&tab=comments');
    exit ();
  }

  if ($action == 'add')
  {
    $torrentid = 0 + $_GET['tid'];
    int_check ($torrentid, true);
    if (allowcomments ($torrentid) == false)
    {
      stderr ($lang->global['error'], $lang->comment['closed']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if (isset ($_POST['submit']))
      {
        $query = sql_query ('SELECT added FROM comments WHERE user = ' . sqlesc ($CURUSER['id']) . ' ORDER by added DESC LIMIT 1');
        if (0 < mysql_num_rows ($query))
        {
          $last_comment = mysql_result ($query, 0, 'added');
        }

        if ((isset ($_POST['ctype']) AND $_POST['ctype'] == 'quickcomment'))
        {
          $rpage = '';
          if ((isset ($_POST['page']) AND is_valid_id ($_POST['page'])))
          {
            $rpage = '&page=' . intval ($_POST['page']);
          }

          $returnto = '' . $BASEURL . '/details.php?id=' . $torrentid . $rpage . '&tab=comments';
          $rt = '#startquickcomment';
          $floodmsg = flood_check ($lang->comment['floodcomment'], $last_comment, true);
        }
        else
        {
          flood_check ($lang->comment['floodcomment'], $last_comment);
        }

        ($res = sql_query ('SELECT name, owner FROM torrents WHERE id = ' . sqlesc ($torrentid)) OR sqlerr (__FILE__, 118));
        $arr = mysql_fetch_array ($res);
        if (!empty ($floodmsg))
        {
          $returnto = '' . $returnto . '&cerror=3' . $rt;
          header ('' . 'Location: ' . $returnto);
          exit ();
        }

        if (!$arr)
        {
          if (isset ($returnto))
          {
            $returnto = '' . $returnto . '&cerror=1' . $rt;
            header ('' . 'Location: ' . $returnto);
            exit ();
          }
          else
          {
            stderr ($lang->global['error'], $lang->global['notorrentid']);
          }
        }

        if (!$msgtext)
        {
          if (isset ($returnto))
          {
            $returnto = '' . $returnto . '&tab=comments&cerror=2' . $rt;
            header ('' . 'Location: ' . $returnto);
            exit ();
          }
          else
          {
            stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
          }
        }

        $query = sql_query ('SELECT id, user, text FROM comments WHERE torrent = ' . sqlesc ($torrentid) . ' ORDER by added DESC LIMIT 0, 1');
        $lastcommentuserid = @mysql_result ($query, 0, 'user');
        if (((0 < mysql_num_rows ($query) AND $lastcommentuserid == $CURUSER['id']) AND !$is_mod))
        {
          $text = mysql_result ($query, 0, 'text');
          $newid = mysql_result ($query, 0, 'id');
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

          $newtext = sqlesc ($text . $eol . $eol . $msgtext);
          sql_query ('' . 'UPDATE comments SET text = ' . $newtext . ' WHERE id = \'' . $newid . '\'');
        }
        else
        {
          sql_query ('INSERT INTO comments (user, torrent, added, text) VALUES (' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($torrentid) . ', ' . sqlesc (get_date_time ()) . ', ' . sqlesc ($msgtext) . ')');
          $newid = mysql_insert_id ();
          sql_query ('UPDATE torrents SET comments = comments + 1 WHERE id = ' . sqlesc ($torrentid));
          $ras = sql_query ('SELECT options FROM users WHERE id = ' . sqlesc ($arr['owner']));
          $arg = mysql_fetch_assoc ($ras);
          if ((preg_match ('#C1#is', $arg['options']) AND $arr['owner'] != $CURUSER['id']))
          {
            require_once INC_PATH . '/functions_pm.php';
            send_pm ($arr['owner'], sprintf ($lang->comment['newcommenttxt'], '[url=' . $BASEURL . '/details.php?id=' . $torrentid . '&tab=comments#startcomments]' . $arr['name'] . '[/url]'), $lang->comment['newcommentsub']);
          }

          kps ('+', '' . $kpscomment, $CURUSER['id']);
        }

        header ('' . 'Refresh: 0; url=details.php?id=' . $torrentid . '&tab=comments&showlast=true&viewcomm=' . $newid . '#cid' . $newid);
        exit ();
      }
    }

    ($res = sql_query ('SELECT name, owner FROM torrents WHERE id = ' . sqlesc ($torrentid)) OR sqlerr (__FILE__, 191));
    $arr = mysql_fetch_array ($res);
    if (!$arr)
    {
      stderr ($lang->global['error'], $lang->global['notorrentid']);
    }

    stdhead (sprintf ($lang->comment['addcomment'], $arr['name']), true, 'supernote');
    define ('IN_EDITOR', true);
    include_once INC_PATH . '/editor.php';
    $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . '?action=add&tid=' . $torrentid . '">';
    if (!empty ($prvp))
    {
      $str .= $prvp;
    }

    $str .= insert_editor (false, NULL, $msgtext, $lang->comment['insertcomment'], sprintf ($lang->comment['addcomment'], htmlspecialchars_uni ($arr['name'])));
    $str .= '</form>';
    echo $str;
    $res = sql_query ('SELECT c.id, c.torrent as torrentid, c.text, c.added, c.modnotice, c.modeditid, c.modeditusername, c.modedittime, u.username, u.id as user, u.usergroup, u.title, g.title as grouptitle, g.namestyle, u.avatar as useravatar FROM comments c LEFT JOIN users u ON c.user = u.id LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE c.torrent = ' . sqlesc ($torrentid) . ' ORDER BY c.id DESC LIMIT 0,5');
    $allrows = array ();
    while ($row = mysql_fetch_array ($res))
    {
      $allrows[] = $row;
    }

    if (count ($allrows))
    {
      echo '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
		<tr>
		<td class="thead" colspan="2"><strong>' . $lang->comment['order'] . '</strong></td></tr>';
      commenttable ($allrows);
    }

    echo '</table>';
    stdfoot ();
    exit ();
  }
  else
  {
    if ($action == 'edit')
    {
      $commentid = 0 + $_GET['cid'];
      int_check ($commentid, true);
      ($res = sql_query ('SELECT c.*, t.name, t.id as torrentid FROM comments AS c JOIN torrents AS t ON c.torrent = t.id WHERE c.id= ' . sqlesc ($commentid)) OR sqlerr (__FILE__, 228));
      $arr = mysql_fetch_assoc ($res);
      if (!$arr)
      {
        stderr ($lang->global['error'], $lang->global['notorrentid']);
      }

      if (($arr['user'] != $CURUSER['id'] AND !$is_mod))
      {
        print_no_permission (true);
      }

      if (allowcomments ($arr['torrentid']) == false)
      {
        stderr ($lang->global['error'], $lang->comment['closed']);
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST')
      {
        $returnto = $BASEURL . '/details.php?id=' . $arr['torrentid'] . '&tab=comments&page=' . intval ($_GET['page']) . '&viewcomm=' . $commentid . '#cid' . $commentid;
        if (isset ($_POST['submit']))
        {
          if ($msgtext == '')
          {
            stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
          }

          $msgtext = sqlesc ($msgtext);
          $editedat = sqlesc (get_date_time ());
          $updateedit = true;
          if ($is_mod)
          {
            $updateedit = false;
            if ($_POST['remove'] == 'yes')
            {
              $modnotice = '\'\'';
              $modeditid = '\'0\'';
              $modeditusername = '\'\'';
              $modedittime = '\'0000-00-00 00:00:00\'';
            }
            else
            {
              if ($_POST['modnotice'] != $arr['modnotice'])
              {
                $modnotice = sqlesc (htmlspecialchars_uni ($_POST['modnotice']));
                $modeditid = (int)$CURUSER['id'];
                $modeditusername = sqlesc (htmlspecialchars_uni ($CURUSER['username']));
                $modedittime = $editedat;
              }
              else
              {
                $modnotice = sqlesc ($arr['modnotice']);
                $modeditid = (int)$arr['modeditid'];
                $modeditusername = sqlesc ($arr['modeditusername']);
                $modedittime = sqlesc ($arr['modedittime']);
              }
            }
          }
          else
          {
            $modnotice = sqlesc ($arr['modnotice']);
            $modeditid = (int)$arr['modeditid'];
            $modeditusername = sqlesc ($arr['modeditusername']);
            $modedittime = sqlesc ($arr['modedittime']);
          }

          (sql_query ('' . 'UPDATE comments SET text=' . $msgtext . ', ' . ($updateedit ? 'editedat=' . $editedat . ', editedby=' . sqlesc ($CURUSER['id']) . ',' : '') . ('' . ' modnotice=' . $modnotice . ', modeditid=' . $modeditid . ', modeditusername=' . $modeditusername . ', modedittime=' . $modedittime . ' WHERE id= ') . sqlesc ($commentid)) OR sqlerr (__FILE__, 281));
          if ($returnto)
          {
            header ('' . 'Location: ' . $returnto);
          }
          else
          {
            header ('' . 'Location: ' . $BASEURL . '/');
          }

          exit ();
        }
      }

      $returnto = ($returnto ? $returnto : fix_url ($_SERVER['HTTP_REFERER'] . '&page=' . intval ($_GET['page']) . '&viewcomm=' . $commentid . '#cid' . $commentid));
      stdhead (sprintf ($lang->comment['adit'], $arr['name']));
      define ('IN_EDITOR', true);
      include_once INC_PATH . '/editor.php';
      $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . '?action=edit&cid=' . $commentid . '&page=' . intval ($_GET['page']) . '">
	<input type="hidden" name="returnto" value="' . $returnto . '">';
      if (!empty ($prvp))
      {
        $str .= $prvp;
      }

      if ($is_mod)
      {
        $postoptionstitle = array ('1' => $lang->comment['modnotice1']);
        $postoptions = array ('1' => '<textarea name="modnotice" id="modnotice" rows="4" cols="70" tabindex="3">' . $arr['modnotice'] . '</textarea><br />
					<label><input style="vertical-align: middle;" class="checkbox" name="remove" value="yes" tabindex="6" type="checkbox"> ' . $lang->comment['modnotice2'] . '</label>');
      }

      $str .= insert_editor (false, NULL, (!empty ($prvp) ? $msgtext : $arr['text']), $lang->comment['editcomment'], sprintf ($lang->comment['adit'], htmlspecialchars_uni ($arr['name'])), $postoptionstitle, $postoptions);
      $str .= '</form>';
      echo $str;
      stdfoot ();
      exit ();
    }
    else
    {
      if ($action == 'delete')
      {
        if (!$is_mod)
        {
          print_no_permission (true);
        }

        $commentid = 0 + $_GET['cid'];
        $torrentid = 0 + $_GET['tid'];
        int_check (array ($commentid, $torrentid), true);
        $referer = '' . 'details.php?id=' . $torrentid . '&tab=comments&page=' . intval ($_GET['page']);
        include_once INC_PATH . '/ts_token.php';
        $ts_token = new ts_token ();
        $ts_token->url = sprintf ($lang->comment['confirm'], '<a href="' . $BASEURL . '/comment.php?action=delete&cid=' . $commentid . '&tid=' . $torrentid . '&hash={1}&sure=1&page=' . intval ($_GET['page']) . '">');
        $ts_token->redirect = $referer;
        $ts_token->create ();
        ($res = sql_query ('SELECT torrent,user FROM comments WHERE id= ' . sqlesc ($commentid)) OR sqlerr (__FILE__, 334));
        $arr = mysql_fetch_array ($res);
        if ($arr)
        {
          $torrentid = $arr['torrent'];
          $userpostid = $arr['user'];
        }
        else
        {
          stderr ($lang->global['error'], $lang->global['notorrentid']);
        }

        (sql_query ('DELETE FROM comments WHERE id=' . sqlesc ($commentid)) OR sqlerr (__FILE__, 345));
        if (($torrentid AND 0 < mysql_affected_rows ()))
        {
          sql_query ('UPDATE torrents SET comments = IF(comments>0, comments - 1, 0) WHERE id = ' . sqlesc ($torrentid));
          (sql_query ('DELETE FROM comments_votes WHERE cid = ' . sqlesc ($commentid) . ' AND uid = ' . sqlesc ($userpostid)) OR sqlerr (__FILE__, 349));
        }

        kps ('-', '' . $kpscomment, $userpostid);
        redirect ('details.php?id=' . $torrentid . '&tab=comments&page=' . intval ($_GET['page']));
        exit ();
      }
      else
      {
        stderr ($lang->global['error'], $lang->global['invalidaction']);
      }
    }
  }

  exit ();
?>
