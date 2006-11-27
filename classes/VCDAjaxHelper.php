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
 * @author  Michał Sawicz <michal@sawicz.net>
 * @package Kernel
 * @version $Id$
 */
?>
<?php

$SETTINGSClass = new vcd_settings();

function formatSelArr($array, $filter = array()) {
	$data[] = array("value" => "null",
					"label" => VCDLanguage::translate("misc.select")
				   );
	foreach ($array as $id => $label) {
		$data[] = array("value" => $id,
						  "label" => $label,
						  "selected" => in_array($id, $filter)
						);
	}
	return $data;
}

function VCDAjaxHelper($queries, $id) {
	try {
		$dataArr = array();
		$queries = explode("|", $queries);
		global $SETTINGSClass;
		if($id == 'null') {
			foreach ($queries as $query) $dataArr[] = array('query' => $query);
			return $dataArr;
		}
		if(!is_numeric($id)) {
			throw new AjaxException("mediaTypeID must be numeric");
		}
		$mediaTypeObj = $SETTINGSClass->getMediaTypeByID($id);
		foreach($queries as $query) {
			if($mediaTypeObj instanceof mediaTypeObj) {
				$data = null;
				switch ($query) {
					case 'cover':
						$cdcoverObj = VCDClassFactory::getInstance('vcd_cdcover');
						$header = VCDLanguage::translate('movie.covers');
						if($mediaTypeObj->getParentID() > 0) {
							$cdcoverArr = $cdcoverObj->getCDcoverTypesOnMediaType($mediaTypeObj->getParentID());
						} else {
							$cdcoverArr = $cdcoverObj->getCDcoverTypesOnMediaType($mediaTypeObj->getmediaTypeID());
						}
						foreach($cdcoverArr as $cdcoverObj) {
							if($cdcoverObj->isThumbnail()) continue;
							$data[] = array("type" => "file",
											"id"   => $cdcoverObj->getCoverTypeID(),
											"label"=> $cdcoverObj->getCoverTypeName()
							);
						}
						break;
					case 'meta':
						$metaDataTypeArr = $SETTINGSClass->getMetadataTypes(VCDUtils::getUserID());
						$header = VCDLanguage::translate('metadata.my');
						$user = $_SESSION['user'];
						if ($user->getPropertyByKey(vcd_user::$PROPERTY_NFO)) {
							$nfoMeta = $SETTINGSClass->getMetadataById(metadataTypeObj::SYS_NFO);
							$data[] = array("type" => "file",
											"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_NFO)."|".metadataTypeObj::SYS_NFO."|".$mediaTypeObj->getmediaTypeID(),
											"label"=> "NFO"
											);
						}
						if ($user->getPropertyByKey(vcd_user::$PROPERTY_PLAYMODE)) {
							$plyMeta = $SETTINGSClass->getMetadataById(metadataTypeObj::SYS_FILELOCATION);
							$data[] = array("type" => "file",
											"clear"=> true,
											"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_FILELOCATION)."|".metadataTypeObj::SYS_FILELOCATION."|".$mediaTypeObj->getmediaTypeID(),
											"label"=> "File path"
											);
						}
						if ($user->getPropertyByKey(vcd_user::$PROPERTY_INDEX)) {
							$idxMeta = $SETTINGSClass->getMetadataById(metadataTypeObj::SYS_MEDIAINDEX);
							$data[] = array("type" => "text",
											"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_MEDIAINDEX)."|".metadataTypeObj::SYS_MEDIAINDEX."|".$mediaTypeObj->getmediaTypeID(),
											"label"=> "Custom Index"
											);
						}
						foreach($metaDataTypeArr as $metaDataTypeObj) {
							$data[] = array("type" => "text",
											"id"   => "meta|".$metaDataTypeObj->getMetadataTypeName()."|".$metaDataTypeObj->getMetadataTypeID()."|".$mediaTypeObj->getmediaTypeID(),
											"label"=> $metaDataTypeObj->getMetadataTypeName()
											);
						}
						break;
					case 'dvd':
						if(!VCDUtils::isDVDType(array($mediaTypeObj))) {
							$data = null;
							break;
						}
						$dvdObj = new dvdObj();
						$header = "DVD";
						foreach ($dvdObj->getRegionList() as $id => $label) {
							$regArr[$id] = $id.". ".$label;
						}
						$dmetaObj = $SETTINGSClass->getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_DEFAULTDVD);
						if (is_array($dmetaObj) && sizeof($dmetaObj) == 1) {
							$dvdSettings = unserialize($dmetaObj[0]->getMetadataValue());
						} else {
							$dvdSettings = array("region" => null,
												 "format" => null,
												 "aspect" => null,
												 "audio" => null,
												 "subs" => null,
												 );
						}
						$data[] = array("type" => "select",
										"id" => "meta|".metaDataTypeObj::SYS_DVDREGION ."|".metaDataTypeObj::SYS_DVDREGION ."|".$mediaTypeObj->getmediaTypeID(),
										"label" => VCDLanguage::translate('dvd.region'),
										"data" => formatSelArr($regArr, array($dvdSettings['region']))
									   );
						$data[] = array("type" => "select",
										"id" => "meta|".metaDataTypeObj::SYS_DVDFORMAT."|".metaDataTypeObj::SYS_DVDFORMAT."|".$mediaTypeObj->getmediaTypeID(),
										"label" => VCDLanguage::translate('dvd.format'),
										"data" => formatSelArr($dvdObj->getVideoFormats(), array($dvdSettings['format']))
									   );
						$data[] = array("type" => "select",
										"id" => "meta|".metaDataTypeObj::SYS_DVDASPECT."|".metaDataTypeObj::SYS_DVDASPECT."|".$mediaTypeObj->getmediaTypeID(),
										"label" => VCDLanguage::translate('dvd.aspect'),
										"data" => formatSelArr($dvdObj->getAspectRatios(), array($dvdSettings['aspect']))
									   );
						$data[] = array("type" => "select",
										"id" => "meta|".metaDataTypeObj::SYS_DVDAUDIO."|".metaDataTypeObj::SYS_DVDAUDIO."|".$mediaTypeObj->getmediaTypeID()."[]",
										"label" => VCDLanguage::translate('dvd.audio'),
										"multi" => true,
										"data" => formatSelArr($dvdObj->getAudioList(), explode("##", $dvdSettings['audio']))
									   );
						$data[] = array("type" => "select",
										"id" => "meta|".metaDataTypeObj::SYS_DVDSUBS."|".metaDataTypeObj::SYS_DVDSUBS."|".$mediaTypeObj->getmediaTypeID()."[]",
										"label" => VCDLanguage::translate('dvd.subtitles'),
										"multi" => true,
										"data" => formatSelArr($dvdObj->getLanguageList(), explode("##", $dvdSettings['subs']))
									   );


						break;
					default:
						throw new AjaxException('Wrong query');
						break;
				}
				$dataArr[] = array('query' => $query, 'header' => $header, 'data' => $data);
			} else {
				throw new AjaxException("No such mediatype");
			}
		}
		return $dataArr;
	}  catch (AjaxException $ex) {
		throw $ex;
	}
}

?>