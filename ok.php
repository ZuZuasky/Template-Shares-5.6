<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('OK_VERSION', '0.4');
  require_once 'global.php';
  gzip ();
  dbconn ();
  if (0 < $CURUSER['id'])
  {
    header ('Location: ' . $BASEURL . '/index.php');
    exit ();
  }

  require INC_PATH . '/functions_getvar.php';
  getvar (array ('type', 'email'));
  if (empty ($type))
  {
    print_no_permission ();
  }

  $lang->load ('ok');
  if ($type == 'adminactivate')
  {
    stdhead ($lang->ok['head']);
    stdmsg ($lang->ok['title'], $lang->ok['adminactivate']);
    stdfoot ();
    return 1;
  }

  if (($type == 'signup' AND !empty ($email)))
  {
    stdhead ($lang->ok['head']);
    stdmsg ($lang->ok['title'], sprintf ($lang->ok['signupemail'], trim (htmlspecialchars ($email)), $SITENAME), false);
    stdfoot ();
    return 1;
  }

  if ($type == 'sysop')
  {
    stdhead ($lang->ok['head2']);
    if (isset ($CURUSER))
    {
      stdmsg ($lang->ok['title2'], sprintf ($lang->ok['sysopact'], $BASEURL), false);
    }
    else
    {
      stdmsg ($lang->ok['title2'], sprintf ($lang->ok['sysopact2'], $BASEURL), false);
    }

    stdfoot ();
    return 1;
  }

  if ($type == 'confirmed')
  {
    stdhead ($lang->ok['head']);
    stdmsg ($lang->ok['title3'], sprintf ($lang->ok['confirmed'], $BASEURL), false);
    stdfoot ();
    return 1;
  }

  if ($type == 'confirm')
  {
    if (isset ($CURUSER))
    {
      stdhead ($lang->ok['head']);
      stdmsg ($lang->ok['title4'], sprintf ($lang->ok['confirmed2'], $BASEURL, $SITENAME), false);
      stdfoot ();
      return 1;
    }

    stdhead ($lang->ok['head']);
    stdmsg ($lang->ok['title4'], sprintf ($lang->ok['confirmed3'], $BASEURL), false);
    stdfoot ();
    return 1;
  }

  print_no_permission ();
?>
