<?

require './radio/config.php';
require './radio/radio_functions.php';

if (!defined('IN_PLUGIN_SYSTEM'))
{
	 die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
}
$lang->load('radio');




		


if(!$scfp){ // Server ist Down oder nicht erreichbar
	$radio .= '
					<title>'.$scdef.': '.$servertitle.'</title>
					<table  cellpadding="6" cellspacing="1" width="100%" border="0">
							<tr>
								<td class="subheader" width="100%" colspan="6"><b>'.$scdef.'  - Offline</b></td>
							</tr>
							<tr>
								<td align="center" width="80px"><img src="radio/images/offline.png" alt="" /></td>
								<td valign="top" align="left" class="text">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="50%" class="alt2" align="center">'.$lang->radio['down'].'. </td>
											<td rowspan="2" width="50%" align="center"><img border="0" src="radio/images/shoutcast_off.gif" alt="Server Down" vspace="5" /><br /><b> '.$lang->radio['down3'].'.</b></td>
										</tr>
										<tr>
			     						<td width="50%">&#160;<center>'.$lang->radio['down2'].'</center></td>
										</tr>
									</table>
								</td>
							</tr>
					</table>
				';
					
}else{ // Server ist Online
			if($streamstatus == "1"){	// On-line
					$sSql = "
						SELECT 
							u.id, 
							u.username, 
							u.enabled,  
							g.namestyle
						FROM 
							users AS u 
							LEFT JOIN usergroups AS g ON (
								u.usergroup=g.gid
							) 
						WHERE
							`ip` IN ('" . implode("','",$aListener) . "') 
						ORDER by 
							g.disporder DESC,
							u.username, 
							u.last_access 
					";
					$rResult = mysql_query($sSql);
					if (mysql_num_rows($rResult) >= 1){
						while ($rowListener = mysql_fetch_assoc($rResult)){
						
							if(sizeof($aImages) > 0){
								$sUserImages = implode(' ', $aImages);
							}
							$aUser[] = '<span style="white-space: nowrap;"><a href="./userdetails.php?id='.$rowListener['id'].'">'.get_user_color($rowListener['username'], $rowListener['namestyle']).'</a>'.($sUserImages ? $sUserImages : '').'</span>';
						}
						$sListener = '<div class="small" style="padding-top: 6px;"><b>'.implode(', ', $aUser).'</b></div>';
					}else{
						$sListener = "".$lang->radio['aktuell']."";
					}
					
					$radio .= '
								<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
								<title>'.$scdef.': '.$servertitle.'</title>
								<script type="text/javascript">
									function scastmp(){
										scastmpWindow = window.open("radio/players.php?do=mp","mp","width=360,height=80");
									}
									function scastrp(){
										scastrpWindow = window.open("radio/players.php?do=rp","rp","width=420,height=160");
									}
									function scastqt(){
										scastqtWindow = window.open("radio/players.php?do=qt","qt","width=330,height=50");
									}
								</script>
								<table width="100%" cellpadding="6" cellspacing="1" border="0">
									<tr>
										<td align="center" class="subheader" width="100%" colspan="6">
											<b>'.$servertitle.' ('.$currentlisteners.'/'.$maxlisteners.' @ '.$bitrate.' kbs)</b>
										</td>
									</tr>
										<td align="center">
											<img src="radio/images/radio.gif" />
										</td>
										<td valign="top" align="left">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" >
												<td class="subheader" colspan="6">
														<b>'.$lang->radio['aktive'].':</b>
													</td>
													<tr>
													<td colspan="6" class="smallfont" style="padding-left: 5px" >
														<tt><center><font color=red><img src=radio/images/radiosongs.png border="0" \> '.$song[0].'</center></font></tt>
													</td>
													
												<tr>
													<td class="subheader" colspan="6">
														<b>'.$lang->radio['tunein'].':</b>
													</td>
												</tr>
												<tr>
													<td width="50%" colspan="6" class="smallfont" style="padding-left: 5px"><center>
														<a href="'.$listenamp.'"><img src="radio/images/im_winamp.gif" title="'.$lang->radio['winamp'].'" border="0" hspace="2" /></a>
														<a href="javascript:scastrp()"><img src="radio/images/im_real.gif" title="'.$lang->radio['real'].'"  border="0" hspace="2" /></a>
														<a href="javascript:scastmp()"><img src="radio/images/im_winmp.gif" title="'.$lang->radio['media'].'" border="0" hspace="2" /></a>
														<a href="javascript:scastqt()"><img src="radio/images/im_qt.gif" title="'.$lang->radio['qt'].'" border="0" hspace="2" /></a><br>
													
													</center></td>
													
												</tr>
												<tr>
										 			<td class="subheader" colspan="3">
										 				<b>'.$lang->radio['stream'].':</b>
										 			</td>
													<td class="subheader" colspan="3">
														<b>'.$lang->radio['lastsongs'].'</b>
													</td>
												</tr>
												<tr>
													<td width="50%" colspan="3">
														'.$lang->radio['mostlisterners'].':<b>'.$peaklisteners.'</b><br />
														'.$lang->radio['stats'].': <b>'.$currentlisteners.'/'.$maxlisteners.'</b><br />
														'.$lang->radio['qualie'].': <b>'.$bitrate.'</b> kbs<br />
														'.$lang->radio['typ'].': <b>'.$content.'</b><br />
														'.$lang->radio['listen'].': <b>'.$streamhits.'</b><br />
														'.$lang->radio['avj'].': <b>'.$averagemin.'</b>
													</td>
													<td width="50%" colspan="3">
														<tt><img src=radio/images/radiosongs.png border="0" \> '.$song[1].'<br />
														<img src=radio/images/radiosongs.png border="0" \> '.$song[2].'<br />
														<img src=radio/images/radiosongs.png border="0" \> '.$song[3].'<br />
														<img src=radio/images/radiosongs.png border="0" \> '.$song[4].'<br />
														<img src=radio/images/radiosongs.png border="0" \> '.$song[5].'</tt>
													</td>
												</tr>
											<tr>
												<td class="subheader" colspan="6">
													<b>'.$lang->radio['listeners'].'</b>
												</td>
											</tr>
											<tr>
												<td colspan="6">
													'.$sListener.'
												</td>
											</tr>
										</table>
									</table>
								';
		} else { // Off-Line
         $radio .= '
                <title>'.$scdef.': '.$servertitle.'</title>
                <table width="100%" cellpadding="6" cellspacing="1" border="0">
                  
                  <tr>
                    <td align="center" width="80px"><img src="radio/images/offline.png" /></td>
                  
                    <td valign="top" align="left" class="text">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td width="80%" align="center" class="subheader" colspan="3">'.$lang->radio['offline3'].'</td>
                         
                        </tr>
                        <tr>
                        <td align="left"><img src="radio/images/off.png" /></td>
                         <td width="80%">&#160;<center>'.$lang->radio['offline2'].'</center></td>
                         <td align="right"><img src="radio/images/offright.png" /></td>
                      
                        </tr>
              
                      </table>
                        <td align="center" width="80px"><img src="radio/images/offline.png" /></td>
                    </td>
                  </tr>
                   
                </table>';
			}
}

?>