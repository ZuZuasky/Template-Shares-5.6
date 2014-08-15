<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('SIGNUP');
  $_d_usergroup = (!empty ($SIGNUP['_d_usergroup']) ? $SIGNUP['_d_usergroup'] : UC_USER);
  $r_verification = (!empty ($SIGNUP['r_verification']) ? $SIGNUP['r_verification'] : 'yes');
  $r_gender = (!empty ($SIGNUP['r_gender']) ? $SIGNUP['r_gender'] : 'yes');
  $r_bday = (!empty ($SIGNUP['r_bday']) ? $SIGNUP['r_bday'] : 'yes');
  $r_timezone = (!empty ($SIGNUP['r_timezone']) ? $SIGNUP['r_timezone'] : 'yes');
  $r_referrer = (!empty ($SIGNUP['r_referrer']) ? $SIGNUP['r_referrer'] : 'no');
  $r_country = (!empty ($SIGNUP['r_country']) ? $SIGNUP['r_country'] : 'yes');
  $r_secretquestion = (!empty ($SIGNUP['r_secretquestion']) ? $SIGNUP['r_secretquestion'] : 'yes');
  $maxusers = (!empty ($SIGNUP['maxusers']) ? $SIGNUP['maxusers'] : '5000');
  $invitesystem = (!empty ($SIGNUP['invitesystem']) ? $SIGNUP['invitesystem'] : 'off');
  $registration = (!empty ($SIGNUP['registration']) ? $SIGNUP['registration'] : 'off');
  $verification = (!empty ($SIGNUP['verification']) ? $SIGNUP['verification'] : 'email');
  $invite_count = (!empty ($SIGNUP['invite_count']) ? $SIGNUP['invite_count'] : '0');
  $autogigsignup = (!empty ($SIGNUP['autogigsignup']) ? $SIGNUP['autogigsignup'] : '0');
  $autosbsignup = (!empty ($SIGNUP['autosbsignup']) ? $SIGNUP['autosbsignup'] : '0');
  $maxip = (!empty ($SIGNUP['maxip']) ? $SIGNUP['maxip'] : '1');
  $badcountries = (!empty ($SIGNUP['badcountries']) ? $SIGNUP['badcountries'] : '');
  $illegalusernames = (!empty ($SIGNUP['illegalusernames']) ? $SIGNUP['illegalusernames'] : 'xam templateshares');
  $pd = (!empty ($SIGNUP['pd']) ? $SIGNUP['pd'] : 'yes');
  unset ($SIGNUP);
?>
