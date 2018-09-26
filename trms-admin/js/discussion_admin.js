$(function(){
	
	$(".comments").live('click', function(){
		var discussionid = $(this).attr("value");
		$("#box_body_top").fadeIn('slow', function(){
			$(this).load('/trms-admin/ajax/discussion_admin.php', {action: "viewcomment", did: discussionid});
		});
	});

	$("input[id^='deletecomment_']").live('click',function(){
		if(confirm("Delete this comment?")){
			$('tr').remove('#commentrow_' + $(this).attr("id").substring(14) );
			$("#box_body_top").fadeOut('slow');
			$.post('/trms-admin/ajax/discussion_admin.php', {action: "deletecomment", did: $(this).attr("id").substring(14) });
		}
	});

	$("input[id^='approvecomment_']").live('click',function(){
		//alert($(this).attr("id").substring(15));
		
		if(confirm("Approve this comment?")){
			$('tr').remove('#commentrow_' + $(this).attr("id").substring(15) );
			$("#box_body_top").fadeOut('slow');
			$.post('/trms-admin/ajax/discussion_admin.php', {action: "approvecomment", did: $(this).attr("id").substring(15) });
		}
	});



});
