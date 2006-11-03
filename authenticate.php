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
 * @version $Id$
 */
?>
<?php 
	require_once(dirname(__FILE__).'/classes/includes.php');

	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		redirect(); /* Redirect browser - Bad request */ 
	}
	
	if ((strcmp($_POST['username'],"") == 0) || (strcmp($_POST['password'],"") == 0)) {
		redirect(); /* Redirect browser - empty request */ 
	}
		
	$username = str_replace("'", "", $_POST['username']);
	$password = str_replace("'", "", $_POST['password']);
	
	$remember = false;
	if (isset($_POST['remember'])) {
		$remember = true;
	}
	
	$user = VCDAuthentication::authenticate($username, $password, (bool)$remember);
	if ($user instanceof userObj ) {
		
		// user has been authenticated
		
		
		// But .. has this account been deleted ?
		if ($user->isDeleted()) {
			redirect('./?page=badlogin&account=disabled'); /* Redirect browser */ 
			exit();
		}
				
		// Store info in users cookie if want's to be remembered
		if (isset($_POST['remember']) && (bool)$_POST['remember']) {
			$Cookie = new SiteCookie("vcd_cookie");
	   		$Cookie->clear();
			$Cookie->put("session_id", session_id());	
			$Cookie->put("session_time", VCDUtils::getmicrotime());
			$Cookie->put("session_uid", $user->getUserId());
			$Cookie->put("language", $_SESSION['vcdlang']);
			$Cookie->set();
		}
			
		// Add userObj to session
		$_SESSION['user'] = $user;
		
		
		// Check if we are supposed to log this event ..
		if (VCDLog::isInLogList(VCDLog::EVENT_LOGIN )) {
			VCDLog::addEntry(VCDLog::EVENT_LOGIN, "User login");
		}
		
		// Redirect to referee page - N.B. HTTP_REFERER cannot always be trusted
		header("Location: ".$_SERVER['HTTP_REFERER'].""); /* Redirect browser */ 
		
		
		
	} else {
		// authentication failed
		redirect('./?page=badlogin'); /* Redirect browser */ 
	}
	
?>