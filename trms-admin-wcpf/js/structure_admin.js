$(function(){
	
	$("img[id^='foldout_']").live("click", function(event) {

		id = event.target.id
		ul = id.substring(id.indexOf("_") +1 ,id.indexOf("x"));
		level  = id.substring(id.indexOf("x") +1 );
		level++;
		
		if( $(this).attr("src") == "/trms-admin/images/arrow_13.gif" )
			$(this).attr({src: "/trms-admin/images/arrow_14.gif"});
		else
			$(this).attr({src: "/trms-admin/images/arrow_13.gif"});

		$("#nodelist_" + ul + " .lev_" + level + ",#nodelist_" + ul + " .cont_" + level).toggle("fast");
	});

})

