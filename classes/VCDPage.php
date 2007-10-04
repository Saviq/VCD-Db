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
 * @version $Id: VCDPage.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

require_once(dirname(__FILE__) . '/external/smarty/Smarty.class.php');


//error_reporting(E_ALL | E_NOTICE | E_COMPILE_WARNING | E_CORE_ERROR | E_WARNING);
error_reporting(E_ALL | E_NOTICE | E_COMPILE_WARNING | E_CORE_ERROR | E_WARNING | E_STRICT);
 

abstract class VCDPage extends Smarty  {

	private $template = null;
	private $debug = false;
	private static $pageBuffer;
	
	protected function __construct($template, $doTranslate = true) {
	
		parent::Smarty();
		$this->force_compile = true; // Remove line for release versions ..
				
		$this->template = $template;
		$this->template_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR;
		$this->compile_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
		$this->cache_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
		$this->debugging = $this->debug;
		
		$this->verifyTemplate();
		
		if ($doTranslate == true) {
			$func = array('VCDPageTransform','translate');
			$this->register_prefilter($func);
		}	
		
		
		
	}
	
	/**
	 * Verify that the template to be rendered with Smarty actually exists.
	 *
	 */
	private function verifyTemplate() {
		$template = $this->template_dir.$this->template;
		if (!file_exists($template)) {
			throw new VCDProgramException('Template "' . $this->template . '" is missing.');
		}
	}
	
	
	/**
	 * Get a value from url parameter that is passed to the page.
	 * If $param does not exists or is an emptry string, null is returned.
	 *
	 * @param string $param | The parameter name
	 * @param boolean $isPost | Ids the parameter from _POST[] or  _GET[]
	 * @param string $defaultValue| Default value to use if $param is not found
	 * @return string | The paramter value
	 */
	protected function getParam($param, $isPost=false, $defaultValue=null) {
				
		if ($isPost) {
			if (isset($_POST[$param]) && (!empty($_POST[$param]))) {
				return $_POST[$param];
			} 			
		} else {
			if (isset($_GET[$param]) && (!empty($_GET[$param]))) {
				return $_GET[$param];
			}
		}
		return $defaultValue;
	}
	
	/**
	 * Render the current template to browser.
	 * if $template is NULL $this->template is used.
	 *
	 * @param string $template | The template to render. 
	 */
	public function render($template=null) {
		if (is_null($template)) {
			$buffer = $this->fetch($this->template);
		} else {
			$buffer = $this->fetch($template);
		}
		
		$base = dirname($_SERVER['PHP_SELF']).'/';
		print $this->rewriteRelative($buffer,$base);
	}
	
	/**
	 * Rewrite all urls to they become absolute, needed when mod_rewrite is used.
	 *
	 * @param string $html | The html buffer to transform
	 * @param string $base | The base directory where VCD-db resides
	 * @return string | The transformed html buffer
	 */
	private function rewriteRelative($html, $base) {

		// generate server-only replacement for root-relative URLs
		$server = preg_replace('@^([^\:]*)://([^/*]*)(/|$).*@', '\1://\2/', $base);
		
		// replace root-relative URLs
		$html = preg_replace('@\<([^>]*) (href|src)="/([^"]*)"@i', '<\1 \2="' . $server . '\3"', $html);
		
		// replace base-relative URLs (rather kludgy, but I couldn't get ! to work)
		$html = preg_replace('@\<([^>]*) (href|src)="(([^\:"])*|([^"]*:[^/"].*))"@i', '<\1 \2="' . $base . '\3"', $html);
		return $html; 
	}
	

		
}


/**
 * This class handles all transformations of the pagebuffer before it's sent to browser.
 *
 */
class VCDPageTransform {
	
	/**
	 * Find translation string based on the key
	 *
	 * @param string $key | The translation key
	 * @return string | The translation value
	 */
	public static function trans($key) {
		return VCDLanguage::translate($key[1]);
	}
	
	/**
	 * Convert all the {$translate.(.*?} smarty tags to translated values.
	 *
	 * @param string $tpl_source | The template's source
	 * @param Smarty $smarty | Smarty instance
	 * @return mixed
	 */
	public static function translate($tpl_source, &$smarty) {
		
		// We hook in here to list all the included modules.
		/*
		preg_match_all('/{include file=\'module.(.*?).tpl\'}/',$tpl_source,$modules,2);
		if (is_array($modules)) {
			$list = array();
			for ($i=0;$i<sizeof($modules);$i++) {
				$list[] = 'module.'.$modules[$i][1].'.tpl';
			}
		}
		*/
		
		return preg_replace_callback('/{\$translate.(.*?)}/', array(__CLASS__, 'trans'), $tpl_source); 
	}
}





?>