<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["paramname"]))$paramname = $_REQUEST["paramname"];
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $paramname;

	if($action == "editPrivilege") 
		editParameter($paramname);
	else if ($action == "createPrivilege")
		createParameter();
	else
		defaultAction();
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print "<div class=\"stdbox_600\">" .
		  "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createParameter\">";

	print "<input type=\"input\" id=\"paramname\" name=\"paramname\" size=\"30\"/>";
	
	print "<input type=\"submit\" value=\"Create new parameter\"></form>" .
	      "</div>" .
		  "<div id=\"userlist_head\">All parameters" .
		  "</div>\n" .
		  "<div id=\"userlist\">" .
		  "<table class=\"adminlist\">\n";

		  $parameters = ParameterGetAll();
		  
		  while($parameters = ParameterGetNext($parameters)){
				
				print "<tr><td>" . $parameters->getName()  . "</td><td>" .$parameters->getValue() . "</td></tr>";
		  }
		  
		  
		
	print "</table>\n" .
	      "</div>\n";


}

function editParameter($paramid){

	print	"edit parameter";
}

function createParameter($privname){

	print	"create parameter";

}



function htmlStart(){

    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
	  "<html>\n" .
	  "<head>\n" .
	  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
	  "<title>Termos Parameters</title>\n" .
	  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
	  "</head>\n" .
	  "<body>\n";
}

function htmlEnd(){
	
    print "</div>" .
	  "</body>\n" .
	  "</html>\n";

}