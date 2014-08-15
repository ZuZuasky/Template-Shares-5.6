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
  define ('R_VERSION', '0.6 ');
  define ('NcodeImageResizer', true);
  include_once INC_PATH . '/functions_security.php';
  stdhead ();
  $res = sql_query ('SELECT title,text,usergroups FROM rules ORDER BY id');
  while ($rules = mysql_fetch_assoc ($res))
  {
    if (((($rules['usergroups'] == '[0]' OR $rules['usergroups'] == '0') OR ($CURUSER AND ($CURUSER['usergroup'] === $rules['usergroups'] OR '[' . $CURUSER['usergroup'] . '].' === $rules['usergroups']))) OR ($CURUSER['usergroup'] AND preg_match ('#\\[' . $CURUSER['usergroup'] . '\\]#U', $rules['usergroups']))))
    {
      echo '
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td class="thead">' . $rules['title'] . '</td>
			</tr>
			<tr>
				<td align="left">' . format_comment ($rules['text']) . '</td>
			</tr>
		</table>
		';
      continue;
    }
    else
    {
      continue;
    }
  }

  stdfoot ();
?>
