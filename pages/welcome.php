<? 
	if (!isset($_SESSION['new_user'])) {
		redirect();
		exit();
	}
?>
<h1><?=$language->show('REGISTER_TITLE')?></h1>
<p class="bold">
<? 
print $_SESSION['new_user']->getFullname() . ", " . $language->show('REGISTER_OK');
unset($_SESSION['new_user']);
?>
</p>