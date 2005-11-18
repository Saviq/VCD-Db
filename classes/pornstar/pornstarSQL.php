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
 * @package Porndata
 * @version $Id$
 */
 ?>
<?

	class pornstarSQL  {
		
		private $TABLE_pornstars 	  = "vcd_Pornstars";
		private $TABLE_vcdtopornstars = "vcd_VcdToPornstars";
		private $TABLE_studios		  = "vcd_PornStudios";
		private $TABLE_adultcategory  = "vcd_PornCategories";
		private $TABLE_vcdtocategory  = "vcd_VcdToPornCategories";
		private $TABLE_vcdtostudios	  = "vcd_VcdToPornStudios";
		private $TABLE_vcd			  = "vcd";
		/**
		 * @var ADOConnection
		 */
		private $db;
		/**
		 * @var Connection
		 */
		private $conn;
	 			
		
		public function __construct() {
			$conn = VCDClassFactory::getInstance('Connection');
	 		$this->db = &$conn->getConnection();
	 		$this->conn = &$conn;
		}
		
		public function getAllPornstars() {
			try {
				
			$query = "SELECT P.pornstar_id, P.name, P.homepage, P.image_name, P.biography, P.pornstar_ID as xv
					  FROM $this->TABLE_pornstars P
					  ORDER BY P.name";
			
			$rs = $this->db->Execute($query);
			$arrPornstarsObj = array();
			foreach ($rs as $row) {
	    		$obj = new pornstarObj($row);
	    		array_push($arrPornstarsObj, $obj);
			}
			
			$rs->Close();
			return $arrPornstarsObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function getPornstarByID($pornstar_id) { 
			try {
				
			$query = "SELECT P.pornstar_id, P.name, P.homepage, P.image_name, P.biography
					  FROM $this->TABLE_pornstars P WHERE P.pornstar_id = " . $pornstar_id; 
						
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$obj = new pornstarObj($rs->FetchRow());
				
				// Get the movie list
				$query = "SELECT v.vcd_id, v.title FROM $this->TABLE_vcd v, $this->TABLE_vcdtopornstars p WHERE
						  p.vcd_id = v.vcd_id AND p.pornstar_id = ".$pornstar_id." 
						  ORDER BY v.title";
				$rs = $this->db->GetAssoc($query);
				if ($rs) {
					$obj->setMovies($rs);
				}

				return $obj;
							
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getPornstarByName($pornstar_name) { 
			try {
				
			$query = "SELECT P.pornstar_id, P.name, P.homepage, P.image_name, P.biography
					  FROM $this->TABLE_pornstars P WHERE P.name = ".$this->db->qstr($pornstar_name).""; 
						
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return new pornstarObj($rs->FetchRow());
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function updatePornstar(pornstarObj $pornstar)  {
			try {
				
			$query = "UPDATE $this->TABLE_pornstars 
					  SET name = ".$this->db->qstr($pornstar->getName()).",
			          homepage = ".$this->db->qstr($pornstar->getHomepage()).",
					  image_name = ".$this->db->qstr($pornstar->getImageName()).",
					  biography = ".$this->db->qstr($pornstar->getBiography())."
				      WHERE pornstar_id = " . $pornstar->getID();
			$this->db->Execute($query);
		
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		
		}
		
		public function getPornstarsByMovieID($movie_id) {
			try {
			
			// No need to get the big biography TEXT field here.			
			$query = "SELECT P.pornstar_id, P.name, P.homepage, P.image_name
					  FROM $this->TABLE_pornstars P, $this->TABLE_vcdtopornstars T
					  WHERE T.pornstar_id = P.pornstar_id AND T.vcd_id = $movie_id
					  ORDER BY P.name";
			
			$rs = $this->db->Execute($query);
			$arrPornstarsObj = array();
			foreach ($rs as $row) {
	    		$obj = new pornstarObj($row);
	    		array_push($arrPornstarsObj, $obj);
			}
			
			$rs->Close();
			return $arrPornstarsObj;
		
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function addPornstar(pornstarObj $pornstarObj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_pornstars (name, homepage, image_name, biography)
					  VALUES (".$this->db->qstr($pornstarObj->getName()).",
					  ".$this->db->qstr($pornstarObj->getHomepage()).",
			          ".$this->db->qstr($pornstarObj->getImageName()).",
			          ".$this->db->qstr($pornstarObj->getBiography()).")";
			/* 	Returns the last autonumbering ID inserted. Returns false if function not supported. 
				Only supported by databases that support auto-increment or object id's,
				such as PostgreSQL, MySQL and MS SQL Server currently. PostgreSQL returns the OID, 
				which can change on a database reload.	*/
			
			// Execute the statement
			$this->db->Execute($query);
					
			$inserted_id = -1;
			$inserted_id = $this->db->Insert_ID();
			
			if ($this->conn->getSQLType() == 'postgres7') {
				
				return $this->conn->oToID($this->TABLE_pornstars, 'pornstar_id');
				
			} elseif (is_numeric($inserted_id) && $inserted_id > 0) {

				return $inserted_id;
				
			} else {
				// InsertedID not supported, we have to dig the lates entry out manually
				$query = "SELECT pornstar_id FROM $this->TABLE_pornstars ORDER BY pornstar_id DESC";
				$rs = $this->db->SelectLimit($query, 1);
				
				// Should only be 1 recordset
				foreach ($rs as $row) {
					$inserted_id = $row[0];
				}
						
				return $inserted_id;
							
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		
		}
		
		
		public function addPornstarToMovie($pornstar_id, $movie_id) { 
			try {
				
			$query = "INSERT INTO $this->TABLE_vcdtopornstars (vcd_id, pornstar_id) VALUES (".$movie_id.", ".$pornstar_id.")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deletePornstarFromMovie($pornstar_id, $movie_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_vcdtopornstars WHERE vcd_id = ".$movie_id." AND
					  pornstar_id = ".$pornstar_id."";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		/* Functions for adult studios */
		public function getAllStudios() {
			try {
				
			$query = "SELECT studio_id, studio_name FROM $this->TABLE_studios ORDER BY studio_name";
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				array_push($arrObj, new studioObj($row));
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getStudiosInUse() {
			try {
				
			$query = "SELECT s.studio_id, s.studio_name FROM
					  $this->TABLE_studios s, $this->TABLE_vcdtostudios t
					  WHERE s.studio_id = t.studio_id
					  GROUP BY s.studio_id, s.studio_name
					  ORDER BY s.studio_name";		
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				array_push($arrObj, new studioObj($row));
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getStudioByID($studio_id) {
			try {
				
			$query = "SELECT studio_id, studio_name FROM $this->TABLE_studios WHERE studio_id = " . $studio_id;
			$rs = $this->db->Execute($query);
			if ($rs) {
				return new studioObj($rs->FetchRow());
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getStudioByName($studio_name) {
			try {
				
			$query = "SELECT studio_id, studio_name FROM $this->TABLE_studios WHERE studio_name = ".$this->db->qstr($studio_name)."";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return new studioObj($rs->FetchRow());
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	
		public function getStudioByMovieID($vcd_id)  {
			try {
				
			$query = "SELECT s.studio_id, s.studio_name FROM $this->TABLE_studios s, $this->TABLE_vcdtostudios ts
				      WHERE s.studio_id = ts.studio_id AND ts.vcd_id = ".$vcd_id."";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return new studioObj($rs->FetchRow());
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		
		}
		
		public function addMovieToStudio($studio_id, $vcd_id) {
			try {
				
			$query = "INSERT INTO $this->TABLE_vcdtostudios (vcd_id, studio_id) VALUES (".$vcd_id.",".$studio_id.")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		public function deleteMovieFromStudio($vcd_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_vcdtostudios WHERE vcd_id = " . $vcd_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
			
		
		public function getSubCategories()	{
			try {
				
			$query = "SELECT category_id, category_name FROM $this->TABLE_adultcategory ORDER BY category_name";
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				array_push($arrObj, new porncategoryObj($row));
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getSubCategoriesInUse() {
			try {
				
			$query = "SELECT c.category_id, c.category_name FROM
					  $this->TABLE_adultcategory c, $this->TABLE_vcdtocategory t
					  WHERE c.category_id = t.category_id
					  GROUP BY c.category_id, c.category_name
					  ORDER BY c.category_name";
			
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				array_push($arrObj, new porncategoryObj($row));
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getSubCategoryByID($category_id) {
			try {
				
			$query = "SELECT category_id, category_name FROM $this->TABLE_adultcategory WHERE category_id = " . $category_id;
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return new porncategoryObj($rs->FetchRow());
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function deleteMovieFromCategories($vcd_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_vcdtocategory WHERE vcd_id = " . $vcd_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getSubCategoriesByMovieID($vcd_id) {
			try {
				
			$query = "SELECT c.category_id, c.category_name FROM $this->TABLE_adultcategory c, 
					  $this->TABLE_vcdtocategory t WHERE t.category_id = c.category_id AND
					  t.vcd_id = ".$vcd_id." ORDER BY c.category_name";
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				array_push($arrObj, new porncategoryObj($row));
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function addCategoryToMovie($vcd_id, $category_id) {
			try {
				
			$query = "INSERT INTO $this->TABLE_vcdtocategory (vcd_id, category_id) VALUES (".$vcd_id.", ".$category_id.")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getPornstarsAlphabet($active_only) {
			try {
				
			if ($active_only) {
				$query = "SELECT DISTINCT ".$this->db->substr."(p.name, 1, 1) AS Letter 
						  FROM $this->TABLE_pornstars p, $this->TABLE_vcdtopornstars t
						  WHERE p.pornstar_id = t.pornstar_id";
			} else {
				$query = "SELECT DISTINCT ".$this->db->substr."(name, 1, 1) AS Letter FROM $this->TABLE_pornstars";
			}
			
			$rs = $this->db->Execute($query);
			$arrAlphabet = array();
			if ($rs && $rs->RecordCount() > 0) {
				foreach ($rs as $row) {
					array_push($arrAlphabet, $row[0]);
				}
				
				$rs->Close();
				return $arrAlphabet;
			}
			
			return null;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		}
		
		public function getPornstarsByLetter($letter, $active_only) {
			try {
							
			if ($active_only) {
				$query = "SELECT p.pornstar_id, p.name, p.homepage, p.image_name,  
						  COUNT(v.pornstar_id) as numfilms 
						  FROM $this->TABLE_pornstars p, $this->TABLE_vcdtopornstars v
						  WHERE p.name LIKE '".$letter."%' AND
						  p.pornstar_id = v.pornstar_id
						  GROUP BY p.pornstar_id, p.name, p.homepage, p.image_name
						  ORDER BY p.name";
			
			} else {
				
				$query = "SELECT p.pornstar_id, p.name, p.homepage, p.image_name,  
						  COUNT(v.pornstar_id) as numfilms 
						  FROM $this->TABLE_pornstars AS p
						  LEFT OUTER JOIN $this->TABLE_vcdtopornstars AS v ON p.pornstar_id = v.pornstar_id
						  WHERE p.name LIKE '".$letter."%' 
						  GROUP BY p.pornstar_id, p.name, p.homepage, p.image_name
						  ORDER BY p.name";
				
			}
			
					
			$rs = $this->db->Execute($query);
			$arrPornstarsObj = array();
			foreach ($rs as $row) {
				$data = array($row[0], $row[1], $row[2],$row[3],'',$row[4]);
	    		$obj = new pornstarObj($data);
	    		array_push($arrPornstarsObj, $obj);
			}
			
			$rs->Close();
			return $arrPornstarsObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		public function addAdultCategory(porncategoryObj $obj) {
			try {
			
				$query = "INSERT INTO $this->TABLE_adultcategory (category_name) VALUES (".$this->db->qstr($obj->getName()).") ";
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function deleteAdultCategory($category_id) {
			try {
			
				$query = "DELETE FROM $this->TABLE_adultcategory WHERE category_id = " . $category_id;
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		
		}
		
		
		public function addStudio(studioObj $obj) {
			try {
			
				$query = "INSERT INTO $this->TABLE_studios (studio_name) VALUES (".$this->db->qstr($obj->getName()).")";
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deleteStudio($studio_id) {
			try {
			
				$query = "DELETE FROM $this->TABLE_studios WHERE studio_id = " . $studio_id;
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	
	}


?>