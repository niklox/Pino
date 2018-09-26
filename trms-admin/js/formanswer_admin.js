$(function(){
	
	$("#box_head").hide();
	$("#box_body").hide();

	$("#forms").change(function(){
		$('#box_head').fadeIn('100', function(){
			$(this).html($("#forms option:selected").html() );
		});
		
		
		$("#box_body").fadeIn('100', function(){
			$("#formanswerlist").load('/trms-admin/ajax/formanswer_edit.php', {action: "default", formid: $("#forms option:selected").val() } ); 
		});

		$('#formselect').fadeOut();
	});

	$(".formanswer").live('click', function(){
		
		var answerid = $(this).attr("value");
		$("#box_body_top").fadeIn('100', function(){
			$(this).load('/trms-admin/ajax/formanswer_edit.php', {action: "viewformanswer", formanswerid: answerid, formid: $("#forms option:selected").val() } ); 
		})
	});

	$("input[id^='deletegv_']").live("click", function(){

		$('tr').remove('#gvrow_' + $(this).attr("id").substring(9) );

		$("#box_body_top").fadeOut('slow');

		$.post('/trms-admin/ajax/formanswer_edit.php', {action: "deleteformanswer", formanswerid: $(this).attr("id").substring(9) });
	});

	$("input[id^='deleteanswer_']").live("click", function(){
		
		if(confirm("Do you want to delete this answer?")){
			
			$('li').remove('#answer_' + $(this).attr("id").substring(13) );
			$("#box_body_top").fadeOut('slow');
			$.post('/trms-admin/ajax/formanswer_edit.php', {action: "deleteformanswer", formanswerid: $(this).attr("id").substring(13) });
		}

	});


	$(".globalvote").live('click', function(){
		
		var answerid = $(this).attr("value");
		$("#box_body_top").fadeIn('slow', function(){

			$(this).load('/trms-admin/ajax/formanswer_edit.php', {action: "viewformanswer", formanswerid: answerid, formid: $("#forms option:selected").val() } );
		});
	});

	

});