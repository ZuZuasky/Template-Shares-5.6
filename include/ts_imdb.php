<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function cleanstring ($imputString)
  {
    $whatToCleanArray = array (chr (13), chr (10), chr (13) . chr (10), chr (10) . chr (13), '
', '  ', '   ', '    ', '

', '

');
    $cleanWithArray = array ('', '', '', '', '', '', '', '', '', '');
    $cleaned = str_replace ($whatToCleanArray, $cleanWithArray, $imputString);
    $cleaned = trim ($cleaned);
    return $cleaned;
  }

  function fetch_data ($url, $cleantext = true)
  {
    @ini_set ('user_agent', 'TS_SE via cURL/PHP');
    $data = false;
    if ((function_exists ('curl_init') AND $ch = curl_init ()))
    {
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_TIMEOUT, 90);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt ($ch, CURLOPT_HEADER, false);
      curl_setopt ($ch, CURLOPT_USERAGENT, 'TS_SE via cURL/PHP');
      $data = curl_exec ($ch);
      curl_close ($ch);
    }

    if ((!$data AND !ini_get ('allow_url_fopen') == 0))
    {
      $contents = '';
      if ($handle = @fopen ($url, 'rb'))
      {
        while (!feof ($handle))
        {
          $contents .= fread ($handle, 8192);
        }

        fclose ($handle);
        $data = $contents;
      }
    }

    if (!$data)
    {
      $data = file_get_contents ($url);
    }

    return ($cleantext == true ? cleanstring ($data) : $data);
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('max_execution_time', '20000');
  @ini_set ('max_input_time', '20000');
  define ('TS_IMDB_VERSION', '0.9 by xam');
  if (((!defined ('IN_TRACKER') OR !defined ('IN_SCRIPT_TSSEv56')) OR !defined ('TU_VERSION')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $regex = '#http://www.imdb.com/title/(.*)/#U';
  preg_match ($regex, $t_link, $_id_);
  $_id_ = $_id_[1];
  $url = '' . 'http://www.imdb.com/title/' . $_id_ . '/';
  $text = fetch_data ($url);
  preg_match ('/<div class="photo"><a name="poster" href=".*" title=".*"><img border="0" alt=".*" title=".*" src="(.*)" \\/><\\/a><\\/div>/isU', $text, $photo);
  $cover_photo_url = $photo[1];
  $cover_photo_name = '' . $torrent_dir . '/images/' . $_id_ . '.jpg';
  if (file_exists ($cover_photo_name))
  {
    unlink ($cover_photo_name);
  }

  $handle = fopen ($cover_photo_name, 'x');
  fwrite ($handle, fetch_data ($cover_photo_url, false));
  fclose ($handle);
  $regex = '#<h1>(.*)</h1>#U';
  preg_match_all ($regex, $text, $title, PREG_SET_ORDER);
  $regex = '#<h5>Plot:</h5>(.*)</div>#U';
  preg_match_all ($regex, $text, $plot, PREG_SET_ORDER);
  $regex = '#<div class="info"><h5>Genre:</h5>(.*)</div>#U';
  preg_match_all ($regex, $text, $genre, PREG_SET_ORDER);
  $regex = '#<div class="info"><h5>Language:</h5><a(.*)>(.*)</a>#U';
  preg_match_all ($regex, $text, $language, PREG_SET_ORDER);
  $regex = '#<div class="info"><h5>Country:</h5><a(.*)>(.*)</a>#U';
  preg_match_all ($regex, $text, $country, PREG_SET_ORDER);
  $regex = '#<div class="meta">(.*)</div>#U';
  preg_match_all ($regex, $text, $rating, PREG_SET_ORDER);
  $regex = '#<h5>Runtime:</h5>(.*)</div>#U';
  preg_match_all ($regex, $text, $runtime, PREG_SET_ORDER);
  $regex = '#<h5>Release Date:</h5>(.*)<a#U';
  preg_match_all ($regex, $text, $releasedate, PREG_SET_ORDER);
  $title = strip_tags ($title[0][1]);
  $t_link = '' . '<table width=\'100%\' border=\'0\' align=\'center\' class=\'none\'><tr><td colspan=\'2\' class=\'none\' align=\'left\'><b>' . $title . '</b></td></tr><tr><td class=\'none\' align=\'center\' valign=\'top\'><img src=\'' . $cover_photo_name . '\' border=\'0\' alt=\'' . $title . '\' title=\'' . $title . '\'></td><td class=\'none\' valign=\'top\' align=\'left\'><b>Genre:</b> ' . strip_tags (str_replace ('more', '', $genre[0][1])) . '<br />' . ($releasedate[0][1] ? '<b>Release Date:</b> ' . $releasedate[0][1] . '<br />' : '') . '<b>User Rating:</b> ' . strip_tags ($rating[0][1]) . ('' . '<br /><b>Language:</b> ' . $language[0][2] . '<br /><b>Country:</b> ' . $country[0][2] . '<br />') . ($runtime[0][1] ? '<b>Runtime:</b> ' . $runtime[0][1] . '<br />' : '') . '<b>Plot Outline:</b> ' . strip_tags (str_replace ('more', '', $plot[0][1])) . ('' . '<br /><b>IMDB Link:</b> <a href=\'' . $t_link . '\' target=\'_blank\' alt=\'' . $title . '\' title=\'' . $title . '\'>' . $t_link . '</a></td></tr></table>');
?>
