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
set_time_limit(0);

class PornstarProxy {

	static private $currCollection;
	
	/**
	 * Initial handshake to check for Service status
	 *
	 * @return string
	 */
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
					}
				}
			}
			
			throw new Exception('Requested index not found');

			
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

/**
 * pornstarUpdater handles the SOAP communications with the master server.
 *
 */
class pornstarUpdater {
	
	
	/**
	 * The nusoapClient handle
	 *
	 * @var nusoapclient
	 */
	private $soapClient;
		
	
	/**
	 * Class constructor
	 *
	 * @param string $wsdl | The url to the WSDL file to use
	 */
	public function __construct($wsdl)
	{
		$this->soapClient = new nusoapclient($wsdl, true);
	}	
	
	
	
	/**
	 * Sends clients pornstar list and gets back servers pornstar list.
	 * The list contains instructions on what to update/get/send
	 *
	 * @param char $letter | The character to seek by.  For example "A"
	 * @return array | Returns the SOAP results from the Remote server
	 */
	public function getListByLetter($letter) {
		try {
		
			PornstarServices::disableErrorHandler();
 			$stars = PornstarServices::getPornstarsByLetter($letter, false);
 			$soapList = $this->createListRequest($stars);
 			
 			// Send the current list
 			$arrResults = $this->sendMyCurrentNameList($soapList);
 			
 			return $arrResults;
 			
 			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Process a single request before it is sent to the Remote Server.
	 *
	 * @param string $action | The action to perform on the server
	 * @param array $pornstarData | The SOAP strucy containing the pornstar info and instructions
	 * @return array | Returns a status array for the Ajax UI to display
	 */
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
						$imagename = VCDUtils::generateUniqueId().'.jpg';
						if ($this->writeImage(BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$imagename,  $imgData)) {
							$obj->setImageName($imagename);
						}
					}
										
					PornstarServices::disableErrorHandler();
					PornstarServices::addPornstar($obj);
					
					return array('action' => 'Incoming', 'message' => $obj->getName());
					
					break;
					
				case 'outgoing':
					
					$response = $this->soapClient->call('AddPornstar', $param);
					if ($response == true) {
						$msg = 'Successfully sent ' . $pornstarData['name'] . ' to master server';
					} else {
						$msg = 'Failed to send ' . $pornstarData['name'] . ' to master server';
					}
					
					return array('action' => 'Outgoing', 'message' => $msg);
					
					break;
					
				case 'serverupdate':
					
					$response = $this->soapClient->call('UpdatePornstar', $param);
					if ($response == true) {
						$msg = 'Successfully sent ' . $pornstarData['name'] . ' to master server';
					} else {
						$msg = 'Failed to send ' . $pornstarData['name'] . ' to master server';
					}
					
					return array('action' => 'Server Update', 'message' => $msg);
					
					
					break;
			
				case 'clientupdate':
					$response = $this->soapClient->call('GetPornstarByName', $param);
					PornstarServices::disableErrorHandler();
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
						PornstarServices::updatePornstar($localObj);
					}
					
					
					return array('action' => 'Client update', 'message' => $localObj->getName() . ' Updates: ' . implode(', ', $updateList));
					
					break;
					
				case 'clientserverupdate':
					
					break;
					
				default: throw new Exception('Undefined action:' . $action);
			}
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Create the Correct SOAP parameters to send to the Remote Server
	 *
	 * @param string $action | The action to perform on the server
	 * @param array $data | The data containing instructions from the Remote Server on what to do
	 * @return array | The SOAP parameters to send to the Remote Server
	 */
	private function prepareSyncRequest($action, $data) {
		try {
			
			switch ($action) {
				case 'incoming':
				case 'clientupdate':
					return array('PornstarName' => $data['name']);
					break;
					
				case 'outgoing':
				case 'serverupdate':
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

					
				case 'clientserverupdate':
					
					break;
					
				default: throw new Exception('Undefined action:' . $action);
			}
			
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
	
	/**
	 * The Message that is sent to the Remote Server to tell the server what data the Client has
	 *
	 * @param array $arrPornstars | The clients local pornstar collection subset
	 * @return array | SOAP message to deliver to the Remote Server
	 */
	private function createListRequest($arrPornstars) {
		try {
		
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
			
		} catch (Exception $ex) {
			throw $ex;
		}
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