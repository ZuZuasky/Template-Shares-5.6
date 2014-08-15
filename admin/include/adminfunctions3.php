<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_get_url_contents ($url = '')
  {
    if ((function_exists ('curl_init') AND $ch = curl_init ()))
    {
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_TIMEOUT, 90);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt ($ch, CURLOPT_HEADER, false);
      $contents = curl_exec ($ch);
      curl_close ($ch);
      return $contents;
    }

    if (!ini_get ('allow_url_fopen') == 0)
    {
      if (!$handle = @fopen ($url, 'rb'))
      {
        return null;
      }

      while (!feof ($handle))
      {
        $contents .= fread ($handle, 8192);
      }

      fclose ($handle);
      return $contents;
    }

    return file_get_contents ($url);
  }

  define ('_AF__3', true);
  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  require_once $thispath . 'include/adminfunctions4.php';
  if (!defined ('_AF___4'))
  {
    exit ('The authentication has been blocked because of invalid file detected!');
  }

?>
