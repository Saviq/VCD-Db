<h1>{$translate.menu.rss}</h1>
	
{if is_array($rssList) && count($rssList)>0}
	
{foreach from=$rssList item=i}

	<p class="normal"><strong>{$i.image}<a href="{$i.link}" target="_blank"">{$i.title}</a></strong>
	
	{if is_array($i.items) && count($i.items)>0}
		<ul style="padding-left:16px;">
		{foreach from=$i.items item=j}
	
			<li><a href="{$j.link}" target="_blank">{$j.title}</a>
			{if $j.desc}
				<a href="{$j.desc}" target="_blank">[link]</a>
			{/if}
			</li>
			
		{/foreach}
		</ul>
	{else}
		<br/><br/>
		RSS Feed not found for {$i.title}, site maybe down.
	{/if}
	
		
	</p>
			


{/foreach}

{/if}