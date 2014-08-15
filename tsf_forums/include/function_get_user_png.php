<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/

// START: Configure Special Groups -- See init.php for usergroup names.
$_moderators = array(UC_SUPERMOD,UC_MODERATOR,UC_FORUMMOD); // Moderator Team. Show rank_moderator.png image for this group users.
$_administrators = array(UC_STAFFLEADER,UC_SYSOP,UC_ADMINISTRATOR); // Admin Team. Show rank_admin.png image for this group users.
$_uploaders = array(UC_UPLOADER); // Uploaders Team. Show rank_founder.png image for this group users.
$_vips = array(UC_VIP); // VIP Team. Show rank_mvp.png image for this group users.
// END: Configure Special Groups -- See init.php for usergroup names.

// Dont touch anything below;
function get_user_png($_user_array=array())
{
	global $_moderators, $_administrators, $_uploaders, $_vips;
	$_png_image = "rank_0";
	if ($_user_array['totalposts'] > 10 && $_user_array['totalposts'] <= 30)
	{
		$_png_image = "rank_1";
	}
	elseif ($_user_array['totalposts'] > 30 && $_user_array['totalposts'] <= 70)
	{
		$_png_image = "rank_2";
	}
	elseif ($_user_array['totalposts'] > 70 && $_user_array['totalposts'] <= 120)
	{
		$_png_image = "rank_3";
	}
	elseif ($_user_array['totalposts'] > 120 && $_user_array['totalposts'] <= 170)
	{
		$_png_image = "rank_4";
	}
	elseif ($_user_array['totalposts'] > 170 && $_user_array['totalposts'] <= 250)
	{
		$_png_image = "rank_5";
	}
	elseif ($_user_array['totalposts'] > 250 && $_user_array['totalposts'] <= 500)
	{
		$_png_image = "rank_6";
	}
	elseif ($_user_array['totalposts'] > 500)
	{
		$_png_image = "rank_postwhore";
	}

	if ($_user_array['enabled'] != 'yes')
	{
		$_png_image = "rank_banned";
	}
	elseif (in_array($_user_array['usergroup'], $_moderators))
	{
		$_png_image = "rank_moderator";
	}
	elseif (in_array($_user_array['usergroup'], $_administrators))
	{
		$_png_image = "rank_admin";		
	}
	elseif (in_array($_user_array['usergroup'], $_uploaders))
	{
		$_png_image = "rank_founder";		
	}
	elseif (in_array($_user_array['usergroup'], $_vips))
	{
		$_png_image = "rank_mvp";
	}
	$_user_array_rank = '<img src="images/ranks/'.$_png_image.'.gif" border="0" alt="'.$_user_array['title'].'" title="'.$_user_array['title'].'">';
	unset($_user_array);
	return $_user_array_rank;
}
?>
