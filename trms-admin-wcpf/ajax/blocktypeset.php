<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';

DBcnx();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["position"]))$position = $_REQUEST["position"];
if(isset($_REQUEST["blocktypeid"]))$blocktypeid = $_REQUEST["blocktypeid"];
if(isset($_REQUEST["blocktypeoptionid"]))$blocktypeoptionid = $_REQUEST["blocktypeoptionid"];

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){

	$contenttext = ContentTextGet($cid, $position);
	
	if($action == "setblocktype")
			$contenttext->setFlag($blocktypeid);
	else if($action == "setblocktypeoption")
			$contenttext->setFlagII($blocktypeoptionid);
			
	ContentTextSave($contenttext);
	
	print 'done!';
	
}


?>