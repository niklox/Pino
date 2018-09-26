<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];

if($action == "login"){

print	"<h3>Please login!</h3>" .
		"<form name=\"logon\" id=\"logon\" method=\"post\">" .
		"<input type=\"hidden\" name=\"cid\" value=\"" . $cid . "\"/>" .
		"Username<br/>" .
		"<input type=\"text\" name=\"AccountName\" id=\"AccountName\" value=\"\" size=\"30\"/><br/>" .
		"Password<br/>" .
		"<input type=\"password\" name=\"Password\" id=\"Password\" value=\"\" size=\"30\"/><br/>" .
		"<input type=\"submit\" id=\"login_btn\" value=\"Login\"/> <input type=\"button\" id=\"closelogin\" value=\"Close\" /> " .
		"</form>";


}else{

	print	"<h3>Logout?</h3>" .
		"<form name=\"logon\" id=\"logon\" method=\"post\">" .
		"<input type=\"hidden\" name=\"cid\" value=\"" . $cid . "\"/>" .
		"<input type=\"hidden\" name=\"AccountName\" id=\"AccountName\" value=\"0\" size=\"30\"/><br/>" .
		"<input type=\"hidden\" name=\"Password\" id=\"Password\" value=\"0\" size=\"30\"/><br/>" .
		"<input type=\"button\" id=\"closelogin\" value=\"Close\" /> <input type=\"submit\" id=\"logout_btn\" value=\"Logout\"/>" .
		"</form>";

}

?>
