<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["donationid"]))$donationid = $_REQUEST["donationid"]; 

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){	
	
	
	//global $action, $donationid;
	
	//if( UserHasPrivilege($admin->getID(), 18) ){

	if($action == "deletedonationrow")
		deleteDonationRow($donationid);
	else
		defaultAction($formid);

	//}else{
	//	print "User has not privilege: 18." . PrivilegeGetName(18);
	//}

}else print "Please login";

function defaultAction(){}

function deleteDonationRow($donationid){
	global $dbcnx;
	
	$sql = 'DELETE FROM PayPal_Payments WHERE ID = "'.$donationid.'"';
	mysqli_query($dbcnx, $sql);
}


?>