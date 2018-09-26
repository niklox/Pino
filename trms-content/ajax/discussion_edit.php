<?php

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Discussions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Discussions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["signature"]))$signature = $_REQUEST["signature"];
if(isset($_REQUEST["commenttext"]))$commenttext = $_REQUEST["commenttext"];

if($action == "savecomment")
	saveComment( $cid, $commenttext, $signature );
else if($action == "showcomments")
	showComments( $cid );
else
	showComments( $cid );

function saveComment( $cid, $commenttext, $signature ){

	$discussion = new Discussion;

	$discussion->setReferenceID($cid);
	$discussion->setParentID(0);
	$discussion->setAuthorID(0);
	$discussion->setFlag(0);
	$discussion->setCreatedDate(date("Y-m-d H:i:s"));
	$discussion->setReference("");
	$discussion->setTitleText("");
	$discussion->setText($commenttext);
	$discussion->setAuthorName($signature);
	$discussion->setIPNumber($_SERVER["REMOTE_ADDR"]);

	DiscussionSave($discussion);

	$content = ContentGetByID($cid);
	$headers = "From: termos-cms@". $_SERVER["SERVER_NAME"] ."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$message = "The content \"" . strip_tags(MTextGet($content->getTitleTextID())) . "\" has been commented.\n" .
	$message .= "Please go to http://" . $_SERVER["SERVER_NAME"] . "/trms-admin to approve or reject.";

	$subject = "A new comment at " . $_SERVER["SERVER_NAME"];

	mail("pinolek@telia.com", $subject, $message, $headers);

	//showComments($cid);
}


function showComments( $cid ){

	$discussion = DiscussionGetAllWithStatusForReferenceID( 1, $cid);
		while($discussion = DiscussionGetNext($discussion)){

			$date = date_create($discussion->getCreatedDate());
			print	"<div class=\"discussionitem\">\n";
			print	"<p class=\"discussion-sign\"><span class=\"discussion-name\">" .$discussion->getAuthorName(). "</span>, ".date_format($date, 'H:i, d F Y')."</p>";
			print	"<p class=\"discussiontext\">" . $discussion->getText() . "</p>";
			print	"</div>\n";
		}
}


?>
