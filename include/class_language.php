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
      global $rootpath;
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

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face="verdana" size="2" color="darkred"><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
