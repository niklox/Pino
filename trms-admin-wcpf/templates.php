<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
DBcnx();

define("URL", "/trms-admin/templates.php");

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["tmplid"]))$tmplid = $_REQUEST["tmplid"]; 
if(isset($_REQUEST["templatename"]))$templatename = $_REQUEST["templatename"];
if(isset($_REQUEST["templatedescription"]))$templatedescription = $_REQUEST["templatedescription"];

$maxlevel = 20;
$positions = 30;
$parentid = 0;

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $tmplid, $templatename, $templatedescription;

	if( UserHasPrivilege($admin->getID(), 10) ){

		if($action == "editTemplate") 
			editTemplate($tmplid);	
		else if ($action == "saveTemplate")	
			saveTemplate($tmplid);				
		else if ($action == "createTemplate")
			createTemplate($templatename);	
		else if ($action == "deleteTemplate")	
			deleteTemplate($tmplid);	
		else
			defaultAction();
	
	}else{
		print "User has not privilege: 10." . PrivilegeGetName(10);
	}
}
else
{
	print "Please login!";

}

htmlEnd();

function defaultAction(){
	
	print	"<div class=\"stdbox_600\">\n" .
			"<form>\n<input type=\"hidden\" id=\"action\" name=\"action\" value=\"createTemplate\">\n" .
			"<input type=\"input\" id=\"templatename\" name=\"templatename\" size=\"30\"/>\n" .
			"<input type=\"submit\" value=\"Create new template\">\n" .
			"</form>\n" . 
			"</div>\n";

	print	"<div id=\"list_head\">Templates</div>\n";
	print	"<div id=\"list_body\">";
	print	"<ul>\n";

	$template = TemplateGetAll();
	while($template = TemplateGetNext($template)){
		print "<li>".$template->getID()." <a href=\"" . URL . "?action=editTemplate&tmplid=" . $template->getID() . "\">" . $template->getName() . "</a>  <img src=\"images/delete_mini.gif\" onclick=\"deleteTemplate(" . $template->getID() . ",'" .  $template->getName() . "')\" border=\"0\"/></li>";
	}
	print	"</ul>\n";
	print	"</div>\n";
}

function editTemplate($tmplid){

	$template = TemplateGetByID($tmplid);

	print	"<div id=\"box_head\">Template: " . $template->getName() . "</div>\n";

	print	"<form method=\"post\" enctype=\"multipart/form-data\">\n" .
			"<input type=\"hidden\" name=\"action\" value=\"saveTemplate\"/>\n" .
			"<input type=\"hidden\" name=\"$tmplid\" value=\"" . $tmplid . "\"/>\n";

	print	"<div id=\"box_body\" class=\"clearfix\">";
	print	"<div class=\"boxes\">";

	print	"<input type=\"hidden\" name=\"tmplid\" value=\"" . $template->getID() . "\"/>";
	
	print	"<label for=\"templatename\">Templatename: </label><br/><input type=\"text\" name=\"templatename\" value=\"" .  $template->getName() . "\" size=\"39\"/><br/>";
	print	"<label for=\"templatedescription\">Description: </label><br/><textarea rows=\"15\" cols=\"43\" name=\"templatedescription\">".$template->getDescription()."</textarea><br/><br/>";

	
	print	"<b>Current number of intances: ". ContentCountAllWithTemplate($template->getID()) ."</b><p>";
	$content = ContentGetAllWithTemplate($template->getID());
	while($content = ContentGetNext($content)){
		print "<a href=\"/". $content->getPermalink() .".html\">> " . strip_tags($content->GetTitle()) . "</a> ".NodeGetName($content->getDefaultNodeID())." ".$content->getDefaultNodeID()." ".NodeGetPermalink($content->getDefaultNodeID()). " ".$content->getID()."<br/>\n";
		//print "<a href=\"/trms-admin/content?action=editContent&cid=".$content->getID()."&nid=".$content->getDefaultNodeID()."\">> " . strip_tags($content->GetTitle()) . "</a><br/>\n";
	}
	print	"</p>";
	print	"</div>\n";
	
	print	"<div class=\"boxes\">\n";
	
	if($template->getStatus() == 0){
		print	"Textblocks <select name=\"templatetexts\">";
		for($i=0; $i<26; $i++)
			print	"<option value=\"".$i."\"". ($i == $template->getTexts()?"selected":"") . ">".$i."</option>\n";
		print	"</select>\n";

		print	"&nbsp;Images <select name=\"templateimages\">";
		for($i=0; $i<26; $i++)
			print	"<option value=\"".$i."\"". ($i == $template->getImages()?"selected":"") . ">".$i."</option>\n";
		print	"</select>\n";
	}else{
		print "Textblocks: " . $template->getTexts() . " Images: " .  $template->getImages() . "\n";
		print	"<input type=\"hidden\" name=\"templatetexts\" value=\"" . $template->getTexts() . "\"/>\n" ;
		print	"<input type=\"hidden\" name=\"templateimages\" value=\"" . $template->getImages() . "\"/>\n";
	}

	
	print	" &nbsp;Locked <input type=\"checkbox\" name=\"templatestatus\" value=\"1\" ".($template->getStatus() > 0 ?"checked":"")."><br/><br/>\n";
	
	if($image = ImageGetByHandleAndLanguage($template->getImageHandle(), -1))
		ImageCreateTag($image, 1000, 0);
	print	"<br/>\n" ;
	print	MTextGet("selectImgFile") . "<input type=\"file\" id=\"file\" name=\"file\" size=\"30\"/><br/><br/>\n";
	print	"<input type=\"submit\" value=\"save template\"/>\n";
	print	"</div>\n";
	print	"</form>\n";
	print	"</div>\n";

}

