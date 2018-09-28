<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];
if(isset($_REQUEST["userid"]))$userid = $_REQUEST["userid"];
if(isset($_REQUEST["usrdetail"]))$userdetail = $_REQUEST["usrdetail"];
if(isset($_REQUEST["usrdetailvalue"]))$userdetailvalue = $_REQUEST["usrdetailvalue"];
if(isset($_REQUEST["accountname"]))$accountname = $_REQUEST["accountname"];
if(isset($_REQUEST["fullname"]))$fullname = $_REQUEST["fullname"];


if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){

	if($action == "listusers")
			listUsers($recordid);
	else if($action == "edituser")
			editUser($userid, $recordid);
	else if($action == "viewuser")
			viewUser($userid);
	else if($action == "createnewuser")
			createNewUser();
	else if($action == "savenewuser")		
			saveNewUser($fullname, $accountname, $recordid);
	else if($action == "saveuserdetail")
			saveUserDetail($userid, $userdetail, $userdetailvalue);
	else if($action == "deleteuser"){
			UserDelete($userid);
			listUsers($recordid);
	}		
	else if($action == "evaluateemail")
			evaluateEmail($accountname, $recordid);
	else	listUsers($recordid);
}

function listUsers($recordid){

	print '<div id="wcp_usr_list">';
	print '<h3>Individuals connected to ' . OrganisationGetName($recordid) .'</h3>';
	print '<div id="wcp_edit_user"></div>';
	
	$usr = UserGetAllInOrganisation($recordid);
	
	print '<table id="tbl_usr_list">';
	
	print '<tr id="tbl_caption">'.
		  '<td></td>' .
		  '<td>Name</td>'.
		 // '<td>UserGroup</td>' .
		  '<td>AccountName</td>'.
		  '<td>Phone</td>'.
		  '<td>Last date</td>'.
		  '<td>Logins</td>'.
		  '<td></td>'.
		  '</tr>';
	
	while($usr = UserGetNext($usr)){
		$row = 0;
		$row++;
		if($row % 2)
		print 	'<tr class="usr_row_even" id="usr_'.$usr->getID().'">';
		else
		print 	'<tr class="usr_row" id="usr_'.$usr->getID().'">';
		
	
		
		print 	'<td><img class="editrow" src="/trms-admin/images/icon_edit.gif" id="usr_'.$usr->getID().'x'.$usr->getAddressID().'"/></td>'.
				'<td>'.$usr->getFullname() . '</td>'.
				//'<td>'.$usr->getUserGroupID().'</td>'.
				'<td>'.$usr->getAccountName().'</td>'.
				'<td>'.$usr->getCell().'</td>'.
				'<td>'.$usr->getLastDate().'</td>'.
				'<td>'.$usr->getCounter().'</td>'.
				'<td><img class="editrow" src="/trms-admin/images/icon_delete.png" width="10" height="14" id="usrdelete_'.$usr->getID().'x'.$usr->getAddressID().'"/></td>'.
				'</tr>';
	}
	print '</table>';
	
	print '<input type="button" class="gf_button_right" id="createuser" value="+ Add new individual"/>';
	print '</div>';
}

