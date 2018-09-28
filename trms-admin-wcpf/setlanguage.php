<?php

session_start();

if(isset($_REQUEST["languageid"]))$languageid = $_REQUEST["languageid"];
if(isset($_REQUEST["returnpath"]))$returnpath = $_REQUEST["returnpath"];
if(isset($_REQUEST["indianchoice"]))$indianchoice = $_REQUEST["indianchoice"];
//print $languageid . " " . $returnpath;

$_SESSION['TermosCurrentLanguage'] = $languageid;

if($indianchoice == 1) $_SESSION['indianchoice'] = $true;

//setcookie("TermosCurrentLanguage", $languageid , 0, '/');
//setcookie("TermosCurrentLanguage", $languageid , time() + (86400 * 300), '/');

header("Location: " . $returnpath);

?>