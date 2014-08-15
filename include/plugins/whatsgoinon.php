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
// Dont change for future reference.
if (!defined('TS_P_VERSION'))
{
	define('TS_P_VERSION', '1.1 by xam');
}
// Security Check.
if (!defined('IN_PLUGIN_SYSTEM'))
{
	 die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}

// BEGIN Plugin: whatsgoinon
require_once(INC_PATH.'/functions_icons.php');
$is_mod=is_mod($usergroups);
$_dt = TIMENOW - TS_TIMEOUT;

$_qsquery = @sql_query('SELECT 1 FROM ts_sessions WHERE userid = \'0\' AND lastactivity > \''.$_dt.'\'');
$_guests = ts_nf(@mysql_num_rows($_qsquery));

$_wgo_query = sql_query('SELECT distinct s.userid as id, u.username, u.options, u.enabled, u.donor, u.leechwarn, u.warned, p.canupload, p.candownload, p.cancomment, p.canmessage, p.canshout, g.namestyle FROM ts_sessions s LEFT JOIN users u ON (s.userid=u.id) LEFT JOIN ts_u_perm p ON (u.id=p.userid) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE s.userid != \'0\' AND s.lastactivity > \''.$_dt.'\' ORDER by u.username, u.last_access');

$_most_ever = mysql_num_rows($_wgo_query)+$_guests;

if (file_exists(TSDIR.'/'.$cache.'/onlinestats.php'))
{
	include(TSDIR.'/'.$cache.'/onlinestats.php');
}

if(!$onlinestats['most_ever'])
{
	$onlinestats['most_ever'] = 0;
}

if ($onlinestats['most_ever'] < $_most_ever)
{
	$_cache_array = $onlinestats = array('most_ever' => $_most_ever, 'most_ever_time' => TIMENOW);
	$_name = 'onlinestats';
	$_filename = TSDIR.'/'.$cache.'/onlinestats.php';
	$_cachefile = @fopen("$_filename", "w");
	$_cachecontents = "<?php\n/** TS Generated Cache#2 - Do Not Alter\n * Cache Name: $_name\n * Generated: ".gmdate("r")."\n*/\n\n";
	$_cachecontents .= "\$$_name = ".@var_export($_cache_array, true).";\n?>";
	@fwrite($_cachefile, $_cachecontents);
	@fclose($_cachefile);
}

$_hidden_members=$_active_members=0;
$_usernames=array();
while($_active_users=mysql_fetch_assoc($_wgo_query))
{
	if(preg_match('#B1#is', $_active_users['options']) && $_active_users['id'] != $CURUSER['id'] && !$is_mod)
	{
		$_hidden_members++;
		continue;
	}
	else
	{		
		if (preg_match('#B1#is', $_active_users['options']))
		{
			$_hidden_members++;
		}
		else
		{
			$_active_members++;
		}		
		
		$_usernames[] = '<span style="white-space: nowrap;"><a href="'.ts_seo($_active_users['id'], $_active_users['username']).'">'.get_user_color($_active_users['username'], $_active_users['namestyle']).'</a>'.(preg_match('#B1#is', $_active_users['options']) ? '+' : '').get_user_icons($_active_users).'</span>';
	}	
}
require(TSDIR.'/'.$cache.'/usergroups.php');
$Legends = '
<script type="text/javascript">
	function ShowDescription(TextToShow)
	{
		if (TextToShow != "")
		{
			TSGetID("WaitingToShow").innerHTML = TextToShow;
		}
		else
		{
			TSGetID("WaitingToShow").innerHTML = "";
		}
	}
</script>
';
foreach ($usergroupscache as $left => $right)
{
	preg_match('#<span style="color:(.*);">#Ui', $right['namestyle'], $results);
	if ($results[1])
	{
		$Legends .= '
		<div style="float:left;">&nbsp;</div>
		<div class="alt2" style="float:left; height:8px; width:8px; padding:0px;cursor: pointer;" group="'.$right['title'].'" bold="y" clr="'.$results[1].'" onmouseover="ShowDescription(\''.$right['title'].'\');" onmouseout="ShowDescription(\'\');">
			<div class="tborder" style="height:8px; width:8px; background:'.$results[1].';"></div>
		</div>';
	}
}

$whatsgoinon = '
<table width="100%" cellpadding="3" cellspacing="0" border="0" align="center">
	<tr>
		<td class="subheader" colspan="2">
			'.($is_mod ? '<span style="float: right;">[<a href="'.$BASEURL.'/admin/index.php?act=whoisonline"><b>'.$lang->index['show'].'</b></a>]</span>' : '').$lang->index['activeusers'].' '.ts_nf($_most_ever).sprintf($lang->index['dactiveusers'], ts_nf($_guests), ts_nf($_active_members), ts_nf($_hidden_members)).'
		</td>
	<tr>
		<td colspan="2">
			<div style="float: right;">'.$Legends.'</div>
			<div>'.sprintf($lang->index['online'], ts_nf($onlinestats['most_ever']), my_datee($dateformat, $onlinestats['most_ever_time']), my_datee($timeformat, $onlinestats['most_ever_time'])).'</div>
			'.implode(', ', $_usernames).'	
			<div style="float: right;" id="WaitingToShow" name="WaitingToShow"></div>
		</td>
	</tr>	
</table>';
// END Plugin: whatsgoinon
?>
