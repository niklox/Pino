<?php
/*
mysql> desc Forms;
+------------------+-----------+------+-----+---------+-------+
| Field            | Type      | Null | Key | Default | Extra |
+------------------+-----------+------+-----+---------+-------+
| FormID           | int(11)   | YES  | MUL | 0       |       |
| FormHandle       | char(20)  | YES  | MUL |         |       |
| FormPosition     | int(11)   | YES  | MUL | 0       |       | Position deprecated Now used for TypeID
| FormNameTextID   | char(20)  | YES  |     | NULL    |       |
| FormAction       | char(100) | YES  |     |         |       |
| FormMailReceiver | char(100) | YES  |     |         |       |
| FormSaveInDBFlag | int(11)   | YES  |     | 0       |       |
+------------------+-----------+------+-----+---------+-------+
*/

define("FORM_SELECT", "SELECT Forms.FormID, Forms.FormHandle, Forms.FormPosition, Forms.FormNameTextID, Forms.FormAction, Forms.FormMailReceiver, Forms.FormSaveInDBFlag FROM Forms ");
define("FORM_INSERT", "INSERT INTO Forms (FormID, FormHandle, FormPosition, FormNameTextID, FormAction, FormMailReceiver, FormSaveInDBFlag) ");



function FormSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormID']);
		$instance->setHandle($row['FormHandle']);
		$instance->setTypeID($row['FormPosition']);
		$instance->setNameTextID($row['FormNameTextID']);
		$instance->setAction($row['FormAction']);
		$instance->setRecipient($row['FormMailReceiver']);
		$instance->setStatus($row['FormSaveInDBFlag']);
		return $instance;
	}
}

function FormGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormID']);
		$instance->setHandle($row['FormHandle']);
		$instance->setTypeID($row['FormPosition']);
		$instance->setNameTextID($row['FormNameTextID']);
		$instance->setAction($row['FormAction']);
		$instance->setRecipient($row['FormMailReceiver']);
		$instance->setStatus($row['FormSaveInDBFlag']);
		return $instance;
	}
}

function FormGetForm($formid){
	global $dbcnx;
	
	$instance = new Form;
	$sqlstr = FORM_SELECT . " WHERE FormID = " . $formid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormGetForm();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormSetAllFromRow($instance);
	return $instance;
}

function FormGetActiveGlobalVote(){
	global $dbcnx;
	
	$instance = new Form;
	$sqlstr = FORM_SELECT . " WHERE FormPosition = 2 AND FormSaveInDBFlag = 1";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormGetActiveGlobalVote();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormSetAllFromRow($instance);
	return $instance;
}

function FormGetFormByHandle($handle){

	global $dbcnx;
	
	$instance = new Form;
	$sqlstr = FORM_SELECT . " WHERE FormHandle = '" . $handle . "'";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormGetFormByHandle();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormSetAllFromRow($instance);
	return $instance;

}

function FormGetAll(){

	global $dbcnx;
	
	$instance = new Form;
	$sqlstr = FORM_SELECT;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormGetAll();<br/> " . mysqli_error($dbcnx) );
	}
	//$instance = FormSetAllFromRow($instance);
	return $instance;

}

function FormGetAllByTypeID($typeid){

	global $dbcnx;
	
	$instance = new Form;
	$sqlstr = FORM_SELECT . " WHERE FormPosition = " . $typeid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormGetAll();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormSetAllFromRow($instance);
	return $instance;

}

function FormGetDefaultAnswerID($formid){
	global $dbcnx;
	
	$sqlstr = "SELECT FormAnswerID FROM FormDefaultAnswer WHERE FormID = " . $formid;
	$result = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		// return mysql_result($row,0,0);
		$row = mysqli_fetch_assoc($result);
		return $row['FormAnswerID'];
	}
}


function FormHasFormAnswers($formid){
	global $dbcnx;
	
	$sqlstr = "SELECT FormAnswerID FROM FormAnswer WHERE FormAnswerFormID = " . $formid;
	$result = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		//return mysql_result($row,0,0);
		$row = mysqli_fetch_assoc($result);
		return $row['FormAnswerID'];
	}
}

function FormSetDefaultAnswer($formid, $formanswerid){
	global $dbcnx;

	$sqlstr = "DELETE FROM FormDefaultAnswer WHERE FormID =" . $formid;
	mysqli_query($dbcnx, $sqlstr);
	$sqlstr = "INSERT INTO FormDefaultAnswer VALUES(". $formid .", ". $formanswerid .")";
	mysqli_query($dbcnx, $sqlstr);

}

function FormSave($instance){
	global $dbcnx;
	
	if($instance->getID() > 0){
		
		$sql = "UPDATE Forms SET FormID=" . $instance->getID() .", FormHandle = '". $instance->getHandle() . "', FormPosition = '" . $instance->getTypeID() . "', FormNameTextID='" . $instance->getNameTextID() . "', FormAction='" . $instance->getAction() . "', FormMailReceiver='" . $instance->getRecipient() . "', FormSaveInDBFlag=" . $instance->getStatus() . " WHERE FormID = ". $instance->getID();
		
	}else{
		$value = TermosGetCounterValue("FormID");
		TermosSetCounterValue("FormID", ++$value);
		$instance->setID($value);
		$instance->setHandle("FORM_" . $value);

		$sql = FORM_INSERT . "VALUES(".$instance->getID().",'" . $instance->getHandle(). "',". $instance->getTypeID(). ",'". $instance->getNameTextID() ."','". $instance->getAction() ."','". $instance->getRecipient() ."',".  $instance->getStatus() . ")";
	 }
	  
	 mysqli_query($dbcnx, $sql);;
}

