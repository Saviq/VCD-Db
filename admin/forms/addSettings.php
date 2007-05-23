<?php 
	
	$objArr = array ("id"=>"", "key"=>"", "value"=>"", "description"=>"", "protected"=>"0");
	$readonly = "";	
	$button_value = "Save";
	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		
		$settingsObj = SettingsServices::getSettingsByID($_GET['recordID']);	
		$objArr['key']   = $settingsObj->getKey();
		$objArr['value'] = $settingsObj->getValue();
		$objArr['description'] = $settingsObj->getDescription();
		$objArr['type'] = $settingsObj->getType();
		
		if ($settingsObj->isProtected()) {
			$readonly = "readonly";
		}
		
		$button_value = "Update";
		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?= $_SERVER['REQUEST_URI']?>">
<? 
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$settingsObj->getID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Key:</td>
	<td><input name="key" type="text" size="30" value="<?=$objArr['key']?>" <?=$readonly?> onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Value:</td>
	<td>
	<? if (isset($objArr['type']) && strcmp($objArr['type'], 'bool') == 0) { 
			$yesSelected = "";
			$noSelected = "";	
			if ($objArr['value'] == 0) {
				$noSelected = "selected";
			}
		
	?>
		<select name="value" onFocus="setBorder(this)" onBlur="clearBorder(this)">	
			<option value="1" <?=$yesSelected?>>True</option>
			<option value="0" <?=$noSelected?>>False</option>
		</select>
	<? } else {?>
		<input name="value" type="text" size="30" value="<?=$objArr['value']?>" onFocus="setBorder(this)" onBlur="clearBorder(this)">	
	<?}?>
	
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td><input name="description" size="60" value="<?=$objArr['description']?>" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Protect key:</td>
	<td>
	<?php if (strcmp($readonly,"readonly") != 0) { ?>
	<input name="protect" type="checkbox" value="1" onFocus="setBorder(this)" onBlur="clearBorder(this)" readonly>
	(Protected keys can not be deleted from the database)
	<?php } else {print "Key is protected";} ?>
	</td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?=$button_value?>" name="<?=strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>
<?php 
	unset($objArr);
?>