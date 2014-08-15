<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class lsmexplain_plan
  {
    var $con = null;
    var $tables = array ();
    var $long_query_time = null;
    function explain_plan ($bco, $db, $user, $pwd)
    {
      if ($this->con = mysql_pconnect ($bco, $user, $pwd))
      {
        if (!mysql_select_db ($db, $this->con))
        {
          echo 'Database doesn\'t exist or unavailable !';
          return null;
        }

        $sql = 'show variables like \'long%\'';
        $res = mysql_query ($sql, $this->con);
        $data = mysql_fetch_row ($res);
        $this->long_query_time = $data[1];
        return null;
      }

      echo 'MySQL unavailable or user and password doesn\'t match';
    }

    function explain ($sql)
    {
      echo '<table width=\'760px\'><tr><td>';
      $this->tables = array ();
      $start = $this->getmicrotime ();
      $res = mysql_query ($sql, $this->con);
      $end = $this->getmicrotime ();
      $executiontime = $end - $start;
      $res = mysql_query ('explain ' . $sql, $this->con);
      $sqlsplited = $this->splitSql ($sql);
      echo '<table width=\'78%\'><tr><td bgcolor=\'#FCE072\'><font face=\'arial\' size=\'2\'><b>SQL statement has just executed :</b></font></td></tr>';
      echo '<tr><td bgcolor=\'#FFEFAF\'><font face=\'arial\' size=\'2\'>' . $sqlsplited . '</font> </td></tr>';
      echo '<tr><td bgcolor=\'#FFEFAF\'><font face=\'arial\' size=\'2\'><b>Time : ' . round ($executiontime, 5) . ' secs - Execution time : ';
      $this->calcTime ($executiontime);
      echo '</b></font> </td></tr>';
      echo ' </table>';
      echo '<table cellspace=\'1\'>';
      echo '<tr bgcolor=\'#86C77A\'>';
      echo '<td width=\'18%\'><font face=\'arial\' size=\'2\'><b>About SQL statement performance : </b></font></td></tr></table>';
      echo '<table cellspace=\'1\'>';
      echo '<tr bgcolor=\'#86C77A\'>';
      echo '<td width=\'18%\'><font face=\'arial\' size=\'2\'>table</font></td>';
      echo '<td width=\'10%\'><font face=\'arial\' size=\'2\'>type</font></td>';
      echo '<td width=\'10%\'><font face=\'arial\' size=\'2\'>key used</font></td>';
      echo '<td width=\'6%\'><font face=\'arial\' size=\'2\'>rows returned</font></td>';
      echo '<td width=\'18%\'><font face=\'arial\' size=\'2\'>extra</font></td>';
      echo '<td width=\'15%\'><font face=\'arial\' size=\'2\'>performance</font></td>';
      echo '<td width=\'15%\'><font face=\'arial\' size=\'2\'>references</font></td>';
      echo '<td width=\'6%\'><font face=\'arial\' size=\'2\'>key length</font></td>';
      echo '<td width=\'10%\'><font face=\'arial\' size=\'2\'>possible keys</font></td>';
      echo '</font></tr>';
      $flagcolor = 0;
      while ($data = mysql_fetch_array ($res))
      {
        if (!$flagcolor)
        {
          $color = '#EEFFEB';
          $flagcolor = 1;
        }
        else
        {
          $flagcolor = 0;
          $color = '#ffffff';
        }

        echo '' . '<tr bgcolor=\'' . $color . '\'>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['table'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['type'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['key'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['rows'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['Extra'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>';
        echo $this->calcPerformance ($data['type'], $data['Extra']);
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['ref'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['key_len'] . '</font></TD>';
        echo '<TD><font face=\'arial\' size=\'2\'>' . $data['possible_keys'] . '</font></TD>';
        echo '</font></TD>';
        echo '</tr>';
      }

      echo '</table>';
      $this->showTables ($sql);
      echo '</td></tr></table>';
    }

    function calcperformance ($type, $extra)
    {
      $value = 0;
      if ($type == 'ALL')
      {
        $value -= 5;
      }
      else
      {
        if ($type == 'index')
        {
          $value -= 3;
        }
        else
        {
          if ($type == 'range')
          {
            $value -= 1;
          }
          else
          {
            if ($type == 'ref')
            {
              $value += 1;
            }
            else
            {
              if ($type == 'eq_ref')
              {
                $value += 3;
              }
            }
          }
        }
      }

      $extras = explode (';', $extra);
      reset ($extras);
      while (list ($key, $value2) = each ($extras))
      {
        $value2 = trim ($value2);
        if ($value2 == 'Using temporary')
        {
          $value -= 5;
          continue;
        }
        else
        {
          if ($value2 == 'Using filesort')
          {
            $value -= 4;
            continue;
          }
          else
          {
            if ($value2 == 'not exists')
            {
              $value += 1;
              continue;
            }
            else
            {
              if ($value2 == 'distinct')
              {
                $value += 3;
                continue;
              }
              else
              {
                if ($value2 == 'Using where')
                {
                  $value += 4;
                  continue;
                }
                else
                {
                  if ($value2 == 'Using index')
                  {
                    $value += 5;
                    continue;
                  }

                  continue;
                }

                continue;
              }

              continue;
            }

            continue;
          }

          continue;
        }
      }

      if ($value < 0)
      {
        echo 'Bad';
        return null;
      }

      if ((0 <= $value AND $value <= 2))
      {
        echo 'Regular';
        return null;
      }

      if ((2 < $value AND $value <= 6))
      {
        echo 'Good';
        return null;
      }

      if (6 < $value)
      {
        echo 'Excelent';
      }

    }

    function splitsql ($sql)
    {
      $sql = strtolower ($sql);
      $sql = ereg_replace ('straight_join', '<B>STRAIGHT_JOIN</B>', $sql);
      $sql = ereg_replace ('join', '<B>JOIN</B>', $sql);
      $sql = ereg_replace ('select', '<B>SELECT</B>', $sql);
      $sql = ereg_replace ('from', '<BR><B>FROM</B>', $sql);
      $sql = ereg_replace ('where', '<BR><B>WHERE</B>', $sql);
      $sql = ereg_replace ('group by', '<BR><B>GROUP BY</B>', $sql);
      $sql = ereg_replace ('having', '<BR><B>HAVING</B>', $sql);
      $sql = ereg_replace ('order by', '<BR><B>ORDER BY</B>', $sql);
      return $sql;
    }

    function calctime ($time)
    {
      $stat = round ($time * 100 / $this->long_query_time, 3);
      if ($stat <= 40)
      {
        echo 'Excelent !';
        return null;
      }

      if ((40 < $stat AND $stat <= 70))
      {
        echo 'Good ';
        return null;
      }

      if ((70 < $stat AND $stat <= 98))
      {
        echo 'Regular ';
        return null;
      }

      if (98 < $stat)
      {
        echo 'Bad ';
      }

    }

    function getmicrotime ()
    {
      list ($usec, $sec) = explode (' ', microtime ());
      return (double)$usec + (double)$sec;
    }

    function showtables ($sql)
    {
      $sortedtable = array ();
      $piece = explode ('#', ereg_replace ('FROM|WHERE|ORDER|GROUP|HAVING|from|where|order|group|having', '#', $sql));
      $tables = explode (',', $piece[1]);
      echo '<table width=\'78%\'>';
      echo '<tr bgcolor=\'#7eb7e7\'>';
      echo '<TD width=\'30%\'><font face=\'arial\' size=\'2\'><b>About Table(s)</b></td><tr></table>';
      echo '<table width=\'78%\'>';
      echo '<tr bgcolor=\'#7eb7e7\'>';
      echo '<TD width=\'30%\'><font face=\'arial\' size=\'2\'><b>Table</b>';
      echo '</font></td>';
      echo '<TD width=\'10%\'><font face=\'arial\' size=\'2\'><b>Type</b>';
      echo '</font></td>';
      echo '<TD width=\'45%\'><font face=\'arial\' size=\'2\'><b>Indexes</b>';
      echo '</font></td>';
      echo '<TD width=\'10%\'><font face=\'arial\' size=\'2\'><b># Registers</b>';
      echo '</font></td>';
      echo '</tr>';
      $colorflag = 0;
      reset ($tables);
      $ind = 0;
      while (list ($key, $value) = each ($tables))
      {
        $tabls = explode (' ', trim ($value));
        $sql2 = 'show table status like \'' . $tabls[0] . '\'';
        $res2 = mysql_query ($sql2, $this->con);
        $dados = mysql_fetch_array ($res2);
        $sortedtable[$ind][0] = $value;
        $sortedtable[$ind][1] = $dados['Rows'];
        $sortedtable[$ind][2] = $dados['Type'];
        ++$ind;
      }

      usort ($sortedtable, 'compare');
      $ind = 0;
      $size = count ($sortedtable) - 1;
      while ($ind <= $size)
      {
        if (!$colorflag)
        {
          $color = '#c6e5ff';
          $colorflag = 1;
        }
        else
        {
          $color = '#ffffff';
          $colorflag = 0;
        }

        echo '' . '<tr bgcolor=\'' . $color . '\'>';
        echo '<TD valign=\'top\'><font face=\'arial\' size=\'2\'>';
        echo $sortedtable[$ind][0];
        echo '</font></td>';
        echo '<TD valign=\'top\'><font face=\'arial\' size=\'2\'>';
        echo $sortedtable[$ind][2];
        echo '</font></td>';
        echo '<TD valign=\'top\'><font face=\'arial\' size=\'2\'>';
        $tabls = explode (' ', trim ($sortedtable[$ind][0]));
        $sql3 = 'show index from ' . $tabls[0];
        $res3 = mysql_query ($sql3, $this->con);
        echo '<table>';
        echo '<tr>';
        echo '<td width=\'20%\'><font face=\'arial\' size=\'2\'><i>Type</i></font></td>';
        echo '<td width=\'5%\'><font face=\'arial\' size=\'2\'><i>Seq</i></font></td>';
        echo '<td width=\'20%\'><font face=\'arial\' size=\'2\'><i>Column</i></font></td>';
        echo '<td width=\'10%\'><font face=\'arial\' size=\'2\'><i>Index type</i></font></td>';
        echo '</tr>';
        while ($data = mysql_fetch_array ($res3))
        {
          $name = $data['Key_name'];
          $seq = $data['Seq_in_index'];
          $column = $data['Column_name'];
          $type = $data['Index_type'];
          echo '<tr>';
          echo '<td><font face=\'arial\' size=\'2\'>';
          $printed = 0;
          if ($tabls[0] != $tableant)
          {
            echo $name;
            $printed = 1;
            $tableant = $tabls[0];
          }

          if ($name != $nameant)
          {
            $nameant = $name;
            if (!$printed)
            {
              echo $name;
            }
          }

          echo '</font></td>';
          echo '<td><font face=\'arial\' size=\'2\'>';
          echo $seq;
          echo '</font></td>';
          echo '<td><font face=\'arial\' size=\'2\'>';
          echo $column;
          echo '</font></td>';
          echo '<td><font face=\'arial\' size=\'2\'>' . $type;
          echo '</font></td>';
          echo '</tr>';
        }

        echo '</table>';
        echo '</font></td>';
        echo '<TD valign=\'top\'><font face=\'arial\' size=\'2\'>';
        echo $sortedtable[$ind][1];
        echo '</font></td>';
        echo '</tr>';
        ++$ind;
      }

      echo '</table>';
      $this->tables = $sortedtable;
    }
  }

  function compare ($a, $b)
  {
    return strnatcasecmp ($a[1], $b[1]);
  }

?>