function FormDelete($formid) {
	global $dbcnx;
	
	if($formid > 0){

		$form = FormGetForm($formid);
		
		// Delete the FormItemAnswers
		$sqlstr = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormID =" . $formid;
		mysqli_query($dbcnx, $sqlstr);

		// Delete the FormAnswers
		$sqlstr = "DELETE FROM FormAnswer WHERE FormAnswerFormID =" . $formid;
		mysqli_query($dbcnx, $sqlstr);

		// Delete the FormDefaultAnswer
		$sqlstr = "DELETE FROM FormDefaultAnswer WHERE FormID =" . $formid;
		mysqli_query($dbcnx, $sqlstr);
		
		// Get and delete the FormItems and the FormItemOptions
		$forminput = FormInputGetAllForForm($formid);

		if(isset($forminput)){
			while($forminput = FormInputGetNext($forminput)){
				FormInputDelete($forminput->getID());
			}
		}
		
		MTextDelete($form->getNameTextID());
		$sqlstr = "DELETE FROM Forms WHERE FormID = " . $formid;
		mysqli_query($dbcnx, $sqlstr);
	}
}

function FormAddToContent($cid, $formhandle, $position){
	global $dbcnx;

	FormDeleteFromContent($cid);
	if($formhandle != "0"){
		$sqlstr = "INSERT INTO PageContentForms VALUES(".$cid.", '".$formhandle."', ".$position.")";
		mysqli_query($dbcnx, $sqlstr);
	}
}

function FormDeleteFromContent($cid){
	global $dbcnx;
	$sqlstr = "DELETE FROM PageContentForms WHERE PageContentID = " . $cid;
	mysqli_query($dbcnx, $sqlstr);
}

function FormHasContent($cid){
	global $dbcnx;

	$sql = "SELECT PageContentID, FormHandle, Position FROM PageContentForms WHERE PageContentID=".$cid;

	$result = @mysqli_query($dbcnx, $sql);;
	if(mysqli_num_rows($result) == 0)
		return -1;
	else{
		//return mysql_result($row,0,0); // removed 180115
		$row = mysqli_fetch_assoc($result);
		return $row['FormHandle'];
	}
}

function FormPrint($formhandle){
	
	$form = FormGetFormByHandle($formhandle);
	$forminput = FormInputGetAllForForm($form->getID());

	print	"<form name=\"".$formhandle."\" id=\"".$formhandle."\" method=\"post\">\n";
	print	"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"\"/>\n";
	print	"<input type=\"hidden\" id=\"formhandle\" name=\"formhandle\" value=\"".$formhandle."\"/>\n";
	print	"<input type=\"hidden\" id=\"formid\" name=\"formid\" value=\"".$form->getID()."\"/>\n";

	while( $forminput = FormInputGetNext($forminput) ){
		FormInputPrint($forminput->getID(), "standard", 0, 1 );
		print "<br/>\n";
	}

	FormControl("reset", "standardreset", $formhandle, MTextGet("reset"));
	FormControl("submit", "standardsubmit", $formhandle, MTextGet("send"));
	print	"</form>\n";
}

function FormPrintNominationForm($formhandle,$pageno, $formanswerid){
	
	$form = FormGetFormByHandle($formhandle);
	$forminput = FormInputGetAllForFormAndPage($form->getID(), $pageno);
	$lastpageno = FormGetMaxPageNo($form->getID());

	print	"<form name=\"NOMINATE_".$formhandle."\" id=\"NOMINATE_".$formhandle."\" method=\"post\">\n";
	print	"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"\"/>\n";
	print	"<input type=\"hidden\" id=\"formhandle\" name=\"formhandle\" value=\"".$formhandle."\"/>\n";
	print	"<input type=\"hidden\" id=\"formid\" name=\"formid\" value=\"".$form->getID()."\"/>\n";
	print	"<input type=\"hidden\" id=\"pageno\" name=\"pageno\" value=\"".$pageno."\"/>\n";
	print	"<input type=\"hidden\" id=\"formanswerid\" name=\"formanswerid\" value=\"".$formanswerid."\"/>\n";

	while( $forminput = FormInputGetNext($forminput) ){
		
		if($forminput->getTypeID() == 2)
		FormInputWithAnswer($forminput->getID(), $formanswerid, "highbox", 0, 1 );
		else
		FormInputWithAnswer($forminput->getID(), $formanswerid, "standard", 0, 1 );
		print "<br/>\n";
	}

	FormControl("reset", "standardreset", $formhandle, MTextGet("reset"));
	
	
	if($pageno > 1)
		print "<input type=\"button\" id=\"prev_btn\" value=\"Previous\"/> ";
	if( $pageno < $lastpageno )
		print "<input type=\"button\" id=\"next_btn\" value=\"Next\"/>";

	print "<div class=\"pageinfo\">Page " .$pageno . "/" . $lastpageno . "</div>";

	if($pageno == $lastpageno)
		print "<input type=\"button\" id=\"completeform\" value=\"View and send\"/>";

	print	"</form>\n";
}

function FormPrintOnlyInput($formhandle){
	
	$form = FormGetFormByHandle($formhandle);
	$forminput = FormInputGetAllForForm($form->getID());

	while( $forminput = FormInputGetNext($forminput) ){
		FormInputPrint($forminput->getID(), "standard", 0, 1 );
		print "<br/>\n";
	}
 }

function FormControl($type, $css, $id, $value){
	print "<input type=\"".$type."\" class=\"".$css."\" id=\"".$type."_".$id."\" value=\"".$value."\"/>\n";
}

function FormGetMaxPageNo($formid){
	global $dbcnx;
	$sql = "SELECT MAX(FormItemPageNo) FROM FormItems WHERE FormItemFormID = " . $formid;

	$result = @mysqli_query($dbcnx, $sql);;
	if(mysqli_num_rows($result) == 0)
		return -1;
	else{
		// return mysql_result($row,0,0); // removed
		$row = mysqli_fetch_assoc($result);
		return $row['MAX(FormItemPageNo)'];
		
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

define("FORMINPUT_SELECT", "SELECT FormItems.FormItemID, FormItems.FormItemFormID, FormItems.FormItemPageNo, FormItems.FormItemPosition, FormItems.FormItemTypeID, FormItems.FormItemTitleTextID, FormItems.FormItemBodyTextID, FormItems.FormItemCommentTextID, FormItems.FormItemLink, FormItems.FormItemImage, FormItems.FormItemVisibility, FormItems.FormItemFieldCols, FormItems.FormItemFieldRows FROM FormItems ");

define("FORMINPUT_INSERT", "INSERT INTO FormItems (FormItemID, FormItemFormID, FormItemPageNo, FormItemPosition, FormItemTypeID, FormItemTitleTextID, FormItemBodyTextID, FormItemCommentTextID, FormItemLink, FormItemImage, FormItemVisibility, FormItemFieldCols, FormItemFieldRows) ");

function FormInputSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormItemID']);
		$instance->setFormID($row['FormItemFormID']);
		$instance->setPageNo($row['FormItemPageNo']);
		$instance->setPosition($row['FormItemPosition']);
		$instance->setTypeID($row['FormItemTypeID']);
		$instance->setTitleTextID($row['FormItemTitleTextID']);
		$instance->setQuestionTextID($row['FormItemBodyTextID']);
		$instance->setCommentTextID($row['FormItemCommentTextID']);
		$instance->setNameAttribute($row['FormItemLink']);
		$instance->setImage($row['FormItemImage']);
		$instance->setVisibility($row['FormItemVisibility']);
		$instance->setCols($row['FormItemFieldCols']);
		$instance->setRows($row['FormItemFieldRows']);
		return $instance;
	}
}

function FormInputGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormItemID']);
		$instance->setFormID($row['FormItemFormID']);
		$instance->setPageNo($row['FormItemPageNo']);
		$instance->setPosition($row['FormItemPosition']);
		$instance->setTypeID($row['FormItemTypeID']);
		$instance->setTitleTextID($row['FormItemTitleTextID']);
		$instance->setQuestionTextID($row['FormItemBodyTextID']);
		$instance->setCommentTextID($row['FormItemCommentTextID']);
		$instance->setNameAttribute($row['FormItemLink']);
		$instance->setImage($row['FormItemImage']);
		$instance->setVisibility($row['FormItemVisibility']);
		$instance->setCols($row['FormItemFieldCols']);
		$instance->setRows($row['FormItemFieldRows']);
		return $instance;
	}
}

function FormInputGet($forminputid){
	global $dbcnx;
	
	$instance = new FormInput;
	$sqlstr = FORMINPUT_SELECT . " WHERE FormItemID = " . $forminputid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputGet();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormInputSetAllFromRow($instance);
	return $instance;
}

function FormInputGetAllForForm($formid){
	global $dbcnx;
	
	$instance = new FormInput;
	$sqlstr = FORMINPUT_SELECT . " WHERE FormItems.FormItemFormID = " . $formid . " ORDER BY FormItemPosition";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputGetAllForForm();<br/> " . mysqli_error($dbcnx) . " SQL " . $sqlstr);
	}
	//$instance = FormInputSetAllFromRow($instance);
	return $instance;
}

function FormInputGetAllForFormOffset($formid, $start, $end){
	global $dbcnx;
	
	$instance = new FormInput;
	$sqlstr = FORMINPUT_SELECT . " WHERE FormItems.FormItemFormID = " . $formid . " AND FormItemPosition >= ". $start ." AND FormItemPosition <= ". $end ." ORDER BY FormItemPosition";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputGetAllForFormOffset();<br/> " . mysqli_error($dbcnx) . " SQL " . $sqlstr);
	}
	//$instance = FormInputSetAllFromRow($instance);
	return $instance;
}

function FormInputGetAllForFormAndPage($formid, $pageno){
	global $dbcnx;
	
	$instance = new FormInput;
	$sqlstr = FORMINPUT_SELECT . " WHERE FormItemFormID =" . $formid . " AND FormItemPageNo=" . $pageno . " ORDER BY FormItemPosition";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputGetAllForFormAndPAge();<br/> " . mysqli_error($dbcnx) . "<br/>" . $sqlstr );
	}
	//$instance = FormInputSetAllFromRow($instance);
	return $instance;
}

function FormInputSave($instance){
	global $dbcnx;
	
	if($instance->getID() > 0){
		
		$sql = "UPDATE FormItems SET FormItemID=" . $instance->getID() .", FormItemFormID = ". $instance->getFormID() . ", FormItemPageNo = " . $instance->getPageNo() . ", FormItemPosition=" . $instance->getPosition() . ", FormItemTypeID=" . $instance->getTypeID() . ", FormItemTitleTextID='" . $instance->getTitleTextID() . "', FormItemBodyTextID='" . $instance->getQuestionTextID() . "', FormItemCommentTextID='" .  $instance->getCommentTextID() . "', FormItemLink='" . $instance->getNameAttribute() . "', FormItemImage='". $instance->getImage() ."', FormItemVisibility=". $instance->getVisibility() .", FormItemFieldCols=" . $instance->getRows() . ", FormItemFieldRows=" . $instance->getCols() . " WHERE FormItemID = ". $instance->getID();
		
	}else{
		$value = TermosGetCounterValue("FormItemID");
		TermosSetCounterValue("FormItemID", ++$value);
		$instance->setID($value);

		$sql = FORMINPUT_INSERT . "VALUES(". $instance->getID() ."," . $instance->getFormID() . ",". $instance->getPageNo(). ",". $instance->getPosition() .",". $instance->getTypeID() .",'". $instance->getTitleTextID() ."','".  $instance->getQuestionTextID() . "', '". $instance->getCommentTextID() ."', '". $instance->getNameAttribute() ."', '". $instance->getImage() ."', ". $instance->getVisibility() .", ". $instance->getRows() .", ". $instance->getCols() .")";
	 }
	  
	 mysqli_query($dbcnx, $sql);;
}

function FormInputGetLabel($forminputid){

	$forminputoption = FormInputOptionGetAll($forminputid);
	$forminputoption = FormInputOptionGetNext($forminputoption);

	return $forminputoption->getLabel();

}

