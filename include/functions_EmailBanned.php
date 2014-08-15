<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function emailbanned ($against)
  {
    global $aggressivecheckemail;
    $email = trim (strtolower ($against));
    $sql = sql_query ('SELECT value FROM bannedemails LIMIT 0,1');
    $bannedemails = mysql_fetch_assoc ($sql);
    $bannedemails = $bannedemails['value'];
    if ($bannedemails !== NULL)
    {
      $bannedemails = @preg_split ('/\\s+/', $bannedemails, 0 - 1, PREG_SPLIT_NO_EMPTY);
      foreach ($bannedemails as $bannedemail)
      {
        if (check_email ($bannedemail))
        {
          $regex = '^' . @preg_quote ($bannedemail, '#') . '$';
        }
        else
        {
          $regex = @preg_quote ($bannedemail, '#') . ($aggressivecheckemail == 'yes' ? '' : '$');
        }

        if (@preg_match ('' . '#' . $regex . '#i', $email))
        {
          return true;
        }
      }
    }

    return false;
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