function editUser($userid, $recordid){

$user = UserGetUserByID($userid);
$org = OrganisationGetByID($recordid);

print '<div id="wcp_edit_userhead">View & Edit: '.$user->getID().'<span id="wcp_close_useredit"> X </span></div>';

print 	'<div class="usr_column_3">';

print 	'<label class="lb">Name</label><br/>';
print	'<input class="usr_input_edit" id="usrname_'.$user->getID().'" value="'.$user->getFullname().'"/>';

print 	'<label class="lb">Jobfunction</label><br/>';
print	'<input class="usr_input_edit" id="usrjobfunction_'.$user->getID().'" value="'.$user->getJobfunction().'"/><br/>';

print 	'<label class="lb">First E-Mail/AccountName</label><br/>';
print	'<input class="usr_input_edit" id="usraccount_'.$user->getID().'" value="'.$user->getAccountName().'" />';

print 	'<label class="lb">Alternate E-Mail</label><br/>';
print	'<input class="usr_input_edit" id="usremail_'.$user->getID().'" value="'.$user->getEMail().'" />';

print 	'<label class="lb">Telephone</label><br/>';
print	'<input class="usr_input_edit" id="usrtel_'.$user->getID().'" value="'.$user->getTel().'"/>';

print 	'<label class="lb">Mobile</label><br/>';
print	'<input class="usr_input_edit" id="usrcell_'.$user->getID().'" value="'.$user->getCell().'"/>';


print 	'</div>';
print 	'<div class="usr_column_3">';

print 	'<label class="lb">Address ( If other than '. $org->getOrgName().' ´s address )</label><br/>';
print	'<input class="usr_input_edit" id="usraddress_'.$user->getID().'" value="'.$user->getAddress().'"/><br/>';

print 	'<label class="lb">Zip</label><br/>';
print	'<input class="usr_input_edit" id="usrzip_'.$user->getID().'" value="'.$user->getZip().'"/><br/>';

print 	'<label class="lb">City</label><br/>';
print	'<input class="usr_input_edit" id="usrcity_'.$user->getID().'" value="'.$user->getCity().'"/>';

print 	'<label class="lb">Country ( If other than '. $org->getOrgName().' ´s country )</label><br/>';

if($user->getCountryID() == 0)
PrintGenericSelect('SELECT country_id, short_name FROM country_t', 'usr_select_edit', 'usrcountry_' . $user->getID(), 'Select country', $org->getCountryID());
else
PrintGenericSelect('SELECT country_id, short_name FROM country_t', 'usr_select_edit', 'usrcountry_' . $user->getID(), 'Select country', $user->getCountryID());

print 	'</div>';
print 	'<div class="usr_column_3">';
print 	'<label class="lb">Created date</label><br/>';
print 	 $user->getCreatedDate() . '<br/>';
print 	'<label class="lb">Last login</label><br/>';
print 	 $user->getLastDate() . '<br/>';
print 	'<label class="lb">Previous date</label><br/>';
print 	 $user->getPreviousDate() . '<br/>';
print 	'<label class="lb">No of logins</label><br/>';
print 	 $user->getCounter() . '<br/>';
print 	'</div>';
		
}

function createNewUser(){

print 	'<div id="wcp_edit_userhead">Create new individual<span id="wcp_close_useredit"> X </span></div>';

print 	'<div class="usr_column_6">'.
		
		'<input type="text" class="usr_input_new" id="fullname"  value="Fullname"/> ' .
		'<input type="text" class="usr_input_new" id="accountname" name="accountname" value="Email/Accountname"/> '.
		'<input type="button" class="gf_button_right2" id="savenewuser" value="Create/Save"/>'.
		'<div id="tooltip"></div>';

print 	'</div>';
}

function saveNewUser($fullname, $accountname, $recordid){

	$newuser = new User;
	$newuser->setFullname($fullname);
	$newuser->setAccountName($accountname);
	$newuser->setEmail($accountname);
	$newuser->setAddressID($recordid);
	// we assume that this is a Contact person for a GF-school
	$newuser->setTypeID(131072);
	
	$newuserid = UserSave($newuser);
	
	editUser($newuserid, $recordid);

}

function saveUserDetail($userid, $userdetail, $userdetailvalue){

	$user = UserGetUserByID($userid);
	
	print $userid . ' ' . $userdetail . ' ' . $userdetailvalue;
	
	switch($userdetail){
			case 'usrname':
			$user->setFullname($userdetailvalue);
			break;
			case 'usrjobfunction':
			$user->setJobFunction($userdetailvalue);
			break;
			case 'usraccount':
			$user->setAccountName($userdetailvalue);
			break;
			case 'usremail':
			$user->setEMail($userdetailvalue);
			break;
			case 'usrtel':
			$user->setTel($userdetailvalue);
			break;
			case 'usrcell':
			$user->setCell($userdetailvalue);
			break;
			case 'usraddress':
			$user->setAddress($userdetailvalue);
			break;
			case 'usrzip':
			$user->setZip($userdetailvalue);
			break;
			case 'usrcity':
			$user->setCity($userdetailvalue);
			break;
			case 'usrcountry':
			$user->setCountryID($userdetailvalue);
			break;
	}
	
	UserSave($user);
}

function evaluateEmail($accountname, $recordid){

	$user = UserGetUserByAccountName($accountname);
	if(is_object($user))
	print $accountname . ' already exists';


}


?>
