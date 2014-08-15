<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('TSF_FORUMS_TSSEv56', true);
  require_once 'global.php';
  if ((!defined ('IN_SCRIPT_TSSEv56') OR !defined ('TSF_FORUMS_GLOBAL_TSSEv56')))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ((isset ($_GET['aid']) AND is_valid_id ($_GET['aid'])))
  {
    $aid = intval ($_GET['aid']);
  }
  else
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalidaid']);
    exit ();
  }

  ($query = sql_query ('SELECT a.*, u.id, u.username, g.namestyle, g.title as usergrouptitle FROM ' . TSF_PREFIX . 'announcement a LEFT JOIN users u ON (a.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE a.announcementid = ' . sqlesc ($aid)) OR sqlerr (__FILE__, 42));
  if (mysql_num_rows ($query) == 0)
  {
    stderr ($lang->global['error'], $lang->tsf_forums['invalidaid']);
    exit ();
  }

  $a = mysql_fetch_assoc ($query);
  $defaulttemplate = ts_template ();
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=';
  echo $charset;
  echo '" />
<link rel="stylesheet" href="';
  echo $BASEURL;
  echo '/include/templates/';
  echo $defaulttemplate;
  echo '/style/style.css" type="text/css" media="screen" />
<title>';
  echo $SITENAME;
  echo '</title>
';
  echo '<s';
  echo 'cript type="text/javascript">
	function to_old_win(url)
	{
		setInterval("window.close()",3000);
		opener.location.href = url;
	}
</script>
</head>

<body>
';
  echo '
<table border="0" cellspacing="0" cellpadding="4" class="none" style="clear: both;" width="100%">
	<tr>
		<td class="thead" colspan="2">' . $lang->tsf_forums['atitle'] . '</td>
	</tr>
	<tr>
		<td class="alt1" width="5%" align="center"><img src="' . $BASEURL . '/tsf_forums/images/announcement_new.gif" border="0" alt="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '" title="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '"></td>
		<td class="alt2" colspan="6">
			<div>
				<span class="smallfont" style="float: right;">' . $lang->tsf_forums['views'] . ': <strong>' . $a['views'] . '</strong> <img class="inlineimg" src="' . $BASEURL . '/' . $pic_base_url . 'comments2.gif" alt="" border="0"></span>
				<strong>' . $lang->tsf_forums['announcements'] . '</strong> ' . htmlspecialchars_uni ($a['title']) . '

			</div>
			<div>
				<span style="float: right;"><span class="smallfont">' . my_datee ($dateformat, $a['posted']) . ' ' . my_datee ($timeformat, $a['posted']) . '</span></span>
				<span class="smallfont"><a href="#" onClick="to_old_win(\'' . ts_seo ($a['id'], $a['username']) . '\')">' . get_user_color ($a['username'], $a['namestyle']) . '</a> (' . $a['usergrouptitle'] . ')</span>
			</div>
		</td>
	</tr>
	<tr>
	<td class="alt1" width="5%" align="center" valign="top"><img src="' . $BASEURL . '/tsf_forums/images/announcement_old.gif" border="0" alt="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '" title="' . $lang->tsf_forums['announcements'] . htmlspecialchars_uni ($a['title']) . '"></td>
	<td align="left">' . $a['pagetext'] . '</td>
	</tr>
</table>
</body>
</html>';
  (sql_query ('UPDATE ' . TSF_PREFIX . 'announcement SET views = views + 1 WHERE announcementid = ' . sqlesc ($aid)) OR sqlerr (__FILE__, 95));
?>
