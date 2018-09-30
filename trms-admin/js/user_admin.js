$(function(){
	
	$("#usergroupid").change( function(){
		location.href = "/trms-admin/users.php?gid=" + $(this).val();
	});

});
