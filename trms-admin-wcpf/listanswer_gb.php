<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';

DBcnx();

/*
Map from Adult Friend Form

DateTime
Type 
Status

365 OrganisationName
366 Kontaktperson
368 Address1
369 Address2
370 Postnummer
371 Ort
372 Region/STATE
373 Country 
374 Email
391 Decription addslahes()
376 Telephone
1251 WWW
377 Fax 
*/

/*
Map from Global Friend Form

DateTime
Type 
Status

352 Skola
353 Kontaktperson
355 Adress1
356 Address2
357 Postnummer
358 City
359 Region
360 Country
354 Antal Elever
361 Email
363 Telefon
364 Fax
362 Hemsida

*/

$formanswer = FormAnswerGetAllForForm(51);
$org = new Organisation;

while($formanswer = FormAnswerGetNext($formanswer)){
		$counter++;
		//print	"<li id=\"answer_" . $formanswer->getID(). "\" class=\"formanswer\" value=\"" . $formanswer->getID() . "\">" . $formanswer->getID() . " " . $formanswer->getDateTime() . " ". ($formanswer->getStatus()==0?"UNCOMPLETE":"COMPLETE")  ." " . $formanswer->getIP() . "</li>";
	
	
	if( $formanswer->getDateTime() > "2013-05-25 00:01:01"){
     
     $forminput = FormInputGetAllForForm($formanswer->getFormID());
     
     print 'Answer #' .$counter . ' '.substr($formanswer->getDateTime(),0,10).'<br/>';
     $org->setID(0);
     $org->setTypeID(1);
     $org->setStatus(1);
     $org->setAuthorID(25);
     $org->setRegistrationDate(substr($formanswer->getDateTime(),0,10));
     $org->setLastUpdate(substr($formanswer->getDateTime(),0,10));
     while($forminput = FormInputGetNext($forminput)){
		
		
		if($forminput->getTypeID() == 1 || $forminput->getTypeID() == 2){
		
			print $forminput->getID() . ' ';
			print  FormInputGetQuestion( $forminput->getID(), $forminput->getTypeID() ) .' ' . FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()). '<br/>';
		
		/*
			switch($forminput->getID()){
			
			case 365: // Namn
			$org->setOrgName(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 366:
			$org->setContactName(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 368:
			$org->setAddress1(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 369:
			$org->setAddress2(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 370:
			$org->setZip(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 371:
			$org->setCity(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 372:
			$org->setRegion(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 374:
			$org->setEmail(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			$org->setContactEmail(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 376:
			$org->setTel(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			$org->setContactTel(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 377:
			$org->setFax(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 391:
			$org->setDescription(addslashes(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID())));
			break;
			case 1251:
			$org->setURL(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			}
			
			*/
			
			
			switch($forminput->getID()){
			
			case 352: // Namn
			$org->setOrgName(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 353:
			$org->setContactName(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 355:
			$org->setAddress1(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 356:
			$org->setAddress2(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 357:
			$org->setZip(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 358:
			$org->setCity(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 359:
			$org->setRegion(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 361:
			$org->setEmail(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			$org->setContactEmail(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 363:
			$org->setTel(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			$org->setContactTel(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 364:
			$org->setFax(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			case 391:
			$org->setDescription(addslashes(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID())));
			break;
			case 362:
			$org->setURL(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			
			case 354:
			$org->setExternalID(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			break;
			}
			
			
		}
		else if($forminput->getTypeID() == 9){
			
			print $forminput->getID() . '  ';
			
			if($forminput->getID() == 373 || $forminput->getID() == 360){
			
			$countryid = CountryGetIDByName(FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()));
			print $countryid . ' ';
			$org->setCountryID( $countryid );
			$org->setContinent( CountryGetContinentName($countryid) );
			
			}
			print $forminput->getTitle() . ' ' . FormInputGetAnswer($formanswer->getID(), $forminput->getID(), $forminput->getTypeID()). '<br/>';
		}
		
		}
	
	print '<br/>';
	//print $org->getTypeID() .'<br/>';
	var_dump(get_object_vars($org));
	//print OrganisationSave($org);
	print '<br/><br/>';
	}
	// make function of this
	//mysql> SELECT country_id FROM country_t WHERE LOWER(short_name) = LOWER("NIGERIA")



}

?>