function FormInputPrint($forminputid, $css, $mode, $flag){

	$forminput = FormInputGet($forminputid);
	
	if( $forminput->getTypeID() == 1 || $forminput->getTypeID() == 2 || $forminput->getTypeID() == 9){
		$forminputoption = FormInputOptionGetAll($forminputid);
		$forminputoption = FormInputOptionGetNext($forminputoption);
	}else
		$forminputoption = FormInputOptionGetAll($forminputid);

	switch( $forminput->getTypeID() ){

		case 1:
			print "<label for=\"".$forminputoption->getOptionName()."\" class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/><input class=\"".$css."\" type=\"text\" name=\"". $forminputoption->getOptionName() ."\" id=\"" . $forminputoption->getOptionName() ."\" value=\"\"/>";
		break;
		case 2:
			print "<label for=\"".$forminputoption->getOptionName()."\" class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/><textarea class=\"".$css."\" name=\"". $forminputoption->getOptionName() ."\" id=\"". $forminputoption->getOptionName() ."\"></textarea>";
		break;
		case 3:
			print "<label for=\"".$forminputoption->getOptionName()."\" class=\"".$css."\">" . $forminput->getQuestion() . "</label><br/>";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"checkbox\" name=\"".$forminputoption->getOptionName()."\" id=\"".$forminputoption->getOptionName()."\" value=\"" .$forminputoption->getID()."\"/>" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
				
		break;
		case 4:
			print "<label for=\"radio_".$forminput->getID()."\" class=\"".$css."\">" . $forminput->getQuestion() . "</label><br/>";
		

			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"radio\"  name=\"radio_".$forminput->getID()."\" id=\"radio_".$forminput->getID()."\" value=\"". $forminputoption->getID()."\"/>" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
				
		break;
		case 5:
		break;
		case 6:
			print	"<label for=\"".$forminputoption->getOptionName()."\" class=\"".$css."\">" . $forminput->getTitle() . "</label><br/>\n";
			print	"<select class=\"".$css."\" id=\"".$forminputoption->getOptionName()."\" name=\"".$forminputoption->getOptionName()."\">\n"; 
			print	"<option value=\"0\">". $forminput->getQuestion() . "</option>\n";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print "<option value=\"".$forminputoption->getOptionValue()."\">" . $forminputoption->getLabel() . "</option>\n"; 

			print	"</select>"; 
		break;

		case 9:
			print	"<label for=\"".$forminputoption->getOptionName()."\" class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/>\n";
			
			PrintGenericSelect("SELECT CountryID, CountryNameTextID FROM Countries ORDER BY CountryNameTextID", $css, $forminputoption->getOptionName(),  MTextGet("selectcountry"), 0);
			
		break;
		case 10:
			print	"<div id=\"".$forminput->getQuestionTextID() ."\" class=\"".$css."\">" . $forminput->getQuestion() . "</div>";
		break;
	}
}

function FormInputWithAnswer($forminputid, $formanswerid, $css, $mode, $flag){

	$forminput = FormInputGet($forminputid);
	
	if( $forminput->getTypeID() == 1 || $forminput->getTypeID() == 2 || $forminput->getTypeID() == 9){
		$forminputoption = FormInputOptionGetAll($forminputid);
		$forminputoption = FormInputOptionGetNext($forminputoption);
	}else
		$forminputoption = FormInputOptionGetAll($forminputid);

	
	$answer = FormInputGetMyAnswer($formanswerid, $forminputid, $forminput->getTypeID() );
	

	switch( $forminput->getTypeID() ){

		case 1:
			print "<label for=\"".$forminputoption->getOptionName()."\"  class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/><input class=\"".$css."\" type=\"text\" name=\"". $forminputoption->getOptionName() ."\" id=\"" . $forminputoption->getOptionName() ."\" value=\"". $answer."\"/>";
		break;

		case 2:
			print "<label for=\"".$forminputoption->getOptionName()."\"  class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/><textarea class=\"".$css."\" name=\"". $forminputoption->getOptionName() ."\" id=\"". $forminputoption->getOptionName() ."\">". $answer ."</textarea>";
		break;

		case 3:
			print "<label  for=\"checkbox_".$forminput->getID()."\" class=\"".$css."\">" . $forminput->getQuestion() . "</label><br/>";
			while($forminputoption = FormInputOptionGetNext($forminputoption)){
				print	"<input type=\"checkbox\" name=\"".$forminputoption->getOptionName()."\" id=\"".$forminputoption->getOptionName()."\" value=\"" .$forminputoption->getID()."\"/>" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
			}	
		break;
		
		case 4:
			print "<label for=\"radio_".$forminput->getID()."\" class=\"".$css."\">" . $forminput->getQuestion() . "</label><br/>";

			while($forminputoption = FormInputOptionGetNext($forminputoption)){
				print "<input type=\"radio\"  name=\"radio_".$forminput->getID()."\" id=\"radio_".$forminput->getID()."\" value=\"". $forminputoption->getID()."\" ".($answer==$forminputoption->getOptionName()?"checked":"")." />" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
			}

		break;

		case 5:
		break;

		case 6:
			print	"<label for=\"select_".$forminput->getID()."\" class=\"".$css."\">" . $forminput->getTitle() . "</label><br/>\n";
			print	"<select class=\"".$css."\" id=\"".$forminputoption->getOptionName()."\" name=\"".$forminputoption->getOptionName()."\">\n"; 
			print	"<option value=\"0\">". $forminput->getQuestion() . "</option>\n";
			while($forminputoption = FormInputOptionGetNext($forminputoption)){
				print "<option value=\"".$forminputoption->getOptionValue()."\">" . $forminputoption->getLabel() . "</option>\n"; 
			}
			print	"</select>"; 
		break;

		case 9:
			print	"<label for=\"select_".$forminput->getID()."\" class=\"".$css."\">" . $forminputoption->getLabel() . "</label><br/>\n";
			
			PrintGenericSelect("SELECT CountryID, CountryNameTextID FROM Countries ORDER BY CountryNameTextID", $css, $forminputoption->getOptionName(),  MTextGet("selectcountry"), 0);
			
		break;

		case 10:
			print	"<div id=\"".$forminput->getQuestionTextID() ."\" class=\"".$css."\">" . $forminput->getQuestion() . "</div>";
		break;
	}
}

