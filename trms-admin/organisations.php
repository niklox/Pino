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
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"];
if(isset($_REQUEST["orgname"]))$orgname = $_REQUEST["orgname"];
DBCnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $oid, $orgname;

	if($action == "editOrganisation") 
		editOrganisation($oid);
	else if($action == "saveOrganisation")
		saveOrganisation($oid);
	else if ($action == "createOrganisation")
		createOrganisation($orgname);
	else if ($action == "deleteOrganisation")
		deleteOrganisation($oid);
	else
		defaultAction();
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	print "<div class=\"stdbox_600\">\n" .
		  "<form><input type=\"hidden\" id=\"action\" name=\"action\" value=\"createOrganisation\">\n";

	
	print "<input type=\"input\" id=\"orgname\" name=\"orgname\" size=\"30\"/>\n";
	
	print "<input type=\"submit\" value=\"Create new organisation\"></form>\n" .
	      "</div>" .
		  "<div id=\"userlist_head\">All organisations" .
		  "</div>\n" .
		  "<div id=\"userlist\">" .
		  "<table class=\"adminlist\">\n";

		  $allorganisations = AddressGetAll();

		  while($allorganisations = AddressGetNext($allorganisations))
		  {
				  print "<tr>\n" . 
					  "<td><a href=\"/trms-admin/organisations.php?action=editOrganisation&oid=". $allorganisations->getID()."\">" . 
					  "<img src=\"images/edit_mini.gif\" border=\"0\" alt=\"edit user\"/></a></td>\n<td>".  $allorganisations->getCompanyName() . "</td>\n" . 
					   "<td class=\"digit\"><img src=\"images/delete_mini.gif\" border=\"0\" alt=\"Delete user\" onclick=\"deleteOrganisation(" .  $allorganisations->getID() . ", '" . $allorganisations->getCompanyName() . "')\"/></td>\n" .
					  "</tr>\n";
		  } 
		
	print "</table>\n" .
	      "</div>\n";
}

function editOrganisation($oid){

	$org = AddressGetByID($oid);
	$attribute = AddressAttributeGetAll();

	print "<div id=\"editbox_head\">";
		print htmlspecialchars($org->getCompanyName())." <a title=\"userlist\" href=\"/trms-admin/organisations.php?action=default\"><img src=\"images/edit_mini.gif\" border=\"0\" alt=\"userlist\"/></a>";
		print "</div>";

		print "<div id=\"editbox\" class=\"clearfix\">" .
			  "<form>" .
			  "<input type=\"hidden\" name=\"oid\" value=\"" . $org->getID() . "\">" .
			  "<input type=\"hidden\" name=\"action\" value=\"saveOrganisation\">" ;

		print "<div class=\"boxes\">\n";
		print "<table>";
		print "<tr><td>Name:</td><td><input type=\"text\" name=\"organisationname\" value=\"" . $org->getCompanyName() . "\" size=\"30\"/></td></tr>\n" .
		      "<tr><td>URL:</td><td> <input type=\"text\" name=\"url\" value=\"" . $org->getURL() . "\" size=\"30\" /></td></tr>\n" .
			  "<tr><td>EMail:</td><td> <input type=\"text\" name=\"email\" value=\"" . $org->getEmail() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>Adress 1:</td><td> <input type=\"text\" name=\"address1\" value=\"" . $org->getAddress1() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>Adress 2:</td><td> <input type=\"text\" name=\"address2\" value=\"" . $org->getAddress2() . "\"size=\"30\"/></td></tr>\n" .
			  "<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"" . $org->getZip() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"" . $org->getCity() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>Country:</td><td><input type=\"text\" name=\"countryid\" value=\"" . $org->getCountryID() . "\" size=\"30\"/></td></tr>\n" ;

		print "</table>\n";
		print "</div>\n";
		
		print "<div class=\"boxes\">\n";
		print "<table>";
		print "<tr><td>Tel:</td><td><input type=\"text\" name=\"tel\" value=\"" . $org->getTel() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>Fax:</td><td><input type=\"text\" name=\"fax\" value=\"" . $org->getFax() . "\" size=\"30\"/></td></tr>\n" .
			  "<tr><td>Contact:</td><td><input type=\"text\" name=\"contact\" value=\"" . $org->getContact() . "\" size=\"30\"/></td></tr>\n";

		print "<tr><td>Type:</td><td><select name=\"organisationtype[]\" style=\"width:210px\" size=\"8\" multiple>\n";

		while($attribute = AddressAttributeGetNext($attribute))
		{
			if( AddressHasAttribute($org->getID(), $attribute->getID()))
				 print "<option value=\"" . $attribute->getID() . "\" selected>" . $attribute->getAttributeHandle() . "</option>\n";
			else
				 print "<option value=\"" . $attribute->getID() . "\">" . $attribute->getAttributeHandle() . "</option>\n";
		}
		
		print "</select></td></tr></table>\n";
		print "</div>\n";
		print "<div class=\"boxes\">";
		print "<table>";
		print "<tr><td>Additional info:<br/><textarea name=\"info\" rows=\"9\" cols=\"30\">" . $org->getAdditionalInfo() . "</textarea></td></tr>\n";
		print "<tr><td><input type=\"submit\" value=\"Save\"></td></tr>\n";
		
		print "</table></div>\n";
		print "</form></div>\n";
			
}

