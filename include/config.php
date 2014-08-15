<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face="verdana" size="2" color="darkred"><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  readconfig ('DATABASE,MAIN,SECURITY,TWEAK,EXTRA,THEME,DATETIME,SEO');
  $mysql_host = (!empty ($DATABASE['mysql_host']) ? $DATABASE['mysql_host'] : 'localhost');
  $mysql_user = (!empty ($DATABASE['mysql_user']) ? $DATABASE['mysql_user'] : 'root');
  $mysql_pass = (!empty ($DATABASE['mysql_pass']) ? $DATABASE['mysql_pass'] : 'pass');
  $mysql_db = (!empty ($DATABASE['mysql_db']) ? $DATABASE['mysql_db'] : 'ts');
  $SITE_ONLINE = (!empty ($MAIN['site_online']) ? $MAIN['site_online'] : 'no');
  $max_torrent_size = (!empty ($MAIN['max_torrent_size']) ? $MAIN['max_torrent_size'] : 10 * 1024 * 1024);
  $torrent_dir = (!empty ($MAIN['torrent_dir']) ? $MAIN['torrent_dir'] : 'torrents');
  $announce_urls = array ();
  $announce_urls[] = (!empty ($MAIN['announce_urls']) ? $MAIN['announce_urls'] : 'http://' . $_SERVER['HTTP_HOST'] . '/announce.php');
  $BASEURL = (!empty ($MAIN['BASEURL']) ? $MAIN['BASEURL'] : 'http://' . $_SERVER['HTTP_HOST']);
  $MEMBERSONLY = (!empty ($MAIN['MEMBERSONLY']) ? $MAIN['MEMBERSONLY'] : 'yes');
  $externalscrape = (!empty ($MAIN['externalscrape']) ? $MAIN['externalscrape'] : 'no');
  $useajax = (!empty ($MAIN['useajax']) ? $MAIN['useajax'] : 'yes');
  $includeexpeers = (!empty ($MAIN['includeexpeers']) ? $MAIN['includeexpeers'] : 'no');
  $SITEEMAIL = (!empty ($MAIN['SITEEMAIL']) ? $MAIN['SITEEMAIL'] : 'contact@');
  $SITENAME = (!empty ($MAIN['SITENAME']) ? $MAIN['SITENAME'] : '');
  $pic_base_url = (!empty ($MAIN['pic_base_url']) ? $MAIN['pic_base_url'] : 'pic/');
  $table_cat = (!empty ($MAIN['table_cat']) ? $MAIN['table_cat'] : 'categories');
  $REPORTMAIL = (!empty ($MAIN['reportemail']) ? $MAIN['reportemail'] : 'report@');
  $contactemail = (!empty ($MAIN['contactemail']) ? $MAIN['contactemail'] : $REPORTMAIL);
  $showlastxtorrents = (!empty ($MAIN['showlastxtorrents']) ? $MAIN['showlastxtorrents'] : 'multi');
  $showimages = (!empty ($MAIN['showimages']) ? $MAIN['showimages'] : 'no');
  $i_torrent_limit = (!empty ($MAIN['i_torrent_limit']) ? $MAIN['i_torrent_limit'] : '5');
  $waitsystem = (!empty ($MAIN['waitsystem']) ? $MAIN['waitsystem'] : 'yes');
  $maxdlsystem = (!empty ($MAIN['maxdlsystem']) ? $MAIN['maxdlsystem'] : 'yes');
  $cache = (!empty ($MAIN['cache']) ? $MAIN['cache'] : 'cache');
  $maxchar = (!empty ($MAIN['maxchar']) ? $MAIN['maxchar'] : '250');
  $vkeyword = (!empty ($SECURITY['vkeyword']) ? $SECURITY['vkeyword'] : 'yes');
  $securelogin = (!empty ($SECURITY['securelogin']) ? $SECURITY['securelogin'] : 'yes');
  $iv = (!empty ($SECURITY['iv']) ? $SECURITY['iv'] : 'no');
  $maxloginattempts = (!empty ($SECURITY['maxloginattempts']) ? $SECURITY['maxloginattempts'] : '5');
  $disablerightclick = (!empty ($SECURITY['disablerightclick']) ? $SECURITY['disablerightclick'] : 'no');
  $aggressivecheckip = (!empty ($SECURITY['aggressivecheckip']) ? $SECURITY['aggressivecheckip'] : 'no');
  $aggressivecheckemail = (!empty ($SECURITY['aggressivecheckemail']) ? $SECURITY['aggressivecheckemail'] : 'yes');
  $privatetrackerpatch = (!empty ($SECURITY['privatetrackerpatch']) ? $SECURITY['privatetrackerpatch'] : 'yes');
  $securehash = (!empty ($SECURITY['securehash']) ? $SECURITY['securehash'] : 'tSxAm__' . $_SERVER['HTTP_HOST'] . '_4_1-3-00-8');
  $badwords = (!empty ($SECURITY['badwords']) ? $SECURITY['badwords'] : 'fuck,shit,whore');
  $allowedreferrers = (!empty ($SECURITY['allowedreferrers']) ? $SECURITY['allowedreferrers'] : '');
  $reCAPTCHAPublickey = (!empty ($SECURITY['reCAPTCHAPublickey']) ? $SECURITY['reCAPTCHAPublickey'] : '');
  $reCAPTCHAPrivatekey = (!empty ($SECURITY['reCAPTCHAPrivatekey']) ? $SECURITY['reCAPTCHAPrivatekey'] : '');
  $reCAPTCHATheme = (!empty ($SECURITY['reCAPTCHATheme']) ? $SECURITY['reCAPTCHATheme'] : 'white');
  $reCAPTCHALanguage = (!empty ($SECURITY['reCAPTCHALanguage']) ? $SECURITY['reCAPTCHALanguage'] : 'en');
  $where = (!empty ($TWEAK['where']) ? $TWEAK['where'] : 'no');
  $iplog1 = (!empty ($TWEAK['iplog1']) ? $TWEAK['iplog1'] : 'yes');
  $ctracker = (!empty ($TWEAK['ctracker']) ? $TWEAK['ctracker'] : 'no');
  $autorefresh = (!empty ($TWEAK['autorefresh']) ? $TWEAK['autorefresh'] : 'no');
  $autorefreshtime = (!empty ($TWEAK['autorefreshtime']) ? $TWEAK['autorefreshtime'] : '0:30');
  $leftmenu = (!empty ($TWEAK['leftmenu']) ? $TWEAK['leftmenu'] : 'no');
  $gzipcompress = (!empty ($TWEAK['gzipcompress']) ? $TWEAK['gzipcompress'] : 'no');
  $cachesystem = (!empty ($TWEAK['cachesystem']) ? $TWEAK['cachesystem'] : 'yes');
  $cachetime = (!empty ($TWEAK['cachetime']) ? $TWEAK['cachetime'] : '60');
  $ts_perpage = (!empty ($TWEAK['ts_perpage']) ? $TWEAK['ts_perpage'] : '20');
  $snatchmod = (!empty ($TWEAK['snatchmod']) ? $TWEAK['snatchmod'] : 'no');
  $torrentspeed = (!empty ($TWEAK['torrentspeed']) ? $TWEAK['torrentspeed'] : 'no');
  $progressbar = (!empty ($TWEAK['progressbar']) ? $TWEAK['progressbar'] : 'no');
  $ratingsystem = (!empty ($TWEAK['ratingsystem']) ? $TWEAK['ratingsystem'] : 'no');
  $thankssystem = (!empty ($TWEAK['thankssystem']) ? $TWEAK['thankssystem'] : 'no');
  $loadlimit = $TWEAK['loadlimit'];
  $checkconnectable = (!empty ($EXTRA['checkconnectable']) ? $EXTRA['checkconnectable'] : 'no');
  $ref = (!empty ($EXTRA['ref']) ? $EXTRA['ref'] : 'yes');
  $hitrun = (!empty ($EXTRA['hitrun']) ? $EXTRA['hitrun'] : 'yes');
  $hitrun_ratio = (!empty ($EXTRA['hitrun_ratio']) ? $EXTRA['hitrun_ratio'] : '0.4');
  $hitrun_gig = (!empty ($EXTRA['hitrun_gig']) ? $EXTRA['hitrun_gig'] : '5');
  $rqs = (!empty ($EXTRA['rqs']) ? $EXTRA['rqs'] : 'yes');
  $tsshoutbot = (!empty ($EXTRA['tsshoutbot']) ? $EXTRA['tsshoutbot'] : 'no');
  $tsshoutboxoptions = (!empty ($EXTRA['tsshoutboxoptions']) ? $EXTRA['tsshoutboxoptions'] : 'upload,newuser,request');
  $tsshoutbotname = (!empty ($EXTRA['tsshoutbotname']) ? $EXTRA['tsshoutbotname'] : 'TS SE ShoutBOT');
  $defaulttemplate = (!empty ($THEME['defaulttemplate']) ? $THEME['defaulttemplate'] : 'default');
  $defaultlanguage = (!empty ($THEME['defaultlanguage']) ? $THEME['defaultlanguage'] : 'english');
  $charset = (!empty ($THEME['charset']) ? trim ($THEME['charset']) : 'UTF-8');
  $shoutboxcharset = (!empty ($THEME['shoutboxcharset']) ? trim ($THEME['shoutboxcharset']) : 'UTF-8');
  $metakeywords = (!empty ($THEME['metakeywords']) ? $THEME['metakeywords'] : 'torrent, php, source, torrent source, free, special edition, xam, xam source, ts v5');
  $metadesc = (!empty ($THEME['metadesc']) ? $THEME['metadesc'] : 'TS Special Edition by xam - Best Torrent Source Ever -');
  $slogan = (!empty ($THEME['slogan']) ? $THEME['slogan'] : 'Slogan!');
  $dateformat = (!empty ($DATETIME['dateformat']) ? $DATETIME['dateformat'] : 'm-d-Y');
  $timeformat = (!empty ($DATETIME['timeformat']) ? $DATETIME['timeformat'] : 'h:i A');
  $regdateformat = (!empty ($DATETIME['regdateformat']) ? $DATETIME['regdateformat'] : 'M Y');
  $timezoneoffset = (!empty ($DATETIME['timezoneoffset']) ? $DATETIME['timezoneoffset'] : '+1');
  $dstcorrection = (!empty ($DATETIME['dstcorrection']) ? $DATETIME['dstcorrection'] : 'yes');
  $ts_seo = ($SEO['ts_seo'] == 'yes' ? 'yes' : 'no');
  unset ($DATABASE);
  unset ($MAIN);
  unset ($SECURITY);
  unset ($TWEAK);
  unset ($EXTRA);
  unset ($THEME);
  unset ($DATETIME);
  unset ($SEO);
?>
