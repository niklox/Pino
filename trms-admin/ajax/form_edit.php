<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';
DBcnx();
loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["formid"]))$formid = $_REQUEST["formid"]; 
if(isset($_REQUEST["forminputid"]))$forminputid = $_REQUEST["forminputid"]; 
if(isset($_REQUEST["forminputoptionid"]))$forminputoptionid = $_REQUEST["forminputoptionid"]; 
if(isset($_REQUEST["formname"]))$formname = $_REQUEST["formname"]; 
if(isset($_REQUEST["formtype"]))$formtype = $_REQUEST["formtype"]; 
if(isset($_REQUEST["formaction"]))$formaction = $_REQUEST["formaction"]; 
if(isset($_REQUEST["formrecipient"]))$formrecipient = $_REQUEST["formrecipient"]; else $formrecipient = WEBMASTER;
if(isset($_REQUEST["formstatus"]))$formstatus = $_REQUEST["formstatus"];

if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){	
	global $action, $formid, $forminputid, $forminputoptionid;
	
	if($action == "save")
		saveForm($formid);
	else if($action == "delete")
		deleteForm($formid);
	else if($action == "formselect")
		formSelect($formid);
	else if($action == "viewform")
		viewForm($formid);
	else if($action == "savedefaultanswer")
		saveDefaultAnswer($formid);
	else if($action == "editinput")
		editFormInput($forminputid);
	else if($action == "saveinput")
		saveFormInput($forminputid, $formid);
	else if($action == "updateforminputlist")
		updateFormInputList($formid);
	else if($action == "saveinputoption")
		saveFormInputOption($forminputoptionid);
	else if($action == "editorview")
		optionEditView($forminputid);
	else if($action == "deleteforminput")
		deleteFormInput($forminputid);
	else if($action == "copyform")
		copyForm($formid);
	else
		defaultAction($formid);

}else print "Please login";

function defaultAction($formid){
	global $formname;
	print	"<div id=\"formedit\">\n";
	
	if( $formid ){
		
		$form = FormGetForm($formid);

		print	"<input type=\"text\" title=\"Formname\" name=\"selectedform\" id=\"selectedform\" value=\"". $form->getName(). "\"/>";
		print	"<input type=\"hidden\" name=\"formid\" id=\"formid\" value=\"". $form->getID(). "\"/>";
		print	"<select title=\"Formtype\" id=\"formtype\" name=\"formtype\">\n" .
				"<option value=\"0\" ". ($form->getTypeID() == 0 ?" selected" : "") .">Standard form</option>\n" .
				"<option value=\"1\" ". ($form->getTypeID() == 1 ?" selected" : "") .">Quiz form</option>\n" .
				"<option value=\"2\" ". ($form->getTypeID() == 2 ?" selected" : "") .">Global Vote</option>\n" .
				"</select>\n";
		
		print	"<input title=\"Formaction\" type=\"text\" name=\"formaction\" id=\"formaction\" value=\"". $form->getAction()."\" />\n";
		print	"<input title=\"Formrecipient\" type=\"text\" name=\"formrecipient\" id=\"formrecipient\" value=\"". $form->getRecipient()."\" />\n";
		
		print	"<select title=\"Formstatus\"  id=\"formstatus\" name=\"formstatus\">\n" .
				"<option value=\"0\" ". ($form->getStatus() == 0 ?" selected" : "") .">Default</option>\n" .
				"<option value=\"1\" ". ($form->getStatus() == 1 ?" selected" : "") .">Active</option>\n" .
				"<option value=\"2\" ". ($form->getStatus() == 2 ?" selected" : "") .">Resting</option>\n" .
				"</select>\n";

		print	"<input type=\"button\" id=\"deleteform\" value=\"" . MTextGet("deleteform") . "\"/> ";
		print	"<input type=\"button\" id=\"copyform\" value=\"" . MTextGet("copyform") . "\"/>";
			
	}else{
		
		print	"<input type=\"text\" name=\"selectedform\" id=\"selectedform\" value=\"". $formname. "\"/>";
		print	"<input type=\"hidden\" name=\"formid\" id=\"formid\" value=\"0\"/>";
		print	"<select id=\"formtype\" name=\"formtype\">\n" .
				"<option value=\"0\">Standard form</option>\n" .
				"<option value=\"1\">Quiz form</option>\n" .
				"<option value=\"2\">Global Vote</option>\n" .
				"</select>\n";
		
		print	"<input type=\"text\" name=\"formaction\" id=\"formaction\" value=\"\" /> \n";
		print	"<input type=\"text\" name=\"formrecipient\" id=\"formrecipient\" value=\"\" />\n";
		
		print	"<select id=\"formstatus\" name=\"formstatus\">\n" .
				"<option value=\"0\">Default</option>\n" .
				"<option value=\"1\">Active</option>\n" .
				"<option value=\"2\">Resting</option>\n" .
				"</select>\n";
	}	

	print	"</div>\n";

	if( $formid ){

		print	"<div id=\"forminputedit\">\n";
		print	"<div id=\"selectedforminput\"></div>";
		print	"<span class=\"blackhead\">Input fields in " . $form->getName() . "</span>";
		print	"<input type=\"button\" id=\"createnewinput\" value=\"Create new input\" /> ";
		
		print	"<select id=\"options_no\"><option value=\"0\">No of options</option>";
		for($i=1; $i<10; $i++)
		print	"<option value=\"" . $i . "\" ".($i == 1?" selected":"").">" . $i . "</option>";
		print	"</select>";
		
		print	"<select id=\"forminputtype\">\n" .
				"<option value=\"\">Select forminput type to create</option>\n" .
				"<option value=\"1\">Textfield</option>\n" .
				"<option value=\"2\">Textarea</option>\n" .
				"<option value=\"3\">Checkbox</option>\n" .
				"<option value=\"4\">Radiobuttons</option>\n" .
				"<option value=\"6\">Selectbox</option>\n" .
				"<option value=\"9\">Country select</option>\n" .
				"<option value=\"10\">Explaning text</option>\n" .
				"</select>";
				
		print	"<ul id=\"forminputlist\">";

		$formitems = FormInputGetAllForForm($formid);
		while($formitems = FormInputGetNext($formitems)){
			print "<li value=\"". $formitems->getID() . "\" class=\"forminput\">" .$formitems->getPosition() . ". " . $formitems->getTitle() . "</li>";
		}
		print	"</ul>";
		print	"</div>\n";
	}	
}

