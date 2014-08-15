<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function fast_db_connect ()
  {
    $dbfile = './config/DATABASE';
    if (!file_exists ($dbfile))
    {
      exit ('DATABASE Configuration file does not exists');
      return null;
    }

    $data = unserialize (@file_get_contents ($dbfile));
    if (!($connect = mysql_connect ($data['mysql_host'], $data['mysql_user'], $data['mysql_pass'])))
    {
      exit ('Error: Mysql Connection!');
      ;
    }

    if (!(mysql_select_db ($data['mysql_db'], $connect)))
    {
      exit ('Error: Mysql DB Selection!');
      ;
    }

    unset ($data);
  }

  function sqlesc ($x)
  {
    return '\'' . mysql_real_escape_string ($x) . '\'';
  }

  function hash_where ($name, $hash)
  {
    return '' . '(' . $name . ' = ' . sqlesc ($hash) . ('' . ' OR ' . $name . ' = ') . sqlesc (preg_replace ('/ *$/s', '', $hash)) . ')';
  }

  function unesc ($x)
  {
    return (get_magic_quotes_gpc () ? stripslashes ($x) : $x);
  }

  function hash_pad ($hash)
  {
    return str_pad ($hash, 20);
  }

  function benc_str ($s)
  {
    return strlen ($s) . ':' . $s;
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  define ('S_VERSION', '0.6 ');
  if (!isset ($_GET['info_hash']))
  {
    exit ('Permission denied!');
  }

  fast_db_connect ();
  $r = 'd' . benc_str ('files') . 'd';
  if (!($res = mysql_query ('SELECT info_hash,seeders,times_completed,leechers FROM torrents WHERE ' . hash_where ('info_hash', unesc ($_GET['info_hash'])) . ' LIMIT 1')))
  {
    exit ('Mysql error!');
    ;
  }

  while ($row = mysql_fetch_assoc ($res))
  {
    $r .= '20:' . hash_pad ($row['info_hash']) . 'd' . benc_str ('complete') . 'i' . $row['seeders'] . 'e' . benc_str ('downloaded') . 'i' . $row['times_completed'] . 'e' . benc_str ('incomplete') . 'i' . $row['leechers'] . 'e' . 'e';
  }

  $r .= 'ee';
  header ('Content-Type: text/plain');
  if ((isset ($_SERVER['HTTP_ACCEPT_ENCODING']) AND $_SERVER['HTTP_ACCEPT_ENCODING'] == 'gzip'))
  {
    header ('Content-Encoding: gzip');
    echo gzencode ($x, 9, FORCE_GZIP);
  }
  else
  {
    echo $r;
  }

  unset ($r);
?>
