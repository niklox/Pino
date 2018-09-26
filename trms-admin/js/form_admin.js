$(function(){
	
	$("#box_head").hide();
	$("#box_body").hide();

	$("#forms").change(function(){
		$('#box_head').fadeIn('100', function(){
			$(this).html($("#forms option:selected").html() + "<input type=\"button\" class=\"whtbtn\" id=\"viewform\" value=\"View this form\" />");
		});
		$("#box_body").fadeIn('100', function(){
			$(this).load('/trms-admin/ajax/form_edit.php', {action: "default", formid: $("#forms option:selected").val() } ); //, formid: $("#forms option:selected").val()
		});

		$('#formselect').fadeOut();
	});

	/*
	 *	Form edit and create
	 *
	 */

	$("#viewform").live('click', function(){

		if( $(this).attr("value") == "View this form" ){
			$("#box_body").load("/trms-admin/ajax/form_edit.php", {action: "viewform", formid: $("#formid").val()} )
			$(this).attr("value", "Edit form");
		}else{
			$("#box_body").load("/trms-admin/ajax/form_edit.php", {action: "default", formid: $("#formid").val()} )
			$(this).attr("value", "View this form");
		}
	});

	$("#createForm").click(function(){

		$("#forms").val(0);
		
		$('#box_head').fadeIn('100', function(){
			$(this).html($("#formname").val());
		});
		$("#box_body").fadeIn('100', function(){
			$(this).load('/trms-admin/ajax/form_edit.php', {action: "default", formname: $("#formname").val() } ); //, formid: $("#forms option:selected").val()
		});
		
		$('#formselect').fadeOut();
	});

	$("#selectedform,#formaction,#formrecipient,#formstatus").live('blur', function(){
		$.post('/trms-admin/ajax/form_edit.php', {action: "save", formid: $("#formid").val(), formname: $("#selectedform").val(), formtype: $("#formtype").val(), formaction: $("#formaction").val(), formrecipient: $("#formrecipient").val(), formstatus: $("#formstatus").val()}, function(data){	
			var $response=$(data);
			var id = $response.filter('#newid').text();
			$("#formid").attr('value', id);
		});

		$('#box_head').html($("#selectedform").val());
	});
	
	$("#formtype").live('change', function(){
		
		$.post('/trms-admin/ajax/form_edit.php', {action: "save", formid: $("#formid").val(), formname: $("#selectedform").val(), formtype: $("#formtype").val(), formaction: $("#formaction").val(), formrecipient: $("#formrecipient").val(), formstatus:  $("#formstatus").val() } );

		if( $(this).val() == 0 ){
			$("#formaction").fadeIn();
			$("#formrecipient").fadeIn();
		}
		else if( $(this).val() == 1 || $(this).val() == 2 ){
			$("#formaction").fadeOut();
			$("#formrecipient").fadeOut();
		}
	});

	$("#deleteform").live('click', function(){
		if( confirm("Do you want to delete" ) ){
			$.post('/trms-admin/ajax/form_edit.php', {action: "delete", formid: $("#formid").val()} );
			$("#box_head").fadeOut();
			$("#box_body").fadeOut();
			
			$("#formselect").html(function(){
				$(this).load('/trms-admin/ajax/form_edit.php', {action: "formselect"} );
				$(this).fadeIn();
			})

			$("#forms").live('change', function(){
		
				$('#box_head').fadeIn('100', function(){
					$(this).html($("#forms option:selected").html());
				});
				$("#box_body").fadeIn('100', function(){
					$(this).load('/trms-admin/ajax/form_edit.php', {action: "default", formid: $("#forms option:selected").val() } ); //, formid: $("#forms option:selected").val()
				});

				$('#formselect').fadeOut();
			
			});
		}
	});

	$("#copyform").live('click', function(){
		$("#box_body").load('/trms-admin/ajax/form_edit.php', { action: "copyform", formid: $("#formid").val() } );
	});

	/*
	 *	set default answer
	 *
	 */

	 $('#showform').live('submit', function() {
		//alert($(this).serialize());

		$("#box_body").load("/trms-admin/ajax/form_edit.php", $(this).serialize() );
		return false;
	});

	
	/*
	 *	Forminput edit, create and save
	 *
	 */

	 $(".forminput").live('click', function(){
		$("#selectedforminput").load('/trms-admin/ajax/form_edit.php',{action: "editinput", forminputid: $(this).attr('value')},function(){
			$(this).fadeIn();
		});
	 });

	 $("#close_btn").live('click', function(){
		$("#selectedforminput").fadeOut();
	 });

	 $("#forminputtype").live('change', function(){

		if( $(this).val() == 3 || $(this).val() == 4 || $(this).val() == 6 || $(this).val() == 10){
			$('#options_no option:eq(0)').attr('selected', 'selected');
			$("#options_no").fadeIn();
		}else{
			$('#options_no option:eq(1)').attr('selected', 'selected');
			$("#options_no").fadeOut();
		}

	 });

	 $("#createnewinput").live('click', function(){

		if(( $("#forminputtype option:selected").val() == 3 || $("#forminputtype option:selected").val() == 4 || $("#forminputtype option:selected").val() == 6 )  && $("#options_no").val() == 0 ){
			alert("Select number of options");
		}else{
			$("#selectedforminput").load('/trms-admin/ajax/form_edit.php',{action: "saveinput", formid: $("#formid").val(), forminputid: 0, options_no: $("#options_no").val(), forminputtypeid: $("#forminputtype option:selected").val()},function(){
				$(this).fadeIn();
			});
		}
	 });

	$("#inputtitle,#inputquestion,#inputcomment,#nameattribute,#imageurl").live('blur', function(){
		$.post('/trms-admin/ajax/form_edit.php', {action: "saveinput", forminputid: $("#forminputid").val(), inputtitle: $("#inputtitle").val(), inputquestion: $("#inputquestion").val(), inputcomment: $("#inputcomment").val(), nameattribute: $("#nameattribute").val(), imageurl: $("#imageurl").val(), inputposition: $("#inputposition").val(), inputpage: $("#inputpage").val(), inputvisibility: $("#inputvisibility").val() } );
		$("#forminputlist").load('/trms-admin/ajax/form_edit.php',{action: "updateforminputlist", formid: $("#formid").val()});
	});

	$("#inputposition,#inputpage,#inputvisibility").live('change', function(){
		$.post('/trms-admin/ajax/form_edit.php', {action: "saveinput", forminputid: $("#forminputid").val(), inputtitle: $("#inputtitle").val(), inputquestion: $("#inputquestion").val(), inputcomment: $("#inputcomment").val(), nameattribute: $("#nameattribute").val(), imageurl: $("#imageurl").val(), inputposition: $("#inputposition").val(), inputpage: $("#inputpage").val(), inputvisibility: $("#inputvisibility").val() } );
	});

	$("#inputposition,#inputpage,#inputvisibility").live('blur', function(){
		$("#forminputlist").load('/trms-admin/ajax/form_edit.php',{action: "updateforminputlist", formid: $("#formid").val()});
	});

	$(".forminputoption_text").live('blur', function(){
		$.post('/trms-admin/ajax/form_edit.php', {action: "saveinputoption", forminputoptionid: $(this).attr("id").substring(2,$(this).attr("id").length), optionname: $(this).val()});
	});

	$("#viewforminput").live('click', function(){
			
		if( $(this).attr("value") == "View this forminput" ){
			$("#theforminput").load('/trms-admin/ajax/form_edit.php', {action: "editorview", forminputid: $("#forminputid").val(), viewinput: "true" });
			$(this).attr("value", "Edit forminput labels");
		}else{
			$("#theforminput").load('/trms-admin/ajax/form_edit.php', {action: "editorview", forminputid: $("#forminputid").val(), viewinput: "false" });
			$(this).attr("value", "View this forminput");
		}
	});

	$("#deleteforminput").live('click', function(){
		if( confirm("Do you want to delete this forminput?" ) ){
			$.post('/trms-admin/ajax/form_edit.php', {action: "deleteforminput", forminputid: $("#forminputid").val()} );
			$("#selectedforminput").fadeOut();
			$("#forminputlist").load('/trms-admin/ajax/form_edit.php',{action: "updateforminputlist", formid: $("#formid").val()});

		}
	});
})

