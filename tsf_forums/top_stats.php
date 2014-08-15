<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_hidden_forums ()
  {
    global $CURUSER;
    global $securehash;
    ($query = sql_query ('SELECT fp.fid,f.fid FROM ' . TSF_PREFIX . 'forumpermissions fp LEFT JOIN ' . TSF_PREFIX . 'forums f ON (fp.fid=f.pid) WHERE fp.canview = \'no\' AND fp.gid = ' . sqlesc ($CURUSER['usergroup'])) OR sqlerr (__FILE__, 47));
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        $uf[] = 0 + $notin['fid'];
      }

      $unsearchforums = implode (',', $uf);
    }

    $query = sql_query ('SELECT fid,password FROM ' . TSF_PREFIX . 'forums WHERE password != \'\'');
    if (0 < mysql_num_rows ($query))
    {
      while ($notin = mysql_fetch_assoc ($query))
      {
        if (($notin['password'] != '' AND $_COOKIE['forumpass_' . $notin['fid']] != md5 ($CURUSER['id'] . $notin['password'] . $securehash)))
        {
          $uf[] = 0 + $notin['fid'];
          continue;
        }
      }

      if ($unsearchforums)
      {
        $unsearchforums .= ',' . @implode (',', $uf);
      }
      else
      {
        $unsearchforums = @implode (',', $uf);
      }
    }

    if ($unsearchforums)
    {
      return '' . 'WHERE fid NOT IN (' . $unsearchforums . ')';
    }

    return '';
  }

  $rootpath = './../';
  require_once $rootpath . 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('TS_VERSION', 'v.1.1 by xam');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ($usergroups['cantopten'] != 'yes')
  {
    print_no_permission ();
    exit ();
  }

  require INC_PATH . '/functions_cache2.php';
  if ($CachedStats = cache_check2 ('top_stats_' . $CURUSER['usergroup']))
  {
    stdhead ();
    echo $CachedStats;
    stdfoot ();
    exit ();
  }

  $lang->load ('top_stats');
  $Skip = get_hidden_forums ();
  $Stats = '
