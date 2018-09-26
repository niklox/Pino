<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();

if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];


$content = ContentGetByID($cid);

for($i=2;$i<6;$i++){

	if(ContentHasImageAtPosition($content->getID(), $i) && $i == 2){
		$j = $i-1;
		ImageGetWithID($content->getID(), $i, "focusimg", "focus_$j");
	}
	else if (ContentHasImageAtPosition($content->getID(), $i)){
		$j = $i-1;
		ImageGetWithID($content->getID(), $i, "extraimg" , "focus_$j");
	}
}
//print	"<div class='focustxt'>" .
//		"<h4 class='blue'>" . $content->getTitle() . "</h4>";

$contenttext = ContentTextGet($content->getID(), 2);

//print	"<div id=\"". $contenttext->getTextID() . "\">" .  MTextGet($contenttext->getTextID()) . "</div>";

//print	ImageCountAllForContent($content->getID());

//print	"</div>";
print	"<div class='focusnav'><ul>";

if(ImageCountAllForContent($content->getID()) > 2){
	for($i=2;$i<6;$i++){

		if(ContentHasImageAtPosition($content->getID(), $i) != -1)
			printf ("<li>%d</li>", $i-1);
	}
}

print "</ul></div>";




?>
