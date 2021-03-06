<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Topic.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Topic.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Discussions.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Discussions.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];

if(isset($_REQUEST["typeid"]))$typeid = $_REQUEST["typeid"]; else $typeid = 0;


if(isset($_REQUEST["statusid"]))$statusid = $_REQUEST["statusid"];
if(isset($_REQUEST["flagid"]))$flagid = $_REQUEST["flagid"];
if(isset($_REQUEST["orgtypeid"]))$orgtypeid = $_REQUEST["orgtypeid"];

if(isset($_REQUEST["organisationid"]))$organisationid = $_REQUEST["organisationid"];

if(isset($_REQUEST["usertypeid"]))$usertypeid = $_REQUEST["usertypeid"];

if(isset($_REQUEST["userstatusid"]))$userstatusid = $_REQUEST["userstatusid"];


if(isset($_REQUEST["recorddetail"]))$recorddetail = $_REQUEST["recorddetail"];
if(isset($_REQUEST["recorddetailvalue"]))$recorddetailvalue = $_REQUEST["recorddetailvalue"];

if(isset($_REQUEST["condition"]))$condition = $_REQUEST["condition"];
if(isset($_REQUEST["topicid"]))$topicid = $_REQUEST["topicid"];

if(isset($_REQUEST["countryid"]))$countryid = $_REQUEST["countryid"];
if(isset($_REQUEST["checked"]))$checked = $_REQUEST["checked"]; else $checked = 0;



if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){
	
	if($action == "createnewrecord")
			createNewRecord();
	else if($action == "editrecord")
			editRecord($recordid, $typeid);
	else if($action == "viewrecord")
			viewRecord($recordid, $typeid);
	else if($action == "saverecorddetail")
			saveRecordDetail($recordid, $recorddetail, $recorddetailvalue, $typeid);
	else if($action == "makequery")
			makeQuery();
			
	else if($action == "checkstatus")
			OrganisationSetStatus($recordid, $statusid);
	else if($action == "uncheckstatus")
			OrganisationRemoveStatus($recordid, $statusid);
	else if($action == "checktype")
			OrganisationSetType($recordid, $orgtypeid);
	else if($action == "unchecktype")
			OrganisationRemoveType($recordid, $orgtypeid);
			
	else if($action == "checktopic")
			TopicAdd($topicid, $recordid);
	else if($action == "unchecktopic")
			TopicRemove($topicid, $recordid);
			
			
	else if($action == "checkuserstatus")
			UserSetStatus($recordid, $userstatusid);
	else if($action == "uncheckuserstatus")
			UserRemoveStatus($recordid, $userstatusid);
	else if($action == "checkusertype")
			UserSetType($recordid, $usertypeid);
	else if($action == "uncheckusertype")
			UserRemoveType($recordid, $usertypeid);		
			
	else if($action == "deleteorganisation")
			deleteOrganisation($recordid);
	else if($action == "deleteuser")
			deleteIndividual($recordid);
	
	else if($action == "comments")
			showComments($recordid, $typeid);
	else if($action == "wcp-program")
			wcpProgram($recordid, $typeid);
	else if($action == "special-projects")
			specialProjects($recordid, $typeid);
	else if($action == "setactive")
			 CountrySetActive($countryid, $checked);
			
}else print "Please login";

function createNewRecord(){

	if(isset($_REQUEST["new_record_name"]))$record_name = $_REQUEST["new_record_name"];
	if(isset($_REQUEST["new_account_name"]))$account_name = $_REQUEST["new_account_name"];
	if(isset($_REQUEST["n_type"]))$record_type = $_REQUEST["n_type"];
	
	if(strcmp($account_name, "Accountname/EMail") == 0){
	
		$nextid = TermosGetCounterValue("UserID");
		$nextid++;
		$account_name = "userid" . $nextid;
	}
	
	
	if($record_type == 1){
	
		$neworg = new Organisation;
		$neworg->setOrgName($record_name);
		$neworg->setRegistrationDate(date("Y-m-d"));
		$orgid = OrganisationSave($neworg);
		$type = "editorg";
		editRecord($orgid, $type);
	
	}else if($record_type == 2){
	
		$newuser = new User;
		$newuser->setFullname($record_name);
		$newuser->setAddressID(72);
		$newuser->setAccountName($account_name);
		$newuser->setEMail($account_name);
		$newuser->setCreatedDate(date("Y-m-d"));
		$userid = UserSave($newuser);
		$type = "edituser";
		editRecord($userid, $type);
	}

	
}

