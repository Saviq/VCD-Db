<? 
	$dvdObj = new dvdObj();	
?>

<p>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
	<td class="tblb">Region:</td>
	<td><select name="region" class="input">
		<? foreach ($dvdObj->getRegionList() as $key => $value)
			print "<option value=\"{$key}\">{$key}. {$value}</option>";
		
		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb">Format:</td>
	<td><select name="region" class="input">
		<? foreach ($dvdObj->getVideoFormats() as $key => $value)
			print "<option value=\"{$key}\">{$value}</option>";
		
		?>
		</select>
	</td>
</tr>

<tr>
	<td class="tblb">Aspect ratio:</td>
	<td><select name="region" class="input">
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
					<select name="available" size="5" style="width:175px;" onDblClick="moveOver(this.form)" class="input">
					<? 
					foreach ($dvdObj->getAudioList() as $key => $value) {
						print "<option value=\"{$key}\">{$value}</option>";
					}
					?>	
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form);" class="input"/>
				</td>
				<td>
					<select multiple name="choiceBox" style="width:175px;" size="5" onDblClick="removeMe(this.form)" class="input">
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
					<select name="available2" size="5" style="width:175px;" onDblClick="moveOver(this.form)" class="input">
					<? 
					foreach ($dvdObj->getLanguageList(false) as $key => $value) {
						print "<option value=\"{$key}\">{$value}</option>";
					}
					?>	
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form);" class="input"/>
				</td>
				<td>
					<select multiple name="choiceBox2" style="width:175px;" size="5" onDblClick="removeMe(this.form)" class="input">
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
</p>