<?php 


/*
Skapa eget filnamn inkl ID:

till: En gammal grek.gif blir 
en-gammal-grek-I004539_0.jpeg språkneutral
en-gammal-grek-I004539_5.jpeg engelska
en-gammal-grek-T004539_0.jpeg 
en-gammal-grek-T004539_5.jpeg

Ogiltiga tecken rensas med regex och filnamnet blir en kombination
av ImageHandle och markörer för T och I, ImageID och språkID. 
=================================================================
Lasse och Åke följer älvens gång.png

lasse-och-ake-foljer-alvens-gang_I003439_0.png
lasse-och-ake-foljer-alvens-gang_T003439_0.png

en-gammal-grek-I004539_0.jpeg 
en-gammal-grek-I004539_5.jpeg 

en-gammal-grek_T004539_0.jpeg 
en-gammal-grek_T004539_5.jpeg 

I004539_-1.JPEG
T004539_-1.JPEG

Möjlighet att resiza bilder vid uppladdning + bhålla eller omforma ratio
Möjlighet att skapa icon och välja storlek på denna

Nya funktioner:

CreateImageFilename()
DecodeImageFilename()

mysql> desc Images;
+-----------------+--------------+------+-----+---------+-------+
| Field           | Type         | Null | Key | Default | Extra |
+-----------------+--------------+------+-----+---------+-------+
| ImageID         | int(11)      | YES  |     | NULL    |       |
| ImageSizeX      | int(11)      | YES  |     | NULL    |       |
| ImageSizeY      | int(11)      | YES  |     | NULL    |       |
| ImageIconSizeX  | int(11)      | YES  |     | NULL    |       |
| ImageIconSizeY  | int(11)      | YES  |     | NULL    |       |
| ImageFormat     | varchar(4)   | YES  |     | NULL    |       |
| ImageIconFormat | varchar(4)   | YES  |     | NULL    |       |
| ImageHandle     | varchar(20)  | YES  |     | NULL    |       |
| ImageLanguageID | int(11)      | YES  |     | NULL    |       |
| ImageAltTextID  | varchar(100) | YES  |     | NULL    |       |
+-----------------+--------------+------+-----+---------+-------+
10 rows in set (0.01 sec)
*/

class Image{

	var $imageid = 0;
	var $imagesizex = 0;
	var $imagesizey = 0;
	var $imageiconsizex = 0;
	var $imageiconsizey = 0;
	var $imageformat = ""; 
	var $imageiconformat= "";
	var $imagehandle = "";
	var $imagelanguageid = 0;
	var $imagealttextid = "";

	var $dbrows;

	function getID(){return $this->imageid;}
	function setID($value){$this->imageid = $value;}

	function getSizeX(){return $this->imagesizex;}
	function setSizeX($value){$this->imagesizex = $value;}

	function getSizeY(){return $this->imagesizey;}
	function setSizeY($value){$this->imagesizey = $value;}
	
	function getIconSizeX(){return $this->imageiconsizex;}
	function setIconSizeX($value){$this->imageiconsizex = $value;}

	function getIconSizeY(){return $this->imageiconsizey;}
	function setIconSizeY($value){$this->imageiconsizey = $value;}

	function getFormat(){return $this->imageformat;}
	function setFormat($value){$this->imageformat = $value;}

	function getIconFormat(){return $this->imageiconformat;}
	function setIconFormat($value){$this->imageiconformat = $value;}

	function getHandle(){return $this->imagehandle;}
	function setHandle($value){$this->imagehandle = $value;}

	function getLanguageID(){return $this->imagelanguageid;}
	function setLanguageID($value){$this->imagelanguageid = $value;}

	function getAltTextID(){return $this->imagealttextid;}
	function setAltTextID($value){$this->imagealttextid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getAltText(){
		return MTextGet($this->getAltTextID());
	}
	
}


/*
mysql> desc ImageCategories;
+-------------------------+-------------+------+-----+---------+-------+
| Field                   | Type        | Null | Key | Default | Extra |
+-------------------------+-------------+------+-----+---------+-------+
| ImageCategoryID         | int(11)     | YES  |     | NULL    |       |
| ImageCategoryNameTextID | varchar(20) | YES  |     | NULL    |       |
| ImageCategoryPosition   | int(11)     | YES  |     | NULL    |       |
+-------------------------+-------------+------+-----+---------+-------+
3 rows in set (0.01 sec)

mysql> desc ImageCategoryValues;
+-----------------+---------+------+-----+---------+-------+
| Field           | Type    | Null | Key | Default | Extra |
+-----------------+---------+------+-----+---------+-------+
| ImageID         | int(11) | YES  |     | NULL    |       |
| ImageCategoryID | int(11) | YES  |     | NULL    |       |
+-----------------+---------+------+-----+---------+-------+
2 rows in set (0.01 sec)
*/

class ImageCategory{

	var $imagecategoryid = 0;
	var $imagecategorynametextid = "";
	var $imagecategoryposition = 0;

	var $dbrows;

	function getID(){return $this->imagecategoryid;}
	function setID($value){$this->imagecategoryid = $value;}

	function getNameTextID(){return $this->imagecategorynametextid;}
	function setNameTextID($value){$this->imagecategorynametextid = $value;}

	function getPosition(){return $this->imagecategoryposition;}
	function setPosition($value){$this->imagecategoryposition = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}

	function getName(){
		return MTextGet($this->getNameTextID());
	}

}

?>