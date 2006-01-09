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

class movieCategoryObj implements XMLable {
		protected $category_id;
		protected $category_name;
		protected $category_count;

		/**
		 * Object constructor
		 *
		 * @param array $dataArr
		 */
		public function __construct($dataArr) {
			$this->category_id   = $dataArr[0];
			$this->category_name = $dataArr[1];
		}


		/**
		 * Get the moviecategory ID
		 *
		 * @return int
		 */
		public function getID() {
			return $this->category_id;
		}

		/**
		 * Get the movie category name
		 *
		 * @param bool $localize | Get the localized name or not
		 * @return string
		 */
		public function getName($localize=true){
			if ($localize && isset($map[$this->category_name]) && $language->show($map[$this->category_name]) != "undefined") {
				global $language;
				$map = getCategoryMapping();
				return $language->show($map[$this->category_name]);
			} else {
				return $this->category_name;
			}
		}

		/**
		 * Set the number of movies belonging to current category object.
		 *
		 * @param int $num
		 */
		public function setCategoryCount($num) {
			$this->category_count = $num;
		}

		/**
		 * Get the movie count under this category object.
		 *
		 * @return int
		 */
		public function getCategoryCount() {
			return $this->category_count;
		}

		/**
		 * Get the XML representation of the object.
		 *
		 * @return string
		 */
		public function toXML() {
			$xmlstr  = "<category>\n";
			$xmlstr .= "<id>".$this->category_id."</id>\n";
			$xmlstr .= "<name>".$this->category_name."</name>\n";
			$xmlstr .= "</category>\n";

			return $xmlstr;

		}


		/**
		 * Get the id and name of the object as an array
		 *
		 * @return array
		 */
		public function getList() {
        	return array("id"   => $this->category_id,
                     	"name" => $this->category_name);
		}



}

?>