<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_fetch_start_end_total_array ($pagenumber, $perpage, $total)
  {
    $first = $perpage * ($pagenumber - 1);
    $last = $first + $perpage;
    if ($total < $last)
    {
      $last = $total;
    }

    ++$first;
    return array ('first' => ts_number_format ($first), 'last' => ts_number_format ($last));
  }

  function ts_number_format ($number, $decimals = 0, $bytesize = false, $decimalsep = null, $thousandsep = null)
  {
    $type = '';
    if (empty ($number))
    {
      return 0;
    }

    if (preg_match ('#^(\\d+(?:\\.\\d+)?)(?>\\s*)([mkg])b?$#i', trim ($number), $matches))
    {
      switch (strtolower ($matches[2]))
      {
        case 'g':
        {
          $number = $matches[1] * 1073741824;
          break;
        }

        case 'm':
        {
          $number = $matches[1] * 1048576;
          break;
        }

        case 'k':
        {
          $number = $matches[1] * 1024;
          break;
        }

        default:
        {
          $number = $matches[1] * 1;
        }
      }
    }

    if ($bytesize)
    {
      if (1073741824 <= $number)
      {
        $number = $number / 1073741824;
        $decimals = 2;
        $type = ' GB';
      }
      else
      {
        if (1048576 <= $number)
        {
          $number = $number / 1048576;
          $decimals = 2;
          $type = ' MB';
        }
        else
        {
          if (1024 <= $number)
          {
            $number = $number / 1024;
            $decimals = 1;
            $type = ' KB';
          }
          else
          {
            $decimals = 0;
            $type = ' B';
          }
        }
      }
    }

    if ($decimalsep === null)
    {
      $decimalsep = '.';
    }

    if ($thousandsep === null)
    {
      $thousandsep = ',';
    }

    return str_replace ('_', '&nbsp;', number_format ($number, $decimals, $decimalsep, $thousandsep)) . $type;
  }

  function ts_construct_page_nav ($pagenumber, $perpage, $results, $address, $address2 = '', $anchor = '')
  {
    global $show;
    $curpage = 0;
    $pagenav = '';
    $firstlink = '';
    $prevlink = '';
    $lastlink = '';
    $nextlink = '';
    if ($results <= $perpage)
    {
      $show['pagenav'] = false;
      return '';
    }

    $show['pagenav'] = true;
    $total = ts_number_format ($results);
    $totalpages = ceil ($results / $perpage);
    $show['prev'] = false;
    $show['next'] = false;
    $show['first'] = false;
    $show['last'] = false;
    if (1 < $pagenumber)
    {
      $prevpage = $pagenumber - 1;
      $prevnumbers = ts_fetch_start_end_total_array ($prevpage, $perpage, $results);
      $show['prev'] = true;
    }

    if ($pagenumber < $totalpages)
    {
      $nextpage = $pagenumber + 1;
      $nextnumbers = ts_fetch_start_end_total_array ($nextpage, $perpage, $results);
      $show['next'] = true;
    }

    $pagenavpages = '3';
    if (!is_array ($pagenavsarr))
    {
      $pagenavs = '10 50 100 500 1000';
      $pagenavsarr[] = preg_split ('#\\s+#s', $pagenavs, 0 - 1, PREG_SPLIT_NO_EMPTY);
    }

    while ($curpage++ < $totalpages)
    {
      if (($pagenavpages <= abs ($curpage - $pagenumber) AND $pagenavpages != 0))
      {
        if ($curpage == 1)
        {
          $firstnumbers = ts_fetch_start_end_total_array (1, $perpage, $results);
          $show['first'] = true;
        }

        if ($curpage == $totalpages)
        {
          $lastnumbers = ts_fetch_start_end_total_array ($totalpages, $perpage, $results);
          $show['last'] = true;
        }

        if (((in_array (abs ($curpage - $pagenumber), $pagenavsarr) AND $curpage != 1) AND $curpage != $totalpages))
        {
          $pagenumbers = ts_fetch_start_end_total_array ($curpage, $perpage, $results);
          $relpage = $curpage - $pagenumber;
          if (0 < $relpage)
          {
            $relpage = '+' . $relpage;
          }

          $pagenav .= '<td class="alt1"><a class="smallfont" href="' . $address . $address2 . ($curpage != 1 ? '&amp;page=' . $curpage : '') . ($anchor ? '#' . $anchor : '') . '" title="Show results ' . $pagenumbers['first'] . ' to ' . $pagenumbers['last'] . ' of ' . $total . '"><!--' . $relpage . '-->' . $curpage . '</a></td>';
          continue;
        }

        continue;
      }
      else
      {
        if ($curpage == $pagenumber)
        {
          $numbers = ts_fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '<td class="alt2"><span class="smallfont" title="Showing results ' . $numbers['first'] . ' to ' . $numbers['last'] . ' of ' . $total . '"><strong>' . $curpage . '</strong></span></td>';
          continue;
        }
        else
        {
          $pagenumbers = ts_fetch_start_end_total_array ($curpage, $perpage, $results);
          $pagenav .= '<td class="alt1"><a class="smallfont" href="' . $address . $address2 . ($curpage != 1 ? '&amp;page=' . $curpage : '') . ($anchor ? '#' . $anchor : '') . '" title="Show results ' . $pagenumbers['first'] . ' to ' . $pagenumbers['last'] . ' of ' . $total . '"><!--' . $relpage . '-->' . $curpage . '</a></td>';
          continue;
        }

        continue;
      }
    }

    $pagenav = '
	<div class="pagenav" align="right">
		<table class="tborder" cellpadding="1" cellspacing="0" border="0" width="100">
			<tr>
				<td style="font-weight:normal">Page ' . $pagenumber . ' of ' . $totalpages . '</td>
				' . ($show['first'] ? '<td class="alt1" nowrap="nowrap"><a class="smallfont" href="' . $address . $address2 . ($anchor ? '#' . $anchor : '') . '" title="First Page - Show results ' . $firstnumbers['first'] . ' to ' . $firstnumbers['last'] . ' of ' . $total . '"><strong>&laquo;</strong> First</a></td>' : '') . '
				' . ($show['prev'] ? '<td class="alt1"><a class="smallfont" href="' . $address . $address2 . ($prevpage != 1 ? '&amp;page=' . $prevpage : '') . ($anchor ? '#' . $anchor : '') . '" title="Prev Page - Show results ' . $prevnumbers['first'] . ' to ' . $prevnumbers['last'] . ' of ' . $total . '">&lt;</a></td>' : '') . '
				' . $pagenav . '
				' . ($show['next'] ? '<td class="alt1"><a class="smallfont" href="' . $address . $address2 . '&amp;page=' . $nextpage . ($anchor ? '#' . $anchor : '') . '" title="Next Page - Show results ' . $nextnumbers['first'] . ' to ' . $nextnumbers['last'] . ' of ' . $total . '">&gt;</a></td>' : '') . '
				' . ($show['last'] ? '<td class="alt1" nowrap="nowrap"><a class="smallfont" href="' . $address . $address2 . '&amp;page=' . $totalpages . ($anchor ? '#' . $anchor : '') . '" title="Last Page - Show results ' . $lastnumbers['first'] . ' to ' . $lastnumbers['last'] . ' of ' . $total . '">Last <strong>&raquo;</strong></a></td>' : '') . '
				' . ($show['popups'] ? '<td class="vbmenu_control" title="' . $address . $address2 . '"><a name="PageNav"></a></td>' : '') . '
			</tr>
		</table>
	</div>';
    return $pagenav;
  }

  function sanitize_maxposts ($perpage = 0, $type = 'post')
  {
    global $CURUSER;
    global $ts_perpage;
    global $f_postsperpage;
    $usermaxposts = '5,10,20,30,40';
    $max = intval (max (explode (',', $usermaxposts)));
    $maxposts = $f_postsperpage;
    if ($type != 'post')
    {
      $maxposts = $ts_perpage;
      $CURUSER['postsperpage'] = $CURUSER['torrentsperpage'];
    }

    if (($max AND $CURUSER['postsperpage']))
    {
      if (!$perpage)
      {
        return ($CURUSER['postsperpage'] == 0 ? $maxposts : $CURUSER['postsperpage']);
      }

      if ($perpage == 0 - 1)
      {
        return $max;
      }

      return ($max < $perpage ? $max : $perpage);
    }

    if (!empty ($maxposts))
    {
      return $maxposts;
    }

    return 10;
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
