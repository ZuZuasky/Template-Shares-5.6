<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('LOTTERY');
  $lottery_enabled = (!empty ($LOTTERY['lottery_enabled']) ? $LOTTERY['lottery_enabled'] : 'no');
  $lottery_allowed_usergroups = (!empty ($LOTTERY['lottery_allowed_usergroups']) ? $LOTTERY['lottery_allowed_usergroups'] : '[2],[3],[4],[8]');
  $lottery_ticket_amount = (!empty ($LOTTERY['lottery_ticket_amount']) ? $LOTTERY['lottery_ticket_amount'] : '100');
  $lottery_amount_type = (!empty ($LOTTERY['lottery_amount_type']) ? $LOTTERY['lottery_amount_type'] : 'MB');
  $lottery_winner_amount = (!empty ($LOTTERY['lottery_winner_amount']) ? $LOTTERY['lottery_winner_amount'] : '100');
  $lottery_max_tickets_per_user = (!empty ($LOTTERY['lottery_max_tickets_per_user']) ? $LOTTERY['lottery_max_tickets_per_user'] : '10');
  $lottery_max_winners = (!empty ($LOTTERY['lottery_max_winners']) ? $LOTTERY['lottery_max_winners'] : '10');
  $lottery_begin_date = (!empty ($LOTTERY['lottery_begin_date']) ? $LOTTERY['lottery_begin_date'] : '');
  $lottery_end_date = (!empty ($LOTTERY['lottery_end_date']) ? $LOTTERY['lottery_end_date'] : '');
  $lottery_last_winners = (!empty ($LOTTERY['lottery_last_winners']) ? $LOTTERY['lottery_last_winners'] : '');
  $lottery_last_winners_amount = (!empty ($LOTTERY['lottery_last_winners_amount']) ? $LOTTERY['lottery_last_winners_amount'] : '');
  $lottery_last_winners_date = (!empty ($LOTTERY['lottery_last_winners_date']) ? $LOTTERY['lottery_last_winners_date'] : '');
  unset ($LOTTERY);
?>
