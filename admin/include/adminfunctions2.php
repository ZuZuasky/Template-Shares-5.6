<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_user_class_name ($class = '')
  {
    global $cache;
    if ($class == 'all')
    {
      return 'ALL Usergroups';
    }

    require TSDIR . '/' . $cache . '/usergroups.php';
    foreach ($usergroupscache as $arr)
    {
      if ($arr['gid'] == $class)
      {
        return $arr['title'];
      }
    }

    return 'ALL Usergroups';
  }

  define ('_AF_2', true);
  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  require_once $thispath . 'include/adminfunctions3.php';
  if (!defined ('_AF__3'))
  {
    exit ('The authentication has been blocked because of invalid file detected!');
  }

?>
