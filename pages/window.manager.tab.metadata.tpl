<div class="flow" align="left">
<p>
<table cellpadding="1" cellspacing="1" border="0">
{foreach from=$itemMetadataList item=i key=key name=metalist}
<tr>
	<td colspan="2">mediatype name</td>
</tr>
{foreach from=$i.metadata item=j name=meta key=mkey}
<tr>
	<td>{$j.name}</td>
	<td>{$j.value}</td>
</tr>
{/foreach}
{/foreach}
</table>
</p>
</div>


<!--
<div class="flow" align="left">
<p>
<table cellpadding="1" cellspacing="1" border="0">
<tr><td colspan="2" class="tblb"><i>Meta: DVD Blu-ray</i></td></tr><tr><td class="tblb">Custom Index:</td><td style="padding-left:5px"><input type="text" name="meta|mediaindex|10|20" size="8" class="input" value=""/></td></tr><tr><td class="tblb">NFO:</td><td style="padding-left:5px"><input type="file" name="meta|nfo|18|20" size="36" class="input" value=""/></td></tr><tr><td class="tblb">File path:</td><td style="padding-left:5px"><input type="text" id="meta|filelocation|11|20" name="meta|filelocation|11|20" size="36" class="input" value=""/>&nbsp;<img src="../images/icon_folder.gif" border="0" align="absmiddle" title="Browse for file" onclick="filebrowse('file', 'meta|filelocation|11|20')"/></td></tr><tr><td colspan="2"><hr/></td></tr><tr><td colspan="2" class="tblb"><i>Meta: DVD-R</i></td></tr><tr><td class="tblb">Custom Index:</td><td style="padding-left:5px"><input type="text" name="meta|mediaindex|10|10" size="8" class="input" value="X45"/>&nbsp;<img src="../images/icon_del.gif" align="absmiddle" alt="eyða" title="eyða" border="0" onclick="deleteMeta(18,2)"/></td></tr><tr><td class="tblb">NFO:</td><td style="padding-left:5px"><input type="text" name="null" readonly="readonly" size="30" class="input" value="a76aef697e4138c9e891b8f638b55b77.nfo"/>&nbsp;&nbsp;<img src="../images/thrashcan.gif" align="absmiddle" onclick="deleteNFO(12,2)" alt="Delete NFO" border="0"/></td></tr><tr><td class="tblb">File path:</td><td style="padding-left:5px"><input type="text" id="meta|filelocation|11|10" name="meta|filelocation|11|10" size="36" class="input" value=""/>&nbsp;<img src="../images/icon_folder.gif" border="0" align="absmiddle" title="Browse for file" onclick="filebrowse('file', 'meta|filelocation|11|10')"/></td></tr></table>
</p>
</div>
-->