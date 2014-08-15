<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (((!defined ('TSF_FORUMS_TSSEv56') OR !defined ('IN_SCRIPT_TSSEv56')) OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if (!defined ('TSF_ROOT'))
  {
    define ('TSF_ROOT', dirname (@dirname (__FILE__)) . '/');
  }

  define ('TSF_VERSION', 'v1.5 by xam');
  gzip ();
  dbconn ();
  maxsysop ();
  if (($MEMBERSONLY == 'yes' OR strstr ($_SERVER['SCRIPT_NAME'], 'index.php') === false))
  {
    loggedinorreturn ();
    parked ();
  }

  $lang->load ('tsf_forums');
  require_once TSF_ROOT . 'include/functions.php';
?>
