<? 
if (!defined('CACHE_FOLDER')) {
	define("CACHE_FOLDER","upload/cache/");
}

require_once('classes/external/snoopy/Snoopy.class.php');
require_once('classes/fetch/fetch_dvdempire.php');

$title = '';
$offset = 1;
$exacturl = "./?page=private&o=add&source=empire";
$empire_id = -1;
$excact = false;
$use_cache = true;

if (isset($_POST['name'])) {
	$title = $_POST['name'];
} 

if (isset($_GET['offset'])) {
	$offset = $_GET['offset'];
}

if (isset($_GET['key'])) {
	$title = urldecode($_GET['key']);
}


if (isset($_GET['id'])) {
	$empire_id = $_GET['id'];
	$excact = true;
}


if (isset($_GET['cache']) && $_GET['cache'] == 'off') {
	$use_cache = false;
}

	

	$snoopy = new Snoopy();
	
	if ($excact) {
		$url = "http://adult.dvdempire.com/Exec/v1_item.asp?userid=00000000000001&item_id=".$empire_id;
	} else if ($offset > 1) {
		$url = "http://adult.dvdempire.com/exec/v1_search_titles.asp?userid=00000000000001&string=". rawurlencode($title) ."&include_desc=0&used=0&view=1&sort=5&page=".$offset;
	} else {
		$url = "http://adult.dvdempire.com/exec/v1_search_titles.asp?userid=00000000000001&string=". rawurlencode($title) ."&include_desc=0&used=0&view=1&sort=5";
	}

	
	/* We cache the results for frequent queries .. */
	$CacheFileName = preg_replace("#([^a-z0-9]*)#","",$title);
	$CacheFileName = CACHE_FOLDER."empire-".$CacheFileName.$offset;
	$CacheFileNameItem = $CacheFileName."_item";
	$itemCache = false;
	
	// Check for the item
	if ($use_cache && file_exists($CacheFileNameItem)) {
		$itemCache = true;
		$result = (implode("",file($CacheFileNameItem)));
		$empire = new fetch_dvdempire($result, $empire_id, $offset);	
				
		// Mark it a straight hit
		$excact = true;
		
		// And set cache flag
		$empire->setCached(true);
	}
		
	//if cache-file for search results found, return it
	else if($use_cache && file_exists($CacheFileName)) {
		$result = (implode("",file($CacheFileName)));
		$empire = new fetch_dvdempire($result, $empire_id, $offset);	
		
		// And set cache flag
		$empire->setCached(true);
	} else {
		// Using live fetch
		$snoopy->fetch($url);
				
		$empire = new fetch_dvdempire($snoopy->results, $empire_id, $offset);	
		
		// And .. cache the results
		VCDUtils::write($CacheFileName, $empire->getContents());
	}
	
	
		
	// We got exact match ... lets crawl that page
	if ($snoopy->lastredirectaddr != '' || $excact) {
		
		// Give it a little slack
		set_time_limit(60);
		
		// mark the cached item as a DVD, not as search results
		if (!$itemCache) {
			fs_rename($CacheFileName, $CacheFileNameItem);
		}
		
		
		// Get the id in the redirect header
		if ($excact) {
			$empire->setID($empire_id);
		} else {
			parse_str($snoopy->lastredirectaddr, $urlArray);
			$empire->setID($urlArray['item_id']);
		}
		
		$empire->fetch();
		
		
		
		// And display results for confirmation
		require_once('pages/empire_confirm.php');		
		
				
	} else {
		$empire->setSearchKey($title);
		$empire->search();
		
		if (!$empire->displayResults()) {
			;
			print "<h1>".language::translate('MENU_ADDMOVIE')."</h1>";
			print "<br/><ul><li>".language::translate('SEARCH_NORESULT')."</li>";
			print "<li><a href=\"javascript:history.back(-1)\">".language::translate('X_TRYAGAIN')."</a></li></ul>";
			
		}
		
	
	}
	    

?>