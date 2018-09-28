/* GlobalDB Scripts */
var countryid = 0;

$(function(){

	//Globals
	var recordid = 0; 
	var recordname = "";
	var typeid = "";
	var previousaction = "";
	var geocoder;
	var usertypeid = 0;
	var userstatusid = 0;
	var emailexists = 0;
	var commentid = 0;
	
	//var address;
	
	 $("#startdate").datepicker({
			showOn: 'button',
			buttonImage: '/trms-admin/images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Choose registration first date'
	});

	$("#enddate").datepicker({
			showOn: 'button',
			buttonImage: '/trms-admin/images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Choose registration last date'
	});
	
	$("select[id^='country_id']").live("change",function(){
		
		
		//alert($(this).val());
		
		if($(this).val() == 215){
			$("#state_id").hide();
			$("#s_region_id").show();
			$("#s_municipality_id").show();
			
		}else{
			$("#state_id").show();
			$("#s_region_id").hide();
			$("#s_municipality_id").hide();
		}
	
		//teamid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		
		//$.post('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		//$("#team_view_right").load('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });

	});
	
	$("select[id^='type']").live("change",function(){
		
		if($(this).val() == 1 && $("#checkboxview").is(":visible")){
			
			$("#program_year").show();
			$("#topicid").show();
			
		}else{
			$("#program_year").hide();
			$("#topicid").hide();
		}
	
		//teamid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		
		//$.post('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		//$("#team_view_right").load('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });

	});
	
	/*$("select[id^='orgcountry']").live("change",function(){
		
		
		if($(this).val() == 215){
		
		//alert($(this).val());
			//$("input[id^='orgstateregion']").hide();
			$("select[id^='orgregion']").show();
			$("select[id^=orgmunicipality']").show();
			
		}else{
			$("input[id^='orgstateregion']").show();
			$("select[id^='orgregion']").hide();
			$("select[id^=orgmunicipality]").hide();
		}
	
		//teamid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		
		//$.post('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		//$("#team_view_right").load('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });

	});*/
	
	
	$("select[id^='s_region_id']").live("change",function(){
		//alert($(this).attr('class'));
	
		//teamid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		
		//$.post('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		//$("#team_view_right").load('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });

		$.getJSON('/trms-content/ajax/municipality_select.php', {regionid: $(this).val()},function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		 $("select[id^='s_municipality_id']").html(options);
		});
	});
	
	$("select[id^='orgregion']").live("change",function(){
		
		$.getJSON('/trms-content/ajax/municipality_select.php', {regionid: $(this).val()},function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		 $("select[id^='orgmunicipality']").html(options);
		});
	});

	$("select[id^='municipalityid_']").live("change",function(){
		
		//alert($(this).attr('class'));
		//teamid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		
		//$("#team_view_right").load('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		//$.post('/trms-content/ajax/team_settings.php', {action: "saveteamdetail", teamid: teamid, teamdetail: $(this).attr('class'), teamdetailvalue: $(this).val() });
		
	});
	
	$("#search").live("click",function(){
		$("#newrecord").removeClass("active");
		$(this).addClass("active");
		
		$("#gf_view").slideUp();
		$("#gf_view_body").html("");
		$("#gf_search_body").slideDown();
		
		$("#newrecordform").hide();
		$("#searchform").show();
	});
	
	$("#newrecord").live("click", function(){
		
		$("#search").removeClass("active");
		$(this).addClass("active");
		
		$("#editrecord").text("Edit").addClass("active");
		$("#viewrecord").text("View").removeClass();
		
		$("#gf_view").slideUp();
		$("#gf_view_body").html("");
		$("#gf_search_body").slideDown();
		
		$("#searchform").hide();
		$("#newrecordform").show();
	});
	
	$("#closesearch").live('click', function(){
	
		$("#search").removeClass("active");
		$("#newrecord").removeClass("active");
		$("#gf_search_body").slideUp();
		
	})
	
	$("#closeview").live('click', function(){
	
		$("#viewrecord").removeClass("active");
		$("#editrecord").removeClass("active");
		$("#gf_view").slideUp();
		
	});
	
	$(".gf_input,.gf_input_date,.usr_input_new").live('focus',function(){
		$(this).css({"color": "#3B3B3B", "font-style": "normal"});
		$(this).val(""); 
	});
	
	// Clear and reset inputs 
	$(".gf_input,.gf_input_date,.usr_input_new").live('blur',function(){
		
		if( $(this).val() == "" ) {
			$(this).css({"color": "#AAAAAA", "font-style": "italic"});
			switch( $(this).attr("id") ){
				case "orgname":
					$(this).val("School or Organisation Name ...");
				break;
				case "startdate":
					$(this).val("Startdate");
				break;
				case "enddate":
					$(this).val("Enddate");
				break;
				case "new_record":
					$(this).val("Name - organisation or individual...");
				break;
				case "username":
					$(this).val("Fullname");
				break;
				case "accountname":
					$(this).val("Email/Accountname");
				break;
			}
		};
	});
	
	$("#searchform").submit(function(){
		var values = $(this).serialize();
			
		$("#gf_view").slideUp();
		$("#gf_view_body").html("");
		$("#gf_search_body").slideDown();	
			
		//alert(values);
		$("#gf_result_body").html('<img src="/trms-admin/images/loader.gif"/>').load("/trms-admin/ajax/globaldb_edit.php", values);
		
		return false;
	});
	
	$("#export_to_xls").live('click', function(){
	
		
		$("#export_fields").slideDown();
	});
	
	$("#exportform").live('submit', function(){
	
		var values = $(this).serialize();
		var stype = 0;
		
		
		//alert(values)
		
		if( $("#type_individual").is(":checked") == true) stype = 2; else stype = 1;
		
		sql = $("#sqlstr").val();
		
		//alert(sql);
		
		window.open('/trms-admin/globaldb_xls.php?sql=' + escape(sql) + '&' + values + '&stype=' + stype,'XLS_window','' );
		return false;
	
	});
	
	
	/**
	 *	Organisation and Individual type arrays
	 *
	 *
	 */
	
	var typesOrg = [{'value':'0','text':'Select organisation type'},
					{'value':'1','text':'Global Friend School'},
					{'value':'2','text':'Global Friend Group'},
					{'value':'4','text':'Adult Friend organisation'},
					{'value':'8','text':'Focal Point'},
					{'value':'16','text':'Donor - monthly'},
					{'value':'32','text':'Donor - one time'},
					{'value':'64','text':'Administrative'},
					{'value':'128','text':'Media'},
					{'value':'256','text':'Subscriber'}
					];
					
	var typesInd = [{'value':'0','text':'Select individual type'},
					{'value':'1','text':'Administrators'},
					{'value':'2','text':'Global Friends'},
					{'value':'4','text':'Adult Friends'},
					{'value':'8','text':'Patrons'},
					{'value':'16','text':'Donor - monthly'},
					{'value':'32','text':'Donor - one time'},
					{'value':'64','text':'Jury member'},
					{'value':'128','text':'Media'},
					{'value':'256','text':'Subscriber'},
					{'value':'512','text':'Laureate'},
					{'value':'1024','text':'Interpreter'},
					{'value':'2048','text':'Staff'},
					{'value':'4096','text':'Child musician'},
					{'value':'8192','text':'Chaperone'},
					{'value':'16384','text':'Intern'},
					{'value':'32768','text':'Volunteer'},
					{'value':'65536','text':'Award ceremony guest'},
					{'value':'131072','text':'Contact person GF-school'},
					];
					
	
	$("#type_individual").click(function(){
	
		$("#program_year").val('').hide();
		$("#topicid").val('').hide();
		$("#checkboxview").hide();
		
		$("#organisationid").show();
		
		
		$("#recordname").val('Individual name');
		$('#type option').remove();
		
		$.each(typesInd, function(i){
    	$('#type').append($("<option></option>")
                    .attr("value",typesInd[i]['value'])
                    .text(typesInd[i]['text']));
		});
	});
	
	$("#type_organisation").click(function(){
	
		$("#checkboxview").show();
		$("#organisationid").hide();
		
		$("#recordname").val('School or Organisation Name ...');
		$('#type option').remove();
		
		$.each(typesOrg, function(i){
    	$('#type').append($("<option></option>")
                    .attr("value",typesOrg[i]['value'])
                    .text(typesOrg[i]['text']));
		});
	});
	
	
	$("#new_org").click(function(){
		$("#new_account_name").hide();
		$("#new_record_name").val("Organisation name...");
		$("#new_account_name").css({"font-style":"italic","color":"#AAAAAA"}).val("Accountname/EMail");
	
	});
	
	$("#new_ind").click(function(){
		$("#new_account_name").show();
		$("#new_record_name").val("Fullname");
	});
	
	$("#new_account_name").live('blur', function(){
		var position =  $(this).offset();
		$.get('/trms-admin/ajax/globaldb_users.php',{action:"evaluateemail", recordid: recordid, accountname: $(this).val()}, function(data){
		 	if(data.indexOf("exists") > -1){
		 		$("#existsmsg").css({left: position.left + 210, top: position.top - 25}).fadeIn("slow").html(data);
		 		
		 		emailexists = 1;
		 	}else emailexists = 0;
		});
	});
	
	$("#new_account_name").live('focus', function(){
		$("#existsmsg").fadeOut();
	});
	
	$("#newrecordform").submit(function(){
		
		var newrecordname = $("#new_record_name").val();
		var values = $(this).serialize();
		
		if(emailexists == 1){ $("#existsmsg").fadeOut(); $("#new_account_name").focus(); return false; }
			
		
		//alert(values);
		
		$("#editrecord").text("Edit:" + newrecordname);
		
		$("#new_record_name").val("");
		$("#gf_view_body").load("/trms-admin/ajax/globaldb_edit.php", values);
		$("#gf_view").show();
		
		$("#new_account_name").css({"font-style":"italic","color":"#AAAAAA"}).val("Accountname/EMail");
		$("#new_record_name").css({"font-style":"italic","color":"#AAAAAA"}).val("Fullname");
		return false;
	});
	
	/*	Show a single organisation record */
	
	 $("a[id^='editorg_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
	 	typeid  = $(this).attr('id').substring(0, $(this).attr('id').lastIndexOf("_"));
	 	recordname = $(this).text();
	 	
	 	address = $("#geoaddress_" + recordid).val() +" " + $("#geocity_" + recordid).html() +" "+  $("#geocountry_" + recordid).html();
		//loadScript();
		
		//alert("Hupp Lat: " + lat + " Long: " + lng);
	 	
	 	$("#editrecord").text("Edit").removeClass();
	 	$("#viewrecord").text("View:" + recordname).addClass("active");
	 	$("#gf_view_body").load("/trms-admin/ajax/globaldb_edit.php", {action:"viewrecord", recordid:recordid, typeid:typeid, latitude: lat, longitude:lng});
		$("#gf_view").slideDown();
		
	 	
	 });
	 
	 /*	Show a single organisation record */
	
	 $("a[id^='edituser_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
	 	typeid  = $(this).attr('id').substring(0, $(this).attr('id').lastIndexOf("_"));
	 	recordname = $(this).text();
	 	
	 	//alert(recordid + " " + recordname + " " + typeid);
	 	
	 	$("#editrecord").text("Edit").removeClass();
	 	$("#viewrecord").text("View:" + recordname).addClass("active");
	 	
	 	
	 	$("#gf_view_body").load("/trms-admin/ajax/globaldb_edit.php", {action:"viewrecord", recordid:recordid, typeid:typeid});
		$("#gf_view").slideDown();
		/*
		address = $("#geoaddress_" + recordid).val() +" " + $("#geocity_" + recordid).html() +" "+  $("#geocountry_" + recordid).html();
		loadScript();
		*/
	 	
	 });
	 
	 // View tab
	 $("#viewrecord").live('click', function(){
	 	
	 	recordid = $("#recordid").val();
	 	typeid = $("#typeid").val();
	 	$("#editrecord").text("Edit").removeClass();
	 	$("#viewrecord").text("View:" + recordname).addClass("active");
	 	
	 	if( typeid == "editorg" ){
	 		recordname = $("#orgname_" + recordid).val();
	 		address = $("#orgaddress1_" + recordid).val() +" " + $("#orgcity_" + recordid).val() +" "+  $("#orgcountry_" + recordid + " option:selected").html();
			//loadScript();
	 	}
	 	else if( typeid == "edituser"){
	 		recordname = $("#usrname_" + recordid).val();
	 	}
	 	
	 	//alert("Lat: " + lat + " Long: " + lng);
	 	
	 	$("#gf_view_body").html("...").load("/trms-admin/ajax/globaldb_edit.php", {action:"viewrecord", recordid:recordid, typeid:typeid, latitude: lat, longitude:lng});
	 });
	 
	 // Edit tab
	 $("#editrecord").live('click', function(){
	 	//alert ("#editrecord " + typeid );
	 	$("#viewrecord").text("View").removeClass();
	 	$("#editrecord").text("Edit:" + recordname).addClass("active");
	 	
	 	
	 	
	 	$("#gf_view_body").load("/trms-admin/ajax/globaldb_edit.php", {action:"editrecord", recordid:recordid, typeid:typeid});
	 	
	 });
	 
	 $(".gf_input_edit,.gf_input_edit_date,.gf_textarea_edit,.gf_textarea_edit_narrow,.gf_input_edit_small,.gf_input_edit_medium").live('blur', function(){

		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		recorddetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		typeid = "edit" + $(this).attr('id').substring(0, 3);
		
		//alert(typeid);
		//alert($(this).attr('id') + " id:" + recordid + " detail:" + recorddetail + " typeid:" + typeid + " " + $(this).val());
		
		$.post('/trms-admin/ajax/globaldb_edit.php', {action: "saverecorddetail", recordid: recordid, recorddetail: recorddetail, recorddetailvalue: $(this).val(), typeid: typeid });
		//$("#console").load('/trms-admin/ajax/globaldb_edit.php', {action: "saverecorddetail", recordid: recordid, recorddetail: recorddetail, recorddetailvalue: $(this).val(), typeid: typeid });
	});
	
	
	var userbirthdate = "";
	$(".gf_input_edit_date").live('blur', function(){
		
		
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		recorddetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		typeid = "edit" + $(this).attr('id').substring(0, 3);
		userbirthdate = $(this).val();
		
		for(i=0;i<userbirthdate.length;i++){
			
			if( (i==4 || i == 7) && userbirthdate.charAt(i) != '-' ){
				alert("Date format YYYY-MM-DD\nEx: 1998-03-24");
				//$(this).val('0000-00-00');
				return;
			}
			else if( i!=4 && i != 7 && true == isNaN(userbirthdate.charAt(i)) ){
				alert("Date format YYYY-MM-DD\nEx: 1998-03-24");
				//$(this).val('0000-00-00');
				return;
			}
			
		}

		
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "saverecorddetail", recordid: recordid, recorddetail: recorddetail, recorddetailvalue: $(this).val(), typeid: typeid });
	});


	 $(".gf_select_edit").live('change', function(){

		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		recorddetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		typeid = "edit" + $(this).attr('id').substring(0, 3);
		
		//alert(typeid + " " + recordid + " " + recorddetail + $(this).val());
		
		$.post('/trms-admin/ajax/globaldb_edit.php', {action: "saverecorddetail", recordid: recordid, recorddetail: recorddetail, recorddetailvalue: $(this).val(), typeid: typeid });
		//$("#gf_view_body").load('/trms-admin/ajax/globaldb_edit.php', {action: "saverecorddetail", recordid: recordid, recorddetail: recorddetail, recorddetailvalue: $(this).val(), typeid: typeid });
	
	});
	
	/* WCP program edit */
	
	$("img[id^='prg_']").live('click', function(){
	
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("x")+1 );
		programid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').indexOf("x"));
		//alert(recordid + " " + programid);	
		$("#prg_" + programid).load('/trms-admin/ajax/globaldb_program.php',{action:"editprogram", recordid: recordid, programid: programid } )
	});
	
	$("img[id^='saveprg_']").live('click', function(){
	
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("x")+1 );
		programid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').indexOf("x"));
		//alert(recordid + " " + programid);	
		$("#prg_" + programid).load('/trms-admin/ajax/globaldb_program.php',{action:"displayprogram", recordid: recordid, programid: programid } )
	});
	
	$("img[id^='deleteprg_']").live('click', function(){
	
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("x")+1 );
		programid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').indexOf("x"));
		//alert(recordid + " " + programid);	
		
		if(confirm('Delete this program?')){
		$.post('/trms-admin/ajax/globaldb_program.php',{action:"deleteprogram", recordid: recordid, programid: programid } );
		$("#prg_" + programid).remove();
		}
	});
	
	$("#createprg").live('click', function(){
		//alert(recordid);
		 $.get('/trms-admin/ajax/globaldb_program.php',{action:"createprogram", recordid: recordid}, function(data){
		 	$("#tbl_caption").after(data);
		 });
	});
	
	$(".prg_input_edit,.prg_input_edit_medium,.prg_input_edit_wide,.prg_textarea_edit").live('blur', function(){

		prgid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		prgdetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		
		//alert("id:" + prgid + " detail:" + prgdetail + " val:"+ $(this).val());
		$.post('/trms-admin/ajax/globaldb_program.php', {action: "saveprgdetail", programid: prgid, programdetail: prgdetail, programdetailvalue: $(this).val() });
		//$("#console").load('/trms-admin/ajax/globaldb_program.php', {action: "saveprgdetail", programid: prgid, programdetail: prgdetail, programdetailvalue: $(this).val() });
	});
	
	/**
	 * User edit 
	 *
	 */
	
	$("img[id^='usr_']").live('click', function(){
	
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("x")+1 );
		userid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').indexOf("x"));
		//alert(userid + " " + recordid);	
		$("#wcp_edit_user").slideDown().load('/trms-admin/ajax/globaldb_users.php',{action:"edituser", userid: userid, recordid: recordid } );
	});
	
	$("img[id^='usrdelete_']").live('click', function(){
	
		recordid = $(this).attr('id').substring($(this).attr('id').indexOf("x")+1 );
		userid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').indexOf("x"));
		
		if(confirm("Do you want to delete user " + userid  + " from " + recordid)){
		$("#wcp_usr_list").load('/trms-admin/ajax/globaldb_users.php', {action: "deleteuser", userid: userid, recordid: recordid});
		}
		
		//$("#wcp_edit_user").slideDown().load('/trms-admin/ajax/globaldb_users.php',{action:"edituser", userid: userid, recordid: recordid } );
	});
	
	$(".usr_input_edit").live('blur', function(){

		usrid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		usrdetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		
		//alert("id:" + usrid + " detail:" + usrdetail + " val:"+ $(this).val());
		$.post('/trms-admin/ajax/globaldb_users.php', {action: "saveuserdetail", userid: usrid, usrdetail: usrdetail, usrdetailvalue: $(this).val() });
		//$("#console").load('/trms-admin/ajax/globaldb_users.php', {action: "saveuserdetail", userid: usrid, usrdetail: usrdetail, usrdetailvalue: $(this).val() });
	});
	
	$(".usr_select_edit").live('change', function(){
	
		usrid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		usrdetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		
		
		$.post('/trms-admin/ajax/globaldb_users.php', {action: "saveuserdetail", userid: usrid, usrdetail: usrdetail, usrdetailvalue: $(this).val() });
	});
	
	$("#wcp_close_useredit").live('click', function(){
	
		$("#wcp_edit_user").slideUp("slow").html("");
		$("#gf_view_card_extra_body").html("....").load('/trms-admin/ajax/globaldb_users.php', {action: "listusers", recordid: recordid});
	});
	
	
	$("#createuser").live('click', function(){
		$("#wcp_edit_user").slideDown().load('/trms-admin/ajax/globaldb_users.php',{action:"createnewuser", recordid: recordid});
	});
	
	
	
	$("#accountname").live('blur', function(){
		var position =  $(this).offset();
		$.get('/trms-admin/ajax/globaldb_users.php',{action:"evaluateemail", recordid: recordid, accountname: $(this).val()}, function(data){
		 	if(data.indexOf("exists") > -1){
		 		$("#tooltip").css({left: position.left + 210, top: position.top - 25}).fadeIn("slow").html(data);
		 		emailexists = 1;
		 	}else emailexists = 0;
		});
	});
	
	$("#accountname").live('focus', function(){
		$("#tooltip").fadeOut();
	});
	
	$("#savenewuser").live('click', function(){
	
		var fullname = $("#fullname").val();
		var accountname = $("#accountname").val();
		
		if(emailexists == 0){
			//alert( "New Registration: " + fullname + " new " + accountname + " recordid " + recordid );
			$("#wcp_edit_user").load('/trms-admin/ajax/globaldb_users.php',{action:"savenewuser", fullname: fullname, accountname: accountname, recordid: recordid } );
		}
		else{
			$("#accountname").focus();
		}
	});
	
	/**
	 *
	 *
	 */
	
	$(".setstatus").live('click', function(){
		
		statusid = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1,$(this).attr("id").lastIndexOf("_"));
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($(this).is(":checked") == true)
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "checkstatus", recordid: recordid, statusid: statusid });
		else
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "uncheckstatus", recordid: recordid, statusid: statusid });
	});
	
	
	 $("td[id^='showrecord_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
		
		if($("tr[id^='compare_" +recordid+ "']").is(":visible") == true){
			$("tr[id^='compare_" +recordid+ "']").slideUp();
			$("div[id^='showorg_"+recordid+"']").html("");
		}else{
		
			//address = $("#geoaddress_" + recordid).val() +" " + $("#geocity_" + recordid).html() +" "+  $("#geocountry_" + recordid).html();
			//loadScript();
			
			$("div[id^='showorg_"+recordid+"']").load("/trms-admin/ajax/globaldb_edit.php", {action: "viewrecord", recordid: recordid, typeid: "editorg", latitude: lat, longitude:lng});
			$("tr[id^='compare_" +recordid+ "']").slideDown();
		}
	});
	
	 $("img[id^='editorg_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
		
		if($("tr[id^='compare_" +recordid+ "']").is(":visible") == true){
			$("tr[id^='compare_" +recordid+ "']").slideUp();
			$("div[id^='showorg_"+recordid+"']").html("");
		}else{
		
			//address = $("#geoaddress_" + recordid).val() +" " + $("#geocity_" + recordid).html() +" "+  $("#geocountry_" + recordid).html();
			//loadScript();
			
			$("div[id^='showorg_"+recordid+"']").load("/trms-admin/ajax/globaldb_edit.php", {action: "editrecord", recordid: recordid, typeid: "editorg", latitude: lat, longitude:lng});
			$("tr[id^='compare_" +recordid+ "']").slideDown();
		}
	});
	
	$(".settype").live('click', function(){
		
		orgtypeid = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1,$(this).attr("id").lastIndexOf("_"));
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($(this).is(":checked") == true)
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "checktype", recordid: recordid, orgtypeid: orgtypeid });
		else
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "unchecktype", recordid: recordid, orgtypeid: orgtypeid });
		
	});
	
	$(".setuserstatus").live('click', function(){
		
		userstatusid = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1,$(this).attr("id").lastIndexOf("_"));
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($(this).is(":checked") == true)
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "checkuserstatus", recordid: recordid, userstatusid: userstatusid });
		else
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "uncheckuserstatus", recordid: recordid, userstatusid: userstatusid });
		
	});
	
	$(".setusertype").live('click', function(){
		
		usertypeid = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1,$(this).attr("id").lastIndexOf("_"));
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($(this).is(":checked") == true)
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "checkusertype", recordid: recordid, usertypeid: usertypeid });
		else
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "uncheckusertype", recordid: recordid, usertypeid: usertypeid });
			
		
		
	});
	
	/*
	$(".gf_button").live('click', function(){
	
		actiontype = $(this).attr("id").substring(0,$(this).attr("id").lastIndexOf("_"));
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($("#gf_view_card_extra_body").is(":hidden")){
			$("#gf_view_card_extra_body").html("....").load('/trms-admin/ajax/globaldb_edit.php', {action:actiontype, recordid: recordid});
			$("#gf_view_card_extra_body").slideDown();
		}else if(previousaction == actiontype){
			$("#gf_view_card_extra_body").html("").slideUp();
		}
		else{
			$("#gf_view_card_extra_body").html("...").load('/trms-admin/ajax/globaldb_edit.php', {action:actiontype, recordid: recordid});
		}
		
		previousaction = actiontype;
	});
	*/
	
	$("input[id^='wcp-program_']").live('click', function(){
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		if($("#gf_view_card_extra_body_" + recordid).is(":visible"))$("#gf_view_card_extra_body" + recordid).slideUp();
		$("#gf_view_card_extra_body_" + recordid).html("....").load('/trms-admin/ajax/globaldb_program.php', {action: "listprograms", recordid: recordid});
		$("#gf_view_card_extra_body_" + recordid).slideDown();
	});
	
	$("input[id^='individuals_']").live('click', function(){
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		
		if($("#gf_view_card_extra_body_" + recordid).is(":visible"))$("#gf_view_card_extra_body_" + recordid).slideUp();
		$("#gf_view_card_extra_body_" + recordid).html("<br/><br/>....").load('/trms-admin/ajax/globaldb_users.php', {action: "listusers", recordid: recordid});
		
		$("#gf_view_card_extra_body_" + recordid).slideDown();
	});
	
	$("input[id^='wcp-topics_']").live('click', function(){
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		$("#gf_view_card_extra_body_" + recordid).html("...").load('/trms-admin/ajax/globaldb_topic_edit.php', {action: "topicsfororganisation", recordid: recordid});
		
		if($("#gf_view_card_extra_body_" + recordid).is(":visible"))$("#gf_view_card_extra_body").slideUp();
		$("#gf_view_card_extra_body_" + recordid).slideDown();
	});
	
	questiontext = 	'<i>Under contruction...</i><br/><br/>' +
					'<input type="checkbox"> Pressconference 2014 <br/>' +
					'<input type="checkbox" CHECKED> Interested of Delegation visit 2014 <br/>' +
					'<input type="checkbox"> Delegation visit 2014<br/>' +
					'<input type="checkbox"> Award ceremony participation 2014 <br/>';
	
	$("input[id^='comments_']").live('click', function(){
	
		
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		//alert(recordid);
		$("#gf_view_card_extra_body_" + recordid).html('<img src="/trms-admin/images/loader.gif"/>').load('/trms-admin/ajax/globaldb_comments.php', {action: "listcomments", recordid: recordid});
		
		if($("#gf_view_card_extra_body_" + recordid).is(":visible"))$("#gf_view_card_extra_body").slideUp();
		$("#gf_view_card_extra_body_" + recordid).slideDown();
	});
	
	$("#newcontactcomment").live('click', function(){
		$("#editcontactcomment").show();
	});
	
	$("#savecontactcomment").live('click', function(){
	
		//alert(commentid + " " + recordid + " " + $("#contactcommenttext").val());
	
		$("#gf_view_card_extra_body" + recordid).load('/trms-admin/ajax/globaldb_comments.php', {action: "savecomment", cid: commentid, recordid: recordid, ctext: $("#contactcommenttext").val()});
		$("#contactcommenttext").val("");
		$("#editcontactcomment").slideUp();
		$("#commentid").val(0);
		commentid = 0;
		
	});
	
	$("img[id^='editcomment_']").live('click', function(){
	
	 commentid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
	 
	 $("#editcontactcomment").slideDown();
	 $("#contactcommenttext").val($("#comment_" + commentid).text());
	 $("#commentid").val(commentid);
	 
	});
	
	$("img[id^='deletecomment_']").live('click', function(){
	
	 	commentid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
	 
	 	if(confirm("Delete this comment?")){
	 		$("#gf_view_card_extra_body" + recordid).load('/trms-admin/ajax/globaldb_comments.php', {action: "deletecomment", cid: commentid, recordid: recordid});
		}
		commentid = 0;
	 });
	
	$("input[id^='checkcomment_']").live('click', function(){
	
	 	commentid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
	 	
	 	if($(this).is(":checked") == true){
	 		//alert("jag är checkad och läst");
			$.post('/trms-admin/ajax/globaldb_comments.php', {action: "unsetcommentstatus", cid: commentid });
			//$("#testbug").load('/trms-admin/ajax/globaldb_comments.php', {action: "unsetcommentstatus", cid: commentid });
		}else{
			//alert("jag är INTE checkad och nu oläst");
			$.post('/trms-admin/ajax/globaldb_comments.php', {action: "setcommentstatus", cid: commentid });
			//$("#testbug").load('/trms-admin/ajax/globaldb_comments.php', {action: "setcommentstatus", cid: commentid });
		}
	 	
	 	//alert(commentid);
	 	// Check or uncheck commentbox
	 	
		commentid = 0;
	 });
	 
	 
	 $("input[id^='connected_']").live('click', function(){
	
		
		recordid = $(this).attr("id").substring($(this).attr("id").lastIndexOf("_")+1,$(this).attr("id").length);
		//alert(recordid);
		$("#gf_view_card_extra_body_" + recordid).html('<img src="/trms-admin/images/loader.gif"/>').load('/trms-admin/ajax/globaldb_linkedto.php', {action: "listconnected", recordid: recordid});
		
		if($("#gf_view_card_extra_body_" + recordid).is(":visible"))$("#gf_view_card_extra_body").slideUp();
		$("#gf_view_card_extra_body_" + recordid).slideDown();
	});
	
	$("input[id^='deleteorg_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
	 	sql = $("#condition").html();
	 	
	 	//alert(sql);
		
		if(confirm("Do you really want to delete this record")){
			$("#gf_result_body").load('/trms-admin/ajax/globaldb_edit.php', {action: "deleteorganisation", recordid: recordid, condition: sql});
			$("#gf_view_body").html("Deleted");
			$("#gf_view").slideUp();
			$("#gf_view_body").html("");
		}
	});
	
	$("input[id^='deleteuser_']").live('click',function(){
	 
	 	userid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
	 	sql = $("#condition").html();
	 	
	 	//alert(sql);
		
		if(confirm("Do you really want to delete this record")){
			$("#gf_result_body").load('/trms-admin/ajax/globaldb_edit.php', {action: "deleteuser", recordid: userid, condition: sql});
			$("#gf_view_body").html("Deleted");
			$("#gf_view").slideUp();
			$("#gf_view_body").html("");
		}
	});
	
	$("img[id^='deleterow_']").live('click',function(){
	 
	 	recordid = $(this).attr('id').substring($(this).attr('id').lastIndexOf("_")+1);
	 	//sql = $("#condition").html();
	 	
	 	//alert(sql);
		
		if(confirm("Do you really want to delete this record " + recordid)){
			$("#showorg_" + recordid).load('/trms-admin/ajax/globaldb_edit.php', {action: "deleteorganisation", recordid: recordid});
			$("#compare_" + recordid ).slideUp();
			$("#compare_" + recordid).remove();
			$("#editrow_" + recordid).remove();
		}
	});
	
	/* WCP topic edit */
	
	$("#newtopicform").submit(function(){
		
		var newtopicname = $("#new_topic_name").val();
		var values = $(this).serialize();
		
		if(newtopicname == "" || newtopicname == "Topic name"){ alert("No topic name. Enter a name for your topic!"); return false }
		
		$("#new_topic_name").val("sadasd");
		$("#gf_topic_list").html("auuuuuuu").load("/trms-admin/ajax/globaldb_topic_edit.php", values);
		//$("#new_topic_name").css({"font-style":"italic","color":"#AAAAAA"}).val("Topic name");
		
		return false;
	});
	
	$("img[id^='topicedit_']").live('click', function(){
	
		topicid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').length);
		//alert(topicid);	
		$("#topic_" + topicid).load('/trms-admin/ajax/globaldb_topic_edit.php',{action:"edittopic", topicid: topicid} )
	});
	
	$("img[id^='topicsave_']").live('click', function(){
	
		topicid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').length);
		//alert(topicid);	
		$("#topic_" + topicid).load('/trms-admin/ajax/globaldb_topic_edit.php',{action:"displaytopic", topicid: topicid} )
	});
	
	$("img[id^='topicdelete_']").live('click', function(){
	
		topicid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1, $(this).attr('id').length);
		//alert(topicid);
		
		if(confirm('Delete this topic?')){
		//$("#gf_topic_list").load('/trms-admin/ajax/globaldb_topic_edit.php',{action:"deletetopic", topicid: topicid} );
		$.post('/trms-admin/ajax/globaldb_topic_edit.php',{action:"deletetopic", topicid: topicid} );
		$("#topic_" + topicid).remove();
		}
		
	});
	
	$(".topic_input_edit").live('blur', function(){

		topicid = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1 );
		topicdetail = $(this).attr('id').substring(0, $(this).attr('id').indexOf("_"));
		
		
		//alert("id:" + topicid + " detail:" + topicdetail + " val:"+ $(this).val());
		$.post('/trms-admin/ajax/globaldb_topic_edit.php', {action: "savetopicdetail", topicid: topicid, topicdetail: topicdetail, topicdetailvalue: $(this).val() });
		
		//$("#console").load('/trms-admin/ajax/globaldb_topic_edit.php', {action: "savetopicdetail", topicid: topicid, topicdetail: topicdetail, topicdetailvalue: $(this).val() });
		//$("#console").load('/trms-admin/ajax/globaldb_program.php', {action: "saveprgdetail", programid: prgid, programdetail: prgdetail, programdetailvalue: $(this).val() });
	});
	
	
	$("input[id^='topicsetstatus_']").live('click', function(){
		
		var topicid = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1, $(this).attr("id").length);
	
		//alert($(this).attr("id") + " topicid:" + topicid + " orgid:" + recordid);
		
		
		if($(this).is(":checked") == true){
			$.post('/trms-admin/ajax/globaldb_topic_edit.php', {action: "settopicstatus", topicid: topicid });
		}else{
			$.post('/trms-admin/ajax/globaldb_topic_edit.php', {action: "unsettopicstatus", topicid: topicid });
		}
		
		
	});
	
	$("#topicviewall").live('click', function(){
		$("#gf_topic_list").load('/trms-admin/ajax/globaldb_topic_edit.php',{action:"listtopics", viewall: "yes"} );
	});
	
	$("#topicviewcurrent").live('click', function(){
		$("#gf_topic_list").load('/trms-admin/ajax/globaldb_topic_edit.php',{action:"listtopics", viewall: "no"} );
	});
	
	$("input[id^='checktopic_']").live('click', function(){
		
		var topicid = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1, $(this).attr("id").indexOf("x"));
		var recordid = $(this).attr("id").substring($(this).attr("id").indexOf("x")+1, $(this).attr("id").length);
	
		//alert($(this).attr("id") + " topicid:" + topicid + " orgid:" + recordid);
		
		if($(this).is(":checked") == true){
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "checktopic", topicid: topicid, recordid: recordid });
		}else{
			$.post('/trms-admin/ajax/globaldb_edit.php', {action: "unchecktopic", topicid: topicid, recordid: recordid });
		}
		
	});
	
	$("input[id^='countrysetactive_']").live('click', function(){
		countryid = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1, $(this).attr("id").length);
		
		if($(this).is(":checked") == true)
		$.post('/trms-admin/ajax/globaldb_edit.php', {action: "setactive", countryid: countryid, checked: 1 });
		else
		$.post('/trms-admin/ajax/globaldb_edit.php', {action: "setactive", countryid: countryid, checked: 0 });
	});
	
	// new 2017-12-11
 	$("input[id^='setcoord_']").live("click", function(){
 		countryid = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1, $(this).attr("id").length);
		//alert(countryid);
		
		 initMap();
	
		$("#overlay").fadeIn('fast', function(){
				$(this).css("filter","alpha(opacity=30)");
		});
		
		$("#mapcontainer").show('fast', function(){
			$("#mapcanvas").show();
			//$(this).load("/trms-content/ajax/standard_form.php", values);
		});
	});
	
	$("#closeoverlay").live("click", function(){
	
		$("#overlay").fadeOut('fast');
		$("#mapcontainer").fadeOut('fast');
	
	});
	// end
	
	 
});


