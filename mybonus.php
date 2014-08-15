<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_mybonus_errors ()
  {
    global $errors;
    global $lang;
    if (0 < count ($errors))
    {
      $error = implode ('<br />', $errors);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $error . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  function show_mybonus_messages ()
  {
    global $messages;
    global $lang;
    if (0 < count ($messages))
    {
      $message = implode ('<br />', $messages);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['sys_message'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="green">
						<strong>
							' . $message . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  function update_user ($userid, $message)
  {
    $bonuscomment = sqlesc (get_date_time () . ' - ' . $message . '
');
    sql_query ('UPDATE users SET bonuscomment = CONCAT(' . $bonuscomment . ', bonuscomment) WHERE id = ' . sqlesc ($userid));
  }

  require 'global.php';
  define ('MB_VERSION', 'v2.0.1');
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  require_once INC_PATH . '/readconfig_kps.php';
  require_once INC_PATH . '/functions_pm.php';
  $lang->load ('mybonus');
  $is_mod = is_mod ($usergroups);
  $SeedPoints = $CURUSER['seedbonus'];
  $Userid = 0 + $CURUSER['id'];
  $errors = array ();
  $messages = array ();
  if (((($bonus == 'disable' AND !$is_mod) OR ($bonus == 'disablesave' AND !$is_mod)) OR $usergroups['canbonus'] != 'yes'))
  {
    stderr ($lang->global['error'], $lang->mybonus['disabled']);
  }

  if ((((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND isset ($_POST['BonusHash'])) AND strlen ($_POST['BonusHash']) == 20) AND $_SESSION['BonusHash'] === $_POST['BonusHash']))
  {
    require_once INC_PATH . '/class_page_check.php';
    $newpage = new page_verify ();
    $newpage->check ('mybonus');
    $ID = intval ($_POST['id']);
    $Query = sql_query ('SELECT id, bonusname, points, art, menge FROM bonus WHERE id = ' . sqlesc ($ID));
    if (mysql_num_rows ($Query) == 0)
    {
      $errors[] = $lang->mybonus['error1'];
    }
    else
    {
      $Result = mysql_fetch_assoc ($Query);
      if ($SeedPoints < $Result['points'])
      {
        $errors[] = sprintf ($lang->mybonus['error2'], $SeedPoints, $Result['points']);
      }
      else
      {
        $KPSUSED = false;
        $DONTCALC = false;
        switch ($Result['art'])
        {
          case 'traffic':
          {
            (sql_query ('UPDATE users SET uploaded = uploaded + ' . $Result['menge'] . ', seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 68));
            if (mysql_affected_rows ())
            {
              update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
              $KPSUSED = true;
            }

            break;
          }

          case 'invite':
          {
            if ($kpsinvite != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
            }
            else
            {
              (sql_query ('UPDATE users SET invites = invites + ' . $Result['menge'] . ', seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 82));
              if (mysql_affected_rows ())
              {
                update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
                $KPSUSED = true;
              }
            }

            break;
          }

          case 'title':
          {
            if ($_POST['update_title'] == 'yes')
            {
              $title = trim ($_POST['title']);
              if ($kpstitle != 'yes')
              {
                $errors[] = $lang->mybonus['error3'];
              }
              else
              {
                if (empty ($title))
                {
                  $errors[] = $lang->mybonus['error4'];
                }
                else
                {
                  require INC_PATH . '/readconfig_signup.php';
                  if (preg_match ('#' . $title . '#i', $illegalusernames))
                  {
                    $errors[] = $lang->mybonus['error5'];
                  }
                  else
                  {
                    if ($title == $CURUSER['title'])
                    {
                      $errors[] = $lang->mybonus['error6'];
                    }
                    else
                    {
                      (sql_query ('UPDATE users SET title = ' . sqlesc (htmlspecialchars_uni ($title)) . ', seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 115));
                      if (mysql_affected_rows ())
                      {
                        update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
                        $KPSUSED = true;
                      }
                    }
                  }
                }
              }
            }

            if ($kpstitle != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
            }
            else
            {
              if ($KPSUSED === false)
              {
                require_once INC_PATH . '/class_page_check.php';
                $newpage = new page_verify ();
                $newpage->create ('mybonus');
                $lang->mybonus['title'] = sprintf ($lang->mybonus['title'], $SITENAME);
                unset ($BonusHash);
                unset ($_SESSION[BonusHash]);
                $BonusHash = mksecret (20);
                $_SESSION['BonusHash'] = $BonusHash;
                stdhead ($lang->mybonus['title'] . ' - ' . sprintf ($lang->mybonus['left'], $SeedPoints), true, 'collapse');
                show_mybonus_errors ();
                echo '
						<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
						<input type="hidden" name="id" value="' . $Result['id'] . '" />
						<input type="hidden" name="BonusHash" value="' . $BonusHash . '" />
						<input type="hidden" name="update_title" value="yes" />
						<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td class="thead">' . ts_collapse ('title') . $lang->mybonus['title'] . '</td>
						</tr>
						' . ts_collapse ('title', 2) . '
							<tr>
								<td valign="top">
									' . $lang->mybonus['entertitle'] . ' <input type="text" size="50" name="title" value="' . htmlspecialchars_uni ($CURUSER['title']) . '" class="inlineimg" /> <input type="submit" value="' . $lang->mybonus['purchase'] . '" class="inlineimg" /> <input type="button" value="' . $lang->mybonus['cancel'] . '" class="inlineimg" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
								</td>
							</tr>
						</tbody>
						</table>
						</form>
						';
                stdfoot ();
                exit ();
              }
            }

            break;
          }

          case 'class':
          {
            if ($kpsvip != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
            }
            else
            {
              if (($is_mod OR $usergroups['isvipgroup'] == 'yes'))
              {
                $errors[] = $lang->mybonus['error11'];
              }
              else
              {
                $vip_until = get_date_time (gmtime () + 28 * 86400);
                (sql_query ('REPLACE INTO ts_auto_vip (userid, vip_until, old_gid) VALUES (\'' . $Userid . '\', \'' . $vip_until . '\', \'' . $CURUSER['usergroup'] . '\')') OR sqlerr (__FILE__, 180));
                if (mysql_affected_rows ())
                {
                  $KPSUSED = true;
                  (sql_query ('UPDATE users SET usergroup = \'' . UC_VIP . '\', seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 184));
                  update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
                }
                else
                {
                  $errors[] = $lang->global['dberror'];
                }
              }
            }

            break;
          }

          case 'gift_1':
          {
            if ($_POST['send_gift'] == 'yes')
            {
              $GIFT = intval ($_POST['gift']);
              $USERNAME = trim ($_POST['username']);
              if ($kpsgift != 'yes')
              {
                $errors[] = $lang->mybonus['error3'];
              }
              else
              {
                if (empty ($GIFT))
                {
                  $errors[] = $lang->mybonus['error7'];
                }
                else
                {
                  $SeedPoints = $SeedPoints - $Result['points'];
                  $DONTCALC = true;
                  if ($SeedPoints < $GIFT)
                  {
                    $errors[] = sprintf ($lang->mybonus['error8'], $SeedPoints, $GIFT);
                  }
                  else
                  {
                    if ($USERNAME == $CURUSER['username'])
                    {
                      $errors[] = $lang->mybonus['error10'];
                    }
                    else
                    {
                      ($Query = sql_query ('SELECT id FROM users WHERE username = ' . sqlesc ($USERNAME) . ' AND enabled = \'yes\' AND status = \'confirmed\'') OR sqlerr (__FILE__, 221));
                      if (mysql_num_rows ($Query) == 0)
                      {
                        $errors[] = $lang->mybonus['error9'];
                      }
                      else
                      {
                        $SUSERID = mysql_result ($Query, 0, 'id');
                        (sql_query ('UPDATE users SET seedbonus = seedbonus + ' . $GIFT . ' WHERE id = ' . sqlesc ($SUSERID)) OR sqlerr (__FILE__, 229));
                        if (mysql_affected_rows ())
                        {
                          update_user ($SUSERID, 'Gift: ' . $GIFT . ' points from ' . $CURUSER['username']);
                          send_pm ($SUSERID, sprintf ($lang->mybonus['giftmsg'], '[b]' . $USERNAME . '[/b]', '[URL=' . $BASEURL . '/userdetails.php?id=' . $Userid . '][b]' . $CURUSER['username'] . '[/b][/URL]', $GIFT), $lang->mybonus['giftsubject']);
                          (sql_query ('UPDATE users SET seedbonus = IF(seedbonus < ' . ($Result['points'] + $GIFT) . ', 0, seedbonus - ' . ($Result['points'] + $GIFT) . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 234));
                          if (mysql_affected_rows ())
                          {
                            update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points. (' . $GIFT . ' Points to ' . $USERNAME . ')');
                            $KPSUSED = true;
                            $SeedPoints = $SeedPoints - $GIFT;
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if ($kpsgift != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
            }
            else
            {
              if ($KPSUSED === false)
              {
                require_once INC_PATH . '/class_page_check.php';
                $newpage = new page_verify ();
                $newpage->create ('mybonus');
                $lang->mybonus['title'] = sprintf ($lang->mybonus['title'], $SITENAME);
                unset ($BonusHash);
                unset ($_SESSION[BonusHash]);
                $BonusHash = mksecret (20);
                $_SESSION['BonusHash'] = $BonusHash;
                stdhead ($lang->mybonus['title'] . ' - ' . sprintf ($lang->mybonus['left'], $SeedPoints), true, 'collapse');
                show_mybonus_errors ();
                echo '
						<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
						<input type="hidden" name="id" value="' . $Result['id'] . '" />
						<input type="hidden" name="BonusHash" value="' . $BonusHash . '" />
						<input type="hidden" name="send_gift" value="yes" />
						<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td class="thead" colspan="2">' . ts_collapse ('title') . $lang->mybonus['title'] . '</td>
						</tr>
						' . ts_collapse ('title', 2) . '
							<tr>
								<td valign="top" align="right">
									' . $lang->mybonus['username'] . '
								</td>
								<td>
									<input type="text" size="20" name="username" value="' . htmlspecialchars_uni ($USERNAME) . '" class="inlineimg" />
								</td>
							</tr>
							<tr>
								<td valign="top" align="right">
									' . $lang->mybonus['gift'] . '
								</td>
								<td>
									<input type="text" size="20" name="gift" value="' . htmlspecialchars_uni ($GIFT) . '" class="inlineimg" />
								</td>
							</tr>
							<tr>
								<td colspan="2" class="subheader" align="center">
									<input type="submit" value="' . $lang->mybonus['purchase'] . '" class="inlineimg" /> <input type="button" value="' . $lang->mybonus['cancel'] . '" class="inlineimg" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
								</td>
							</tr>
						</tbody>
						</table>
						</form>
						';
                stdfoot ();
                exit ();
              }
            }

            break;
          }

          case 'warning':
          {
            if ($kpswarning != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
            }
            else
            {
              if ($CURUSER['timeswarned'] < 1)
              {
                $errors[] = $lang->mybonus['error15'];
              }
              else
              {
                (sql_query ('UPDATE users SET timeswarned = IF(timeswarned < 1, 0, timeswarned - ' . $Result['menge'] . '), seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 317));
                if (mysql_affected_rows ())
                {
                  update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
                  $KPSUSED = true;
                }
              }
            }

            break;
          }

          case 'ratiofix':
          {
            if ($_POST['ratiofix'] == 'yes')
            {
              $TID = intval ($_POST['torrentid']);
              if ($kpsratiofix != 'yes')
              {
                $errors[] = $lang->mybonus['error3'];
              }
              else
              {
                if (!is_valid_id ($TID))
                {
                  $errors[] = $lang->mybonus['error12'];
                }
                else
                {
                  ($Query = sql_query ('SELECT uploaded, downloaded, seedtime FROM snatched WHERE torrentid = \'' . $TID . '\' AND finished = \'yes\' AND userid = \'' . $Userid . '\'') OR sqlerr (__FILE__, 340));
                  if (mysql_num_rows ($Query) == 0)
                  {
                    $errors[] = $lang->mybonus['error13'];
                  }
                  else
                  {
                    readconfig ('HITRUN');
                    $MinSeedTime = $HITRUN['MinSeedTime'] * 60 * 60;
                    unset ($HITRUN);
                    $SDetails = mysql_fetch_assoc ($Query);
                    if (($SDetails['downloaded'] <= $SDetails['uploaded'] AND $MinSeedTime <= $SDetails['seedtime']))
                    {
                      $errors[] = $lang->mybonus['error14'];
                    }
                    else
                    {
                      (sql_query ('UPDATE snatched SET uploaded = IF(uploaded < downloaded, downloaded, uploaded), seedtime = IF(seedtime < ' . $MinSeedTime . ', ' . $MinSeedTime . ', seedtime) WHERE torrentid = \'' . $TID . '\' AND finished = \'yes\' AND userid = \'' . $Userid . '\'') OR sqlerr (__FILE__, 357));
                      if (mysql_affected_rows ())
                      {
                        (sql_query ('UPDATE users SET seedbonus = IF(seedbonus < ' . $Result['points'] . ', 0, seedbonus - ' . $Result['points'] . ') WHERE id = ' . sqlesc ($Userid)) OR sqlerr (__FILE__, 360));
                        update_user ($Userid, 'Purchased item: ' . $Result['bonusname'] . ' for ' . $Result['points'] . ' points.');
                        $KPSUSED = true;
                      }
                      else
                      {
                        $errors[] = $lang->global['dberror'];
                      }
                    }
                  }
                }
              }
            }

            if ($kpsratiofix != 'yes')
            {
              $errors[] = $lang->mybonus['error3'];
              break;
            }
            else
            {
              if ($KPSUSED === false)
              {
                require_once INC_PATH . '/class_page_check.php';
                $newpage = new page_verify ();
                $newpage->create ('mybonus');
                $lang->mybonus['title'] = sprintf ($lang->mybonus['title'], $SITENAME);
                unset ($BonusHash);
                unset ($_SESSION[BonusHash]);
                $BonusHash = mksecret (20);
                $_SESSION['BonusHash'] = $BonusHash;
                stdhead ($lang->mybonus['title'] . ' - ' . sprintf ($lang->mybonus['left'], $SeedPoints), true, 'collapse');
                show_mybonus_errors ();
                echo '
						<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
						<input type="hidden" name="id" value="' . $Result['id'] . '" />
						<input type="hidden" name="BonusHash" value="' . $BonusHash . '" />
						<input type="hidden" name="ratiofix" value="yes" />
						<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td class="thead">' . ts_collapse ('title') . $lang->mybonus['title'] . '</td>
						</tr>
						' . ts_collapse ('title', 2) . '
							<tr>
								<td valign="top">
									' . $lang->mybonus['torrentid'] . ' <input type="text" size="10" name="torrentid" value="' . $TID . '" class="inlineimg" /> <input type="submit" value="' . $lang->mybonus['purchase'] . '" class="inlineimg" /> <input type="button" value="' . $lang->mybonus['cancel'] . '" class="inlineimg" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
								</td>
							</tr>
						</tbody>
						</table>
						</form>
						';
                stdfoot ();
                exit ();
              }
            }
          }
        }

        if ($KPSUSED === true)
        {
          if ($DONTCALC === false)
          {
            $SeedPoints = $SeedPoints - $Result['points'];
          }

          $messages[] = sprintf ($lang->mybonus['message1'], htmlspecialchars_uni ($Result['bonusname']));
        }
      }
    }
  }

  ($Query = sql_query ('SELECT * FROM bonus ORDER BY id') OR sqlerr (__FILE__, 427));
  if (mysql_num_rows ($Query) == 0)
  {
    stderr ($lang->global['error'], $lang->mybonus['disabled']);
  }

  $Count = 0;
  $AvailableOptions = '<tr>';
  $BonusHash = mksecret (20);
  $_SESSION['BonusHash'] = $BonusHash;
  while ($BOptions = mysql_fetch_assoc ($Query))
  {
    $Alert = '';
    if ($SeedPoints < $BOptions['points'])
    {
      $Alert = ' onclick="alert(\'' . sprintf ($lang->mybonus['error2'], $SeedPoints, $BOptions['points']) . '\'); return false;"';
    }

    if ($Count % 3 == 0)
    {
      $AvailableOptions .= '</tr><tr>';
    }

    $AvailableOptions .= '
	<td class="none" valign="top">
		<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="id" value="' . $BOptions['id'] . '" />
		<input type="hidden" name="BonusHash" value="' . $BonusHash . '" />
		<table border="0" cellpadding="2" cellspacing="0" width="290">
			<tr>
				<td class="subheader">' . ts_collapse ('subtitle' . $BOptions['id']) . htmlspecialchars_uni ($BOptions['bonusname']) . '</td>
			</tr>
			' . ts_collapse ('subtitle' . $BOptions['id'], 2) . '
				<tr>
					<td height="60" valign="top"><div align="justify">' . htmlspecialchars_uni ($BOptions['description']) . '</div></td>
				</tr>
				<tr>
					<td valign="bottom"><div style="float: right;"><input type="submit" value="' . $lang->mybonus['purchase'] . '" class="button"' . $Alert . ' /></div><div class="highlight" style="float: left;">' . sprintf ($lang->mybonus['required'], $BOptions['points']) . '</div></td>
			</tbody>
		</table>
		</form>
	</td>
	';
    ++$Count;
  }

  $AvailableOptions .= '</tr>';
  $lang->mybonus['title'] = sprintf ($lang->mybonus['title'], $SITENAME);
  $Main = '
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead">' . ts_collapse ('title') . $lang->mybonus['title'] . '</td>
	</tr>
	' . ts_collapse ('title', 2) . '
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellpadding="3" cellspacing="0">
					' . $AvailableOptions . '
				</table>
			</td>
		</tr>
	</tbody>
</table>
';
  require_once INC_PATH . '/class_page_check.php';
  $newpage = new page_verify ();
  $newpage->create ('mybonus');
  stdhead ($lang->mybonus['title'] . ' - ' . sprintf ($lang->mybonus['left'], $SeedPoints), true, 'collapse');
  show_mybonus_errors ();
  show_mybonus_messages ();
  echo $Main;
  stdfoot ();
?>
