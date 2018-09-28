<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];


if(isset($recordid))

$org = OrganisationGetByID($recordid);

print $org->getOrgName();

?>