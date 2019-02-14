<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
DBcnx();
?>
<!DOCTYPE html">

<html>
<head>
<title>Termos login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/trms-admin/js/content_admin.js"></script>
<link rel="stylesheet" href="./css/termosadmin.css"/>
</head>
<body>
<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($admin)){
	
	print	'<div id="content">';
			//'<h3>Redigera</h3>';
	
	$products = ContentGetAllInNode(PRODUCTS);
	while($products = (ContentGetNext($products))){
	
	$position = ContentHasNode($products->getID(), PRODUCTS);
	
	print '<div class="productbox" draggable="true" ondragstart="dragstart_handler(event);" ondrop="drop_handler(event);" ondragover="dragover_handler(event);" id="prodbox_'.$products->getID().'">';
	/*print '<div class="editproduct">'.
			'<div class="editarea">'.
			'<input type="text" class="std_input" name="" id="" value="'.MTextGet($products->getTitleTextID()).'" placeholder="Titel">'.
			'<input type="text" class="std_input" name="" id="" value="'.MTextGet($products->getPermalinkTextID()).'" placeholder="Link">'.
			'<textarea></textarea>'.
			
			'</div>'.
			'<div class="editarea">2</div>'.
			'<div class="editarea">3</div>';
	*/
	
	print '<div class="productpos">'.$position.'</div>';
	print '<div class="productthumb" id="procuctthumb_'.$products->getID().'">';
	ImageGetII_SMALL($products->getID(),1,"");
	print '</div>';
	
	
	print '<div class="producttext">';
	//print  $position . ' ' .MTextGet($products->getTitleTextID()). ' ' . $products->getFlag();
	print '<h2><a href="/trms-admin/content.php?action=editContent&nid='.$products->getDefaultNodeID().'&cid='.$products->getID().'">' . MTextGet($products->getTitleTextID()).'</a></h2>';
	print '</div>';
	
	print '<div class="producttext" id=""></div>';
	
	
	//print '<div class="editcontent">
	//i<br/>
	//d
	//</div>';
	
	//print '</div>';
	print '</div>';
	}
	
	print	'<form action="setlanguage.php">' .
			'<input type="hidden" name="returnpath" id="returnpath" value="' . $_SERVER["SCRIPT_NAME"] . '"/>' .
			'<select name="languageid" id="languageid=">' .
			'<option value="'. TermosGetDefaultLanguage() .'">'. MTextGet("selectlanguage"). '</option>';
			
			$language = LanguageGetAll();

			while($language = LanguageGetNext($language)){
				if($language->getID() == TermosGetCurrentLanguage())
				print '<option value="'.$language->getID().'" selected>'. $language->getName() .'</option>';
				else
				print '<option value="'.$language->getID().'">'. $language->getName() .'</option>';
			}
	print	' &nbsp;<input type="submit" value="'. MTextGet("setlanguage") .'"/>';
	print	'</select>';
	
	
	print	'<div class="dashboardbox">' .
			'	<div class="dashboardbox_head">Snabblänkar</div>' .
			'	<div class="dashboardbox_body">' ;
	print	'<p>Lägga till produkter <a href="https://docs.google.com/document/pub?id=19vNEqKxzi9zRE2Y_pcMziYAOdnaJBkFJn6XM13Sqm4M" target="_new">lathund &#187;</a></p>';
	print	'	</div>' .
			'</div>';
			
			
	print '</div>';
}else{
	print	'<div id=content>';
	print 	'<div id="loginbox">';
	
	print	
			'<form action="/trms-admin/logincheck.php" method="post"/>'.
			'Username or Email Address<br/><input type="text" name="AccountName" id="AccountName" size="28"/><br/>'.
			'Password<br/><input type="password" name="Password" id="Password" size="25"/><br/>'.
			'<input type="submit" id="Loginbtn" value="Login"/><br/>'.
			'</form>';	
	
	print '</div>';
	print '</div>';
} 
?>
</body>
</html>
