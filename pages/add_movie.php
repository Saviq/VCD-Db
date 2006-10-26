<h1><?=VCDLanguage::translate('menu.addmovie')?></h1>
&nbsp;&nbsp;<strong class="plain"><?=VCDLanguage::translate('addmovie.info')?></strong>
<br/>
<br/>
<h2>A) <?=VCDLanguage::translate('addmovie.imdb')?></h2>
<ul>

<form action="./index.php?page=private&o=add&source=webfetch" method="post" name="imdb" id="imdb">
<table cellspacing="1" cellpadding="1" class="plain">
<tr>
	<td><?=VCDLanguage::translate('addmovie.imdbtitle')?>:</td>
	<td><input type="text" name="searchTitle" class="input" size="15"/>&nbsp; <?= display_fetchsites();?>&nbsp; <input type="submit" value="<?=VCDLanguage::translate('search.search')?>" class="buttontext"/></td>
</tr>
</table>
</form>
</ul>

<h2><a href="./?page=private&amp;o=add_manually">B) <?=VCDLanguage::translate('addmovie.manual')?></a></h2>

<h2><a href="./?page=private&amp;o=add_listed">C) <?=VCDLanguage::translate('addmovie.listed')?></a></h2>

<h2>D) <?=VCDLanguage::translate('addmovie.xml')?></h2>
<ul>
<form action="./index.php?page=private&o=add&source=xml" method="post" name="xml" enctype="multipart/form-data">
<input type="hidden" value="xml" name="xml"/>
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<table cellspacing="1" cellpadding="1" class="plain">
<tr>
	<td nowrap><?=VCDLanguage::translate('addmovie.xmlfile')?>:</td>
	<td><input type="file" name="xmlfile" class="input"/>&nbsp;<input type="submit" value="<?=VCDLanguage::translate('menu.submit')?>" class="input" onclick="return checkupload(this.form.xmlfile.value)"/>
	<a title="Max upload filesize can be adjusted in the php.ini">(<?=VCDLanguage::translate('addmovie.maxfilesize')?>: <?= ini_get('upload_max_filesize')?>)</a>
	</td>
</tr>
<tr>
	<td colspan="2" valign="top">
	<?=VCDLanguage::translate('addmovie.xmlnote')?>
		
	</td>
</tr>
</table>
</form>
</ul>
