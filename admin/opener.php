<?php
	require_once("../classes/includes.php");
	require_once("functions/adminPageFunctions.php");
	
	if (!VCDAuthentication::isAdmin()) {
		VCDException::display("Only administrators have access here");
		print "<script>self.close();</script>";
		exit();
	}
	
	$WORKING_MODE = "";
	$RECORD_ID = "";
	if (isset($_GET['mode']))
		$WORKING_MODE = $_GET['mode'];
	if (isset($_GET['recordID']))
		$RECORD_ID = $_GET['recordID'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">		 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>...........................</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="../includes/css/admin.css" type="text/css" media="all" />
<script src="../includes/js/admin.js" type="text/javascript"></script>

</head>
<body onload="window.focus()">
<?php 
	if (strcmp($WORKING_MODE,"changePassword") == 0) {
		$USERClass = new vcd_user();
		$vuser = $USERClass->getUserByID($RECORD_ID);
		
		if (isset($_POST['save'])) {
			$new_pass = $_POST['password'];
			$vuser->setPassword(md5($new_pass));
			$USERClass->updateUser($vuser);
			refreshAndClose();
			exit();
		}
				
		?>

		<form name="password" method="post">
		<table cellspacing=1 cellpadding=1 border=0 class="add">
		<tr>
			<td colspan="2">Change password for <strong><?php echo $vuser->getUsername() ?></strong><br/><br/></td>
		</tr>
		<tr>
			<td width="5%">Password:</td>
			<td><input type="password" name="password" class="inp" maxlength="20"></td>
		</tr>
		<tr>
			<td valign="top">Confirm password:</td>
			<td><input type="password" name="password2" maxlength="20" class="inp">
				&nbsp; <input type="submit" value="Submit" class="add" name="save" class="save" onclick="return checkPasses(this.form.password.value, this.form.password2.value)">
			</td>
		</tr>
		</table>

		
		</form> <?php
	}
	
	if (strcmp($WORKING_MODE,"changeRole") == 0) {
		$USERClass = new vcd_user();
		$roleObjArr = $USERClass->getAllUserRoles();
		$vuser = $USERClass->getUserByID($RECORD_ID);
		
		if (isset($_POST['save'])) {
			$new_role = $_POST['roles'];
			if (strcmp($new_role, "null") != 0) {
				$vuser->setRoleID($new_role);
				$USERClass->updateUser($vuser);
				
			}
			
			refreshAndClose();
			exit();
			
		}
		
		
		?>
		<div class="add">
		<form name="roles" method="post">
		Change role for <strong><?php echo $vuser->getUsername() ?></strong><br/><br/>
		<?php
		createDropDown($roleObjArr,"roles","Select user role","add");
		?>&nbsp; <input type="submit" value="Submit" class="add" name="save"></form></div> <?php
		
	}
	
?>

</body>
</html>

