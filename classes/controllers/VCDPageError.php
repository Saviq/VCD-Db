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
 * @version $Id: VCDPageError.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
/**
 * This controller displayes un-catchable errors
 *
 */
class VCDPageError extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		if (is_dir(VCDDB_BASE.DIRECTORY_SEPARATOR.'setup')) {
			$this->assign('showSetup',true);
		}
	}
	
	
}
?>