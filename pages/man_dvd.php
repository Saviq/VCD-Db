<?
	$dvdObj = new dvdObj();
	if (!is_null($arrMyMeta)) {
		$arrDVDMetaObj = metadataTypeObj::filterByMediaTypeID($arrMyMeta, $current_dvd);
		$arrDVDMetaObj = metadataTypeObj::getDVDMeta($arrDVDMetaObj);
	}

	$dvd_region = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDREGION);
	$dvd_format = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDFORMAT);
	$dvd_aspect = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDASPECT);
	$dvd_audio =  VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDAUDIO);
	$dvd_subs =   VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDSUBS);
	
	if (strcmp($dvd_audio, "") != 0) {
		$dvd_audio = explode('#', $dvd_audio);
	}
	
	if (strcmp($dvd_subs, "") != 0) {
		$dvd_subs = explode('#', $dvd_subs);
	}
	

?>
<input type="hidden" id="selected_dvd" name="selected_dvd"/>
<input type="hidden" id="current_dvd" name="current_dvd" value="<?=$current_dvd?>"/>
<input type="hidden" id="audio_list" name="audio_list" value=""/>
<input type="hidden" id="sub_list" name="sub_list" value=""/>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
	<td class="tblb"><?= $language->show('M_MEDIATYPE')?>:</td>
	<td><? createDVDDropdown($arrCopies['mediaTypes'], $current_dvd); ?></td>
</tr>
<tr>
	<td class="tblb"><?= $language->show('DVD_REGION')?>:</td>
	<td><select name="dvdregion" class="input">
		<? foreach ($dvdObj->getRegionList() as $key => $value) {
			if ($key == $dvd_region) {
				print "<option value=\"{$key}\" selected=\"selected\">{$key}. {$value}</option>";
			} else {
				print "<option value=\"{$key}\">{$key}. {$value}</option>";
			}
		}
		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb"><?= $language->show('DVD_FORMAT')?>:</td>
	<td><select name="dvdformat" class="input">
		<? foreach ($dvdObj->getVideoFormats() as $key => $value) {
			if ($key == $dvd_format) {
				print "<option value=\"{$key}\" selected=\"selected\">{$value}</option>";
			} else {
				print "<option value=\"{$key}\">{$value}</option>";
			}

		}

		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb"><?= $language->show('DVD_ASPECT')?>:</td>
	<td><select name="dvdaspect" class="input">
		<? foreach ($dvdObj->getAspectRatios() as $key => $value) {
			if ($key == $dvd_aspect) {
				print "<option value=\"{$key}\" selected=\"selected\">{$value}</option>";
			} else {
				print "<option value=\"{$key}\">{$value}</option>";
			}
		}


		?>
		</select>

	</td>
</tr>

<tr>
	<td class="tblb" valign="top"><?= $language->show('DVD_AUDIO')?>:</td>
	<td valign="top">

	<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>
					<select name="audioAvailable" id="audioAvailable" size="5" style="width:175px;" onDblClick="moveOver(this.form, 'audioAvailable', 'audioChoices')" class="input">
					<?
					foreach ($dvdObj->getAudioList() as $key => $value) {
						if (!(is_array($dvd_audio) && in_array($key, $dvd_audio))) {
							print "<option value=\"{$key}\">{$value}</option>";
						}
					}
					?>
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'audioAvailable', 'audioChoices');" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form, 'audioAvailable', 'audioChoices');" class="input"/>
				</td>
				<td>
					<select multiple name="audioChoices" id="audioChoices" style="width:175px;" size="5" onDblClick="removeMe(this.form, 'audioAvailable', 'audioChoices')" class="input">
					<?
						if (is_array($dvd_audio)) {
							foreach ($dvd_audio as $item) {
								print "<option value=\"{$item}\">{$dvdObj->getAudio($item)}</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			</table>


	</td>
</tr>

<tr>
	<td class="tblb" valign="top"><?= $language->show('DVD_SUBTITLES')?>:</td>
	<td valign="top">
	<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>
					<select name="langAvailable" id="langAvailable" size="5" style="width:175px;" onDblClick="moveOver(this.form, 'langAvailable', 'langChoices')" class="input">
					<?
					foreach ($dvdObj->getLanguageList(false) as $key => $value) {
						if (!(is_array($dvd_subs) && in_array($key, $dvd_subs))) { 
							print "<option value=\"{$key}\">{$value}</option>";
						}
					}
					?>
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'langAvailable', 'langChoices');" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form,'langAvailable', 'langChoices');" class="input"/>
				</td>
				<td>
					<select multiple name="langChoices" id="langChoices" style="width:175px;" size="5" onDblClick="removeMe(this.form,'langAvailable', 'langChoices')" class="input">
					<?
						if (is_array($dvd_subs)) {
							foreach ($dvd_subs as $item) {
								print "<option value=\"{$item}\">{$dvdObj->getLanguage($item)}</option>";
							}
						} else {
							foreach ($dvdObj->getDefaultSubtitles() as $key => $value) {
								print "<option value=\"{$key}\">{$value}</option>";
							}	
						}
					
						
					?>

					</select>
				</td>
			</tr>
			</table>

	</td>
</tr>


</table>
