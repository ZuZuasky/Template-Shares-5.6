<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_ann ($message)
  {
    global $shoutboxcharset;
    echo '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=' . $shoutboxcharset . '" />
			<title></title>
			<style>
				body
				{
					background: #EEEEEE;
					color: #333333;
					font: 10pt verdana, geneva, lucida, \'lucida grande\', arial, helvetica, sans-serif;
					margin: 0px 5px 0px 5px;
				}
				a:link, body_alink
				{
					color: #000000;
					text-decoration: none;
				}
				a:visited, body_avisited
				{
					color: #000000;
					text-decoration: none;
				}
				a:hover, a:active, body_ahover
				{
					color: #4E72A2;
					text-decoration: underline;
				}
			</style>
		</head>
		<body>
			' . $message . '
		</body>
</html>';
  }

  require_once 'global.php';
  define ('CA_VERSION', '0.5');
  dbconn ();
  if ((((!$CURUSER OR $usergroups['isbanned'] == 'yes') OR $CURUSER['status'] != 'confirmed') OR $CURUSER['enabled'] != 'yes'))
  {
    exit ();
  }

  $lang->load ('clear_ann');
  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
  if (($CURUSER AND $CURUSER['announce_read'] == 'no'))
  {
    $res = sql_query ('SELECT subject,message,added,`by` FROM announcements WHERE minclassread IN (0,' . $CURUSER['usergroup'] . ') ORDER by added DESC LIMIT 1');
    if (0 < mysql_num_rows ($res))
    {
      $arr = mysql_fetch_assoc ($res);
      sql_query ('UPDATE users SET announce_read = \'yes\' WHERE announce_read = \'no\' AND id = ' . sqlesc ($CURUSER['id']));
      show_ann ('<b>' . str_replace ('&amp;', '&', htmlspecialchars_uni ($arr['subject'])) . ' - ' . htmlspecialchars_uni ($arr['by']) . ' - ' . my_datee ($dateformat, $arr['added']) . ', ' . my_datee ($timeformat, $arr['added']) . '</b><br /><br />' . format_comment ($arr['message']));
      if (mysql_affected_rows ())
      {
        exit ($lang->clear_ann['cleared']);
        return 1;
      }
    }
  }
  else
  {
    show_ann ($lang->clear_ann['noann']);
  }

?>
