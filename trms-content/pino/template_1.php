<?php
if(isset($_SESSION['orderid']))$orderid = $_SESSION['orderid']; else $orderid = -1;
print	'<body class="main">';
print 	'<!-- Template 1 -->';

get_template('/template_navigation.php');

print '<!--' . $thiscontent->getID() .  '-->';

print	'<div class="headarea blue">'.
		'	<div class="pinothebearhead">'.
		'	<img src="/images/pinothebear-txt_I000776_-1.png" alt="Pino" />'.
		'	</div>'.
		'<div class="figures">'.
		'<img src="/trms-content/pino/images/pino-friends.png" alt="Pino" />'.
		
		'</div>'.
		'<div class="welcometext">Välkommen till Pinos butik!</div>'.
		'</div>';
print	'<div class="wrapper">'.
		'<div class="masonry">';

$products = ContentGetAllInNode(ContentGetList(219, 0));
while($products = ContentGetNext($products)){
	if($products->getFlag()) continue;
	print '<div class="brick"><a href="/'.$products->getPermalink().'.html">';
	
	ImageGetWithIDNoSizeII($products->getID(), 1, "productimg", "more_" . $products->getID(), 2);
	
	print '</a>';
	
	$text = ContentTextGet($products->getID(), 1);
	print '<div id="' .$text->getTextID(1). '">' . MTextGet($text->getTextID()) . '</div>';
	
	// The META 
	print '<div class="prod-info-and-buttons">'.
		  '<h6>Pris: '.$products->getValue().' kr</h6>';
		  
	$text = ContentTextGet($products->getID(), 2);
	print '<div class="metafacts" id="' .$text->getTextID(2). '">' . MTextGet($text->getTextID(2)) . '</div>';
	
	// The Buttons
	print '<div>';
	
	// The cartbutton
	print '<div class="cartbutton" title="Visa varukorgen" id="orderquant_'. $products->getID() .'">';
		  
		  if(isset($_SESSION['orderid']) && OrderItemGetQuantity($products->getID(),$_SESSION['orderid']))
		  print '<img src="/trms-content/pino/images/cartfull-wht.png" class="cartimg" />';
		  else
		  print '<img src="/trms-content/pino/images/cart-wht.png" class="cartimg" />';
		   
	print '<div class="cartitems">'.(isset($_SESSION['orderid'])?number_format(OrderItemGetQuantity($products->getID(),$_SESSION['orderid'])):"0").'</div>'.
		  '</div>';

		
	// The buybutton
	if($products->getFlag() == 1)
	print '<div class="soldoutbutton" title="Slutsåld">SLUTSÅLD</div>';
	else
	print '<div class="buybutton" title="Klicka för att lägga varan i varukorgen" id="prodid_'.$products->getID().'">KÖP!</div>';
	
	print '</div>';
	// closes Buttons
	print '</div>';
	// closes META
	print '</div>';
	// closes brick
	
}
print '</div>';
print '<div class="divider">Slutsålda artiklar. Kan läsas på Jaramba och kidsread.se</div>';
print '<div class="masonry">';

$products = ContentGetAllInNode(ContentGetList(219, 0));
while($products = ContentGetNext($products)){
	if($products->getFlag() == false) continue;
	print '<div class="brick"><a href="/'.$products->getPermalink().'.html">';
	
	ImageGetWithIDNoSizeII($products->getID(), 1, "productimg", "more_" . $products->getID(), 2);
	
	print '</a>';
	
	$text = ContentTextGet($products->getID(), 1);
	print '<div id="' .$text->getTextID(1). '">' . MTextGet($text->getTextID()) . '</div>';
	
	// The META 
	print '<div class="prod-info-and-buttons">'.
		  '<h6>Pris: '.$products->getValue().' kr</h6>';
		  
	$text = ContentTextGet($products->getID(), 2);
	print '<div class="metafacts" id="' .$text->getTextID(2). '">' . MTextGet($text->getTextID(2)) . '</div>';
	
	// The Buttons
	print '<div>';
	
	// The cartbutton
	print '<div class="cartbutton" title="Visa varukorgen" id="orderquant_'. $products->getID() .'">';
		  
		  if(isset($_SESSION['orderid']) && OrderItemGetQuantity($products->getID(),$_SESSION['orderid']))
		  print '<img src="/trms-content/pino/images/cartfull-wht.png" class="cartimg" />';
		  else
		  print '<img src="/trms-content/pino/images/cart-wht.png" class="cartimg" />';
		   
	print '<div class="cartitems">'.(isset($_SESSION['orderid'])?number_format(OrderItemGetQuantity($products->getID(),$_SESSION['orderid'])):"0").'</div>'.
		  '</div>';

		
	// The buybutton
	if($products->getFlag() == 1)
	print '<div class="soldoutbutton" title="Slutsåld">SLUTSÅLD</div>';
	else
	print '<div class="buybutton" title="Klicka för att lägga varan i varukorgen" id="prodid_'.$products->getID().'">KÖP!</div>';
	
	print '</div>';
	// closes Buttons
	print '</div>';
	// closes META
	print '</div>';
	// closes brick
}


print '</div>';
print '</div>';

/*
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}
*/

?>


