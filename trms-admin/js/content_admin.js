$(function(){
	

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
});

function setSelectedTemplate(id, name){
	document.getElementById('tmplid').value = id;
	document.getElementById('selectedtemplate').innerHTML = name;
}

/*
 *	DRAG and DROP functions to move order of the products in admin
 */

function dragstart_handler(ev) {
 	// Add the target element's id to the data transfer object
 	ev.dataTransfer.setData("text/plain", ev.target.id);
}

function drop_handler(ev) {
 	ev.preventDefault();
 	
 	// Get the parent container of all products
 	var parent = document.getElementById(ev.target.id).parentNode;
 	var child_nodes = parent.childNodes;
 	var targ = document.getElementById(ev.target.id);
 
 	// Get the id of the object we just dropped and add it to the DOM
 	var data = ev.dataTransfer.getData("text/plain");
 	var src = document.getElementById(data);
 	parent.insertBefore(src, targ);
 	
 	// Loop through the child_nodes to set all new positions after the drop
 	// Create an array of JSON objects containing productid (cid), nodeid (pid) and the 
 	// new position Send the JSON via post to the server for update in db.
 
 	var termosnode = 72;
 	var jsonstr = '[';
 	var posbox = "";
 	for (i = 0; i < child_nodes.length; i++) {
 		jsonstr += '{"cid":"'+child_nodes[i].id.substring(8,child_nodes[i].id.length)+'",';
 		
 		// Get the productpos container, set the new position, add it to the jsonobject 
		posbox = child_nodes[i].children;
 		for(j=0;j<posbox.length;j++){
 			if(posbox[j].className == "productpos"){
 			posbox[j].innerHTML = i + 1;
 			jsonstr += '"position":"'+ (i + 1) +'",';
 			}
 		}
 		jsonstr += '"pid":"'+termosnode+'"}';
 		if(i < child_nodes.length-1) jsonstr += ',';
 	}
 	jsonstr += ']';
 	
 	//console.log(jsonstr);
 	sendData(jsonstr);
}

function dragover_handler(ev) {
 	ev.preventDefault();
 	// Set the dropEffect to move
 	ev.dataTransfer.dropEffect = "move"
}

function sendData(data) {
  var XHR = new XMLHttpRequest();
  
  // Define what happens in case of error
  XHR.addEventListener('error', function(event) {
    alert('Oops! Something went wrong.');
  });

  // Set up our request
  XHR.open('POST', '/trms-admin/ajax/content_positions.php', true);

  // Send our FormData as a JSON object; HTTP headers are set automatically
  XHR.send(data);
}


