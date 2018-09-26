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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Termos login</title>
<link rel="stylesheet" href="css/termosadmin.css"/>
</head>
<body>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($admin)){

	print	"<div id=\"content\">" .
			"<form action=\"setlanguage.php\">\n" .
			"<input type=\"hidden\" name=\"returnpath\" id=\"returnpath\" value=\"" . $_SERVER["SCRIPT_NAME"] . "\"/>" .
			"<select name=\"languageid\" id=\"languageid=\">\n" .
			"<option value=\"". TermosGetDefaultLanguage() ."\">". MTextGet("selectlanguage"). "</option>\n" ;
			$language = LanguageGetAll();

			while($language = LanguageGetNext($language)){
				if($language->getID() == TermosGetCurrentLanguage())
				print "<option value=\"".$language->getID()."\" selected>". $language->getName() ."</option>\n";
				else
				print "<option value=\"".$language->getID()."\">". $language->getName() ."</option>\n";
			}
	print	" <input type=\"submit\" value=\"". MTextGet("setlanguage") ."\"/>\n";
	print	"</select>\n";

	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">Snabblänkar</div>\n" .
			"	<div class=\"dashboardbox_body\">" ;

	print	"<p>Lägga till produkter <a href=\"https://docs.google.com/document/pub?id=19vNEqKxzi9zRE2Y_pcMziYAOdnaJBkFJn6XM13Sqm4M\" target=\"_new\">lathund &#187;</a></p>";

	print	"	</div>\n" .
			"</div>";

	print "</div>\n";
}
else
{
	print "<div id=\"content\">";
	print "Please login";
	print "</div>\n";
}
?>
</body>
</html>
