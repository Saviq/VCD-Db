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
require_once('installer.php');
require_once('../functions/WebFunctions.php');
require_once('../classes/VCDUtils.php');
session_start();

$setup_step = 0;
if (isset($_GET['step'])) {
	$setup_step = $_GET['step'] + 1;
}


if (isset($_GET['del'])) {
	session_destroy();
	redirect();
}

if (isset($_SESSION['vcdinstall'])) {
	$install = $_SESSION['vcdinstall'];
	$install->setStep($setup_step);
} else {
	$install = new installer();
	$_SESSION['vcdinstall'] = $install;
}


if (isset($_POST) && sizeof($_POST) > 0) {
	$install->gatherData($_POST);
}

if (isset($_GET['retry'])) {
	$install->retry();
}

// Show no PHP errors
error_reporting(0);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><title>VCD-db - Setup</title>
<style type="text/css" media="all">@import url("../includes/templates/default/style.css");</style>
<style type="text/css" media="all">@import url("../includes/css/setup.css");</style>
<script src="setup.js" type="text/javascript"></script>
</head>
<body>
<form name="setup" method="post" action="index.php?step=<?=$setup_step?>">
<table cellspacing="0" cellpadding="0" width="830" border="0">
<tr>
	<td colspan="2"><div class="bar">Setup</div></td>
</tr>
<tr>
	<td valign="top" style="padding:18px 0px 0px 16px" class="content">
	<!-- Setup content -->
	<?$install->showStep();?>
	<? 
		if ($install->getStep() == 0)	 {
			?>
			<h2>VCD-DB (v. <?= VCDDB_VERSION ?>) Setup</h2>
			Congratulations with your copy of VCD-DB<br/>
			Before we start there are few things that need to be done.
			<br/><br/>
			<ul>
				<li>1) You must have a database access.</li>
				<li>2) Create an empty database.<br/>
				<blockquote>At the moment the following databases are supported <br/>(although others might work like Oracle)
				<br/> a) MySQL (3.x, 4.x and up)
				<br/> b) Microsoft SQL (7 and 2000)
				<br/> c) Postgres
				<br/> d) IBM DB2 (7.2 and up)
				</blockquote></li>
			<li>3) If your webserver is on a <span title="Such as WinXP, Windows 2000 and Windows 2003 server">Win32 box</span> this step is unneccassary,<br/> otherwise if your webserver is on a Unix/Linux box, you must have an Shell Access <br/>to chmod some files and folders.
			Open up a console session to your box and<br/> go to the directory where you extracted the VCD-DB zip file.<br/>
			<b>Enter the following commands:</b>
			<blockquote>
			chmod 0777 classes/VCDConstants.php<br/>
			chmod 0777 upload<br/>
			chmod 0777 upload/cache<br/>
			chmod 0777 upload/covers<br/>
			chmod 0777 upload/pornstars<br/>
			chmod 0777 upload/screenshots/albums<br/>
			chmod 0777 upload/screenshots/generated<br/>
			chmod 0777 upload/thumbnails<br/>
			</blockquote>
			
			</li>
			<li>
			4) Now you are all set and ready to install. Press Continue to proceed.<br/>
			<b>(Attention: After successful install be sure to delete the setup folder<br/>to prevent abuse.)</b>
			</li>
				
						
			</ul>
			
			
			
			<?
		}
	?>
	
	
	<br/><br/>
	<?
		if ($install->showNextStep()) {
			print "<input type=\"submit\" value=\"Continue &gt;&gt;\" title=\"Press only once!\" onclick=\"return validate(".$setup_step.",this.form)\"/>";	
		}
	?>
	
	<? 
		if ($install->getStep() == 0) {
		?><br><br><br><input type="button" onclick="location.href='./upgrade.php'" value="Upgrade VCD-db &gt;&gt;"> (Click to upgrade previous VCD-db installation)<?
		}
	?>
		
	<!-- / Setup content  -->
	</td>
	<td valign="top" width="300" style="padding-right:14px;">
	<!-- Sidebar table -->
	<br/>
	<table cellspacing="0" cellpadding="6" border="0" width="300" id="menu">
	<?
		$steps = & $install->getSteps();
		for ($i = 0; $i < sizeof($steps); $i++) {
			if ($i == $install->getStep()) {
				print "<tr><td class=\"active\">&gt; ".$steps[$i]."</td></tr>";
			} else {
				print "<tr><td>&gt; ".$steps[$i]."</td></tr>";
			}
		}
	?>
	</table>

	
	<!-- / Sidebar table -->
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><img src="../images/logotest.gif" alt="" hspace="80" vspace="2"/><br/><br/></td>
</tr>
<tr>
	<td colspan="2"><div class="bar">(c)<a href="./?del=true">start over</a></div></td>
</tr>
</table>
</form>
</body>
</html>