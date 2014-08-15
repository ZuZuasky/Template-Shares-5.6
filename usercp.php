<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function create_link ($act = '', $text)
  {

    return '<a href="' . $_SERVER['SCRIPT_NAME'] . ($act ? '?act=' . $act : '') . '">' . $text . '</a>';

  }

  function show__message ($message, $subject = '')
  {
    global $lang;
    $subject = ($subject ? $subject : $lang->global['error']);
    return '<fieldset><legend>' . $subject . '</legend>' . $message . '</fieldset>';
  }

  function check_avatar ($width, $height, $type, $size)
  {
    global $f_avatar_maxwidth;
    global $f_avatar_maxheight;
    global $f_avatar_maxsize;
    global $lang;
    $error = false;
    $types_array = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
    if ((((empty ($width) OR empty ($height)) OR empty ($type)) OR empty ($size)))
    {
	  $error = $lang->usercp['a_error1'];
    }
    else
    {
      if (!in_array ($type, $types_array))
      {
        $error = sprintf ($lang->usercp['a_invalid_image'], implode (', ', $types_array), htmlspecialchars_uni ($type));
      }
      else
      {
        if (($f_avatar_maxwidth < $width OR $f_avatar_maxheight < $height))
        {
          $error = sprintf ($lang->usercp['a_error2'], $f_avatar_maxwidth, $f_avatar_maxheight, $width, $height);
        }
        else
        {
          if ($f_avatar_maxsize < $size)
          {
            $error = sprintf ($lang->usercp['a_error3'], mksize ($f_avatar_maxsize), mksize ($size));
          }
        }
      }
    }

    if ($error)
    {
      return show__message ($error);
    }

    return '';
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('UCP_VERSION', '2.1.3 ');
  require INC_PATH . '/user_options.php';
  define ('NcodeImageResizer', true);
  $lang->load ('usercp');
  $act = (isset ($_GET['act']) ? htmlspecialchars_uni ($_GET['act']) : (isset ($_POST['act']) ? htmlspecialchars_uni ($_POST['act']) : ''));
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : ''));
  $userid = intval ($CURUSER['id']);
  $IsStaff = is_mod ($usergroups);
  $contents = array ();
  $main = $substhreads = $substorrents = '';
  $allowed_types = array ('gif', 'jpg', 'png');
  if ($act == 'unsubscribe')
  {
    if (($do == 'unsubscribe_threads' AND is_array ($_POST['threadids'])))
    {
      $deletethreads = array ();
      foreach ($_POST['threadids'] as $__tid)
      {
        if (is_valid_id ($__tid))
        {
          $deletethreads[] = $__tid;
          continue;
        }
      }

      if (0 < count ($deletethreads))
      {
        (sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE tid IN (0, ' . implode (',', $deletethreads) . ('' . ') AND userid=\'' . $userid . '\'')) OR sqlerr (__FILE__, 67));
      }
    }
    else
    {
      if (($do == 'unsubscribe_torrents' AND is_array ($_POST['torrentids'])))
      {
        $deletetorrents = array ();
        foreach ($_POST['torrentids'] as $__tid)
        {
          if (is_valid_id ($__tid))
          {
            $deletetorrents[] = $__tid;
            continue;
          }
        }

        if (0 < count ($deletetorrents))
        {
          (sql_query ('DELETE FROM bookmarks WHERE torrentid IN (0, ' . implode (',', $deletetorrents) . ('' . ') AND userid=\'' . $userid . '\'')) OR sqlerr (__FILE__, 82));
        }
      }
    }

    unset ($act);
    unset ($do);
  }

  if ((empty ($act) AND empty ($do)))
  {
    ($query = sql_query ('' . 'SELECT COUNT(id) as totalcomments FROM comments WHERE user = \'' . $userid . '\'') OR sqlerr (__FILE__, 90));
    $res = mysql_fetch_assoc ($query);
    $comments = ts_nf ($res['totalcomments']);
    $join_date = my_datee ($dateformat, $CURUSER['added']) . ' ' . my_datee ($timeformat, $CURUSER['added']);
    $avatar = get_user_avatar ($CURUSER['avatar']);
    $kps = ts_nf ($CURUSER['seedbonus']);
    $invites = ts_nf ($CURUSER['invites']);
    $posts = ts_nf ($CURUSER['totalposts']);
    $substhreads = '
	<br />
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '" name="unsubscribe_threads">
	<input type="hidden" value="unsubscribe" name="act" />
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="thead">
				<span style="float: right"><input checkall="group1" onclick="javascript: return select_deselectAll (\'unsubscribe_threads\', this, \'group1\');" type="checkbox" /></span>
				' . $lang->usercp['s1'] . '
				</td>
		</tr>';
    ($query = sql_query ('SELECT s.tid, t.subject FROM ' . TSF_PREFIX . 'subscribe s LEFT JOIN ' . TSF_PREFIX . ('' . 'threads t ON (s.tid=t.tid) WHERE s.userid=\'' . $userid . '\'')) OR sqlerr (__FILE__, 110));
    if (0 < mysql_num_rows ($query))
    {
      while ($subs = mysql_fetch_assoc ($query))
      {
        $substhreads .= '
			<tr>
				<td>
					<span style="float: right"><input checkme="group1" type="checkbox" name="threadids[]" value="' . $subs['tid'] . '" /></span>
					<a href="' . $BASEURL . '/tsf_forums/showthread.php?tid=' . $subs['tid'] . '">' . cutename ($subs['subject'], 90) . '</a>
				</td>
			</tr>
			';
      }
    }
    else
    {
      $substhreads .= '
			<tr>
				<td>' . $lang->usercp['s3'] . '</td>
			</tr>
			';
    }

    $substhreads .= '
		<tr>
			<td><span style="float: right"><select name="do"><option value="unsubscribe_threads">' . $lang->usercp['s5'] . '</option></select> <input type="submit" value="' . $lang->usercp['s6'] . '" /></span></td>
		</tr>
	</table>
	</form>';
    $substorrents = '
	<br />
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '" name="unsubscribe_torrents">
	<input type="hidden" value="unsubscribe" name="act" />
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="thead">
				<span style="float: right"><input checkall="group2" onclick="javascript: return select_deselectAll (\'unsubscribe_torrents\', this, \'group2\');" type="checkbox" /></span>
				' . $lang->usercp['s2'] . '
				</td>
		</tr>';
    ($query = sql_query ('' . 'SELECT b.torrentid, t.name FROM bookmarks b LEFT JOIN torrents t ON (b.torrentid=t.id) WHERE b.userid=\'' . $userid . '\'') OR sqlerr (__FILE__, 151));
    if (0 < mysql_num_rows ($query))
    {
      while ($subs = mysql_fetch_assoc ($query))
      {
        $substorrents .= '
			<tr>
				<td>
					<span style="float: right"><input checkme="group2" type="checkbox" name="torrentids[]" value="' . $subs['torrentid'] . '" /></span>
					<a href="' . $BASEURL . '/details.php?id=' . $subs['torrentid'] . '">' . cutename ($subs['name'], 90) . '</a>
				</td>
			</tr>
			';
      }
    }
    else
    {
      $substorrents .= '
			<tr>
				<td>' . $lang->usercp['s4'] . '</td>
			</tr>
			';
    }

    $substorrents .= '
		<tr>
			<td><span style="float: right"><select name="do"><option value="unsubscribe_torrents">' . $lang->usercp['s7'] . '</option></select> <input type="submit" value="' . $lang->usercp['s6'] . '" /></span></td>
		</tr>
	</table>
	</form>';
    $main = '
	<table cellpadding="3" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td class="none" valign="top">
					' . $avatar . '
				</td>
				<td class="none" valign="top">
					' . sprintf ($lang->usercp['details'], $join_date, htmlspecialchars_uni ($CURUSER['email']), $posts, $comments, $kps, $invites) . '
				</td>
			</tr>
		</tbody>
	</table>';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['title2'], 'main' => $main);
  }

  if ($act == 'auto_dst')
  {
    $dst = (user_options ($CURUSER['options'], 'dst') ? '1' : '0');
    if ($dst == '1')
    {
      $dst = '0';
    }
    else
    {
      if ($dst == '0')
      {
        $dst = '1';
      }
    }

    $newUoptions = preg_replace ('#O[0-1]#', 'O' . $dst, $CURUSER['options']);
    (sql_query ('UPDATE users SET options = ' . sqlesc ($newUoptions) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 212));
    redirect ($_SERVER['SCRIPT_NAME'], $lang->usercp['dst_updated']);
    exit ();
  }

  if ($act == 'show_gallery')
  {
    $GalleryPath = INC_PATH . '/avatars/gallery/';
    if (!is_dir ($GalleryPath))
    {
      redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_avatar', $lang->usercp['avgalery2']);
      exit ();
    }

    $GalleryFiles = array ();
    if ($handle = opendir ($GalleryPath))
    {
      while (false !== $file = readdir ($handle))
      {
        if ((($file != '.' AND $file != '..') AND in_array (get_extension ($file), $allowed_types)))
        {
          $GalleryFiles[] = $BASEURL . '/include/avatars/gallery/' . $file;
          continue;
        }
      }
    }

    if (((((((((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND isset ($_POST['hash'])) AND !empty ($_POST['hash'])) AND strlen ($_POST['hash']) == 15) AND $_POST['hash'] === $_SESSION['AvatarHash']) AND isset ($_POST['avatar'])) AND !empty ($_POST['avatar'])) AND in_array ($_POST['avatar'], $GalleryFiles)) AND in_array (get_extension ($_POST['avatar']), $allowed_types)))
    {
      (sql_query ('UPDATE users SET avatar = ' . sqlesc (htmlspecialchars_uni ($_POST['avatar'])) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 240));
      if (mysql_affected_rows ())
      {
        $upload_path = INC_PATH . '/avatars/';
        if ($handle = opendir ($upload_path))
        {
          while (false !== $file = readdir ($handle))
          {
            if ((($file != '.' AND $file != '..') AND in_array (get_extension ($file), $allowed_types)))
            {
              $__exp = str_replace (array ('.gif', '.png', '.jpg'), '', $file);
              $__exp = explode ('_', $__exp);
              $__userid = $__exp[1];
              if ($__userid == $userid)
              {
                @unlink ($upload_path . $file);
                continue;
              }

              continue;
            }
          }

          closedir ($handle);
        }
      }

      redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_avatar', $lang->usercp['a_uploaded']);
      exit ();
    }

    if (0 < count ($GalleryFiles))
    {
      $AvatarHash = mksecret (15);
      $_SESSION['AvatarHash'] = $AvatarHash;
      $main = '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?act=show_gallery&do=save_avatar">
		<input type="hidden" name="act" value="show_gallery" />
		<input type="hidden" name="do" value="save_avatar" />
		<input type="hidden" name="hash" value="' . $AvatarHash . '" />
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
			<tr>';
      $AvatarCount = 0;
      foreach ($GalleryFiles as $AvatarImage)
      {
        if ($AvatarCount % 7 == 0)
        {
          $main .= '</tr><tr>';
        }

        $main .= '<td class="none" align="center"><img src="' . $AvatarImage . '" border="0" class="inlineimg" width="100" height="100" /><br /><input type="radio" name="avatar" class="none" value="' . $AvatarImage . '" /></td>';
        ++$AvatarCount;
      }

      $main .= '
			</tr>
			<tr>
				<td class="subheader" colspan="7" align="center"><input type="submit" value="' . $lang->usercp['save'] . '" /> <input type="reset" value="' . $lang->usercp['reset'] . '" /></td>
			</tr>
		</table>
		</form>';
      $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['avgalery1'], 'main' => $main);
    }
    else
    {
      redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_avatar', $lang->usercp['avgalery2']);
      exit ();
    }
  }

  if ($act == 'edit_avatar')
  {
    $A_Upload = false;
    require INC_PATH . '/readconfig_forumcp.php';
    $rules = sprintf ($lang->usercp['a_rules'], $f_avatar_maxwidth, $f_avatar_maxheight, mksize ($f_avatar_maxsize), strtoupper (implode (', ', $allowed_types)));
    if ((((($do == 'save_avatar' AND isset ($_POST['hash'])) AND !empty ($_POST['hash'])) AND strlen ($_POST['hash']) == 15) AND $_POST['hash'] === $_SESSION['UploadHash']))
    {
      if (((!empty ($_FILES['avatar_file']) AND !empty ($_FILES['avatar_file']['name'])) AND !empty ($_FILES['avatar_file']['tmp_name'])))
      {
        @clearstatcache ();
        $image_info = @getimagesize ($_FILES['avatar_file']['tmp_name']);
        $size = @filesize ($_FILES['avatar_file']['tmp_name']);
        $error = check_avatar ($image_info[0], $image_info[1], $image_info['mime'], $size);
        if (!$error)
        {
          $file_ext = get_extension ($_FILES['avatar_file']['name']);
          $newname = substr (md5 ($CURUSER['ip'] . time () . $CURUSER['id']), 0, 15) . '_' . $CURUSER['id'] . '.' . $file_ext;
          $upload_path = INC_PATH . '/avatars/';
          if (move_uploaded_file ($_FILES['avatar_file']['tmp_name'], $upload_path . $newname))
          {
            $avatar = $BASEURL . '/include/avatars/' . $newname;
          }
          else
          {
            $error = show__message ($lang->usercp['a_error4']);
          }
        }
      }
      else
      {
        if (!empty ($_POST['avatar_url']))
        {
          @clearstatcache ();
          $avatar = (isset ($_POST['avatar_url']) ? $_POST['avatar_url'] : '');
          $image_info = @getimagesize ($avatar);
          if ((!$remote_file = @fopen ($avatar, 'rb') OR !$image_info))
          {
            $error = show__message ($lang->usercp['a_error1']);
            unset ($avatar);
          }
          else
          {
            $user_avatar_size = 0;
            do
            {
              if ((strlen (@fread ($remote_file, 1)) == 0 OR $f_avatar_maxsize < $user_avatar_size))
              {
                break;
              }

              ++$user_avatar_size;
            }while (!(true));

            @fclose ($remote_file);
            $error = check_avatar ($image_info[0], $image_info[1], $image_info['mime'], $user_avatar_size);
            if ($error)
            {
              unset ($avatar);
            }
          }
        }
      }

      if (((isset ($avatar) AND !empty ($avatar)) AND !$error))
      {
        (sql_query ('UPDATE users SET avatar = ' . sqlesc ($avatar) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 372));
        if (mysql_affected_rows ())
        {
          $error = show__message ('<img src="' . htmlspecialchars_uni ($avatar) . '" border="0" class="inlineimg" />', $lang->usercp['a_uploaded']);
          $A_Upload = true;
          $upload_path = INC_PATH . '/avatars/';
          if ($handle = opendir ($upload_path))
          {
            while (false !== $file = readdir ($handle))
            {
              if (((($file != '.' AND $file != '..') AND $file != $newname) AND in_array (get_extension ($file), $allowed_types)))
              {
                $__exp = str_replace (array ('.gif', '.png', '.jpg'), '', $file);
                $__exp = explode ('_', $__exp);
                $__userid = $__exp[1];
                if ($__userid == $userid)
                {
                  @unlink ($upload_path . $file);
                  continue;
                }

                continue;
              }
            }

            closedir ($handle);
          }
        }
      }
      else
      {
        $error = ($error ? $error : show__message ($lang->usercp['a_error1']));
      }
    }

    $UploadHash = mksecret (15);
    $_SESSION['UploadHash'] = $UploadHash;
    $main = '
	<script type="text/javascript">
		function toggleuploadmode(mode)
		{
			switch (mode)
			{
				case 0:
					show("avatar_file", "block");
					hide("avatar_url");
					break;
				case 1:
					hide("avatar_file");
					show("avatar_url", "block");
					break;
			}
		}
		function focusfield(fl) {
			if (fl.value=="xq33") {
				fl.value="";
				fl.style.color="black";
			}
		}
		function show(id, type)
		{
			var o = document.getElementById(id);
			if (o)
				o.style.display = type || "";
		}

		function hide(id)
		{
			var o = document.getElementById(id);
			if (o)
				o.style.display = "none";
		}
	</script>
	' . $error . '
	<form name="avatarupload" enctype="multipart/form-data" method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_avatar&do=save_avatar">
	<input type="hidden" name="act" value="edit_avatar" />
	<input type="hidden" name="do" value="save_avatar" />
	<input type="hidden" name="hash" value="' . $UploadHash . '" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					' . (!$A_Upload ? '
					<fieldset>
						<legend>' . $lang->usercp['a_current'] . '</legend>
						' . get_user_avatar ($CURUSER['avatar']) . '
					</fieldset>' : '') . '
					<fieldset style="margin-bottom: 5px;">
						<legend>' . $lang->usercp['a_head'] . '</legend>
							<p>' . $lang->usercp['a_title'] . '</p>
							<input name="uploadtype" onclick="toggleuploadmode(1)" checked="checked" type="radio" /> <strong>' . $lang->usercp['a_option1'] . '</strong><br />
							<input name="uploadtype" onclick="toggleuploadmode(0)" type="radio" /> <strong>' . $lang->usercp['a_option2'] . '</strong><br />
							<input name="uploadtype" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '?act=show_gallery\'); return false;" type="radio" /> <strong>' . $lang->usercp['avgalery3'] . '</strong>
							<div id="avatar_url">
								<input type="text" name="avatar_url" size="70" onfocus="focusfield(this)" />
								<input type="submit" name="submit" value="' . $lang->usercp['a_button'] . '" />
							</div>
							<div id="avatar_file" style="display: none">
								<input type="file" name="avatar_file" size="70" />
								<input type="submit" name="submit" value="' . $lang->usercp['a_button'] . '" />
							</div>
							<p>
							' . $rules . '
							</p>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	</form>';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['link4'], 'main' => $main);
  }

  if ($act == 'edit_signature')
  {
    if ($usergroups['cansignature'] != 'yes')
    {
      $main = $lang->global['nopermission'];
    }
    else
    {
      if (($_POST['previewpost'] AND !empty ($_POST['message'])))
      {
        $signature = trim ($_POST['message']);
      }
      else
      {
        if ($do == 'save_signature')
        {
          $signature = trim ($_POST['message']);
          $sigstrlen = strlen ($signature);
          if (($maxchar < $sigstrlen AND !$IsStaff))
          {
            $error = sprintf ($lang->usercp['s_error1'], ts_nf ($maxchar), ts_nf ($sigstrlen));
          }
          else
          {
            (sql_query ('UPDATE users SET signature = ' . sqlesc ($signature) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 508));
            redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_signature', $lang->usercp['saved2']);
            exit ();
          }
        }
      }

      define ('IN_EDITOR', true);
      require INC_PATH . '/editor.php';
      $signature = (isset ($signature) ? $signature : $CURUSER['signature']);
      $main .= '
		<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_signature&do=save_signature">
		<input type="hidden" name="act" value="edit_signature" />
		<input type="hidden" name="do" value="save_signature" />
		' . (isset ($error) ? show__message ($error) . '<br />' : ($signature ? show__message (format_comment ($signature), $lang->usercp['s_current']) . '<br /><br />' : '')) . '
		';
      $main .= insert_editor (false, NULL, $signature, $lang->usercp['link5']);
      $main .= '
		</form>';
    }

    $contents = array ('title' => $lang->usercp['title'], 'title2' => NULL, 'main' => $main);
  }

  if ($act == 'edit_password')
  {
    if ($do == 'save_password')
    {
      $currentpassword = trim ($_POST['currentpassword']);
      $newpassword1 = trim ($_POST['newpassword1']);
      $newpassword2 = trim ($_POST['newpassword2']);
      $newemail1 = trim ($_POST['newemail1']);
      $newemail2 = trim ($_POST['newemail2']);
      $passhint = intval ($_POST['passhint']);
      $hintanswer = htmlspecialchars_uni ($_POST['hintanswer']);
      if ((empty ($currentpassword) OR $CURUSER['passhash'] != md5 ($CURUSER['secret'] . $currentpassword . $CURUSER['secret'])))
      {
        $error = show__message ($lang->usercp['e_error1']);
      }
      else
      {
        if ((!empty ($newpassword1) AND !empty ($newpassword2)))
        {
          if ($newpassword1 != $newpassword2)
          {
            $error = show__message ($lang->usercp['e_error2']);
          }
          else
          {
            if ($newpassword1 == $CURUSER['username'])
            {
              $error = show__message ($lang->usercp['e_error3']);
            }
            else
            {
              if (40 < strlen ($newpassword1))
              {
                $error = show__message ($lang->usercp['e_error4']);
              }
              else
              {
                if (strlen ($newpassword1) < 6)
                {
                  $error = show__message ($lang->usercp['e_error5']);
                }
                else
                {
                  require INC_PATH . '/functions_login.php';
                  $secret = mksecret ();
                  $passhash = md5 ($secret . $newpassword1 . $secret);
                  (sql_query ('UPDATE users SET secret = ' . sqlesc ($secret) . ', passhash = ' . sqlesc ($passhash) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 574));
                  if (mysql_affected_rows ())
                  {
                    logincookie ($userid, $passhash);
                    sessioncookie ($userid, $passhash);
                    $error = show__message ($lang->usercp['saved3'], $lang->usercp['e_pass2']);
                  }
                  else
                  {
                    $error = show__message ($lang->global['dberror']);
                  }
                }
              }
            }
          }
        }
      }

      if (((!empty ($newemail1) AND !empty ($newemail2)) AND $newemail1 != $CURUSER['email']))
      {
        if ((empty ($currentpassword) OR $CURUSER['passhash'] != md5 ($CURUSER['secret'] . $currentpassword . $CURUSER['secret'])))
        {
          $error = show__message ($lang->usercp['e_error1']);
        }
        else
        {
          require_once INC_PATH . '/functions_EmailBanned.php';
          if ($newemail1 != $newemail2)
          {
            $error .= show__message ($lang->usercp['e_error6']);
          }
          else
          {
            if ((!check_email ($newemail1) OR emailbanned ($newemail1)))
            {
              $error .= show__message ($lang->usercp['e_error7']);
            }
            else
            {
              ($query = sql_query ('SELECT email FROM users WHERE email = ' . sqlesc ($newemail1)) OR sqlerr (__FILE__, 608));
              if (0 > mysql_num_rows ($query))
              {
                $error .= show__message ($lang->usercp['e_error8']);
              }
              else
              {
                if (!$IsStaff)
                {
                  $sec = mksecret ();
                  $hash = md5 ($sec . $newemail1 . $sec);
                  $obemail = urlencode ($newemail1);
                  (sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($userid)) OR sqlerr (__FILE__, 620));
                  (sql_query ('INSERT INTO ts_user_validation (editsecret, userid) VALUES (' . sqlesc ($sec) . ', ' . sqlesc ($userid) . ')') OR sqlerr (__FILE__, 621));
                  $body = sprintf ($lang->usercp['emailbody'], $CURUSER['username'], $SITENAME, $newemail1, $_SERVER['REMOTE_ADDR'], $BASEURL, $userid, $hash, $obemail);
                  sent_mail ($newemail1, sprintf ($lang->usercp['emailsubject'], $SITENAME), $body, 'profile', false);
                  $error .= show__message ($lang->usercp['saved5'], $lang->usercp['e_pass5']);
                }
                else
                {
                  (sql_query ('UPDATE users SET email = ' . sqlesc ($newemail1) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 628));
                  if (mysql_affected_rows ())
                  {
                    $error .= show__message ($lang->usercp['saved4'], $lang->usercp['e_pass5']);
                  }
                  else
                  {
                    $error .= show__message ($lang->global['dberror']);
                  }
                }
              }
            }
          }
        }
      }

      if (((!empty ($_POST['passhint']) AND !empty ($_POST['hintanswer'])) AND is_valid_id ($_POST['passhint'])))
      {
        if ((empty ($currentpassword) OR $CURUSER['passhash'] != md5 ($CURUSER['secret'] . $currentpassword . $CURUSER['secret'])))
        {
          $error = show__message ($lang->usercp['e_error1']);
        }
        else
        {
          if (in_array ($passhint, array ('1', '2', '3')))
          {
            if (strlen ($hintanswer) < 3)
            {
              $error .= show__message ($lang->usercp['e_error9']);
            }
            else
            {
              if (20 < strlen ($hintanswer))
              {
                $error .= show__message ($lang->usercp['e_error10']);
              }
              else
              {
                if ($hintanswer == $CURUSER['username'])
                {
                  $error .= show__message ($lang->usercp['e_error11']);
                }
                else
                {
                  (sql_query ('' . 'REPLACE INTO ts_secret_questions (userid, passhint, hintanswer) VALUES (\'' . $userid . '\', \'' . $passhint . '\', ' . sqlesc (md5 ($hintanswer)) . ')') OR sqlerr (__FILE__, 667));
                  if (mysql_affected_rows ())
                  {
                    $error .= show__message ($lang->usercp['saved7'], $lang->usercp['e_pass8']);
                  }
                  else
                  {
                    $error .= show__message ($lang->global['dberror']);
                  }
                }
              }
            }
          }
        }
      }
    }

    $QArray = array ('1' => $lang->usercp['hr0'], '2' => $lang->usercp['hr1'], '3' => $lang->usercp['hr2']);
    $questions = '<select name="passhint">';
    foreach ($QArray as $ID => $Question)
    {
      $questions .= '<option value="' . $ID . '">' . htmlspecialchars_uni ($Question) . '</option>';
    }

    $questions .= '</select>';
    $main = $error . '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_password&do=save_password">
	<input type="hidden" name="act" value="edit_password" />
	<input type="hidden" name="do" value="save_password" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					<fieldset>
						<legend>' . $lang->usercp['e_pass'] . '</legend>
						<input type="password" size="40" value="" name="currentpassword" />
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['e_pass2'] . '</legend>
						' . $lang->usercp['e_pass3'] . '<br />
						<input type="password" size="40" value="" name="newpassword1" /><br />
						' . $lang->usercp['e_pass4'] . '<br />
						<input type="password" size="40" value="" name="newpassword2" />
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['e_pass5'] . '</legend>
						' . $lang->usercp['e_pass6'] . '<br />
						<input type="text" size="40" value="' . htmlspecialchars_uni ((isset ($newemail1) ? $newemail1 : $CURUSER['email'])) . '" name="newemail1" /><br />
						' . $lang->usercp['e_pass7'] . '<br />
						<input type="text" size="40" value="' . htmlspecialchars_uni ((isset ($newemail2) ? $newemail2 : $CURUSER['email'])) . '" name="newemail2" />
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['e_pass8'] . '</legend>
						' . $lang->usercp['e_pass9'] . '<br /><br />
						' . $lang->usercp['e_pass10'] . '<br />
						' . $questions . '<br />
						' . $lang->usercp['e_pass11'] . '<br />
						<input type="text" size="30" value="' . (isset ($hintanswer) ? $hintanswer : '') . '" class="inlineimg" name="hintanswer" />
					</fieldset>
					<fieldset style="margin-bottom: 5px;">
						<legend>' . $lang->usercp['save'] . '</legend>
						<input type="submit" value="' . $lang->usercp['save'] . '" /> <input type="reset" value="' . $lang->usercp['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	</form>';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['link6'], 'main' => $main);
  }

  if ($act == 'edit_details')
  {
    $caneditbday = (($IsStaff OR !$CURUSER['birthday']) ? true : false);
    if ($do == 'save_details')
    {
      $updateset = $update = array ();
      if (((($caneditbday AND is_valid_id ($_POST['day'])) AND is_valid_id ($_POST['month'])) AND is_valid_id ($_POST['year'])))
      {
        $day = htmlspecialchars_uni ($_POST['day']);
        $month = htmlspecialchars_uni ($_POST['month']);
        $year = intval ($_POST['year']);
        $bday = '' . $day . '-' . $month . '-' . $year;
        if ($bday != $CURUSER['birthday'])
        {
          $updateset[] = 'birthday = ' . sqlesc ($bday);
        }
      }

      if ($_POST['country'] != $CURUSER['country'])
      {
        $updateset[] = 'country = ' . sqlesc (intval ($_POST['country']));
      }

      $UserSpeed = trim ($_POST['download']) . '~' . trim ($_POST['upload']);
      if ($UserSpeed != $CURUSER['speed'])
      {
        $updateset[] = 'speed = ' . sqlesc ($UserSpeed);
      }

      if ($_POST['tzoffset'] != $CURUSER['tzoffset'])
      {
        $updateset[] = 'tzoffset = ' . sqlesc (htmlspecialchars_uni ($_POST['tzoffset']));
      }

      if ($_POST['dst'] == '2')
      {
        $dst = (user_options ($CURUSER['options'], 'dst') ? '1' : '0');
        $autodst = '1';
      }
      else
      {
        if ($_POST['dst'] == '1')
        {
          $dst = '1';
          $autodst = '0';
        }
        else
        {
          $dst = '0';
          $autodst = '0';
        }
      }

      if ($usergroups['canemailnotify'] == 'yes')
      {
        $notifs = ((isset ($_POST['pmnotif']) AND $_POST['pmnotif'] == 'yes') ? '[pm]' : '');
        $notifs .= ((isset ($_POST['emailnotif']) AND $_POST['emailnotif'] == 'yes') ? '[email]' : '');
        ($query = sql_query ('SELECT id FROM categories') OR sqlerr (__FILE__, 796));
        while ($cats = mysql_fetch_assoc ($query))
        {
          if ((isset ($_POST['cat' . $cats['id']]) AND $_POST['cat' . $cats['id']] == 'yes'))
          {
            $notifs .= '[cat' . $cats['id'] . ']';
            continue;
          }
        }

        $updateset[] = 'notifs = ' . sqlesc ($notifs);
      }

      if (in_array ($_POST['torrentsperpage'], array (0, 5, 10, 20, 30, 40)))
      {
        $updateset[] = 'torrentsperpage = ' . sqlesc (intval ($_POST['torrentsperpage']));
      }

      if (in_array ($_POST['postsperpage'], array (0, 5, 10, 20, 30, 40)))
      {
        $updateset[] = 'postsperpage = ' . sqlesc (intval ($_POST['postsperpage']));
      }

      if (0 < count ($updateset))
      {
        (sql_query ('UPDATE users SET ' . implode (',', $updateset) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 816));
      }

      $new_user_options = $_POST['options'];
      $update[] = 'A' . ((isset ($new_user_options['parked']) AND $new_user_options['parked'] == 'yes') ? '1' : '0');
      $update[] = 'B' . ((isset ($new_user_options['invisible']) AND $new_user_options['invisible'] == 'yes') ? '1' : '0');
      $update[] = 'C' . ((isset ($new_user_options['commentpm']) AND $new_user_options['commentpm'] == 'yes') ? '1' : '0');
      $update[] = 'D' . ((isset ($new_user_options['avatars']) AND $new_user_options['avatars'] == 'yes') ? '1' : '0');
      $update[] = 'E' . (user_options ($CURUSER['options'], 'showoffensivetorrents') ? '1' : '0');
      $update[] = 'F' . ((isset ($new_user_options['popup']) AND $new_user_options['popup'] == 'yes') ? '1' : '0');
      $update[] = 'G' . (user_options ($CURUSER['options'], 'leftmenu') ? '1' : '0');
      $update[] = 'H' . ((isset ($new_user_options['signatures']) AND $new_user_options['signatures'] == 'yes') ? '1' : '0');
      $update[] = 'I' . (user_options ($CURUSER['options'], 'privacy', 1) ? '1' : (user_options ($CURUSER['options'], 'privacy', 2) ? '2' : (user_options ($CURUSER['options'], 'privacy', 3) ? '3' : '4')));
      $update[] = 'K' . ((isset ($new_user_options['acceptpms']) AND $new_user_options['acceptpms'] == 'yes') ? '1' : ($new_user_options['acceptpms'] == 'friends' ? '2' : '3'));
      $update[] = 'L' . ((isset ($new_user_options['gender']) AND $new_user_options['gender'] == '1') ? '1' : ((isset ($new_user_options['gender']) AND $new_user_options['gender'] == '2') ? '2' : '3'));
      $update[] = 'M' . ((isset ($new_user_options['visitormsg']) AND $new_user_options['visitormsg'] == 'yes') ? '1' : ((isset ($new_user_options['visitormsg']) AND $new_user_options['visitormsg'] == 'staff') ? '2' : '3'));
      $update[] = 'N' . $autodst;
      $update[] = 'O' . $dst;
      $update[] = 'P' . ((isset ($new_user_options['quickmenu']) AND $new_user_options['quickmenu'] == 'yes') ? '1' : '0');
      $options = implode ('', $update);
      (sql_query ('UPDATE users SET options = ' . sqlesc ($options) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 835));
      unset ($new_user_options);
      unset ($update);
      unset ($options);
      redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_details', $lang->usercp['saved1']);
      exit ();
    }

    if ($caneditbday)
    {
      $userbday = @explode ('-', $CURUSER['birthday']);
      $days = '<select name="day">';
      $i = 1;
      while ($i <= 31)
      {
        $days .= '<option value="' . $i . '"' . ($userbday[0] == $i ? ' selected="selected"' : '') . '>' . $i . '</option>';
        ++$i;
      }

      $days .= '</select>';
      $months_array = @explode (',', $lang->usercp['dob5']);
      $months = '
		<select name="month">
		<option value="-1"></option>';
      $first = 1;
      foreach ($months_array as $left => $right)
      {
        $months .= '<option value="' . $first . '"' . ($userbday[1] == $first ? ' selected="selected"' : '') . '>' . $right . '</option>';
        ++$first;
      }

      $months .= '</select>';
      $year = '
		<input type="text" size="4" name="year" value="' . $userbday[2] . '" />
		';
    }

    $country = '<select name="country">';
    ($query = sql_query ('SELECT id, name FROM countries ORDER by name') OR sqlerr (__FILE__, 868));
    while ($countries = mysql_fetch_assoc ($query))
    {
      $country .= '<option value="' . intval ($countries['id']) . '"' . ($CURUSER['country'] == $countries['id'] ? ' selected="selected"' : '') . '>' . htmlspecialchars_uni ($countries['name']) . '</option>';
    }

    $country .= '</select>';
    $downloadspeed = '
	<select name="download">
	<option value="0">-------</option>';
    require TSDIR . '/' . $cache . '/downloadspeed.php';
    $UserSpeed = explode ('~', $CURUSER['speed']);
    foreach ($_downloadspeed as $ds_b)
    {
      $downloadspeed .= '<option value="' . intval ($ds_b['id']) . '"' . ($UserSpeed[0] == $ds_b['id'] ? ' selected="selected"' : '') . '>' . htmlspecialchars_uni ($ds_b['name']) . '</option>';
    }

    $downloadspeed .= '</select>';
    $uploadspeed = '
	<select name="upload">
	<option value="0">-------</option>';
    require TSDIR . '/' . $cache . '/uploadspeed.php';
    foreach ($_uploadspeed as $us_b)
    {
      $uploadspeed .= '<option value="' . intval ($us_b['id']) . '"' . ($UserSpeed[1] == $us_b['id'] ? ' selected="selected"' : '') . '>' . htmlspecialchars_uni ($us_b['name']) . '</option>';
    }

    $uploadspeed .= '</select>';
    unset ($_uploadspeed);
    unset ($_downloadspeed);
    unset ($ds_b);
    unset ($us_b);
    $torrentsperpage = '
	<select name="torrentsperpage">
		<option value="0">' . $lang->usercp['z5'] . '</option>';
    foreach (array ('5', '10', '20', '30', '40') as $perpage)
    {
      $torrentsperpage .= '<option value="' . $perpage . '"' . ($CURUSER['torrentsperpage'] == $perpage ? ' selected="selected"' : '') . '>' . sprintf ($lang->usercp['z6'], $perpage) . '</option>';
    }

    $torrentsperpage .= '</select>';
    $postsperpage = '
	<select name="postsperpage">
		<option value="0">' . $lang->usercp['z5'] . '</option>';
    foreach (array ('5', '10', '20', '30', '40') as $perpage)
    {
      $postsperpage .= '<option value="' . $perpage . '"' . ($CURUSER['postsperpage'] == $perpage ? ' selected="selected"' : '') . '>' . sprintf ($lang->usercp['z7'], $perpage) . '</option>';
    }

    $postsperpage .= '</select>';
    require INC_PATH . '/functions_timezone.php';
    require INC_PATH . '/functions_category2.php';
    $main .= '
	<script type="text/javascript">
		function show_hide_list()
		{
			var WorkArea = document.getElementById("categorylist").style.display;
			if (WorkArea == "none")
			{
				document.getElementById("categorylist").style.display = "block";
			}
			else
			{
				document.getElementById("categorylist").style.display = "none";
			}
		}
	</script>
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_details&do=save_details" name="edit_details">
	<input type="hidden" name="act" value="edit_details" />
	<input type="hidden" name="do" value="save_details" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					<fieldset>
						<legend>' . $lang->usercp['imode'] . '</legend>
						' . $lang->usercp['imode2'] . '<br />
						<input type="checkbox" name="options[invisible]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'invisible') ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['imode'] . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['pacc'] . '</legend>
						' . $lang->usercp['pacc2'] . '<br />
						<input type="checkbox" name="options[parked]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'parked') ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pacc'] . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['pm'] . '</legend>
						' . $lang->usercp['pm2'] . '<br /><br />
						' . $lang->usercp['pm3'] . '<br />
						<input type="radio" name="options[acceptpms]" value="yes"' . (user_options ($CURUSER['options'], 'acceptpms', 1) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm4'] . '
						<input type="radio" name="options[acceptpms]" value="friends"' . (user_options ($CURUSER['options'], 'acceptpms', 2) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm5'] . '
						<input type="radio" name="options[acceptpms]" value="no"' . (user_options ($CURUSER['options'], 'acceptpms', 3) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm6'] . '<br /><br />
						' . $lang->usercp['pm7'] . '<br />
						<input type="checkbox" name="options[popup]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'popup') ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm8'] . '<br /><br />
						' . $lang->usercp['pm9'] . '<br />
						<input type="checkbox" name="options[commentpm]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'commentpm') ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm10'] . '
						' . ($usergroups['canemailnotify'] == 'yes' ? '<br /><br />
						' . $lang->usercp['pm11'] . '<br />
						<input type="checkbox" name="pmnotif" value="yes" class="inlineimg"' . (strpos ($CURUSER['notifs'], '[pm]') !== false ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm12'] . '<br /><br />
						' . $lang->usercp['pm13'] . '<br />
						<input type="checkbox" name="emailnotif" value="yes" class="inlineimg"' . (strpos ($CURUSER['notifs'], '[email]') !== false ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['pm14'] . '<br />
						<input type="checkbox" name="nothingtodo" value="xxx" class="inlineimg" onclick="javascript: show_hide_list();" /> ' . $lang->usercp['pm15'] . '<span id="categorylist" style="display: none;">' . ts_category_list2 (1, 'edit_details') . '</span>' : '') . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['vm'] . '</legend>
						' . $lang->usercp['vm2'] . '<br /><br />
						' . $lang->usercp['vm3'] . '<br />
						<input type="checkbox" name="options[visitormsg]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'visitormsg', 1) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['vm4'] . '<br /><br />
						' . $lang->usercp['vm5'] . '<br />
						<input type="checkbox" name="options[visitormsg]" value="staff" class="inlineimg"' . (user_options ($CURUSER['options'], 'visitormsg', 2) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['vm6'] . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['show'] . '</legend>
						' . $lang->usercp['show1'] . '<br />
						<input type="checkbox" name="options[signatures]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'signatures', 1) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['show2'] . '<br />
						<input type="checkbox" name="options[avatars]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'avatars', 1) ? ' checked="checked"' : '') . ' /> ' . $lang->usercp['show3'] . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['z1'] . '</legend>
						' . $lang->usercp['z2'] . '<br /><br />
						<b>' . $lang->usercp['z3'] . '</b><br />
						' . $torrentsperpage . '<br /><br />
						<b>' . $lang->usercp['z4'] . '</b><br />
						' . $postsperpage . '
					</fieldset>
					' . ($caneditbday ? '
					<fieldset>
						<legend>' . $lang->usercp['dob1'] . '</legend>
						' . $lang->usercp['dob6'] . '<br />
						<table border="0" cellpadding="1" cellspacing="0" width="40">
							<tbody>
								<tr>
									<td class="none">' . $lang->usercp['dob2'] . '</td>
									<td class="none">' . $lang->usercp['dob3'] . '</td>
									<td class="none">' . $lang->usercp['dob4'] . '</td>
								</tr>
								<tr>
									<td class="none">' . $days . '</td>
									<td class="none">' . $months . '</td>
									<td class="none">' . $year . '</td>
								</tr>
							</tbody>
						</table>
					</fieldset>' : '') . '
					<fieldset>
						<legend>' . $lang->usercp['g1'] . '</legend>
						' . $lang->usercp['g5'] . '<br />
						<select name="options[gender]">
							<option value="1"' . (user_options ($CURUSER['options'], 'gender', 1) ? ' selected="selected"' : '') . '>' . $lang->usercp['g2'] . '</option>
							<option value="2"' . (user_options ($CURUSER['options'], 'gender', 2) ? ' selected="selected"' : '') . '>' . $lang->usercp['g3'] . '</option>
							<option value="3"' . (user_options ($CURUSER['options'], 'gender', 3) ? ' selected="selected"' : '') . '>' . $lang->usercp['g4'] . '</option>
						</select>
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['c1'] . '</legend>
						' . $lang->usercp['c2'] . '<br />
						' . $country . '
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['is1'] . '</legend>
						' . $lang->usercp['is4'] . '<br />
						<table border="0" cellpadding="1" cellspacing="0" width="200">
							<tbody>
								<tr>
									<td class="none">' . $lang->usercp['is2'] . '</td>
									<td class="none">' . $lang->usercp['is3'] . '</td>
								</tr>
								<tr>
									<td class="none">' . $downloadspeed . '</td>
									<td class="none">' . $uploadspeed . '</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
					' . show_timezone ($CURUSER['tzoffset'], (user_options ($CURUSER['options'], 'autodst') ? 1 : 0), (user_options ($CURUSER['options'], 'dst') ? 1 : 0)) . '
					<fieldset>
						<legend>' . $lang->usercp['browse1'] . '</legend>
						' . $lang->usercp['browse2'] . '<br />
						<input type="checkbox" name="options[quickmenu]" value="yes" class="inlineimg"' . (user_options ($CURUSER['options'], 'quickmenu', 1) ? ' checked="checked"' : '') . ' /> <b>' . $lang->usercp['browse3'] . '</b>
					</fieldset>
					<fieldset style="margin-bottom: 5px;">
						<legend>' . $lang->usercp['save'] . '</legend>
						<input type="submit" value="' . $lang->usercp['save'] . '" /> <input type="reset" value="' . $lang->usercp['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	</form>
	';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['link3'], 'main' => $main);
  }

  if ($act == 'edit_privacy')
  {
    ($query = sql_query ('' . 'SELECT userid FROM peers WHERE userid = \'' . $userid . '\'') OR sqlerr (__FILE__, 1058));
    if (0 < mysql_num_rows ($query))
    {
      $usergroups['canresetpasskey'] = 'no';
    }

    $equery = '';
    if ($do == 'save_privacy')
    {
      $_POST['showoffensivetorrents'] = ($_POST['showoffensivetorrents'] == 'no' ? '0' : '1');
      $CURUSER['options'] = preg_replace ('#I[0-5]#', 'I' . intval ($_POST['privacy']), $CURUSER['options']);
      $CURUSER['options'] = preg_replace ('#E[0-5]#', 'E' . intval ($_POST['showoffensivetorrents']), $CURUSER['options']);
      if (($_POST['resetpasskey'] == 'yes' AND $usergroups['canresetpasskey'] == 'yes'))
      {
        $randomtext = md5 ($SITENAME);
        $randompasskey = md5 ($CURUSER['username'] . get_date_time () . $CURUSER['passhash'] . $randomtext);
        $equery = ', passkey = ' . sqlesc ($randompasskey);
      }

      (sql_query ('UPDATE users SET options = ' . sqlesc ($CURUSER['options']) . $equery . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 1075));
      redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_privacy', $lang->usercp['saved6']);
      exit ();
    }

    $main .= '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_privacy&do=save_privacy">
	<input type="hidden" name="act" value="edit_privacy" />
	<input type="hidden" name="do" value="save_privacy" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					<fieldset>
						<legend>' . $lang->usercp['p0'] . '</legend>
						' . $lang->usercp['p1'] . '<br />
						<b>' . $lang->usercp['p0'] . ':</b><br />
						<select name="privacy">
							<option value="1"' . (user_options ($CURUSER['options'], 'privacy', 1) ? ' selected="selected"' : '') . '>' . $lang->usercp['p2'] . '</option>
							<option value="2"' . (user_options ($CURUSER['options'], 'privacy', 2) ? ' selected="selected"' : '') . '>' . $lang->usercp['p3'] . '</option>
							<option value="3"' . (user_options ($CURUSER['options'], 'privacy', 3) ? ' selected="selected"' : '') . '>' . $lang->usercp['p4'] . '</option>
							<option value="4"' . (user_options ($CURUSER['options'], 'privacy', 4) ? ' selected="selected"' : '') . '>' . $lang->usercp['p5'] . '</option>
						</select>
					</fieldset>
					<fieldset>
						<legend>' . $lang->usercp['o0'] . '</legend>
						' . $lang->usercp['o1'] . '<br />
						<input type="checkbox" name="showoffensivetorrents" value="no" class="inlineimg"' . (user_options ($CURUSER['options'], 'showoffensivetorrents') ? '' : ' checked="checked"') . ' /> ' . $lang->usercp['o0'] . '
					</fieldset>
					' . ($usergroups['canresetpasskey'] == 'yes' ? '
					<fieldset>
						<legend>' . $lang->usercp['r1'] . '</legend>
						' . $lang->usercp['r2'] . '<br />
						<input type="checkbox" name="resetpasskey" value="yes" class="inlineimg" /> ' . $lang->usercp['r1'] . '
					</fieldset>' : '') . '
					<fieldset style="margin-bottom: 5px;">
						<legend>' . $lang->usercp['save'] . '</legend>
						<input type="submit" value="' . $lang->usercp['save'] . '" /> <input type="reset" value="' . $lang->usercp['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	</form>
	';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['link7'], 'main' => $main);
  }

  if ($act == 'edit_theme_language')
  {
    if ($do == 'save_theme_language')
    {
      if ($_POST['stylesheet'] != $CURUSER['stylesheet'])
      {
        (sql_query ('UPDATE users SET stylesheet = ' . sqlesc (htmlspecialchars_uni ($_POST['stylesheet'])) . ('' . ' WHERE id = \'' . $userid . '\'')) OR sqlerr (__FILE__, 1131));
        redirect ($_SERVER['SCRIPT_NAME'] . '?act=edit_theme_language', $lang->usercp['saved8']);
        exit ();
      }
    }

    $dirlist = '';
    $link = 0;
    foreach (dir_list (INC_PATH . '/languages') as $language)
    {
      if (($link AND $link % 8 == 0))
      {
        $dirlist .= '<br />';
      }

      $dirlist .= '
		<a href="#" onclick="window.open(\'' . $BASEURL . '/set_language.php?language=' . $language . '\',\'set_language\',\'toolbar=no, scrollbars=no, resizable=no, width=250, height=20, top=250, left=250\'); return false;"><img src="' . $BASEURL . '/include/languages/' . $language . '/flag/flag.gif" alt="' . $language . '" title="' . $language . '" width="32" height="20" border="0" /></a>&nbsp;';
      ++$link;
    }

    $defaulttemplate = ts_template ();
    $template_dirs = dir_list (INC_PATH . '/templates');
    if (empty ($template_dirs))
    {
      $dirlist2 = '<option value="">There is no template</option>';
    }
    else
    {
      $t_image = '<img src="' . $BASEURL . '/include/templates/' . ($CURUSER['stylesheet'] ? htmlspecialchars_uni ($CURUSER['stylesheet']) : 'default') . '/images/header.jpg" border="0" width="100" height="20" style="vertical-align: middle;" id="t_image">';
      $dirlist2 = '<select name=\'stylesheet\' id=\'specialboxs\' style=\'vertical-align: middle;\' OnChange=\'javascript:document.forms[0].t_image.src="' . $BASEURL . '/include/templates/"+this.value+"/images/header.jpg"\'>';
      foreach ($template_dirs as $dir)
      {
        if (substr ($dir, 0 - 4, 1) !== '.')
        {
          $dirlist2 .= '<option value="' . $dir . '" ' . ($CURUSER['stylesheet'] == $dir ? 'selected="selected"' : ($defaulttemplate == $dir ? 'selected="selected"' : '')) . '>' . $dir . '</option>';
          continue;
        }
      }

      $dirlist2 .= '</select> ' . $t_image;
    }

    $main .= '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?act=edit_theme_language&do=save_theme_language">
	<input type="hidden" name="act" value="edit_theme_language" />
	<input type="hidden" name="do" value="save_theme_language" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					<fieldset>
						<legend>' . $lang->usercp['link8'] . '</legend>
						<b>' . $lang->usercp['l1'] . ':</b><br />
						' . $dirlist . '<br /><br />
						<b>' . $lang->usercp['l2'] . ':</b><br />
						' . $dirlist2 . '
					</fieldset>
					<fieldset style="margin-bottom: 5px;">
						<legend>' . $lang->usercp['save'] . '</legend>
						<input type="submit" value="' . $lang->usercp['save'] . '" /> <input type="reset" value="' . $lang->usercp['reset'] . '" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	</form>
	';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['link8'], 'main' => $main);
  }

  if ($act == 'show_userbar')
  {
    $main = '
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td class="none" width="100%">
					<fieldset style="margin-bottom: 5px;">
						<legend><img src="' . $BASEURL . '/torrentbar/torrentbar.php/' . $userid . '.png" border="0" class="inlineimg" /></legend>
						' . $lang->usercp['ub2'] . '<br /><textarea onClick="highlight(this);" cols="80" rows="1">[url=' . $BASEURL . '][img]' . $BASEURL . '/torrentbar/torrentbar.php/' . $userid . '.png[/img][/url]</textarea>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	';
    $contents = array ('title' => $lang->usercp['title'], 'title2' => $lang->usercp['ub1'], 'main' => $main);
  }

  stdhead ($lang->usercp['title']);
  echo '<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr valign="top">
			<td valign="top" width="180" class="none">
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo create_link (NULL, $lang->usercp['link1']);
  echo '								</td>
							</tr>
							<tr>
								<td>
									<b>&#187;</b> <a href="';
  echo ts_seo ($userid, $CURUSER['username']);
  echo '">';
  echo $lang->usercp['link2'];
  echo '</a><br />
									<b>&#187;</b> ';
  echo create_link ('edit_avatar', $lang->usercp['link4']);
  echo '<br />
									<b>&#187;</b> ';
  echo create_link ('edit_signature', $lang->usercp['link5']);
  echo '<br />
									<b>&#187;</b> ';
  echo create_link ('edit_password', $lang->usercp['link6']);
  echo '<br />
									<b>&#187;</b> ';
  echo create_link ('edit_details', $lang->usercp['link3']);
  echo '<br />
									<b>&#187;</b> ';
  echo create_link ('edit_privacy', $lang->usercp['link7']);
  echo '<br />
								    <b>&#187;</b> ';
  echo create_link ('edit_theme_language', $lang->usercp['link8']);
  echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo $lang->usercp['usertools'];
  echo '								</td>
							</tr>
							<tr>
								<td>
									<b>&#187;</b> ';
  echo create_link ('show_userbar', $lang->usercp['ub1']);
  echo '<br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/takeflush.php?id=';
  echo $userid;
  echo '">';
  echo $lang->usercp['usertools1'];
  echo '</a><br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/invite.php">';
  echo $lang->usercp['usertools2'];
  echo '</a><br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/referrals.php">';
  echo $lang->usercp['usertools3'];
  echo '</a><br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/port_check.php">';
  echo $lang->usercp['usertools4'];
  echo '</a><br />
									';
  echo ($IsStaff ? '<b>&#187;</b> <a href="' . $BASEURL . '/admin/ts_watch_list.php?action=show_list">' . $lang->usercp['usertools5'] . '</a>' : '');
  echo '								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo $lang->usercp['m1'];
  echo '								</td>
							</tr>
							<tr>
								<td>
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/messages.php">';
  echo $lang->usercp['m2'];
  echo '</a><br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/messages.php?userid=';
  echo $userid;
  echo '&do=editfolders">';
  echo $lang->usercp['m3'];
  echo '</a><br />
									<b>&#187;</b> <a href="';
  echo $BASEURL;
  echo '/sendmessage.php">';
  echo $lang->usercp['m4'];
  echo '</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</td>
			<td valign="top" class="none" style="padding-left: 15px">
				<div style="padding-bottom: 15px;">
					<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td class="thead">
									';
  echo $contents['title'];
  echo '								</td>
							</tr>
							';
  echo ($contents['title2'] != '' ? '
							<tr>
								<td class="subheader">
									' . $contents['title2'] . '
								</td>
							</tr>' : '');
  echo '
							<tr>
								<td>
									';
  echo $contents['main'];
  echo '								</td>
							</tr>
						</tbody>
					</table>
					';
  echo $substhreads . $substorrents;
  echo '				</div>
			</td>
		</tr>
	</tbody>
</table>
';
  stdfoot ();
?>
