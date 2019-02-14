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

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
DBcnx();
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

	print	'<form id="contentImgUpload" action="/trms-admin/ajax/content_image_upload.php" name="contentImgUpload" method="POST" enctype="multipart/form-data">' .
			'<label for="file">'.MTextGet("selectFile").'</label><br/><input type="file" id="file" name="file" size="50"/><br/>' .
			'<label for="imghandle">'.MTextGet("imgHandle").'</label><br/><input type="text" id="imghandle" name="imghandle" size="55"/><br/><br/>';

	print 	MTextGet("imgwidthinfo"). '<br/><br/>';	
	print	'<input type="text" name="imagewidth" id="imagewidth" size="4"/> '.  MTextGet("imagewidth") .  
			' <input type="text" name="imagewidthlarge" id="imagewidthlarge" size="4"/> '.  MTextGet("imagelar") . 
			' <input type="text" name="imagewidthmid" id="imagewidthmid" size="4"/> '.  MTextGet("imagemid") .  
			' <input type="text" name="iconwidth" id="iconwidth" size="4"/> '. MTextGet("iconwidth") ;
	
	print	'<br/><br/><label for="imgcatid">'.MTextGet("chooseImgCat").'</label><br/><select name="imgcatid" id="imgcatid">' .
			'<option value="0">'. MTextGet("selectImgCat") . '</option>';
			$imgcat = ImageCategoryGetAll();
			while($imgcat = ImageCategoryGetNext($imgcat)){
				print '<option value="'.$imgcat->getID().'">' . $imgcat->getName() . '</option>';
			}
	print	'</select><br/>';

	print	'<label for="imagealttext">'.MTextGet("imageAltText").'</label><br/><textarea name="imagealttext" id="imagealttext" class="alttextbox"></textarea>';
	
	print	'<input type="hidden" name="action" id="action" value="uploadImage"/>' .
			'<input type="hidden" name="imgpos" id="imgpos" value="'.$imgpos.'"/>' .
			'<input type="hidden" name="cid" id="cid" value="'.$cid.'"/>' .
			'<input type="submit" value="Upload"/>' .
			'</form>';

	print	'<div id="output"></div>';
}

function uploadImage($cid, $imgpos){
	
	if(isset($_REQUEST["imghandle"]))$imghandle = $_REQUEST["imghandle"];
	if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"];
	if(isset($_REQUEST["imagealttext"]))$imagealttext = $_REQUEST["imagealttext"];
	if(isset($_REQUEST["imagealttextid"]))$imagealttextid = $_REQUEST["imagealttextid"];

	if(isset($_REQUEST["imagewidth"]))$imagewidth = $_REQUEST["imagewidth"];
	if(isset($_REQUEST["imagewidthlarge"]))$imagewidthlarge = $_REQUEST["imagewidthlarge"];
	if(isset($_REQUEST["imagewidthmid"]))$imagewidthmid = $_REQUEST["imagewidthmid"];
	if(isset($_REQUEST["iconwidth"]))$iconwidth = $_REQUEST["iconwidth"];

	$langid = -1;

	//print $cid ." " . $imghandle;
	
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
					ImageAddToContent($cid, $imghandle, $imgpos);

					$alttext = MTextNewInCategory("imageTexts", $imagealttext);
					MTextUpdateTextContentCopyAllLanguages($alttext);

					$image->setAltTextID($alttext->getID());

					$image->setID(0);
					ImageSave($image, $langid);
						
					print	MTextGet("imageSaved") . 
							"<input type=\"hidden\" name=\"status\" id=\"status\" value=\"ok\"/>";

				}else print MTextGet("imageHandleExists");
			}else print MTextGet("fileTypeNotSupported");
		}else print MTextGet("fileToLarge");
	}else print MTextGet("noFileData");
	
}

function loadscripts(){
	print	'<script type="text/javascript" src="/trms-admin/js/content_image_upload.js"></script>';
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
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	return $dst;
}
?>