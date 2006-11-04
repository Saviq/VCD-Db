<?php
/* 
	The main Switch Block for the application.
	Files needed are included here according the 
	global $CURRENT_PAGE parameter.
*/

$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');

switch ($CURRENT_PAGE) {

	case 'category':
		if (is_numeric($_GET['category_id'])) {
			require_once(VCDDB_BASE.'/pages/category.php');
		} else {
			redirect();
			exit();
		}
		break;
		
		
	case 'adultcategory':
		if ($SETTINGSClass->getSettingsByKey('SITE_ADULT')) {
			
			if (isset($_GET['category_id'])) {
				require_once(VCDDB_BASE.'/pages/adultcategory.php');
				break;
			} elseif (isset($_GET['studio_id'])) {
				require_once(VCDDB_BASE.'/pages/adultstudio.php');
				break;
			} else {
				redirect();
				exit();
			}	
				
		} else {
			redirect();
			exit();
		}
	
		break;
		
	case 'register':
		if (LDAP_AUTH != 1) {
			require_once(VCDDB_BASE.'/pages/register.php');
		} else {
			redirect();
		}
		break;
		
	case 'welcome':
		require_once(VCDDB_BASE.'/pages/welcome.php');
		break;
		
		
	case 'badlogin':
		require_once(VCDDB_BASE.'/pages/login_error.php');
		break;
		
	case 'reset':
		require_once(VCDDB_BASE.'/pages/newpass.php');
		break;
		
	case 'cd':
		if (is_numeric($_GET['vcd_id'])) {
			global $showright;
			require_once(VCDDB_BASE.'/pages/vcd.php');
		} else {
			redirect();
			exit();
		}
		break;
		
	case 'detailed_search':
		require_once(VCDDB_BASE.'/pages/detailed_search.php');
		break;
		
	case 'pornstars':
		require_once(VCDDB_BASE.'/pages/view_pornstars.php');
		break;
		
		
	case 'pornstar':
		if (is_numeric($_GET['pornstar_id'])) {
			// is this permitted by the administrator ?
			if ($SETTINGSClass->getSettingsByKey('SITE_ADULT')) {
				require_once(VCDDB_BASE.'/pages/pornstar.php');
			} else {
				redirect();
				exit();
			}
			
		} else{
			redirect();
			exit();
		}
		break;
		
	/* All logged in user actions */
	case 'private':
		if (isset($_GET['o']) && VCDUtils::isLoggedIn()) {
			$OPERATION  = $_GET['o'];
		} else {
			redirect();
			exit();
		}
		
			switch ($OPERATION) {
				case 'settings':
					require_once(VCDDB_BASE.'/pages/edit_user.php');
				break;

				case 'new':
					require_once(VCDDB_BASE.'/pages/add_movie.php');
				break;
				
				case 'add_manually':
					require_once(VCDDB_BASE.'/pages/add_manually.php');
				break;
				
				case 'add_listed':
					require_once(VCDDB_BASE.'/pages/add_listed.php');
				break;
				
				case 'movies':
					require_once(VCDDB_BASE.'/pages/my_movies.php');
				break;
				
				case 'wishlist':
					require_once(VCDDB_BASE.'/pages/wishlist.php');
				break;
				
				case 'publicwishlist':
					require_once(VCDDB_BASE.'/pages/wishlistpublic.php');
				break;
				
				case 'rss':
					require_once(VCDDB_BASE.'/pages/my_rssfeeds.php');
				break;
				
				case 'stats':
					require_once(VCDDB_BASE.'/pages/user_statistics.php');
				break;
				
				
				case 'loans':
					if (isset($_GET['history'])) {
						require_once(VCDDB_BASE.'/pages/loanhistory.php');
					} else {
						require_once(VCDDB_BASE.'/pages/loans.php');
					}
				break;
				
				
				/* Adding new movies .. */
				case 'add':
			
					if (isset($_GET['source'])) {
						$source = $_GET['source'];
						
						if (strcmp($source, "webfetch") == 0) {
							require_once(VCDDB_BASE.'/pages/add_webfetch.php');
						}
						
						if (strcmp($source, "moviefetch") == 0) {
							require_once(VCDDB_BASE.'/pages/add_imdbfetch.php');
						}
						
						if (strcmp($source, "adultmoviefetch") == 0) {
							require_once(VCDDB_BASE.'/pages/add_empirefetch.php');
						}
						
						if (strcmp($source, "xmlresults") == 0) {
							require_once(VCDDB_BASE.'/pages/xml_results.php');
						}
						
						if (strcmp($source, "xml") == 0) {
							require_once(VCDDB_BASE.'/pages/xml_confirm.php');
						}
						
						if (strcmp($source, "excelresults") == 0) {
							require_once(VCDDB_BASE.'/pages/excel_results.php');
						}
						
						if (strcmp($source, "excel") == 0) {
							require_once(VCDDB_BASE.'/pages/excel_confirm.php');
						}
						
						if (strcmp($source, "listed") == 0) {
							require_once(VCDDB_BASE.'/pages/listed_confirm.php');
						}
						
						
					}
					break;
					
					
				default:
					redirect();
					break;
				
			}
		
	
	
	
		break;
	
	
		
	default:
		
		include(dirname(__FILE__).'/frontpage.php');
		break;

}

?>