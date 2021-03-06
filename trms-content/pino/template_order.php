<?php

if(isset($_SESSION['orderid']))$orderid = $_SESSION['orderid']; else $orderid = -1;
/*
if($orderid > 0)
print	'<ul id="tabs"><li id="info" class="inactive">ORDERINFORMATION</li><li class="active" id="basket">VARUKORG</li></ul>';
else
print	'<ul id="tabs"><li  id="info" class="active">ORDERINFORMATION</li><li class="inactive" id="basket">VARUKORG</li></ul>';
*/
print	'<div id="order">';
		
//print 	'Orderid: ' . $orderid;
if($orderid > 0){
	
	$order = OrderGetByID($orderid);
	print	'<div id="orderbasket">';
	print	'<div id="orderinfo" class="top">'.
			'<h4>Min beställning</h4>';
	//ImagePrint("kassaapparat", "");
	
	print	'<div id="orderrows">';
	
	print	'<table class="ordertable">' .
			'<tr><td class="head1">Beskrivning</td><td class="head2">Antal</td><td class="head3">Pris</td><td class="head4">Summa</td></tr>';
	$orderitem = OrderItemGetAllForOrder($order->getID());
	$tab = 0;
	while($orderitem = OrderItemGetNext($orderitem)){
		$tab++;
		$product = ContentGetByID($orderitem->getProductID());
		print '<tr>'.
				'<td>' .$product->getTitle() .'</td>'.
				'<td>'.
				'<div class="pcs">'.
				'<input type="text" class="quantity" id="orderrow_'.$orderitem->getID().'" value="' . round($orderitem->getQuantity()) . '" tabindex="'.$tab.'"/>'.
				'<div class="add" id="add_'.$orderitem->getID().'">+</div>'.
				'<div class="sub" id="sub_'.$orderitem->getID().'">-</div>'.
				'</div></td><td>' . number_format($orderitem->getPrice(), 2, ",", " ") . '</td>'.
				'<td class="right">' . number_format($orderitem->getQuantity() * $orderitem->getPrice(), 2, ",", " ") .'</td>'.
				'</tr>';
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
	print	'<tr><td rowspan="2" colspan="2" class="transp_small"><br/>'.

			'</td>'.
			'<td>Frakt</td><td class="right">'.number_format($freightcost, 2, ","," ").'</td></tr>';
	if($ordertotal > 0)
	print	'<tr><td>Summa:</td><td class="right">' . number_format($ordertotal + $freightcost, 2, ",", " ") . '</td></tr>';
	print	'</table>';
	print	'</div>';
	
	
	
	// organisation or private
	// print	'<input class="left" type="checkbox" tabindex="'.++$tab.'" id="organisation-private" '.($order->getResellerID() & 2?"CHECKED":"").'/>';
	// print	'<span class="infotext"> Vi är en förskola/företag/organisation</span>';
	
	print 	'<label class="container">Vi är en förskola/företag/organisation' .
  			'	<input type="checkbox" id="organisation-private" '.($order->getResellerID() & 2?"CHECKED":"").'>' .
  			'	<span class="checkmark"></span>' .
			'</label>';
	
	
	print	'<input type="button" class="gotoshop" value="Fortsätt handla">';
	print	'<form>';
	print 	'<div id="orderhead">';
	
	if( $order->getResellerID() & 1 )
	print	'<h4 id="invoiceaddress" class="orderheaders">Fakturaadress</h4>';
	else
	print	'<h4 id="invoiceaddress" class="orderheaders">Faktura & leveransadress</h4>';
	print 	'<div id="ordercompanyfields">';
	
	if( $order->getResellerID() & 2 )
	print	'<input type="text" class="orderdetails" id="ordercompanyname" value="' . $order->getCompanyName() . '"tabindex="'.++$tab.'"placeholder="Förskola/Organisation/Företag"/>'.
			'<input type="text" class="orderdetails" id="orderreference" value="' . $order->getCustomerRef() .'" tabindex="'.++$tab.'" placeholder="Ev. referensnr, rekvisition etc."/>';
	
	print 	'</div>';
	
	print 	'<input type="text" class="orderdetails" id="orderfullname" value="' . $order->getLastName().'" tabindex="'.++$tab.'" placeholder="För och efternamn *"/>'.
		 	'<input type="text" class="orderdetails" id="orderaddress" value="' . $order->getAddress1().'" tabindex="'.++$tab.'" placeholder="Adress *"/>' .
			'<input type="text" class="orderdetails" id="orderzip" value="' . $order->getZip() . '" tabindex="'.++$tab.'" placeholder="Postnummer *"/>'.
			'<input type="text" class="orderdetails" id="ordercity" value="' . $order->getCity().'"tabindex="'.++$tab.'" placeholder="Ort *"/>'.
			'<input type="text" class="orderdetails" id="orderemail" value="' . $order->getContactEmail().'" tabindex="'.++$tab.'" placeholder="Epost *"/>'.		
			'<input type="text" class="orderdetails" id="ordertel" value="' . $order->getAddress2() .'" tabindex="'.++$tab.'" placeholder="Telefon *"/>'.
			'<textarea class="orderdetails" id="ordermessage" placeholder="Skriv in meddelande och eventuell rabattkod här.  Har du en rabattkod dras rabatten av manuellt på fakturan du får men syns inte på ordern på denna sida." tabindex="'.++$tab.'">' . $order->getCustomerComments() . '</textarea>';

	print	'<input type="hidden" id="orderid" value="'.$order->getID().'"/>';
	
	print 	'</div>'; 

	//print	'<input class="left" type="checkbox" tabindex="'.++$tab.'" id="shippingaddress" '.($order->getResellerID() & 1?"CHECKED":"").'/>';
	//print	'<span class="infotext"> Jag vill att varorna levereras till en annan adress eller skickas som gåva till någon</span>';
	
	print 	'<label class="container">Jag vill att varorna levereras till en annan adress eller skickas som gåva till någon'.
  			'<input type="checkbox" id="shippingaddress" '.($order->getResellerID() & 1?"CHECKED":"").'>'.
    		'<span class="checkmark"></span>'.
    		'</label>';
	
	print	'<div id="orderheadship">';
	if($order->getResellerID() & 1 ){
	print	'<h4>Leveransadress</h4>';
	
	print 	'<div id="deliverycompanyname">';
	// separate shipping adress
	if($order->getResellerID() & 2 )
	print 	'<input type="text" class="orderdetails" id="ordershipcompanyname" value="' . $order->getShipCompanyName().'" tabindex="'.++$tab.'" placeholder="Mottagare förskola/organisation/företag">';
	print 	'</div>';
	
	print 	'<input type="text" class="orderdetails" id="ordershipfullname" value="' . $order->getShipLastName().'" tabindex="'.++$tab.'" placeholder="Mottagarens för- och efternamn"/>'.
			'<input type="text" class="orderdetails" id="ordershipaddress" value="' . $order->getShipAddress1().'" tabindex="'.++$tab.'" placeholder="Adress"/>'.
			'<input type="text" class="orderdetails" id="ordershipzip" value="' . $order->getShipZip().'" tabindex="'.++$tab.'" placeholder="Postnummer"/>'.
			'<input type="text" class="orderdetails" id="ordershipcity" value="' . $order->getShipCity().'" tabindex="'.++$tab.'" placeholder="Ort"/>'.
			'<textarea class="orderdetails" id="orderrecievermessage" tabindex="'.++$tab.'" placeholder="Ev. meddelande eller hälsning till mottagaren">' . $order->getComments() . '</textarea>';
	}
	print	'</div>';
	
	print	'</div>';
	print	'<div id="orderbuttons">'.
			//'<input type="text" name="discountcode" id="discountcode" placeholder="Skriv in eventuell rabattkod här ***" value=""/>'.
			//'<input type="text" name="discountbutton" id="discountbutton" value="BEKRÄFTA KOD"/>'.
	 		'<input type="button" id="deleteorder" value="TA BORT BESTÄLLNING" />'.
	 		'<input type="button" id="sendorder" value="SKICKA" />'.
	 		'</div>';
	print	'</form>';
	print	'</div>';
}else{

	print	'<div id="orderbasket" class="under">' .
			'<div id="orderinfo">';
?>
	<?php //ImageGet($thiscontent->getID(), 2, "pinochoice") ?>
	<div id="<?php $thiscontent->getTextID(2) ?>"><?php $thiscontent->getText(2) ?> aasdasd</div>

<?php
	print	'</div>' .
			'</div>';
}
if($orderid > 0)
print	'<div id="ordergenericinfo" class="under">';
else
print	'<div id="ordergenericinfo" class="top">';



?>

	<?php //ImageGet($thiscontent->getID(), 1, "rightimage") ?>
	<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
	
</div>
</div>
