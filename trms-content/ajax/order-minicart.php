<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Orders.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Orders.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
DBcnx();
if($_SESSION['orderid'] > 0){
print '<div id="close-minicart">x</div>';
print '<h6>Varukorgen</h6>';
$total = 0;
$orderitem = OrderItemGetAllForOrder($_SESSION['orderid']);
			print '<ul>';
			while($orderitem = OrderItemGetNext($orderitem)){
				$product = ContentGetByID($orderitem->getProductID());
				
				print  '<li class="orderrow-minicart">';
				ImageGetII_SMALL($product->getID(), 1, ""); 
				print ' '. round($orderitem->getQuantity()) . ' st, ' .$product->getTitle(). ', '.round($orderitem->getQuantity())*$product->getValue().' kr</li>';
				$total += round($orderitem->getQuantity())*$product->getValue(); 
			}
			print  '<li class="orderrow-minicart">Summa: '.$total.' kr</li>';
			
			print '</ul>';
			
print '<a href="/order"><div id="gotocheckout">Gå till kassan &#187;</div></a>';
}else{
print '<div id="close-minicart">x</div>'.
	  '<h6>Varukorgen</h6>'.
	  'Varukorgen är tom';
}
?>