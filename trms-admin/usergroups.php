<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["message"]))$message = $_REQUEST["message"];
if(isset($_REQUEST["gid"]))$gid = $_REQUEST["gid"];
if(isset($_REQUEST["groupname"]))$groupname = $_REQUEST["groupname"];
DBCnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $gid, $groupname;

	if($action == "editUserGroup") 
		editUserGroup($gid);
	else if ($action == "createUserGroup")
		createUserGroup($groupname);
	else if ($action == "deleteUserGroup")
		deleteUserGroup($gid);
	else
		defaultAction();
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print "<div class=\"stdbox_600\">" .
		  "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createUserGroup\">";

	print "<input type=\"input\" id=\"groupname\" name=\"groupname\" size=\"30\"/>";
	
	print "<input type=\"submit\" value=\"Create new usergroup\"></form>" .
	      "</div>" .
		  "<div id=\"userlist_head\">All usergroups" .
		  "</div>\n" .
		  "<div id=\"userlist\">" .
		  "<table class=\"adminlist\">\n";

		  $allgroups = UserGroupGetAll();

		  while($allgroups = UserGroupGetNext($allgroups))
		  {
				
				print "<tr>\n" . 
					  "<td><a href=\"/trms-admin/usergroups.php?action=editUserGroup&gid=". $allgroups->getID()."\">" . 
					  "<img src=\"images/edit_mini.gif\" border=\"0\" alt=\"edit user\"/></a></td>\n<td>".  MTextGet($allgroups->getUserGroupNameTextID()) . "</td>\n" . 
					   "<td class=\"digit\"><img src=\"images/delete_mini.gif\" border=\"0\" alt=\"Delete usergroup\" onclick=\"deleteUserGroup(" .  $allgroups->getID() . ", '" . MTextGet($allgroups->getUserGroupNameTextID()) . "')\"/></td>\n" .
					  "</tr>\n";
		  } 
		
	print "</table>\n" .
	      "</div>\n";


}

function editUserGroup($gid){

	$grp =  UserGroupGetByID($gid);

	print "<div id=\"editbox_head\">Usergroup: <a href=\"/trms-admin/mtext.php?action=edit&textid=" . $grp->getUserGroupNameTextID() . "\" title=\"Edit the name of this group\">" . MTextGet($grp->getUserGroupNameTextID()) . "</a> <a title=\"usergrouplist\" href=\"/trms-admin/usergroups.php?action=default\"><img src=\"images/edit_mini.gif\" border=\"0\" alt=\"usergrouplist\"/></a>";
	print "</div>";
	print "<div id=\"editbox\">Users in this group:<br/><br/>\n";

	$users = UserGetAllInGroup($gid);
	while($users = UserGetNext($users))
	{
		print $users->getFullname() . "<br/>";
	}

	print "</div>";
}

function createUserGroup($groupname){

	$mtext = MTextNewInCategory("partnerCategori", $groupname);
	$group = new UserGroup;

	$group->setUserGroupNameTextID($mtext->getID());
	UserGroupSave($group);

	defaultAction();

}

function deleteUserGroup($gid){

	UserGroupDelete($gid);
	defaultAction();
}


function htmlStart(){

    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
	  "<html>\n" .
	  "<head>\n" .
	  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
	  "<title>Termos UserGroups</title>\n" .
	  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
	  "<script>\n" .
	  "function deleteUserGroup(groupid, groupname){\n" .
	  "	if(confirm('" . MTextGet("deleteUserGroup") . " ' + groupname + '?'))\n" .
	  "	location.href = 'usergroups.php?action=deleteUserGroup&gid=' + groupid;\n" .
	  "}\n" .
	  "</script>" .
	  "</head>\n" .
	  "<body>\n";
}

function htmlEnd(){
	
    print "</div>" .
	  "</body>\n" .
	  "</html>\n";

}