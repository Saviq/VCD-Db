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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<?php
require_once(VCDDB_BASE.'/classes/external/nusoap.php');

class PornstarProxy {

	static private $currCollection;
	
	static public function doHandshake() {
		try {
			
			// Get the endpoint
			self::discoverServiceUri();
			
			// Report success
			return "Got Service: " . self::getWSDL();
		
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
		}
	}

	/**
	 * Discover the SOAP Service from the vcddb.konni.com website.
	 * And then store the wsdl url in session
	 *
	 */
	static private function discoverServiceUri() {
		try {
			
			$base = "http://vcddb.konni.com/ws.vcddb.xml";
			$xml = simplexml_load_file($base);
		
			if (!$xml) {
				throw new Exception('Could not locate WSDL uri, try again later.');
			}
		
			$wsdluri = (string)$xml->wsdl;
			$_SESSION['vcddb-wsdl']	= $wsdluri;
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the Pornstar Sync Server Endpoint
	 *
	 * @return string | The WSDL endpoint url
	 */
	static private function getWSDL() {
		try {
			
			if (!isset($_SESSION['vcddb-wsdl'])) {
				throw new Exception('No Service Endpoint has been specified');
			} else {
				return $_SESSION['vcddb-wsdl'];
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the update list for the current letter
	 *
	 * @param int $letter | The Firstletter of stars to check for updates
	 */
	static public function getUpdateList($letter) {
		try {
		
			
			$Updater = new pornstarUpdater(self::getWSDL());
			$list = $Updater->getListByLetter($letter);
			
			// Store the list in session
			self::storeList($list);
			
			//VCDUtils::write('/home/konni/www/vcddb/upload/'.$letter.'.txt', print_r($list, true));
			
			$totalSize = 0;
			foreach ($list as $arr)	{ $totalSize += sizeof($arr);}
			
			return array(
				'incoming' => sizeof($list['incoming']),
				'outgoing' => sizeof($list['outgoing']),
				'supdate'  => sizeof($list['serverupdate']),
				'cupdate'  => sizeof($list['clientupdate']),
				'csupdate' => sizeof($list['clientserverupdate']),
				'entries'  => $totalSize,
				'letter'   => $letter
			);
			
			
			
			
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
		}
	}
	
	/**
	 * Sync the updates found by calling the getUpdateList()
	 *
	 * @param int $index | The index of the item in the update list to process
	 */
	static public function getUpdates($index) {
		try {
		
			
			// Get the list from session
			$list = self::retrieveList();
			
			
			$arrListTypes = array('incoming','outgoing','serverupdate','clientupdate','clientserverupdate');
			$listIndex = 0;
			foreach ($arrListTypes as $listType) {
				$currArray = $list[$listType];
				for ($i=0;$i<sizeof($currArray);$i++,$listIndex++) {
					if ($index == $listIndex){
						$Updater = new pornstarUpdater(self::getWSDL());
						return $Updater->processSyncRequest($listType, $currArray[$i]);
						//return "Action: " . $listType . " pornstar: " . $currArray[$i]['name'];
					}
				}
			}
			
			throw new Exception('Requested index not found');

			
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
		}		
	}
	
	/**
	 * Store the current SOAP results in memory
	 *
	 * @param array $soaplist | The incoming SOAP message
	 */
	static private function storeList($soaplist) {
		try {
			
			$_SESSION['starCollection'] = $soaplist;
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the current SOAP message from memory
	 *
	 * @return array 
	 */
	static private function retrieveList() {
		try {
			
			if (!isset($_SESSION['starCollection'])) {
				throw new Exception('No list found in memory');
			} else  {
				return $_SESSION['starCollection'];
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}

class pornstarUpdater {
	
	
	/**
	 * The nusoapClient handle
	 *
	 * @var nusoapclient
	 */
	private $soapClient;
	//private $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	//private $letters = array('A');
	
	
	
	public function __construct($wsdl)
	{
		$this->soapClient = new nusoapclient($wsdl, true);
	}	
	
	
	
	public function getListByLetter($letter) {
		try {
		
			PornstarServices::disableErrorHandler();
 			$stars = PornstarServices::getPornstarsByLetter($letter, false);
 			$soapList = $this->createListRequest($stars);
 			
 			// Send the current list
 			$arrResults = $this->sendMyCurrentNameList($soapList);
 			
 			return $arrResults;
 			
 			//$this->handleListResponse($arrResults);
 			
		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	public function processSyncRequest($action, $pornstarData) {
		try {
			
			$param = $this->prepareSyncRequest($action, $pornstarData);
			
			switch ($action) {
				case 'incoming':
					$response = $this->soapClient->call('GetPornstarByName', $param);
					$obj = new pornstarObj(array('',$response['name'], $response['website'], '', $response['biography']));
										
					
					$imgData = $response['image'];
					// Check if this is really an image
					if (strlen($imgData) > 10) {
						//VCDUtils::write('/home/konni/www/vcddb/upload/images.txt', $obj->getName() . base64_decode($imgData) . "\n", true);
						$imagename = VCDUtils::generateUniqueId().'.jpg';
						if ($this->writeImage(BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$imagename,  $imgData)) {
							$obj->setImageName($imagename);
						}
					}
					
					
					
					PornstarServices::disableErrorHandler();
					PornstarServices::addPornstar($obj);
					return 'incoming ' . $obj->getName();
					
					break;
					
				case 'outgoing':
					
					VCDUtils::write('/home/konni/www/vcddb/upload/sending.txt', print_r($param, true) , true);
					
					$response = $this->soapClient->call('AddPornstar', $param);
					if ($response == true) {
						return 'outgoing: successfully sent ' . $pornstarData['name'] . ' to master server';
					} else {
						return 'outgoing: failed to send ' . $pornstarData['name'] . ' to master server';
					}
					break;
					
				case 'serverupdate':
					
					break;
			
				case 'clientupdate':
					$response = $this->soapClient->call('GetPornstarByName', $param);
					$localObj = PornstarServices::getPornstarByName($pornstarData['name']);
					
					$updateList = array();
					
					if ($pornstarData['biography'] == 'client') {
						$localObj->setBiography($response['biography']);
						$updateList[] = 'biography';
					}
					
					if ($pornstarData['website'] == 'client') {
						$localObj->setHomePage($response['website']);
						$updateList[] = 'website';
					}
					
					if ($pornstarData['image'] == 'client') {
						$imgData = $response['image'];
						// Check if this is really an image
						if (strlen($imgData) > 10) {
							$imagename = VCDUtils::generateUniqueId().'.jpg';
							if ($this->writeImage(BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$imagename,  $imgData)) {
								$localObj->setImageName($imagename);
							}
							$updateList[] = 'image';
						}
					}
					
					if (sizeof($updateList) > 0) {
						PornstarServices::disableErrorHandler();
						PornstarServices::updatePornstar($localObj);
					}
					
					
					return 'clientupdate ' . $localObj->getName() . ' updates: ' . implode(',', $updateList);
					break;
					
				case 'clientserverupdate':
					
					break;
					
				default: throw new Exception('Undefined action:' + $action);
			}
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	private function prepareSyncRequest($action, $data) {
		try {
			
			switch ($action) {
				case 'incoming':
				case 'clientupdate':
					return array('PornstarName' => $data['name']);
					break;
					
				case 'outgoing':
					PornstarServices::disableErrorHandler();
					$pornstarObj = PornstarServices::getPornstarByName($data['name']);
					$param = array(
						'name' => utf8_encode($pornstarObj->getName()),
						'biography' =>  utf8_encode($pornstarObj->getBiography()),
						'website' => utf8_encode($pornstarObj->getHomepage()),
						'image' => base64_encode(file_get_contents(BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$pornstarObj->getImageName()))
						);
					return array('Pornstar' => $param);
					break;
					
				case 'serverupdate':
					
					break;
			
					
				case 'clientserverupdate':
					
					break;
					
				default: throw new Exception('Undefined action:' + $action);
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	private function handleListResponse($arrServerResults) {
		try {
			
			$arrIncoming = $arrServerResults['incoming'];
			$arrOutgoing = $arrServerResults['outgoing'];
			$arrServerUpdate = $arrServerResults['serverupdate'];
			$arrClientUpdate = $arrServerResults['clientupdate'];
			$arrClientServerUpdate = $arrServerResults['clientserverupdate'];
			
			
			print "<br>Send = " . sizeof($arrOutgoing) . " , Get = " . sizeof($arrIncoming) . " , ClientServerUpdate = " . sizeof($arrClientServerUpdate)
					. " , ClientUpdate = " . sizeof($arrClientUpdate) . " , ServerUpdate = " . sizeof($arrServerUpdate);

			
			$totalSize = 0;
			foreach ($arrServerResults as $arr)	{ $totalSize += sizeof($arr);}
			print "<br>Total size = " .$totalSize;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Send the current pornstarlist beginning with specified character
	 *
	 * @param array $arrSoapList | The Porntstar array wrapped as a Soap Message
	 * @return array | Array including 5 arrays
	 */
	private function sendMyCurrentNameList($arrSoapList) {
		
		try {
		
			$listParam = $arrSoapList;
			$param = array('PornstarStructs' => $listParam);
			$response = $this->soapClient->call('GetPornstarUpdateList', $param);
			
			
			$arrIncoming = array();
			$arrOutgoing = array();
			$arrServerUpdate = array();
			$arrClientUpdate = array();
			$arrClientServerUpdate = array();
			
				
			if (!$this->soapClient->fault) {
				
				if (is_array($response)) {
					
					foreach ($response as $responseItem) {
						
						switch ($responseItem['action']) {
							case 'getFromServer':
								$arrIncoming[] = $responseItem;
								break;
								
							case 'sendToServer':
								$arrOutgoing[] = $responseItem;
								break;
														
							case 'clientserverUpdate':
								$arrClientServerUpdate[] = $responseItem;
								break;
								
							case 'clientUpdate':
								$arrClientUpdate[] = $responseItem;
								break;
								
							case 'serverUpdate':
								$arrServerUpdate[] = $responseItem;
								break;
						
							default: break;
						}
					}
				} 
							
				return array(
					'incoming' 			 => $arrIncoming,
					'outgoing'			 => $arrOutgoing,
					'serverupdate' 		 => $arrServerUpdate,
					'clientupdate' 		 => $arrClientUpdate,
					'clientserverupdate' => $arrClientServerUpdate
				);
				
				
				
			} else {
				throw new Exception(print_r($response, true));
			}
		
		
		
		} catch (Exception $ex) {
			throw $ex;
		}
		
				
	}
	
	private function createListRequest($arrPornstars) {
	
		$arrSoapList = array();
		
		foreach ($arrPornstars as $pornstarObj) {
			
			$website = "0";
			$image = "0";
			if (strlen(trim($pornstarObj->getHomePage())) > 0) { $website = "1"; }
			if (strlen(trim($pornstarObj->getImageName())) > 0) { $image = "1";}
			
			$pObj = PornstarServices::getPornstarByID($pornstarObj->getId());
			$biolength = strlen($pObj->getBiography());
			
			
			$arrSoapList[] = array(
				'name' => utf8_encode($pornstarObj->getName()),
				'biographylength' =>  $biolength,
				'haswebsite' => $website,
				'hasimage' => $image
				);
			
		}
		
		return $arrSoapList;
		
	}
	
	
	/**
	 * Write the image thumbnail to disk
	 *
	 * @param string $filename | The image filename
	 * @param string $content | The file data
	 * @return bool | returns true if successful
	 */
	private function writeImage($filename, $content) {
		try {
			
			if (empty($filename)) {
				throw new Exception('No filename given for target file.');
			}
			
			if (empty($content)) {
				throw new Exception('cant write to file: ' . $filename ." , no content specified.");
			}
		
			$fp = fopen($filename,"w");
			$b = fwrite($fp,$content);
			fclose($fp);
			if($b != -1){
				return true;
			} else {
				throw new Exception('Could not write to disk, check permissons');
			}
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	
	
}



?>