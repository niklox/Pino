<?php
/*
mysql> desc Forms;
+------------------+-----------+------+-----+---------+-------+
| Field            | Type      | Null | Key | Default | Extra |
+------------------+-----------+------+-----+---------+-------+
| FormID           | int(11)   | YES  | MUL | 0       |       |
| FormHandle       | char(20)  | YES  | MUL |         |       |
| FormPosition     | int(11)   | YES  | MUL | 0       |       |
| FormNameTextID   | char(20)  | YES  |     | NULL    |       |
| FormAction       | char(100) | YES  |     |         |       |
| FormMailReceiver | char(100) | YES  |     |         |       |
| FormSaveInDBFlag | int(11)   | YES  |     | 0       |       |
+------------------+-----------+------+-----+---------+-------+
*/

class Form {
	
	var	$formid = 0;
	var $handle = "";
	var $typeid = 0;
	var $nametextid = "";
	var $action = "";
	var $recipient = "";
	var $status = 0;

	var $dbrows;

	function getID(){return $this->formid;}
	function setID($value){$this->formid = $value;}

	function getHandle(){return $this->handle;}
	function setHandle($value){$this->handle = $value;}

	function getTypeID(){return $this->typeid;}
	function setTypeID($value){$this->typeid = $value;}

	function getNameTextID(){return $this->nametextid;}
	function setNameTextID($value){$this->nametextid = $value;}

	function getAction(){return $this->action;}
	function setAction($value){$this->action = $value;}

	function getRecipient(){return $this->recipient;}
	function setRecipient($value){$this->recipient = $value;}

	function getStatus(){return $this->status;}
	function setStatus($value){$this->status = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getName(){
		return MTextGet($this->getNameTextID());
	}
	
}

/*
mysql> desc FormItems;
+-----------------------+-----------+------+-----+---------+-------+
| Field                 | Type      | Null | Key | Default | Extra |
+-----------------------+-----------+------+-----+---------+-------+
| FormItemID            | int(11)   | NO   |     | 0       |       |
| FormItemFormID        | int(11)   | NO   |     | 0       |       |
| FormItemPageNo        | int(11)   | NO   |     | 1       |       |
| FormItemPosition      | int(11)   | YES  |     | NULL    |       |
| FormItemTypeID        | int(11)   | NO   |     | 1       |       |
| FormItemTitleTextID   | char(20)  | YES  |     | NULL    |       |
| FormItemBodyTextID    | char(20)  | YES  |     | NULL    |       |
| FormItemCommentTextID | char(20)  | YES  |     | NULL    |       |
| FormItemLink          | char(100) | YES  |     | NULL    |       |
| FormItemImage         | char(100) | YES  |     | NULL    |       |
| FormItemVisibility    | int(11)   | NO   |     | 1       |       |
| FormItemFieldCols     | int(11)   | YES  |     | NULL    |       |
| FormItemFieldRows     | int(11)   | YES  |     | NULL    |       |
+-----------------------+-----------+------+-----+---------+-------+
13 rows in set (0.01 sec)
*/

class FormInput {

	var $formitemid = 0;
	var $formid = 0;
	var $pageno = 0;
	var $position = 0;
	var $typeid = 0;
	var $titletextid = "";
	var $questiontextid = "";
	var $commenttextid = "";
	var $nameattribute = "";
	var $imageurl = "";
	var $visibility = 1;
	var $cols = 0;
	var $rows = 0;

	var $dbrows;

	function getID(){return $this->formitemid;}
	function setID($value){$this->formitemid = $value;}

	function getFormID(){return $this->formid;}
	function setFormID($value){$this->formid = $value;}
	
	function getPageNo(){return $this->pageno;}
	function setPageNo($value){$this->pageno = $value;}
	
	function getPosition(){return $this->position;}
	function setPosition($value){$this->position = $value;}

	function getTypeID(){return $this->typeid;}
	function setTypeID($value){$this->typeid = $value;}

	function getTitleTextID(){return $this->titletextid;}
	function setTitleTextID($value){$this->titletextid = $value;}

	function getQuestionTextID(){return $this->questiontextid;}
	function setQuestionTextID($value){$this->questiontextid = $value;}

	function getCommentTextID(){return $this->commenttextid;}
	function setCommentTextID($value){$this->commenttextid = $value;}

	function getNameAttribute(){return $this->nameattribute;}
	function setNameAttribute($value){$this->nameattribute = $value;}

	function getImage(){return $this->imageurl;}
	function setImage($value){$this->imageurl = $value;}

	function getVisibility(){return $this->visibility;}
	function setVisibility($value){$this->visibility = $value;}

	function getCols(){return $this->cols;}
	function setCols($value){$this->cols = $value;}

	function getRows(){return $this->rows;}
	function setRows($value){$this->rows = $value;}
	
	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getTitle(){
		return MTextGet($this->getTitleTextID());
	}

	function getQuestion(){
		return MTextGet($this->getQuestionTextID());
	}

	function getComment(){
		return MTextGet($this->getCommentTextID());
	}
	
}
/*
mysql> desc FormItemOption;
+--------------------------+--------------+------+-----+---------+-------+
| Field                    | Type         | Null | Key | Default | Extra |
+--------------------------+--------------+------+-----+---------+-------+
| FormItemOptionID         | int(11)      | NO   | MUL | 0       |       |
| FormItemOptionFormItemID | int(11)      | NO   | MUL | 0       |       |
| FormItemOptionName       | varchar(50)  | YES  |     | NULL    |       |
| FormItemOptionValue      | varchar(100) | YES  |     | NULL    |       |
| FormItemOptionText       | varchar(100) | YES  |     | NULL    |       |
| FormItemOptionVisibility | int(11)      | NO   |     | 1       |       |
+--------------------------+--------------+------+-----+---------+-------+
6 rows in set (0.01 sec)
*/

class FormInputOption {

