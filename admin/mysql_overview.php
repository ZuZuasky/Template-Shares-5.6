<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function formatbytedown ($value, $limes = 2, $comma = 0)
  {
    $dh = pow (10, $comma);
    $li = pow (10, $limes);
    $return_value = $value;
    $unit = $GLOBALS['byteUnits'][0];
    $d = 6;
    $ex = 15;
    while (1 <= $d)
    {
      if ((isset ($GLOBALS['byteUnits'][$d]) AND $li * pow (10, $ex) <= $value))
      {
        $value = round ($value / (pow (1024, $d) / $dh)) / $dh;
        $unit = $GLOBALS['byteUnits'][$d];
        break;
      }

      --$d;
      $ex -= 3;
    }

    if ($unit != $GLOBALS['byteUnits'][0])
    {
      $return_value = number_format ($value, $comma, '.', ',');
    }
    else
    {
      $return_value = number_format ($value, 0, '.', ',');
    }

    return array ($return_value, $unit);
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ((isset ($_GET['Do']) AND isset ($_GET['table'])))
  {
    $Do = ($_GET['Do'] === 'T' ? sqlesc ($_GET['Do']) : '');
    if (!ereg ('[^A-Za-z_]+', $_GET['table']))
    {
      $Table = '`' . $_GET['table'] . '`';
    }
    else
    {
      print_no_permission (true);
    }

    $sql = '' . 'OPTIMIZE TABLE ' . $Table;
    if (preg_match ('@^(CHECK|ANALYZE|REPAIR|OPTIMIZE)[[:space:]]TABLE[[:space:]]' . $Table . '$@i', $sql))
    {
      if (!(@sql_query ($sql)))
      {
        exit ('<b>Something was not right!</b>.
<br />Query: ' . $sql . '<br />
Error: (' . mysql_errno () . ') ' . htmlspecialchars (mysql_error ()));
        ;
      }

      $return_url = $_this_script_ . '&Do=F';
      header ('Location:  ' . $_this_script_ . '&Do=F');
      exit ();
    }
  }

  $GLOBALS['byteUnits'] = array ('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
  stdhead ('Stats');
  echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
  echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Mysql Server Table Status</td></tr>';
  echo '
<!-- Start table -->

<table id="torrenttable" border="1" cellpadding="5" width="100%">

<!-- Start table headers -->
<tr>


<td class=subheader align=left>Name</td>

<td class=subheader align=right>Size</td>

<td class=subheader align=right>Rows</td>

<td class=subheader align=right>Avg row length</td>

<td class=subheader align=right>Data length</td>

<!-- <td>Max_data_length</td> -->

<td c';
  echo 'lass=subheader align=right>Index length</td>

<td class=subheader align=right>Overhead</td>

<!-- <td>Auto_increment</td> -->

<!-- <td>Timings</td> -->

</tr>

<!-- End table headers -->

';
  $count = 0;
  if (!($res = @sql_query ('SHOW TABLE STATUS FROM `' . $mysql_db . '`')))
  {
    exit (mysql_error ());
    ;
  }

  while ($row = mysql_fetch_array ($res))
  {
    list ($formatted_Avg, $formatted_Abytes) = formatbytedown ($row['Avg_row_length']);
    list ($formatted_Dlength, $formatted_Dbytes) = formatbytedown ($row['Data_length']);
    list ($formatted_Ilength, $formatted_Ibytes) = formatbytedown ($row['Index_length']);
    list ($formatted_Dfree, $formatted_Fbytes) = formatbytedown ($row['Data_free']);
    $tablesize = $row['Data_length'] + $row['Index_length'];
    list ($formatted_Tsize, $formatted_Tbytes) = formatbytedown ($tablesize, 3, (0 < $tablesize ? 1 : 0));
    $thispage = '&Do=T&table=' . $row['Name'];
    $overhead = (0 < $formatted_Dfree ? '<a href=' . $_this_script_ . $thispage . '><font color=\'red\'><b>' . $formatted_Dfree . ' ' . $formatted_Fbytes . '</b></font></a>' : $formatted_Dfree . ' ' . $formatted_Fbytes);
    echo '' . '<tr align="right"><td align="left">' . $row['Name'] . '</td>' . ('' . '<td>' . $formatted_Tsize . ' ' . $formatted_Tbytes . '</td>') . ('' . '<td>' . $row['Rows'] . '</td>') . ('' . '<td>' . $formatted_Avg . ' ' . $formatted_Abytes . '</td>') . ('' . '<td>' . $formatted_Dlength . ' ' . $formatted_Dbytes . '</td>') . ('' . '<td>' . $formatted_Ilength . ' ' . $formatted_Ibytes . '</td>') . ('' . '<td>' . $overhead . '</td></tr>') . ('' . '<tr><td colspan="7" align="right"><i><b>Row Format:</b></i> ' . $row['Row_format']) . ('' . '<br /><i><b>Create Time:</b></i> ' . $row['Create_time']) . ('' . '<br /><i><b>Update Time:</b></i> ' . $row['Update_time']) . ('' . '<br /><i><b>Check Time:</b></i> ' . $row['Check_time'] . '</td></tr>');
    ++$count;
  }

  echo '' . '<tr><td><b>Tables: ' . $count . '</b></td><td colspan="6" align="right">If it\'s <font color="red"><b>RED</b></font> it probably needs optimising!!</td></tr>';
  echo '<!-- End table -->
</table></table></table>

';
  stdfoot ();
?>
