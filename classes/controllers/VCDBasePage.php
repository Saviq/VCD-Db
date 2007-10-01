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
 * @version $Id: VCDBasePage.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDBasePage extends VCDPage {
	
	/**
	 * The _VCDPageNode object that holds the Controllers configuration.
	 *
	 * @var _VCDPageNode
	 */
	protected $config = null;
	
	private $templateTop = 'page.basepage.top.tpl';
	private $templateBottom = 'page.basepage.bottom.tpl';
	
		
	public function __construct(_VCDPageNode $node) {
		
		$this->config = $node;
		
		// If the request contains _POST data .. force the Controller to handle it.
		if (sizeof($_POST) > 0) {
			$this->handleRequest();
		} 
		
		parent::__construct($this->config->getTemplate());
		
		
	}
	
	/**
	 * All Controllers must know how to handle request to their page.
	 *
	 */
	public function handleRequest() {
		
	}
	
	
	public function render($template=null) {
		
		if ($this->config->isStandalone()) {
			
			parent::render();
			
		} else {			
					
			$this->initPage();
			$this->renderPageTop();
			parent::render();
			$this->renderPageBottom();
			
		}
	}
	
	
	private function renderPageTop() {
		
		//$this->display($this->templateTop);
		parent::render($this->templateTop);
	}
	
	private function renderPageBottom() {
		//$this->display($this->templateBottom);
		parent::render($this->templateBottom);
	}
	
	
	private function initPage() {
		
		$this->assign('pageCharset', VCDUtils::getCharSet());
		$this->assign('pageStyle', VCDUtils::getStyle());
		
		if (VCDUtils::isLoggedIn()) {
			$this->assign('isAuthenticated', true);
			if (VCDUtils::getCurrentUser()->isAdmin()) {
				$this->assign('isAdmin', true);
			}
		}
		
		$this->doSearchModule();
		$this->doHeaderModule();
		$this->doCategorylistModule();
		$this->doTopUserModule();
		$this->doPornstarModule();
		
		$this->assign('pageloadTime', sprintf(VCDLanguage::translate('misc.footer'), VCDUtils::getPageLoadTime(), VCDConnection::getQueryCount()));
		
	}
	
	private function doCategorylistModule() {
				
		$categories = SettingsServices::getMovieCategoriesInUse();
		$adult_id = SettingsServices::getCategoryIDByName('adult');
		
		$curr_catid = $this->getParam('category_id',false,-1);
	
		$arrSorted = array();
		if (sizeof($categories) > 0) {
		
			foreach ($categories as $category) {
				$cssclass = "nav";
				if ($category->getID() == $curr_catid) { $cssclass = "navon"; }
				if ($category->getID() == $adult_id) {
					
					if (!VCDUtils::showAdultContent()) {
						continue;
					}
				
				} 
				array_push($arrSorted, array('name' => $category->getName(true), 'id' => $category->getID(), 'css' => $cssclass));
			}
			
			asort($arrSorted);
			
		}
		
		$this->assign('categoryList',$arrSorted);
	}
	
	private function doSearchModule() {
		
		$searchTypes = array('title' => VCDLanguage::translate('search.title'),
			'actor' => VCDLanguage::translate('search.actor'),
			'director' => VCDLanguage::translate('search.director'));

		$this->assign('searchOptions', $searchTypes);
			
		if (isset($_SESSION['searchkey']) && key_exists($_SESSION['searchkey'], $searchTypes)) {
			$this->assign('lastSearchMethod', $_SESSION['searchkey']);
		} else {
			$this->assign('lastSearchMethod', 'title');
		}
	}
	
	
	private function doHeaderModule() {
		
		$languageObj = VCDClassFactory::loadClass('VCDLanguage');
		$list = $languageObj->getAllLanguages();
		$results = array();
		foreach ($list as $obj) {
			$results[$obj->getId()] = $obj->getName();
		}
		
		$this->assign('languageList', $results);
		$this->assign('selectedLanguage', $languageObj->getPrimaryLanguageID());
		
		if (LDAP_AUTH == 0) {
			$this->assign('canRegister',true);
		}
	}
	
	
	private function doTopUserModule() {
		$list = UserServices::getUserTopList();
		$results = array();
		$maxentries = 6;
		for ($i=0;($i<sizeof($list) && ($i<=$maxentries) );$i++) {
			$results[] = array('name' => $list[$i]['user_name'], 'count' => $list[$i]['count']);
		}
	
		$this->assign('topuserList',$results);
	}
	
	private function doPornstarModule() {
		if (VCDUtils::showAdultContent()) {
			$this->assign('showAdult',true);	
		}
		
	}
	
		
}



?>