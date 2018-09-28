<?
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];

if($action == "viewissue"){
	$content = ContentGetByID($cid);
	print $content->getContentText();
}else{

	$content = ContentGetAllInNode(404);
	print "	<table class=\"stdtable\">\n" ;

	while($content = ContentGetNext($content)){
		print	"<tr><td><a id=\"viewissue_".$content->getID()."\" href=\"#\">" .strip_tags($content->getTitle()) . "</a></td><td></td><td></td></tr>\n";
	}
		print	"</table>\n";
}
?>