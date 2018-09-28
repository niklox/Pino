<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();

// Filepath
$csv_filepath = "AFO_Master_List.csv";


$row = 1;

$org = new Organisation;

if (($handle = fopen($csv_filepath, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
        if($row > 607) break;
		 
		$org->setID(0);
     	$org->setTypeID(4);
     	$org->setStatus(1);
     	$org->setAuthorID(25);
     	$org->setLastUpdate(date("Y-m-d"));
     	
     	
     	
		$num = count($data);
       // echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;

		print 	'<br/>no #'.$row.' Date ' . date("Y-m-d"). ' <br/>0: '. $data[0];
		
		$comment = trim($data[1]);
		
		print	'<br/>#1 Comment: ' . $comment;
		
		$description = 'Comment: <br/>-----------<br/>'. $comment . '<br/>----------<br/>';
		print 	'<br/>#2 Type: ' . $data[2];
		 
		print 	'<br/>#3 Linked to:  '. $data[3];
		
		$org->setCoordinates($data[3]);
		
		print 	'<br/>#4 Name: '.  $data[4];
		
		$org->setOrgName(trim($data[4]));
		
		print	'<br/>#5 Contact person: ' . $data[5];
		$org->setContactName(trim($data[5]));
		
		print 	'<br/>#6 Total no of children: ' . $data[6];
		$org->setExternalID($data[6]);
		
		print 	'<br/>#7 Address 1: '. $data[7];
		$org->setAddress1(trim($data[7]));
		
		print 	'<br/>#8 Address2:'. $data[8];
		$org->setAddress2(trim($data[8]));
		
		print 	'<br/>#9 Zip: '. $data[9];
		$org->setZip(trim($data[9]));
		
		print 	'<br/>#10 Region: '. $data[10];
		$org->setRegion(trim($data[10]));
		
		print 	'<br/>#11 City: '. $data[11];
		
		$countryid = CountryGetIDByName(trim($data[12]));
				
		print 	'<br/>#12 Country: '. $data[12] . ' ' . $countryid;
		
		$org->setCountryID($countryid);
		$continent = CountryGetContinentName($countryid);
		
		print 	'<br/> Continent: ' . $continent;
		
		$org->setContinent($continent);
		
		
		$language = $data[13];
		
		print	'<br/>#13 Language: '. $language . ' ';
		
		switch($language){
		case "ENG":
		print "5";
		
		$org->setLanguageID(5);
		break; 
		
		case "SPA":
		print "6";
		$org->setLanguageID(6);
		break;
		
		case "SWE":
		print "1";
		$org->setLanguageID(1);
		break;
		
		case "FRA":
		print "7";
		$org->setLanguageID(7);
		break;
		
		case "POR":
		print "8";
		$org->setLanguageID(8);
		
		break;
		
		
		}
		//print "<br/>";		
		print	'<br/>#14 Mail: '. trim($data[14]).
				'<br/>#15 Website: '. trim($data[15]);
				
		print   '<br/>#16 Tel: '. trim($data[16]).'<br/>#17 Fax: '. trim($data[17]);
		print 	'<br/>#18 Created: '. trim($data[18]);
		print 	'<br/>#19 Updated: '. trim($data[19]);
		//print	'<br/>#20 Voted 2006: '. $data[20].'<br/>#21 Voted 2007: '. $data[21].'<br/>#22 Voted 2008: '. $data[22];
		print 	'<br/>#23 Alt.Address: '. $data[23];
		print 	'<br/>#24 Payed: '. $data[24];
		print	'<br/>#25 DatePayment: '. $data[25];
		print 	'<br/>#26 Text: '. addslashes($data[26]);
		print 	'<br/><br/>';
		var_dump(get_object_vars($org));

		//print '<br/>' . UserSave($player) . '<br/><br/>';
		
		print '<br/><br/>';

    }
    fclose($handle);
}

function formatdatestr($datestr){
	
	$socialsec = array();
	// Check if the string seems to bee correct
	if(strlen($datestr) === 11 && substr($datestr, 6, 1) === "-"){
		
		$firstpart = substr($datestr, 0, 6);
		$lastpart = substr($datestr, 7, 4);
		
		// First prefix to year
		if(is_numeric(substr($firstpart, 0, 2)) && substr($firstpart, 0, 2) > 30 && substr($firstpart, 0, 2) <= 99)
		$firstpart = "19" . $firstpart;
		else
		$firstpart = "20" . $firstpart;

		// Create a string for birthday

		$birthday = substr($firstpart,0,4) . '-' . substr($firstpart,4,2) . '-' . substr($firstpart,6,2);

		$socialsec[0]= $birthday;
		$socialsec[1]= $lastpart;

		return $socialsec;

	}
}

/*


*/
?>
