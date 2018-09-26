<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
DBcnx();
header('Content-Type: text/html; charset=utf-8');

if(isset($_REQUEST["tmplid"]))$tmplid = $_REQUEST["tmplid"];	// parentid

			$template = TemplateGetByID($tmplid);

			print "<h2>" . MTextGet($template->getNameTextID()) . "</h2>\n";
			print "<p>" . MTextGet($template->getDescriptionTextID()) . "</h5><br/><br/>";

			if($image = ImageGetByHandleAndLanguage($template->getImageHandle(), -1))
			ImageCreateTag($image, 1000, 1);
			
?>