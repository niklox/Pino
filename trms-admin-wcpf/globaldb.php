<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["message"]))$message = $_REQUEST["message"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"];
if(isset($_REQUEST["orgname"]))$orgname = $_REQUEST["orgname"];

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print '<div id="content">';

if(isset($admin))
{
	global $action, $oid, $orgname;

	
	defaultAction();
	
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){
$counter = 0;

	print 		'<div id="gf_search">'.
				'<div id="gf_search_head"><ul id="headtabs"><li id="search">Search</li></li><li id="newrecord">Create new</li><li id="closesearch" class="close_btn">x</li></ul></div>'.
				'<div id="gf_search_body">'.
				'<form id="searchform">' .
				'<input type="hidden" id="action" name="action" value="makequery"/>'.
				'<div class="gf_column_2">'.
				
				'<div class="gf_column_3">'.
				'<input class="gf_input" id="recordname" name="recordname" value="School or Organisation Name ..."/>'.
				'<div class="gf_column_6"><input class="gf_input_date" id="startdate" name="startdate" value="Startdate"/></div>'.
				'<div class="gf_column_6"><input class="gf_input_date" id="enddate" name="enddate" value="Enddate"/></div>'.
				'<div id="checkboxview"><input type="radio" id="" name="checkboxview" value="0" CHECKED> Standard view<br/><input type="radio" id="" name="checkboxview" value="1"> Checkboxview Topics<br/><input type="radio" id="" name="checkboxview" value="2"> Checkboxview Status</div>' .
				'</div>'.
				'<div class="gf_column_3">'.
				'<select name="typeid" id="type" class="gf_select">'.
				'<option value="0">Select organisation type</option>'.
				'<option value="1">Global Friend School</option>'.
				'<option value="2">Global Friend Group</option>'.
				'<option value="4">Adult Friend Organisation</option>'.
				'<option value="8">Focal point</option>'.
				'<option value="16">Donor - monthly</option>'.
				'<option value="32">Donor - one time</option>'.
				'<option value="64">Administrative</option>'.
				'<option value="128">Media</option>'.
				'<option value="256">Subscriber</option>'.
				
				'</select>';
				
				PrintGenericSelect('SELECT TopicID, Topic FROM Topic WHERE TopicStatus > 0', 'gf_select', 'topicid', 'Select by topic', '');

		print	'<select class="gf_select" id="program_year" name="program_year">'.
				'<option>Select by program year</option>'.
				'<option value="2018-01-01">2018</option>'.
				'<option value="2017-01-01">2017</option>'.
				'<option value="2016-01-01">2016</option>'.
				'<option value="2015-01-01">2015</option>'.
				'<option value="2014-01-01">2014</option>'.
				'<option value="2013-01-01">2013</option>'.
				'<option value="2012-01-01">2012</option>'.
				'<option value="2011-01-01">2011</option>'.
				'<option value="2010-01-01">2010</option>'.
				'</select>';
		PrintGenericSelect(ORGANISATION_SELECT . ' WHERE TypeID & 64', 'gf_select_edit', 'organisationid', 'Select organisation', 0);
		print 	'</div>'.
				'<div class="gf_column_2">'.
				'<div class="gf_column_9"><input type="checkbox" id="flag1" name="flag1" value="1"/> Pending</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag2" name="flag2" value="2"/> Approved</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag3" name="flag3" value="4"/> Follow-up</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag4" name="flag4" value="8"/> Incomplete</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag5" name="flag5" value="16"/> Resting</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag6" name="flag6" value="32"/> Confirmed</div>'.
				'<div class="gf_column_9"><input type="checkbox" id="flag7" name="flag7" value="64"/> Inactive</div>'.
				'</div>'.
				'</div>'.
				'<div class="gf_column_3">';
				PrintGenericSelect('SELECT country_id, short_name FROM country_t WHERE active = 1', 'gf_select', 'country_id', 'Select country', '');
				PrintGenericSelect('SELECT RegionID, RegionName FROM Region ORDER BY RegionID','gf_select','s_region_id', 'Välj län', '');
		
		print	'<select class="gf_select" id="s_municipality_id" name="s_municipality_id"><option value="0">Välj kommun</option></select>';
		print	'<input type="text" id="state_id" class="gf_input" value=""/>';		
		
		print	'</div>'.
				'<div class="gf_column_1">'. 
				'<input type="radio" id="type_organisation" name="stype" value="1" checked/> Organisation '.
				'<input type="radio" id="type_individual" name="stype" value="2"/> Individual '.
				'<input type="submit" class="gf_submit" value="Search"/>'.
				'</div>'.
				'</form>';
				
		print	'<form id="newrecordform">'.
				'<input type="hidden" id="action" name="action" value="createnewrecord"/>'.
				'<div class="gf_column_1">'.
				'<input type="text" class="gf_input" id="new_record_name" name="new_record_name" value="Organisation name"/> '. 
				'<input type="text" class="gf_input" id="new_account_name" name="new_account_name" value="Accountname/EMail"/> '. 
				'<input type="radio" id="new_org" name="n_type" value="1" checked/> Organisation'. 
				'<input type="radio" id="new_ind" name="n_type" value="2"/> Individual'.
				'<input type="submit" class="gf_submit" value="Create new record"/>'.
				'<div id="existsmsg"></div>' .
				'</div>'. 
				'</form>'.
				'</div>'.
				'</div>';
		print 	'<div id="gf_view">'.
				'<div id="gf_view_head"><ul id="headtabsview"><li id="viewrecord">View</li></li><li id="editrecord">Edit</li><li id="closeview" class="close_btn">x</li></ul></div>'.
				'<div id="gf_view_body">';
				
				
		print '<div align="center" id="map"><br/></div>';
		//print	'<span id="lat"></span>';
    	//print '<span id="lng"></span>';
				
		print  '</div>'.
						//'<div id="console">asdasd</div>'.
				'</div>';
				
		print 	'<div id="gf_result">'.
				'<div id="gf_result_head">Result</div>'.
				'<div id="gf_result_body">';
		
		//date('jS F Y H:i.s', strtotime('-1 week'));		
		//$sql = 'WHERE Organisation.Status & 1 AND Organisation.RegistrationDate >= "2014-12-02"';
		
		$sql = 'WHERE Organisation.Status & 1 AND (Organisation.RegistrationDate >= "'.date('Y-m-d', strtotime('-2 month')).'" OR Organisation.LastUpdated >= "'.date('Y-m-d', strtotime('-2 month')).'") ORDER BY Organisation.LastUpdated DESC';
		
		print '<h4>Recent new or updated and now pending registrations to be audited</h4>';
		//print $sql .'<br/>';
		
		$orgs = OrganisationGetII($sql);
				
		print '<table>';
		print '<tr><td>School/Organisation</td><td>City</td><td>LastUpdate</td><td>RegistrationDate</td><td>Country</td>';
		
		while($orgs = OrganisationGetNext($orgs)){
		
		print '<tr class="'.($counter % 2 != 0?"list_odd":"list_even").'">';
		//print '<td><a id="editorg_'.$orgs->getID().'" href="#" > &nbsp;<img src="/trms-admin/images/edit_mini.gif"/></a></td>';
		print '<td><a id="editorg_'.$orgs->getID().'" href="#" >' . $orgs->getOrgName() . '</a><input type="hidden" id="geoaddress_'.$orgs->getID().'" value="'.$orgs->getAddress1().'"/></td>'; 
		print '<td id="geocity_'.$orgs->getID().'">' . $orgs->getCity() . '</td>'; 

		//print '<td>' . MunicipalityGetName($orgs->getMunicipalityID()) . '</td>';
		//print '<td>' . RegionGetName($orgs->getRegionID()) . '</td>';
		
		print '<td>' . substr($orgs->getLastUpdate(),0,10) . '</td>';
		print '<td>' . $orgs->getRegistrationDate() . '</td>';
		
		print '<td id="geocountry_'.$orgs->getID().'">' . CountryGetName($orgs->getCountryID()) . '</td>';
		print'</tr>';
		 
		 $counter++;
		
		}
		
		
		
		
		print '</table>';
				
		//$orgs = OrganisationGetAll();
		
		//print '<span id="condition" style="font-size:10px;font-style:italic">' .$sql . '</span><br/>';
		
		//print '<input type="hidden" id="sqlstr" name="sqlstr" />'; 
		//print '<input class="gf_button_sharp" type="button" id="export_to_xls" value="Export to xls"/>';
		
		//print '<table>';
		//print '<tr><td>School/Organisation</td><td>City</td><td>Municipality</td><td>Region</td><td>Country</td>';
		
/*		while($orgs = OrganisationGetNext($orgs)){
		
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
*/
		
		//print '</table>';
		print	'</div>'.
				'</div>';
}

function htmlStart(){

    print 	'<!DOCTYPE html>' .
	  		'<html>' .
	  		'<head>' .
	  		'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
	  		'<title>Termos Organisation</title>' .
	  		'<link rel="stylesheet" href="css/termosadmin.css"/>' .
	  		'<link rel="stylesheet" href="css/globaldb.css"/>' .
	  		'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			//'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/globaldb.js"></script>' .
			//'<script>window.onload = loadScript;</script>'.
			//'<script type="text/javascript" src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAgrj58PbXr2YriiRDqbnL1RSqrCjdkglBijPNIIYrqkVvD1R4QxRl47Yh2D_0C1l5KXQJGrbkSDvXFA"></script>'.
	  		'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANMI_RvV_nHDdhvGUrA3fH28etn2N81jk&callback=initMap" async defer></script>'.
	  		//
	  		'</head>' .
	  		'<body>';
	  		//'<body onload="load()">';
	
}

function htmlEnd(){
	
    print 	'</div>' .
	        '</body>' .
	  		'</html>';

}