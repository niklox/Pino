<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Topic.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Topic.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["topicid"]))$topicid = $_REQUEST["topicid"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];
if(isset($_REQUEST["new_topic_name"]))$topicname = $_REQUEST["new_topic_name"];
if(isset($_REQUEST["topicdetail"]))$topicdetail = $_REQUEST["topicdetail"];
if(isset($_REQUEST["topicdetailvalue"]))$topicdetailvalue = $_REQUEST["topicdetailvalue"];




if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){
	
	if($action == "createnewtopic")
			createTopic($topicname);
	else if($action == "edittopic")
			editTopic($topicid);
	else if($action == "displaytopic")
			displayTopic($topicid);
	else if($action == "savetopicdetail")
			saveTopicDetail($topicid, $topicdetail, $topicdetailvalue);
	else if($action == "topicsfororganisation")
			topicListForGF($recordid);
	else if($action == "deletetopic")
			deleteTopic($topicid);
	else if($action == "settopicstatus")
			TopicSetStatus($topicid, 1);
	else if($action == "unsettopicstatus")
			TopicRemoveStatus($topicid, 1);
	else if($acton == "listtopics")
			displayTopicList();
	else	displayTopicList();
}


function displayTopicList(){

	if(isset($_REQUEST["viewall"]))$viewall = $_REQUEST["viewall"];
	
	if($viewall == "yes"){ 
	$topics = TopicGetAll();
	}
	else{ 
	$topics = TopicGetAllByStatus(1);
	}
	
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
	
	if($viewall == "yes"){
		print '<input type="button" class="gf_button_sharp" id="topicviewcurrent" value="View current topics"/>';
	}else{ 
		print '<input type="button" class="gf_button_sharp" id="topicviewall" value="View all topics"/>';
	}
}


function topicListForGF($recordid){

	print '<div id="wcp_topic_list">';

	print '<h3>Topics for ' . OrganisationGetName($recordid) .'</h3>';

	$topics = TopicGetAllByStatus(1);
	while($topics = TopicGetNext($topics)){
		print '<input type="checkbox" class="checktopic" id="checktopic_'.$topics->getID().'x'.$recordid.'" ' . (OrganisationHasTopic($topics->getID(),$recordid)?"CHECKED":"") . ' /> '.$topics->getTopic().'<br/>';
	}
	print 	'</div>';
}

function createTopic($topicname){
	
	$topic = new Topic;
	
	$topic->setTopic($topicname);
	$topic->setStatus(1);
	$topic->setDescription("");
	$topic->setDate(date("Y-m-d"));
	
	TopicSave($topic);
	
	displayTopicList();
	
}

function editTopic($topicid){

	if($topicid > 0){
	
	$topic = TopicGetByID($topicid);
	print 	'<td><img class="editrow" src="/trms-admin/images/icon_content.gif" id="topicsave_'.$topic->getID().'"/></td>'.
			'<td><input type="text" class="topic_input_edit" id="topicname_'.$topic->getID().'" value="'.$topic->getTopic(). '"/></td>' .
			'<td></td>' .
			'<td></td>';
			
	}
	
}

function displayTopic($topicid){

	if($topicid > 0){
	
	$topic = TopicGetByID($topicid);
	
	print 	'<td><img class="editrow" src="/trms-admin/images/icon_edit.gif" id="topicedit_'.$topic->getID().'"/></td>'.
			'<td>'.$topic->getTopic().'</td>'.
			'<td><input type="checkbox" id="topic_'.$topic->getID().'" '.($topic->getStatus() == 1?"CHECKED":"").'/></td>'.
			'<td><img class="deleterow" src="/trms-admin/images/delete_mini.gif" id="topicdelete_'.$topic->getID().'"/></td>';
			
	}
}

function saveTopicDetail($topicid, $topicdetail, $topicdetailvalue){

	
	
	$topic = TopicGetByID($topicid);

	
	print 'id ' . $topicid . ' name ' . $topic->getTopic();
	
	switch($topicdetail){
			case 'topicname':
			$topic->setTopic($topicdetailvalue);
			
	}
		
	TopicSave($topic);
}



function deleteTopic($topicid){

	TopicDelete($topicid);

}




?>