function FormInputPrintInputOnly($forminputid, $css, $mode, $flag){

	$forminput = FormInputGet($forminputid);
	
	if( $forminput->getTypeID() == 1 || $forminput->getTypeID() == 2 || $forminput->getTypeID() == 9){
		$forminputoption = FormInputOptionGetAll($forminputid);
		$forminputoption = FormInputOptionGetNext($forminputoption);
	}else
		$forminputoption = FormInputOptionGetAll($forminputid);

	switch( $forminput->getTypeID() ){

		case 1:
			print "<input class=\"".$css."\" type=\"text\" name=\"". $forminputoption->getOptionName() ."\" id=\"" . $forminputoption->getOptionName() ."\" value=\"\"/>";
		break;
		case 2:
			print "<textarea class=\"".$css."\" name=\"". $forminputoption->getOptionName() ."\" id=\"". $forminputoption->getOptionName() ."\"/></textarea>";
		break;
		case 3:
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"checkbox\" name=\"".$forminputoption->getOptionName()."\" id=\"".$forminputoption->getOptionName()."\" value=\"" .$forminputoption->getID()."\"/>";
				
		break;
		case 4:
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"radio\"  name=\"radio_".$forminput->getID()."\" id=\"radio_".$forminput->getID()."\" value=\"". $forminputoption->getID()."\"/>";
				
		break;
		case 5:
		break;
		case 6:
			print	"<select class=\"".$css."\" id=\"".$forminputoption->getOptionName()."\" name=\"".$forminputoption->getOptionName()."\">\n"; 
			print	"<option value=\"0\">". $forminput->getQuestion() . "</option>";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print "<option value=\"".$forminputoption->getOptionValue()."\">" . $forminputoption->getLabel() . "</option>\n"; 

			print	"</select>\n"; 
		break;

		case 9:
			PrintGenericSelect("SELECT CountryID, CountryNameTextID FROM Countries ORDER BY CountryNameTextID", "", $forminputoption->getOptionName(), "Select country", 0);
		break;
		case 10:
		break;
	}
}

function FormInputPrintDefaultAnswer($forminputid, $css, $mode, $flag){

	$forminput = FormInputGet($forminputid);

	$defaultanswerid = FormGetDefaultAnswerID($forminput->getFormID());
	
	if( $forminput->getTypeID() == 1 || $forminput->getTypeID() == 2 ){
		$forminputoption = FormInputOptionGetAll($forminputid);
		$forminputoption = FormInputOptionGetNext($forminputoption);
	}else
		$forminputoption = FormInputOptionGetAll($forminputid);

	switch( $forminput->getTypeID() ){

		case 1:
			print $forminputoption->getLabel() . "<br/><input class=\"".$css."\" type=\"text\" name=\"". $forminputoption->getOptionName() ."\" id=\"" . $forminputoption->getOptionName() ."\" value=\"\"/>";
		break;
		case 2:
			print $forminputoption->getLabel() . "<br/><textarea class=\"".$css."\" name=\"". $forminputoption->getOptionName() ."\" id=\"". $forminputoption->getOptionName() ."\"/></textarea>";
		break;
		case 3:
			print $forminput->getQuestion() . "<br/>";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"checkbox\" name=\"".$forminputoption->getOptionName()."\" id=\"".$forminputoption->getOptionName()."\" value=\"" .$forminputoption->getID()."\"/>" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
				
		break;
		case 4:
			print $forminput->getQuestion() . "<br/>";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print	"<input type=\"radio\"  name=\"radio_".$forminput->getID()."\" id=\"radio_".$forminput->getID()."\" value=\"". $forminputoption->getID()."\" ".(FormInputOptionCheck($forminputoption->getID(), $forminputid, $defaultanswerid)==1?" checked":"")."/>" .$forminputoption->getLabel() . "".($flag==1?"<br/>":"")."";
				
		break;
		case 5:
		break;
		case 6:
			print	$forminput->getTitle() . "<br/>\n";
			print	"<select class=\"".$css."\" id=\"".$forminputoption->getOptionName()."\" name=\"".$forminputoption->getOptionName()."\">\n"; 
			print	"<option value=\"0\">". $forminput->getQuestion() . "</option>";
			while($forminputoption = FormInputOptionGetNext($forminputoption))
				print "<option value=\"".$forminputoption->getOptionValue()."\">" . $forminputoption->getLabel() . "</option>\n"; 

			print	"</select>\n"; 
		break;

		case 9:
			print	$forminputoption->getLabel() . "<br/>\n";
			PrintGenericSelect("SELECT CountryID, CountryNameTextID FROM Countries ORDER BY CountryNameTextID", "", $forminput->getNameAttribute(), "Select country", 0);
		break;

		case 10:
		break;
	}
}

function FormInputDelete($forminputid) {
	global $dbcnx;

	$forminput = FormInputGet($forminputid);
	
	MTextDelete( $forminput->getTitleTextID() );
	MTextDelete( $forminput->getQuestionTextID() );
	MTextDelete( $forminput->getCommentTextID() );
	
	$forminputoption = FormInputOptionGetAll($forminputid);
	while($forminputoption = FormInputOptionGetNext($forminputoption)){
		FormInputOptionDelete($forminputoption->getID());
	}

	$sql = "DELETE FROM FormItems WHERE FormItemID = " . $forminputid;
	mysqli_query($dbcnx, $sql);;
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

void FormItemOptionDelete(int formItemOptionID);
void FormItemOptionSave(FormItemOption *formItemOption);
*/

define("FORMINPUT_OPTION_SELECT", "SELECT FormItemOption.FormItemOptionID, FormItemOption.FormItemOptionFormItemID, FormItemOption.FormItemOptionName, FormItemOption.FormItemOptionValue, FormItemOption.FormItemOptionText, FormItemOption.FormItemOptionVisibility FROM FormItemOption ");

define("FORMINPUT_OPTION_INSERT", "INSERT INTO FormItemOption (FormItemOptionID, FormItemOptionFormItemID, FormItemOptionName, FormItemOptionValue, FormItemOptionText, FormItemOptionVisibility) ");

function FormInputOptionSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormItemOptionID']);
		$instance->setFormItemID($row['FormItemOptionFormItemID']);
		$instance->setOptionName($row['FormItemOptionName']);
		$instance->setOptionValue($row['FormItemOptionValue']);
		$instance->setTextID($row['FormItemOptionText']);
		$instance->setVisibility($row['FormItemOptionVisibility']);
		return $instance;
	}
}

function FormInputOptionGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormItemOptionID']);
		$instance->setFormItemID($row['FormItemOptionFormItemID']);
		$instance->setOptionName($row['FormItemOptionName']);
		$instance->setOptionValue($row['FormItemOptionValue']);
		$instance->setTextID($row['FormItemOptionText']);
		$instance->setVisibility($row['FormItemOptionVisibility']);
		return $instance;
	}
}

