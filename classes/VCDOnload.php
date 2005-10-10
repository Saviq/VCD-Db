<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Core
 * @version $Id$
 */
?>
<?PHP
/* 
	VCD Application OnLoad functions and commands
*/


session_name("VCD");
session_start();	
$start_time = VCDUtils::getmicrotime(true);


// Logout user | But we have to keep the users selected language in session
if (isset($_GET['do']) && strcmp($_GET['do'],'logout') == 0) {
	$sel_lang = "";
	if (isset($_SESSION['vcdlang'])) {
		$sel_lang = $_SESSION['vcdlang'];
	}
	
	
	$cookie = new SiteCookie("vcd_cookie", time()-86400);
	$cookie->clear();
	$cookie->set();
	
	session_destroy();
	
	// Restore selected language if needed
	if (strcmp($sel_lang, "") != 0) {
		session_name("VCD");
		session_start();
		$_SESSION['vcdlang'] = $sel_lang;
	}
	
	
	header("Location: ".$_SERVER['HTTP_REFERER'].""); /* Redirect browser */ 
	exit();
}


	
// Only check for cookie if user is not logged in
if (!isset($_SESSION['user'])) {
	VCDAuthentication::checkCookie();
} 


global $CURRENT_PAGE;
if (isset($_GET['page'])) {
	$CURRENT_PAGE = $_GET['page'];
}


if (isset($_SESSION['vcdlang'])) {
	$language = new language();
	$language->load($_SESSION['vcdlang']);
		
} else {
	$language = new language();
	
	// Has the user a selected language in cookie?
	SiteCookie::extract('vcd_cookie');
	if (isset($_COOKIE['language'])) {
		if ($language instanceof language ) {
			$language->load($_COOKIE['language']);
		}
		
	}
	
}
	
	
if (isset($_POST)) {
	if (isset($_POST['lang'])) {
		
		global $language;
		$lang_tag = $_POST['lang'];
		$language->load($lang_tag);
		
		// Check for existing cookie
		SiteCookie::extract('vcd_cookie');
		if (isset($_COOKIE['session_id']) && isset($_COOKIE['session_uid'])) {
			$session_id    = $_COOKIE['session_id'];			
			$user_id 	   = $_COOKIE['session_uid'];
			$session_time  = $_COOKIE['session_time'];	
			
			
			$Cookie = new SiteCookie("vcd_cookie");
			$Cookie->clear();
			$Cookie->put("session_id", $session_id);	
			$Cookie->put("session_time", $session_time);
			$Cookie->put("session_uid", $user_id);
			$Cookie->put("language",$_POST['lang']);	
			$Cookie->set();
			
		} else {
		
			/* Add selected value in cookie for future visits*/
	   		$Cookie = new SiteCookie("vcd_cookie");
			$Cookie->put("language",$_POST['lang']);	
			$Cookie->set();
			
		}
		
		
		
			
		$ref = $_SERVER['HTTP_REFERER'];
		/* Redirect to avoid expired page*/
		if (strlen($ref) > 0) {
			header("Location: $ref"); /* Redirect browser */
		} else {
			redirect(); /* Redirect browser */
		}
	}
}


// Show or Hide right sidebar layer ..
global $showright;
$showright = true;


?>