function editRecord($id, $type){
	
	global $action;

	if($type == "editorg"){
	
		if(is_object($org = OrganisationGetByID($id))){
		print 	'<input type="hidden" id="recordid" value="'.$org->getID().'"/><input type="hidden" id="typeid" value="editorg"/>';
		print 	'<div class="gf_column_3">'.
				'<label class="lb">Name</label><input type="text" class="gf_input_edit" id="orgname_'. $org->getID() .'" name="orgname" value="'.  str_replace("\"","&quot;",stripslashes($org->getOrgName())) .'"/>'.
				'<label class="lb">Short name</label><input type="text" class="gf_input_edit" id="orgshortname_'. $org->getID() .'" name="orgshortname" value="'. str_replace("\"", "&quot;", stripslashes($org->getShortName())) .'"/>'.
				'<label class="lb">Street address</label><input type="text" class="gf_input_edit" id="orgaddress1_'.$org->getID().'" name="orgaddress1" value="'. $org->getAddress1() .'"/>';
		print 	'<label class="lb">Zip Code</label><input type="text" class="gf_input_edit" id="orgzip_'.$org->getID().'" name="orgzip" value="'. $org->getZip() .'"/>'.
				'<label class="lb">City</label><input type="text" class="gf_input_edit" id="orgcity_'.$org->getID().'" name="orgcity"  value="'. $org->getCity() .'"/><br/>';
		
		print 	'<label class="lb">Postal address (box,zip,city)</label><input type="text" class="gf_input_edit" id="orgaddress2_'.$org->getID().'" name="orgaddress2" value="'. $org->getAddress2() .'"/><br/>';
		
		if($org->getCountryID() == 215)
		print 	'<label class="lb">Invoice address (name,street,box,zip,city)</label><input type="text" class="gf_input_edit" id="orginvoiceaddress_'.$org->getID().'"  value="'. $org->getCoordinates() .'"/><br/>';
		
		
		//print 	$org->getCountryID();
				
		print 	'<label class="lb">Country</label><br/>';
		PrintGenericSelect('SELECT country_id, short_name FROM country_t', 'gf_select_edit', 'orgcountry_' . $org->getID(), 'Select country', $org->getCountryID());
		
		if( $org->getCountryID() == 215 || $action == "createnewrecord"){
			
		print 	'<label class="lb">Län</label><br/>';
		PrintGenericSelect('SELECT RegionID, RegionName FROM Region ORDER BY RegionID','gf_select_edit','orgregion_' . $org->getID(), 'Select region', ($org->getRegionID() == 0?-1:$org->getRegionID())  );
		print 	'<label class="lb">Kommun</label><br/>';
			if($org->getRegionID() == 0 || $org->getRegionID() == "")
				print	'<select class="gf_select_edit" id="orgmunicipality_'.$org->getID().'" name="orgmunicipality_'.$org->getID().'"><option value="0">Select municipality</option><option value="0">Select region first</option></select>';
			else
				PrintGenericSelect('SELECT MunicipalityID, MunicipalityName FROM Municipality WHERE RegionID = ' . $org->getRegionID() . ' ORDER BY MunicipalityID', 'gf_select_edit','orgmunicipality_' .$org->getID(), 'Select municipality', $org->getMunicipalityID());
		}
		if( ($org->getCountryID() > 0 && $org->getCountryID() != 215) || $action == "createnewrecord" ){
			print	'<label class="lb">Region/State</label><input type="text" id="orgstateregion_'.$org->getID().'" class="gf_input_edit" value="'.$org->getRegion().'"/>';	
		}		
				
		
		print	'<label class="lb">Continent</label>'.
			 	'<select class="gf_select_edit" id="orgcontinent_'.$org->getID().'" name="orgcontinent_'.$org->getID().'">'.
				'<option value="">Select continent</option>'.
				'<option value="Africa" '.($org->getContinent() == 'Africa'?" selected":"").'>Africa</option>'.
				'<option value="Asia" '.($org->getContinent() == 'Asia'?" selected":"").'>Asia</option>'.
				'<option value="Australia &amp; Oceania" '.($org->getContinent() == 'Australia'?" selected":"").'>Australia & Oceania</option>'.
				'<option value="Europe" '.($org->getContinent() == 'Europe'?" selected":"").'>Europe</option>'.
				'<option value="North America" '.($org->getContinent() == 'North America'?" selected":"").'>North America</option>'.
				'<option value="South America" '.($org->getContinent() == 'South America'?" selected":"").'>South America</option>'.
				'</select>';
				
				
		if($org->getTypeID() &~ 8 && $org->getCountryID() != 215){
		
		print 	'<label class="lb">Linked to</label>'.
				'<select class="gf_select_edit" id="orgparent_'.$org->getID().'" name="orgparent_'.$org->getID().'">'.
				'<option value="0">Select Focal point</option>';
				$fpoint = OrganisationGet('WHERE TypeID & 8');
				
				while($fpoint = OrganisationGetNext($fpoint)){
					print '<option value="'.$fpoint->getID().'" '.($fpoint->getID() == $org->getParentID()?"SELECTED":"" ).'>'.$fpoint->getShortName().  ' - ' . CountryGetName($fpoint->getCountryID()). '</option>';
				}
				
		print 	'</select>';
		
		}
			
				
		print 	'</div>';
		
		
		print 	'<div class="gf_column_2">';
		print 	'<div class="gf_column_3">';
		
		print 	'<label class="lb">Contact name</label><input type="text" class="gf_input_edit" id="orgcontactname_'. $org->getID().'" name="orgcontactname" value="'.$org->getContactName().'"/>'.
				'<label class="lb">Contact phone</label><input type="text" class="gf_input_edit"id="orgcontacttel_'. $org->getID().'" name="orgcontacttel" value="'.$org->getContactTel().'"/>'.
				'<label class="lb">Contact email</label><input type="text" class="gf_input_edit" id="orgcontactemail_'. $org->getID().'" name="orgcontactemail" value="'.$org->getContactEMail().'"/>';
				
		print 	'<label class="lb">Telephone school</label><input type="text" class="gf_input_edit" id="orgtel_'. $org->getID().'"  name="orgtel" value="'.$org->getTel().'"/>'.
				'<label class="lb">EMail school</label><input type="text" class="gf_input_edit" id="orgemail_'. $org->getID().'"  name="orgemail" value="'.$org->getEMail().'"/>'.
				'<label class="lb">Fax</label><input type="text" class="gf_input_edit"id="orgfax_'. $org->getID().'"  name="orgfax" value="'.$org->getFax().'"/>'.
				'<label class="lb">www</label><input type="text" class="gf_input_edit" id="orgurl_'. $org->getID().'"  name="orgurl" value="'.$org->getURL().'"/>';
		
		print	'<label class="lb">Language</label>'.
			 	'<select class="gf_select_edit" id="orglanguage_'.$org->getID().'" name="orglanguage_'.$org->getID().'">'.
				'<option value="0">Select language</option>'.
				'<option value="5" '.($org->getLanguageID() == '5'?" selected":"").'>English</option>'.
				'<option value="6" '.($org->getLanguageID() == '6'?" selected":"").'>Spanish</option>'.
				'<option value="7" '.($org->getLanguageID() == '7'?" selected":"").'>French</option>'.
				'<option value="8" '.($org->getLanguageID() == '8'?" selected":"").'>Portuguese</option>'.
				'<option value="1" '.($org->getLanguageID() == '1'?" selected":"").'>Swedish</option>'.
				'<option value="9" '.($org->getLanguageID() == '9'?" selected":"").'>Thai</option>'.
				'<option value="11" '.($org->getLanguageID() == '11'?" selected":"").'>Hindi</option>'.
				'<option value="10" '.($org->getLanguageID() == '10'?" selected":"").'>Tamil</option>'.
				'</select>';		
		print 	'</div>';
		
		print 	'<div class="gf_column_3">';
		print 	'<div class="checkboxgroup3">';
		print 	'<div class="boxlabel">Organisation type</div>';
		print 	'<input type="checkbox" class="settype" id="type_1_'.$org->getID().'" value="1" '.($org->getTypeID() & 1?"checked":"" ).'/> Global Friend School<br/>'.
				'<input type="checkbox" class="settype" id="type_2_'.$org->getID().'" value="2" '.($org->getTypeID() & 2?"checked":"" ).'/> Global Friend Group<br/>'.
				'<input type="checkbox" class="settype" id="type_4_'.$org->getID().'" value="4" '.($org->getTypeID() & 4?"checked":"" ).'/> Adult Friend Organisation<br/>'.
				'<input type="checkbox" class="settype" id="type_8_'.$org->getID().'" value="8" '.($org->getTypeID() & 8?"checked":"" ).'/> Focal point<br/>'.
				'<input type="checkbox" class="settype" id="type_16_'.$org->getID().'" value="16" '.($org->getTypeID() & 16?"checked":"" ).'/> Donor - Monthly<br/>'.
				'<input type="checkbox" class="settype" id="type_32_'.$org->getID().'" value="32" '.($org->getTypeID() & 32?"checked":"" ).'/> Donor - One time<br/>'.
				'<input type="checkbox" class="settype" id="type_64_'.$org->getID().'" value="64" '.($org->getTypeID() & 64?"checked":"" ).'/> Administrative<br/>'.
				'<input type="checkbox" class="settype" id="type_128_'.$org->getID().'" value="128" '.($org->getTypeID() & 128?"checked":"" ).'/> Media<br/>'.
				'<input type="checkbox" class="settype" id="type_256_'.$org->getID().'" value="256" '.($org->getTypeID() & 256?"checked":"" ).'/> Subscriber<br/>';
		
		
		print 	'</div>';
		
		if($org->getTypeID() & 1)
		print	'<label class="lb">No of students</label><br/>'.
				'<input type="text" class="gf_input_edit_small" id="orgstudents_'.$org->getID().'" name="orgstudents_'.$org->getID().'" value="'.$org->getExternalID().'" size="5"/><br/>';
		
		print 	'<label class="lb">Record created</label><br/>'.
				'<input type="text" class="gf_input_edit_date" id="orgcreateddate_'.$org->getID().'" name="orgcreateddate_'.$org->getID().'" value="'.$org->getRegistrationDate().'"/>';
		
		print 	'</div>';
		
		print 	'<div class="gf_column_2">';
		print 	'<div class="checkboxgroup2">';
		print 	'<div class="boxlabel">Status</div>';
		print 	'<input type="checkbox" class="setstatus" id="status_1_'.$org->getID().'" value="1" '.($org->getStatus() & 1?"checked":"" ).'/> Pending '.
				'<input type="checkbox" class="setstatus" id="status_2_'.$org->getID().'" value="2" '.($org->getStatus() & 2?"checked":"" ).'/> Approved '.
				'<input type="checkbox" class="setstatus" id="status_4_'.$org->getID().'" value="4" '.($org->getStatus() & 4?"checked":"" ).'/> Follow-up '.
				'<input type="checkbox" class="setstatus" id="status_8_'.$org->getID().'" value="8" '.($org->getStatus() & 8?"checked":"" ).'/> Incomplete '.
				'<input type="checkbox" class="setstatus" id="status_16_'.$org->getID().'" value="16" '.($org->getStatus() & 16?"checked":"" ).'/> Resting '.
				'<input type="checkbox" class="setstatus" id="status_32_'.$org->getID().'" value="32" '.($org->getStatus() & 32?"checked":"" ).'/> Confirmed '.
				'<input type="checkbox" class="setstatus" id="status_64_'.$org->getID().'" value="64" '.($org->getStatus() & 64?"checked":"" ).'/> Inactive';
		
		print 	'</div>';
		
		print 	'<label class="lb">Description</label><textarea id="orgdescription_'.$org->getID().'" name="" class="gf_textarea_edit">'.stripslashes($org->getDescription()).'</textarea>';
		print  	'<input type="button" class="gf_button_right" id="deleteorg_'.$org->getID().'" value="Delete '. $org->getOrgName() .'"/>';
		
		print 	'</div>';
		
		}else{ print 'No record with id: ' . $id;}
		
	}else if($type == "edituser"){
	
		if(is_object($user = UserGetUserByID($id))){
		
		$org = OrganisationGetByID($user->getAddressID());
		
		print 	'<input type="hidden" id="recordid" value="'.$user->getID().'"/><input type="hidden" id="typeid" value="edituser"/>';
		
		print 	'<div class="gf_column_2">';
		print 	'<div class="gf_column_3">'.
				'<label class="lb">Fullname</label><input type="text" class="gf_input_edit" id="usrname_'. $user->getID() .'" name="username" value="'.  str_replace("\"","&quot;",stripslashes($user->getFullName())) .'"/>'.
				'<label class="lb">Jobfunction/Title</label><input type="text" class="gf_input_edit" id="usrjobfunction_'. $user->getID() .'" name="userjobfunction" value="'.  str_replace("\"","&quot;",stripslashes($user->getJobfunction())) .'"/>'.
				'<label class="lb">First EMail/AccountName</label><input type="text" class="gf_input_edit" id="usraccountname_'. $user->getID() .'" name="useraccountname" value="'.  $user->getAccountName() .'"/>'.
				'<label class="lb">Alternate EMail</label><input type="text" class="gf_input_edit" id="usremail_'. $user->getID() .'" name="useremail" value="'.  $user->getEMail() .'"/>'.
				'<label class="lb">Telephone</label><input type="text" class="gf_input_edit" id="usrtel_'. $user->getID() .'" name="usertel" value="'.  $user->getTel() .'"/>'.
				'<label class="lb">Cell</label><input type="text" class="gf_input_edit" id="usrcell_'. $user->getID() .'" name="usercell" value="'.  $user->getCell() .'"/>'.
				'<label class="lb">Birth date</label><br/><input type="text" class="gf_input_edit_date" id="usrbirthdate_'. $user->getID() .'" name="usrbirthdate" value="'.  $user->getBirthDate() .'" maxlength="10"/><br/>'.
				'<label class="lb">ExternalID/Passport No/Etc.</label><br/><input type="text" class="gf_input_edit" id="usrexternalid_'. $user->getID() .'" name="usrexternalid" value="'.  $user->getExternalID() .'"/>'.
				'</div>';
				
		print 	'<div class="gf_column_3">';
		
		print 	'<label class="lb">Organisation</label><br/>';
		PrintGenericSelect(ORGANISATION_SELECT . ' WHERE TypeID & 64', 'gf_select_edit', 'usrorganisation_' . $user->getID(), 'Select Organisation', $user->getAddressID());
		
		print 	'<label class="lb">Address ( If other than '. $org->getOrgName().' ´s address ) </label><br/>';
		print	'<input class="gf_input_edit" id="usraddress_'.$user->getID().'" value="'.$user->getAddress().'"/><br/>';

		print 	'<label class="lb">Zip</label><br/>';
		print	'<input class="gf_input_edit" id="usrzip_'.$user->getID().'" value="'.$user->getZip().'"/><br/>';

		print 	'<label class="lb">City</label><br/>';
		print	'<input class="gf_input_edit" id="usrcity_'.$user->getID().'" value="'.$user->getCity().'"/>';

		print 	'<label class="lb">Country ( If other than '. $org->getOrgName().' ´s country )</label><br/>';

		if($user->getCountryID() == 0)
		PrintGenericSelect('SELECT country_id, short_name FROM country_t', 'gf_select_edit', 'usrcountry_' . $user->getID(), 'Select country', $org->getCountryID());
		else
		PrintGenericSelect('SELECT country_id, short_name FROM country_t', 'gf_select_edit', 'usrcountry_' . $user->getID(), 'Select country', $user->getCountryID());
		
		print  '<label class="lb">Info/Desc</label><br/><textarea class="gf_textarea_edit_narrow" id="usrinfotext_'.$user->getID().'" name="">' . $user->getInfoText() . '</textarea>';
		print 	'</div>';
		
		
		print 	'<div class="checkboxgroup2">';
		print 	'<div class="boxlabel">Status</div>';
		print 	'<input type="checkbox" class="setuserstatus" id="status_1_'.$user->getID().'" value="1" '.($user->getStatus() & 1?"checked":"" ).'/> Pending '.
				'<input type="checkbox" class="setuserstatus" id="status_2_'.$user->getID().'" value="2" '.($user->getStatus() & 2?"checked":"" ).'/> Approved '.
				'<input type="checkbox" class="setuserstatus" id="status_4_'.$user->getID().'" value="4" '.($user->getStatus() & 4?"checked":"" ).'/> Follow-up '.
				'<input type="checkbox" class="setuserstatus" id="status_8_'.$user->getID().'" value="8" '.($user->getStatus() & 8?"checked":"" ).'/> Incomplete '.
				'<input type="checkbox" class="setuserstatus" id="status_16_'.$user->getID().'" value="16" '.($user->getStatus() & 16?"checked":"" ).'/> Resting '.
				'<input type="checkbox" class="setuserstatus" id="status_32_'.$user->getID().'" value="32" '.($user->getStatus() & 32?"checked":"" ).'/> Confirmed '.
				'<input type="checkbox" class="setuserstatus" id="status_64_'.$user->getID().'" value="64" '.($user->getStatus() & 64?"checked":"" ).'/> Inactive';
		print 	'</div>';
		
		print 	'</div>';
		
		/*
		print 	'<div class="gf_column_3">';
		print 	'<label class="lb">Created date</label><br/>';
		print 	 $user->getCreatedDate() . '<br/>';
		print 	'<label class="lb">Last login</label><br/>';
		print 	 $user->getLastDate() . '<br/>';
		print 	'<label class="lb">Previous date</label><br/>';
		print 	 $user->getPreviousDate() . '<br/>';
		print 	'<label class="lb">No of logins</label><br/>';
		print 	 $user->getCounter() . '<br/>';
		print 	'</div>';*/
		
		print 	'<div class="gf_column_3">';
		print 	'<div class="checkboxgroup3B">';
		print 	'<div class="boxlabel">Individual type</div>';
		print 	'<input type="checkbox" class="setusertype" id="type_1_'.$user->getID().'" value="1" '.($user->getTypeID() & 1?"checked":"" ).'/> Administrator<br/>'.
				'<input type="checkbox" class="setusertype" id="type_2_'.$user->getID().'" value="2" '.($user->getTypeID() & 2?"checked":"" ).'/> Global Friend<br/>'.
				'<input type="checkbox" class="setusertype" id="type_4_'.$user->getID().'" value="4" '.($user->getTypeID() & 4?"checked":"" ).'/> Adult Friend<br/>'.
				'<input type="checkbox" class="setusertype" id="type_8_'.$user->getID().'" value="8" '.($user->getTypeID() & 8?"checked":"" ).'/> Patron<br/>'.
				'<input type="checkbox" class="setusertype" id="type_16_'.$user->getID().'" value="16" '.($user->getTypeID() & 16?"checked":"" ).'/> Donor - monthly<br/>'.
				'<input type="checkbox" class="setusertype" id="type_32_'.$user->getID().'" value="32" '.($user->getTypeID() & 32?"checked":"" ).'/> Donor - one time<br/>'.
				'<input type="checkbox" class="setusertype" id="type_64_'.$user->getID().'" value="64" '.($user->getTypeID() & 64?"checked":"" ).'/> Jury member<br/>'.
				'<input type="checkbox" class="setusertype" id="type_128_'.$user->getID().'" value="128" '.($user->getTypeID() & 128?"checked":"" ).'/> Media<br/>'.
				'<input type="checkbox" class="setusertype" id="type_256_'.$user->getID().'" value="256" '.($user->getTypeID() & 256?"checked":"" ).'/> Subscriber<br/>'.
				'<input type="checkbox" class="setusertype" id="type_512_'.$user->getID().'" value="512" '.($user->getTypeID() & 512?"checked":"" ).'/> Laureate<br/>'.
				'<input type="checkbox" class="setusertype" id="type_1024_'.$user->getID().'" value="1024" '.($user->getTypeID() & 1024?"checked":"" ).'/> Interpreter<br/>'.
				'<input type="checkbox" class="setusertype" id="type_2048_'.$user->getID().'" value="2048" '.($user->getTypeID() & 2048?"checked":"" ).'/> Staff<br/>'.
				'<input type="checkbox" class="setusertype" id="type_4096_'.$user->getID().'" value="4096" '.($user->getTypeID() & 4096?"checked":"" ).'/> Child musician<br/>'.
				'<input type="checkbox" class="setusertype" id="type_8192_'.$user->getID().'" value="8192" '.($user->getTypeID() & 8192?"checked":"" ).'/> Chaperone<br/>'.
				'<input type="checkbox" class="setusertype" id="type_16384_'.$user->getID().'" value="16384" '.($user->getTypeID() & 16384?"checked":"" ).'/> Intern<br/>'.
				'<input type="checkbox" class="setusertype" id="type_32768_'.$user->getID().'" value="32768" '.($user->getTypeID() & 32768?"checked":"" ).'/> Volunteer<br/>'.
				'<input type="checkbox" class="setusertype" id="type_65536_'.$user->getID().'" value="65536" '.($user->getTypeID() & 65536?"checked":"" ).'/> Award ceremony guest<br/>'.
				'<input type="checkbox" class="setusertype" id="type_131072_'.$user->getID().'" value="131072" '.($user->getTypeID() & 131072?"checked":"" ).'/> Contact person GF-school<br/>'.
				'</div>';
				
		print 	'<input type="button" class="gf_button_right" id="deleteuser_'.$user->getID().'" value="Delete '. $user->getFullname() .'"/>';
		print 	'</div>';
				
		
		
		
		}
	
	}else{print "no type for " . $id;}
}

