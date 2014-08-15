<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class ts_template
  {
    function get_ts_template ($ts_templates_name = '')
    {
      $ts_templates = array ();
      $ts_templates_query = sql_query ('SELECT title, template FROM ts_templates WHERE name = ' . sqlesc ($ts_templates_name));
      while ($ts_templates_row = mysql_fetch_assoc ($ts_templates_query))
      {
        $ts_templates[$ts_templates_row['title']] = $ts_templates_row['template'];
      }

      if (0 < count ($ts_templates))
      {
        return $ts_templates;
      }

    }
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TS_CT_VERSION', 'v0.1 by xam');
?>
