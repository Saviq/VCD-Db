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

	CONST PRIMARY_LANGINDEX = "includes/languages/languages.xml";
	private $arrLanguages = array();
	
	private $primaryLanguage = null;
	
	function __construct() {
	
		$this->init();
		
	}
	
	
	private function init() {
		try {

			if (file_exists(self::PRIMARY_LANGINDEX )) {
			
				$xmlStream = simplexml_load_file(self::PRIMARY_LANGINDEX );
								
				foreach ($xmlStream->language as $node) {
					array_push($this->arrLanguages, new _VCDLanguageItem($node));
				}
				
			}
			
			
			/*
			foreach ($this->arrLanguages as $obj) {
				print $obj->getName() . "<br>";
			}
			*/
			
			
			$this->arrLanguages[1]->getKeys();
			$this->primaryLanguage = $this->arrLanguages[1];
						
			
			/*
			foreach ($keys as $keyObj) {
				print $keyObj->getKey() . " <br>";
			}
			*/
			
		
		} catch (Exception $ex) {
		
			throw $ex;
			
		}
	}
	
	public function getLanguage() {
		return $this->primaryLanguage;
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
		$file = self::LANG_FILE_ROOT.$this->id.".xml";
		
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
	 * @return unknown
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
	 function offsetSet($key, $value) { 
	   	throw new Exception("Language Keys are read only!");
	 } 
	
	 /** 
	 * Defined by ArrayAccess interface 
	 * Return a value given it's key e.g. echo $A['title']; 
	 * @param mixed key (string or integer) 
	 * @return mixed value 
	 */ 
	 function offsetGet($key) { 
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
	 function offsetUnset($key) { 
		throw new Exception("Language Keys are read only!");
	 } 
	
	 /** 
	 * Defined by ArrayAccess interface 
	 * Check value exists, given it's key e.g. isset($A['title']) 
	 * @param mixed key (string or integer) 
	 * @return boolean 
	 */ 
	 function offsetExists($offset) { 
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
		$this->id = $element['id'];
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