<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('TSF_FORUMS_TSSEv56', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $tid = (isset ($_POST['tid']) ? intval ($_POST['tid']) : (isset ($_GET['tid']) ? intval ($_GET['tid']) : 0));
  $do = (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : ''));
  $userid = intval ($CURUSER['id']);
  if (!is_valid_id ($tid))
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $query = sql_query ('SELECT t.tid, f.pid as parent FROM ' . TSF_PREFIX . 'threads t LEFT JOIN ' . TSF_PREFIX . 'forums f ON (t.fid=f.fid) WHERE t.tid=' . sqlesc ($tid));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalid_tid']);
    exit ();
  }

  $thread = mysql_fetch_assoc ($query);
  if ((!$moderator AND ($permissions[$thread['parent']]['canview'] == 'no' OR $permissions[$thread['parent']]['canviewthreads'] == 'no')))
  {
    print_no_permission (true);
    exit ();
  }

  if ($do == 'addsubscription')
  {
    $query = sql_query ('SELECT userid FROM ' . TSF_PREFIX . 'subscribe WHERE tid = ' . sqlesc ($tid) . ' AND userid = ' . sqlesc ($userid));
    if (mysql_num_rows ($query) != 0)
    {
      redirect ('tsf_forums/showthread.php?tid=' . $tid, $lang->tsf_forums['dsubs']);
      exit ();
    }

    sql_query ('INSERT INTO ' . TSF_PREFIX . 'subscribe (tid,userid) VALUES (' . sqlesc ($tid) . ',' . sqlesc ($userid) . ')');
    redirect ('tsf_forums/showthread.php?tid=' . $tid, $lang->tsf_forums['dsubs']);
    exit ();
    return 1;
  }

  if ($do == 'removesubscription')
  {
    sql_query ('DELETE FROM ' . TSF_PREFIX . 'subscribe WHERE userid = ' . sqlesc ($userid) . ' AND tid = ' . sqlesc ($tid));
    redirect ('tsf_forums/showthread.php?tid=' . $tid, $lang->tsf_forums['rsubs']);
    exit ();
    return 1;
  }

  print_no_permission ();
  exit ();
?>
