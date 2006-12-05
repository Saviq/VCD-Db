<?php 

	if (strcmp($WORKING_MODE, "edit") != 0) {
		?>
		<div id="newObj" style="display: none;">
		<strong>Use the edit icon to change values</strong><br/><br/>
		</div>
		<?

	} else {

	$coverTypes = CoverServices::getAllCoverTypes();
	$mediaObj = SettingsServices::getMediaTypeByID($_GET['recordID']);
	
	$covers = CoverServices::getCDcoverTypesOnMediaType($mediaObj->getmediaTypeID());
	
	// remove the already selected from the available list
	if (is_array($covers) && sizeof($covers) > 0) {
		$filtererCovers = array();
		foreach ($coverTypes as $cobj) {
			if (!in_array ($cobj, $covers)) {
			    array_push($filtererCovers, $cobj);
			}
		}
		$coverTypes = $filtererCovers;
	}

?>


<div id="newObj" style="display: none;">
<form name="new" method="POST">
<input type="hidden" name="media_id" value="<?=$mediaObj->getmediaTypeID()?>">
<table class="add">
<tr>
	<td>Media Type:</td>
	<td><input name="name" type="text" id="name" value="<?=$mediaObj->getName()?>" onFocus="setBorder(this)" onBlur="clearBorder(this)" readonly></td>
</tr>
<tr>
	<td valign="top" nowrap>Allowed types:</td>
	<td valign="top">
		
			<input type="hidden" name="id_list">
			<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td><div align="center">Available cover types</div>
				<select name="available" size=8 style="width:150px;" onDblClick="moveOver(this.form);">
					<?php 
						foreach ($coverTypes as $obj) {
							$arr = $obj->getList();
							print "<option value=\"".$arr['id']."\">".$arr['name']."</option>";
						}
						unset($arr);
					?>					
				</select>
				</td>
				<td>
				<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);" class="inp" style="margin-bottom:5px;"/><br/>
				<input type="button" value="<<" onclick="removeMe(this.form);" class="inp"/>
				</td>
				<td><div align="center">Selected cover types</div>
				<select multiple name="choiceBox" style="width:150px;" size="8" class="input" onDblClick="removeMe(this.form)">
					<?php
						foreach ($covers as $obj) { 
							$arr = $obj->getList();
							print "<option value=\"".$arr['id']."\">".$arr['name']."</option>";
						}
						unset($arr);
					?>
				</select>
			</td>			
			</tr>
			</table>
	
	
	
	</td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="Save" name="save" class="save" onClick="return checkFields(this.form)"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>

<? } ?>