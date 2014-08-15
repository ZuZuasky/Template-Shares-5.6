<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class bencode
  {
    function makesorted ($array)
    {
      $i = 0;
      if (empty ($array))
      {
        return $array;
      }

      foreach ($array as $key => $value)
      {
        $keys[$i++] = stripslashes ($key);
      }

      sort ($keys);
      $i = 0;
      while (isset ($keys[$i]))
      {
        $return[addslashes ($keys[$i])] = $array[addslashes ($keys[$i])];
        ++$i;
      }

      return $return;
    }

    function encodeentry ($entry, &$fd, $unstrip = false)
    {
      if (is_bool ($entry))
      {
        $fd .= 'de';
        return null;
      }

      if ((is_int ($entry) OR is_float ($entry)))
      {
        $fd .= 'i' . $entry . 'e';
        return null;
      }

      if ($unstrip)
      {
        $myentry = stripslashes ($entry);
      }
      else
      {
        $myentry = $entry;
      }

      $length = strlen ($myentry);
      $fd .= $length . ':' . $myentry;
    }

    function encodelist ($array, &$fd)
    {
      $fd .= 'l';
      if (empty ($array))
      {
        $fd .= 'e';
        return null;
      }

      $i = 0;
      while (isset ($array[$i]))
      {
        $this->decideEncode ($array[$i], $fd);
        ++$i;
      }

      $fd .= 'e';
    }

    function decideencode ($unknown, &$fd)
    {
      if (is_array ($unknown))
      {
        if ((isset ($unknown[0]) OR empty ($unknown)))
        {
          return $this->encodeList ($unknown, $fd);
        }

        return $this->encodeDict ($unknown, $fd);
      }

      $this->encodeEntry ($unknown, $fd);
    }

    function encodedict ($array, &$fd)
    {
      $fd .= 'd';
      if (is_bool ($array))
      {
        $fd .= 'e';
        return null;
      }

      $newarray = $this->makeSorted ($array);
      foreach ($newarray as $left => $right)
      {
        $this->encodeEntry ($left, $fd, true);
        $this->decideEncode ($right, $fd);
      }

      $fd .= 'e';
    }
  }

  function bencode ($array)
  {
    $string = '';
    $encoder = new BEncode ();
    $encoder->decideEncode ($array, $string);
    return $string;
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
