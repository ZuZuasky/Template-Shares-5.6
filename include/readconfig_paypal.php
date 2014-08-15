<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('PAYPAL');
  $pmail = (!empty ($PAYPAL['pmail']) ? $PAYPAL['pmail'] : '');
  $pcc = (!empty ($PAYPAL['pcc']) ? $PAYPAL['pcc'] : 'USD');
  $tn = (!empty ($PAYPAL['tn']) ? $PAYPAL['tn'] : '100');
  $donationamounts = (!empty ($PAYPAL['donationamounts']) ? $PAYPAL['donationamounts'] : '5:10:15:20:25:30:35:40:45:50:75:100:150:250:500');
  $paypal_auto_mode = (!empty ($PAYPAL['paypal_auto_mode']) ? $PAYPAL['paypal_auto_mode'] : 'no');
  $paypal_auth_token = (!empty ($PAYPAL['paypal_auth_token']) ? $PAYPAL['paypal_auth_token'] : '');
  $moneybookersemail = (!empty ($PAYPAL['moneybookersemail']) ? $PAYPAL['moneybookersemail'] : '');
  $wire_form = (!empty ($PAYPAL['wire_form']) ? $PAYPAL['wire_form'] : '');
  $showdonorlist = (!empty ($PAYPAL['showdonorlist']) ? $PAYPAL['showdonorlist'] : '20');
  $paypal_demo_mode = (!empty ($PAYPAL['paypal_demo_mode']) ? $PAYPAL['paypal_demo_mode'] : 'no');
?>
