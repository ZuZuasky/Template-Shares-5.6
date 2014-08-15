<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_msg ($message = '', $error = false)
  {
    global $shoutboxcharset;
    header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
    header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
    header ('Cache-Control: no-cache, must-revalidate');
    header ('Pragma: no-cache');
    header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
    if ($error)
    {
      exit ('<error>' . $message . '</error>');
    }

    exit ($message);
  }

  function sgpermission ($Option)
  {
    global $usergroups;
    $Options = array ('canview' => '0', 'cancreate' => '1', 'canpost' => '2', 'candelete' => '3', 'canjoin' => '4', 'canedit' => '5', 'canmanagemsg' => '6', 'canmanagegroup' => '7');
    $What = (isset ($Options[$Option]) ? $Options[$Option] : 0);
    return ($usergroups['sgperms'][$What] == '1' ? true : false);
  }

  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  require 'global.php';
  gzip ();
  dbconn ();
  define ('TS_AJAX_VERSION', '1.2.0 ');
  define ('NcodeImageResizer', true);
  if (((!defined ('IN_SCRIPT_TSSEv56') OR strtoupper ($_SERVER['REQUEST_METHOD']) != 'POST') OR !$CURUSER))
  {
    exit ();
  }

  $do = (isset ($_POST['do']) ? trim ($_POST['do']) : '');
  $groupid = (isset ($_POST['groupid']) ? intval ($_POST['groupid']) : 0);
  if (!is_valid_id ($groupid))
  {
    show_msg ($lang->ts_social_groups['error1'], true);
  }

  $query = sql_query ('SELECT groupid FROM ts_social_groups WHERE groupid = ' . sqlesc ($groupid));
  if (mysql_num_rows ($query) == 0)
  {
    show_msg ($lang->ts_social_groups['error1'], true);
  }

  $lang->load ('ts_social_groups');
  $query = sql_query ('SELECT userid FROM ts_social_group_members WHERE userid = ' . sqlesc ($CURUSER['id']) . ' AND type = \'public\'');
  if (mysql_num_rows ($query) == 0)
  {
    show_msg ($lang->ts_social_groups['error7'], true);
  }

  if ((($do == 'save_sgm' AND sgpermission ('canpost')) AND sgpermission ('canview')))
  {
    $text = urldecode ($_POST['message']);
    $text = strval ($text);
    if (strtolower ($shoutboxcharset) != 'utf-8')
    {
      if (function_exists ('iconv'))
      {
        $text = iconv ('UTF-8', $shoutboxcharset, $text);
      }
      else
      {
        if (function_exists ('mb_convert_encoding'))
        {
          $text = mb_convert_encoding ($text, $shoutboxcharset, 'UTF-8');
        }
        else
        {
          if (strtolower ($shoutboxcharset) == 'iso-8859-1')
          {
            $text = utf8_decode ($text);
          }
        }
      }
    }

    if ((!$text OR strlen ($text) < 3))
    {
      show_msg ($lang->ts_social_groups['error1'], true);
    }

    $userid = intval ($CURUSER['id']);
    $posted = time ();
    if (!(sql_query ('INSERT INTO ts_social_group_messages (groupid, userid, posted, message) VALUES (' . sqlesc ($groupid) . ', ' . sqlesc ($CURUSER['id']) . ', ' . sqlesc ($posted) . ', ' . sqlesc ($text) . ')')))
    {
      exit ();
      ;
    }

    $mid = mysql_insert_id ();
    if ((mysql_affected_rows () AND $mid))
    {
      sql_query ('UPDATE ts_social_groups SET messages = messages + 1, lastpostdate = \'' . $posted . '\', lastposter = \'' . $CURUSER['id'] . '\' WHERE groupid = ' . sqlesc ($groupid));
    }

    $ULink = '<a href="' . ts_seo ($CURUSER['id'], $CURUSER['username']) . '">' . get_user_color ($CURUSER['username'], $usergroups['namestyle']) . '</a>';
    $UAvatar = get_user_avatar ($CURUSER['avatar'], true, '80', '80');
    $UMsg = format_comment ($text);
    $Posted = my_datee ($dateformat, $posted) . ' ' . my_datee ($timeformat, $posted);
    show_msg ('
	<tr>
		<td class="none">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<th rowspan="2" class="none" width="80" height="80" valign="top">
						' . $UAvatar . '
					</th>
					<td class="none" valign="top">
						<div class="subheader">' . sprintf ($lang->ts_social_groups['by2'], $Posted, $ULink) . '</div>
					</td>
				</tr>
				<tr>
					<td class="none" valign="top">
						<div id="message_' . $mid . '" name="message_' . $mid . '">
							' . $UMsg . '
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	');
  }

?>
