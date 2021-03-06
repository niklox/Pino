$(function(){

	/*$("input").onfocus.css({'background' : '#FFFFFF'});*/
	$("#page").css({height:$(document).height()});
	$("#overlay_login").css({height:$(document).height()});

	if( $("#tmplid").val() == 11 && $("#shippingaddress").is(":checked") == true ){
		$("#invoiceaddress").html('Faktura & leveransadress');
		$("#orderheadship").hide();
		$("#page").css({height:$(document).height()});
	}
		
	if( $("#tmplid").val() == 17 ){
		$("#content").css({height:970});
		$("#page").css({height:$(document).height()});
		
	}
	/**
	 * Productfocus 
	 */
	$("img[id^='more']").click( function(event) {
		
		productid = event.target.id.substring(event.target.id.indexOf("_")+1 );
	
		$("#productfocus").css("top", event.pageY - 50, "left", event.pageX + 50);
		$("#overlay").fadeIn('fast', function(){
			$(this).css("filter","alpha(opacity=0)");
		});
		
		$("#productfocus").show('fast', function(){
			$("#closeup").load("/trms-content/misty/ajax/product_focus.php", {action: "show", cid: productid});
		});
	});
	
	/**
	 * Functionality for the buybutton in the productlist
	 */
	$("div[id^='prodid_'],input[id^='prodid_'],img[id^='prodid_']").click( function(event) {
		
		productid = event.target.id.substring(event.target.id.indexOf("_")+1 );
		//alert(productid);
		$.post('/trms-content/ajax/order_edit.php',{action: 'additem', prid: productid});
		
		//quantity = $("#orderquant_" + productid).val();
		quantity = $("#orderquant_" + productid + " .cartitems").html();
		quantity = quantity.slice(quantity.lastIndexOf(" "));
		//alert(quantity);
		quantity++;
		
		//$("#orderquant_" + productid).val("ANTAL I VARUKORGEN: " + quantity);
		$("#orderquant_" + productid).html('<img src="/trms-content/pino/images/cartfull-wht.png" class="cartimg"><div class="cartitems">' + quantity + '</div>');
		//$("#carticon").html('<img src="/trms-content/pino/images/cartfull.png" /><div id="minicart">xx</div></div></div>');
		$("#cart").attr('src','/trms-content/pino/images/cartfull.png');
		$(this).blur();
	});

	$(".cartbutton").click( function(event){
		location.href = "/order";
	});

	/**
	 * For the ordered quantity at the orderpage
	 */
	
	$(document).on("change", "input[id^='orderrow_']", function(event) {
		//alert("asd");
		orderrow = event.target.id.substring(event.target.id.indexOf("_")+1 );
		//alert(orderrow );
		quant = event.target.value;
		//alert(quant);
		$("#orderrows").load('/trms-content/ajax/order_edit.php',{action: 'setquantity', orderitemid: orderrow, quantity: quant});
	});
	
	/**
	 * For the ordered quantity at the orderpage
	 */
	
	$(document).on("click", ".add,.sub", function(event) {
		let orderrowid, quant = 0;
		orderrowid = $(this).attr('id').substring( $(this).attr('id').indexOf("_")+1 );
		quant = $("#orderrow_" + orderrowid ).attr("value");
		if($(this).attr('class') === "add"){
			$("#orderrow_" + orderrowid ).attr("value", ++quant);
		}else if($(this).attr('class') === "sub"){
			$("#orderrow_" + orderrowid ).attr("value", --quant);
		}
		$("#orderrows").load('/trms-content/ajax/order_edit.php',{action: 'setquantity', orderitemid: orderrowid, quantity: quant});
	});
	
	/**
	 *	For the back to shop button
	 */
	 $(".gotoshop").click(function(){
	 	location.href = "/";
	 });
	 
	
	/**
	 * set orderdiscount
	 */
	
	$(document).on("click", "#discountbutton", function(event) {
		var code  = $("#discountcode").val();
		$("#orderrows").load('/trms-content/ajax/order_edit.php',{action: 'addiscount', discountcode: code});
		
	});
	
	/**
	 * Saves the orderdetail name,address etc for the order
	 */
	$(document).on("blur", ".orderdetails", function(event) {

		//alert($("#orderid").val() + " " + event.target.id + " " + $(this).attr("value"));
		//alert($("#orderid").val() + " " + event.target.id + " " + event.target.value);
		$.post('/trms-content/ajax/order_edit.php',{action: "setorderdetail", oid: $("#orderid").val(), orderdetail: event.target.id, orderdetailvalue: event.target.value});
	});
	
	
	/**
	 * Is this an order for an organisation school etc.
	 */
	 
	 $("#organisation-private").click(function(){
	 	
		if($(this).is(":checked") == true){
			console.log("we are true");
			$("#ordercompanyfields").load('/trms-content/ajax/order_edit.php',{action: 'setorganisationaddress', oid: $("#orderid").val(), organisationaddress: "true"});
			$("#deliverycompanyname").html('<input type="text" class="orderdetails" id="ordershipcompanyname" value="" tabindex="" placeholder="Mottagare förskola/organisation/företag"/>');
		}else{
			console.log("we are false " +  $("#orderid").val());
			$.post('/trms-content/ajax/order_edit.php',{action: 'setorganisationaddress', oid: $("#orderid").val(), organisationaddress: 'false'});
			$("#ordercompanyfields").html("");
			$("#deliverycompanyname").html("");
			
			$("#ordercompanyname").val('');
			$("#orderreference").val('');
		}
		$("#page").css({height:$(document).height()});
	});
	
	
	/**
	 * Do we have a different shipping adress or not
	 */
	 
	 $("#shippingaddress").click(function(){
		if($(this).is(":checked") == true){
			$("#orderheadship").load('/trms-content/ajax/order_edit.php',{action: 'setshipaddress', oid: $("#orderid").val(), shippingaddress: "true"});
			$("#invoiceaddress").html('Fakturaadress');
			$("#orderheadship").attr("display","block").fadeIn();
		}else{
			$.post('/trms-content/ajax/order_edit.php',{action: 'setshipaddress', oid: $("#orderid").val(), shippingaddress: 'false'});
			$("#invoiceaddress").html('Faktura & leveransadress');
			$("#ordershipfullname").val('');
			$("#ordershipcompanyname").val('');
			$("#ordershipaddress").val('');
			$("#ordershipzip").val('');
			$("#ordershipcity").val('');
			
			$("#orderheadship").attr("display","none").fadeOut();
		}
		$("#page").css({height:$(document).height()});
	});

	/**
	 * Complete and send the order
	 */
	$("#sendorder").click(function(){
		var errors = 0;

		$('input[type=text]').each(function(index) {
				
				//alert($(this).attr('placeholder').indexOf('*') + " " + $(this).attr('id') );
				//if( $(this).text().indexOf('*') > -1 && $("#" + $(this).attr("for")).val() == "" ){
				
				if( $(this).val().length == 0 && $(this).attr('id').indexOf('orderrow') == -1 && $(this).attr('placeholder').indexOf('*') > -1 ){
					
					$(this).css("background-color", "#FFCC00");
					errors++;
				}
				
				if( $(this).text().indexOf('*') > -1 && $("#" + $(this).attr("for")).is("input:radio") == true){
					btnerrors = 1;
					$("input:radio").each(function(index){ 
						if($(this).is(":checked") == true)btnerrors = 0; 
					});
					if(btnerrors > 0) $(this).css("background-color", "#FFCC00");
				}
				
		});
		
		if(errors > 0)
			alert("Du måste fylla i alla fält märkta med *");
		else{
			
			$("#overlay").show();
			$("#loader").show();
			$("#orderbasket").load("/trms-content/ajax/order_edit.php", {action: "sendorder", oid: $("#orderid").val(), cid: $("#cid").val()});
			$(window).scrollTop(0);
			$("#overlay").fadeOut(3000);
			$("#loader").fadeOut(3000);
			
		
		}
	});

	/**
	 * Delete order
	 */
	$("#deleteorder").click(function(){
		$("#orderbasket").load("/trms-content/ajax/order_edit.php", {action: "deleteorder", oid: $("#orderid").val(), cid: $("#cid").val()});
	});

	$("#closebtn").click( function(e){
		$("#productfocus").hide();
		$("#overlay").hide();
		$("#closeup").html("");
	});

	//$("#productfocus").draggable();

	/**
	 *	Ordertabs
	 */

	 $("#tabs>li").click( function(){
		
		$(this).removeClass();
		$(this).addClass('active');
		$(this).siblings().removeClass();
		$(this).siblings().addClass('inactive');

		if( $(this).html() == 'ORDERINFORMATION' ){
			$("#orderbasket").removeClass();
			$("#orderbasket").addClass('under');
			$("#ordergenericinfo").removeClass();
			$("#ordergenericinfo").addClass('over');
		}
		else if( $(this).html() == 'VARUKORG' ){
			$("#orderbasket").removeClass();
			$("#orderbasket").addClass('over');
			$("#ordergenericinfo").removeClass();
			$("#ordergenericinfo").addClass('under');
		}
	 });

	/**
	 *	Login
	 */

	$("#loginlabel").click(function(){
		$("#overlay_login").fadeIn('fast', function(){
			$(this).css("filter","alpha(opacity=30)");
		});
		var str = $(this).text();

		if( str.search(/login/i) > -1 ){
			
			$("#confirmation_std").show('slow', function(){
				$(this).load("/trms-content/ajax/login.php", {action: "login", cid: $("#cid").val()});
				$('html').animate({scrollTop:0}, 'fast');
			});

		}else{

			$("#confirmation_std").show('slow', function(){
				$(this).load("/trms-content/ajax/login.php", {action: "logout", cid: $("#cid").val()});
				$('html').animate({scrollTop:0}, 'fast');
			});
		}
	});

	$(document).on("click", "#logout_btn", function(){
		var values = $("#logon").serialize();
		
		$("#confirmation_std").load('/trms-content/ajax/logincheck.php?', values);
		return false;
	})

	
	$(document).on('click', "#closelogin", function(){
		$("#confirmation_std").hide('slow', function(){
			$(this).html("");
		});
		$("#overlay_login").fadeOut();
	})

	/**
	 * Discussions 
	 */

	
	/* Contactform */

	$("#pinocontact").submit(function(){
	
		if( $("#n_1357").val() == "" || $("#n_1358").val() == "" || $("#n_1359").val() == "" ){
			alert("Fyll i alla fält märkta med *");
			return false;
		}
	});


	
})