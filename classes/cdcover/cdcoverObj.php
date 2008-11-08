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
 * @subpackage CDCover
 * @version $Id$
 */

?>
<?php
require_once(dirname(__FILE__).'/cdcoverTypeObj.php');

class cdcoverObj extends cdcoverTypeObj implements XMLable  {

	/**
	 * Id of the cdcover
	 *
	 * @var int
	 */
	private $cover_id;
	/**
	 * cd id the cover belongs tp
	 *
	 * @var int
	 */
	private $vcd_id;
	/**
	 * the filename of the cdcover
	 *
	 * @var string
	 */
	private $filename;
	/**
	 * cover filesize
	 *
	 * @var double
	 */
	private $filesize;
	/**
	 * owner id of the cover object creator
	 *
	 * @var int
	 */
	private $owner_id;
	/**
	 * date of creation
	 *
	 * @var string
	 */
	private $date_added;
	/**
	 * image_id if stored in database
	 *
	 * @var int
	 */
	private $image_id;

	/**
	 * Constructor, accepts array as an parameter containing all the objects variables.
	 *
	 * @param array $dataArr
	 * @return cdcoverObj
	 */
	public function __construct($dataArr = null) {
		if (is_array($dataArr)) {
			$this->cover_id 		   = $dataArr[0];
			$this->vcd_id 		   	   = $dataArr[1];
			$this->filename	  		   = $dataArr[2];
			$this->filesize 		   = $dataArr[3];
			$this->owner_id 		   = $dataArr[4];
			$this->date_added		   = $dataArr[5];
			$this->covertype_id		   = $dataArr[6];
			$this->covertypeName 	   = $dataArr[7];
			$this->image_id			   = $dataArr[8];
		}
	}


	/**
	 * Get the coverID
	 *
	 * @return int
	 */
	public function getId() {
		return $this->cover_id;
	}

	/**
	 * Set cover ID
	 *
	 * @param int $cover_id
	 */
	public function setCoverID($cover_id) {
		$this->cover_id = $cover_id;
	}

	/**
	 * Get the id of the CD that this cover belongs to.
	 *
	 * @return int
	 */
	public function getVcdId() {
		return $this->vcd_id;
	}

	/**
	 * Set the CD id that this cover belongs to.
	 *
	 * @param int $vcd_id
	 */
	public function setVcdId($vcd_id) {
		$this->vcd_id = $vcd_id;
	}

	/**
	 * Get the filename of the cover.
	 *
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Set the cover object filename.
	 *
	 * @param string $strFilename
	 */
	public function setFilename($strFilename) {
		$this->filename = $strFilename;
	}

	/**
	 * Get the filesize of the cover object.
	 *
	 * @return double
	 */
	public function getFilesize() {
		if (is_numeric($this->filesize)) {
			return $this->filesize;
		} else {
			return -1;
		}
	}

	/**
	 * Set the objects filesize.
	 *
	 * @param double $fsize
	 */
	public function setFilesize($fsize) {
		$this->filesize = $fsize;
	}


	/**
	 * Get the user_id of the cover objects creator
	 *
	 * @return int
	 */
	public function getOwnerId() {
		return $this->owner_id;
	}

	/**
	 * Set the user_id of the cover objects creator.
	 *
	 * @param int $owner_id
	 */
	public function setOwnerId($owner_id) {
		$this->owner_id = $owner_id;
	}

	/**
	 * Get the date this cover object was added.
	 *
	 * @return string
	 */
	public function getDateAdded() {
		return $this->date_added;
	}

	/**
	 * Get the image_id in database if image is stored as binary stream, otherwise returns -1
	 *
	 * @return int
	 */
	public function getImageID() {
		if ($this->isInDB()) {
			return $this->image_id;
		} else {
			return -1;
		}
	}

	/**
	 * Set the database image_id
	 *
	 * @param int $image_id
	 */
	public function setImageID($image_id) {
		$this->image_id = $image_id;
	}


	/**
	 * Check if image is stored in database or on filelevel.
	 *
	 * @return boolean
	 */
	public function isInDB() {
		return (isset($this->image_id) && $this->image_id > 0);
	}

	/**
	 * Returns the HTML image tag for display-ing the current cdcover object.
	 *
	 * @return string | The img src source
	 */
	public function showImage() {
			
		$img = '<img src="%s" alt="" name="%s" class="imgx" border="0"/>';
		$html = sprintf($img, '?page=file&amp;cover_id='.$this->cover_id, $this->covertypeName);
		return $html;
	}


	/**
	 * Get the HTML image SRC string for display-ing the cover object.
	 * Param prefix can be directory below like "../"
	 * Function can accept height and width as parameters to
	 * force image width and height in the IMG SRC string.
	 *
	 * @param string $prefix
	 * @param int $height
	 * @param int $width
	 * @return string
	 */
	public function getIMGSRC($prefix = "", $height="", $width="") {
		if (isset($this->image_id) && $this->image_id > 0) {
			// image is in DB
			if ($height != '' && $width != '') {
				return "<img src=\"".$prefix."vcd_image.php?id=".$this->image_id."\" width=\"".$width."\" height=\"".$height."\"  name=\"".$this->covertypeName."\" class=\"imgx\" border=\"0\"/><br/>";
			} else {
				return "<img src=\"".$prefix."vcd_image.php?id=".$this->image_id."\" name=\"".$this->covertypeName."\" class=\"imgx\" border=\"0\"/><br/>";
			}

		} else {
			// image is on disk
			if ($this->isThumbnail()) {

				if ($height != '' && $width != '') {
					return "<img src=\"".$prefix.THUMBNAIL_PATH.$this->filename."\" width=\"".$width."\" height=\"".$height."\" class=\"imgx\" border=\"0\"/>";
				} else {
					return "<img src=\"".$prefix.THUMBNAIL_PATH.$this->filename."\" class=\"imgx\" border=\"0\"/>";
				}


			} else {
				return "<a name=\"".$this->covertypeName."\"><img src=\"".$prefix.COVER_PATH.$this->filename."\" class=\"imgx\" border=\"0\"/></a><br/>";
			}

		}
	}


