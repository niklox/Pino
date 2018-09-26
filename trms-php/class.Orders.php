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
| OrderResellerID       | int(11)      | YES  |     | NULL    |       |
| OrderCustomerComments | varchar(255) | YES  |     |         |       |
| OrderDiscount         | decimal(6,4) | YES  |     | 0.0000  |       |
| OrderCreatedDate      | datetime     | YES  |     | NULL    |       |
+-----------------------+--------------+------+-----+---------+-------+
37 rows in set (0.00 sec)
*/

class Order {

	var $orderid = 0;
	var $orderaddressid = 0;
	var $orderuserid = 0;
	var $orderwebuserid = 0;
	var $orderfirstname = ""; 
	var $orderlastname = "";   
	var $ordercompanyname = "";  	
	var $orderaddress1 = "";     	
	var $orderaddress2 = "";     	
	var $orderzip = "";          	
	var $ordercity = "";         	
	var $ordercountryid = 0;  	
	var $ordershipfirstname = "";    	
	var $ordershiplastname = "";     	
	var $ordershipcompanyname = "";  	
	var $ordershipaddress1 = "";     	
	var $ordershipaddress2 = "";     	
	var $ordershipzip = "";          	
	var $ordershipcity = "";         	
	var $ordershipcountryid = 0;  	
	var $orderordereddate = "";      	
	var $orderdeliverydate = "";    	
	var $orderstatus = 0;        	
	var $ordertotal = 0;            	
	var $ordercontactemail = "";     	
	var $ordervendorref = "";       	
	var $ordercustomerref = "";      	
	var $orderpaymentmethod = "";   	
	var $orderpaymentterms = "";    	
	var $ordershippinghandle = "";   	
	var $ordershippingmethod = "";   	
	var $ordercomments = "";   	
	var $ordertype = 0;         	
	var $orderresellerid = 0;       	
	var $ordercustomercomments = ""; 	
	var $orderdiscount = 0;      	
	var $ordercreateddate = "";      	
	
	var $dbrows;

	function getID(){
return $this->orderid;
}

	function setID($value){
$this->orderid = $value;
}

	function getAddressID(){return $this->orderaddressid;
}

	function setAddressID($value){
$this->orderaddressid = $value;
}

	function getUserID(){return $this->orderuserid;}
	function setUserID($value){$this->orderuserid = $value;}

	function getWebUserID(){return $this->orderwebuserid;}
	function setWebUserID($value){$this->orderwebuserid = $value;}

	function getFirstName(){return $this->orderfirstname;}
	function setFirstName($value){$this->orderfirstname = $value;}

	function getLastName(){return $this->orderlastname;}
	function setLastName($value){$this->orderlastname = $value;}

	function getCompanyName(){return $this->ordercompanyname;}
	function setCompanyName($value){$this->ordercompanyname = $value;}

	function getAddress1(){return $this->orderaddress1;}
	function setAddress1($value){$this->orderaddress1 = $value;}

	function getAddress2(){return $this->orderaddress2;}
	function setAddress2($value){$this->orderaddress2 = $value;}

	function getZip(){return $this->orderzip;}
	function setZip($value){$this->orderzip = $value;}

	function getCity(){return $this->ordercity;}
	function setCity($value){$this->ordercity = $value;}

	function getCountryID(){return $this->ordercountryid;}
	function setCountryID($value){$this->ordercountryid = $value;}

	function getShipFirstName(){return $this->ordershipfirstname;}
	function setShipFirstName($value){$this->ordershipfirstname = $value;}

	function getShipLastName(){return $this->ordershiplastname;}
	function setShipLastName($value){$this->ordershiplastname = $value;}

	function getShipCompanyName(){return $this->ordershipcompanyname;}
	function setShipCompanyName($value){$this->ordershipcompanyname = $value;}

	function getShipAddress1(){return $this->ordershipaddress1;}
	function setShipAddress1($value){$this->ordershipaddress1 = $value;}

	function getShipAddress2(){return $this->ordershipaddress2;}
	function setShipAddress2($value){$this->ordershipaddress2 = $value;}

	function getShipZip(){return $this->ordershipzip;}
	function setShipZip($value){$this->ordershipzip = $value;}

	function getShipCity(){return $this->ordershipcity;}
	function setShipCity($value){$this->ordershipcity = $value;}

	function getShipCountryID(){return $this->ordershipcountryid;}
	function setShipCountryID($value){$this->ordershipcountryid = $value;}

	function getOrderedDate(){return $this->orderordereddate;}
	function setOrderedDate($value){$this->orderordereddate = $value;}

