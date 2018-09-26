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
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["message"]))$message = $_REQUEST["message"];
if(isset($_REQUEST["prid"]))$prid = $_REQUEST["prid"];
if(isset($_REQUEST["privname"]))$privname = $_REQUEST["privname"];
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $prid, $privname;

	if($action == "editPrivilege") 
		editPrivilege($prid);
	else if ($action == "createPrivilege")
		createPrivilege($privname);
	else if ($action == "deletePrivilege")
		deletePrivilege($prid);
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
		  "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createPrivilege\">";

	print "<input type=\"input\" id=\"privname\" name=\"privname\" size=\"30\"/>";
	
	print "<input type=\"submit\" value=\"Create new privilege\"></form>" .
	      "</div>" .
		  "<div id=\"userlist_head\">All privileges" .
		  "</div>\n" .
		  "<div id=\"userlist\">" .
		  "<table class=\"adminlist\">\n";

		  $allprivileges = PrivilegeGetAll();

		  while($allprivileges = PrivilegeGetNext($allprivileges))
		  {
				
				print "<tr>\n" . 
					  "<td><a href=\"/trms-admin/privileges.php?action=editPrivilege&prid=". $allprivileges->getID()."\">" . 
					  "<img src=\"images/edit_mini.gif\" border=\"0\" alt=\"edit user\"/></a></td>\n<td> ". $allprivileges->getID() .". ".  MTextGet($allprivileges->getPrivilegeNameTextID()) . "</td>\n" . 
					   "<td class=\"digit\"><img src=\"images/delete_mini.gif\" border=\"0\" alt=\"Delete user\" onclick=\"deletePrivilege(" .  $allprivileges->getID() . ", '" . MTextGet($allprivileges->getPrivilegeNameTextID()) . "')\"/></td>\n" .
					  "</tr>\n";
		  } 
		
	print "</table>\n" .
	      "</div>\n";


}

function editPrivilege($prid){

	$priv = PrivilegeGetByID($prid);
	print "<div id=\"editbox_head\">Privilege: <a href=\"/trms-admin/mtext.php?action=edit&textid=" . $priv->getPrivilegeNameTextID() . "\" title=\"Edit the name of this privilege\">" . MTextGet($priv->getPrivilegeNameTextID()) . "</a> <a title=\"privilegelist\" href=\"/trms-admin/privileges.php?action=default\"><img src=\"images/edit_mini.gif\" border=\"0\" alt=\"privilegelist\"/></a>";
	print "</div>";
	print "<div id=\"editbox\">Users having this privilege:<br/><br/>\n";

	$users = UserGetAllWithPrivilege($prid);
	while($users = UserGetNext($users))
	{
		print $users->getFullname() . "<br/>";
	}

	print "</div>";
}

function createPrivilege($privname){

	$mtext = MTextNewInCategory("privCategories", $privname);
	$privilege = new Privilege;

	$privilege->setPrivilegeNameTextID($mtext->getID());
	PrivilegeSave($privilege);

	defaultAction();

}

function deletePrivilege($priv){

	PrivilegeDelete($priv);
	defaultAction();
}


function htmlStart(){

    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
	  "<html>\n" .
	  "<head>\n" .
	  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
	  "<title>Termos Privileges</title>\n" .
	  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
	  "<script>\n" .
	  "function deletePrivilege(privid, privname){\n" .
	  "	if(confirm('" . MTextGet("deletePrivilege") . "  ' + privname + '?'))\n" .
	  "	location.href = 'privileges.php?action=deletePrivilege&prid=' + privid;\n" .
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