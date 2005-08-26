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
		$mediaTypeObj = $SETTINGSclass->getMediaTypeByID($_GET['recordID']);	
		
		
		$objArr['name']   = $mediaTypeObj->getName();
		$objArr['parent'] = $mediaTypeObj->getParentID();
		$objArr['description'] = $mediaTypeObj->getDescription();
		$parent_id = $mediaTypeObj->getParentID();
		$readonly = "readonly";	
		$button_value = "Update";

		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST">
<? 
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$mediaTypeObj->getmediaTypeID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" type="text" <?=$readonly?> value="<?=$objArr['name']?>" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Parent:</td>
	<td><? if (strcmp($WORKING_MODE, "edit") != 0) {
		createDropDown($mtypes, "parent","Select parent","add", $parent_id); print " (if any)";
	} else {
		$parentObj = $SETTINGSclass->getMediaTypeByID($mediaTypeObj->getParentID());
		if ($parentObj instanceof mediaTypeObj)
			print $parentObj->getName();
		else 	
			print "None";
	}
	?>
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td><input name="description" size="40" value="<?=$objArr['description']?>" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?=$button_value?>" name="<?=strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>