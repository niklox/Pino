<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
DBcnx();

if(isset($_REQUEST['blocktypeid'])) $blocktypeid = $_REQUEST['blocktypeid'];

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){

	print	'[{"optionValue":0, "optionDisplay": "Choose blocktype option"}';
	PrintJSON_BlocktypeOptions($blocktypeid); // Defined in functions.Content.php
	print	']';

}



?>



