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
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<?php

class statisticsObj {
	
	private $total_movies;
	private $movies_addedtoday;
	private $movies_addedweek;
	private $movies_addedmonth;
	
	private $ArrMonthlyCats = array();
	private $ArrAllCats = array();
			
	private $mostactive_users;
	
	private $total_covers;
	private $total_coversthisweek;
	private $total_coversthismonth;
	
	
	
	/**
	 * Function constructor
	 *
	 * @return statisticsObj
	 */
	public function __construct() {}
	
	/**
	 * Set the total number of movies in database
	 *
	 * @param int $num
	 */
	public function setMovieCount($num) {
		$this->total_movies = $num;
	}
	
	/**
	 * Get the total number of movies in database
	 *
	 * @return int
	 */
	public function getMovieCount() {
		return $this->total_movies;
	}
	
	/**
	 * Set the number of movies added today
	 *
	 * @param int $num
	 */
	public function setMovieTodayCount($num) {
		$this->movies_addedtoday = $num;
	}
	
	/**
	 * Get the number of movies added today
	 *
	 * @return int
	 */
	public function getMovieTodayCount() {
		return $this->movies_addedtoday;
	}
	
	/**
	 * Set the number of movies added this week
	 *
	 * @param int $num
	 */
	public function setMovieWeeklyCount($num) {
		$this->movies_addedweek = $num;
	}
	
	/**
	 * Get the number of movies added this week
	 *
	 * @return int
	 */
	public function getMovieWeeklyCount() {
		return $this->movies_addedweek;
	}
	
		
	/**
	 * Set the number of movies added this month
	 *
	 * @param int $num
	 */
	public function setMovieMonthlyCount($num) {
		$this->movies_addedmonth = $num;
	}
	
	/**
	 * Get the number of movies added this month
	 *
	 * @return int
	 */
	public function getMovieMonthlyCount() {
		return $this->movies_addedmonth;
	}
	
	/**
	 * Set the most popular categories
	 *
	 * @param array $arrcats
	 */
	public function setBiggestCats($arrcats) {
		$this->ArrAllCats = $arrcats;
	}
		
	/**
	 * Set the montly most popular categories
	 *
	 * @param array $arrcats
	 */
	public function setBiggestMonhtlyCats($arrcats) {
		$this->ArrMonthlyCats = $arrcats;
	}
	
	/**
	 * Get the overall most popular categories
	 *
	 * @return array
	 */
	public function getBiggestCats() {
		return $this->ArrAllCats;
	}
		
	/**
	 * Get the monthly most popular categories
	 *
	 * @return array
	 */
	public function getBiggestMonhtlyCats() {
		return $this->ArrMonthlyCats;
	}
	
	/**
	 * Reset all internal category arrays
	 *
	 */
	public function resetCategories() {
		$this->ArrAllCats = null;
		$this->ArrAllCats = array();
		$this->ArrMontlyCats = null;
		$this->ArrMontlyCats = array();
	}
	
	/**
	 * Set the cover count in VCD-db
	 *
	 * @param int $all
	 * @param int $weekly
	 * @param int $monthly
	 */
	public function setCoverCount($all, $weekly, $monthly) {
		$this->total_covers = $all;
		$this->total_coversthisweek = $weekly;
		$this->total_coversthismonth = $monthly;
	}
	
	/**
	 * Set the number of total CD covers in the database
	 *
	 * @return int
	 */
	public function getTotalCoverCount() {
		return $this->total_covers;
	}
	
	
	/**
	 * Get the number of new covers this week
	 *
	 * @return int
	 */
	public function getWeeklyCoverCount() {
		return $this->total_coversthisweek;
	}
	
	/**
	 * Get the number of new covers this month
	 *
	 * @return int
	 */
	public function getMonthlyCoverCount() {
		return $this->total_coversthismonth;
	}
	
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		
		$arr1 = array();
		$arr2 = array();
		foreach ($this->ArrAllCats as $obj) {
			array_push($arr1, $obj->toSoapEncoding());
		}
		foreach ($this->ArrMonthlyCats as $obj2) {
			array_push($arr2, $obj2->toSoapEncoding());
		}
		
		
		
		return array(
			'ArrAllCats' =>  $arr1,
			'ArrMonthlyCats' => $arr2,
			'mostactive_users' => $this->mostactive_users,
			'movies_addedmonth' => $this->movies_addedmonth,
			'movies_addedtoday' => $this->movies_addedtoday,
			'movies_addedweek' => $this->movies_addedweek,
			'total_covers' => $this->total_covers,
			'total_coversthismonth' => $this->total_coversthismonth,
			'total_coversthisweek' => $this->total_coversthisweek,
			'total_movies' => $this->total_movies);
	}
	
}



?>