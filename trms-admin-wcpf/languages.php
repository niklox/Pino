<?php	
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
DBcnx();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["uid"]))$uid = $_REQUEST["uid"];
if(isset($_REQUEST["accountname"]))$accountname = $_REQUEST["accountname"];
if(isset($_REQUEST["languageid"]))$languageid = $_REQUEST["languageid"];
if(isset($_REQUEST["languagenametextid"]))$languagenametextid = $_REQUEST["languagenametextid"];

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print '<div id="content">';

if(isset($admin))
{
	global $action, $languageid, $languagenametextid, $textcategoryid;

	if( UserHasPrivilege($admin->getID(), 42) ){

		if($action == "editlanguage")
			editLanguage( $languageid );
		else if($action == "createlanguage")
			createLanguage($languagenametextid);
		else if ($action == "deletelanguage")
			deleteLanguage($languageid);
		else if ($action == "reallydeletelanguage")
			reallyDeleteLanguage($languageid);
		else
			defaultAction($textcategoryid);

	}else{
		print "User has not privilege: 42." . PrivilegeGetName(42);
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print "<div class=\"stdbox_600\">" .
		  "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createlanguage\">\n" .
		  "<input type=\"input\" id=\"languagenametextid\" name=\"languagenametextid\" size=\"30\"/>\n" .
		  "<input type=\"submit\" value=\"" . MTextGet("createLanguage") . "\"/>\n" .
		  "</form>\n".
		  "</div>\n" .
		  "<div id=\"userlist_head\">Languages</div>\n";


	print "<div id=\"userlist\">";

	$languages = LanguageGetAll();

	while($languages = LanguageGetNext($languages)){
		
		print "<img src=\"/trms-admin/images/delete_mini.gif\" onclick=\"deleteLanguage(".$languages->getID().",'". LanguageName($languages->getID()) ."')\" /> &nbsp;" . MTextGet($languages->getLanguageNameTextID()) . "<br/>\n";

	}
	print "</div>\n";
		  
}

function editLanguage($languageid){
	
	print "Edit language";		
}

function createLanguage($languagenametextid){

	$mtext = new MText;
	$language = new Language;
	
	$languages = LanguageGetAll();
	while($languages = LanguageGetNext($languages)){
		$mtext->setID($languagenametextid);
		$mtext->setTextCategoryID(2);
		$mtext->setLanguageID($languages->getID());
		$mtext->setTextPosition(0);
		$mtext->setTextContent($languagenametextid);
		MTextUpdateTextContent($mtext);
	}
	
	$language->setLanguageNameTextID($languagenametextid);
	LanguageNew($language);
	defaultAction();
}

function deleteLanguage($languageid){
	
	print "Really delete the language ".  LanguageName($languageid) . "?";
	print " <a href=\"/trms-admin/languages.php?action=reallydeletelanguage&languageid=".$languageid."\">&#187; YES</a>";
	print " <a href=\"/trms-admin/languages.php\">&#187; NO</a>";

}

function reallyDeleteLanguage($languageid){
	
	global $dbcnx;
	
	// commented Just for security. This is a VERY critical action!!!! // 
	
	//$sql = "DELETE FROM PageContentCountries WHERE CountryID =" . $languageid;
	//mysqli_query($dbcnx, $sql);
	//$sql = "DELETE FROM PageCountries WHERE CountryID =" . $languageid;
	//mysqli_query($dbcnx, $sql);
	//$sql = "DELETE FROM DocumentCategoryCountries WHERE CountryID =" . $languageid;
	//mysqli_query($dbcnx, $sql);
	//$sql = "DELETE FROM Texts WHERE LanguageID =" . $languageid;
	//mysqli_query($dbcnx, $sql);
	//$sql = "DELETE FROM Languages WHERE LanguageID =" . $languageid;
	//mysqli_query($dbcnx, $sql);
	
	
	//$sql = "DELETE Images For language"
	defaultAction();

}

function htmlStart(){

	print 
		  '<!DOCTYPE html>' .
		  '<html>' .
		  '<head>' .
		  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
		  '<title>Termos Languages</title>' .
		  '<link rel="stylesheet" href="css/termosadmin.css"/>' .
		  '<script>' .
		  'function deleteLanguage(languageid, language){' .
		  '	if(confirm("' . MTextGet("deleteLanguage") . ' " + language))' .
		  ' location.href = "languages.php?action=deletelanguage&languageid= + languageid;"' .
		  '}' .
		  '</script>' .
		  '</head>' .
		  '<body>';
	}

function htmlEnd(){
	
	print "</div>" .
		  "</body>\n" .
		  "</html>\n";
}



?>

