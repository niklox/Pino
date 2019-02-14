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
// This is not necessary
//require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
define("URL", "/trms-admin/images.php");
DBcnx();

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
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print	'<div class="stdbox">' .
			'<form><input type="hidden" id="action" name="action" value="createImgCat">' .
			'<input type="input" id="imgcatname" name="imgcatname" class="std_input" size="30"/>' .
			'<input type="submit" class="std_button" value="Create new imagecategory">' .
			'</form>' . 
			'</div>';

	print	'<div id="box_head">Imagecategories</div>';
	print	'<div id="box_body">';
	print	'<ul>';

	$imgcat = ImageCategoryGetAllOrderByName();
	while($imgcat = ImageCategoryGetNext($imgcat)){
		print '<li><a href="' . URL . '?action=editImgCat&imgcatid=' . $imgcat->getID() . '">' . MTextGetByLanguage($imgcat->getNameTextID(), TermosGetCurrentLanguage()) . '</a></li>';
	}
	//<img src=\"images/delete_mini.gif\" onclick=\"deleteImageCat(" . $imgcat->getID() . ",'" .  $imgcat->getName() . "')\" border=\"0\"/>
	print	'</ul>';
	print	'</div>';
}

function editImage($imageid){
	global $imgcatid;
	
	// Check for TextCategory
	$image = ImageGetByID($imageid);
	ImageGetNext($image);
	$text = MTextGetMTextByLanguage($image->getAltTextID(), TermosGetCurrentLanguage());
	$textcatid = $text->getTextCategoryID();

	$image = ImageGetByID($imageid);
	
	if(!isset($imgcatid))$imgcatid = ImageGetCategoryID($imageid);
	
	$imgcat = ImageCategoryGetByID($imgcatid);

	print	'<div id="box_head">Edit image';
	print	' <input type="button" class="wht" value="' . $imgcat->getName() . '" onclick="location.href=\'' . $_SERVER["SCRIPT_NAME"] .'?action=editImgCat&imgcatid=' . $imgcat->getID() . '\'"/>';
			
	if($prev = ImageGetPreviousInCategory($imageid, $imgcatid)) 
		print '<input type="button" title="' . MTextGet("previmgincat") . '" class="wht" value="<" onclick="location.href=\''. $_SERVER["SCRIPT_NAME"].'?action=editImage&imgid='.$prev.'&imgcatid=' . $imgcatid . '\'"/>';
	else
		print '<input type="button" class="void" title="" value="<"/> ';

	if($next = ImageGetNextInCategory($imageid, $imgcatid)) 
		print '<input type="button" title="' . MTextGet("nextimgincat") . '" class="wht" value=">" onclick="location.href=\''. $_SERVER["SCRIPT_NAME"].'?action=editImage&imgid='.$next.'&imgcatid=' . $imgcatid . '\'"/>';
	else
		print ' <input type="button" class="void" value=">"/>';
	
	print	'<form><p class="btnright">' .
			'<input type="hidden" id="action" name="action" value="addLanguage"/>' .
			'<input type="hidden" id="imgid" name="imgid" value="' . $imageid . '"/>' .
			'<select name="langid"><option>Add language</option>';

	$language = LanguageGetAll();
	while($language = LanguageGetNext($language)){
		print '<option value="' . $language->getID() . '">' . $language->getLanguageNameTextID() . '</option>';
	}
	
	print	'</select> &nbsp;<input type="submit" class="wht" value="add"/></p></form></div>'; 
	print	'<div id="box_body" class="clearfix">'; //
	
	while($image = ImageGetNext($image)){
		
		print	'<form  method="post" enctype="multipart/form-data">'; //action=\"/test/upload_file.php\"
		print	'<h5 class="imghead">' . LanguageName($image->getLanguageID()) . '</h5>';
		print	'<div class="imagebox">';
		print	'<div class="leftbox">';
		print	'<input type="hidden" name="action" value="saveImage">';
		print	'<input type="hidden" name="imgid" value="' . $image->getID() . '">';
		print	'<input type="hidden" name="langid" value="' . $image->getLanguageID() . '">';
		print	'<input type="hidden" name="imagealttextid" value="' . $image->getAltTextID() . '">';
		
		print	'<span class="lbl">ID:</span> '. $image->getID() . '<br/>' .
				'<span class="lbl">ImageHandle:</span> ' . $image->getHandle() . '<br/>' ;
		
		
				
		print 	'<span class="lbl">Language:</span> ' . LanguageName($image->getLanguageID()) . '<br/>';

		print	'<span class="lbl">Image size x:</span> ' . $image->getSizeX() . '<br/>';
		print	'<span class="lbl">Image size y:</span> ' . $image->getSizeY() . '<br/>';
		print	'<span class="lbl">Imageicon size x:</span> ' . $image->getIconSizeX() . '<br/>';
		print	'<span class="lbl">Imageicon size y:</span> ' . $image->getIconSizeY() . '<br/><br/>';

		print	'<span class="lbl">'.$image->getLanguageID(). ' ' . $image->getAltTextID() .
				' <a href="/trms-admin/mtext.php?action=edit&textid='.$image->getAltTextID().'&textcatid='. $textcatid.'">Image Alt text:</a></span><br/>'.
				'<textarea id="imagealttext" name="imagealttext" rows="3" cols="40">'.  MTextGetByLanguage($image->getAltTextID(), $image->getLanguageID()) . '</textarea><br/><br/>';

		print	'<span class="lbl">Filename:</span> ' . ImageCreateFilename($image, IMGMASK, 0) . '<br/>';
		print	'<span class="lbl">Filename icon:</span> ' . ImageCreateFilename($image, IMGMASK, 1);

		print	'</div>';
		print	'<div class="rightbox">';

		print 	ImageCreateTagWithCSSII($image, IMGMASK, 1, "");
		
		print 	'<br/><br/><a style="color:red;text-decoration:underline" href="/trms-admin/rawimg.php?imgid='.$image->getID().'" target="_new">'.MTextGet("viewallsizes").' &#187;</a><br/><br/>';

		print	MTextGet("selectImgFile") . '<br/><br/><input style="background:#EFEFEF;width:600px" type="file" id="file" name="file" size="30"/><br/><br/>';
			
		print 	MTextGet("imgwidthinfo"). '<br/><br/>';	
		print	'<input type="text" name="imagewidth" id="imagewidth" size="4"/> '.  MTextGet("imagewidth") .  
				' <input type="text" name="imagewidthlarge" id="imagewidthlarge" size="4"/> '.  MTextGet("imagelar") . 
				' <input type="text" name="imagewidthmid" id="imagewidthmid" size="4"/> '.  MTextGet("imagemid") .  
				' <input type="text" name="iconwidth" id="iconwidth" size="4"/> '. MTextGet("iconwidth") ;
		
		print	'<br/><br/><input type="submit" value="Save image"/> <input type="button" name="deleteimage" value="Delete Image" onclick="deleteImage('.$image->getID().',\''.$image->getHandle().'\', \''.LanguageName($image->getLanguageID()).'\',' . $image->getLanguageID() . ')"/>';
		print	'</div>';
		print	'</div>';
		print	'</form>';
	}
	print	'</div>';
}

