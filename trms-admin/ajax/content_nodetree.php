<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
DBcnx();
//header('Content-Type: text/html; charset=utf-8');

if(isset($_REQUEST["nid"]))$nid = $_REQUEST["nid"];	// parentid

			print "<ul>";
			$node = NodeGetByID($nid);
			print	"<li class=\"cont_1\">". $node->getName() ."</li>\n";
			
			$content = ContentGetAllInNode( $node->getID() ); 
			while($content = ContentGetNext($content)){
				print	"<li class=\"cont_1\"><a href=\"" . CONTENT_ADMIN .  "?action=editContent&cid=". $content->getID() . "&nid=" . $node->getID() ."\"><img class=\"iconimg\" src=\"images/icon_content.gif\"/></a>" . strip_tags($content->getTitle()) . "</li>\n";
			}

			PrintNodeTreeWithContent($nid, 1);

			print "</ul>";
			
?>