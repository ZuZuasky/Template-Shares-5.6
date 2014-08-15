<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class trackerlanguage
  {
    var $path = null;
    var $language = null;
    function set_path ($path)
    {
      $this->path = $path;
    }

    function set_language ($language = 'english')
    {
      $language = str_replace (array ('/', '\\', '..'), '', trim ($language));
      if ($language == '')
      {
        $language = 'english';
      }

      $this->language = $language;
    }

    function load ($section)
    {
      $lfile = $this->path . '/' . $this->language . '/' . $section . '.lang.php';
      if (file_exists ($lfile))
      {
        require_once $lfile;
      }
      else
      {
        define ('errorid', 3);
        include_once TSDIR . '/ts_error.php';
        exit ();
      }

      if ((isset ($language) AND is_array ($language)))
      {
        foreach ($language as $key => $val)
        {
          if ((!isset ($this->$key) OR $this->$key != $val))
          {
            $val = preg_replace ('#\\{([0-9]+)\\}#', '%$1\\$s', $val);
            $this->$key = $val;
            continue;
          }
        }
      }

    }
  }

  function savelog ($Text)
  {
    mysql_query ('INSERT INTO sitelog VALUES (NULL, NOW(), ' . sqlesc ($Text) . ')');
  }

  function tsrowcount ($C, $T, $E = '')
  {
    $Q = mysql_query ('' . 'SELECT COUNT(' . $C . ') FROM ' . $T . ($E ? '' . ' WHERE ' . $E : ''));
    $R = mysql_fetch_row ($Q);
    return $R[0];
  }

  function mksize ($bytes)
  {
    if ($bytes < 1000 * 1024)
    {
      return number_format ($bytes / 1024, 2) . ' KB';
    }

    if ($bytes < 1000 * 1048576)
    {
      return number_format ($bytes / 1048576, 2) . ' MB';
    }

    if ($bytes < 1000 * 1073741824)
    {
      return number_format ($bytes / 1073741824, 2) . ' GB';
    }

    return number_format ($bytes / 1099511627776, 2) . ' TB';
  }

  function sqlesc ($value)
  {
    if (get_magic_quotes_gpc ())
    {
      $value = stripslashes ($value);
    }

    if (!is_numeric ($value))
    {
      $value = '\'' . mysql_real_escape_string ($value) . '\'';
    }

    return $value;
  }

  function readconfig ($ConfigName)
  {
    if (is_array ($ConfigName))
    {
      foreach ($ConfigName as $CFGName)
      {
        readconfig ($CFGName);
      }

      return null;
    }

    clearstatcache ();
    $Array = unserialize (file_get_contents (CONFIG_DIR . $ConfigName));
    foreach ($Array as $Name => $Value)
    {
      $GLOBALS[$Name] = $Value;
    }

    unset ($Array);
  }

  function logcronaction ($filename, $querycount, $executetime)
  {
    mysql_query ('REPLACE INTO ts_cron_log (filename, querycount, executetime, runtime) VALUES (\'' . $filename . '\', \'' . $querycount . '\', \'' . $executetime . '\', \'' . TIMENOW . '\')');
  }

  function databaseconnect ()
  {
    clearstatcache ();
    $Array = unserialize (file_get_contents (CONFIG_DIR . 'DATABASE'));
    extract ($Array, EXTR_PREFIX_SAME, 'wddx');
    unset ($Array);
    if (mysql_connect ($mysql_host, $mysql_user, $mysql_pass))
    {
      if (mysql_select_db ($mysql_db))
      {
        return true;
      }
    }

    return false;
  }

  function deadtime ()
  {
    clearstatcache ();
    $Array = unserialize (file_get_contents (CONFIG_DIR . 'ANNOUNCE'));
    extract ($Array, EXTR_PREFIX_SAME, 'wddx');
    unset ($Array);
    return TIMENOW - floor ($announce_interval * 1.30000000000000004440892);
  }

  if (!defined ('IN_CRON'))
  {
    exit ();
  }

?>
