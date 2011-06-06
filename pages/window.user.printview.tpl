<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>
<body onload="window.focus()" class="nobg">

{if $smarty.get.mode eq 'text'}

<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td class="bold" width="50%">{$translate.movie.title}</td>
	<td class="bold">{$translate.movie.category}</td>
	<td class="bold">{$translate.movie.year}</td>
	<td class="bold">{$translate.movie.media}</td>
	<td class="bold">{$translate.movie.date}</td>
</tr>
{foreach from=$itemList item=i}
<tr>
	<td>{$i.title|escape}</td>
	<td nowrap="nowrap">{$i.category}</td>
	<td>{$i.year}</td>
	<td nowrap="nowrap">{$i.mediatype}</td>
	<td nowrap="nowrap">{$i.date|date_format:$config.date}</td>
</tr>
{/foreach}
</table>


{else}

{assign var='cols' value=6}
{math equation="(int)(y/x)" x=$cols y=$itemCount assign=iterations}
{math equation="(int)(x/100)" x=$cols assign=width}
<table border="0" cellpadding="0" cellspacing="1" width="100%">
{foreach from=$itemList item=i name=list}
{if $smarty.foreach.list.first}
<tr>
{/if}
	<td align="center" width="{$width}"><span class="ptil">{$i.title|truncate:20:".."}</span><br/>{$i.image}</td>
{if $smarty.foreach.list.last or (((($smarty.foreach.list.iteration) % $cols) == 0) and not $smarty.foreach.list.first)}
</tr>
{if not $smarty.foreach.list.last and (int)($smarty.foreach.list.index/$cols) < ($iterations)}
<tr>
{/if}
{/if}
{/foreach}
</table>




{/if}




</body>
</html>