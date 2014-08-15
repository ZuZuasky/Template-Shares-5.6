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
$lang->load('poll');
// BEGIN Plugin: tspoll
$tspoll = '
<!-- begin tspoll -->
<a id="showtspoll" name="showtspoll"></a>';
$pollbits = '';
$counter = 1;

$show['editpoll'] = ($usergroups && is_mod($usergroups) ? true : false);

// get poll info
$pollinfoQ = sql_query("
	SELECT *
	FROM " . TSF_PREFIX . "poll
	WHERE fortracker = '1' AND active = '1' ORDER BY dateline DESC LIMIT 1
") or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($pollinfoQ) == 0)
{
	$tspoll .= $lang->poll['nopoll'];
}
else
{
	$pollinfo = mysql_fetch_assoc($pollinfoQ);

	$pollinfo['question'] = htmlspecialchars_uni($pollinfo['question']);
	$splitoptions = explode('~~~', $pollinfo['options']);
	$splitvotes = explode('~~~', $pollinfo['votes']);
	$showresults = 0;
	$uservoted = 0;
	if ($CURUSER['id'] > 0 AND $usergroups['canvote'] != 'yes')
	{
		$nopermission = 1;
	}

	if (!$pollinfo['active'] OR ($pollinfo['dateline'] + ($pollinfo['timeout'] * 86400) < TIMENOW AND $pollinfo['timeout'] != 0) OR $nopermission)
	{
		//poll is closed, ie show results no matter what
		$showresults = 1;
	}
	else
	{
		//get userid, check if user already voted	
		if (isset($_COOKIE['showpollresult']) AND $_COOKIE['showpollresult'] == $pollinfo['pollid'] OR (isset($_COOKIE['poll_voted_'.$pollinfo['pollid']])))
		{
			$uservoted = 1;
		}
	}

	if ($pollinfo['timeout'] AND !$showresults)
	{
		$pollendtime = my_datee($timeformat, $pollinfo['dateline'] + ($pollinfo['timeout'] * 86400));
		$pollenddate = my_datee($dateformat, $pollinfo['dateline'] + ($pollinfo['timeout'] * 86400));
		$show['pollenddate'] = true;
	}
	else
	{
		$show['pollenddate'] = false;
	}

	foreach ($splitvotes AS $index => $value)
	{
		$pollinfo['numbervotes'] += $value;
	}

	if ($CURUSER['id'] > 0)
	{
		$pollvotes = sql_query("
			SELECT voteoption
			FROM " . TSF_PREFIX . "pollvote
			WHERE userid = " . $CURUSER['id'] . " AND pollid = {$pollinfo['pollid']}
		");
		if (mysql_num_rows($pollvotes) > 0)
		{
			$uservoted = 1;
		}
	}

	if (isset($_GET['do']) AND $_GET['do'] == 'showpublicresults' AND $pollinfo['public'])
	{
		$public = sql_query("
			SELECT p.userid, p.voteoption, u.username, g.namestyle
			FROM " . TSF_PREFIX . "pollvote AS p
			INNER JOIN users AS u ON (p.userid = u.id)
			LEFT JOIN usergroups g ON (u.usergroup=g.gid)
			WHERE p.pollid = '$pollinfo[pollid]'
			ORDER BY u.username ASC
		") or sqlerr(__FILE__,__LINE__);
		$allnames = array();
		while ($name = mysql_fetch_assoc($public))
		{
			$allnames["$name[voteoption]"][] = '<a href="'.ts_seo($name['userid'], $name['username']).'">'.get_user_color($name['username'], $name['namestyle']).'</a>';
		}
	}

	if ($showresults OR $uservoted)
	{
		if ($uservoted)
		{
			$uservote = array();
			while ($pollvote = mysql_fetch_assoc($pollvotes))
			{
				$uservote["$pollvote[voteoption]"] = 1;
			}
		}
	}

	$option['open'] = '1';
	$option['close'] = 'r';

	foreach ($splitvotes AS $index => $value)
	{
		$arrayindex = $index + 1;
		$option['uservote'] = ($uservote["$arrayindex"] ? true : false);
		$option['question'] = htmlspecialchars_uni($splitoptions["$index"]);

		$show['pollvoters'] = false;
		if ($pollinfo['public'] AND $value AND is_array($allnames))
		{
			$names = $allnames[($index+1)];
			unset($allnames[($index+1)]);
			if (!empty($names))
			{
				$names = implode(', ', $names);
				$show['pollvoters'] = true;
			}
		}

		// public link
		if ($CURUSER['id'] > 0  AND $pollinfo['public'] AND $value)
		{
			$option['votes'] = '<a href="'.$BASEURL.'/index.php?do=showpublicresults&amp;pollid=' . $pollinfo['pollid'] . '#showtspoll">' . ts_nf(0+$value) . '</a>';
		}
		else
		{
			$option['votes'] = ts_nf(0+$value);   //get the vote count for the option
		}

		$option['number'] = $counter;  //number of the option

		//Now we check if the user has voted or not
		if ($showresults OR $uservoted)
		{ // user did vote or poll is closed

			if ($value <= 0)
			{
				$option['percent'] = 0;
			}
			else if ($pollinfo['multiple'])
			{
				$option['percent'] = number_format(($value < $pollinfo['voters']) ? $value / $pollinfo['voters'] * 100 : 100, 2);
			}
			else
			{
				$option['percent'] = number_format(($value < $pollinfo['numbervotes']) ? $value / $pollinfo['numbervotes'] * 100 : 100, 2);
			}

			$option['graphicnumber'] = $option['number'] % 6 + 1;
			$option['barnumber'] = round($option['percent']) * 2;
			$option['remainder'] = 201 - $option['barnumber'];

			// Phrase parts below
			if ($nopermission)
			{
				$pollstatus = $lang->poll['poll13'];
			}
			else if ($showresults)
			{
				$pollstatus = $lang->poll['poll12'];
			}
			else if ($uservoted)
			{
				$pollstatus = $lang->poll['poll11'];
			}		

			$pollbits .= '
				<tr>
					<td class="alt1" width="50%" align="left">
						'.($option['uservote'] ? '<em>'.$option['question'].'</em> *' : 	$option['question']).'
						'.($show['pollvoters'] ? '<div class="smallfont" style="border:inset 1px; margin-top:6px; padding:6px"><font size="1">'.$names.'</font></div>' : '').'
					</td>
					<td class="alt2" width="50%">
						<img src="'.$BASEURL.'/tsf_forums/images/polls/bar'.$option['graphicnumber'].'-'.$option['open'].'.gif" alt="" width="3" height="10"/><img src="'.$BASEURL.'/tsf_forums/images/polls/bar'.$option['graphicnumber'].'.gif" alt="" width="'.$option['barnumber'].'" height="10"/><img src="'.$BASEURL.'/tsf_forums/images/polls/bar'.$option['graphicnumber'].'-'.$option['close'].'.gif" alt="" width="3" height="10" />
					</td>
					<td class="alt1" align="center" title=""><strong>'.$option['votes'].'</strong></td>
					<td class="alt2" align="right" nowrap="nowrap">'.$option['percent'].'%</td>
				</tr>
				';
		}
		else
		{
			if ($pollinfo['multiple'])
			{
				$pollbits .= '
				<div> <label for="cb_optionnumber_'.$option['number'].'"> <input class="none" type="checkbox" name="optionnumber['.$option['number'].']" value="yes" id="cb_optionnumber_'.$option['number'].'" />'.$option['question'].'</label></div>';
			}
			else
			{
				$pollbits .= '
				<div><label for="rb_optionnumber_'.$option['number'].'"><input class="none" type="radio" name="optionnumber" value="'.$option['number'].'" id="rb_optionnumber_'.$option['number'].'" />'.$option['question'].'</label></div>';
			}
		}
		$counter++;
	}

	if ($pollinfo['multiple'])
	{
		$pollinfo['numbervotes'] = $pollinfo['voters'];
		$show['multiple'] = true;
	}

	if ($pollinfo['public'])
	{
		$show['publicwarning'] = true;
	}
	else
	{
		$show['publicwarning'] = false;
	}

	if ($showresults OR $uservoted)
	{
		$tspoll .= '
		<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td class="subheader" colspan="4">
					'.($show['editpoll'] ? '<span class="smallfont" style="float:right;">[<a href="'.$BASEURL.'/admin/index.php?act=manage_poll&amp;action=polledit&amp;pollid='.$pollinfo['pollid'].'">'.$lang->poll['editpoll'].'</a>]</span>' : '').'
					'.$lang->poll['results'].'<span class="normal">: '.$pollinfo['question'].'</span>
				</td>
			</tr>
			'.($show['pollenddate'] ? '
			<tr>
				<td class="thead" colspan="4" align="center" style="font-weight:normal">'.sprintf($lang->poll['closed'], $pollenddate, $pollendtime).'</td>
			</tr>' : '').'		
			'.$pollbits.'
			<tr>
				<td class="tfoot" colspan="4" align="center"><span class="smallfont">'.($show['multiple'] ? $lang->poll['multiple'] : '').' '.$lang->poll['voters'].': <strong>'.$pollinfo['numbervotes'].'</strong>. '.$pollstatus.'</span></td>
			</tr>
		</table>';
	}
	else
	{
		$tspoll .= '
		<form action="'.$BASEURL.'/poll.php?do=pollvote&amp;pollid='.$pollinfo['pollid'].'" method="post">	
		<input type="hidden" name="do" value="pollvote" />
		<input type="hidden" name="pollid" value="'.$pollinfo['pollid'].'" />

		<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td class="subheader">
					'.($show['editpoll'] ? '<span class="smallfont" style="float:right;">[<a href="'.$BASEURL.'/admin/index.php?act=manage_poll&amp;action=polledit&amp;pollid='.$pollinfo['pollid'].'">'.$lang->poll['editpoll'].'</a>]</span>' : '').'
					'.$lang->poll['poll'].'<span class="normal">: '.$pollinfo['question'].'</span>
				</td>
			</tr>
			'.($show['pollenddate'] ? '
			<tr>
				<td class="thead" align="center" style="font-weight:normal">'.sprintf($lang->poll['closed'], $pollenddate, $pollendtime).'</td>
			</tr>' : '').'
			<tr>
				<td class="panelsurround" align="center">
					<div class="panel">
						<div align="left">					
							'.($show['publicwarning'] ? '					
							<div class="fieldset">'.$lang->poll['warning'].'</div>' : '').'
							
							<fieldset class="fieldset">
								<legend>'.$lang->poll['options'].'</legend>
								<div style="padding:3px">
									<div style="margin-bottom:3px"><strong>'.$pollinfo['question'].'</strong></div>
									'.$pollbits.'
								</div>
							</fieldset>

							<div style="padding-top: 3px;">
								<span style="float:right;"><a href="'.$BASEURL.'/poll.php?do=showresults&amp;pollid='.$pollinfo['pollid'].'">'.$lang->poll['results'].'</a></span>
								<input type="submit" class="button" value="'.$lang->poll['votenow'].'" />
							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
		</form>';
	}
}
$tspoll .= '
<!-- end tspoll -->
';
//  END Plugin: tspoll
?>
