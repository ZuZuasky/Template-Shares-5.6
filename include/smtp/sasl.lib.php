<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class sasl
  {
    function _hmac_md5 ($key, $data)
    {
      if (64 < strlen ($key))
      {
        $key = pack ('H32', md5 ($key));
      }

      if (strlen ($key) < 64)
      {
        $key = str_pad ($key, 64, chr (0));
      }

      $k_ipad = substr ($key, 0, 64) ^ str_repeat (chr (54), 64);
      $k_opad = substr ($key, 0, 64) ^ str_repeat (chr (92), 64);
      $inner = pack ('H32', md5 ($k_ipad . $data));
      $digest = md5 ($k_opad . $inner);
      return $digest;
    }

    function cram_md5 ($user, $pass, $challenge)
    {
      var_dump ($challenge);
      $chall = base64_decode ($challenge);
      var_dump ($chall);
      return base64_encode (sprintf ('%s %s', $user, $this->_hmac_md5 ($pass, $chall)));
    }

    function plain ($username, $password)
    {
      return base64_encode (sprintf ('%c%s%c%s', 0, $username, 0, $password));
    }

    function login ($input)
    {
      return base64_encode (sprintf ('%s', $input));
    }
  }

  require_once INC_PATH . '/smtp/net.const.php';
?>
