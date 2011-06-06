<form name="user" method="post" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=updateprofile">
<h1>{$translate.menu.settings}</h1>
<fieldset id="settings" title="{$translate.menu.settings}">
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="displist">
<tr>
	<td width="35%">{$translate.register.fullname}:</td>
	<td><input type="text" name="name" value="{$fullname}"/></td>
</tr>
<tr>
	<td>{$translate.login.username}:</td>
	<td><input type="text" name="username" readonly="readonly" value="{$username}"/></td>
</tr>
<tr>
	<td>{$translate.register.email}:</td>
	<td><input type="text" name="email" value="{$email}"/></td>
</tr>
<tr>
	<td>{$translate.login.password}:</td>
	<td><input type="password" name="password" autocomplete="off"/></td>
</tr>
<tr>
	<td colspan="2">({$translate.login.info})<br/><br/></td>
</tr>
{foreach from=$properties key=id item=i}
<tr>
	<td nowrap="nowrap">{$i.description}</td>
	<td><input type="checkbox" name="property[]" class="nof" value="{$id}" {$i.checked}/>&nbsp;{$i.extra}</td>
</tr>
{/foreach}
<tr>
	<td><div class="info">{$message}</div>&nbsp;</td>
	<td><input type="submit" value="{$translate.misc.update}"/></td>
</tr>
</table>
</fieldset>
</form>
<br/>



<fieldset id="pagelook" title="{$translate.usersettings.pagelook}">
<legend class="bold">{$translate.usersettings.pagelook}</legend>

<p style="padding:0px 0px 2px 2px">
&nbsp;{$translate.usersettings.pagemode}
{html_options id=template name=template values=$templates output=$templates selected=$smarty.cookies.template|default:$config.style onchange="switchTemplate(this.options[this.selectedIndex].value)"}
</p>
</fieldset>
<br/>

{if is_array($borrowerList) && count($borrowerList) > 0}
<fieldset id="setBorrowers" title="{$translate.mymovies.friends}">
<legend class="bold">{$translate.mymovies.friends}</legend>
<table cellpadding="1" cellspacing="1" width="100%" border="0">
<tr>
	<td>
	{html_options id=borrowers name=borrowers options=$borrowerList selected=$selectedBorrower}
	&nbsp;<input type="button" value="{$translate.misc.edit}" onclick="changeBorrower()"/>
	<img src="images/icon_del.gif" hspace="4" alt="" style="vertical-align: middle;" onclick="deleteBorrower()" border="0"/>
	</td>
	{if $editBorrower}
	<td nowrap="nowrap" width="65%">
	<form name="update_borrower" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=update_borrower" method="post">
	<table cellpadding="0" cellspacing="0" border="0" class="list" width="100%">
		<tr>
			<td>{$translate.loan.name}:</td>
			<td><input type="text" size="18" name="borrower_name" value="{$borrowerName}"/></td>
			<td>{$translate.register.email}:</td>
			<td><input type="text" size="18" name="borrower_email" value="{$borrowerEmail}"/></td>
			<td>&nbsp;</td>
			<td><input type="submit" value="{$translate.misc.update}" id="saveBorrower" name="saveBorrower" onclick="return checkBorrower(this.form)"/></td>
		</tr>
	</table>
	<input type="hidden" name="borrower_id" value="{$borrowerId}"/>
	</form>
	</td>
	{/if}
</tr>
</table>
</fieldset>
<br/>
{/if}




<fieldset id="setRss" title="{$translate.rss.title}">
<legend class="bold">{$translate.rss.title}</legend>
{if is_array($feedList) && count($feedList) > 0}
	<table cellspacing="1" cellpadding="1" border="0" class="displist" width="100%">
	{foreach from=$feedList item=i}
	<tr>
		<td align="center"><img src="{$i.image}" hspace="4" title="{$i.title}" border="0"/></td>
		<td width="95%">{$i.name}</td>
		<td><a href="{$i.link}"><img src="images/rss.gif" border="0" alt="{$translate.rss.view}"/></a></td>
		<td><img src="images/icon_del.gif" onclick="deleteFeed({$i.id})"/></td>
	</tr>
	{/foreach}
	</table>
{else}
	<p>{$translate.rss.none}</p>
{/if}
<p><input type="button" value="{$translate.rss.add}" onclick="addFeed('vcddb');return false"/></p>
</fieldset>
<br/>





