<p>
{$translate.mymovies.helppickerinfo}
</p>

<br/><br/>

<div style="padding-left:10px">
<form name="picker" method="post" action="{$smarty.server.SCRIPT_NAME}">

<table cellpadding="1" cellspacing="1" width="100%" class="tblsmall">
<tr>
	<td width="80%">{$translate.mymovies.joinscat}</td>
	<td>{html_options id=category name=category options=$myCategoryList}</td>
</tr>
{if $isSeenlist}
<tr>
	<td>{$translate.mymovies.notseen}</td>
	<td><input type="checkbox" name="onlynotseen" value="1" class="nof"/></td>
</tr>
{/if}
<tr>
	<td>&nbsp;</td>
	<td><input type="button" name="search" value="{$translate.mymovies.find}" class="buttontext" onclick="showSuggestion(this.form)"/></td>
</tr>
</table>
</form>

<br/><br/>

{** Show the movie suggested by VCD-db **}
<div id="suggestion" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td valign="top" width="120" align="center" id="cover"></td>
	<td valign="top" nowrap="nowrap">

	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="2" valign="top"><h1 id="title"></h1></td>
	</tr>
	<tr>
		<td width="20%" class="bold">&nbsp;{$translate.movie.category}:</td>
		<td id="cat"></td>
	</tr>
	<tr>
		<td class="bold">&nbsp;{$translate.movie.year}:</td>
		<td id="year"></td>
	</tr>
	<tr>
		<td colspan="2" class="bold" style="padding-top:30px"><a href="#" id="link" target="_self">&nbsp;{$translate.misc.showmore} &gt;&gt;</a></td>
	</tr>
	</table>
	</td>
</tr>
</table>
</div>
<div id="noresults" style="display:none">
<p class="bold">{$translate.search.noresult}</p>
</div>

</div>

