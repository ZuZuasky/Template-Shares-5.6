<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('TSF_FORUMS_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $rootpath = './../';
  define ('TSF_FORUMS_GLOBAL_TSSEv56', true);
  require_once $rootpath . 'global.php';
  include_once INC_PATH . '/readconfig_forumcp.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  require_once './include/init.php';
  if ((@function_exists ('mb_internal_encoding') AND !empty ($charset)))
  {
    @mb_internal_encoding ($charset);
  }

  $navbits = array ();
  $navbits[0]['name'] = $f_forumname;
  $navbits[0]['url'] = '' . $BASEURL . '/tsf_forums/index.php';
  $permissions = forum_permissions ();
  if ((($usergroups['isforummod'] == 'yes' OR $usergroups['cansettingspanel'] == 'yes') OR $usergroups['issupermod'] == 'yes'))
  {
    $moderator = true;
  }
  else
  {
    $moderator = false;
  }

  $action = (isset ($_GET['action']) ? htmlspecialchars_uni ($_GET['action']) : (isset ($_POST['action']) ? htmlspecialchars_uni ($_POST['action']) : ''));
  $forumtokencode = md5 ($CURUSER['username'] . $securehash . $CURUSER['id']);
  $posthash = (isset ($_POST['hash']) ? htmlspecialchars_uni ($_POST['hash']) : (isset ($_GET['hash']) ? htmlspecialchars_uni ($_GET['hash']) : ''));
  $pagenumber = (isset ($_GET['page']) ? intval ($_GET['page']) : (isset ($_POST['page']) ? intval ($_POST['page']) : ''));
  $perpage = $f_threadsperpage;
  unset ($warningmessage);
  if (($f_forum_online == 'no' AND $usergroups['canaccessoffline'] != 'yes'))
  {
    stderr ($lang->global['error'], $f_offlinemsg);
    exit ();
    return 1;
  }

  if ($f_forum_online == 'no')
  {
    $warningmessage = show_notice ($lang->tsf_forums['warningmsg'], true);
  }

?>