function viewForm($formid){

	$form = FormGetForm($formid);
	$forminput = FormInputGetAllForForm($formid);
	print	"<form id=\"showform\" method=\"post\" action=\"/trms-admin/ajax/form_edit.php\">";
	print	"<input type=\"hidden\" id=\"formid\" name=\"formid\"  value=\"".$formid."\"/>";
	print	"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"savedefaultanswer\"/>";

	while($forminput = FormInputGetNext($forminput)){
		if($form->getTypeID() == 1)
		FormInputPrintDefaultAnswer($forminput->getID(), "standard", 0, 1);
		else
		FormInputPrint($forminput->getID(), "standard", 0, 1);
		print "<br/>";
	}
	if($form->getTypeID() == 1)
	print	"<input type=\"submit\" id=\"setdefaultanswer\" value=\"Create defaultanswer\">";
	print	"</form>";

}

function saveDefaultAnswer($formid){

	$forminputanswer = new FormInputAnswer;
	$formanswer = new FormAnswer;
	
	$defaultanswerid = FormGetDefaultAnswerID($formid);

	if( $defaultanswerid > 0 )
		FormAnswerDelete($defaultanswerid);
	
	$formanswer->setID(0);
	$formanswer->setFormID($formid);
	$formanswer->setUserID(0);
	$formanswer->setTypeID(1);
	$formanswer->setDateTime( date("Y-m-d H:i:s") );
	$formanswer->setStatus(1);
	$formanswer->setIP(getenv('REMOTE_ADDR'));
	FormAnswerSave($formanswer);

	foreach($_REQUEST as $key => $value){
			
			if(strstr($key, "radio")){
			$forminputanswer->setFormAnswerID(TermosGetCounterValue("FormAnswer"));
			$forminputanswer->setFormID($formid);
			$forminputanswer->setFormInputID( substr($key, 6, strlen( $key)) );
			$forminputanswer->setFormInputOptionID($value);
			$forminputanswer->setAnswerText("radio");
			FormInputAnswerSave($forminputanswer);
		}
	}

	FormSetDefaultAnswer($formid, TermosGetCounterValue("FormAnswer"));

	viewForm($formid);
}

