<h1><?=language::translate('MENU_ADDMOVIE')?></h1>
&nbsp;&nbsp;<strong class="plain"><?=language::translate('ADD_INFO')?></strong>
<br/>
<br/>
<h2>A) <?=language::translate('ADD_IMDB')?></h2>
<ul>

<form action="./index.php?page=private&o=add&source=webfetch" method="post" name="imdb" id="imdb">
<table cellspacing="1" cellpadding="1" class="plain">
<tr>
	<td><?=language::translate('ADD_IMDBTITLE')?>:</td>
	<td><input type="text" name="searchTitle" class="input" size="15"/>&nbsp; <?= display_fetchsites();?>&nbsp; <input type="submit" value="<?=language::translate('SEARCH')?>" class="buttontext"/></td>
</tr>
</table>
</form>
</ul>

<h2><a href="./?page=private&amp;o=add_manually">B) <?=language::translate('ADD_MANUAL')?></a></h2>

<h2><a href="./?page=private&amp;o=add_listed">C) <?=language::translate('ADD_LISTED')?></a></h2>

<h2>D) <?=language::translate('ADD_XML')?></h2>
<ul>
<form action="./index.php?page=private&o=add&source=xml" method="post" name="xml" enctype="multipart/form-data">
<input type="hidden" value="xml" name="xml">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<table cellspacing="1" cellpadding="1" class="plain">
<tr>
	<td nowrap><?=language::translate('ADD_XMLFILE')?>:</td>
	<td><input type="file" name="xmlfile" class="input"/>&nbsp;<input type="submit" value="<?=language::translate('MENU_SUBMIT')?>" class="input" onclick="return checkupload(this.form.xmlfile.value)"/>
	<a title="Max upload filesize can be adjusted in the php.ini">(<?=language::translate('ADD_MAXFILESIZE')?>: <?= ini_get('upload_max_filesize')?>)</a>
	</td>
</tr>
<tr>
	<td colspan="2" valign="top">
	<?=language::translate('ADD_XMLNOTE')?>
		
	</td>
</tr>
</table>
</form>
</ul>
