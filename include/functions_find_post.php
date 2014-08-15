<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function find_post ($pid)
  {
    global $CURUSER;
    global $BASEURL;
    if (!$pid)
    {
      return false;
    }

    require INC_PATH . '/readconfig_forumcp.php';
    if (($CURUSER['postsperpage'] AND $CURUSER['postsperpage'] <= 40))
    {
      $f_postsperpage = intval ($CURUSER['postsperpage']);
    }

    $query = sql_query ('SELECT tid FROM ' . TSF_PREFIX . 'posts WHERE pid = ' . sqlesc ($pid));
    if (mysql_num_rows ($query) < 1)
    {
      return false;
    }

    $tid = mysql_result ($query, 0, 'tid');
    if (!$tid)
    {
      return false;
    }

    $subres = sql_query ('SELECT COUNT(*) FROM ' . TSF_PREFIX . 'posts WHERE tid = ' . sqlesc ($tid) . ' AND pid < ' . sqlesc ($pid));
    $subrow = mysql_fetch_row ($subres);
    $count = $subrow[0];
    $page = floor ($count / $f_postsperpage);
    if ($page < 2)
    {
      ++$page;
    }

    return $BASEURL . '/tsf_forums/showthread.php?tid=' . $tid . '&page=' . $page . '#pid' . $pid;
  }

  if (!defined ('IN_TRACKER'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
