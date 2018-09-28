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
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Topic.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Topic.php';

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

	$totalmembers = 0;
	if(isset($_REQUEST["count"])) $count = $_REQUEST["count"]; else $count = -1;
	
	
	if(isset($_REQUEST["count"]))
	$countries = OrganisationCountGFByCountry();
	else
	$countries = CountryCountActive();
	
	//print '<input type="button" id="setcoord_3" value="set"/>';

	print 		'<div id="gf_countries">'.
				'<div id="gf_countries_head"><ul id="headtabs"><li id="search">Total active countries:</li><li>'.mysqli_num_rows($countries).',</li><li>Total schools/organisations: '.OrganisationCountByTypeAndAndStatus(1, 0).'</li><li id="closesearch" class="close_btn">x</li></ul></div>'.
			'<div id="gf_countries_body">';
				
	if(isset($_REQUEST["count"]))
		print 	'Global Friend Schools by occurence in country <a href="/trms-admin/globaldb_countries.php"><input type="button" class="gf_button_sharp" id="countriesviewall" value="Order by country"/></a>';
	else
		print 	'Global Friend Schools by country <a href="/trms-admin/globaldb_countries.php?count=1"><input type="button" class="gf_button_sharp" id="countriesviewall" value="Order by occurence"/></a>';
	
				
	print 		'<div id="gf_countries_list">';
	
	$totalcountries = 0;
	$totalGF = 0;
	$totalStudents = 0;
	
	print 	'<table id="countrieslist">';
	if(isset($_REQUEST["count"]))
	print 	'<tr><td style="width:260px">Country</td><td style="width:140px">Global Friend Schools</td><td style="width:140px;text-align:right">Students</td><td></td></tr>';
	else
	print 	'<tr><td style="width:260px">Country</td><td style="width:140px">Global Friend Schools</td><td style="width:140px;text-align:right"></td><td></td></tr>';

	
	while($row = mysqli_fetch_array($countries)){
	
		$count++;
		if($count % 2)
		print 	'<tr class="countries_row">';
		else
		print 	'<tr class="countries_row_even">';
		
		
		if(isset($_REQUEST["count"])){
			print 	'<td>' . $row[1] . '</td><td style="text-align:right">'. number_format($row[2], '0',' ',' ').'</td><td style="text-align:right">'.number_format($row[3], '0',' ',' ').'</td><td></td>';
			//print 	'<td>' . $row[1] . '</td><td style="text-align:right">'. number_format($row[2], ' ',' ',' ').'</td><td style="text-align:right">xx</td><td></td>'; 
			$totalGF += $row[2];
			$totalStudents += $row[3];
		}
		else{
			print 	'<td>' . $row[1] . '</td><td style="text-align:right">'. $row[3].'</td><td style="text-align:right"></td><td></td>'; 
			//print 	'<td>' . $row[1] . '</td><td style="text-align:right">x</td><td style="text-align:right">p</td><td></td>'; 
			$totalGF += $row[3];
		}
		
		//if(is_object($coords = CountryGetCoords($row[0]))   )
		
		$coords = CountryGetCoords($row[0]);
		
		
		print 	'<td><input type="text" id="latitude_'.$row[0].'" placeholder="latitude" value="'.$coords[0].'"/> <input type="text" id="longitude_'.$row[0].'" placeholder="longitude"  value="'.$coords[1].'"/> <input type="button" class="setcoord" id="setcoord_'.$row[0].'" value="set coordinates"/></td>'.
				'</tr>';
		
		//else
		//print 	'<td><input type="text" id="latitude_'.$row[0].'" placeholder="latitude"/> <input type="text" id="longitude_'.$row[0].'" placeholder="longitude"/> <input type="button" class="setcoord" id="setcoord_'.$row[0].'" value="set coordinates"/></td>'.
			//	'</tr>';
	
	
	}
	
	print 	'<tr><td style="width:260px;font-weight:bold">Total:</td><td style="width:140px;text-align:right;font-weight:bold">'. number_format($totalGF, '0',' ',' ') .'</td><td style="width:140px;text-align:right;font-weight:bold">'.number_format($totalStudents, '0',' ',' ') .'</td><td></td></tr>';

	print 	'</table>';
	
	print	'</div>';
	print 	'</div>' .
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
	  		'<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/globaldb.js"></script>' .
			'</head>' .
	  		'<body>';
}

function htmlEnd(){

	
	
    //print 	'</div>';
    
     print	'<div id="overlay"></div>'.
    		'<div id="mapcontainer">'.
    		'<div id="closeoverlay">x</div>'.
    		'<div id="mapcanvas"></div>'.
			'</div>' ;
    //print	'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0W0rJN2DORr00dPwBaaueJ9lxxCF2_Ew" 
	//			async defer></script>';
	print	'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANMI_RvV_nHDdhvGUrA3fH28etn2N81jk" 
				async defer></script>';
    
	print   '</div>'. 
			'</body>' .
	  		'</html>';

}


?>