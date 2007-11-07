
<input type="hidden" name="id_list" id="id_list"/>
<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top">Studio:</td>
	<td>{html_options id=studio name=studio options=$itemStudioList selected=$selectedStudio class="input"}</td>
</tr>
<tr>
	<td class="tblb" valign="top" colspan="2">{$translate.dvdempire.subcat}:<br/>
	
		<table cellspacing="0" cellpadding="2" border="0">
		<tr>
			<td>{html_options size="8" class="input" style="width:200px;height:185px" id=adultCategoriesAvail name=adultCategoriesAvail options=$subCategoriesAvailable onDblClick="moveOver(this.form, 'adultCategoriesAvail', 'adultCategoriesUsed')" class="input"}</td>
			<td>
				<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'adultCategoriesAvail', 'adultCategoriesUsed');" class="input" style="margin-bottom:5px;"/>
				<br/>
				<input type="button" value="<<" onclick="removeMe(this.form, 'adultCategoriesAvail', 'adultCategoriesUsed');" class="input"/>
			</td>
			<td>{html_options size="8" class="input" style="width:200px;height:185px" id=adultCategoriesUsed name=adultCategoriesUsed options=$subCategoriesUsed onDblClick="removeMe(this.form, 'adultCategoriesAvail', 'adultCategoriesUsed')" class="input"}</td>
		</tr>
		</table>
		
	</td>
</tr>
</table>
