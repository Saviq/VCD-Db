{if $noJoin}
	<p>
		Sorry, but your underlying MySQL database is prior to version 4.1,<br/>
		only MySQL 4.1 and up support subqueries, and these functions require subquery support.
	</p>
	
{else}

<form name="discjoin" method="post" action="{$smarty.server.SCRIPT_NAME}?page=movies&amp;do=join">

<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td>1) {$translate.mymovies.joinsuser}</td>
	<td>{html_options id=owner name=owner options=$ownerList selected=$smarty.post.owner}</td>
</tr>
<tr>
	<td>2) {$translate.mymovies.joinsmedia}</td>
	<td>{html_options id=mediatype name=mediatype options=$mediatypeList selected=$smarty.post.mediatype}</td>
</tr>
<tr>
	<td>3) {$translate.mymovies.joinscat}</td>
	<td>{html_options id=category name=category options=$categoryJoinList selected=$smarty.post.category}</td>
</tr>
<tr>
	<td>4) {$translate.mymovies.joinstype}</td>
	<td>{html_options id=method name=method options=$methodList selected=$smarty.post.method}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$translate.mymovies.joinshow}"/></td>
</tr>
</table>
</form>


{if $isJoin}
	{if is_array($joinResults) && count($joinResults)>0}
	<ol>
		{foreach from=$joinResults item=title key=k}
		<li><a href="?page=cd&amp;vcd_id={$k}">{$title|escape}</a></li>
		{/foreach}
		</ol>
	{else}
		<p class="bold">{$translate.mymovies.noresults}</p>
	{/if}
{/if}

{/if}