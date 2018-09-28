// used for /trms-admin/ajax/content_image_links.php
$(function(){

	$.getJSON('/trms-admin/ajax/content_json_nodelinks.php', {nid: 21},function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		 $('select#webnodetree').html(options);
	 });

	 $('select#webnodetree').change(function(){
		var nodeid = $(this).val();
		$("#imglinkurl").val($(this).val());
	});

	$('#savelink').click(function(){
		linkurl = $('#imglinkurl').val();
		cid = $('#cid').val();
		pos = $('#imgpos').val();
		target = $('#imglinktarget').val();
		
		if(linkurl == "")
			alert("Insert link");
		else{
			$('#imglinkadmin').load('/trms-admin/ajax/content_image_link.php',{action:"savelink", cid: cid, imgpos: pos, imglink: linkurl, target: target});	
		}
		
	});

	$("#deletelink").live("click", function(){
		cid = $('#cid').val();
		pos = $('#imgpos').val();
		$('#imglinkadmin').load('/trms-admin/ajax/content_image_link.php',{action:"deletelink", cid: cid, imgpos: pos});
	});

	$('#imgselect').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_select.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});
	$('#imgupload').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_upload.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});

});