function viewRecord($id, $type){

	if($type == "editorg"){
	
		if(is_object($org = OrganisationGetByID($id))){
		print '<input type="hidden" id="recordid" value="'.$org->getID().'"/><input type="hidden" id="typeid" value="editorg"/>';
		print '<div id="gf_view_card">';
		
		print '<div id="gf_view_card_head">'.stripslashes($org->getOrgName()).' #'.$org->getID().'</div>';
		print '<div id="gf_view_card_body">';
		
		//		The first column
		
		print '<div class="gf_view_3">';
		
		print 	'<label class="lb">Name</label><br/>' . $org->getOrgName() . '<br/>';
				
		print 	'<label class="lb">Street address</label><br/>' . ' ' . $org->getAddress1() . '<br/>';
		print 	$org->getZip() . ' ' . $org->getCity() . '<br/>';
		
		print 	'<label class="lb">Postal address (box,zip,city)</label><br/>';
		if(strlen($org->getAddress2())) print $org->getAddress2() . '<br/>'; else print '-<br/>';
		
		// Invoice address saved in field Coordinates only for Swedish
		if($org->getCountryID() == 215){
		print 	'<label class="lb">Invoice address (name,box,zip,city)</label><br/>';
		if(strlen($org->getCoordinates())) print $org->getCoordinates() . '<br/>'; else print '-<br/>';
		}
		
		
		if( $org->getCountryID() == 215 && $org->getRegionID() > 0 && $org->getMunicipalityID()  )
				print '<label class="lb">Municipality</label><br/>' . MunicipalityGetName($org->getMunicipalityID()) . '<br/>';
		
		if( $org->getCountryID() == 215 && $org->getRegionID() > 0 )
				print '<label class="lb">Region</label><br/>' . RegionGetName($org->getRegionID()) . '<br/>';
				
		if($org->getCountryID() > 0 && $org->getCountryID() != 215 )
				print '<label class="lb">Region/State</label><br/>' .$org->getRegion() . '<br/>';
		
		print 	'<label class="lb">Country</label><br/>' . CountryGetName($org->getCountryID()) . '<br/>' .
				'<label class="lb">Continent</label><br/>' .$org->getContinent() . '<br/>';
			
		if($org->getParentID() > 0)
		print '<label class="lb">Linked to</label><br/>' . OrganisationGetName($org->getParentID()) .'<br/>';	
		
		// Unknown focalpoint saved in field Coordinates only for non swedish
		/*
		if($org->getCountryID() != 215){
		if(strlen($org->getCoordinates()))
		print  $org->getCoordinates() .'<br/>';	
		}
		*/
		
		print '</div>';
		
		//		The second column
		print '<div class="gf_view_3">';
		
		
		print 	'<label class="lb">Contact</label><br/>'. $org->getContactName() . '<br/>';
		print 	'<label class="lb">Contact telephone (obsolete)</label><br/>'. $org->getContactTel() . '<br/>';
		print 	'<label class="lb">Contact e-mail (obsolete)</label><br/>'. $org->getContactEMail() . '<br/>';
		
		print 	'<label class="lb">Telephone school</label><br/>'. $org->getTel() . '<br/>';
		print 	'<label class="lb">E-mail school</label><br/>'. $org->getEMail() . '<br/>';
		print 	'<label class="lb">Fax</label><br/>'. $org->getFax() . '<br/>';
		print 	'<label class="lb">www</label><br/>'. $org->getUrl() . '<br/>';
		
		print '<label class="lb">Record created</label><br/>' . $org->getRegistrationDate() .'<br/>';
		print '<label class="lb">No of students</label><br/>' . $org->getExternalID() .'<br/>';
		
		print '</div>';
		
		// the map column
		print '<div class="gf_view_map">';
		print '<div id="map"></div>';
		print '<span style="font-size:10px;font-style:italic">';
		print 'Lat: ' . $_REQUEST["latitude"] . ' ';
		print 'Lng: ' . $_REQUEST["longitude"];
		print '</span>';
		print '</div>';
		
		print '</div>'; // gf_view_card_body end
		
		print '<div id="gf_view_card_extra_body_'.$org->getID().'"></div>';
		//		This area is ajax filled
		print '</div>';
		
		// 		Some extra tabs
		print '<div id="gf_view_card_extra">';
		print '<div id="gf_view_card_extra_tabs">'.
			  '<input type="button" class="gf_button" id="individuals_'.$org->getID().'" value="Individuals"/>'.
			  '<input type="button" class="gf_button" id="wcp-program_'.$org->getID().'" value="WCP Programs"/>'.
			  '<input type="button" class="gf_button" id="wcp-topics_'.$org->getID().'" value="Topics"/>'.
			  '<input type="button" class="gf_button" id="comments_'.$org->getID().'" value="Contact comments '.(DiscussionGetUnread($org->getID())>0?"*":"").'"/>';
			
		if($org->getTypeID() & 8)
		print  '<input type="button" class="gf_button" id="connected_'.$org->getID().'" value="Linked to ('.FocalPointCountConnected($org->getID()).')"/>';
		
		print '</div>';
		print '</div>';
		
		print '</div>';
		}else{ print 'No record with id: ' . $id;}
		
	}else if($type == "edituser"){
	
		
		if(is_object($user = UserGetUserByID($id))){
		print '<input type="hidden" id="recordid" value="'.$user->getID().'"/><input type="hidden" id="typeid" value="edituser"/>';
		
		print '<div id="gf_view_card">';
		
		print '<div id="gf_view_card_head">'.stripslashes($user->getFullName()).' #' . $user->getID() . '</div>';
		print '<div id="gf_view_card_body">';
		
		print '<div class="gf_view_3">';
		print '<label class="lb">Fullname</label><br/>' . $user->getFullName() . '<br/>';
		print '<label class="lb">Jobfunction/Title</label><br/>' . $user->getJobFunction() . '<br/>';
		print '<label class="lb">EMail/AccountName</label><br/>' .$user->getAccountName() . '<br/>';
		print '<label class="lb">Tel</label><br/>' .$user->getTel() . '<br/>';
		print '<label class="lb">Cell</label><br/>' .$user->getCell() . '<br/>';
		print '<label class="lb">BirthDate</label><br/>' .$user->getBirthDate() . '<br/>';
		print '</div>';
		
		print '<div class="gf_view_3">';
		
		$org = OrganisationGetByID($user->getAddressID());
		print '<label class="lb">Connected to</label><br/>' . $org->getOrgName() . '<br/>';
		print '<label class="lb">Address ( If other than '. $org->getOrgName().' ´s address )</label><br/>' . $user->getAddress() . '<br/>';
		print '<label class="lb">Zip</label><br/>' . $user->getZip() . '<br/>';
		print '<label class="lb">City</label><br/>' . $user->getCity() . '<br/>';
		print '<label class="lb">Country</label><br/>' . CountryGetName($user->getCountryID()) . '<br/>';
		
		print '</div>';
		
		print 	'<div class="gf_view_3">';
		print 	'<label class="lb">Created date</label><br/>';
		print 	 $user->getCreatedDate() . '<br/>';
		print 	'<label class="lb">Last login</label><br/>';
		print 	 substr($user->getLastDate(), 0,16) . '<br/>';
		print 	'<label class="lb">Previous date</label><br/>';
		print 	 substr($user->getPreviousDate(), 0,16) . '<br/>';
		print 	'<label class="lb">No of logins</label><br/>';
		print 	 $user->getCounter() . '<br/>';
		print 	'</div>';
		
		print '</div>';
		print '</div>';
		
		}else{ print 'No record with id: ' . $id;}
	
	}else{print "no typefor " . $id;}
}

