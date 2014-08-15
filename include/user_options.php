<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function user_options ($options, $field, $number = 0)
  {
    if ((!$options = strtoupper ($options) OR !$field = strtolower ($field)))
    {
      return false;
    }

    $array = array ('parked' => 'A1', 'invisible' => 'B1', 'commentpm' => 'C1', 'avatars' => 'D1', 'showoffensivetorrents' => 'E1', 'popup' => 'F1', 'leftmenu' => 'G1', 'signatures' => 'H1', 'privacy' => 'I' . $number, 'acceptpms' => 'K' . $number, 'gender' => 'L' . $number, 'visitormsg' => 'M' . $number, 'autodst' => 'N1', 'dst' => 'O1', 'quickmenu' => 'P1');
    return (preg_match ('#' . $array[$field] . '#is', $options) ? true : false);
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
