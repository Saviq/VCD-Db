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
abstract class VCDPage extends Smarty  {

	private $template = null;
	private $debug = false;
	private static $pageBuffer;
	private $tidy = false;
	private $mod_rewrite = false;
	
	protected function __construct($template, $doTranslate = true) {
	
		parent::Smarty();
		$this->force_compile = false;
		$this->compile_check = false;
		if ($template !== 'page.error.tpl') {
			$this->mod_rewrite = VCDConfig::isUsingFriendlyUrls();
		}
		
		
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
			if (isset($_GET[$param]) && (strcmp($_GET[$param],'')!=0)) {
				return $_GET[$param];
			}
		}
		return $defaultValue;
	}
	
	/**
	 * Render the current template to the internal pagebuffer.
	 * if $template is NULL $this->template is used.
	 *
	 * @param string $template | The template to render. 
	 */
	public function render($template=null) {
		
		$compile_id = VCDClassFactory::getInstance('VCDLanguage')->getPrimaryLanguageID();
				
		if (is_null($template)) {
			$buffer = $this->fetch($this->template, 'vcddb', $compile_id);
		} else {
			$buffer = $this->fetch($template, 'vcddb', $compile_id);
		}
		
		$base = VCDConfig::getWebBaseDir();
		self::$pageBuffer .= $this->rewriteRelative($buffer,$base);
		
	}
	
	
	public function renderPage() {
		
		if($this->mod_rewrite) {
			$this->rewriteShortUrls();
		}
		
		if ($this->tidy && extension_loaded('tidy')) {
			// Specify configuration
			$config = array(
			           'indent'         => true,
			           'output-xhtml'   => true,
			           'wrap'           => 200);
			
			// Tidy
			$tidy = new tidy;
			$tidy->parseString(self::$pageBuffer, $config, 'utf8');
			$tidy->cleanRepair();
			print $tidy;
		} else {
			print self::$pageBuffer;
		}
	}
	
	private function rewriteShortUrls() {
		$in = array(
			"'index.php\?page=cd&amp;vcd_id=([0-9]*)'",
			"'\?page=cd&amp;vcd_id=([0-9]*)'",
			"'\?page=category&amp;category_id=([0-9]*)&amp;batch=([0-9]*)&amp;viewmode=(img|text)'",
            "'\?page=category&amp;category_id=([0-9]*)&amp;batch=([0-9]*)&amp;sort=([a-zA-Z\\-]*)'",
			"'\?page=category&amp;category_id=([0-9]*)&amp;batch=([0-9]*)'",
			"'\?page=category&amp;category_id=([0-9]*)'",
			
			"'index.php\?page=adultcategory&amp;(category|studio)_id=([0-9]*)'",
			"'\?page=adultcategory&amp;(category|studio)_id=([0-9]*)&amp;batch=([0-9]*)&amp;viewmode=(img|text)'",
			"'\?page=adultcategory&amp;(category|studio)_id=([0-9]*)&amp;batch=([0-9]*)'",
			"'\?page=adultcategory&amp;(category|studio)_id=([0-9]*)'",
						
			"'\?page=pornstars&amp;view=(all|active)&amp;l=([a-zA-Z\\+]){1}&amp;viewmode=(img|text)'",
			"'\?page=pornstars&amp;view=(all|active)'",
			
			"'\?page=pornstar&amp;pornstar_id=([0-9]*)'",
						
			"'index.php\?page=movies&amp;do=([a-zA-Z\\-]*)&amp;index=([0-9]*)'",
			"'index.php\?page=movies&amp;do=([a-zA-Z\\-]*)'",
			"'\?page=movies&amp;do=([a-zA-Z\\-]*)&amp;index=([0-9]*)'", 
			"'\?page=movies&amp;do=([a-zA-Z\\-]*)'",
			"'\?page=file&amp;(cover|pornstar)_id=([0-9]*)'",
			"'\?page=search&amp;by=(actor|director)&amp;searchstring=([^\<]*)'",
			"'index.php\?page=([a-zA-Z\\-]*)'",
			"'\?page=([a-zA-Z\\-]*)'"
		);
		
		$out = array(
			'movie/\\1',
			'movie/\\1',
        	'category/\\1/\\2/\\3',
            'category/\\1/\\2/\\3',
        	'category/\\1/\\2',
        	'category/\\1',
        	
        	'xxx/\\1/\\2',
        	'xxx/\\1/\\2/\\3/\\4',
        	'xxx/\\1/\\2/\\3',
        	'xxx/\\1/\\2',
        	        	
        	'pornstars/\\1/\\2/\\3',
        	'pornstars/\\1',
        	
        	'pornstar/\\1',
        	
        	'page/movies/\\1/\\2', 
        	'page/movies/\\1', 
        	'page/movies/\\1/\\2',
        	'page/movies/\\1',
        	'file/\\1/\\2',
        	'search/\\1/\\2',
        	'page/\\1',
        	'page/\\1'
		);        

    	self::$pageBuffer = preg_replace($in, $out, &self::$pageBuffer);
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
		$html = preg_replace('@\<([^>]*) (href|src|archive)="(([^\:"])*|([^"]*:[^/"].*))"@i', '<\1 \2="' . $base . '\3"', $html);
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