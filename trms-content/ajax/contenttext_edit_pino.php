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
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
DBcnx();
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; 
if(isset($_REQUEST["textid"]))$textid = $_REQUEST["textid"]; 
if(isset($_REQUEST["textcontent"]))$textcontent = $_REQUEST["textcontent"]; 
if(isset($_REQUEST["languageid"]))$languageid = $_REQUEST["languageid"]; 

if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $cid, $textid;

	if( UserHasPrivilege($admin->getID(), 26) || UserHasPriviledgeForLanguage($admin->getID(), TermosGetCurrentLanguage() ) ){
		
		if($action == "editText")
			defaultAction($cid, $textid);
		else if($action == "saveText")
			saveText($cid, $textid);
			//print "Testing function " . date();
		else
			defaultAction($cid, $textid);
	}else{
		$language = LanguageGetByID(TermosGetCurrentLanguage());
		print "No privilege to edit texts in " . $language->getName();
	}

}else print "Please login";

function defaultAction($cid, $textid){
	
	print	'<div id="#output"></div>';
	print	'<form method="post" id="editcontenttext" action="/trms-content/ajax/contenttext_edit.php" >' .
			'<input type="hidden" id="cid" name="cid" value="' . $cid . '"/>' .
			'<input type="hidden" id="textid" name="textid" value="' . $textid . '"/>' .
			'<input type="hidden" id="action" name="action" value="saveText"/>' .
			'<input type="hidden" id="languageid" name="languageid" value="' . TermosGetCurrentLanguage() . '"/>';
	
	print 	'<select id="tags">'.
			'<option value="0">Välj tag</option>'.
			'<option value="1">p</option>'.
			'<option value="2">i</option>'.
			'<option value="3">.cerise-anfang</option>'.
			'<option value="4">h3</option>'.
			'<option value="5">Artikelrubrik &lt;h4 class="pino-artikelrubrik"&gt;rubrik&lt;/h4&gt;</option>'.
			'<option value="6">a</option>'.
			'<option value="7">.topingress</option>'.
			'<option value="8">br</option>'.
			'<option value="9">.navyheader</option>'.
			'<option value="10">rensa</option>'.
			'</select>';
			
	print 	'<textarea name="textcontent" id="textcontent" class="frontent-text-editor">';
	print	MTextGet($textid);
	print	'</textarea>';
	print	'<input type="button" class="std_button" id="textedit" value="'. MTextGet("save") .'">' .
			'</form>';
}

function saveText($cid, $textid){
	global $textcontent, $languageid, $admin;
		
	$mtext = MTextGetMTextForLanguage($textid, $languageid);
	$mtext->setTextContent($textcontent);
	MTextUpdateTextContent($mtext);

	if( $content = ContentGetByID($cid) ) {
		$content->setAuthorID($admin->getID());
		$content->setArchiveDate(date('Y-m-d H:i:s'));
		ContentSave($content);
	}
}

function loadscripts(){
	print	'<script type="text/javascript" src="/trms-content/js/contenttext_edit_pino.js"></script>';
	print 	'<style type="text/css">.frontent-text-editor{width:98%;height:420px;padding:7px;margin:10px 30px 10px 0;font-family:Helvetica, Arial;font-size:1.1em}</style>';
}

?>