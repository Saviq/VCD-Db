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
 * @subpackage Controller
 * @version $Id: VCDPageUserAddItemListed.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserAddItemListed extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
			try {
				// Only initialize base values if no action is taking place
				if (is_null($this->getParam('action'))) {
					$this->initPage();	
				}		
				
			} catch (Exception $ex) {
				VCDException::display($ex);
			}
	}
	
	/**
	 * Initilize the base stage of adding listed movies
	 *
	 */
	private function initPage() {
		
		$movies = MovieServices::getAllVcdForList(VCDUtils::getUserID());
		$results = array();
		$showAdult = VCDUtils::showAdultContent();
		$adultCatId = SettingsServices::getCategoryIDByName('adult');
		foreach ($movies as $obj) {
			if (!$showAdult && $obj->getCategoryId()==$adultCatId) continue;
			$results[$obj->getId()] = $obj->getTitle();
		}
		$this->assign('movieList', $results);
		
		
	}
	
	/**
	 * Handle _POST requests to the controller
	 *
	 */
	public function handleRequest() {
	
		$action = $this->getParam('action');
		switch ($action) {
			case 'select':
				$list = explode('#',$this->getParam('id_list',true));
				if (is_array($list) && sizeof($list)>0) {
					$this->doSelectedMovies($list);					
				} else {
					redirect('?page=add_listed');
				}
				break;
		
			case 'confirm':
				$list = explode('#',$this->getParam('id_list',true));
				if (is_array($list) && sizeof($list)>0) {
					$this->doAddSelectedMovies($list);					
				} else {
					redirect('?page=add_listed');
				}
				break;
				
			default:
				redirect('?page=add_listed');
				break;
		}
	}

	/**
	 * Add the selectedmovies to the database.
	 *
	 * @param array $idList | The array of ID's to get movies from
	 */
	private function doAddSelectedMovies($idList)	{
		try {

			$movieCount = sizeof($idList);
			$results = array();
			
			for($i=0;$i<$movieCount;$i++) {
				$mediaType = $this->getParam('mediatype_'.$i,true);
				$cdCount = $this->getParam('cds_'.$i,true);
				
				if (is_null($mediaType)) {
					throw new VCDInvalidInputException('No Media type selected for item: ' . MovieServices::getVcdByID($idList[$i])->getTitle());
				}
				
				if (is_null($cdCount)) {
					throw new VCDInvalidInputException('CD count not selected for item: ' . MovieServices::getVcdByID($idList[$i])->getTitle());
				}
				
				$results[] = array('id' => $idList[$i], 'media' => $mediaType, 'cds' => $cdCount);
			}
			
			
			// Now that the data has been validated we add the movies ..
			foreach ($results as $item) {
				MovieServices::addVcdToUser(VCDUtils::getUserID(), $item['id'], $item['media'],$item['cds']);
			}
			
			// Success .. just redirect back to the frontpage ..
			redirect();
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	/**
	 * Populate the media type selection of the selected movies to add
	 *
	 * @param array $idList | The array of ID's to get movies from
	 */
	private function doSelectedMovies($idList) {
		try {
			
			$movies = MovieServices::getVcdForListByIds($idList);
			$results = array();
			foreach ($movies as $obj) {
				$results[$obj->getId()] = $obj->getTitle();
			}
			$this->assign('movieList', $results);
			
			
			// Populate the dropdown boxes
			
			// Set the mediaType list
			$results = array();
			$results[null] = VCDLanguage::translate('misc.select');
			
			foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
				$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
				if ($mediaTypeObj->getChildrenCount() > 0) {
					foreach ($mediaTypeObj->getChildren() as $childObj) { 
						$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
					}
				}
			}
			
			$this->assign('mediatypeList', $results);
			
			
			
			// Set the number of cd's list
			$results = array();
			$results[null] = VCDLanguage::translate('misc.select');
			for($i=1;$i<11;$i++) {
				$results[$i] = $i;
			}
			$this->assign('cdList',$results);
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	
}

?>