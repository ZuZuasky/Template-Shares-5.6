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

// BEGIN Plugin: seedersneeded
$seedersneeded = '';
$Query = sql_query('SELECT id, name, seeders, leechers FROM torrents WHERE leechers > 0 AND seeders = 0 ORDER BY leechers DESC LIMIT 10');
if (mysql_num_rows($Query) > 0)
{
	$seedersneeded .= '
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td class="subheader" align="left" width="80%">Torrent Name</td>
		<td class="subheader" align="center" width="10%">Seeders</td>
		<td class="subheader" align="center" width="10%">Leechers</td>
	</tr>';
	while ($Torrent = mysql_fetch_assoc($Query))
	{
		$seolink = ts_seo($Torrent['id'], $Torrent['name'], 's');
		$seedersneeded .= '
		<tr>
			<td align="left" width="80%"><a href="'.$seolink . '" target="_top"><b>'.htmlspecialchars_uni($Torrent['name']).'</b></a></td>
			<td align="center" width="10%"><font color="#ff0000">'.$Torrent['seeders'].'</font></td>
			<td align="center" width="10%">'.ts_nf($Torrent['leechers']).'</td>
		</tr>';
	}
	$seedersneeded .= ' </table>';
}
else
{
	$seedersneeded .= '<div align="center"><b>All Torrents have Seeders.</b></div>';
}
// END Plugin: seedersneeded
?>
