<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
?>
<!DOCTYPE html">

<html>
<head>
<title>Termos login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./css/termosadmin.css"/>
</head>
<body>
<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($admin)){

	print	'<div id="content">' .
			'<form action="setlanguage.php">' .
			'<input type="hidden" name="returnpath" id="returnpath" value="' . $_SERVER["SCRIPT_NAME"] . '"/>' .
			'<select name="languageid" id="languageid=">' .
			'<option value="'. TermosGetDefaultLanguage() .'">'. MTextGet("selectlanguage"). '</option>';
			
			$language = LanguageGetAll();

			while($language = LanguageGetNext($language)){
				if($language->getID() == TermosGetCurrentLanguage())
				print '<option value="'.$language->getID().'" selected>'. $language->getName() .'</option>';
				else
				print '<option value="'.$language->getID().'">'. $language->getName() .'</option>';
			}
	print	' &nbsp;<input type="submit" value="'. MTextGet("setlanguage") .'"/>';
	print	'</select>';

	print	'<div class="dashboardbox">' .
			'	<div class="dashboardbox_head">Snabblänkar</div>' .
			'	<div class="dashboardbox_body">' ;
	print	'<p>Lägga till produkter <a href="https://docs.google.com/document/pub?id=19vNEqKxzi9zRE2Y_pcMziYAOdnaJBkFJn6XM13Sqm4M" target="_new">lathund &#187;</a></p>';
	print	'	</div>' .
			'</div>';
	print '</div>';
}
else
{
	print '<div id=content>';
	print '<div id="loginbox">';
	
	print	
			'<form action="/trms-admin/logincheck.php" method="post"/>'.
			'Username or Email Address<br/><input type="text" name="AccountName" id="AccountName" size="28"/><br/>'.
			'Password<br/><input type="password" name="Password" id="Password" size="25"/><br/>'.
			'<input type="submit" id="Loginbtn" value="Login"/><br/>'.
			'</form>';	
			
			echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
	
	
	print '</div>';
	print '</div>';
} 
?>
</body>
</html>