function saveOrganisation($oid){

	if( $org = AddressGetByID($oid) )
	{
		
		if(isset($_REQUEST["organisationname"]))$organisationname = $_REQUEST["organisationname"];
		if(isset($_REQUEST["url"]))$url = $_REQUEST["url"];
		if(isset($_REQUEST["email"]))$email = $_REQUEST["email"];
		if(isset($_REQUEST["address1"]))$address1 = $_REQUEST["address1"];
		if(isset($_REQUEST["address2"]))$address2 = $_REQUEST["address2"];
		if(isset($_REQUEST["zip"]))$zip = $_REQUEST["zip"];
		if(isset($_REQUEST["city"]))$city = $_REQUEST["city"];
		if(isset($_REQUEST["zip"]))$zip = $_REQUEST["zip"];
		if(isset($_REQUEST["city"]))$city = $_REQUEST["city"];
		if(isset($_REQUEST["countryid"]))$countryid = $_REQUEST["countryid"];
		if(isset($_REQUEST["tel"]))$tel = $_REQUEST["tel"];
		if(isset($_REQUEST["fax"]))$fax = $_REQUEST["fax"];
		if(isset($_REQUEST["contact"]))$contact = $_REQUEST["contact"];
		if(isset($_REQUEST["info"]))$info = $_REQUEST["info"];
			
		$org->setCompanyName($organisationname);
		$org->setUrl($url);
		$org->setEMail($email);
		$org->setAddress1($address1);
		$org->setAddress2($address2);
		$org->setZip($zip);
		$org->setCity($city);
		$org->setCountryID($countryid);
		$org->setTel($tel);
		$org->setFax($fax);
		$org->setContact($contact);
		$org->setAdditionalInfo($info);

		AddressSave($org);
		

		if(isset($_REQUEST["organisationtype"]))$info = $_REQUEST["organisationtype"];
		
		$sqlstr = "DELETE FROM AddressAttributeValues WHERE AddressID = " . $oid;
		mysql_query($sqlstr);
		for ($a = 0; $a < count($info); $a++ ) {
			$sqlstr = "INSERT INTO AddressAttributeValues VALUES(" . $oid . ", " . $info[$a] . ") ";
			mysql_query($sqlstr);
		}

		editOrganisation($oid);
	}
	else
	{
		print "No organisation with " . $oid;
	}

}

function createOrganisation(){

	global $action, $uid,  $orgname;

	$organisation = new Address;

	$organisation->setCompanyName($orgname);
	
	AddressSave($organisation);

	if($organisation = AddressGetByCompanyName($orgname))
	editOrganisation($organisation->getID($organisation));

}

function deleteOrganisation($oid){

	AddressDelete($oid);
	defaultAction();
}


function htmlStart(){

    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
	  "<html>\n" .
	  "<head>\n" .
	  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
	  "<title>Termos Organisation</title>\n" .
	  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
	  "<script>\n" .
	  "function deleteOrganisation(orgid, orgname){\n" .
	  "	if(confirm('" . MTextGet("deleteOrganisation") . " for ' + orgname + '?'))\n" .
	  "	location.href = 'organisations.php?action=deleteOrganisation&oid=' + orgid;\n" .
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