function formSelect(){

	print	"<select name=\"forms\" id=\"forms\">\n" .
			"<option value=\"0\">".MTextGet('selectForm')."</option>\n";

			$form = FormGetAll();

			while($form = FormGetNext($form)){
				print "<option value=\"". $form->getID() ."\" >" . $form->getName() . "</option>\n";
			}
	
	print	"</select>\n" .
			"<input type=\"hidden\" id=\"action\" name=\"action\" value=\"createForm\">\n" .
			"<input type=\"input\" id=\"formname\" name=\"formname\" size=\"30\"/>\n" .
			"<input type=\"button\" id=\"createForm\" value=\"Create new form\">\n";
}

function saveForm($formid){

	global $formname, $formtype, $formaction, $formrecipient, $formstatus;
	
	if($formid != 0){
		$form = FormGetForm($formid);
		$mtext = MTextGetMTextByLanguage($form->getNameTextID(), TermosGetCurrentLanguage());
		$mtext->setTextContent($formname);
		MTextUpdateTextContent($mtext);
	
	}else{
		
		$form = new Form;
		$mtext = MTextNewInCategory("formTexts", $formname);
		MTextUpdateTextContentCopyAllLanguages($mtext);
		$form->setNameTextID($mtext->getID());
	}

	$form->setID($formid);
	$form->setTypeID($formtype);
	$form->setAction($formaction);
	$form->setRecipient($formrecipient);
	$form->setStatus($formstatus);

	FormSave($form);

	if($formid == 0)
		print "<span id=\"newid\">". TermosGetCounterValue("FormID") ."</span>";
	else
		print "<span id=\"newid\">".$formid."</span>";
}

function deleteForm($formid){

	FormDelete($formid);
}

function editFormInput($forminputid){

	if($forminputid > 0){
		
		// Edit existing forminput
		
		$forminput = FormInputGet($forminputid);
		print	"<div id=\"editforminput_header\">". MTextGet(FormInputTypeGetName($forminput->getTypeID())) . " <span id=\"close_btn\">x</span> </div>" .
				"<div id=\"editforminput\">";

		print	"<input type=\"hidden\" name=\"forminputid\" id=\"forminputid\" value=\"" . $forminput->getID() . "\" />";
		
		// leftcolumn
		print	"<div id=\"editforminput_left\" class=\"editforminput_boxes\">"; 
	
		print	"<label for=\"inputtitle\"><a title=\"Edit MText for Title\" href=\"/trms-admin/mtext.php?action=edit&textid=".$forminput->getTitleTextID()."&textcatid=".MTextGetCategoryid($forminput->getTitleTextID(), TermosGetCurrentLanguage())."\" target=\"_new\">Title</a></label><br/><input type=\"text\" class=\"forminput_text\" name=\"inputtitle\" id=\"inputtitle\" value=\"" . $forminput->getTitle() . "\" /><br/>";
		print		"<label for=\"inputquestion\"><a title=\"Edit MText for Question\" href=\"/trms-admin/mtext.php?action=edit&textid=".$forminput->getQuestionTextID()."&textcatid=".MTextGetCategoryid($forminput->getQuestionTextID(), TermosGetCurrentLanguage())."\" target=\"_new\">Question</a></label><br/><textarea class=\"forminput_textarea\" name=\"inputquestion\" id=\"inputquestion\">" . $forminput->getQuestion() . "</textarea><br/>\n";
		print		"<label for=\"inputcomment\"><a title=\"Edit MText for Comment\" href=\"/trms-admin/mtext.php?action=edit&textid=".$forminput->getCommentTextID()."&textcatid=".MTextGetCategoryid($forminput->getCommentTextID(), TermosGetCurrentLanguage())."\" target=\"_new\">Comment</a></label><br/><textarea class=\"forminput_textarea\" name=\"inputcomment\" id=\"inputcomment\">" . $forminput->getComment() . "</textarea><br/>\n" ;


		print	"<label for=\"inputtitle\">Name Attribute</label><br/><input type=\"text\" class=\"forminput_text\" name=\"nameattribute\" id=\"nameattribute\" value=\"" . $forminput->getNameAttribute() . "\" /><br/>";

		print	"<label for=\"inputtitle\">Image URL or Handle</label><br/><input type=\"text\" class=\"forminput_text\" name=\"imageurl\" id=\"imageurl\" value=\"" . $forminput->getImage() . "\" /><br/>";
		
		print	"</div>";
		
		// rightcolumn
		print	"<div id=\"editforminput_right\" class=\"editforminput_boxes\"><br/>"; 

		print	"Position: <select id=\"inputposition\">";
		
		for($i=0; $i<70; $i++){
		print	"<option value=\"" . $i . "\" ".($forminput->getPosition() == $i ? " selected": "" ). ">" . $i . "</option>";
		}
		print	"</select> ";

		print	" Page: <select id=\"inputpage\">";
		
		for($i=0; $i<30; $i++){
		print	"<option value=\"" . $i . "\" ".($forminput->getPageNo() == $i ? " selected": "" ). ">" . $i . "</option>";
		}
		print	"</select>";

		print	" Visibility: <select id=\"inputvisibility\">";

		print	"<option value=\"0\" ".($forminput->getVisibility() == 0 ? " selected": "" ). ">hidden</option>";
		print	"<option value=\"1\" ".($forminput->getPageNo() == 1 ? " selected": "" ). ">visible</option>";

		print	"</select><br/><br/>";
		print	"<div id=\"theforminput\">";
		
		if($viewinput == false){

			switch($forminput->getTypeID()){
				case 1: print "Textfield label<br/>"; break; case 2: print "Textarea label<br/>"; break; case 3: print "Checkbox labels<br/>"; break; case 4: print "Radiobutton labels<br/>"; break; case 6: print "Selectbox options<br/>"; break; case 9: print "Countryselect label<br/>"; break; case 10: print "Explaining text label<br/>"; break;
			}

			$options = FormInputOptionGetAll($forminputid);
			while($options = FormInputOptionGetNext($options)){
					print	"<input type=\"text\" class=\"forminputoption_text\" id=\"". $options->getOptionName() . "\" value=\"" . $options->getLabel() . "\"/> &nbsp;<span class=\"tbtn\"><a title=\"Edit MText for label\" href=\"/trms-admin/mtext.php?action=edit&textid=".$options->getTextID()."&textcatid=".MTextGetCategoryid($options->getTextID(), TermosGetCurrentLanguage())."\"\" target=\"_new\">T</a></span><br/>";
			}
		}else{

			print	"view!";
		}

		print	"</div>";
		print	"<br/><input type=\"button\" id=\"deleteforminput\" value=\"Delete this forminput\" /> <input type=\"button\" id=\"viewforminput\" value=\"View this forminput\" />";
		print	"</div>";
		print	"</div>";
	}
}

