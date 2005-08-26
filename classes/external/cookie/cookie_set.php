<?php
	// Include the Class
	include "SiteCookie.php";
	
	// Create a local object
	$SiteCookie=new SiteCookie("test_cookie");
	
	// Add the variables to be saved in the cookie
	$SiteCookie->put("namefirst","Jo");
	$SiteCookie->put("namelast","Foo");
	$SiteCookie->put("number","1234");
	$SiteCookie->put("time",time());

    // Set the cookie
	$SiteCookie->set();
	
	echo "<br>The values saved in the cookie test_cookie are:";
	echo "<br>namefirst: = $_COOKIE[namefirst]";
	echo "<br>namelast: = $_COOKIE[namelast]";
	echo "<br>number: = $_COOKIE[number]";
	echo "<br>time: = $_COOKIE[time]";
	echo "<br><br>END";

?>