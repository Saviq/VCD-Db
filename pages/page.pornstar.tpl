<h1>Pornstar | {$name}</h1>

<script type="text/javascript">
var messages = new Array();
{foreach from=$scriptItem item=i}
messages[{$i.index}] = ['{$i.image}', 145, 205];
{/foreach}
</script>

<table cellspacing="1" cellpadding="0" border="0" class="displist" width="100%">
<tr>
	<td valign="top" width="170">{$image}<br/><div align="center"><strong>{$iafdlink}</strong></div></td>
	<td valign="top" style="padding-left:3px;text-indent:0px">
	<strong>{$translate.pornstar.name}:</strong>{$name}<br/>
	<strong>{$translate.pornstar.web}:</strong> {$homepage}<br/>
	<strong>{$translate.pornstar.moviecount}:</strong> {$moviecount}<br/><br/>
		
	<ul>
	{foreach from=$movies key=id item=i}
		<li onmouseover="doTooltip(event,{$i.index})" onmouseout="hideTip()"><a href="?page=cd&amp;vcd_id={$id}">{$i.title}</a></li>
	{/foreach}
	</ul>
	</td>
</tr>
</table>
<p>
{$biography|nl2br}
</p>
<script>
Tooltip.init();
</script>