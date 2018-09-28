<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Discussions.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Discussions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
//loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["did"]))$did = $_REQUEST["did"]; 


if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $oid, $startdate, $enddate, $orderdetail, $orderdetailvalue;
	
	if($action == "viewcomment")
		commentView( $did );
	else if($action == "deletecomment")
		deleteComment($did);
	else if($action == "approvecomment")
		approveComment($did);

}else print "Please login";


function commentView($did){
	$comment = DiscussionGetByID($did);
	print	"<table class=\"widetable\"><tr><td>";
	print	"<table class=\"viewcomment\">" .
			"<tr><td class=\"text\">Text</td><td class=\"author\">AuthorName</td><td class=\"commentdate\">Date</td><td class=\"btns\"></td><td class=\"btns\"></td></tr>" .
			"<tr>" .
			"	<td>".$comment->getText()."</td>" .		
			"	<td>".$comment->getAuthorName()."</td>" .
			"	<td>".substr($comment->getCreatedDate(), 0,16 )."</td>" .
			"	<td class=\"btns\"><input type=\"button\" id=\"approvecomment_".$comment->getID()."\" value=\"Approve\"/></td>".
			"	<td class=\"btns\"><input type=\"button\" id=\"deletecomment_".$comment->getID()."\" value=\"Delete\"/></td>".
			"</tr>".
			"</table>";
	
	
	print	"</td></tr></table>";
}

function deleteComment($did){

	DiscussionDelete($did);
}


function approveComment($did){
	$comment =  DiscussionGetByID($did);
	$comment->setFlag(1);
	DiscussionSave($comment);
}

function loadscripts(){
	//print "<script type=\"text/javascript\" src=\"/trms-admin/js/order_admin_ajax.js\"></script>\n";
}
?>