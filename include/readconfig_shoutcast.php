<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('SHOUTCAST');
  $s_servername = (!empty ($SHOUTCAST['s_servername']) ? $SHOUTCAST['s_servername'] : $SITENAME);
  $s_serverip = (!empty ($SHOUTCAST['s_serverip']) ? $SHOUTCAST['s_serverip'] : $BASEURL);
  $s_serverport = (!empty ($SHOUTCAST['s_serverport']) ? $SHOUTCAST['s_serverport'] : '8000');
  $s_serverpassword = (!empty ($SHOUTCAST['s_serverpassword']) ? $SHOUTCAST['s_serverpassword'] : '');
  $s_servercachefile = (!empty ($SHOUTCAST['s_servercachefile']) ? $SHOUTCAST['s_servercachefile'] : 'cache.xml');
  $s_servercachetime = (!empty ($SHOUTCAST['s_servercachetime']) ? $SHOUTCAST['s_servercachetime'] : '120');
  $s_serverirc = (!empty ($SHOUTCAST['s_serverirc']) ? $SHOUTCAST['s_serverirc'] : 'irc.server.net');
  $s_allowedusergroups = (!empty ($SHOUTCAST['s_allowedusergroups']) ? $SHOUTCAST['s_allowedusergroups'] : '2,3,4,5,6,7,8,10,11');
  unset ($SHOUTCAST);
?>
