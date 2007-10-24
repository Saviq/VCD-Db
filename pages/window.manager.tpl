<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/manager.css" media="screen, projection"/>
	<script type="text/javascript" language="javascript" src="includes/js/main.js" ></script>
	<script type="text/javascript" language="javascript" src="includes/js/js_tabs.js" ></script>
	
</head>
<body onload="tabInit();window.focus()" class="nobg">


<form onsubmit="copyFiles(this);" action="{$smarty.server.SCRIPT_NAME}?action=updatemovie" method="post" name="choiceForm" enctype="multipart/form-data">
<input type="hidden" name="cd_id" value=""/>

<div class="tabs">
<table cellpadding=0 cellspacing=0 border=0 style="width:100%; height:100%">
<tr>
{foreach from=$pageTabs item=i key=k name=tabs}
	{if $smarty.foreach.tabs.first}
	<td id="tab{$smarty.foreach.tabs.iteration}" class="tab tabActive" height="18">{$i.title}</td>
	{else}
	<td id="tab{$smarty.foreach.tabs.iteration}" class="tab">{$i.title}</td>
	{/if}
{/foreach}
</tr>
<tr>
{foreach from=$pageTabs item=i key=k name=base}
	{if $smarty.foreach.tabs.first}
	<td id="t{$smarty.foreach.tabs.iteration}base" style="height:2px; border-left:solid thin #E0E7EC"></td>
	{else}
	<td id="t{$smarty.foreach.tabs.iteration}base" style="height:2px; background-color:#E0E7EC"></td>
	{/if}
{/foreach}
</tr>
</table>
</div>


{foreach from=$pageTabs item=i key=k name=c}
{assign var='template' value=$i.template}
<div id="content{$smarty.foreach.c.iteration}" class="content">
{include file="$template" }
</div>
{/foreach}


<div id="submitters">
{if $isAdult}
<input type="submit" name="update" id="update" value="{$translate.misc.update}" class="buttontext" onClick="checkFieldsRaw(this.form,'choiceBox', 'id_list');alert('do extra check')"/>
<input type="submit" name="submit" id="submit" value="{$translate.misc.saveandclose}" class="buttontext" onClick="checkFieldsRaw(this.form,'choiceBox', 'id_list');alert('do extra check')"/>
{else}
<input type="submit" name="update" id="update" value="{$translate.misc.update}" class="buttontext" />
<input type="submit" name="submit" id="submit" value="{$translate.misc.saveandclose}" class="buttontext"/>
{/if}
<input type="button" name="close" value="{$translate.misc.close}" class="buttontext" onClick="window.close()"/>

</div>
</form>




</body>
</html>