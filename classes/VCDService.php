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



class VCDServices {

	
	private static $_Movie;
	private static $_User;
	private static $_Pornstar;
	private static $_Cdcover;
	private static $_Settings;
	
	/* 
		Movie Services
	
	*/
	
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public static function Movie_GetVCDById($movie_id) {
		try {
			return self::Movie()->getVcdByID($movie_id);
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
		
	}
	
	
	
	/**
	 * Get an instantce of the vcd_movie class
	 *
	 * @return vcd_movie
	 */
	private static function Movie() {
		if (self::$_Movie instanceof vcd_movie ) {
			return self::$_Movie;
		} else {
			self::$_Movie = VCDClassFactory::getInstance('vcd_movie');
			return self::$_Movie;
		}
	}


}

?>