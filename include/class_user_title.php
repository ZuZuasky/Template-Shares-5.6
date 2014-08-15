<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_error ($errormsg = '')
  {
    $im = imagecreatetruecolor (150, 30);
    $bgc = imagecolorallocate ($im, 255, 255, 255);
    $tc = imagecolorallocate ($im, 0, 0, 0);
    imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
    imagestring ($im, 1, 5, 5, $errormsg, $tc);
    header ('Content-Type: image/png');
    imagepng ($im);
    imagedestroy ($im);
    exit ();
  }

  function get_charset ()
  {
    $path = './../config/THEME';
    $fp = fopen ($path, 'r');
    if (!$fp)
    {
      show_error ('Can\'t open THEME file.');
    }

    $content;
    while (!feof ($fp))
    {
      $content .= fread ($fp, 102400);
    }

    fclose ($fp);
    $tmp = unserialize ($content);
    if (empty ($tmp))
    {
      show_error ('Can\'t open THEME file.');
    }

    $GLOBALS['THEME'] = $tmp;
  }

  error_reporting (E_ALL & ~E_NOTICE);
  ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  ini_set ('display_errors', '0');
  ini_set ('display_startup_errors', '0');
  ini_set ('ignore_repeated_errors', '1');
  define ('CUT_VERSION', '1.2 by xam');
  get_charset ();
  $text = (isset ($_GET['str']) ? base64_decode ($_GET['str']) : 'Registered User');
  $text = strval ($text);
  if (strtolower ($THEME['charset']) != 'utf-8')
  {
    if (function_exists ('iconv'))
    {
      $text = iconv ('UTF-8', $THEME['charset'], $text);
    }
    else
    {
      if (function_exists ('mb_convert_encoding'))
      {
        $text = mb_convert_encoding ($text, $THEME['charset'], 'UTF-8');
      }
      else
      {
        if (strtolower ($THEME['charset']) == 'iso-8859-1')
        {
          $text = utf8_decode ($text);
        }
      }
    }
  }

  $pngpath = './../pic/info/';
  $png = '' . $pngpath . 'rank_star_blank.png';
  if (isset ($_GET['png']))
  {
    $temppng = base64_decode ($_GET['png']);
    if (file_exists ('' . $pngpath . $temppng . '.png'))
    {
      $png = '' . $pngpath . $temppng . '.png';
    }
  }

  if (!file_exists ($png))
  {
    show_error ('' . 'Can\'t open ' . $png . ' file');
  }

  $im = imagecreatefrompng ($png);
  if (!$im)
  {
    show_error ('Can\'t create Image file');
  }

  $orange = imagecolorallocate ($im, 220, 210, 60);
  $px = (imagesx ($im) - 7.5 * strlen ($text)) / 2;
  imagestring ($im, 2, $px, 3, $text, $orange);
  header ('' . 'Content-type: image/png; charset=' . $THEME['charset']);
  imagepng ($im);
  imagedestroy ($im);
?>
