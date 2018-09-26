<?php

/*
+----------------+--------------+------+-----+---------+-------+
| Field          | Type         | Null | Key | Default | Extra |
+----------------+--------------+------+-----+---------+-------+
| AddressID      | int(11)      | YES  |     | NULL    |       |
| RegionCode     | varchar(10)  | YES  |     | NULL    |       |
| CompanyName    | varchar(100) | YES  |     | NULL    |       |
| URL            | varchar(255) | YES  |     | NULL    |       |
| EMail          | varchar(100) | YES  |     | NULL    |       |
| Address1       | varchar(100) | YES  |     | NULL    |       |
| Address2       | varchar(100) | YES  |     | NULL    |       |
| Zip            | varchar(20)  | YES  |     | NULL    |       |
| City           | varchar(50)  | YES  |     | NULL    |       |
| CountryID      | int(11)      | YES  |     | NULL    |       |
| Tel            | varchar(50)  | YES  |     | NULL    |       |
| Fax            | varchar(50)  | YES  |     | NULL    |       |
| Contact        | varchar(50)  | YES  |     | NULL    |       |
| ExternalID     | varchar(50)  | YES  |     | NULL    |       |
| AdditionalInfo | text         | YES  |     | NULL    |       |
| Flag           | int(11)      | YES  |     | NULL    |       |
+----------------+--------------+------+-----+---------+-------+


+---------------------------+----------+------+-----+---------+-------+
| Field                     | Type     | Null | Key | Default | Extra |
+---------------------------+----------+------+-----+---------+-------+
| PartnerCategoryID         | int(11)  | YES  |     | NULL    |       |
| PartnerCategoryNameTextID | char(20) | YES  |     | NULL    |       |
+---------------------------+----------+------+-----+---------+-------+

*/


$usergroup_select = "SELECT PartnerCategories.PartnerCategoryID, PartnerCategories.PartnerCategoryNameTextID FROM PartnerCategories ";

$usergroup_insert = "INSERT INTO PartnerCategories (PartnerCategoryID, PartnerCategoryNameTextID) ";

$usergroup_condition_1 = " WHERE PartnerCategoryID = ";

// Map the data into object
function UserGroupSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PartnerCategoryID']);
		$instance->setUserGroupNameTextID($row['PartnerCategoryNameTextID']);
		
		return $instance;
	}
}

// Go to the next row
function UserGroupGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
	
		$instance->setID($row['PartnerCategoryID']);
		$instance->setUserGroupNameTextID($row['PartnerCategoryNameTextID']);
		
		return $instance;
	}
}

function UserGroupGetByID($id){
 global $dbcnx, $usergroup_select;
 $instance = new UserGroup;
 $sqlstr = $usergroup_select . " WHERE PartnerCategoryID = " . $id;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function UserGroupGetByID; No Usergroup width ID: ". $id. "<br/> " . mysqli_error($dbcnx) );
	}

	
	$instance = UserGroupSetAllFromRow($instance);

	return $instance;
}


function UserGroupGetAll(){
 global $dbcnx,  $usergroup_select;
 $instance = new UserGroup;
 $sqlstr = $usergroup_select;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGroupGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}


function UserGroupSave($usergroup){
	 global $dbcnx, $usergroup_insert;
	
	if($usergroup->getID() > 0)	// Update DB row //
	{
		$sql = "UPDATE PartnerCategories SET PartnerCategoryGetNameTextID='".$usergroup->getUserGroupNameTextID()."', WHERE PartnerCategoryID=". $usergroup->getID();

		mysqli_query($dbcnx, $sql);

	}
	else					// Create new DB row //
	{
		$value = TermosGetCounterValue("PartnerCategoryID");
		TermosSetCounterValue("PartnerCategoryID", ++$value);
		$usergroup->setID($value);
		
		$sql = $usergroup_insert . "VALUES (".$usergroup->getID().",'".$usergroup->getUserGroupNameTextID()."')";

		mysqli_query($dbcnx, $sql);
	}
}

function UserGroupDelete($gid){
	global $dbcnx;

	$group = UserGroupGetByID($gid);
	MTextDelete($group->getUserGroupNameTextID());

	$sql = "DELETE FROM PartnerCategories WHERE PartnerCategoryID = " . $gid;
	mysqli_query($dbcnx, $sql);

}

?>