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
 * @version $Id: VCDPageUserWishlist.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserWishlist extends VCDBasePage  {
	
	public function __construct(_VCDPageNode $node) {
		try {
			parent::__construct($node);
		
			if (strcmp($this->getParam('action'),'delete')==0) {
				$this->doDeleteFromWishlist();
			}
			
			$this->assign('wishList',SettingsServices::getWishList(VCDUtils::getUserID()));	
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}

	
	/**
	 * Delete entry from the wishlist
	 *
	 */
	private function doDeleteFromWishlist() {
		
		$item_id = $this->getParam('vcd_id');
		if (is_numeric($item_id)) {
			SettingsServices::removeFromWishList($item_id, VCDUtils::getUserID());
		}
		redirect('?page=wishlist');
		exit();
	}
	
}

?>