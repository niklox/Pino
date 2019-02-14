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
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
DBcnx();
define("URL", "/trms-admin/images.php");

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["imgid"]))$imgid = $_REQUEST["imgid"]; // image
if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"];	// imageacategoryid
if(isset($_REQUEST["langid"]))$langid = $_REQUEST["langid"];	// languageid
if(isset($_REQUEST["imgcatname"]))$imagecatname = $_REQUEST["imgcatname"];
if(isset($_REQUEST["imgcatposition"]))$imgcatposition = $_REQUEST["imgcatposition"];

$maxlevel = 20;
$positions = 30;
$parentid = 0;

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $imgid, $imgcatid, $langid, $imagecatname, $imgcatposition;

	if( UserHasPrivilege($admin->getID(), 15) ){
		if($action == "editImage") 
			editImage($imgid);				// OK
		else if ($action == "saveImage")	
			saveImage($imgid);				// OK
		else if ($action == "newImage")
			newImage($langid, $imgcatid);	// OK
		else if ($action == "addLanguage")
			addLanguage($imgid, $langid);	// OK
		else if ($action == "createImage")
			createImage($langid, $imgcatid);	// OK
		else if ($action == "deleteImage")	
			deleteImage($imgid, $langid);	// OK
		else if($action == "editImgCat")	
			editImageCategory($imgcatid);	// OK ++
		else if ($action == "saveImgCat")
			saveImageCategory($imgcatid, $imagecatname, $imgcatposition);
		else if ($action == "createImgCat")
			createImageCategory($imagecatname);
		else if ($action == "deleteImgCat")
			deleteImageCategory($imgcatid);
		else
			defaultAction();
	}else{
		print "No permission";
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print	"<div class=\"stdbox_800\">\n" .
			"<form>\n<input type=\"hidden\" id=\"action\" name=\"action\" value=\"createImgCat\">\n" .
			"<input type=\"input\" id=\"imgcatname\" name=\"imgcatname\" size=\"30\"/>\n" .
			"<input type=\"submit\" value=\"Create new imagecategory\">\n" .
			"</form>\n" . 
			"</div>\n";

	print	"<div id=\"list_head_800\">Imagecategories</div>\n";
	print	"<div id=\"list_body_800\">";
	print	"<ul>\n";

	$imgcat = ImageCategoryGetAllOrderByName();
	while($imgcat = ImageCategoryGetNext($imgcat)){
		print "<li><a href=\"" . URL . "?action=editImgCat&imgcatid=" . $imgcat->getID() . "\">" . $imgcat->getName() . "</a></li>\n";
	}
	//<img src=\"images/delete_mini.gif\" onclick=\"deleteImageCat(" . $imgcat->getID() . ",'" .  $imgcat->getName() . "')\" border=\"0\"/>
	print	"</ul>\n";
	print	"</div>\n";
}

