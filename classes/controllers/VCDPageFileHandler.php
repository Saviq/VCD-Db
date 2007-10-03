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
 * @version $Id: VCDPageFileHander.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

/**
 * All images, NFO's and other downloadeble contents will be routed through this controller.
 *
 */
class VCDPageFileHandler extends VCDBasePage {
	
	
	public function __construct(_VCDPageNode $node) {
		parent::__construct($node);

		
		if ((!is_null($this->getParam('cover_id')) && (is_numeric($this->getParam('cover_id'))))) {
			$this->doImage($this->getParam('cover_id'));
		} elseif ((!is_null($this->getParam('nfo')) && (is_numeric($this->getParam('nfo'))))) {
			$this->doNfo($this->getParam('nfo'));
		}
		
		
	}
	
	private function doFile() {
		
	}
	
	/**
	 * Show NFO file in browser
	 *
	 */
	private function doNfo($metadata_id) {
		
		$metaObj = SettingsServices::getMetadataById($metadata_id);
		if (!$metaObj instanceof metadataObj ) {
			return;
		}
		
		$nfoFilePath = VCDDB_BASE.DIRECTORY_SEPARATOR.NFO_PATH.$metaObj->getMetadataValue();
		$nfoFontPath = VCDDB_BASE.DIRECTORY_SEPARATOR.'includes/fonts/terminal.phpfont';
		
		
		if (file_exists($nfoFilePath)) {
			
			$filesize =	filesize ($nfoFilePath);
			$filenum = fopen ($nfoFilePath, "r");
			$nfoFile = fread ($filenum, $filesize);
			fclose ($filenum);	
			
			if (!file_exists ( $nfoFontPath ) ) {
				throw new VCDProgramException('The fontfile was not found.');
			}
			
			
			// Create the image
			$nfolines = explode ("\n", $nfoFile);
			$font = imageloadfont ($nfoFontPath);
	
			$width = 0;
			$height = 0;
			$fontwidth 	= ImageFontWidth ($font);
			$fontheight = ImageFontHeight ($font);
	
			foreach ( $nfolines as $line ) {
				if ( (strlen ($line)*$fontwidth) > $width ) {
					$width = strlen ($line) * $fontwidth;
				}
				$height += $fontheight;
			}
	
			$width += $fontwidth*2;
			$height += $fontheight*3;
	
			$image = ImageCreate ($width, $height);
	
			$white = ImageColorAllocate ($image, 255,255,255);
			imagecolortransparent ($image, $white);
	
			$black = ImageColorAllocate ($image, 0, 0, 0);
	
			$i = $fontheight;
			foreach ( $nfolines as $line ) {
				ImageString ($image, $font , $fontwidth, $i, $line, $black);
				$i += $fontheight;
			}
	
			$poweredby = "powered by VCD-db (c) vcddb.konni.com";
			$wid = ($width - ($fontwidth*strlen($poweredby) ) ) / 2;
			ImageString ($image, $font , $wid, $i, $poweredby, $black);
	
			ImageAlphaBlending($image, true);
			
			Header("Content-type: image/png"); 
			header('Content-Disposition: inline; filename="'.$metaObj->getMetadataValue().'.png"');
			header("Content-Transfer-Encoding: binary\n");
			ImagePNG ($image);   
			ImageDestroy($image);
			exit();
			
			
		} 
		
	}
	
	/**
	 * Show cover image in the browser
	 *
	 * @param string $cover_id | The id of the cover to display
	 */
	private function doImage($cover_id) {
		
		$cover = CoverServices::getCoverById($cover_id);
		
		if ($cover instanceof cdcoverObj ) {
			if ($cover->isInDB()) {
				
				$imageClass = new VCDImage($cover->getImageID());
				
				@session_write_close();
				@ob_end_clean();
				header("Cache-Control: ");
				header("Pragma: ");
				header("Content-Type: application/octet-stream");
				header("Content-Length: " .(string)($imageClass->getFilesize()) );
				header('Content-Disposition: attachment; filename="'.$imageClass->getImageName().'"');
				header("Content-Transfer-Encoding: binary\n");
				echo $imageClass->getImageStream($cover->getImageID());
								
				
			} else {
				
				if (strcmp(strtolower($cover->getCoverTypeName()),'thumbnail')==0) {
					$fullpath = VCDDB_BASE.DIRECTORY_SEPARATOR.THUMBNAIL_PATH.$cover->getFilename();
				} else {
					$fullpath = VCDDB_BASE.DIRECTORY_SEPARATOR.COVER_PATH.$cover->getFilename();
				}
				$this->streamFile($fullpath);
				
			}
			
		}
		exit();
	}
	
	/**
	 * Stream binary data to browser
	 *
	 */
	private function doStream() {
		
	}
	
	
	/**
	 * Stream the contents of the file to browser
	 *
	 * @param string $path | The full path to the file
	 * @return bool | Returns true on success
	 */
	private function streamFile($path) {
		session_write_close();
		@ob_end_clean();
		if (!is_file($path) || connection_status()!=0) {
			return(false);
		}
		
		//to prevent long file from getting cut off from    //max_execution_time
		set_time_limit(0);
		
		$name = basename($path);
		
		//filenames in IE containing dots will screw up the
		//filename unless we add this
		
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			$name = preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1);			
		}

		
		//required, or it might try to send the serving    //document instead of the file
		header("Cache-Control: ");
		header("Pragma: ");
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .(string)(filesize($path)) );
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Content-Transfer-Encoding: binary\n");
		
		if($file = fopen($path, 'rb')){
			while( (!feof($file)) && (connection_status()==0) ){
				print(fread($file, 1024*8));
				flush();
			}
			fclose($file);
		}
		
		return((connection_status()==0) && !connection_aborted());
		
		}
	
}