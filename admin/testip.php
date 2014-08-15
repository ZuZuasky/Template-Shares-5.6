<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $ip = (isset ($_POST['ip']) ? htmlspecialchars (trim ($_POST['ip'])) : (isset ($_GET['ip']) ? htmlspecialchars (trim ($_GET['ip'])) : ''));
  if (!empty ($ip))
  {
    $nip = ip2long ($ip);
    if ($nip == 0 - 1)
    {
      $msg = '<font color=\'red\'>Bad IP!</font>';
    }

    require_once INC_PATH . '/functions_isipbanned.php';
    if (isipbanned ($ip))
    {
      $msg = '<font color=\'red\'>The IP address <b>' . $ip . '</b> is banned.</font>';
    }
    else
    {
      $msg = '<font color=\'green\'>The IP address <b>' . $ip . '</b> is not banned.</font>';
    }
  }

  stdhead ('Test IP');
  if (!empty ($msg))
  {
    _form_header_open_ ('Results: ' . $ip);
    echo '<tr><td>' . $msg . '</td></tr>';
    _form_header_close_ ();
    echo '<br />';
  }

  _form_header_open_ ('Test IP address');
  echo '
<form method=post action="' . $_this_script_ . '">
<table border=1 cellspacing=0 cellpadding=5 width=100%>
<tr><td>IP address <input type=text name=ip id=specialboxn> <input type=submit class=button value=test></td></tr>
</form>
</table>';
  _form_header_close_ ();
  stdfoot ();
?>
