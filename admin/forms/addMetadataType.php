<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "description"=>"", "owner"=>"", "public"=>"");
	$readonly = "";
	$system = false;	
	$button_value = "Save";

	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		$metaDataTypeObj = SettingsServices::getMetadataType(null, $_GET['recordID']);	
		
		$objArr['name']   = $metaDataTypeObj->getMetadataTypeName();
		$objArr['description'] = $metaDataTypeObj->getMetadataDescription();
		$objArr['owner'] = $metaDataTypeObj->getMetadataTypeLevel();
		$objArr['public'] = $metaDataTypeObj->getMetadataTypePublic();
		$system = $metaDataTypeObj->isSystemObj();
		$readonly = $system?"readonly":"";
		$button_value = "Update";

		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?php echo $_SERVER['REQUEST_URI']?>">
<?php
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$metaDataTypeObj->getMetadataTypeID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" type="text" <?php echo $readonly?> value="<?php echo $objArr['name']?>" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Description:</td>
	<td><input name="description" size="40" <?php echo $readonly?> value="<?php echo $objArr['description']?>" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Owner:</td>
	<td><?php if ($system) {
		echo '<input name="owner_id" readonly value="System">';
	} else {
		createDropDown($users, "owner_id","Select owner","add", $objArr['owner']);
	}
	?>
	</td>
</tr>
<tr>
	<td>Public:</td>
	<td><input type="checkbox" name="public"<?php echo $objArr['public']?" checked":""?>></td>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?php echo $button_value?>" name="<?php echo strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>