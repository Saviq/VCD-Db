<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	{$pageScripts}
</head>
<body onload="window.focus()" class="nobg">

<div id="tabledata">
<table cellspacing="1" cellpadding="1" border="0" width="100%">
<tr>
	<td>&nbsp;</td>
	{foreach from=$statsMediatypes item=title name=mediatypes}
	<td>{$title}</td>
	{/foreach}
	<td>&nbsp;</td>
</tr>
{foreach from=$statsCategories item=i key=categoryName name=mediatypes}
<tr class="{cycle values="stata,statb"}">
	<td>{$categoryName}</td>
	{foreach from=$i item=j name=items}
	<td align="right">{$j}</td>
	{/foreach}
</tr>	
{/foreach}
<tr class="{cycle values="stata,statb"}">
	<td>&nbsp;</td>
	{foreach from=$statsSums item=sums}
	<td class="bold" align="right">{$sums}</td>
	{/foreach}
</tr>
</table>
</div>
<hr/>
<p align="center"><input onclick="window.close()" type="button" value="{$translate.misc.close}"/></p>

{literal}
<script type="text/javascript">
try {
	var tHeight = document.getElementById("tabledata").offsetHeight + 110;
	var tWidth = document.layers ? window.outerWidth : document.body.clientWidth;
	window.resizeTo(tWidth,tHeight);
} catch (ex) {}
</script>
{/literal}
</body>
</html>