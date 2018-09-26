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
*/


$address_select = "SELECT Address.AddressID, Address.RegionCode, Address.CompanyName, Address.URL, Address.EMail, Address.Address1, Address.Address2, Address.Zip, Address.City, Address.CountryID, Address.Tel, Address.Fax, Address.Contact, Address.ExternalID, Address.AdditionalInfo, Address.Flag FROM Address ";

$address_insert = "INSERT INTO Address (AddressID, RegionCode, CompanyName, URL, EMail, Address1, Address2, Zip, City, CountryID, Tel, Fax, Contact, ExternalID, AdditionalInfo, Flag) ";

$addressattribute_select = "SELECT AddressAttributes.AddressAttributeID, AddressAttributes.AddressAttributeHandle, AddressAttributes.AddressAttributeNameTextID, AddressAttributes.AddressAttributePosition FROM AddressAttributes ";

// Map the data into object
function AddressSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['AddressID']);
		$instance->setRegionCode($row['RegionCode']);
		$instance->setCompanyName($row['CompanyName']);
		$instance->setURL($row['URL']);
		$instance->setEMail($row['EMail']);
		$instance->setAddress1($row['Address1']);
		$instance->setAddress2($row['Address2']);
		$instance->setZip($row['Zip']);
		$instance->setCity($row['City']);
		$instance->setCountryID($row['CountryID']);
		$instance->setTel($row['Tel']);
		$instance->setFax($row['Fax']);
		$instance->setContact($row['Contact']);
		$instance->setExternalID($row['ExternalID']);
		$instance->setAdditionalInfo($row['AdditionalInfo']);
		$instance->setFlag($row['Flag']);
		
		return $instance;
	}
}

// Go to the next row
function AddressGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
	
		$instance->setID($row['AddressID']);
		$instance->setRegionCode($row['RegionCode']);
		$instance->setCompanyName($row['CompanyName']);
		$instance->setURL($row['URL']);
		$instance->setEMail($row['EMail']);
		$instance->setAddress1($row['Address1']);
		$instance->setAddress2($row['Address2']);
		$instance->setZip($row['Zip']);
		$instance->setCity($row['City']);
		$instance->setCountryID($row['CountryID']);
		$instance->setTel($row['Tel']);
		$instance->setFax($row['Fax']);
		$instance->setContact($row['Contact']);
		$instance->setExternalID($row['ExternalID']);
		$instance->setAdditionalInfo($row['AdditionalInfo']);
		$instance->setFlag($row['Flag']);

		return $instance;
	}
}

function AddressGetByID($id){
 global $dbcnx, $address_select;
 $instance = new Address;
 $sqlstr = $address_select . " WHERE AddressID = " . $id;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function AddressGetAddressByID(); No Address width ID: ". $id. "<br/> " . mysqli_error($dbcnx) );
	}

	
	$instance = AddressSetAllFromRow($instance);

	return $instance;
}

function AddressGetByCompanyName($companyName){
 global $dbcnx, $address_select;
 $instance = new Address;
 $sqlstr = $address_select . " WHERE CompanyName = '" . $companyName ."' ";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function AddressGetByCompanyName(); ' . mysqli_error($dbcnx) );
	}

	$instance = AddressSetAllFromRow($instance);

	return $instance;
}

function AddressGetAll(){
 global $dbcnx, $address_select;
 $instance = new Address;
 $sqlstr = $address_select;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function AddressGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}


function AddressSave($address){
	 global $dbcnx, $address_insert;
	
	if($address->getID() > 0)	// Update DB row //
	{
		$sql = "UPDATE Address SET RegionCode='".$address->getRegionCode()."', CompanyName='".addslashes($address->getCompanyName())."', URL='".$address->getURL()."', EMail='".$address->getEMail()."', Address1='".$address->getAddress1()."', Address2='".$address->getAddress2()."', Zip='".$address->getZip()."', City='".$address->getCity()."', CountryID=".$address->getCountryID().", Tel='".$address->getTel()."', Fax='".$address->getFax()."', Contact='".$address->getContact()."', ExternalID='".$address->getExternalID()."', AdditionalInfo='".$address->getAdditionalInfo()."', Flag=".$address->getFlag()." WHERE AddressID=". $address->getID();

		mysqli_query($dbcnx, $sql);

	}
	else					// Create new DB row //
	{
		$value = TermosGetCounterValue("AddressID");
		TermosSetCounterValue("AddressID", ++$value);
		$address->setID($value);
		
		$sql = $address_insert . "VALUES (".$address->getID().",'".$address->getRegionCode()."','".addslashes($address->getCompanyName())."','".$address->getURL(). "', '".$address->getEMail()."', '".$address->getAddress1()."', '".$address->getAddress2()."', '".$address->getZip()."', '".$address->getCity()."', ".$address->getCountryID().", '".$address->getTel()."', '".$address->getFax()."', '".$address->getContact()."', '".$address->getExternalID()."', '".$address->getAdditionalInfo()."', ".$address->getFlag().")";

		mysqli_query($dbcnx, $sql);
	}
}

function AddressDelete($oid){

	 global $dbcnx;
	 $sqlstr = "DELETE FROM Address WHERE AddressID = " . $oid;

	 mysqli_query($dbcnx, $sqlstr);

	 $sqlstr = "DELETE FROM AddressAttributeValues WHERE AddressID = " . $oid;
	 mysqli_query($dbcnx, $sqlstr);

	 // Maybe some more rows in DB to be deleted here //
}


/***********************************************************************/

function AddressAttributeGetAll(){
 global $dbcnx, $addressattribute_select;
 $instance = new AddressAttribute;
 $sqlstr = $addressattribute_select;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function AddressAttributeGetAll(): ' . mysqli_error($dbcnx) );
	}

	return $instance;
}

function AddressAttributeGetByID($id){
 global $dbcnx, $addressattribute_select;
 $instance = new Address;
 $sqlstr = $addressattribute_select . " WHERE AddressAttributeID = " . $id;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function AddressAttributeGetByID(); No Attribute width ID: ". $id. "<br/> " . mysqli_error($dbcnx) );
	}

	$instance = AddressAttributeSetAllFromRow($instance);

	return $instance;
}

// Map the data into object
function AddressAttributeSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['AddressAttributeID']);
		$instance->setAttributeHandle($row['AddressAttributeHandle']);
		$instance->setAttributeNameTextID($row['AddressAttributeNameTextID']);
		$instance->setAttributePosition($row['AddressAttributePosition']);
		
		return $instance;
	}
}

function AddressAttributeGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['AddressAttributeID']);
		$instance->setAttributeHandle($row['AddressAttributeHandle']);
		$instance->setAttributeNameTextID($row['AddressAttributeNameTextID']);
		$instance->setAttributePosition($row['AddressAttributePosition']);
		
		return $instance;
	}
}

function AddressHasAttribute($oid, $aid){
	global $dbcnx;

	$sqlstr ="SELECT AddressID, AttributeID FROM AddressAttributeValues WHERE AddressID = " . $oid . " AND AttributeID = " . $aid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}


?>