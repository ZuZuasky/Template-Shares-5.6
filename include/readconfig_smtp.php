<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('SMTP');
  $smtptype = (!empty ($SMTP['smtptype']) ? $SMTP['smtptype'] : 'default');
  $smtp_host = (!empty ($SMTP['smtp_host']) ? $SMTP['smtp_host'] : 'localhost');
  $smtp_port = (!empty ($SMTP['smtp_port']) ? $SMTP['smtp_port'] : '25');
  if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
  {
    $smtp_from = (!empty ($SMTP['smtp_from']) ? $SMTP['smtp_from'] : '');
  }

  $smtpaddress = (!empty ($SMTP['smtpaddress']) ? $SMTP['smtpaddress'] : '');
  $smtpport = (!empty ($SMTP['smtpport']) ? $SMTP['smtpport'] : '');
  $accountname = (!empty ($SMTP['accountname']) ? $SMTP['accountname'] : '');
  $accountpassword = (!empty ($SMTP['accountpassword']) ? $SMTP['accountpassword'] : '');
?>
