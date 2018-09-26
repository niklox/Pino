<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Discussions.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Discussions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
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

	if( UserHasPrivilege($admin->getID(), 15) ){

		defaultAction();

	}else{
		print "No permission";
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	global $admin;

	print	"<div id=\"box_head\">".MTextGet("comments")."</div>";
	print	"<div id=\"box_body\"><div id=\"box_body_top\"></div>";
	
	print	"<div id=\"comments\">";
	print	"<table class=\"commentlist\">\n";
	print	"<tr><td class=\"date\">Date</td><td class=\"ip\">IP</td><td class=\"content\">Commented content</td><td class=\"comment\">Comment</td></tr>";

	$comments = DiscussionGetAllWithStatus(0);
	while($comments = DiscussionGetNext($comments)){

		$content = ContentGetByID($comments->getReferenceID());
		print "<tr class=\"comments\" id=\"commentrow_".$comments->getID()."\" value=\"" .$comments->getID() ."\"><td>" . $comments->getCreatedDate() . "</td><td>".$comments->getIPNumber()."</td><td>".MTextGet($content->getTitleTextID())."</td><td>".substr($comments->getText(), 0, 30)." ...</td></tr>\n";

	}
		
	print	"</table>\n";
	print	"</div>";
	print	"</div>"; 

}

function htmlStart(){

    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Dicussions</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css\" type=\"text/css\" media=\"all\" />\n" .
			"<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"/trms-admin/js/discussion_admin.js\"></script>\n" .
			"</head>\n" .
			"<body>\n";
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}

?>