function editImage($imageid){
	global $imgcatid;
	$image = ImageGetByID($imageid);

	if(!isset($imgcatid))$imgcatid = ImageGetCategoryID($imageid);
	
	$imgcat = ImageCategoryGetByID($imgcatid);

	print	"<div id=\"box_head\">Edit image\n";
	print	" <input type=\"button\" class=\"wht\" value=\"" . $imgcat->getName() . "\" onclick=\"location.href='" . $_SERVER["SCRIPT_NAME"] ."?action=editImgCat&imgcatid=" . $imgcat->getID() . "'\" /> ";
			
	if($prev = ImageGetPreviousInCategory($imageid, $imgcatid)) 
		print "<input type=\"button\" title=\"" . MTextGet("previmgincat") . "\" class=\"wht\" value=\"<\" onclick=\"location.href='". $_SERVER["SCRIPT_NAME"]."?action=editImage&imgid=".$prev."&imgcatid=" . $imgcatid . "'\"/> ";
	else
		print "<input type=\"button\" class=\"void\" title=\"\" value=\"<\"/> ";

	if($next = ImageGetNextInCategory($imageid, $imgcatid)) 
		print "<input type=\"button\" title=\"" . MTextGet("nextimgincat") . "\" class=\"wht\" value=\">\" onclick=\"location.href='". $_SERVER["SCRIPT_NAME"]."?action=editImage&imgid=".$next."&imgcatid=" . $imgcatid . "'\"/>";
	else
		print " <input type=\"button\" class=\"void\" title=\"\" value=\">\"/>";
	
	print	"<form><p class=\"btnright\">" .
			"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"addLanguage\"/>\n" .
			"<input type=\"hidden\" id=\"imgid\" name=\"imgid\" value=\"" . $imageid . "\"/>\n" .
			"<select name=\"langid\">\n<option>Add language</option>\n";

	$language = LanguageGetAll();
	while($language = LanguageGetNext($language)){
		print "<option value=\"" . $language->getID() . "\">" . $language->getLanguageNameTextID() . "</option>\n";
	}
	
	print	"</select> &nbsp;<input type=\"submit\" class=\"wht\" value=\"add\"/></p></form></div>\n"; 
	print	"<div id=\"box_body\" class=\"clearfix\">"; //
	
	while($image = ImageGetNext($image)){
		
		print	"<form  method=\"post\" enctype=\"multipart/form-data\">\n"; //action=\"/test/upload_file.php\"
		print	"<h5 class=\"imghead\">" . LanguageName($image->getLanguageID()) . "</h5>\n";
		print	"<div class=\"imagebox\">\n";
		print	"<div class=\"boxes\">\n";
		print	"<input type=\"hidden\" name=\"action\" value=\"saveImage\">\n";
		print	"<input type=\"hidden\" name=\"imgid\" value=\"" . $image->getID() . "\">\n";
		print	"<input type=\"hidden\" name=\"langid\" value=\"" . $image->getLanguageID() . "\">\n";
		print	"<input type=\"hidden\" name=\"imagealttextid\" value=\"" . $image->getAltTextID() . "\">\n";
		
		print	"<span class=\"lbl\">ID:</span> ". $image->getID() . "<br/>\n" .
				"<span class=\"lbl\">ImageHandle:</span> " . $image->getHandle() . "<br/>\n" . 
				"<span class=\"lbl\">Language:</span> " . LanguageName($image->getLanguageID()) . "<br/>\n";

		print	"<span class=\"lbl\">Image size x:</span> " . $image->getSizeX() . "<br/>\n";
		print	"<span class=\"lbl\">Image size y:</span> " . $image->getSizeY() . "<br/>\n";
		print	"<span class=\"lbl\">Imageicon size x:</span> " . $image->getIconSizeX() . "<br/>\n";
		print	"<span class=\"lbl\">Imageicon size y:</span> " . $image->getIconSizeY() . "<br/><br/>\n";

		print	"<span class=\"lbl\">Image Alt text:</span><br/>\n<textarea id=\"imagealttext\" name=\"imagealttext\" rows=\"3\" cols=\"40\">".  MTextGet($image->getAltTextID()) . "</textarea><br/><br/>\n";

		print	"<span class=\"lbl\">Filename:</span> " . ImageCreateFilename($image, IMGMASK, 0) . "<br/>";
		print	"<span class=\"lbl\">Filename icon:</span> " . ImageCreateFilename($image, IMGMASK, 1);

		print	"</div>\n";
		print	"<div class=\"boxes\">\n";

		print	ImageCreateTag($image, IMGMASK, 0) . "<br/><br/>\n";

		print	ImageCreateTag($image, IMGMASK, 1) . "<br/><br/>\n";

		print	MTextGet("selectImgFile") . "<br/><input type=\"file\" id=\"file\" name=\"file\" size=\"30\"/>";
			
		print	"<input type=\"text\" name=\"imagewidth\" id=\"imagewidth\" size=\"4\"/> ".  MTextGet("imagewidth") .  " <input type=\"text\" name=\"iconwidth\" id=\"iconwidth\" size=\"4\"/> ". MTextGet("iconwidth")  ."<br/><br/>" . MTextGet("imgwidthinfo");
		print	"<br/><br/><input type=\"submit\" value=\"Save image\"/> <input type=\"button\" name=\"deleteimage\" value=\"Delete Image\" onclick=\"deleteImage(".$image->getID().",'".$image->getHandle()."', '".LanguageName($image->getLanguageID())."'," . $image->getLanguageID() . ")\"/>";
		print	"</div>\n";
	
		
	
		print	"</div>\n";
		print	"</form>\n";
	}
	print	"</div>\n";
}

