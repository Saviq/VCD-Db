
<input type="hidden" id="selected_dvd" name="selected_dvd"/>
<input type="hidden" id="current_dvd" name="current_dvd" value=""/>
<input type="hidden" id="audio_list" name="audio_list" value=""/>
<input type="hidden" id="sub_list" name="sub_list" value=""/>

<table width="100%" cellpadding="0" cellspacing="1" border="0">
<tr>
	<td class="tblb">{$translate.movie.mediatype}:</td>
	<td>
	{if is_array($itemDvdTypeList)}
	{html_options id=dvdtype name=dvdtype options=$itemDvdTypeList selected=$itemDvdType class="input"}</td>
	{else}
	{$itemDvdTypeList}
	{/if}
	
</tr>
<tr>
	<td class="tblb">{$translate.dvd.region}:</td>
	<td>{html_options id=dvdregion name=dvdregion options=$itemRegionList selected=$itemRegion class="input"}</td>
</tr>
<tr>
	<td class="tblb">{$translate.dvd.format}:</td>
	<td>{html_options id=dvdformat name=dvdformat options=$itemFormatList selected=$itemFormat class="input"}</td>
</tr>

<tr>
	<td class="tblb">{$translate.dvd.aspect}:</td>
	<td>{html_options id=dvdaspect name=dvdaspect options=$itemAspectList selected=$itemAspect class="input"}</td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.dvd.audio}:</td>
	<td valign="top">

	<table cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td>{html_options id=audioAvailable name=audioAvailable size="5" options=$itemAudioList class="input" style="width:175px;" onDblClick="moveOver(this.form, 'audioAvailable', 'audioChoices')"}</td>
		<td>
			<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'audioAvailable', 'audioChoices');" class="input" style="margin-bottom:5px;"/>
			<br/>
			<input type="button" value="<<" onclick="removeMe(this.form, 'audioAvailable', 'audioChoices');" class="input"/>
		</td>
		<td>{html_options id=audioChoices name=audioChoices size="5" options=$itemAudioListSelected class="input" style="width:175px;" onDblClick="removeMe(this.form, 'audioAvailable', 'audioChoices')"}</td>
	</tr>
	</table>

	</td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.dvd.subtitles}:</td>
	<td valign="top">
	
	<table cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td>{html_options id=langAvailable name=langAvailable size="5" options=$itemSubtitleList class="input" style="width:175px;" onDblClick="moveOver(this.form, 'langAvailable', 'langChoices')"}</td>
		<td>
			<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'langAvailable', 'langChoices');" class="input" style="margin-bottom:5px;"/>
			<br/>
			<input type="button" value="<<" onclick="removeMe(this.form,'langAvailable', 'langChoices');" class="input"/>
		</td>
		<td>{html_options id=langChoices name=langChoices options=$itemSubtitleListSelected class="input" style="width:175px;" size="5" onDblClick="removeMe(this.form,'langAvailable', 'langChoices')"}</td>
	</tr>
	</table>

	</td>
</tr>
</table>
