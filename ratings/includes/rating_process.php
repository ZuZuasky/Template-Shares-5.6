<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('SKIP_LOCATION_SAVE', true);
  define ('DEBUGMODE', false);
  $rootpath = './../../';
  require_once $rootpath . 'global.php';
  dbconn ();
  if ((!$CURUSER OR $usergroups['canrate'] != 'yes'))
  {
    exit ();
  }

  header ('Expires: Sat, 1 Jan 2000 01:00:00 GMT');
  header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . 'GMT');
  header ('Cache-Control: no-cache, must-revalidate');
  header ('Pragma: no-cache');
  header ('' . 'Content-type: text/html; charset=' . $shoutboxcharset);
  $expire = time () + 99999999;
  $domain = ($_SERVER['HTTP_HOST'] != 'localhost' ? $_SERVER['HTTP_HOST'] : false);
  if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
  {
    $RType = (int)$_POST['type'];
    $rating_id = (int)$_POST['ratingid'];
    $rating = intval ($_POST['rated']);
    if ((((($rating <= 5 AND 1 <= $rating) AND is_valid_id ($rating_id)) AND is_valid_id ($rating)) AND is_valid_id ($RType)))
    {
      if ((@mysql_fetch_assoc (@mysql_query ('' . 'SELECT id FROM ratings WHERE type = \'' . $RType . '\' AND userid = ' . @sqlesc ($CURUSER['id']) . ' AND rating_id = ' . @sqlesc ($rating_id))) OR isset ($_COOKIE['has_voted_' . $rating_id . '_' . $RType])))
      {
        $lang->load ('userdetails');
        exit ($lang->userdetails['alreadyvotes']);
      }
      else
      {
        if (($RType == '2' AND $CURUSER['id'] == $rating_id))
        {
          $lang->load ('userdetails');
          exit ($lang->userdetails['rateerror']);
        }
        else
        {
          @setcookie ('has_voted_' . $rating_id . '_' . $RType, $rating_id, $expire, '/', $domain, false);
          mysql_query ('' . 'INSERT INTO ratings (rating_id,rating_num,userid,type) VALUES (\'' . $rating_id . '\',\'' . $rating . '\',\'' . $CURUSER['id'] . ('' . '\',\'' . $RType . '\')'));
          include_once INC_PATH . '/readconfig_kps.php';
          kps ('+', $kpsrate, $CURUSER['id']);
        }
      }
    }
  }

  exit ();
?>
