<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  $rootpath = './../';
  require $rootpath . 'global.php';
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  parked ();
  $lang->load ('shoutcast');
  define ('TS_SHOUTCAST', true);
  require 'setup.php';
  stdhead ($scdef . ': ' . htmlspecialchars_uni ($servertitle));
  $days = array (1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');
  $todayis = date ('D');
  $timenow = date ('G:i');
  if (!$scfp)
  {
    echo '
	<div style="float: right;">
		<input type="button" value="' . $lang->shoutcast['refresh'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
		<input type="button" value="' . $lang->shoutcast['djlist'] . '" onclick="jumpto(\'dj.php?do=list\'); return false;" />
		<input type="button" value="' . $lang->shoutcast['bedj'] . '" onclick="jumpto(\'dj.php?do=request\'); return false;" />
	</div>
	<br /><br />
	' . show_notice ($lang->shoutcast['down'], true, $scdef . ' - ' . $lang->shoutcast['offline']);
  }
  else
  {
    if ($streamstatus === '1')
    {
      ($Query = sql_query ('SELECT uid, activedays, activetime, genre, u.username, g.namestyle FROM ts_shoutcastdj LEFT JOIN users u ON (uid=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE active = \'1\' ORDER by activetime') OR sqlerr (__FILE__, 53));
      if (mysql_num_rows ($Query))
      {
        $activedjlist = '
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td class="subheader">' . $lang->shoutcast['djname'] . '</td>
					<td class="subheader">' . $lang->shoutcast['adays'] . '</td>
					<td class="subheader">' . $lang->shoutcast['atime'] . '</td>
					<td class="subheader">' . $lang->shoutcast['genre'] . '</td>
				</tr>';
        $Found = false;
        $ISDJ = false;
        while ($List = mysql_fetch_assoc ($Query))
        {
          if ($List['uid'] == $CURUSER['id'])
          {
            $ISDJ = true;
          }

          if (preg_match ('@' . $todayis . '@Ui', $List['activedays']))
          {
            $Found = true;
            $activedjlist .= '
					<tr>
						<td><a href="' . ts_seo ($List['uid'], $List['username']) . '">' . get_user_color ($List['username'], $List['namestyle']) . '</a></td>
						<td>' . htmlspecialchars_uni ($List['activedays']) . '</td>
						<td>' . htmlspecialchars_uni ($List['activetime']) . '</td>
						<td>' . htmlspecialchars_uni ($List['genre']) . '</td>
					</tr>
					';
            continue;
          }
        }

        if (!$Found)
        {
          $activedjlist = $lang->shoutcast['down2'];
        }
        else
        {
          $activedjlist .= '
				</table>';
        }
      }
      else
      {
        $activedjlist = $lang->shoutcast['down2'];
      }

      echo '
		<script type="text/javascript" src="' . $BASEURL . '/scripts/prototype.js?v=' . O_SCRIPT_VERSION . '"></script>
		<script type="text/javascript">
			function AutoRefreshLS()
			{
				new Ajax.Request("ajax_refresh.php",
				{
					method: "POST",
					contentType: "application/x-www-form-urlencoded",
					encoding: charset,
					onLoading: function()
					{
						Element.show("loadingimg");
					},
					onSuccess: function(transport)
					{
						var result = transport.responseText;
						if(result.match(/<error>(.*)<\\/error>/))
						{
							message=result.match(/<error>(.*)<\\/error>/);
							if(!message[1])
							{
								message[1]=l_ajaxerror;
							}
							alert(l_updateerror+message[1]);
							Element.hide("loadingimg");
						}
						else
						{
							$("latestsongs").innerHTML = result;
							Element.hide("loadingimg");
						}
					},		
					onFailure: function ()
					{
						alert(l_ajaxerror);
						Element.hide("loadingimg");
					}
				});
			}
			setInterval("AutoRefreshLS()", 35000);
		</script>
		<script type="text/javascript" src="flashradioswfobject.js"></script>
		
		<script type="text/javascript">
			function scastmp()
			{
				scastmpWindow = window.open("play.php?type=mp","mp","width=360,height=80");
			}
			function scastrp()
			{
				scastrpWindow = window.open("play.php?type=rp","rp","width=420,height=160");
			}
			function scastqt()
			{
				scastqtWindow = window.open("play.php?type=qt","qt","width=330,height=50");
			}
		</script>
		<div style="float: right; padding-bottom: 5px;">
			' . ($ISDJ ? '
			<input type="button" value="' . $lang->shoutcast['manage'] . '" onclick="jumpto(\'dj.php?do=manage\'); return false;" />' : '') . '
			<input type="button" value="' . $lang->shoutcast['refresh'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
			<input type="button" value="' . $lang->shoutcast['djlist'] . '" onclick="jumpto(\'dj.php?do=list\'); return false;" />
			<input type="button" value="' . $lang->shoutcast['bedj'] . '" onclick="jumpto(\'dj.php?do=request\'); return false;" />
		</div>
		<table align="center" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr valign="top">
					
					<td valign="top" width="200" class="none">

					<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">' . $lang->shoutcast['tunein'] . '</td>
								</tr>
								<tr>
									<td>
										<img class="inlineimg" src="images/im_winamp.gif" alt="' . $lang->shoutcast['listen1'] . '" border="0" hspace="2" /><a href="' . $listenamp . '">' . $lang->shoutcast['player1'] . '</a><br />
										<img class="inlineimg" src="images/im_real.gif" alt="' . $lang->shoutcast['listen2'] . '" border="0" hspace="2" /><a href="javascript:scastrp()">' . $lang->shoutcast['player2'] . '</a><br />
										<img class="inlineimg" src="images/im_winmp.gif" alt="' . $lang->shoutcast['listen3'] . '" border="0" hspace="2" /><a href="javascript:scastmp()">' . $lang->shoutcast['player3'] . '</a><br />
										<img class="inlineimg" src="images/im_qt.gif" alt="' . $lang->shoutcast['listen4'] . '" border="0" hspace="2" /><a href="javascript:scastqt()">' . $lang->shoutcast['player4'] . '</a>
									</td>
								</tr>
							</table>
						</div>

						<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">' . $lang->shoutcast['stream'] . '</td>
								</tr>
								<tr>
									<td>
										<div >' . sprintf ($lang->shoutcast['stream2'], 0 + $peaklisteners, 0 + $currentlisteners, 0 + $maxlisteners, 0 + $bitrate, htmlspecialchars_uni ($content), 0 + $streamhits, 0 + $averagemin) . '</div>
									</td>
								</tr>
							</table>
						</div>

						<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">' . $lang->shoutcast['sdetails'] . '</td>
								</tr>
								<tr>
									<td>
										<img class="inlineimg" src="images/im_genre.gif" alt="' . $lang->shoutcast['genre'] . '" /> ' . htmlspecialchars_uni ($servergenre) . '<br /><img class="inlineimg" src="images/im_icq.gif" alt="' . $lang->shoutcast['icq'] . '" /> ' . htmlspecialchars_uni ($icq) . '<br /><img class="inlineimg" src="images/im_aim.gif" alt="' . $lang->shoutcast['aim'] . '" /> ' . htmlspecialchars_uni ($aim) . '<br /><img class="inlineimg" src="images/im_mirc.gif" alt="' . $lang->shoutcast['irc'] . '" /> <a href="' . htmlspecialchars_uni ($irclink) . '">' . htmlspecialchars_uni ($ircsite) . '</a> : ' . htmlspecialchars_uni ($irc) . '
									</td>
								</tr>
							</table>
						</div>

					</td>

					<td valign="top" class="none" style="padding-left: 10px">
							
						<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">' . $lang->shoutcast['playing'] . '</td>
								</tr>
								<tr>
									<td align="center">
											<div  id="flashcontent">
											</div>
											<script type="text/javascript">
												var browser=navigator.appName;
												var res = "";
												browser = browser.toLowerCase();
												if(browser=="netscape")
												{
													res = "opened_by=MOZILLA";		
												}
												else
												{
													res = "opened_by=IE";
												}

												var webradio = new SWFObject("flashradio.swf?"+res+"&xml=flashradioconfig.xml&player_skin=flashradiotemplate.swf", "flashradiotemplate","576", "148", "8", "#FFFFFF", true);			
												webradio.addParam("quality", "high");
												webradio.addParam("allowScriptAccess", "always");			
												webradio.write("flashcontent");
										</script>										
									</td>
								</tr>
							</table>
						</div>				

						<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">										
										' . $lang->shoutcast['lastsongs'] . '
									</td>
								</tr>
								<tr>
									<td>
										<span style="float: right;">
											<img src="' . $BASEURL . '/' . $pic_base_url . 'ajax-loader.gif" alt="" title="" border="0" id="loadingimg" class="inlineimg" name="loadingimg" style="display: none" />
										</span>
										<div id="latestsongs" name="latestsongs">
											' . file_get_contents ('lps.dat') . '
										</div>
									</td>
								</tr>
							</table>
						</div>

						<div style="padding-bottom: 10px;">
							<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td class="thead">
										' . $lang->shoutcast['activedj'] . '
									</td>
								</tr>
								<tr>
									<td>
										<tt>' . $activedjlist . '</tt>
									</td>
								</tr>
							</table>
						</div>

					</td>

				</tr>
			</tbody>
		</table>';
    }
    else
    {
      echo '
		<div style="float: right;">
		<input type="button" value="' . $lang->shoutcast['refresh'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
		<input type="button" value="' . $lang->shoutcast['djlist'] . '" onclick="jumpto(\'dj.php?do=list\'); return false;" />
		<input type="button" value="' . $lang->shoutcast['bedj'] . '" onclick="jumpto(\'dj.php?do=request\'); return false;" />
		</div>
		<br /><br />
		' . show_notice ($lang->shoutcast['down2'], true, $scdef . ' - ' . $lang->shoutcast['offline']);
    }
  }

  stdfoot ();
?>
