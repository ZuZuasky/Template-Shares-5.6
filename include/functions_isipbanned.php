<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function isipbanned ($against = '')
  {
    global $aggressivecheckip;
    global $cache;
    require TSDIR . '/' . $cache . '/ipbans.php';
    if ((is_array ($ipbanscache) AND 0 < count ($ipbanscache)))
    {
      $banarray = @explode (' ', @preg_replace ('/[[:space:]]+/', ' ', @trim ($ipbanscache['value'])));
      if ((is_array ($banarray) AND 0 < count ($banarray)))
      {
        $gethostbyaddr = (($aggressivecheckip == 'yes' AND isvalidip ($against)) ? @gethostbyaddr ($against) : '');
        foreach ($banarray as $cban)
        {
          if (strpos ($cban, '*') === false)
          {
            if (($cban === $against OR @strstr ($against, $cban)))
            {
              return true;
            }

            if (($aggressivecheckip == 'yes' AND ($cban === $gethostbyaddr OR @strstr ($gethostbyaddr, $cban))))
            {
              return true;
              continue;
            }

            continue;
          }
          else
          {
            $regexp = str_replace (array ('.', '*'), array ('\\.', '.+'), $cban);
            if (eregi (('' . '^' . $regexp . '$'), $against))
            {
              return true;
            }

            if (($aggressivecheckip == 'yes' AND eregi (('' . '^' . $regexp . '$'), $gethostbyaddr)))
            {
              return true;
              continue;
            }

            continue;
          }
        }

        unset ($banarray);
        unset ($gethostbyaddr);
        unset ($cban);
        unset ($regexp);
      }
    }

    return false;
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
