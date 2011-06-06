<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<?php

final class logSQL extends VCDConnection {
		
	private $TABLE_log   = "vcd_Log";

	/**
	 * Class constructor
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * Add a single LogEntry to database.
	 *
	 * @param VCDLogEntry $entry
	 */
	public function addEntry(VCDLogEntry $entry) {
		try {
			
			$query = "INSERT INTO $this->TABLE_log (event_id, message, user_id, event_date, ip) VALUES 
					  (".$entry->getType().", ".$this->db->qstr($entry->getMessage()).", 
					  ".$entry->getUserID().",  ".$this->db->DBTimeStamp(time()).", ".$this->db->qstr($entry->getIP()).") ";
			$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	/**
	 * Get all LogEntries within the specified time interval.  If $numrows and $offset are not specified
	 * All LogEntries from database will be returned.
	 * Returns array of LogEntries
	 *
	 * @param int $numrows | Number of rows to fetch
 	 * @param int $offset | Start at offset ..
 	 * @param int $item_filter | Filter by specific event type
	 * @return array
	 */
	public function getLogEntries($numrows = null, $offset = null, $item_filter = null) {
		try {
			
			$usefilter = "";
			if (!is_null($item_filter) && is_numeric($item_filter)) {
				$usefilter = "WHERE event_id = " . $item_filter;
			}
		
			if (is_null($numrows) && is_null($offset)) {
				$query = "SELECT event_id, message, user_id, event_date, ip FROM $this->TABLE_log {$usefilter} ORDER BY event_date DESC";
				$rs = $this->db->Execute($query);
			} else {
				$query = "SELECT event_id, message, user_id, event_date, ip FROM $this->TABLE_log {$usefilter} ORDER BY event_date DESC";
				$rs = $this->db->SelectLimit($query, $numrows, $offset);
				
			}
								
			
			$arrLogEntries = array();
			foreach ($rs as $row) {
	    		$obj = new VCDLogEntry($row[0], $row[1], $row[2], $row[3], $row[4]);
	    		array_push($arrLogEntries, $obj);
			}
			
			$rs->Close();
			return $arrLogEntries;
		
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	/**
	 * Delete all LogEntries from database
	 *
	 */
	public function clearLog() {
		try {
		
			$query = "DELETE FROM $this->TABLE_log";
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	/**
	 * Get the count of total logentries in database.
	 *
	 * @param int $item_filter | The Item Event to filter by
	 * @return int
	 */
	public function getLogCount($item_filter = null) {
		try {
						
			$query = "SELECT COUNT(*) FROM $this->TABLE_log";
			
			if (!is_null($item_filter) && is_numeric($item_filter)) {
				$query .= " WHERE event_id = " . $item_filter;
			}
			
			return $this->db->getOne($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
}
?>