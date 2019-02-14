<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["uid"]))$uid = $_REQUEST["uid"];
if(isset($_REQUEST["accountname"]))$accountname = $_REQUEST["accountname"];
if(isset($_REQUEST["textid"]))$textid = $_REQUEST["textid"];
if(isset($_REQUEST["languageid"]))$languageid = $_REQUEST["languageid"];
if(isset($_REQUEST["textcatid"]))$textcategoryid = $_REQUEST["textcatid"];
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $textid, $languageid, $textcategoryid;

	
	if( UserHasPrivilege($admin->getID(), 1) ){
	
		if($action == "insertlanguage" && isset($textid) && isset($languageid))
			insertLanguage( $textid,  $textcategoryid, $languageid );
		else if($action == "edit" && isset($textid))
			editMText($textid, $textcategoryid);
		else if ($action == "savetext" && isset($textid))
			saveMText($textid, $textcategoryid, $languageid);
		else if ($action == "deletemtextlanguage" && isset($textid))
			deleteMTextLanguage($textid, $textcategoryid, $languageid);
		else if ($action == "deletemtext" && isset($textid))
			deleteMText($textid, $textcategoryid);
		else if ($action == "newtext" && isset($textid))
			createMText($textid, $textcategoryid);
		else if ($action == "changetextcategory" && isset($textid))
			changeTextCategory($textid, $textcategoryid);
		else
			defaultAction($textcategoryid);

	}else{
		print "No permission";
	}
}
else
{
	print "Please login!";
}

htmlEnd();

$action = "";

function defaultAction( $textcategoryid ){

	print "<div class=\"stdbox_800\">\n" .
			  "<form>\n<select name=\"textcatid\"><option>". MTextGet('selectTextCat') . "\n";

		$textcategory = TextCategoryGetAll();
		while($textcategory = TextCategoryGetNext($textcategory))
		{
			if($textcategoryid == $textcategory->getID())
				print "<option value=\"".$textcategory->getID()."\" selected>". MTextGet($textcategory->getTextCategoryNameTextID()) . "</option>\n";
			else
				print "<option value=\"".$textcategory->getID()."\">". MTextGet($textcategory->getTextCategoryNameTextID()) . "</option>\n";
		}

		print "</select>\n" .
			  " <input type=\"submit\" value=\"Visa\"/>\n" .
			  "</form>\n" .
			  "</div>\n";

		if($textcategoryid)
		displayTextCategory($textcategoryid);
}

function displayTextCategory($textcategoryid){
	
	$mtext = new MText;
	$textcategory = new TextCategory;
	$textcategory = TextCategoryGetByID($textcategoryid);

	if($mtext = MTextGetAllInCategory($textcategoryid))
	{
		print "<div id=\"mtextlist_head\">All texts in " . MTextGet($textcategory->getTextCategoryNameTextID()) .			  "\n<form>\n" .
			  "<input type=\"hidden\" name=\"textcatid\" value=\"" . $textcategoryid . "\">\n" .
			  "<input type=\"hidden\" name=\"action\" value=\"newtext\">\n" .
			  "<input type=\"text\" name=\"textid\" value=\"\" maxlength=\"20\">\n" .
			  "<input type=\"submit\" value=\"" . MTextGet('createMText') . "\">\n" .
			  "</form>\n" .
			  "</div>\n" .
			  "<div id=\"mtextlist\">" .
			  "<table class=\"adminlist\">\n";

		
		while($mtext = MTextGetNext($mtext))
		{
			$counter++;
			print "<tr><td class=\"column20\">" . 
				  "<a href=\"/trms-admin/mtext.php?action=edit&textid=". $mtext->getID() . "&textcatid=". $mtext->getTextCategoryID() ."\"><img src=\"images/edit_mini.gif\" border=\"0\" alt=\"edit mtext\" align=\"absmiddle\"/></a>  " 
				  . $mtext->getID() . 
				  "</td><td><img src=\"images/delete_mini.gif\" border=\"0\" alt=\"delete mtext\" align=\"absmiddle\" onclick=\"deleteMText('" .  $mtext->getID(). "'," . $mtext->getTextCategoryID() . ")\"/> <i>" . $mtext->getTextContent() . 
				  "</i>";
			print "</td></tr>\n";
		}
		print "<tr><td>".$counter."</td></tr>";
		print "</table>\n"  .
			  "</div>\n";
	}
}

