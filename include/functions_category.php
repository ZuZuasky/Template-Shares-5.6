<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_category_list ($selectname = 'type', $selected = 0, $extra = '', $style = 'specialboxn')
  {
    global $usergroups;
    global $cache;
    global $_categoriesS;
    global $_categoriesC;
    $subcategoriesss = array ();
    if ((count ($_categoriesS) == 0 OR count ($_categoriesC) == 0))
    {
      require TSDIR . '/' . $cache . '/categories.php';
    }

    if ((is_array ($_categoriesS) AND 0 < count ($_categoriesS)))
    {
      foreach ($_categoriesS as $scquery)
      {
        if (($usergroups['canviewviptorrents'] != 'yes' AND $scquery['vip'] == 'yes'))
        {
          continue;
        }

        $subcategoriesss[$scquery['pid']] = (isset ($subcategoriesss[$scquery['pid']]) ? $subcategoriesss[$scquery['pid']] : '') . '
					<option value="' . $scquery['id'] . '"' . ($scquery['id'] == $selected ? ' selected="selected"' : '') . '>&nbsp;&nbsp;|-- ' . $scquery['name'] . '</option>
					';
      }
    }

    $showcategories = '<select name="' . $selectname . '" id="' . $style . '">
	' . $extra;
    if ((is_array ($_categoriesC) AND 0 < count ($_categoriesC)))
    {
      foreach ($_categoriesC as $mcquery)
      {
        if (($usergroups['canviewviptorrents'] != 'yes' AND $mcquery['vip'] == 'yes'))
        {
          continue;
        }

        $showcategories .= '
				<option value="' . $mcquery['id'] . '"' . ($mcquery['id'] == $selected ? ' selected="selected"' : '') . ' style="color:red;">' . $mcquery['name'] . '</option>
				' . (isset ($subcategoriesss[$mcquery['id']]) ? $subcategoriesss[$mcquery['id']] : '') . '
				';
      }
    }

    $showcategories .= '</select>';
    return $showcategories;
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
