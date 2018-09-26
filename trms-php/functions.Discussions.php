<?php

/*
mysql> desc Discussions;
+-------------------------+--------------+------+-----+---------+-------+
| Field                   | Type         | Null | Key | Default | Extra |
+-------------------------+--------------+------+-----+---------+-------+
| DiscussionID            | int(11)      | YES  |     | NULL    |       |
| DiscussionReferenceID   | int(11)      | YES  |     | NULL    |       |
| DiscussionParentID      | int(11)      | YES  |     | NULL    |       |
| DiscussionAuthorID      | int(11)      | YES  |     | NULL    |       |
| DiscussionArchiveFlag   | int(11)      | YES  |     | NULL    |       |
| DiscussionCreatedDate   | datetime     | YES  |     | NULL    |       |
| DiscussionReferenceType | varchar(20)  | YES  |     | NULL    |       |
| DiscussionTitleText     | tinytext     | YES  |     | NULL    |       |
| DiscussionBodyText      | mediumtext   | YES  |     | NULL    |       |
| DiscussionAuthorName    | varchar(200) | YES  |     | NULL    |       |
| DiscussionIPNumber      | varchar(255) | YES  |     | NULL    |       |
+-------------------------+--------------+------+-----+---------+-------+
11 rows in set (0.00 sec)
*/

define("DISCUSSION_SELECT", "SELECT Discussions.DiscussionID, Discussions.DiscussionReferenceID, Discussions.DiscussionParentID, Discussions.DiscussionAuthorID, Discussions.DiscussionArchiveFlag, Discussions.DiscussionCreatedDate, Discussions.DiscussionTitleText, Discussions.DiscussionBodyText, Discussions.DiscussionAuthorName, Discussions.DiscussionIPNumber FROM Discussions ");
define("DISCUSSION_INSERT", "INSERT INTO Discussions (DiscussionID, DiscussionReferenceID, DiscussionParentID, DiscussionAuthorID, DiscussionArchiveFlag, DiscussionCreatedDate, DiscussionReferenceType, DiscussionTitleText, DiscussionBodyText, DiscussionAuthorName, DiscussionIPNumber) ");


// Map the data into object
function DiscussionSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['DiscussionID']);
		$instance->setReferenceID($row['DiscussionReferenceID']);
		$instance->setParentID($row['DiscussionParentID']);
		$instance->setAuthorID($row['DiscussionAuthorID']);
		$instance->setFlag($row['DiscussionArchiveFlag']);
		$instance->setCreatedDate($row['DiscussionCreatedDate']);
		$instance->setReference($row['DiscussionReferenceType']);
		$instance->setTitleText($row['DiscussionTitleText']);
		$instance->setText($row['DiscussionBodyText']);
		$instance->setAuthorName($row['DiscussionAuthorName']);
		$instance->setIPNumber($row['DiscussionIPNumber']);
		
		return $instance;
	}
}

// Go to the next row
 
function DiscussionGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['DiscussionID']);
		$instance->setReferenceID($row['DiscussionReferenceID']);
		$instance->setParentID($row['DiscussionParentID']);
		$instance->setAuthorID($row['DiscussionAuthorID']);
		$instance->setFlag($row['DiscussionArchiveFlag']);
		$instance->setCreatedDate($row['DiscussionCreatedDate']);
		$instance->setReference($row['DiscussionReferenceType']);
		$instance->setTitleText($row['DiscussionTitleText']);
		$instance->setText($row['DiscussionBodyText']);
		$instance->setAuthorName($row['DiscussionAuthorName']);
		$instance->setIPNumber($row['DiscussionIPNumber']);
		
		return $instance;
	}
}

function DiscussionGetByID($id){
 global $dbcnx;
 $instance = new Discussion;
 $sqlstr = DISCUSSION_SELECT . " WHERE DiscussionID = " . $id;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function DiscussionGetByID; No Discussion width ID: ". $id. "<br/> " . $sqlstr . "<br/>" . mysqli_error($dbcnx) );
	}
	$instance = DiscussionSetAllFromRow($instance);

	return $instance;
}


