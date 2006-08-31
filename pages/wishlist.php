<?
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	
	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$listArr = $SETTINGSClass->getWishList(VCDUtils::getUserID());
?>

<h1><?= language::translate('MENU_WISHLIST')?></h1>

<? 
	if (!is_array($listArr) || sizeof($listArr) == 0) {
		print "<p class=\"bold\">".language::translate('W_EMPTY')."</p>";
	} else {
?>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
<tr>
	<td class="bold" width="92%"><?= language::translate('M_TITLE')?></td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<? 
	foreach ($listArr as $item) {
		$iown = "&nbsp;";
		if ($item[2] == 1) {
			$iown = "<img src=\"images/mark_seen.gif\" border=\"0\" alt=\"{language::translate('W_OWN')}\"/>";
		}
		
		print "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$item[0]."\">".$item[1]."</a></td><td align=\"center\">{$iown}</td><td align=\"center\"><a href=\"#\"><img src=\"images/icon_del.gif\" onclick=\"deleteFromWishlist(".$item[0].")\" border=\"0\"/></a></td></tr>";
	}
	
	unset($listArr);
?>
</table>



<? 	}  ?>