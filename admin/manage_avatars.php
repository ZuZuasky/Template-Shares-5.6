<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function scan_image ($image)
  {
    global $_adir;
    $image = trim (file_get_contents ($_adir . $image));
    if (!$image)
    {
      return false;
    }

    if (preg_match ('#(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize|alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title)#is', $image))
    {
      return false;
    }

    return true;
  }

  function get_image_contents ($image)
  {
    global $_adir;
    $image = getimagesize ($_adir . $image);
    if (!$image)
    {
      return false;
    }

    return array ('width' => $image['0'], 'height' => $image['1'], 'mime' => $image['mime']);
  }

  if (!defined ('IN_ADMIN_PANEL'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('M_AVATARS', 'v.1.2 by xam');
  $_adir = INC_PATH . '/avatars/';
  $_filetypes = array ('gif', 'jpg', 'png');
  $_avatars = array ();
  if (((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND 0 < count ($_POST['avatars'])) AND in_array ($_POST['action_type'], array ('resize', 'delete'), true)))
  {
    $action_avatars = $_POST['avatars'];
    $action_type = $_POST['action_type'];
    if ($action_type == 'delete')
    {
      include_once $rootpath . '/admin/include/staff_languages.php';
      require_once INC_PATH . '/functions_pm.php';
      foreach ($action_avatars as $delete_avatar)
      {
        $__exp = str_replace (array ('.gif', '.png', '.jpg'), '', $delete_avatar);
        $__exp = explode ('_', $__exp);
        $__userid = $__exp[1];
        if (unlink ($_adir . $delete_avatar))
        {
          sql_query ('UPDATE users SET avatar = \'\' WHERE id = ' . sqlesc ($__userid));
          send_pm ($__userid, $manage_avatars['message']['body'], $manage_avatars['message']['subject']);
          continue;
        }
      }
    }
    else
    {
      if ($action_type == 'resize')
      {
        require INC_PATH . '/readconfig_forumcp.php';
        $width = $f_avatar_maxwidth;
        $height = $f_avatar_maxheight;
        foreach ($action_avatars as $filename)
        {
          $exti = get_extension ($filename);
          $filename = $_adir . $filename;
          list ($width_orig, $height_orig) = getimagesize ($filename);
          $ratio_orig = $width_orig / $height_orig;
          if ($ratio_orig < $width / $height)
          {
            $width = $height * $ratio_orig;
          }
          else
          {
            $height = $width / $ratio_orig;
          }

          $image_p = imagecreatetruecolor ($width, $height);
          if ($exti == 'jpg')
          {
            $image = imagecreatefromjpeg ($filename);
          }
          else
          {
            if ($exti == 'gif')
            {
              $image = imagecreatefromgif ($filename);
            }
            else
            {
              if ($exti == 'png')
              {
                $image = imagecreatefrompng ($filename);
              }
            }
          }

          imagecopyresampled ($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
          ob_start ();
          if ($exti == 'jpg')
          {
            imagejpeg ($image_p, null, 100);
          }
          else
          {
            if ($exti == 'gif')
            {
              imagegif ($image_p);
            }
            else
            {
              if ($exti == 'png')
              {
                imagepng ($image_p);
              }
            }
          }

          $image = ob_get_contents ();
          ob_end_clean ();
          $fp = fopen ($filename, 'w');
          fwrite ($fp, $image);
          fclose ($fp);
        }
      }
    }
  }

  if ($handle = opendir ($_adir))
  {
    while (false !== $file = readdir ($handle))
    {
      if ((($file != '.' AND $file != '..') AND in_array (get_extension ($file), $_filetypes, true)))
      {
        $_avatars[] = $file;
        continue;
      }
    }

    closedir ($handle);
  }

  $users = array ();
  ($query = sql_query ('' . 'SELECT u.avatar,u.username, u.id, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.avatar REGEXP \'^' . $BASEURL . '/include/avatars/.*\\.(gif|jpg|png)\'') OR sqlerr (__FILE__, 123));
  while ($user = mysql_fetch_assoc ($query))
  {
    $users[$user['id']][$user['avatar']] = '<a href="' . $BASEURL . '/userdetails.php?id=' . $user['id'] . '">' . get_user_color ($user['username'], $user['namestyle']) . '</a>';
  }

  stdhead ('Manage Avatars - ' . M_AVATARS);
  echo '
<style type="text/css">
	div.thumb
	{
		float: left;
		height: 195px;
		margin-bottom: 10px;
		margin-right: 10px;
		width: 125px;
		text-align: left;
	}

	div.thumb a img
	{
		border: none;
		margin: 0;
	}
</style>
<script type="text/javascript"> 
	function active_this_image(ImageID)
	{	
		var valuEtoChange = document.getElementById(ImageID).style.background;
		if (valuEtoChange.match(/white/) || !valuEtoChange)
		{
			document.getElementById(ImageID).style.background = "#DF7401";
		}
		else
		{
			document.getElementById(ImageID).style.background = "white";
		}
	}
</script>
<form method="post" action="' . $_this_script_ . '">
';
  _form_header_open_ ('Manage Avatars');
  $str = '<tr><td>';
  foreach ($_avatars as $avatar)
  {
    $_exp = str_replace (array ('.gif', '.png', '.jpg'), '', $avatar);
    $_exp = explode ('_', $_exp);
    $_userid = $_exp[1];
    $_sav = $BASEURL . '/include/avatars/' . $avatar;
    $_ad = get_image_contents ($avatar);
    $str .= '
	<div class="thumb">
		<img src="' . $BASEURL . '/include/avatars/' . $avatar . '" border="0" width="100" height="100"> 
		Scan: ' . (scan_image ($avatar) ? '<font color="green"><b>Passed</b></font>' : '<font color="red"><b>Possible Hack!</b></font>') . '<br />
		Size: ' . mksize (filesize ($_adir . $avatar)) . '<br />
		Pixel:  ' . $_ad['width'] . 'x' . $_ad['height'] . ' pixel<br />
		Type: ' . $_ad['mime'] . '<br />
		Owner: ' . $users[$_userid][$_sav] . '<br />
		<table border="0">
			<tr>
				<td class="none" id="' . md5 ($avatar) . '">
					<input class="none" type="checkbox" name="avatars[]" value="' . $avatar . '" onclick="active_this_image(\'' . md5 ($avatar) . '\')" />
				</td>
			</tr>
		</table>
	</div>';
  }

  $str .= '</td></tr>
<tr>
	<td>
		What do you want to do with selected images? 
		<select name="action_type">
			<option value="resize">resize selected images</option>
			<option value="delete">delete selected images</option>
		</select>
		<input type="submit" value="do it" class=button style="vertical-align: middle;">
	</td>
</tr>';
  echo $str;
  _form_header_close_ ();
  echo '</form>';
  stdfoot ();
  exit ();
?>
