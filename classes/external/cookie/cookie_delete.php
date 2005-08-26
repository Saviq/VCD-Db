<?php

    // You can destroy the entire cookie two ways.
    //  1. set the expire time in the past
    //  2. call the clear() method and then the set() method.

	// Include the Class
	include "SiteCookie.php";
	
	// Create a local object
	$SiteCookie=new SiteCookie("test_cookie", time()-86400);
	
    // Clear all values
	$SiteCookie->clear();

    // Set the cookie
	$SiteCookie->set();
	
?>