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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD-db Screenshots</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="includes/templates/default/style.css"/>
</head>
<body>
<?
	$screen_id = $_GET['s_id'];
	$s = new VCDScreenshot($screen_id);
	if (isset($_GET['slide'])) {
		$s->setPage($_GET['slide']);
	} 
	
	if (isset($_GET['image_id'])) {
		$s->showImage($_GET['image_id']);
	} else {
		$s->showPage();
	}
?>
</body>
</html>