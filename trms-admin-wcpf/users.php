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
if(isset($_REQUEST["accountname"]))$accountname = $_REQUEST["accountname"];
if(isset($_REQUEST["usergroupid"]))$usergroupid = $_REQUEST["usergroupid"]; else $usergroupid = 11;;

DBcnx();

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

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
		print "User has not privilege: 2." . PrivilegeGetName(2);
	}
}
else
{
	print "Please login!";
}

htmlEnd();


function defaultAction(){

	global $message, $usergroupid;

	print "<div class=\"stdbox_800\"><form><select name='usergroupid' class='std_select'><option value=''>".MTextGet("select_usergroup")."</option>" ;
	
	$usergroup = UserGroupGetAll();
	while($usergroup = UserGroupGetNext($usergroup)){
		if($usergroup->getID()==$usergroupid)
		print "<option value=\"".$usergroup->getID()."\" SELECTED>" . MTextGet($usergroup->getUserGroupNameTextID()) . '</option>';
		else
		print "<option value=\"".$usergroup->getID()."\">" . MTextGet($usergroup->getUserGroupNameTextID()) . '</option>';
	}
	 
	print "</select> <input class='std_button' type='submit' value=\"Show\"/></form> ";
		  
	print "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createUser\">";

	if($message == "userexists" || $message == "noaccountname")
		print "<input type=\"input\" class=\"std_input\" id=\"accountname\" name=\"accountname\" value=\"" . $message . "\" size=\"30\"/>"; 
	else
		print "<input type=\"input\" class=\"std_input\" id=\"accountname\" name=\"accountname\" size=\"30\"/>";
	
	print " <input type=\"submit\" class=\"std_button\" value=\"Create new user\"></form>" .
	      "</div>" .
		  "<div id=\"userlist_head\">All users" .
		  "</div>\n" .
		  "<div id=\"userlist\">" .
		  "<table class=\"adminlist\">\n";

		 // $allusers = UserGetAll();
		 
		 
		 $allusers = UserGetAllInGroup($usergroupid);

		  while($allusers = UserGetNext($allusers))
		  {
				print "<tr>\n" . 
					  "<td><a href=\"/trms-admin/users.php?action=editUser&uid=". $allusers->getID()."\">" . 
					  "<img src=\"images/edit_mini.gif\" border=\"0\" alt=\"edit user\"/></a></td>\n<td>". $allusers->getFullname() . "</td>\n" . 
					  "<td>".$allusers->getUserGroupID()."</td>\n" . 
					  //"<td>" . MTextGet(UserGetGroupTextID($allusers->getUserGroupID())) . "</td>\n" . 
					 // "<td>".$allusers->getAddressID()."</td>\n" .
					  "<td>". UserGetOrganisationName($allusers->getAddressID()) . "</td>\n" .
					  "<td class=\"digit\">" . $allusers->getLastdate() . "</td>\n" . 
					  "<td class=\"digit\">" .  $allusers->getCounter() . "</td>\n" . 
					  "<td class=\"digit\"><img src=\"images/delete_mini.gif\" border=\"0\" alt=\"Delete user\" onclick=\"deleteUser(" .  $allusers->getID() . ", '" .$allusers->getFullname() . "')\"/></td>\n" .
					  "</tr>\n";
		  } 
		
	print "</table>\n" .
	      "</div>\n";

}

