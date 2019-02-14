<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Orders.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Orders.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
//loadscripts();

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"]; 
if(isset($_REQUEST["startdate"]))$startdate = $_REQUEST["startdate"]; 
if(isset($_REQUEST["enddate"]))$enddate = $_REQUEST["enddate"]; 
if(isset($_REQUEST["orderstatus"]))$orderstatus = $_REQUEST["orderstatus"]; 
if(isset($_REQUEST["orderdetail"]))$orderdetail = $_REQUEST["orderdetail"];
if(isset($_REQUEST["orderdetailvalue"]))$orderdetailvalue= $_REQUEST["orderdetailvalue"];


if($admin = UserGetUserByID(TermosGetCurrentUserID())){	
	global $action, $oid, $startdate, $enddate, $orderdetail, $orderdetailvalue;
	
	if($action == "listorders")
		listOrders( $startdate, $enddate, $orderstatus);
	else if($action == "vieworder")
		orderView($oid);
	else if($action == "deleteorder")
		deleteOrder($oid);
	else if($action == "saveorderdetails")
		orderSaveDetails($oid, $orderdetail, $orderdetailvalue);
	else
		listOrders($startdate, $enddate, $orderstatus);

}else print "Please login";

function listOrders($startdate, $enddate, $orderstatus){
	$status = array("Ej slutförd","Inskickad","Parkerad","Levererad","Slutförd");
	$paymentmethod = array("Faktura","Kreditkort");
	
	$ordercount = 0;
	
	$orders = OrderSearch($startdate, $enddate, $orderstatus);
	print "<table class=\"orderlist\">\n";
	print "<tr><td class=\"id\">OrderID</td><td class=\"name\">Namn</td><td class=\"city\">Stad</td><td class=\"created\">Skapad</td><td class=\"status\">Status</td><td class=\"payment\">Betalning</td><td class=\"total\">Ordertotal</td></tr>";
	while( $orders = OrderGetNext($orders)){
		print "<tr class=\"orders\"  id=\"orderrow_".$orders->getID()."\" value=\"" .$orders->getID() ."\"><td>" . $orders->getID() . "</td><td>" .$orders->getLastName(). "</td><td>".$orders->getCity()."</td><td>". substr($orders->getCreatedDate(),0, 16) ."</td><td>" . $status[$orders->getStatus()]. "</td><td>".$paymentmethod[$orders->getPaymentMethod()]."</td><td class=\"ordervalue\">" .number_format($orders->getTotal(), 2, ',', ' '). "</td></tr>\n" ;
		$ordercount++;
		$total += $orders->getTotal();
	}
	print "<tr><td colspan=\"5\"></td><td>Total: " .$ordercount . "</td><td class=\"ordervalue\" colspan=\"1\"> ".number_format($total, 2, ',', ' ')."</td></tr>";
	print "</table>\n";
}

