<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
DBcnx();
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["path"]))$path = $_REQUEST["path"];

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){

	ContentDelete($cid);
	print "<script>location.href='".$path."';</script>";
}else{
	print "Please login";
}

?>