function saveImage($imageid){

	if(isset($_REQUEST["langid"]))$langid = $_REQUEST["langid"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];
	if(isset($_REQUEST["imagealttextid"]))$imagealttextid = $_REQUEST["imagealttextid"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];

	$image = ImageGetByIDAndLanguage($imageid, $langid);

	$mtext = MTextGetMTextForLanguage($imagealttextid, TermosGetCurrentLanguage());
	$mtext->setTextContent($imagealttext);
	MTextUpdateTextContent($mtext);

	//$image->setAltTextID($imagealttext);

	if ( $_FILES["file"]["size"] > 0 ) {
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") { 
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,100,0))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0));
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1));
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

			// If make icon
			{
				$thumbnail = new SimpleImage();
				$thumbnail->load($_FILES["file"]["tmp_name"]);
				if($iconwidth > 0)
					$thumbnail->resizeToWidth($iconwidth);
				else
					$thumbnail->resizeToWidth(50);

				$thumbnail->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat());
				
				$image->setIconSizeX($thumbnail->getWidth());
				$image->setIconSizeY($thumbnail->getHeight());
			}

			// If Resize
			if($imagewidth > 0)
			{
				$theimage = new SimpleImage();
				$theimage->load($_FILES["file"]["tmp_name"]);
				$theimage->resizeToWidth($imagewidth);
				$theimage->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());

				$image->setSizeX($theimage->getWidth());
				$image->setSizeY($theimage->getHeight());
			}
			else{
			
				move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());	

			}
			

			}else{ print "fel typ"; return; }
		}
		else{ print "för stor fil"; return; }
	}
	ImageSave($image,$langid);
	editImage($imageid);
}

function addLanguage($imageid, $langid){

	if(ImageHasLanguage($imageid, $langid))
	print "Language exists";
	else{
		ImageInsertLanguage($imageid, $langid);
		editImage($imageid);
	}
}

function newImage($langid, $imgcatid){

	print	"<div id=\"box_head\">". MTextGet("newImage") . "</div>\n"; 
	print	"<div id=\"box_body\" class=\"clearfix\">"; 

	print	"<form  method=\"post\" enctype=\"multipart/form-data\">\n"; //action=\"/test/upload_file.php\"
	
	print	"<div class=\"editimage\">\n";
	print	"<div class=\"boxes\">\n";

	print	"<input type=\"hidden\" name=\"action\" value=\"createImage\">\n";
	print	"<input type=\"hidden\" name=\"langid\" value=\"" . $langid ."\">\n";
	print	"<input type=\"hidden\" name=\"imgcatid\" value=\"" . $imgcatid ."\">\n";
	
	print	"ImageHandle:<br/>\n<input type=\"text\" id=\"handle\" name=\"handle\" size=\"36\"/><br/><br/>\n" ;
	print	"Image Alt text:<br/>\n<textarea id=\"imagealttext\" name=\"imagealttext\" rows=\"3\" cols=\"40\"></textarea>\n";

	print	"</div>\n";
	print	"<div class=\"boxes\">\n";
	print	MTextGet("selectImgFile") . "<input type=\"file\" id=\"file\" name=\"file\" size=\"30\"/>";

	print	"<input type=\"text\" name=\"imagewidth\" id=\"imagewidth\" size=\"4\"/> ".  MTextGet("imagewidth") .  " <input type=\"text\" name=\"iconwidth\" id=\"iconwidth\" size=\"4\"/> ". MTextGet("iconwidth")  ."<br/><br/>" . MTextGet("imgwidthinfo");
	
	print	"<br/><br/><input type=\"submit\" value=\"Save image\"/>" ;
	print	"</div>\n";

	

	print	"</div>\n";
	print	"</form>\n";
	print	"</div>\n";
}