function orderView($oid){
	
	
	print	"<table class=\"widetable\"><tr><td>";

	if($order = OrderGetByID($oid) ){

	print	"<table class=\"orderdetails\">\n";
	print	"<tr><td class=\"left\" rowspan=\"6\">" . 
			"<span class=\"whitehead\">Order: ". $order->getID() . "</span>";
			
	


	print	"</td><td class=\"label\" colspan=\"4\"><h6>". MTextGet("invoiceaddress") ."</h6></td></tr>" ;

	
	print	"<tr>".
			"	<td class=\"label\">Namn:</td>" .
			"	<td class=\"value\">" . $order->getLastName() . "</td>".
			"	<td class=\"label\">Adress:</td>" .
			"	<td class=\"value\">" . $order->getAddress1()."</td>" .
			"</tr>\n";

	print	"<tr>".
			"	<td class=\"label\">Företag/skola:</td>".
			"	<td class=\"value\">" . $order->getCompanyName() . "</td>".
			"	<td class=\"label\">Postnummer:</td><td class=\"value\">". $order->getZip() ."</td>".
			"</tr>\n";

	print	"<tr>".
			"	<td class=\"label\">E-post:</td><td class=\"value\">" . $order->getContactEmail()."</td>".
			"	<td class=\"label\">Ort:</td><td class=\"value\">" . $order->getCity()."</td>".
			"</tr>\n";

	print	"<tr>".
			"	<td class=\"label\">Telefon:</td><td class=\"value\">" . $order->getAddress2() ."</td>".
			"	<td class=\"label\">Referens:</td><td class=\"value\">" . $order->getCustomerRef() ."</td>".
			"</tr>\n";

	print	"<tr>".
			"<td class=\"label\">Meddelande:</td><td colspan=\"3\" class=\"value\">" . $order->getCustomerComments() . "</td>".
			"</tr>\n";
	
	
	if( $order->getResellerID() == 1 ){
	
		print	"<tr><td class=\"left\" rowspan=\"6\">&nbsp;</td><td class=\"label\" colspan=\"4\"><h6>" . MTextGet("deliveryaddress") . "</h6></td></tr>" ;
		print	"<tr>".
				"	<td class=\"label\">Namn:</td>" .
				"	<td class=\"value\">" . $order->getShipLastName()."</td>".
				"	<td class=\"label\">Adress:</td>" .
				"	<td class=\"value\">" . $order->getShipAddress1()."</td>" .
				"</tr>\n";
		

		print	"<tr>".
				"	<td class=\"label\">Företag/skola:</td>".
				"	<td class=\"value\">" . $order->getShipCompanyName()."</td>".
				"	<td class=\"label\">Postnummer:</td><td class=\"value\">" . $order->getShipZip()."</td>".
				"</tr>\n";

		print	"<tr>".
				"	<td></td><td></td>".
				"	<td class=\"label\">Ort:</td><td class=\"value\">" . $order->getShipCity()."</td>".
				"</tr>\n";
	
		print	"<tr>".
				"<td class=\"label\">Meddelande till mottagaren:</td><td colspan=\"3\" class=\"value\">" . $order->getComments() . "</td>".
				"</tr>\n";
		
				
	}

	print	"</table>\n";

		print	"<table class=\"orderrows\">" .
				"<tr class=\"underlined\"><td colspan=\"2\" class=\"head1\">" . MTextGet("description") . "</td><td class=\"head2\">" . MTextGet("quantity") . "</td><td class=\"head3\">" . MTextGet("price") . "</td><td class=\"head4\">" . MTextGet("sum") . "</td></tr>\n" ;
				$orderitem = OrderItemGetAllForOrder($order->getID());

				while($orderitem = OrderItemGetNext($orderitem)){

					$product = ContentGetByID($orderitem->getProductID());
					
					print "<tr><td>" .$product->getTitle() . "</td><td class=\"externalid\">".$product->getExternalID()."</td><td>" . round($orderitem->getQuantity()) . "</td><td>" . number_format($orderitem->getPrice(), 2, ',', ' ') . "</td><td class=\"right\">" . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ',', ' ') ."</td></tr>\n";
					$ordertotal += $orderitem->getQuantity() * $orderitem->getPrice();
				}
				//$freightcost = 50;
				
				
			if($order->getDiscount() > 0){
		
			$discount = round($ordertotal * 0.2);
			$ordertotal = round($ordertotal * 0.8);
			print '<tr id="discountrow"><td>Kampanjrabatt</td><td></td><td>20%</td><td> -'.number_format($discount,2,",", " " ) .'</td><td class="right">'.number_format($ordertotal,2,","," ").'</td></tr>';
		
			}

				if($ordertotal < 301)
					$freightcost = 36;
				else if($ordertotal < 1001)
					$freightcost = 48;
				else if ($ordertotal < 1 || $ordertotal > 1000)
					$freightcost = 0;
				
				// . MTextGet("VAT-incl") . " ". number_format(($ordertotal + $freightcost) * 0.2, 2,  ',', ' ')."

				print	"<tr><td class=\"transp\" colspan=\"2\"></td><td  class=\"transp\"></td><td class=\"transp\">" . MTextGet("carriage") . "</td><td class=\"sum\">".number_format($freightcost, 2, ',',' ')."</td></tr>";
				if($ordertotal > 0)
				print	"<tr><td class=\"transp\" colspan=\"2\"></td><td class=\"transp\"></td><td class=\"transp\">" . MTextGet("totalsum") . "</td><td class=\"sum\">" . number_format($ordertotal + $freightcost, 2, ',', ' ') . "</td></tr>";
				print	"</table>";
	}

	print	"</td></tr>";
	print	"<tr><td>";

	print	"Skapad: " .substr($order->getCreatedDate(), 0,10) . "&nbsp;";

	print	"&nbsp;&nbsp; Status: ";

	print	"<select id=\"setorderstatus\">" .
			"<option value=\"0\" ".($order->getStatus() == 0?"SELECTED":"").">Ej slutförd</option>" .
			"<option value=\"1\" ".($order->getStatus() == 1?"SELECTED":"").">Inskickad</option>" .
			"<option value=\"2\" ".($order->getStatus() == 2?"SELECTED":"").">Parkerad</option>" .
			"<option value=\"3\" ".($order->getStatus() == 3?"SELECTED":"").">Levererad</option>" .
			"<option value=\"4\" ".($order->getStatus() == 4?"SELECTED":"").">Slutförd</option>" .
			"</select>";

	print	" &nbsp;&nbsp;&nbsp;&nbsp; Levererad:  <input type=\"text\" class=\"dateinput\" name=\"deliverydate\" id=\"deliverydate\" value=\"". substr($order->getDeliveryDate(),0,10) ."\"/> ";
	print	"<input type=\"hidden\" id=\"orderid\" value=\"".$order->getID()."\"/>";
	print	"&nbsp;&nbsp;";
	print	"<input type=\"button\" class=\"deleteorder\" id=\"deleteorder_".$order->getID()."\" value=\"Radera order\"/>";
	print	"</td></tr></table>";
	
}

function deleteOrder($oid){
	OrderDelete($oid);
}

function orderSaveDetails($oid, $orderdetail, $orderdetailvalue){

	$order = OrderGetByID($oid);

	switch($orderdetail){
		case 'deliverydate':
		$order->setDeliveryDate($orderdetailvalue);
		break;
		case 'setorderstatus':
		$order->setStatus($orderdetailvalue);
		break;
	}
	OrderSave($order);
}


function loadscripts(){
	//print "<script type=\"text/javascript\" src=\"/trms-admin/js/order_admin_ajax.js\"></script>\n";
}
?>