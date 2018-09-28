<?php

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';	
DBcnx();
if(isset($_REQUEST["imgid"]))$imgid = $_REQUEST["imgid"]; 

print '<html><title>RAWIMG</title>';



if(isset($imgid)){


$image = ImageGetByID($imgid);
ImageGetNext($image);

print 'Original (filename: ' . ImageCreateFileName($image, IMGMASK, 0) . ')<br/>';
ImageCreateTagWithCSSII($image, IMGMASK, 0, "");
print '<br/>';

print 'Large (filename: ' .ImageCreateFileName($image, IMGMASK, 3) . ')<br/>';
ImageCreateTagWithCSSII($image, IMGMASK, 3, "");
print '<br/>';

print 'Medium (filename: ' . ImageCreateFileName($image, IMGMASK, 2). ')<br/>';
ImageCreateTagWithCSSII($image, IMGMASK, 2, "");
print '<br/>';

print 'Small (filename: '.ImageCreateFileName($image, IMGMASK, 1). ')<br/>';
ImageCreateTagWithCSSII($image, IMGMASK, 1, "");
print '<br/>';
}else
print 'No imageid';


print '</html>';



?>