function optionEditView($forminputid){

	if(isset($_REQUEST["viewinput"]))$viewinput = $_REQUEST["viewinput"];

	$forminput = FormInputGet($forminputid);

	if($viewinput == "false"){

			switch($forminput->getTypeID()){
				case 1: print "Textfield label<br/>"; break; case 2: print "Textarea label<br/>"; break; case 3: print "Checkbox labels<br/>"; break; case 4: print "Radiobutton labels<br/>"; break; case 6: print "Selectbox options<br/>"; break; case 9: print "Countryselect label<br/>"; break; case 10: print "Explaining text label<br/>"; break;
			}

			$options = FormInputOptionGetAll($forminputid);
			while($options = FormInputOptionGetNext($options)){
					print	"<input type=\"text\" class=\"forminputoption_text\" id=\"". $options->getOptionName() . "\" value=\"" . $options->getLabel() . "\"/>&nbsp;<span class=\"tbtn\"><a title=\"Edit MText for label\" href=\"/trms-admin/mtext.php?action=edit&textid=".$options->getTextID()."&textcatid=".MTextGetCategoryid($options->getTextID(), TermosGetCurrentLanguage())."\"\" target=\"_new\">T</a></span><br/>";
			}

		}else{

			FormInputPrint($forminputid, "standard", 0, 1);
		}

}

