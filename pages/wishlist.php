<?
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	$listArr = SettingsServices::getWishList(VCDUtils::getUserID());
?>

<h1><?= VCDLanguage::translate('menu.wishlist')?></h1>

<? 
	if (!is_array($listArr) || sizeof($listArr) == 0) {
		print "<p class=\"bold\">".VCDLanguage::translate('wishlist.empty')."</p>";
	} else {
?>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
<tr>
	<td class="bold" width="92%"><?= VCDLanguage::translate('movie.title')?></td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<? 
	foreach ($listArr as $item) {
		$iown = "&nbsp;";
		if ($item[2] == 1) {
			$iown = "<img src=\"images/mark_seen.gif\" border=\"0\" alt=\"".VCDLanguage::translate('wishlist.own')."\"/>";
		}
		
		print "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$item[0]."\">".$item[1]."</a></td><td align=\"center\">{$iown}</td><td align=\"center\"><a href=\"#\"><img src=\"images/icon_del.gif\" onclick=\"deleteFromWishlist(".$item[0].")\" border=\"0\"/></a></td></tr>";
	}
	
	unset($listArr);
?>
</table>



<? 	}  ?>