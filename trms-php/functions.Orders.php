<?php
/*
mysql> desc Orders;
+-----------------------+--------------+------+-----+---------+-------+
| Field                 | Type         | Null | Key | Default | Extra |
+-----------------------+--------------+------+-----+---------+-------+
| OrderID               | int(11)      | NO   | PRI | 0       |       |
| OrderAddressID        | int(11)      | NO   | MUL | 0       |       |
| OrderUserID           | int(11)      | NO   | MUL | 0       |       |
| OrderWebUserID        | int(11)      | NO   | MUL | 0       |       |
| OrderBillFirstName    | varchar(100) | YES  |     |         |       |
| OrderBillLastName     | varchar(100) | YES  |     |         |       |
| OrderBillCompanyName  | varchar(100) | YES  |     |         |       |
| OrderBillAddress1     | varchar(100) | YES  |     |         |       |
| OrderBillAddress2     | varchar(100) | YES  |     |         |       |
| OrderBillZip          | varchar(100) | YES  |     |         |       |
| OrderBillCity         | varchar(100) | YES  |     |         |       |
| OrderBillCountryID    | int(11)      | YES  |     | 0       |       |
| OrderShipFirstName    | varchar(100) | YES  |     |         |       |
| OrderShipLastName     | varchar(100) | YES  |     |         |       |
| OrderShipCompanyName  | varchar(100) | YES  |     |         |       |
| OrderShipAddress1     | varchar(100) | YES  |     |         |       |
| OrderShipAddress2     | varchar(100) | YES  |     |         |       |
| OrderShipZip          | varchar(100) | YES  |     |         |       |
| OrderShipCity         | varchar(100) | YES  |     |         |       |
| OrderShipCountryID    | int(11)      | YES  |     | 0       |       |
| OrderOrderedDate      | datetime     | YES  |     | NULL    |       |
| OrderDeliveryDate     | datetime     | YES  |     | NULL    |       |
| OrderStatus           | int(11)      | YES  |     | 0       |       |
| OrderTotal            | int(11)      | YES  |     | NULL    |       |
| OrderContactEmail     | varchar(100) | YES  |     |         |       |
| OrderVendorRef        | varchar(100) | YES  |     |         |       |
| OrderCustomerRef      | varchar(100) | YES  |     |         |       |
| OrderPaymentMethod    | varchar(100) | YES  |     |         |       |
| OrderPaymentTerms     | varchar(255) | YES  |     |         |       |
| OrderShippingHandle   | varchar(100) | YES  |     |         |       |
| OrderShippingMethod   | varchar(255) | YES  |     |         |       |
| OrderExtraComments    | text         | YES  |     | NULL    |       |
| OrderType             | int(11)      | YES  |     | 0       |       |
| OrderResellerID       | int(11)      | YES  |     | NULL    |       | // reseller id is used as order flag
| OrderCustomerComments | varchar(255) | YES  |     |         |       |
| OrderDiscount         | decimal(6,4) | YES  |     | 0.0000  |       |
| OrderCreatedDate      | datetime     | YES  |     | NULL    |       |
+-----------------------+--------------+------+-----+---------+-------+
37 rows in set (0.00 sec)
*/

define("ORDER_SELECT","SELECT Orders.OrderID, Orders.OrderAddressID, Orders.OrderUserID, Orders.OrderWebUserID, Orders.OrderBillFirstName, Orders.OrderBillLastName, Orders.OrderBillCompanyName, Orders.OrderBillAddress1, Orders.OrderBillAddress2, Orders.OrderBillZip, Orders.OrderBillCity, Orders.OrderBillCountryID, Orders.OrderShipFirstName, Orders.OrderShipLastName, Orders.OrderShipCompanyName, Orders.OrderShipAddress1, Orders.OrderShipAddress2, Orders.OrderShipZip, Orders.OrderShipCity, Orders.OrderShipCountryID, Orders.OrderOrderedDate, Orders.OrderDeliveryDate, Orders.OrderStatus, Orders.OrderTotal, Orders.OrderContactEmail, Orders.OrderVendorRef, Orders.OrderCustomerRef, Orders.OrderPaymentMethod, Orders.OrderPaymentTerms, Orders.OrderShippingHandle, Orders.OrderShippingMethod, Orders.OrderExtraComments, Orders.OrderType, Orders.OrderResellerID, Orders.OrderCustomerComments, Orders.OrderDiscount, Orders.OrderCreatedDate FROM Orders");
define("ORDER_INSERT", "INSERT INTO Orders (OrderID, OrderAddressID, OrderUserID, OrderWebUserID, OrderBillFirstName, OrderBillLastName, OrderBillCompanyName, OrderBillAddress1, OrderBillAddress2, OrderBillZip, OrderBillCity, OrderBillCountryID, OrderShipFirstName, OrderShipLastName, OrderShipCompanyName, OrderShipAddress1, OrderShipAddress2, OrderShipZip, OrderShipCity, OrderShipCountryID, OrderOrderedDate, OrderDeliveryDate, OrderStatus, OrderTotal, OrderContactEmail, OrderVendorRef, OrderCustomerRef, OrderPaymentMethod, OrderPaymentTerms, OrderShippingHandle, OrderShippingMethod, OrderExtraComments, OrderType, OrderResellerID, OrderCustomerComments, OrderDiscount, OrderCreatedDate) ");
define("DISCOUNTCODE", "MLK403");

function OrderSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['OrderID']);	
		$instance->setAddressID($row['OrderAddressID']);
		$instance->setUserID($row['OrderUserID']);
		$instance->setWebUserID($row['OrderWebUserID']);
		$instance->setFirstName($row['OrderBillFirstName']);
		$instance->setLastName($row['OrderBillLastName']);
		$instance->setCompanyName($row['OrderBillCompanyName']);
		$instance->setAddress1($row['OrderBillAddress1']);
		$instance->setAddress2($row['OrderBillAddress2']);
		$instance->setZip($row['OrderBillZip']);
		$instance->setCity($row['OrderBillCity']);
		$instance->setCountryID($row['OrderBillCountryID']);
		$instance->setShipFirstName($row['OrderShipFirstName']);
		$instance->setShipLastName($row['OrderShipLastName']);
		$instance->setShipCompanyName($row['OrderShipCompanyName']);
		$instance->setShipAddress1($row['OrderShipAddress1']);
		$instance->setShipAddress2($row['OrderShipAddress2']);
		$instance->setShipZip($row['OrderShipZip']);
		$instance->setShipCity($row['OrderShipCity']);
		$instance->setShipCountryID($row['OrderShipCountryID']);
		$instance->setOrderedDate($row['OrderOrderedDate']);
		$instance->setDeliveryDate($row['OrderDeliveryDate']);
		$instance->setStatus($row['OrderStatus']);
		$instance->setTotal($row['OrderTotal']);
		$instance->setContactEmail($row['OrderContactEmail']);
		$instance->setVendorRef($row['OrderVendorRef']);
		$instance->setCustomerRef($row['OrderCustomerRef']);
		$instance->setPaymentMethod($row['OrderPaymentMethod']);
		$instance->setPaymentTerms($row['OrderPaymentTerms']);
		$instance->setShipHandle($row['OrderShippingHandle']);
		$instance->setShipMethod($row['OrderShippingMethod']);
		$instance->setComments($row['OrderExtraComments']);
		$instance->setOrderType($row['OrderType']);
		$instance->setResellerID($row['OrderResellerID']);
		$instance->setCustomerComments($row['OrderCustomerComments']);
		$instance->setDiscount($row['OrderDiscount']);
		$instance->setCreatedDate($row['OrderCreatedDate']);

		return $instance;
	}
}


function OrderGetNext($instance){
	
	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['OrderID']);	
		$instance->setAddressID($row['OrderAddressID']);
		$instance->setUserID($row['OrderUserID']);
		$instance->setWebUserID($row['OrderWebUserID']);
		$instance->setFirstName($row['OrderBillFirstName']);
		$instance->setLastName($row['OrderBillLastName']);
		$instance->setCompanyName($row['OrderBillCompanyName']);
		$instance->setAddress1($row['OrderBillAddress1']);
		$instance->setAddress2($row['OrderBillAddress12']);
		$instance->setZip($row['OrderBillZip']);
		$instance->setCity($row['OrderBillCity']);
		$instance->setCountryID($row['OrderBillCountryID']);
		$instance->setShipFirstName($row['OrderShipFirstName']);
		$instance->setShipLastName($row['OrderShipLastName']);
		$instance->setShipCompanyName($row['OrderShipCompanyName']);
		$instance->setShipAddress1($row[' OrderShipAddress1']);
		$instance->setShipAddress2($row[' OrderShipAddress2']);
		$instance->setShipZip($row['OrderShipZip']);
		$instance->setShipCity($row['OrderShipCity']);
		$instance->setShipCountryID($row['OrderShipCountryID']);
		$instance->setOrderedDate($row['OrderOrderedDate']);
		$instance->setDeliveryDate($row['OrderDeliveryDate']);
		$instance->setStatus($row['OrderStatus']);
		$instance->setTotal($row['OrderTotal']);
		$instance->setContactEmail($row['OrderContactEmail']);
		$instance->setVendorRef($row['OrderVendorRef']);
		$instance->setCustomerRef($row['OrderCustomerRef']);
		$instance->setPaymentMethod($row['OrderPaymentMethod']);
		$instance->setPaymentTerms($row['OrderPaymentTerms']);
		$instance->setShipHandle($row['OrderShippingHandle']);
		$instance->setShipMethod($row['OrderShippingMethod']);
		$instance->setComments($row['OrderExtraComments']);
		$instance->setOrderType($row['OrderType']);
		$instance->setResellerID($row['OrderResellerID']);
		$instance->setCustomerComments($row['OrderCustomerComments']);
		$instance->setDiscount($row['OrderDiscount']);
		$instance->setCreatedDate($row['OrderCreatedDate']);

		return $instance;
	}
}

