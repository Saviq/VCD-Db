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
