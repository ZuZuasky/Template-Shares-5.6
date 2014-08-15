<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  readconfig ('FORUMCP');
  $f_forum_online = (!empty ($FORUMCP['f_forum_online']) ? $FORUMCP['f_forum_online'] : 'no');
  $f_offlinemsg = (!empty ($FORUMCP['f_offlinemsg']) ? $FORUMCP['f_offlinemsg'] : 'These forums are currently closed for maintenance. Please check back later.');
  $f_forumname = (!empty ($FORUMCP['f_forumname']) ? $FORUMCP['f_forumname'] : $SITENAME . ' Forums');
  $f_threadsperpage = (!empty ($FORUMCP['f_threadsperpage']) ? $FORUMCP['f_threadsperpage'] : '10');
  $f_postsperpage = (!empty ($FORUMCP['f_postsperpage']) ? $FORUMCP['f_postsperpage'] : '10');
  $f_minmsglength = (!empty ($FORUMCP['f_minmsglength']) ? $FORUMCP['f_minmsglength'] : '3');
  $f_avatar_maxwidth = (!empty ($FORUMCP['f_avatar_maxwidth']) ? $FORUMCP['f_avatar_maxwidth'] : '100');
  $f_avatar_maxheight = (!empty ($FORUMCP['f_avatar_maxheight']) ? $FORUMCP['f_avatar_maxheight'] : '100');
  $f_avatar_maxsize = (!empty ($FORUMCP['f_avatar_maxsize']) ? $FORUMCP['f_avatar_maxsize'] : '50000');
  $f_showstats = (!empty ($FORUMCP['f_showstats']) ? $FORUMCP['f_showstats'] : 'yes');
  $f_upload_path = (!empty ($FORUMCP['f_upload_path']) ? $FORUMCP['f_upload_path'] : './uploads/');
  $f_upload_maxsize = (!empty ($FORUMCP['f_upload_maxsize']) ? $FORUMCP['f_upload_maxsize'] : '1024');
  $f_allowed_types = (!empty ($FORUMCP['f_allowed_types']) ? $FORUMCP['f_allowed_types'] : 'gif,jpg,png,rar,zip,txt');
  $f_ads = (!empty ($FORUMCP['f_ads']) ? $FORUMCP['f_ads'] : '');
  $f_sfpertr = (!empty ($FORUMCP['f_sfpertr']) ? $FORUMCP['f_sfpertr'] : '3');
  unset ($FORUMCP);
?>
