<? 
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}
	$language = new VCDLanguage();
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	VCDClassFactory::put($language, true);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body onload="window.focus()">
<h2><?=VCDLanguage::translate('loan.registeruser')?></h2>
<form name="borrower" action="../exec_form.php?action=borrower" method="post" onsubmit="return submitBorrower(this)">
<table cellspacing="1" cellpadding="1" border="0" class="plain">
<tr>
	<td><?=VCDLanguage::translate('loan.name')?>:</td>
	<td><input type="text" name="borrower_name"/></td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('register.email')?>:</td>
	<td><input type="text" name="borrower_email"/></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="<?=VCDLanguage::translate('misc.confirm')?>" id="vista" onclick="return val_borrower(this.form)"/></td>
</tr>
</table>
</form>
</body>
</html>