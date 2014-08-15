<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('P_VERSION', '0.8');
  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  define ('NcodeImageResizer', true);
  require_once 'global.php';
  dbconn ();
  if (!$CURUSER)
  {
    exit ();
  }

  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
  if (empty ($_POST['msg']))
  {
    exit ('<error>' . $lang->global['dontleavefieldsblank'] . '</error>');
    return 1;
  }

  $msg = urldecode ($_POST['msg']);
  $msg = strval ($msg);
  if (strtolower ($shoutboxcharset) != 'utf-8')
  {
    if (function_exists ('iconv'))
    {
      $msg = iconv ('UTF-8', $shoutboxcharset, $msg);
    }
    else
    {
      if (function_exists ('mb_convert_encoding'))
      {
        $msg = mb_convert_encoding ($msg, $shoutboxcharset, 'UTF-8');
      }
      else
      {
        if (strtolower ($shoutboxcharset) == 'iso-8859-1')
        {
          $msg = utf8_decode ($msg);
        }
      }
    }
  }

  include_once INC_PATH . '/functions_ratio.php';
  $signature = (!empty ($CURUSER['signature']) ? '<br /><hr size="1" width="50%"  align="left" />' . format_comment ($CURUSER['signature'], true, true, true, true, 'signatures') : '');
  $textbody = format_comment ($msg);
  $IsUserOnline = '<img src="' . $BASEURL . '/' . $pic_base_url . 'user_online.gif" border="0" class="inlineimg" />';
  $SendPM = ' <a href="' . $BASEURL . '/sendmessage.php?receiver=' . $CURUSER['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'pm.gif" border="0" title="' . $lang->global['sendmessageto'] . htmlspecialchars_uni ($CURUSER['username']) . '" class="inlineimg" /></a>';
  $Ratio = get_user_ratio ($CURUSER['uploaded'], $CURUSER['downloaded']);
  $UserStats = '<b>' . $lang->global['added'] . ':</b> ' . my_datee ($regdateformat, $CURUSER['added']) . '<br /><b>' . $lang->global['uploaded'] . '</b> ' . mksize ($CURUSER['uploaded']) . '<br /><b>' . $lang->global['downloaded'] . '</b> ' . mksize ($CURUSER['downloaded']) . '<br /><b>' . $lang->global['ratio'] . '</b> ' . strip_tags ($Ratio);
  $OnMouseOver = '' . 'onmouseover="ddrivetip(\'' . $UserStats . '\', 200)"; onmouseout="hideddrivetip()" ';
  $username = ($CURUSER['username'] ? '<a ' . $OnMouseOver . 'href="' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '" alt="' . $CURUSER['username'] . '">' . get_user_color ($CURUSER['username'], $usergroups['namestyle']) . '</a> (' . ($CURUSER['title'] ? htmlspecialchars_uni ($CURUSER['title']) : get_user_color ($usergroups['title'], $usergroups['namestyle'])) . ') ' . ($CURUSER['donor'] == 'yes' ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'star.gif" alt="' . $lang->global['imgdonated'] . '" title="' . $lang->global['imgdonated'] . '" border="0" class="inlineimg" />' : '') . (($CURUSER['warned'] == 'yes' OR $CURUSER['leechwarn'] == 'yes') ? ' <img src="' . $BASEURL . '/' . $pic_base_url . 'warned.gif" alt="' . $lang->global['imgwarned'] . '" title="' . $lang->global['imgwarned'] . '" border="0" class="inlineimg" />' : '') : $lang->global['guest']);
  $showcommentstable .= '<br />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tbody>
			<tr>
				<td colspan="2" class="subheader">
					<div style="float: right;"></div>
					<div style="float: left;"><a name="cid' . $cid . '" id="cid' . $cid . '"></a><a href="#cid' . $cid . '">#' . ($ts_perpage + 1) . '</a> by ' . $username . ' ' . my_datee ($dateformat, TIMENOW) . ' ' . my_datee ($timeformat, TIMENOW) . '</div>
				</td>
			</tr>
			<tr>
				<td align="center" valign="top" height="1%" width="1%">
					' . get_user_avatar ($CURUSER['avatar'], false, 100, 100) . '
				</td>
				<td align="left" valign="top">
					<div id="post_message_' . $cid . '" style="display: inline;">' . $textbody . '</div>
					' . $signature . '
				</td>
			</tr>
			<tr>
				<td align="center" height="32" width="100">' . $IsUserOnline . $SendPM . '</td>
				<td><div style="float: right;">' . $p_report . ' ' . $p_delete . ' ' . $p_edit . ' ' . $p_quote . '</div>' . $p_commenthistory . '</td>
			</tr>
		</tbody>
	</table>
	';
  exit ($showcommentstable);
?>
