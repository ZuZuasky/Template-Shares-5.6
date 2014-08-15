<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function bark ($msg)
  {
    global $lang;
    global $where;
    stderr ($lang->global['error'], $msg);
  }

  function dict_check ($d, $s)
  {
    global $lang;
    if ($d['type'] != 'dictionary')
    {
      bark ($lang->upload['dicterror1']);
    }

    $a = explode (':', $s);
    $dd = $d['value'];
    $ret = array ();
    foreach ($a as $k)
    {
      unset ($t);
      if (preg_match ('/^(.*)\\((.*)\\)$/', $k, $m))
      {
        $k = $m[1];
        $t = $m[2];
      }

      if (!isset ($dd[$k]))
      {
        bark ($lang->upload['dicterror2']);
      }

      if (isset ($t))
      {
        if ($dd[$k]['type'] != $t)
        {
          bark ($lang->upload['dicterror3']);
        }

        $ret[] = $dd[$k]['value'];
        continue;
      }
      else
      {
        $ret[] = $dd[$k];
        continue;
      }
    }

    return $ret;
  }

  function dict_get ($d, $k, $t)
  {
    global $lang;
    if ($d['type'] != 'dictionary')
    {
      bark ($lang->upload['dicterror1']);
    }

    $dd = $d['value'];
    if (!isset ($dd[$k]))
    {
      return null;
    }

    $v = $dd[$k];
    if ($v['type'] != $t)
    {
      bark ($lang->upload['dicterror4']);
    }

    return $v['value'];
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if (100 < TOTAL_FILES)
  {
    $s = sprintf ($lang->details['bigfile'], ts_nf (TOTAL_FILES));
    return 1;
  }

  require_once INC_PATH . '/benc.php';
  define ('FFL_VERSION', '0.3 by xam');
  @set_time_limit (0);
  @ini_set ('upload_max_filesize', $max_torrent_size);
  @ini_set ('memory_limit', '-1');
  @ignore_user_abort (1);
  $lang->load ('upload');
  $dict = bdec_file (TSDIR . '/' . $torrent_dir . '/' . $id . '.torrent', $max_torrent_size);
  list ($info) = dict_check ($dict, 'info');
  $filelist = array ();
  $totallen = dict_get ($info, 'length', 'integer');
  if (isset ($totallen))
  {
    list ($dname, $plen, $pieces) = dict_check ($info, 'name(string):piece length(integer):pieces(string)');
    $filelist[] = array ($dname, $totallen);
    $type = 'single';
  }
  else
  {
    $flist = dict_get ($info, 'files', 'list');
    if (!isset ($flist))
    {
      bark ($lang->upload['dicterror5']);
    }

    if (!count ($flist))
    {
      bark ($lang->upload['dicterror6']);
    }

    $totallen = 0;
    foreach ($flist as $fn)
    {
      list ($ll, $ff) = dict_check ($fn, 'length(integer):path(list)');
      $totallen += $ll;
      $ffa = array ();
      foreach ($ff as $ffe)
      {
        if ($ffe['type'] != 'string')
        {
          bark ($lang->upload['dicterror7']);
        }

        $ffa[] = $ffe['value'];
      }

      if (!count ($ffa))
      {
        bark ($lang->upload['dicterror7']);
      }

      $ffe = implode ('/', $ffa);
      $filelist[] = array ($ffe, $ll);
    }

    $type = 'multi';
  }

  $s = '<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
';
  $s .= '<tr><td class=\'colhead\'>&nbsp;</td><td class=\'colhead\' align=\'left\'>' . $lang->details['path'] . '</td><td class=\'colhead\' align=\'center\'>' . $lang->details['size'] . '</td></tr>
';
  require_once INC_PATH . '/functions_get_file_icon.php';
  foreach ($filelist as $file)
  {
    $s .= '<tr><td align=\'center\'>' . get_file_icon ($file[0], 'tsf_forums/images/attach/') . '</td><td align=\'left\'>' . htmlspecialchars_uni ($file[0]) . '</td><td align=\'center\'>' . mksize ($file[1]) . '</td></tr>
';
  }

  $s .= '</table>
';
  unset ($filelist);
?>
