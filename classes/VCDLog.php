<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id$
 */
?>
<?php

require_once(dirname(__FILE__).'/settings/logSQL.php');

/**
 * The VCD-db log.
 *
 */
class VCDLog { 

	CONST EVENT_LOGIN    = 1;
	CONST EVENT_ERROR 	 = 2;
	CONST EVENT_SOAPCALL = 3;
	CONST EVENT_RSSCALL  = 4;
	CONST EVENT_EMAILS	 = 5;
	CONST EVENT_TASKS	 = 6;
		
	
	/**
	 * Number of defined Constants
	 *
	 * @var int
	 */
	public static $numEventTypes = 6;
	
	
	/**
	 * Array of EVENT_TYPES to log.
	 *
	 * @var array
	 */
	private static $logItems = null;
	
		
	/**
	 * Get all log entries.  Returns array of VCDLogEntry objects.
	 * If $numrows and $offset are not specified, all entries are returned.
	 *
	 * @param int $numrows | Number of rows to fetch
	 * @param int $offset | Start at offset ..
	 * @param int $item_filter | Filter by specific event type
	 * @return array
	 */
	public static function getLogEntries($numrows = null, $offset = null, $item_filter = null) {
		try {
			return VCDClassFactory::getInstance('logSQL')->getLogEntries($numrows, $offset, $item_filter);
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add new entry to the log.
	 * Use VCDLog::EVENT_* for the selected event type.
	 *
	 * @param int $EVENT_TYPE Use the event types constants defined in VCDLog
	 * @param string $message
	 */
	public static function addEntry($EVENT_TYPE, $message = "") {
		try {
		
			if (!is_numeric($EVENT_TYPE)) {
				throw new Exception('Use EVENT_TYPE defined in VCDLog::EVENT_TYPES');
			}
						
			$entry = new VCDLogEntry($EVENT_TYPE, $message, VCDUtils::getUserID(), date(time()), $_SERVER['REMOTE_ADDR']);
			$logSQL = VCDClassFactory::getInstance('logSQL');
			$logSQL->addEntry($entry);
						
		
		} catch (Exception $ex) {
			throw $ex;
		}		
	}
	
	
	/**
	 * Check if specified EVENT_TYPE is marked for logging.
	 * Use the VCDLog::EVENT_* types for parameter.
	 *
	 * @param int $EVENT_TYPE
	 * @return bool
	 */
	public static function isInLogList($EVENT_TYPE) {
		try {
		
			if (VCDDB_USEPROXY==1) {return false;}
			
			if (is_array(self::$logItems)) {
				return in_array($EVENT_TYPE, self::$logItems);
			}
			
			$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
			$metaArr = $SettingsClass->getMetadata(0, 0, 'logtypes');
			if (is_array($metaArr) && sizeof($metaArr) > 0) {
				$metaObj = $metaArr[0];
				if ($metaObj instanceof metadataObj ) {
					self::$logItems = explode('#', $metaObj->getMetadataValue());			
				}
				
				return in_array($EVENT_TYPE, self::$logItems);
			} 
			
			return false;
		
		} catch (Exception $ex) {
			throw $ex;
		}		
	}
	
	
	/**
	 * Get Count of Log entries.
	 *
	 * @param int $item_filter | The Item Event to filter by
	 * @return int
	 */
	public static function getLogCount($item_filter = null)
	{
		try {
			
			return VCDClassFactory::getInstance('logSQL')->getLogCount($item_filter);
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Clear all log entries from database
	 *
	 */
	public static function clearLog() 
	{
		try {
			VCDClassFactory::getInstance('logSQL')->clearLog();
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the common description of the current log constant.
	 *
	 * @param int $EVENT_TYPE | EVENT_TYPE defined in VCDLog::EVENTS_
	 * @return string
	 */
	public static function getLogTypeDescription($EVENT_TYPE) {
		try {
		
			if (!is_numeric($EVENT_TYPE))
				throw new Exception("EVENT_TYPE needs to be numeric");
				
				
			switch ($EVENT_TYPE) {
				case VCDLog::EVENT_ERROR:
					return "Error";	
					break;
			
				case VCDLog::EVENT_LOGIN:
					return "Authentication";
					break;
					
				case VCDLog::EVENT_RSSCALL:
					return "RSS Call";
					break;
					
				case VCDLog::EVENT_SOAPCALL:
					return "SOAP Call";
					break;
				
				case VCDLog::EVENT_EMAILS:
					return "Sent Emails";
					break;
					
				case VCDLog::EVENT_TASKS :
					return "Tasks";
					break;
					
				default:
					return "Unknown";
					break;
			}

			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	

}


/**
 * One log entry in the log.
 *
 */
class VCDLogEntry {

	/**
	 * Event type.  Uses the entry types defined in the VCDLoc class.
	 *
	 * @var int
	 */
	private $eventType;
	/**
	 * The Log message text.
	 *
	 * @var text
	 */
	private $message;
	/**
	 * The userid that triggers the log entry, if any.
	 *
	 * @var int
	 */
	private $user_id;
	/**
	 * Timestamp of the log entry
	 *
	 * @var date
	 */
	private $datetime;
	
	/**
	 * IP address of the computer that triggered the error.
	 *
	 * @var string
	 */
	private $remote_ip;
	
	/**
	 * Create a new log entry.
	 *
	 * @param int $type
	 * @param string $message
	 * @param int $user_id
	 * @param date $datetime
	 */
	public function __construct($type, $message, $user_id = null, $datetime = null, $remote_ip = null) {		
		$this->eventType = $type;
		$this->message = $message;
		$this->user_id = $user_id;
		$this->datetime = $datetime;
		$this->remote_ip = $remote_ip;
	}
	
	
	/**
	 * Get the numeric type of the log entry.
	 *
	 * @return int
	 */
	public function getType() {
		return $this->eventType;
	}
	
	/**
	 * Get the log entry message.
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * Get the user id that belongs to the log entry.
	 * If user was not logged in when log entry was made, 0 is returned.
	 *
	 * @return int
	 */
	public function getUserID() {
		if (is_numeric($this->user_id)) {
			return $this->user_id;
		} else {
			return 0;
		}
	}
	
	/**
	 * Get the date of the log entry
	 *
	 * @return date
	 */
	public function getDate()
	{
		return $this->datetime;
	}
	
	/**
	 * Get the IP address of the user that triggered the log entry.
	 *
	 * @return string
	 */
	public function getIP()
	{
		return $this->remote_ip;
	}
	
	
}



?>