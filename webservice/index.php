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
 * @subpackage WebService
 * @version $Id$
 */
?>
<?
require_once('../classes/external/nusoap.php');
require_once('../classes/includes.php');

$valid = false;
$userObj = null;


if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
	
	$userObj = VCDAuthentication::authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
	
	if ($userObj instanceof userObj) {
		$valid = true;
		
		// Add userObj to session
		$_SESSION['user'] = $userObj;
	}
}

if (!$valid) {
	
	// Check if we are supposed to log this event ..
	if (VCDLog::isInLogList(VCDLog::EVENT_SOAPCALL  )) {
		VCDLog::addEntry(VCDLog::EVENT_SOAPCALL , "Invalid authentication");
	}
	
    header('HTTP/1.0 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="VCD-db Webservice"');
    echo "Authenticaion failed. Hit F5 to refresh and try again.";
        
    exit();
} 

// Check if we are supposed to log this event ..
if (VCDLog::isInLogList(VCDLog::EVENT_SOAPCALL  )) {
	VCDLog::addEntry(VCDLog::EVENT_SOAPCALL , "Successful Authentication");
}


$server = new nusoap_server;
$server->configureWSDL('VCDdbService','http://vcddb.konni.com');
$server->wsdl->schemaTargetNamespace = 'http://vcddb.konni.com';


$server->wsdl->addComplexType(
	'VCDMovieStruct',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name'=>'id','type'=>'xsd:string'),
		'title' => array('name'=>'title','type'=>'xsd:string'),
		'category_id' => array('name'=>'category_id','type'=>'xsd:string'),
		'category' => array('name'=>'category','type'=>'xsd:string'),
		'year' => array('name'=>'year','type'=>'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'ArrayOfVCDMovieStruct',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:VCDMovieStruct[]')),
	'tns:VCDMovieStruct'
);


$server->wsdl->addComplexType(
	'VCDBorrowerStruct',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name'=>'id','type'=>'xsd:string'),
		'owner_id' => array('name'=>'owner_id','type'=>'xsd:string'),
		'name' => array('name'=>'name','type'=>'xsd:string'),
		'email' => array('name'=>'email','type'=>'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'ArrayOfVCDBorrowerStruct',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:VCDBorrowerStruct[]')),
	'tns:VCDBorrowerStruct'
);



$server->wsdl->addComplexType(
	'VCDWLStruct',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name'=>'id','type'=>'xsd:string'),
		'title' => array('name'=>'title','type'=>'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'ArrayOfVCDWLStruct',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:VCDWLStruct[]')),
	'tns:VCDWLStruct'
);


$server->wsdl->addComplexType(
	'VCDLoanStruct',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'loan_id' => 	 array('name'=>'loan_id','type'=>'xsd:string'),
		'cd_id' => 		 array('name'=>'cd_id','type'=>'xsd:string'),
		'cd_title' => 	 array('name'=>'cd_title','type'=>'xsd:string'),
		'borrower_id' => array('name'=>'borrower_id','type'=>'xsd:string'),
		'dateout' => 	 array('name'=>'dateout','type'=>'xsd:string'),
		'datein' => 	 array('name'=>'datein','type'=>'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'ArrayOfVCDLoanStruct',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:VCDLoanStruct[]')),
	'tns:VCDLoanStruct'
);



$server->wsdl->addComplexType(
	'VCDDetailedMovieStruct',
	'complexType',
	'struct',
	'all',
	'',
	array(

		'id' 		  	=> array('name'=>'id','type'=>'xsd:string'),
		'title' 	  	=> array('name'=>'title','type'=>'xsd:string'),
		'category_id' 	=> array('name'=>'category_id','type'=>'xsd:string'),
		'category'	    => array('name'=>'category','type'=>'xsd:string'),
		'year'			=> array('name'=>'year','type'=>'xsd:string'),
		'media'			=> array('name'=>'media','type'=>'xsd:string'),
		'date'			=> array('name'=>'date','type'=>'xsd:string'),
		'imdbid'		=> array('name'=>'imdbid','type'=>'xsd:string'),
		'imdbtitle'		=> array('name'=>'imdbtitle','type'=>'xsd:string'),
		'director'		=> array('name'=>'director','type'=>'xsd:string'),
		'country'		=> array('name'=>'country','type'=>'xsd:string'),
		'imdbcategory'	=> array('name'=>'imdbcategory','type'=>'xsd:string'),
		'runtime'		=> array('name'=>'runtime','type'=>'xsd:string'),
		'rating'		=> array('name'=>'rating','type'=>'xsd:string'),
		'plot'			=> array('name'=>'plot','type'=>'xsd:string'),
		'cast'			=> array('name'=>'cast','type'=>'xsd:string'),
		'cover'			=> array('name'=>'cover','type'=>'xsd:string'),
	)
);

