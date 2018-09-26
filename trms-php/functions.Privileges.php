<?php

/*
mysql> desc PrivCategories;
+------------------------+----------+------+-----+---------+-------+
| Field                  | Type     | Null | Key | Default | Extra |
+------------------------+----------+------+-----+---------+-------+
| PrivCategoryID         | int(11)  | YES  |     | NULL    |       |
| PrivCategoryNameTextID | char(20) | YES  |     | NULL    |       |
+------------------------+----------+------+-----+---------+-------+
*/

$privilege_select = "SELECT PrivCategories.PrivCategoryID, PrivCategories.PrivCategoryNameTextID FROM PrivCategories ";
$privilege_insert = "INSERT INTO PrivCategories (PrivCategoryID, PrivCategoryNameTextID) ";

$privilege_condition_1 = " WHERE PrivCategoryID = ";

// Map the data into object
function PrivilegeSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PrivCategoryID']);
		$instance->setPrivilegeNameTextID($row['PrivCategoryNameTextID']);
		
		return $instance;
	}
}

// Go to the next row
function PrivilegeGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
	
		$instance->setID($row['PrivCategoryID']);
		$instance->setPrivilegeNameTextID($row['PrivCategoryNameTextID']);
		
		return $instance;
	}
}

function PrivilegeGetByID($id){
 global $dbcnx, $privilege_select;
 $instance = new Privilege;
 $sqlstr = $privilege_select . " WHERE PrivCategoryID = " . $id;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function PrivilegeGetByID; No Privilege width ID: ". $id. "<br/> " . $sqlstr . "<br/>" . mysqli_error($dbcnx) );
	}

	
	$instance = PrivilegeSetAllFromRow($instance);

	return $instance;
}


function PrivilegeGetAll(){
 global $dbcnx,  $privilege_select;
 $instance = new Privilege;
 $sqlstr = $privilege_select . "ORDER BY PrivCategories.PrivCategoryID";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function PrivilegeGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}


function PrivilegeSave($privilege){
	 global $dbcnx, $privilege_insert;
	
	if($privilege->getID() > 0)	// Update DB row //
	{
		$sql = "UPDATE PrivCategories SET PrivCategoryNameTextID='".$privilege->getPrivilegeNameTextID()."', WHERE PrivCategoryID=". $privilege->getID();

		mysqli_query($dbcnx, $sql);

	}
	else					// Create new DB row //
	{
		$value = TermosGetCounterValue("PrivCategoryID");
		TermosSetCounterValue("PrivCategoryID", ++$value);
		
		$privilege->setID($value);

		$sql = $privilege_insert . "VALUES (".$privilege->getID().",'".$privilege->getPrivilegeNameTextID()."')";

		mysqli_query($dbcnx, $sql);
	}
}

function PrivilegeDelete($prid){
	global $dbcnx;

	$priv = PrivilegeGetByID($prid);
	MTextDelete($priv->getPrivilegeNameTextID());

	$sql = "DELETE FROM UserPrivCategories WHERE PrivCategoryID = " . $prid;
	mysqli_query($dbcnx, $sql);
	
	$sql = "DELETE FROM PrivCategories WHERE PrivCategoryID = " . $prid;
	mysqli_query($dbcnx, $sql);

}



?>