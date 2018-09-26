<?php
/*
Nodes used to be Pages and we still have the DBtable like this

mysql> desc Page;
+-------------------------+----------+------+-----+---------------------+-------+
| Field                   | Type     | Null | Key | Default             | Extra |
+-------------------------+----------+------+-----+---------------------+-------+
| PageID                  | int(11)  | YES  |     | NULL                |       |
| PageNameTextID          | char(20) | YES  |     | NULL                |       |
| PagePosition            | int(11)  | YES  |     | NULL                |       |
| PageShowDefaultLanguage | int(11)  | YES  |     | NULL                |       |
| PageTypeID              | int(11)  | YES  |     | 0                   |       |
| PageCreatedDate         | datetime | YES  |     | 1970-01-01 00:00:00 |       |
| PageArchiveDate         | datetime | YES  |     | 2020-01-01 00:00:00 |       |
| PagePermalinkTextID     | char(20) | YES  |     | NULL                |       |
+-------------------------+----------+------+-----+---------------------+-------+

*/

class Node {

	var $nodeid = 0;
	var $nodenametextid = "";
	var $nodeposition = 0;
	var $nodeflag= 0;
	var $nodetypeid = 0;
	var $createddate = "";
	var $archivedate = "";
	var $nodepermalinktextid = "";
	
	var $dbrows;


	function getID(){
return $this->nodeid;
}

	function setID($value){
$this->nodeid = $value;
}

	function getNodeNameTextID(){
return $this->nodenametextid;
}

	function setNodeNameTextID($value){
$this->nodenametextid = $value;
}

	function getPosition(){return $this->nodeposition;}
	function setPosition($value){$this->nodeposition = $value;}

	function getFlag(){return $this->nodeflag;}
	function setFlag($value){$this->nodeflag = $value;}

	function getNodeTypeID(){return $this->nodetypeid;}
	function setNodeTypeID($value){$this->nodetypeid = $value;}
	
	function getCreatedDate(){return $this->createddate;}
	function setCreatedDate($value){$this->createddate = $value;}

	function getArchiveDate(){return $this->archivedate;}
	function setArchiveDate($value){$this->archivedate = $value;}

	function getPermalinkTextID(){return $this->nodepermalinktextid;}
	function setPermalinkTextID($value){$this->nodepermalinktextid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getName(){
		return MTextGet($this->getNodeNameTextID());
	}

	function getPermalink(){
		return MTextGet($this->getPermalinkTextID());
	}

}
/*
+--------------------+----------+------+-----+---------+-------+
| Field              | Type     | Null | Key | Default | Extra |
+--------------------+----------+------+-----+---------+-------+
| PageTypeID         | int(11)  | YES  |     | NULL    |       |
| PageTypeNameTextID | char(20) | YES  |     | NULL    |       |
+--------------------+----------+------+-----+---------+-------+
*/

class NodeType {

	var $nodetypeid = 0;
	var $nodetypenametextid = "";

	var $dbrows;

	function getID(){return $this->nodetypeid;}
	function setID($value){$this->nodetypeid = $value;}

	function getNodeTypeNameTextID(){return $this->nodetypenametextid;}
	function setNodeTypeNameTextID($value){$this->nodetypenametextid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

}



?>
