<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.5.3
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: May 5, 2008, 2:44 am
|   Signature Key: TSSE9012008
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
// Dont change for future reference.
define('TS_P_VERSION', '1.2 by xam');
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
    die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}

include_once(INC_PATH.'/functions_icons.php');
$is_mod = is_mod($usergroups);

# BEGIN Plugin last24online

$last24 = TIMENOW - 86400;
$activeusers24 = array();
$res=@sql_query('SELECT u.id, u.username, u.enabled, u.options, u.warned, u.leechwarn, u.donor, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE UNIX_TIMESTAMP(u.last_access) > '.$last24.' ORDER by u.username, u.last_access DESC');
$_hidden_members=$_active_members=0;
$_usernames=array();
while($_active_users=mysql_fetch_assoc($res))
{
    $_images=array();
    $_u_images=false;
    if(preg_match('#B1#is', $_active_users['options']) && $_active_users['id'] != $CURUSER['id'] && !$is_mod)
    {
        $_hidden_members++;
        continue;
    }
    else
    {
        $_active_members++;

        if ($_active_users['warned'] == 'yes' || $_active_users['leechwarn'] == 'yes')
        {
            $_images[] = '<img src="'.$BASEURL.'/'.$pic_base_url.'warned.gif" border="0" width="11" height="11" alt="'.$lang->global['imgwarned'].'" title="'.$lang->global['imgwarned'].'" />';
        }
        if ($_active_users['donor'] == 'yes')
        {
            $_images[] = '<img src="'.$BASEURL.'/'.$pic_base_url.'star.gif" border="0" width="11" height="11" alt="'.$lang->global['imgdonated'].'" title="'.$lang->global['imgdonated'].'" />';
        }
        if(sizeof($_images) > 0)
        {
            $_u_images = implode(' ', $_images);
        }
        $_usernames[] = '<span style="white-space: nowrap;"><a href="./userdetails.php?id='.$_active_users['id'].'">'.get_user_color($_active_users['username'], $_active_users['namestyle']).'</a>'.(preg_match('#B1#is', $_active_users['options']) ? '+' : '').($_u_images ? $_u_images : '').'</span>';
    }
}


$last24online .= '<fieldset class="fieldset"><legend><b>Last 24 hrs. Online</b></legend><center><div class="small" style="padding-top: 6px;">'.implode(', ', $_usernames).'</div></center></fieldset>';

# END Plugin last24online

?>