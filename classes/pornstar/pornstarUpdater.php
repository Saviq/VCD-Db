<?php
require_once(VCDDB_BASE.'/classes/external/nusoap.php');

class PornstarProxy {

	//static private $wsdl = "http://bis-konnz:88/projects/vcddb-ws/index.php?wsdl";
	static private $wsdl = "http://konni/vcddb-ws/index.php?wsdl";
	
	
	static public function doHandshake() {
		try {
		
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
		
			
			$Updater = new pornstarUpdater(self::$wsdl);
			$list = $Updater->getListByLetter($letter);
			
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
		
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());
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
	private $letters = array('A');
	
	
	
	public function __construct($wsdl)
	{
		$this->soapClient = new nusoapclient($wsdl, true);
	}	
	
	
	
	public function getListByLetter($letter) {
		try {
		
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
								$arrIncoming[] = $responseItem['name'];
								break;
								
							case 'sendToServer':
								$arrOutgoing[] = $responseItem['name'];
								break;
														
							case 'clientserverUpdate':
								$arrClientServerUpdate[] = $responseItem['name'];
								break;
								
							case 'clientUpdate':
								$arrClientUpdate[] = $responseItem['name'];
								break;
								
							case 'serverUpdate':
								$arrServerUpdate[] = $responseItem['name'];
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
	
	
	
	
	
	
}



?>