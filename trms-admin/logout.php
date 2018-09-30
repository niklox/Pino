<?php
session_start();
$_SESSION["TermosCurrentUserPasswd"] = "";
$_SESSION["TermosCurrentUserID"] = 0;
header("Location: /trms-admin/index.php");
?>