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
 * @package Vcd
 * @version $Id$
 */
 
?>
<? 
/**
	Class cdObj
	Root object for the application.
	For future expansion, all new cd object must derive from this base class
	This class cannot be instance-iated.	
*/

	abstract class cdObj {
	
		/* local variables */
		protected $id;
		protected $title;
		protected $year;
		
		
					
		/**
		 * Get the cd objects ID
		 *
		 * @return int
		 */
		public function getID() {
			return $this->id;
		}
						
		/**
		 * Set the cd object ID
		 *
		 * @param int $id
		 */
		public function setID($id) {
			$this->id = $id;
		}		
		
		/**
		 * Get title
		 *
		 * @return string
		 */
		public function getTitle() {
			return $this->title;
		}
		
		/**
		 * Set title
		 *
		 * @param string $strTitle
		 */
		public function setTitle($strTitle) {
			$this->title = $strTitle;
		}
		
		/**
		 * Get year
		 *
		 * @return int
		 */
		public function getYear() {
			return $this->year;
		}
		
		/**
		 * Set year
		 *
		 * @param int $iYear
		 */
		public function setYear($iYear) {
			$this->year = $iYear;
		}
			
		
		/**
		 * Get the id and name of this object as an array
		 *
		 * @return array
		 */
		public function getList() {
    	    return array("id"   => $this->id,
         	             "name" => $this->title);
		}

		
		
	}


?>