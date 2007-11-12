<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "parent"=>"", "description"=>"");
	$readonly = "";	
	$button_value = "Save";
	$parent_id = "";

	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		$mediaTypeObj = SettingsServices::getMediaTypeByID($_GET['recordID']);	
		
		
		$objArr['name']   = $mediaTypeObj->getName();
		$objArr['parent'] = $mediaTypeObj->getParentID();
		$objArr['description'] = $mediaTypeObj->getDescription();
		$parent_id = $mediaTypeObj->getParentID();
		$readonly = "readonly";	
		$button_value = "Update";

		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?php echo $_SERVER['REQUEST_URI']?>">
<?php
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$mediaTypeObj->getmediaTypeID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" type="text" <?php echo $readonly?> value="<?php echo $objArr['name']?>" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Parent:</td>
	<td><?php if (strcmp($WORKING_MODE, "edit") != 0) {
		createDropDown($mtypes, "parent","Select parent","add", $parent_id); print " (if any)";
	} else {
		
		if (is_numeric($mediaTypeObj->getParentID())) {
			$parentObj = SettingsServices::getMediaTypeByID($mediaTypeObj->getParentID());
			print $parentObj->getName();	
		} else {
			print "None";
		}
	}
	?>
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td><input name="description" size="40" value="<?php echo $objArr['description']?>" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?php echo $button_value?>" name="<?php echo strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>