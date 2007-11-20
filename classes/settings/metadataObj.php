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
 * @subpackage Settings
 * @version $Id$
 */

?>
<?php

class metadataObj extends metadataTypeObj implements XMLable {

	private $metadata_id;
	private $record_id;
	private $user_id;
	private $mediatype_id = 0;
	private $metadata_value;

	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->metadata_id	     = $dataArr[0];
		$this->record_id	     = $dataArr[1];
		$this->user_id 		     = $dataArr[2];
		$this->setMetadataTypeName($dataArr[3]);
		$this->metadata_value    = $dataArr[4];

		// Optional mediatype_id
		if (isset($dataArr[5])) {
			$this->mediatype_id = $dataArr[5];
		}

		// Optional metatype_id
		if (isset($dataArr[6]))	{
			$this->metatype_id   = $dataArr[6];
		}

		// Optional owner_id
		if (isset($dataArr[7])) {
			$this->metatype_level = $dataArr[7];
		}

		// Optional metadata type description
		if (isset($dataArr[8])) {
			$this->metatype_description = $dataArr[8];
		}

	}


	/**
	 * Get the metadata ID
	 *
	 * @return int
	 */
	public function getMetadataID() {
		return $this->metadata_id;
	}

	/**
	 * Set the metadata ID
	 *
	 * @param int $id
	 */
	public function setMetadataID($id) {
		$this->metadata_id = $id;
	}

	/**
	 * Get the record ID associated with this metadata object
	 *
	 * @return int
	 */
	public function getRecordID() {
		return $this->record_id;
	}

	/**
	 * Set the recordID belonging to this metadataObject
	 *
	 * @param int $id
	 */
	public function setRecordID($id) {
		$this->record_id = $id;
	}
	
	/**
	 * Get the media type ID assigned to metadata if any.
	 *
	 * @return int
	 */
	public function getMediaTypeID() {
		return $this->mediatype_id;
	}

	/**
	 * Set the media type ID
	 *
	 * @param int $media_id
	 */
	public function setMediaTypeID($media_id) {
		if (is_numeric($media_id)) {
			$this->mediatype_id = $media_id;
		}
	}

	/**
	 * Get the user ID of the metadata object
	 *
	 * @return int
	 */
	public function getUserID() {
		return $this->user_id;
	}

	/**
	 * Get the metadata key
	 *
	 * @return string
	 */
	public function getMetadataName() {
		return $this->getMetadataTypeName();
	}

	/**
	 * Get the metadata value
	 *
	 * @return string
	 */
	public function getMetadataValue() {
		return $this->metadata_value;
	}

	/**
	 * Set the metadata value
	 *
	 * @param string $strValue
	 */
	public function setMetadataValue($strValue) {
		$this->metadata_value = $strValue;
	}

	/**
	 * Get the metadataValue pretty formatted
	 *
	 * @param cdObj $obj
	 * @return string
	 */
	public function prettyPrint(cdObj &$obj) {
		switch ($this->metatype_id) {
			case self::SYS_FILELOCATION:
				$command = "";
				if (getPlayCommand($obj, VCDUtils::getUserID(), $command, $this)) {
					return "<a href=\"javascript:void(0)\"><img src=\"images/play.gif\" alt=\"Play\" border=\"0\" onclick=\"playFile('".$command."')\"/></a>";
				}
				return $this->getMetadataValue();
				break;

			default:
				return $this->getMetadataValue();
		}
	}
	
	/**
	 * Get the metadataObj as XML
	 *
	 */
	public function toXML() {
		$xmlstr  = "<metadata>\n";
		$xmlstr .= "<type_id>".$this->metatype_id."</type_id>\n";
		$xmlstr .= "<type_name>".$this->metatype_name."</type_name>\n";
		$xmlstr .= "<type_desc><![CDATA[".$this->metatype_description."]]></type_desc>\n";
		$xmlstr .= "<type_level>".$this->metatype_level."</type_level>\n";
		$xmlstr .= "<record_id>".$this->record_id."</record_id>\n";
		$xmlstr .= "<mediatype_id>".$this->mediatype_id."</mediatype_id>\n";
		$xmlstr .= "<data><![CDATA[".trim($this->metadata_value)."]]></data>\n";
		$xmlstr .= "</metadata>\n";
		return $xmlstr;
		
	}
	
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'mediatype_id' => $this->mediatype_id,
			'metadata_id'  => $this->metadata_id,
			'metadata_value' => $this->metadata_value,
			'metatype_description' => $this->metatype_description,
			'metatype_id' => $this->metatype_id,
			'metatype_level' => $this->metatype_level,
			'metatype_name' => $this->metatype_name,
			'record_id' => $this->record_id,
			'user_id' => $this->user_id);
	}

}