function saveImage($imageid){
	
	$square = false; $portrait = false; $landscape = false;

	if(isset($_REQUEST["langid"]))$langid = $_REQUEST["langid"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];
	if(isset($_REQUEST["imagealttextid"]))$imagealttextid = $_REQUEST["imagealttextid"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["imagewidthlarge"]))$imagewidthlarge = $_REQUEST["imagewidthlarge"];
	if(isset($_REQUEST["imagewidthmid"]))$imagewidthmid = $_REQUEST["imagewidthmid"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];
	
	$image = ImageGetByIDAndLanguage($imageid, $langid);

	if($langid == -1)
	$mtext = MTextGetMTextForLanguage($imagealttextid, TermosGetCurrentLanguage());
	else
	$mtext = MTextGetMTextForLanguage($imagealttextid, $langid);

	$mtext->setTextContent($imagealttext);
	MTextUpdateTextContent($mtext);

	//$image->setAltTextID($imagealttext);

	//print $imageid .  ' ' . $_FILES["file"]["size"];

	if ( $_FILES["file"]["size"] > 0 ) {
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") { 
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,100,0))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,0));
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,1));
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,2))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,2));
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,3))){
				unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,3));
			}
			
			$imagedata = getimagesize($_FILES["file"]["tmp_name"]);
			$ratio = $imagedata[0] / $imagedata[1];
			
			switch( $imagedata[2] ){
				case 1:
					$image->setFormat("gif");
					$image->setIconFormat("gif");
					$filext = 'gif';
				break;
				case 2:
					$image->setFormat("jpeg");
					$image->setIconFormat("jpeg");
					$filext = 'jpeg';
				break;
				case 3:
					$image->setFormat("png");
					$image->setIconFormat("png");
					$filext = 'png';
			}

			// Create SMALL image
			{
				$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat();
				
				if($iconwidth > 0){
					$imgData = resize_image($_FILES["file"]["tmp_name"], $iconwidth, calcHeight($ratio, $iconwidth), $filext);
					$image->setIconSizeX($iconwidth);
					$image->setIconSizeY(calcHeight($ratio, $iconwidth));
				}else{
					$imgData = resize_image($_FILES["file"]["tmp_name"], 400, calcHeight($ratio, 400), $filext);
					$image->setIconSizeX(400);
					$image->setIconSizeY(calcHeight($ratio, 400));
				}
				saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
			}
					
			// Create MEDIUM image
			{
				
				$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,2) . $image->getFormat();
				
				if($imagewidthmid > 0)
					$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidthmid, calcHeight($ratio, $imagewidthmid), $filext);
				else
					$imgData = resize_image($_FILES["file"]["tmp_name"], 800, calcHeight($ratio, 800), $filext);
				
				saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			}
					
			// Create LARGE image
			{
				
				$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,3) . $image->getFormat();
				
				if($imagewidthlarge > 0)
					$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidthlarge, calcHeight($ratio, $imagewidthlarge), $filext);
				else
					$imgData = resize_image($_FILES["file"]["tmp_name"], 1200, calcHeight($ratio, 1200), $filext);
				
				saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
			}
			
			// Create ORIGINAL or XL image
			{
			
				$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat();
				
				if($imagewidth > 0){
					$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidth, calcHeight($ratio, $imagewidth), $filext);
					$image->setSizeX($imagewidth);
					$image->setSizeY(calcHeight($ratio, $imagewidth));
				}else{
					$imgData = resize_image($_FILES["file"]["tmp_name"], 1600, calcHeight($ratio, 1600), $filext);
					$image->setSizeX(1600);
					$image->setSizeY(calcHeight($ratio, 1600));
				}
				saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
			}
			

			
			}else{ print "fel typ"; return; }
		}
		else{ print "fšr stor fil"; return; }
		
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

	print	'<div id="box_head">'. MTextGet("newImage") . '</div>'; 
	print	'<div id="box_body" class="clearfix">'; 

	print	'<form  method="post" enctype="multipart/form-data">'; //action=\"/test/upload_file.php\"
	
	print	'<div class="editimage">';
	print	'<div class="leftbox">';

	print	'<input type="hidden" name="action" value="createImage">';
	print	'<input type="hidden" name="langid" value="' . $langid .'">';
	print	'<input type="hidden" name="imgcatid" value="' . $imgcatid .'">';
	
	print	'ImageHandle:<br/><input type="text" id="imghandle" name="imghandle" size="36"/><br/><br/>' ;
	print	'Image Alt text:<br/><textarea id="imagealttext" name="imagealttext" rows="3" cols="40"></textarea>';

	print	'</div>';
	print	'<div class="rightbox">';
	print	'<input type="file" id="file" name="file" size="30"/><br/><br/>';

	print 	MTextGet("imgwidthinfo"). '<br/><br/>';	
	print	'<input type="text" name="imagewidth" id="imagewidth" size="4"/> '.  MTextGet("imagewidth") .  
			' <input type="text" name="imagewidthlarge" id="imagewidthlarge" size="4"/> '.  MTextGet("imagelar") . 
			' <input type="text" name="imagewidthmid" id="imagewidthmid" size="4"/> '.  MTextGet("imagemid") .  
			' <input type="text" name="iconwidth" id="iconwidth" size="4"/> '. MTextGet("iconwidth") ;
	
	print	'<br/><br/><input type="submit" value="Save image"/>' ;
	print	'</div>';
	
	print	'</div>';
	print	'</form>';
	print	'</div>';
}

