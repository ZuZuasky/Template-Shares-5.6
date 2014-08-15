<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function is_valid_id ($id)
  {
    return ((is_numeric ($id) AND 0 < $id) AND floor ($id) == $id);
  }

  function get_userid ()
  {
    $id = preg_replace ('#(.*)\\/(.*)\\.png#i', '' . '$2', $_SERVER['REQUEST_URI']);
    $id = trim (substr (trim ($id), 0, 10));
    if (!is_valid_id ($id))
    {
      exit ('Invalid Request!');
    }

    return 0 + $id;
  }

  function mysql_init ()
  {
    global $config_file;
    define ('IN_ANNOUNCE', true);
    include_once $config_file;
    if (!($link = @mysql_connect ($mysql_host, $mysql_user, $mysql_pass)))
    {
      exit ('Cannot connect to database!');
      ;
    }

    if (!(mysql_select_db ($mysql_db, $link)))
    {
      exit ('Cannot select database!');
      ;
    }

  }

  function ifthen ($ifcondition, $iftrue, $iffalse)
  {
    if ($ifcondition)
    {
      return $iftrue;
    }

    return $iffalse;
  }

  function getpostfix ($val)
  {
    $postfix = 'b';
    if (1024 <= $val)
    {
      $postfix = 'kb';
    }

    if (1048576 <= $val)
    {
      $postfix = 'mb';
    }

    if (1073741824 <= $val)
    {
      $postfix = 'gb';
    }

    if (1099511627776 <= $val)
    {
      $postfix = 'tb';
    }

    if (1125899906842624 <= $val)
    {
      $postfix = 'pb';
    }

    if (1152921504606846976 <= $val)
    {
      $postfix = 'eb';
    }

    if (1180591620717411303424 <= $val)
    {
      $postfix = 'zb';
    }

    if (1.20892581961462917470618e+24 <= $val)
    {
      $postfix = 'yb';
    }

    return $postfix;
  }

  function roundcounter ($value, $postfix)
  {
    $val = $value;
    switch ($postfix)
    {
      case 'kb':
      {
        $val = $val / 1024;
        break;
      }

      case 'mb':
      {
        $val = $val / 1048576;
        break;
      }

      case 'gb':
      {
        $val = $val / 1073741824;
        break;
      }

      case 'tb':
      {
        $val = $val / 1099511627776;
        break;
      }

      case 'pb':
      {
        $val = $val / 1125899906842624;
        break;
      }

      case 'eb':
      {
        $val = $val / 1152921504606846976;
        break;
      }

      case 'zb':
      {
        $val = $val / 1180591620717411303424;
        break;
      }

      case 'yb':
      {
        $val = $val / 1.20892581961462917470618e+24;
        break;
      }

      default:
      {
        break;
      }
    }

    return $val;
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  $config_file = './../include/config_announce.php';
  $template_file = './template.png';
  $rating_x = 37;
  $rating_y = 6;
  $upload_x = 104;
  $upload_y = 6;
  $download_x = 198;
  $download_y = 6;
  $digits_template = './digits.png';
  $digits_config = './digits.ini';
  if (!($digits_ini = @parse_ini_file ($digits_config)))
  {
    exit ('Cannot load Digits Configuration file!');
    ;
  }

  if (!($digits_img = @imagecreatefrompng ($digits_template)))
  {
    exit ('Cannot Initialize new GD image stream!');
    ;
  }

  $download_counter = 0;
  $upload_counter = 0;
  $rating_counter = 0;
  if (!($img = @imagecreatefrompng ($template_file)))
  {
    exit ('Cannot Initialize new GD image stream!');
    ;
  }

  $userid = get_userid ();
  mysql_init ();
  if (!($result = mysql_query ('SELECT uploaded, downloaded, options FROM users WHERE enabled = \'yes\' AND status = \'confirmed\' AND id = ' . mysql_real_escape_string ($userid))))
  {
    exit ('Could not select data!');
    ;
  }

  if (mysql_num_rows ($result) == 0)
  {
    exit ('Invalid User!');
  }

  $user = mysql_fetch_assoc ($result);
  if ((preg_match ('#I3#is', $user['options']) OR preg_match ('#I4#is', $user['options'])))
  {
    $user['uploaded'] = 0;
    $user['downloaded'] = 0;
  }

  $upload_counter = $user['uploaded'];
  $download_counter = $user['downloaded'];
  if (0 < $download_counter)
  {
    $rating_counter = $upload_counter / $download_counter;
  }

  $dot_pos = strpos ((string)$rating_counter, '.');
  if (0 < $dot_pos)
  {
    $rating_counter = (string)round (substr ((string)$rating_counter, 0, $dot_pos + 1 + 2), 2);
  }
  else
  {
    $rating_counter = (string)$rating_counter;
  }

  $counter_x = $rating_x;
  $i = 0;
  while ($i < strlen ($rating_counter))
  {
    $d_x = $digits_ini[ifthen ($rating_counter[$i] == '.', 'dot', $rating_counter[$i]) . '_x'];
    $d_w = $digits_ini[ifthen ($rating_counter[$i] == '.', 'dot', $rating_counter[$i]) . '_w'];
    imagecopy ($img, $digits_img, $counter_x, $rating_y, $d_x, 0, $d_w, imagesy ($digits_img));
    $counter_x = $counter_x + $d_w - 1;
    ++$i;
  }

  $postfix = getpostfix ($upload_counter);
  $upload_counter = roundcounter ($upload_counter, $postfix);
  $dot_pos = strpos ((string)$upload_counter, '.');
  if (0 < $dot_pos)
  {
    $upload_counter = (string)round (substr ((string)$upload_counter, 0, $dot_pos + 1 + 2), 2);
  }
  else
  {
    $upload_counter = (string)$upload_counter;
  }

  $counter_x = $upload_x;
  $i = 0;
  while ($i < strlen ($upload_counter))
  {
    $d_x = $digits_ini[ifthen ($upload_counter[$i] == '.', 'dot', $upload_counter[$i]) . '_x'];
    $d_w = $digits_ini[ifthen ($upload_counter[$i] == '.', 'dot', $upload_counter[$i]) . '_w'];
    imagecopy ($img, $digits_img, $counter_x, $upload_y, $d_x, 0, $d_w, imagesy ($digits_img));
    $counter_x = $counter_x + $d_w - 1;
    ++$i;
  }

  $counter_x += 3;
  $d_x = $digits_ini[$postfix . '_x'];
  $d_w = $digits_ini[$postfix . '_w'];
  imagecopy ($img, $digits_img, $counter_x, $upload_y, $d_x, 0, $d_w, imagesy ($digits_img));
  $postfix = getpostfix ($download_counter);
  $download_counter = roundcounter ($download_counter, $postfix);
  $dot_pos = strpos ((string)$download_counter, '.');
  if (0 < $dot_pos)
  {
    $download_counter = (string)round (substr ((string)$download_counter, 0, $dot_pos + 1 + 2), 2);
  }
  else
  {
    $download_counter = (string)$download_counter;
  }

  $counter_x = $download_x;
  $i = 0;
  while ($i < strlen ($download_counter))
  {
    $d_x = $digits_ini[ifthen ($download_counter[$i] == '.', 'dot', $download_counter[$i]) . '_x'];
    $d_w = $digits_ini[ifthen ($download_counter[$i] == '.', 'dot', $download_counter[$i]) . '_w'];
    imagecopy ($img, $digits_img, $counter_x, $download_y, $d_x, 0, $d_w, imagesy ($digits_img));
    $counter_x = $counter_x + $d_w - 1;
    ++$i;
  }

  $counter_x += 3;
  $d_x = $digits_ini[$postfix . '_x'];
  $d_w = $digits_ini[$postfix . '_w'];
  imagecopy ($img, $digits_img, $counter_x, $download_y, $d_x, 0, $d_w, imagesy ($digits_img));
  header ('Content-type: image/png');
  imagepng ($img);
  imagedestroy ($img);
?>