function editMText($textid, $textcategoryid){
	
	$language = new Language;
	$mtext = new MText;


	$textcategory = TextCategoryGetByID($textcategoryid);

	print "<div id=\"mtextlist_head\">Text ID: " . $textid . "<input type=\"button\" class=\"whtbtn\" value=\"Display all texts in " . MTextGet($textcategory->getTextCategoryNameTextID()) . " \" onclick=\"location.href='/trms-admin/mtext.php?textcatid=" . $textcategoryid . "'\"/></div>\n";
	print "<div class=\"stdbox_800\">\n".
		  "<form>\n" .
		  "<input type=\"hidden\" name=\"textid\" value=\"" . $textid . "\">\n" .
		  "<input type=\"hidden\" name=\"textcatid\" value=\"" . $textcategoryid . "\">\n" .
		  "<input type=\"hidden\" name=\"action\" value=\"insertlanguage\">\n" .
		  "<select name=\"languageid\">\n" .
		  "<option value=\"". $language->getID() ."\">" . MTextGet('selectlanguage') . "</option>\n";
	
	$language = LanguageGetAll();
	while($language = LanguageGetNext($language)){
		print "<option value=\"" . $language->getID() . "\">" . $language->getLanguageNameTextID() . "</option>\n";
	}
	
	print "</select>\n<input type=\"submit\" value=\"" . MTextGet('addlanguage') . "\"></form>";

	print "<form>\n";
	$textcategory = TextCategoryGetAll();
	print "<input type=\"hidden\" name=\"action\" id=\"action\" value=\"changetextcategory\">\n" .
		  "<input type=\"hidden\" name=\"textid\" id=\"textid\" value=\"" . $textid . "\">\n";
	print "<select id=\"textcatid\" name=\"textcatid\">\n";
	while($textcategory = TextCategoryGetNext($textcategory))
	{
		if($textcategoryid == $textcategory->getID())
			print "<option value=\"".$textcategory->getID()."\" selected>". MTextGet($textcategory->getTextCategoryNameTextID()) . "</option>\n";
		else
			print "<option value=\"".$textcategory->getID()."\">". MTextGet($textcategory->getTextCategoryNameTextID()) . "</option>\n";
	}
	print "</select>\n";
	print "<input type=\"submit\" value=\"" . MTextGet("setTextCategory") . $textid . "\"/>";
	print "</form>\n";
	
	print "<br/><br/>\n";
	
	$mtext = MTextGetMText($textid);
	while($mtext = MTextGetNext($mtext)){

		if($language = LanguageGetByID($mtext->getLanguageID()))
		print	MTextGet($language->getLanguageNameTextID()) . 
				"\n<form>\n<input type=\"hidden\" name=\"action\" value=\"savetext\"/>\n" .
				"<input type=\"hidden\" name=\"textid\" value=\"" .  $mtext->getID() . "\"/>\n" .
				"<input type=\"hidden\" name=\"textcatid\" value=\"" .  $mtext->getTextCategoryID() . "\"/>\n" .
				"<input type=\"hidden\" name=\"languageid\" value=\"" .  $mtext->getLanguageID() . "\"/>\n" .
				"<a href=\"/trms-admin/mtext.php?action=deletetext&textid=" . $mtext->getID() ."&languageid=" . $mtext->getLanguageID() . "&textcatid=". $mtext->getTextCategoryID() ."\">\n" .
				"<img src=\"/img/delete_mini.gif\" border=\"0\"/></a><br/>\n" .
				"<textarea rows=\"7\" cols=\"95\" name=\"textcontent\">" . $mtext->getTextContent() . "</textarea><br/>\n" .
				"<input type=\"submit\" value=\"" . MTextGet('save') . "\"/> <input type=\"button\" value=\"" . MTextGet('deletetextforlang') . " " . MTextGet($language->getLanguageNameTextID()) . "\" onclick=\"deleteMTextForLanguage('".$mtext->getID()."',".$mtext->getLanguageID().",'".MTextGet($language->getLanguageNameTextID())."',".$mtext->getTextCategoryID().")\"/>\n</form>\n<br/><br/>\n";
	}
	print "</div>\n";
}

