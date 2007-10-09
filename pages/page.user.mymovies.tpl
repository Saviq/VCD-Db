<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td width="70%"><h1>{$translate.menu.movies}</h1></td>
	<td><h1>{$translate.mymovies.actions}</h1></td>
</tr>
<tr>
	<td valign="top">
	<!-- User content -->
	 
	Content
		
	<!-- / User content -->
	</td>
	<td valign="top">
	<!-- User menu -->
	<ul>
		<li><a href="?page=movies&amp;do=diff">{$translate.mymovies.join}</a></li>
		<li><a href="#" onclick="showUserStatus()">{$translate.mymovies.overview}</a></li>
		<li><a href="#" onclick="showUserStatusDetailed()">{$translate.mymovies.overviewdetail}</a></li>
		{if $isIndex}
		<li><a href="./?page=private&o=movies&do=customkeys">{$translate.mymovies.keys}</a></li>
		{/if}
		{if $isSeenslist}
		<li><a href="./?page=private&o=movies&do=seenlist">{$translate.mymovies.seenlist}</a></li>
		{/if}
		<li><a href="./?page=private&o=movies&do=picker">{$translate.mymovies.helppicker}</a></li>
		<br/><br/>
		<li><a href="#" onclick="printView('text')">{$translate.mymovies.textall}</a></li>
		<li><a href="#" onclick="printView('all')">{$translate.mymovies.pwall}</a></li>
		<li><a href="#" onclick="printView('movies')">{$translate.mymovies.pwmovies}</a></li>
		<li><a href="#" onclick="printView('tv')">{$translate.mymovies.pwtv}</a></li>
		<li><a href="#" onclick="printView('blue')">{$translate.mymovies.pwblue}</a></li>

	</ul>
	
	<!-- / User menu -->
	</td>
</tr>
</table>


{** Default actions **}

<fieldset id="pagelook" title="{$translate.mymovies.export}">
<legend class="bold">{$translate.mymovies.export}</legend>

<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td width="4%"><img src="images/icon_xls.gif" border="0" alt="Excel" hspace="2"/></td>
	<td width="4%">&nbsp;</td>
	<td width="4%">&nbsp;</td>
	<td><a href="?page=file&amp;action=export&amp;type=excel">{$translate.mymovies.excel}</a> <br/></td>
</tr>
<tr>
	<td width="4%"><img src="images/icon_pdf.gif" border="0" alt="PDF" hspace="2"/></td>
	<td width="4%">&nbsp;</td>
	<td width="4%">&nbsp;</td>
	<td><a href="?page=file&amp;action=export&amp;type=pdf">{$translate.mymovies.pdf}</a> <br/></td>
</tr>
<tr>
	<td><a href="?page=file&amp;action=export&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;type=xml">{$translate.mymovies.xml}</a></td>
</tr>
<tr>
	<td><a href="?page=file&amp;action=export&amp;filter=thumbs&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;filter=thumbs&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;filter=thumbs&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="?page=file&amp;action=export&amp;filter=thumbs&amp;type=xml">{$translate.mymovies.xmlthumbs}</a></td>
</tr>
{if $isWindows}
<tr>
	<td><img src="images/icon_cd.gif" border="0" alt="VCD-db Client" hspace="2"/></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><a href="images/VCDdb-Client-0.1.zip">VCD-db Client</a></td>
</tr>
{/if}
</table>
</fieldset>
