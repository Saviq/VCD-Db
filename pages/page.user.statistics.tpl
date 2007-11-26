<h1>{$translate.menu.statistics}</h1>


{if $showgraphs}

	<div id="graphtypes" style="float:right;margin-right:10px">
		<a href="?page=stats&amp;view=gfx"><img src="images/icons/chart_pie.png" border="0"/></a>
		<a href="?page=stats&amp;view=gfx&amp;c=2"><img src="images/icons/chart_bar.png" border="0"/></a>
		<a href="?page=stats&amp;view=gfx&amp;c=3"><img src="images/icons/chart_line.png" border="0"/></a>
		<a href="?page=stats&amp;view=gfx&amp;c=4"><img src="images/icons/chart_curve.png" border="0"/></a>
		
	</div>

	<img src="?page=file&amp;graph={$graph1}" alt=""/>
	<hr/>
	<img src="?page=file&amp;graph={$graph2}" alt=""/>
	<hr/>
	<img src="?page=file&amp;graph={$graph3}" alt=""/>

{elseif $showTables}

	{* Categories *}
	<table cellspacing="1" cellpadding="1" border="0" width="100%" class="list">
	<tr>
		<td width="1"><a href="?page=stats&amp;view=gfx"><img src="images/icons/chart_bar.png" border="0" vspace="0"/></a></td>
		<td class="statheader">{$translate.movie.category}</td>
		<td class="statheader">{$movieCount}</td>
		<td class="statheader">&nbsp;</td>
	</tr>
	{foreach from=$statsCategoryList item=i}
	<tr>
		<td colspan="2" width="130"><a href="?page=category&amp;category_id={$i.id}">{$i.name}</a></td>
		<td width="30" align="right">{$i.count}</td>
		<td width="72%" nowrap="nowrap">
			<img src="images/bar_l.gif" height="10" alt="{$i.alt}" border="0"/>{$i.image}<img src="images/bar_r.gif" height="10" alt="{$i.alt}" border="0"/>
		</td>
	</tr>
	{/foreach}
	</table>
	
	
	
	{* Media Types *}
	<table cellspacing="1" cellpadding="1" border="0" width="100%" class="list">
	<tr>
		<td width="1"><a href="?page=stats&amp;view=gfx"><img src="images/icons/chart_bar.png" border="0" vspace="0"/></a></td>
		<td class="statheader">{$translate.movie.media}</td>
		<td class="statheader">{$movieCount}</td>
		<td class="statheader">&nbsp;</td>
	</tr>
	{foreach from=$statsMediaList item=i}
	<tr>
		<td colspan="2" width="130">{$i.name}</td>
		<td width="30" align="right">{$i.count}</td>
		<td width="72%" nowrap="nowrap">
			<img src="images/bar_l.gif" height="10" alt="{$i.alt}" border="0"/>{$i.image}<img src="images/bar_r.gif" height="10" alt="{$i.alt}" border="0"/>
		</td>
	</tr>
	{/foreach}
	</table>
	
	
	{* Production Year *}
	<table cellspacing="1" cellpadding="1" border="0" width="100%" class="list">
	<tr>
		<td width="1"><a href="?page=stats&amp;view=gfx"><img src="images/icons/chart_bar.png" border="0" vspace="0"/></a></td>
		<td class="statheader">{$translate.movie.year}</td>
		<td class="statheader">{$movieCount}</td>
		<td class="statheader">&nbsp;</td>
	</tr>
	{foreach from=$statsYearList item=i}
	<tr>
		<td colspan="2" width="130">{$i.name}</td>
		<td width="30" align="right">{$i.count}</td>
		<td width="72%" nowrap="nowrap">
			<img src="images/bar_l.gif" height="10" alt="{$i.alt}" border="0"/>{$i.image}<img src="images/bar_r.gif" height="10" alt="{$i.alt}" border="0"/>
		</td>
	</tr>
	{/foreach}
	</table>

{else}
	
	<p>You can use this page after you have added some movies to your collection.</p>

{/if}