function createImage($langid, $imgcatid){

	if(isset($_REQUEST["imghandle"]))$imghandle = $_REQUEST["imghandle"];
	if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];
	if(isset($_REQUEST["imagealttextid"]))$imagealttextid = $_REQUEST["imagealttextid"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["imagewidthlarge"]))$imagewidthlarge = $_REQUEST["imagewidthlarge"];
	if(isset($_REQUEST["imagewidthmid"]))$imagewidthmid = $_REQUEST["imagewidthmid"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];

	//$langid = -1;

	if ( $_FILES["file"]["size"] > 0 ) {
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") {
				if(ImageHandleExists($imghandle) == false){
					$imagetmpid = TermosGetCounterValue("ImageID");
					$imagetmpid++;
					$image = new Image;

					$image->setID($imagetmpid);
					$image->setHandle($imghandle);
					$image->setLanguageID($langid);
					
					$imagedata = getimagesize($_FILES["file"]["tmp_name"]);
					$image->setSizeX($imagedata[0]);
					$image->setSizeY($imagedata[1]);

					$imagedata = getimagesize($_FILES["file"]["tmp_name"]);
					$ratio = $imagedata[0] / $imagedata[1];
			
					switch( $imagedata[2] ){
						case 1:
						$image->setFormat("gif");
						$image->setIconFormat("gif");
						$filext = 'gif';
						break;
						
						case 2:
						$image->setFormat("jpeg");
						$image->setIconFormat("jpeg");
						$filext = 'jpeg';
						break;
						
						case 3:
						$image->setFormat("png");
						$image->setIconFormat("png");
						$filext = 'png';
					}

					// Create SMALL image
					{
						$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat();
				
						if($iconwidth > 0){
							$imgData = resize_image($_FILES["file"]["tmp_name"], $iconwidth, calcHeight($ratio, $iconwidth), $filext);
							$image->setIconSizeX($iconwidth);
							$image->setIconSizeY(calcHeight($ratio, $iconwidth));
						}else{
							$imgData = resize_image($_FILES["file"]["tmp_name"], 400, calcHeight($ratio, 400), $filext);
							$image->setIconSizeX(400);
							$image->setIconSizeY(calcHeight($ratio, 400));
						}
						saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
					}
					
					// Create MEDIUM image
					{
				
						$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,2) . $image->getFormat();
				
						if($imagewidthmid > 0)
							$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidthmid, calcHeight($ratio, $imagewidthmid), $filext);
						else
							$imgData = resize_image($_FILES["file"]["tmp_name"], 800, calcHeight($ratio, 800), $filext);
				
						saveResizedImage($imagedata[2], $imgData, $resizedFilename);
					}
					
					// Create LARGE image
					{
				
						$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,3) . $image->getFormat();
				
						if($imagewidthlarge > 0)
							$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidthlarge, calcHeight($ratio, $imagewidthlarge), $filext);
						else
							$imgData = resize_image($_FILES["file"]["tmp_name"], 1200, calcHeight($ratio, 1200), $filext);
				
						saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
					}
			
					// Create ORIGINAL or XL image
					{
			
						$resizedFilename = $_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat();
				
						if($imagewidth > 0){
							$imgData = resize_image($_FILES["file"]["tmp_name"], $imagewidth, calcHeight($ratio, $imagewidth), $filext);
							$image->setSizeX($imagewidth);
							$image->setSizeY(calcHeight($ratio, $imagewidth));
						}else{
							$imgData = resize_image($_FILES["file"]["tmp_name"], 1600, calcHeight($ratio, 1600), $filext);
							$image->setSizeX(1600);
							$image->setSizeY(calcHeight($ratio, 1600));
						}
						
						saveResizedImage($imagedata[2], $imgData, $resizedFilename);
			
					}

					
					ImageAddCategory($image->getID(), $imgcatid);
					
					$alttext = MTextNewInCategory("imageTexts", $imagealttext);
					MTextUpdateTextContentCopyAllLanguages($alttext);

					$image->setAltTextID($alttext->getID());

					$image->setID(0);
					ImageSave($image, $langid);
						
					//print	MTextGet("imageSaved") . '<input type="hidden" name="status" id="status" value="ok"/>';

				}else print MTextGet("imageHandleExists");
			}else print MTextGet("fileTypeNotSupported");
		}else print MTextGet("fileToLarge");
	}else print MTextGet("noFileData");
	
	editImage($imagetmpid);
}

