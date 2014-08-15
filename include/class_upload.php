<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class ts_upload
  {
    var $error = '';
    var $url = '';
    var $file_type = '';
    var $allowed_ext = array (0 => 'gif', 1 => 'jpg', 2 => 'png');
    var $valid_link = array (0 => 'http://www.imdb.com/title/');
    var $extension = '';
    var $return_type = 'stderr';
    function check_url ()
    {
      global $lang;
      if (empty ($this->url))
      {
        if ($this->return_type == 'stderr')
        {
          stderr ($lang->global['error'], $lang->upload['invalid_url_empty']);
        }
        else
        {
          exit ($lang->upload['invalid_url_empty']);
        }
      }

      if ($this->file_type === 'image')
      {
        $this->extension = get_extension ($this->url);
        if (!in_array ($this->extension, $this->allowed_ext, true))
        {
          if ($this->return_type == 'stderr')
          {
            stderr ($lang->global['error'], sprintf ($lang->upload['invalid_image'], implode (',', $this->allowed_ext)));
          }
          else
          {
            exit (sprintf ($lang->upload['invalid_image'], implode (',', $this->allowed_ext)));
          }
        }
      }
      else
      {
        if ($this->file_type === 'imdb')
        {
          if ((strstr ($this->url, 'imdb') AND !in_array (substr ($this->url, 0, 26), $this->valid_link, true)))
          {
            stderr ($lang->global['error'], $lang->upload['invalid_url_imdb']);
          }
        }
      }

      if (!preg_match ('#^((http|ftp)s?):\\/\\/#i', $this->url, $check))
      {
        if ($this->return_type == 'stderr')
        {
          stderr ($lang->global['error'], $lang->upload['invalid_url_link']);
        }
        else
        {
          exit ($lang->upload['invalid_url_link']);
        }
      }

      @ini_set ('user_agent', 'TS_SE via cURL/PHP');
      if ((function_exists ('curl_init') AND $ch = curl_init ()))
      {
        curl_setopt ($ch, CURLOPT_URL, $this->url);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_HEADER, false);
        curl_setopt ($ch, CURLOPT_USERAGENT, 'TS_SE via cURL/PHP');
        $contents = curl_exec ($ch);
        curl_close ($ch);
      }
      else
      {
        if (!ini_get ('allow_url_fopen') == 0)
        {
          if (!$handle = @fopen ($this->url, 'rb'))
          {
            if ($this->return_type == 'stderr')
            {
              stderr ($lang->global['error'], $lang->upload['curl_error']);
            }
            else
            {
              exit ($lang->upload['curl_error']);
            }
          }

          while (!feof ($handle))
          {
            $contents .= fread ($handle, 8192);
          }

          fclose ($handle);
        }
        else
        {
          stderr ($lang->global['error'], $lang->upload['curl_error']);
        }
      }

      if (empty ($contents))
      {
        if ($this->return_type == 'stderr')
        {
          stderr ($lang->global['error'], $lang->upload['invalid_url']);
          return null;
        }

        exit ($lang->upload['invalid_url']);
      }

    }
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('CU_VERSION', '0.4 by xam');
?>
