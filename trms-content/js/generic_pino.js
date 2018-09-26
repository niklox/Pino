$(function(){
	var $dialog = $('<div id="textAdmin" style="z-index:1000;"></div>')
	.dialog({ autoOpen: false, title: 'Edit text', width: 600, height: 600});
	
	
	//byt ut live till on
	$("div[id^='contentTexts'],li[id^='contentTexts'],h1[id^='contentTexts'],div[id^='formItemTexts'],div[id^='imageTexts']").live('click', function(event) {
		
		//alert("hellp");
		//$dialog( "option", "position", { my: "center", at: "center", of: window } );
		
		$dialog.load('/trms-content/ajax/contenttext_edit_pino.php', {cid: $("#cid").val(), textid: $(this).attr("id") }).dialog('open').dialog('option','title', 'Edit text id: ' + $(this).attr("id")).dialog("option", "position", { my: "left+3 bottom-0", of: event });
	
	});
});