<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; 
if(isset($_REQUEST["imgpos"]))$imgpos = $_REQUEST["imgpos"];
if(isset($_REQUEST["nid"]))$nid = $_REQUEST["nid"];

if(isset($_REQUEST["imglink"]))$imglink = $_REQUEST["imglink"];
if(isset($_REQUEST["target"]))$target = $_REQUEST["target"];



if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $cid, $imgpos, $nid, $imglink, $target;
	
	if($action == "savelink")
		 saveLink($cid, $imgpos, $imglink, $target);
	else if($action == "deletelink")
		 deleteLink($cid, $imgpos);
	else
		 defaultAction($cid, $imgpos);

}else print "Please login";

function defaultAction($cid, $imgpos){
	
	
	
	if($imglink = ImageLinkGet($cid, $imgpos)){
		$link = $imglink->getURL();
		$target = $imglink->getTarget();
	}else{
		$link = "";
		$target = "";
	}


	
	print "<div id=\"imglinkadmin\">";
	print	"<ul id=\"imagetabs\">" .
			"	<li id=\"imgselect\">Imagebank</li>" .
			"	<li id=\"imgupload\">Upload image</li>" .
			"	<li id=\"imglink\" class=\"active\">Imagelink</li>" .
			"</ul>";
	
	
	ImageGet($cid, $imgpos, "imglinkadmin");
	
	print	"<select id=\"webnodetree\"></select><br/>\n";
	print	MTextGet("imagelink") . "<br/>";
	print	"<input type=\"text\" id=\"imglinkurl\" value=\"" . $link . "\"/><br/>\n";
	print	MTextGet("imagelinktarget") . "<br/>";
	
	print	"<select id=\"imglinktarget\">\n" .
			"<option value=\"0\" " . ($target == 0 ?' SELECTED':'') .">".MTextGet("samewindow")."</option>\n" .
			"<option value=\"1\" " . ($target == 1 ?' SELECTED':'') .">".MTextGet("newwindow")."</option>\n" . 
			"</select>\n";

	print	"<input type=\"button\" id=\"savelink\" value=\"".MTextGet("saveimagelink")."\"/> <input type=\"button\" id=\"deletelink\" value=\"".MTextGet("deleteimglink")."\"/>";

	print	"<input type=\"hidden\" name=\"imgpos\" id=\"imgpos\" value=\"".$imgpos."\"/>" .
			"<input type=\"hidden\" name=\"cid\" id=\"cid\" value=\"".$cid."\"/>";
	
	print	"</div>\n";
}

function saveLink($cid, $imgpos, $imagelink, $target){

	$contentimglink = new ContentImageLink;
	//print "cid: " . $cid . " pos: " . $imgpos  . " imglink " . $imagelink  . " target " . $target;

	$contentimglink->setContentID($cid);
	$contentimglink->setPosition($imgpos);
	$contentimglink->setURL($imagelink);
	$contentimglink->setTitle("no title");
	$contentimglink->setTarget($target);
		
	ImageLinkSave($contentimglink);

	defaultAction($cid, $imgpos);
}

function deleteLink($cid, $imgpos){
	ImageLinkDelete($cid, $imgpos);
	defaultAction($cid, $imgpos);
}

function loadscripts(){
	print "<script type=\"text/javascript\" src=\"/trms-admin/js/content_image_link.js\"></script>\n";
}
?>