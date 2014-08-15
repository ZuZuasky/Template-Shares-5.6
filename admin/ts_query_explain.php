<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function server_load ()
  {
    if (strtolower (substr (PHP_OS, 0, 3)) === 'win')
    {
      if (class_exists ('COM'))
      {
        $wmi = new COM ('WinMgmts:\\\\.');
        $cpus = $wmi->InstancesOf ('Win32_Processor');
        $cpuload = 0;
        $i = 0;
        if (version_compare ('4.50.0', PHP_VERSION) == 1)
        {
          while ($cpu = $cpus->Next ())
          {
            $cpuload += $cpu->LoadPercentage;
            ++$i;
          }
        }
        else
        {
          foreach ($cpus as $cpu)
          {
            $cpuload += $cpu->LoadPercentage;
            ++$i;
          }
        }

        $cpuload = round ($cpuload / $i, 2);
        return '' . $cpuload . '%';
      }

      return 'Unknown';
    }

    if (@file_exists ('/proc/loadavg'))
    {
      $load = @file_get_contents ('/proc/loadavg');
      $serverload = explode (' ', $load);
      $serverload[0] = round ($serverload[0], 4);
      if (!$serverload)
      {
        $load = @exec ('uptime');
        $load = split ('load averages?: ', $load);
        $serverload = explode (',', $load[1]);
      }
    }
    else
    {
      $load = @exec ('uptime');
      $load = split ('load averages?: ', $load);
      $serverload = explode (',', $load[1]);
    }

    $returnload = trim ($serverload[0]);
    if (!$returnload)
    {
      $returnload = 'Unknown';
    }

    return $returnload;
  }

  function calctime ($time)
  {
    $stat = round ($time * 100 / 1, 3);
    if ($stat <= 40)
    {
      return '' . $time . ' (<font color=\'green\'>Excellent</font>)';
    }

    if ((40 < $stat AND $stat <= 70))
    {
      return '' . $time . ' (<font color=\'darkgreen\'>Good</font>)';
    }

    if ((70 < $stat AND $stat <= 98))
    {
      return '' . $time . ' (<font color=\'red\'>Regular</font>) ';
    }

    if (98 < $stat)
    {
      return '' . $time . ' (<font color=\'darkred\'>Bad</font>) ';
    }

  }

  function explain_query ($sql, $executiontime)
  {
    $calcTime = @calctime ($executiontime);
    $output = '<span style="float: right"><b>Query Time:</b> ' . $calcTime . '</span><b>Query</b><hr>' . @splitsql ($sql) . '<br />';
    if (preg_match ('#^SELECT#sU', $sql))
    {
      $explain = @mysql_query ('EXPLAIN ' . $sql);
      $output .= '
		<br>
		<b>Explain Query</b><hr>
		<table width="100%" cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td class="subheader">id</td>
				<td class="subheader">select_type</td>
				<td class="subheader">table</td>
				<td class="subheader">type</td>
				<td class="subheader">possible_keys</td>
				<td class="subheader">key</td>
				<td class="subheader">key_len</td>
				<td class="subheader">ref</td>
				<td class="subheader">rows</td>
				<td class="subheader">Extra</td>
			</tr>';
      while ($results = @mysql_fetch_assoc ($explain))
      {
        $output .= '
			<tr>
				<td>' . $results['id'] . '</td>
				<td>' . $results['select_type'] . '</td>
				<td>' . $results['table'] . '</td>
				<td>' . $results['type'] . '</td>
				<td>' . $results['possible_keys'] . '</td>
				<td>' . $results['key'] . '</td>
				<td>' . $results['key_len'] . '</td>
				<td>' . $results['ref'] . '</td>
				<td>' . $results['rows'] . '</td>
				<td>' . $results['Extra'] . '</td>
			</tr>
			';
      }

      $output .= '</table>';
    }

    return $output;
  }

  function splitsql ($sql)
  {
    $sql = strtolower ($sql);
    $sql = ereg_replace ('straight_join', '<B>STRAIGHT_JOIN</B>', $sql);
    $sql = ereg_replace ('join', '<B>JOIN</B>', $sql);
    $sql = ereg_replace ('select', '<B>SELECT</B>', $sql);
    $sql = ereg_replace ('delete', '<B>DELETE</B>', $sql);
    $sql = ereg_replace ('update', '<B>UPDATE</B>', $sql);
    $sql = ereg_replace ('from', '<BR><B>FROM</B>', $sql);
    $sql = ereg_replace ('where', '<BR><B>WHERE</B>', $sql);
    $sql = ereg_replace ('group by', '<BR><B>GROUP BY</B>', $sql);
    $sql = ereg_replace ('having', '<BR><B>HAVING</B>', $sql);
    $sql = ereg_replace ('order by', '<BR><B>ORDER BY</B>', $sql);
    return $sql;
  }

  $rootpath = './../';
  define ('TQE_VERSION', '0.4 by xam');
  define ('DEBUGMODE', false);
  require_once $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  if ($usergroups['cansettingspanel'] !== 'yes')
  {
    print_no_permission (true);
  }

  if (function_exists ('memory_get_usage'))
  {
    $memory_usage = ' - <b>Memory Usage:</b> ' . mksize (memory_get_usage ());
  }

  $queries = $_SESSION['queries'];
  if ((!empty ($queries) AND is_array ($queries)))
  {
    $str = '
	<table width="100%" align="center" cellspacing="0" cellpadding="5" border="0">		
		<tr>
			<td  class="thead" width="100%" align="left">Query Debug</td>
		</tr>';
    $id = 1;
    $querytime = 0;
    foreach ($queries as $q => $v)
    {
      $query_explain = explain_query ($v['query'], $v['query_time']);
      $str .= '
		<tr>
			<td  align="left"><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>' . $query_explain . '</td></tr></table></td>
		</tr>';
      ++$id;
      $querytime += $v['query_time'];
    }

    $phptime = $_SESSION['totaltime'] - $querytime;
    $percentphp = @number_format ($phptime / $_SESSION['totaltime'] * 100, 2);
    $percentsql = @number_format ($querytime / $_SESSION['totaltime'] * 100, 2);
    $included_files = str_replace ('\\', '/', get_included_files ());
    $str .= '
		<tr>
			<td  class="thead" width="100%" align="left">System Debug</td>
		</tr>
		<tr>
			<td align="left">				
				<b>Generated in</b> ' . htmlspecialchars_uni ($_SESSION['totaltime']) . ' seconds (' . $percentphp . '% PHP / ' . $percentsql . '% MySQL)<br />
				<b>MySQL Queries:</b> ' . ($id - 1) . ' / <b>Global Parsing Time:</b> ' . $querytime . $memory_usage . '<br />
				<b>PHP version:</b> ' . phpversion () . ' / <b>Server Load:</b> ' . server_load () . ' / <b>GZip Compression:</b> ' . ($gzipcompress == 'yes' ? 'Enabled' : 'Disabled') . '
			</td>
		</tr>
		<tr>
			<td class="thead" width="100%" align="left">Included Files</td>
		</tr>
		<tr>
			<td align="left">
				' . implode ('<br />', $included_files) . '
			</td>
		</tr>
		</table>';
    define ('WYSIWYG_EDITOR', true);
    define ('USE_BB_CODE', true);
    define ('USE_SMILIES', true);
    define ('USE_HTML', false);
    require $thispath . 'wysiwyg/wysiwyg.php';
    stdhead ('DEBUG MODE');
    echo $str;
  }
  else
  {
    echo 'There is no query to show..';
  }

  stdfoot ();
?>
