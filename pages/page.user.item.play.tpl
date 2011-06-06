<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
{literal} 
<script>
function doRun(message) {
	try {
		var obj = document.getElementById('vcddb');
		if (!obj.launchFile(message)) {
			alert('Could not play file.  Something went wrong');
		}
	} catch (ex) {
		alert('Problem with the launcher applet.\n'+ex.Message);
	}
}
</script>
{/literal} 


</head>
<body>

<applet id="vcddb" name="VCD-db Launcher" width="0" height="0" 
	code="com.konni.vcddb.vcddbLauncher" archive="includes/bin/vcddb-launcher.jar">
<param name="file" value="{$itemFilename}"></param>
</applet>


{if $isPlayable} 
<script>
	var message = '{$itemLaunchCommand}';
	doRun(message);
</script>
{/if}

</body>
</html>