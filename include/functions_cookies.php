<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_get_array_cookie ($name, $id)
  {
    if (!isset ($_COOKIE['tsf'][$name]))
    {
      return false;
    }

    if (isset ($_COOKIE['tsf'][$name]))
    {
      $cookie = unserialize (@stripslashes ($_COOKIE['tsf'][$name]));
    }

    if (isset ($cookie[$id]))
    {
      return $cookie[$id];
    }

    return 0;
  }

  function ts_set_array_cookie ($name, $id, $value)
  {
    if (isset ($_COOKIE['tsf']))
    {
      $cookie = $_COOKIE['tsf'];
      $newcookie = @unserialize ($cookie[$name]);
    }

    $newcookie[$id] = $value;
    $newcookie = addslashes (@serialize ($newcookie));
    my_setcookiee (('' . 'tsf[' . $name . ']'), $newcookie);
  }

  function my_setcookiee ($name, $value = '', $expires = '', $httponly = false)
  {
    if ($expires == 0 - 1)
    {
      $expires = 0;
    }
    else
    {
      if (($expires == '' OR $expires == null))
      {
        $expires = TIMENOW + 60 * 60 * 24 * 365;
      }
      else
      {
        $expires = TIMENOW + intval ($expires);
      }
    }

    $cookie = '' . 'Set-Cookie: ' . $name . '=' . urlencode ($value);
    if (0 < $expires)
    {
      $cookie .= '; expires=' . gmdate ('D, d-M-Y H:i:s \\G\\M\\T', $expires);
    }

    if ($httponly == true)
    {
      $cookie .= '; HttpOnly';
    }

    @header ($cookie, false);
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
