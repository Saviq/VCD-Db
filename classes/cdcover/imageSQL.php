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
 * @subpackage CDCover
 * @version $Id$
 */
 
?>
<?php

class imageSQL extends VCDConnection {
	
	/**
	 * Constant representing the image table name in database.
	 *
	 * @var string
	 */
	private $TABLE_images = "vcd_Images";

 			

	
	/**
	 * Object constructor.
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	* @return The inserted Image id
	* @desc Add image to the database
	
	* @param string $name
	* @param string $file_type
	* @param string $file_size
	* @param MIME64 string $stream
	*/
	public function addImage($name = 'Unnamed image', $file_type = 'image/jpg', $file_size = 0, $stream = null) {
		
		try {
		
		
			if ($file_size == 0 || $stream == null) {
				return false;
			}
			
			
			// Figure out the name of the file and extension ..
			
			
			$query = "INSERT INTO $this->TABLE_images (name, image_type, image_size, image)
					  VALUES (".$this->db->qstr($name).",".$this->db->qstr($file_type).", $file_size, null)";

			// Execute the query
			$this->db->Execute($query);
			
			// Get the last inserted ID
			try {
				$new_imageid = $this->db->Insert_Id($this->TABLE_images, 'image_id');
			} catch (Exception $ex) {
			// Check if this is a Postgre not using OID columns
			if ($this->isPostgres()) {
				// Yeap, postgres not using OID ..
				$new_imageid = $this->oToID($this->TABLE_images, 'image_id');
			} else {
				throw $ex;
			}
		}
			
			
			
			// is the new imageID ok ?
			if (!is_numeric($new_imageid)) {
				// seems not ok .. let's get last inserted id manually
				$query = "SELECT image_id FROM $this->TABLE_images ORDER BY image_id DESC";
				$rs = $this->db->SelectLimit($query, 1);
				// Should only be 1 recordset
				foreach ($rs as $row) {
					$new_imageid = $row[0];
				}
				$rs->Close();
			}
			
			
			$update_value = "image_id=" . $new_imageid;
			
			
			// Update the BLOB field with the actual MIME64 stream
		   	$this->db->UpdateBlob($this->TABLE_images,'image', $stream, $update_value);	
		   	
		   	return $new_imageid;
	   	
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
		
	}
	
	
	/**
	 * Get the binary stream from database.
	 *
	 * @param int $image_id
	 * @return string
	 */
	public function getImageStream($image_id) {
		
		try {
		
			$query = "SELECT image FROM $this->TABLE_images WHERE image_id =". $image_id;
			$stream = $this->db->GetOne($query);
			if (!$stream) {
				return false;
			} else {
				return base64_decode($stream);	
			}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
		
	}
	
	/**
	 * Get all image data from database.  Except for the binary image stream.
	 *
	 * @param int $image_id
	 * @return array
	 */
	public function getImageDetails($image_id) {
		try {
		
			if (!is_numeric($image_id)) {
				throw new Exception("Invalid image ID");
			}
			
			
			$query = "SELECT image_id, name, image_type, image_size FROM $this->TABLE_images
					  WHERE image_id = " . $image_id;
			$rs = $this->db->Execute($query);
			if ($rs) {
				return $rs->GetRowAssoc();
			} else {
				return null;
			}
			
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		} 
	
	}
	
	
}
?>