<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["message"]))$message = $_REQUEST["message"];
if(isset($_REQUEST["uid"]))$uid = $_REQUEST["uid"];
if(isset($_REQUEST["gid"]))$gid = $_REQUEST["gid"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"];
if(isset($_REQUEST["accountname"]))$accountname = $_REQUEST["accountname"];
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print '<div id="content">';

if(isset($admin))
{
	global $action, $uid, $accountname;

		if( UserHasPrivilege($admin->getID(), 2) ){

		if($action == "saveUser")
			saveUser($uid);
		else if($action == "editUser") 
			editUser($uid);
		else if ($action == "createUser")
			createUser($accountname);
		else if ($action == "deleteUser")
			deleteUser($uid);
		else
			defaultAction();

	}else{
		print "No permission";
	}
}
else
{
	print "Please login!";
}

htmlEnd();


function defaultAction(){

	global $message, $gid;

	$counter = 0;

	$usergroup = UserGroupGetAll();

	print '<div class="stdbox_800">' .
		  '<form><input type="hidden" id="action" name="action" value="createUser">';
	
	if($message == "userexists" || $message == "noaccountname")
		print '<input type="input" id="accountname" name="accountname" value="' . $message . '" size="30"/>'; 
	else
		print '<input type="input" id="accountname" name="accountname" value="user'.(TermosGetParameterValue("UserIDCounter") + 1).'" size="30"/>';
	
	print '<input type="submit" title="'.TermosGetParameterValue("UserIDCounter").'" value="Create new user">';
	
	
	
	
	print ' &nbsp;<select id="usergroupid" name="gid">';
	print	'<option value="0">'. MTextGet("selectgroup") .'</option>';
	while($usergroup = UserGroupGetNext($usergroup)){
		print	'<option value="'.$usergroup->getID(). '" ' .($gid==$usergroup->getID()?"SELECTED":"").'>'.MTextGet($usergroup->getUserGroupNameTextID()).'</option>';
	}
	
	print '</select></form>';
	print '</div>';
	
	if($gid > 0){
		$selectedgroup = UserGroupGetByID($gid);
		print	'<div id="userlist_head">'. MTextGet("group") . ': ' . $selectedgroup->getName().'</div>';
	}
	else
		print	'<div id="userlist_head">'.MTextGet("users").'</div>';
	
	print '<div id="userlist">';
	if($gid > 0){
			  $allusers = UserGetAllInGroup($gid);
		      print '<table class="adminlist">';
			  while($allusers = UserGetNext($allusers)){
					$counter++;
					print '<tr>' . 
						  '<td><a href="/trms-admin/users.php?action=editUser&uid='. $allusers->getID().'&gid='.$gid.'">' . 
						  '<img src="images/edit_mini.gif" border="0" alt="edit user"/></a></td><td>'. $allusers->getFullname() . '</td>' . 
						  '<td>' . $allusers->getJobFunction() . '</td>' .
						  '<td>' . MTextGet(UserGetGroupTextID($allusers->getUserGroupID())) . '</td>' . 
						  '<td>'. UserGetCompanyName($allusers->getAddressID()) . '</td><td class="digit">' . $allusers->getLastdate() . '</td>' . 
						  '<td class="digit">' .  $allusers->getCounter() . '</td>' . 
						  '<td class="digit"><img src="images/delete_mini.gif" border="0" alt="Delete user" onclick="deleteUser(' .  $allusers->getID() . ', \'' .$allusers->getFullname() . '\')"/></td>' .
						  '</tr>';
			  } 
			  print '</table>';

			  print  'Antal: ' . $counter;
		  }
	print  '</div>';
}

function editUser($uid){
	
	global $gid;
	//$address = new Address;
	//$usergroup = new UserGroup;
	
	if( $user = UserGetUserByID($uid) )
	{
		print '<div id="editbox_head">';
		print htmlspecialchars(MTextGet("currentuser") . ': ' . $user->getFullname()).' <a title="userlist" href="/trms-admin/users.php?action=default&gid='.$gid.'"><img src="images/edit_mini.gif" border="0" alt="userlist"/></a>';
		print '</div>';

		print '<div id="editbox" class="clearfix">' .
			  '<form method="post">' .
			  '<div class="boxes">' .
			  '<tr><td class="third">' .
			  '<table>';
		
		print '<tr><td>UserID:</td><td><input type="hidden" id="uid" name="uid" value="'. $user->getID() .'"/>'. $user->getID() . '</td></tr>';
		
		print '<tr><td>Fullname:</td><td><input type="input" id="fullname" name="fullname" value="'. htmlspecialchars($user->getFullname()) .'" size="28"/></td></tr>' .
			  '<tr><td>Organisation:</td><td><select name="addressid">';

		$address = AddressGetAll();
		while($address = AddressGetNext($address))
		{
			if($user->getAddressID() == $address->getID())
				print '<option value="'. $address->getID().'" SELECTED>'.$address->getCompanyName().'</option>';
			else
				print '<option value="'. $address->getID().'">'.$address->getCompanyName().'</option>';
		}

		print '</select></td></tr>' .
		      '<tr><td>Aliasname:</td><td><input type="input" id="aliasname" name="aliasname" value="'. htmlspecialchars($user->getAliasname()) .'" size="28"/></td></tr>' .
		      '<tr><td>Jobfunction:</td><td><input type="input" id="jobfunction" name="jobfunction" value="'. $user->getJobfunction() .'" size="28"/></td></tr>';

		print '<tr><td>Group:</td><td><select name="usergroupid">'; 

		$usergroup = UserGroupGetAll();
		while($usergroup = UserGroupGetNext($usergroup))
		{
			if ($user->getUserGroupID() == $usergroup->getID())
			print '<option value="'. $usergroup->getID().'" SELECTED>'. MTextGet($usergroup->getUserGroupNameTextID()) .'</option>';
			else
			print '<option value="' . $usergroup->getID() . '">'. MTextGet($usergroup->getUserGroupNameTextID()) .'</option>';
		}
		
		print '</select></td></tr>';
		
		print '<tr><td>Email:</td><td><input type="input" id="email" name="email" value="'. $user->getEMail() .'" size="28"/></td></tr>' .
		      '<tr><td>Tel:</td><td><input type="input" id="tel" name="tel" value="'. $user->getTel() .'" size="28"/></td></tr>' .
		      '<tr><td>Cell:</td><td><input type="input" id="cell" name="cell" value="'. $user->getCell() .'" size="28"/></td></tr>' .
			  '<tr><td>Account:</td><td>'. $user->getAccountname() . '</td></tr>' .
		      '<tr><td>Password:</td><td><input type="password" id="password" name="password" value="********"/></td></tr>' .
		      '<tr><td>Address:</td><td><input type="input" id="address" name="address" value="'. $user->getAddress() .'" size="28"/></td></tr>' .
		      '<tr><td>Zip:</td><td><input type="input" id="zip" name="zip" value="'. $user->getZip() .'" size="28"/></td></tr>' .
		      '<tr><td>City</td><td><input type="input" id="city" name="city" value="'. $user->getCity() .'" size="28"/></td></tr>' .
		      '<tr><td><input type="hidden" id="action" name="action" value="saveUser"></td><td><input type="submit" value="Save"></td></tr>' .
		      '</table>';

		//print '<tr><td>Fax:</td><td>'. $user->getFax() . '</td></tr>\n";
		//print '<tr><td>CountryID:</td><td>'. $user->getCountryID() . '</td></tr>\n";
		//print '<tr><td>Approved:</td><td>'. $user->getApproved() . '</td></tr>\n";
		//print '<tr><td>Anonymous:</td><td>'. $user->getAnonymous() . '</td></tr>\n";
		//print '<tr><td>URL:</td><td>'. $user->getURL() . '</td></tr>\n";
		//print '<tr><td>RegionID:</td><td>'. $user->getRegionID() . '</td></tr>\n";
		//print '<tr><td>MunicipalityID:</td><td>'. $user->getMunicipalityID() . '</td></tr>\n";
		//print '<tr><td>CompanyFlag:</td><td>'. $user->getCompanyflag() . '</td></tr>\n";
		//print '<tr><td>Last message:</td><td>'. $user->getLastmessage() . '</td></tr>\n";
		//print '<tr><td>Birthdate:</td><td>'. $user->getBirthdate() . '</td></tr>\n";
		//print '<tr><td>Infoflag:</td><td>'. $user->getInfoflag() . '</td></tr>\n";
		//print '<tr><td>Infotext:</td><td><textarea id="infotext" name="infotext" rows="6" cols="40">'. $user->getInfotext() . '</textarea></td></tr>\n";
		
		print '</div><div class="boxes">' .
		      '<table>' .
			  '<tr><td>Image:</td><td class="digit"><img src="images/gubbe.gif" width="87" height="87"/></td></tr>' .
			  '<tr><td colspan="2">Infotext:<br/><textarea id="infotext" name="infotext" rows="6" cols="28">'. $user->getInfotext() . '</textarea><hr class="line"/></td></tr>' .
	          '<tr><td>Last date:</td><td>'. $user->getLastdate() . '</td></tr>' .
		      '<tr><td>Previous date:</td><td>'. $user->getPreviousdate() . '</td></tr>' .
		      '<tr><td>Counter:</td><td>'. $user->getCounter() . '</td></tr>' .
		      '<tr><td>Createddate:</td><td>'. $user->getCreateddate() . '</td></tr>';

		print '</table>';
		print '</div>' .
			  '<div class="boxes"><h4>'. MTextGet("privcategories") .'</h4>';

		$priv = PrivilegeGetAll();
		while($priv = PrivilegeGetNext($priv))
		{
			if(UserHasPrivilege($uid, $priv->getID()))
				print '<input type="checkbox" name="privilege[]"  value="' . $priv->getID() . '" checked/>&nbsp;&nbsp;' . MTextGet($priv->getPrivilegeNameTextID()) . '<br/>';
			else
				print '<input type="checkbox" name="privilege[]"  value="' . $priv->getID() . '"/>&nbsp;&nbsp;' . MTextGet($priv->getPrivilegeNameTextID()) . '<br/>';
		}
		
		print	'</div>' .
				'</form>' .
				'</div>';

	}else{
		print "Error in function editUser(). No user with uid " . $uid;

	}
}

function saveUser($uid){
	global $dbcnx;
	if( $user = UserGetUserByID($uid) )
	{
		if(isset($_REQUEST["addressid"]))$addressid = $_REQUEST["addressid"];
		if(isset($_REQUEST["usergroupid"]))$usergroupid = $_REQUEST["usergroupid"];
		if(isset($_REQUEST["fullname"]))$fullname = $_REQUEST["fullname"];
		if(isset($_REQUEST["aliasname"]))$aliasname = $_REQUEST["aliasname"];
		if(isset($_REQUEST["email"]))$email = $_REQUEST["email"];
		if(isset($_REQUEST["password"]))$password = $_REQUEST["password"];
		if(isset($_REQUEST["address"]))$address = $_REQUEST["address"];
		if(isset($_REQUEST["zip"]))$zip = $_REQUEST["zip"];
		if(isset($_REQUEST["city"]))$city = $_REQUEST["city"];
		if(isset($_REQUEST["cell"]))$cell = $_REQUEST["cell"];
		if(isset($_REQUEST["jobfunction"]))$jobfunction = $_REQUEST["jobfunction"];
		if(isset($_REQUEST["infotext"]))$infotext = $_REQUEST["infotext"];
			
		$user->setAddressID($addressid);
		$user->setUserGroupID($usergroupid);
		$user->setFullname($fullname);
		$user->setAliasname($aliasname);
		$user->setEMail($email);
		if($password != "********") $user->setPassword(crypt($password, LEGAL_SALT));
		$user->setAddress($address);
		$user->setZip($zip);
		$user->setCity($city);
		$user->setCell($cell);
		$user->setJobfunction($jobfunction);
		$user->setInfotext($infotext);

		UserSave($user);

		

		if(isset($_REQUEST["privilege"]))$privilege = $_REQUEST["privilege"];
		$sqlstr = "DELETE FROM UserPrivCategories WHERE UserID = " . $uid;
		 mysqli_query($dbcnx, $sqlstr);

		for($a=0;$a<count($privilege);$a++){
			$sqlstr = "INSERT INTO UserPrivCategories(PrivCategoryID, UserID) VALUES(". $privilege[$a] .", ". $uid .")";
			 mysqli_query($dbcnx, $sqlstr);
		}

		editUser($uid);

	}
	else
	{
		print "No user with " . $uid;
	}

}

function createUser(){
	

	global $admin, $action, $uid, $gid, $accountname;

	//print $accountname; 

	if(strlen($accountname))
	{
		
		if($user = UserGetUserByAccountName($accountname))
			print "User accountname already exists";
			//header("Location: /trms-admin/users.php?action=default&message=userexists");
		else{
			$user = new User;
			
			$user->setFullname("Insert name!");
			$user->setAddressID($admin->getAddressID());
			$user->setUserGroupID($gid);
			$user->setEmail($accountname);
			$user->setAccountname($accountname);
			$userid = UserSave($user);

			//if($user = UserGetUserByAccountName($accountname))
			editUser($userid);
		}
	}
	else
		header("Location: /trms-admin/users.php?action=default&message=noaccountname");
}

function deleteUser($uid){

	UserDelete($uid);
	defaultAction();
}

function htmlStart(){

	print '<!DOCTYPE html>' .
		  '<html>' .
		  '<head>' .
		  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
		  '<title>Termos Users</title>' .
		  '<link rel="stylesheet" href="./css/termosadmin.css"/>' .
		  '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.
		  '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
		  '<script type="text/javascript" src="./js/user_admin.js"></script>' .
		  '<script>' .
		  'function deleteUser(userid, username){' .
		  '  if(confirm("' . (MTextGet("deleteUser")) . ' for " + username + "?"))' .
		  '	location.href = "users.php?action=deleteUser&uid=" + userid ' .
		  '}' .
		  '</script>' .
		  '</head>' .
		  '<body>';
}

function htmlEnd(){
	
	print '</div>' .
		  '</body>' .
		  '</html>';
}