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
 * @version $Id: VCDPageController.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
/**
 * The Controller for the UI layer.  All requests and posts pass through here.
 * This is the only entrance to the application by the UI.
 *
 */
class VCDPageController {
	
	/**
	 * Array of all knowm pagenodes in the system.
	 *
	 * @var array
	 */
	private static $pageNodes = null;
	
	/**
	 * The Controller instance to handle the page
	 *
	 * @var VCDPage
	 */
	private static $controllerInstance = null;

	private static $fileroot;
	
	private static $loadedModules = null;
	
	/**
	 * Class constructor
	 *
	 * @param string $pagename | The pagename/action to act to
	 */
	public function __construct($pagename) {

		self::$fileroot = dirname(__FILE__).'/controllers/';
		
		try {
			
			$this->loadPageNodes();
			$this->loadController($pagename);
			
		} catch (VCDSecurityException $sex) {
			redirect();			
		} catch (VCDProgramException $ex) {
			print $ex->getMessage();
		}
				
		
		if (self::$controllerInstance instanceof VCDPage ) {
			self::$controllerInstance->render();
			self::$controllerInstance->renderPage();
		}
	}
	
	
	/**
	 * Try to load the corrisponding controller for the requested action/page
	 *
	 * @param string $pagename | The pagename/action to react upon
	 */
	private function loadController($pagename) {
		
		// Get the correct Controller for the request
		$pageNode = $this->getPageNode($pagename);
		if (is_null($pageNode)) {
			redirect();
		}
		
		// Check permissions
		if ($pageNode->isProtected() && !VCDUtils::isLoggedIn()) {
			throw new VCDSecurityException('No permission to view Page:'.$pagename);
		}
		

		// Everything seems ok .. load the class
		try {
			
	    	$controller = $this->getController($pageNode);
	    	if ($controller instanceof VCDPage) {
	    		self::$controllerInstance = $controller;
	    	} else {
	    		throw new VCDSecurityException('Class ' . $pagename . ' is invalid is this context.');
	    	}
		} catch (VCDProgramException $ex) {
			throw $ex;
		}
	} 
	
	
	
	/**
	 * Get an instance of the correct PageController based on the _VCDPageNode item.
	 *
	 * @param _VCDPageNode $node
	 * @return VCDBasePage | The Selected Controller instance
	 */
	private function getController(_VCDPageNode &$node) {
	
		$className = $node->getHandler();
		$fileName = $node->getHandleFilename();
		$controllersPath = self::$fileroot.$fileName;
		if (file_exists($controllersPath)) {
			require_once($controllersPath);
			if (class_exists($className)) {
				return new $className(&$node);
			} else {
				throw new VCDProgramException('Could not load class ' . $className);
			}
		} else {
			throw new VCDProgramException('Class ' . $className . " not found.");
		}
	}

	
	/**
	 * Load all the registered pages that are found in the XML control file.
	 *
	 */
	private function loadPageNodes() {
		self::$pageNodes = array();
		$nodeFile = self::$fileroot._VCDPageNode::PAGESFILE;
		if (!file_exists($nodeFile)) {
			throw new VCDProgramException('Page definitions are missing.');
		}
		$xmlStream = simplexml_load_file($nodeFile);
		foreach ($xmlStream->pages->page as $node) {
			array_push(self::$pageNodes, new _VCDPageNode($node));
		}
	}
	
	/**
	 * Get the pagenode that contains information about the Controller to load.
	 * Returns null if no match is found.
	 *
	 * @param string $action | The requested action in the web-application
	 * @return _VCDPageNode
	 */
	private function getPageNode($action) {
		if (is_array(self::$pageNodes)) {
			$hits = array();
			foreach (self::$pageNodes as $pageNode) {
				if (strcmp($pageNode->getAction(),$action)==0) {
					array_push($hits, $pageNode);
				}
			}
			if (sizeof($hits)==1) {
				return array_pop($hits);
			} elseif (sizeof($hits)>1) {
				return $this->getCorrectNode($action, &$hits);
			}
		} 
		return null;
	}
	
	
	
	/**
	 * When multiple nodes are found for the same action, the correct one has to be
	 * selected on the condition they where given in the PAGES XML file.
	 *
	 * @param string $action | The requested action in the web-application
	 * @param array $pageNodes | Array of pagenodes that match the requested action.
	 * @return _VCDPageNode | The correct node
	 */
	private function getCorrectNode($action, &$pageNodes) {
		
		/*
		 This time the controller cannot play dum anymore ..
		 We need to load the cdObj to find out which Controller to load
		 in the case of page=cd
		 */
		if (strcmp($action,'cd')==0) {

			if (!isset($_GET['vcd_id'])) {
				redirect();
				exit();
			}
			
			$itemId = $_GET['vcd_id'];
			$category_id = SettingsServices::getCategoryIDByItemId($itemId);
			
			// One of the items in the array has no conditions .. that one is used
			// if the categoryID condition is not fulfilled.
			
			$defaultNode = null;
			foreach ($pageNodes as $pageNode) {
				if (is_null($pageNode->getCondition())) {
					$defaultNode = $pageNode;
				} 
				if ($pageNode->getCondition() == $category_id) {
					return $pageNode;
				}
			}
					
			if (is_null($defaultNode)) {
				throw new VCDProgramException('No default handler defined for action "' . $action . '"');
			}
			
			return $defaultNode;
			
		} else {
			throw new VCDProgramException('Multiple definitions for action "' . $action . '" is invalid.');
		}
		
	}
 	
	
	
}


/**
 * Data wrapper around the XML page definitions.
 *
 */
class _VCDPageNode {
	
	CONST PAGESFILE = 'pages.xml';

	private $standalone = false;
	private $requiresAuth = false;
	private $name;
	private $handler;
	private $template = null;
	private $action;
	private $condition;
	
	/**
	 * Class constructor
	 *
	 * @param SimpleXMLElement $element
	 */
	public function __construct(SimpleXMLElement $element) {
		
		$this->name = (string)$element->name;
		$this->handler = (string)$element->handler;
		if (isset($element->template)) {
			$this->template = (string)$element->template;	
		}
	
		if (isset($element['standalone'])) {
			$standalone = strtolower((string)$element['standalone']);
			$this->standalone = (strcmp($standalone,'true') == 0);
		}
		if (isset($element['auth'])) {
			$auth = strtolower((string)$element['auth']);
			$this->requiresAuth = (strcmp($auth,'true') == 0);
		}
		if (isset($element->action)) {
			$this->action = (string)$element->action;
		}
		if (isset($element->category)) {
			$this->condition = (string)$element->category;
		}
	}
	
	/**
	 * Get the action that this page handles
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * Get the smarty template associated with this page.
	 *
	 * @return string | The smarty template name.
	 */
	public function getTemplate() {
		return $this->template;
	}
	
	/**
	 * Get the Controller handler name.
	 *
	 * @return string
	 */
	public function getHandler() {
		return $this->handler;
	}
	
	/**
	 * Returns the filename where the class resides.
	 *
	 * @return string
	 */
	public function getHandleFilename() {
		return $this->handler.'.php';
	}
	
	/**
	 * Check if page should be embedded in site look or not.
	 *
	 * @return bool
	 */
	public function isStandalone() {
		return $this->standalone;
	}
	
	/**
	 * Check if logged in user is required to view this page.
	 *
	 * @return bool
	 */
	public function isProtected() {
		return $this->requiresAuth;
	}
	
	/**
	 * If more than 1 page has the same action .. it may have a condition attribute
	 *
	 * @return string
	 */
	public function getCondition() {
		return $this->condition;
	}
	
	
}

?>