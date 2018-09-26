<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Orders.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Orders.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/PHPMailer-master/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/PHPMailer-master/src/SMTP.php';

DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["prid"]))$prid = $_REQUEST["prid"];
if(isset($_REQUEST["oid"]))$oid = $_REQUEST["oid"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"];
if(isset($_REQUEST["quantity"]))$quantity = $_REQUEST["quantity"];
if(isset($_REQUEST["orderitemid"]))$orderitemid = $_REQUEST["orderitemid"];
if(isset($_REQUEST["orderdetail"]))$orderdetail = $_REQUEST["orderdetail"];
if(isset($_REQUEST["orderdetailvalue"]))$orderdetailvalue= $_REQUEST["orderdetailvalue"];
if(isset($_REQUEST["discountcode"]))$discountcode= $_REQUEST["discountcode"];
if(isset($_REQUEST["shippingaddress"]))$shippingaddress= $_REQUEST["shippingaddress"];

if($action == "additem")
	addItem( $prid );
else if($action == "deleteitem")
	deleteItem( $oid, $prid );
else if($action == "saveorder")
	saveOrder( $oid );
else if($action == "sendorder")
	sendOrder( $oid, $cid );
else if($action == "setquantity")
	setOrderItemQuantity($orderitemid, $quantity);
else if($action == "setorderdetail")
	 setOrderDetail($oid, $orderdetail, $orderdetailvalue);
else if($action == "setshipaddress")
	setShippingAddressFlag($oid, $shippingaddress);
else if($action == "addiscount")
	addDiscount( $discountcode );
else if($action == "deleteorder")
	deleteOrder( $oid, $cid );
else
	defaultAction();

function defaultAction(){
	print "defaultAction";
}

function confirmOrder( $oid, $cid ){

	$content = ContentGetByID($cid);
	$ctext = ContentTextGet($content->getID(), 3);
	print	"<div id=\"orderinfo\">\n";
	print	MTextGet($ctext->getTextID());
	print	"</div>\n";

}

function missingDetails( $oid, $cid ){

	$content = ContentGetByID($cid);
	$ctext = ContentTextGet($content->getID(), 4);
	print	"<div id=\"orderinfo\">popopo\n";
	print	MTextGet($ctext->getTextID());
	print	"</div>\n";

}

function updateOrderRows(){

	if($order = OrderGetByID( getOrderID() ) ){

		print	"<table class=\"ordertable\">" .
			"<tr class=\"underlined\"><td class=\"head1\">Beskrivning</td><td class=\"head2\">Antal</td><td class=\"head3\">Pris</td><td class=\"head4\">Summa</td></tr>\n" ;
			$orderitem = OrderItemGetAllForOrder($order->getID());

			while($orderitem = OrderItemGetNext($orderitem)){

				$product = ContentGetByID($orderitem->getProductID());

				print "<tr><td>" .$product->getTitle() . "</td><td><input type=\"text\" class=\"quantity\" id=\"orderrow_".$orderitem->getID()."\" value=\"" . round($orderitem->getQuantity()) . "\"/></td><td>" . number_format($orderitem->getPrice(), 2, ',', ' ') . "</td><td class=\"right\">" . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ',', ' ') ."</td></tr>\n";
				$ordertotal += $orderitem->getQuantity() * $orderitem->getPrice();

			}
		if($order->getDiscount() > 0){

			$discount = round($ordertotal * 0.2);
			$ordertotal = round($ordertotal * 0.8);
			print '<tr id="discountrow"><td>Kampanjrabatt</td><td>20%</td><td> -'.number_format($discount,2,",", " " ) .'</td><td class="right">'.number_format($ordertotal,2,","," ").'</td></tr>';

		}

			if($ordertotal < 301)
				$freightcost = 36;
			else if($ordertotal < 1001)
				$freightcost = 48;
			else if ($ordertotal < 1 || $ordertotal > 1000)
				$freightcost = 0;
			// Moms ingår med: ". number_format(($ordertotal + $freightcost) * 0.2, 2,  ',', ' ')."
			print	"<tr><td rowspan=\"2\" class=\"transp_small\">* Obligatoriska fält<br/>** Du kan ändra i antalsrutan på orderraden. Skriver du 0 tas varan bort</td><td  class=\"transp\"></td><td>Frakt</td><td class=\"right\">".number_format($freightcost, 2, ',',' ')."</td></tr>";
			if($ordertotal > 0)
			print	"<tr><td class=\"transp\"></td><td>Summa:</td><td class=\"right\">" . number_format($ordertotal + $freightcost, 2, ',', ' ') . "</td></tr>";
			print	"</table>";

			OrderCalculateTotal( $order->getID() );
	}
}

