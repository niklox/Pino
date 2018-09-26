<?php

$uid = $_REQUEST['userid'];
$pwd = $_REQUEST['pwd'];
$cid = $_REQUEST['cid'];
$lid = $_REQUEST['language'];

session_start();

setcookie("TermosCurrentUserID", $uid, 0, '/');
setcookie("TermosCurrentUserPasswd", $pwd, 0, '/');
setcookie("TermosCurrentLanguage", $lid, 0, '/');

$_SESSION["TermosCurrentUserID"] = $uid;
$_SESSION["TermosCurrentUserPasswd"] = $pwd;

//print "Hey " . $uid . $pwd;
header("Location: http://" . $_SERVER["SERVER_NAME"] . "/?cid=". $cid);

?>
