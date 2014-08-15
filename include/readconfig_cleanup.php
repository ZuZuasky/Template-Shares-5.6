<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('CLEANUP');
  $ai = (!empty ($CLEANUP['ai']) ? $CLEANUP['ai'] : 'yes');
  $max_dead_torrent_time = (!empty ($CLEANUP['max_dead_torrent_time']) ? $CLEANUP['max_dead_torrent_time'] : '2');
  $referrergift = (!empty ($CLEANUP['referrergift']) ? $CLEANUP['referrergift'] : '2.5');
  $autoinvitetime = (!empty ($CLEANUP['autoinvitetime']) ? $CLEANUP['autoinvitetime'] : '28');
  $ban_user_limit = (!empty ($CLEANUP['ban_user_limit']) ? $CLEANUP['ban_user_limit'] : '5');
  $promote_gig_limit = (!empty ($CLEANUP['promote_gig_limit']) ? $CLEANUP['promote_gig_limit'] : '0');
  $promote_min_ratio = (!empty ($CLEANUP['promote_min_ratio']) ? $CLEANUP['promote_min_ratio'] : '1.05');
  $promote_min_reg_days = (!empty ($CLEANUP['promote_min_reg_days']) ? $CLEANUP['promote_min_reg_days'] : '28');
  $demote_min_ratio = (!empty ($CLEANUP['demote_min_ratio']) ? $CLEANUP['demote_min_ratio'] : '0.95');
  $leechwarn_remove_ratio = (!empty ($CLEANUP['leechwarn_remove_ratio']) ? $CLEANUP['leechwarn_remove_ratio'] : '0.8');
  $leechwarn_min_ratio = (!empty ($CLEANUP['leechwarn_min_ratio']) ? $CLEANUP['leechwarn_min_ratio'] : '0.4');
  $leechwarn_gig_limit = (!empty ($CLEANUP['leechwarn_gig_limit']) ? $CLEANUP['leechwarn_gig_limit'] : '5');
  $leechwarn_length = (!empty ($CLEANUP['leechwarn_length']) ? $CLEANUP['leechwarn_length'] : '2');
  unset ($CLEANUP);
?>
