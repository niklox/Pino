<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Topic.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Topic.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["message"]))$message = $_REQUEST["message"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"];
if(isset($_REQUEST["orgname"]))$orgname = $_REQUEST["orgname"];

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print '<div id="content">';

if(isset($admin))
{
	global $action, $oid, $orgname;
	defaultAction();
}
else
{
	print "Please login!";
}

htmlEnd();


function defaultAction(){
	$row = 0;

	print 		'<div id="gf_topic">'.
				'<div id="gf_topic_head"><ul id="headtabs"><li id="search">Topics</li></li><li id="newtopic">Create new</li><li id="closesearch" class="close_btn">x</li></ul></div>'.
				'<div id="gf_topic_body">';
				
	print		'<form id="newtopicform">'.
				'<input type="hidden" id="action" name="action" value="createnewtopic"/>'.
				'<div class="gf_column_1">'.
				'<input type="text" class="gf_input" id="new_topic_name" name="new_topic_name" value="Topic name"/> '. 
				'<input type="submit" class="gf_submit" value="Create new topic"/>'.
				'</div>'. 
				'</form>';
				
	print 		'<div id="gf_topic_list">';
	
		print 	'<div id="console"></div>';
	
	
	$topics = TopicGetAllByStatus(1);
	
	print 	'<table id="topiclist">' .
			'<tr><td style="width:20px"></td><td>Topic</td><td>ID</td><td tyle="width:20px">Current</td><td tyle="width:20px"></td></tr>';
	
	while($topics = TopicGetNext($topics)){
		
		
		
		$row++;
		if($row % 2)
		print 	'<tr class="topic_row_even" id="topic_'. $topics->getID() . '">';
		else
		print 	'<tr class="topic_row" id="topic_' . $topics->getID() . '">';
		
		print 	'<td><img class="editrow" src="/trms-admin/images/icon_edit.gif" id="topicedit_'.$topics->getID().'"/></td>'.
				'<td>'.$topics->getTopic().'</td>'.
				'<td>TopicID: '.$topics->getID().'</td>'.
				'<td><input type="checkbox" id="topicsetstatus_'.$topics->getID().'" '.($topics->getStatus() == 1?"CHECKED":"").'/></td>'.
				'<td><img class="deleterow" src="/trms-admin/images/delete_mini.gif" id="topicdelete_'.$topics->getID().'"/></td>'.
				'</tr>';
	
	}
	
	print 	'</table>';
	print 	'<input type="button" class="gf_button_sharp" id="topicviewall" value="View all topics"/>';
	
	print		'</div>';
				
	print 		'</div>' .
				'</div>';
				
}


function htmlStart(){

    print 	'<!DOCTYPE html>' .
	  		'<html>' .
	  		'<head>' .
	  		'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
	  		'<title>Termos Organisation</title>' .
	  		'<link rel="stylesheet" href="css/termosadmin.css"/>' .
	  		'<link rel="stylesheet" href="css/globaldb.css"/>' .
	  		'<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/globaldb.js"></script>' .
			'</head>' .
	  		'<body>';
}

function htmlEnd(){
	
    print 	'</div>' .
	        '</body>' .
	  		'</html>';

}


?>