/**
 * The metadataTypeObj is a master container for all metadata.
 *
 */
class metadataTypeObj {

	CONST LEVEL_SYSTEM = 0;
	CONST LEVEL_USER   = 1;

	CONST SYS_LANGUAGES    = 1;
	CONST SYS_LOGTYPES     = 2;
	CONST SYS_FRONTSTATS   = 3;
	CONST SYS_FRONTBAR     = 4;
	CONST SYS_DEFAULTROLE  = 5;
	CONST SYS_PLAYER	   = 6;
	CONST SYS_PLAYERPATH   = 7;
	CONST SYS_FRONTRSS 	   = 8;
	CONST SYS_IGNORELIST   = 9;
	CONST SYS_MEDIAINDEX   = 10;
	CONST SYS_FILELOCATION = 11;
	CONST SYS_SEENLIST 	   = 12;

	/* DVD Specific constants */
	CONST SYS_DVDREGION	   = 13;
	CONST SYS_DVDFORMAT	   = 14;
	CONST SYS_DVDASPECT	   = 15;
	CONST SYS_DVDAUDIO	   = 16;
	CONST SYS_DVDSUBS	   = 17;
	CONST SYS_NFO		   = 18;

	
	/* Additional system types */
	CONST SYS_LASTFETCH	   = 19;
	CONST SYS_DEFAULTDVD   = 20;
	
	
	/* Reserved for later use  */
	CONST SYS_RESERVED02   = 21;
	CONST SYS_RESERVED03   = 22;
	CONST SYS_RESERVED04   = 23;
	CONST SYS_RESERVED05   = 24;
	CONST SYS_RESERVED06   = 25;
	CONST SYS_RESERVED07   = 26;
	CONST SYS_RESERVED08   = 27;
	CONST SYS_RESERVED09   = 28;
	CONST SYS_RESERVED10   = 29;
	CONST SYS_RESERVED11   = 30;
	
	protected $metatype_id;
	protected $metatype_name;
	protected $metatype_level = metadataTypeObj::LEVEL_SYSTEM;
	protected $metatype_description;

	/**
	 * List of types that allow duplicate records for same media type.
	 *
	 * @var array
	 */
	private $duplicatesAllowed = array(self::SYS_FILELOCATION);
	
	/**
	 * Object constructor
	 *
	 * @param int $id | The id of the metadataTypeObj
	 * @param string $name | The name of the metadataTypeObj
	 * @param string $description | The description of the metadatatype
	 * @param int $level | the access level of the Obj (0 or 1)
	 */
	public function __construct($id = -1, $name, $description, $level = self::LEVEL_SYSTEM) {
		$this->metatype_id = $id;
		$this->metatype_name = $name;
		$this->metatype_description = $description;
		$this->metatype_level = $level;

	}


	/**
	 * Get the Object instance.
	 *
	 * @return metadataTypeObj
	 */
	public function getMetaDataTypeInstance() {
		return $this;
	}