function getMovieById($movie_id) {
	
	try {
	
	global $userObj;
	$user_id = $userObj->getUserID();
	$VCDClass = new vcd_movie();
	
	$vcdObj = $VCDClass->getVcdByID($movie_id);
	
	
	if ($vcdObj instanceof vcdObj ) {
				
		$imdb_id	 = "";
		$imdb_title  = "";
		$director	 = "";
		$country     = "";
		$imdbcat	 = "";
		$runtime 	 = "";
		$rating 	 = "";
		$plot 		 = "";
		$cast 		 = "";
		$cover 		 = "";
		
		$imdb = $vcdObj->getIMDB();
		if ($imdb instanceof imdbObj ) {
			$imdb_id	 = $imdb->getIMDB();
			$imdb_title  = $imdb->getTitle();
			$director	 = $imdb->getDirector();
			$country     = $imdb->getCountry();
			$imdbcat	 = $imdb->getGenre();
			$runtime 	 = $imdb->getRuntime();
			$rating 	 = $imdb->getRating();
			$plot 		 = $imdb->getPlot();
			$cast 		 = $imdb->getCast(false);
		} elseif ($vcdObj->isAdult()) {
			$PORNClass = new vcd_pornstar();
			$arr = $PORNClass->getPornstarsByMovieID($vcdObj->getID());
			foreach ($arr as $pornstar) {
				$cast .= $pornstar->getName() . "\n";
			}
		}
		
		$coverObj = $vcdObj->getCover("thumbnail");
		if (!is_null($coverObj)) {
			$cover = $coverObj->getCoverAsBinary();
		}
		
		$mediaTypes = "";
		$mediaCDs = "";
		$arrCopies = $vcdObj->getInstancesByUserID($user_id);
		$arrMediaTypes = $arrCopies['mediaTypes'];
		$arrNumcds = $arrCopies['discs'];
		
		$arrTypes = array();
		
		foreach ($arrMediaTypes as $mediaTypeObj) {
			array_push($arrTypes, $mediaTypeObj->getDetailedName());
		}
		
		$mediaTypes = implode(":",$arrTypes);
		$mediaCDs = implode(":",$arrNumcds);
		$categoryObj = $vcdObj->getCategory();
		
		return array(
			'id' 		  	=> $vcdObj->getID(),
			'title' 	  	=> $vcdObj->getTitle(),
			'category_id' 	=> $categoryObj->getID(),
			'category'	    => $categoryObj->getName(true),
			'year'			=> $vcdObj->getYear(),
			'media'			=> $mediaTypes,
			'date'			=> $mediaCDs,
			'imdbid'		=> $imdb_id,
			'imdbtitle'		=> $imdb_title,
			'director'		=> $director,
			'country'		=> $country,
			'imdbcategory'	=> $imdbcat,
			'runtime'		=> $runtime,
			'rating'		=> $rating,
			'plot'			=> $plot,
			'cast'			=> $cast,
			'cover'			=> $cover
			
			
		);
	}
	
	} catch (Exception $e) {
		throw new soap_fault($e->getMessage());
	}
	
}


function getMyMovies() {
	
	global $userObj;
	$user_id = $userObj->getUserID();
	$SETTINGClass = new vcd_settings();
	$VCDClass = new vcd_movie();
	$arrMovies = $VCDClass->getAllVcdByUserId($user_id, true);
	
	foreach ($arrMovies as $vcdObj) {
		$aMOV[]= array('id' => $vcdObj->getID(),
					   'title' => $vcdObj->getTitle(),
					   'category_id' => $vcdObj->getCategoryID(),
					   'category' => $SETTINGClass->getMovieCategoryByID($vcdObj->getCategoryID())->getName(true),
					   'year' => $vcdObj->getYear()
					   );
	}
	return $aMOV;
}

