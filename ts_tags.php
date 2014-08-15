<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function select_random_color ()
  {
    return sprintf ('#%02X%02X%02X', mt_rand (0, 255), mt_rand (0, 255), mt_rand (0, 255));
  }

  function select_random_font_size ()
  {
    global $__min;
    global $__max;
    return rand ($__min, $__max);
  }

  function generate_tags ($tags = array ())
  {
    global $BASEURL;
    $__tags = array ();
    $__count = 0;
    do
    {
      $__tags[] = ' <a href="' . $BASEURL . '/browse.php?do=search&amp;search_type=t_both&amp;category=0&amp;keywords=' . urlencode ($tags[$__count]) . '&amp;tags=true"><font style="color: ' . select_random_color () . '; font-size: ' . select_random_font_size () . 'px; font-family: arial;">' . $tags[$__count] . '</font></a> ';
      ++$__count;
    }while (!($__count < count ($tags)));

    return implode ('&nbsp;', $__tags);
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  define ('TT_VERSION', '1.1.1 ');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  include_once INC_PATH . '/functions_security.php';
  $show_tags = '';
  $array_tags = array ();
  $query = sql_query ('SELECT name, descr FROM torrents WHERE visible = \'yes\' ORDER BY RAND() LIMIT 10');
  if (0 < mysql_num_rows ($query))
  {
    require TSDIR . '/admin/include/global_config.php';
    if (((!$__min OR !$__max) OR !$sc_displaycharminimum))
    {
      $__min = 10;
      $__max = 30;
      $sc_displaycharminimum = 2;
    }

    while ($qtags = mysql_fetch_assoc ($query))
    {
      $qtags['name'] = preg_replace ('#[^a-z|A-Z]#', ' ', $qtags['name']);
      $__temp = explode (' ', $qtags['name']);
      foreach ($__temp as $__T)
      {
        if ($sc_displaycharminimum < strlen ($__T))
        {
          $array_tags[] = $__T;
          continue;
        }
      }

      $qtags['descr'] = preg_replace ('#[^a-z|A-Z]#', ' ', $qtags['descr']);
      $__temp2 = explode (' ', $qtags['descr']);
      foreach ($__temp2 as $__T2)
      {
        if ($sc_displaycharminimum < strlen ($__T2))
        {
          $array_tags[] = $__T2;
          continue;
        }
      }
    }

    if (0 < count ($array_tags))
    {
      $show_tags = generate_tags ($array_tags);
    }
    else
    {
      $show_tags = $lang->global['nothingfound'];
    }
  }
  else
  {
    $show_tags = $lang->global['nothingfound'];
  }

  stdhead ($SITENAME . ' - Search Cloud');
  echo '
<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" style="table-layout:fixed;">
	<tbody>
		<tr>
			<td class="thead" align="center">' . $SITENAME . ' - Search Cloud</td>
		</tr>
		<tr>
			<td style="line-height: 25px;">
				<div align="justify">
					' . $show_tags . '
				</div>
			</td>
		</tr>
	</tbody>
</table>
';
  stdfoot ();
  exit ();
?>
