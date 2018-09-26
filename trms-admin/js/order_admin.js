$(function(){
	
	$("#startdate").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Select a date'
	});

	$("#enddate").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Select a date'
	});

	

	$("#searchorders").click(function(){
		//alert($("#orderstatus option:selected").val());
		$("#box_body_top").html('');
		$("#orderlist").html('');
		$("#orderlist").load('/trms-admin/ajax/order_admin.php', {action: "listorders", startdate: $("#startdate").val(),  enddate: $("#enddate").val(), orderstatus: $("#orderstatus option:selected").val()});
	});

	$(".orders").live('click', function(){
		var orderid = $(this).attr("value");
		$("#box_body_top").fadeIn('slow', function(){
			$(this).load('/trms-admin/ajax/order_admin.php', {action: "vieworder", oid: orderid}, function(){
				
				$("#deliverydate").datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				dateFormat: 'yy-mm-dd',
				buttonText: 'Select a date'
				});
			});
		});
	});

	$("input[id^='deleteorder_']").live('click',function(){
		if(confirm("Delete this order?")){
			$('tr').remove('#orderrow_' + $(this).attr("id").substring(12) );
			$("#box_body_top").fadeOut('slow');
			$.post('/trms-admin/ajax/order_admin.php', {action: "deleteorder", oid: $(this).attr("id").substring(12) });
		}
	});

	
	/**
	 * Saves the orderdetail name,address etc for the order
	 */
	$("#deliverydate,#setorderstatus").live("change", function(event) {

		//alert($("#orderid").val() + " " +event.target.id + " " + event.target.value);
		$.post('/trms-admin/ajax/order_admin.php',{action: 'saveorderdetails', oid: $("#orderid").val(), orderdetail: event.target.id, orderdetailvalue: event.target.value});
		$("#orderlist").load('/trms-admin/ajax/order_admin.php', {action: "listorders", startdate: $("#startdate").val(),  enddate: $("#enddate").val(), orderstatus: $("#setorderstatus option:selected").val(),});

	});

})
