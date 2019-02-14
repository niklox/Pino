<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; 
if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"]; 
if(isset($_REQUEST["imgpos"]))$imgpos = $_REQUEST["imgpos"];
if(isset($_REQUEST["imghandle"]))$imghandle = $_REQUEST["imghandle"];

if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $cid, $imgpos, $imgcatid, $imghandle;
	
	if($action == "addImage")
		addImage($cid, $imghandle, $imgpos);
	else if($action == "removeImage")
		removeImage($cid, $imghandle, $imgpos);
	else
		defaultAction($cid, $imgpos, $imgcatid, $imghandle);

}else print "Please login";

function defaultAction($cid, $imgpos, $imgcatid, $imghandle){

	print	'<ul id="imagetabs">' .
			'	<li id="imgselect" class="active">Imagebank</li>' .
			'	<li id="imgupload">Upload image</li>' .
			'	<li id="imglink">Imagelink</li>' .
			'</ul>';

	print	'<form id="imageadmin">'; //cid: " . $cid . " imgcatid: " . $imgcatid . " imgpos: " . $imgpos . "handle ". $imghandle;
	
	print	'<select class="stdselect" name="imgcatid" id="imgcatid">' .
			'<option value="0">'. MTextGet("selectImgCat") . '</option>';
	
	$imgcat = ImageCategoryGetAllOrderByName();
	while($imgcat = ImageCategoryGetNext($imgcat)){
		print '<option value="'.$imgcat->getID().'" '.($imgcatid == $imgcat->getID() ?" selected":"").'>' . $imgcat->getName() . '</option>';
	}
	print	'</select>';

	if($imgcatid > 0){

		print	'<select class="stdselectmltp" name="images" id="images" size="10">';
		$img = ImageGetAllInCategoryOrderByHandle($imgcatid, 1);
		while($img = ImageGetNext($img)){
			print '<option value="'.$img->getHandle().'">'. $img->getHandle(). '</option>';
		}
		print	'</select>';
	}
	
	print	'<input type="button" class="std_button" id="imgselectbutton" value="'. MTextGet('selectThisImage') .'"/>';
	print	'<input type="button" class="std_button" id="imgdeletebutton" value="'. MTextGet('removeThisImage') .'"/>';

	print	'<div id="selectedimg">';
	
	$handle = ContentHasImageAtPosition($cid, $imgpos);
	if( $handle > -1 ){

		print	'<input type="button" id="imgdeletebutton" value="'. MTextGet('removeThisImage') .'"/>';

		$image = ImageGetByHandleAndLanguage($handle, -1);
		ImageCreateTag($image, 100, 1);

		print '<br/>ImageHandle: '. $handle . '&nbsp; <a style="color:red;text-decoration:underline" href="/trms-admin/rawimg.php?imgid='.$image->getID().'" target="_new">'.MTextGet("viewallsizes").' &#187;</a><br/>';
		$catid  = ImageGetCategoryID($image->getID());
		//print $catid;
		$imgcat = ImageCategoryGetByID($catid);
		print 'ImageCategory: ' . $imgcat->getName();
	}
	
	print	'</div>';
	print	'<input type="hidden" name="imgpos" id="imgpos" value="'.$imgpos.'"/>' .
			'<input type="hidden" name="cid" id="cid" value="'.$cid.'"/>' .
			'<input type="hidden" id="imghandle" name="imghandle" value="'. ($handle > -1 ? $handle : $imghandle ).'"/>';
	print	'</form>';
}

function addImage($cid, $imghandle, $imgpos){
	ImageAddToContent($cid, $imghandle, $imgpos);
}

function removeImage($cid, $imghandle, $imgpos){
	ImageDeleteFromContent($cid, $imghandle, $imgpos);
}

function loadscripts(){
	print '<script type="text/javascript" src="/trms-admin/js/content_image_select.js"></script>';
}
?>