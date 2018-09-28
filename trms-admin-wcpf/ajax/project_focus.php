<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["mtextid"]))$mtextid = $_REQUEST["mtextid"];
if(isset($_REQUEST["textcontent"]))$textcontent = $_REQUEST["textcontent"];

if($action == "viewissue"){
	viewIssue($cid);
}else if($action ==  "savetext"){
	saveText($mtextid, $textcontent, $cid);
}else{
	defaultAction();
}

function defaultAction(){

	$content = ContentGetAllInNode(404);
	print "	<table class=\"stdtable\">\n" .
			"	<tr><td><u>Issue</u></td><td><u>Start</ul></td><td><u>Last edited</ul></td></tr>";

	while($content = ContentGetNext($content)){
print	"<tr><td><a id=\"viewissue_".$content->getID()."\" href=\"#\">" .strip_tags($content->getTitle()) . "</a></td><td>".substr($content->getStartDate(),0,10). "</td><td>".substr($content->getArchiveDate(),0,10)."</td></tr>\n";	}
		print	"</table>\n";

}

function viewIssue($cid){

	$content = ContentGetByID($cid);
	print "<h5 class=\"imghead\">" . $content->getTitle() . "</h5>";
	print "<div><textarea id=\"issuecontent\" class=\"issuecontentbox\">" . $content->getContentText() . "</textarea></div>";
	print "<input type=\"hidden\" id=\"cid\" value=\"". $content->getID() ."\"/>";
	print "<input type=\"button\" name=\"\" id=\"savetext_".$content->getContentTextID()."\" value=\"Save changes\"/> <input type=\"button\" name=\"viewlist\" id=\"viewlist\" value=\"View list\"/>";

}

function saveText($mtextid, $textcontent, $cid){

	$mtext = MTextGetMTextForLanguage($mtextid, TermosGetCurrentLanguage());
	
	$mtext->setTextContent($textcontent);
	MTextUpdateTextContentCopyAllLanguages($mtext);
	
	viewIssue($cid);
}

?>
