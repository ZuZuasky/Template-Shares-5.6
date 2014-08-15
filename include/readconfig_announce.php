<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('ANNOUNCE');
  $announce_actions = (!empty ($ANNOUNCE['announce_actions']) ? $ANNOUNCE['announce_actions'] : 'no');
  $aggressivecheat = (!empty ($ANNOUNCE['aggressivecheat']) ? $ANNOUNCE['aggressivecheat'] : 'yes');
  $nc = (!empty ($ANNOUNCE['nc']) ? $ANNOUNCE['nc'] : 'yes');
  $announce_wait = (!empty ($ANNOUNCE['announce_wait']) ? $ANNOUNCE['announce_wait'] : 0);
  $announce_interval = (!empty ($ANNOUNCE['announce_interval']) ? $ANNOUNCE['announce_interval'] : '3600');
  $max_rate = (!empty ($ANNOUNCE['max_rate']) ? $ANNOUNCE['max_rate'] : 2097152);
  $bannedclientdetect = (!empty ($ANNOUNCE['bannedclientdetect']) ? $ANNOUNCE['bannedclientdetect'] : 'no');
  $allowed_clients = (!empty ($ANNOUNCE['allowed_clients']) ? $ANNOUNCE['allowed_clients'] : '-UT1610-,-AZ3034-,-UT1750-');
  $detectbrowsercheats = (!empty ($ANNOUNCE['detectbrowsercheats']) ? $ANNOUNCE['detectbrowsercheats'] : 'yes');
  $checkconnectable = (!empty ($ANNOUNCE['checkconnectable']) ? $ANNOUNCE['checkconnectable'] : 'no');
  $checkip = (!empty ($ANNOUNCE['checkip']) ? $ANNOUNCE['checkip'] : 'no');
  unset ($ANNOUNCE);
?>
