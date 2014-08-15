<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class smtp
  {
    var $_version = '0.0.2.2';
    var $_debug = false;
    var $_connection = null;
    var $_hdrs = array ();
    var $_body = '';
    var $_mime = 'text/plain';
    var $_charset = 'UTF-8';
    function smtp ()
    {
      $this->_add_hdr ('X-Mailer', sprintf ('LAGNUT-SMTP/%s', $this->_version));
      $this->_add_hdr ('User-Agent', sprintf ('LAGNUT-SMTP/%s', $this->_version));
      $this->_add_hdr ('MIME-Version', '1.0');
    }

    function debug ($debug)
    {
      $this->_debug = (bool)$debug;
    }

    function _clean (&$input)
    {
      if (!is_string ($input))
      {
        return false;
      }

      $input = urldecode ($input);
      $input = str_replace ('
', '', str_replace ('
', '', $input));
    }

    function _cmd ($cmd, $data = false)
    {
      $this->_clean ($cmd);
      $this->_clean ($data);
      if ($this->_is_closed ())
      {
        return false;
      }

      if (!$data)
      {
        $command = sprintf ('%s
', $cmd);
      }
      else
      {
        $command = sprintf ('%s: %s
', $cmd, $data);
      }

      fwrite ($this->_connection, $command);
      $resp = $this->_read ();
      if ($this->_debug)
      {
        printf ($command);
        printf ($resp);
      }

      if ($this->_is_closed ($resp))
      {
        return false;
      }

      return $resp;
    }

    function _add_hdr ($key, $data)
    {
      $this->_clean ($key);
      $this->_clean ($data);
      $this->_hdrs[$key] = sprintf ('%s: %s
', $key, $data);
    }

    function _read ()
    {
      if ($this->_is_closed ())
      {
        return false;
      }

      $o = '';
      do
      {
        $str = @fgets ($this->_connection, 515);
        if (!$str)
        {
          break;
        }

        $o .= $str;
        if (substr ($str, 3, 1) == ' ')
        {
          break;
        }
      }while (!(true));

      return $o;
    }

    function _is_closed ($response = false)
    {
      if (!$this->_connection)
      {
        return true;
      }

      if ((isset ($response[0]) AND ($response[0] == 4 OR $response[0] == 5)))
      {
        $this->close ();
        return true;
      }

      return false;
    }

    function open ($server, $port = 25)
    {
      $this->_connection = fsockopen ($server, $port, $e, $er, 8);
      if ($this->_is_closed ())
      {
        return false;
      }

      $init = $this->_read ();
      if ($this->_debug)
      {
        printf ($init);
      }

      if ($this->_is_closed ($init))
      {
        return false;
      }

      $lhost = (isset ($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1');
      if (strpos ($init, 'ESMTP') === false)
      {
        $this->_cmd ('HELO ' . gethostbyaddr ($lhost));
        return null;
      }

      $this->_cmd ('EHLO ' . gethostbyaddr ($lhost));
    }

    function start_tls ()
    {
      if (!function_exists ('stream_socket_enable_crypto'))
      {
        trigger_error ('TLS is not supported', E_USER_ERROR);
        return false;
      }

      $this->_cmd ('STARTTLS');
      stream_socket_enable_crypto ($this->_connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
    }

    function auth ($username, $password, $type = LOGIN)
    {
      include_once INC_PATH . '/smtp/sasl.lib.php';
      $sasl = &new sasl ($sasl, $username, $password);
      switch ($type)
      {
        case PLAIN:
        {
          $this->_cmd ('AUTH PLAIN');
          $this->_cmd ($sasl->plain ($username, $password));
          break;
        }

        case LOGIN:
        {
          $this->_cmd ('AUTH LOGIN');
          $this->_cmd ($sasl->login ($username));
          $this->_cmd ($sasl->login ($password));
          break;
        }

        case CRAM_MD5:
        {
          $resp = explode (' ', $this->_cmd ('AUTH CRAM-MD5'));
          $this->_cmd ($sasl->cram_md5 ($username, $password, trim ($resp[1])));
        }
      }

    }

    function close ()
    {
      if ($this->_is_closed ())
      {
        return false;
      }

      $this->_cmd ('RSET');
      $this->_cmd ('QUIT');
      fclose ($this->_connection);
      $this->_connection = null;
    }

    function from ($email, $name = '')
    {
      $from = (!empty ($name) ? sprintf ('%s <%s>', $name, $email) : $email);
      $this->_cmd ('MAIL FROM', sprintf ('<%s>', $email));
      $this->_add_hdr ('FROM', $from);
      $this->_add_hdr ('Return-path', $email);
    }

    function reply_to ($email, $name = '')
    {
      $to = (!empty ($name) ? sprintf ('%s <%s>', $name, $email) : $email);
      $this->_add_hdr ('REPLY-TO', $to);
    }

    function to ($email, $name = '')
    {
      $to = (!empty ($name) ? sprintf ('%s <%s>', $name, $email) : $email);
      $this->_cmd ('RCPT TO', sprintf ('<%s>', $email));
      $this->_add_hdr ('TO', $to);
    }

    function mime_charset ($mime, $charset)
    {
      $this->_charset = $charset;
      $this->_mime = $mime;
      $this->_add_hdr ('Content-type', sprintf ('%s; charset=%s', $mime, $charset));
    }

    function subject ($subject)
    {
      $this->_clean ($subject);
      $this->_add_hdr ('SUBJECT', $this->encode_hdrs ($subject));
    }

    function body ($body)
    {
      $body = preg_replace ('/([
|
])\\.([
|
])/', '' . '$1..$2', $body);
      $this->_body = sprintf ('
%s', $body);
    }

    function send ()
    {
      $resp = $this->_cmd ('DATA');
      if ($this->_is_closed ($resp))
      {
        $this->close ();
        return false;
      }

      foreach ($this->_hdrs as $header)
      {
        fwrite ($this->_connection, $header);
        if ($this->_debug)
        {
          printf ($header);
          continue;
        }
      }

      fwrite ($this->_connection, $this->_body);
      fwrite ($this->_connection, '
.
');
      $resp = trim ($this->_read ());
      if ($this->_debug)
      {
        printf ('%s
', $this->_body);
        printf ('
.
');
        printf ('%s', $resp);
      }

      if ((int)$resp[0] != 2)
      {
        return false;
      }

      return true;
    }

    function encode_hdrs ($input)
    {
      $replacement = preg_replace ('/([\\x80-\\xFF])/e', '"=" . strtoupper(dechex(ord("\\1")))', $input);
      $input = str_replace ($input, sprintf ('=?%s?Q?%s?=', $this->_charset, $replacement), $input);
      return $input;
    }
  }

  require_once INC_PATH . '/smtp/net.const.php';
?>
