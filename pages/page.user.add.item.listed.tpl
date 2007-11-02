<h1>{$translate.addmovie.listed}</h1>

{if !is_array($movieList) || count($movieList)==0}

<p>{$translate.addmovie.notitles}</p>
	
{else}

<p class="bold">{$translate.addmovie.listedstep1}</p>

<div style="padding-left:10px;">
<form action="{$smarty.server.SCRIPT_NAME}?page=add_listed&amp;action=add" method="post" name="choiceForm">
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

{/if}