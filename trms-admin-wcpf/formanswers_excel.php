<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["fid"]))$formid = $_REQUEST["fid"]; // formid

define("URL","/trms-admin/forms.php");

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $formid;

	if( UserHasPrivilege($admin->getID(), 19) ){

		defaultAction($formid);

	}else{
		print "No permission: 19." . PrivilegeGetName(19);
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction($formid){

	global $admin;

	$form = FormGetForm($formid);
	$formanswer = FormAnswerGetAllForForm($form->getID());

	print	"<div id=\"box_head_wide\">".$form->getName()."</div>";
	print	"<div id=\"box_body_wide\">";
	print	"<table>\n";

	$forminput = FormInputGetAllForForm($form->getID());
	print	"<tr><td>Date</td>\n";
	while($forminput = FormInputGetNext($forminput)){
		
		if($forminput->getTypeID() == 1 || $forminput->getTypeID() == 2){
			print "<td class=\"question\">" . FormInputGetQuestion( $forminput->getID(), $forminput->getTypeID() ) ."</td>";
		}
		else if($forminput->getTypeID() == 4){
			print "<td class=\"question\">" . $forminput->getQuestion() ."</td>";
		}
		else if($forminput->getTypeID() == 9){
			print "<td class=\"question\">" . $forminput->getTitle() ."</td>";
		}		

	}
	print	"</tr>\n";

	while($formanswer = FormAnswerGetNext($formanswer)){
		print	"<tr><td>" .$formanswer->getDateTime(). "</td>\n";

		$forminput = FormInputGetAllForForm($formanswer->getFormID());
		while($forminput = FormInputGetNext($forminput)){

			if($forminput->getTypeID() == 1 || $forminput->getTypeID() == 2){
			print "<td class=\"answer\">" . FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()). "</td>";
			}
			else if($forminput->getTypeID() == 4){
				print "<td class=\"answer\">" . FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()). "</td>";
			}
			else if($forminput->getTypeID() == 9){
				print "<td class=\"answer\">" . FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()). "</td>";
			}

		}
		print	"</tr>\n";
	}
	print	"</table>\n";
	print "</div>";
}

function htmlStart(){

    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Form Answers Excel</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css\" type=\"text/css\" media=\"all\" />\n" .
			"<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"/trms-admin/js/formanswer_admin.js\"></script>\n" .
			"</head>\n" .
			"<body>\n";
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}

?>