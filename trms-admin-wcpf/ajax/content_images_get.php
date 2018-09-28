<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["imghandle"]))$imghandle = $_REQUEST["imghandle"];

$image = ImageGetByHandleAndLanguage($imghandle, -1);
//ImageCreateTag($image, 1000, 1);
ImageCreateTagWithCSSII($image, 1000, 1, "");

print '<br/><br/>ImageHandle: '. $image->getHandle() . '&nbsp; <a style="color:red;text-decoration:underline" href="/trms-admin/rawimg.php?imgid='.$image->getID().'" target="_new">'.MTextGet("viewallsizes").' &#187;</a><br/>';
		$catid  = ImageGetCategoryID($image->getID());
		//print $catid;
		$imgcat = ImageCategoryGetByID($catid);
		print 'ImageCategory: ' . $imgcat->getName();

?>