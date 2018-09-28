<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
DBcnx();

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"]; // orderid



print '<div id="content">';

if(isset($admin))
{
	global $action, $formid;

	//if( UserHasPrivilege($admin->getID(), 19) ){

		defaultAction();

	//}else{
	//	print "No permission";
	//}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	global $admin, $dbcnx;
	$counter = 0;

	print	'<div id="box_head">Submitted from payment/donation forms</div>';
	print	'<div id="box_body"><div id="box_body_top"></div>';
	
	print	'<div id="comments">';
	
	
	
	
	print	'<table class="donationlist">';
	print	'<tr>'.
			'<td>DonationID</td>'.
			'<td>TransactionID/No</td>'.
			'<td>Amount</td>'.
			'<td>Currency</td>'.
			'<td>Date/Time</td>'.
			'<td>Name of Donor, Payer or Giftreceiver</td>'.
			//'<td>Address</td>'.
			//'<td>Zip</td>'.
			//'<td>City</td>'.
			//'<td>Country</td>'.
			//'<td><a title="Donors Address?" href="#">D.A.</a></td>'.
			//'<td>E-mail</td>'.
			//'<td>Phone</td>'.
			'<td>Type</td>'.
			//'<td>Donor or Message</td>'.
			'<td></td>'.
			'</tr>';
			
	$sql = 'SELECT ID,PayPal_tx_ID,Amount,Currency,DateAndTime,Fullname,Address,Zip,City,Country,EMail,Phone,PaymentType,Message,MyAddress,RecipientsEmail,CertificateMsg FROM PayPal_Payments ORDER BY DateAndTime DESC';
	
	//print $sql;
	$result = mysqli_query($dbcnx, $sql);
	
	
	while ($row = mysqli_fetch_array($result)) {
	
	print 	'<tr class="'.($counter % 2 != 0?"odd":"even").'" id="'.$row[0].'">'.
   		  	'<td class="donationid" id="donation-opendata_'.$row[0].'">'.$row[0].' &#187;</td>'.
   		  	'<td>'.$row[1].'</td>'.
   		  	'<td>'.$row[2].'</td>'.
   		  	'<td>'.$row[3].'</td>'.
   		  	'<td>'.substr($row[4],0,16).'</td>'.
   		  	'<td>'.$row[5].'</td>'.
   		  	//'<td>'.$row[6].'</td>'.
   		  	//'<td>'.$row[7].'</td>'.
   		  	//'<td>'.$row[8].'</td>'.
   		  	//'<td>'.$row[9].'</td>'.
   		  	//'<td>'.($row[14] == "1"?"Yes":"No").'</td>'.
   		  	//'<td>'.$row[10].'</td>'.
   		  	//'<td>'.$row[11].'</td>'.
   		  	'<td>'.$row[12].'</td>'.
   		  	//'<td>'.$row[13].'</td>'.
   		  	'<td><img class="deletedonation" id="donation_'.$row[0].'" src="/trms-admin/images/delete_mini.gif"/></td>'.
			'</tr>'.
			'<tr class="'.($counter % 2 != 0?"odd":"even").'">'.
			'<td colspan="8" class="donationdata" id="data_'.$row[0].'">'.
			'	<div class="dondatabody"><hr/>'.
			'	<table><tr>';
			
	
	print 	'<td>'.
				'Payment type: ' . $row[12]. '<br/>' .
				'Amount: ' . $row[2]. '<br/>' .
				'Currency: ' . $row[3]. '<br/>'.
				'Date: ' .substr($row[4],0,10) . '<br/>' .
				'Time: ' .substr($row[4],11).
			'</td>';
			
	if( strstr($row[0], "G") ){
	print 	'<td>'.
				'Payer: ' . $row[5]. '<br/>' .
				'Email: '. $row[10]. '<br/>' .
				'Telephone: ' . $row[11]. '<br/>' .
			'</td>';
	}
	else
	{
			
	print 	'<td>'.
				'Payer/Donor: ' . $row[5]. '<br/>' .
				'Address: ' . $row[6]. '<br/>' .
				'Zipcode: ' . $row[7]. '<br/>'.
				'City: ' .	$row[8] . '<br/>' .
				'Country: ' .$row[9]. '<br/>' .
				'Email: '. $row[10]. '<br/>' .
				'Telephone: ' . $row[11]. '<br/>' .
			'</td>';
	}		
	if( strstr($row[0], "G") ){
	print 	'<td>'.
				'Giftreciever: ' . $row[13]. '<br/>' .
				
			'</td>'.
			
			'<td>' .
			'Giftcard address: ' . ( $row[14] == "1"?$row[13]:$row[5] ) . '<br/>' .
			'Address: ' . $row[6]. '<br/>' .
			'Zipcode: ' . $row[7]. '<br/>'.
			'City: ' .	$row[8] . '<br/>' .
			'Country: ' .CountryGetName($row[9]). '<br/><br/>' .
			'Name on card: '. $row[13] . '<br/>'.
			
		'</td>';
	}
	else
	{
	print 	'<td>'.
				'Message: ' . $row[13]. '<br/>' .
			'</td>';
	}
			
			
				
		
	print 	'</tr></table><hr/></div>'.
			'</td>'.
			'</tr>';
			//$counter++;
	}

	print	'</table>';
	print	'</div>';
	print	'</div>'; 

}

function htmlStart(){

    print	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
			'<html>' .
			'<head>' .
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
			'<title>Termos Donations</title>' .
			'<link rel="stylesheet" href="css/termosadmin.css"/>' .
			'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/donation_admin.js"></script>' .
			'</head>' .
			'<body>';
}

function htmlEnd(){
	
    print	'</div>' .
			'</body>' .
			'</html>';
}

?>