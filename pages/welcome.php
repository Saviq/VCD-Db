<? 
	if (!isset($_SESSION['new_user'])) {
		redirect();
		exit();
	}
?>
<h1><?=VCDLanguage::translate('register.title')?></h1>
<p class="bold">
<? 
print $_SESSION['new_user']->getFullname() . ", " . VCDLanguage::translate('register.ok');
unset($_SESSION['new_user']);
?>
</p>