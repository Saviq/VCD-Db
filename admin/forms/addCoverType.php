<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "description"=>"");
	$readonly = "";	
	$button_value = "Save";
	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		
		$coverTypeObj = $CTClass->getCoverTypeById($_GET['recordID']);	
		$objArr['name']   = $coverTypeObj->getCoverTypeName();
		$objArr['description'] = $coverTypeObj->getCoverTypeDescription();
		
		$button_value = "Update";
		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST">
<? 
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$coverTypeObj->getCoverTypeID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" type="text" id="name" value="<?=$objArr['name']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)" <?php if (strcmp($WORKING_MODE, "edit") == 0){ print "readonly"; }?>></td>
</tr>
<tr>
	<td>Description:</td>
	<td><input name="description" type="text" id="description" value="<?=$objArr['description']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?=$button_value?>" name="<?=strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>