function DiscussionGetAll(){
 global $dbcnx;
 $instance = new Discussion;
 $sqlstr = DISCUSSION_SELECT . "ORDER BY Discussions.DiscussionCreatedDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function DiscussionGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}

function DiscussionGetAllWithStatus($status){
 global $dbcnx;
 $instance = new Discussion;
 $sqlstr = DISCUSSION_SELECT . " WHERE Discussions.DiscussionArchiveFlag = " .$status." ORDER BY Discussions.DiscussionCreatedDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function DiscussionGetAllWithStatus(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}
		 
function DiscussionGetAllForReferenceID($referenceid){
 global $dbcnx;
 $instance = new Discussion;
 $sqlstr = DISCUSSION_SELECT . " WHERE Discussions.DiscussionReferenceID = ".$referenceid." ORDER BY Discussions.DiscussionCreatedDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function DiscussionGetForReferenceID(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}


function DiscussionGetAllWithStatusForReferenceID($status, $referenceid){
 global $dbcnx;
 $instance = new Discussion;
 $sqlstr = DISCUSSION_SELECT . " WHERE Discussions.DiscussionReferenceID = ".$referenceid." AND Discussions.DiscussionArchiveFlag = ". $status . " ORDER BY Discussions.DiscussionCreatedDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function DiscussionGetAllWithStatusForReferenceID(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}

function DiscussionSave($instance){
	 global $dbcnx;
	
	if($instance->getID() > 0){
		$sql = "UPDATE Discussions SET DiscussionReferenceID=".$instance->getReferenceID().", DiscussionParentID=".$instance->getParentID().", DiscussionAuthorID  =".$instance->getAuthorID().", DiscussionArchiveFlag=".$instance->getFlag().", DiscussionCreatedDate='".$instance->getCreatedDate()."', DiscussionReferenceType='".$instance->getReference()."', DiscussionTitleText='".$instance->getTitleText()."', DiscussionBodyText='".$instance->getText()."', DiscussionAuthorName='".$instance->getAuthorName()."', DiscussionIPNumber='".$instance->getIPNumber()."' WHERE DiscussionID=". $instance->getID();

		mysqli_query($dbcnx, $sql);

		//print $sql;

	}else{
		$value = TermosGetCounterValue("DiscussionID");
		TermosSetCounterValue("DiscussionID", ++$value);
		$instance->setID($value);
		
		$sql = DISCUSSION_INSERT . "VALUES (".$instance->getID().",".$instance->getReferenceID().",".$instance->getParentID().",".$instance->getAuthorID().",".$instance->getFlag().", '".$instance->getCreatedDate()."','".$instance->getReference()."','".$instance->getTitleText()."','".$instance->getText()."','".$instance->getAuthorName()."','".$instance->getIPNumber()."')";

		mysqli_query($dbcnx, $sql);

		//print $sql;
	}

	return $instance->getID();
}

function DiscussionDelete($did){
	global $dbcnx;

	$sql = "DELETE FROM Discussions WHERE DiscussionID = " . $did;
	mysqli_query($dbcnx, $sql);
	
}

function DiscussionDeleteAllWithReferenceID($referenceid){
	global $dbcnx;

	$sql = "DELETE FROM Discussions WHERE DiscussionReferenceID = " . $referenceid;
	mysqli_query($dbcnx, $sql);
	
}

function DiscussionCountAll($referenceid){
	global $dbcnx;

	$sqlstr = "SELECT COUNT(*) FROM Discussions WHERE Discussions.DiscussionReferenceID = " . $referenceid;

	$result = @mysqli_query($dbcnx, $sqlstr);
	// return mysql_result($row,0,0);
	$row = mysqli_fetch_assoc($result);
	return $row['COUNT(*)'];
	
}

function DiscussionCountAllApproved($referenceid){
	global $dbcnx;

	$sqlstr = "SELECT COUNT(*) FROM Discussions WHERE Discussions.DiscussionArchiveFlag = 1 AND Discussions.DiscussionReferenceID = " . $referenceid;

	$result = @mysqli_query($dbcnx, $sqlstr);
	//return mysql_result($row,0,0);
	$row = mysqli_fetch_assoc($result);
	return $row['COUNT(*)'];
}



?>
