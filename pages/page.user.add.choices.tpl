<h1>{$translate.menu.addmovie}</h1>

<fieldset id="pzone" title="{$translate.menu.addmovie}">
<legend class="bold">{$translate.addmovie.info}</legend>

<p class="outer">
	<img src="images/icons/transmit.png" border=0> {$translate.addmovie.imdb}
	<form action="{$smarty.server.SCRIPT_NAME}?page=private&amp;o=add&amp;source=webfetch" method="post" name="imdb" id="imdb">
	<p class="inner">
		{$translate.addmovie.imdbtitle}:
		<input type="text" name="searchTitle" class="input" size="15"/>&nbsp; 
		{html_options name=fetchsite options=$fetchSiteList selected=$selectedFetchSite}
		&nbsp;
		<input type="checkbox" value="1" name="searchIsId" class="nof" title="{$translate.addmovie.id}"/>&nbsp;
		<input type="submit" value="{$translate.search.search}" class="buttontext"/>
	</p>
	</form>
</p>

<p class="outer"><img src="images/icons/film_edit.png" border=0> <a href="?page=private&amp;o=add_manually">{$translate.addmovie.manual}</a></p>

<p class="outer"><img src="images/icons/film_go.png" border=0> <a href="?page=private&amp;o=add_listed">{$translate.addmovie.listed}</a></p>


<p class="outer"><img src="images/icons/feed.png" border=0> {$translate.addmovie.xml}
<form action="{$smarty.server.SCRIPT_NAME}?page=private&amp;o=add&source=xml" method="post" name="xml" enctype="multipart/form-data">
	<input type="hidden" value="xml" name="xml"/>
	<p class="inner">
		{$translate.addmovie.xmlfile}: <input type="file" name="xmlfile" class="input"/>&nbsp;
		<input type="submit" value="{$translate.menu.submit}" class="input" onclick="return checkupload(this.form.xmlfile.value)"/>
		<a title="Max upload filesize can be adjusted in the php.ini">({$translate.addmovie.maxfilesize}: {$maxFileSize})</a>
		<br/><br/>
		{$translate.addmovie.xmlnote}
	</p>
</form>
</p>
</fieldset>
