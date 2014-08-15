<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_file_icon ($filename, $path = 'images/attach/')
  {
    $ext = get_extension ($filename);
    return '<img src="' . $path . (file_exists ('' . $path . $ext . '.gif') ? $ext : 'attach') . '.gif" border="0" class="inlineimg" alt="' . $ext . '" title="' . $ext . '">&nbsp;';
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