	/**
	 * Print the cover objects HTML IMG SRC string with a clickable link under.
	 * Param title is optional and is used in alt text
	 * Param prefix can be directory below like "../"
	 *
	 * @param string $url
	 * @param string $title
	 * @param string $prefix
	 */
	public function showImageAndLink($url, $title = "", $prefix = "") {
		if (isset($this->image_id) && $this->image_id > 0) {
			// image is in DB
			print "<a href=\"$url\"><img src=\"".$prefix."vcd_image.php?id=".$this->image_id."\" name=\"".$this->covertypeName."\" class=\"imgx\" border=\"0\"/></a>";
		} else {
			// image is on disk
			if ($this->isThumbnail()) {
				print "<a href=\"$url\"><img src=\"".$prefix.THUMBNAIL_PATH.$this->filename."\" class=\"imgx\" width=\"135\" title=\"$title\" alt=\"$title\" border=\"0\"/></a>";
			} else {
				print "<a href=\"$url\"><img src=\"".$prefix.COVER_PATH.$this->filename."\" class=\"imgx\" border=\"0\"/></a>";
			}

		}
	}


	/**
	 * Same as function showImageAndLink but is explicitly used for display-ing movie categories.
	 *
	 * @param string $url | The url behind the image
	 * @param string $title | The image title
	 * @param int $width | The image width
	 * @param int $heigth | The image height
	 * 
	 * @return string | Returns the IMG src
	 */
	public function getCategoryImageAndLink($url, $title = '', $width=100, $height=145) {
		
		$img = '<img src="%s" alt="%s" title="%s" class="imgx" width="%d" height="%d" border="0"/>';
		$html = sprintf($img, '?page=file&amp;cover_id='.$this->cover_id, $title, $title, $width,$height);
		$link = '<span><div><a href="%s">%s</a></div></span>';
		return sprintf($link, $url, $html);
		
	}


	/**
	 * Get the path of the file within the VCD-db application.
	 * Returns the image path.
	 *
	 * @return string
	 */
	public function getImagePath() {
		if (isset($this->image_id) && $this->image_id > 0) {
			// image is in DB
			return "vcd_image.php?id=".$this->image_id;
		} else {
			// image is on disk
			if ($this->isThumbnail()) {
				return THUMBNAIL_PATH.$this->filename;
			} else {
				return COVER_PATH.$this->filename;
			}

		}
	}

	/**
	 * Returns the XML reprentation of the cdcover object.
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<cdcover>\n";
		$xmlstr .= "<cover_id>".$this->cover_id."</cover_id>\n";
		$xmlstr .= "<vcd_id>".$this->vcd_id."</vcd_id>\n";
		$xmlstr .= "<filename>".$this->filename."</filename>\n";
		if ($this->getFilesize() != -1) {
			$xmlstr .= "<filesize>".$this->filesize."</filesize>\n";
		}
		$xmlstr .= "<owner_id>".$this->owner_id."</owner_id>\n";
		$xmlstr .= "<date_added>".$this->date_added."</date_added>\n";
		$xmlstr .= "<type_id>".$this->covertype_id."</type_id>\n";
		$xmlstr .= "<type_name>".$this->covertypeName."</type_name>\n";
		if ($this->getImageID() != -1) {
			$xmlstr .= "<image_id>".$this->image_id."</image_id>\n";
		}
		$cover_contents = $this->getCoverAsBinary();
		if (!$cover_contents) {
			return "";
		}

		if ($cover_contents != false) {
			$xmlstr .= "<data><![CDATA[".$cover_contents."]]></data>\n";
		}
		$xmlstr .= "</cdcover>\n";

		return $xmlstr;
	}


	/**
	 * Get the object SOAP encoded
	 *
	 * @return string
	 */
	public function toSoapEncoding() {
		return array(
			'cover_id'				=> $this->cover_id,
			'covertype_id' 			=> $this->covertype_id,
			'coverTypeDescription' 	=> $this->coverTypeDescription,
			'covertypeName'			=> $this->covertypeName,
			'date_added'			=> $this->date_added,
			'filename'				=> $this->filename,
			'filesize'				=> $this->filesize,
			'image_id'				=> $this->image_id,
			'owner_id'				=> $this->owner_id,
			'vcd_id'				=> $this->vcd_id
		);
	}

	/**
	 * Get cover image as binary stream for the xml stream.
	 * Image streams from database are return base64 encoded and
	 * Images stored on HD are also read, and then returned as base64 stream.
	 *
	 * @return string
	 */
	public function getCoverAsBinary() {

		if ($this->isInDB()) {
			$vcdImage = new VCDImage();
			return base64_encode($vcdImage->getImageStream($this->image_id));
		} else {

			$filepath = VCDDB_BASE.DIRECTORY_SEPARATOR.THUMBNAIL_PATH.$this->filename;

			if (fs_file_exists($filepath)) {

				$fd = fopen($filepath,'rb');
				if (!$fd) {
					return false;
				}

				// Read the file
				$contents = fread($fd, filesize($filepath));

				// Close the file descriptor
				fclose($fd);

				return base64_encode($contents);


			} else {

				return false;
			}
		}
	}


}
?>