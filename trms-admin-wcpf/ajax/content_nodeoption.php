<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
//header('Content-Type: text/html; charset=utf-8');

if(isset($_REQUEST['nid'])) $root = $_REQUEST['nid'];

$childNodes = NodeGetAllChildren($root);
if(mysql_num_rows($childNodes->getDBRows()) == 0){
	print	"[{\"optionValue\":0, \"optionDisplay\": \"". MTextGet('rootHasNoChildren') ."\"}]";
}else{ 
	print	"[{\"optionValue\":0, \"optionDisplay\": \"". MTextGet('selectNode'). "\"}";
	PrintJSONodeTreeOptions($root, 0);
	print	"]\n";
}
?>