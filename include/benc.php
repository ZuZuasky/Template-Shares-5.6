<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function benc ($obj)
  {
    if (((!is_array ($obj) OR !isset ($obj['type'])) OR !isset ($obj['value'])))
    {
      return null;
    }

    $c = $obj['value'];
    switch ($obj['type'])
    {
      case 'string':
      {
        benc_str ($c);
      }
    }

    return ;
  }

  function benc_str ($s)
  {
    return strlen ($s) . ':' . $s;
  }

  function benc_int ($i)
  {
    return 'i' . $i . 'e';
  }

  function benc_list ($a)
  {
    $s = 'l';
    foreach ($a as $e)
    {
      $s .= benc ($e);
    }

    $s .= 'e';
    return $s;
  }

  function benc_dict ($d)
  {
    $s = 'd';
    $keys = array_keys ($d);
    sort ($keys);
    foreach ($keys as $k)
    {
      $v = $d[$k];
      $s .= benc_str ($k);
      $s .= benc ($v);
    }

    $s .= 'e';
    return $s;
  }

  function bdec_file ($f, $ms)
  {
    $fp = fopen ($f, 'rb');
    if (!$fp)
    {
      return null;
    }

    $e = fread ($fp, $ms);
    fclose ($fp);
    return bdec ($e);
  }

  function bdec ($s)
  {
    if (preg_match ('/^(\\d+):/', $s, $m))
    {
      $l = $m[1];
      $pl = strlen ($l) + 1;
      $v = substr ($s, $pl, $l);
      $ss = substr ($s, 0, $pl + $l);
      if (strlen ($v) != $l)
      {
        return null;
      }

      return array ('type' => 'string', 'value' => $v, 'strlen' => strlen ($ss), 'string' => $ss);
    }

    if (preg_match ('/^i(\\d+)e/', $s, $m))
    {
      $v = $m[1];
      $ss = 'i' . $v . 'e';
      if ($v === '-0')
      {
        return null;
      }

      if (($v[0] == '0' AND strlen ($v) != 1))
      {
        return null;
      }

      return array ('type' => 'integer', 'value' => $v, 'strlen' => strlen ($ss), 'string' => $ss);
    }

    switch ($s[0])
    {
      case 'l':
      {
        bdec_list ($s);
      }
    }

    return ;
  }

  function bdec_list ($s)
  {
    if ($s[0] != 'l')
    {
      return null;
    }

    $sl = strlen ($s);
    $i = 1;
    $v = array ();
    $ss = 'l';
    while (true)
    {
      if ($sl <= $i)
      {
        return null;
      }

      if ($s[$i] == 'e')
      {
        break;
      }

      $ret = bdec (substr ($s, $i));
      if ((!isset ($ret) OR !is_array ($ret)))
      {
        return null;
      }

      $v[] = $ret;
      $i += $ret['strlen'];
      $ss .= $ret['string'];
    }

    $ss .= 'e';
    return array ('type' => 'list', 'value' => $v, 'strlen' => strlen ($ss), 'string' => $ss);
  }

  function bdec_dict ($s)
  {
    if ($s[0] != 'd')
    {
      return null;
    }

    $sl = strlen ($s);
    $i = 1;
    $v = array ();
    $ss = 'd';
    while (true)
    {
      if ($sl <= $i)
      {
        return null;
      }

      if ($s[$i] == 'e')
      {
        break;
      }

      $ret = bdec (substr ($s, $i));
      if (((!isset ($ret) OR !is_array ($ret)) OR $ret['type'] != 'string'))
      {
        return null;
      }

      $k = $ret['value'];
      $i += $ret['strlen'];
      $ss .= $ret['string'];
      if ($sl <= $i)
      {
        return null;
      }

      $ret = bdec (substr ($s, $i));
      if ((!isset ($ret) OR !is_array ($ret)))
      {
        return null;
      }

      $v[$k] = $ret;
      $i += $ret['strlen'];
      $ss .= $ret['string'];
    }

    $ss .= 'e';
    return array ('type' => 'dictionary', 'value' => $v, 'strlen' => strlen ($ss), 'string' => $ss);
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  @ini_set ('log_errors', '1');
?>
