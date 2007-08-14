<? 
define('BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
require_once(BASE .'/classes/includes.php');
?>
<html>
<head>
	<title>VCD-db Websevices</title>
</head>
<body>
<h2>The following services are available.</h2>
<ul>
	<li><a href="cover.php">Cover services</a> - <a href="cover.php?wsdl">WSDL</a></li>
	<li><a href="movie.php">Movie services</a> - <a href="movie.php?wsdl">WSDL</a></li>
	<li><a href="user.php">User services</a> - <a href="user.php?wsdl">WSDL</a></li>
	<li><a href="settings.php">Settings services</a> - <a href="settings.php?wsdl">WSDL</a></li>
	<li><a href="pornstar.php">Pornstar services</a> - <a href="pornstar.php?wsdl">WSDL</a></li>
</ul>


<br>

More details in the <a href="http://vcddb.konni.com/api/">VCD-db API</a>.

</body>
</html>