<?php
/*
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 */
 // $Id:
?>
<?
error_reporting(E_STRICT | E_ALL | E_NOTICE);
require_once("classes/includes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
if (rightbar()) {
	require_once('modules/sidebar_right.php') ;
}


?>
<!-- Right Sidebar ends -->

<? require_once('modules/main_switch.php') ?>
</div><!-- /cont -->
</div><!-- / bodyblock-->

<? require_once('modules/footer.php') ?>

</div> <!-- /outer -->


<?= inc_tooltipjs() ?>
</body>
</html>