<?
	$dvdObj = new dvdObj();
?>
<input type="hidden" id="selected_dvd" name="selected_dvd"/>
<input type="hidden" id="current_dvd" name="current_dvd" value="<?=$current_dvd?>"/>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
	<td class="tblb">Media:</td>
	<td><? createDVDDropdown($arrCopies['mediaTypes'], $current_dvd); ?></td>
</tr>
<tr>
	<td class="tblb">Region:</td>
	<td><select name="dvdregion" class="input">
		<? foreach ($dvdObj->getRegionList() as $key => $value)
			print "<option value=\"{$key}\">{$key}. {$value}</option>";

		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb">Format:</td>
	<td><select name="dvdformat" class="input">
		<? foreach ($dvdObj->getVideoFormats() as $key => $value)
			print "<option value=\"{$key}\">{$value}</option>";

		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb">Aspect ratio:</td>
	<td><select name="dvdaspect" class="input">
		<? foreach ($dvdObj->getAspectRatios() as $key => $value)
			print "<option value=\"{$key}\">{$value}</option>";

		?>
		</select>

	</td>
</tr>

<tr>
	<td class="tblb" valign="top">Audio:</td>
	<td valign="top">

	<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>
					<select name="audioAvailable" size="5" style="width:175px;" onDblClick="moveOver(this.form, 'audioAvailable', 'audioChoices')" class="input">
					<?
					foreach ($dvdObj->getAudioList() as $key => $value) {
						print "<option value=\"{$key}\">{$value}</option>";
					}
					?>
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'audioAvailable', 'audioChoices');" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form, 'audioAvailable', 'audioChoices');" class="input"/>
				</td>
				<td>
					<select multiple name="audioChoices" style="width:175px;" size="5" onDblClick="removeMe(this.form, 'audioAvailable', 'audioChoices')" class="input">
					<?

					?>

					</select>
				</td>
			</tr>
			</table>


	</td>
</tr>

<tr>
	<td class="tblb" valign="top">Subtitles:</td>
	<td valign="top">
	<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>
					<select name="langAvailable" size="5" style="width:175px;" onDblClick="moveOver(this.form, 'langAvailable', 'langChoices')" class="input">
					<?
					foreach ($dvdObj->getLanguageList(false) as $key => $value) {
						print "<option value=\"{$key}\">{$value}</option>";
					}
					?>
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'langAvailable', 'langChoices');" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form,'langAvailable', 'langChoices');" class="input"/>
				</td>
				<td>
					<select multiple name="langChoices" style="width:175px;" size="5" onDblClick="removeMe(this.form,'langAvailable', 'langChoices')" class="input">
					<?
						foreach ($dvdObj->getDefaultSubtitles() as $key => $value) {
							print "<option value=\"{$key}\">{$value}</option>";
					}
					?>

					</select>
				</td>
			</tr>
			</table>

	</td>
</tr>


</table>