function OrderGetByID($oid){
	global $dbcnx;
	
	$instance = new Order;
	$sqlstr = ORDER_SELECT . " WHERE OrderID = " . $oid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderGetByID();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = OrderSetAllFromRow($instance);
	return $instance;
}

function OrderGetAll(){
	global $dbcnx;
	
	$instance = new Order;
	$sqlstr = ORDER_SELECT;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderGetAll();<br/> " . mysqli_error($dbcnx) );
	}
	
	return $instance;
}

function OrderGetAllByStatus($status){
	global $dbcnx;
	
	$instance = new Order;
	$sqlstr = ORDER_SELECT . " WHERE OrderStatus = " . $status;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderGetAll();<br/> " . mysqli_error($dbcnx) );
	}
	
	return $instance;
}

function OrderSave($instance){
	global $dbcnx;

	if($instance->getID() > 0){
		$sql = "UPDATE Orders SET OrderAddressID = ". $instance->getAddressID() . ", OrderUserID = ".$instance->getUserID(). ", OrderWebUserID=".$instance->getWebUserID().", OrderBillFirstName='" . $instance->getFirstName() . "', OrderBillLastName='" . $instance->getLastName() . "', OrderBillCompanyName='". $instance->getCompanyName() ."', OrderBillAddress1='".$instance->getAddress1()."', OrderBillAddress2='". $instance->getAddress2()."', OrderBillZip='".$instance->getZip()."', OrderBillCity='".$instance->getCity()."', OrderBillCountryID=".$instance->getCountryID().", OrderShipFirstName='".$instance->getShipFirstName()."', OrderShipLastName='".$instance->getShipLastName()."', OrderShipCompanyName='".$instance->getShipCompanyName()."', OrderShipAddress1 ='".$instance->getShipAddress1()."', OrderShipAddress2='".$instance->getShipAddress2()."', OrderShipZip='".$instance->getShipZip()."', OrderShipCity='".$instance->getShipCity()."', OrderShipCountryID=".$instance->getShipCountryID().", OrderOrderedDate='". $instance->getOrderedDate() . "', OrderDeliveryDate='". $instance->getDeliveryDate() ."', OrderStatus=". $instance->getStatus() .", OrderTotal=".$instance->getTotal().", OrderContactEmail='".$instance->getContactEmail()."', OrderVendorRef='".$instance->getVendorRef()."', OrderCustomerRef='".$instance->getCustomerRef()."', OrderPaymentMethod='".$instance->getPaymentMethod()."', OrderPaymentTerms='".$instance->getPaymentTerms()."', OrderShippingHandle='".$instance->getShipHandle()."', OrderShippingMethod='".$instance->getShipMethod()."', OrderExtraComments='".$instance->getComments()."', OrderType=".$instance->getOrderType()." , OrderResellerID=".$instance->getResellerID().", OrderCustomerComments='".$instance->getCustomerComments()."', OrderDiscount=".$instance->getDiscount().", OrderCreatedDate='".$instance->getCreatedDate()."' WHERE OrderID = ". $instance->getID() ."";

	}else{
		$value = TermosGetCounterValue("OrderID");
		TermosSetCounterValue("OrderID", ++$value);
		$instance->setID($value);

		$sql = ORDER_INSERT . "VALUES(" . $instance->getID() .",". $instance->getAddressID() . ",".$instance->getUserID(). ",".$instance->getWebUserID().",'" . $instance->getFirstName() . "','" . $instance->getLastName() . "','". $instance->getCompanyName() ."','" . $instance->getAddress1() . "','". $instance->getAddress2()."','".$instance->getZip()."','".$instance->getCity()."',".$instance->getCountryID().",'".$instance->getShipFirstName()."', '".$instance->getShipLastName()."','".$instance->getShipCompanyName()."','".$instance->getShipAddress1()."','".$instance->getShipAddress2()."','".$instance->getShipZip()."','".$instance->getShipCity()."',".$instance->getShipCountryID().",'". $instance->getOrderedDate() . "','". $instance->getDeliveryDate() ."',". $instance->getStatus() .", ".$instance->getTotal().", '".$instance->getContactEmail()."', '".$instance->getVendorRef()."','".$instance->getCustomerRef()."', '".$instance->getPaymentMethod()."','".$instance->getPaymentTerms()."', '".$instance->getShipHandle()."','".$instance->getShipMethod()."', '".$instance->getComments()."',".$instance->getOrderType()." , ".$instance->getResellerID().",'".$instance->getCustomerComments()."', ".$instance->getDiscount().",'".$instance->getCreatedDate()."')";
		//print $sql;
	  }
	 
	mysqli_query($dbcnx, $sql);
	return $instance->getID();
}

