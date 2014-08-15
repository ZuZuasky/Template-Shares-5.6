<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  $rootpath = './../';
  require $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('shoutcast');
  require INC_PATH . '/readconfig_shoutcast.php';
  if ($s_allowedusergroups = explode (',', $s_allowedusergroups))
  {
    if (!in_array ($CURUSER['usergroup'], $s_allowedusergroups))
    {
      print_no_permission ();
    }
  }

  ($query = sql_query ('SELECT uid FROM ts_shoutcastdj WHERE active = \'1\' AND uid = \'' . $CURUSER['id'] . '\'') OR sqlerr (__FILE__, 37));
  if (mysql_num_rows ($query) == 0)
  {
    print_no_permission (true);
  }

  stdhead ($lang->shoutcast['faq']);
  echo show_notice ($lang->shoutcast['dj_faq'], false, $lang->shoutcast['faq']);
  stdfoot ();
?>
