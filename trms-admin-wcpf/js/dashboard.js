// used for /trms-admin/index.php DASHBOARD
$(function(){
	
	$("a[id^='viewissue_']").live("click" , function(event) {
		issue = event.target.id.substring(event.target.id.indexOf("_")+1 );
		$("#highlighttask").load("/trms-admin/ajax/project_focus.php", {action: "viewissue", cid: issue});
	});

	$("#viewlist").live("click", function(){
		$("#highlighttask").load("/trms-admin/ajax/project_focus.php", {action: "default"});
	});

	$("input[id^='savetext_']").live("click", function(e){
		textid = e.target.id.substring(e.target.id.indexOf("_")+1 );

		//text = $("#issuecontent").html();

		text = $('textarea#issuecontent').val();

		issue= $("#cid").val();
		
		//alert(textid + " " + text + " " + issue);

		$("#highlighttask").load("/trms-admin/ajax/project_focus.php", {action: "savetext", mtextid: textid, textcontent: text, cid: issue});
	});


});