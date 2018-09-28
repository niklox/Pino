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

	$("#searchglobalvote").click(function(){
		$("#box_body_top").html('');
		$("#globalvotelist").html('');
		$("#globalvotelist").load('/trms-admin/ajax/formanswer_edit.php', {action: "listglobalvote", formid: $("#formid").val(), startdate: $("#startdate").val(),  enddate: $("#enddate").val(), countryid: $("#countryid option:selected").val(), votetype: $("#votetype option:selected").val()});
	});

	$("#statistics").click(function(){
		$("#box_body").load('/trms-admin/ajax/formanswer_edit.php', {action: "viewstatistics", formid: $("#forms option:selected").val() } );
	});

	
})
