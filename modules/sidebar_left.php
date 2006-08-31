<? 
if (VCDUtils::isLoggedIn()) {
	display_userlinks();
} else {
	VCDAuthentication::printLoginBox();
}
?>

<? display_moviecategories(); ?>

<? display_search(); ?>

<? display_toggle(); ?>

<div class="topic"><?=language::translate('MENU_TOPUSERS')?></div>
<? display_topusers() ?>

<? display_adultmenu() ?>
