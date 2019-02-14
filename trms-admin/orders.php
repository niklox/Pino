<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Orders.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Orders.php';
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"]; // orderid

define("URL","/trms-admin/orders.php");


print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $formid;

	//if( UserHasPrivilege($admin->getID(), 19) ){

		defaultAction();

	//}else{
	//	print "No permission";
	//}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	global $admin;

	print	"<div id=\"box_head\">Orders</div>";
	print	"<div id=\"box_body\"><div id=\"box_body_top\"> <input type=\"text\" class=\"dateinput\" name=\"deliverydate\" id=\"deliverydate\" value=\"\"/></div>";
	
	print	"<div id=\"searchbox\"><span class=\"whitehead\">" .MTextGet("searchorders"). "</span>\n";
	print	"Startdate 	<input type=\"text\" class=\"dateinput\" name=\"startdate\" id=\"startdate\" value=\"\"/> ";
	print	"&nbsp;&nbsp;Enddate 	<input type=\"text\" class=\"dateinput\" name=\"enddate\" id=\"enddate\" value=\"\"/>";
	
	print	" &nbsp;&nbsp;&nbsp; Orderstatus  <select id=\"orderstatus\" name=\"orderstatus\" class=\"\">";
	print	"<option value=\"-1\">Alla</option>" .
			"<option value=\"0\">Ej inkomna</option>" .
			"<option value=\"1\">Inkomna</option>" .
			"<option value=\"2\">Parkerade</option>" .
			"<option value=\"3\">Levererade</option>" .
			"<option value=\"4\">Slutförda</option>";
	print	"</select>";
	print	"&nbsp; <input type=\"button\" id=\"searchorders\" value=\"Search\"/>";
	print	"</div>\n";
			
	/*
	$orders = OrderGetAll();
	while($orders = OrderGetNext($orders)){
		print $orders->getID() . " Createddate: " . $orders->getCreatedDate() . "<br/>\n";
	}
	*/
	print	"<div id=\"orderlist\"></div>";
	print	"</div>"; 

}

function htmlStart(){

    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Orders</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<link rel=\"stylesheet\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css\" type=\"text/css\" media=\"all\" />\n" .
			"<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"/trms-admin/js/order_admin.js\"></script>\n" .
			"</head>\n" .
			"<body>\n";
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}

?>