function deleteImage($imageid, $languageid){

	//Contentcheck here.
	$flag = false;

	$image = ImageGetByIDAndLanguage($imageid, -1);
	$imagecatid = ImageGetCategoryID($image->getID());
	
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
				// Delete midsize
				if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,2))){
							unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,2));
				}
				// Delete largesize
				if(file_exists($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,3))){
							unlink($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilename($image,IMGMASK,3));
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
			//MTextDeleteLanguage($image->getAltTextID(), $languageid);
			ImageDeleteLanguage($imageid, $languageid);
			
		}
	
	editImageCategory($imagecatid);
	}
}
 
function editImageCategory($imgcatid){

	$imgcat = ImageCategoryGetByID($imgcatid);

	print	'<div class="stdbox">';
	print	'<form>' . 
			'<input type="hidden" id="action" name="action" value="saveImgCat">' .
			'<input type="hidden" id="imgcatid" name="imgcatid" value="' . $imgcatid . '">'.
			'<input type="hidden" id="imgcatposition" name="imgcatposition" value="0">' .
			'<input type="text" id="imgcatname" class="std_input" name="imgcatname" value="'. $imgcat->getName() .'" size="40"/>';

	
	print	'</select>'.
			'<input type="submit" class="std_button" value="' . MTextGet("saveImgCat") . '"/>'.
			'</form>'.
			'</div>';

	print	'<div id="box_head">Imagecategory: ' . $imgcat->getName() . '<input type="button" class="whtbtn" id="newimage" value="' . MTextGet("newImage"). '" onclick=location.href="' . $_SERVER["SCRIPT_NAME"] . '?action=newImage&langid=-1&imgcatid='. $imgcatid .'"></div>';

	print	'<div id="box_body" class="clearfix">'.
			'<h5 class="imghead">"' . MTextGet("imagesInCategory") . '"</h5>'.
			'<div class="imagebox">'.
			'<div class="boxes">'.
			'<ul>';

	$image = ImageGetAllInCategory($imgcat->getID(), 0);
	while($image = ImageGetNext($image)){
	
	print 	'<li>';
	print 	' <a href="' . $_SERVER["SCRIPT_NAME"] . '?action=editImage&imgid=' . $image->getID() . '&imgcatid=' . $imgcatid . '">'; 
	print 	ImageCreateTagWithCSSII($image, IMGMASK, 1, "");
	print 	$image->getHandle() . '</a>'.
			'</li>';
	}
	print	'</ul>'.
			'</div>'.
			'</div>'.
			'</div>';
}

