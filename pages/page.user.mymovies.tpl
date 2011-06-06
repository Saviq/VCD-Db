<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td width="70%"><h1>{$pageTitle}</h1></td>
	<td><h1>{$translate.mymovies.actions}</h1></td>
</tr>
<tr>
	<td valign="top">
	<!-- User content -->
	 
	{if $smarty.get.do eq 'join'}
		{include file='page.user.mymovies.join.tpl'}
	{elseif $smarty.get.do eq 'keys'}
		{include file='page.user.mymovies.keys.tpl'}
	{elseif $smarty.get.do eq 'seen'}
		{include file='page.user.mymovies.seenlist.tpl'}
	{elseif $smarty.get.do eq 'pick'}
		{include file='page.user.mymovies.picker.tpl'}
	{else $smarty.get.do eq 'join'}
		
		<p>{$translate.mymovies.info}</p>
		<br/>
	
		{** Default actions **}

		<fieldset id="pagelook" title="{$translate.mymovies.export}" style="border:0px">
		<legend class="bold">{$translate.mymovies.export}</legend>
		
		<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
		<tr>
			<td width="4%"><img src="images/icon_xls.gif" border="0" alt="Excel" hspace="2"/></td>
			<td width="4%">&nbsp;</td>
			<td width="4%">&nbsp;</td>
			<td><a href="?page=file&amp;action=data&amp;t=xls">{$translate.mymovies.excel}</a> <br/></td>
		</tr>
		<tr>
			<td width="4%"><img src="images/icon_pdf.gif" border="0" alt="PDF" hspace="2"/></td>
			<td width="4%">&nbsp;</td>
			<td width="4%">&nbsp;</td>
			<td><a href="?page=file&amp;action=data&amp;t=pdf">{$translate.mymovies.pdf}</a> <br/></td>
		</tr>
		<tr>
			<td><a href="?page=file&amp;action=data&amp;t=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml">{$translate.mymovies.xml}</a></td>
		</tr>
		<tr>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;f=thumbs"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;c=tar&amp;f=thumbs"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;c=zip&amp;f=thumbs"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
			<td><a href="?page=file&amp;action=data&amp;t=xml&amp;f=thumbs">{$translate.mymovies.xmlthumbs}</a></td>
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
	
	{/if}
	
	<!-- / User content -->
	</td>
	<td valign="top">
	<!-- User menu -->
	<ul>
		<li><a href="?page=movies&amp;do=join">{$translate.mymovies.join}</a></li>
		<li><a href="#" onclick="showUserStatus();return false">{$translate.mymovies.overview}</a></li>
		{if $isIndex}
		<li><a href="?page=movies&amp;do=keys">{$translate.mymovies.keys}</a></li>
		{/if}
		{if $isSeenlist}
		<li><a href="?page=movies&amp;do=seen">{$translate.mymovies.seenlist}</a></li>
		{/if}
		<li><a href="?page=movies&amp;do=pick">{$translate.mymovies.helppicker}</a></li>
		
		<li style="margin-top:20px"><a href="#" onclick="printView('text');return false">{$translate.mymovies.textall}</a></li>
		<li><a href="#" onclick="printView('all');return false">{$translate.mymovies.pwall}</a></li>
		<li><a href="#" onclick="printView('movies');return false">{$translate.mymovies.pwmovies}</a></li>
		<li><a href="#" onclick="printView('tv');return false">{$translate.mymovies.pwtv}</a></li>
		<li><a href="#" onclick="printView('blue');return false">{$translate.mymovies.pwblue}</a></li>

	</ul>
	
	<!-- / User menu -->
	</td>
</tr>
</table>