<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Discussions.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Discussions.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];
if(isset($_REQUEST["ctext"]))$ctext = $_REQUEST["ctext"];


if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){
	
	if($action == "savecomment")
			saveComment($cid, $recordid, $ctext);
	else if($action == "deletecomment")
			deleteComment($cid, $recordid);
	else if($action == "listcomments")
			displayCommentList($recordid);
	else if($action == "setcommentstatus")
			setCommentStatus($cid);
	else if($action == "unsetcommentstatus")
			unsetCommentStatus($cid);
	else	displayCommentList($recordid);
}

function saveComment($cid, $recordid, $ctext){
	global $admin;
	
	
	if($cid == 0){
	$comment = new Discussion;
	
	$comment->setID($cid);
	$comment->setReferenceID($recordid);
	$comment->setParentID(0);
	$comment->setAuthorID($admin->getID());
	$comment->setFlag(0);
	$comment->setCreatedDate(date("Y-m-d H:i:s"));
	
	$comment->setReference("gf_contact_comment");
	$comment->setTitleText("");
	$comment->setText($ctext);
	$comment->setAuthorName($admin->getFullname());
	$comment->setIPNumber($_SERVER["REMOTE_ADDR"]);
	
	}else{
	
	$comment = DiscussionGetByID($cid);
	$comment->setReferenceID($recordid);
	$comment->setReference("gf_contact_comment");
	$comment->setText($ctext);
	$comment->setAuthorName($admin->getFullname());
	$comment->setIPNumber($_SERVER["REMOTE_ADDR"]);
	
	}
	
	DiscussionSave($comment);


	//print $cid . '<br/>' . $recordid . '<br/>' . $ctext; 
	
	
	displayCommentList($recordid);

}

function deleteComment($cid, $recordid){

	DiscussionDelete($cid);
	displayCommentList($recordid);
}

function displayCommentList($recordid){

 print '<div id="wcp_comment_list">';
 print '<input type="button" class="gf_button_sharp" id="newcontactcomment" value="New Comment"/>';
 print '<input type="hidden" id="commentid", value="0"/>';
 print '<div id="editcontactcomment"><textarea id="contactcommenttext"></textarea><br/><input type="button" id="savecontactcomment" class="gf_button_sharp" value="Save"/></div>';
 
// print $recordid;
 
 $comments = DiscussionGetAllByReferenceTypeAndID($recordid, "gf_contact_comment");

 
 while($comments = DiscussionGetNext($comments)){
 
 	print '<p class="contactcomment" id="comment_'.$comments->getID().'">' . nl2br($comments->getText()) . '</p>'.
 		  '<p class="bylinecomment">'.substr($comments->getCreatedDate(),0,16). ' '. $comments->getAuthorName(). 
 		  ' <img src="/trms-admin/images/edit_mini.gif" id="editcomment_'.$comments->getID().'"/> '.
 		  '<img src="/trms-admin/images/delete_mini.gif" id="deletecomment_'.$comments->getID().'"/>'.
 		  ' <input type="checkbox" name="" id="checkcomment_'.$comments->getID().'" '.($comments->getFlag() & 1?"":"checked" ).'/> Granskad/Läst' .
 		  '</p>';
 		  
 	print '<p id="testbug"></p>';
 
 }
 
 print '</div>';

}

function setCommentStatus($cid){
	// it is checked
	
	print 'Nu sätter vi ettan';
	DiscussionSetStatus($cid, 1);

}

function unsetCommentStatus($cid){
	//it is unchecked
	print 'Nu sätter vi nollan';
	DiscussionRemoveStatus($cid, 1);



}






?>