$(function(){
	
	var id;
	var cid;
	var pos;

	$("#createddate").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Select a date'
	});

	$("#archivedate").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			buttonText: 'Select a date'
	});

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

	$("textarea[id^='contentTexts']").focusin( function(event) {
		$(this).height("400px");
	});

	$("textarea[id^='contentTexts']").focusout( function(event) {
		$(this).height("49px");
	});
	
	$("textarea[name^='textcontent']").focusin( function(event) {
		$(this).height("400px");
	});

	$("textarea[name^='textcontent']").focusout( function(event) {
		$(this).height("20px");
	});

	$("select[id^='blocktype_']").change( function(event) {

		id = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		cid = id.substring(0, id.indexOf("x"));
		pos = id.substring(id.indexOf("x")+1);
		
		//alert(id + " " + cid + " " + pos + " " + $(this).val());
		
		$.post('/trms-admin/ajax/blocktypeset.php', {action: "setblocktype", cid: cid, position: pos, blocktypeid: $(this).val() });
		
		$.getJSON('/trms-admin/ajax/blocktype_options.php', {blocktypeid: $(this).val()},function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		  $("#blocktypeoption_" + id ).html(options);
		});
	
	});
	
	$("select[id^='blocktypeoption_']").change( function(event) {
		
		id = $(this).attr('id').substring($(this).attr('id').indexOf("_")+1);
		cid = id.substring(0, id.indexOf("x"));
		pos = id.substring(id.indexOf("x")+1);
		
		//alert(id + $(this).val());
		$.post('/trms-admin/ajax/blocktypeset.php', {action: "setblocktypeoption", cid: cid, position: pos, blocktypeoptionid: $(this).val() });
	});
	
	
	
	/** 
	 *	This in used to create an dialogbox when clicking add image button
	 */
	
	var $dialog = $('<div id=\"imageAdmin\"></div>')
			.dialog({
				autoOpen: false,
				title: 'Edit image',
				width: 450,
				height: 600
			});

	$('.contentimage, .contentimagebox').click(function(){
		$dialog.load('/trms-admin/ajax/content_image_select.php', {cid: $("#cid").val(), imgpos: $(this).attr('id').substring(6)}).dialog('open').dialog('option','title', 'Edit image for position ' + $(this).attr('id').substring(6));
	});

	$('#templatedescription,#templatedescr').click(function(){
		$dialog.load('/trms-admin/ajax/templatedescription.php', {tmplid: $("#tmplid").val()}).dialog('open').dialog('option','title', 'Templatedescription');
	});

	// end
	
	$('#list_head').hide();
	$('#list_body').hide();
	// Fill up the box #nodeTree according to userchoice
	
	$('select#rootNodes').change(function(){

		$('#list_head').fadeOut();
		$('#list_body').fadeOut();

		 $.getJSON('/trms-admin/ajax/content_nodeoption.php', {nid: $(this).val()},function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		 $('select#nodeTree').html(options);
		})
	});

	// Close the area before change

	$('select#nodeTree').change(function(){
		$('#list_head').fadeOut();
		$('#list_body').fadeOut();
	})
	
	// Check whats filled up and display 

	$('#displayTree').click(function(){

		if($("#nodeTree option:selected").val() == 0){
			if($("#rootNodes option:selected").val() == 0)alert("Please select something!");
			else{
				$('#list_head').fadeIn('100', function (){
					document.getElementById('nid').value = $("#rootNodes option:selected").val();
				});
				$('#list_body').fadeIn('100', function (){
					$(this).load('/trms-admin/ajax/content_nodetree.php', {nid: $("#rootNodes option:selected").val() } );
				});
		}
		}else{
			$('#list_head').fadeIn('100', function(){
				document.getElementById('nid').value = $("#nodeTree option:selected").val();
			});

			$('#list_body').fadeIn('100', function (){
				$(this).load('/trms-admin/ajax/content_nodetree.php', {nid: $("#nodeTree option:selected").val() } );
			});
		}
	});

	$("#openforms").click(function(){

		$("#content_forms").load('/trms-admin/ajax/content_forms.php', {action: "default", cid: $("#cid").val() } );
	});

	$("#formid").live("change", function(){
		$("#content_forms").load('/trms-admin/ajax/content_forms.php', {action: "addform", cid: $("#cid").val(), formid: $(this).val()} );
	});
	
	$("#displaymap").live("click", function(){
		
		 initMap();
	
		$("#overlay").fadeIn('fast', function(){
				$(this).css("filter","alpha(opacity=30)");
		});
		
		$("#mapcontainer").show('fast', function(){
			$("#map").show();
			//$(this).load("/trms-content/ajax/standard_form.php", values);
		});
	});
	
	$("#closeoverlay").live("click", function(){
	
		$("#overlay").fadeOut('fast');
		$("#mapcontainer").fadeOut('fast');
	
	});
	
});

var map;
var initialized = 0;
function initMap(){
	
	if(initialized < 1){
		map = new google.maps.Map(document.getElementById("mapcanvas"), {
		center: {lat:  26.21351, lng: -15.68921},
		zoom: 3
		});
		initialized = 1;
		
		google.maps.event.addListener(map, "click", function(event) {
    		var lat = event.latLng.lat();
   			var lng = event.latLng.lng();
    		$("#latitude").val(lat);
    		$("#longitude").val(lng);
		});
		
		// if theese values are set - Place the marker on the map
		if($("#latitude").val() != "" && $("#longitude").val() != ""){
		
			var marker = new google.maps.Marker({
      		position: { lat: parseFloat($("#latitude").val()), lng: parseFloat($("#longitude").val())},
      		map: map
      		});
      	}
	}
}

function setSelectedTemplate(id, name){
	document.getElementById('tmplid').value = id;
	document.getElementById('selectedtemplate').innerHTML = name;
}
