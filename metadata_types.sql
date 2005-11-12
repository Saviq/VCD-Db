CREATE TABLE `vcd_metadatatypes` (
  `metadata_type_id` int(10) unsigned NOT NULL auto_increment,
  `metadata_type_name` varchar(50) default NULL,
  `metadata_type_level` enum('system','user') NOT NULL default 'system',
  PRIMARY KEY  (`metadata_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

INSERT INTO `vcd_metadatatypes` VALUES (1, 'languages', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (2, 'logtypes', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (3, 'frontstats', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (4, 'frontbar', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (5, 'default_role', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (6, 'player', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (7, 'playerpath', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (8, 'frontrss', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (9, 'ignorelist', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (10, 'mediaindex', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (11, 'filelocation', 'system');
INSERT INTO `vcd_metadatatypes` VALUES (12, 'seenlist', 'system');