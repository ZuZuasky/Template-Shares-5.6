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

  define ('LC_VERSION', '0.6 by xam');
  require_once INC_PATH . '/commenttable.php';
  $limit = ($_POST['limit'] ? intval ($_POST['limit']) : 10);
  if ((empty ($limit) OR !is_valid_id ($limit)))
  {
    $limit = 10;
  }

  ($subres = sql_query ('' . 'SELECT c.id, c.text, c.user, c.added, c.editedby, c.editedat, c.modnotice, c.modeditid, c.modeditusername, c.modedittime, c.torrent as torrentid, t.name as torrentname, u.warned, u.username, u.title, u.usergroup, u.last_access, u.options, u.enabled, u.donor, u.uploaded, u.downloaded, g.title as grouptitle, g.namestyle, u.avatar as useravatar FROM comments c INNER JOIN torrents t ON (c.torrent=t.id) LEFT JOIN users u ON (c.user=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) ORDER BY c.id DESC LIMIT 0, ' . $limit) OR sqlerr (__FILE__, 27));
  $allrows = array ();
  while ($subrow = mysql_fetch_assoc ($subres))
  {
    $allrows[] = $subrow;
  }

  stdhead ('Latest Comments on ' . $SITENAME, true, 'supernote');
  _form_header_open_ ('Latest ' . $limit . ' Comments on ' . $SITENAME . '
<form method="post" action="' . $_this_script_ . '">
<input type="hidden" name="act" value="' . $act . '" />
<input type="text" name="limit" id="specialboxes" value="' . $limit . '" />
<input type="submit" value="Show" class="button" />
</form>');
  $useajax = 'no';
  commenttable ($allrows, NULL, NULL, true);
  _form_header_close_ ();
  stdfoot ();
?>
