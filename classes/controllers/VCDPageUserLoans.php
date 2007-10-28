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
 * @version $Id: VCDPageUserLoans.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

/**
 * This controller handles users loans and loan history.
 *
 */
class VCDPageUserLoans extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
				
		$this->initPage();
	
		
	}
	
	/**
	 * Handle all $_POST actions the the page
	 *
	 */
	public function handleRequest() {
		
		$action = $this->getParam('action');
		if (strcmp($action,'addloan')==0) {
			$this->addLoan();
		}
		
		
	}
	
	/**
	 * Mark selected movies loaned to selected borrower.
	 *
	 */
	private function addLoan() {
		try {
			
			$ids = $this->getParam('id_list',true);
			$borrower_id = $this->getParam('borrowers',true);
			
			if (is_null($ids)) {
				throw new VCDInvalidInputException('No movies selected.');
			}
			if (is_null($borrower_id)) {
				throw new VCDInvalidInputException('No borrower selected.');
			}
			
			SettingsServices::loanCDs($borrower_id, split('#',$ids));
			
			redirect('?page='.$this->config->getAction());			
			exit();
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);	
		}
	}
	
	private function initPage() {
		
		// Populate the myMovies list
		$loans = SettingsServices::getLoans(VCDUtils::getUserID(), false);
		$movieList = $this->doGetMyMovieList($loans);
		
		$results = array();
		foreach ($movieList as $obj) {
			$results[$obj->getId()] = $obj->getTitle();
		}
		$this->assign('myMovieList',$results);
		
		
		// Populate the borrowers list
		$borrowers = SettingsServices::getBorrowersByUserID(VCDUtils::getUserID());
		if (is_array($borrowers) && sizeof($borrowers)>0) {
			$results = array();
			foreach ($borrowers as $obj) {
				$results[$obj->getId()] = $obj->getName();
			}
			$this->assign('borrowersList', $results);
		}
		
		// Populate the movies in loan list
		$this->doLoanList($loans);
		
	}
	
	
	private function doLoanList($loans) {
				
		if (is_array($loans) && sizeof($loans)>0) {
			
			$results = array();
			$lastBorrower = null;
			
			foreach ($loans as $loanObj) {
		
				$items = array();
				$borrower = $loanObj->getBorrower();
				if ($lastBorrower == $borrower) {
					
					$results[$borrower->getID()]['items'][$loanObj->getLoanID()] = array(
									'id' => $loanObj->getCDID(), 'title' => $loanObj->getCDTitle(),
									'in' => $loanObj->getDateIn(), 'out' => $loanObj->getDateOut()
					);
					
				} else {
	
					$results[$borrower->getID()] = array(
						'name' => $borrower->getName(), 'email' => $borrower->getEmail(), 
							'items' => array());
							
					$results[$borrower->getID()]['items'][$loanObj->getLoanID()] = array(
						'id' => $loanObj->getCDID(), 'title' => $loanObj->getCDTitle(),
						'in' => $loanObj->getDateIn(), 'out' => $loanObj->getDateOut()
					);
				}
				 
				$lastBorrower = $borrower;
			}

			unset($lastBorrower);
			$this->assign('loanList',$results);
		}
	}
	
	/**
	 * Get the movie list to display as available movies to loan
	 *
	 * @param array $loans | Array of loan objects
	 * @return array | Array of vcdObj
	 */
	private function doGetMyMovieList($loans) {
		$movies = MovieServices::getAllVcdByUserId(VCDUtils::getUserID());
		
	
		if (is_array($loans) && sizeof($loans) > 0 && sizeof($movies) > 0) {
			return $this->doFilterLoanList($movies, $loans);
		}
		
		return $movies;
	}
	
	
	/**
	 * Remove movies from the list that are already loaned
	 *
	 * @param array $arrMovies | Array of movies
	 * @param array $arrLoans | Array of loan objects
	 * @return array | Array of movie objects
	 */
	private function doFilterLoanList($arrMovies, $arrLoans) {

		// create array with movie id's that are in loan ..
		$loanIds = array();
		foreach ($arrLoans as $loanObj) {
			array_push($loanIds, $loanObj->getCDID());
		}
	
		$arrAvailable = array();
		foreach ($arrMovies as $vcdObj) {
			if (!in_array($vcdObj->getId(), $loanIds)) {
				array_push($arrAvailable, $vcdObj);
			}
		}
	
		unset($loanIds);
		return $arrAvailable;
	}
	
	
	
}



?>