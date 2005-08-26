CREATE TABLE `vcd_MetaData` (
  `metadata_id` int(10) NOT NULL auto_increment,
  `record_id` int(10) NOT NULL default '0',
  `user_id` int(10) NOT NULL default '0',
  `metadata_name` varchar(50) NOT NULL default '',
  `metadata_value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`metadata_id`)
) AUTO_INCREMENT=1 ;