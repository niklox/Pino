$(function(){

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
	$("input[id^='prodid_'],img[id^='prodid_']").click( function(event) {

		productid = event.target.id.substring(event.target.id.indexOf("_")+1 );
		$.post('/trms-content/ajax/order_edit.php',{action: 'additem', prid: productid});
		quantity = $("#orderquant_" + productid).val();
		quantity++;
		$("#orderquant_" + productid).val(quantity);
	});

	$(".basketbutton").click( function(event){
		location.href = "/order";
	});

	/**
	 * For the ordered quantity at the orderpage
	 */
	$("input[id^='orderrow_']").live("change", function(event) {
		orderrow = event.target.id.substring(event.target.id.indexOf("_")+1 );
		quant = event.target.value;
		$("#orderrows").load('/trms-content/ajax/order_edit.php',{action: 'setquantity', orderitemid: orderrow, quantity: quant});
	});
	
	/**
	 * set orderdiscount
	 */
	$("#discountbutton").live("click", function(event) {
		
		var code  = $("#discountcode").val();
		$("#orderrows").load('/trms-content/ajax/order_edit.php',{action: 'addiscount', discountcode: code});
		
	});
	
	/**
	 * Saves the orderdetail name,address etc for the order
	 */
	$(".orderdetails").blur(function(event) {

		//alert($("#orderid").val() + " " + event.target.id + " " + $(this).attr("value"));
		//alert($("#orderid").val() + " " + event.target.id + " " + event.target.value);
		$.post('/trms-content/ajax/order_edit.php',{action: "setorderdetail", oid: $("#orderid").val(), orderdetail: event.target.id, orderdetailvalue: event.target.value});
	});
	
	/**
	 * Do we have a different shipping adress or not
	 */
	$("#shippingaddress").click(function(){
		if($(this).is(":checked") == false){
			
			$("#orderheadship").fadeIn();
			$.post('/trms-content/ajax/order_edit.php',{action: 'setshipaddress', oid: $("#orderid").val(), shippingaddress: 'true'});
			$("#invoiceaddress").html('Fakturaadress');
		}else{
			
			$("#invoiceaddress").html('Faktura & leveransadress');
			$("#ordershipfullname").val('');
			$("#ordershipcompanyname").val('');
			$("#ordershipaddress").val('');
			$("#ordershipzip").val('');
			$("#ordershipcity").val('');
			$.post('/trms-content/ajax/order_edit.php',{action: 'setshipaddress', oid: $("#orderid").val(), shippingaddress: "false"});
			$("#orderheadship").fadeOut("slow");
		}
		$("#page").css({height:$(document).height()});
	});

	/**
	 * Complete and send the order
	 */
	$("#sendorder").click(function(){
		var errors = 0;

		$('label').each(function(index) {
		
				if( $(this).text().indexOf('*') > -1 && $("#" + $(this).attr("for")).val() == "" ){
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
			$("#orderbasket").load("/trms-content/ajax/order_edit.php", {action: "sendorder", oid: $("#orderid").val(), cid: $("#cid").val()});
		}
	});

	/**
	 * Delete order
	 */
	$("#deleteorder").click(function(){
		$("#orderbasket").load("/trms-content/ajax/order_edit.php", {action: "deleteorder", oid: $("#orderid").val(), cid: $("#cid").val()});
	});


	/**
	 * Menububble
	 */
	$(".focusnav>ul>li").live("mouseover", function(){
		
		var focus = "#focus_" + $(this).html();
		$(".focusnav>ul").css("color", "#11C0F3");
		
		$(".focusimg,.extraimg").hide();
		$(focus).show();
		$(this).css("color", "#E42D24")
	});
	
	$(".focusnav>ul>li").live("mouseout", function(){
		$(this).css("color", "#11C0F3");
	});

	$("#closebtn").click( function(e){
		$("#productfocus").hide();
		$("#overlay").hide();
		$("#closeup").html("");
	});

	$("#productfocus").draggable();

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
	 *	Blogpage
	 */

	 $("#createblogpost").click( function(){
		$("#blogform").toggle('slow');
	 });

	$("#blogpost").submit(function(){
		var values = $(this).serialize();
		
		alert(values);
		//$(this).load("/trms-content/ajax/standard_form.php", values);
		
		return false;
	});

	$("img[id^='deletepost_']").click( function(event){

		postid = event.target.id.substring(event.target.id.indexOf("_")+1, event.target.id.indexOf("-") );
		path = event.target.id.substring( event.target.id.indexOf("-")+1 );
		
		if(confirm("Do you want to remove this post?")){
			$.post("/trms-content/ajax/delete_post.php", {cid: postid, path: path});
			$("#blogpost_" + postid).remove();
			$("#discussion_" + postid).remove();
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

	$("#login_btn").live("click", function(){
		var values = $("#logon").serialize();

		//alert(values);
		
		$("#confirmation_std").load('/trms-content/ajax/logincheck.php?', values);
		return false;
	})

	$("#logout_btn").live("click", function(){
		var values = $("#logon").serialize();
		
		$("#confirmation_std").load('/trms-content/ajax/logincheck.php?', values);
		return false;
	})

	$("#closelogin").live('click', function(){
		$("#confirmation_std").hide('slow', function(){
			$(this).html("");
		});
		$("#overlay_login").fadeOut();
	})

	/**
	 * Discussions 
	 */

	 $("input[id^='commenticon']").click(function(event){

		blogpostid = event.target.id.substring(event.target.id.indexOf("_")+1 );
		
		$("#commenttext_" + blogpostid).val("");
		$("#signature_" + blogpostid).val("");
		$("#discussionconfirm_" + blogpostid).fadeOut("slow"); 
		$("#discussioninput_" + blogpostid).toggle("fast");

		$("#page").css({height:$(document).height()});
	 });

	$("input[id^='showcomments']").click(function(event){

		blogpostid = event.target.id.substring(event.target.id.indexOf("_")+1 );
		$("#discussionlist_" + blogpostid).toggle("fast");

		$("#page").css({height:$(document).height()});
	 });

	 $(".sendcomment").click(function(event){
		
		blogpostid = event.target.id.substring(event.target.id.indexOf("_")+1 );
		//alert(blogpostid);
		if( $("#commenttext_" + blogpostid).val() != "" && $("#signature_" + blogpostid).val() != ""){
			//$("#discussionlist_" + blogpostid).load("/trms-content/ajax/discussion_edit.php", {action: "savecomment", cid: blogpostid, commenttext: $("#commenttext_" + blogpostid).val(), signature: $("#signature_" + blogpostid).val()})
			
			$.post("/trms-content/ajax/discussion_edit.php", {action: "savecomment", cid: blogpostid, commenttext: $("#commenttext_" + blogpostid).val(), signature: $("#signature_" + blogpostid).val()})

			$("#discussioninput_" + blogpostid).fadeOut("slow", function(){
				$("#discussionconfirm_" + blogpostid).fadeIn("slow"); 
			}); 

		}else{
			alert("Please fill out both signature and comment!");
		}
	});

	/* Contactform */

	$("#pinocontact").submit(function(){
	
		if( $("#n_1357").val() == "" || $("#n_1358").val() == "" || $("#n_1359").val() == "" ){
			alert("Fyll i alla fält märkta med *");
			return false;
		}
	});


	
})