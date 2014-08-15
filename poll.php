<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  gzip ();
  dbconn ();
  $lang->load ('poll');
  define ('P_VERSION', 'v.0.1');
  $do = (isset ($_GET['do']) ? $_GET['do'] : (isset ($_POST['do']) ? $_POST['do'] : ''));
  if (($do == 'showresults' AND is_valid_id ($_GET['pollid'])))
  {
    setcookie ('showpollresult', intval ($_GET['pollid']), time () + 30);
    redirect ('index.php#showtspoll');
    exit ();
  }

  if ($do == 'pollvote')
  {
    $pollid = intval ($_POST['pollid']);
    ($Query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 35));
    $pollinfo = mysql_fetch_assoc ($Query);
    if (!$pollinfo['pollid'])
    {
      stderr ($lang->global['error'], $lang->poll['invalid']);
    }

    if ((0 < $CURUSER['id'] AND $usergroups['canvote'] != 'yes'))
    {
      print_no_permission ();
    }

    if ((!$pollinfo['active'] OR ($pollinfo['dateline'] + $pollinfo['timeout'] * 86400 < TIMENOW AND $pollinfo['timeout'] != 0)))
    {
      stderr ($lang->global['error'], $lang->poll['closed2']);
    }

    if (!empty ($_POST['optionnumber']))
    {
      if (!$CURUSER['id'])
      {
        if (isset ($_COOKIE['poll_voted_' . $pollid]))
        {
          stderr ($lang->global['error'], $lang->poll['avoted']);
        }
      }
      else
      {
        ($Query = sql_query ('
			SELECT userid
			FROM ' . TSF_PREFIX . 'pollvote
			WHERE userid = ' . $CURUSER['id'] . ('' . '
				AND pollid = \'' . $pollid . '\'
			')) OR sqlerr (__FILE__, 73));
        if (0 < mysql_num_rows ($Query))
        {
          stderr ($lang->global['error'], $lang->poll['avoted']);
        }
      }

      $totaloptions = substr_count ($pollinfo['options'], '~~~') + 1;
      if ($pollinfo['multiple'])
      {
        $skip_voters = false;
        foreach ($_POST['optionnumber'] as $val => $vote)
        {
          $Queries = array ();
          $val = intval ($val);
          if ((($vote AND 0 < $val) AND $val <= $totaloptions))
          {
            $Queries[] = 'pollid = \'' . $pollid . '\'';
            if (!$CURUSER['id'])
            {
              $Queries[] = 'userid = \'0\'';
            }
            else
            {
              $Queries[] = 'userid = \'' . $CURUSER['id'] . '\'';
            }

            $Queries[] = 'votedate = \'' . TIMENOW . '\'';
            $Queries[] = 'voteoption = \'' . $val . '\'';
            $Queries[] = 'votetype = \'' . $val . '\'';
            ($Query = sql_query ('INSERT INTO ' . TSF_PREFIX . 'pollvote SET ' . implode (',', $Queries)) OR sqlerr (__FILE__, 106));
            if (!$Query)
            {
              stderr ($lang->global['error'], $lang->poll['poll11']);
            }

            if ($skip_voters)
            {
              ($Query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 114));
              $pollinfo = mysql_fetch_assoc ($Query);
            }

            $old_votes_array = explode ('~~~', $pollinfo['votes']);
            ++$old_votes_array[$val - 1];
            $new_votes_array = implode ('~~~', $old_votes_array);
            (sql_query ('UPDATE ' . TSF_PREFIX . 'poll SET ' . (!$skip_voters ? 'voters = voters + 1, lastvote = \'' . TIMENOW . '\', ' : '') . 'votes = ' . sqlesc ($new_votes_array) . ('' . ' WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'')) OR sqlerr (__FILE__, 120));
            $skip_voters = true;
            continue;
          }
        }
      }
      else
      {
        if (((is_valid_id ($_POST['optionnumber']) AND 0 < $_POST['optionnumber']) AND $_POST['optionnumber'] <= $totaloptions))
        {
          $Queries = array ();
          $Queries[] = 'pollid = \'' . $pollid . '\'';
          if (!$CURUSER['id'])
          {
            $Queries[] = 'userid = \'0\'';
          }
          else
          {
            $Queries[] = 'userid = \'' . $CURUSER['id'] . '\'';
          }

          $Queries[] = 'votedate = \'' . TIMENOW . '\'';
          $Queries[] = 'voteoption = \'' . intval ($_POST['optionnumber']) . '\'';
          $Queries[] = 'votetype = \'0\'';
          ($Query = sql_query ('INSERT INTO ' . TSF_PREFIX . 'pollvote SET ' . implode (',', $Queries)) OR sqlerr (__FILE__, 142));
          if (!$Query)
          {
            stderr ($lang->global['error'], $lang->poll['poll11']);
          }

          $old_votes_array = explode ('~~~', $pollinfo['votes']);
          ++$old_votes_array[intval ($_POST['optionnumber']) - 1];
          $new_votes_array = implode ('~~~', $old_votes_array);
          (sql_query ('UPDATE ' . TSF_PREFIX . 'poll SET voters = voters + 1, lastvote=\'' . TIMENOW . '\', votes = ' . sqlesc ($new_votes_array) . ('' . ' WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'')) OR sqlerr (__FILE__, 150));
        }
      }

      if (0 < $CURUSER['id'])
      {
        include_once INC_PATH . '/readconfig_kps.php';
        kps ('+', $kpspoll, $CURUSER['id']);
      }

      setcookie ('poll_voted_' . $pollid, $pollid, time () + 12 * 7 * 24 * 60 * 60 * 60);
      redirect ('index.php#showtspoll', $lang->poll['thx']);
      exit ();
      return 1;
    }

    stderr ($lang->global['error'], $lang->poll['nselected']);
  }

?>
