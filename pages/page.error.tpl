<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="includes/templates/default/style.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
</head>
<body>

<h3 align="center" style="margin-right:100px">
	<img src="images/logotest.gif" width="187" align="center" height="118" alt="" border="0"/>
	<br/>
	Oops an exception occurred ..
</h3>

<p align="center" style="margin-right:100px">
{if $smarty.get.type eq 'wscredentials'}
<span style="color:red">
Invalid credentials for remote VCD-db via webservice.
</span>
{else}
<strong>Database Access misconfigured.</strong>
<br/>
<span style="color:red">
	Cannot procceed without database access<br/>
	Check the <u>connection settings</u> to solve the problem.
</span>
{if $showSetup}
	<br/><br/>
	<h3 align="center" style="margin-right:100px">For a new installation <a href="setup/">go here</a></h3>
{/if}
{/if}
</p>
</body>
</html>