function editUser($uid){

	//$address = new Address;
	//$usergroup = new UserGroup;
	
	if( $user = UserGetUserByID($uid) )
	{
		print "<div id=\"editbox_head\">";
		print htmlspecialchars($user->getFullname())." <a title=\"userlist\" href=\"/trms-admin/users.php?action=default\"><img src=\"images/edit_mini.gif\" border=\"0\" alt=\"userlist\"/></a>";
		print "</div>\n";

		print "<div id=\"editbox\" class=\"clearfix\">\n" .
			  "<form method=\"post\">" .
			  "<div class=\"boxes\">\n" .
			  "<tr><td class=\"third\">" .
			  "<table>\n";
		
		print "<tr><td>UserID:</td><td><input type=\"hidden\" id=\"uid\" name=\"uid\" value=\"". $user->getID() ."\"/>". $user->getID() . "</td></tr>\n";
		
		print "<tr><td>Fullname:</td><td><input type=\"input\" id=\"fullname\" name=\"fullname\" value=\"". htmlspecialchars($user->getFullname()) ."\" size=\"28\"/></td></tr>\n" .
			  "<tr><td>Organisation:</td><td><select name=\"addressid\">\n";

		$address = AddressGetAll();
		while($address = AddressGetNext($address))
		{
			if($user->getAddressID() == $address->getID())
				print "<option value=\"". $address->getID()."\" SELECTED>".$address->getCompanyName()."</option>\n";
			else
				print "<option value=\"". $address->getID()."\">".$address->getCompanyName()."</option>\n";
		}

		print "</select>\n</td></tr>\n" .
		      "<tr><td>Aliasname:</td><td><input type=\"input\" id=\"aliasname\" name=\"aliasname\" value=\"". htmlspecialchars($user->getAliasname()) ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td>Jobfunction:</td><td><input type=\"input\" id=\"jobfunction\" name=\"jobfunction\" value=\"". $user->getJobfunction() ."\" size=\"28\"/></td></tr>\n";

		print "<tr><td>Group:</td><td><select name=\"usergroupid\">\n"; 

		$usergroup = UserGroupGetAll();
		while($usergroup = UserGroupGetNext($usergroup))
		{
			if ($user->getUserGroupID() == $usergroup->getID())
			print "<option value=\"". $usergroup->getID()."\" SELECTED>". MTextGet($usergroup->getUserGroupNameTextID()) ."</option>\n";
			else
			print "<option value=\"" . $usergroup->getID() . "\">". MTextGet($usergroup->getUserGroupNameTextID()) ."</option>\n";
		}
		
		print "</select></td></tr>\n";
		
		print "<tr><td>Email:</td><td><input type=\"input\" id=\"email\" name=\"email\" value=\"". $user->getEMail() ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td>Tel:</td><td><input type=\"input\" id=\"tel\" name=\"tel\" value=\"". $user->getTel() ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td>Cell:</td><td><input type=\"input\" id=\"cell\" name=\"cell\" value=\"". $user->getCell() ."\" size=\"28\"/></td></tr>\n" .
			  "<tr><td>Account:</td><td>". $user->getAccountname() . "</td></tr>\n" .
		      "<tr><td>Password:</td><td><input type=\"password\" id=\"password\" name=\"password\" value=\"********\"/></td></tr>\n" .
		      "<tr><td>Address:</td><td><input type=\"input\" id=\"address\" name=\"address\" value=\"". $user->getAddress() ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td>Zip:</td><td><input type=\"input\" id=\"zip\" name=\"zip\" value=\"". $user->getZip() ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td>City</td><td><input type=\"input\" id=\"city\" name=\"city\" value=\"". $user->getCity() ."\" size=\"28\"/></td></tr>\n" .
		      "<tr><td><input type=\"hidden\" id=\"action\" name=\"action\" value=\"saveUser\"></td><td><input type=\"submit\" value=\"Save\"></td></tr>\n" .
		      "</table>\n";

		//print "<tr><td>Fax:</td><td>". $user->getFax() . "</td></tr>\n";
		//print "<tr><td>CountryID:</td><td>". $user->getCountryID() . "</td></tr>\n";
		//print "<tr><td>Approved:</td><td>". $user->getApproved() . "</td></tr>\n";
		//print "<tr><td>Anonymous:</td><td>". $user->getAnonymous() . "</td></tr>\n";
		//print "<tr><td>URL:</td><td>". $user->getURL() . "</td></tr>\n";
		//print "<tr><td>RegionID:</td><td>". $user->getRegionID() . "</td></tr>\n";
		//print "<tr><td>MunicipalityID:</td><td>". $user->getMunicipalityID() . "</td></tr>\n";
		//print "<tr><td>CompanyFlag:</td><td>". $user->getCompanyflag() . "</td></tr>\n";
		//print "<tr><td>Last message:</td><td>". $user->getLastmessage() . "</td></tr>\n";
		//print "<tr><td>Birthdate:</td><td>". $user->getBirthdate() . "</td></tr>\n";
		//print "<tr><td>Infoflag:</td><td>". $user->getInfoflag() . "</td></tr>\n";
		//print "<tr><td>Infotext:</td><td><textarea id=\"infotext\" name=\"infotext\" rows=\"6\" cols=\"40\">". $user->getInfotext() . "</textarea></td></tr>\n";
		
		print "</div>\n<div class=\"boxes\">\n" .
		      "<table>\n" .
			  "<tr><td>Image:</td><td class=\"digit\"><img src=\"images/gubbe.gif\" width=\"87\" height=\"87\"/></td></tr>\n" .
			  "<tr><td colspan=\"2\">Infotext:<br/><textarea id=\"infotext\" name=\"infotext\" rows=\"6\" cols=\"28\">". $user->getInfotext() . "</textarea><hr class=\"line\"/></td></tr>\n" .
	          "<tr><td>Last date:</td><td>". $user->getLastdate() . "</td></tr>\n" .
		      "<tr><td>Previous date:</td><td>". $user->getPreviousdate() . "</td></tr>\n" .
		      "<tr><td>Counter:</td><td>". $user->getCounter() . "</td></tr>\n" .
		      "<tr><td>Createddate:</td><td>". $user->getCreateddate() . "</td></tr>\n";

		print "</table>\n";
		print "</div>\n" .
			  "<div class=\"boxes\">\n";

		$priv = PrivilegeGetAll();
		while($priv = PrivilegeGetNext($priv))
		{
			if(UserHasPrivilege($uid, $priv->getID()))
				print "<input type=\"checkbox\" name=\"privilege[]\"  value=\"" . $priv->getID() . "\" checked/>&nbsp;&nbsp;" . MTextGet($priv->getPrivilegeNameTextID()) . "<br/>\n";
			else
				print "<input type=\"checkbox\" name=\"privilege[]\"  value=\"" . $priv->getID() . "\"/>&nbsp;&nbsp;" . MTextGet($priv->getPrivilegeNameTextID()) . "<br/>\n";
		}
		
		print	"</div>\n" .
				"</form>\n" .
				"</div>\n";

	}else{
		print "No user with uid " . $uid;

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

	global $action, $uid, $accountname;

	if($accountname)
	{
		if($user = UserGetUserByAccountName($accountname))
			header("Location: /trms-admin/users.php?action=default&message=userexists");
		else{
			$user = new User;
			
			$user->setFullname("Insert name!");
			$user->setEmail($accountname);
			$user->setAccountname($accountname);
			UserSave($user);

			if($user = UserGetUserByAccountName($accountname))
			editUser($user->getID());
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

	print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
		  "<html>\n" .
		  "<head>\n" .
		  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
		  "<title>Termos Users</title>\n" .
		  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
		  "<script>\n" .
		  "function deleteUser(userid, username){\n" .
		  "	if(confirm('" . MTextGet("deleteUser") . " for ' + username + '?'))\n" .
		  "	location.href = 'users.php?action=deleteUser&uid=' + userid;\n" .
		  "}\n" .
		  "</script>" .
		  "</head>\n" .
		  "<body>\n";
}

function htmlEnd(){
	
	print "</div>\n" .
		  "</body>\n" .
		  "</html>\n";
}