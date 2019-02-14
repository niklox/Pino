<?php

/*
 * This updates the positions in the on Drag and Drop admin
 * file: /trms-admin/index.php
 * Called by content_admin.js on the Drag and Drop events
 * Data comes as JSON in the payload
 */
 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
DBcnx();

// Get JSON as a string from the re
$json = file_get_contents('php://input');
$data = json_decode($json, true);

foreach($data as $key => $value ){
	ContentUpdatePositionAtNode($value["cid"], $value["pid"], $value["position"]);
}

?>