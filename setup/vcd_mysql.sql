-- 
-- Server version: 4.1.10
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd`
-- 

CREATE TABLE `vcd` (
  `vcd_id` int(10) NOT NULL auto_increment,
  `title` text,
  `category_id` int(10) NOT NULL default '0',
  `year` int(10) default NULL,
  PRIMARY KEY  (`vcd_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Borrowers`
-- 

CREATE TABLE `vcd_Borrowers` (
  `borrower_id` int(10) NOT NULL auto_increment,
  `owner_id` int(10) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`borrower_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Comments`
-- 

CREATE TABLE `vcd_Comments` (
  `comment_id` int(10) NOT NULL auto_increment,
  `vcd_id` int(10) NOT NULL default '0',
  `user_id` int(10) NOT NULL default '0',
  `comment_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` text,
  `isPrivate` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_CoverTypes`
-- 

CREATE TABLE `vcd_CoverTypes` (
  `cover_type_id` int(10) NOT NULL auto_increment,
  `cover_type_name` varchar(50) NOT NULL default '',
  `cover_type_description` varchar(100) default NULL,
  PRIMARY KEY  (`cover_type_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Covers`
-- 

CREATE TABLE `vcd_Covers` (
  `cover_id` int(10) NOT NULL auto_increment,
  `vcd_id` int(10) NOT NULL default '0',
  `cover_type_id` int(10) NOT NULL default '0',
  `cover_filename` varchar(100) NOT NULL default '',
  `cover_filesize` int(10) default NULL,
  `user_id` int(10) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `image_id` int(10) default NULL,
  PRIMARY KEY  (`cover_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_CoversAllowedOnMediatypes`
-- 

CREATE TABLE `vcd_CoversAllowedOnMediatypes` (
  `cover_type_id` int(10) NOT NULL default '0',
  `media_type_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_IMDB`
-- 

CREATE TABLE `vcd_IMDB` (
  `imdb` varchar(32) NOT NULL default '',
  `title` text,
  `alt_title1` text,
  `alt_title2` text,
  `image` varchar(100) default NULL,
  `year` int(10) default NULL,
  `plot` text,
  `director` varchar(100) default NULL,
  `fcast` text,
  `rating` varchar(5) default NULL,
  `runtime` int(10) default NULL,
  `country` varchar(80) default NULL,
  `genre` varchar(100) default NULL,
  PRIMARY KEY  (`imdb`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Images`
-- 

CREATE TABLE `vcd_Images` (
  `image_id` int(10) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `image_type` varchar(12) NOT NULL default '',
  `image_size` int(10) NOT NULL default '0',
  `image` longblob,
  PRIMARY KEY  (`image_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Log`
-- 

CREATE TABLE `vcd_Log` (
  `event_id` tinyint(4) NOT NULL default '0',
  `message` varchar(200) NOT NULL default '',
  `user_id` int(11) default NULL,
  `event_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default ''
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_MediaTypes`
-- 

CREATE TABLE `vcd_MediaTypes` (
  `media_type_id` int(10) NOT NULL auto_increment,
  `media_type_name` varchar(50) NOT NULL default '',
  `parent_id` int(10) default NULL,
  `media_type_description` varchar(100) default NULL,
  PRIMARY KEY  (`media_type_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_MetaData`
-- 

CREATE TABLE `vcd_MetaData` (
  `metadata_id` int(10) NOT NULL auto_increment,
  `record_id` int(10) NOT NULL default '0',
  `mediatype_id` int(11) NOT NULL default '0',
  `user_id` int(10) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `metadata_value` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`metadata_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_MetaDataTypes`
-- 

CREATE TABLE `vcd_MetaDataTypes` (
  `type_id` int(10) unsigned NOT NULL auto_increment,
  `type_name` varchar(50) default NULL,
  `type_description` varchar(150) default NULL,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`type_id`),
  KEY `metadata_type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_MovieCategories`
-- 

CREATE TABLE `vcd_MovieCategories` (
  `category_id` int(10) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_PornCategories`
-- 

CREATE TABLE `vcd_PornCategories` (
  `category_id` int(10) NOT NULL auto_increment,
  `category_name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_PornStudios`
-- 

CREATE TABLE `vcd_PornStudios` (
  `studio_id` int(10) NOT NULL auto_increment,
  `studio_name` text,
  PRIMARY KEY  (`studio_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Pornstars`
-- 

CREATE TABLE `vcd_Pornstars` (
  `pornstar_id` int(10) NOT NULL auto_increment,
  `name` text,
  `homepage` varchar(100) default NULL,
  `image_name` varchar(100) default NULL,
  `biography` text,
  PRIMARY KEY  (`pornstar_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_PropertiesToUser`
-- 

CREATE TABLE `vcd_PropertiesToUser` (
  `user_id` int(10) NOT NULL default '0',
  `property_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_RssFeeds`
-- 

CREATE TABLE `vcd_RssFeeds` (
  `feed_id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL default '0',
  `feed_name` varchar(60) NOT NULL default '',
  `feed_url` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`feed_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Screenshots`
-- 

CREATE TABLE `vcd_Screenshots` (
  `vcd_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`vcd_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Sessions`
-- 

CREATE TABLE `vcd_Sessions` (
  `session_id` varchar(50) NOT NULL default '',
  `session_user_id` int(10) NOT NULL default '0',
  `session_start` int(10) NOT NULL default '0',
  `session_ip` varchar(15) NOT NULL default ''
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Settings`
-- 

CREATE TABLE `vcd_Settings` (
  `settings_id` int(10) NOT NULL auto_increment,
  `settings_key` varchar(50) NOT NULL default '',
  `settings_value` text,
  `settings_description` text,
  `isProtected` tinyint(4) default NULL,
  `settings_type` varchar(20) default NULL,
  PRIMARY KEY  (`settings_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_SourceSites`
-- 

CREATE TABLE `vcd_SourceSites` (
  `site_id` int(10) NOT NULL auto_increment,
  `site_name` varchar(50) NOT NULL default '',
  `site_alias` varchar(50) default NULL,
  `site_homepage` varchar(80) NOT NULL default '',
  `site_getCommand` varchar(100) default NULL,
  `site_isFetchable` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`site_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_UserLoans`
-- 

CREATE TABLE `vcd_UserLoans` (
  `loan_id` int(10) NOT NULL auto_increment,
  `vcd_id` int(10) NOT NULL default '0',
  `owner_id` int(10) NOT NULL default '0',
  `borrower_id` int(10) NOT NULL default '0',
  `date_out` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_in` datetime default NULL,
  PRIMARY KEY  (`loan_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_UserProperties`
-- 

CREATE TABLE `vcd_UserProperties` (
  `property_id` int(10) NOT NULL auto_increment,
  `property_name` varchar(50) NOT NULL default '',
  `property_description` varchar(80) default NULL,
  PRIMARY KEY  (`property_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_UserRoles`
-- 

CREATE TABLE `vcd_UserRoles` (
  `role_id` int(10) NOT NULL auto_increment,
  `role_name` varchar(50) NOT NULL default '',
  `role_description` varchar(100) default NULL,
  PRIMARY KEY  (`role_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_UserWishList`
-- 

CREATE TABLE `vcd_UserWishList` (
  `user_id` int(10) NOT NULL default '0',
  `vcd_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_Users`
-- 

CREATE TABLE `vcd_Users` (
  `user_id` int(10) NOT NULL auto_increment,
  `user_name` varchar(80) NOT NULL default '',
  `user_password` varchar(32) NOT NULL default '',
  `user_fullname` varchar(100) NOT NULL default '',
  `user_email` varchar(80) NOT NULL default '',
  `role_id` int(10) NOT NULL default '0',
  `is_deleted` tinyint(4) NOT NULL default '0',
  `date_created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`)
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_VcdToPornCategories`
-- 

CREATE TABLE `vcd_VcdToPornCategories` (
  `vcd_id` int(10) NOT NULL default '0',
  `category_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_VcdToPornStudios`
-- 

CREATE TABLE `vcd_VcdToPornStudios` (
  `vcd_id` int(10) NOT NULL default '0',
  `studio_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_VcdToPornstars`
-- 

CREATE TABLE `vcd_VcdToPornstars` (
  `vcd_id` int(10) NOT NULL default '0',
  `pornstar_id` int(10) NOT NULL default '0'
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_VcdToSources`
-- 

CREATE TABLE `vcd_VcdToSources` (
  `vcd_id` int(10) NOT NULL default '0',
  `site_id` int(10) NOT NULL default '0',
  `external_id` varchar(32) NOT NULL default ''
)  ;

-- --------------------------------------------------------

-- 
-- Table structure for table `vcd_VcdToUsers`
-- 

CREATE TABLE `vcd_VcdToUsers` (
  `vcd_id` int(10) NOT NULL default '0',
  `user_id` int(10) NOT NULL default '0',
  `media_type_id` int(10) NOT NULL default '0',
  `disc_count` int(10) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00'
)  ;


INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_SERVER','undefined','Default SMTP Server that the application uses',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SITE_HOME','undefined','Base url of VCD web',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SITE_NAME','undefined','The name of your website',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_FROM','undefined','Emails address the application uses to send from',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_USER','undefined','Set the user name if the server requires authentication',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_PASS','undefined','Set the authentication password',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_REALM','undefined','Set to the authentication realm, usually the authentication user e-mail domain',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SMTP_DEBUG','0','Set to 1 to output the communication with the SMTP server',1,'bool');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SITE_ADULT','undefined','Show/Allow adult content on the site or not',1,'bool');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SITE_ROOT','undefined','Path within the wwwroot (set to / for root)',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('DB_COVERS','undefined','Store Cover images in  DB',1,'bool');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('SESSION_LIFETIME','undefined','For how many hours should the session stay alive',4,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('PAGE_COUNT','undefined','Number of Records to display in the category view',1,'');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('ALLOW_REGISTRATION','undefined','Allow new users to register or not',1,'bool');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('RSS_SITE','1','Allow RSS feeds from this VCD database',1,'bool');
INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('RSS_USERS','1','Allow RSS feeds from individual users from this VCD Database',1,'bool');

-- <descr>User Roles</descr>
INSERT INTO vcd_UserRoles (role_id,role_name,role_description) VALUES (1,'Administrator','Site administration');
INSERT INTO vcd_UserRoles (role_id,role_name,role_description) VALUES (2,'User','Normal Site User');
INSERT INTO vcd_UserRoles (role_id,role_name,role_description) VALUES (3,'Viewer','Browsing privileges');

-- <descr>User Properties</descr>
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (1,'SHOW_ADULT','Show adult content on the site ?');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (2,'NOTIFY','Send me an email when new movie is added?');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (3,'RSS','Allow RSS feed from my movielist?');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (4,'WISHLIST','Allow others to see my wishlist ?');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (5,'USE_INDEX','Use index number fields for custom media IDs');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (6,'SEEN_LIST','Keep track of movies that I have seen');
INSERT INTO vcd_UserProperties (property_id,property_name,property_description) VALUES (7,'PLAYOPTION','Use client playback options');

-- <descr>CD Cover Types</descr>
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (1,'VCD Front Cover','Front cover for VCD and SVCD movies');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (2,'VCD Back Cover','Back cover for VCD and SVCD movies');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (3,'DVD Cover','Complete DVD cover');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (4,'Inside','Inside cover');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (5,'CD cover','Scanned CD image');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (6,'Inlay','DVD Inlay cover');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (7,'VHS cover','Cover for video cassettes');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (8,'Thumbnail','Thumbnail poster');
INSERT INTO vcd_CoverTypes (cover_type_id,cover_type_name,cover_type_description) VALUES (9,'DVD CD cover','Cover image for DVD and DVD-R');

-- <descr>Media Types</descr>
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (1,'DVD',null,'Standard DVD');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (2,'VCD',null,'Video Compact Disc');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (3,'VHS',null,'Video Cassette');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (4,'SVCD',null,'Super VideoCD');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (5,'DivX',null,'Divx coded');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (6,'Xvid',5,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (7,'Screener',2,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (8,'Telesync',2,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (9,'Cam',2,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (10,'DVD-R',null,'Recorded DVD');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (11,'Screener',10,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (12,'Screener',4,'SVCD Screener');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (13,'TV-Rip',null,'Media recorded from TV');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (14,'SVCD',13,'Ripped with digital boxes');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (15,'Telecine',2,'');
INSERT INTO vcd_MediaTypes (media_type_id,media_type_name,parent_id,media_type_description) VALUES (16,'Telesync',4,'SVCD telesync');

-- <descr>Covers allowed on Media Types</descr>
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 5);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (2, 5);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (1, 5);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (9, 1);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (3, 1);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 1);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (3, 10);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (9, 10);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 10);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (2, 4);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (1, 4);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 4);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (5, 4);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (2, 13);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (1, 13);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 13);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (5, 13);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (2, 2);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (1, 2);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 2);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (5, 2);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (7, 3);
INSERT INTO vcd_CoversAllowedOnMediatypes VALUES (8, 3);

-- <descr>Movie Categories</descr>
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (1,'Action');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (2,'Comedy');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (3,'Horror');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (4,'Drama');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (5,'Adventure');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (6,'Animation');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (7,'Documentary');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (8,'Family');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (9,'Musical');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (10,'Sci-Fi');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (11,'War');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (12,'Western');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (13,'Short');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (14,'Adult');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (15,'X-Rated');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (16,'James Bond');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (17,'Tv Shows');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (18,'Crime');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (19,'Fantasy');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (20,'Film-Noir');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (21,'Mystery');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (22,'Romance');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (23,'Thriller');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (24,'Music Video');
INSERT INTO vcd_MovieCategories (category_id,category_name) VALUES (25,'Anime / Manga');

-- <descr>Source Sites</descr>
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (1,'Internet Movie Database','imdb','http://www.imdb.com',null,1);
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (2,'Adult DVD Empire','DVDempire','http://www.adultdvdempire.com',null,1);
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (3,'Internet Adult Film Database','iafd','http://www.iafd.com/',null,0);
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (4,'Jaded Video','jaded','http://jadedvideo.com',null,0);
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (5,'CDcovers.cc','CDCovers.cc','http://www.cdcovers.cc',null,0);
INSERT INTO vcd_SourceSites (site_id,site_name,site_alias,site_homepage,site_getCommand,site_isFetchable) VALUES (6,'Rotten Tomatos','Rotten Tomatoes','http://rottentomatoes.com',null,0);

-- <descr>System Metadata Types</descr>
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (1, 'languages', 'Excluded langugages', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (2, 'logtypes', 'Log types', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (3, 'frontstats', 'Show frontpage statistics', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (4, 'frontbar', 'Show the latest movies on frontpage', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (5, 'default_role', 'The default role when user registered', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (6, 'player', 'The media player parameters', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (7, 'playerpath', 'The path to the media player', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (8, 'frontrss', 'RSS feeds for the frontpage', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (9, 'ignorelist', 'Users to ignore', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (10, 'mediaindex', 'Custom index for cd', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (11, 'filelocation', 'The media file location', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (12, 'seenlist', 'Item marked seen', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (13, 'dvdregion', 'DVD Region list', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (14, 'dvdformat', 'DVD Format', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (15, 'dvdaspect', 'DVD Ascpect ratio', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (16, 'dvdaudio', 'DVD Audio streams', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (17, 'dvdsubs', 'DVD subtitles', 0);
INSERT INTO vcd_MetaDataTypes (type_id,type_name, type_description, owner_id) VALUES (18, 'nfo', 'NFO Files', 0);

-- <descr>XML Frontpage feeds</descr>
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers All Sex', 'http://adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_26986.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers Compilation', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_27003.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers Feature', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_27004.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers Fetish', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_27005.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers Gonzo', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_27006.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers Interactive ', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_27007.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Bestsellers R-Rated', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_best_33384.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Editors Picks All Sex', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_edpick_25941.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Editors Picks Compilation', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_edpick_25723.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Editors Picks Feature', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_edpick_25720.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Editors Picks Fetish', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_edpick_25944.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Editors Picks Interactive', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_edpick_25725.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Pornstars New Stars', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_pornstars_new.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Pornstars Popular Stars', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_pornstars_pop.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases All Sex', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8925.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases Compilation ', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8927.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases Feature', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8924.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases Fetish', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8923.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases Gonzo', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8926.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases Interactive', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8928.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'Adult DVD Empire - Recent Releases R-Rated', 'http://www.adultdvdempire.com/Data_Include/RSS_Feeds/rss_adult_dvd_video_recent_8930.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'futuremovies.co.uk - Latest articles', 'http://www.futuremovies.co.uk/rss/film.asp');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'futuremovies.co.uk - Latest reviews', 'http://www.futuremovies.co.uk/rss/reviews.asp');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movie-gazette.com - DVD releases', 'http://www.movie-gazette.com/rss/rssdvd.asp');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movie-gazette.com - Screen releases', 'http://www.movie-gazette.com/rss/rsscinema.asp');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - DVD News', 'http://www.movieweb.com/out/dvd_news.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - Latest DVD Easter Eggs', 'http://www.movieweb.com/out/dvd_eggs.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - Latest Film Trailers', 'http://www.movieweb.com/out/trailers.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - Movie News', 'http://www.movieweb.com/out/movie_news.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - This Weeks DVD Releases', 'http://www.movieweb.com/out/releases_dvd.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - This Weeks Theatrical Releases', 'http://www.movieweb.com/out/releases_theater.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'movieweb.com - Todays Box Office Take', 'http://www.movieweb.com/out/box_office_daily.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - Anime', 'http://nforce.nl/rss/rss_16.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - DivX', 'http://nforce.nl/rss/rss_12.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - DVD-R', 'http://nforce.nl/rss/rss_18.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - SVCD', 'http://nforce.nl/rss/rss_15.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - TV-Rips', 'http://nforce.nl/rss/rss_19.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - VCD', 'http://nforce.nl/rss/rss_14.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - XviD', 'http://nforce.nl/rss/rss_13.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'NFOrce.nl - XXX', 'http://nforce.nl/rss/rss_17.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'rottentomatoes.com - Box Office Movies', 'http://images.rottentomatoes.com/files/rss/box_office.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'rottentomatoes.com - Movies Opening This Week', 'http://images.rottentomatoes.com/files/rss/opening.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'rottentomatoes.com - New DVD Releases', 'http://images.rottentomatoes.com/files/rss/new_releases.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'rottentomatoes.com - Top DVD Rentals', 'http://images.rottentomatoes.com/files/rss/top_rentals.xml');
INSERT INTO vcd_RssFeeds (user_id, feed_name, feed_url) VALUES (0, 'rottentomatoes.com - Upcoming Movies', 'http://images.rottentomatoes.com/files/rss/upcoming.xml');

INSERT INTO `vcd_users` VALUES (1,'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin@admin.com', 1, 0, '2005-12-02 00:00:00');