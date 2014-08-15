<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  define ('STF_VERSION', '0.6');
  define ('NcodeImageResizer', true);
  $lang->load ('contactstaff');
  $query = sql_query ('SELECT added FROM staffmessages WHERE sender = ' . sqlesc ($CURUSER['id']) . ' ORDER by added DESC LIMIT 1');
  if (0 < mysql_num_rows ($query))
  {
    $last_staffmsg = mysql_result ($query, 0, 'added');
    flood_check ($lang->contactstaff['floodcomment'], $last_staffmsg);
  }

  $msgtext = trim ($_POST['message']);
  $subject = trim ($_POST['subject']);
  $avatar = get_user_avatar ($CURUSER['avatar']);
  if (($_POST['previewpost'] AND !empty ($msgtext)))
  {
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($msgtext) . '</td>
	</tr></table><br />';
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if (isset ($_POST['submit']))
    {
      if ((empty ($msgtext) OR empty ($subject)))
      {
        stderr ($lang->global['error'], $lang->global['dontleavefieldsblank']);
      }

      $added = sqlesc (get_date_time ());
      $userid = (int)$CURUSER['id'];
      $message = sqlesc ($msgtext);
      $subject = sqlesc ($subject);
      (sql_query ('' . 'INSERT INTO staffmessages (sender, added, msg, subject) VALUES(' . $userid . ', ' . $added . ', ' . $message . ', ' . $subject . ')') OR sqlerr (__FILE__, 58));
      if (!empty ($_POST['returnto']))
      {
        $returnto = fix_url ($_POST['returnto']);
      }
      else
      {
        $returnto = 'staff.php';
      }

      redirect ($returnto, $lang->global['msgsend'], NULL, 3, false, false);
      exit ();
    }
  }

  if ((($_GET['subject'] AND $_GET['subject'] == 'invalid_link') AND ($_GET['link'] AND substr ($_GET['link'], 0, 7) == 'http://')))
  {
    $link = htmlspecialchars_uni ($_GET['link']);
    $link = str_replace ('http://referhide.com/?g=', '', $link);
    $subject = sprintf ($lang->contactstaff['invalidlink'], $link);
  }

  stdhead ($lang->contactstaff['contactstaff'], false);
  stdmsg ($lang->contactstaff['info'], NULL, false);
  $returnto = (isset ($_GET['returnto']) ? fix_url ($_GET['returnto']) : fix_url ($_SERVER['HTTP_REFERER']));
  define ('IN_EDITOR', true);
  include_once INC_PATH . '/editor.php';
  $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type="hidden" name="returnto" value="' . $returnto . '">';
  if (!empty ($prvp))
  {
    $str .= $prvp;
  }

  $str .= insert_editor (true, $subject, $msgtext, $lang->contactstaff['contactstaff'], $lang->contactstaff['sendmessage']);
  $str .= '</form>';
  echo $str;
  stdfoot ();
?>
