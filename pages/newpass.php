<? 
	$username = "";
	$email = "";
	$message = "";
	
	if (isset($_POST['username']))  {
		$username = $_POST['username'];
	}
	
	if (isset($_POST['email']))  {
		$email = $_POST['email'];
	}
	
	
	$USERClass = VCDClassFactory::getInstance('vcd_user');
	$obj = $USERClass->getUserByUsername($username);
	if ($obj instanceof userObj ) {
		if (strcmp($obj->getEmail(),$email) == 0) {
			
			$newpass = substr(VCDUtils::generateUniqueId(),0, 6);
			$md5newpass = md5($newpass);
			
			$body  = "Request for new password was made for your account from computer: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
			$body .= $obj->getFullname() . ", your new password as requested is ".$newpass . "\n";
			$body .= "\nGood luck, (The VCD-db)";
			
			if ((VCDUtils::sendMail($email, "New password as requested",$body))) {
				$message  = "New password has been mailed to " . $email . "<br/>";
				$message .= "You can change the password next time you log in.";	
				
				// actually update the password since we now know that the email was successfully sent
				$obj->setPassword($md5newpass);
				$USERClass->updateUser($obj);
				
			} else {
				$message = "The site owner has wrong mail settings defined, cannot sent password";
			}
			
						
			
			
			
			
		} else {
			$message = "User and email combination not right.";
		}
	} else {
		$message = "User " . $username . " does not exist";
	}
	
?>

<h1>Request new password</h1>


<p class="bold">
	<?=$message?>
	<br/><br/>
	<a href="./?">Back to front page</a>
</p>


