<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function colorhex ($img, $HexColorString)
  {
    $R = hexdec (substr ($HexColorString, 0, 2));
    $G = hexdec (substr ($HexColorString, 2, 2));
    $B = hexdec (substr ($HexColorString, 4, 2));
    return imagecolorallocate ($img, $R, $G, $B);
  }

  function colorhexshadow ($img, $HexColorString, $mork)
  {
    $R = hexdec (substr ($HexColorString, 0, 2));
    $G = hexdec (substr ($HexColorString, 2, 2));
    $B = hexdec (substr ($HexColorString, 4, 2));
    if ($mork)
    {
      (99 < $R ? $R -= 100 : $R = 0);
      (99 < $G ? $G -= 100 : $G = 0);
      (99 < $B ? $B -= 100 : $B = 0);
    }
    else
    {
      ($R < 220 ? $R += 35 : $R = 255);
      ($G < 220 ? $G += 35 : $G = 255);
      ($B < 220 ? $B += 35 : $B = 255);
    }

    return imagecolorallocate ($img, $R, $G, $B);
  }

  function outputimage ($img)
  {
    header ('Content-type: image/jpg');
    imagejpeg ($img, NULL, 100);
  }

  @ini_set ('display_errors', '0');
  @error_reporting (0);
  @ini_set ('log_errors', '0');
  if (!extension_loaded ('gd'))
  {
    exit ();
  }

  $data = $_GET['data'];
  $label = $_GET['label'];
  if ((((empty ($data) OR empty ($label)) OR !preg_match ('#^[0-9]+\\*[0-9]+#U', $data, $results)) OR !preg_match ('#^[a-z|A-Z]+\\*[a-z|A-Z]+#U', $label, $results2)))
  {
    exit ();
  }

  $width = ($_GET['width'] ? intval ($_GET['width']) : 96);
  $shadow_height = ($_GET['shadow_height'] ? intval ($_GET['shadow_height']) : 3);
  if (!$width)
  {
    $width = 96;
  }

  if (!$shadow_height)
  {
    $shadow_height = 6;
  }

  $show_label = true;
  $show_percent = true;
  $show_text = true;
  $show_parts = false;
  $label_form = 'square';
  $background_color = 'FFFFFF';
  $text_color = '000000';
  $colors = array ('003366', 'CCD6E0', '7F99B2', 'F7EFC6', 'C6BE8C', 'CC6600', '990000', '520000', 'BFBFC1', '808080');
  $shadow_dark = true;
  $height = $width / 2;
  $data = explode ('*', $data);
  if ($label != '')
  {
    $label = explode ('*', $label);
  }

  $i = 0;
  while ($i < count ($label))
  {
    if ($data[$i] / @array_sum ($data) < 0.100000000000000005551115)
    {
      $number[$i] = ' ' . number_format ($data[$i] / @array_sum ($data) * 100, 1, ',', '.') . '%';
    }
    else
    {
      $number[$i] = number_format ($data[$i] / array_sum ($data) * 100, 1, ',', '.') . '%';
    }

    if ($text_length < strlen ($label[$i]))
    {
      $text_length = strlen ($label[$i]);
    }

    ++$i;
  }

  if (is_array ($label))
  {
    $antal_label = count ($label);
    $xtra = 5 + 15 * $antal_label - ($height + ceil ($shadow_height));
    if (0 < $xtra)
    {
      $xtra_height = 5 + 15 * $antal_label - ($height + ceil ($shadow_height));
    }

    $xtra_width = 5;
    if ($show_label)
    {
      $xtra_width += 20;
    }

    if ($show_percent)
    {
      $xtra_width += 45;
    }

    if ($show_text)
    {
      $xtra_width += $text_length * 8;
    }

    if ($show_parts)
    {
      $xtra_width += 35;
    }
  }

  $img = imagecreatetruecolor ($width + $xtra_width, $height + ceil ($shadow_height) + $xtra_height);
  imagefill ($img, 0, 0, colorhex ($img, $background_color));
  foreach ($colors as $colorkode)
  {
    $fill_color[] = colorhex ($img, $colorkode);
    $shadow_color[] = colorhexshadow ($img, $colorkode, $shadow_dark);
  }

  $label_place = 5;
  if (is_array ($label))
  {
    $i = 0;
    while ($i < count ($label))
    {
      if (($label_form == 'round' AND $show_label))
      {
        imagefilledellipse ($img, $width + 11, $label_place + 5, 10, 10, colorhex ($img, $colors[$i % count ($colors)]));
        imageellipse ($img, $width + 11, $label_place + 5, 10, 10, colorhex ($img, $text_color));
      }
      else
      {
        if (($label_form == 'square' AND $show_label))
        {
          imagefilledrectangle ($img, $width + 6, $label_place, $width + 16, $label_place + 10, colorhex ($img, $colors[$i % count ($colors)]));
          imagerectangle ($img, $width + 6, $label_place, $width + 16, $label_place + 10, colorhex ($img, $text_color));
        }
      }

      if ($show_percent)
      {
        $label_output = $number[$i] . ' ';
      }

      if ($show_text)
      {
        $label_output = $label_output . $label[$i] . ' ';
      }

      if ($show_parts)
      {
        $label_output = $label_output . $data[$i];
      }

      imagestring ($img, '2', $width + 20, $label_place, $label_output, colorhex ($img, $text_color));
      $label_output = '';
      $label_place = $label_place + 15;
      ++$i;
    }
  }

  $centerX = round ($width / 2);
  $centerY = round ($height / 2);
  $diameterX = $width - 4;
  $diameterY = $height - 4;
  $data_sum = array_sum ($data);
  $start = 270;
  $i = 0;
  while ($i < count ($data))
  {
    $value += $data[$i];
    $end = @ceil ($value / $data_sum * 360) + 270;
    $slice[] = array ($start, $end, $shadow_color[$value_counter % count ($shadow_color)], $fill_color[$value_counter % count ($fill_color)]);
    $start = $end;
    ++$value_counter;
    ++$i;
  }

  $i = $centerY + $shadow_height;
  while ($centerY < $i)
  {
    $j = 0;
    while ($j < count ($slice))
    {
      imagefilledarc ($img, $centerX, $i, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][2], IMG_ARC_PIE);
      ++$j;
    }

    --$i;
  }

  $j = 0;
  while ($j < count ($slice))
  {
    imagefilledarc ($img, $centerX, $centerY, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][3], IMG_ARC_PIE);
    ++$j;
  }

  outputimage ($img);
  imagedestroy ($img);
?>
