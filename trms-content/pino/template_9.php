<?php
print	'<body class="main">';
print 	'<!-- Template 9 -->';
get_template('/template_navigation.php');
print 	'<div class="wrapper">';

print 	'<div id="product">'.	
		'	<div class="product-box">';
ImageGetWithIDNoSizeII($thiscontent->getID(), 1, "productimg", "more_" . $thiscontent->getID(), 3);
print 	'	</div>';

$text = ContentTextGet($thiscontent->getID(), 3);

print	'	<div class="product-box">'.
		'		<div class="product-box-inner" id="'.$text->getTextID(1).'">'. $text->getText(1).
		'		</div>';
// The META 
print 	'<div class="prod-info-and-buttons">'.
		'<h6>Pris: '.$thiscontent->getValue().' kr</h6>';
	  
$text = ContentTextGet($thiscontent->getID(), 2);
print	'<div class="metafacts">' . MTextGet($text->getTextID(2)) . '</div>'; //remove textedit here id="' .$text->getTextID(2). '"
	
// The Buttons
print 	'<div>';
	
// The cartbutton
print 	'<div class="cartbutton" title="Visa varukorgen" id="orderquant_'. $thiscontent->getID() .'">';
		  
		  if(isset($_SESSION['orderid']) && OrderItemGetQuantity($thiscontent->getID(),$_SESSION['orderid']))
		  print '<img src="/trms-content/pino/images/cartfull-wht.png" class="cartimg" />';
		  else
		  print '<img src="/trms-content/pino/images/cart-wht.png" class="cartimg" />';
		   
print 	'<div class="cartitems">'.(isset($_SESSION['orderid'])?number_format(OrderItemGetQuantity($thiscontent->getID(),$_SESSION['orderid'])):"0").'</div>'.
		'</div>';

// The buybutton
if($thiscontent->getFlag() == 1)
print '<div class="soldoutbutton" title="Slutsåld">SLUTSÅLD</div>';
else
print '<div class="buybutton" title="Klicka för att lägga varan i varukorgen" id="prodid_'.$thiscontent->getID().'">KÖP!</div>';

print '<div class="backbutton" title="Klicka för att gå till varulistan"><a href="/">TILLBAKA</a></div>'; 
print '</div>';
// closes Buttons
print '</div>';
// closes META
		

print	'	</div>'.
		'</div>';
print '</div>';

?>
