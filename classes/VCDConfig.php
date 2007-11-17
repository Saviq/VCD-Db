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
 * @version $Id: VCDConfig.php 1062 2007-07-05 15:10:11Z konni $
 * @since  0.990
  */
?>
<?php
/**
 * A wrapper class for the config.php configuration file.
 *
 */
final class VCDConfig {

	/**
	 * Check if VCD-db is connect to webservice proxy or database
	 *
	 * @return bool
	 */
	public static final function isUsingWebservice() {
		if (defined('VCDDB_USEPROXY') && (int)VCDDB_USEPROXY == 1) {
			return true;
		}
		return false;
	}
	
	
}
?>