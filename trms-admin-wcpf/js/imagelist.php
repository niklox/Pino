<?
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';


$counter = 0;
$catcounter = 0;

if($admin = UserGetUserByID(TermosGetCurrentUserID())){
	print 'var tinyMCEImageList = new Array(';

	// Name, URL
	//print	"[\"Word icon\", \"/images/word-icon_I001020_-1.gif\"]," .
	//		"[\"PDF icon\", \"/images/pdf-icon_I001021_-1.gif\"]"; // Dont forget the , sign here

	// To be developed further
	 renderImagesForTinyMCE();
	
	print ');';
}
else{
	print "Please login!";
}

function renderImages($imagecat){
	global $counter, $catcounter;

	$images = ImageGetAllInCategoryOrderByHandle($imagecat->getID(), 1);
	
	if( mysql_num_rows($images->getDBRows()) > 0){
	
		if($catcounter > 0) print ',';
	
		 print '["'.MTextGet($imagecat->getNameTextID()).'",""],';
		 
		 $catcounter++;
	 
	 }
	while($images = ImageGetNext($images)){
		if($counter > 0)
		print ',["';
		else
		print '["';
		
		print '- ' . $images->getHandle(). '", "/images/'. ImageCreateFilename($images, 1000, 0) .'"]';

		$counter++;
	}
	
	$counter = 0;
}

function renderImagesForTinyMCE(){
	global $catcounter;
	$imagecat = ImageCategoryGetAllOrderByName();
	while($imagecat = ImageCategoryGetNext($imagecat)){
	
		//if( $imagecat->getID() == 64 || $imagecat->getID() == 65 ){
		renderImages($imagecat);
		//}
		
		
	}
	
	
}

?>