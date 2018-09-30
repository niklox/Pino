<?php

/*
+-------------------+--------------+------+-----+---------+-------+
| Field             | Type         | Null | Key | Default | Extra |
+-------------------+--------------+------+-----+---------+-------+
| UserID            | int(11)      | NO   | MUL | 0       |       |
| AddressID         | int(11)      | NO   | MUL | 0       |       |
| FullName          | varchar(100) | YES  |     | NULL    |       |
| AliasName         | varchar(50)  | YES  |     | NULL    |       |
| EMail             | varchar(100) | YES  |     | NULL    |       |
| PartnerCategoryID | int(11)      | YES  |     | NULL    |       |
| AccountName       | varchar(200) | YES  |     | NULL    |       |
| Password          | varchar(20)  | YES  |     | NULL    |       |
| Fax               | varchar(20)  | YES  |     | NULL    |       |
| CountryID         | int(11)      | YES  |     | NULL    |       |
| LastDate          | datetime     | YES  |     | NULL    |       |
| Counter           | int(11)      | YES  |     | NULL    |       |
| CreatedDate       | date         | YES  |     | NULL    |       |
| Approved          | int(11)      | YES  |     | NULL    |       |
| HiddenInfo        | int(11)      | YES  |     | NULL    |       |
| URL               | varchar(255) | YES  |     | NULL    |       |
| Address           | varchar(100) | YES  |     | NULL    |       |
| JobFunction       | varchar(100) | YES  |     | NULL    |       |
| Zip               | varchar(20)  | YES  |     | NULL    |       |
| City              | varchar(50)  | YES  |     | NULL    |       |
| Tel               | varchar(50)  | YES  |     | NULL    |       |
| Cell              | varchar(50)  | YES  |     | NULL    |       |
| InfoText          | text         | YES  |     | NULL    |       |
| RegionID          | int(11)      | YES  |     | NULL    |       |
| MunicipalityID    | int(11)      | YES  |     | NULL    |       |
| CompanyFlag       | int(11)      | YES  |     | NULL    |       |
| LastMessage       | date         | YES  |     | NULL    |       |
| BirthDate         | date         | YES  |     | NULL    |       |
| PreviousDate      | datetime     | YES  |     | NULL    |       |
| InfoFlag          | int(11)      | YES  |     | 0       |       |
+-------------------+--------------+------+-----+---------+-------+
*/

$user_select = "SELECT Users.UserID, Users.AddressID, Users.FullName, Users.AliasName, Users.EMail, Users.PartnerCategoryID, Users.AccountName, Users.Password, Users.Fax, Users.CountryID, Users.LastDate, Users.Counter, Users.CreatedDate, Users.Approved, Users.HiddenInfo, Users.URL, Users.Address, Users.JobFunction, Users.Zip, Users.City, Users.Tel, Users.Cell, Users.InfoText, Users.RegionID, Users.MunicipalityID, Users.CompanyFlag, Users.LastMessage, Users.BirthDate, Users.PreviousDate, Users.InfoFlag FROM Users ";

$user_insert = "INSERT INTO Users (UserID, AddressID, FullName, AliasName, EMail, PartnerCategoryID, AccountName, Password, Fax, CountryID, LastDate, Counter, CreatedDate, Approved, HiddenInfo, URL, Address, JobFunction, Zip, City, Tel, Cell, InfoText, RegionID, MunicipalityID, CompanyFlag, LastMessage, BirthDate, PreviousDate, InfoFlag) ";

// Map the data into object
function UserSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['UserID']);
		$instance->setAddressID($row['AddressID']);
		$instance->setFullname($row['FullName']);
		$instance->setAliasname($row['AliasName']);
		$instance->setEMail($row['EMail']);
		$instance->setUserGroupID($row['PartnerCategoryID']);
		$instance->setAccountName($row['AccountName']);
		$instance->setPassword($row['Password']);
		$instance->setFax($row['Fax']);
		$instance->setCountryID($row['CountryID']);
		$instance->setLastdate($row['LastDate']);
		$instance->setCounter($row['Counter']);
		$instance->setCreateddate($row['CreatedDate']);
		$instance->setApproved($row['Approved']);
		$instance->setAnonymous($row['HiddenInfo']);
		$instance->setURL($row['URL']);
		$instance->setJobfunction($row['JobFunction']);
		$instance->setAddress($row['Address']);
		$instance->setZip($row['Zip']);
		$instance->setCity($row['City']);
		$instance->setTel($row['Tel']);
		$instance->setCell($row['Cell']);
		$instance->setInfotext($row['InfoText']);
		$instance->setRegionID($row['RegionID']);
		$instance->setMunicipalityID($row['MunicipalityID']);
		$instance->setCompanyflag($row['CompanyFlag']);
		$instance->setLastmessage($row['LastMessage']);
		$instance->setPreviousdate($row['PreviousDate']);
		$instance->setBirthdate($row['BirthDate']);
		$instance->setInfoFlag($row['InfoFlag']);
		return $instance;
	}
}

