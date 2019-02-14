<?php
if(isset($_SESSION['orderid']))$orderid = $_SESSION['orderid']; else $orderid = -1;

print	'<div id="menuicon">&#9776; <span class="icontext"></span>'.
		'<nav id="navigate">'.
		'	<ul id="menu">'.
	//	'		<li><a href="/about-pino">Om Pino</a></li>'.
		'		<li><a href="/">Pino Shop</a></li>'.
		'		<li><a href="/order">Varukorg</a></li>'.
		'		<li><a href="https://www.youtube.com/user/PinoForlag" target="_new">Titta på Pino</a></li>'.
	//	'		<li><a href="/about-pino#kontakt">Kontakt</a></li>'.
		'	</ul>'.
		'</nav>'.
		'</div>';

print	'<div id="carticon">';
		if($orderid > 0)
print 	'<img id="cart" src="/trms-content/pino/images/cartfull.png" />';
		else
print 	'<img id="cart" src="/trms-content/pino/images/cart.png" />';

print	'<div id="minicart">'.
 		'	<div id="minicart-inner">'.
 		'		<div id="close-minicart">x</div>'.
 		'	</div>'.
		'</div>'.
		'</div>';
?>