<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function fetch_timezone ($offset = 'all')
  {
    $timezones = array ('-12' => 'timezone_gmt_minus_1200', '-11' => 'timezone_gmt_minus_1100', '-10' => 'timezone_gmt_minus_1000', '-9' => 'timezone_gmt_minus_0900', '-8' => 'timezone_gmt_minus_0800', '-7' => 'timezone_gmt_minus_0700', '-6' => 'timezone_gmt_minus_0600', '-5' => 'timezone_gmt_minus_0500', '-4.5' => 'timezone_gmt_minus_0430', '-4' => 'timezone_gmt_minus_0400', '-3.5' => 'timezone_gmt_minus_0330', '-3' => 'timezone_gmt_minus_0300', '-2' => 'timezone_gmt_minus_0200', '-1' => 'timezone_gmt_minus_0100', '0' => 'timezone_gmt_plus_0000', '1' => 'timezone_gmt_plus_0100', '2' => 'timezone_gmt_plus_0200', '3' => 'timezone_gmt_plus_0300', '3.5' => 'timezone_gmt_plus_0330', '4' => 'timezone_gmt_plus_0400', '4.5' => 'timezone_gmt_plus_0430', '5' => 'timezone_gmt_plus_0500', '5.5' => 'timezone_gmt_plus_0530', '5.75' => 'timezone_gmt_plus_0545', '6' => 'timezone_gmt_plus_0600', '6.5' => 'timezone_gmt_plus_0630', '7' => 'timezone_gmt_plus_0700', '8' => 'timezone_gmt_plus_0800', '9' => 'timezone_gmt_plus_0900', '9.5' => 'timezone_gmt_plus_0930', '10' => 'timezone_gmt_plus_1000', '11' => 'timezone_gmt_plus_1100', '12' => 'timezone_gmt_plus_1200');
    return ($offset == 'all' ? $timezones : $timezones['' . $offset]);
  }

  function show_timezone ($tzoffset = 0, $autodst = 0, $dst = 0)
  {
    global $lang;
    $timezoneoptions = '';
    foreach (fetch_timezone () as $optionvalue => $timezonephrase)
    {
      $timezoneoptions .= '<option value="' . $optionvalue . '"' . ($tzoffset == $optionvalue ? ' selected="selected"' : '') . '>' . $lang->timezone['' . $timezonephrase] . '</option>';
    }

    $selectdst = array ();
    if ($autodst)
    {
      $selectdst[2] = ' selected="selected"';
    }
    else
    {
      if ($dst)
      {
        $selectdst[1] = ' selected="selected"';
      }
      else
      {
        $selectdst[0] = ' selected="selected"';
      }
    }

    return '
	<fieldset class="fieldset">
		<legend><label for="sel_tzoffset">' . $lang->timezone['time_zone'] . '</label></legend>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="none">' . $lang->timezone['time_auto_corrected_to_location'] . '</td>
		</tr>
		<tr>
			<td class="none">
				<span style="float:right">
				<select name="tzoffset" id="sel_tzoffset">
					' . $timezoneoptions . '
				</select>
				</span>
				<label for="sel_tzoffset"><b>' . $lang->timezone['time_zone'] . ':</b></label>
			</td>
		</tr>
		<tr>
			<td class="none">' . $lang->timezone['allow_daylight_savings_time'] . '</td>
		</tr>
		<tr>
			<td class="none">
				<span style="float:right">
				<select name="dst" id="sel_dst">
					<option value="2"' . (isset ($selectdst[2]) ? $selectdst[2] : '') . '>' . $lang->timezone['dstauto'] . '</option>
					<option value="1"' . (isset ($selectdst[1]) ? $selectdst[1] : '') . '>' . $lang->timezone['dston'] . '</option>
					<option value="0"' . (isset ($selectdst[0]) ? $selectdst[0] : '') . '>' . $lang->timezone['dstoff'] . '</option>
				</select>
				</span>
				<label for="sel_dst"><b>' . $lang->timezone['dst_correction_option'] . ':</b></label>
			</td>
		</tr>
		</table>
	</fieldset>
	';
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $lang->load ('timezone');
?>
