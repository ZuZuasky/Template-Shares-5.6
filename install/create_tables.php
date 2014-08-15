<?
$ts_tables[] = '
CREATE TABLE `casino_bets` (
`id` int(10) unsigned NOT NULL auto_increment,
`userid` int(10) NOT NULL default \'0\',
`proposed` varchar(40) NOT NULL default \'\',
`challenged` varchar(40) NOT NULL default \'\',
`amount` bigint(20) NOT NULL default \'0\',
`time` datetime NOT NULL default \'0000-00-00 00:00:00\',
PRIMARY KEY (`id`),
KEY `userid` (`userid`,`proposed`,`challenged`,`amount`)
) ENGINE=MyISAM;
';

$ts_tables[] = '
CREATE TABLE `casino` (
`userid` int(10) NOT NULL default \'0\',
`win` bigint(20) default NULL,
`lost` bigint(20) default NULL,
`trys` int(11) NOT NULL default \'0\',
`date` datetime NOT NULL default \'0000-00-00 00:00:00\',
`enableplay` enum(\'yes\',\'no\') NOT NULL default \'yes\',
`deposit` bigint(20) NOT NULL default \'0\',
PRIMARY KEY (`userid`)
) ENGINE=MyISAM;
';

$ts_tables[] = '
CREATE TABLE `cards` (
`id` int(11) NOT NULL auto_increment,
`points` int(11) NOT NULL default \'0\',
`pic` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM;
';

$ts_tables[] = '
CREATE TABLE `blackjack` (
`userid` int(11) NOT NULL default \'0\',
`points` int(11) NOT NULL default \'0\',
`status` enum(\'playing\',\'waiting\') NOT NULL default \'playing\',
`cards` text NOT NULL,
`date` datetime NOT NULL default \'0000-00-00 00:00:00\',
PRIMARY KEY (`userid`)
) ENGINE=MyISAM;
';

$ts_tables[] = '
CREATE TABLE `ddl_usage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ddlid` int(10) unsigned NOT NULL DEFAULT \'0\',
  `userid` int(10) unsigned NOT NULL DEFAULT \'0\',
  `vote` enum(\'yeah\',\'against\') NOT NULL DEFAULT \'yeah\',
  `detailddlid` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;
';

$ts_tables[] = '
CREATE TABLE  `ddl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT \'0\',
  `name` varchar(225) NOT NULL DEFAULT \'\',
  `descr` text NOT NULL,
  `added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  `yeah` int(10) unsigned NOT NULL DEFAULT \'0\',
  `against` int(10) unsigned NOT NULL DEFAULT \'0\',
  `category` int(10) unsigned NOT NULL DEFAULT \'0\',
  `comments` int(10) unsigned NOT NULL DEFAULT \'0\',
  `allowed` enum(\'allowed\',\'pending\',\'denied\') NOT NULL DEFAULT \'pending\',
  `link` text NOT NULL,
  `img` text NOT NULL,
  `heberg` enum(\'Megaupload\',\'Rapidshard\',\'Multiupload\',\'Free\',\'Autre\') NOT NULL DEFAULT \'Megaupload\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
';

$ts_tables[] = '
CREATE TABLE `tsf_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  `owner` int(10) NOT NULL DEFAULT \'0\',
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT \'\',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=65 ;';

$ts_tables[] = '
CREATE TABLE `ratings2` (
  `divx` int(10) unsigned NOT NULL DEFAULT \'0\',
  `user` int(10) unsigned NOT NULL DEFAULT \'0\',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
  `added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  PRIMARY KEY (`divx`,`user`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;';

$ts_tables[] = '
CREATE TABLE  `seedbox` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `seedboxip` varchar(50) NOT NULL DEFAULT \'\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23;';

$ts_tables[] = '
CREATE TABLE  `staffshoutbox` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT \'0\',
  `username` varchar(25) NOT NULL DEFAULT \'\',
  `date` int(11) NOT NULL DEFAULT \'0\',
  `text` text NOT NULL,
  `added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21;';



$ts_tables[] = '
CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `requestid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `subject` varchar(64) NOT NULL default \'\',
  `message` text NOT NULL,
  `by` char(16) NOT NULL default \'Admin\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `minclassread` tinyint(3) NOT NULL default \'1\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `announce_actions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrentid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `ip` varchar(15) NOT NULL default \'\',
  `passkey` varchar(32) NOT NULL default \'\',
  `actionmessage` tinytext NOT NULL,
  `actiontime` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `badusers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `username` varchar(64) NOT NULL default \'\',
  `email` varchar(64) NOT NULL default \'\',
  `ipaddress` varchar(15) NOT NULL default \'\',
  `comment` varchar(255) NOT NULL default \'\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `addedby` varchar(30) NOT NULL default \'\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `value` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;';

$ts_tables[] = '
CREATE TABLE `bonus` (
  `id` int(5) NOT NULL auto_increment,
  `bonusname` varchar(50) NOT NULL default \'\',
  `points` decimal(5,1) NOT NULL default \'0.0\',
  `description` tinytext NOT NULL,
  `art` char(10) NOT NULL default \'traffic\',
  `menge` bigint(20) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10;';

$ts_tables[] = '
CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `torrentid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`,`torrentid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default \'\',
  `image` varchar(100) NOT NULL default \'\',
  `cat_desc` varchar(30) NOT NULL default \'\',
  `minclassread` tinyint(3) unsigned NOT NULL,
  `vip` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `type` varchar(1) NOT NULL default \'c\',
  `pid` smallint(5) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';

$ts_tables[] = '
CREATE TABLE `categoriess` (
  `id` int(10) unsigned NOT NULL auto_increment,

  `name` varchar(100) NOT NULL default \'\',
  `image` varchar(100) NOT NULL default \'\',
  `cat_desc` varchar(30) NOT NULL default \'\',

  `minclassread` tinyint(3) unsigned NOT NULL,
  `vip` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `type` varchar(1) NOT NULL default \'c\',

  `pid` smallint(5) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';

$ts_tables[] = '
CREATE TABLE `cheat_attempts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `torrentid` int(10) unsigned NOT NULL default \'0\',
  `agent` char(32) NOT NULL default \'\',
  `transfer_rate` bigint(20) unsigned NOT NULL default \'0\',
  `beforeup` bigint(20) unsigned NOT NULL default \'0\',
  `upthis` bigint(20) unsigned NOT NULL default \'0\',
  `timediff` int(10) unsigned NOT NULL default \'0\',
  `ip` char(15) NOT NULL default \'\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default \'0\',
  `torrent` int(10) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default \'0\',
  `editedat` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `request` int(10) NOT NULL default \'0\',
  `offer` int(10) NOT NULL default \'0\',
  `modnotice` tinytext NOT NULL,
  `modeditid` int(10) unsigned NOT NULL default \'0\',
  `modeditusername` varchar(16) NOT NULL default \'\',
  `modedittime` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `totalvotes` varchar(6) NOT NULL default \'0|0\',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `comments_votes` (
  `cid` int(10) unsigned NOT NULL default \'0\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `vid` tinyint(2) NOT NULL default \'0\',
  UNIQUE KEY `cid` (`cid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(25) NOT NULL default \'\',
  `flagpic` char(25) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=115;';

$ts_tables[] = '
CREATE TABLE `downloadspeed` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default \'\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=19;';

$ts_tables[] = '
CREATE TABLE  `divx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL,
  `url` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `synopsis` text NOT NULL,
  `category` int(10) unsigned NOT NULL DEFAULT \'0\',
  `descr` text NOT NULL,
  `added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  `owner` int(10) unsigned NOT NULL DEFAULT \'0\',
  `torrentstreaming` varchar(255) NOT NULL DEFAULT \'\',
  `ddlstreaming` varchar(255) NOT NULL DEFAULT \'\',
  `allocine` varchar(255) NOT NULL DEFAULT \'\',
  `sid` int(5) NOT NULL,
  `views` int(5) NOT NULL,
  `rating` int(5) NOT NULL,
  `type` varchar(20) NOT NULL,
  `dat` varchar(15) NOT NULL,
  `visitorcount` int(10) unsigned NOT NULL DEFAULT \'0\',
  `size` varchar(75) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT \'0\',
  `numratings` int(10) unsigned NOT NULL DEFAULT \'0\',
  `ratingsum` int(10) unsigned NOT NULL DEFAULT \'0\',
 `allowed` enum(\'allowed\',\'pending\',\'denied\') NOT NULL DEFAULT \'allowed\',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71;';

$ts_tables[] = '
CREATE TABLE `divx_usage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(75) NOT NULL DEFAULT \'\',
  `divxid` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=320;';

$ts_tables[] = '
CREATE TABLE `faq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` set(\'categ\',\'item\') NOT NULL default \'item\',
  `question` tinytext NOT NULL,
  `answer` text NOT NULL,
  `flag` tinyint(1) unsigned NOT NULL default \'1\',
  `categ` int(10) unsigned NOT NULL default \'0\',
  `order` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=72;';

$ts_tables[] = '
CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `friendid` int(10) unsigned NOT NULL default \'0\',
  `status` char(1) NOT NULL default \'c\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `friendid` (`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `funds` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cash` decimal(8,2) NOT NULL default \'0.00\',
  `user` int(10) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  PRIMARY KEY  (`id`),
  KEY `cash` (`cash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default \'0\',
  `invitee` char(64) NOT NULL default \'\',
  `hash` char(32) NOT NULL default \'\',
  `time_invited` datetime NOT NULL default \'0000-00-00 00:00:00\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ipbans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `value` text NOT NULL,
  `date` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `modifier` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;';

$ts_tables[] = '
CREATE TABLE `iplog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ip` char(15) NOT NULL default \'\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6;';

$ts_tables[] = '
CREATE TABLE `leecherspmlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default \'0\',
  `date` datetime NOT NULL default \'0000-00-00 00:00:00\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `loginattempts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ip` char(15) NOT NULL default \'\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `banned` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `attempts` int(10) unsigned NOT NULL default \'0\',
  `type` enum(\'login\',\'recover\') NOT NULL default \'login\',
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';

$ts_tables[] = '
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender` int(10) unsigned NOT NULL default \'0\',
  `receiver` int(10) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `subject` varchar(64) NOT NULL default \'No Subject\',
  `msg` text NOT NULL,
  `unread` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `location` tinyint(4) NOT NULL default \'1\',
  `saved` enum(\'no\',\'yes\') NOT NULL default \'no\',
  PRIMARY KEY  (`id`),
  KEY `sender` (`sender`,`saved`),
  KEY `receiver` (`receiver`,`unread`,`location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';

$ts_tables[] = '
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `body` text NOT NULL,
  `title` varchar(255) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `notconnectablepmlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default \'0\',
  `date` datetime NOT NULL default \'0000-00-00 00:00:00\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `name` varchar(225) NOT NULL default \'\',
  `descr` text NOT NULL,
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `yeah` int(10) unsigned NOT NULL default \'0\',
  `against` int(10) unsigned NOT NULL default \'0\',
  `category` int(10) unsigned NOT NULL default \'0\',
  `comments` int(10) unsigned NOT NULL default \'0\',
  `allowed` enum(\'allowed\',\'pending\',\'denied\') NOT NULL default \'pending\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `offervotes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `offerid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `vote` enum(\'yeah\',\'against\') NOT NULL default \'yeah\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default \'0\',
  `peer_id` varchar(20) NOT NULL default \'\',
  `ip` varchar(15) NOT NULL default \'\',
  `port` smallint(5) unsigned NOT NULL default \'0\',
  `uploaded` bigint(20) unsigned NOT NULL default \'0\',
  `downloaded` bigint(20) unsigned NOT NULL default \'0\',
  `to_go` bigint(20) unsigned NOT NULL default \'0\',
  `seeder` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `started` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `last_action` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `prev_action` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `connectable` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `agent` varchar(32) NOT NULL default \'\',
  `finishedat` int(10) unsigned NOT NULL default \'0\',
  `downloadoffset` bigint(20) unsigned NOT NULL default \'0\',
  `uploadoffset` bigint(20) unsigned NOT NULL default \'0\',
  `passkey` varchar(32) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`),
  KEY `passkey` (`passkey`),
  KEY `torrent_seeder` (`seeder`),
  KEY `peer_id` (`peer_id`,`seeder`),
  KEY `torrent` (`torrent`),
  KEY `downloaded` (`downloaded`),
  KEY `uploaded` (`uploaded`)
) ENGINE=HEAP DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `pincode` (
  `pincode` varchar(32) NOT NULL default \'\',
  `sechash` varchar(32) NOT NULL default \'\',
  `area` tinyint(1) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`pincode`),
  KEY `area` (`area`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `pmboxes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `boxnumber` tinyint(4) unsigned NOT NULL default \'2\',
  `name` char(15) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`,`boxnumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `rating_id` int(11) unsigned NOT NULL default \'0\',
  `rating_num` int(11) unsigned NOT NULL default \'0\',
  `userid` int(11) unsigned NOT NULL default \'0\',
  `type` tinyint(1) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `rating_id` (`rating_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `referrals` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default \'0\',
  `referring` int(10) unsigned NOT NULL default \'0\',
  `credit` bigint(30) unsigned NOT NULL default \'0\',
  `done` enum(\'yes\',\'no\') NOT NULL default \'no\',
  PRIMARY KEY  (`id`),
  KEY `done` (`done`,`referring`,`uid`),
  KEY `referring` (`referring`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `referrer` (
  `referrer_url` char(50) NOT NULL default \'No referrer detected.\',
  UNIQUE KEY `referrer_url` (`referrer_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `addedby` int(10) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `votedfor` int(10) unsigned NOT NULL default \'0\',
  `votedfor_xtra` int(10) unsigned NOT NULL default \'0\',
  `type` enum(\'torrent\',\'user\',\'forum\',\'comment\',\'request\',\'reqcomment\',\'offer\',\'offercomment\',\'forumpost\',\'visitormsg\') NOT NULL default \'torrent\',
  `reason` varchar(255) NOT NULL default \'\',
  `dealtby` int(10) unsigned NOT NULL default \'0\',
  `dealtwith` tinyint(1) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `dealtwith` (`dealtwith`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `request` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `hits` int(10) unsigned NOT NULL default \'0\',
  `cat` int(10) unsigned NOT NULL default \'0\',
  `filledby` int(10) unsigned NOT NULL default \'0\',
  `filledurl` varchar(70) default NULL,
  `filled` enum(\'yes\',\'no\') NOT NULL default \'no\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `rules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default \'\',
  `text` text NOT NULL,
  `usergroups` varchar(100) NOT NULL default \'[0]\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9;';

$ts_tables[] = '
CREATE TABLE `shoutbox` (
  `id` int(10) NOT NULL auto_increment,
  `username` char(32) NOT NULL default \'\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `namestyle` char(100) NOT NULL default \'{username}\',
  `date` int(10) unsigned NOT NULL default \'0\',
  `content` varchar(255) NOT NULL default \'\',

  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=HEAP DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `txt` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';

$ts_tables[] = '
CREATE TABLE `snatched` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrentid` int(10) unsigned default \'0\',
  `userid` int(10) unsigned default \'0\',
  `port` smallint(5) unsigned NOT NULL default \'0\',
  `uploaded` bigint(20) unsigned NOT NULL default \'0\',
  `downloaded` bigint(20) unsigned NOT NULL default \'0\',
  `to_go` bigint(20) unsigned NOT NULL default \'0\',
  `seeder` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `last_action` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `startdat` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `completedat` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `connectable` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `agent` char(32) NOT NULL default \'\',
  `finished` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `downspeed` bigint(20) unsigned NOT NULL default \'0\',
  `upspeed` bigint(20) unsigned NOT NULL default \'0\',
  `seedtime` int(10) unsigned NOT NULL default \'0\',
  `leechtime` int(10) unsigned NOT NULL default \'0\',
  `ip` char(15) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `seeder` (`seeder`,`last_action`),
  KEY `torrentid` (`torrentid`),
  KEY `userid` (`userid`),
  KEY `finished` (`finished`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `staffmessages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender` int(10) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `msg` text,
  `subject` varchar(100) NOT NULL default \'\',
  `answeredby` int(10) unsigned NOT NULL default \'0\',
  `answered` tinyint(1) unsigned NOT NULL default \'0\',
  `answer` text,
  PRIMARY KEY  (`id`),
  KEY `answered` (`answered`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `staffpanel` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(32) NOT NULL default \'\',
  `description` varchar(64) NOT NULL default \'\',
  `filename` char(32) NOT NULL default \'\',
  `usergroups` char(32) NOT NULL default \'[8]\',
  PRIMARY KEY  (`id`),
  KEY `usergroups` (`usergroups`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=94;';

$ts_tables[] = '
CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` char(20) NOT NULL default \'\',
  `name` varchar(255) NOT NULL default \'\',
  `filename` varchar(255) NOT NULL default \'\',
  `descr` text NOT NULL,
  `category` int(10) unsigned NOT NULL default \'0\',
  `size` bigint(20) unsigned NOT NULL default \'0\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `numfiles` int(10) unsigned NOT NULL default \'0\',
  `comments` int(10) unsigned NOT NULL default \'0\',
  `hits` int(10) unsigned NOT NULL default \'0\',
  `times_completed` int(10) unsigned NOT NULL default \'0\',
  `leechers` int(10) unsigned NOT NULL default \'0\',
  `seeders` int(10) unsigned NOT NULL default \'0\',
  `last_action` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `visible` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `banned` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `owner` int(10) unsigned NOT NULL default \'0\',
  `free` enum(\'yes\',\'no\') default \'no\',
  `anonymous` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `sticky` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `offensive` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `silver` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `t_image` varchar(255) NOT NULL default \'\',

  `t_link` text NOT NULL,
  `isnuked` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `WhyNuked` varchar(255) NOT NULL default \'Bad quality!\',
  `isrequest` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `ts_external` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `ts_external_url` varchar(128) NOT NULL default \'\',
  `ts_external_lastupdate` int(10) unsigned NOT NULL default \'0\',
  `allowcomments` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `doubleupload` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `isScene` int(11) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `info_hash` (`info_hash`),
  KEY `category` (`category`),
  KEY `times_completed` (`times_completed`,`leechers`,`seeders`),
  KEY `visible` (`visible`,`banned`),
  KEY `t_image` (`t_image`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_announcement` (
  `announcementid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL default \'\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `posted` int(10) unsigned NOT NULL default \'0\',
  `pagetext` mediumtext NOT NULL,
  `forumid` smallint(6) unsigned NOT NULL default \'0\',
  `views` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`announcementid`),
  KEY `forumid` (`forumid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_attachments` (
  `a_id` int(10) unsigned NOT NULL auto_increment,
  `a_name` varchar(120) NOT NULL default \'\',
  `a_size` bigint(10) unsigned NOT NULL default \'0\',
  `a_count` int(10) unsigned NOT NULL default \'0\',
  `a_tid` int(10) unsigned NOT NULL default \'0\',
  `a_pid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`a_id`),
  KEY `a_tid` (`a_tid`,`a_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_forumpermissions` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `fid` int(10) unsigned NOT NULL default \'0\',
  `gid` int(10) unsigned NOT NULL default \'0\',
  `canview` char(3) NOT NULL default \'yes\',
  `canviewthreads` char(3) NOT NULL default \'yes\',
  `canpostthreads` char(3) NOT NULL default \'yes\',
  `canpostreplys` char(3) NOT NULL default \'yes\',
  `caneditposts` char(3) NOT NULL default \'yes\',
  `candeleteposts` char(3) NOT NULL default \'no\',
  `candeletethreads` char(3) NOT NULL default \'no\',
  `canpostattachments` char(3) NOT NULL default \'yes\',
  `cansearch` char(3) NOT NULL default \'yes\',
  PRIMARY KEY  (`pid`),
  KEY `fid` (`fid`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_forums` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(120) NOT NULL default \'\',
  `description` text NOT NULL,
  `type` char(1) NOT NULL default \'\',
  `pid` smallint(5) unsigned NOT NULL default \'0\',
  `parentlist` tinytext NOT NULL,
  `disporder` smallint(5) unsigned NOT NULL default \'0\',
  `threads` int(10) unsigned NOT NULL default \'0\',
  `posts` int(10) unsigned NOT NULL default \'0\',
  `lastpost` int(10) unsigned NOT NULL default \'0\',
  `lastposter` char(32) NOT NULL default \'\',
  `lastposteruid` int(10) unsigned NOT NULL default \'0\',
  `lastposttid` int(10) NOT NULL default \'0\',
  `lastpostsubject` varchar(120) NOT NULL default \'\',
  `password` char(32) NOT NULL default \'\',
  `image` varchar(32) NOT NULL default \'private2.png\',
  PRIMARY KEY  (`fid`),
  KEY `pid` (`pid`),
  KEY `password` (`password`),
  KEY `type` (`type`,`pid`,`disporder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_moderators` (
  `moderatorid` smallint(5) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `forumid` smallint(6) NOT NULL default \'0\',
  PRIMARY KEY  (`moderatorid`),
  KEY `userid` (`userid`,`forumid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_poll` (
  `pollid` int(10) unsigned NOT NULL auto_increment,
  `question` varchar(100) NOT NULL default \'\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `options` text,
  `votes` text,
  `active` smallint(6) unsigned NOT NULL default \'1\',
  `numberoptions` smallint(5) unsigned NOT NULL default \'0\',
  `timeout` smallint(5) unsigned NOT NULL default \'0\',
  `multiple` smallint(5) unsigned NOT NULL default \'0\',
  `voters` smallint(5) unsigned NOT NULL default \'0\',
  `public` smallint(6) NOT NULL default \'0\',
  `lastvote` int(10) unsigned NOT NULL default \'0\',
  `fortracker` tinyint(1) NOT NULL default \'0\',
  PRIMARY KEY  (`pollid`),
  KEY `fortracker` (`fortracker`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_pollvote` (
  `pollvoteid` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `votedate` int(10) unsigned NOT NULL default \'0\',
  `voteoption` int(10) unsigned NOT NULL default \'0\',
  `votetype` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`pollvoteid`),
  KEY `pollid` (`pollid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_posts` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `tid` int(10) unsigned NOT NULL default \'0\',
  `replyto` int(10) unsigned NOT NULL default \'0\',
  `fid` smallint(5) unsigned NOT NULL default \'0\',
  `subject` varchar(120) NOT NULL default \'\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `username` char(32) NOT NULL default \'\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `message` text NOT NULL,
  `ipaddress` char(15) NOT NULL default \'\',
  `edituid` int(10) unsigned NOT NULL default \'0\',
  `edittime` int(10) NOT NULL default \'0\',
  `modnotice` tinytext NOT NULL,
  `modnotice_info` char(40) NOT NULL default \'\',
  `iconid` smallint(5) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`pid`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`),
  KEY `fid` (`fid`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_searchlog` (
  `sid` varchar(32) NOT NULL default \'\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `ipaddress` char(15) NOT NULL default \'\',
  `threads` text NOT NULL,
  `posts` text NOT NULL,
  `searchtype` char(10) NOT NULL default \'\',
  `resulttype` char(10) NOT NULL default \'\',
  `querycache` text NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `tsf_subscribe` (
  `id` int(10) NOT NULL auto_increment,
  `tid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_thanks` (
  `tid` int(10) unsigned NOT NULL default \'0\',
  `pid` int(10) unsigned NOT NULL default \'0\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  KEY `tid` (`tid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `tsf_threadrate` (
  `threadrateid` int(10) unsigned NOT NULL auto_increment,
  `threadid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `vote` smallint(6) unsigned NOT NULL default \'0\',
  `ipaddress` char(15) NOT NULL default \'\',
  PRIMARY KEY  (`threadrateid`),
  KEY `threadid` (`threadid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_threads` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `fid` smallint(5) unsigned NOT NULL default \'0\',
  `subject` varchar(120) NOT NULL default \'\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `username` char(32) NOT NULL default \'\',
  `dateline` int(10) NOT NULL default \'0\',
  `firstpost` int(10) unsigned NOT NULL default \'0\',
  `lastpost` int(10) NOT NULL default \'0\',
  `lastposter` char(32) NOT NULL default \'\',
  `lastposteruid` int(10) unsigned NOT NULL default \'0\',
  `views` int(100) NOT NULL default \'0\',
  `replies` int(100) NOT NULL default \'0\',
  `closed` char(3) NOT NULL default \'\',
  `sticky` int(1) unsigned NOT NULL default \'0\',
  `votenum` smallint(5) unsigned NOT NULL default \'0\',
  `votetotal` smallint(5) unsigned NOT NULL default \'0\',
  `pollid` int(10) unsigned NOT NULL default \'0\',
  `iconid` smallint(5) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`tid`),
  KEY `dateline` (`dateline`),
  KEY `firstpost` (`firstpost`),
  KEY `uid` (`uid`),
  KEY `lastpost` (`lastpost`),
  KEY `fid` (`fid`,`lastpost`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `tsf_threadsread` (
  `tid` int(10) unsigned NOT NULL default \'0\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  UNIQUE KEY `tiduid` (`tid`,`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_applications` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `title` char(100) NOT NULL default \'\',
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `created` int(10) unsigned NOT NULL default \'0\',
  `enabled` tinyint(1) unsigned NOT NULL default \'1\',
  `by` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`aid`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_application_requests` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `aid` int(10) unsigned NOT NULL default \'0\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `url` varchar(255) NOT NULL default \'\',
  `info` text NOT NULL,
  `created` int(10) unsigned NOT NULL default \'0\',
  `status` tinyint(1) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`rid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_auto_vip` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `vip_until` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `old_gid` tinyint(3) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`userid`),
  KEY `vip_until` (`vip_until`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_cron` (
  `cronid` int(10) NOT NULL auto_increment,
  `nextrun` int(10) unsigned NOT NULL default \'0\',
  `minutes` int(10) unsigned NOT NULL default \'0\',
  `filename` char(50) NOT NULL default \'\',
  `description` char(100) NOT NULL default \'\',
  `loglevel` tinyint(1) unsigned NOT NULL default \'1\',
  `active` tinyint(1) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`cronid`),
  KEY `nextrun` (`nextrun`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;';

$ts_tables[] = '
CREATE TABLE `ts_cron_log` (
  `filename` char(100) NOT NULL default \'\',
  `querycount` smallint(6) unsigned NOT NULL default \'0\',
  `executetime` char(10) NOT NULL default \'0\',
  `runtime` int(10) unsigned NOT NULL default \'0\',
  UNIQUE KEY `filename` (`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_events` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default \'\',
  `event` mediumtext NOT NULL,
  `date` varchar(64) NOT NULL default \'\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_faq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` char(1) NOT NULL default \'\',
  `name` varchar(120) NOT NULL default \'\',
  `pid` int(10) unsigned NOT NULL default \'0\',
  `disporder` smallint(10) unsigned NOT NULL default \'0\',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=20;';

$ts_tables[] = '
CREATE TABLE `ts_hit_and_run` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `torrentid` int(10) unsigned NOT NULL default \'0\',
  `added` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_inactivity` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `inactivitytag` int(10) unsigned NOT NULL default \'0\',
  KEY `userid` (`userid`),
  KEY `inactivitytag` (`inactivitytag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_lottery_tickets` (
  `ticketid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`ticketid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_nfo` (
  `id` int(10) unsigned NOT NULL default \'0\',
  `nfo` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_plugins` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default \'\',
  `description` varchar(32) NOT NULL default \'\',
  `content` text NOT NULL,
  `position` tinyint(1) unsigned NOT NULL default \'0\',
  `sort` tinyint(2) unsigned NOT NULL default \'0\',
  `permission` varchar(100) NOT NULL default \'[all]\',
  `active` tinyint(1) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`pid`),
  KEY `position` (`position`,`permission`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=20;';

$ts_tables[] = '
CREATE TABLE `ts_profilevisitor` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `visitorid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `visible` int(10) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`visitorid`,`userid`),
  KEY `userid` (`userid`,`visible`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_secret_questions` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `passhint` tinyint(1) unsigned NOT NULL default \'0\',
  `hintanswer` char(32) NOT NULL default \'\',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_sessions` (
  `sessionhash` char(32) NOT NULL default \'\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `host` char(15) NOT NULL default \'\',
  `lastactivity` int(10) unsigned NOT NULL default \'0\',
  `location` char(255) NOT NULL default \'\',
  `useragent` char(100) NOT NULL default \'\',
  PRIMARY KEY  (`sessionhash`),
  KEY `userid` (`userid`,`lastactivity`)
) ENGINE=HEAP DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_shoutcastdj` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default \'0\',
  `active` tinyint(1) unsigned NOT NULL default \'0\',
  `activedays` char(30) NOT NULL default \'\',
  `activetime` char(11) NOT NULL default \'\',
  `genre` char(50) NOT NULL default \'\',
  KEY `id` (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_smilies` (
  `sid` smallint(5) unsigned NOT NULL auto_increment,
  `stitle` char(100) NOT NULL default \'\',
  `stext` char(20) NOT NULL default \'\',
  `spath` char(100) NOT NULL default \'\',
  `sorder` smallint(5) unsigned NOT NULL default \'1\',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=140;';

$ts_tables[] = '
CREATE TABLE `ts_social_groups` (
  `groupid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default \'\',
  `description` text NOT NULL,
  `owner` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `members` int(10) unsigned NOT NULL default \'0\',
  `messages` int(10) unsigned NOT NULL default \'0\',
  `type` enum(\'public\',\'inviteonly\') NOT NULL default \'public\',
  `lastpostdate` int(10) unsigned NOT NULL default \'0\',
  `lastposter` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`groupid`),
  KEY `owner` (`owner`),
  KEY `lastposter` (`lastposter`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_social_group_members` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `groupid` int(10) unsigned NOT NULL default \'0\',
  `joined` int(10) unsigned NOT NULL default \'0\',
  `type` enum(\'public\',\'inviteonly\') NOT NULL default \'public\',
  PRIMARY KEY  (`userid`,`groupid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_social_group_messages` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `groupid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `posted` int(10) unsigned NOT NULL default \'0\',
  `message` text NOT NULL,
  PRIMARY KEY  (`mid`),
  KEY `groupid` (`groupid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_social_group_reports` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `mid` int(10) unsigned NOT NULL default \'0\',
  `groupid` int(10) unsigned NOT NULL default \'0\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `report` text NOT NULL,
  `page` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`rid`),
  KEY `mid` (`mid`,`groupid`,`userid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_subtitles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default \'\',
  `language` int(10) unsigned NOT NULL default \'0\',
  `cds` tinyint(1) unsigned NOT NULL default \'0\',
  `fps` char(10) NOT NULL default \'\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  `date` int(10) unsigned NOT NULL default \'0\',
  `filename` varchar(64) NOT NULL default \'\',
  `dlcount` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_support` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `supportfor` varchar(255) NOT NULL default \'\',
  `supportlang` varchar(100) NOT NULL default \'\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_templates` (
  `templateid` int(10) NOT NULL auto_increment,
  `name` char(32) NOT NULL default \'\',
  `title` varchar(100) NOT NULL default \'\',
  `template` mediumtext NOT NULL,
  `template_orj` mediumtext NOT NULL,
  PRIMARY KEY  (`templateid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=17;';

$ts_tables[] = '
CREATE TABLE `ts_thanks` (
  `tid` int(10) unsigned NOT NULL default \'0\',
  `uid` int(10) unsigned NOT NULL default \'0\',
  KEY `tid` (`tid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_torrents_details` (
  `did` int(10) unsigned NOT NULL auto_increment,
  `tid` int(10) unsigned NOT NULL default \'0\',
  `video_info` varchar(255) NOT NULL default \'\',
  `audio_info` varchar(255) NOT NULL default \'\',
  PRIMARY KEY  (`did`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_tutorials` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default \'0\',
  `date` int(10) unsigned NOT NULL default \'0\',
  `title` varchar(100) NOT NULL default \'\',
  `content` longtext NOT NULL,
  `views` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_user_validation` (
  `editsecret` char(32) NOT NULL default \'\',
  `userid` int(10) unsigned NOT NULL default \'0\',
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_u_perm` (
  `userid` int(10) unsigned NOT NULL default \'0\',
  `canupload` tinyint(1) unsigned NOT NULL default \'1\',
  `candownload` tinyint(1) unsigned NOT NULL default \'1\',
  `cancomment` tinyint(1) unsigned NOT NULL default \'1\',
  `canmessage` tinyint(1) unsigned NOT NULL default \'1\',
  `canshout` tinyint(1) unsigned NOT NULL default \'1\',
   `canstream` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

$ts_tables[] = '
CREATE TABLE `ts_visitor_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `visitorid` int(10) unsigned NOT NULL default \'0\',
  `visitormsg` text NOT NULL,
  `added` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `ts_watch_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `added_by` int(10) unsigned NOT NULL default \'0\',
  `reason` text NOT NULL,
  `public` tinyint(1) unsigned NOT NULL default \'1\',
  `date` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`id`),
  KEY `added_by` (`added_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `unbanrequests` (
  `id` int(10) NOT NULL auto_increment,
  `ip` char(15) NOT NULL default \'\',
  `realip` char(15) NOT NULL default \'\',
  `email` char(64) NOT NULL default \'\',
  `comment` text NOT NULL,
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

$ts_tables[] = '
CREATE TABLE `uploadspeed` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(50) NOT NULL default \'\',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=19;';

$ts_tables[] = '
CREATE TABLE `usergroups` (
  `gid` smallint(5) unsigned NOT NULL auto_increment,
  `disporder` smallint(6) unsigned NOT NULL default \'0\',
  `type` tinyint(1) unsigned NOT NULL default \'1\',
  `title` char(20) NOT NULL default \'\',
  `description` tinytext NOT NULL,
  `isbanned` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canpm` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `candownload` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canupload` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canrequest` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cancomment` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canreport` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canbookmark` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canresetpasskey` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canviewotherprofile` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canvote` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canrate` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canthanks` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canshout` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `caninvite` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canbonus` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canmemberlist` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canfriendlist` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cansnatch` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canpeers` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cantopten` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cansettingspanel` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canstaffpanel` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canuserdetails` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `pmquote` int(10) unsigned NOT NULL default \'90\',
  `floodlimit` tinyint(5) unsigned NOT NULL default \'60\',
  `autoinvite` tinyint(5) unsigned NOT NULL default \'3\',
  `namestyle` char(100) NOT NULL default \'{username}\',
  `showstaffteam` enum(\'yes\',\'no\',\'staff\') NOT NULL default \'no\',
  `issupermod` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canviewviptorrents` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canaccessoffline` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `isvipgroup` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canfreeleech` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canbaduser` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `candeletetorrent` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `isforummod` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `cantransfer` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `canmassdelete` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `canemailnotify` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cansignature` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `waitlimit` tinyint(3) unsigned NOT NULL default \'0\',
  `slotlimit` tinyint(2) unsigned NOT NULL default \'0\',
  `canexternal` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `cancreatepoll` enum(\'yes\',\'no\') NOT NULL default \'yes\',
   `sgperms` varchar(8) NOT NULL default \'10101111\',
 
  PRIMARY KEY  (`gid`),
  KEY `showstaffteam` (`showstaffteam`),
  KEY `autoinvite` (`autoinvite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;';

$ts_tables[] = '
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` char(32) NOT NULL default \'\',
  `passhash` char(32) NOT NULL default \'\',
  `secret` char(20) NOT NULL default \'\',
  `email` char(64) NOT NULL default \'\',
  `status` enum(\'pending\',\'confirmed\') NOT NULL default \'pending\',
  `added` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `last_login` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `last_access` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `stylesheet` char(32) default NULL,
  `ip` char(15) NOT NULL default \'\',
  `uploaded` bigint(20) unsigned NOT NULL default \'0\',
  `downloaded` bigint(20) unsigned NOT NULL default \'0\',
  `title` char(30) NOT NULL default \'\',
  `country` int(10) unsigned NOT NULL default \'0\',
  `notifs` mediumtext NOT NULL,
  `modcomment` text NOT NULL,
  `enabled` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `donor` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `warned` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `warneduntil` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `torrentsperpage` tinyint(2) unsigned NOT NULL default \'0\',
  `passkey` char(32) NOT NULL default \'\',
  `tzoffset` char(4) NOT NULL default \'0\',
  `invites` int(10) NOT NULL default \'0\',
  `invited_by` int(10) NOT NULL default \'0\',
  `seedbonus` decimal(9,1) NOT NULL default \'0.0\',
  `bonuscomment` text NOT NULL,
  `leechwarn` enum(\'yes\',\'no\') NOT NULL default \'no\',
  `leechwarnuntil` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `lastwarned` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `timeswarned` int(10) NOT NULL default \'0\',
  `warnedby` char(32) NOT NULL default \'\',
  `page` varchar(100) NOT NULL default \'\',
  `donated` decimal(8,2) NOT NULL default \'0.00\',
  `donoruntil` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `total_donated` decimal(8,2) NOT NULL default \'0.00\',
  `lastinvite` datetime NOT NULL default \'0000-00-00 00:00:00\',
  `announce_read` enum(\'yes\',\'no\') NOT NULL default \'yes\',
  `usergroup` tinyint(3) unsigned NOT NULL default \'0\',
  `last_forum_visit` int(10) unsigned NOT NULL default \'0\',
  `last_forum_active` int(10) unsigned NOT NULL default \'0\',
  `avatar` varchar(200) NOT NULL default \'\',
  `postsperpage` tinyint(3) unsigned NOT NULL default \'0\',
  `signature` mediumtext NOT NULL,
  `totalposts` bigint(30) unsigned NOT NULL default \'0\',
  `birthday` char(10) NOT NULL default \'\',
  `visitorcount` int(10) unsigned NOT NULL default \'0\',
  `options` char(30) NOT NULL default \'A0B0C0D1E1F0G1H1I2K1L1M1N1O0\',
  `pmunread` smallint(5) unsigned NOT NULL default \'0\',
  `speed` char(5) NOT NULL default \'0~0\',
 `droits_film` decimal(10,0) NOT NULL DEFAULT \'10\',
  `stream` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `uploadstream` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `smilies` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL DEFAULT \'pic/smilies/rtfm.gif\',
  `droits_ddl` decimal(10,0) NOT NULL DEFAULT \'10\',
 PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `passkey` (`passkey`),
  KEY `uploaded` (`uploaded`),
  KEY `added` (`added`),
  KEY `last_forum_active` (`last_forum_active`),
  KEY `usergroup` (`usergroup`),
  KEY `warned` (`warned`,`leechwarn`,`enabled`),
  KEY `status` (`status`,`timeswarned`,`enabled`),
  KEY `downloaded` (`leechwarn`,`uploaded`,`downloaded`),
  KEY `donor` (`donor`,`donoruntil`),
  KEY `last_access` (`last_access`),
  KEY `country` (`country`),
  KEY `invited_by` (`invited_by`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;';
?>
