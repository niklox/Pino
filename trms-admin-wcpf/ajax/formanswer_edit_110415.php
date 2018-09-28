<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["formid"]))$formid = $_REQUEST["formid"]; 
if(isset($_REQUEST["formanswerid"]))$formanswerid = $_REQUEST["formanswerid"]; 

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){	
	global $action, $formid, $formanswerid;
	
	if( UserHasPrivilege($admin->getID(), 31) || UserHasPrivilege($admin->getID(), 19) ){
		if($action == "deleteanswer")
			deleteFormAnswer($formanswerid);
		else if($action == "viewformanswer")
			viewFormAnswer($formanswerid, $formid);
		else if($action == "deleteformanswer")
			deleteFormAnswer($formanswerid);
		else if($action == "viewstatistics")
			viewStatistics($formid);
		else if($action == "listglobalvote")
			listGlobalVotes($formid);
		else
			defaultAction($formid);
	}else{
		print "No permission: 31." . PrivilegeGetName(31) . " and 19." . PrivilegeGetName(19);
	}

}else print "Please login";

function defaultAction($formid){
	
	$form = FormGetForm($formid);
	
	switch($form->getTypeID()){
		case 0:
			formAnswerList($form);
		break;

		case 1:
			formAnswerList($form);
		break;

		case 2:
			 formAnswerGlobalVoteSearch($form);
		break;
	}
}

function formAnswerList($form){

	$formanswer = FormAnswerGetAllForForm($form->getID());

	print	"<ul>";

	while($formanswer = FormAnswerGetNext($formanswer)){
		$counter++;
		print	"<li id=\"answer_" . $formanswer->getID(). "\" class=\"formanswer\" value=\"" . $formanswer->getID() . "\">" . $formanswer->getID() . " " . $formanswer->getDateTime() . " ". ($formanswer->getStatus()==0?"UNCOMPLETE":"COMPLETE")  ." " . $formanswer->getIP() . "</li>";
	}

	print	"</ul>";
	print "Entries: " . $counter;
	print " <input type=\"button\" id=\"viewasexcel\" value=\"View all answers in a table\"/>";
	print " <input type=\"hidden\" id=\"fid\" value=\"" . $form->getID() . "\"/>";
}

function formAnswerGlobalVoteSearch($form){

	print	"<div id=\"searchbox\">\n";
	print	"<input type=\"hidden\" id=\"formid\" name=\"formid\" value=\"" . $form->getID() . "\"/>";
	print	"Startdate 	<input type=\"text\" class=\"dateinput\" name=\"startdate\" id=\"startdate\" value=\"\"/> ";
	print	"&nbsp;&nbsp;Enddate 	<input type=\"text\" class=\"dateinput\" name=\"enddate\" id=\"enddate\" value=\"\"/>";
	
	PrintGenericSelect("SELECT CountryID, CountryNameTextID FROM Countries ORDER BY CountryNameTextID", "countryid", "countryid", "Country", 0);

	print	"<select id=\"votetype\" name=\"votetype\" class=\"\">";
	print	"<option value=\"-2\">Select votetype</option>" .
			"<option value=\"-1\">All</option>" .
			"<option value=\"0\">Multivote</option>" .
			"<option value=\"1\">Singlevote</option>";
	print	"</select>";
	print	"<input type=\"button\" id=\"searchglobalvote\" value=\"Search\"/>";
	
	if( UserHasPrivilege(TermosGetCurrentUserID(), 30))
	print	"<input type=\"button\" id=\"statistics\" value=\"Statistics\"/>";

	print	"</div>\n";

	print	"<div id=\"globalvotelist\"></div>";

}

function listGlobalVotes($formid){
	
	if(isset($_REQUEST["startdate"]))$startdate = $_REQUEST["startdate"];
	if(isset($_REQUEST["enddate"]))$enddate = $_REQUEST["enddate"]; 
	if(isset($_REQUEST["countryid"]))$countryid = $_REQUEST["countryid"]; 
	if(isset($_REQUEST["votetype"]))$votetype = $_REQUEST["votetype"]; 

	$formanswers = FormAnswerSearch($startdate, $enddate, $countryid, $votetype, $formid);
	print "<table>";
	while($formanswers = FormAnswerGetNext($formanswers)){

		print "<tr class=\"globalvote\" id=\"gvrow_".$formanswers->getID()."\" value=\"" .$formanswers->getID() ."\"><td class=\"answerdate\">" . $formanswers->getDateTime() . "</td><td class=\"answercontact\">";
		
		if( $formanswers->getTypeID() == 0 )
			$forminputanswer = FormItemAnswerGetForSpecificItem($formanswers->getID(), "School_required");
		else if($formanswers->getTypeID() == 1 )
			$forminputanswer = FormItemAnswerGetForSpecificItem($formanswers->getID(), "Contact_required");
		
		if( isset($forminputanswer))
		print $forminputanswer->getAnswerText();
		
		print "</td><td class=\"answercountry\"> " . CountryGet($formanswers->getUserID()) . "</td> ";
		print "<td class=\"answerstatus\">" . ($formanswers->getStatus()== 0 ?" UNCOMPLETE":" COMPLETE") . "</td>";
		print "<td class=\"answertype\">" . ($formanswers->getTypeID()== 0 ?" Multivote":" Singlevote"). "</td>";
		print "<td class=\"answerip\">" . $formanswers->getIP() . "</td></tr>";

	}

	print "</table>";
}

function viewFormAnswer($formanswerid, $formid){

	$form = FormGetForm($formid);
	
	switch($form->getTypeID()){
		case 0:
			viewAnswer($formanswerid);
		break;

		case 1:
			viewQuizAnswer($formanswerid);
		break;

		case 2:
			 viewGlobalVote($formanswerid);
		break;
	}
}