function OrderDelete($oid){
	global $dbcnx;

	$sql = "DELETE FROM OrderItems WHERE OrderItemOrderID=" . $oid;
	mysqli_query($dbcnx, $sql);

	$sql = "DELETE FROM Orders WHERE OrderID=" . $oid;
	mysqli_query($dbcnx, $sql);
}

function OrderCalculateTotal($oid){
	$ordertotal = 0;
	$order = OrderGetByID($oid);
	$orderitem = OrderItemGetAllForOrder($oid);

	while($orderitem = OrderItemGetNext($orderitem)){
		$product = ContentGetByID($orderitem->getProductID());
		$ordertotal += $product->getValue() * $orderitem->getQuantity(); 
	}

	if($ordertotal < 301)
		$ordertotal += 36;
	else if($ordertotal < 1001)
		$ordertotal += 48;
	else if ($ordertotal < 1 || $ordertotal > 1000)
		$ordertotal += 0;

	$order->setTotal($ordertotal);
	OrderSave($order);
}

function OrderHasItem($oid, $prid){
	global $dbcnx;

	$sql = ORDERITEM_SELECT . " WHERE OrderItemOrderID = " . $oid . " AND OrderItemProductID = " . $prid;
	$orderrow = @mysqli_query($dbcnx, $sql);

	return mysqli_num_rows($orderrow);
}

function OrderAddItem($oid, $prid){
	
	if ( OrderHasItem($oid, $prid) ){
		$orderitem = OrderItemGetByProductAndOrderID( $oid, $prid );
		$quantity = $orderitem->getQuantity();
		$quantity++;
		$orderitem->setQuantity($quantity);
		OrderItemSave($orderitem);

	}else{
		
		$product = ContentGetByID($prid);
		
		$orderitem = new OrderItem;
		$orderitem->setOrderID($oid);
		$orderitem->setQuantity(1);
		$orderitem->setProductID($prid);
		$orderitem->setPrice($product->getValue());
		OrderItemSave($orderitem);
	}

}

function OrderDeleteItem($oid, $prid){

	$orderitem = OrderItemGetByProductAndOrderID( $oid, $prid );
	OrderItemDelete($orderitem->getID());
}

