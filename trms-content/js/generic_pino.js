$(function(){
	var $dialog = $('<div id="textAdmin" style="z-index:1000; background:#EFEFEF"></div>')
	.dialog({ autoOpen: false, title: 'Edit text', width: 600, height: 550});
	
	$(document).on('click', "div[id^='contentTexts'],li[id^='contentTexts'],h1[id^='contentTexts'],div[id^='formItemTexts'],div[id^='imageTexts']", function(event) {
		$dialog.load('/trms-content/ajax/contenttext_edit_pino.php', {cid: $("#cid").val(), textid: $(this).attr("id") }).dialog('open').dialog('option','title', 'Edit text id: ' + $(this).attr("id")).dialog("option", "position", { my: "left+3 bottom-0", of: event });
	});
});