		/**
	 * Get the Type ID associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeID() {
		return $this->metatype_id;
	}

	/**
	 * Get the Type Name associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeName() {
		return $this->metatype_name;
	}


	/**
	 * Set the metadataType ID
	 *
	 * @param int $id
	 */
	public function setMetaDataTypeID($id) {
		$this->metatype_id = $id;
	}


	/**
	 * Sets the metadatatype name of the object.
	 * If any of the SYS_constants is being used as $typename parameter
	 * the typename and id will be automatically set and $level set to SYSTEM.
	 *
	 * @param mixed $typename | Either use any of the SYS_constants or string for USER level data.
	 */
	public function setMetadataTypeName($typename) {
		if (is_numeric($typename)) {
			$this->metatype_name = $this->getSystemTypeMapping($typename);
			$this->metatype_id = $typename;
			$this->metatype_level = self::LEVEL_SYSTEM;
		} else {
			$this->metatype_name = $typename;
			$this->metatype_level = self::LEVEL_USER;
			if (!isset($this->metatype_id) || !is_numeric($this->metatype_id)) {
				$this->metatype_id = -1;
			}
		}

	}

	/**
	 * Get the Type Level associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeLevel() {
		return $this->metatype_level;
	}

	/**
	 * Check weither metadata type belongs to the system or a user.
	 *
	 * @return bool
	 */
	public function isSystemObj() {
		return ($this->metatype_level == self::LEVEL_SYSTEM);
	}

	/**
	 * Get the metadata description
	 *
	 * @return string
	 */
	public function getMetadataDescription() {
		return $this->metatype_description;
	}
	
