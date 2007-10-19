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


<form onSubmit="copyFiles(this);" action="../exec_form.php?action=updatemovie" method="post" name="choiceForm" enctype="multipart/form-data">
<input type="hidden" name="cd_id" value=""/>

<div class="tabs">
<table cellpadding=0 cellspacing=0 border=0 style="width:100%; height:100%">
<tr>
	<td id="tab1" class="tab tabActive" height="18">{$translate.manager.basic}</td>
	<td id="tab2" class="tab">
	{if $isAdult}
		{$translate.manager.empire}
	{else}
		{$translate.manager.imdb}
	{/if}
	</td>
	<td id="tab3" class="tab">{$translate.movie.actors}</td>
	<td id="tab4" class="tab">Covers</td>
	{if $isDvdType} 
	<td id="tab5" class="tab">DVD</td>
	{/if}
	{if $hasMetadata}
	<td id="tab6" class="tab">Meta</td>
	{/if}
</tr>
<tr>
	<td id="t1base" style="height:2px; border-left:solid thin #E0E7EC"></td>
	<td id="t2base" style="height:2px; background-color:#E0E7EC"></td>
	<td id="t3base" style="height:2px; background-color:#E0E7EC; border-right:solid thin #E0E7EC"></td>
	<td id="t4base" style="height:2px; background-color:#E0E7EC"></td>
	<td id="t5base" style="height:2px; background-color:#E0E7EC"></td>
	{if $hasMetadata}
	<td id="t6base" style="height:2px; background-color:#E0E7EC"></td>
	{/if}
</tr>
</table>
</div>


<div id="content1" class="content">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td valign="top" width="80%">
	<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb">Nr:</td>
	<td>{$itemId}</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.title}:</td>
	<td><input type="text" name="title" class="input" value="{$itemTitle}" size="40"/></td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.category}:</td>
	<td>{html_options id=category name=category options=$itemCategoryList selected=$itemCategoryId class="input"}</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.year}:</td>
	<td>{html_options id=year name=year options=$itemYearList selected=$itemYear class="input"}</td>
</tr>
{if $isAdult}
<tr>
	<td class="tblb">{$translate.movie.screenshots}:</td>
	<td>
	{if $hasScreenshots} 
		{$translate.misc.yes}
	{else}
		{$translate.misc.no}
	{/if}
	&nbsp;&nbsp;&nbsp;<a href="#" onclick="addScreenshots({$itemId})">[{$translate.manager.addmedia}]</a>
	</td>
</tr>
{else}
<tr>
	<td class="tblb">ID</td>
	<td><input type="text" value="{$itemExternalId}" size="8" name="imdb" class="input"/>&nbsp;{$itemSourceSiteName}</td>
</tr>
{/if}
<tr>
{if count($itemCopies) == 0}
	<td colspan="2"><hr/>{$translate.manager.nocopy}</td>
{elseif count($itemMediaTypes)==1}
	<td colspan="2"><hr/><strong>{$translate.manager.copy}</strong></td>
{else}
	<td colspan="2"><hr/><strong>{$translate.manager.copies}</strong></td>
{/if}
</tr>

<tr>
	<td colspan="2" valign="top">
	{if count($itemCopies)>0}
	<!-- Begin instance table -->
	<table cellspacing="1" cellpadding="1" border="0" width="100%">
	<tr>
		<td>{$translate.manager.1copy}</td>
		<td>{$translate.movie.mediatype}</td>
		<td>{$translate.movie.num}</td>
		<td>&nbsp;</td>
	</tr>
	{foreach from=$itemUserMediaTypes item=i key=key}
	<tr>
		<td>{$counter}</td>
		<td>dropdown type</td>
		<td>dropdown cd's</td>
		<td>delete link</td>
	</tr>
	{/foreach}
	<tr>
		<td>{$counter}</td>
		<td>dropdown new</td>
		<td>dropdown fjoldi</td>
		<td>&nbsp;</td>
	</tr>
	</table>
	<input type="hidden" name="usercdcount" value="{$itemUserMediaTypesSize}"/>
	{/if}
	<!-- End instance table -->
	</td>
</tr>
</table>


