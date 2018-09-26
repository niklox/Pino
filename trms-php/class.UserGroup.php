<?php
/*
+---------------------------+----------+------+-----+---------+-------+
| Field                     | Type     | Null | Key | Default | Extra |
+---------------------------+----------+------+-----+---------+-------+
| PartnerCategoryID         | int(11)  | YES  |     | NULL    |       |
| PartnerCategoryNameTextID | char(20) | YES  |     | NULL    |       |
+---------------------------+----------+------+-----+---------+-------+
*/

class UserGroup {

	var $usergroupid = 0;
	var $usergroupnametextid = "";
	
	var $dbrows;

	function getID(){
return $this->usergroupid;
}

	function setID($value){
$this->usergroupid = $value;
}

	function getUserGroupNameTextID(){
return $this->usergroupnametextid;
}

	function setUserGroupNameTextID($value){
$this->usergroupnametextid = $value;
}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getName(){
		return MTextGet($this->getUserGroupNameTextID());
	}
}

?>