var strLatLng;
var lat;
var lng;
var address = "Tandåsgatan 12 Göteborg Sverige";
	
function geometrics(){
	
		var geo = new google.maps.Geocoder;
		geo.geocode({'address':address},function(results, status){
    		if (status == google.maps.GeocoderStatus.OK) { 
    	            
        	myLatLng = results[0].geometry.location.toString();
        	strLatLng = myLatLng.substring(1,myLatLng.length-1);
        	lat  = strLatLng.substring(0,strLatLng.indexOf(","));
        	lng  = strLatLng.substring(strLatLng.indexOf(",")+1, strLatLng.length);
        	initialize();
       		} else {
        	alert("Geocode was not successful for the following reason: " + status);
    		}
    	});
}
	
function initialize(){

	var mapProp = {
  		center:new google.maps.LatLng(lat, lng),
  		zoom:10,
  		mapTypeId:google.maps.MapTypeId.ROADMAP
  	};
	var map=new google.maps.Map(document.getElementById("map"),mapProp);
  	
  	var marker=new google.maps.Marker({
  		position:mapProp.center,
 	 });

	marker.setMap(map);
}
	
function loadScript() {
 	 var script = document.createElement('script');
 	 script.type = 'text/javascript';
  	 script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
      'callback=geometrics';
  	document.body.appendChild(script);
}

// nytt från 20171211
var map;
var initialized = 0;
function initMap(){

	//alert(countryid);
	
	if(initialized < 1){
		map = new google.maps.Map(document.getElementById("mapcanvas"), {
		center: {lat:  26.21351, lng: -15.68921},
		zoom: 3
		});
		initialized = 1;
		
		google.maps.event.addListener(map, "click", function(event) {
    		var lat = event.latLng.lat();
   			var lng = event.latLng.lng();
    		$("#latitude_"+countryid).val(lat);
    		$("#longitude_"+countryid).val(lng);
    		$.post('/trms-admin/ajax/globaldb_country_coords.php', {action: "savecoords", country_id: countryid, latitude: lat, longitude: lng });

		});
		
		// if theese values are set - Place the marker on the map
		if($("#latitude_"+countryid).val() != "" && $("#longitude_"+countryid).val() != ""){
		
			var marker = new google.maps.Marker({
      		position: { lat: parseFloat($("#latitude_"+countryid).val()), lng: parseFloat($("#longitude_"+countryid).val())},
      		map: map
      		});
      	}
      	
	}
}