function saveMText($textid, $textcategoryid, $languageid){

	$mtext = new MText;
	$textcontent = $_REQUEST["textcontent"];

	$mtext = MTextGetMTextByLanguage($textid, $languageid);
	$mtext->setTextContent($textcontent);
	
	MTextUpdateTextContent($mtext);
	editMText($textid, $textcategoryid, $languageid);
}

function createMText($textid, $textcategoryid){

	$mtext = new MText;
	
	if($mtext = MTextGetMText($textid))
	{
		print "<script>alert('textid exists')</script>\n";
	}
	else
	{
		$newmtext = new MText;
		$newmtext->setID($textid);
		$newmtext->setTextCategoryID($textcategoryid);
		$newmtext->setLanguageID(1);
		$newmtext->setTextPosition(0);
		$newmtext->setTextContent("no text");

		//print $newmtext->getID() . $newmtext->getTextCategoryID() . $newmtext->getLanguageID() . //$newmtext->getTextPosition() . $newmtext->getTextContent();
		
		 MTextUpdateTextContentCopyAllLanguages($newmtext);
	}
	editMText($textid, $textcategoryid);
}


function deleteMTextLanguage($textid, $textcategoryid, $languageid){
	
	MTextDeleteLanguage($textid, $languageid);
	editMText($textid, $textcategoryid);
}

function deleteMText($textid, $textcategoryid){
	
	MTextDelete($textid);
	defaultAction($textcategoryid);
}

function changeTextCategory($textid, $textcategoryid){
	
	//print $textid . " " . $textcategoryid;
	MTextChangeTextCategory($textid, $textcategoryid);
	editMText($textid, $textcategoryid);
	
}

function insertLanguage( $textid, $textcategoryid, $languageid ){
	
	$mtext = new MText;
	if($mtext = MTextGetMTextByLanguage($textid, $languageid))
	{
		
		editMText($textid, $textcategoryid);
		//header("Location: /trms-admin/mtext.php?action=edit&textid=". $textid . "&message=exists");
	}
	else
	{
		$newmtext = new MText;
		
		$newmtext->setID($textid);
		$newmtext->setTextCategoryID($textcategoryid);
		$newmtext->setLanguageID($languageid);
		$newmtext->setTextPosition("0");
		$newmtext->setTextContent(".");
		MTextInsertLanguage($newmtext);
	
		editMText($textid, $textcategoryid);
	}

}

function htmlStart(){

	print 
		  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
		  "<html>\n" .
		  "<head>\n" .
		  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
		  "<title>Termos MText</title>\n" .
		  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
		  "<script>\n" .
		  "function deleteMTextForLanguage(mtextid, languageid, language, textcatid){\n" .
		  "	if(confirm('" . MTextGet("deleteMTextForLang") . "  ' + language + '?'))\n" .
		  "	location.href = 'mtext.php?action=deletemtextlanguage&textid=' + mtextid + '&languageid=' + languageid + '&textcatid=' + textcatid;\n" .
		  "}\n" .
		  "function deleteMText(mtextid, textcatid){\n" .
		  "	if(confirm('" . MTextGet("deleteMText") . "  ' + mtextid + '?'))\n" .
		  "	location.href = 'mtext.php?action=deletemtext&textid=' + mtextid + '&textcatid=' + textcatid;\n" .
		  "}\n" .
		  "</script>" .
		  "</head>\n" .
		  "<body>\n";
	}

function htmlEnd(){
	
	print "</div>" .
		  "</body>\n" .
		  "</html>\n";
}



?>

