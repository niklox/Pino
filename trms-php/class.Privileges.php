<?php
/*
+------------------------+----------+------+-----+---------+-------+
| Field                  | Type     | Null | Key | Default | Extra |
+------------------------+----------+------+-----+---------+-------+
| PrivCategoryID         | int(11)  | YES  |     | NULL    |       |
| PrivCategoryNameTextID | char(20) | YES  |     | NULL    |       |
+------------------------+----------+------+-----+---------+-------+
*/

class Privilege {

	var $privilegeid = 0;
	var $privilegenametextid = "";
	
	var $dbrows;

	function getID(){
return $this->privilegeid;
}

	function setID($value){
$this->privilegeid = $value;
}

	function getPrivilegeNameTextID(){
return $this->privilegenametextid;
}

	function setPrivilegeNameTextID($value){
$this->privilegenametextid = $value;
}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
}

?>