function OrderSearch($startdate, $enddate, $orderstatus){
	global $dbcnx;
	
	$instance = new Order;
	$sqlstr = ORDER_SELECT . " WHERE Orders.OrderID > 0 ";

	if(strlen($startdate))
		$sqlstr = $sqlstr .  " AND Orders.OrderCreatedDate>= '" . $startdate . "'";
	if(strlen($enddate))
		$sqlstr = $sqlstr .  " AND Orders.OrderCreatedDate <= '" . $enddate . "'";
	if($orderstatus > -1)
		$sqlstr = $sqlstr .  " AND Orders.OrderStatus = " . $orderstatus;
	
	$sqlstr = $sqlstr . " ORDER BY OrderCreatedDate DESC";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderSearch();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

// Data column OrderResellerID is used as OrderFlag bitwise 
function OrderSetFlag($orderid, $flagid){
	global $dbcnx;
	$sql = 'UPDATE Orders SET OrderResellerID  = OrderResellerID  + '.$flagid.' WHERE OrderResellerID  & '.$flagid.' = 0 AND OrderID =' . $orderid;
	mysqli_query($dbcnx, $sql);
}

function OrderRemoveFlag($orderid, $flagid){
	global $dbcnx;
	$sql = 'UPDATE Orders SET OrderResellerID = OrderResellerID &~ '.$flagid.' WHERE OrderID =' . $orderid;
	mysqli_query($dbcnx, $sql);
}

/*

mysql> desc OrderItems;
+------------------------+--------------+------+-----+---------+-------+
| Field                  | Type         | Null | Key | Default | Extra |
+------------------------+--------------+------+-----+---------+-------+
| OrderItemID            | int(11)      | NO   | PRI | 0       |       |
| OrderItemOrderID       | int(11)      | NO   | MUL | 0       |       |
| OrderItemPosition      | int(11)      | YES  |     | NULL    |       |
| OrderItemProductID     | int(11)      | YES  |     | NULL    |       |
| OrderItemDiscount      | double(16,4) | YES  |     | NULL    |       |
| OrderItemStatus        | int(11)      | YES  |     | NULL    |       |
| OrderItemDeliveredDate | datetime     | YES  |     | NULL    |       |
| OrderItemExtraComment  | text         | YES  |     | NULL    |       |
| OrderItemQuantity      | double(16,4) | YES  |     | NULL    |       |
| OrderItemPrice         | double(16,4) | YES  |     | NULL    |       |
| OrderItemUnit          | varchar(100) | YES  |     | NULL    |       |
| OrderItemExternalID    | varchar(100) | YES  |     | NULL    |       |
| OrderItemAttribute1    | varchar(100) | YES  |     | NULL    |       |
| OrderItemAttribute2    | varchar(100) | YES  |     | NULL    |       |
| OrderItemResellerID    | int(11)      | YES  |     | NULL    |       |
+------------------------+--------------+------+-----+---------+-------+
15 rows in set (0.00 sec)

*/

define("ORDERITEM_SELECT","SELECT OrderItems.OrderItemID, OrderItems.OrderItemOrderID, OrderItems.OrderItemPosition, OrderItems.OrderItemProductID, OrderItems.OrderItemDiscount, OrderItems.OrderItemStatus, OrderItems.OrderItemDeliveredDate, OrderItems.OrderItemExtraComment, OrderItems.OrderItemQuantity, OrderItems.OrderItemPrice, OrderItems.OrderItemUnit, OrderItems.OrderItemExternalID, OrderItems.OrderItemAttribute1, OrderItems.OrderItemAttribute2, OrderItems.OrderItemResellerID FROM OrderItems ");
define("ORDERITEM_INSERT","INSERT INTO OrderItems (OrderItemID, OrderItemOrderID, OrderItemPosition, OrderItemProductID, OrderItemDiscount, OrderItemStatus, OrderItemDeliveredDate, OrderItemExtraComment, OrderItemQuantity, OrderItemPrice, OrderItemUnit, OrderItemExternalID, OrderItemAttribute1, OrderItemAttribute2, OrderItemResellerID) ");

function OrderItemSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['OrderItemID']);
		$instance->setOrderID($row['OrderItemOrderID']);
		$instance->setPosition($row['OrderItemPosition']);
		$instance->setProductID($row['OrderItemProductID']);
		$instance->setDiscount($row['OrderItemDiscount']);
		$instance->setStatus($row['OrderItemStatus']);
		$instance->setDeliveryDate($row['OrderItemDeliveredDate']);
		$instance->setComment($row['OrderItemExtraComment']);
		$instance->setQuantity($row['OrderItemQuantity']);
		$instance->setPrice($row['OrderItemPrice']);
		$instance->setUnit($row['OrderItemUnit']);
		$instance->setExternalID($row['OrderItemExternalID']);
		$instance->setAttribute1($row['OrderItemAttribute1']);
		$instance->setAttribute2($row['OrderItemAttribute2']);
		$instance->setResellerID($row['OrderItemResellerID']);
	
		return $instance;
	}
}

function OrderItemGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['OrderItemID']);
		$instance->setOrderID($row['OrderItemOrderID']);
		$instance->setPosition($row['OrderItemPosition']);
		$instance->setProductID($row['OrderItemProductID']);
		$instance->setDiscount($row['OrderItemDiscount']);
		$instance->setStatus($row['OrderItemStatus']);
		$instance->setDeliveryDate($row['OrderItemDeliveredDate']);
		$instance->setComment($row['OrderItemExtraComment']);
		$instance->setQuantity($row['OrderItemQuantity']);
		$instance->setPrice($row['OrderItemPrice']);
		$instance->setUnit($row['OrderItemUnit']);
		$instance->setExternalID($row['OrderItemExternalID']);
		$instance->setAttribute1($row['OrderItemAttribute1']);
		$instance->setAttribute2($row['OrderItemAttribute2']);
		$instance->setResellerID($row['OrderItemResellerID']);
	
		return $instance;
	}
}

function OrderItemGetByID($orderitemid){
	global $dbcnx;
	
	$instance = new OrderItem;
	$sqlstr = ORDERITEM_SELECT . " WHERE OrderItemID = " . $orderitemid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderItemGetByID();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = OrderItemSetAllFromRow($instance);
	return $instance;
}

function OrderItemGetAllForOrder($oid){
	global $dbcnx;
	
	
	
	$instance = new OrderItem;
	$sqlstr = ORDERITEM_SELECT . " WHERE OrderItemOrderID = " . $oid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderItemGetAllForOrderID();<br/> " . mysqli_error($dbcnx) );
	}
	//$instance = OrderItemSetAllFromRow($instance);
	return $instance;
}

function OrderItemGetByProductAndOrderID($oid, $prid){
	global $dbcnx;
	
	$instance = new OrderItem;

	$sqlstr = ORDERITEM_SELECT . " WHERE OrderItemOrderID = " . $oid. " AND OrderItemProductID = " . $prid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderItemGetByProductAndOrderID();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = OrderItemSetAllFromRow($instance);
	return $instance;
}


function OrderItemSetQuantity($orderitemid, $quantity){

	if($quantity < 1){
		OrderItemDelete($orderitemid);
	}
	else{
		$orderitem = OrderItemGetByID($orderitemid);
		$orderitem->setQuantity($quantity);
		
		OrderItemSave($orderitem);
	}
}

function OrderItemGetQuantity($prid, $oid){

	global $dbcnx;
	
	$instance = new OrderItem;

	$sqlstr = ORDERITEM_SELECT . " WHERE OrderItemOrderID = " . $oid. " AND OrderItemProductID = " . $prid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function OrderItemGetQuantity();<br/> " . mysqli_error($dbcnx) . $sqlstr);
	}
	$instance = OrderItemSetAllFromRow($instance);
	
	if($instance) return $instance->getQuantity();

}

function OrderItemSave($instance){
	global $dbcnx;

	if($instance->getID() > 0){
		$sql = "UPDATE OrderItems SET OrderItemID=".$instance->getID().", OrderItemOrderID=".$instance->getOrderID().", OrderItemPosition=".$instance->getPosition().", OrderItemProductID=".$instance->getProductID().", OrderItemDiscount=".$instance->getDiscount().", OrderItemStatus='".$instance->getStatus()."', OrderItemDeliveredDate='". $instance->getDeliveryDate()."', OrderItemExtraComment='".$instance->getComment()."', OrderItemQuantity=".$instance->getQuantity().", OrderItemPrice=".$instance->getPrice().", OrderItemUnit='".$instance->getUnit()."', OrderItemExternalID='".$instance->getExternalID()."', OrderItemAttribute1='".$instance->getAttribute1()."', OrderItemAttribute2='".$instance->getAttribute2()."', OrderItemResellerID='".$instance->getResellerID()."' WHERE OrderItemID=" .  $instance->getID();
		
	}else{
		$value = TermosGetCounterValue("OrderItemID");
		TermosSetCounterValue("OrderItemID", ++$value);
		$instance->setID($value);

		$sql = ORDERITEM_INSERT . "VALUES(".$instance->getID() .",".$instance->getOrderID().",".$instance->getPosition().",".$instance->getProductID().", ".$instance->getDiscount().",'".$instance->getStatus()."','". $instance->getDeliveryDate()."','".$instance->getComment()."',".$instance->getQuantity().", ".$instance->getPrice().",'".$instance->getUnit()."','".$instance->getExternalID()."','".$instance->getAttribute1()."','".$instance->getAttribute2()."','".$instance->getResellerID()."')";
	  }
	  //print $sql;
	mysqli_query($dbcnx, $sql);
}



function OrderItemDelete($orderitemid){
	global $dbcnx;

	$sql = "DELETE FROM OrderItems WHERE OrderItemID=" . $orderitemid;
	mysqli_query($dbcnx, $sql);
}

