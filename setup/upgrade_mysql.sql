CREATE TABLE `vcd_Log` (
  `event_id` tinyint(4) NOT NULL default '0',
  `message` varchar(200) NOT NULL default '',
  `user_id` int(11) default NULL,
  `event_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default ''
);

CREATE TABLE `vcd_MetaDataTypes` (
  `type_id` int(10) unsigned NOT NULL auto_increment,
  `type_name` varchar(50) default NULL,
  `type_description` varchar(150) default NULL,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`type_id`),
  KEY `metadata_type_id` (`type_id`)
);
