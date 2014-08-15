<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  $mechs = array ('LOGIN', 'PLAIN', 'CRAM_MD5');
  foreach ($mechs as $mech)
  {
    if (!defined ($mech))
    {
      define ($mech, $mech);
      continue;
    }
    else
    {
      if (constant ($mech) != $mech)
      {
        trigger_error (sprintf ('Constant %s already defined, can\'t proceed', $mech), E_USER_ERROR);
        continue;
      }

      continue;
    }
  }

?>