<fieldset id="setRssCustom" title="{$translate.usersettings.custom}">
<legend class="bold">{$translate.usersettings.custom}</legend>
<form name="choiceForm" method="post" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=update_frontpage">
<input type="hidden" name="rss_list" id="rss_list"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="40%">{$translate.usersettings.showstat}</td>
	<td><input type="checkbox" {$statChecked} name="stats" class="nof" value="yes"/></td>
</tr>
<tr>
	<td>{$translate.usersettings.showside}</td>
	<td><input type="checkbox" {$sideChecked} name="sidebar" class="nof" value="yes"/></td>
</tr>
<tr>
	<td valign="top">{$translate.usersettings.selectrss}</td>
	<td valign="top">
	<!-- Open rss selection  -->
	<table cellpadding="1" cellspacing="1">
	<tr>
		<td>{html_options size="5" style="width:300px;" id=rssAvailable name=rssAvailable options=$rssAvailList onDblClick="moveOver(this.form, 'rssAvailable', 'rssChoices')"}</td>
	</tr>
	<tr>
		<td align="center"><img src="images/move_down.gif" onclick="moveOver(document.choiceForm, 'rssAvailable', 'rssChoices');" hspace="4" border="0"/><img src="images/move_up.gif" onclick="removeMe(document.choiceForm, 'rssAvailable', 'rssChoices');" border="0"/></td>
	</tr>
	<tr>
		<td>{html_options size="5" class="input" style="width:300px;" id=rssChoices name=rssChoices options=$rssChList ondblclick="removeMe(document.choiceForm, 'rssAvailable', 'rssChoices')"}</td>
	</tr>
	</table>
	<!-- Close rss selection -->
	</td>

</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$translate.misc.update}" onclick="checkFieldsRaw(this.form,'rssChoices', 'rss_list')"/> 
		&nbsp; <input type="button" value="{$translate.rss.add}" onclick="addFeed('site');return false"/>
	</td>
</tr>
</table>
</form>
</fieldset>
<br/>








<form name="frmSubtitles" id="frmSubtitles" method="post" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=update_dvdsettings">
<fieldset id="dvdsettings">
<legend class="bold">{$translate.dvd.default}</legend>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td width="13%">{$translate.dvd.format}:</td>
	<td colspan="2">{html_options id=format name=format options=$formatList selected=$selectedFormat}</td>
</tr>
<tr>
	<td nowrap="nowrap">{$translate.dvd.aspect}:</td>
	<td colspan="2">{html_options id=aspect name=aspect options=$aspectList selected=$selectedAspect}</td>
</tr>
<tr>
	<td>{$translate.dvd.region}:</td>
	<td colspan="2">{html_options id=region name=region options=$regionList selected=$selectedRegion}</td>
	
</tr>
<tr>
	<td valign="top">{$translate.dvd.audio}:</td>
	<td>{html_options id=audioAvailable name=audioAvailable size="5" options=$audioList style="width:200px;" onDblClick="addAudio(this.form, 'audioAvailable')" class="input"}</td>
	<td width="60%"><div id="audio" style="height:80px">
	{if is_array($selectedAudio) && count($selectedAudio) > 0}
	<ul>
	{foreach from=$selectedAudio item=i}
		<li class="audio" id="{$i.key}" ondblclick="removeAudio('{$i.key}')">{$i.name}</li>
	{/foreach}
	</ul>
	{/if}
	</div></td>
</tr>
<tr>
	<td valign="top">{$translate.dvd.subtitles}:</td>
	<td>{html_options id=langAvailable name=langAvailable options=$subtitleList size="5" style="width:200px;" onDblClick="addFlag(this.form, 'langAvailable','subs')" class="input"}</td>
	<td><div id="subtitles" style="height:80px;margin-top:5px">
	{if is_array($selectedSubs) && count($selectedSubs) > 0}
	<ul class="flags">
	{foreach from=$selectedSubs item=i name=subs}
	{cycle values="x,y,z" assign=className}<li id="{$i.key}" class="{$className}"><img src="{$i.img}" vspace="2" hspace="2" border="0" ondblclick="removeFlag('{$i.key}',1)" title="{$i.name}" style="vertical-align: middle;"/>{$i.name|truncate:12:".."}</li>
	{if $className eq 'z' or $smarty.foreach.subs.last}<li class="clr"><br class="clr"/></li>{/if}
	{/foreach}
	</ul>
	{/if}
	</div></td>
