<? 
	if (!isset($_SESSION['new_user'])) {
		redirect();
		exit();
	}
?>
<h1><?=language::translate('REGISTER_TITLE')?></h1>
<p class="bold">
<? 
print $_SESSION['new_user']->getFullname() . ", " . language::translate('REGISTER_OK');
unset($_SESSION['new_user']);
?>
</p>