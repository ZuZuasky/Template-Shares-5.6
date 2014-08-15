<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function validusername ($username)
  {
    if (!preg_match ('|[^a-z\\|A-Z\\|0-9]|', $username))
    {
      return true;
    }

    return false;
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  define ('MINCHAR', 3);
  define ('FU_VERSION', '0.9');
  unset ($error);
  unset ($results);
  $formname = (isset ($_POST['formname']) ? htmlspecialchars ($_POST['formname']) : (isset ($_GET['formname']) ? htmlspecialchars ($_GET['formname']) : 'message'));
  $value = (isset ($_POST['value']) ? htmlspecialchars ($_POST['value']) : (isset ($_GET['value']) ? htmlspecialchars ($_GET['value']) : 'message'));
  $lang->load ('finduser');
  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $username = trim ($_POST['username']);
    if (strlen ($username) < MINCHAR)
    {
      $error = sprintf ($lang->finduser['error1'], MINCHAR);
    }
    else
    {
      if (!validusername ($username))
      {
        $error = $lang->finduser['error2'];
      }
      else
      {
        $query = 'username LIKE ' . sqlesc ('' . '%' . $username . '%') . ' AND status=\'confirmed\' AND enabled=\'yes\'';
        ($search = sql_query ('' . 'SELECT username FROM users WHERE ' . $query . ' ORDER BY username ASC') OR sqlerr (__FILE__, 45));
        if (mysql_num_rows ($search) == '0')
        {
          $error = $lang->finduser['error3'];
        }
        else
        {
          while ($user = mysql_fetch_array ($search))
          {
            $users .= '<option value=\'' . htmlspecialchars ($user['username']) . '\'>' . htmlspecialchars ($user['username']) . '</option>
';
          }

          $results = true;
        }
      }
    }
  }

  $defaulttemplate = ts_template ();
  echo '<html>
<head>
<link rel="stylesheet" href="';
  echo $BASEURL;
  echo '/include/templates/';
  echo $defaulttemplate;
  echo '/style/style.css" type="text/css" media="screen" />
</head>
<body>
';
  echo '<s';
  echo 'cript language=javascript>
<!--
var message="";
///////////////////////////////////
function clickIE() {if (document.all) {(message);return false;}}
function clickNS(e) {if
(document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {(message);return false;}}}
if (document.layers)
{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
else{';
  echo 'document.onmouseup=clickNS;document.oncontextmenu=clickIE;}

document.oncontextmenu=new Function("return false")
// -->
</script>
';
  echo '<s';
  echo 'cript type="text/javascript">
<!--
function refresh_username(selected_username)
{
	opener.document.forms[\'';
  echo $formname;
  echo '\'].';
  echo $value;
  echo '.value = selected_username;
	opener.focus();
	window.close();
}
//-->
</script>
<br />
';
  echo '<s';
  echo 'cript type="text/javascript" src="';
  echo $BASEURL;
  echo '/scripts/prototype.js"></script>
';
  echo '<s';
  echo 'cript type="text/javascript" src="';
  echo $BASEURL;
  echo '/ratings/js/scriptaculous.js"></script>
';
  echo '<s';
  echo 'cript type="text/javascript" src="';
  echo $BASEURL;
  echo '/scripts/autocomplete.js"></script>
<form method="post" action="finduser.php">
<input type="hidden" name="formname" value="';
  echo $formname;
  echo '">
<input type="hidden" name="value" value="';
  echo $value;
  echo '">
<table width="300" cellspacing="0" cellpadding="3" align="center">
<tr><td class="colhead">';
  echo $lang->finduser['head'];
  echo '</td><tr>
<tr><td><input type="text" name="username" id="auto_keywords" autocomplete="off" size="30" ';
  echo ($_POST['username'] ? 'value="' . htmlspecialchars (trim ($_POST['username'])) . '"' : '');
  echo '>
';
  echo '<s';
  echo 'cript type="text/javascript">  new AutoComplete(\'auto_keywords\', \'ts_ajax.php?action=autocomplete&type=users&field=username&keyword=\', { delay: 0.25, resultFormat: AutoComplete.Options.RESULT_FORMAT_TEXT }); </script>
<input type="submit" value="';
  echo $lang->global['buttonsearch'];
  echo '" class=button></td></tr>
';
  if ($results)
  {
    echo '<tr><td align="left">';
    echo '<s';
    echo 'elect name="username_list">';
    echo $users;
    echo '</select> <input type="submit" onClick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);return false;" name="use" value="';
    echo $lang->global['buttonselect'];
    echo '" class=button></td></tr>
';
  }
  else
  {
    if ($error)
    {
      echo '<tr><td align="left"><font color="red">' . $error . '</font></td></tr>';
    }
  }

  echo '<tr><td align="left"><a href="javascript:window.close();"><font class="small">';
  echo $lang->global['buttonclosewindow'];
  echo '</font></a></td></tr>
</form>
</table>
</body>
</html>';
?>