function saveImageCategory($imgcatid, $imgcatname, $imgcatpos){

	// this function gives an error when MText not exists
	
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

/**
 * 	Helper functions for image resizing
 */
 
function saveResizedImage($format,$imgData,$resizedFilename){
 	switch( $format ){
		case 1:
		imagegif($imgData, $resizedFilename);
		break;
		case 2:
		imagejpeg($imgData, $resizedFilename, 75);
		break;
		case 3:
		imagepng($imgData, $resizedFilename, 1);
	}
}

function calcHeight($r, $w){

	if($r === 1) 			// square
	$height = $w;
	else if($ratio < 1) 	// portrait
	$height = ceil( $w / $r );
	else if($ratio > 1)		// landscape
	$height = ceil( $w * $r );
				
	return $height;
}
 
function resize_image($file, $w, $h, $xt, $crop=false) {
    
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
        	
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
        	
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
	switch($xt){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
  
    $dst = imagecreatetruecolor($newwidth, $newheight);
    
    // Theese two rows solved transparent backgrounds 2019-02-12
    imagealphablending( $dst, false );
	imagesavealpha( $dst, true );
	
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	return $dst;
}


function htmlStart(){

   print	'<!DOCTYPE html>' .
			'<html>' .
			'<head>' .
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
			'<title>Termos Images</title>'.
			'<link rel="stylesheet" href="css/termosadmin.css"/>' .
			'<script>';
			
	print	"function deleteImage(imgid, imagehandle, imagelanguage, langid){" .
			"	if(confirm('" . MTextGet("deleteImage") . ":  ' + imagehandle + ' in ' + imagelanguage + '?'))" .
			"	location.href = 'images.php?action=deleteImage&imgid=' + imgid + '&langid=' + langid;" .
			"}" .
			"function deleteImageCat(imgcatid, imagecatname){" .
			"	if(confirm('" . MTextGet("deleteImageCat") . ":  ' + imagecatname + '?'))" .
			"	location.href = 'images.php?action=deleteImgCat&imgcatid=' + imgcatid;" .
			"}";
			
	print 	'</script>' .
			'</head>' .
			'<body>';
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}

?>