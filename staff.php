<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  define ('SP_VERSION', 'v.0.8 ');
  require_once INC_PATH . '/class_template.php';
  $new_ts_template = new ts_template ();
  $ts_template = $new_ts_template->get_ts_template ('staff');
  $lang->load ('staff');
  stdhead ($lang->staff['staff'], true, 'collapse');
  $dt = get_date_time (gmtime () - TS_TIMEOUT);
  $groups = array ();
  $is_mod = is_mod ($usergroups);
  $query = sql_query ('SELECT gid FROM usergroups WHERE showstaffteam=\'yes\'' . ($is_mod ? ' OR showstaffteam=\'staff\'' : ''));
  while ($group = mysql_fetch_array ($query))
  {
    $groups[$group['gid']] = $group;
  }

  $groups_in = implode (',', array_keys ($groups));
  $query = sql_query ('' . 'SELECT u.id,u.username,u.usergroup,u.last_access,u.options,u.country,c.name,c.flagpic,g.namestyle FROM users u LEFT JOIN countries c ON (u.country=c.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.usergroup IN (' . $groups_in . ') ORDER BY u.username');
  while ($arr = mysql_fetch_array ($query))
  {
    $last_access = $arr['last_access'];
    $userid = $arr['id'];
    if (((preg_match ('#B1#is', $arr['options']) AND !$is_mod) AND $userid != $CURUSER['id']))
    {
      $onoffpic = '<img src="' . $pic_base_url . 'user_offline.gif" border="0">';
    }
    else
    {
      if (($dt < $last_access OR $userid == $CURUSER['id']))
      {
        $onoffpic = '<img src="' . $pic_base_url . 'user_online.gif" border="0">';
      }
      else
      {
        $onoffpic = '<img src="' . $pic_base_url . 'user_offline.gif" border="0">';
      }
    }

    eval ($ts_template['table_1']);
  }

  $query = sql_query ('SELECT gid,title,namestyle,disporder FROM usergroups WHERE showstaffteam=\'yes\'' . ($is_mod ? ' OR showstaffteam=\'staff\'' : '') . ' ORDER by disporder');
  while ($group = mysql_fetch_array ($query))
  {
    if (isset ($staff_table[$group['gid']]))
    {
      eval ($ts_template['table_2']);
      continue;
    }
  }

  $firstline = '';
  $query = sql_query ('SELECT s.supportlang,s.supportfor,u.id,u.username,u.usergroup,u.last_access,u.options,u.country,c.name,c.flagpic,g.namestyle FROM ts_support s LEFT JOIN users u ON (s.userid=u.id) LEFT JOIN countries c ON (u.country=c.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY u.username');
  while ($arr = mysql_fetch_array ($query))
  {
    $last_access = $arr['last_access'];
    $userid = $arr['id'];
    if (((preg_match ('#B1#is', $arr['options']) AND !$is_mod) AND $userid != $CURUSER['id']))
    {
      $onoffpic = '<img src="' . $pic_base_url . 'user_offline.gif" border="0">';
    }
    else
    {
      if (($dt < $last_access OR $userid == $CURUSER['id']))
      {
        $onoffpic = '<img src="' . $pic_base_url . 'user_online.gif" border="0">';
      }
      else
      {
        $onoffpic = '<img src="' . $pic_base_url . 'user_offline.gif" border="0">';
      }
    }

    eval ($ts_template['table_3']);
  }

  if (!empty ($firstline))
  {
    eval ($ts_template['table_4']);
  }

  stdfoot ();
?>
