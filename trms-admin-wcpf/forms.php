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

	if( UserHasPrivilege($admin->getID(), 18) ){

		if($action == "editForm") 
			editForm($formid);
		else
			defaultAction();

	}else{
		print "User has not privilege: 18." . PrivilegeGetName(18);
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	

	print	'<div id="formselect" class="stdbox">' .
			'<select class="std_select" name="forms" id="forms">' .
			'<option value="0">'.MTextGet("selectForm").'</option>';

			$form = FormGetAll();

			while($form = FormGetNext($form)){
				print '<option value="'. $form->getID() .'" >' . $form->getName() . ' ('.$form->getID().')</option>';
			}
	
	print	'</select>'.
			
			'<input type="hidden" id="action" name="action" value="createForm">' .
			'<input type="input" class="std_input" id="formname" name="formname" size="30"/>' .
			'<input type="button" class="std_button" id="createForm" value="Create new form">' .
			
			'</div>';
	print	'<div id="box_head"></div>';
	print	'<div id="box_body"></div>';

}

function editForm($formid){

	print "edit";

}

function htmlStart(){

    print	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
			'<html>' .
			'<head>' .
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
			'<title>Termos Forms</title>' .
			'<link rel="stylesheet" href="css/termosadmin.css"/>' .
			'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/form_admin.js"></script>' .
			'</head>' .
			'<body>';
}

function htmlEnd(){
	
    print	'</div>' .
			'</body>' .
			'</html>';
}

?>