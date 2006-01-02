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
 * @package Kernel
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<?php

class loanObj {
		private $loan_id;
		private $cd_id;
		private $cd_title;
		private $date_in;
		private $date_out;
		/**
		 * @var borrowerObj
		 */
		private $borroweObj;

		
	
	public function __construct($dataArr) {
		$this->loan_id    	  = $dataArr[0];
		$this->cd_id 	  	  = $dataArr[1];
		$this->cd_title   	  = $dataArr[2];
		$this->borroweObj     = $dataArr[3];
		$this->date_out		  = $dataArr[4];
		$this->date_in		  = $dataArr[5];
	}
	
	/**
	 * Get the loan ID
	 *
	 * @return int
	 */
	public function getLoanID() {
		return $this->loan_id;
	}
	
	/**
	 * Get the CD object that is loaned
	 *
	 * @return int
	 */
	public function getCDID() {
		return $this->cd_id;
	}
	
	/**
	 * Get the title of the CD object in loan
	 *
	 * @return string
	 */
	public function getCDTitle() {
			return $this->cd_title;
		
	}
	
	/**
	 * Get the borrower object that is the loantaker
	 *
	 * @return borrowerObj
	 */
	public function getBorrower() {
		return $this->borroweObj;
	}
	
	/**
	 * Get the ID of the borrower
	 *
	 * @return int
	 */
	public function getBorrowerID() {
		if ($this->borroweObj instanceof borrowerObj ) {
			return $this->borroweObj->getID();
		} else {
			return 0;
		}
	}
	
	/**
	 * Get the borrowers name
	 *
	 * @return string
	 */
	public function getBorrowerName() {
		if ($this->borroweObj instanceof borrowerObj ) {
			return $this->borroweObj->getName();
		}
	}
	
	/**
	 * Get the date that the item was borrowed
	 *
	 * @return date
	 */
	public function getDateOut() {
		return $this->date_out;
	}
	
	/**
	 * Check if item has been returned from loan
	 *
	 * @return bool
	 */
	public function isReturned() {
		if (is_integer($this->date_in)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Get the date of item return
	 *
	 * If the item has not been returned, null is returned.
	 *
	 * @return date
	 */
	public function getDateIn() {
		if (isset($this->date_in)) {
			return $this->date_in;
		} else {
			return null;
		}
	}
	
	/**
	 * Calculate the loan time period
	 *
	 */
	public function getLoanTime() {
		if (!isset($this->date_in)) {
			
		} else {
		
		}
	}
	

	



}

?>