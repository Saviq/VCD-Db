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
<?PHP

class VCDException extends Exception {

	protected $name = null;
	
	/**
	 * Constructor
	 *
	 * @param Exception $exception
	 */
	public function __construct($message = null, $code = 0) { 
		
		if (is_null($this->getName())) {
 	      $this->setName(get_class($self));
 	    }
 	    
		parent::__construct($message, $code);
	} 

	
	/**
	 * Display the exception to user with javascript alert box.
	 *
	 * $goback parameter indicated weither to make browser go back after
	 * displaying the error message or not.  Param $exception can either be an
	 * Exception or string containing the error message.
	 *
	 * @param mixed $exception
	 * @param bool $goback
	 */
	public static function display($exception, $goback = false) { 
	   $err = "Exception occurred.";
	
	   if ($exception instanceof Exception) {
	   		
	   		if ($exception instanceof VCDException ) {
	   			$err = $exception->getName() . " occurred.";	
	   		}
	   	
	   		$error_file = basename($exception->getFile());
	   		
	   		$exmsg = str_replace(Chr(13),"",$exception->getMessage());
	   		$exmsg = str_replace(Chr(12),"",$exmsg);
	   		$exmsg = str_replace(Chr(11),"",$exmsg);
	   		$exmsg = str_replace(Chr(9),"",$exmsg);
	   		
	   		$msg = "Message: " . $exmsg;
	   		
	   		$message = $err ."<break>";
	   		$message .= "File: " .$error_file ."<break>";
	   		$message .= "Line: ".$exception->getLine()."<break>";
	   		$message .= $msg."<break>";
	   		
	   		
	   } else {
	   		$msg = "Message: " . $exception;
	   		$message = $err."<break>".$msg."<break>";
	   }
	   
	  
	   // Check if this needs looging ..
	   self::logException($exception);
	      		   
	      		   
		print "<script>";
		print "alert('".self::fixMessageForJS($message)."');";	       
		print "</script>";
		if ($goback) {
			self::goBack();
		}
	}
	
	
	/**
	 * Get the name of the exception
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set the name of the exception
	 *
	 * @param string $name | The Exception name
	 */
	protected function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Navigate browser back.
	 *
	 */
	private static function goBack() {
		print "<script>history.back(-1)</script>";
		exit();
	}
	
	
	/**
	 * Fix the error message so they will display properly in javascript alert window.
	 *
	 * @param string $errorMessage
	 * @return string
	 */
	private static function fixMessageForJS($errorMessage) {
		   			
		$errorMessage = str_replace('"','\'',"$errorMessage");
		$errorMessage = str_replace("'",'\"',"$errorMessage");
		
		$errorMessage = str_replace("(","[","$errorMessage");
		$errorMessage = str_replace(")","]","$errorMessage");
		$errorMessage = str_replace("\n", " ", "$errorMessage");
		
		$errorMessage = str_replace("<break>", "\\n", "$errorMessage");
		
		return $errorMessage;
	}

	/**
	 * Check if we are supposed to log the error and if so then write it do database.
	 *
	 * @param mixed $exception | Either instance of Exception or plain text
	 */
	private static function logException($exception) {
		// Check if we are supposed to log this ...
		if (VCDLog::isInLogList(VCDLog::EVENT_ERROR )) {
			if ($exception instanceof Exception ) {
				$error_file = basename($exception->getFile());
				$logmsg = "File:".$error_file. " Message:".$exception->getMessage();
			} else {
				$logmsg = $exception;
			}
			
		VCDLog::addEntry(VCDLog::EVENT_ERROR , $logmsg);
		}
	}
}

/**
 * Exceptions that are thrown when invalid arguments are passed to
 * functions within VCD-db.
 *
 */
class VCDInvalidArgumentException extends VCDException {

	public function __construct ($message = null, $code = 0) {
		$this->setName(get_class($this));
		parent::__construct($message, $code);
	}
}

/**
 * Exceptions that are thrown in the database layer.
 *
 */
class VCDSqlException extends VCDException {

	public function __construct ($message = null, $code = 0) {
		$this->setName(get_class($this));
		parent::__construct($message, $code);
	}
}

/**
 * Exceptions that are thrown when required constraints are broken.
 *
 */
class VCDConstraintException extends VCDException {

	public function __construct ($message = null, $code = 0) {
		$this->setName(get_class($this));
		parent::__construct($message, $code);
	}
}

/**
 * Exceptions that are unrecoverable.
 *
 */
class VCDProgramException extends VCDException {

	public function __construct ($message = null, $code = 0) {
		$this->setName(get_class($this));
		parent::__construct($message, $code);
	}
}	

?>