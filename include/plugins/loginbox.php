<?php






/*©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©
©       quick access plugin by lateam & claude33   ©             
©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©©*/


if (!defined('TS_P_VERSION'))

{

    define('TS_P_VERSION', '1.1 by xam');

}

// Security Check.

if (!defined('IN_PLUGIN_SYSTEM'))

{

     die("<font face='verdana' size='2' color='darkred'><b>Erreur!</b>  Direct initialization of this file is not allowed.</font>");

}



// BEGIN Plugin: Login

if (!$CURUSER)

{

    $lang->load('login');

    require(INC_PATH.'/class_page_check.php');

    $newpage = new page_verify();

    $newpage->create('login');

    $username = (!empty($_COOKIE['ts_username']) ? htmlspecialchars_uni($_COOKIE['ts_username']) : '');    

    $image = '<img src="' . $BASEURL . '/include/class_user_title.php?str=' . base64_encode ($user['title']) . '&png=' . base64_encode ($png) . '" border="0" alt= "" title ="" />';

    $loginbox = '

    <form method="post" action="takelogin.php">

    '.$lang->login['username'].'<br /><input type="text" size="20" name="username" class="inputUsernameLoginbox" value="'.$username.'" /><br />

    '.$lang->login['password'].'<br /><input type="password" size="20" name="password" class="inputPasswordLoginbox" value="" /><br />';



    if ($securelogin == "yes") $sec = "checked=\"checked\" disabled=\"disabled\" /";

    elseif ($securelogin == "no") $sec = "disabled=\"disabled\" /";

    elseif ($securelogin == "op") $sec = " /";

    if ($iv == "reCAPTCHA")

    {

        include_once(INC_PATH.'/recaptchalib.php');

        $loginbox .= '

        <script type="text/javascript">

            var RecaptchaOptions = {

                theme : "'.$reCAPTCHATheme.'",

                lang : "'.$reCAPTCHALanguage.'"

                };

        </script>

        '.$lang->global['secimage'].'<br />

        '.recaptcha_get_html($reCAPTCHAPublickey, NULL);

    }

    elseif ($iv == 'yes')

    {

        unset($_SESSION['security_code']);

        $loginbox .= '

        <script type="text/javascript" src="'.$BASEURL.'/scripts/reload_image.js"></script>

        '.$lang->global['secimage'].'<br />

        <table>

            <tr>

                <td rowspan="2" class="none"><img src="'.$BASEURL.'/include/class_tscaptcha.php?width=132&amp;height=50" id="regimage" border="0" alt="" /></td>

                <td class="none"><img src="'.$BASEURL.'/'.$pic_base_url.'listen.gif" border="0" style="cursor:pointer" onclick="return ts_open_popup(\''.$BASEURL.'/listen.php\', 400, 120);" alt="'.$lang->global['seclisten'].'" title="'.$lang->global['seclisten'].'" /></td>

            </tr>

            <tr>

                <td class="none"><img src="'.$BASEURL.'/'.$pic_base_url.'reload.gif" border="0" style="cursor:pointer" onclick="javascript:reload()" alt="'.$lang->global['secimagehint'].'" title="'.$lang->global['secimagehint'].'" /></td>

            </tr>

        </table>

        '.$lang->global['seccode'].'<br />

        <input type="text" size="20" name="security_code" class="inputPasswordLoginbox" value="" />';

    }



    $loginbox .= '

    <input type="checkbox" class="none" name="logout" style="vertical-align: middle;" value="yes" />'.$lang->login['logout15'].' <br />

    <input type="checkbox" class="none" name="logintype" style="vertical-align: middle;" value="yes" '.$sec.'>'.$lang->login['securelogin'].'<br />

    <input type="submit" value="'.$lang->login['login'].'" /> <input type="reset" value="'.$lang->login['reset'].'" />

    </form>

    ';

}

else

