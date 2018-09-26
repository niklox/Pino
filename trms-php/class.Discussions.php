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

class Discussion {

	var $discussionid = 0;
	var $referenceid = 0;
	var $parentid = 0;
	var $authorid = 0;
	var $flag = 0;
	var $createddate = "";
	var $referencetype = "";
	var $titletext = "";
	var $bodytext = "";
	var $authorname = "";
	var $ipnumer = "";
	var $authoremail = "";
	
	var $dbrows;

	function getID(){
return $this->discussionid;
}

	function setID($value){
$this->discussionid = $value;
}

	function getReferenceID(){
return $this->referenceid;
}

	function setReferenceID($value){
$this->referenceid = $value;
}

	function getParentID(){return $this->parentid;}
	function setParentID($value){$this->parentid = $value;}
	
	function getAuthorID(){return $this->authorid;}
	function setAuthorID($value){$this->authorid = $value;}

	function getFlag(){return $this->flag;}
	function setFlag($value){$this->flag = $value;}

	function getCreatedDate(){return $this->createddate;}
	function setCreatedDate($value){$this->createddate = $value;}

	function getReference(){return $this->referencetype;}
	function setReference($value){$this->referencetype = $value;}

	function getTitleText(){return $this->titletext;}
	function setTitleText($value){$this->titletext = $value;}

	function getText(){return $this->bodytext;}
	function setText($value){$this->bodytext = $value;}

	function getAuthorName(){return $this->authorname;}
	function setAuthorName($value){$this->authorname = $value;}

	function getIPNumber(){return $this->ipnumber;}
	function setIPNumber($value){$this->ipnumber = $value;}

	function getAuthorEmail(){return $this->authoremail;}
	function setAuthorEmail($value){$this->authoremail = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
}

?>