function viewAnswer($formanswerid){

	$formanswer = FormAnswerGet($formanswerid);
	$forminput = FormInputGetAllForForm($formanswer->getFormID());

	print	"<table class=\"widetable\">";
	print	"<tr><td>" . $formanswer->getDateTime(). "</td><td><input type=\"button\" id=\"deleteanswer_".$formanswerid."\" value=\"".MTextGet("deleteanswer")." " . $formanswerid . "\"/></td></tr>";
	
	while($forminput = FormInputGetNext($forminput)){

		if($forminput->getTypeID() == 1 || $forminput->getTypeID() == 2){
			print "<tr><td class=\"question\">" . FormInputGetQuestion( $forminput->getID(), $forminput->getTypeID() ) ."</td><td class=\"answer\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()). "</td></tr>";
		}
		else if($forminput->getTypeID() == 4){
			print "<tr><td class=\"question\">" . $forminput->getQuestion() ."</td><td class=\"answer\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()). "</td></tr>";
		}
		else if($forminput->getTypeID() == 9){
			print "<tr><td class=\"question\">" . $forminput->getTitle() ."</td><td class=\"answer\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()). "</td></tr>";
		}
	}
	
	print	"</table>";
}

function viewQuizAnswer($formanswerid){

	$formanswer = FormAnswerGet($formanswerid);
	$defaultanswerid = FormGetDefaultAnswerID($formanswer->getFormID());
	$forminput = FormInputGetAllForForm($formanswer->getFormID());

	print	"<table class=\"widetable\">";
	print	"<tr><td colspan=\"3\">" . $formanswer->getDateTime(). "</td></tr>";
	
	while($forminput = FormInputGetNext($forminput)){

		if($forminput->getTypeID() == 1 || $forminput->getTypeID() == 2){
			print "<tr><td class=\"question\">" . FormInputGetQuestion( $forminput->getID(), $forminput->getTypeID() ) ."</td><td class=\"answer\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()). "</td><td></td></tr>";
		}
		else if($forminput->getTypeID() == 4){
			print "<tr><td class=\"question\">" . $forminput->getQuestion() ."</td><td class=\"answer\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()). "</td><td class=\"result\">";
			
			if($defaultanswerid > 0){

				if(FormInputCheckAnswer($forminput->getID(), $formanswer->getID(), $defaultanswerid) == 1 ){
					$correctanswers++;
					print "true";
				}else{
					print "false";
				}
			}
			
			print"</td></tr>";
		}
	}
	print	"<tr><td></td><td></td><td>".$correctanswers."</td></tr>";
	print	"</table>";
}

function viewGlobalVote($formanswerid){

	$formanswer = FormAnswerGet($formanswerid);

	print	"<table class=\"widetable\">";
	print	"<tr><td  colspan=\"2\">";
	print	"Global vote ID: " . $formanswer->getID() . ", Date: " .  $formanswer->getDateTime() . ", Type: " . ( $formanswer->getTypeID() == 0?"Singevote":"Multivote" ) . ", IP: " . $formanswer->getIP();
	print	"</td></tr>";
	print	"<tr><td class=\"left\">Info</td><td class=\"right\">Votes</td></tr>";

	print	"<tr><td class=\"left\"><table class=\"stdtable\">";

	$forminput = FormInputGetAllForForm($formanswer->getFormID());

	while($forminput = FormInputGetNext($forminput)){

		if(!strstr($forminput->getNameAttribute(), "Nominee")){
			print	"<tr><td class=\"left\">" . $forminput->getTitle() . "</td><td class=\"right\">" . 
					
					FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()) . 
				
					"</td></tr>";
		}
	}

	print	"</table></td><td class=\"right\"><table class=\"stdtable\">";

	$forminput = FormInputGetAllForForm($formanswer->getFormID());

	while($forminput = FormInputGetNext($forminput)){

		if(strstr($forminput->getNameAttribute(), "Nominee")){
			
			print	"<tr>" .
					"<td class=\"left80\">" . $forminput->getTitle() . "</td>" . 
					"<td class=\"right20\">" . FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID()) . "</td>" .
					"</tr>";

			$noofvotes += FormInputGetAnswer($formanswerid, $forminput->getID(), $forminput->getTypeID());
		}
	}

	print	"<tr><td class=\"left80\">Total votes:</td><td class=\"right20\">" . $noofvotes . "</td></tr>";
	print	"<tr><td class=\"left80\"><input type=\"button\" class=\"deleteanswer\" id=\"deletegv_".$formanswer->getID()."\" value=\"Delete this vote\"/></td><td class=\"right20\"></td></tr>";
	print	"</table></td></tr>";
	print	"</table>";
}

function viewStatistics($formid){

	if( UserHasPrivilege(TermosGetCurrentUserID(), 30) ){

		print	"<table class=\"widetable\">";
		$forminput = FormInputGetAllForForm($formid);

		while( $forminput = FormInputGetNext($forminput) ){

			if( strstr( $forminput->getNameAttribute(),"Nominee" ) ){

				$votes = FormInputAnswerGetGlobalVoteResult($forminput->getID());
				print	"<tr><td class=\"left80\">" . $forminput->getTitle() . "</td><td class=\"right20\">" . $votes . "</td></tr>";
				$totalvotes += $votes;
			}
		}
		print	"<tr><td class=\"left80\"><b>Total:</b></td><td class=\"right20\"><b>"  . $totalvotes . "</b></td></tr>";
		print	"</table>";

	}else print	"no privilege";
}

function deleteFormAnswer($formanswerid){

	FormAnswerDelete($formanswerid);
}

function loadscripts(){
	print	"<script type=\"text/javascript\" src=\"/trms-admin/js/formanswer_edit.js\"></script>\n";
}

?>