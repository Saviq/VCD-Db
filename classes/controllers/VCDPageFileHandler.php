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
		} elseif (strcmp($this->getParam('action'),'data')==0) {
			$this->doExport();
		} elseif (!is_null($this->getParam('graph'))) {
			$this->doGraph();
		} elseif (!is_null($this->getParam('pornstar_id'))) {
			$this->doPornstarImage($this->getParam('pornstar_id'));
		}
		
		
		// We never use the parent functions ..
		exit();
	}
	
	
	/**
	 * Generate graphs for the "user statistics" page.
	 *
	 */
	private function doGraph() {
		
		// include the graph class
		require_once(VCDDB_BASE.'/classes/external/powergraph.php');
		
		$instructions = $this->getParam('graph');
		$qs = base64_decode($instructions);
		$qs = utf8_decode(urldecode($qs));
		$PG = new PowerGraphic($qs);
		$PG->drawimg = false;
		$PG->start();
	
		$obj = $PG->create_graphic();
		header('Content-type: image/png');
		imagepng($obj);
		imagedestroy($obj);
		exit();		
		
	}
	
	
	/**
	 * Export data
	 *
	 */
	private function doExport() {

		$type = $this->getParam('t');
		$filter = $this->getParam('f');
		$compression = $this->getParam('c');
		
		switch ($type) {
			case 'xml':
				$this->exportXml($filter,$compression);
				break;
			
			case 'xls':
				$this->exportXls();
				break;
				
			case 'pdf':
				$this->exportPdf();
				break;
		
			default:
				break;
		}
	}
	
	
	/**
	 * Export VCD-db movie data as XML files, optionally compressed
	 *
	 * @param string $filter | Use only subset of the data?
	 * @param string $compression | Which compression to use? 
	 */
	private function exportXml($filter=null, $compression=null) {
		
		if (!is_null($filter) && strcmp($filter,'thumbs')==0) {
			
			switch ($compression) {
				case 'tar':
					VCDXMLExporter::exportThumbnails(VCDXMLExporter::EXP_TGZ);
					break;
			
				case 'zip':
					VCDXMLExporter::exportThumbnails(VCDXMLExporter::EXP_ZIP);
					break;
					
				default:
					VCDXMLExporter::exportThumbnails(VCDXMLExporter::EXP_XML);
					break;
			}
			
		} else {
			
			switch ($compression) {
				case 'tar':
					VCDXMLExporter::exportMovies(VCDXMLExporter::EXP_TGZ);
					break;
			
				case 'zip':
					VCDXMLExporter::exportMovies(VCDXMLExporter::EXP_ZIP);
					break;
					
				default:
				VCDXMLExporter::exportMovies(VCDXMLExporter::EXP_XML);
					break;
			}
		}
	}
	
	/**
	 * Export the movie list as Excel document
	 *
	 */
	private function exportXls() {
		
		// include the Excel lib
		require_once(VCDDB_BASE.'/classes/external/excel/ExcelGen.php');
		
		$arrMovies = MovieServices::getAllVcdByUserId(VCDUtils::getUserID());
	
		//initiate a instance of "excelgen" class
		$excel = new ExcelGen("My_Movies");
	
		//initiate $row,$col variables
		$row=0;
		$col=0;
	
		//write text in cell(0,0)
		$excel->WriteText($row,$col, utf8_decode( VCDLanguage::translate('menu.movies') ) . " " . "(".date("d.m.Y").") (Generated by VCD-db)");
		$row++;
	
		// Write the column headers
		$excel->WriteText($row, 0, utf8_decode( VCDLanguage::translate('movie.title')) );
		$excel->WriteText($row, 1, utf8_decode( VCDLanguage::translate('movie.category')) );
		$excel->WriteText($row, 2, utf8_decode( VCDLanguage::translate('movie.media')) );
		$excel->WriteText($row, 3, utf8_decode( VCDLanguage::translate('movie.year')) );
		$excel->WriteText($row, 4, utf8_decode( VCDLanguage::translate('movie.mediaindex')) );
		$row++;
	
		for ($row; $row < sizeof($arrMovies) + 2; $row++) {
			$col = 0;
	
			$movie = $arrMovies[$row-2];
	
			// Write The title
			$excel->WriteText($row, $col, $movie->getTitle());
			$col++;
	
			// Write The category
			if (!is_null($movie->getCategory())) {
				$excel->WriteText($row, $col, utf8_decode($movie->getCategory()->getName(true)));
			}
			$col++;
	
			// Write The Media type
			if (!is_null($movie->getMediaType())) {
				$mediaTypeObj = $movie->getMediaType();
				$excel->WriteText($row, $col, $mediaTypeObj[0]->getDetailedName());
			}
			$col++;
	
			// Write The Year
			$excel->WriteNumber($row, $col, $movie->getYear());
			$col++;
	
			// Write The Media index
			$arr = SettingsServices::getMetadata($movie->getID(), VCDUtils::getUserID(), 'mediaindex');
			if (is_array($arr) && sizeof($arr) == 1) {
				$excel->WriteText($row, $col, $arr[0]->getMetadataValue());
			}
			$col++;
		}
	
		//stream Excel for user to download or show on browser
		$excel->SendFile();
		exit();
	}
	
	
	/**
	 * Export the movie list as PDF
	 *
	 */
	private function exportPdf() {
		
		// Include the PDF lib ...
		require_once(VCDDB_BASE.'/classes/external/pdf/tcpdf.php');
				
		
		$arrMovies = MovieServices::getAllVcdByUserId(VCDUtils::getUserID());
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
		
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
		$pdf->AliasNbPages();
		$pdf->SetFont("FreeSerif", "", 10);
		$pdf->AddPage();
		
		$row=0;
		
		$pdf->Cell(0,5, VCDLanguage::translate('menu.movies') . " " . "(".date("d.m.Y").") (Generated by VCD-db)",1,1);
		$row++;
		
		$pdf->Cell(82,5, VCDLanguage::translate('movie.title'),1,0);
		$pdf->Cell(27,5, VCDLanguage::translate('movie.category'),1,0);
		$pdf->Cell(27,5, VCDLanguage::translate('movie.media'),1,0);
		$pdf->Cell(27,5, VCDLanguage::translate('movie.year'),1,0);
		$pdf->Cell(27,5, VCDLanguage::translate('movie.mediaindex'),1,1);
		$row++;
		
		
		for ($row; $row < sizeof($arrMovies) + 2; $row++) {
			
			$movie = $arrMovies[$row-2];
			
			if (strlen($movie->getTitle())<=56){
			$pdf->Cell(82,5,$movie->getTitle(),1,0);
			}
			elseif (strlen($movie->getTitle())>56){
			$pdf->Cell(82,5,substr_replace($movie->getTitle(),'...',52),1,0);
			}
			
		if (!is_null($movie->getCategory())) {
			$pdf->Cell(27,5,$movie->getCategory()->getName(true),1,0);
			}
				
		if (!is_null($movie->getMediaType())) {
			$mediaTypeObj = $movie->getMediaType();
			$pdf->Cell(27,5,$mediaTypeObj[0]->getDetailedName(),1,0);
			}
			
			$pdf->Cell(27,5,$movie->getYear(),1,0);
			
		$arr = SettingsServices::getMetadata($movie->getID(), VCDUtils::getUserID(), 'mediaindex');
		if (is_array($arr) && sizeof($arr) == 1) {
			$pdf->Cell(27,5,$arr[0]->getMetadataValue(),1,1);
			}
		else
			$pdf->Cell(27,5,'',1,1);
		}
	
		$pdf->Output('MyMovies.pdf','D');
		exit();
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
		
		$download = isset($_GET['download']) ? true : false;
		$cover = CoverServices::getCoverById($cover_id);
				
		if ($cover instanceof cdcoverObj ) {
			
			
			// If in webservice mode, we migth need to fetch the image stream
			if (VCDConfig::isUsingWebservice()) {
				$cachedFilename = $this->checkCache($cover_id, $cover->getFilename());
				if (is_null($cachedFilename)) {
					$fileContents = FileServices::getCover($cover_id);
					$fileDestination = $this->cacheFile($cover_id, $cover->getFilename(), $fileContents);
					if (!is_null($fileDestination)) {
						$this->streamImage($fileDestination);
					} else {
						$this->streamImageStream($fileContents, $cover->getFilename(),
							$this->getImageMimeType($cover->getFilename()), $cover->getFilesize());
					}
				} else {
					$this->streamImage($cachedFilename);
					exit();
				}
			}
			
			if ($cover->isInDB()) {
				
				$imageClass = new VCDImage($cover->getImageID());
				if ($download) {
					
					$contents = $imageClass->getImageStream($cover->getImageID());
					$fileDestination = $this->cacheFile($cover_id, $cover->getFilename(),$contents,true);
					$this->streamFile($fileDestination, $this->getDetailedFileName($cover));
					unlink($fileDestination);
				} else {
					$this->streamImageStream($imageClass->getImageStream($cover->getImageID()), 
						$cover->getFilename(), $this->getImageMimeType($cover->getFilename()),$cover->getFilesize());	
				}
				
				
			} else {
				
				if (strcmp(strtolower($cover->getCoverTypeName()),'thumbnail')==0) {
					$fullpath = VCDDB_BASE.DIRECTORY_SEPARATOR.THUMBNAIL_PATH.$cover->getFilename();
				} else {
					$fullpath = VCDDB_BASE.DIRECTORY_SEPARATOR.COVER_PATH.$cover->getFilename();
				}
				
				if ($download) {
					$this->streamFile($fullpath, $this->getDetailedFileName($cover));
				} else {
					$this->streamImage($fullpath, null, $cover->getFilesize());	
				}
				
				
			}
			
		}
		exit();
	}
	
	
	/**
	 * Display pornstar thumbnail
	 *
	 */
	private function doPornstarImage($pornstar_id) {
		
		if (is_numeric($pornstar_id)) {
			$pornstarObj = PornstarServices::getPornstarByID($pornstar_id);
			if ($pornstarObj instanceof pornstarObj ) {
				$image = VCDDB_BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$pornstarObj->getImageName();
				// check if file exists ..
				if (!file_exists($image)) {
					$image = VCDDB_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'notfoundimagestar.gif';
				}
				$this->streamImage($image);
			}
		}
	}
	
	/**
	 * Stream binary data to browser
	 *
	 */
	private function doStream() {
		
	}
	
	
	/**
	 * Stream the image stream as an image to the browser
	 *
	 * @param string $stream | The imagestream in binary64 format
	 * @param string $filename | The image file name
	 * @param string $mimetype | The image mime type
	 * @param int $filesize | The image filesize
	 */
	private function streamImageStream($stream, $filename, $mimetype, $filesize) {
		session_write_close();
		@ob_end_clean();
		header("Cache-Control: cache");
		header("Pragma: cache");
		header("Content-Type: {$mimetype}"); 
		header("Content-Disposition: inline; filename={$filename}");
		header("Content-Length: " . $filesize);
		header("Content-Transfer-Encoding: binary\n");
		echo $stream;
	}
	
	/**
	 * Stream Image to the browser
	 *
	 * @param string $filepath | The local path to the file
	 * @param string $mimeType | The mimetype of the image (example image/jpeg)
	 * @param int $filesize | The image filesize
	 * @return bool | Returns true on success
	 */
	private function streamImage($filepath, $mimeType=null, $filesize=null) {
		session_write_close();
		@ob_end_clean();
		if (!is_file($filepath) || connection_status()!=0) {
			return(false);
		}
		
		
		$filename = basename($filepath);
		if (is_null($mimeType)) {
			$mimeType = $this->getImageMimeType($filename);
		}
		
		if (is_null($filesize) || $filesize==0) {
			$filesize = filesize($filepath);
		}
		
		header("Cache-Control: cache");
		header("Pragma: cache");
		header("Content-Type: {$mimeType}"); 
		header("Content-Disposition: inline; filename={$filename}");
		header("Content-Length: " . $filesize);
		header("Content-Transfer-Encoding: binary\n");
		readfile($filepath);
		
		return((connection_status()==0) && !connection_aborted());
	}
	
	
	/**
	 * Stream the contents of the file to browser
	 *
	 * @param string $path | The full path to the file
	 * @param string $filename | The filename to use if specified
	 * @return bool | Returns true on success
	 */
	private function streamFile($path, $filename=null) {
		session_write_close();
		@ob_end_clean();
		if (!is_file($path) || connection_status()!=0) {
			return(false);
		}
		
		//to prevent long file from getting cut off from    //max_execution_time
		@set_time_limit(0);
		
		if (is_null($filename)) {
			$name = basename($path);	
		} else {
			$name = $filename;
		}
		
		
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
	
	
	/**
	 * Check if file exists in cache folder and return it's path if it does.
	 * If file does not exist, NULL is returned.
	 *
	 * @param string $filename | The filename to check for.
	 * @return string | The existing filename
	 */
	private function checkCache($cover_id, $filename) {
		$cacheFolder = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
		$cachedFilename = $cacheFolder.$cover_id.'-'.$filename;
		if (file_exists($cachedFilename)) {
			return $cachedFilename;
		} else {
			return null;
		}
	}
	
	
	/**
	 * Write file to the cache folder and store for future use.
	 * Returns null if $contents are null or writing to disk failes.
	 *
	 * @param int $cover_id | The coverID of the image
	 * @param string $filename | The cover filename
	 * @param string $contents | The cover binary data contents
	 * @return string | The path to file on filesystem.
	 */
	private function cacheFile($cover_id, $filename, $contents=null, $skipBase64Decode=false) {
		if (is_null($contents)) return null;
		
		$fileDestination = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$cover_id.'-'.$filename;
		
		if (!$skipBase64Decode) {
			$contents = base64_decode($contents);
		}
		
		if (VCDUtils::write($fileDestination ,$contents,false)) {
			return $fileDestination;
		} else {
			return null;
		}
	}
	
	
	/**
	 * Get the correct mimetype of an image
	 *
	 * @param string $filename | The image filename
	 * @return string | The complete mimetype name
	 */
	private function getImageMimeType($filename) {
		return 'image/'.VCDUtils::getFileExtension($filename);
	}
	
	
	/**
	 * Get detailed covername by the coverType and movie name
	 *
	 * @param cdcoverObj $obj | The cdCover object
	 * @return string | The generated cover name
	 */
	private function getDetailedFileName(cdcoverObj $obj) {
		$itemObj = MovieServices::getVcdByID($obj->getVcdId());
		$coverName = $itemObj->getTitle().'-'.$obj->getCoverTypeName();
		return preg_replace('/[^a-zA-Z0-9\.]/','_',$coverName).'[vcd-db].'.VCDUtils::getFileExtension($obj->getFilename());
	}
	
}