function createImage($langid, $imgcatid){
	
	if(isset($_REQUEST["handle"]))$imghandle = $_REQUEST["handle"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];


	$imagetmpid = TermosGetCounterValue("ImageID");
	$imagetmpid++;
	$image = new Image;

	$image->setID($imagetmpid);
	$image->setHandle($imghandle);
	$image->setLanguageID($langid);
	
	if ( $_FILES["file"]["size"] > 0 ) {
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") { 
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0));
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1));
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
			if($imagewidth > 0)
			{
				$theimage = new SimpleImage();
				$theimage->load($_FILES["file"]["tmp_name"]);
				$theimage->resizeToWidth($imagewidth);
				$theimage->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());

				$image->setSizeX($theimage->getWidth());
				$image->setSizeY($theimage->getHeight());
			}
			// If make icon
			{
				$thumbnail = new SimpleImage();
				$thumbnail->load($_FILES["file"]["tmp_name"]);
				if($iconwidth > 0)
					$thumbnail->resizeToWidth($iconwidth);
				else
					$thumbnail->resizeToWidth(50);

				$thumbnail->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat());
				
				$image->setIconSizeX($thumbnail->getWidth());
				$image->setIconSizeY($thumbnail->getHeight());
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());
			
			}else{ print "fel typ"; return; }
		}
		else{ print "för stor fil"; return; }
	}
	ImageAddCategory($image->getID(), $imgcatid);

	// ID is set in ImageSave function
	$image->setID(0);

	$alttext = MTextNewInCategory("imageTexts", $imagealttext);
	MTextUpdateTextContentCopyAllLanguages($alttext);

	$image->setAltTextID($alttext->getID());

	ImageSave($image, $langid);
	editImage($imagetmpid);
}

function deleteImage($imageid, $languageid){

	//Contentcheck here.
	$flag = false;

	$image = ImageGetByIDAndLanguage($imageid, -1);
	$content = ContentGetAllWhereImageAppears($image->getHandle());
	while($content = ContentGetNext($content)){
		print $content->getTitle() . "<br/>";
		$flag = true;
	}

	if($flag == true) print MTextGet("detachFromContent");
	
	if($flag == false){
		if($languageid == -1){
		
			if( ImageCheckForLanguageImages($imageid) == 1 ){

				$image = ImageGetByIDAndLanguage($imageid, $languageid);
				// Delete image
				if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0))){
							unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0));
				}
				// Delete thumbnail
				if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1))){
							unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1));
				}
				MTextDelete($image->getAltTextID());
				ImageDelete($image->getID());
				

			}else{
				
				print "Error: " . MTextGet("deleteLangImg");
			}
			
		}else if($languageid > 0){

			$image = ImageGetByIDAndLanguage($imageid, $languageid);
			// Delete image
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0))){
						unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0));
			}
			// Delete thumbnail
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1))){
						unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1));
			}
			MTextDeleteLanguage($image->getAltTextID(), $languageid);
			ImageDeleteLanguage($imageid, $languageid);
			
		}
	defaultAction();
	}
}
 
