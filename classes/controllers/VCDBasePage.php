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
	
	
	private $scripts = array();
	private $scriptblocks = array();
	
	/**
	 * Available java script libs for inclusion.
	 */
	protected static $JS_MAIN = 'main.js';
	protected static $JS_LANG = '?page=strings.js';
	protected static $JS_JSON = 'json.js';
	protected static $JS_AJAX = 'ajax.js';
	protected static $JS_LYTE = 'lytebox.js';
	protected static $JS_TABS = 'js_tabs.js';
			
	/**
	 * Class contructor, loads the confing and forces the pages/views to handle $_POST requests.
	 *
	 * @param _VCDPageNode $node
	 */
	public function __construct(_VCDPageNode $node) {
		
		$this->config = $node;
		
		// Set the date and time format to global config
		$config = array('date' => VCDConfig::getDateFormat(), 'time' => VCDConfig::getTimestampFormat());
		$this->assign('config',$config);
		
		// If the request contains _POST data .. force the Controller to handle it.
		if (sizeof($_POST) > 0) {
			// Check for site language change.
			if (!is_null($this->getParam('lang',true))) {
				$this->doLanguageChange();
			} else {
				$this->handleRequest();	
			}
		} 
		
		parent::__construct($this->config->getTemplate());
		
		
	}
	
	/**
	 * All Controllers must know how to handle request to their page.
	 *
	 */
	public function handleRequest() {
		
	}
	
	
	/**
	 * Display contents from the Smarty templates to the browser
	 *
	 * @param string $template
	 */
	public function render($template=null) {
				
		if ($this->config->isStandalone()) {
			
			$this->assign('pageCharset', VCDUtils::getCharSet());
			$this->assign('pageStyle', VCDUtils::getStyle());
			
			// If a param "close=true" is detected, scriptblock to close window.self 
			// and reload parent will be triggered.
			if ($this->getParam('close') == 'true') {
				$this->registerScriptBlock('try{window.opener.location.reload();self.close();}catch(ex){try{self.close();}catch (ex){}}');
			}
			
			$this->initJavascripts();
			parent::render();
			
		} else {			
						
			$this->initPage();
			$this->initJavascripts();
			
			$this->renderPageTop();
			parent::render();
			$this->renderPageBottom();
			
		}
	}
	
	
	/**
	 * Register a javascript file to be included in the header
	 *
	 * @param string $scriptname | The script filename
	 */
	protected function registerScript($scriptname) {
		$this->scripts[] = $scriptname;
	}
	
	/**
	 * Register javascript block to be included in the header
	 *
	 * @param string $scriptblock | The javascriptblock to add
	 */
	protected function registerScriptBlock($scriptblock) {
		$this->scriptblocks[] = $scriptblock;
	}
	
	
	
	/**
	 * Render the layout contents above the main content
	 *
	 */
	private function renderPageTop() {
		parent::render($this->templateTop);
	}
	
	
	/**
	 * * Render the layout contents below the main content
	 *
	 */
	private function renderPageBottom() {
		parent::render($this->templateBottom);
	}
	
	
	/**
	 * Include javascript and javascriptblocks that have been added to the template
	 *
	 */
	private function initJavascripts() {
		$scriptData = '';
		$scriptBase = '<script type="text/javascript" src="includes/js/%s"></script>%s%s';
		$scriptBaseStrings = '<script type="text/javascript" src="%s" charset="utf-8"></script>%s%s';
		// Display the script array in reverse order, so main.js in always on top
		for ($i=(sizeof($this->scripts)-1);$i>=0;$i--) {
			if ($this->scripts[$i] === self::$JS_LANG) {
				$scriptData .= sprintf($scriptBaseStrings, $this->scripts[$i], chr(13),chr(9));
			} else {
				$scriptData .= sprintf($scriptBase, $this->scripts[$i], chr(13),chr(9));
			}
			
		}
		
		// Next handle scriptblocks
		if (sizeof($this->scriptblocks) > 0) {
			$scriptData .= '<script type="text/javascript">';
			for ($i=0;$i<sizeof($this->scriptblocks);$i++) {
				if ($i != (sizeof($this->scriptblocks)-1)) {
					$scriptData .= $this->scriptblocks[$i].chr(13);
				} else {
					$scriptData .= $this->scriptblocks[$i];
				}
			}
			$scriptData .= '</script>';
		}
				
		$this->assign('pageScripts',$scriptData);
		
		
	}
	
	
	/**
	 * Initialize and assign base variables needed by all pages/views that are NOT standalone.
	 *
	 */
	private function initPage() {
		
		$this->assign('pageCharset', VCDUtils::getCharSet());
		$this->assign('pageStyle', VCDUtils::getStyle());
						
		// Check if RSS link should be displayed
		if ((bool)SettingsServices::getSettingsByKey('RSS_SITE')) {
			$this->assign('pageRsslink',true);
		}
		
		// Standalone pages that need main.js and/or the language support must include it manually		
		$this->registerScript(self::$JS_MAIN);
		$this->registerScript(self::$JS_LANG);
		
		if (VCDUtils::isLoggedIn()) {
			$this->assign('isAuthenticated', true);
			$this->assign('pageUsername', VCDUtils::getCurrentUser()->getFullname());
			
			// Check weither to display Rss menuitem
			if (sizeof(SettingsServices::getRssFeedsByUserId(VCDUtils::getUserID()))>0) {
				$this->assign('showRssFeeds',true);
			}
			
			// Check if weither to display the public wishlist
			if (SettingsServices::isPublicWishLists(VCDUtils::getUserID())) {
				$this->assign('showWishlists',true);
			}
			
			
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
	
	/**
	 * Display the categoryList module
	 *
	 */
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
	
	/**
	 * Display the Search module
	 *
	 */
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
	
	
	/**
	 * Display the header module
	 *
	 */
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
	
	
	/**
	 * Display the TopUsersList module
	 *
	 */
	private function doTopUserModule() {
		$list = UserServices::getUserTopList();
		
		$results = array();
		$maxentries = 6;
		if (is_array($list)) {
			for ($i=0;($i<sizeof($list) && ($i<=$maxentries) );$i++) {
				$results[] = array('name' => $list[$i]['username'], 'count' => $list[$i]['count']);
			}
		}
		
	
		$this->assign('topuserList',$results);
	}
	
	/**
	 * Display the pornstar pages module
	 *
	 */
	private function doPornstarModule() {
		if (VCDUtils::showAdultContent()) {
			$this->assign('showAdult',true);	
		}
	}
	
	/**
	 * Update the users selected language for the site.  
	 * Upon change, all the cache templates must be deleted so the
	 * new translation will take effect.
	 *
	 */
	private function doLanguageChange() {
						
		$lang_tag = $this->getParam('lang',true);
		$language = VCDClassFactory::getInstance('VCDLanguage');
		$language->load($lang_tag);

		// Check for existing cookie
		SiteCookie::extract('vcd_cookie');
		if (isset($_COOKIE['session_id']) && isset($_COOKIE['session_uid'])) {
			$session_id    = $_COOKIE['session_id'];
			$user_id 	   = $_COOKIE['session_uid'];
			$session_time  = $_COOKIE['session_time'];

			// Has the user set a preferred template ?
			$template = "";
			if (isset($_COOKIE['template'])) {
				$template = $_COOKIE['template'];
			}


			$Cookie = new SiteCookie("vcd_cookie");
			$Cookie->clear();
			$Cookie->put("session_id", $session_id);
			$Cookie->put("session_time", $session_time);
			$Cookie->put("session_uid", $user_id);
			$Cookie->put("language",$_POST['lang']);
			if (strcmp($template, "") != 0) {
				$Cookie->put("template", $template);
			}
			$Cookie->set();

		} else {

			/* Add selected value in cookie for future visits*/
	   		$Cookie = new SiteCookie("vcd_cookie");
			$Cookie->put("language",$lang_tag);

			// Has the user set a preferred template ?
			$template = "";
			if (isset($_COOKIE['template'])) {
				$template = $_COOKIE['template'];
			}
			if (strcmp($template, "") != 0) {
				$Cookie->put("template", $template);
			}

			$Cookie->set();
		}


		$ref = $_SERVER['HTTP_REFERER'];
		/* Redirect to avoid expired page*/
		if (strlen($ref) > 0) {
			header("Location: $ref"); /* Redirect browser */
			exit();
		} else {
			redirect(); /* Redirect browser */
		}
	}
	
		
}



?>