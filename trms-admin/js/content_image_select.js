// used for /trms-admin/ajax/content_image_select.php
$(function(){

	
	$('#imgupload').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_upload.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});
	$('#imglink').click(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_link.php', {cid: $('#cid').val(), imgpos: $('#imgpos').val() })
	});

	$('select#imgcatid').change(function(){ 
		$('#imageAdmin').load('/trms-admin/ajax/content_image_select.php', {cid: $('#cid').val(), imgcatid: $(this).val(), imgpos: $('#imgpos').val() })
	});

	$('select#images').change(function(){
		$('#imgdeletebutton').hide();
		$('#selectedimg').load('/trms-admin/ajax/content_images_get.php', {imghandle: $(this).val()}); 
		$('#imgselectbutton').show();
		$('#selectedimg').show();
		$('#imghandle').attr('value', $(this).val());
	});

	$('#imgselectbutton').click(function(){
		$.post('/trms-admin/ajax/content_image_select.php',{action: 'addImage', cid: $('#cid').val(),imghandle: $('#imghandle').val(),imgpos: $('#imgpos').val()});
		$('#image_' + $('#imgpos').val()).load('/trms-admin/ajax/content_images_get.php', {imghandle: $('#imghandle').val()});
		$('#imgdeletebutton').show();
		$('#selectedimg').show();
		$(this).hide();
	});

	$('#imgdeletebutton').click(function(){
		$.post('/trms-admin/ajax/content_image_select.php',{action: 'removeImage', cid: $('#cid').val(),imghandle: $('#imghandle').val(),imgpos: $('#imgpos').val()});
		$('#image_' + $('#imgpos').val()).html('no image');
		$(this).hide();
		$('#selectedimg').hide();
	});

	if($('#imghandle').val() != "") $('#imgdeletebutton').show();

});

