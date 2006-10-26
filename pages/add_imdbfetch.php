<? 
require_once('classes/fetch/fetch_imdb.php');

$im = new fetch_imdb();


if (isset($_POST['imdb'])) {
	$imdb_title = $_POST['imdb'];
}


if (isset($_GET['fid'])) {
	
	$id = $_GET['fid'];
	$im->populateObj($id);
	$obj = $im->getObj();
	
	if ($obj instanceof imdbObj ) {
		
		// Grab the image
		if ($im->getPosterUrl() != ITEM_NOTFOUND) {
			$filename = VCDUtils::grabImage($im->getPosterUrl());
			$obj->setImage($filename);
		}
		
		require_once('pages/imdb_confirm.php');
	} else {
		VCDException::display('Error after straight fetch');
		
	}
	
	
} else {
	
	$res = $im->search($imdb_title);
	
	
	if ($res == EXACT_MATCH) {

		// Give it a little slack
		set_time_limit(60);
		
		$im->populateObj($im->getImdbID());
		$obj = $im->getObj();
		
		if ($obj instanceof imdbObj ) {
			// Grab the image
			if ($im->getPosterUrl() != ITEM_NOTFOUND) {
				$filename = VCDUtils::grabImage($im->getPosterUrl());
				$obj->setImage($filename);
			}
			
			require_once('pages/imdb_confirm.php');
			
		} else {
			VCDException::display('Error after exact match');
		}
			
		
		
	} elseif ($res == SEARCH_DONE) {
		
		print "<h1>".VCDLanguage::translate('add.imdb')."</h1>";
		
		if ($im->gotResults()) {
			
			$im->showSearchResults();
		} else {
			print "<br/><ul><li>".VCDLanguage::translate('search.noresult')."</li>";
			print "<li><a href=\"javascript:history.back(-1)\">".VCDLanguage::translate('misc.tryagain')."</a></li></ul>";
			
		
		}
				
	}
		
	
}



	
	
	
	
?>