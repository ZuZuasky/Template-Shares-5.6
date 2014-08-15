<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class page_verify
  {
    function page_verify ()
    {
      if (session_id () == '')
      {
        @session_name ('TSSE_Session');
        @session_start ();
      }

    }

    function create ($task_name = 'Default')
    {
      global $CURUSER;
      $_SESSION['Task_Time'] = time ();
      $_SESSION['Task'] = securehash ('User_ID:' . $CURUSER['id'] . '::TName-' . $task_name . '::' . $_SESSION['Task_Time']);
      $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
    }

    function check ($task_name = 'Default')
    {
      global $CURUSER;
      if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])
      {
        define ('errorid', 4);
        include_once TSDIR . '/ts_error.php';
        exit ();
      }

      if ($_SESSION['Task'] != securehash ('User_ID:' . $CURUSER['id'] . '::TName-' . $task_name . '::' . $_SESSION['Task_Time']))
      {
        define ('errorid', 4);
        include_once TSDIR . '/ts_error.php';
        exit ();
      }

      $this->create ();
    }
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
