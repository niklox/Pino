<?php

if(isset($_SESSION['orderid']))$orderid = $_SESSION['orderid']; else $orderid = -1;

//if($orderid > 0)
//print	'<ul id="tabs"><li id="info" class="inactive">ORDERINFORMATION</li><li class="active" id="basket">VARUKORG</li></ul>';
//else
//print	'<ul id="tabs"><li  id="info" class="active">ORDERINFORMATION</li><li class="inactive" id="basket">VARUKORG</li></ul>';

print	'<div id="order">';

if($orderid > 0){

	$order = OrderGetByID($orderid);
	print	'<div id="orderbasket">';
	print	'<div id="orderinfo" class="top">';
	//ImagePrint("kassaapparat", "");
	
	print	'<div id="orderrows">';
	print	'<table class="ordertable">' .
			'<tr><td class="head1">Beskrivning</td><td class="head2">Antal **</td><td class="head3">Pris</td><td class="head4">Summa</td></tr>';
	$orderitem = OrderItemGetAllForOrder($order->getID());
	$tab = 16;
	while($orderitem = OrderItemGetNext($orderitem)){
		$tab++;
		$product = ContentGetByID($orderitem->getProductID());
		print '<tr><td>' .$product->getTitle() . '</td><td><input type="text" class="quantity" id="orderrow_'.$orderitem->getID().'" value="' . round($orderitem->getQuantity()) . '" tabindex="'.$tab.'"/></td><td>' . number_format($orderitem->getPrice(), 2, ",", " ") . '</td><td class="right">' . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ",", " ") .'</td></tr>';
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

	//Moms ingår med: ". number_format(($ordertotal + $freightcost) * 0.2, 2,  ',', ' ')."
	print	'<tr><td rowspan="2" class="transp_small">* Obligatoriska fält<br/>** Du kan ändra i antalsrutan på orderraden. Skriver du 0 tas varan bort<br/>'.

			'</td>'.
			'<td  class="transp"></td><td>Frakt</td><td class="right">'.number_format($freightcost, 2, ","," ").'</td></tr>';
	if($ordertotal > 0)
	print	'<tr><td class="transp"></td><td>Summa:</td><td class="right">' . number_format($ordertotal + $freightcost, 2, ",", " ") . '</td></tr>';
	print	'</table>';
	print	'</div>';

	print	'<h6 id="invoiceaddress">Fakturaadress</h6>';
	print	'<form>';
	print 	'<input type="text" id="orderfullname" value="' . $order->getLastName().'" tabindex="1" placeholder="För och efternamn *"/>'.
		 	'<input type="text" id="orderaddress" value="' . $order->getAddress1().'" tabindex="2" placeholder="Adress *"/>' .
			'<input type="text" id="orderzip" value="' . $order->getZip() . '" tabindex="3" placeholder="Postnummer *"/>'.
			'<input type="text" id="ordercity" value="' . $order->getCity().'" tabindex="4" placeholder="Ort *"/>'.
			'<input type="text" id="ordercompanyname" value="' . $order->getCompanyName() . '" tabindex="5" placeholder="Skola/Organisation"/>'.
			'<input type="text" id="orderemail" value="' . $order->getContactEmail().'" tabindex="6" placeholder="Epost *"/>'.		
			'<input type="text" id="ordertel" value="' . $order->getAddress2() .'" tabindex="7" placeholder="Telefon *"/>'.
			'<input type="text" id="orderreference" value="' . $order->getCustomerRef() .'" tabindex="8" placeholder="Referens"/>'.
			'<textarea id="ordermessage" placeholder="Skriv in meddelande och eventuell rabattkod här.  Har du en rabattkod dras rabatten av manuellt på fakturan du får men syns inte på ordern på denna sida." tabindex="9">' . $order->getCustomerComments() . '</textarea>';

	

	print	'<input class="left" type="checkbox" tabindex="10" id="shippingaddress" '.($order->getResellerID() == 0?"CHECKED":"").'/>';

	print	'Faktura och leveransadressen är samma';
			
	print	'<input type="hidden" id="orderid" value="'.$order->getID().'"/>';
	

	print	'<div id="orderheadship">';
	print	'<h6>Leveransadress</h6>';
	print 	'<input type="text" id="ordershipfullname" value="' . $order->getShipLastName().'" tabindex="11" placeholder="Namn (referens leverans)"/>'.
			'<input type="text" id="ordershipcompanyname" value="' . $order->getShipCompanyName().'" tabindex="12" placeholder="Skola/Organisation">'.
			'<input type="text" id="ordershipaddress" value="' . $order->getShipAddress1().'" tabindex="13" placeholder="Leveransadress"/>'.
			'<input type="text" id="ordershipzip" value="' . $order->getShipZip().'" tabindex="14" placeholder="Postnummer"/>'.
			'<input type="text" id="ordershipcity" value="' . $order->getShipCity().'" tabindex="15" placeholder="Ort (leveransadress)"/>'.
			'<textarea id="orderrecievermessage" tabindex="16" placeholder="Message">' . $order->getComments() . '</textarea>';
			
	print	'</div>';

	print	'</div>';
	
	
	


	print	'<div id="orderbuttons">'.
			//'<input type="text" name="discountcode" id="discountcode" placeholder="Skriv in eventuell rabattkod här ***" value=""/>'.
			//'<input type="text" name="discountbutton" id="discountbutton" value="BEKRÄFTA KOD"/>'.
	 		'<input type="button" id="deleteorder" value="TA BORT ORDER" />'.
	 		'<input type="button" id="sendorder" value="SKICKA" />'.
	 		'</div>';
	print	'</form>';
	print	'</div>';
}else{

	print	'<div id="orderbasket" class="under">' .
			'<div id="orderinfo">asdasd';
?>
	<?php ImageGet($thiscontent->getID(), 2, "pinochoice") ?>
	<div id="<?php $thiscontent->getTextID(2) ?>"><?php $thiscontent->getText(2) ?> </div>

<?php
	print	'</div>' .
			'</div>';
}
if($orderid > 0)
print	'<div id="ordergenericinfo" class="under">';
else
print	'<div id="ordergenericinfo" class="top">';
?>

	<?php ImageGet($thiscontent->getID(), 1, "rightimage") ?>
	<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
</div>
</div>