function saveRecordDetail($recordid, $recorddetail, $recorddetailvalue, $typeid){

	//print $recordid . ' ' . $recorddetail . ' ' . $recorddetailvalue . ' ' . $typeid;
	
	if($typeid == "editorg")
		$organisation = OrganisationGetByID($recordid);
	else if($typeid == "editusr")
		$user = UserGetUserByID($recordid);
	
	switch($recorddetail){
	
			//organisationedits
			case 'orgname':
			$organisation->setOrgName($recorddetailvalue);
			break;
			case 'orgaddress1':
			$organisation->setAddress1($recorddetailvalue);
			break;
			case 'orgaddress2':
			$organisation->setAddress2($recorddetailvalue);
			break;
			
			case 'orginvoiceaddress':
			$organisation->setCoordinates($recorddetailvalue);
			break;
			case 'orgzip':
			$organisation->setZip($recorddetailvalue);
			break;
			case 'orgcity':
			$organisation->setCity($recorddetailvalue);
			break;
			case 'orgcountry':
			$organisation->setCountryID($recorddetailvalue);
			break;
			case 'orgstateregion':
			$organisation->setRegion($recorddetailvalue);
			break;
			
			case 'orgregion':
			$organisation->setRegionID($recorddetailvalue);
			break;
			case 'orgmunicipality':
			$organisation->setMunicipalityID($recorddetailvalue);
			break;
			case 'orgcontinent':
			$organisation->setContinent($recorddetailvalue);
			break;
			case 'orgcontactname':
			$organisation->setContactName($recorddetailvalue);
			break;
			case 'orgcontacttel':
			$organisation->setContactTel($recorddetailvalue);
			break;
			case 'orgcontactemail':
			$organisation->setContactEmail($recorddetailvalue);
			break;
			case 'orgtel':
			$organisation->setTel($recorddetailvalue);
			break;
			case 'orgfax':
			$organisation->setFax($recorddetailvalue);
			break;
			case 'orgemail':
			$organisation->setEMail($recorddetailvalue);
			break;
			case 'orgurl':
			$organisation->setURL($recorddetailvalue);
			break;
			case 'orgdescription':
			$organisation->setDescription($recorddetailvalue);
			break;
			case 'orglanguage':
			$organisation->setLanguageID($recorddetailvalue);
			break;
			case 'orgstudents':
			$organisation->setExternalID($recorddetailvalue);
			break;
			case 'orgshortname':
			$organisation->setShortName($recorddetailvalue);
			break;
			case 'orgparentid':
			$organisation->setParentID($recorddetailvalue);
			break;
			case 'orgcreateddate':
			$organisation->setRegistrationDate($recorddetailvalue);
			break;
			case 'orgparent':
			$organisation->setParentID($recorddetailvalue);
			break;
			
			
			// useredits
			case 'usrname':
			$user->setFullname($recorddetailvalue);
			break;
			case 'usrjobfunction':
			$user->setJobFunction($recorddetailvalue);
			break;
			case 'usraccountname':
			$user->setAccountName($recorddetailvalue);
			break;
			case 'usremail':
			$user->setEMail($recorddetailvalue);
			break;
			case 'usrtel':
			$user->setTel($recorddetailvalue);
			break;
			case 'usrcell':
			$user->setCell($recorddetailvalue);
			break;
			case 'usrbirthdate':
			$user->setBirthDate($recorddetailvalue);
			break;
			case 'usrexternalid':
			$user->setExternalID($recorddetailvalue);
			break;
			case 'usrorganisation':
			$user->setAddressID($recorddetailvalue);
			break;
			case 'usraddress':
			$user->setAddress($recorddetailvalue);
			break;
			case 'usrzip':
			$user->setZip($recorddetailvalue);
			break;
			case 'usrcity':
			$user->setCity($recorddetailvalue);
			break;
			case 'usrcountry':
			$user->setCountryID($recorddetailvalue);
			break;	
			case 'usrinfotext':
			$user->setInfoText($recorddetailvalue);
			break;	
	}
	
	if($typeid == "editorg"){
		$organisation->setLastUpdate(date("Y-m-d H:i:s"));
		OrganisationSave($organisation);
	}
	else if( $typeid == "editusr" ){
		UserSave($user);
	}
}

