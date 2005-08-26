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
<?PHP
	
	class VCDException extends Exception {
		
		/**
		 * Constructor
		 *
		 * @param Exception $exception
		 */
		function __construct(Exception $exception) { 
       		$this->exception = $exception; 
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
   		public function display($exception, $goback = false) { 
   		   $err = "Exception occurred.";
   		
   		   if ($exception instanceof Exception) {
   		   		
   		   		$file = str_replace('\\',"#",$exception->getFile());
   		   		$file = split("#",$file);
   		   		$error_file = $file[sizeof($file)-1];
   		   		
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
   		   
   		      		   
   		      		   
	       print "<script>";
	       print "alert('".VCDException::fixMessageForJS($message)."');";	       
	       print "</script>";
	       if ($goback) {
	       	  VCDException::goBack();
	       }
   		} 
   		
   		
   		
   		
   		/**
   		 * Navigate browser back.
   		 *
   		 */
   		private function goBack() {
   			print "<script>history.back(-1)</script>";
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
   		
	
	}


?>