<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function pathurlencode ($uri = '')
  {
    $uri = urlencode ($uri);
    $uri = str_replace ('%3A', ':', $uri);
    $uri = str_replace ('%2F', '/', $uri);
    $uri = str_replace ('%26', '&', $uri);
    $uri = str_replace ('%40', '@', $uri);
    $uri = str_replace ('%3A', ':', $uri);
    $uri = str_replace ('%3F', '?', $uri);
    $uri = str_replace ('%3D', '=', $uri);
    $uri = str_replace ('%5B', '[', $uri);
    $uri = str_replace ('%5D', ']', $uri);
    return $uri;
  }

  function scrape_connect ($httpurl, $binsha1s)
  {
    global $error;
    $fp = fopen ($httpurl, 'rb');
    $stream = '';
    if ($fp)
    {
      while (!feof ($fp))
      {
        $stream .= @fread ($fp, 128);
      }

      fclose ($fp);
    }

    if (($fp === FALSE OR empty ($stream)))
    {
      $error[] = '' . 'No Response From Tracker. Please Try Again. (' . $httpurl . ')';
      return null;
    }

    $decoded = bdecode ($stream);
    if ($decoded['files'] === TRUE)
    {
      $error[] = 'File not present on tracker (torrent may be dead).';
      return null;
    }

    $files = $decoded['files'];
    $sha1tor = $files[$binsha1s];
    if (isset ($files[$binsha1s]))
    {
      $GLOBALS['e_seeders'] = $GLOBALS['e_seeders'] + $sha1tor['complete'];
      $GLOBALS['e_leechers'] = $GLOBALS['e_leechers'] + $sha1tor['incomplete'];
      if (isset ($sha1tor['downloaded']))
      {
        $GLOBALS['e_completed'] = $GLOBALS['e_completed'] + $sha1tor['downloaded'];
        return null;
      }
    }
    else
    {
      $error[] = 'Error with tracker response.';
    }

  }

  define ('TSE_VERSION', '0.5 by xam');
  require_once INC_PATH . '/ts_external_scrape/ts_decode.php';
  require_once INC_PATH . '/ts_external_scrape/ts_encode.php';
  $error = array ();
  $stream = @file_get_contents ($externaltorrent);
  if ($stream == FALSE)
  {
    $error[] = '' . $externaltorrent . ' cannot be opened!';
  }
  else
  {
    if (!isset ($stream))
    {
      $error[] = '' . 'Error in Opening file: ' . $externaltorrent;
    }
    else
    {
      $array = bdecode ($stream);
      if ($array === FALSE)
      {
        $error[] = '' . 'Error in file. Not valid BEncoded Data: ' . $externaltorrent;
      }
      else
      {
        if (array_key_exists ('info', $array) === FALSE)
        {
          $error[] = '' . 'Error in file. Not a valid torrent file: ' . $externaltorrent;
        }
        else
        {
          $infohash = sha1 (bencode ($array['info']));
          $skip_first = false;
          if (isset ($array['announce-list']))
          {
            foreach ($array['announce-list'] as $alist)
            {
              $announce = strtolower ($alist[0]);
              if ($announce == strtolower ($array['announce']))
              {
                $skip_first = true;
              }

              $tmp = str_replace ('//announce', '//_temp_', $announce);
              if (substr ($announce, 0, 7) == 'http://')
              {
                if (substr_count ($tmp, '/announce') == 1)
                {
                  $scrape = str_replace (array ('/announce', '//_temp_'), array ('/scrape', '//announce'), $tmp);
                  $httpget = (preg_match ('#\\?passkey=#i', $scrape) ? '&' : '?') . 'info_hash=';
                  $binsha1 = pack ('H*', $infohash);
                  $binsha1s = addslashes ($binsha1);
                  $fullurl = $scrape . $httpget . $binsha1;
                  $httpurl = pathurlencode ($fullurl);
                  scrape_connect ($httpurl, $binsha1s);
                  continue;
                }
                else
                {
                  $error[] = '' . 'Bad Tracker URL for scraping (Maybe trackerless torrent). ' . $announce;
                  continue;
                }

                continue;
              }
              else
              {
                $error[] = '' . '>Bad Tracker URL for scraping (Maybe trackerless torrent). ' . $announce;
                continue;
              }
            }
          }

          if ((!$skip_first AND isset ($array['announce'])))
          {
            $announce = strtolower ($array['announce']);
            $tmp = str_replace ('//announce', '//_temp_', $announce);
            if (substr ($announce, 0, 7) === 'http://')
            {
              if (substr_count ($tmp, '/announce') == 1)
              {
                $scrape = str_replace (array ('/announce', '//_temp_'), array ('/scrape', '//announce'), $tmp);
                $httpget = (preg_match ('#\\?passkey=#i', $scrape) ? '&' : '?') . 'info_hash=';
                $binsha1 = pack ('H*', $infohash);
                $binsha1s = addslashes ($binsha1);
                $fullurl = $scrape . $httpget . $binsha1;
                $httpurl = pathurlencode ($fullurl);
                scrape_connect ($httpurl, $binsha1s);
              }
              else
              {
                $error[] = '' . 'Bad Tracker URL for scraping (Maybe trackerless torrent). ' . $announce;
              }
            }
            else
            {
              $error[] = '' . '>Bad Tracker URL for scraping (Maybe trackerless torrent). ' . $announce;
            }
          }
        }
      }
    }
  }

  if (0 < count ($error))
  {
    write_log (implode ('
--------------------------------
', $error));
  }

  if (((0 < $e_seeders OR 0 < $e_leechers) OR 0 < $e_completed))
  {
    $visible = (($e_seeders == 0 AND $e_leechers == 0) ? '\'no\'' : '\'yes\'');
    sql_query ('' . 'UPDATE torrents SET seeders = ' . $e_seeders . ', leechers = ' . $e_leechers . ', times_completed = ' . $e_completed . ', visible = ' . $visible . ', ts_external = \'yes\', ts_external_url = ' . sqlesc ($httpurl) . ', ts_external_lastupdate = ' . time () . ' WHERE id = ' . sqlesc ($id));
    if (defined ('USE_AJAX'))
    {
      $seeders = $e_seeders;
      $leechers = $e_leechers;
    }
  }

?>
