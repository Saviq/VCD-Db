<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Resolver</title>
	<style>
    body {
 	background: #F6F6F6;
	font: 11px/1.2 Verdana, Arial, Helvetica, sans-serif;
	margin: 1px;
	padding: 1px;
}
    </style>

</head>
<body leftmargin="0" topmargin="0">
<?php
echo gethostbyaddr($_GET['ip']);
?>
</body>
</html>