function editImageCategory($imgcatid){

	$imgcat = ImageCategoryGetByID($imgcatid);

	print	"<div class=\"stdbox_800\">\n";
	print	"<form>\n" . 
			"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"saveImgCat\">\n" .
			"<input type=\"hidden\" id=\"imgcatid\" name=\"imgcatid\" value=\"" . $imgcatid . "\">\n" .
			"<input type=\"hidden\" id=\"imgcatposition\" name=\"imgcatposition\" value=\"0\">\n" .
			"<input type=\"text\" id=\"imgcatname\" name=\"imgcatname\" value=\"". $imgcat->getName() . "\" size=\"40\"/>";

	/*print	" <select name=\"imgcatposition\">\n";
	for($i=0; $i<25; $i++){
		if($imgcat->getPosition() == $i)
		print	"<option value=\"".$i."\" selected>". $i . "</option>\n";
		else
		print	"<option value=\"".$i."\">". $i . "</option>\n";
	}*/
	print	"</select>\n";
	print	" <input type=\"submit\" value=\"" . MTextGet("saveImgCat") . "\"/>\n";
	print	"</form>\n";
	print	"</div>\n";

	print	"<div id=\"box_head\">Imagecategory: " . $imgcat->getName() . "<input type=\"button\" class=\"whtbtn\" id=\"newimage\" value=\"" . MTextGet("newImage"). "\" onclick=\"location.href='" . $_SERVER["SCRIPT_NAME"] . "?action=newImage&langid=-1&imgcatid=". $imgcatid ."'\"/></div>\n";

	print	"<div id=\"box_body\" class=\"clearfix\">"; //
	print	"<h5 class=\"imghead\">" . MTextGet("imagesInCategory") . "</h5>\n";
	print	"<div class=\"imagebox\">\n";
	print	"<div class=\"boxes\">";
	print	"<ul>\n";

	$image = ImageGetAllInCategory($imgcat->getID(), 0);
	while($image = ImageGetNext($image)){
		print "<li>";
		//if ($image->getIconSizeX() > 0) print ImageCreateTag($image, IMGMASK, 1);
		print " <a href=\"" . $_SERVER["SCRIPT_NAME"] . "?action=editImage&imgid=" . $image->getID() . "&imgcatid=" . $imgcatid . "\"> "; 
		if ($image->getIconSizeX() > 0) print ImageCreateTag($image, IMGMASK, 1);
		print " " . $image->getHandle() . "</a></li>\n";
	}
	print	"</ul>\n";
	print	"</div>\n";
	print	"</div>\n";
	print	"</div>\n";
}

function saveImageCategory($imgcatid, $imgcatname, $imgcatpos){

	$imagecategory = ImageCategoryGetByID($imgcatid);
	
	$mtext = MTextGetMTextForLanguage($imagecategory->getNameTextID(), TermosGetCurrentLanguage());
	$mtext->setTextContent($imgcatname);
	MTextUpdateTextContentCopyAllLanguages($mtext);

	$imagecategory->setPosition($imgcatpos);
	
	ImageCategorySave($imagecategory);
	editImageCategory($imgcatid);
}

function createImageCategory($imagecatname){
	
	$mtext = MTextNewInCategory("imageTexts", $imagecatname);
	$imagecat = new ImageCategory;

	$imagecat->setNameTextID($mtext->getID());
	$imagecat->setPosition(0);
	ImageCategorySave($imagecat);

	defaultAction();
	
}

function deleteImageCategory($imgcatid){

	if(ImageCategoryHasImages($imgcatid))
		print "Image category has imaages remove these first";
	else
		ImageCategoryDelete($imgcatid);

	defaultAction();
}

function htmlStart(){

    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Images</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<script>\n" .
			"function deleteImage(imgid, imagehandle, imagelanguage, langid){\n" .
			"	if(confirm('" . MTextGet("deleteImage") . ":  ' + imagehandle + ' in ' + imagelanguage + '?'))\n" .
			"	location.href = 'images.php?action=deleteImage&imgid=' + imgid + '&langid=' + langid;\n" .
			"}\n" .

			"function deleteImageCat(imgcatid, imagecatname){\n" .
			"	if(confirm('" . MTextGet("deleteImageCat") . ":  ' + imagecatname + '?'))\n" .
			"	location.href = 'images.php?action=deleteImgCat&imgcatid=' + imgcatid;\n" .
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

?>