// Go to the next row
function UserGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
	
		$instance->setID($row['UserID']);
		$instance->setAddressID($row['AddressID']);
		$instance->setFullname($row['FullName']);
		$instance->setAliasname($row['AliasName']);
		$instance->setEMail($row['EMail']);
		$instance->setUserGroupID($row['PartnerCategoryID']);
		$instance->setAccountName($row['AccountName']);
		$instance->setPassword($row['Password']);
		$instance->setFax($row['Fax']);
		$instance->setCountryID($row['CountryID']);
		$instance->setLastdate($row['LastDate']);
		$instance->setCounter($row['Counter']);
		$instance->setCreateddate($row['CreatedDate']);
		$instance->setApproved($row['Approved']);
		$instance->setAnonymous($row['HiddenInfo']);
		$instance->setURL($row['URL']);
		$instance->setJobfunction($row['JobFunction']);
		$instance->setAddress($row['Address']);
		$instance->setZip($row['Zip']);
		$instance->setCity($row['City']);
		$instance->setTel($row['Tel']);
		$instance->setCell($row['Cell']);
		$instance->setInfotext($row['InfoText']);
		$instance->setRegionID($row['RegionID']);
		$instance->setMunicipalityID($row['MunicipalityID']);
		$instance->setCompanyflag($row['CompanyFlag']);
		$instance->setLastmessage($row['LastMessage']);
		$instance->setPreviousdate($row['PreviousDate']);
		$instance->setBirthdate($row['BirthDate']);
		$instance->setInfoFlag($row['InfoFlag']);

		return $instance;
	}
}

function UserGetUserByID($id){
 global $dbcnx, $user_select;
 $instance = new User;
 $sqlstr = $user_select . " WHERE UserID = " . $id;
	
	$row = @mysqli_query( $dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGetUserByID(); No user width ID: '. $id . "<br/> " . mysqli_error($dbcnx) . "<br/>" .$sqlstr);
	}
	$instance = UserSetAllFromRow($instance);

	return $instance;
}

function UserGetUserByAccountName($accountName){
 global $dbcnx, $user_select;
 $instance = new User;
 $sqlstr = $user_select . " WHERE AccountName = '" . $accountName ."' ";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGetUserByAccountName(); ' . mysqli_error($dbcnx) );
	}

	$instance = UserSetAllFromRow($instance);
	return $instance;
}

function UserGetAll(){
 global $dbcnx, $user_select;
 $instance = new User;
 $sqlstr = $user_select;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}

