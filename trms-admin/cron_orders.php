<?php
//#!/home/niklo/local/php5_for_apache/bin/php

require "/home/niklo/www.pino.se/wwwserver/trms-php/termosdefine.php";
require "/home/niklo/www.pino.se/wwwserver/trms-php/db.inc.php";
require "/home/niklo/www.pino.se/wwwserver/trms-php/class.Orders.php"; 
require "/home/niklo/www.pino.se/wwwserver/trms-php/functions.Orders.php";
DBcnx();
//echo "Hello World you bastard!\n";

$sometimeaogo = mktime(date("H")-4 );
$fourhoursearlier = date("Y-m-d H:i:s",$sometimeaogo). "\n";

$orders = OrderGetAllByStatus(0);
while($orders = OrderGetNext($orders)){

	if( $orders->getCreatedDate()  < $fourhoursearlier){
		OrderDelete($orders->getID());
	}
	
}
?>