function makeQuery(){
	global $typeid;

	$arg = 0;
	$sqlstr = " WHERE ";
	$usersinorg = 0;
	$totalzeros = 0;
	$counter = 0;
	
	
	if(isset($_REQUEST["stype"]))$stype = $_REQUEST["stype"];
	if(isset($_REQUEST["checkboxview"]))$checkboxview = $_REQUEST["checkboxview"];
	
	print '<table>';
	
	/**********************************
	 * Build a query for organisations 
	 **********************************/
	 
	if( $stype == 1 ){
	
		
		if(isset($_REQUEST["recordname"]) && strlen($_REQUEST["recordname"]) > 2 &&  $_REQUEST["recordname"] != "School or Organisation Name ..."){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$recordname = $_REQUEST["recordname"];
			$sqlstr .= 'Organisation.Name LIKE "%' . $recordname . '%"';
			$arg++;
		}
		
		if(isset($_REQUEST["country_id"]) && $_REQUEST["country_id"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$countryid = $_REQUEST["country_id"];
			$sqlstr .= 'Organisation.CountryID = ' . $countryid;
			$arg++;
		}
		
		if(isset($_REQUEST["s_region_id"]) && $_REQUEST["s_region_id"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$regionid = $_REQUEST["s_region_id"];
			$sqlstr .= 'Organisation.RegionID = ' . $regionid;
			$arg++;
		}
		
		if(isset($_REQUEST["s_municipality_id"]) && $_REQUEST["s_municipality_id"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$municipalityid = $_REQUEST["s_municipality_id"];
			$sqlstr .= 'Organisation.MunicipalityID = ' . $municipalityid;
			$arg++;
		}
		
		if(isset($_REQUEST["flag1"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag1 = $_REQUEST["flag1"];
			$sqlstr .= 'Organisation.Status & ' . $flag1;
			$arg++;
		}
		
		if(isset($_REQUEST["flag2"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag2 = $_REQUEST["flag2"];
			$sqlstr .= 'Organisation.Status & ' . $flag2;
			$arg++;
		}
		
		if(isset($_REQUEST["flag3"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag3 = $_REQUEST["flag3"];
			$sqlstr .= 'Organisation.Status & ' . $flag3;
			$arg++;
		}
		if(isset($_REQUEST["flag4"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag4 = $_REQUEST["flag4"];
			$sqlstr .= 'Organisation.Status & ' . $flag4;
			$arg++;
		}
		if(isset($_REQUEST["flag5"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag5 = $_REQUEST["flag5"];
			$sqlstr .= 'Organisation.Status & ' . $flag5;
			$arg++;
		}
		if(isset($_REQUEST["flag6"]) ){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$flag6 = $_REQUEST["flag6"];
			$sqlstr .= 'Organisation.Status & ' . $flag6;
			$arg++;
		}
		if(isset($_REQUEST["typeid"]) && $_REQUEST["typeid"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$typeid = $_REQUEST["typeid"];
			$sqlstr .= 'Organisation.TypeID & ' . $typeid;
			$arg++;
		}
		if(isset($_REQUEST["startdate"]) && $_REQUEST["startdate"] != "Startdate"){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$startdate = $_REQUEST["startdate"];
			$sqlstr .= 'Organisation.RegistrationDate >= "' . $startdate . '"';
			$arg++;
		}
		if(isset($_REQUEST["enddate"]) && $_REQUEST["enddate"] != "Enddate") {
			if($arg > 0 ) $sqlstr .= ' AND ';
			$enddate = $_REQUEST["enddate"];
			$sqlstr .= 'Organisation.RegistrationDate <= "' . $enddate .'"';
			$arg++;
		}
		
		
		if(isset($_REQUEST["topicid"]) && $_REQUEST["topicid"] != 0){
				$pfrx_sql = ', TopicValues AS TV ';
				if($arg > 0 ) $sqlstr .= ' AND ';
			
				$sqlstr .= 'TV.TopicID = ' . $_REQUEST["topicid"] . ' AND TV.OrganisationID = Organisation.OrganisationID ';
				$sqlstr = $pfrx_sql . $sqlstr;
			
				$arg++;
		}
		
		if(isset($_REQUEST["program_year"]) && $_REQUEST["program_year"] != 0){
				$pfrx2_sql = ', WCP_Program AS WCPP ';
				if($arg > 0 ) $sqlstr .= ' AND ';
			
				$sqlstr .= 'WCPP.Year = "' . $_REQUEST["program_year"] . '" AND WCPP.OrgID = Organisation.OrganisationID ';
				$sqlstr = $pfrx2_sql . $sqlstr;
			
				$arg++;
		}
		
		if($arg == 0 ) $sqlstr = '';
		
		
		if($arg == 0 || ( $typeid == 1 && $arg == 1 )) // if typeid is Global Friend School and the selecttion is to wide
		print '<td colspan="6">No arguments in sql query or query to wide, Please make a narrower selection</td>';
		else if($checkboxview == 0){
		
			
		 print '<span id="condition" style="font-size:10px;font-style:italic">' .$sqlstr . '</span><br/>';
		
		
			$orgs = OrganisationGet($sqlstr);
			print 	'<tr>'.
					'<td>School/Organisation</td>'.
					'<td>Last update</td>'.
					'<td>City</td>';
			if($orgs->getCountryID() == 215)	
			print	'<td>Municipality</td>';
			else
			print	'<td></td>';
					
			print 	'<td>Region</td>'.
					'<td>Country</td>'.
					'</tr>';
		
			while($orgs = OrganisationGetNext($orgs)){
		
				$usersinorg = UserCountByOrganisationID($orgs->getID());
				
				if($usersinorg == 0) $totalzeros++;
				
				print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
				//print '<td><a id="editorg_'.$orgs->getID().'" href="#" > &nbsp;<img src="/trms-admin/images/edit_mini.gif"/></a></td>'; // ('.UserCountByOrganisationID($orgs->getID).')
				print '<td><a id="editorg_'.$orgs->getID().'" href="#" >' . $orgs->getOrgName() . '</a> ('. $usersinorg .') <input type="hidden" id="geoaddress_'.$orgs->getID().'" value="'.$orgs->getAddress1().'"/></td>'; 
				
				//print '<td>'.$orgs->getExternalID().'</td>';
				print '<td>'.$orgs->getLastUpdate().'</td>';
				print '<td id="geocity_'.$orgs->getID().'">' . $orgs->getCity() . '</td>'; 
				
				//print '<td>' .$orgs->getContactEMail() . '</td>';
				//print '<td>' .$orgs->getContactTel() . '</td>';
				
				if($orgs->getCountryID() == 215)
				print '<td>' . MunicipalityGetName($orgs->getMunicipalityID()) . '</td>';
				else
				print '<td></td>';
		
				if($orgs->getCountryID() != 215)
				print '<td>' . $orgs->getRegion() . '</td>';
				else
				print '<td>' . RegionGetName($orgs->getRegionID()) . '</td>';
				
				print '<td id="geocountry_'.$orgs->getID().'">' . CountryGetName($orgs->getCountryID()) . '</td>';
		 		print'</tr>';
		 
		 		$counter++;
			}
			print '<tr><td colspan="6">Total zeros: ' . $totalzeros . '</td></tr>';
		}
		else if($checkboxview == 1){
		
		/**
		 *  Get the Topics
		 */
		 print '<span id="condition" style="font-size:10px;font-style:italic">' .addslashes($sqlstr) . '</span><br/>';
		 
		 	$topics = TopicGetAllByStatus(1);
		 	$currenttopics = array();

			while($topics = TopicGetNext($topics)){
			$currenttopics[$topics->getID()] = $topics->getTopic();
			}

		
			$orgs = OrganisationGet($sqlstr);
			print 	'<tr><td>School/Organisation</td>';
			
			
			foreach($currenttopics as $key=>$key_value)
			print '<td>' . $key_value. '</td>';
			
			//'<td></td><td></td><td></td><td></td>';
			
			print 	'</tr>';
		
			while($orgs = OrganisationGetNext($orgs)){
		
				print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
				print '<td>' . $orgs->getOrgName() . ' / '. $orgs->getCity() . '</td>'; 
				
				foreach($currenttopics as $key=>$key_value){
				print '<td><input type="checkbox" class="checktopic" id="checktopic_'. $key .'x'.$orgs->getID().'"' . (OrganisationHasTopic($key,$orgs->getID())?"CHECKED":"") . ' /></td>';
				}
				print'</tr>';
		 
		 		$counter++;
			}
		}
		else if($checkboxview == 2){
			
			$orgs = OrganisationGet($sqlstr);
			print 	'<tr>'.
					'<td>School/Organisation</td>'.
					
					'<td class="nrw">Pending</td>'.
					'<td class="nrw">Approved</td>'.
					'<td class="nrw">Follow-up</td>'.
					'<td class="nrw">Incomplete</td>'.
					'<td class="nrw">Resting</td>'.
					'<td class="nrw">Confirmed</td>'.
					'<td class="nrw">Inactive</td>'.
					
					'<td></td>'.
					'<td></td>'.
					'<td></td>'.
					
					'</tr>';
		
			while($orgs = OrganisationGetNext($orgs)){
		
				$usersinorg = UserCountByOrganisationID($orgs->getID());
				
				if($usersinorg == 0) $totalzeros++;
				
				print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'" id="editrow_'.$orgs->getID().'" >';

				print '<td class="showrecord" id="showrecord_'.$orgs->getID().'">' . $orgs->getOrgName() . ' ('. $usersinorg .') <input type="hidden" id="geoaddress_'.$orgs->getID().'" value="'.$orgs->getAddress1().'"/></td>'; 
				
				print '<td><input type="checkbox" class="setstatus" id="status_1_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 1?"checked":"" ).'/></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_2_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 2?"checked":"" ).'//></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_4_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 4?"checked":"" ).'//></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_8_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 8?"checked":"" ).'//></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_16_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 16?"checked":"" ).'//></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_32_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 32?"checked":"" ).'//></td>'.
					  '<td><input type="checkbox" class="setstatus" id="status_64_'.$orgs->getID().'" value="" '.($orgs->getStatus() & 64?"checked":"" ).'//></td>';
					  
				print '<td id="geocity_'.$orgs->getID().'">' . $orgs->getCity() . '</td>'; 
				print '<td id="geocountry_'.$orgs->getID().'">' . CountryGetName($orgs->getCountryID()) . '</td>';
		 		print '<td><img class="editrow" src="/trms-admin/images/edit_mini.gif" id="editorg_'.$orgs->getID().'"/></td>';
		 		print '<td><img src="/trms-admin/images/delete_mini.gif" id="deleterow_'.$orgs->getID().'"/></td>';
		 		
		 		print'</tr>';
		 		
		 		print '<tr class="compare_org" id="compare_'.$orgs->getID().'"><td colspan="11"><div class="comparerecord" id="showorg_'.$orgs->getID().'" >...</div></td></tr>';
		 
		 		$counter++;
			}
		
		
		}
		
	/*******************************
	 * Build a query for individuals
	 *******************************/
	
	} else if( $stype == 2 ){
	
		if(isset($_REQUEST["recordname"]) && strlen($_REQUEST["recordname"]) > 2 &&  $_REQUEST["recordname"] != "Individual name"){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$recordname = $_REQUEST["recordname"];
			$sqlstr .= 'FullName LIKE "%' . $recordname . '%"';
			$arg++;
		}
	
		if(isset($_REQUEST["country_id"]) && $_REQUEST["country_id"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$countryid = $_REQUEST["country_id"];
			$sqlstr .= 'O.CountryID = ' . $countryid;
			$arg++;
		}
		
		if(isset($_REQUEST["typeid"]) && $_REQUEST["typeid"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$typeid = $_REQUEST["typeid"];
			$sqlstr .= 'Users.TypeID & ' . $typeid;
			$arg++;
		}
		
		if(isset($_REQUEST["organisationid"]) && $_REQUEST["organisationid"] != 0){
			if($arg > 0 ) $sqlstr .= ' AND ';
			$organisationid = $_REQUEST["organisationid"];
			$sqlstr .= 'Users.AddressID = ' . $organisationid;
			$arg++;
		}
		
		if($arg > 0){
		
		$sqlstr = ', Organisation AS O ' . $sqlstr . ' AND O.OrganisationID = Users.AddressID'; 
		
		
		}
		
		if($arg == 0) $sqlstr = '';
		
		print '<span id="condition" style="font-size:10px;font-style:italic">' .$sqlstr . '</span><br/>';
		$users = UserGet($sqlstr);
		print '<tr><td>Name</td><td>Organisation</td><td>Accountname</td><td>Lastlogin</td><td>Country</td>';
		
		while($users = UserGetNext($users)){
			
			$org = OrganisationGetByID($users->getAddressID());
			
			print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
			//print '<td><a id="editorg_'.$orgs->getID().'" href="#" > &nbsp;<img src="/trms-admin/images/edit_mini.gif"/></a></td>';
			print '<td><a id="edituser_'.$users->getID().'" href="#" >' . $users->getFullname() . ' ('. $users->getID() .')</a></td>'; 
			print '<td><a id="editorg_'.$org->getID().'" href="#" >' . $org->getOrgName() .  '</a></td>';
			//print '<td>' . $users->getAddressID() .  '</td>';
			print '<td>' . $users->getAccountName() . '</td>'; 
			//print '<td>' . MunicipalityGetName($orgs->getMunicipalityID()) . '</td>';

			print '<td>' . substr($users->getLastdate(),0,10). '</td>';
			//print '<td id="geocountry_'.$org->getID().'">' .  '</td>';
			print '<td id="geocountry_'.$org->getID().'">' . CountryGetName($org->getCountryID()) . '</td>';
		 	print'</tr>';
		 
		 	$counter++;
		}
	
	}
	print 	'<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
	print 	'<td colspan="12"><b>' . $counter . ' records in result</b></td></tr>';
	
	print '</table>';
	
	print 	"<input type='hidden' id='sqlstr' name='sqlstr' value='".$sqlstr."'/>"; 
	print 	'<input class="gf_button_sharp" type="button" id="export_to_xls" value="Export result to excel"/>';
	print 	'<div id="export_fields">'.
			'<h4>Check fields for export to excel</h4>' .
			'<form id="exportform">';
	if( $stype == 1 ){		
	print	'<input type="checkbox" id="orgid" name="orgid" value="1" checked/> ID '.
			'<input type="checkbox" id="orgname" name="orgname" value="1" checked/> Name '.
			'<input type="checkbox" id="students" name="students" value="1"/> Students ' .
			'<input type="checkbox" id="address1" name="address1" value="1"/> Street Address '.
			'<input type="checkbox" id="zipcode" name="zipcode" value="1" /> Zip Code '.
			'<input type="checkbox" id="city" name="city" value="1"/> City '.
			'<input type="checkbox" id="address2" name="address2" value="1"/> Postal Address '.
			'<input type="checkbox" id="invoiceaddress" name="invoiceaddress" value="1"/> Invoice Address '.
			'<input type="checkbox" id="municipality" name="municipality" value="1"/> Municipality '.
			'<input type="checkbox" id="region" name="region" value="1"/> Region '.
			'<input type="checkbox" id="country" name="country" value="1"/> Country '.
			'<input type="checkbox" id="contactname" name="contactname" value="1"/> Contact Name '.
			
			'<nobr><input type="checkbox" id="contacttel" name="contactemail" value="1"/> Contact Email</nobr> '.
			'<nobr><input type="checkbox" id="contacttel" name="contacttel" value="1"/> Contact Telephone</nobr> '.
			'<nobr><input type="checkbox" id="telephone" name="telephone" value="1"/> Telephone school</nobr> '.
			'<nobr><input type="checkbox" id="email" name="email" value="1"/> E-mail school</nobr> '.
			'<nobr><input type="checkbox" id="contactemails" name="contactemails" value="1"/> All contact e-mails</nobr>'.
			'<hr/>';
	
	print 	'<h4>Topics</h4>' .
			'<input type="checkbox" id="showtopics" name="showtopics" value="1"/> Show Active Topics '.
			'<hr/>';
			
	print 	'<h4>WCP Program data</h4>' .
			'<select name="year" id="year"><option value="0">Select year</option>'.
			'<option value="2018">2018</option>'.
			'<option value="2017">2017</option>'.
			'<option value="2016">2016</option>'.
			'<option value="2015">2015</option>'.
			'<option value="2014">2014</option>'.
			'<option value="2013">2013</option>'.
			'<option value="2012">2012</option>'.
			'</select>';
	}
	else if( $stype == 2 ){
	
	print	'<input type="checkbox" id="fullname" name="fullname" value="1" checked/> Name '.
			'<input type="checkbox" id="email" name="email" value="1"/> EMail/Accountname '.
			'<input type="checkbox" id="cell" name="cell" value="1" /> Cellphone '.
			'<input type="checkbox" id="orgname" name="orgname" value="1" /> Organisation ';
	}
			
	print	'<br/><br/>' .
			'<input class="gf_button_sharp" type="submit" id="download_xls_file" value="Download file"/>'. 
			'</form>' .
			'</div>';
	//print $_SERVER["QUERY_STRING"];
}

function deleteOrganisation($recordid){

	OrganisationDelete($recordid);
	print 'Deleted';
	
	//updateList($condition);
}

function deleteIndividual($userid){

	UserDelete($userid);
	print 'Deleted';
}

function updateList($sql){

		print '<span id="condition" style="font-size:10px;font-style:italic">' .$sql . '</span><br/>';

		$orgs = OrganisationGet($sql);
		
		print '<table>';
		print '<tr><td>School/Organisation</td><td>City</td><td>Municipality</td><td>Region</td><td>Country</td>';
		
		while($orgs = OrganisationGetNext($orgs)){
		
		print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
		//print '<td><a id="editorg_'.$orgs->getID().'" href="#" > &nbsp;<img src="/trms-admin/images/edit_mini.gif"/></a></td>';
		print '<td><a id="editorg_'.$orgs->getID().'" href="#" >' . $orgs->getOrgName() . '</a><input type="hidden" id="geoaddress_'.$orgs->getID().'" value="'.$orgs->getAddress1().'"/></td>'; 
		print '<td id="geocity_'.$orgs->getID().'">' . $orgs->getCity() . '</td>'; 

		print '<td>' . MunicipalityGetName($orgs->getMunicipalityID()) . '</td>';
		print '<td>' . RegionGetName($orgs->getRegionID()) . '</td>';
		print '<td id="geocountry_'.$orgs->getID().'">' . CountryGetName($orgs->getCountryID()) . '</td>';
		print'</tr>';
		 
		 $counter++;
		
		}
		
		print '</table>';


}

function showComments($recordid){

	print 'Comments under construction';

}

function wcpProgram($recordid){

	print 'wcp program under construction';

}


function specialProjects($recordid){

	print 'special projects under construction';

}


?>