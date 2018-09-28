<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
DBcnx();
//print 	'<style rel="stylesheet" href="/trms-admin/css/termosadmin.css"/>';
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; 
if(isset($_REQUEST["imgpos"]))$imgpos = $_REQUEST["imgpos"];

if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $cid, $imgpos, $imgcatid, $imghandle;
	
	if($action == "uploadImage")
		uploadImage($cid, $imgpos);
	else
		defaultAction($cid, $imgpos);

}else print "Please login";

function defaultAction($cid, $imgpos){

	

	print	'<ul id="imagetabs">' .
			'	<li id="imgselect">Imagebank</li>' .
			'	<li id="imgupload" class="active">Upload image</li>' .
			'	<li id="imglink">Imagelink</li>' .
			'</ul>';
	//print 	'<p style="color:red">arbete pågår</p>';

	print	'<form id="contentImgUpload" action="/trms-admin/ajax/content_image_upload.php" name="contentImgUpload" method="POST" enctype="multipart/form-data">';
	
	
	/*print 	'<div class="fileUpload btn btn-primary">'.
    		'<span>Upload</span>'.
    		'<input type="file" class="upload" id="file" name="file" />'.
			'</div>';*/
	
	print	'<input class="stdupload" type="file" id="file" name="file" size="100"/><br/>' ;
	
	
	print	'<label for="imghandle">'.MTextGet("imgHandle").'</label><br/><input type="text" class="stdinput" id="imghandle" name="imghandle" size="55"/><br/>';

	print	'<select class="stdselect" name="imgcatid" id="imgcatid">' .
			'<option value="0">'. MTextGet("selectImgCat") . '</option>';
			$imgcat = ImageCategoryGetAllOrderByName();
			while($imgcat = ImageCategoryGetNext($imgcat)){
				print '<option value="'.$imgcat->getID().'">'. $imgcat->getName() . '</option>';
			}
	print	'</select><br/>';
	
	print 	MTextGet("imgwidthinfo"). '<br/>';	
	print	'<input type="text" name="imagewidth" id="imagewidth" size="4"/> '.  MTextGet("imagewidth") .  
			' <input type="text" name="imagewidthlarge" id="imagewidthlarge" size="4"/> '.  MTextGet("imagelar") . 
			' <input type="text" name="imagewidthmid" id="imagewidthmid" size="4"/> '.  MTextGet("imagemid") .  
			' <input type="text" name="iconwidth" id="iconwidth" size="4"/> '. MTextGet("iconwidth") ;
	
	print 	'<br/>';		
	
	


	print	'<label for="imagealttext">'.MTextGet("imageAltText").'</label><br/>'.
			'<textarea name="imagealttext" id="imagealttext" class="stdtextarea"></textarea> ';
	
	print	'<input type="hidden" name="action" id="action" value="uploadImage"/>' .
			'<input type="hidden" name="imgpos" id="imgpos" value="'.$imgpos.'"/>' .
			'<input type="hidden" name="cid" id="cid" value="'.$cid.'"/>' .
			'<input type="submit" class="std_button" value="Upload"/>' .
			'</form>';

	print	'<div id="output"></div>';
}

function uploadImage($cid, $imgpos){
	
	if(isset($_REQUEST["imghandle"]))$imghandle = $_REQUEST["imghandle"];
	if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];
	if(isset($_REQUEST["imagealttextid"]))$imagealttextid = $_REQUEST["imagealttextid"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["imagewidthmid"]))$imagewidthmid = $_REQUEST["imagewidthmid"];
	if(isset($_REQUEST["imagewidthlarge"]))$imagewidthlarge = $_REQUEST["imagewidthlarge"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];

	$langid = -1;

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
							$thumbnail->resizeToWidth(400);
						$thumbnail->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat());
						
						$image->setIconSizeX($thumbnail->getWidth());
						$image->setIconSizeY($thumbnail->getHeight());
					}
					
					// If make midsize
					{
						$midsize = new SimpleImage();
						$midsize->load($_FILES["file"]["tmp_name"]);
						if($imagewidthmid > 0)
							$midsize->resizeToWidth($imagewidthmid);
						else
							$midsize->resizeToWidth(800);
						$midsize->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,2) . $image->getFormat());
					}
					
					// If make largesize
					{
						$largesize = new SimpleImage();
						$largesize->load($_FILES["file"]["tmp_name"]);
						if($imagewidthlarge > 0)
						$largesize->resizeToWidth($imagewidthlarge);
						else
						$largesize->resizeToWidth(1200);
						$largesize->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,3) . $image->getFormat());
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
					}else{
					
						$theimage = new SimpleImage();
						$theimage->load($_FILES["file"]["tmp_name"]);
						$theimage->resizeToWidth(1600);
						$theimage->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());

						$image->setSizeX($theimage->getWidth());
						$image->setSizeY($theimage->getHeight());
						
						//move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());
					}

					ImageAddCategory($image->getID(), $imgcatid);
					ImageAddToContent($cid, $imghandle, $imgpos);

					$alttext = MTextNewInCategory("imageTexts", $imagealttext);
					MTextUpdateTextContentCopyAllLanguages($alttext);

					$image->setAltTextID($alttext->getID());

					$image->setID(0);
					ImageSave($image, $langid);
						
					print	MTextGet("imageSaved") . '<input type="hidden" name="status" id="status" value="ok"/>';

				}else print MTextGet("imageHandleExists");
			}else print MTextGet("fileTypeNotSupported");
		}else print MTextGet("fileToLarge");
	}else print MTextGet("noFileData");
	
}

function loadscripts(){
	print	'<script type="text/javascript" src="/trms-admin/js/content_image_upload.js"></script>';
	//print	"<script type=\"text/javascript\" src=\"/trms-admin/js/jquery.form.js\"></script>\n";
}
?>