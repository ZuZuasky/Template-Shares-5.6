<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function mysql_modified_rows ()
  {
    $info_str = mysql_info ();
    $a_rows = mysql_affected_rows ();
    ereg ('Rows matched: ([0-9]*)', $info_str, $r_matched);
    return ($a_rows < 1 ? ($r_matched[1] ? $r_matched[1] : 0) : $a_rows);
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('UG_VERSION', 'v0.2 by xam');
  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    $success = false;
    $query = trim ($_POST['query']);
    if (!empty ($query))
    {
      $doquery = mysql_query ($query);
      if ($doquery)
      {
        $count = mysql_modified_rows ();
        $success = true;
      }
      else
      {
        $errormsg = htmlspecialchars_uni (mysql_errno () . ': ' . mysql_error ());
      }
    }
  }

  if (isset ($success))
  {
    if ($success)
    {
      $msg = '<br /><br /><font color="white"><b>Your query executed without any problem! Affected rows: ' . (0 < $count ? $count : 0) . '</b></font>';
    }
    else
    {
      $msg = '<br /><br /><font color="darkred"><b>' . $errormsg . '</b></font>';
    }
  }

  echo '
<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type="hidden" name="do" value="ts_execute_sql_query">
<textarea name="query" cols="150" rows="10" class="bginput">' . $query . '</textarea>
<br /><br />
<input type="submit" value="run query">' . $msg . '
</form>
';
?>
