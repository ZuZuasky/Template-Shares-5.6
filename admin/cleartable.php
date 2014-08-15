<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('CT_VERSION', '0.4 by xam');
  if (((isset ($_GET['do']) AND $_GET['do'] == 'clear') AND (0 < count ($_POST['tablenames']) OR isset ($_GET['tablehash']))))
  {
    $tables = $_POST['tablenames'];
    if (!isset ($_GET['sure']))
    {
      $tablehash = base64_encode (implode (':', $tables));
      stderr ('Sanity check', 'We STRONGLY recommend backup before Truncate any table.
		<br />Are you sure you want to Truncate following tables?
		<br /><br />Selected Table(s): ' . implode (', ', $tables) . '
		<br /><br /><a href="' . $_this_script_ . '&amp;do=clear&amp;sure=true&amp;tablehash=' . $tablehash . '">Yes, I am sure</a>, <a href="' . $_this_script_ . '">No, go back!</a>', false);
    }

    $tablehash = explode (':', base64_decode ($_GET['tablehash']));
    if (((!empty ($tablehash) AND 0 < count ($tablehash)) AND is_array ($tablehash)))
    {
      $str;
      foreach ($tablehash as $table)
      {
        if (!empty ($table))
        {
          $str .= '' . '
				Table \'<b>' . $table . '</b>\' has been emptied!<br />';
          (sql_query ('' . 'TRUNCATE TABLE `' . $table . '`') OR sqlerr (__FILE__, 42));
          continue;
        }
      }

      stdhead ('TRUNCATE Mysql Tables');
      _form_header_open_ ('TRUNCATE Mysql Tables');
      echo '
		<tr>
			<td>
				' . $str . '
				<br />
				Total ' . count ($tablehash) . ' table(s) has been emptied!
				<br /><br />				
				Please do not forget to optimize tables now! (Click <a href="' . $BASEURL . '/admin/managesettings.php?do=dboptimize">here</a> to optimize tables)
			</td>
		</tr>';
      _form_header_close_ ();
      stdfoot ();
      exit ();
    }
  }

  ($result = sql_query ('' . 'SHOW TABLES FROM ' . $mysql_db) OR sqlerr (__FILE__, 63));
  while ($row = mysql_fetch_row ($result))
  {
    $options .= '
	<option value="' . $row[0] . '">' . $row[0] . '</option>
	';
  }

  mysql_free_result ($result);
  stdhead ('TRUNCATE Mysql Tables');
  _form_header_open_ ('TRUNCATE Mysql Tables');
  echo '
<tr>
	<td>
		Please select a table from the below to TRUNCATE. Use the CTRL key to select multiple tables.
		<form method="post" action="' . $_this_script_ . '&amp;do=clear">
		<select name="tablenames[]" size="15" multiple="multiple" style="width: 450px;">
		' . $options . '
		</select>
		<p>&nbsp;</p>
		<input type="submit" value="TRUNCATE Selected Tables">
		</form>
	</td>
</tr>
';
  _form_header_close_ ();
  stdfoot ();
?>
