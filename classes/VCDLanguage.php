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
 * @version $Id$
 */
?>
<?php
class VCDLanguage {
	
	CONST PRIMARY_LANGINDEX = "languages.xml";
	CONST USERSET_LANGINDEX = "upload/languages.xml";
	CONST FALLBACK_ID = "en_EN";
	
	public static $LANGUAGE_ROOT;
	private $arrLanguages = array();
	
	/**
	 * The Primary _VCDLanguageItem to use translation from
	 *
	 * @var _VCDLanguageItem
	 */
	private $primaryLanguage = null;
	private $isRestricted = false;
	
		
	/**
	 * The Fallback language to use if translation from Primary is not found.
	 * Only populated if needed.  Default English.
	 *
	 * @var _VCDLanguageItem
	 */
	private $fallbackLanguage = null;
	
	/**
	 * Class Contructor.  Loads specified language if languageID is provided.
	 * By default no language is loaded.
	 *
	 * @param string $strLanguageID | The ID of the language to load
	 */
	public function __construct($strLanguageID = null) {
		$this->setFileRoot();
		$this->init();
		if (!is_null($strLanguageID)) {
			$this->load($strLanguageID);
		} else if (!isset($_SESSION['vcdlang'])) {
			$this->load($this->detectBrowserLanguage());
		}
	}
	
	
	/**
	 * Initialize the class, load the index file with available languages.
	 *
	 */
	private function init() {
		try {
			
					
			// Check if a restricted language subset should be used ..
			if ($this->loadRestricted()) {
				return;
			}
				
			// Nope .. no restrictions defined, using the defaults
			if (file_exists(self::$LANGUAGE_ROOT.self::PRIMARY_LANGINDEX )) {
				$xmlStream = simplexml_load_file(self::$LANGUAGE_ROOT.self::PRIMARY_LANGINDEX );
								
				foreach ($xmlStream->language as $node) {
					$this->arrLanguages[] = new _VCDLanguageItem($node);
				}
			}
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	/**
	 * Try to load languages based on restrictions if any ..
	 * Returns true if restrictions were found, otherwise false.
	 *
	 * @return bool
	 */
	private function loadRestricted() {
		try {
			
			$fileroot = str_replace('classes', '', dirname(__FILE__));
			if (file_exists($fileroot.self::USERSET_LANGINDEX)) {
				$xmlStream = simplexml_load_file($fileroot.self::USERSET_LANGINDEX);
				foreach ($xmlStream->language as $node) {
					$this->arrLanguages[] = new _VCDLanguageItem($node);
				}
				$this->isRestricted = true;
				return true;
			} else {
				return false;
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all the languages loaded within the class,
	 * returns array of _VCDLanguageItem
	 *
	 * @return array
	 */
	public function getAllLanguages() {
		return $this->arrLanguages;
	}
	
	/**
	 * Set restrictions on what languages to display
	 *
	 * @param array $arrRestrictionIDs | Array of language ID's
	 */
	public function setRestrictions($arrRestrictionIDs) {
		
		try {
		
			$newRestrictions = array();
			foreach($this->getTranslationFiles() as $transObj) {
				if (in_array($transObj['id'], $arrRestrictionIDs)) {
					array_push($newRestrictions, $transObj);
				}
			}
			
			if (sizeof($newRestrictions) < 1) {
				throw new Exception('At least 1 language must be defined.');
			}
			
			
			$strXML  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			$strXML .= "<languages>";
			foreach($newRestrictions as $transObj) {
				$strXML .= "\t<language id=\"{$transObj['id']}\" charset=\"{$transObj['charset']}\">\n";
				$strXML .= "\t\t<name>{$transObj['name']}</name>\n";
				$strXML .= "\t\t<native/>\n";
				$strXML .= "\t\t<author/>\n";
				$strXML .= "\t</language>\n";
			}
			$strXML .= "</languages>";
			
			
			$xmlObj = simplexml_load_string($strXML);
			$xmlFile = str_replace('classes', '', dirname(__FILE__)).self::USERSET_LANGINDEX;
			$xmlObj->asXML($xmlFile);
			

		} catch (Exception $ex) {
			throw $ex;
		}

	}
	
	/**
	 * Check if the language system is running in restriction mode
	 *
	 * @return bool
	 */
	public function isRestricted() {
		return $this->isRestricted;
	}
	
	/**
	 * Set the correct language file root
	 *
	 */
	private function setFileRoot() {
		$fileroot = str_replace('classes', '', dirname(__FILE__));
		self::$LANGUAGE_ROOT = $fileroot.'includes'.DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Load a translation based on the parameter ID.
	 * For example is_IS for Icelandic.
	 *
	 * @param string $strLanguageID
	 */
	public function load($strLanguageID) {
		foreach($this->arrLanguages as $obj) {
			if (strcmp($obj->getID(), $strLanguageID) == 0) {
				$this->primaryLanguage = &$obj;
				$this->primaryLanguage->getKeys();
				
				// Store the current selection in Session
		  		$_SESSION['vcdlang'] = $strLanguageID;
				
				return;
			}
		}
		
		// Language not found .. try to load the default one ..
		foreach($this->arrLanguages as $obj) {
			if (strcmp($obj->getID(), self::FALLBACK_ID) == 0) {
				$this->primaryLanguage = &$obj;
				$this->primaryLanguage->getKeys();
				// Store the current selection in Session
		  		$_SESSION['vcdlang'] = self::FALLBACK_ID;
				return;
			}
		}
		
		// English not available in the list .. then force en_EN include
		try {
			$this->primaryLanguage = new _VCDLanguageItem($this->createDefaultElement());
			$this->primaryLanguage->getKeys();
			$this->fallbackLanguage = &$this->primaryLanguage;
			$_SESSION['vcdlang'] = self::FALLBACK_ID;
		} catch (Exception $ex) {
			throw new Exception("Could not load language with ID " . $strLanguageID);
		}
	}
	
	/**
	 * Get the translation for the requested key, if key is not found in the
	 * Primary language object, translation is seeked from the fallback Language object.
	 *
	 * @param string $key | The Key for the language phrase
	 * @return string | The translated phrase
	 */
	public function doTranslate($key) {
		$strValue = $this->primaryLanguage[$key];
		if (!is_null($strValue)) {
			return $strValue;
		} else {
			return $this->fallbackTranslate($key);
		}
	}
	
	/**
	 * Get all translation keys and values for javascripts.
	 * They all begin with "js." in the xml files.
	 *
	 * @return array
	 */
	public function doJavascriptKeys() {
		return $this->primaryLanguage->getJsKeys();
	}
	
	/**
	 * Check if the primary language is English
	 *
	 * @return bool
	 */
	public function isEnglish() {
		return strcmp(self::PRIMARY_LANGINDEX, $this->primaryLanguage->getID() == 0);
	}
	
	
	/**
	 * Get the ID of the primary/selected langauge
	 *
	 * @return string
	 */
	public function getPrimaryLanguageID() {
		return $this->primaryLanguage->getID();
	}
	
	/**
	 * Print out the language selection HTML dropdown box.
	 *
	 * @return string
	 */
	public function printDropdownBox() {
		
		if (is_null($this->primaryLanguage)) {
			$this->primaryLanguage = &$this->fallbackLanguage;
		}
		
		$html = "<div id=\"lang\"><form name=\"vcdlang\" method=\"post\" action=\"./index.php?\">";
		$html .= "<select name=\"lang\" onchange=\"document.vcdlang.submit()\" class=\"inp\">";
		foreach ($this->arrLanguages as $langObj) {
			$strSelected = "";
			if (strcmp($langObj->getID(), $this->primaryLanguage->getID()) == 0) {
				$strSelected = " selected=\"selected\"";
			}
			$html .= "<option value=\"{$langObj->getID()}\"{$strSelected}>{$langObj->getName()}</option>";
		}
			
		$html .= "</select></form></div>";
		return $html;
		
	}
	
	/**
	 * Get information about all the translations residing in /includes/languages
	 * Returns array of array's with index [id,filename,name,charset,num]
	 *
	 * @return array
	 */
	public function getTranslationFiles() {
		$arrFiles = $this->findfiles(self::$LANGUAGE_ROOT,'/\.(xml)$/');
		$arrLangs = array();
		foreach ($arrFiles as $file) {
			$xmlFile = @simplexml_load_file($file);
			if (is_object($xmlFile) && strcmp(basename($file), "languages.xml") != 0) {
				$item = array(
					'id' 		=> (string)$xmlFile->id,
					'filename'	=> basename($file),
					'name'		=> (string)$xmlFile->name,
					'charset'	=> (string)$xmlFile->charset,
					'num'		=> count($xmlFile->strings->string)
				);
				array_push($arrLangs, $item);
			}
		}
		
		return $arrLangs;
	}

	
	/**
	 * Find translation for the current key
	 *
	 * @param string $key
	 * @return string
	 */
	public static function translate($key) {
		return VCDClassFactory::getInstance('VCDLanguage')->doTranslate($key);
	}
	
	
	/**
	 * Get keys, only for javascript items.
	 *
	 * @return array
	 */
	public static function getJavascriptKeys() {
		return VCDClassFactory::getInstance('VCDLanguage')->doJavascriptKeys();
	}
	
	/**
	 * Try to detect the default browser language if no language has been selected.
	 *
	 * @return string | The best match for language ID
	 */
	private function detectBrowserLanguage() {
		try {
		
			if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
				return self::FALLBACK_ID;
			}
			
			$pref=array();
		    foreach(split(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $lang) {
		        if (preg_match('/^([a-z]+).*?(?:;q=([0-9.]+))?/i', $lang.';q=1.0', $split) && isset($split[2])) {
		        	$pref[sprintf("%f%d", $split[2], rand(0,9999))]=strtolower($split[1]);
		        }
		    }
		    krsort($pref);
		    
		    $a = array();
		    $b = array();
		    foreach ($this->arrLanguages as $langObj) {
		    	$tokens = explode('_', $langObj->getID());
		    	array_push($a, $tokens[0]);
		    	$b[$tokens[0]] = $langObj->getID();
		    }
		    
		    $items = array_merge(array_intersect($pref, $a), $a);
		    $bestMatch = array_shift($items);
		    	    		    
		    if (isset($b[$bestMatch])) {
		    	return $b[$bestMatch];
		    } else {
		    	return self::FALLBACK_ID;
		    }
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	

	/**
	 * Get the translated value from the fallback language, since it
	 * was not found in the Primary translation object.
	 * 
	 * @param string $key | The key for the language phrase
	 * @return The translated language phrase
	 */
	private function fallbackTranslate($key) {
		$bIsloaded = false;
		if (!$this->fallbackLanguage instanceof _VCDLanguageItem) {
			foreach($this->arrLanguages as $obj) {
				if (strcmp($obj->getID(), self::FALLBACK_ID ) == 0) {
					$this->fallbackLanguage = $obj;
					$this->fallbackLanguage->getKeys();
					$bIsloaded = true;
					break;
				}
			}
		}
		
		if (!$bIsloaded) {
			// If fallbacklanguge could not be loaded .. then force the load
			$this->fallbackLanguage = new _VCDLanguageItem($this->createDefaultElement());
			$this->fallbackLanguage->getKeys();
		}
		
				
		$strValue = $this->fallbackLanguage[$key];
		if (!is_null($strValue)) {
			return $strValue;
		} else {
			return "undefined";
		}
	}
	
	
	private function createDefaultElement() {
		$xmlElement = "<language id=\"en_EN\" charset=\"UTF-8\"><name>English</name>";
		$xmlElement .= "<native>English</native><author>Konni</author></language>";
		return simplexml_load_string($xmlElement);
	}
	
	/**
	 * Search folder for files with certain extensions defined in the $fileregex parameter.
	 *
	 * @param string $location | The file location to seek
	 * @param string $fileregex | The regual expression to search by
	 * @return array
	 */
	private function findfiles($location='',$fileregex='') {
   		if (!$location or !is_dir($location) or !$fileregex) {
       		return false;
   		}

		$matchedfiles = array();

	   	$all = opendir($location);
	   	while ($file = readdir($all)) {
	       	if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {
	         	$subdir_matches = $this->findfiles($location.'/'.$file,$fileregex);
	         	$matchedfiles = array_merge($matchedfiles,$subdir_matches);
	         	unset($file);
	       	}
	       	elseif (!is_dir($location.'/'.$file)) {
	         	if (preg_match($fileregex,$file)) {
	             	array_push($matchedfiles,$location.'/'.$file);
	         	}
		       }
	   		}
	   	   closedir($all);
		   unset($all);
		   sort($matchedfiles);
	       return $matchedfiles;
 	}

	
	
}

/**
 * Container for a Language Translation object
 *
 */
class _VCDLanguageItem implements ArrayAccess {
	
	CONST LANG_FILE_ROOT = "includes/languages/";
	
	private $id;
	private $charset;
	private $name;
	private $native_name;
	private $author;
	private $filename;

	private $keys = array();
	private $jsKeypointer = array();
	
	/**
	 * Function constructor, gets a single SimpleXMLElement from the language.xml index file.
	 *
	 * @param SimpleXMLElement $element
	 */
	public function __construct(SimpleXMLElement $element) {
		$this->id = (string)$element['id'];
		$this->charset = (string)$element['charset'];
		$this->name = (string)$element->name;
		$this->native_name = (string)$element->native;
		$this->author = (string)$element->author;
	}
		
	
	/**
	 * Load the language keys from the XML file
	 *
	 */
	private function loadKeys() {
		$file = VCDLanguage::$LANGUAGE_ROOT.$this->id.".xml";
		
		if (file_exists($file)) {
			
			$xmlStream = simplexml_load_file($file);
			foreach ($xmlStream->strings->string as $node) {
				$k = new _VCDLanguageKey($node);
				$this->keys[] = $k;
				if (strpos($k->getID(),'js.')===0) {
					$this->jsKeypointer[] = $k;
				}
			}
			
		} else {
			throw new Exception("Could not load language file " . $file . ".xml");
		}
	}
	
	/**
	 * Get the language Keys for this language
	 *
	 * @return array
	 */
	public function getKeys() {
		if (sizeof($this->keys) == 0) {
			$this->loadKeys();
		}
		
		return $this->keys;
	}
	
	/**
	 * Get the javascript only languages keys.
	 *
	 * @return array
	 */
	public function getJsKeys() {
		if (sizeof($this->keys) == 0) {
			$this->loadKeys();
		}
		return $this->jsKeypointer;
	}
	
	/**
	 * Get the Language ID
	 *
	 * @return string
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the Language charset
	 *
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}
	
	/**
	 * Get the Language name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get the language name on the native tongue
	 *
	 * @return string
	 */
	public function getNativeName() {
		return $this->native_name;
	}
	
	/**
	 * Get the original author of the translation
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 * Set the Obj filename
	 *
	 * @param string $strFileName
	 */
	public function setFileName($strFileName) {
		$this->filename = $strFileName;
	}
	
	/**
	 * Get the filename of the Xml file
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->filename;
	}
		
		
		
		
	 /** 
	 * Defined by ArrayAccess interface 
	 * Set a value given it's key e.g. $A['title'] = 'foo'; 
	 * @param mixed key (string or integer) 
	 * @param mixed value 
	 * @return void 
	 */ 
	public function offsetSet($key, $value) { 
		throw new Exception("Language Keys are read only!");
	} 
	
	 /** 
	 * Defined by ArrayAccess interface 
	 * Return a value given it's key e.g. echo $A['title']; 
	 * @param mixed key (string or integer) 
	 * @return mixed value 
	 */ 
	public function offsetGet($key) { 
		foreach ($this->keys as $keyObj) {
			if (strcmp($keyObj->getID(), $key) == 0) {
				return $keyObj->getKey();
			}
		}
		return null;
	} 
	
	 /** 
	 * Defined by ArrayAccess interface 
	 * Unset a value by it's key e.g. unset($A['title']); 
	 * @param mixed key (string or integer) 
	 * @return void 
	 */ 
	public function offsetUnset($key) { 
		throw new Exception("Language Keys are read only!");
	}
	
	 /** 
	 * Defined by ArrayAccess interface 
	 * Check value exists, given it's key e.g. isset($A['title']) 
	 * @param mixed key (string or integer) 
	 * @return boolean 
	 */ 
	public function offsetExists($offset) { 
		foreach ($this->keys as $keyObj) {
			if (strcmp($keyObj->getID(), $key) == 0) {
				return true;
			}
		}
		return false;
	} 
 
	
}

class _VCDLanguageKey { 
	private $id;
	private $key;
	
	public function __construct(SimpleXMLElement $element) {
		$this->id = (string)$element['id'];
		$this->key = (string)$element;
	}
	
	/**
	 * Get the Key ID
	 *
	 * @return string
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the Key value
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
	
	
	
}



?>