function createTemplate($templatename){
	$template = new Template;
	
	$mtext = MTextNewInCategory("templateTexts", $templatename);
	MTextUpdateTextContentCopyAllLanguages($mtext);
	$template->setNameTextID($mtext->getID());

	$mtext = MTextNewInCategory("templateTexts", "descriptiontexts");
	MTextUpdateTextContentCopyAllLanguages($mtext);
	$template->setDescriptionTextID($mtext->getID());
	
	$tmplid = TermosGetCounterValue("TemplateID");

	$tmplid++;
	
	$template->setTexts(0);
	$template->setImages(0);
	$template->setImageHandle("templateimg_" . $tmplid);
	$template->setStatus(0);
	TemplateSave($template);
	

	$templatefile = $_SERVER['DOCUMENT_ROOT'] . "/trms-content" . CURRENT_THEME . "/template_" . $tmplid  .".php";
	$fh = fopen($templatefile, 'w') or die("can't open file");
	$filecontentstr =	"<!-- START Template " . $tmplid  . " -->\n" .
						"<!-- Markup your HTML here please! -->\n" .
						"<div>Template " . $tmplid  . " </div>\n" .
						"<!-- END Template " . $tmplid  . " -->\n";

	fwrite($fh, $filecontentstr);
	fclose($fh);
	
	$tmplid = TermosGetCounterValue("TemplateID");
	editTemplate($tmplid);
}

function saveTemplate($tmplid){

	global $templatename, $templatedescription;

	$templatetexts = "";
	$templateimages = "";

	$template = TemplateGetByID($tmplid);
	
	$mtext = MTextGetMTextForLanguage($template->getNameTextID(), TermosGetCurrentLanguage());
	$mtext->setTextContent($templatename);
	MTextUpdateTextContent($mtext);

	$mtext = MTextGetMTextForLanguage($template->getDescriptionTextID(), TermosGetCurrentLanguage());
	$mtext->setTextContent($templatedescription);
	MTextUpdateTextContent($mtext);

	if(isset($_REQUEST["templatetexts"]))$templatetexts = $_REQUEST["templatetexts"];
	$template->setTexts($templatetexts);

	if(isset($_REQUEST["templateimages"]))$templateimages = $_REQUEST["templateimages"];
	$template->setImages($templateimages);

	$template->setImageHandle("templateimg_" . $tmplid);

	if(isset($_REQUEST["templatestatus"])){
		$templatestatus = $_REQUEST["templatestatus"];
		$template->setStatus($templatestatus);
	}else{
		$templatestatus = 0;
		$template->setStatus($templatestatus);
	}

	TemplateSave($template);

	if ( $_FILES["file"]["size"] > 0 ) {
		if ( $_FILES["file"]["size"] < 600000 ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") { 

			if(!$image = ImageGetByHandleAndLanguage($template->getImageHandle(), -1)){
				
				$newimage = true;
				$imagetmpid = TermosGetCounterValue("ImageID");
				$imagetmpid++;
				$image = new Image;
				$image->setID($imagetmpid);
				$image->setHandle("templateimg_" . $template->getID());
				$image->setLanguageID(-1);
				
				$alttext = MTextNewInCategory("imageTexts", "templateimage");
				MTextUpdateTextContentCopyAllLanguages($alttext);

				$image->setAltTextID($alttext->getID());
				ImageAddCategory($image->getID(), TEMPLATE_IMAGECATEGORY);
			}

			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,1000,0))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,1000,0));
			}

			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,1000,1))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,1000,1));
			}
			
			$imagedata = getimagesize($_FILES["file"]["tmp_name"]);
			$image->setSizeX($imagedata[0]);
			$image->setSizeY($imagedata[1]);

			switch( $imagedata[2] ){
				case 1:
					$image->setFormat("gif");
					$image->setIconFormat("gif");
				break;
				case 2:
					$image->setFormat("jpeg");
					$image->setIconFormat("jpeg");
					break;
				case 3:
					$image->setFormat("png");
					$image->setIconFormat("png");
			}

			// If Resize
			// If make icon
			{
				$thumbnail = new SimpleImage();
				$thumbnail->load($_FILES["file"]["tmp_name"]);
				$thumbnail->resizeToWidth(250);
				$thumbnail->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,1000,1) . $image->getFormat());
				
				$image->setIconSizeX($thumbnail->getWidth());
				$image->setIconSizeY($thumbnail->getHeight());
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,1000,0) . $image->getFormat());
			
			// If the image is new ID is set in the ImageSave function //
			if($newimage)$image->setID(0);
			
			ImageSave($image, -1);

			}else{ print "fel typ"; return; }
		}
		else{ print "för stor fil"; return; }
	}
	editTemplate($tmplid);
}

function deleteTemplate($tmplid){
	
	//check for instances first!

	TemplateDelete($tmplid);	
	defaultAction();
}


function htmlStart(){

    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Templates</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<script>\n" .
			"function deleteTemplate(templateid, templatename){\n" .
			"	if(confirm('" . MTextGet("deleteTemplate") . ":  ' + templatename + '?'))\n" .
			"	location.href = 'templates.php?action=deleteTemplate&tmplid=' + templateid;\n" .
			"}\n" .
			"</script>" .
			"</head>\n" .
			"<body>\n";
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}
