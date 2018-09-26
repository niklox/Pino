<?php
/*
mysql> desc Parameters;
+----------------+----------+------+-----+---------+-------+
| Field          | Type     | Null | Key | Default | Extra |
+----------------+----------+------+-----+---------+-------+
| ParameterName  | char(80) | YES  |     | NULL    |       |
| ParameterValue | char(80) | YES  |     | NULL    |       |
+----------------+----------+------+-----+---------+-------+
*/

class Parameter {

	var $parametername = "";
	var $parametervalue = 0;
	var $dbrows;

	function getName(){return $this->parametername;}
	function setName($value){$this->parametername = $value;}

	function getValue(){return $this->parametervalue;}
	function setValue($value){$this->parametervalue = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

}

/*
mysql> desc Languages;
+--------------------+----------+------+-----+---------+-------+
| Field              | Type     | Null | Key | Default | Extra |
+--------------------+----------+------+-----+---------+-------+
| LanguageID         | int(11)  | YES  |     | NULL    |       |
| LanguageNameTextID | char(20) | YES  |     | NULL    |       |
+--------------------+----------+------+-----+---------+-------+

*/

class Language {

	var $languageid = 0;
	var $languagenametextid = "";

	var $dbrows;

	function getID(){return $this->languageid;}
	function setID($value){$this->languageid = $value;}

	function getLanguageNameTextID(){return $this->languagenametextid;}
	function setLanguageNameTextID($value){$this->languagenametextid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getName(){
		return MTextGet($this->getLanguageNameTextID());
	}
}

/*
mysql> desc TextCategories;
+------------------------+----------+------+-----+---------+-------+
| Field                  | Type     | Null | Key | Default | Extra |
+------------------------+----------+------+-----+---------+-------+
| TextCategoryID         | int(11)  | YES  |     | NULL    |       |
| TextCategoryNameTextID | char(20) | YES  |     | NULL    |       |
+------------------------+----------+------+-----+---------+-------+
*/

class TextCategory {

	var $textcategoryid = 0;
	var $textcategorynametextid = "";
	
	var $dbrows;

	function getID(){return $this->textcategoryid;}
	function setID($value){$this->textcategoryid = $value;}

	function getTextCategoryNameTextID(){return $this->textcategorynametextid;}
	function setTextCategoryNameTextID($value){$this->textcategorynametextid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
}

/*
mysql> desc Texts;
+----------------+-------------+------+-----+---------+-------+
| Field          | Type        | Null | Key | Default | Extra |
+----------------+-------------+------+-----+---------+-------+
| TextID         | varchar(20) | NO   | MUL |         |       |
| TextCategoryID | int(11)     | YES  |     | NULL    |       |
| LanguageID     | int(11)     | NO   |     | 0       |       |
| TextPosition   | int(11)     | YES  |     | NULL    |       |
| TextContent    | text        | YES  |     | NULL    |       |
+----------------+-------------+------+-----+---------+-------+
*/

class MText {

	var $textid = "";
	var $textcategoryid = "";
	var $languageid = "";
	var $textposition = "";
	var $textcontent = "";
	
	var $dbrows;


	function getID(){
return $this->textid;
}

	function setID($value){
$this->textid = $value;
}

	function getTextCategoryID(){
return $this->textcategoryid;
}

	function setTextCategoryID($value){
$this->textcategoryid = $value;
}

	function getLanguageID(){return $this->languageid;}
	function setLanguageID($value){$this->languageid = $value;}

	function getTextPosition(){return $this->textposition;}
	function setTextPosition($value){$this->textposition = $value;}

	function getTextContent(){return $this->textcontent;}
	function setTextContent($value){$this->textcontent = $value;}
	
	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
}

?>
