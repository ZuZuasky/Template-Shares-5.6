<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function send_pm ($receiver = 0, $msg = '', $subject = '', $sender = 0, $saved = 'no', $location = '1', $unread = 'yes')
  {
    if (((($sender != 0 AND !is_valid_id ($sender)) OR !is_valid_id ($receiver)) OR empty ($msg)))
    {
      return null;
    }

    sql_query ('' . '
					INSERT INTO messages 
						(sender, receiver, added, subject, msg, unread, saved, location)
						VALUES 
						(\'' . $sender . '\', \'' . $receiver . '\', NOW(), ' . sqlesc ($subject) . ', ' . sqlesc ($msg) . ('' . ', \'' . $unread . '\', \'' . $saved . '\', \'' . $location . '\')
					'));
    sql_query ('' . 'UPDATE users SET pmunread = pmunread + 1 WHERE id = \'' . $receiver . '\'');
  }

  if (!function_exists ('is_valid_id'))
  {
    function is_valid_id ($id)
    {
      return ((is_numeric ($id) AND 0 < $id) AND floor ($id) == $id);
    }
  }

  if (!function_exists ('sql_query'))
  {
    function sql_query ($query)
    {
      return mysql_query ($query);
    }
  }

?>
