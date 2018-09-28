<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];



if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){
	
		displayConnected($recordid);
}



function displayConnected($recordid){

 print '<div id="wcp_connected_list">';
 

print '<h3>Connected Schools</h3>';
print '<table>';
 
 
 $orgs = OrganisationGetAllByParentID($recordid);

 
 while($orgs = OrganisationGetNext($orgs)){
 
				print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
				print '<td><a id="editorg_'.$orgs->getID().'" href="#" >' . $orgs->getOrgName() . '</a> <input type="hidden" id="geoaddress_'.$orgs->getID().'" value="'.$orgs->getAddress1().'"/></td>'; 
				
				print '<td>'.$orgs->getExternalID().'</td>';
				print '<td id="geocity_'.$orgs->getID().'">' . $orgs->getCity() . '</td>'; 
				
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
 
 print '</table>';
 
 print '</div>';

}








?>