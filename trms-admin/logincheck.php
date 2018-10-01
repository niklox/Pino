<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';

$account = $_REQUEST['AccountName'];
$password = $_REQUEST['Password'];
DBcnx();

if($user = UserGetUserByAccountName($account)){
	
	if( crypt($password, LEGAL_SALT) == $user->getPassword()){
	
		$user->setPreviousdate($user->getLastdate());
		$user->setLastdate(date("Y-m-d H:i:s"));
		$user->setCounter($user->getCounter()+1);
		UserSave($user);

		$_SESSION["TermosCurrentUserID"] = $user->getID();
		$_SESSION["TermosCurrentUserPasswd"] = $user->getPassword();
		
		header("Location: /trms-admin/index.php");
	}
	else
		print "UserID and password does not match! <br/>";
}
else
	print "No user found with the accountname: ". $account;
?>