	var $optionid = 0;
	var $formitemid = 0;
	var $optionnametextid = "";
	var $optionvalue = "";
	var $optiontext = "";
	var $visibility = 1;

	var $dbrows;

	function getID(){return $this->optionid;}
	function setID($value){$this->optionid = $value;}

	function getFormItemID(){return $this->formitemid;}
	function setFormItemID($value){$this->formitemid = $value;}

	function getOptionName(){return $this->optionnametextid;}
	function setOptionName($value){$this->optionnametextid = $value;}

	function getOptionValue(){return $this->optionvalue;}
	function setOptionValue($value){$this->optionvalue = $value;}

	function getTextID(){return $this->optiontext;}
	function setTextID($value){$this->optiontext = $value;}

	function getVisibility(){return $this->visibility;}
	function setVisibility($value){$this->visibility = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getLabel(){
		return MTextGet($this->getTextID());
	}
	
}

/*

mysql> desc FormItemTypes;
+------------------------+--------------+------+-----+---------+-------+
| Field                  | Type         | Null | Key | Default | Extra |
+------------------------+--------------+------+-----+---------+-------+
| FormItemTypeID         | int(11)      | YES  | MUL | NULL    |       |
| FormItemTypeNameTextID | varchar(100) | YES  |     | NULL    |       |
+------------------------+--------------+------+-----+---------+-------+
2 rows in set (0.00 sec)
*/


/*
mysql> desc FormAnswer;
+------------------+-------------+------+-----+---------+-------+
| Field            | Type        | Null | Key | Default | Extra |
+------------------+-------------+------+-----+---------+-------+
| FormAnswerID     | int(11)     | NO   | MUL | 0       |       |
| FormAnswerFormID | int(11)     | NO   | MUL | 0       |       |
| FormAnswerUserID | int(11)     | NO   |     | 0       |       |
| FormAnswerTypeID | int(11)     | YES  | MUL | NULL    |       |
| FormAnswerDate   | datetime    | YES  |     | NULL    |       |
| FormAnswerStatus | int(11)     | NO   | MUL | 0       |       |
| FormAnswerIP     | varchar(50) | YES  |     | NULL    |       |
+------------------+-------------+------+-----+---------+-------+
7 rows in set (0.00 sec)
*/

class FormAnswer {

	var $formanswerid = 0;
	var $formanswerformid = 0;
	var $formansweruserid = 0;
	var $formanswertypeid = 0;
	var $formanswerdate = "";
	var $formanswerstatus = 0;
	var $formanserip = "";

	var $dbrows;

	function getID(){return $this->formanswerid;}
	function setID($value){$this->formanswerid = $value;}

	function getFormID(){return $this->formanswerformid;}
	function setFormID($value){$this->formanswerformid = $value;}

	function getUserID(){return $this->formansweruserid;}
	function setUserID($value){$this->formansweruserid = $value;}

	function getTypeID(){return $this->formanswertypeid;}
	function setTypeID($value){$this->formanswertypeid = $value;}

	function getDateTime(){return $this->formanswerdate;}
	function setDateTime($value){$this->formanswerdate = $value;}

	function getStatus(){return $this->formanswerstatus;}
	function setStatus($value){$this->formanswerstatus = $value;}

	function getIP(){return $this->formanserip;}
	function setIP($value){$this->formanserip = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

}

/*
mysql> desc FormItemAnswers;
+--------------------------------+---------+------+-----+---------+-------+
| Field                          | Type    | Null | Key | Default | Extra |
+--------------------------------+---------+------+-----+---------+-------+
| FormItemAnswerFormAnswerID     | int(11) | NO   | MUL | 0       |       |
| FormItemAnswerFormID           | int(11) | NO   | MUL | 0       |       |
| FormItemAnswerFormItemID       | int(11) | NO   | MUL | 0       |       |
| FormItemAnswerFormItemOptionID | int(11) | NO   | MUL | 0       |       |
| FormItemAnswerText             | text    | YES  |     | NULL    |       |
+--------------------------------+---------+------+-----+---------+-------+
5 rows in set (0.00 sec)
*/

class FormInputAnswer {
	
	var $formanswerid = 0;
	var $formid = 0;
	var $forminputid = 0;
	var $forminputoptionid = 0;
	var $formanswertext = "";

	var $dbrows;

	function getFormAnswerID(){return $this->formanswerid;}
	function setFormAnswerID($value){$this->formanswerid = $value;}

	function getFormID(){return $this->formid;}
	function setFormID($value){$this->formid = $value;}

	function getFormInputID(){return $this->forminputid;}
	function setFormInputID($value){$this->forminputid = $value;}

	function getFormInputOptionID(){return $this->forminputoptionid;}
	function setFormInputOptionID($value){$this->forminputoptionid = $value;}

	function getAnswerText(){return $this->formanswertext;}
	function setAnswerText($value){$this->formanswertext = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
}

/*
mysql> desc FormDefaultAnswer;
+--------------+---------+------+-----+---------+-------+
| Field        | Type    | Null | Key | Default | Extra |
+--------------+---------+------+-----+---------+-------+
| FormID       | int(11) | YES  | MUL | NULL    |       |
| FormAnswerID | int(11) | YES  | MUL | NULL    |       |
+--------------+---------+------+-----+---------+-------+
2 rows in set (0.00 sec)

*/


?>