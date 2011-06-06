<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @subpackage Controller
 * @version $Id: VCDPageUserWishlists.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserWishlists extends VCDBasePage  {
	
	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
		
			$this->doWishlists();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	private function doWishlists() {
		
		$results = array();
		
		// Get all available wishlists except for my own.
		$propObj = UserServices::getPropertyByKey(vcd_user::$PROPERTY_WISHLIST);
		if ($propObj instanceof userPropertiesObj ) {
			$usersArr = UserServices::getAllUsersWithProperty($propObj->getpropertyID());
			if (sizeof($usersArr) > 0) {
				// Loop through the users wishlists
				foreach ($usersArr as $userObj) {
					if ($userObj->getUserID() != VCDUtils::getUserID()) {
						$currList = SettingsServices::getWishList($userObj->getUserID());
						if (sizeof($currList) > 0) {
							$movies = array();
							$results[] = array('username' => $userObj->getUserName(), 'fullname' => $userObj->getFullname(), 'items' => &$movies);
							foreach ($currList as $item) {
								$movies[$item['id']] = array('title' => $item['title'], 'style' => ($item['mine']==1)?'green':'red', 
									'text' => ($item['mine']==1)?VCDLanguage::translate('wishlist.own'):VCDLanguage::translate('wishlist.notown'));
							}
							unset($movies);
						}
					}
				}
			}
		}
		

		$this->assign('itemWishLists',$results);
	
	}

	
}

?>