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

function ParameterSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setName($row['ParameterName']);
		$instance->setValue($row['ParameterValue']);
		return $instance;
	}
}

function ParameterGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setName($row['ParameterName']);
		$instance->setValue($row['ParameterValue']);
		return $instance;
	}
}

function ParameterGetAll(){
 global $dbcnx;
 $instance = new Parameter;
 $sqlstr = "SELECT ParameterName, ParameterValue FROM Parameters ORDER BY ParameterName";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function ParameterGetAll(): ' . mysql_error($dbcnx) );
	}

	return $instance;
}

function ParameterGetByName($paramname){
	global $dbcnx;
	$instance = new Language;
	$sqlstr = "SELECT ParameterName, ParameterValue FROM Parameters WHERE ParameterName=" . $paramname;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ParameterGetByName() " . mysql_error($dbcnx) );
	}

	$instance = ParameterSetAllFromRow($instance);

	return $instance;
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

function LanguageSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['LanguageID']);
		$instance->setLanguageNameTextID($row['LanguageNameTextID']);
		return $instance;
	}
}

function LanguageGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['LanguageID']);
		$instance->setLanguageNameTextID($row['LanguageNameTextID']);
		return $instance;
	}
}

function LanguageGetAll(){
 global $dbcnx;
 $instance = new Language;
 $sqlstr = "SELECT LanguageID, LanguageNameTextID from Languages ORDER BY LanguageID";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function LanguageGetAll(): ' . mysql_error($dbcnx) );
	}

	return $instance;
}

function LanguageGetByID($id){
	global $dbcnx;
	$instance = new Language;
	$sqlstr = "SELECT LanguageID, LanguageNameTextID FROM Languages WHERE LanguageID=" . $id;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function LanguageGetByID() " . mysql_error($dbcnx) );
	}

	$instance = LanguageSetAllFromRow($instance);

	return $instance;
}

function LanguageName($languageid){
	
	if($languageid == 0 || $languageid == -1){
		return "Default";
	}else{
		$language = LanguageGetByID($languageid);
		return MTextGet($language->getLanguageNameTextID());
	}
}

function LanguageNew($language){
		
		global $dbcnx;

		$value = TermosGetCounterValue("LanguageID");
		TermosSetCounterValue("LanguageID", ++$value);
		$language->setID($value);
		$sql = "INSERT INTO Languages VALUES(" . $language->getID() . ",'" . $language->getLanguageNameTextID() . "')";

		mysqli_query($dbcnx, $sql);
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

function TextCategorySetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['TextCategoryID']);
		$instance->setTextCategoryNameTextID($row['TextCategoryNameTextID']);
		return $instance;
	}
}

function TextCategoryGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['TextCategoryID']);
		$instance->setTextCategoryNameTextID($row['TextCategoryNameTextID']);
		return $instance;
	}
}

function TextCategoryGetAll(){
 global $dbcnx;
 $instance = new TextCategory;
 $sqlstr = "SELECT TextCategoryID, TextCategoryNameTextID from TextCategories";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function TextCategoryGetAll(): ' . mysql_error($dbcnx) );
	}

	return $instance;
}

function TextCategoryGetByID($id){
	global $dbcnx;
	$instance = new TextCategory;
	$sqlstr = "SELECT TextCategoryID, TextCategoryNameTextID from TextCategories WHERE TextCategoryID=" . $id;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function TextCategoryGetByID() " . mysql_error($dbcnx) );
	}

	$instance = TextCategorySetAllFromRow($instance);

	return $instance;
}

function TextCategoryGetByNameTextID($text_id){

	global $dbcnx;
	$instance = new TextCategory;
	$sqlstr = "SELECT TextCategoryID, TextCategoryNameTextID from TextCategories WHERE TextCategoryNameTextID='" . $text_id . "'";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function TextCategoryGetByNameTextID() " . mysql_error($dbcnx) );
	}

	$instance = TextCategorySetAllFromRow($instance);

	return $instance;
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
$mtext_select = "SELECT Texts.TextID, Texts.TextCategoryID, Texts.LanguageID, Texts.TextPosition, Texts.TextContent FROM Texts";
$mtext_insert = "INSERT INTO Texts (TextID, TextCategoryID, LanguageID, TextPosition, TextContent) ";

// Map the data into object
function MTextSetAllFromRow($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['TextID']);
		$instance->setTextCategoryID($row['TextCategoryID']);
		$instance->setLanguageID($row['LanguageID']);
		$instance->setTextPosition($row['TextPosition']);
		$instance->setTextContent($row['TextContent']);
		
		return $instance;
	}
}

// Go to the next row
function MTextGetNext($instance){

	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['TextID']);
		$instance->setTextCategoryID($row['TextCategoryID']);
		$instance->setLanguageID($row['LanguageID']);
		$instance->setTextPosition($row['TextPosition']);
		$instance->setTextContent($row['TextContent']);

		return $instance;
	}
}

function MTextGetMText($textid){
 global $dbcnx, $mtext_select;
 $instance = new MText;
 $sqlstr = $mtext_select . " WHERE TextID = \"" . $textid . "\"";
 
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$numrows = mysqli_num_rows($row);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function MTextGetMText(); No MText width ID: ". $textid. "<br/>" . $sqlstr . "<br/>". mysql_error($dbcnx) );
	}
	
	//$instance = MTextSetAllFromRow($instance);

	
	return $instance;
}

function MTextGetMTextForLanguage($textid, $languageid){
 global $dbcnx, $mtext_select;
 $instance = new MText;
 $sqlstr = $mtext_select . " WHERE TextID = \"" . $textid . "\" AND LanguageID =" . $languageid;
 
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function MTextGetMText2(); No MText width ID: ". $textid. "<br/>" . $sqlstr . "<br/>". mysql_error($dbcnx) );
	}
	
	
	$instance = MTextSetAllFromRow($instance);
	return $instance;
}


