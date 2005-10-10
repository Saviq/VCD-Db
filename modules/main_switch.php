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
			require_once('pages/category.php');
		} else {
			redirect();
			exit();
		}
		break;
		
		
	case 'adultcategory':
		if ($SETTINGSClass->getSettingsByKey('SITE_ADULT')) {
			
			if (isset($_GET['category_id'])) {
				require_once('pages/adultcategory.php');
				break;
			} elseif (isset($_GET['studio_id'])) {
				require_once('pages/adultstudio.php');
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
		require_once('pages/register.php');
		break;
		
	case 'welcome':
		require_once('pages/welcome.php');
		break;
		
		
	case 'badlogin':
		require_once('pages/login_error.php');
		break;
		
	case 'reset':
		require_once('pages/newpass.php');
		break;
		
	case 'cd':
		if (is_numeric($_GET['vcd_id'])) {
			global $showright;
			require_once('pages/vcd.php');
		} else {
			redirect();
			exit();
		}
		break;
		
	case 'detailed_search':
		require_once('pages/detailed_search.php');
		break;
		
	case 'pornstars':
		require_once('pages/view_pornstars.php');
		break;
		
			
	case 'langdump':
		ob_get_clean();
		ob_start();
		print_r($language->getLangDump($_GET['i']));
		ob_flush();
		exit();
		break;
		
	case 'pornstar':
		if (is_numeric($_GET['pornstar_id'])) {
			// is this permitted by the administrator ?
			if ($SETTINGSClass->getSettingsByKey('SITE_ADULT')) {
				require_once('pages/pornstar.php');
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
					require_once('pages/edit_user.php');
				break;

				case 'new':
					require_once('pages/add_movie.php');
				break;
				
				case 'add_manually':
					require_once('pages/add_manually.php');
				break;
				
				case 'add_listed':
					require_once('pages/add_listed.php');
				break;
				
				case 'movies':
					require_once('pages/my_movies.php');
				break;
				
				case 'wishlist':
					require_once('pages/wishlist.php');
				break;
				
				case 'publicwishlist':
					require_once('pages/wishlistpublic.php');
				break;
				
				case 'rss':
					require_once('pages/my_rssfeeds.php');
				break;
				
				case 'stats':
					require_once('pages/user_statistics.php');
				break;
				
				
				case 'loans':
					if (isset($_GET['history'])) {
						require_once('pages/loanhistory.php');
					} else {
						require_once('pages/loans.php');
					}
				break;
				
				
				/* Adding new movies .. */
				case 'add':
			
					if (isset($_GET['source'])) {
						$source = $_GET['source'];
						
						if (strcmp($source, "imdb") == 0) {
							require_once('pages/add_imdbfetch.php');
						}
						
						if (strcmp($source, "dvdempire") == 0) {
							require_once('pages/add_empirefetch.php');
						}
						
						if (strcmp($source, "xmlresults") == 0) {
							require_once('pages/xml_results.php');
						}
						
						if (strcmp($source, "xml") == 0) {
							require_once('pages/xml_confirm.php');
						}
						
						if (strcmp($source, "excelresults") == 0) {
							require_once('pages/excel_results.php');
						}
						
						if (strcmp($source, "excel") == 0) {
							require_once('pages/excel_confirm.php');
						}
						
						if (strcmp($source, "listed") == 0) {
							require_once('pages/listed_confirm.php');
						}
						
						
					}
					break;
					
					
				default:
					redirect();
					break;
				
			}
		
	
	
	
		break;
	
	
		
	default:
		
		include('frontpage.php');
		break;

}









?>