function UserGetAllWithPrivilege($prid){
 global $dbcnx, $user_select;
 $instance = new User;
 $sqlstr = $user_select . ", UserPrivCategories WHERE Users.UserID = UserPrivCategories.UserID AND UserPrivCategories.PrivCategoryID = " . $prid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGetAllWithPrivilege(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}


function UserGetAllInGroup($gid){
 global $dbcnx, $user_select;
 $instance = new User;
 $sqlstr = $user_select . " WHERE Users.PartnerCategoryID = " . $gid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function UserGetAllInGroup(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}
//SELECT Users.UserID FROM Users, UserPrivCategories WHERE Users.UserID = UserPrivCategories.UserID AND UserPrivCategories.PrivCategoryID = 2; 

function UserSave($user){
	 global $dbcnx, $user_insert;
	
	if($user->getID() > 0)	// Update DB row //
	{
		$sql = "UPDATE Users SET AddressID=".$user->getAddressID().", FullName='".addslashes($user->getFullname())."', AliasName='".addslashes($user->getAliasname())."', EMail='".$user->getEMail()."', PartnerCategoryID=".$user->getUserGroupID().", AccountName='".$user->getAccountname()."', Password='".$user->getPassword()."', Fax='".$user->getFax()."', CountryID=".$user->getCountryID().", Counter=".$user->getCounter().", CreatedDate='".$user->getCreateddate()."', LastDate='".$user->getLastdate()."', Approved=".$user->getApproved().", HiddenInfo=".$user->getAnonymous().", URL='".addslashes($user->getURL())."', Address='".addslashes($user->getAddress())."', JobFunction='".addslashes($user->getJobfunction())."', Zip='".addslashes($user->getZip())."', City='".addslashes($user->getCity())."', Tel='".addslashes($user->getTel())."', Cell='".addslashes($user->getCell())."', InfoText='".addslashes($user->getInfotext())."', RegionID=".$user->getRegionID().", MunicipalityID=".$user->getMunicipalityID().", CompanyFlag=".$user->getCompanyflag().", LastMessage='".$user->getLastMessage()."', BirthDate='".$user->getBirthdate()."', PreviousDate='".$user->getPreviousdate()."', InfoFlag=".$user->getInfoflag()." WHERE UserID=". $user->getID();

		mysqli_query($dbcnx, $sql);

	}
	else					// Create new DB row //
	{
		$value = TermosGetCounterValue("UserID");
		TermosSetCounterValue("UserID", ++$value);
		$user->setID($value);
		$user->setCreatedDate( date("Y-m-d") );

		$sql = $user_insert . "VALUES (".$user->getID().",".$user->getAddressID().",'".addslashes($user->getFullname())."','".addslashes($user->getAliasname()). "', '".$user->getEMail()."', ".$user->getUserGroupID().", '".$user->getAccountname()."', '".$user->getPassword()."', '".$user->getFax()."', ".$user->getCountryID().", '".$user->getLastdate()."', ".$user->getCounter().", '".$user->getCreateddate()."', ".$user->getApproved().", ".$user->getAnonymous().", '".$user->getURL()."', '".$user->getAddress()."', '".$user->getJobfunction()."', '".$user->getZip()."', '".$user->getCity()."', '".$user->getTel()."','".$user->getCell()."', '".$user->getInfotext()."', ".$user->getRegionID().", ".$user->getMunicipalityID().", ".$user->getCompanyflag().", '".$user->getLastMessage()."', '".$user->getBirthdate()."', '".$user->getPreviousdate()."', ".$user->getInfoflag().")";

		mysqli_query($dbcnx, $sql);
	}
}

function UserGetCompanyName($addressID){
	 global $dbcnx;
	 $sqlstr = "SELECT CompanyName FROM Address WHERE AddressID = ". $addressID;

	 $result = @mysqli_query($dbcnx, $sqlstr);

	 if(!$result){
		exit(' Error in function UserGetCompanyName(): ' . mysqli_error($dbcnx) );
	}

	//return mysql_result($row, 0); removed 180115 ///
	$row = mysqli_fetch_assoc($result);
	return $row['CompanyName']; 
}

function UserGetGroupTextID($groupID){

	global $dbcnx;
	 $sqlstr = "SELECT PartnerCategoryNameTextID FROM PartnerCategories WHERE PartnerCategoryID = ". $groupID;

	 $result = @mysqli_query($dbcnx, $sqlstr);

	 if(!$result){
		exit(' Error in function UserGetCompanyName(): ' . mysqli_error($dbcnx) );
	}

	//return mysql_result($row, 0); removed 180115 ///
	$row = mysqli_fetch_assoc($result);
	return $row['PartnerCategoryNameTextID'];

}

function UserDelete($uid){

	 global $dbcnx;
	 $sqlstr = "DELETE FROM Users WHERE UserID = " . $uid;
	
	
	 // Maybe some more rows in DB to be deleted here //
	 mysqli_query($dbcnx, $sqlstr);
}

function UserHasPrivilege($uid, $prid){
	global $dbcnx;

	$sqlstr ="SELECT PrivCategoryID, UserID FROM UserPrivCategories WHERE PrivCategoryID=" . $prid . " AND UserID=" . $uid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}

function UserHasPriviledgeForLanguage($uid, $langid){
	global $dbcnx;

	switch ($langid){
		case 1:
			$sqlstr ="SELECT PrivCategoryID, UserID FROM UserPrivCategories WHERE PrivCategoryID=30 AND UserID=" . $uid;
		break;
		case 5:
			$sqlstr ="SELECT PrivCategoryID, UserID FROM UserPrivCategories WHERE PrivCategoryID=32 AND UserID=" . $uid;
		break;
		case 11:
			$sqlstr ="SELECT PrivCategoryID, UserID FROM UserPrivCategories WHERE PrivCategoryID=31 AND UserID=" . $uid;
		break;
	
	}

	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}

function UserGetName($uid){

	if($user = UserGetUserByID($uid)){
		return $user->getFullname();
	}else{
		return "noname";
	}
}

function UserCheckAccountName($accountname){

	if($user = UserGetUserByAccountName($accountname)){
		return 1;
	}else{
		return 0;
	}
}

?>