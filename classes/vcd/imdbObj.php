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
 * @author  H�kon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage Vcd
 * @version $Id$
 */

?>
<?
/**
	Class imdbObj
	Container for the IMDB data on the current movie.
*/

	class imdbObj extends fetchedObj implements XMLable {

	private $imdb;
	private $alt_title1;
	private $alt_title2;
	private $plot;
	private $director;
	private $cast;
	private $rating;
	private $country;
	private $genre;


	/**
	 * Function contsructor
	 *
	 * @param array $dataArr
	 * @return imdbObj
	 */
	public function imdbObj($dataArr = null) {
		if (is_array($dataArr)) {
			$this->objectID		= $dataArr[0];
			$this->title		= $dataArr[1];
			$this->alt_title1	= $dataArr[2];
			$this->alt_title2	= $dataArr[3];
			$this->image		= $dataArr[4];
			$this->year			= $dataArr[5];
			$this->plot			= $dataArr[6];
			$this->director		= $dataArr[7];
			$this->cast			= $dataArr[8];
			$this->rating		= $dataArr[9];
			$this->runtime		= $dataArr[10];
			$this->country		= $dataArr[11];
			$this->genre		= $dataArr[12];
			}
		}


	/**
	 * Get the IMDB plot
	 *
	 * @return string
	 */
	public function getPlot() {
		return $this->plot;
	}


	/**
	 * Set the IMDB plot
	 *
	 * @param string $strPlot
	 */
	public function setPlot($strPlot) {
		$this->plot = stripslashes($strPlot);
	}

	/**
	 * Get IMDB cast
	 *
	 * @param bool $format
	 * @return string
	 */
	public function getCast($format = true) {
		if ($format) {
			return $this->formatCast($this->cast);
		} else {
			return $this->cast;
		}

	}

	/**
	 * Set the IMDB cast
	 *
	 * @param string $strCast
	 */
	public function setCast($strCast) {
		$this->cast = $strCast;
	}


	/**
	 * Get the IMDB alternative title
	 *
	 * @return string
	 */
	public function getAltTitle() {
		return $this->alt_title1;
	}

	/**
	 * Set the IMDB alternative title
	 *
	 * @param string $strAltTitle
	 */
	public function setAltTitle($strAltTitle) {
		$this->alt_title1 = $strAltTitle;
	}


	/**
	 * Get the Director
	 *
	 * @return string
	 */
	public function getDirector() {
		return $this->director;
	}

	/**
	 * Set the director
	 *
	 * @param string $strDirector
	 */
	public function setDirector($strDirector)  {
		$this->director = stripslashes($strDirector);
	}

	/**
	 * Get the Director link for searching
	 *
	 * @return string
	 */
	public function getDirectorLink() {
		$directorLink =  "<a href=\"search.php?searchstring=".$this->director."&amp;by=director\">".$this->director."</a>";
		$imdb = explode(" ", $this->director);
		// Create imdb url for director
		if (isset($imdb[2])) {
			$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[2],+$imdb[0]+$imdb[1]\" target=\"_new\">[imdb]</a>";
		} elseif(isset($imdb[1])) {
			$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[1],+$imdb[0]\" target=\"_new\">[imdb]</a>";	} else {
			$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[0]\" target=\"_new\">[imdb]</a>";
		}
		return "<strong>".$directorLink."</strong> &nbsp;".$urlid;
	}

	/**
	 * Get the IMDB rating
	 *
	 * @return double
	 */
	public function getRating() {
		return $this->rating;
	}

	/**
	 * Set the IMDB ratings
	 *
	 * @param double $strRating
	 */
	public function setRating($strRating) {
		$this->rating = $strRating;
	}



	/**
	 * Get the country list origin of the movie
	 *
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Set the procuction countries of this movie.
	 *
	 * @param string $strCountry
	 */
	public function setCountry($strCountry) {
		$this->country = $strCountry;
	}

	/**
	 * Get the IMDB genres
	 *
	 * @return string
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * Set the IMDB genres
	 *
	 * @param string $strGenre
	 */
	public function setGenre($strGenre) {
		if (is_array($strGenre)) {
			$this->genre = implode(", ",$strGenre);
		} else {
			$this->genre = $strGenre;	
		}
		
	}

	/**
	 * Get the IMDB id
	 *
	 * @return string
	 */
	public function getIMDB() {
		return $this->objectID;
	}

	/**
	 * Set the IMDB id
	 *
	 * @param string $strIMDB
	 */
	public function setIMDB($strIMDB) {
		$this->objectID = $strIMDB;
	}




	/**
	 * Draw the rating of the IMDB object, writes html image star icons
	 *
	 */
	public function drawRating() {
		$max = 10;
		$stjornur = round($this->rating);
		$tomar = $max - $stjornur;
		$counter = 0;
		for (;$counter < $stjornur; $counter++) {
			echo("<img src=\"images/goldstar.gif\" border=\"0\" alt=\"$stjornur stars\"/>");
		}

		$counter = 0;
		for (;$counter < $tomar; $counter++) {
			echo("<img src=\"images/greystar.gif\" border=\"0\" alt=\"$stjornur stars\"/>");
		}


	}


	/**
	 * Format the cast list and prints the cast list.
	 *
	 * @param string $cast
	 */
	public function formatCast($cast)	{
		$cast = ereg_replace(13,"<br>",$cast);

		$pieces = explode("<br>", $cast);
		$st		= count($pieces);
		$counter = 0;
			for ($n=0; $n < $st-1; $n++ ) {
				$tmp = explode("...",$pieces[$n]);
				$role = strstr($pieces[$n],'....');
				$role = str_replace('....','',$role);

				$imdb = explode(" ",$tmp[0]); // the IMDB url
				$tmp[0] = "<a href=\"search.php?searchstring=".trim($tmp[0])."&amp;by=actor\">".trim($tmp[0])."</a>";
				$actor = trim($tmp[0]);

				// Create imdb url for actor
				if (isset($imdb[2])) {
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[2],+$imdb[0]+$imdb[1]\" target=\"_new\">[imdb]</a>";
				} elseif(isset($imdb[1])) {
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[1],+$imdb[0]\" target=\"_new\">[imdb]</a>";
				} else {
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[0]\" target=\"_new\">[imdb]</a>";
				}
				print "<span class=\"item\"><strong>$actor</strong>&nbsp;&nbsp;$urlid<br/>$role</span>";
			}
	}


	/**
	 * Print html link to the IMDB page of the movie.
	 *
	 * @param string $align
	 */
	public function printImageLink($align = "") {
		if (!empty($align)) {
			$align = "align=\"$align\"";
		}

		print "<a href=\"http://www.imdb.com/title/tt".$this->imdb."\" target=\"_new\"><img src=\"images/imdb-logo.gif\" style=\"padding-right:15px;\" alt=\"\" title=\"Detailed info\" border=\"0\" ".$align."/></a>";
	}




	/**
	 * Return the XML representation of the IMDB object.
	 *
	 * @return string
	 */
	public function toXML() {

			$xmlstr  = "<imdb>\n";
			$xmlstr .= "<imdb_id>".$this->getIMDB()."</imdb_id>\n";
			$xmlstr .= "<title><![CDATA[".utf8_encode($this->title)."]]></title>\n";
			$xmlstr .= "<alt_title><![CDATA[".utf8_encode($this->alt_title1)."]]></alt_title>\n";
			$xmlstr .= "<image>".$this->image."</image>\n";
			$xmlstr .= "<year>".$this->year."</year>\n";
			$xmlstr .= "<plot><![CDATA[".utf8_encode($this->plot)."]]></plot>\n";
			$xmlstr .= "<director><![CDATA[".utf8_encode($this->director)."]]></director>\n";
			$xmlstr .= "<cast><![CDATA[".utf8_encode( $this->formatCastForXmlExport() )."]]></cast>\n";
			$xmlstr .= "<rating>".$this->rating."</rating>\n";
			$xmlstr .= "<runtime>".$this->runtime."</runtime>\n";
			$xmlstr .= "<country>".$this->country."</country>\n";
			$xmlstr .= "<genre>".$this->genre."</genre>\n";
			$xmlstr .= "</imdb>\n";

			return $xmlstr;
	}


	/**
	 * Format the cast in XML compatible manner.
	 *
	 * @return string
	 */
	private function formatCastForXmlExport() {
		$export_cast = ereg_replace(13,"|",$this->cast);
		return $export_cast;
	}



}

?>