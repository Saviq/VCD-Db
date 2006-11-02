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
<?
require_once(dirname(__FILE__).'/config.php');

if (isset($_GET['searchstring']) && isset($_GET['by'])) {
	$search_string = $_GET['searchstring'];
	$search_method = $_GET['by'];
	// remember last search method
	$_SESSION['searchkey'] = $search_method;
	
	// Get the search results

} else {
	// BAD request .. forward to front page
	redirect();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= VCDUtils::getCharSet()?>"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" /> 
	<link rel="stylesheet" type="text/css" href="<?= VCDUtils::getStyle()?>" media="screen, projection"/>
	<?= VCDUtils::getAlternateLink() ?>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>
<body>


<div id="outer">

<? require_once('modules/header.php') ?>

<div id="bodyblock" align="right">

<!-- Sidebar starts -->
<div id="l-col">
<? require_once('modules/sidebar_left.php') ?>
</div>
<!-- Sidebar ends -->

<div id="cont">

<!-- Right Sidebar starts -->
<? 
//require_once('modules/sidebar_right.php') ;
if (!$showright) {	hidelayer("right"); }
?>
<!-- Right Sidebar ends -->

<? require_once('pages/search_results.php'); ?>
</div><!-- /cont -->
</div><!-- / bodyblock-->

<? require_once('modules/footer.php') ?>

</div> <!-- /outer -->


</body>
</html>