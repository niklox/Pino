<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["country_id"]))$countryid = $_REQUEST["country_id"];
if(isset($_REQUEST["latitude"]))$latitude = $_REQUEST["latitude"];
if(isset($_REQUEST["longitude"]))$longitude = $_REQUEST["longitude"];


if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){
	
	if($action == "savecoords")
			saveCoords($countryid, $latitude, $longitude);
	else if($action == "deletecoords")
			deleteCoords($cid, $recordid);
}

function saveCoords($countryid, $latitude, $longitude){
	CountrySaveCoords($countryid, $latitude, $longitude);
}

function deleteCoords($countryid){
	CountryDeleteCoords($countryid);
}
?>