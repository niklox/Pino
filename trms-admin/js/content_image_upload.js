// used for /trms-admin/ajax/content_image_upload.php
$(function(){

	$('#imgselect').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_select.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});
	
	$('#imglink').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_link.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});

	$('#contentImgUpload').ajaxForm({
		target:        '#output',
		beforeSubmit: showRequest,
        success:      showResponse
    });

	function showRequest(formData, jqForm, options) { 
		
		var msgtext = "Formdata is missing for:\n";
		
		if(formData[0].value == "") // imghandle
		msgtext += "Image name,";
		if(formData[1].value == "") // imgfile
		msgtext += "Upload file,";
		if(formData[4].value == 0) // imgcategory
		msgtext += "Image category,";
		if(formData[5].value == "") // img alt text
		msgtext += "Image ALT text,";
		
		if (msgtext.length > 25){
			alert(msgtext);
			return false; 
		}else return true;

	}
	 
	function showResponse(responseText, statusText, xhr, $form)  { 
		
		$('#output').html(responseText); 
		if($('#status').attr('value') == "ok"){
			$('#image_' + $('#imgpos').val()).load('/trms-admin/ajax/content_images_get.php', {imghandle: $('#imghandle').val()});
		}
		$form.clearForm();
	} 

});