</tr>
<tr>
	<td valign="top">{$translate.dvd.languages}:</td>
	<td>{html_options id=spokenAvailable name=spokenAvailable options=$subtitleList size="5" style="width:200px;" onDblClick="addFlag(this.form, 'spokenAvailable','langs')" class="input"}</td>
	<td><div id="langspoken" style="height:80px;margin-top:5px">
	{if is_array($selectedSpoken) && count($selectedSpoken) > 0}
	<ul class="flags">
	{foreach from=$selectedSpoken item=i name=spoken}
	{cycle values="x,y,z" assign=className reset=true}<li id="{$i.key}" class="{$className}"><img src="{$i.img}" vspace="2" hspace="2" border="0" ondblclick="removeFlag('{$i.key}',2)" title="{$i.name}" style="vertical-align: middle;"/>{$i.name|truncate:12:".."}</li>
	{if $className eq 'z' or $smarty.foreach.spoken.last}<li class="clr"><br class="clr"/></li>{/if}
	{/foreach}
	</ul>
	{/if}
	</div></td>
</tr>
<tr>
	<td colspan="3" align="right"><input type="submit" value="{$translate.misc.update}"/></td>
</tr>
</table>
</fieldset>
<input type="hidden" name="dvdaudio" id="dvdaudio" value="{$jsAudio}"/>
<input type="hidden" size=40 name="dvdsubs" id="dvdsubs" value="{$jsSubs}"/>
<input type="hidden" size=40 name="dvdlang" id="dvdlang" value="{$jsLang}"/>
</form>
<br/>




{*	We only display the ignore list if more than 1 active users	is using VCD-db. *}
{if $showIgnoreList}
<fieldset id="setIgnorelist" title="{$translate.usersettings.list}">
<legend class="bold">{$translate.usersettings.list}</legend>
<form name="ignore" method="post" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=update_ignorelist">
<input type="hidden" name="id_list" id="id_list"/>

<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td width="44%" valign="top">{$translate.usersettings.desc}</td>
	<td width="10%">{html_options id=available name=available options=$userAvailList size="5" style="width:100px;" onDblClick="moveOver(this.form, 'available', 'choiceBox')"}</td>
	<td width="5%" align="center">
	<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');" class="input" style="margin-bottom:5px;"/>
	<br/>
	<input type="button" value="&lt;&lt;" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
	</td>
	<td width="10%">{html_options id=choiceBox name=choiceBox options=$userSelList size="5" style="width:100px;" class="input"}</td>
	<td align="left" valign="bottom"><input type="submit" value="{$translate.misc.update}" onclick="checkFieldsRaw(this.form, 'choiceBox', 'id_list')"/></td>
</tr>
</table>
</form>
</fieldset>
<br/>
{/if}





<fieldset id="setMetadata" title="{$translate.metadata.my}">
<legend class="bold">{$translate.metadata.my}</legend>
<form name="metadata" method="post" action="{$smarty.server.SCRIPT_NAME}?page=settings&amp;action=addmetadata">
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td valign="top" width="60%">
	{if is_array($myMetadata) && count($myMetadata) > 0}
		<table cellspacing="1" cellpadding="1" border="0" class="displist" width="100%">
		{foreach from=$myMetadata key=id item=i}
			<tr>
				<td>{counter}</td>
				<td>{$i.name}</td>
				<td>{$i.desc}</td>
				<td>{if $i.public}{$translate.metadata.public}{else}{$translate.metadata.private}{/if}</td>
				<td width="2%"><img src="images/icons/edit.png" onclick="invokeEditMetaType({$id})"/></td>
				<td width="2%"><img src="images/icon_del.gif" onclick="deleteMetaType({$id})"/></td>
			</tr>
		{/foreach}
			<tr>
				<td width="2%"><img id="addmetaimg" src="images/icons/add.png" onclick="addMetaType()"/></td>
			</tr>
		</table>
	{else}
		{$translate.metadata.none}
	{/if}
	</td>
	<td valign="top" width="40%">
		<div id="metadatatype" style="display:none;">
			<input type="hidden" name="metadataid"/>
			<table cellpadding="1" cellspacing="1" width="100%" border="0">
			<tr>
				<td>{$translate.metadata.name}: </td>
				<td><input type="text" name="metadataname"/></td>
			</tr>
			<tr>
				<td>{$translate.metadata.desc}: </td>
				<td><input type="text" name="metadatadescription"/></td>
			</tr>
			<tr>
				<td>{$translate.metadata.public}: </td>
				<td><input type="checkbox" name="metadatapublic"/></td>
			</tr>
			<tr>
				<td><input type="submit" name="newmeta" value="{$translate.misc.save}"/></td>
			</tr>
			</table>
		</div>
	</td>

</tr>
</table>
</form>
</fieldset>
<br/>