	/**
	 * Check if the current type allows duplicate records.
	 *
	 * @return bool
	 */
	public function isDuplicatesAllowed() {
		return in_array($this->metatype_id, $this->duplicatesAllowed);
	}

	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'desc' => $this->metatype_description,
			'id'	=> $this->metatype_id,
			'level' => $this->metatype_level,
			'name' => $this->metatype_name
		);
	}
	
	
	

	/**
	 * Get the string mapping for the current SYSTEM constant.
	 * If the $SYS_CONST does not match with any of the SYS_ constants defined
	 * in metadataTypeObj, function returns false.
	 *
	 * @param int $SYS_CONST | any of the SYS_ constants defined in metadataTypeObj
	 * @return string
	 */
	public static function getSystemTypeMapping($SYS_CONST) {
		switch ($SYS_CONST) {
			case self::SYS_LANGUAGES: 	 return 'languages'; 	 break;
			case self::SYS_LOGTYPES: 	 return 'logtypes';  	 break;
			case self::SYS_FRONTSTATS:   return 'frontstats';  	 break;
			case self::SYS_FRONTBAR: 	 return 'frontbar';  	 break;
			case self::SYS_DEFAULTROLE:  return 'default_role';  break;
			case self::SYS_PLAYER: 		 return 'player';  		 break;
			case self::SYS_PLAYERPATH: 	 return 'playerpath';  	 break;
			case self::SYS_FRONTRSS: 	 return 'frontrss';  	 break;
			case self::SYS_IGNORELIST: 	 return 'ignorelist';  	 break;
			case self::SYS_MEDIAINDEX: 	 return 'mediaindex';  	 break;
			case self::SYS_FILELOCATION: return 'filelocation';  break;
			case self::SYS_SEENLIST: 	 return 'seenlist';  	 break;
			case self::SYS_DVDREGION:	 return 'dvdregion';	 break;
			case self::SYS_DVDFORMAT:	 return 'dvdformat';	 break;
			case self::SYS_DVDASPECT:	 return 'dvdaspect';	 break;
			case self::SYS_DVDAUDIO:	 return 'dvdaudio';		 break;
			case self::SYS_DVDSUBS:	 	 return 'dvdsubs';		 break;
			case self::SYS_NFO:	 	 	 return 'nfo';			 break;
			case self::SYS_LASTFETCH:	 return 'lastfetch';	 break;
			case self::SYS_DEFAULTDVD:	 return 'defaultdvd';    break;
			case self::SYS_RESERVED02:	 return 'reserved';	     break;
			case self::SYS_RESERVED03 :	 return 'reserved';	     break;
			case self::SYS_RESERVED04 :	 return 'reserved';	     break;
			case self::SYS_RESERVED05 :	 return 'reserved';	     break;
			case self::SYS_RESERVED06 :	 return 'reserved';	     break;
			case self::SYS_RESERVED07 :	 return 'reserved';	     break;
			case self::SYS_RESERVED08 :	 return 'reserved';	     break;
			case self::SYS_RESERVED09 :	 return 'reserved';	     break;
			case self::SYS_RESERVED10 :	 return 'reserved';	     break;
			case self::SYS_RESERVED11 :	 return 'reserved';	     break;
			default: 					 return false; 			 break;


		}
	}

	/**
	 * Filter out the the DVD related metadata from a metadata collection.
	 * If incoming array is null, null is returned back.
	 *
	 * @param array $arrMetaData | Array of metadataObjects
	 * @return array
	 */
	public static function getDVDMeta($arrMetaData) {
		if (is_array($arrMetaData)) {
			$arrDVDMeta = array();
			foreach ($arrMetaData as &$metaDataObj) {
				switch ($metaDataObj->getMetadataTypeID()) {
					case self::SYS_DVDASPECT:
					case self::SYS_DVDAUDIO:
					case self::SYS_DVDFORMAT:
					case self::SYS_DVDREGION:
					case self::SYS_DVDSUBS:
						array_push($arrDVDMeta, $metaDataObj);
						break;
				}
			}

			return $arrDVDMeta;

		} else {
			return null;
		}
	}
	
	
	/**
	 * Filter an array full of metadataObj by the mediatype_id.
	 * Returns array with metadataObjects with the same mediatype_id as the incoming metadata_id
	 *
	 * @param array $arrMetaData
	 * @param int $mediatype_id
	 * @return array
	 */
	public static function filterByMediaTypeID($arrMetaData, $mediatype_id, $user_id = null) {
		if (is_array($arrMetaData)) {
			$arrReturnValues = array();
			foreach ($arrMetaData as $metaDataObj) {
				
				if (!is_null($user_id) && is_numeric($user_id)) {
					if ($metaDataObj->getMediaTypeID() == $mediatype_id && $metaDataObj->getUserID() == $user_id) {
						array_push($arrReturnValues, $metaDataObj);
					}					
				} else {
					if ($metaDataObj->getMediaTypeID() == $mediatype_id) {
						array_push($arrReturnValues, $metaDataObj);
					}					
				}
				
				
			}
			return $arrReturnValues;
		} else {
			return null;
		}
	}
	
	/**
	 * Filter out all SYSTEM Specific metadata from a metadata collection with the 
	 * exception of SYS_MEDIAINDEX and SYS_FILELOCATION.
	 * Returns filtered array of metadataObjects
	 *
	 * @param array $arrMetaData
	 * @return array
	 */
	public static function filterOutSystemMeta($arrMetaData) {
		if (is_array($arrMetaData)) {
			$arrReturnValue = array();
			foreach ($arrMetaData as $metaDataObj) {
				$type_id = $metaDataObj->getMetadataTypeID();
								
				if ((int)$type_id == (int)self::SYS_MEDIAINDEX ) {
					array_push($arrReturnValue, $metaDataObj);
				} else if ((int)$type_id == (int)self::SYS_FILELOCATION ) {
					array_push($arrReturnValue, $metaDataObj);
				} else if ((int)$type_id > 30) {
					array_push($arrReturnValue, $metaDataObj);
				}
				
			}
			
			return $arrReturnValue;
			
			
		} else {
			return null;
		}
		
	}


}

?>