function saveFormInput($forminputid, $formid){

	if(isset($_REQUEST["inputtitle"]))$inputtitle = $_REQUEST["inputtitle"]; 
	if(isset($_REQUEST["inputquestion"]))$inputquestion = $_REQUEST["inputquestion"]; 
	if(isset($_REQUEST["inputcomment"]))$inputcomment = $_REQUEST["inputcomment"]; 
	if(isset($_REQUEST["nameattribute"]))$nameattribute = $_REQUEST["nameattribute"]; 
	if(isset($_REQUEST["imageurl"]))$imageurl = $_REQUEST["imageurl"]; 
	if(isset($_REQUEST["inputposition"]))$inputposition = $_REQUEST["inputposition"]; 
	if(isset($_REQUEST["inputpage"]))$inputpage = $_REQUEST["inputpage"]; 
	if(isset($_REQUEST["inputvisibility"]))$inputvisibility = $_REQUEST["inputvisibility"]; 

	
	if($forminputid > 0){
		
		// Save forminput
		$forminput = FormInputGet($forminputid);

		$mtext = MTextGetMTextByLanguage($forminput->getTitleTextID(), TermosGetCurrentLanguage());
		$mtext->setTextContent($inputtitle);
		MTextUpdateTextContent($mtext);

		$mtext = MTextGetMTextByLanguage($forminput->getQuestionTextID(), TermosGetCurrentLanguage());
		$mtext->setTextContent($inputquestion);
		MTextUpdateTextContent($mtext);

		$mtext = MTextGetMTextByLanguage($forminput->getCommentTextID(), TermosGetCurrentLanguage());
		$mtext->setTextContent($inputcomment);
		MTextUpdateTextContent($mtext);

		$forminput->setPageNo($inputpage);
		$forminput->setPosition($inputposition);
		$forminput->setNameAttribute($nameattribute);
		$forminput->setImage($imageurl);
		$forminput->setVisibility($inputvisibility);

		FormInputSave($forminput);
	
	}else{
		// Create forminput
		if(isset($_REQUEST["options_no"]))$options_no = $_REQUEST["options_no"]; 
		if(isset($_REQUEST["forminputtypeid"]))$forminputtypeid = $_REQUEST["forminputtypeid"]; 
		
		$forminput = new FormInput;

		$mtext = MTextNewInCategory("formItemTexts", "insert title");
		MTextUpdateTextContentCopyAllLanguages($mtext);
		$forminput->setTitleTextID($mtext->getID());

		$mtext = MTextNewInCategory("formItemTexts", "");
		MTextUpdateTextContentCopyAllLanguages($mtext);
		$forminput->setQuestionTextID($mtext->getID());

		$mtext = MTextNewInCategory("formItemTexts", "");
		MTextUpdateTextContentCopyAllLanguages($mtext);
		$forminput->setCommentTextID($mtext->getID());

		$forminput->setID(0);
		$forminput->setFormID($formid);
		$forminput->setTypeID($forminputtypeid);
		$forminput->setPageNo(1);
		$forminput->setPosition(0);
		$forminput->setNameAttribute("");
		$forminput->setImage("");
		$forminput->setVisibility(1);

		FormInputSave($forminput);

		$forminputcountervalue = TermosGetCounterValue("FormItemID");

		$forminputoption = new FormInputOption;

		for($i=0; $i<$options_no; $i++){
			
			$forminputoptioncountervalue = TermosGetCounterValue("FormItemOptionID");
			$forminputoptioncountervalue++;

			$mtext = MTextNewInCategory("formItemTexts", "insert label");
			MTextUpdateTextContentCopyAllLanguages($mtext);
			$forminputoption->setTextID($mtext->getID());
			
			$forminputoption->setID(0);
			$forminputoption->setFormItemID($forminputcountervalue);
			$forminputoption->setOptionName("n_" . $forminputoptioncountervalue);
			$forminputoption->setOptionValue("v_" . $forminputoptioncountervalue);
			$forminputoption->setVisibility(1);

			FormInputOptionSave($forminputoption);
		}

		editFormInput($forminputcountervalue);
	}
}

function updateFormInputList($formid){

	$formitems = FormInputGetAllForForm($formid);
	while($formitems = FormInputGetNext($formitems)){
		print "<li value=\"". $formitems->getID() . "\" class=\"forminput\">" .$formitems->getPosition() . ". " . $formitems->getTitle() . "  " .$formitems->getPageNo(). "</li>";
	}
}

function deleteFormInput($forminputid){
	FormInputDelete($forminputid);
}

function saveFormInputOption($forminputoptionid){
	
	if(isset($_REQUEST["optionname"]))$optionname = $_REQUEST["optionname"]; 

	if($forminputoptionid > 0){
		
		$inputoption = FormInputOptionGet($forminputoptionid);
		
		$mtext = MTextGetMTextByLanguage($inputoption->getTextID(), TermosGetCurrentLanguage());
		$mtext->setTextContent($optionname);
		MTextUpdateTextContent($mtext);
	}
}

