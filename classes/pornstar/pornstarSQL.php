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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<?php

class pornstarSQL extends VCDConnection {
	
	private $TABLE_pornstars 	  = "vcd_Pornstars";
	private $TABLE_vcdtopornstars = "vcd_VcdToPornstars";
	private $TABLE_studios		  = "vcd_PornStudios";
	private $TABLE_adultcategory  = "vcd_PornCategories";
	private $TABLE_vcdtocategory  = "vcd_VcdToPornCategories";
	private $TABLE_vcdtostudios	  = "vcd_VcdToPornStudios";
	private $TABLE_vcd			  = "vcd";
		
	public function __construct() {
		parent::__construct();
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function updatePornstar(pornstarObj $pornstar)  {
		try {
			
		// Oracle check ..
		$biography = $pornstar->getBiography();
		if ($this->isOracle() && strlen($biography) >= 4000) {
			$biography = $this->shortenText($biography, 3990);
		}
			
		$query = "UPDATE $this->TABLE_pornstars 
				  SET name = ".$this->db->qstr($pornstar->getName()).",
		          homepage = ".$this->db->qstr($pornstar->getHomepage()).",
				  image_name = ".$this->db->qstr($pornstar->getImageName()).",
				  biography = ".$this->db->qstr($biography)."
			      WHERE pornstar_id = " . $pornstar->getID();
		$this->db->Execute($query);
	
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
	
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addPornstar(pornstarObj $pornstarObj) {
		try {
			
			
		// Oracle check ..
		$biography = $pornstarObj->getBiography();
		if ($this->isOracle() && strlen($biography) >= 4000) {
			$biography = $this->shortenText($biography, 3990);
		}
			
		$query = "INSERT INTO $this->TABLE_pornstars (name, homepage, image_name, biography)
				  VALUES (".$this->db->qstr($pornstarObj->getName()).",
				  ".$this->db->qstr($pornstarObj->getHomepage()).",
		          ".$this->db->qstr($pornstarObj->getImageName()).",
		          ".$this->db->qstr($biography).")";
		/* 	Returns the last autonumbering ID inserted. Returns false if function not supported. 
			Only supported by databases that support auto-increment or object id's,
			such as PostgreSQL, MySQL and MS SQL Server currently. PostgreSQL returns the OID, 
			which can change on a database reload.	*/
		
		// Execute the statement
		$this->db->Execute($query);
				
		$inserted_id = -1;
		
		try {
			$inserted_id = $this->db->Insert_ID($this->TABLE_pornstars, 'pornstar_id');
		} catch (Exception $ex) {
			// Check if this is a Postgre not using OID columns
			if ($this->isPostgres()) {
				// Yeap, postgres not using OID ..
				$inserted_id = $this->oToID($this->TABLE_pornstars, 'pornstar_id');
			} else {
				throw $ex;
			}
		}
		
		
		
		if (is_numeric($inserted_id) && $inserted_id > 0) {

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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addPornstarToMovie($pornstar_id, $movie_id) { 
		try {
			
		$query = "INSERT INTO $this->TABLE_vcdtopornstars (vcd_id, pornstar_id) VALUES (".$movie_id.", ".$pornstar_id.")";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deletePornstarFromMovie($pornstar_id, $movie_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_vcdtopornstars WHERE vcd_id = ".$movie_id." AND
				  pornstar_id = ".$pornstar_id."";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getStudioByID($studio_id) {
		try {
			
		$query = "SELECT studio_id, studio_name FROM $this->TABLE_studios WHERE studio_id = " . $studio_id;
		$rs = $this->db->Execute($query);
		if ($rs) {
			return new studioObj($rs->FetchRow());
		}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function addMovieToStudio($studio_id, $vcd_id) {
		try {
			
		$query = "INSERT INTO $this->TABLE_vcdtostudios (vcd_id, studio_id) VALUES (".$vcd_id.",".$studio_id.")";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function deleteMovieFromStudio($vcd_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_vcdtostudios WHERE vcd_id = " . $vcd_id;
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deleteMovieFromCategories($vcd_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_vcdtocategory WHERE vcd_id = " . $vcd_id;
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function addCategoryToMovie($vcd_id, $category_id) {
		try {
			
		$query = "INSERT INTO $this->TABLE_vcdtocategory (vcd_id, category_id) VALUES (".$vcd_id.", ".$category_id.")";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
		if ($rs && $rs->RecordCount() > 1) {
			foreach ($rs as $row) {
				array_push($arrAlphabet, $row[0]);
			}
			
			$rs->Close();
			return $arrAlphabet;
		} else {
			
			// Some have reported that the Alphabet query does not return any results
			// although the table contains data ..  There for we try another approach here ..
			$count_query = "SELECT COUNT(*) FROM ".$this->TABLE_pornstars;
			$pornstarcount = $this->db->GetOne($count_query);
			if (is_numeric($pornstarcount) && $pornstarcount > 0) {
				
				if ($active_only) {
					$queryAllNames = "SELECT p.name FROM $this->TABLE_pornstars p, $this->TABLE_vcdtopornstars t 
									  WHERE p.pornstar_id = t.pornstar_id ORDER BY name";	
				} else {
					$queryAllNames = "SELECT name FROM $this->TABLE_pornstars ORDER BY name";	
				}
				
				
				
				$rsAll = $this->db->Execute($queryAllNames);
				if ($rsAll && $rsAll->RecordCount() > 0) {
					foreach ($rsAll as $row) {
						$currName = $row[0];
						$currChar = strtoupper($currName{0});
						if (!in_array($currChar, $arrAlphabet)) {
							array_push($arrAlphabet, $currChar);	
						}
					}	
				}
				
				return $arrAlphabet;
				
			}
		}
		
		return null;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getPornstarsByLetter($letter, $active_only) {
		try {
		
			
		$letter = $letter."%";
			
		if ($active_only) {
			$query = "SELECT p.pornstar_id, p.name, p.homepage, p.image_name,  
					  COUNT(v.pornstar_id) as numfilms 
					  FROM $this->TABLE_pornstars p, $this->TABLE_vcdtopornstars v
					  WHERE p.name LIKE ".$this->db->qstr($letter)." AND
					  p.pornstar_id = v.pornstar_id
					  GROUP BY p.pornstar_id, p.name, p.homepage, p.image_name
					  ORDER BY p.name";
		
		} else {
			
			$query = "SELECT p.pornstar_id, p.name, p.homepage, p.image_name,  
					  COUNT(v.pornstar_id) as numfilms 
					  FROM $this->TABLE_pornstars p
					  LEFT OUTER JOIN $this->TABLE_vcdtopornstars v ON p.pornstar_id = v.pornstar_id
					  WHERE p.name LIKE ".$this->db->qstr($letter)."
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
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addAdultCategory(porncategoryObj $obj) {
		try {
		
			$query = "INSERT INTO $this->TABLE_adultcategory (category_name) VALUES (".$this->db->qstr($obj->getName()).") ";
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function deleteAdultCategory($category_id) {
		try {
		
			$query = "DELETE FROM $this->TABLE_adultcategory WHERE category_id = " . $category_id;
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addStudio(studioObj $obj) {
		try {
		
			$query = "INSERT INTO $this->TABLE_studios (studio_name) VALUES (".$this->db->qstr($obj->getName()).")";
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
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
	
	public function deletePornstar($pornstar_id) {
		try {
			
			$query = "DELETE FROM $this->TABLE_pornstars WHERE pornstar_id =  " . $pornstar_id;
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage());
		}
	}
	
	
	/*
	 * Shorten text, used by Oracle
	 *
	 * @param string $text | The text to shorten
	 * @param string $length | The supposed text length
	 * @return string | The shortened string
	 */
	private function shortenText($text, $length) {
		if (strlen($text) > $length) {
				$text_spl = explode(' ', $text);
				$i = 1;
				$text = $text_spl[0];
				while(strlen($text.$text_spl[$i]) < $length) {
					$text .= " ".$text_spl[$i++];
				}
				$text = $text."...";
			}
		return $text;
	}
	

}


?>