<?

/***********************************************/

/*=========[TS Special Edition v.5.6]==========*/

/*=============[Special Thanks To]=============*/

/*        DrNet - wWw.SpecialCoders.CoM        */

/*          Vinson - wWw.Decode4u.CoM          */

/*    MrDecoder - wWw.Fearless-Releases.CoM    */

/*           Fynnon - wWw.BvList.CoM           */

/***********************************************/





  define ('AS_VERSION', '2.2.1 by xam');

  define ('SKIP_LOCATION_SAVE', true);

  define ('DEBUGMODE', false);

  $rootpath = './../';

  define ('NcodeImageResizer', true);

  require_once $rootpath . 'global.php';

  if (!defined ('IN_SCRIPT_TSSEv56'))

  {

    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');

  }



  dbconn ();

 function execcommand_pmmessage ($message = '<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;"><center>vous n avez plus acces a la shout! consulter votre message avant (<a href=messages.php target=_top>clic <img src="http://lateam.cc/pic/pn_inboxnew.gif" ">

</a>)</center></div>', $forcemessage = false)

  {

    if ((mysql_affected_rows () OR $forcemessage))

    {

      echo $message;

          exit();

    }



  }

 $is_mod = is_mod ($usergroups);

  if ((!$CURUSER OR $usergroups['canshout'] != 'yes'))

  {

    exit ('<div style="background: #FFECCE; border: 1px solid #EA5F00; padding-left: 5px;">' . $lang->global['shouterror'] . '</div>');

    return 1;

  }



  require_once $rootpath . 'shoutbox/config.php';

  $limit = intval ($LIMIT_INDEX);

  $extralink = '';

  if (((isset ($_GET['popupshoutbox']) AND $_GET['popupshoutbox'] == 'yes') AND $is_mod))

  {

    $limit = intval ($LIMIT_POPUP);

    $extralink = '&popupshoutbox=yes';

  }



  $mod = $str = '';

 $shout_query = mysql_query ('' . 'SELECT s.*, u.username, last_access, u.options, u.enabled, u.donor, u.leechwarn, u.warned, u.country, c.name, c.flagpic, g.namestyle FROM shoutbox s LEFT JOIN users u ON (s.userid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN countries c ON (u.country=c.id) ORDER BY s.date DESC LIMIT 0,' . $limit);

  while ($shout_row = mysql_fetch_assoc ($shout_query))

  {

    $date = '[' . my_datee ($timeformat, $shout_row['date']) . ']';



    if ($shout_row[userid] == $CURUSER[id] OR ($is_mod))

    {

      $mod = '<a href="#" onClick="popup(\'shoutbox.php?do=edit&id=' . intval ($shout_row['id']) . $extralink . '\')"><img src="/images/edit.gif" title="Editer" border="0" width="14" height="14"></a>  <a href="#" onClick="popup(\'shoutbox.php?do=delete&id=' . intval ($shout_row['id']) . $extralink . '\')"><img src="/images/delete.gif" title="Effacer" border="0" width="16" height="16"></a>';

    }

      else

    {  $mod = '';

       }







$avatars2 =  '<img src="/images/start.gif" width="50" height="50"/>';



    if (preg_match_all ('' . '#^{systemnotice}(.*)$#', $shout_row['content'], $Matches, PREG_SET_ORDER))

    {

           $str .= '' . '<span class=\'subheader\'>' . $avatars3 . '' . $avatars2 . ' ' . $date . ' ' . $mod . ' - <b>' . $lang->global['snotice'] . '</b> ' . format_comment ($Matches[0][1], true, false) . '</span><br />';



       continue;

    }

    else

    {

      $pics = ($shout_row['donor'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'star.gif" alt="' . $lang->global['imgdonated'] . '" title="' . $lang->global['imgdonated'] . '" border="0" style="vertical-align: middle; margin-center: 4pt; white-space: nowrap;" />' : '');

      if ($shout_row['enabled'] == 'yes')

      {

        $pics .= ($shout_row['leechwarn'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'warned.gif" title="' . $lang->global['imgwarned'] . '" alt="' . $lang->global['imgwarned'] . '" border="0" style="vertical-align: middle; margin-center: 4pt; white-space: nowrap;" />' : '') . ($shout_row['warned'] == 'yes' ? '<img src=\'' . $BASEURL . '/' . $pic_base_url . 'warned3.gif\' alt="' . $lang->global['imgwarned'] . '" title="' . $lang->global['imgwarned'] . '" border="0" style="vertical-align: middle; margin-center: 4pt; white-space: nowrap;" />' : '');

      }

      else

      {

        $pics .= '<img src="' . $BASEURL . '/' . $pic_base_url . 'disabled.gif" alt="' . $lang->global['disabled'] . '"  title="' . $lang->global['disabled'] . '" border="0" style="vertical-align: middle; margin-center: 4pt; white-space: nowrap;" />

';

      }



  $dt = get_date_time (gmtime () - TS_TIMEOUT);

    $last_access = $shout_row['last_access'];







      if ($dt < $last_access)





      {

        $onoffpic = '<img src="/pic/friends/online.png" title="On Line" border="0">';

      }

      else

      {

        $onoffpic = '<img src="/pic/friends/offline.png" title="Off Line" border="0">';

      }





       $gender = $shout_row['gender'];

















  $imagepath = '' . $BASEURL . '/' . $pic_base_url . 'friends/';

  if (preg_match ('#L1#is', $shout_row['options']))

  {

    $UserGender = '<img src="' . $imagepath . 'Male.png" alt="Male" title="Male" border="0"  />';

  }

  else

  {

    if (preg_match ('#L2#is', $shout_row['options']))

    {

      $UserGender = '<img src="' . $imagepath . 'Female.png" alt="Female" title="Female" border="0"  />';

    }

    else

    {

      $UserGender = '<img src="' . $imagepath . 'NA.png" alt="--" title="--" border="0"  />';

    }

  }



  $Userpm2 = '' . $shout_row[userid] . '';



 $Userpm = '<a href="' . $BASEURL . '/sendmessage.php?receiver=' . $Userpm2 . '" alt="Send a PM to member" title="Send a PM to member"><img src=/pic/pm.png></a> ';

 $avatar3 =  '<img src="/pic/verified.gif" width="50" height="50"/>';;



$bimg2 = @mysql_fetch_array(@mysql_query("SELECT avatar FROM users WHERE id=$shout_row[userid]"));



   $avatar = htmlspecialchars($bimg2["avatar"]);

if (!$avatar) {

$avatar = "pic/default_avatar.gif";

}



    if (!$avatar)

           $avatar = "".$BASEURL."/pic/default_avatar.gif";



           $avatars =  '<img src="'.$avatar.'" width="50" height="50"/>';

 $QueryF = @sql_query ('' . 'SELECT COUNT(*) FROM messages WHERE receiver=' . $CURUSER[id] . ' and unread=\'yes\'') OR sqlerr (__FILE__, 532);

  $message = mysql_fetch_row ($QueryF);

  $unreadmail = $message[0];

if ($unreadmail){

        $unread=execcommand_pmmessage();



}else{

$unread='';

}

   $bimg3 = @mysql_fetch_array(@mysql_query("SELECT smilies FROM users WHERE id=$shout_row[userid]"));



   $smiliies = htmlspecialchars($bimg3["smilies"]);

   $smiliess =  '<img src="'.$smiliies.'" width="30" height="30"/>';



      $shouter_name = '<a target="_blank" href="' . ts_seo ($shout_row['userid'], $shout_row['username']) . '">' . get_user_color ($shout_row['username'], $shout_row['namestyle']) . '</a> ' . $pics;

      $shout_content = format_comment ($shout_row['content'], true );

      $str .= '' . '<span class=\'shoutbox\'>' . $avatars . ' ' . $date . ' ' . $mod . ' ' . $onoffpic . ' ' . $UserGender . ' ' . $country . ' ' . $Userpm . ' ' . $shouter_name . '  ' . $smiliess . ' - ' . $shout_content . '</span><br />';

      continue;

    }

  }



  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');

  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');

  header ('Cache-Control: no-cache, must-revalidate');

  header ('Pragma: no-cache');

  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);

  echo $str;

?>