{

$UserInfo = array ('username' => get_user_color ($CURUSER['username'], $CURUSER['namestyle']), 'title' => get_user_color($CURUSER['title'], $CURUSER['namestyle']), 'joindate' => my_datee ($regdateformat, $CURUSER['added']), 'lastaccess' => my_datee ($dateformat, $CURUSER['last_access']) . ' ' . my_datee ($timeformat, $CURUSER['last_access']), 'page' => (($IsStaff OR $SameUser) ? $CURUSER['page'] : $lang->userdetails['hidden']));

  switch ($CURUSER['usergroup'])

  {

    case 0:

    {

    }



    case 1:

    {

    }



    case 2:

    {

      $png = 'rank_full_blank';

      break;

    }



    default:

    {

      $png = 'rank_star_blank';

      break;

    }

  }

 include_once(INC_PATH.'/functions_ratio.php'); 

  if (0 < $CURUSER['downloaded'])

  {

    $sr = $CURUSER['uploaded'] / $CURUSER['downloaded'];

    if (4 <= $sr)

    {

      $s = 'w00t';

    }

    else

    {

      if (2 <= $sr)

      {

        $s = 'grin';

      }

      else

      {

        if (1 <= $sr)

        {

          $s = 'smile1';

        }

        else

        {

          if (0.5 <= $sr)

          {

            $s = 'noexpression';

          }

          else

          {

            if (0.25 <= $sr)

            {

              $s = 'sad';

            }

            else

            {

              $s = 'cry';

            }

          }

        }

      }

    }



    $ratioimage = ' <img src="' . $BASEURL . '/' . $pic_base_url . 'smilies/' . $s . '.giz" border="0" alt="" title="" class="inlineimg" />';

  }

 require_once(INC_PATH.'/functions_mkprettytime.php');
 
 



    $query = sql_query("SELECT COUNT(t.id) as total_torrents FROM torrents t WHERE t.banned = 'no' AND t.owner = ".sqlesc($CURUSER['id'])." LIMIT 1");
    $totaltcount = mysql_result($query, 0, 'total_torrents');

    $query = sql_query("SELECT COUNT(t.id) as total_weak_torrents FROM torrents t WHERE t.banned = 'no' AND t.owner = ".sqlesc($CURUSER['id'])." AND (t.visible = 'no' OR (t.leechers > 0 AND t.seeders = 0) OR (t.leechers = 0 AND t.seeders = 0)) LIMIT 1");
    $totalwcount = mysql_result($query, 0, 'total_weak_torrents');

    $query = sql_query ('SELECT COUNT(r.id) as total_parrainage FROM referrals r WHERE r.uid = '.sqlesc($CURUSER['id']).'' );
    $total_parrainage = mysql_result($query, 0, 'total_parrainage');

    $query = sql_query ('SELECT COUNT(f.id) as total_friends FROM friends f WHERE f.userid = '.sqlesc($CURUSER['id']).'');
    $total_friends = mysql_result($query, 0, 'total_friends');

    $query = sql_query ("SELECT COUNT(p.id) as total_seed FROM peers p WHERE p.seeder = 'yes' AND p.userid = ".sqlesc($CURUSER['id'])." LIMIT 1");
    $total_seed = mysql_result($query, 0, 'total_seed');

    $query = sql_query ("SELECT COUNT(p.id) as total_leech FROM peers p WHERE p.seeder = 'no' AND p.userid = ".sqlesc($CURUSER['id'])." LIMIT 1");
    $total_leech = mysql_result($query, 0, 'total_leech');

    $query = sql_query ("SELECT u.total_donated as total_don FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE u.enabled = 'yes' AND u.donor = 'yes' AND u.id = ".sqlesc($CURUSER['id'])." LIMIT 1");
    $total_don = mysql_result($query, 0, 'total_don');


  $image_hash = $_SESSION['image_hash'] = md5 ($CURUSER['id'] . $CURUSER['username'] . $securehash);

  $image = '<img src="' . $BASEURL . '/include/class_user_title.php?str=' . base64_encode ($usergroups['title'])  . '&png=' . base64_encode ($png) . '" border="0" alt= "" title ="" />';

    $ratio = get_user_ratio($CURUSER['uploaded']/$CURUSER['downloaded'],true);

    $ratio = '<font color="' . get_ratio_color . '">' . $ratio . $ratioimage . '</font>';

    if ($CURUSER['donor'] == 'yes')

            $medaldon = '<img src="'.$BASEURL.'/'.$pic_base_url.'star.gif" alt="'.$lang->global['imgdonated'].'" title="'.$lang->global['imgdonated'].'" class="inlineimg" />';

        if ($CURUSER['warned'] == 'yes' OR $CURUSER['leechwarn'] == 'yes')

            $warn = '<img src="'.$BASEURL.'/'.$pic_base_url.'warned.gif" alt="'.$lang->global['imgwarned'].'" title="'.$lang->global['imgwarned'].'" class="inlineimg" />';

    $loginbox = '<table border="0" width="100%" height="150">



   <tr>

    <td class="none" align="center" width="125" rowspan="5" height="125">'.get_user_avatar($CURUSER['avatar']).'</td>
    <td width="85%" height="15" colspan="3" >
      <p align="center"><strong>'.$lang->global['welcomeback'].'</strong><a href="'.ts_seo($CURUSER['id'],  $CURUSER['username']).'">'.get_user_color($CURUSER['username'],$usergroups['namestyle']).'</a> '.(isset($medaldon) ?  $medaldon : '').'  '.(isset($warn) ? $warn : '').'<br /><iframe src="'.$BASEURL.'/cl.html" frameborder="0" scrolling="no" align="center" style="width: 100%; height:  15px;"></iframe>
      </p>
    </td>
  </tr>
  <tr>
    <td width="300" height="15"><strong><img border="0" src="'.$BASEURL.'/info/ip.png" width="20" height="20">IP:</strong> ('.$CURUSER['ip'].')</td>
    <td width="276" height="15"><strong><img border="0" src="'.$BASEURL.'/info/up.png" width="20" height="20">Uploaded</strong> ('.$total_seed.')</td>

 <td width="263" height="15"><strong><img border="0" src="'.$BASEURL.'/info/jvod.png" width="20" height="20">'.$lang->global['jeton'].' </strong><font color="red">('.$CURUSER  ['droits_film'].')</font></td>

  </tr>

  <tr>
    <td width="300" height="15"><strong><img border="0" src="'.$BASEURL.'/info/last.png" width="20" height="20">'.sprintf($lang->index['llogin'] , my_datee($dateformat, $CURUSER['last_login']).'</strong>  '.my_datee($timeformat, $CURUSER['last_login'])).'</td>
    <td width="276" height="15"><strong><img border="0" src="'.$BASEURL.'/info/dl.png" width="20" height="20">Downloaded </strong>('.$total_leech.')</td>
    <td width="263" height="15"><strong><img border="0" src="'.$BASEURL.'/info/jddl.png" width="20" height="20">'.$lang->global['jetons'].' </strong><font color="red">('.$CURUSER['droits_ddl'].') </font></td>
  </tr>

  <tr>
    <td width="300" height="15"><strong><img border="0" src="'.$BASEURL.'/info/ratio.png" width="20" height="20">'.$lang->global['ratio'].'</strong> '.$ratio.'</td>
    <td width="276" height="15"><strong><img border="0" src="'.$BASEURL.'/info/mes_torrents.png" width="20" height="20"><a href="'.$BASEURL.'/browse.php?special_search=mytorrents">My Torrents:</a>
      </strong><a href="'.$BASEURL.'/browse.php?special_search=mytorrents">('.$totaltcount.')</a></td>
    <td class=tools width="263" height="15"><a href="' . $BASEURL . '/invite.php"><img border="0" src="'.$BASEURL.'/info/invitation.png" width="20" height="20">'.$lang->userdetails['usertools2'].' Invitations: </a>('.ts_nf($CURUSER['invites']).')</td>
    
  </tr>

  <tr>
   
 <td width="300" height="15"><strong> <img border="0" src="'.$BASEURL.'/info/upload.png" width="20" height="20"> <strong>'.$lang->global['uploaded'].'</strong> <font color="green">'. mksize ($CURUSER['uploaded']).'</font></strong></td>
    <td width="276" height="15"><img border="0" src="'.$BASEURL.'/info/reseed.png" width="20" height="20"><a href="'.$BASEURL.'/browse.php?special_search=myreseeds"><strong>My ReSeeds :</strong>('. $totalwcount.')</a></td>
    <td class=tools width="263" height="10"><a href="'.$BASEURL.'/usercp.php"><img src="'.$BASEURL.'/info/profil.png" width="20" border="0" height="20" />Profile</a></td>
    
  </tr>

  <tr>
    <td class="none" align="left" height="15" width="253">
      <p align="center">'.$image.'</p>
    </td>
    <td width="300" height="15"><strong><img border="0" src="'.$BASEURL.'/info/download.png" width="20" height="20">'.$lang->global['downloaded'].' </strong><font color="red">'.mksize($CURUSER ['downloaded']).'</font></td>
    <td width="276" height="15"><strong><img border="0" src="'.$BASEURL.'/info/parrainage.png" width="22" height="20"><a href="'.$BASEURL.'/referrals.php">Referrals :</a></strong><a href="'.$BASEURL.'/referrals.php">('.$total_parrainage.')</a></td>
    <td class=tools width="263" height="10"><a href="'.$BASEURL.'/messages.php"><U><img src="'.$BASEURL.'/info/mp.png" width="20" border="0" height="20" /></U></a><strong><font color="blue"> 
      <a href="'.$BASEURL.'/messages.php">'.sprintf($lang->index['unreadmessages'],  ts_nf($CURUSER['pmunread'] > 0 ?  $CURUSER['pmunread'] : 0)).'</a></font></strong></td>
    
  </tr>
  <tr>
    <td width="253" height="1" align="center"><strong>Your totals:</strong>'.$total_don.'</td>
    <td width="300" height="1"><strong><img border="0" src="'.$BASEURL.'/info/bonus.png" width="20" height="20">'.$lang->global['bonus'].' <font color="blue"><a href="'. $BASEURL.'/mybonus.php">'.$CURUSER['seedbonus'].'</a> &nbsp;</font></strong></td>
    <td width="276" height="1"><strong><img border="0" src="'.$BASEURL.'/info/amis.png" width="20" height="20"><a href="'.$BASEURL.'/friends.php">My Friends :</a></strong><a href="'.$BASEURL.'/friends.php">('.$total_friends.')</a></td>
    
    <td class=tools width="263" height="1" rowspan="3"><a href="'.$BASEURL.'/logout.php"><img border="0" src="'.$BASEURL.'/info/quiter.png" width="20" height="20">Logout</a></td>
  </tr>






</table>';



// END Plugin: Login

}

?> 