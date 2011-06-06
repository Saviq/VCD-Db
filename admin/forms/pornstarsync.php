<script>
	var Updater = new Updater();
</script>
<h1>Sync pornstar database with master server</h1>
<p id="synctext">
	Here you can get updates for your pornstar database.<br/>
	Get new star names, updated pictures, website links and biography from the starlets.<br/>
	This process can take some time, so please be patent,  the current process will be updated
	after each operation so you can see the ongoing progress.
	
	<p>
	<input type="button" id="btnStartCall" value="Start Operation" onclick="Updater.startUpdate()"/>
	</p>
</p>
<p id="statusPanel">
<table cellpadding="0" cellspacing="1" border="0" width="100%" id="tblupdater" class="datatable"></table>
</p>