function MTextGetMTextByLanguage($textid, $languageid){
 global $dbcnx, $mtext_select;
 $instance = new MText;
 $sqlstr = $mtext_select . " WHERE TextID = \"" . $textid . "\" AND LanguageID = " . $languageid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function MTextGetMTextByLanguage(); No MText width ID: ". $textid. "<br/> " . mysql_error($dbcnx) );
	}

	$instance = MTextSetAllFromRow($instance);

	return $instance;
}

function MTextGet($textID){
	global $dbcnx;
	$sql = "SELECT TextContent FROM Texts WHERE TextID = '" . $textID . "' AND LanguageID = ". TermosGetCurrentLanguage();
	$result = @mysqli_query($dbcnx, $sql);
	
	if(mysqli_num_rows($result) == 0){
		$sql = "SELECT TextContent FROM Texts WHERE TextID = '" . $textID . "' AND LanguageID = ". TermosGetDefaultLanguage();
		$result = @mysqli_query($dbcnx, $sql);
	}

	if(mysqli_num_rows($result) == 0){
		$returnstr = "NOID " . $textID;
		return $returnstr;
	}
	else {
		
		// $returnstr = @mysql_result($result, 0);
		// return stripslashes($returnstr); replaced 180115 ///
		
		$returnstr = mysqli_fetch_assoc($result);
		return stripslashes($returnstr['TextContent']);
	}

	//if( @mysql_result($result, 0) != "no text" && @mysql_result($result, 0) != "." )){ //
}


function MTextNewInCategory($textcat_textID, $textcontent){
	
	$tc = new TextCategory;
	$mtext = new MText;
	
	// Lets first create a new TextID
	if(!$tc = TextCategoryGetByNameTextID($textcat_textID)){
		exit("No TextCategory with textcat_textID " . $textcatID);
	}

	$idcounter = TermosGetCounterValue($tc->getTextCategoryNameTextID());
	TermosSetCounterValue($tc->getTextCategoryNameTextID(), ++$idcounter);
	$textid = sprintf("%s%05d", $tc->getTextCategoryNameTextID(), $idcounter);

	// Set all data and insert it.

	$mtext->setID($textid);
	$mtext->setTextCategoryID($tc->getID());
	$mtext->setLanguageID(TermosGetCurrentLanguage());
	$mtext->setTextPosition(0);
	$mtext->setTextContent($textcontent);

	MTextInsertLanguage($mtext);

	return $mtext;
}

function MTextInsertLanguage($mtext){
	global $dbcnx;

	$sql = "INSERT INTO Texts VALUES(\"".$mtext->getID()."\", ".$mtext->getTextCategoryID().", ".$mtext->getLanguageID().", ".$mtext->getTextPosition().", \"".addslashes($mtext->getTextContent())."\")";

	mysqli_query($dbcnx, $sql);
}

function MTextUpdateTextContent($mtext){
	global $dbcnx;
	
	$sql = "DELETE FROM Texts WHERE TextID='". $mtext->getID(). "' AND LanguageID=" . $mtext->getLanguageID();
	mysqli_query($dbcnx, $sql);

	MTextInsertLanguage($mtext);
}

function MTextUpdateTextContentCopyAllLanguages($mtext) {
	global $dbcnx;
	$language = new Language;
	$sql = "DELETE FROM Texts WHERE TextID='" . $mtext->getID() . "'";

	mysqli_query($dbcnx, $sql);

	$language = LanguageGetAll();

	while( $language = LanguageGetNext($language) )
	{
		$mtext->setLanguageID($language->getID());
		MTextInsertLanguage($mtext);
	}
}

function MTextDeleteLanguage($mtextid, $languageid){
	global $dbcnx;
	
	$sql = "DELETE FROM Texts WHERE TextID='". $mtextid ."' AND LanguageID=" . $languageid;
	mysqli_query($dbcnx, $sql);
}

function MTextDelete($mtextid){
	global $dbcnx;
	
	$sql = "DELETE FROM Texts WHERE TextID='". $mtextid ."'";
	mysqli_query($dbcnx, $sql);
}

function MTextDeleteAllTextsForLanguage($languageid){
	global $dbcnx;
	
	$sql = "DELETE FROM Texts WHERE LanguageID='". $languageid ."'";
	mysqli_query($dbcnx, $sql);
}

function MTextGetAllInCategory($textcatID){
	global $dbcnx, $mtext_select;
	$mtext = new MText;
	$sql = $mtext_select . " WHERE TextCategoryID=" . $textcatID . " AND LanguageID=" . TermosGetCurrentLanguage() . " ORDER BY TextID"; 
	

	$row = @mysqli_query($dbcnx, $sql);
	$mtext->setDBrows($row);

	if(!$mtext->getDBrows()){
		exit(' Error in function MTextGetAllInCategory(): ' . mysql_error($dbcnx) );
	}

	return $mtext;
}

function MTextChangeTextCategory($mtextid, $textcatid){
	global $dbcnx;
	
	$sql = "UPDATE Texts SET TextCategoryID = " . $textcatid . " WHERE TextID='". $mtextid ."'";
	mysqli_query($dbcnx, $sql);
}

function MTextGetCategoryID($textid,$languageid){
	global $dbcnx;

	$sql = "SELECT TextCategoryID FROM Texts WHERE TextID ='".$textid."' AND LanguageID =" .$languageid;

	$result = @mysqli_query($dbcnx, $sql);
	if(mysqli_num_rows($result) == 0)
		return -1;
	else{
		//return mysql_result($row,0,0);
		$row = mysqli_fetch_assoc($result);
		return $row['TextCategoryID'];
	}
}

?>
