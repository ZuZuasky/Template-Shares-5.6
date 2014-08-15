<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_category_list2 ($type = 1, $formname = 'usercp')
  {
    global $usergroups;
    global $CURUSER;
    global $cache;
    global $_categoriesS;
    global $_categoriesC;
    if ((count ($_categoriesS) == 0 OR count ($_categoriesC) == 0))
    {
      require TSDIR . '/' . $cache . '/categories.php';
    }

    $subcategoriesss = array ();
    foreach ($_categoriesS as $scquery)
    {
      if (($usergroups['canviewviptorrents'] != 'yes' AND $scquery['vip'] == 'yes'))
      {
        continue;
      }

      $subcategoriesss[$scquery['pid']] = (isset ($subcategoriesss[$scquery['pid']]) ? $subcategoriesss[$scquery['pid']] : '') . '
				<tr>
					<td valign="top" class="none">
						<input type="checkbox" value="' . ($type == 1 ? 'yes' : $scquery['id']) . '" checkme="group' . $scquery['pid'] . '" name="' . ($type == 1 ? 'cat' . $scquery['id'] : 'cat[]') . '"' . (strpos ($CURUSER['notifs'], '[cat' . $scquery['id'] . ']') !== false ? ' checked="checked"' : '') . '> <span style="color: #000000; font-size: 11px; font-weight: normal;">' . $scquery['name'] . '</span>
					</td>
				</tr>';
    }

    $showcategories = '
	<div style="border: 1px solid #000;">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>';
    $count = 0;
    foreach ($_categoriesC as $mcquery)
    {
      if (($usergroups['canviewviptorrents'] != 'yes' AND $mcquery['vip'] == 'yes'))
      {
        continue;
      }

      if ($count % 3 == 0)
      {
        $showcategories .= '</tr><tr>';
      }

      $showcategories .= '
					<td valign="top" class="none">
						<input type="checkbox" value="yes" name="cat' . $mcquery['id'] . '"' . (strpos ($CURUSER['notifs'], '[cat' . $mcquery['id'] . ']') !== false ? ' checked="checked"' : '') . ' checkall="group' . $mcquery['id'] . '" onclick="javascript: return select_deselectAll (\'' . $formname . '\', this, \'group' . $mcquery['id'] . '\');"> <span style="color: red; font-size: 12px; font-weight: bold;">' . $mcquery['name'] . '</span>
						' . (isset ($subcategoriesss[$mcquery['id']]) ? '
						<div style="margin-left: 20px;">
							<table>
								' . $subcategoriesss[$mcquery['id']] . '
							</table>
						</span>' : '') . '
					</td>	
			';
      ++$count;
    }

    $showcategories .= '
			</tr>
		</table>
	</div>';
    return $showcategories;
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
