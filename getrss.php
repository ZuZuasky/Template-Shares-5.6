<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('R_VERSION', 'v1.7');
  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  $lang->load ('getrss');
  $allowed_timezones = array ('-12', '-11', '-10', '-9', '-8', '-7', '-6', '-5', '-4', '-3.5', '-3', '-2', '-1', '0', '1', '2', '3', '3.5', '4', '4.5', '5', '5.5', '6', '7', '8', '9', '9.5', '10', '11', '12');
  $allowed_showrows = array ('5', '10', '20', '30', '40', '50');
  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $_queries_ = array ();
    $link = $BASEURL . '/rss.php?secret_key=' . $CURUSER['passkey'] . '&';
    if ($_POST['feedtype'] == 'download')
    {
      $_queries_[] = 'feedtype=download';
    }
    else
    {
      $_queries_[] = 'feedtype=details';
    }

    if ((isset ($_POST['timezone']) AND in_array ($_POST['timezone'], $allowed_timezones, 1)))
    {
      $_queries_[] = 'timezone=' . (int)$_POST['timezone'];
    }
    else
    {
      $_queries_[] = 'timezone=1';
    }

    if ((isset ($_POST['showrows']) AND in_array ($_POST['showrows'], $allowed_showrows, 1)))
    {
      $_queries_[] = 'showrows=' . (int)$_POST['showrows'];
    }
    else
    {
      $_queries_[] = 'showrows=20';
    }

    if (isset ($_POST['showall']))
    {
      $_queries_[] = 'categories=all';
    }
    else
    {
      $sqlquery = sql_query ('SELECT id FROM categories WHERE type = \'c\'');
      while ($res = mysql_fetch_assoc ($sqlquery))
      {
        if ($_POST['cat' . $res['id'] . ''] == 'yes')
        {
          if (!is_array ($_POST['cat']))
          {
            $_POST['cat'] = array ();
          }

          array_push ($_POST['cat'], $res['id']);
          continue;
        }
      }

      if (isset ($_POST['cat']))
      {
        $_queries_[] = 'categories=' . implode (',', (array)$_POST['cat']);
      }
      else
      {
        $_queries_[] = 'categories=all';
      }
    }

    $__queries = implode ('&', $_queries_);
    if ($__queries)
    {
      $link .= $__queries;
    }

    stdhead ($lang->getrss['title']);
    echo '
	<table border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">' . $lang->getrss['done2'] . '</td>
		</tr>
		<tr>
			<td class=""><div style="border: thin inset; padding: 2px; overflow: auto;"><b>' . htmlspecialchars ($link) . '</b></div></td>
		</tr>
	</table>
	';
    stdfoot ();
    exit ();
  }

  stdhead ($lang->getrss['title']);
  include_once INC_PATH . '/functions_category2.php';
  $catoptions = ts_category_list2 (2, 'rss');
  echo '<FORM method="post" action="';
  echo $_SERVER['SCRIPT_NAME'];
  echo '" name="rss">
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<TR><TD class="thead" colspan="2">';
  echo $lang->getrss['title'];
  echo '</td></tr>
<TR>
<TD class="rowhead">';
  echo $lang->getrss['field1'];
  echo '</TD>
<TD>
';
  echo $catoptions;
  echo '</TD>
</TR>
<TR>
<TD class="rowhead">';
  echo $lang->getrss['field3'];
  echo '</TD>
<TD>
<INPUT type="radio" name="feedtype" value="details" checked />';
  echo $lang->getrss['field4'];
  echo '<br />
<INPUT type="radio" name="feedtype" value="download"/>';
  echo $lang->getrss['field5'];
  echo '</TD>
</TR>
<tr>

<td align="right"><b>';
  echo $lang->getrss['field6'];
  echo '</b></td>
<td valign="top">';
  echo '<s';
  echo 'elect name="timezone">

    <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option>

    <option value="-11">(GMT -11:00) Midway Island, Samoa</option>

    <option value="-10">(GMT -10:00) Hawaii</option>

    <option value="-9">(GMT -9:00) Alaska</option>

    <option value="-8">(GMT -8:00) Pacific Time (US & Canada)</option>

    <option value="-7">(GMT -7:00) Mountain Time (US & Canada)';
  echo '</option>

    <option value="-6">(GMT -6:00) Central Time (US & Canada), Mexico City</option>

    <option value="-5">(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima</option>

    <option value="-4">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>

    <option value="-3.5">(GMT -3:30) Newfoundland</option>

    <option value="-3">(GMT -3:00) Brazil, Buenos Aires, Georgetown</optio';
  echo 'n>

    <option value="-2">(GMT -2:00) Mid-Atlantic</option>

    <option value="-1">(GMT -1:00 hour) Azores, Cape Verde Islands</option>

    <option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>

    <option value="1">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>

    <option value="2">(GMT +2:00) Kaliningrad, South Africa</option>

    <option value="';
  echo '3">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>

    <option value="3.5">(GMT +3:30) Tehran</option>

    <option value="4">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>

    <option value="4.5">(GMT +4:30) Kabul</option>

    <option value="5">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>

    <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New ';
  echo 'Delhi</option>

    <option value="6">(GMT +6:00) Almaty, Dhaka, Colombo</option>

    <option value="7">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>

    <option value="8">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>

    <option value="9">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>

    <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>

    <option value="10">';
  echo '(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>

    <option value="11">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>

    <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>

    </select></td></tr>
<tr><td align="right"><b>';
  echo $lang->getrss['field7'];
  echo '</b></td><td>';
  echo '<s';
  echo 'elect name="showrows">
<option value="5">5</option>
<option value="10">10</option>
<option value="20">20</option>
<option value="30">30</option>
<option value="40">40</option>
<option value="50">50</option>
</select> <BUTTON type="submit" class=button>';
  echo $lang->getrss['field8'];
  echo '</BUTTON></td></tr>
</TABLE>
</FORM>
';
  stdfoot ();
?>
