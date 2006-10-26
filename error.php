<? 
	include_once('classes/VCDConstants.php');
	include_once('functions/WebFunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><title>VCD-db</title>
<link rel="stylesheet" type="text/css" href="<?=STYLE?>style.css"/>
<script src="includes/js/main.js" type="text/javascript"></script>
</head>
<body>


<h3 align="center" style="margin-right:100px">
<img src="images/logotest.gif" width="187" align="center" height="118" alt="" border="0"/>
<br/>
Oops database error
</h3>

<p align="center" style="margin-right:100px">


<strong>Database Access misconfigured.</strong>
<br/>
<span style="color:red">
	Cannot procceed without database access<br/>
	Check the <u>connection settings</u> to solve the problem.
</span>
<? 
	if (checkInstall()) {
		?>
		<br/><br/>
		<h3 align="center" style="margin-right:100px">For a new installation <a href="setup/">go here</a></h3>
		<?	
	}
?>





</p>



</body>
</html>