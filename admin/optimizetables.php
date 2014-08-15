<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('OT_VERSION', 'v.0.6 by xam');
  $rootpath = './../';
  define ('IN_ANNOUNCE', true);
  include INC_PATH . '/config_announce.php';
  set_time_limit (100);
  if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
  {
    $eol = '
';
  }
  else
  {
    if (strtoupper (substr (PHP_OS, 0, 3) == 'MAC'))
    {
      $eol = '
';
    }
    else
    {
      $eol = '
';
    }
  }

  $type = (isset ($_GET['type']) ? trim ($_GET['type']) : 'optimize');
  $dummy_db = $mysql_db;
  $db_link = mysql_connect ($mysql_host, $mysql_user, $mysql_pass);
  $dbs[] = $mysql_db;
  foreach ($dbs as $db_name)
  {
    echo '' . '<p>Database : <b>' . $db_name . '</b></p>' . $eol;
    if (!($res = mysql_db_query ($dummy_db, 'SHOW TABLE STATUS FROM `' . $db_name . '`', $db_link)))
    {
      exit ('Query : ' . mysql_error ());
      ;
    }

    $to_optimize = array ();
    $to_repair = array ();
    while ($rec = mysql_fetch_array ($res))
    {
      if ($type == 'optimize')
      {
        if ((0 < $rec['Data_free'] AND $rec['Engine'] == 'MyISAM'))
        {
          $to_optimize[] = $rec['Name'];
          echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;Table <b>' . $rec['Name'] . '</b> needs optimization [<font color="red">optimized</font>]' . $eol;
          continue;
        }

        continue;
      }
      else
      {
        if ($type == 'repair')
        {
          $to_repair[] = $rec['Name'];
          echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;Table <b>' . $rec['Name'] . '</b> needs optimization [<font color="red">repaired</font>]' . $eol;
          continue;
        }

        continue;
      }
    }

    if (0 < count ($to_optimize))
    {
      foreach ($to_optimize as $tbl)
      {
        mysql_db_query ($db_name, 'OPTIMIZE TABLE `' . $tbl . '`', $db_link);
      }
    }

    if (0 < count ($to_repair))
    {
      foreach ($to_repair as $tbl)
      {
        mysql_db_query ($db_name, 'REPAIR TABLE `' . $tbl . '`', $db_link);
      }

      continue;
    }
  }

  if ($type == 'optimize')
  {
    echo '<p><font color="red">All tables has been optimized!</font></p>' . $eol;
  }
  else
  {
    if ($type == 'repair')
    {
      echo '<p><font color="red">All tables has been repaired!</font></p>' . $eol;
    }
  }

  echo '</table>' . $eol;
?>
