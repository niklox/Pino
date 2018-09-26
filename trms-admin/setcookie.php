<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';

DBcnx();
$uid = $_REQUEST['userid'];
$pwd = $_REQUEST['pwd'];
$cid = $_REQUEST['cid'];

session_start();

setcookie("TermosCurrentUserID", $uid, 0, '/');
setcookie("TermosCurrentUserPasswd", $pwd, 0, '/');
setcookie("TermosCurrentLanguage", TermosGetCurrentLanguage(), 0, '/');

$_SESSION["TermosCurrentUserID"] = $uid;
$_SESSION["TermosCurrentUserPasswd"] = $pwd;

header("Location: http://". $_SERVER["SERVER_NAME"] . "/trms-admin/index.php");

?>
