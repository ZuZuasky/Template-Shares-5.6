<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function hash_pad ($hash)
  {
    return str_pad ($hash, 20);
  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  define ('CE_VERSION', 'v.0.3');
  $lang->load ('confirmemail');
  $id = (int)$_GET['id'];
  $md5 = $_GET['hash'];
  $email = urldecode ($_GET['email']);
  if (((empty ($id) OR !is_valid_id ($id)) OR strlen ($md5) != 32))
  {
    stderr ($lang->global['error'], $lang->confirmemail['error1']);
  }

  $res = sql_query ('SELECT editsecret FROM ts_user_validation WHERE userid = ' . sqlesc ($id));
  $row = mysql_fetch_assoc ($res);
  if (empty ($row))
  {
    stderr ($lang->global['error'], $lang->confirmemail['error2']);
  }

  $sec = hash_pad ($row['editsecret']);
  if (preg_match ('/^ *$/s', $sec))
  {
    stderr ($lang->global['error'], $lang->confirmemail['error2']);
  }

  if ($md5 != md5 ($sec . $email . $sec))
  {
    stderr ($lang->global['error'], $lang->confirmemail['error3']);
  }

  sql_query ('UPDATE users SET email = ' . sqlesc ($email) . ' WHERE id = ' . sqlesc ($id));
  if (!mysql_affected_rows ())
  {
    stderr ($lang->global['error'], $lang->confirmemail['error4']);
  }

  sql_query ('DELETE FROM ts_user_validation WHERE userid = ' . sqlesc ($id));
  header ('' . 'Location: ' . $BASEURL . '/usercp.php?action=security&type=saved&mailchanged=1');
?>