</td>
	<td valign="top" align="right" width="20%">{if isset($itemThumbnail)}{$itemThumbnail}{/if}</td>
</tr>
</table>
</div>

<div id="content2" class="content">
{if $isAdult}
<input type="hidden" name="id_list" id="id_list"/>
<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top">Studio:</td>
	<td>{html_options id=studio name=studio options=$itemStudioList selected=$selectedStudio class="input"}</td>
</tr>
<tr>
	<td class="tblb" valign="top" colspan="2">{$translate.dvdempire.subcat}:<br/>
	
		<table cellspacing="0" cellpadding="2" border="0">
		<tr>
			<td>{html_options size="8" class="input" style="width:200px;height:185px" id=available name=available options=$subCategoriesAvailable onDblClick="moveOver(this.form, 'available', 'choiceBox')" class="input"}</td>
			<td>
				<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');" class="input" style="margin-bottom:5px;"/>
				<br/>
				<input type="button" value="<<" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
			</td>
			<td>{html_options size="8" class="input" style="width:200px;height:185px" id=choiceBox name=choiceBox options=$subCategoriesUsed onDblClick="removeMe(this.form, 'available', 'choiceBox')" class="input"}</td>
		</tr>
		</table>
		
	</td>
</tr>
</table>


{else}

<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top">{$translate.movie.title}:</td>
	<td><input type="text" name="imdbtitle" class="input" value="{$sourceTitle}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.movie.alttitle}:</td>
	<td><input type="text" name="imdbalttitle" class="input" value="{$sourceAlttitle}" size="45"/></td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.grade}:</td>
	<td><input type="text" name="imdbgrade" class="input" value="{$sourceGrade}" size="3"/> {$translate.manager.stars}</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.runtime}:</td>
	<td><input type="text" name="imdbruntime" class="input" value="{$sourceRuntime}" size="3"/> min.</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.director}:</td>
	<td><input type="text" name="imdbdirector" class="input" value="{$sourceDirector}" size="45"/></td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.country}:</td>
	<td><input type="text" name="imdbcountries" class="input" value="{$sourceCountries}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">IMDB {$translate.movie.category}:</td>
	<td><input type="text" name="imdbcategories" class="input" value="{$sourceCategoryList}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.movie.plot}:</td>
	<td><textarea cols="40" rows="5" name="plot" class="input">{$sourcePlot}</textarea></td>
</tr>
</table>

{/if}
</div>

<div id="content3" class="content">
<div class="flow" align="left">
{if $isAdult}
<div align="right">
	<input type="button" value="{$translate.manager.addact}" class="buttontext" title="{$translate.manager.addact}" onClick="addActors({$itemId})"/>
</div>

	{if is_array($itemPornstars) && count($itemPornstars)>0}
		<table cellspacing="1" cellpadding="1" border="0">
		{foreach from=$itemPornstars item=name key=key}
		<tr>
			<td><li><a href="?page=pornstar&amp;pornstar_id={$key}" target="_blank">{$name}</a></li></td>
			<td>Pornstar links to search eingines come here</td>
		</tr>
		{/foreach}
		</table>
	{else}
		{$translate.movie.noactors}
	{/if}


{else}

<div align="center">
	<textarea cols="65" rows="15" name="actors" class="input">{$sourceActors}</textarea>
</div>


{/if}

<!-- Leikarar enda -->
</div>
</div>

<div id="content4" class="content">
<table cellspacing="1" cellpadding="1" border="0">
{foreach from=$itemCovers item=i key=key}
<tr>
	<td class="tblb" valign="top">{$i.typename}</td>
	<td><input type="text" name="{$i.typename}" size="20" class="input" value="{$i.filename}"/></td>
	<td><input type="file" name="{$i.typeid}" value="{$i.typename}" size="10" class="input"/></td>
	<td>delete cover link</td>
</tr>
{/foreach}
</table>
</div>

{if $isDVD}
<div id="content5" class="content">
	DVD contents
</div>
{/if}

{if $hasMetadata}
<div id="content6" class="content">
	<div class="flow" align="left">
	<p>
	<table cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td>Metadata table ..</td>
		</tr>
	</table>
	</p>
	</div>
</div>
{/if}

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