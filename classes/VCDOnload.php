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
/*
	VCD Application OnLoad functions and commands
*/
session_start();
$start_time = VCDUtils::getmicrotime(true);


// Set timezone values to get rid of E_STRICT errors in PHP5.1 and above
if (function_exists('date_default_timezone_get')) {
	$dz = @date_default_timezone_get();
	date_default_timezone_set($dz);
}

// Logout user | But we have to keep the users selected language in session
// and remember the selected template
if (isset($_GET['do']) && strcmp($_GET['do'],'logout') == 0) {
	$sel_lang = "";
	if (isset($_SESSION['vcdlang'])) {
		$sel_lang = $_SESSION['vcdlang'];
	}

	// check for selected template
	$template = null;
	SiteCookie::extract('vcd_cookie');
	if (isset($_COOKIE['template']) && $_COOKIE['template'] != '') {
		$template = $_COOKIE['template'];
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

	// remember the template if any is selected
	if (!is_null($template)) {
		$Cookie = new SiteCookie("vcd_cookie");
		if (strcmp($sel_lang, "") != 0) {
			$Cookie->put("language",$sel_lang);	
		}
		$Cookie->put("template", $template);
		$Cookie->set();
	}

	header("Location: ".$_SERVER['HTTP_REFERER'].""); /* Redirect browser */
	exit();
}



// Only check for cookie if user is not logged in
if (!isset($_SESSION['user'])) {
	VCDAuthentication::checkCookie();
}


// Clean up magic_quotes garbage
VCDUtils::cleanMagicQuotes();


global $CURRENT_PAGE;
if (isset($_GET['page'])) {
	$CURRENT_PAGE = $_GET['page'];
}

if (isset($_SESSION['vcdlang'])) {
	$language = new VCDLanguage();
	$language->load($_SESSION['vcdlang']);
	VCDClassFactory::put($language);
} else {
	$language = new VCDLanguage();

	// Has the user a selected language in cookie?
	SiteCookie::extract('vcd_cookie');
	if (isset($_COOKIE['language'])) {
		if ($language instanceof VCDLanguage ) {
			$language->load($_COOKIE['language']);
		}

	}
	VCDClassFactory::put($language);

}

if (isset($_POST)) {
	if (isset($_POST['lang'])) {

		$lang_tag = $_POST['lang'];
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
			$Cookie->put("language",$_POST['lang']);

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
