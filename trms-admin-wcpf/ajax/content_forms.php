<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();

loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; 
if(isset($_REQUEST["formid"]))$formid = $_REQUEST["formid"]; 

if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $cid, $formid;
	
	if($action == "addform")
		addForm($cid, $formid);
	

	else
		defaultAction($cid, $formid);

}else print "Please login";

function defaultAction($cid, $formid){

	print	"<h5 id=\"openforms\" class=\"texthead_u\">" . MTextGet("addform") . "</h5>";
	
	
	
	$formhandle = FormInContent($cid);
	//print	$formhandle . " ". $cid . "<br/>";
	
	print	"<select class=\"formselect\" name=\"formid\" id=\"formid\">" .
			"<option value=\"0\">" . MTextGet("selectform") . "</option>";

	$form = FormGetAll();

	while($form = FormGetNext($form)){
		print	"<option class=\"formoption\" value=\"".$form->getHandle()."\" ".($formhandle==$form->getHandle()?"selected":"").">" . $form->getName() . "</option>";
	}
	print	"</select>" . $formid;
}

function addForm($cid, $formid){
	//print $cid . " " . $formid;

	FormAddToContent($cid, $formid, 0);
	defaultAction($cid, $formid);
}

function loadscripts(){
	//print "<script type=\"text/javascript\" src=\"/trms-admin/js/content_image_select.js\"></script>\n";
}
?>