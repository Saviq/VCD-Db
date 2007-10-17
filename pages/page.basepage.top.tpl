<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/lytebox.css" media="screen, projection" />
	<script src="includes/js/main.js" type="text/javascript"></script>
	{$pageScripts}
	{$pageRsslink}
</head>
<body>



<div id="outer">

{include file='module.header.tpl'}

<div id="bodyblock" align="right">

<!-- Sidebar starts -->
<div id="l-col">
{include file='module.sidebar.left.tpl'}
</div>
<!-- Sidebar ends -->

<div id="cont">

<!-- Right Sidebar starts -->
{if $showRightbar}
	{include file='module.sidebar.right.tpl'}
{/if}
<!-- Right Sidebar ends -->

{* Here below page data will be rendered *}