	function getDeliveryDate(){return $this->orderdeliverydate;}
	function setDeliveryDate($value){$this->orderdeliverydate = $value;}

	function getStatus(){return $this->orderstatus;}
	function setStatus($value){$this->orderstatus = $value;}

	function getTotal(){return $this->ordertotal;}
	function setTotal($value){$this->ordertotal = $value;}

	function getContactEmail(){return $this->ordercontactemail;}
	function setContactEmail($value){$this->ordercontactemail = $value;}

	function getVendorRef(){return $this->ordervendorref;}
	function setVendorRef($value){$this->ordervendorref = $value;}

	function getCustomerRef(){return $this->ordercustomerref;}
	function setCustomerRef($value){$this->ordercustomerref = $value;}

	function getPaymentMethod(){return $this->orderpaymentmethod;}
	function setPaymentMethod($value){$this->orderpaymentmethod = $value;}

	function getPaymentTerms(){return $this->orderpaymentterms;}
	function setPaymentTerms($value){$this->orderpaymentterms = $value;}

	function getShipHandle(){return $this->ordershippinghandle;}
	function setShipHandle($value){$this->ordershippinghandle = $value;}

	function getShipMethod(){return $this->ordershippingmethod;}
	function setShipMethod($value){$this->ordershippingmethod = $value;}

	function getComments(){return $this->ordercomments;}
	function setComments($value){$this->ordercomments = $value;}

	function getOrderType(){return $this->ordertype;}
	function setOrderType($value){$this->ordertype = $value;}

	function getResellerID(){return $this->orderresellerid;}
	function setResellerID($value){$this->orderresellerid = $value;}

	function getCustomerComments(){return $this->ordercustomercomments;}
	function setCustomerComments($value){$this->ordercustomercomments = $value;}

	function getDiscount(){return $this->orderdiscount;}
	function setDiscount($value){$this->orderdiscount = $value;}

	function getCreatedDate(){return $this->ordercreateddate;}
	function setCreatedDate($value){$this->ordercreateddate = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
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

class OrderItem {

	var $orderitemid = 0;        
	var $orderitemorderid = 0;       
	var $orderitemposition = 0;      
	var $orderitemproductid = 0;
	var $orderitemdiscount = 0;      
	var $orderitemstatus = 0;         

	var $orderitemdelivereddate = "";

	var $orderitemextracomment = "";

	var $orderitemquantity = 0;    

	var $orderitemprice = 0;        

	var $orderitemunit = "";         

	var $orderitemexternalid = "";    
	var $orderitemattribute1 = ""; 
	var $orderitemattribute2 = "";      
	var $orderitemresellerid = 0;  
	var $dbrows;

	function getID(){return $this->orderitemid;}
	function setID($value){$this->orderitemid = $value;}

	function getOrderID(){return $this->orderitemorderid;}
	function setOrderID($value){$this->orderitemorderid = $value;}

	function getPosition(){return $this->orderitemposition;}
	function setPosition($value){$this->orderitemposition = $value;}

	function getProductID(){return $this->orderitemproductid;}
	function setProductID($value){$this->orderitemproductid = $value;}

	function getDiscount(){return $this->orderitemdiscount;}
	function setDiscount($value){$this->orderitemdiscount = $value;}

	function getStatus(){return $this->orderitemstatus;}
	function setStatus($value){$this->orderitemstatus = $value;}

	function getDeliveryDate(){return $this->orderitemdelivereddate;}
	function setDeliveryDate($value){$this->orderitemdelivereddate = $value;}

	function getComment(){return $this->orderitemextracomment;}
	function setComment($value){$this->orderitemextracomment = $value;}

	function getQuantity(){return $this->orderitemquantity;}
	function setQuantity($value){$this->orderitemquantity = $value;}

	function getPrice(){return $this->orderitemprice;}
	function setPrice($value){$this->orderitemprice = $value;}

	function getUnit(){return $this->orderitemunit;}
	function setUnit($value){$this->orderitemunit = $value;}

	function getExternalID(){return $this->orderitemexternalid;}
	function setExternalID($value){$this->orderitemexternalid = $value;}

	function getAttribute1(){return $this->orderitemattribute1;}
	function setAttribute1($value){$this->orderitemattribute1 = $value;}

	function getAttribute2(){return $this->orderitemattribute2;}
	function setAttribute2($value){$this->orderitemattribute2 = $value;}

	function getResellerID(){return $this->orderitemresellerid;}
	function setResellerID($value){$this->orderitemresellerid = $value;}

	function getDBrows(){return $this->dbrows;}
	function setDBrows($value){$this->dbrows = $value;}
	
}
?>