function getMyBorrowers() {
	
	global $userObj;
	$user_id = $userObj->getUserID();
	$SETTINGClass = new vcd_settings();
	$arrBorrowers = $SETTINGClass->getBorrowersByUserID($user_id);	
	
	foreach ($arrBorrowers as $borrowerObj) {
				
		$aBorr[]= array('id' => $borrowerObj->getID(),
					   'owner_id' => $user_id,
					   'name' => $borrowerObj->getName(),
					   'email' => $borrowerObj->getEmail()
					   );
	}
	return $aBorr;
}

function getMyLoans() {
	global $userObj;
	$user_id = $userObj->getUserID();
	$SETTINGClass = new vcd_settings();
	$arrLoans = $SETTINGClass->getLoans($user_id, true);	
	
	foreach ($arrLoans as $loanObj) {
		
		if ($loanObj->isReturned()) {
			$din = date("d/m/Y",$loanObj->getDateIn());
		} else {
			$din = "";
		}
		
		$aLoans[]= array('loan_id' => $loanObj->getLoanID(),
						 'cd_id' => $loanObj->getCDID(),
					     'cd_title' => $loanObj->getCDTitle(),
					     'borrower_id' => $loanObj->getBorrowerID(),
					     'dateout' => date("d/m/Y",$loanObj->getDateOut()),
					     'datein' => $din
					     );
	}
	return $aLoans;
}

function addBorrower($name, $email) {
	try {
	
		global $userObj;
		$user_id = $userObj->getUserID();
		$SETTINGClass = new vcd_settings();
		$obj = new borrowerObj(array('',$user_id, $name, $email));
		$SETTINGClass->addBorrower($obj);
		
	} catch (Exception $e) {
		throw new soap_fault('Error in adding borrower');
	}
	
}

function addLoan($borrower_id, $movie_id) {
	try {
		
		$SETTINGClass = new vcd_settings();
		$SETTINGClass->loanCDs($borrower_id, array($movie_id));
		
	} catch (Exception $e) {
		throw new soap_fault($e->getMessage());
	}
}

function returnLoan($loan_id) {
	try {
		
		global $userObj;
		$user_id = $userObj->getUserID();
		$SETTINGClass = new vcd_settings();
		$SETTINGClass->loanReturn($loan_id);
		
	} catch (Exception $e) {
		throw new soap_fault($e->getMessage());
	}
}

function getMyWishList() {
	try {
		global $userObj;
		$user_id = $userObj->getUserID();
		$SETTINGClass = new vcd_settings();
		$arrWishlist = $SETTINGClass->getWishList($user_id);
		
		foreach ($arrWishlist as $item) {
			$aWL[]= array('id' 	  => $item[0],
						  'title' => $item[1]
					   		);
			}
		
		return $aWL;
		
	} catch (Exception $e) {
		throw new soap_fault($e->getMessage());
	}
}

function removeFromWishlist($movie_id) {
	try {
		
		global $userObj;
		$user_id = $userObj->getUserID();
		$SETTINGClass = new vcd_settings();
		$SETTINGClass->removeFromWishList($movie_id, $user_id);
		
	} catch (Exception $e) {
		throw new soap_fault($e->getMessage());
	}
}


$server->register('getMyMovies',array(),array('return'=>'tns:ArrayOfVCDMovieStruct'),'http://vcddb.konni.com');
$server->register('getMovieById',array('movie_id'=>'xsd:string'),array('return'=>'tns:VCDDetailedMovieStruct'),'http://vcddb.konni.com');
$server->register('getMyBorrowers',array(),array('return'=>'tns:ArrayOfVCDBorrowerStruct'),'http://vcddb.konni.com');
$server->register('getMyLoans',array(),array('return'=>'tns:ArrayOfVCDLoanStruct'),'http://vcddb.konni.com');
$server->register('addBorrower',array('name'=>'xsd:string', 'email'=>'xsd:string'),array(),'http://vcddb.konni.com');
$server->register('returnLoan',array('loan_id'=>'xsd:string'),array(),'http://vcddb.konni.com');
$server->register('addLoan',array('borrower_id'=>'xsd:string', 'movie_id'=>'xsd:string'),array(),'http://vcddb.konni.com');
$server->register('removeFromWishlist',array('movie_id'=>'xsd:string'),array(),'http://vcddb.konni.com');
$server->register('getMyWishList',array(),array('return'=>'tns:ArrayOfVCDWLStruct'),'http://vcddb.konni.com');


// finally call the service method to initiate the transaction and send the response
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
