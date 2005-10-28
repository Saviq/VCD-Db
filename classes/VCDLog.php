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
 * @package Core
 * @version $Id$
 */
?>
<?



/**
 * The VCD-db log.
 *
 */
class VCDLog { 

	CONST EVENT_LOGIN    = 1;
	CONST EVENT_ERROR 	 = 2;
	CONST EVENT_SOAPCALL = 3;
	CONST EVENT_RSSCALL  = 4;
		
	public function __construct() {
		
	}
	
	
	
	/**
	 * Add new entry to the log.
	 * Use VCDLog::EVENT_* for the selected event type.
	 *
	 * @param int $EVENT_TYPE Use the event types constants defined in VCDlog
	 * @param string $message
	 */
	public static function addEntry($EVENT_TYPE, $message = "") {
		try {
		
			$entry = new VCDLogEntry($EVENT_TYPE, $message, VCDUtils::getUserID(), date(time()));
						
		
		} catch (Exception $ex) {
			VCDException::display($ex);	
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
	 * Create a new log entry.
	 *
	 * @param int $type
	 * @param string $message
	 * @param int $user_id
	 * @param date $datetime
	 */
	public function __construct($type, $message, $user_id = null, $datetime = null) {		
		$this->eventType = $type;
		$this->message = $message;
		$this->user_id = $user_id;
		$this->datetime = $datetime;
	}
	
}



?>