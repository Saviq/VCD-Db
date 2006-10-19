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
<?
class VCDLanguage {
	
	CONST PRIMARY_LANGINDEX = "languages.xml";
	CONST FALLBACK_ID = "en_EN";
	
	public static $LANGUAGE_ROOT;
	private $arrLanguages = array();
	
	/**
	 * The Primary _VCDLanguageItem to use translation from
	 *
	 * @var _VCDLanguageItem
	 */
	private $primaryLanguage = null;
	
	private $test = array();
	
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
		}
			
		/*
		foreach ($this->primaryLanguage->getKeys() as $obj) {
			$this->test[$obj->getID()] = $obj->getKey();
		}
		*/
	}
	
	
	/**
	 * Initialize the class, load the index file with available languages.
	 *
	 */
	private function init() {
		try {
			
			if (file_exists(self::$LANGUAGE_ROOT.self::PRIMARY_LANGINDEX )) {
				$xmlStream = simplexml_load_file(self::$LANGUAGE_ROOT.self::PRIMARY_LANGINDEX );
								
				foreach ($xmlStream->language as $node) {
					array_push($this->arrLanguages, new _VCDLanguageItem($node));
				}
			}
		
		} catch (Exception $ex) {
			throw $ex;
		}
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
		
		throw new Exception("Could not load language with ID " . $strLanguageID);
	}
	
	/**
	 * Get the translation for the requested key, if key is not found in the
	 * Primary language object, translation is seeked from the fallback Language object.
	 *
	 * @param string $key | The Key for the language phrase
	 * @return string | The translated phrase
	 */
	public function doTranslate($key) {
		//return $this->test[$key];
		$strValue = $this->primaryLanguage[$key];
		if (!is_null($strValue)) {
			return $strValue;
		} else {
			return $this->fallbackTranslate($key);
		}
	}
	
	/**
	 * Check if the primary language is English
	 *
	 * @return unknown
	 */
	public function isEnglish() {
		return strcmp(self::PRIMARY_LANGINDEX, $this->primaryLanguage->getID() == 0);
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
	 * Find translation for the current key
	 *
	 * @param string $key
	 * @return string
	 */
	public static function translate($key) {
		return VCDClassFactory::getInstance('VCDLanguage')->doTranslate($key);
	}
	

	/**
	 * Get the translated value from the fallback language, since it
	 * was not found in the Primary translation object.
	 * 
	 * @param string $key | The key for the language phrase
	 * @return The translated language phrase
	 */
	private function fallbackTranslate($key) {
		if (!$this->fallbackLanguage instanceof _VCDLanguageItem) {
			foreach($this->arrLanguages as $obj) {
				if (strcmp($obj->getID(), self::FALLBACK_ID ) == 0) {
					$this->fallbackLanguage = $obj;
					$this->fallbackLanguage->getKeys();
					break;
				}
			}
		}
		
		$strValue = $this->fallbackLanguage[$key];
		if (!is_null($strValue)) {
			return $strValue;
		} else {
			return "undefined";
		}
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

	private $keys = array();
	
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
				array_push($this->keys, new _VCDLanguageKey($node));
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