function updateOrderDetails(){
	print	"UpdateOrderDetails";
}

function createOrder(){
	$order = new Order;
	$order->setCreatedDate(date("Y-m-d H:i:s"));
	$order->setResellerID(1);
	$_SESSION["orderid"] = OrderSave($order);
	return $order->getID();
}

function getOrderID(){

	if(isset($_SESSION['orderid']) && $_SESSION['orderid'] > 0 )
		return $_SESSION['orderid'];
	else
		return createOrder();
}

function addItem($prid){

	$orderid = getOrderID();
	OrderAddItem($orderid, $prid);
	OrderCalculateTotal($orderid);
}

function addDiscount($discountcode){

	$orderid = getOrderID();

	if($discountcode == DISCOUNTCODE){
		$order = OrderGetByID($orderid);
		$order->setDiscount(0.2);
		OrderSave($order);
	}

	OrderCalculateTotal($orderid);
	updateOrderRows();
}


function setOrderItemQuantity($orderitemid, $quantity){

	OrderItemSetQuantity($orderitemid, $quantity);
	updateOrderRows();
}

function setOrderDetail($oid, $orderdetail, $orderdetailvalue){

	$order = OrderGetByID($oid);

	switch($orderdetail){
		case 'orderfullname':
		$order->setLastName($orderdetailvalue);
		break;
		case 'ordercompanyname':
		$order->setCompanyName($orderdetailvalue);
		break;
		case 'orderaddress':
		$order->setAddress1($orderdetailvalue);
		break;
		case 'orderzip':
		$order->setZip($orderdetailvalue);
		break;
		case 'ordercity':
		$order->setCity($orderdetailvalue);
		break;
		case 'orderreference':
		$order->setCustomerRef($orderdetailvalue);
		break;
		case 'orderemail':
		$order->setContactEmail($orderdetailvalue);
		break;
		case 'ordertel':
		$order->setAddress2($orderdetailvalue);
		break;
		case 'ordermessage':
		$order->setCustomerComments($orderdetailvalue);
		break;
		case 'orderrecievermessage':
		$order->setComments($orderdetailvalue);
		break;
		case 'ordershipfullname':
		$order->setShipLastName($orderdetailvalue);
		break;
		case 'ordershipcompanyname':
		$order->setShipCompanyName($orderdetailvalue);
		break;
		case 'ordershipaddress':
		$order->setShipAddress1($orderdetailvalue);
		break;
		case 'ordershipzip':
		$order->setShipZip($orderdetailvalue);
		break;
		case 'ordershipcity':
		$order->setShipCity($orderdetailvalue);
		break;

	}
	OrderSave($order);
}

function setShippingAddressFlag($oid, $shippingaddress){

	$order = OrderGetByID($oid);
	if($shippingaddress == "true")
		$order->setResellerID(1);
	else{
		$order->setResellerID(0);
		$order->setShipLastName("");
		$order->setShipCompanyName("");
		$order->setShipAddress1("");
		$order->setShipZip("");
		$order->setShipCity("");
	}
	OrderSave($order);
}