function copyForm($formid){
	
	$srcForm = FormGetForm($formid);
	
	
	$trgForm = new Form;
	$mtext = MTextNewInCategory("formTexts", $srcForm->getName() . " (copy)");
	MTextUpdateTextContentCopyAllLanguages($mtext);
	$trgForm->setNameTextID($mtext->getID());
	
	$trgForm->setTypeID($srcForm->getTypeID());
	$trgForm->setAction($srcForm->getAction());
	$trgForm->setRecipient($srcForm->getRecipient());
	$trgForm->setStatus($srcForm->getStatus());

	FormSave($trgForm);

	$trgFormID = TermosGetCounterValue("FormID");
	
	$srcFormInput = FormInputGetAllForForm($formid);

	while($srcFormInput = FormInputGetNext($srcFormInput)){

		$trgFormInput = new FormInput;

		//$mtext = MTextNewInCategory("formItemTexts", $srcFormInput->getTitle());
		//MTextUpdateTextContentCopyAllLanguages($mtext);
		//$trgFormInput->setTitleTextID($mtext->getID());
		
		$mtextid = MTextCopyMText($srcFormInput->getTitleTextID(), "formItemTexts", $srcFormInput->getTitle());
		$trgFormInput->setTitleTextID($mtextid);

		//$mtext = MTextNewInCategory("formItemTexts", $srcFormInput->getQuestion());
		//MTextUpdateTextContentCopyAllLanguages($mtext);
		//$trgFormInput->setQuestionTextID($mtext->getID());
		
		$mtextid = MTextCopyMText($srcFormInput->getQuestionTextID(), "formItemTexts", $srcFormInput->getQuestion());
		$trgFormInput->setQuestionTextID($mtextid);

		//$mtext = MTextNewInCategory("formItemTexts", $srcFormInput->getComment());
		//MTextUpdateTextContentCopyAllLanguages($mtext);
		//$trgFormInput->setCommentTextID($mtext->getID());
		
		$mtextid = MTextCopyMText($srcFormInput->getCommentTextID(), "formItemTexts", $srcFormInput->getComment());
		$trgFormInput->setCommentTextID($mtextid);

		$trgFormInput->setFormID($trgFormID);
		$trgFormInput->setPageNo($srcFormInput->getPageNo());
		$trgFormInput->setPosition($srcFormInput->getPosition());
		$trgFormInput->setTypeID($srcFormInput->getTypeID());
		$trgFormInput->setNameAttribute($srcFormInput->getNameAttribute());
		$trgFormInput->setImage($srcFormInput->getImage());
		$trgFormInput->setVisibility($srcFormInput->getVisibility());
		$trgFormInput->setCols($srcFormInput->getCols());
		$trgFormInput->setRows($srcFormInput->getRows());

		FormInputSave($trgFormInput);

		$trgFormInputID = TermosGetCounterValue("FormItemID");

		$srcFormInputOption = FormInputOptionGetAll($srcFormInput->getID());
		$trgFormInputOption = new FormInputOption;

		while($srcFormInputOption = FormInputOptionGetNext($srcFormInputOption)){

			$forminputoptioncountervalue = TermosGetCounterValue("FormItemOptionID");
			$forminputoptioncountervalue++;

			//$mtext = MTextNewInCategory("formItemTexts", $srcFormInputOption->getLabel());
			//MTextUpdateTextContentCopyAllLanguages($mtext);
			//$trgFormInputOption->setTextID($mtext->getID());
			
			$mtextid = MTextCopyMText($srcFormInputOption->getTextID(), "formItemTexts", $srcFormInputOption->getLabel());
			$trgFormInputOption->setTextID($mtextid);
			
			$trgFormInputOption->setID(0);
			$trgFormInputOption->setFormItemID($trgFormInputID);
			$trgFormInputOption->setOptionName("n_" . $forminputoptioncountervalue);
			$trgFormInputOption->setOptionValue("v_" . $forminputoptioncountervalue);
			$trgFormInputOption->setVisibility(1);

			FormInputOptionSave($trgFormInputOption);
		}
	}
	
	print "Done! \"" . $srcForm->getName() . "\" is now copied.";
}

function loadscripts(){
	print	"<script type=\"text/javascript\" src=\"/trms-admin/js/form_edit.js\"></script>\n";
			
}

?>