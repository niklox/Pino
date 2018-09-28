<?
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';

if($admin = UserGetUserByID(TermosGetCurrentUserID())){
	print "var tinyMCELinkList = new Array(\n";
	renderLinksForTinyMCE();
	print ");\n";
}
else{
	print "Please login!";
}

function renderLinks($rid, $level){
	
	$level++;
	if($level < MAXLEVELS){

		$node = NodeGetAllChildren($rid);
		while($node = NodeGetNext($node)){
			
			print ",\n[\"";
			
			for($i=0; $i<$level; $i++)print "- ";
			print $node->getName(). "\", \"/". $node->getPermalink() ."\"]";

			if(NodeHasChildren($node->getID()))
				 renderLinks($node->getID(),  $level);
		}
	  }else return;
}

function renderLinksForTinyMCE(){
	$counter = 0;
	$roots = NodeGetAllRoots();
	while($roots = NodeGetNext($roots)){

		if($counter > 0)print ",";
		print "[\"". $roots->getName(). "\", \"/". $roots->getPermalink() ."\"]\n";
		$counter++;
		
		renderLinks($roots->getID(), 0);
	}
}

?>