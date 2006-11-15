<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id$
 * @since  0.985
  */
?>
<?php

class UserServices extends VCDServices implements IUser {
	
		
	
	
}

class SettingsServices extends VCDServices implements ISettings {
	
}

class CoverServices extends VCDServices implements ICdcover {
	
}

class PornstarServices extends VCDServices implements IPornstar {
	
}

class MovieServices extends VCDServices implements IVcd {
	
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public static function GetVCDById($movie_id) {
		try {
			
			return self::Movie()->getVcdByID($movie_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
}


class VCDServices {
	
	/**
	 * Get an instantce of the vcd_movie class
	 *
	 * @return vcd_movie
	 */
	protected static function Movie() {
		return VCDClassFactory::getInstance('vcd_movie');
	}

	/**
	 * Get an instance of the vcd_user class
	 *
	 * @return vcd_user
	 */
	protected static function User() {
		return VCDClassFactory::getInstance('vcd_user');
	}
	
	/**
	 * Get an instance of the vcd_pornstar class
	 *
	 * @return vcd_pornstar
	 */
	protected static function Pornstar() {
		return VCDClassFactory::getInstance('vcd_pornstar');
	}
	
	/**
	 * Get an instance of the CDcover class
	 *
	 * @return vcd_cdcover
	 */
	protected static function CDcover() {
		return VCDClassFactory::getInstance('vcd_cdcover');
	}
	
	/**
	 * Get an instance of the vcd_settings class
	 *
	 * @return vcd_settings
	 */
	protected static function Settings() {
		return VCDClassFactory::getInstance('vcd_settings');
	}
}




?>