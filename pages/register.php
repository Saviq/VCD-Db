<? 
	
	global $language;
	$USERClass = VCDClassFactory::getInstance("vcd_user");
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	$allow_registration = $SETTINGSClass->getSettingsByKey("ALLOW_REGISTRATION");

	/* Process the registration form */
	if (sizeof($_POST) > 0) {
		$name = $_POST['name'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = md5($_POST['password']);
		
		$insArr = array("", $username, $password, $name, $email, "", "", "", "");
		$userObj = new userObj($insArr);
		
		// find selected properties
		if (isset($_POST['props'])) {
			foreach ($_POST['props'] as $propKey) {
				$propObj = $USERClass->getPropertyByKey($propKey);
				$userObj->addProperty($propObj);	
			}
		}
		
		
		
		if ($USERClass->addUser($userObj)) {
			// Try to send mail to user with registration details.
			$body = sprintf($language->show('MAIL_REGISTER'), $name, $username, $_POST['password'], $SETTINGSClass->getSettingsByKey('SITE_HOME'));
			sendMail($email, "VCD-db " . $language->show('REGISTER_TITLE'), $body, true);	
		
			// save the user in session
			$_SESSION['new_user'] = $userObj;
			redirect('./?page=welcome');
		} 
	}

/* 
	Display and process registration if allowed by sysadmin
*/
print "<h1>".$language->show('REGISTER_TITLE')."</h1>";

if (!$allow_registration) {
	print "<p>".$language->show('REGISTER_DISABLED')."</p>";
} else {

?>

<form name="register" method="post" action="./?page=register">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="45%"><?=$language->show('REGISTER_FULLNAME') ?> :</td>
	<td><input type="text" name="name"/></td>
</tr>
<tr>
	<td><?=$language->show('LOGIN_USERNAME') ?> :</td>
	<td><input type="text" name="username"/></td>
</tr>
<tr>
	<td><?=$language->show('REGISTER_EMAIL') ?> :</td>
	<td><input type="text" name="email"/></td>
</tr>
<tr>
	<td><?=$language->show('LOGIN_PASSWORD') ?> :</td>
	<td><input type="password" name="password"/></td>
</tr>
<tr>
	<td><?=$language->show('REGISTER_AGAIN') ?> :</td>
	<td><input type="password" name="password2"/></td>
</tr>
<? /* 
	Get all the custom user properties
   */
	$props = $USERClass->getAllProperties();
	foreach ($props as $propertyObj) {
		
		// Check if translation for property exists
		$langkey = "PRO_".$propertyObj->getpropertyName();
		$description = $language->show($langkey);
		if (strcmp($description, "undefined") == 0) {
			$description = $propertyObj->getpropertyDescription();
		}
		
		
		print "<tr>
					<td nowrap=\"nowrap\">".$description."</td>
					<td><input type=\"checkbox\" class=\"nof\" name=\"props[]\" value=\"".$propertyObj->getpropertyName()."\"/></td>
		       </tr>";
	}
   
?>
<tr>
	<td></td>
	<td><input type="submit" value="<?=$language->show('MENU_SUBMIT') ?>" onclick="return checkReg(this.form)"/></td>
</tr>
</table>
</form>

<? } ?>