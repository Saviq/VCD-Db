{if $smarty.get.web eq 'excalibur'}
	{assign var='formname' value='jsDVDform'}
{elseif $smarty.get.web eq 'goliath'}
	{assign var='formname' value='littlesearch'}
{elseif $smarty.get.web eq 'searchextreme'}
	{assign var='formname' value='quickie'}
{elseif $smarty.get.web eq 'google'}
	{assign var='formname' value='qs'}
{elseif $smarty.get.web eq 'eurobabe'}
	{assign var='formname' value='eurobabe'}
{/if}

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>{$smarty.get.pornstar}</title>
</head>
<body onload="document.{$formname}.submit()">


<div align="center" style="color:red;font-weight:bolder">Processing request .......</div>

<div id="forms" style="display:none">
{if $smarty.get.web eq 'excalibur'}
<form name="jsDVDform" action="http://www.excaliburfilms.com/excals.htm" target="_self">
	<input type="radio" name="SearchFor" value="Title.x"/>
	<input type="radio" name="SearchFor" value="Star.x" checked="checked"/>
	<input name="searchString" type="Text" value="{$smarty.get.pornstar}"/>
	<input type="hidden" name="Case" value="ExcalMovies"/>
	<input type="hidden" name="Search" value="AdultDVDMovies"/>
</form>
		
{elseif $smarty.get.web eq 'goliath'}

<form name="littlesearch" method="post" action="http://www.goliathfilms.com/index.php" target="_self">
	<input name="search[text]" value="{$smarty.get.pornstar}" type="text" />
	<input type="radio" name="search[in]" value="artists" checked="checked" />
</form>

{elseif $smarty.get.web eq 'searchextreme'}

<form action="http://www.searchextreme.com/quickie.aspx" method="post" name="quickie" target="_self">
	<input type="hidden" name="searchType" value="actor"/>
	<input type="hidden" name="searchstring" value="{$smarty.get.pornstar}"/>
</form>
		
{elseif $smarty.get.web eq 'google'}

<form action="http://images.google.com/images" method="get" name="qs" target="_self">
	<input type=text name=q size=41 maxlength=2048 value="{$smarty.get.pornstar}" title=""/>
</form>

{elseif $smarty.get.web eq 'eurobabe'}

<form method="post" action="http://www.eurobabeindex.com/modules/search.py" name="eurobabe" target="_self">
	<input type="hidden" name="text" value="{$smarty.get.pornstar}"/>
	<input type="hidden" name="what" value="Search Babe"/>
</form>

{/if}
</div>

</body>
</html>
