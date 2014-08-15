<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function deadtime ()
  {
    global $announce_interval;
    return TIMENOW - floor ($announce_interval * 1.30000000000000004440892);
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  $id = 0 + $_GET['id'];
  int_check ($id, true);
  $lang->load ('takeflush');
  require INC_PATH . '/readconfig_announce.php';
  if ((is_mod ($usergroups) OR $CURUSER['id'] == $id))
  {
    $deadtime = deadtime ();
    sql_query ('' . 'DELETE FROM peers WHERE last_action < FROM_UNIXTIME(' . $deadtime . ') AND userid=' . sqlesc ($id));
    if (mysql_affected_rows ())
    {
      stderr ($lang->takeflush['done'], $lang->takeflush['done2']);
      return 1;
    }

    stderr ($lang->global['error'], $lang->takeflush['noghost']);
    return 1;
  }

  print_no_permission ();
?>
