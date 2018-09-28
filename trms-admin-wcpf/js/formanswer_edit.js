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
	
	var formid = $("#formid").val();

	$("#searchglobalvote").click(function(){
		$("#box_body_top").html('');
		$("#globalvotelist").html('');
		$("#globalvotelist").load('/trms-admin/ajax/formanswer_edit.php', {action: "listglobalvote", formid: $("#formid").val(), startdate: $("#startdate").val(),  enddate: $("#enddate").val(), countryid: $("#countryid option:selected").val(), votetype: $("#votetype option:selected").val()});
	});

	$("#statistics").live('click', function(){
		$("#box_body").load('/trms-admin/ajax/formanswer_edit.php', {action: "viewstatistics", formid: $("#forms option:selected").val() } );
	});
	
	
	$("#gv_by_country").live('click', function(){
		$("#box_body").html('<img src="/trms-admin/images/loader.gif">').load('/trms-admin/ajax/formanswer_edit.php', {action: "viewstatisticsbycountry", formid: $("#forms option:selected").val() } );
	});
	
	$("#gv_total_result").live('click', function(){
		$("#box_body").load('/trms-admin/ajax/formanswer_edit.php', {action: "viewstatistics", formid: $("#forms option:selected").val() } );
	});
	
	$("#gv_search").live('click', function(){
	
		//alert(formid);
		$("#box_body").load('/trms-admin/ajax/formanswer_edit.php', {action: "gvsearch", formid: formid} );
	});
	
	
	

	
})
