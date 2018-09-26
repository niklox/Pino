<?php

if(isset($_SESSION['orderid']))$orderid = $_SESSION['orderid']; else $orderid = -1;

if($orderid > 0)
print	'<ul id="tabs"><li id="info" class="inactive">ORDERINFORMATION</li><li class="active" id="basket">VARUKORG</li></ul>';
else
print	'<ul id="tabs"><li  id="info" class="active">ORDERINFORMATION</li><li class="inactive" id="basket">VARUKORG</li></ul>';

print	'<div id="order">';

if($orderid > 0){

	$order = OrderGetByID($orderid);
	print	'<div id="orderbasket">';
	print	'<div id="orderinfo" class="top">' .
			'<div id="orderheadleft"><h4 class="blue">Min order</h4>';
	ImagePrint("kassaapparat", "");

	print	'</div>';
	print	'<div id="orderheadright">';
	print	'<h6 id="invoiceaddress" class="blue">Fakturaadress</h6>';
	print	'<form>';
	print	'<table class="orderdetails">';
	print	'<tr>'.
			'<td class="lblleft"><label for="orderfullname">Namn:*</label></td>' .
			'<td><input type="text" id="orderfullname" class="orderdetails" value="' . $order->getLastName().'" tabindex="1"/></td>'.
			'<td class="lblright">Företag/skola:</td>'.
			'<td><input type="text" id="ordercompanyname" class="orderdetails" value="' . $order->getCompanyName() . '" tabindex="5"/></td>'.

			'</tr>';

	print	'<tr>'.
			'<td class="lblleft"><label for="orderaddress">Adress:*</a></td>' .
			'<td><input type="text" id="orderaddress" class="orderdetails" value="' . $order->getAddress1().'" tabindex="2"/></td>' .
			'<td class="lblright"><label for="orderemail">E-post:*</label></td><td><input type="text" id="orderemail" class="orderdetails" value="' . $order->getContactEmail().'" tabindex="6"/></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lblleft"><label for="orderzip">Postnummer:*</label></td><td><input type="text" id="orderzip" class="orderdetails" value="' . $order->getZip() . '" tabindex="3"/></td>'.
			'<td class="lblright"><label for="ordertel">Telefon:*</label></td><td><input type="text" id="ordertel" class="orderdetails" value="' . $order->getAddress2() .'" tabindex="7"/></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lblleft"><label for="ordercity">Ort:*</label></td><td><input type="text" id="ordercity" class="orderdetails" value="' . $order->getCity().'" tabindex="4"/></td>'.
			'<td class="lblright">Referensnr:</td><td><input type="text" id="orderreference" class="orderdetails" value="' . $order->getCustomerRef() .'" tabindex="8"/></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lbltop">Meddelande:</td><td colspan="3"><textarea id="ordermessage" placeholder="Skriv in meddelande och eventuell rabattkod här.  Har du en rabattkod dras rabatten av manuellt på fakturan du får men syns inte på ordern på denna sida." class="orderdetails" tabindex="9">' . $order->getCustomerComments() . '</textarea></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lbltop"></td><td colspan="3">';

	print	'<input class="left" type="checkbox" tabindex="10" id="shippingaddress" '.($order->getResellerID() == 0?"CHECKED":"").'/>';

	print	'Faktura och leveransadressen är samma</td>'.
			'</tr>';
	print	'<input type="hidden" id="orderid" value="'.$order->getID().'"/>';
	print	'</table>';

	print	'<div id="orderheadship">';
	print	'<h6 class="blue">Leveransadress</h6>';
	print	'<table class="orderdetails">';
	print	'<tr>'.
			'<td class="lblleft">Namn:</td>' .
			'<td><input type="text" id="ordershipfullname" class="orderdetails" value="' . $order->getShipLastName().'" tabindex="11"/></td>'.
			'<td class="lblright">Adress:</td>' .
			'<td><input type="text" id="ordershipaddress" class="orderdetails" value="' . $order->getShipAddress1().'" tabindex="13"/></td>'.
			'</tr>';


	print	'<tr>'.
			'<td class="lblleft">Företag/skola:</td>'.
			'<td><input type="text" id="ordershipcompanyname" class="orderdetails" value="' . $order->getShipCompanyName().'" tabindex="12"/></td>'.
			'<td class="lblright">Postnummer:</td><td><input type="text" id="ordershipzip" class="orderdetails" value="' . $order->getShipZip().'" tabindex="14"/></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lblleft"</td><td></td>'.
			'<td class="lblright">Ort:</td><td><input type="text" id="ordershipcity" class="orderdetails" value="' . $order->getShipCity().'" tabindex="15"/></td>'.
			'</tr>';

	print	'<tr>'.
			'<td class="lbltop">Meddelande till mottagaren:</td><td colspan="3"><textarea id="orderrecievermessage" class="orderdetails" tabindex="16">' . $order->getComments() . '</textarea></td>'.
			'</tr>';

	print	'</table>';
	print	'</div>';

	print	'</div>';

	print	'</div>';

	print	'<div id="orderrows">';
	print	'<table class="ordertable">' .
			'<tr class="underlined"><td class="head1">Beskrivning</td><td class="head2">Antal **</td><td class="head3">Pris</td><td class="head4">Summa</td></tr>';
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
			'<div id="orderinfo">';
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
