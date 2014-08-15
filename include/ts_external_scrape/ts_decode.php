<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class bdecode
  {
    function numberdecode ($wholefile, $start)
    {
      $ret[0] = 0;
      $offset = $start;
      $negative = false;
      if ($wholefile[$offset] == '-')
      {
        $negative = true;
        ++$offset;
      }

      if ($wholefile[$offset] == '0')
      {
        ++$offset;
        if ($negative)
        {
          return array (false);
        }

        if (($wholefile[$offset] == ':' OR $wholefile[$offset] == 'e'))
        {
          ++$offset;
          $ret[0] = 0;
          $ret[1] = $offset;
          return $ret;
        }

        return array (false);
      }

      while (true)
      {
        if (('0' <= $wholefile[$offset] AND $wholefile[$offset] <= '9'))
        {
          $ret[0] *= 10;
          $ret[0] += ord ($wholefile[$offset]) - ord ('0');
          ++$offset;
          continue;
        }
        else
        {
          if (($wholefile[$offset] == 'e' OR $wholefile[$offset] == ':'))
          {
            $ret[1] = $offset + 1;
            if ($negative)
            {
              if ($ret[0] == 0)
              {
                return array (false);
              }

              $ret[0] = 0 - $ret[0];
            }

            return $ret;
          }

          return array (false);
        }
      }

    }

    function decodeentry ($wholefile, $offset = 0)
    {
      if ($wholefile[$offset] == 'd')
      {
        return $this->decodeDict ($wholefile, $offset);
      }

      if ($wholefile[$offset] == 'l')
      {
        return $this->decodelist ($wholefile, $offset);
      }

      if ($wholefile[$offset] == 'i')
      {
        ++$offset;
        return $this->numberdecode ($wholefile, $offset);
      }

      $info = $this->numberdecode ($wholefile, $offset);
      if ($info[0] === false)
      {
        return array (false);
      }

      $ret[0] = substr ($wholefile, $info[1], $info[0]);
      $ret[1] = $info[1] + strlen ($ret[0]);
      return $ret;
    }

    function decodelist ($wholefile, $start)
    {
      $offset = $start + 1;
      $i = 0;
      if ($wholefile[$start] != 'l')
      {
        return array (false);
      }

      $ret = array ();
      while (true)
      {
        if ($wholefile[$offset] == 'e')
        {
          break;
        }

        $value = $this->decodeEntry ($wholefile, $offset);
        if ($value[0] === false)
        {
          return array (false);
        }

        $ret[$i] = $value[0];
        $offset = $value[1];
        ++$i;
      }

      $final[0] = $ret;
      $final[1] = $offset + 1;
      return $final;
    }

    function decodedict ($wholefile, $start = 0)
    {
      $offset = $start;
      if ($wholefile[$offset] == 'l')
      {
        return $this->decodeList ($wholefile, $start);
      }

      if ($wholefile[$offset] != 'd')
      {
        return false;
      }

      $ret = array ();
      ++$offset;
      while (true)
      {
        if ($wholefile[$offset] == 'e')
        {
          ++$offset;
          break;
        }

        $left = $this->decodeEntry ($wholefile, $offset);
        if (!$left[0])
        {
          return false;
        }

        $offset = $left[1];
        if ($wholefile[$offset] == 'd')
        {
          $value = $this->decodedict ($wholefile, $offset);
          if (!$value[0])
          {
            return false;
          }

          $ret[addslashes ($left[0])] = $value[0];
          $offset = $value[1];
          continue;
        }

        if ($wholefile[$offset] == 'l')
        {
          $value = $this->decodeList ($wholefile, $offset);
          if ((!$value[0] AND is_bool ($value[0])))
          {
            return false;
          }

          $ret[addslashes ($left[0])] = $value[0];
          $offset = $value[1];
          continue;
        }
        else
        {
          $value = $this->decodeEntry ($wholefile, $offset);
          if ($value[0] === false)
          {
            return false;
          }

          $ret[addslashes ($left[0])] = $value[0];
          $offset = $value[1];
          continue;
        }
      }

      if (empty ($ret))
      {
        $final[0] = true;
      }
      else
      {
        $final[0] = $ret;
      }

      $final[1] = $offset;
      return $final;
    }
  }

  function bdecode ($wholefile)
  {
    $decoder = new BDecode ();
    $return = $decoder->decodeEntry ($wholefile);
    return $return[0];
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
