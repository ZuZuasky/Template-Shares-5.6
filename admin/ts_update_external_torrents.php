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

  define ('TUET_VERSION', '0.3 by xam');
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : 1));
  $wait = (isset ($_POST['wait']) ? intval ($_POST['wait']) : (isset ($_GET['wait']) ? intval ($_GET['wait']) : 30));
  if ($do == 1)
  {
    unset ($_SESSION[updated]);
    stdhead ('Update External Torrents');
    $count = tsrowcount ('id', 'torrents', 'ts_external = \'yes\'');
    _form_header_open_ ('Update External Torrents');
    if ($count < 1)
    {
      echo '<tr><td>There is no external torrent to update!</td></tr>';
    }
    else
    {
      $externalpreview = '<div id=\'loading-layer\' style=\'position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Updating... Please wait...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>';
      echo '<tr><td>You can update number of seeders and leechers of any torrent by clicking on "UPDATE". Please notice, that it can take up to 30 seconds for each torrent, depending on the tracker\'s response speed. Don\'t close this window until the end of the update (or stop it by clicking "STOP").<br /><br />
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="ts_update_external_torrents">
		<input type="hidden" name="do" value="2">
		Wait before Update: <input type="text" name="wait" value="30" size="3"> seconds 
		<input type="submit" name="submit" value="UPDATE" onclick="ts_show(\'loading-layer\')">
		</form>
		' . $externalpreview . '</td></tr>';
    }

    _form_header_close_ ();
    stdfoot ();
    exit ();
    return 1;
  }

  stdhead ('Update External Torrents');
  _form_header_open_ ('' . 'Update External Torrents - <a href="' . $_this_script_ . '&do=1">STOP');
  $lang->load ('upload');
  $query = sql_query ('SELECT ts_external_lastupdate, id, name FROM torrents WHERE ts_external = \'yes\'');
  $skipped = '<font color="red"><b>UP-TO-DATE!</b></font>';
  $updated = '<font color="darkgreen"><b>UPDATED!</b></font>';
  $count = 0;
  $script = '';
  $externalpreview = '<div id=\'loading-layer\' style=\'position: absolute; display:block; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000\'><div style=\'font-weight:bold\' id=\'loading-layer-text\' class=\'small\'>Updating... Please wait...</div><br /><img src=\'' . $BASEURL . '/' . $pic_base_url . 'await.gif\' border=\'0\' /></div>';
  while ($et = mysql_fetch_assoc ($query))
  {
    if ($count == 0)
    {
      echo '<tr><td align="right"><b>Torrent:</b></td><td align="left">' . htmlspecialchars_uni ($et['name']);
      $ts_external_lastupdate = $et['ts_external_lastupdate'];
      $id = $et['id'];
      if ((time () - $ts_external_lastupdate < 3600 OR $_SESSION['updated'][$et['id']]))
      {
        $skip = true;
        $message = $skipped;
      }
      else
      {
        $skip = false;
        $message = $updated;
      }

      if (!$skip)
      {
        $externaltorrent = TSDIR . '/' . $torrent_dir . '/' . $id . '.torrent';
        include_once INC_PATH . '/ts_external_scrape/ts_external.php';
        echo '<br />Announce Url: ' . htmlspecialchars_uni ($announce) . '<br />Scrape Url: ' . htmlspecialchars_uni ($httpurl) . '<br />Seeders: ' . ts_nf ($e_seeders) . ' / Leechers: ' . ts_nf ($e_leechers) . '</td>';
        $count = 1;
        $_SESSION['updated'][$et['id']] = 1;
      }

      echo '<td align="center">' . $message . '</td></tr>';
      continue;
    }
    else
    {
      echo '<tr><td colspan="3"><div id="waitmessage">Please wait...</div></td></tr>';
      echo '			';
      echo '<s';
      echo 'cript language="javascript">
				x6115=';
      echo $wait;
      echo ';
				function countdown() 
				{
					if ((0 <= 100) || (0 > 0))
					{
						x6115--;
						if(x6115 == 0)
						{
							document.getElementById("waitmessage").innerHTML = "';
      echo $externalpreview;
      echo '";
							jumpto(\'';
      echo $_this_script_;
      echo '&do=2&wait=';
      echo $wait;
      echo '\');
						}
						if(x6115 > 0)
						{
							document.getElementById("waitmessage").innerHTML = \'Please wait <font size="3"><b>\'+x6115+\'</b></font> seconds..\';
							setTimeout(\'countdown()\',1000);
						}
					}
				}
				countdown();
			</script>
			';
      break;
    }
  }

  _form_header_close_ ();
  stdfoot ();
  exit ();
?>
