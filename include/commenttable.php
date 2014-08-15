<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function commenttable ($rows, $type = '', $edit = '', $lc = false, $quote = false, $return = false)
  {
    global $CURUSER;
    global $BASEURL;
    global $rootpath;
    global $pic_base_url;
    global $lang;
    global $usergroups;
    global $timeformat;
    global $dateformat;
    global $useajax;
    global $torrent;
    global $regdateformat;
    include_once INC_PATH . '/functions_ratio.php';
    $moderator = is_mod ($usergroups);
    $dt = get_date_time (gmtime () - TS_TIMEOUT);
    $totalrows = count ($rows);
    $quickmenu = '';
    $showcommentstable = '';
    $ajax_quick_edit_loaded = false;
    $quote_loaded = false;
    $ajax_quick_report_loaded = false;
    $QuickVoteLoaded = false;
    $_count = 0;
    foreach ($rows as $row)
    {
      if ($row['totalvotes'] != '0|0')
      {
        $TotalVotes = @explode ('|', $row['totalvotes']);
        $row['totalvotes'] = $TotalVotes[0] - $TotalVotes[1];
        if (($row['totalvotes'] <= 0 - 5 AND !$moderator))
        {
          continue;
        }
      }
      else
      {
        $row['totalvotes'] = 0;
      }

      ++$_count;
      $p_commenthistory = $p_edit = $p_delete = $p_text = $p_report = $p_quote = '';
      if ($QuickVoteLoaded == false)
      {
        $showcommentstable .= '<script type="text/javascript" src="' . $BASEURL . '/scripts/quick_vote.js?v=' . O_SCRIPT_VERSION . '"></script>';
      }

      if (($row['user'] == $CURUSER['id'] OR $moderator))
      {
        if ($useajax == 'yes')
        {
          if ($ajax_quick_edit_loaded == false)
          {
            $showcommentstable .= '
					<script type="text/javascript">
						var l_quick_save_button = "' . $lang->global['buttonsave'] . '";
						var l_quick_cancel_button = "' . $lang->global['cancel'] . '";
						var l_quick_adv_button = "' . $lang->global['advancedbutton'] . '";
						var bbcodes = \'' . trim (str_replace (array ('\'', '
', '
'), array ('\\\'', '', ''), ts_show_bbcode_links ('quick_edit_form', 'newContent'))) . '\';
					</script>	
					<script type="text/javascript" src="' . $BASEURL . '/scripts/inline_quick_edit.js?v=' . O_SCRIPT_VERSION . '"></script>
					' . ((($moderator OR $usergroups['canreport'] == 'yes') AND $ajax_quick_report_loaded == false) ? '
					<script type="text/javascript">
						var ReportComment = "' . $lang->report['reportcomment'] . '";
						var ReportReason = "' . $lang->report['reason'] . '";
						var ReportDone = "' . $lang->report['done'] . '";
					</script>
					<script type="text/javascript" src="' . $BASEURL . '/scripts/inline_quick_report.js?v=' . O_SCRIPT_VERSION . '"></script>' : '');
            $ajax_quick_edit_loaded = true;
            $ajax_quick_report_loaded = true;
          }

          $p_edit = '<img src="' . $BASEURL . '/' . $pic_base_url . 'p_edit.gif" border="0" style="cursor: pointer;" onclick="TSQuickEditPost(\'post_message_' . $row['id'] . '\',\'' . $BASEURL . '/' . $edit . 'comment.php?action=edit&amp;cid=' . $row['id'] . '&amp;page=' . intval ($_GET['page']) . '\');" class="inlineimg" />';
        }
        else
        {
          $p_edit = '<a href="' . $BASEURL . '/' . $edit . 'comment.php?action=edit&amp;cid=' . $row['id'] . '&amp;page=' . intval ($_GET['page']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'p_edit.gif" border="0" class="inlineimg" /></a>';
        }
      }

      if ($moderator)
      {
        $p_delete = '<a href="' . $BASEURL . '/' . $edit . 'comment.php?action=delete&amp;cid=' . $row['id'] . '&amp;tid=' . $row['torrentid'] . '&amp;page=' . intval ($_GET['page']) . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'p_delete.gif" border="0" class="inlineimg" /></a>';
        $p_commenthistory = '<div style="float: left;"><input type="button" class="button" value="View Comment History" onclick="jumpto(\'' . $BASEURL . '/userhistory.php?action=viewcomments&id=' . $row['user'] . '\'); return false;" /></div>';
      }

      if ($quote === true)
      {
        if ($quote_loaded == false)
        {
          $p_quote .= '
				<script type="text/javascript">
					function quote(textarea,form,quote)
					{
						var area=document.forms[form].elements[textarea];
						area.value=area.value+" "+quote+" ";
						area.focus();
					};
				</script>';
          $quote_loaded = true;
        }

        $p_quote .= '<a href="javascript: quote(\'message\', \'comment\', \'[quote=' . htmlspecialchars_uni ($row['username']) . ']' . htmlspecialchars_uni ($row['text']) . '[/quote]\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'p_quote.gif" border="0" class="inlineimg" /></a>';
      }

      if (($moderator OR $usergroups['canreport'] == 'yes'))
      {
        if ($ajax_quick_report_loaded == false)
        {
          $showcommentstable .= '
				<script type="text/javascript">
					var ReportComment = "' . $lang->report['reportcomment'] . '";
					var ReportReason = "' . $lang->report['reason'] . '";
					var ReportDone = "' . $lang->report['done'] . '";
				</script>
				<script type="text/javascript" src="' . $BASEURL . '/scripts/inline_quick_report.js?v=' . O_SCRIPT_VERSION . '"></script>
				';
          $ajax_quick_report_loaded = true;
        }

        $p_report = '<a ' . ($useajax == 'yes' ? 'onclick="TSReportComment(\'' . $row['id'] . '\'); return false;" ' : '') . 'href="' . $BASEURL . '/report.php?action=report' . $type . 'comment&amp;reportid=' . $row['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'report.gif" border="0" title="' . $lang->global['reportcomment'] . '" style="display: inline;" id="report_image_' . $row['id'] . '" class="inlineimg" /></a>';
      }

      if ($row['editedby'])
      {
        $p_text .= '
				<br />
				<div>
					<font size="1" class="small">' . $lang->global['lastedited'] . ' <a href="' . ts_seo ($row['editedby'], $row['editedbyuname']) . '">' . get_user_color ($row['editedbyuname'], $row['editbynamestyle']) . '</a> ' . my_datee ($dateformat, $row['editedat']) . ' ' . my_datee ($timeformat, $row['editedat']) . '</font>
				</div>';
      }

      if (!empty ($row['modnotice']))
      {
        $p_text .= '
				<br />
				<div class="modnotice">
					' . sprintf ($lang->global['modnotice'], $row['modeditid'], $row['modeditusername'], my_datee ($dateformat, $row['modedittime']) . ' ' . my_datee ($timeformat, $row['modedittime']), format_comment ($row['modnotice'])) . '				
				</div>
			';
      }

      $signature = ((!empty ($row['signature']) AND preg_match ('#H1#is', $CURUSER['options'])) ? '<br /><hr size="1" width="50%"  align="left" />' . format_comment ($row['signature'], true, true, true, true, 'signatures') : '');
      $textbody = format_comment ($row['text']);
      if (((preg_match ('#B1#is', $row['options']) AND !$moderator) AND $row['user'] != $CURUSER['id']))
      {
        $IsUserOnline = '<img src="' . $BASEURL . '/' . $pic_base_url . 'user_offline.gif" border="0" class="inlineimg" />';
      }
      else
      {
        if (($dt < $row['last_access'] OR $row['user'] == $CURUSER['id']))
        {
          $IsUserOnline = '<img src="' . $BASEURL . '/' . $pic_base_url . 'user_online.gif" border="0" class="inlineimg" />';
        }
        else
        {
          $IsUserOnline = '<img src="' . $BASEURL . '/' . $pic_base_url . 'user_offline.gif" border="0" class="inlineimg" />';
        }
      }

      $SendPM = ' <a href="' . $BASEURL . '/sendmessage.php?receiver=' . $row['user'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'pm.gif" border="0" title="' . $lang->global['sendmessageto'] . htmlspecialchars_uni ($row['username']) . '" class="inlineimg" /></a>';
      if (((preg_match ('#I3#is', $row['options']) OR preg_match ('#I4#is', $row['options'])) AND !$moderator))
      {
        $OnMouseOver = 'onmouseover="ddrivetip(\'' . $lang->global['nopermission'] . '\', 200)"; onmouseout="hideddrivetip()" ';
      }
      else
      {
        $Ratio = get_user_ratio ($row['uploaded'], $row['downloaded']);
        $UserStats = '<b>' . $lang->global['added'] . ':</b> ' . my_datee ($regdateformat, $row['registered']) . '<br /><b>' . $lang->global['uploaded'] . '</b> ' . mksize ($row['uploaded']) . '<br /><b>' . $lang->global['downloaded'] . '</b> ' . mksize ($row['downloaded']) . '<br /><b>' . $lang->global['ratio'] . '</b> ' . strip_tags ($Ratio);
        $OnMouseOver = '' . 'onmouseover="ddrivetip(\'' . $UserStats . '\', 200)"; onmouseout="hideddrivetip()" ';
      }

      $username = ($row['username'] ? '<a ' . $OnMouseOver . 'href="' . ts_seo ($row['user'], $row['username']) . '" alt="' . $row['username'] . '">' . get_user_color ($row['username'], $row['namestyle']) . '</a> (' . ($row['title'] ? htmlspecialchars_uni ($row['title']) : get_user_color ($row['grouptitle'], $row['namestyle'])) . ') ' . ($row['donor'] == 'yes' ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'star.gif" alt="' . $lang->global['imgdonated'] . '" title="' . $lang->global['imgdonated'] . '" border="0" class="inlineimg" />' : '') . (($row['warned'] == 'yes' OR $row['leechwarn'] == 'yes') ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'warned.gif" alt="' . $lang->global['imgwarned'] . '" title="' . $lang->global['imgwarned'] . '" border="0" class="inlineimg" />' : '') . (($row['enabled'] != 'yes' OR $row['usergroup'] == UC_BANNED) ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'disabled.gif" alt="' . $lang->global['imgdisabled'] . '" title="' . $lang->global['imgdisabled'] . '" border="0" class="inlineimg" />' : '') : $lang->global['guest']);
      $HighLight = ($row['totalvotes'] <= 0 - 5 ? ' class="highlight"' : '');
      $showcommentstable .= ($_count == 1 ? '' : '<br />') . '
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tbody>
			' . ($_count == 1 ? '
				<tr>
					<td class="thead" colspan="2" align="left">' . htmlspecialchars_uni ($torrent['name']) . '</td>
				</tr>
			' : '') . '
				<tr>
					<td colspan="2"' . ($HighLight ? $HighLight : ' class="subheader"') . '>
						<div style="float: right;"><span id="commentvotes' . $row['id'] . '" name="commentvotes' . $row['id'] . '">' . $row['totalvotes'] . '</span> <img src="' . $BASEURL . '/' . $pic_base_url . 'down.png" alt="" title="" border="0" class="inlineimg" style="cursor: pointer;" onclick="TSQuickVote(\'' . $row['id'] . '\', \'-1\'); return false;" /> <img src="' . $BASEURL . '/' . $pic_base_url . 'up.png" alt="" title="" border="0" class="inlineimg" style="cursor: pointer;" onclick="TSQuickVote(\'' . $row['id'] . '\', \'1\'); return false;" /></div>
						<div style="float: left;"><a name="cid' . $row['id'] . '" id="cid' . $row['id'] . '"></a><a href="#cid' . $row['id'] . '">#' . $_count . '</a> by ' . $username . ' ' . my_datee ($dateformat, $row['added']) . ' ' . my_datee ($timeformat, $row['added']) . '</div>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top" height="1%" width="1%"' . $HighLight . '>
						' . get_user_avatar ($row['useravatar'], false, 100, 100) . '
					</td>
					<td align="left" valign="top"' . $HighLight . '>
						<div id="post_message_' . $row['id'] . '" style="display: inline;">' . $textbody . '</div>
						<p id="textinfo">' . $p_text . '</p>
						' . $signature . '
						<div id="report_message_' . $row['id'] . '" style="display: none;"></div>
					</td>
				</tr>
				<tr' . $HighLight . '>
					<td align="center" height="32" width="100">' . $IsUserOnline . $SendPM . '</td>
					<td><div style="float: right;">' . $p_report . ' ' . $p_delete . ' ' . $p_edit . ' ' . $p_quote . ' <img src="' . $BASEURL . '/' . $pic_base_url . 'p_up.gif" alt="" title="" border="0" class="inlineimg" style="cursor: pointer;" onclick="javascript:scroll(0,0); return false;" /></div>' . $p_commenthistory . '</td>
				</tr>
			</tbody>
		</table>
		';
    }

    $showcommentstable .= '<div style="display: block;" id="ajax_comment_preview"></div><div style="display: block;" id="ajax_comment_preview2"></div>';
    if ($return)
    {
      return $showcommentstable;
    }

    echo $showcommentstable;
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('NcodeImageResizer', true);
?>
