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

		defaultAction();

	}else{
		print "No permission: 19." . PrivilegeGetName(19);
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	global $admin, $dbcnx;

	print	'<div id="formselect" class="stdbox">' .
			'<select class="std_select" name="forms" id="forms">' .
			'<option value="0">'.MTextGet("selectForm").'</option>';

			$form = FormGetAll();

			while($form = FormGetNext($form)){
				
				
				if( FormHasFormAnswers($form->getID()) > 0  ){
					if(strstr($form->getName(), "Global Vote") && UserHasPrivilege($admin->getID(), 31) == false )continue;
					print '<option value="'. $form->getID() .'" >' . $form->getName() . '</option>';
				}
			}
	
	print	'</select>' .
			'</div>';
	
	print	'<div id="box_head"></div>';
	print	'<div id="box_body"><div id="box_body_top"></div><div id="formanswerlist"></div></div>'; 

}

/*function viewFormAnswers(){

	print "view";

}*/

function htmlStart(){

    print	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
			'<html>' .
			'<head>' .
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
			'<title>Termos Form Answers</title>' .
			'<link rel="stylesheet" href="css/termosadmin.css"/>' .
			'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/formanswer_admin.js"></script>' .
			'</head>' .
			'<body>';
}

function htmlEnd(){
	
    print	'</div>' .
			'</body>' .
			'</html>';
}

?>