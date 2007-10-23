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

class VCDAjaxHelper {
	
	private static function formatSelArr($array, $filter = array(), $multi = false) {
		if(!$multi) {
			$data[] = array("value" => "null",
			"label" => VCDLanguage::translate("misc.select")
			);		
		}
		foreach ($array as $id => $label) {
			$data[] = array("value" => $id,
			"label" => $label,
			"selected" => in_array($id, $filter)
			);
		}
		return $data;
	}

	public static function getDataForMediaType($queries, $id) {
		try {
			$dataArr = array();
			$queries = explode("|", $queries);
			if($id == 'null') {
				foreach ($queries as $query) $dataArr[] = array('query' => $query);
				return $dataArr;
			}
			if(!is_numeric($id)) {
				throw new AjaxException("mediaTypeID must be numeric");
			}
			$mediaTypeObj = SettingsServices::getMediaTypeByID($id);
			foreach($queries as $query) {
				if($mediaTypeObj instanceof mediaTypeObj) {
					$data = null;
					switch ($query) {
						case 'cover':
							$header = VCDLanguage::translate('movie.covers');
							if($mediaTypeObj->getParentID() > 0) {
								$cdcoverArr = CoverServices::getCDcoverTypesOnMediaType($mediaTypeObj->getParentID());
							} else {
								$cdcoverArr = CoverServices::getCDcoverTypesOnMediaType($mediaTypeObj->getmediaTypeID());
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
							$metaDataTypeArr = SettingsServices::getMetadataTypes(VCDUtils::getUserID());
							$header = VCDLanguage::translate('metadata.my');
							$user = $_SESSION['user'];
							if ($user->getPropertyByKey(vcd_user::$PROPERTY_NFO)) {
								$nfoMeta = SettingsServices::getMetadataById(metadataTypeObj::SYS_NFO);
								$data[] = array("type" => "file",
								"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_NFO)."|".metadataTypeObj::SYS_NFO."|".$mediaTypeObj->getmediaTypeID(),
								"label"=> "NFO"
								);
							}
							if ($user->getPropertyByKey(vcd_user::$PROPERTY_PLAYMODE)) {
								$plyMeta = SettingsServices::getMetadataById(metadataTypeObj::SYS_FILELOCATION);
								$data[] = array("type" => "file",
								"clear"=> true,
								"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_FILELOCATION)."|".metadataTypeObj::SYS_FILELOCATION."|".$mediaTypeObj->getmediaTypeID(),
								"label"=> "File path"
								);
							}
							if ($user->getPropertyByKey(vcd_user::$PROPERTY_INDEX)) {
								$idxMeta = SettingsServices::getMetadataById(metadataTypeObj::SYS_MEDIAINDEX);
								$data[] = array("type" => "text",
								"id"   => "meta|".metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_MEDIAINDEX)."|".metadataTypeObj::SYS_MEDIAINDEX."|".$mediaTypeObj->getmediaTypeID(),
								"label"=> "Custom Index"
								);
							}
							if (is_array($metaDataTypeArr)) {
								foreach($metaDataTypeArr as $metaDataTypeObj) {
									$data[] = array("type" => "text",
									"id"   => "meta|".$metaDataTypeObj->getMetadataTypeName()."|".$metaDataTypeObj->getMetadataTypeID()."|".$mediaTypeObj->getmediaTypeID(),
									"label"=> $metaDataTypeObj->getMetadataTypeName()
									);
								}
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
							$dmetaObj = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_DEFAULTDVD);
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
							"data" => VCDAjaxHelper::formatSelArr($regArr, array($dvdSettings['region']))
							);
							$data[] = array("type" => "select",
							"id" => "meta|".metaDataTypeObj::SYS_DVDFORMAT."|".metaDataTypeObj::SYS_DVDFORMAT."|".$mediaTypeObj->getmediaTypeID(),
							"label" => VCDLanguage::translate('dvd.format'),
							"data" => VCDAjaxHelper::formatSelArr($dvdObj->getVideoFormats(), array($dvdSettings['format']))
							);
							$data[] = array("type" => "select",
							"id" => "meta|".metaDataTypeObj::SYS_DVDASPECT."|".metaDataTypeObj::SYS_DVDASPECT."|".$mediaTypeObj->getmediaTypeID(),
							"label" => VCDLanguage::translate('dvd.aspect'),
							"data" => VCDAjaxHelper::formatSelArr($dvdObj->getAspectRatios(), array($dvdSettings['aspect']))
							);
							$data[] = array("type" => "select",
							"id" => "meta|".metaDataTypeObj::SYS_DVDAUDIO."|".metaDataTypeObj::SYS_DVDAUDIO."|".$mediaTypeObj->getmediaTypeID()."[]",
							"label" => VCDLanguage::translate('dvd.audio'),
							"multi" => true,
							"data" => VCDAjaxHelper::formatSelArr($dvdObj->getAudioList(), explode("#", $dvdSettings['audio']), true)
							);
							$data[] = array("type" => "select",
							"id" => "meta|".metaDataTypeObj::SYS_DVDSUBS."|".metaDataTypeObj::SYS_DVDSUBS."|".$mediaTypeObj->getmediaTypeID()."[]",
							"label" => VCDLanguage::translate('dvd.subtitles'),
							"multi" => true,
							"data" => VCDAjaxHelper::formatSelArr($dvdObj->getLanguageList(), explode("#", $dvdSettings['subs']), true)
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
	
	
	public static function getRss($rss_id) {
	
		$results = array();
		$rssFetch = new lastRSS(CACHE_FOLDER, RSS_CACHE_TIME);
		$obj = SettingsServices::getRssfeed($rss_id);
		$rss = $rssFetch->Get($obj->getFeedUrl());
		if ($rss && $rss['items_count'] > 0) {
			foreach ($rss['items'] as $item) {
	
				$hover = $item['description'];
				$title = unhtmlentities(str_replace('&apos;', '',$item['title']));
				$link  = $item['link'];
				
				// Sanitize the hover text to keep the javascript good
				$h = str_replace('&#039;', '', $hover);
            	$h = str_replace("'", '', $h);
            	$h = str_replace("\"", '', $h);
				$h = str_replace("&apos;", '', $h);
            	$h = str_replace(chr(13), '', $h);
            	$h = str_replace(chr(10), '', $h);
            	$hover = $h;
				
				$results[] = array('title' => $title, 'link' => $link, 'hover' => $hover);
			}
		}
		return array('id' =>  $rss_id, 'items' => $results);
	}
	
	
	public static function getScreenshots($movie_id) {
		try {
			
			if (!is_numeric($movie_id)) {
				throw new Exception('Movie id must be numeric');
			}
			
			$folder = VCDDB_BASE.DIRECTORY_SEPARATOR.'upload/screenshots/albums/'.$movie_id;
			
			if (!is_dir($folder)) {
				throw new VCDProgramException('Screenshot folder for movie does not exist.');
			}
			
			$files = array();
			$it = new DirectoryIterator($folder);
			foreach ($it as $file) {
				if (!$file->isDir()) {
					$files[] = $file->getFileName();
				}
			}
			
			return array('id' => $movie_id, 'files' => $files);
			
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public static function getRandomMovie($category_id, $seenlist=false) {
		try {
			
			$obj = MovieServices::getRandomMovie($category_id, $seenlist);
			$results = null;
			if ($obj instanceof cdObj) {
				$categoryObj = $obj->getCategory();
				$cover = $obj->getCover('thumbnail');
				$cover instanceof cdcoverObj ? $cover_id=$cover->getId() : $cover_id=-1;
				$results = array('title' => $obj->getTitle(),
					'id' => $obj->getID(),
					'category_id' => $categoryObj->getID(),
					'category' => $categoryObj->getName(true),
					'year' => $obj->getYear(),
					'cover_id' => $cover_id);
			} 
			
			return $results;
			
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
		}
	}
	
}

?>