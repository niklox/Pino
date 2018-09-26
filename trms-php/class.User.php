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

mysql> desc UserImages;
+-------------+----------+------+-----+---------+-------+
| Field       | Type     | Null | Key | Default | Extra |
+-------------+----------+------+-----+---------+-------+
| UserID      | int(11)  | YES  |     | NULL    |       |
| ImageHandle | char(20) | YES  |     | NULL    |       |
| Position    | int(11)  | YES  |     | 1       |       |
+-------------+----------+------+-----+---------+-------+
*/

class User {

	var $id = 0, $addressid = 0;
	var $fullname = "void";
	var $aliasname = "void";
	var $email = "";
	var $usergoupid = 0;
	var $accountname = "";
	var $password = "";
	var $fax = "";
	var	$countryid = 0;
	var $lastdate = "";
	var $counter = 0;
	var $createddate = "";
	var $approved = 0;
	var $anonymous = 0;
	var $url = "";
	var $address = "";
	var $jobfunction = "";
	var $zip = "";
	var $city = "";
	var $tel = "";
	var $cell = "";
	var $infotext = "";
	var $regionid = 0;
	var $municipalityid = 0;
	var $companyflag = 0;
	var $lastmessage = "";
	var $birthdate = "";
	var $previousdate = "";
	var $infoflag = 0;

	var $dbrows;


	function getID(){
return $this->id;
}

	function setID($value){
$this->id = $value;
}

	function getAddressID(){return $this->addressid;}
	function setAddressID($value){$this->addressid = $value;}


	function getFullname(){
return $this->fullname;
}

	function setFullname($value){
$this->fullname = $value;
}

	//function getAliasname(){return stripslashes($this->aliasname);}
	//function setAliasname($value){$this->aliasname = addslashes($value);}

	function getAliasname(){return $this->aliasname;}
	function setAliasname($value){$this->aliasname = $value;}

	function getEMail(){return $this->email;}
	function setEMail($value){$this->email = $value;}
	
	function getUserGroupID(){return $this->usergoupid;}
	function setUserGroupID($value){$this->usergoupid = $value;}

	function getAccountname(){return $this->accountname;}
	function setAccountname($value){$this->accountname = $value;}

	function getPassword(){return $this->password;}
	function setPassword($value){$this->password = $value;}

	function getFax(){return $this->fax;}
	function setFax($value){$this->fax = $value;}

	function getCountryID(){return $this->countryid;}
	function setCountryID($value){$this->countryid = $value;}

	function getLastdate(){return $this->lastdate;}
	function setLastdate($value){$this->lastdate = $value;}

	function getCounter(){return $this->counter;}
	function setCounter($value){$this->counter = $value;}

	function getCreateddate(){return $this->createddate;}
	function setCreateddate($value){$this->createddate = $value;}

	function getApproved(){return $this->approved;}
	function setApproved($value){$this->approved = $value;}

	function getAnonymous(){return $this->anonymous;}
	function setAnonymous($value){$this->anonymous = $value;}

	function getURL(){return $this->url;}
	function setURL($value){$this->url = $value;}

	function getAddress(){return $this->address;}
	function setAddress($value){$this->address = $value;}

	function getJobfunction(){return $this->jobfunction;}
	function setJobfunction($value){$this->jobfunction = $value;}

	function getZip(){return $this->zip;}
	function setZip($value){$this->zip = $value;}

	function getCity(){return $this->city;}
	function setCity($value){$this->city = $value;}

	function getTel(){return $this->tel;}
	function setTel($value){$this->tel = $value;}

	function getCell(){return $this->cell;}
	function setCell($value){$this->cell = $value;}

	function getInfotext(){return $this->infotext;}
	function setInfotext($value){$this->infotext = $value;}

	function getRegionID(){return $this->regionid;}
	function setRegionID($value){$this->regionid = $value;}

	function getMunicipalityID(){return $this->municipalityid;}
	function setMunicipalityID($value){$this->municipalityid = $value;}

	function getCompanyflag(){return $this->companyflag;}
	function setCompanyflag($value){$this->companyflag = $value;}

	function getLastmessage(){return $this->lastmessage;}
	function setLastmessage($value){$this->lastmessage = $value;}

	function getPreviousdate(){return $this->previousdate;}
	function setPreviousdate($value){$this->previousdate = $value;}

	function getBirthdate(){return $this->birthdate;}
	function setBirthdate($value){$this->birthdate = $value;}

	function getInfoflag(){return $this->infoflag;}
	function setInfoflag($value){$this->infoflag = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

}

?>