<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr valign="top">					
';
  $Stats .= '
			<td valign="top" width="160" class="none">
				<div style="padding-bottom: 0px;">
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['hottest'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT tid, subject, replies FROM ' . TSF_PREFIX . 'threads ' . $Skip . ' ORDER by lastpost DESC LIMIT 10') OR sqlerr (__FILE__, 95));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . tsf_seo_clean_text ($HT['subject'], 't', $HT['tid']) . '" alt="' . htmlspecialchars_uni ($HT['subject']) . '"  title="' . htmlspecialchars_uni ($HT['subject']) . '">' . cutename ($HT['subject'], 15) . '</a></td>
							<td width="10%" align="center">' . ts_nf ($HT['replies']) . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
';
  $Stats .= '
				<div style="padding-bottom: 0px;">
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['mostrated'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT tid, subject, round((votetotal / votenum),2) as rating FROM ' . TSF_PREFIX . 'threads ' . $Skip . ' GROUP BY tid ORDER BY rating DESC LIMIT 10') OR sqlerr (__FILE__, 117));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . tsf_seo_clean_text ($HT['subject'], 't', $HT['tid']) . '" alt="' . htmlspecialchars_uni ($HT['subject']) . '"  title="' . htmlspecialchars_uni ($HT['subject']) . '">' . cutename ($HT['subject'], 15) . '</a></td>
							<td width="10%" align="center">' . $HT['rating'] . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
';
  $Stats .= '
				<div style="padding-bottom: 0px;">
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['topposters'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT u.id, u.username, u.totalposts, g.namestyle FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled=\'yes\' ORDER by totalposts DESC LIMIT 10') OR sqlerr (__FILE__, 139));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . ts_seo ($HT['id'], $HT['username']) . '">' . get_user_color ($HT['username'], $HT['namestyle']) . '</a></td>
							<td width="10%" align="center">' . ts_nf ($HT['totalposts']) . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
			</td>
';
  $Stats .= '
			<td valign="top" width="160" class="none" style="padding-left: 6px">
				<div style="padding-bottom: 0px;">					
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['mostviewed'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT tid, subject, views FROM ' . TSF_PREFIX . 'threads ' . $Skip . ' ORDER by views DESC LIMIT 10') OR sqlerr (__FILE__, 164));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . tsf_seo_clean_text ($HT['subject'], 't', $HT['tid']) . '" alt="' . htmlspecialchars_uni ($HT['subject']) . '"  title="' . htmlspecialchars_uni ($HT['subject']) . '">' . cutename ($HT['subject'], 15) . '</a></td>
							<td width="10%" align="center">' . ts_nf ($HT['views']) . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
';
  $Stats .= '
				<div style="padding-bottom: 0px;">
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['mostreplied'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT tid, subject, replies FROM ' . TSF_PREFIX . 'threads ' . $Skip . ' ORDER by replies DESC LIMIT 10') OR sqlerr (__FILE__, 186));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . tsf_seo_clean_text ($HT['subject'], 't', $HT['tid']) . '" alt="' . htmlspecialchars_uni ($HT['subject']) . '"  title="' . htmlspecialchars_uni ($HT['subject']) . '">' . cutename ($HT['subject'], 15) . '</a></td>
							<td width="10%" align="center">' . ts_nf ($HT['replies']) . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
';
  $Stats .= '
				<div style="padding-bottom: 0px;">
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['topthreadstarters'] . '</td>
						</tr>';
  ($query = sql_query ('SELECT count(t.uid) AS totalthreads, u.id, u.username, g.namestyle FROM ' . TSF_PREFIX . 'threads t LEFT JOIN users u ON (t.uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) GROUP BY t.uid ORDER BY totalthreads DESC LIMIT 10') OR sqlerr (__FILE__, 208));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="89%" align="left"><a href="' . ts_seo ($HT['id'], $HT['username']) . '">' . get_user_color ($HT['username'], $HT['namestyle']) . '</a></td>
							<td width="10%" align="center">' . ts_nf ($HT['totalthreads']) . '</td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
			</td>
';
  $Stats .= '
			<td valign="top" class="none" style="padding-left: 6px" rowspan="3">
				<div style="padding-bottom: 0px;">					
					<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="3" class="thead">' . $lang->top_stats['latestposts'] . '</td>
						</tr>
						<tr>
							<td class="subheader"></td>
							<td class="subheader">Thread</td>
							<td class="subheader">Posted By</td>						
						</tr>';
  ($query = sql_query ('SELECT tid, subject, u.id, u.username, g.namestyle FROM ' . TSF_PREFIX . 'posts LEFT JOIN users u ON (uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ' . $Skip . ' GROUP by tid ORDER by dateline DESC LIMIT 31') OR sqlerr (__FILE__, 237));
  while ($HT = mysql_fetch_assoc ($query))
  {
    $Stats .= '
						<tr>
							<td width="1%" align="center"><img src="./images/post_old.gif" border="0" title="" /></td>
							<td width="890%" align="left"><a href="' . tsf_seo_clean_text ($HT['subject'], 't', $HT['tid'], '&amp;action=lastpost') . '" alt="' . htmlspecialchars_uni ($HT['subject']) . '"  title="' . htmlspecialchars_uni ($HT['subject']) . '">' . cutename ($HT['subject'], 70) . '</a></td>
							<td width="10%"><a href="' . ts_seo ($HT['id'], $HT['username']) . '">' . get_user_color ($HT['username'], $HT['namestyle']) . '</a></td>
						</tr>
	';
  }

  $Stats .= '
					</table>
				</div>
			</td>
';
  $Stats .= '
		</tr>
	</tbody>
</table>';
  stdhead ();
  echo $Stats;
  stdfoot ();
  cache_save2 ('top_stats_' . $CURUSER['usergroup'], $Stats);
?>
