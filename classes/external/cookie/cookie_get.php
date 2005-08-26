<?php
	// Include the Class
	include "SiteCookie.php";
	
	// Extract all the variables out of the saved cookie
	// into their own 'cookies'
	SiteCookie::extract("test_cookie");

	// Display the values found
	echo "<BR> Values of variables retrieved from test_cookie" ;
	echo "<br> Name: ";
	echo $_COOKIE['namefirst']; 
	echo " ";
	echo $_COOKIE['namelast'];
	echo "<br> Number: ";
	echo $_COOKIE['number'];
	echo "<br> Time: ";
	echo $_COOKIE['time'];
	
	echo "<br><br>END";

?>