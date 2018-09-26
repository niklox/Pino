<?php


if(isset($_REQUEST["languageid"]))$languageid = $_REQUEST["languageid"];
if(isset($_REQUEST["returnpath"]))$returnpath = $_REQUEST["returnpath"];
//print $languageid . " " . $returnpath;

setcookie("TermosCurrentLanguage", $languageid , 0, '/');
header("Location: " . $returnpath);

?>
