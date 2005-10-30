<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Settings
 * @version $Id$
 */
 
?>
<?PHP

class logSQL {
		
		private $TABLE_log   = "vcd_Log";
		/**
		 *
		 * @var ADOConnection
		 */
		private $db = null;

		public function __construct() {
			$conn = new Connection();
	 		$this->db = &$conn->getConnection();
		}
		
		
		public function addEntry(VCDLogEntry $entry) {
			try {
				
				$query = "INSERT INTO $this->TABLE_log (event_id, message, user_id, event_date, ip) VALUES 
						  (".$entry->getType().", ".$this->db->qstr($entry->getMessage()).", 
						  ".$entry->getUserID().",  ".$this->db->DBTimeStamp(time()).", ".$this->db->qstr($entry->getIP()).") ";
				$this->db->Execute($query);
			
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function getLogEntries($date_from = null, $date_to = null) {
			try {
			
				if (is_null($date_from) && is_null($date_to)) {
					$query = "SELECT event_id, message, user_id, event_date, ip FROM $this->TABLE_log ORDER BY event_date DESC";
				} else {
				
				}
				
				
				$rs = $this->db->Execute($query);
				$arrLogEntries = array();
				foreach ($rs as $row) {
		    		$obj = new VCDLogEntry($row[0], $row[1], $row[2], $row[3], $row[4]);
		    		array_push($arrLogEntries, $obj);
				}
				
				$rs->Close();
				return $arrLogEntries;
			
			
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}			
			
		}
		
		
}