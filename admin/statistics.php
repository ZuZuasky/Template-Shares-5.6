<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function start_form ($hiddens = '', $name = 'theAdminForm', $js = '')
  {
    global $_this_script_;
    $form = '' . '<form action=\'' . $_this_script_ . '\' method=\'post\' name=\'' . $name . '\' ' . $js . '>
				 ';
    if (is_array ($hiddens))
    {
      foreach ($hiddens as $k => $v)
      {
        $form .= '' . '
<input type=\'hidden\' name=\'' . $v[0] . '\' value=\'' . $v[1] . '\'>';
      }
    }

    return $form;
  }

  function form_dropdown ($name, $list = array (), $default_val = '', $js = '', $css = '')
  {
    if ($js != '')
    {
      $js = ' ' . $js . ' ';
    }

    if ($css != '')
    {
      $css = ' class="' . $css . '" ';
    }

    $html = '' . '<select name=\'' . $name . '\'' . $js . ('' . ' ' . $css . ' class=\'dropdown\'>
');
    foreach ($list as $k => $v)
    {
      $selected = '';
      if (($default_val != '' AND $v[0] == $default_val))
      {
        $selected = ' selected';
      }

      $html .= '<option value=\'' . $v[0] . '\'' . $selected . '>' . $v[1] . '</option>
';
    }

    $html .= '</select>

';
    return $html;
  }

  function end_form ($text = '', $js = '', $extra = '')
  {
    $html = '';
    $colspan = '';
    $td_colspan = 0;
    if ($text != '')
    {
      if (0 < $td_colspan)
      {
        $colspan = ' colspan=\'' . $td_colspan . '\' ';
      }

      $html .= '<tr><td align=\'center\' class=\'form\'' . $colspan . ('' . '><input type=\'submit\' value=\'' . $text . '\'') . $js . ('' . ' id=\'button\' accesskey=\'s\' class=button>' . $extra . '</td></tr>
');
    }

    $html .= '</form>';
    return $html;
  }

  function result_screen ($mode = 'reg')
  {
    global $month_names;
    global $rootpath;
    global $pic_base_url;
    $page_title = '<h2>Statistic Center Results</h2>';
    $page_detail = '&nbsp;';
    if (!checkdate ($_POST['to_month'], $_POST['to_day'], $_POST['to_year']))
    {
      exit ('The \'Date To:\' time is incorrect, please check the input and try again');
    }

    if (!checkdate ($_POST['from_month'], $_POST['from_day'], $_POST['from_year']))
    {
      exit ('The \'Date From:\' time is incorrect, please check the input and try again');
    }

    $to_time = mktime (12, 0, 0, $_POST['to_month'], $_POST['to_day'], $_POST['to_year']);
    $from_time = mktime (12, 0, 0, $_POST['from_month'], $_POST['from_day'], $_POST['from_year']);
    $human_to_date = getdate ($to_time);
    $human_from_date = getdate ($from_time);
    if ($mode == 'reg')
    {
      $table = 'Registration Statistics';
      $sql_table = 'users';
      $sql_field = 'added';
      $page_detail = 'Showing the number of users registered. (Note: All times based on GMT)';
    }
    else
    {
      if ($mode == 'rate')
      {
        $table = 'Rating Statistics';
        $sql_table = 'ratings';
        $sql_field = 'added';
        $page_detail = 'Showing the number of ratings. (Note: All times based on GMT)';
      }
      else
      {
        if ($mode == 'post')
        {
          $table = 'Post Statistics';
          $sql_table = 'posts';
          $sql_field = 'added';
          $page_detail = 'Showing the number of posts. (Note: All times based on GMT)';
        }
        else
        {
          if ($mode == 'msg')
          {
            $table = 'PM Sent Statistics';
            $sql_table = 'messages';
            $sql_field = 'added';
            $page_detail = 'Showing the number of sent messages. (Note: All times based on GMT)';
          }
          else
          {
            if ($mode == 'torr')
            {
              $table = 'Torrent Statistics';
              $sql_table = 'torrents';
              $sql_field = 'added';
              $page_detail = 'Showing the number of Torrents. (Note: All times based on GMT)';
            }
            else
            {
              if ($mode == 'bans')
              {
                $table = 'Ban Statistics';
                $sql_table = 'bans';
                $sql_field = 'added';
                $page_detail = 'Showing the number of Bans. (Note: All times based on GMT)';
              }
              else
              {
                if ($mode == 'comm')
                {
                  $table = 'Comment Statistics';
                  $sql_table = 'comments';
                  $sql_field = 'added';
                  $page_detail = 'Showing the number of torrent Comments. (Note: All times based on GMT)';
                }
                else
                {
                  if ($mode == 'new')
                  {
                    $table = 'News Statistics';
                    $sql_table = 'news';
                    $sql_field = 'added';
                    $page_detail = 'Showing the number of News Items added. (Note: All times based on GMT)';
                  }
                  else
                  {
                    if ($mode == 'poll')
                    {
                      $table = 'Poll Statistics';
                      $sql_table = TSF_PREFIX . 'poll';
                      $sql_field = 'dateline';
                      $page_detail = 'Showing the number of Polls added. (Note: All times based on GMT)';
                    }
                    else
                    {
                      if ($mode == 'rqst')
                      {
                        $table = 'Request Statistics';
                        $sql_table = 'requests';
                        $sql_field = 'added';
                        $page_detail = 'Showing the number of Requests made. (Note: All times based on GMT)';
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    switch ($_POST['timescale'])
    {
      case 'daily':
      {
        $sql_date = '%w %U %m %Y';
        $php_date = 'F jS - Y';
        break;
      }

      case 'monthly':
      {
        $sql_date = '%m %Y';
        $php_date = 'F Y';
        break;
      }

      default:
      {
        $sql_date = '%U %Y';
        $php_date = ' [F Y]';
        break;
      }
    }

    $sortby = (isset ($_POST['sortby']) ? mysql_real_escape_string ($_POST['sortby']) : '');
    $sqlq = '' . 'SELECT UNIX_TIMESTAMP(MAX(' . $sql_field . ')) as result_maxdate,
				 COUNT(*) as result_count,
				 DATE_FORMAT(' . $sql_field . ',\'' . $sql_date . '\') AS result_time
				 FROM ' . $sql_table . '
				 WHERE UNIX_TIMESTAMP(' . $sql_field . ') > \'' . $from_time . '\'
				 AND UNIX_TIMESTAMP(' . $sql_field . ') < \'' . $to_time . '\'
				 GROUP BY result_time
				 ORDER BY ' . $sql_field . ' ' . $sortby;
    $res = @sql_query ($sqlq);
    $running_total = 0;
    $max_result = 0;
    $results = array ();
    $html = $page_title . '<br /><table id=torrenttable border=1 width=100%><tr><td colspan=3>' . ucfirst ($_POST['timescale']) . ' ' . $table . ('' . ' (' . $human_from_date['mday'] . ' ' . $month_names[$human_from_date['mon']] . ' ' . $human_from_date['year'] . ' to') . ('' . ' ' . $human_to_date['mday'] . ' ' . $month_names[$human_to_date['mon']] . ' ' . $human_to_date['year'] . ')<br />' . $page_detail . '</td></tr>
');
    if (mysql_num_rows ($res))
    {
      while ($row = mysql_fetch_array ($res))
      {
        if ($max_result < $row['result_count'])
        {
          $max_result = $row['result_count'];
        }

        $running_total += $row['result_count'];
        $results[] = array ('result_maxdate' => $row['result_maxdate'], 'result_count' => $row['result_count'], 'result_time' => $row['result_time']);
      }

      foreach ($results as $pOOp => $data)
      {
        $img_width = intval ($data['result_count'] / $max_result * 100 - 20);
        if ($img_width < 1)
        {
          $img_width = 1;
        }

        $img_width .= '%';
        if ($_POST['timescale'] == 'weekly')
        {
          $date = 'Week #' . strftime ('%W', $data['result_maxdate']) . '<br />' . date ($php_date, $data['result_maxdate']);
        }
        else
        {
          $date = date ($php_date, $data['result_maxdate']);
        }

        $html .= '<tr><td width=25%>' . $date . '</td><td width=70%><img src=\'' . $BASEURL . '/' . $pic_base_url . 'bar_end.gif\' border=\'0\' height=\'10\' align=\'middle\' alt=\'\'><img src=\'' . $BASEURL . '/' . $pic_base_url . ('' . 'bar.gif\' border=\'0\' width=\'' . $img_width . '\' height=\'10\' align=\'middle\' alt=\'\'><img src=\'') . $BASEURL . '/' . $pic_base_url . 'bar_end.gif\' border=\'0\' height=\'10\' align=\'middle\' alt=\'\'></td><td align=right width=5%>' . $data['result_count'] . '</td></tr>
';
      }

      $html .= '<tr><td colspan=3>&nbsp;' . '<div align=\'right\'><b>Total </b>' . '<b>' . $running_total . '</b></div></td></tr>
';
    }
    else
    {
      $html .= '<tr><td>No results found</td></tr>
';
    }

    print $html . '</table>
<br />';
  }

  function main_screen ($mode = 'reg')
  {
    global $month_names;
    $page_title = 'Statistic Center';
    $page_detail = 'Please define the date ranges and other options below.<br />Note: The statistics generated are based on the information currently held in the database, they do not take into account pruned forums or delete posts, etc.';
    if ($mode == 'reg')
    {
      $form_code = 'show_reg';
      $table = 'Registration Statistics<br />';
    }
    else
    {
      if ($mode == 'rate')
      {
        $form_code = 'show_rate';
        $table = 'Rating Statistics';
      }
      else
      {
        if ($mode == 'post')
        {
          $form_code = 'show_post';
          $table = 'Post Statistics';
        }
        else
        {
          if ($mode == 'msg')
          {
            $form_code = 'show_msg';
            $table = 'PM Statistics';
          }
          else
          {
            if ($mode == 'torr')
            {
              $form_code = 'show_torr';
              $table = 'Torrent Statistics';
            }
            else
            {
              if ($mode == 'bans')
              {
                $form_code = 'show_bans';
                $table = 'Ban Statistics';
              }
              else
              {
                if ($mode == 'comm')
                {
                  $form_code = 'show_comm';
                  $table = 'Comment Statistics';
                }
                else
                {
                  if ($mode == 'new')
                  {
                    $form_code = 'show_new';
                    $table = 'News Statistics';
                  }
                  else
                  {
                    if ($mode == 'poll')
                    {
                      $form_code = 'show_poll';
                      $table = 'Polls Statistics';
                    }
                    else
                    {
                      if ($mode == 'rqst')
                      {
                        $form_code = 'show_rqst';
                        $table = 'Request Statistics';
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $old_date = getdate (time () - 3600 * 24 * 90);
    $new_date = getdate (time () + 3600 * 24);
    $html = '' . '<table id=torrenttable border=1 width=100%><tr><td>' . $table . '</td></tr>';
    $html .= '' . '<tr><td>' . $page_title . '<br />' . $page_detail . '</td></tr>';
    $html .= start_form (array (1 => array ('code', $form_code), 2 => array ('action', 'stats')));
    $html .= '<tr><td><br /><b>Date From</b>' . form_dropdown ('from_month', make_month (), $old_date['mon']) . '&nbsp;&nbsp;' . form_dropdown ('from_day', make_day (), $old_date['mday']) . '&nbsp;&nbsp;' . form_dropdown ('from_year', make_year (), $old_date['year']) . '<br /></td></tr>';
    $html .= '<tr><td><br /><b>Date To</b>' . form_dropdown ('to_month', make_month (), $new_date['mon']) . '&nbsp;&nbsp;' . form_dropdown ('to_day', make_day (), $new_date['mday']) . '&nbsp;&nbsp;' . form_dropdown ('to_year', make_year (), $new_date['year']) . '<br /></td></tr>';
    if ($mode != 'views')
    {
      $html .= '<tr><td><br /><b>Time scale</b>' . form_dropdown ('timescale', array (0 => array ('daily', 'Daily'), 1 => array ('weekly', 'Weekly'), 2 => array ('monthly', 'Monthly'))) . '<br /></td></tr>';
    }

    $html .= '<tr><td><br /><b>Result Sorting</b>' . form_dropdown ('sortby', array (0 => array ('asc', 'Ascending - Oldest dates first'), 1 => array ('desc', 'Descending - Newest dates first')), 'desc') . '<br /></td></tr>';
    $html .= end_form ('Show') . '</table>';
    print $html;
  }

  function make_year ()
  {
    $time_now = getdate ();
    $return = array ();
    $start_year = 2002;
    $latest_year = intval ($time_now['year']);
    if ($latest_year == $start_year)
    {
      $start_year -= 1;
    }

    $y = $start_year;
    while ($y <= $latest_year)
    {
      $return[] = array ($y, $y);
      ++$y;
    }

    return $return;
  }

  function make_month ()
  {
    global $month_names;
    reset ($month_names);
    $return = array ();
    $m = 1;
    while ($m <= 12)
    {
      $return[] = array ($m, $month_names[$m]);
      ++$m;
    }

    return $return;
  }

  function make_day ()
  {
    $return = array ();
    $d = 1;
    while ($d <= 31)
    {
      $return[] = array ($d, $d);
      ++$d;
    }

    return $return;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  stdhead ('STATISTICS CENTRE');
  echo '		
		<h2>STATISTICS CENTRE</h2>
		
		<table id=\'torrenttable\' border=\'1\' width=100%>
		<tr><td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=reg\'>Registration Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=rate\'>Rating Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=post\'>Post Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=msg\'>Personal Message</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=torr\'>Torrents Stats</a></td>
		</tr>
		
		<tr><td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=bans\'>Ban Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=comm\'>Comment Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=new\'>News Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=poll\'>Poll Stats</a></td>
		<td>&nbsp;<img src=\'';
  echo $BASEURL . '/' . $pic_base_url;
  echo 'item.gif\' border=\'0\' alt=\'\' valign=\'absmiddle\'>&nbsp;<a href=\'';
  echo $_this_script_;
  echo '&action=stats&code=rqst\'>Request Stats</a></td>
		</tr>
		</table>
		<br />
		
';
  if ((!isset ($_GET['action']) AND !isset ($_POST['action'])))
  {
    echo '<div style=\'background-color: lightgrey; border: grey 2px dashed; font-style: italic;\'>
		<br />You could put something useful here!! ;)<br /><br /></div>';
  }

  $month_names = array ();
  $tmp_in = array_merge ($_GET, $_POST);
  foreach ($tmp_in as $k => $v)
  {
    unset ($$k);
  }

  $month_names = array (1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  if ((isset ($tmp_in['code']) AND $tmp_in['code'] != ''))
  {
    switch ($tmp_in['code'])
    {
      case 'show_reg':
      {
        result_screen ('reg');
        break;
      }

      case 'show_rate':
      {
        result_screen ('rate');
        break;
      }

      case 'rate':
      {
        main_screen ('rate');
        break;
      }

      case 'show_post':
      {
        result_screen ('post');
        break;
      }

      case 'post':
      {
        main_screen ('post');
        break;
      }

      case 'show_msg':
      {
        result_screen ('msg');
        break;
      }

      case 'msg':
      {
        main_screen ('msg');
        break;
      }

      case 'show_torr':
      {
        result_screen ('torr');
        break;
      }

      case 'torr':
      {
        main_screen ('torr');
        break;
      }

      case 'show_bans':
      {
        result_screen ('bans');
        break;
      }

      case 'bans':
      {
        main_screen ('bans');
        break;
      }

      case 'show_comm':
      {
        result_screen ('comm');
        break;
      }

      case 'comm':
      {
        main_screen ('comm');
        break;
      }

      case 'show_new':
      {
        result_screen ('new');
        break;
      }

      case 'new':
      {
        main_screen ('new');
        break;
      }

      case 'show_poll':
      {
        result_screen ('poll');
        break;
      }

      case 'poll':
      {
        main_screen ('poll');
        break;
      }

      case 'show_rqst':
      {
        result_screen ('rqst');
        break;
      }

      case 'rqst':
      {
        main_screen ('rqst');
        break;
      }

      default:
      {
        main_screen ('reg');
        break;
      }
    }
  }

  stdfoot ();
?>
