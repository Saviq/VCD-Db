<script type="text/javascript" src="includes/js/json.js"></script> 
<script type="text/javascript" src="includes/js/ajax.js"></script> 
<script type="text/javascript"> 
{php}
// include the Ajax javascript
global $ajaxClient;
echo $ajaxClient->getJavaScript();
{/php}
</script>

<form name="user" method="post" action="{$smarty.server.SCRIPT_NAME}?page=private&o=settings&action=updateprofile">
<h1>{$translate.menu.settings}</h1>
<fieldset id="settings" title="{$translate.menu.settings}">
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="displist">
<tr>
	<td width="35%">{$translate.register_fullname}:</td>
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
	<td><input type="checkbox" name="property[]" class="nof" value="{$id}" {$i.checked}>&nbsp;{$i.extra}</td>
</tr>
{/foreach}
<tr>
	<td><div class="info">{$message}</div>&nbsp;</td>
	<td><input type="submit" value="{$translate.misc.update}"></td>
</tr>
</table>
</form>
</fieldset>
<br/>



<fieldset id="pagelook" title="{$translate.usersettings.pagelook}">
<legend class="bold">{$translate.usersettings.pagelook}</legend>

<p style="padding:0px 0px 2px 2px">
&nbsp;{$translate.usersettings.pagemode}
{html_options id=template name=template values=$templates output=$templates selected=$smarty.cookies.template onchange="switchTemplate(this.options[this.selectedIndex].value)"}
</p>
</fieldset>



{if is_array($borrowerList) && count($borrowerList) > 0}
<fieldset id="mainset" title="{$translate.mymovies.friends}">
<legend class="bold">{$translate.mymovies.friends}</legend>
<table cellpadding="1" cellspacing="1" width="100%" border="0">
<tr>
	<td>
	<form name="borrowForm">
	{html_options id=borrowers name=borrowers options=$borrowerList selected=$selectedBorrower}
	&nbsp;<input type="button" value="{$translate.misc.edit}" onclick="changeBorrower(this.form)">
	<img src="images/icon_del.gif" hspace="4" alt="" align="absmiddle" onclick="deleteBorrower(this.form)" border="0"/>
	</form>
	</td>
	{if $editBorrower}
	<td nowrap="nowrap">
	<form name="update_borrower" action="exec_form.php?action=edit_borrower" method="post">
	<table cellpadding="0" cellspacing="0" border="0" class="list">
		<tr>
			<td>{$translate.loan.name}:</td>
			<td><input type="text" size="12" name="borrower_name" value="{$borrowerName}"/></td>
			<td>{$translate.register.email}:</td>
			<td><input type="text" size="16" name="borrower_email" value="{$borrowerEmail}"/></td>
			<td>&nbsp;</td>
			<td><input type="submit" value="{$translate.misc_update}" id="vista" onclick="return val_borrower(this.form)"/></td>
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




<fieldset id="mainset" title="{$translate.rss.title}">
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
<p><input type="button" value="{$translate.rss_add}" onclick="addFeed()"/></p>
</fieldset>
<br/>





<fieldset id="mainset" title="{$translate.usersettings.custom}">
<legend class="bold">{$translate.usersettings.custom}</legend>
<form name="choiceForm" method="post" action="exec_form.php?action=edit_frontpage">
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
		&nbsp; <input type="button" value="{$translate.rss.add}" onclick="addPrivateFeed()"/>
	</td>
</tr>
</table>
</form>
</fieldset>
<br/>








<form name="frmSubtitles" id="frmSubtitles" method="post" action="exec_form.php?action=update_dvdsettings">
<fieldset id="dvdsettings"  name="defaultdvd">
<legend class="bold">Default DVD settings</legend>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td width="13%">{$translate.dvd.format}:</td>
	<td colspan="2">{html_options id=format name=format options=$formatList selected=$selectedFormat}</td>
</tr>
<tr>
	<td wrap="nowrap">{$translate.dvd.aspect}:</td>
	<td colspan="2">{html_options id=aspect name=aspect options=$aspectList selected=$selectedAspect}</td>
</tr>
<tr>
	<td>{$translate.dvd.region}:</td>
	<td colspan="2">{html_options id=region name=region options=$regionList selected=$selectedRegion}</td>
	
</tr>
<tr>
	<td valign="top">{$translate.dvd.audio}:</td>
	<td>{html_options id=audioAvailable name=audioAvailable size="5" options=$audioList style="width:200px;" onDblClick="addAudio(this.form, 'audioAvailable')" class="input"}</td>
	<td width="60%"><div id="audio" style="height:80px";>
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
	<td>{html_options id=langAvailable name=langAvailable options=$subtitleList selected=$selectedRegion size="5" style="width:200px;" onDblClick="addSubtitle(this.form, 'langAvailable')" class="input"}</td>
	<td><div id="subtitles" style="height:80px;margin-top:5px";>
	{if is_array($selectedSubs) && count($selectedSubs) > 0}
	<ul>
	{foreach from=$selectedSubs item=i}
		<li id="{$i.key}"><img src="{$i.img}" vspace="2" hspace="2" border="0" ondblclick="removeSub('{$i.key}')" title="{$i.name}" align="absmiddle">{$i.name|truncate:12:".."}</li>
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
</form>
<br/>




{*	We only display the ignore list if more than 1 active users	is using VCD-db. *}
{if $showIgnoreList}
<fieldset id="mainset" title="{$translate.usersettings.list}">
<legend class="bold">{$translate.usersettings.list}</legend>
<form name="ignore" method="post" action="exec_form.php?action=update_ignorelist">
<input type="hidden" name="id_list" id="id_list"/>

<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td width="44%" valign="top"">{$translate.usersettings.desc}</td>
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





<fieldset id="mainset" title="{$translate.metadata.my}">
<legend class="bold">{$translate.metadata.my}</legend>
<form name="metadata" method="post" action="exec_form.php?action=addmetadata">
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td valign="top" width="60%">
	{if is_array($metadata) && count($metadata) > 0}
		<table cellspacing="1" cellpadding="1" border="0" class="displist" width="100%">
		{foreach from=$metadata key=id item=i}
			<tr>
				<td>{counter}</td>
				<td>{$i.name}</td>
				<td>{$i.desc}</td>
				<td width="2%"><img src="images/icon_del.gif" onclick="deleteMetaType({$id})"/></td>
			</tr>
		{/foreach}
		</table>
	{else}
		{$translate.metadata.none}
	{/if}
	</td>
	<td valign="top" width="40%">
		<table cellpadding="1" cellspacing="1" width="100%" border="0">
		<tr>
			<td>{$translate.metadata.name}: </td>
			<td><input type="text" name="metadataname"/></td>
		</tr>
		<tr>
			<td>{$translate.metadata.desc}: </td>
			<td><input type="text" name="metadatadescription"/> &nbsp; <input type="submit" name="newmeta" value="{$translate.misc.save}"/></td>
		</tr>
		</table>
	</td>

</tr>
</table>
</form>
</fieldset>
</br>