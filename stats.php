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
  loggedinorreturn ();
  maxsysop ();
  $lang->load ('stats');
  define ('S_VERSION', '0.4 ');
  $query = sql_query ('SELECT COUNT(id) as totaltorrents, SUM(times_completed) as totalcompleted FROM torrents');
  $totaltorrents = ts_nf (@mysql_result ($query, 0, 'totaltorrents'));
  $totalcompleted = ts_nf (@mysql_result ($query, 0, 'totalcompleted'));
  $query = sql_query ('SELECT COUNT(id) as totaldeadtorrents FROM torrents WHERE visible = \'no\' OR (leechers=0 AND seeders=0)');
  $totaldeadtorrents = ts_nf (@mysql_result ($query, 0, 'totaldeadtorrents'));
  $query = sql_query ('SELECT COUNT(id) as totalextorrents FROM torrents WHERE ts_external=\'yes\'');
  $totalextorrents = ts_nf (@mysql_result ($query, 0, 'totalextorrents'));
  $totalinternaltorrents = $totaltorrents - $totalextorrents;
  $query = sql_query ('SELECT COUNT(id) as totalratiounder1 FROM users WHERE uploaded / downloaded < 1.0');
  $totalratiounder1 = ts_nf (@mysql_result ($query, 0, 'totalratiounder1'));
  include_once INC_PATH . '/functions_ratio.php';
  $yourratio = get_user_ratio ($CURUSER['uploaded'], $CURUSER['downloaded']);
  $query = sql_query ('' . 'SELECT COUNT(id) as yourtorrentratio FROM snatched WHERE uploaded / downloaded < 1.0 AND userid = ' . $CURUSER['id']);
  $yourtorrentratio = ts_nf (@mysql_result ($query, 0, 'yourtorrentratio'));
  $query = sql_query ('SELECT count(id) as totalseeders FROM peers WHERE seeder = \'yes\'');
  $totalseeders = ts_nf (@mysql_result ($query, 0, 'totalseeders'));
  $query = sql_query ('SELECT count(id) as totalleechers FROM peers WHERE seeder = \'no\'');
  $totalleechers = ts_nf (@mysql_result ($query, 0, 'totalleechers'));
  $query = sql_query ('SELECT SUM(downloaded) AS totaldl, SUM(uploaded) AS totalul FROM users');
  $row = mysql_fetch_assoc ($query);
  $totaldownloaded = mksize ($row['totaldl']);
  $totaluploaded = mksize ($row['totalul']);
  $ts_e_query = sql_query ('SELECT SUM(leechers) as leechers, SUM(seeders) as seeders FROM torrents WHERE ts_external = \'yes\'');
  $ts_e_query_r = mysql_fetch_row ($ts_e_query);
  $leechers = ts_nf ($ts_e_query_r[0]);
  $seeders = ts_nf ($ts_e_query_r[1]);
  stdhead ($lang->stats['head']);
  echo '
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<td class="thead" align="center">' . $lang->stats['head'] . '</td>
	</tr>
	<tr>
		<td>
			' . sprintf ($lang->stats['showstats'], $totaltorrents, $totalinternaltorrents, $totalextorrents, $totaldeadtorrents, $totalratiounder1, $yourratio, $yourtorrentratio, $totalseeders, $totalleechers, ts_nf ($totalseeders + $totalleechers), $totaluploaded, $totaldownloaded, $seeders, $leechers, ts_nf ($ts_e_query_r[0] + $ts_e_query_r[1]), $totalcompleted) . '
		</td>
	</tr>
</table>
';
  stdfoot ();
?>