function FormInputOptionGet($forminputoptionid){
	global $dbcnx;
	
	$instance = new FormInputOption;
	$sqlstr = FORMINPUT_OPTION_SELECT . " WHERE FormItemOption.FormItemOptionID = " . $forminputoptionid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputOptionGet();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormInputOptionSetAllFromRow($instance);
	return $instance;
}

function FormInputOptionGetByName($forminputoptionname){
	global $dbcnx;
	
	$instance = new FormInputOption;
	$sqlstr = FORMINPUT_OPTION_SELECT . " WHERE FormItemOption.FormItemOptionName = '" . $forminputoptionname . "'";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputOptionGetByName();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormInputOptionSetAllFromRow($instance);
	return $instance;
}

function FormInputOptionGetInputTypeID($forminputoptionname){
	global $dbcnx;
	$sqlstr = "SELECT I.FormItemTypeID FROM FormItems AS I, FormItemOption AS O WHERE O.FormItemOptionName = '".$forminputoptionname."' AND O.FormItemOptionFormItemID = I.FormItemID";

	$result = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		// return mysql_result($row,0,0); Removed 180115 ///////
		$row = mysqli_fetch_assoc($result);
		return $row['FormItemTypeID'];
	}
}

function FormInputOptionGetLabel($forminputoptionname){
	
	$forminputoption = FormInputOptionGetByName($forminputoptionname);
	return $forminputoption->getLabel();
}

function FormInputOptionGetAll($forminputid){
	global $dbcnx;
	
	$instance = new FormInputOption;
	$sqlstr = FORMINPUT_OPTION_SELECT . " WHERE FormItemOption.FormItemOptionFormItemID = " . $forminputid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputOptionGetAll();<br/> " . mysqli_error($dbcnx) . "<br/>" . $sqlstr);
	}
	//$instance = FormInputSetAllFromRow($instance);
	return $instance;
}

function FormInputOptionSave($instance){
	global $dbcnx;
	
	if($instance->getID() > 0){
		
		$sql = "UPDATE FormItemOption SET FormItemOptionID=" . $instance->getID() .", FormItemOptionFormItemID = ". $instance->getFormItemID() . ", FormItemOptionName = '" . $instance->getOptionName() . "', FormItemOptionValue='" . $instance->getOptionValue() . "', FormItemOptionText='" . $instance->getTextID() . "', FormItemOptionVisibility=" . $instance->getVisibility() . " WHERE FormItemOptionID = ". $instance->getID();
		
	}else{
		$value = TermosGetCounterValue("FormItemOptionID");
		TermosSetCounterValue("FormItemOptionID", ++$value);
		$instance->setID($value);

		$sql = FORMINPUT_OPTION_INSERT . "VALUES(". $instance->getID() ."," . $instance->getFormItemID() . ",'". $instance->getOptionName() . "','". $instance->getOptionValue() ."','". $instance->getTextID() ."',". $instance->getVisibility() .")";
	 }
	  
	 mysqli_query($dbcnx, $sql);;
}

function FormInputOptionDelete($forminputoptionid) {
	global $dbcnx;

	$forminputoption = FormInputOptionGet($forminputoptionid);

	MTextDelete($forminputoption->getTextID());
	
	$sql = "DELETE FROM FormItemOption WHERE FormItemOptionID =" . $forminputoptionid;
	
	 mysqli_query($dbcnx, $sql);;
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

function FormInputTypeGetName($formitemtypeid){
	global $dbcnx;
	$sqlstr = "SELECT FormItemTypeNameTextID FROM FormItemTypes WHERE FormItemTypeID=" .$formitemtypeid;

	$result = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($result) > 0){
		//return mysql_result($row,0,0);
		$row = mysqli_fetch_assoc($result);
		return $row['FormItemTypeNameTextID'];
	}
}

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
void FormAnswerSave(FormAnswer* formAnswer);
void FormAnswerDelete(int formAnswerID);
 
FormAnswer* FormAnswerSearch(char *startdate, char *enddate, int countryID, int votetype, int formID);



*/

define("FORMANSWER_SELECT", "SELECT FormAnswer.FormAnswerID, FormAnswer.FormAnswerFormID, FormAnswer.FormAnswerUserID, FormAnswer.FormAnswerTypeID, FormAnswer.FormAnswerDate, FormAnswer.FormAnswerStatus, FormAnswer.FormAnswerIP FROM FormAnswer ");

define("FORMANSWER_INSERT", "INSERT INTO FormAnswer (FormAnswerID, FormAnswerFormID, FormAnswerUserID, FormAnswerTypeID, FormAnswerDate, FormAnswerStatus, FormAnswerIP) ");

function FormAnswerSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormAnswerID']);
		$instance->setFormID($row['FormAnswerFormID']);
		$instance->setUserID($row['FormAnswerUserID']);
		$instance->setTypeID($row['FormAnswerTypeID']);
		$instance->setDateTime($row['FormAnswerDate']);
		$instance->setStatus($row['FormAnswerStatus']);
		$instance->setIP($row['FormAnswerIP']);
		return $instance;
	}
}

function FormAnswerGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['FormAnswerID']);
		$instance->setFormID($row['FormAnswerFormID']);
		$instance->setUserID($row['FormAnswerUserID']);
		$instance->setTypeID($row['FormAnswerTypeID']);
		$instance->setDateTime($row['FormAnswerDate']);
		$instance->setStatus($row['FormAnswerStatus']);
		$instance->setIP($row['FormAnswerIP']);
		return $instance;
	}
}

function FormAnswerGet($formanswerid){
	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswerID = " . $formanswerid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerGet();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormAnswerSetAllFromRow($instance);
	return $instance;
}

