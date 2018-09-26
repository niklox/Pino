<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
DBcnx();
$account = $_REQUEST['AccountName'];
$password = $_REQUEST['Password'];
$cid = $_REQUEST['cid'];

if($user = UserGetUserByAccountName($account))
{
	if( crypt($password, LEGAL_SALT) == $user->getPassword() ){

		$user->setPreviousdate($user->getLastdate());
		$user->setLastdate(date("Y-m-d H:i:s"));
		$user->setCounter($user->getCounter()+1);
		UserSave($user);

		print "Success! You're about to be logged in ....";
		print "<script>window.location='/trms-content/ajax/setcookie.php?userid=".$user->getID()."&pwd=".$user->getPassword()."&cid=". $cid . "&language=".TermosGetCurrentLanguage()."'</script>";
	}
	else{
		print "UserID and password does not match! <br/>";
		print "<script>window.location='/trms-content/ajax/setcookie.php?userid=&pwd=&cid= ". $cid . "'</script>";
	}
}
else{
	print "Log out!";
	print "<script>window.location='/trms-content/ajax/setcookie.php?userid=&pwd=&cid= ". $cid . "'</script>";
}

?>
