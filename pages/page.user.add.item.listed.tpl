<h1>{$translate.addmovie.listed}</h1>

{if !is_array($movieList) || count($movieList)==0}

<p>{$translate.addmovie.notitles}</p>
	
{elseif $smarty.get.action eq ''}

<p class="bold">{$translate.addmovie.listedstep1}</p>

<div style="padding-left:10px;">
<form action="{$smarty.server.SCRIPT_NAME}?page=add_listed&amp;action=select" method="post" name="choiceForm">
<input type="hidden" name="id_list" id="id_list"/>
<input type="hidden" name="keys" value=""/>

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td><h2>{$translate.addmovie.indb}</h2>
		{html_options id=available name=available options=$movieList size="20" style="width:300px" onDblClick="moveOver(this.form, 'available', 'choiceBox');clr()" onkeypress="selectKeyPress()" onkeydown="onSelectKeyDown()" onblur="clr()" onfocus="clr()"}
	</td>
	<td>
		<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/>
		<br/>
		<input type="button" value="&lt;&lt;" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
	</td>
	<td><h2>{$translate.addmovie.selected}</h2>
		<select multiple name="choiceBox" id="choiceBox" style="width:300px;" size="8" class="input"></select>
		<br/>
		<input type="submit" onClick="return checkListed(this.form)" value="{$translate.misc.proceed} &gt;&gt;"/>
	</td>
</tr>
</table>

<br/>
<p>{$translate.addmovie.infolist}</p>

</form>
</div>

{elseif $smarty.get.action eq 'select'}

<p class="bold">{$translate.addmovie.listedstep2}</p>

<br/>

<form method="post" action="?page=add_listed&amp;action=confirm">
<table cellpadding="1" cellspacing="1" border="0" width="100%" class="displist">
<tr>
	<td class="bold" width="80%">{$translate.movie.title}</td>
	<td class="bold" nowrap="nowrap">{$translate.movie.mediatype}</td>
	<td class="bold" nowrap="nowrap">{$translate.movie.num}</td>
</tr>
{foreach from=$movieList item=title key=id name=list}
{assign var='c' value=$smarty.foreach.list.index}
<tr>
	<td>{$title|escape}</td>
	<td>{html_options id=mediatype_$c name=mediatype_$c options=$mediatypeList}</td>
	<td>{html_options id=cds_$c name=cds_$c options=$cdList}</td>
</tr>
{/foreach}
</table>

<p align="right" style="padding-right:85px;">
	<input type="hidden" name="id_list" value="{$smarty.post.id_list}"/>
	<input type="submit" value="{$translate.misc.confirm}" onclick="return confirmListed(this.form)"/>
</p>

</form>

{/if}