function FormAnswerGetAllForForm($formid){
	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswer.FormAnswerFormID = " . $formid . " ORDER BY FormAnswer.FormAnswerDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerGetAllForForm();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormAnswerGetAllForFormAndUser($formid, $userid){
	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswer.FormAnswerFormID = " . $formid . "AND FormAnswer.FormAnswerUserID=" .$userid. " ORDER BY FormAnswer.FormAnswerDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerGetAllForFormAndUser();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormAnswerGetActiveForUser($userid){

	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswerUserID = " . $userid . " AND FormAnswerStatus = 0";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerGetActiveForUser();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormAnswerSetAllFromRow($instance);
	return $instance;
}

function FormAnswerGetAllCompleted($formid){
	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswer.FormAnswerFormID = " . $formid . " AND FormAnswer.FormAnswerStatus = 1 ORDER BY FormAnswer.FormAnswerDate DESC";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerGetAllForForm();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormAnswerSearch($startdate, $enddate, $countryid, $votetype, $formid){
	global $dbcnx;
	
	$instance = new FormAnswer;
	$sqlstr = FORMANSWER_SELECT . " WHERE FormAnswer.FormAnswerFormID = " . $formid;

	if(strlen($startdate))
		$sqlstr = $sqlstr .  " AND FormAnswer.FormAnswerDate >= '" . $startdate . "'";
	if(strlen($enddate))
		$sqlstr = $sqlstr .  " AND FormAnswer.FormAnswerDate <= '" . $enddate . "'";
	if($countryid > 0)
		$sqlstr = $sqlstr .  " AND FormAnswer.FormAnswerUserID = " . $countryid;
	if($votetype == 0 || $votetype == 1)
		$sqlstr = $sqlstr .  " AND FormAnswer.FormAnswerTypeID = " . $votetype;

	$sqlstr = $sqlstr . " ORDER BY FormAnswerDate DESC";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormAnswerSearch();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormAnswerSave($instance){
	global $dbcnx;
	
	if($instance->getID() > 0){
		
		$sql = "UPDATE FormAnswer SET FormAnswerID=" . $instance->getID() .", FormAnswerFormID = ". $instance->getFormID() . ", FormAnswerUserID = " . $instance->getUserID() . ", FormAnswerTypeID=" . $instance->getTypeID() . ", FormAnswerDate='" . $instance->getDateTime() . "', FormAnswerStatus=" . $instance->getStatus() . ", FormAnswerIP = '". $instance->getIP() . "' WHERE FormAnswerID = ". $instance->getID();
		
	}else{
		$value = TermosGetCounterValue("FormAnswer");
		TermosSetCounterValue("FormAnswer", ++$value);
		$instance->setID($value);

		$sql = FORMANSWER_INSERT . "VALUES(". $instance->getID() ."," . $instance->getFormID() . ",". $instance->getUserID() . ",". $instance->getTypeID() .",'". $instance->getDateTime() ."',". $instance->getStatus() .", '". $instance->getIP() ."')";
	 }

	mysqli_query($dbcnx, $sql);;

	return $instance->getID();
}

function FormAnswerDelete($formanswerid){
	global $dbcnx;
	
	$sql = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormAnswerID =" . $formanswerid;
	mysqli_query($dbcnx, $sql);;
	$sql = "DELETE FROM FormAnswer WHERE FormAnswerID =" .  $formanswerid;
	mysqli_query($dbcnx, $sql);;
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

define("FORMINPUTANSWER_SELECT", "SELECT FormItemAnswers.FormItemAnswerFormAnswerID, FormItemAnswers.FormItemAnswerFormID, FormItemAnswers.FormItemAnswerFormItemID, FormItemAnswers.FormItemAnswerFormItemOptionID, FormItemAnswers.FormItemAnswerText FROM FormItemAnswers ");

define("FORMINPUTANSWER_INSERT", "INSERT INTO FormItemAnswers (FormItemAnswerFormAnswerID, FormItemAnswerFormID, FormItemAnswerFormItemID, FormItemAnswerFormItemOptionID, FormItemAnswerText) ");

function FormInputAnswerSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setFormAnswerID($row['FormItemAnswerFormAnswerID']);
		$instance->setFormID($row['FormItemAnswerFormID']);
		$instance->setFormInputID($row['FormItemAnswerFormItemID']);
		$instance->setFormInputOptionID($row['FormItemAnswerFormItemOptionID']);
		$instance->setAnswerText($row['FormItemAnswerText']);
		return $instance;
	}
}

function FormInputAnswerGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setFormAnswerID($row['FormItemAnswerFormAnswerID']);
		$instance->setFormID($row['FormItemAnswerFormID']);
		$instance->setFormInputID($row['FormItemAnswerFormItemID']);
		$instance->setFormInputOptionID($row['FormItemAnswerFormItemOptionID']);
		$instance->setAnswerText($row['FormItemAnswerText']);
		return $instance;
	}
}

function FormInputAnswerSave($forminputanswer){
	global $dbcnx;
	
	$sql = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormAnswerID=".$forminputanswer->getFormAnswerID()." AND FormItemAnswerFormItemID =" . $forminputanswer->getFormInputID();
	mysqli_query($dbcnx, $sql);;
	
	$sql = FORMINPUTANSWER_INSERT . " VALUES(".$forminputanswer->getFormAnswerID().", ".$forminputanswer->getFormID().", ".$forminputanswer->getFormInputID().", ".$forminputanswer->getFormInputOptionID().", '".$forminputanswer->getAnswerText(). "')";
	mysqli_query($dbcnx, $sql);;
}

function FormItemAnswerRadioSave($forminputanswer){
	global $dbcnx;
	
	$sql = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormAnswerID=".$forminputanswer->getFormAnswerID()." AND FormItemAnswerFormItemID =" . $forminputanswer->getFormInputID();
	mysqli_query($dbcnx, $sql);;
	
	$sql = FORMINPUTANSWER_INSERT . " VALUES(".$forminputanswer->getFormAnswerID().", ".$forminputanswer->getFormID().", ".$forminputanswer->getFormInputID().", ".$forminputanswer->getFormInputOptionID().", 'radio')";
	mysqli_query($dbcnx, $sql);;
}

function FormItemAnswerCheckboxSave($forminputanswer){
	global $dbcnx;
	
	$sql = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormAnswerID=".$forminputanswer->getFormAnswerID()." AND FormItemAnswerFormItemID =" . $forminputanswer->getFormInputID();
	mysqli_query($dbcnx, $sql);;
	
	$sql = FORMINPUTANSWER_INSERT . " VALUES(".$forminputanswer->getFormAnswerID().", ".$forminputanswer->getFormID().", ".$forminputanswer->getFormInputID().", ".$forminputanswer->getFormInputOptionID().", 'Checkbox')";
	mysqli_query($dbcnx, $sql);;
}