function sendOrder($oid,$cid){

	$errors = 0;
	$order = OrderGetByID($oid);


	if(!strlen( $order->getLastName() )){$errors++; $fel .= ' lastname';}
	if(!strlen( $order->getAddress1() )){$errors++; $fel .= ' adress1';}
	if(!strlen( $order->getZip() )){$errors++; $fel .= ' zip';}
	if(!strlen( $order->getCity() )){$errors++; $fel .= ' city';}
	if(!strlen( $order->getAddress2() )){$errors++; $fel .= ' adress2';}
	if(!strlen( $order->getContactEmail() )){$errors++; $fel .= ' email';}

	if($errors == 0){

		$orderstyles =		"<style type=\"text/css\">\n" .
							"table.orderrows{width:100%; border-width:0; border-style:hidden;  border-collapse: collapse;background-color:#DDDDDD}\n" .
							"table.orderrows td.head1{font-size: 12px; background-color:#DDDDDD; border-bottom:1px solid #FFFFFF; padding:3px 5px;width:60%}\n" .
							"table.orderrows td.head2{font-size: 12px; background-color:#DDDDDD; border-bottom:1px solid #FFFFFF; padding:3px 5px;width:10%}\n" .
							"table.orderrows td.head3{font-size: 12px; background-color:#DDDDDD; border-bottom:1px solid #FFFFFF; padding:3px 5px;width:15%}\n" .
							"table.orderrows td.head4{text-align:right;font-size: 12px; background-color:#DDDDDD; border-bottom:1px solid #FFFFFF; padding:3px 5px;width:15%}\n" .
							"table.orderrows td{border:1px solid white;padding:4px}\n" .
							"table.orderrows td.transp{background-color:#DDDDDD; border:1px solid #DDDDDD}\n" .
							"table.orderrows td.sum{background-color:#DDDDDD; border:1px solid #DDDDDD; text-align:right}\n" .
							"table.orderrows td.right{text-align:right}\n" .
							"table.orderrows td.externalid{vertical-align:bottom;font-size:12px;width:16%}\n" .
							"table.orderdetails{margin:10px 0 20px 0;width:100%; border-width:2px; border-style:visible; border-collapse: none; background-color:#DDDDDD;}\n" .
							"table.orderdetails td{ border-width:0; padding:2px; margin:3px}\n" .
							"table.orderdetails td.left{padding:0;margin:0;width:150px;vertical-align:top}\n" .
							"table.orderdetails td.label{width:100px; padding:0 0 0 10px}\n" .
							"table.orderdetails td.value{width:150px;background-color:#EEEEEE;}\n" .
							".whitehead{font-family:serif;font-size:20px;color:#FFFFFF;font-weight:normal;padding:0 10px 0 0}\n" .
							"#box_head{width:804px;font-family:serif;font-size:24px;color:#FFFFFF; padding:3px 10px 3px 10px;background-color:#7FA5BD}\n" .
							"#box_body{border: 2px #7FA5BD solid; padding:0 10px;background-color:#DDDDDD;width:800px;}\n" .
							"#box_body_top{}\n" .
							".widetable{border-collapse:collapse; width:100%; margin: 0 0 10px 0;}\n" .
							"h4{margin:10px 0 0 0;}\n".
							"</style>\n";


		$ordercontent .=	"<div id=\"box_body\">\n" .
							"<div id=\"box_body_top\" >\n" .
							"<table class=\"widetable\"><tr><td>\n" .
							"<table class=\"orderdetails\">\n" .
							"<tr><td class=\"left\" rowspan=\"6\">" .
							"<span class=\"whitehead\">Ordernr: ". $order->getID() . "</span></td><td class=\"label\" colspan=\"4\"><h4>". MTextGet("invoiceaddress") ."</h4></td></tr>" .
							"<tr>".
							"	<td class=\"label\">Namn:</td>" .
							"	<td class=\"value\">" . $order->getLastName() . "</td>".
							"	<td class=\"label\">Adress:</td>" .
							"	<td class=\"value\">" . $order->getAddress1()."</td>" .
							"</tr>\n";

		$contentplain .=	"Order www.pino.se ID:" .  $order->getID() . "\n" .
							"=================================================\n";
		$contentplain .=	"FAKTURA";
							if( $order->getResellerID() == 0 )
		$contentplain .=	" & LEVERANS";
		$contentplain .=	"ADRESS\n";


		$ordercontent .=	"<tr> ".
							"	<td class=\"label\">Företag/skola:</td>".
							"	<td class=\"value\">" . $order->getCompanyName() . "</td>".
							"	<td class=\"label\">Postnummer:</td><td class=\"value\">". $order->getZip() ."</td>".
							"</tr>\n" .
							"<tr>".
							"	<td class=\"label\">E-post:</td><td class=\"value\">" . $order->getContactEmail()."</td>".
							"	<td class=\"label\">Ort:</td><td class=\"value\">" . $order->getCity()."</td>".
							"</tr>\n";

		$ordercontent .=	"<tr>".
							"	<td class=\"label\">Telefon:</td><td class=\"value\">" . $order->getAddress2() ."</td>".
							"	<td class=\"label\">Referens:</td><td class=\"value\">" . $order->getCustomerRef() ."</td>".
							"</tr>\n" .
							"<tr>".
							"	<td class=\"label\">Meddelande:</td><td colspan=\"3\" class=\"value\">" . $order->getCustomerComments() . "</td>".
							"</tr>\n";

		$contentplain .=	"Namn:\t\t\t" . $order->getLastName() . "\n".
							"Företag/skola:\t\t" .  $order->getCompanyName() . "\n".
							"Adress:\t\t\t" . $order->getAddress1() . "\n".
							"Postnr:\t\t\t" . $order->getZip() . "\n".
							"Ort:\t\t\t" . $order->getCity() . "\n".
							"E-post:\t\t\t" . $order->getContactEmail() . "\n".
							"Telefon:\t\t" . $order->getAddress2() . "\n".
							"Referens:\t\t" . $order->getCustomerRef() . "\n".
							"Meddelande:\t\t\n" . $order->getCustomerComments() . "\n".
							"=================================================\n";

		if( $order->getResellerID() == 1 ){

		$ordercontent .=	"<tr><td class=\"left\" rowspan=\"6\">&nbsp;</td><td class=\"label\" colspan=\"4\"><h4>" . MTextGet("deliveryaddress") . "</h4></td></tr>" .
							"<tr>".
							"	<td class=\"label\">Namn:</td>" .
							"	<td class=\"value\">" . $order->getShipLastName()."</td>".
							"	<td class=\"label\">Adress:</td>" .
							"	<td class=\"value\">" . $order->getShipAddress1()."</td>" .
							"</tr>\n";


		$ordercontent .=	"<tr>".
							"	<td class=\"label\">Företag/skola:</td>".
							"	<td class=\"value\">" . $order->getShipCompanyName()."</td>".
							"	<td class=\"label\">Postnummer:</td><td class=\"value\">" . $order->getShipZip()."</td>".
							"</tr>\n" .
							"<tr>".
							"	<td></td><td></td>".
							"	<td class=\"label\">Ort:</td><td class=\"value\">" . $order->getShipCity()."</td>".
							"</tr>\n" .
							"<tr>" .
							"<td class=\"label\">Meddelande till mottagaren:</td><td colspan=\"3\" class=\"value\">" . $order->getComments() . "</td>" .
							"</tr>\n";

		$contentplain .=	"LEVERANSADRESS\n";
		$contentplain .=	"Namn:\t\t\t" .  $order->getShipLastName() . "\n".
							"Företag/skola:\t\t" . $order->getShipCompanyName() . "\n".
							"Adress:\t\t\t" . $order->getShipAddress1() . "\n".
							"Postnr:\t\t\t" . $order->getShipZip() . "\n".
							"Ort:\t\t\t" . $order->getShipCity() . "\n".
							"Meddelande/Info:\t\n" . $order->getComments() . "\n".
							"=================================================\n";
		}

		$ordercontent .=	"</table>\n";

		$ordercontent .=	"<table class=\"orderrows\">" .
							"<tr class=\"underlined\"><td colspan=\"2\" class=\"head1\">.." . MTextGet("description") . "</td><td class=\"head2\">" . MTextGet("quantity") . "</td><td class=\"head3\">" . MTextGet("price") . "</td><td class=\"head4\">" . MTextGet("sum") . "</td></tr>\n" ;

					$orderitem = OrderItemGetAllForOrder($order->getID());

					while($orderitem = OrderItemGetNext($orderitem)){

						$product = ContentGetByID($orderitem->getProductID());

						$ordercontent .= "<tr><td>" .$product->getTitle() . "</td><td class=\"externalid\">".$product->getExternalID()."</td><td>" . round($orderitem->getQuantity()) . "</td><td>" . number_format($orderitem->getPrice(), 2, ',', ' ') . "</td><td class=\"right\">" . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ',', ' ') ."</td></tr>\n";
						$ordertotal += $orderitem->getQuantity() * $orderitem->getPrice();

						$contentplain .= $product->getTitle() . "\n" . $product->getExternalID() . "\t" . round($orderitem->getQuantity()) . " x " . number_format($orderitem->getPrice(), 2, ',', ' ') . " = " . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ',', ' ') . "\n";
					}

		if($order->getDiscount() > 0){

			$discount = round($ordertotal * 0.2);
			$ordertotal = round($ordertotal * 0.8);
			$ordercontent .= '<tr id="discountrow"><td>Kampanjrabatt</td><td></td><td>20%</td><td> -'.number_format($discount,2,",", " " ) .'</td><td class="right">'.number_format($ordertotal,2,","," ").'</td></tr>';

			$contentplain .=  "Kampanjrabatt \t\t 20% -". number_format($discount,2,',', ' ' ) . " \t" . number_format($ordertotal,2,',',' ') . "\n";
		}


					if($ordertotal < 301)
						$freightcost = 36;
					else if($ordertotal < 1001)
						$freightcost = 48;
					else if ($ordertotal < 1 || $ordertotal > 1000)
						$freightcost = 0;

					$ordercontent .= "<tr><td class=\"transp\" colspan=\"2\"></td><td  class=\"transp\"></td><td class=\"transp\">" . MTextGet("carriage") . "</td><td class=\"sum\">".number_format($freightcost, 2, ',',' ')."</td></tr>";
					if($ordertotal > 0)
					$ordercontent .= "<tr><td class=\"transp\" colspan=\"2\"></td><td class=\"transp\"></td><td class=\"transp\">" . MTextGet("totalsum") . "</td><td class=\"sum\">" . number_format($ordertotal + $freightcost, 2, ',', ' ') . "</td></tr>";
					$ordercontent .= "</table>";

					$contentplain .=	"=================================================\n".
										"Moms ingår i alla priser\n".
										"\t\t\t Frakt: \t" .  number_format($freightcost, 2, ',',' ') . "\n" .
										"\t\t\t Ordertotal:\t" . number_format($ordertotal + $freightcost, 2, ',', ' ');

		$ordercontent .= "</td></tr>";
		$ordercontent .= "<tr><td>";
		$ordercontent .=	"</td></tr></table>" .
							"</td></tr></table>\n" .
							"</div>\n" .
							"</div>\n";
		///////////////////////////

		$headers = "From: shop@pino.se\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		$headersplain .= "From: Pino webshop <".$order->getContactEmail().">\r\n";
		$headersplain .= "MIME-Version: 1.0\r\n";
		$headersplain .= "Content-Type: text/plain; charset=UTF-8\r\n";

		$subject = "Pinoorder: " . $order->getID();

		//$mailcontent .= "<html><div id=\"box_head\">Order www.pino.se ".substr($order->getCreatedDate(), 0,10)."</div>\n" .$orderstyles . $ordercontent ."</html>";

		$mailcontent = $contentplain;

		$customermailcontent .=	"<html><div id=\"box_head\">Orderbekräftelse www.pino.se ".substr($order->getCreatedDate(), 0,10)."</div>\n" . $orderstyles . $ordercontent . "</html>";;

		//mail("pinolek@telia.com,niklas@entertainment.se", $subject, $mailcontent, $headersplain);
		//mail("niklas@entertainment.se", $subject, $mailcontent, $headersplain);
		//mail($order->getContactEmail(), $subject, $customermailcontent, $headers);
		
		/// Confirmation mail to customer
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = MAILHOST;
		$mail->Port = MAILPORT;
		$mail->Username = MAILUSER;
		$mail->Password = MAILPSWD;
		$mail->SetFrom('info@entertainment.se', 'Pinolek');
		$mail->Subject = $subject;
		$address = $order->getContactEmail();
		$name = "";
		$mail->MsgHTML($customermailcontent);
		$mail->AddAddress($address, $name);
		$mail->Send();
		
		/// Confirmation mail to Pinolek
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = MAILHOST;
		$mail->Port = MAILPORT;
		$mail->Username = MAILUSER;
		$mail->Password = MAILPSWD;
		$mail->SetFrom('info@entertainment.se', 'Pinolek');
		$mail->Subject = $subject;
		$address = 'niklas@entertainment.se';
		$name = "";
		$mail->MsgHTML($mailcontent);
		$mail->AddAddress($address, $name);
		$mail->Send();
		

		$order->setStatus(1);
		$order->setPaymentMethod(0);
		OrderSave($order);

		if( UserCheckAccountName($order->getContactEmail()) < 1 ){
			$customer = new User;

			$customer->setFullName($order->getLastName());
			$customer->setAddressID(81);
			$customer->setUserGroupID(23);
			$customer->setAccountName($order->getContactEmail());
			$customer->setAddress($order->getAddress1());
			$customer->setZip($order->getZip());
			$customer->setCity($order->getCity());
			$customer->setTel( $order->getAddress2());
			$customer->setEMail($order->getContactEmail());

			UserSave($customer);
		}

		$_SESSION['orderid'] = 0;

		confirmOrder( $oid, $cid );

	}else{

		print $fel . '<br/>';
		missingDetails($oid,$cid);
	}




}

function deleteOrder($oid,$cid){

	OrderDelete($oid);
	$_SESSION['orderid'] = 0;

	$content = ContentGetByID($cid);
	$ctext = ContentTextGet($content->getID(), 2);
	print	"<div id=\"orderinfo\">\n";
	print	MTextGet($ctext->getTextID());
	print	"</div>\n";

}

?>
