<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';

DBcnx();

$account = $_REQUEST['AccountName'];
$password = $_REQUEST['Password'];

if($user = UserGetUserByAccountName($account))
{
	//setcookie("TermosCurrentUserID", $user->getID(), 0, '/');
	
	if( crypt($password, LEGAL_SALT) == $user->getPassword()){
		
		

		$user->setPreviousdate($user->getLastdate());
		$user->setLastdate(date("Y-m-d H:i:s"));
		$user->setCounter($user->getCounter()+1);
		UserSave($user);

		//setcookie("TermosCurrentUserPasswd", $user->getPassword(), 0, '/');
		//setcookie("TermosCurrentLanguage", 5, 0, '/');

		$qstr = "userid=".$user->getID()."&pwd=".$user->getPassword();

		/*
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, 'http://www.worldschildrensprize.org/trms-admin/setcookie.php');
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $qstr);
		curl_exec ($c);
		curl_close ($c); */

		//print "<a href=\" /trms-admin/setcookie.php?userid=".$user->getID()."&pwd=".$user->getPassword()."\">Log in</a>";

		//print "trms-admin/setcookie.php?userid=".$user->getID()."&pwd=".$user->getPassword()."&language=".TermosGetCurrentLanguage();

		print "<script>window.location='/trms-admin/setcookie.php?userid=".$user->getID()."&pwd=".$user->getPassword()."&language=".TermosGetCurrentLanguage()."'</script>";
		
		//header("Location: /trms-admin/index.php");
	}
	else
		print "UserID and password does not match! <br/>";
		//. crypt($password, LEGAL_SALT). " " .$user->getPassword();
}
else
	print "No user with the accountname: ". $account;
?>
