<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_faq_errors ()
  {
    global $faq_errors;
    global $lang;
    if (0 < count ($faq_errors))
    {
      $errors = implode ('<br />', $faq_errors);
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
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TSATS_VERSION', 'v0.2 by xam');
  $do = (isset ($_GET['do']) ? intval ($_GET['do']) : (isset ($_POST['do']) ? intval ($_POST['do']) : 0));
  $faq_errors = array ();
  require $thispath . 'include/global_config.php';
  if ($do == 1)
  {
    $torrentid = intval ($_POST['torrentid']);
    $tracker = htmlspecialchars_uni ($_POST['tracker']);
    if ((!is_valid_id ($torrentid) OR !file_exists (TSDIR . '/' . $torrent_dir . '/' . $torrentid . '.torrent')))
    {
      $faq_errors[] = 'The specific torrent does not exists!';
    }

    if (empty ($tracker))
    {
      $faq_errors[] = 'Please select a Tracker!';
    }

    $validtracker = false;
    foreach ($config['ts_auto_torrent_submit'] as $name => $url)
    {
      if ($tracker == $name)
      {
        $trackerurl = $url;
        $validtracker = true;
        break;
      }
    }

    if (!$validtracker)
    {
      $faq_errors[] = 'Invalid Tracker!';
    }

    if (0 < count ($faq_errors))
    {
      $do = 0;
    }
    else
    {
      stdhead ('TS Auto Torrent Submitter - ' . TEU_VERSION);
      _form_header_open_ ('TS Auto Torrent Submitter');
      echo '
		<tr>
			<td class="subheader" align="center">
			Upload INSTRUCTIONS!
			</td>
		</tr>
		<tr>
			<td>
				<ul>
					<li>Download the torrent to your computer by clicking <a href="' . $BASEURL . '/download.php?id=' . $torrentid . '&amp;fromadminpanel=true"><b>here</b></a>.</li>					
					<li>Select torrent file from your computer which you was downloaded by clicking above link. (see frame window below)</li>
					<li>Enter torrent name, category, description etc.. (see frame window below)</li>
					<li>Click on upload button. (see frame window below)</li>
					<li>Done!</li>
				</ul>
				<div align="center">
					Click <a href="' . $_this_script_ . '" target="_self">here</a> to upload another torrent.
				</div>
			</td>
		</tr>
		';
      _form_header_close_ ();
      echo '<br />';
      _form_header_open_ ('Upload Frame for: ' . $trackerurl);
      echo '
		<tr>
			<td>
				<iframe src ="' . $trackerurl . '" width="100%" height="600"></iframe>
			</td>
		</tr>
		';
      _form_header_close_ ();
      stdfoot ();
    }
  }

  if ($do == 0)
  {
    $torrents = '<select name="torrentid">';
    ($query = sql_query ('SELECT id, name FROM torrents WHERE ts_external = \'no\'') OR sqlerr (__FILE__, 133));
    while ($torrent = mysql_fetch_assoc ($query))
    {
      $torrents .= '<option value="' . intval ($torrent['id']) . '">' . htmlspecialchars_uni ($torrent['name']) . '</option>';
    }

    $torrents .= '</select>';
    $trackers = '<select name="tracker">';
    foreach ($config['ts_auto_torrent_submit'] as $name => $url)
    {
      $trackers .= '<option value="' . $name . '">' . $name . '</option>';
    }

    $trackers .= '</select>';
    stdhead ('TS Auto Torrent Submitter - ' . TEU_VERSION);
    show_faq_errors ();
    $str = '<form method="post" action="' . $_this_script_ . '">
	<input type="hidden" name="do" value="1"';
    _form_header_open_ ('TS Auto Torrent Submitter', 2);
    $str .= '<tr><td align="right" class="trow1" width="30%">Select Torrent:</td><td align="left" width="70%">' . $torrents . '</td></tr>';
    $str .= '<tr><td align="right" class="trow1" width="30%">Select Tracker:</td><td align="left" width="70%">' . $trackers . '</td></tr>';
    $str .= '<tr><td colspan="2" align="center"><input type="submit" value="Create torrent & Get upload details"></td></tr>';
    $str .= '</form>';
    echo $str;
    _form_header_close_ ();
    stdfoot ();
  }

?>
