<h1><?=VCDLanguage::translate('menu.addmovie')?></h1>

<fieldset id="pzone" title="<?=VCDLanguage::translate('menu.addmovie')?>">
<legend class="bold"><?=VCDLanguage::translate('addmovie.info')?></legend>

<p class="outer">
<img src="images/icons/transmit.png" border=0> <?=VCDLanguage::translate('addmovie.imdb')?>
<form action="./index.php?page=private&o=add&source=webfetch" method="post" name="imdb" id="imdb">
<p class="inner">
	<?=VCDLanguage::translate('addmovie.imdbtitle')?>:
	<input type="text" name="searchTitle" class="input" size="15"/>&nbsp; 
	<?= display_fetchsites();?>&nbsp;
	<input type="checkbox" value="1" name="searchIsId" class="nof" title="<?=VCDLanguage::translate('addmovie.id')?>"/>&nbsp;
	<input type="submit" value="<?=VCDLanguage::translate('search.search')?>" class="buttontext"/>
</p>
</form>
</p>

<p class="outer"><img src="images/icons/film_edit.png" border=0> <a href="./?page=private&amp;o=add_manually"><?=VCDLanguage::translate('addmovie.manual')?></a></p>

<p class="outer"><img src="images/icons/film_go.png" border=0> <a href="./?page=private&amp;o=add_listed"><?=VCDLanguage::translate('addmovie.listed')?></a></p>


<p class="outer"><img src="images/icons/feed.png" border=0> <?=VCDLanguage::translate('addmovie.xml')?>
<form action="./index.php?page=private&o=add&source=xml" method="post" name="xml" enctype="multipart/form-data">
<input type="hidden" value="xml" name="xml"/>
<p class="inner">
<?=VCDLanguage::translate('addmovie.xmlfile')?>: <input type="file" name="xmlfile" class="input"/>&nbsp;
<input type="submit" value="<?=VCDLanguage::translate('menu.submit')?>" class="input" onclick="return checkupload(this.form.xmlfile.value)"/>
<a title="Max upload filesize can be adjusted in the php.ini">(<?=VCDLanguage::translate('addmovie.maxfilesize')?>: <?= ini_get('upload_max_filesize')?>)</a>
<br/><br/>
<?=VCDLanguage::translate('addmovie.xmlnote')?>
</p></form>

</p>
</fieldset>
