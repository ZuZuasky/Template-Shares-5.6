<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('PJIRC');
  $pjirchost = (!empty ($PJIRC['pjirchost']) ? $PJIRC['pjirchost'] : 'irc.p2p-irc.net');
  $pjircchannel = (!empty ($PJIRC['pjircchannel']) ? $PJIRC['pjircchannel'] : '#');
  $ircbot = (!empty ($PJIRC['ircbot']) ? $PJIRC['ircbot'] : 'no');
  $botip = (!empty ($PJIRC['botip']) ? $PJIRC['botip'] : '');
  $botport = (!empty ($PJIRC['botport']) ? $PJIRC['botport'] : '');
  unset ($PJIRC);
?>