function FormInputAnswerDelete($formanswerid, $forminputid){
	global $dbcnx;

	$sql = "DELETE FROM FormItemAnswers WHERE FormItemAnswerFormAnswerID=".$formanswerid." AND FormItemAnswerFormItemID =".$forminputid;
	mysqli_query($dbcnx, $sql);;
}

function FormInputAnswerGet($formanswerid, $forminputoptionid){
	global $dbcnx;

	$instance = new FormInputAnswer;
	$sqlstr = FORMINPUTANSWER_SELECT . "WHERE FormItemAnswerFormAnswerID = ". $formanswerid ." AND FormItemAnswerFormItemOptionID = " . $forminputoptionid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGet()<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormInputAnswerSetAllFromRow($instance);
	return $instance;
}

function FormInputGetMyAnswer($formanswerid, $forminputid, $forminputtypeid){

	if($forminputanswer = FormInputAnswerGetForInput($formanswerid, $forminputid)){
		
		if($forminputtypeid == 1 || $forminputtypeid == 2){
			return $forminputanswer->getAnswerText();
		}else if( $forminputtypeid == 9 ){
			return CountryGet($forminputanswer->getAnswerText());
		}else if($forminputtypeid == 4){
			$forminputoption = FormInputOptionGet( $forminputanswer->getFormInputOptionID() );
			return $forminputoption->getOptionName();
		}
	}
	else{
		return "";
	}
}

function FormInputGetQuestion($forminputid, $forminputtypeid){

		
	if($forminputtypeid == 1 || $forminputtypeid == 2){
		
		$forminputoption = FormInputOptionGetAll($forminputid);
		$forminputoption = FormInputOptionGetNext($forminputoption);
		return nl2br($forminputoption->getLabel());

	}else if($forminputtypeid == 4){

		$forminputoption = FormInputOptionGet( $forminputanswer->getFormInputOptionID() );
		return $forminputoption->getLabel();
	}
}

function FormInputGetAnswer($formanswerid, $forminputid, $forminputtypeid){

	if($forminputanswer = FormInputAnswerGetForInput($formanswerid, $forminputid)){
		
		if($forminputtypeid == 1 || $forminputtypeid == 2){
			return $forminputanswer->getAnswerText();
		}else if( $forminputtypeid == 9 ){
			return CountryGet($forminputanswer->getAnswerText());
		}else if($forminputtypeid == 4){
			$forminputoption = FormInputOptionGet( $forminputanswer->getFormInputOptionID() );
			return $forminputoption->getLabel();
		}
	}
	else{
		return "-";
	}
}

function FormInputCheckAnswer($forminputid, $formanswerid, $defaultanswerid){

	if($thisanswer = FormInputAnswerGetForInput($formanswerid, $forminputid)){

		$correctanswer = FormInputAnswerGetForInput($defaultanswerid, $forminputid);

		if ( $thisanswer->getFormInputOptionID() == $correctanswer->getFormInputOptionID() )
			return 1;
		else
			return 0;
	}else return 0;

}
function FormInputOptionCheck($forminputoptionid, $forminputid, $defaultanswerid){

	$correctanswer = FormInputAnswerGetForInput($defaultanswerid, $forminputid);

	if ($defaultanswerid > 0 && $forminputoptionid == $correctanswer->getFormInputOptionID() )
		return 1;
	else
		return 0;

}


function FormInputAnswerGetForInput($formanswerid, $forminputid){
	global $dbcnx;

	$instance = new FormInputAnswer;
	$sqlstr = FORMINPUTANSWER_SELECT . "WHERE FormItemAnswerFormAnswerID = " . $formanswerid . " AND FormItemAnswerFormItemID =" . $forminputid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGetForInput()<br/> " . mysqli_error($dbcnx) . "<br/>" . $sqlstr);
	}
	$instance = FormInputAnswerSetAllFromRow($instance);
	return $instance;
}

// For checkboxes and multiple selects
function FormInputAnswerGetMultipleForInput($formanswerid, $forminputid){
	global $dbcnx;

	$instance = new FormInputAnswer;
	$sqlstr = FORMINPUTANSWER_SELECT . "WHERE FormItemAnswerFormAnswerID = " . $formanswerid . " AND FormItemAnswerFormItemID =" . $forminputid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGetMultipleForInput()<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormInputAnswerGetAllForFormAnswer($formanswerid){

	global $dbcnx;

	$instance = new FormInputAnswer;
	$sqlstr = FORMINPUTANSWER_SELECT . "WHERE FormItemAnswerFormAnswerID = " . $formanswerid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGetAllForFormAnswer()<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}


function FormInputAnswerGetAllForForm($formid){
	global $dbcnx;
	
	$instance = new FormInputAnswer;
	$sqlstr = FORMINPUTANSWER_SELECT . "WHERE FormItemAnswerFormID =" . $formid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGetAllForForm()<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function FormItemAnswerGetForSpecificItem($formanswerid, $formitemnameattribute){
	global $dbcnx;
	$instance = new FormInputAnswer;

	$sqlstr = "SELECT DISTINCT A.* FROM FormItemAnswers AS A, FormItems AS I WHERE A.FormItemAnswerFormAnswerID = ".$formanswerid." AND A.FormItemAnswerFormItemID = I.FormItemID AND I.FormItemLink = '". $formitemnameattribute . "'";
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function FormInputAnswerGetForSpecificItem()<br/> " . mysqli_error($dbcnx) );
	}
	$instance = FormInputAnswerSetAllFromRow($instance);
	return $instance;
}

function FormInputAnswerGetGlobalVoteResult($forminputid){
	global $dbcnx;
	$sqlstr = "SELECT SUM(FormItemAnswerText) FROM FormItemAnswers WHERE FormItemAnswerFormItemID = " . $forminputid;
	
	$result = @mysqli_query($dbcnx, $sqlstr);
	
	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		// return mysql_result($row,0); // removed
		$row = mysqli_fetch_assoc($result);
		return $row['SUM(FormItemAnswerText'];
	}
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